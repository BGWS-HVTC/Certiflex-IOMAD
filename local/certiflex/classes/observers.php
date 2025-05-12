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

use local_certiflex\queue;
use local_certiflex\lib;

/**
 * Certiflex plugin event ebservers class.
 *
 * @package   local_certiflex
 * @author    Bartosz, Solin
 * @copyright 2023 Solin
 */
class local_certiflex_observers {
    /**
     * A course has been completed.
     *
     * @param \core\event\course_completed $event The event.
     * @return void
     */
    public static function course_completed(\core\event\course_completed $event) {
        $dodid = lib::get_valid_dodid($event->relateduserid);

        if ($dodid) {
            $skills = lib::get_skills_for_course($event->courseid);

            foreach ($skills as $skillid) {
                queue::add($dodid, $skillid, $event->timecreated);
            }
        }
    }

    /**
     * An activity has been completed.
     *
     * @param \core\event\course_module_completion_updated $event The event.
     * @return void
     */
    public static function course_module_completion_updated(\core\event\course_module_completion_updated $event) {
        $dodid = lib::get_valid_dodid($event->relateduserid);

        if ($dodid) {
            $eventdata = $event->get_record_snapshot('course_modules_completion', $event->objectid);

            if ($eventdata->completionstate == COMPLETION_COMPLETE || $eventdata->completionstate == COMPLETION_COMPLETE_PASS) {
                $skills = lib::get_skills_for_activity($eventdata->coursemoduleid);
                foreach ($skills as $skillid) {
                    queue::add($dodid, $skillid, $event->timecreated);
                }
            }
        }
    }
}
