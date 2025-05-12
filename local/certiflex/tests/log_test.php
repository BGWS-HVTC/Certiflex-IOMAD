<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_certiflex;

use completion_completion;
use core_competency\api;

use local_certiflex\{log, smart};

class log_test extends \advanced_testcase {
    public function test_completion() {
        global $DB;
        $time = time();

        $this->resetAfterTest(true);

        $this->setAdminUser();

        $user = $this->getDataGenerator()->create_user(['username' => '1234567890']);

        $skillid = 100;
        $course = $this->getDataGenerator()->create_course(['name' => 'Course', 'customfield_skillid' => $skillid]);
        $page = $this->getDataGenerator()->create_module('page', ['course' => $course->id]);

        $this->getDataGenerator()->enrol_user($user->id, $course->id);

        // Now, mark the course as completed.
        $ccompletion = new completion_completion(['course' => $course->id, 'userid' => $user->id]);
        $ccompletion->mark_complete();

        $queue = queue::get_queue();
        $this->assertCount(1, $queue);

        $data = end($queue);
        $this->assertEquals($user->username, $data->dodid);
        $this->assertEquals($skillid, $data->skillid);
        $this->assertEquals(log::STATUS_FAIL, $data->status);
        $this->assertEquals(0, $data->attempt);

        $dg = $this->getDataGenerator();
        $lpg = $dg->get_plugin_generator('core_competency');
        $f1 = $lpg->create_framework();
        $c1 = $lpg->create_competency(['idnumber' => 200, 'competencyframeworkid' => $f1->get('id')]);
        $c2 = $lpg->create_competency(['idnumber' => 300, 'competencyframeworkid' => $f1->get('id')]);

        $cc = api::add_competency_to_course($course->id, $c1->get('id'));
        $cc = api::add_competency_to_course($course->id, $c2->get('id'));

        $cm = get_coursemodule_from_instance('page', $page->id);
        $ccm = api::add_competency_to_course_module($cm, $c1->get('id'));
        $ccm = api::add_competency_to_course_module($cm, $c2->get('id'));

        // Mark activity complete.
        $completion = new \completion_info($course);
        $current = $completion->get_data($cm, false, $user->id);
        $current->completionstate = COMPLETION_COMPLETE;
        $current->timemodified = $time;
        $completion->internal_set_data($cm, $current);

        $queue = queue::get_queue();
        $this->assertCount(3, $queue);

        $data = end($queue);
        $this->assertEquals($user->username, $data->dodid);
        $this->assertEquals(300, $data->skillid);
        $this->assertEquals(log::STATUS_FAIL, $data->status);
        $this->assertEquals(0, $data->attempt);

        // Make all possible tries to send data to the API.
        $config = get_config('local_certiflex');
        for ($i = 0; $i < $config->retry_count + 1; $i++) {
            smarts::send();
        }

        // Logs are empty because the API connection is not configured.
        $logs = $DB->get_records('certiflex_log');
        $this->assertCount(0, $logs);

        // After reaching the resending limit the queue should be empty.
        $queue = queue::get_queue();
        $this->assertCount(0, $queue);

        // But in database should stay information about the attemptes.
        $queue = $DB->get_records('certiflex_queue');
        $this->assertCount(3, $queue);

        $data = end($queue);
        $this->assertEquals($config->retry_count, $data->attempt);
    }
}
