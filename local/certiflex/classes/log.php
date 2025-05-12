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

/*
 * Certiflex log and send email.
 *
 * @package   local_certiflex
 * @author    Bartosz, Solin
 * @copyright 2023 Solin
 */

class log {

    const STATUS_SUCCESS = 1;
    const STATUS_FAIL = 0;

    public static function get_statuses() {
        return [
            self::STATUS_SUCCESS => get_string('status_success', 'local_certiflex'),
            self::STATUS_FAIL => get_string('status_fail', 'local_certiflex'),
        ];
    }

    public static function save_log($dodid, $skillid, $completiondate, $status, $description = '') {
        global $DB;

        $log = (object)[
            'dodid' => $dodid,
            'skillid' => $skillid,
            'completiondate' => $completiondate,
            'status' => $status,
            'description' => $description,
            'timecreated' => time(),
        ];

        return $DB->insert_record('certiflex_log', $log);
    }
}
