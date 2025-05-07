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

use core_customfield\category_controller;
use core_customfield\field_controller;
use core_customfield\api;

/*
 * Certiflex plugin library.
 *
 * @package   local_certiflex
 * @author    Bartosz, Solin
 * @copyright 2023 Solin
 */

class lib {
    /**
     * Create custom course fields category
     *
     * @param string $name
     * @param string $description
     * @param string $component
     * @param string $area
     * @param int $itemid
     *
     * @return int
     */
    public static function create_category(string $name, string $description = "",
        string $component = 'core_course', string $area = 'course', int $itemid = 0): int {
        global $DB;

        $record = $DB->get_record('customfield_category', ['name' => $name]);
        if (empty($record)) {
            $category = (object)[
                'name' => $name,
                'description' => $description,
                'component' => $component,
                'area' => $area,
                'itemid' => $itemid,
                'timecreated' => time(),
                'timemodified' => time(),
            ];

            $category = category_controller::create(0, $category);
            api::save_category($category);
            return $category->get('id');
        } else {
            return $record->id;
        }
    }

    /**
     * Create custom course field.
     *
     * @param int $categoryid
     * @param string $shortname
     * @param string $name
     * @param string $description
     * @param string $type text | file | menu | checkbox
     * @param string $defaultvalue
     * @param string $visibility
     * @param bool $required
     * @param bool $uniquevalues
     * @param bool $locked
     * @param array $configdata
     * @param string $defaultvalueformat
     *
     * @return int
     */
    public static function create_course_field(
        int $categoryid,
        string $shortname,
        string $name = '',
        string $description = '',
        string $type = 'text',
        string $defaultvalue = '',
        string $visibility = "2",
        bool $required = false,
        bool $uniquevalues = false,
        bool $locked = false,
        array $configdata = [],
        $defaultvalueformat = "1"
    ): int {
        global $DB;

        $category = category_controller::create($categoryid);

        // Check if field exists already.
        $record = $DB->get_record('customfield_field', ['shortname' => $shortname, 'categoryid' => $categoryid]);
        if (empty($record)) {
            $sortorder = $DB->get_record_sql('SELECT sortorder FROM {customfield_field} order by sortorder desc limit 1');

            $record = [];
            $record['shortname'] = $shortname;
            $record['name'] = $name ? $name : $shortname;
            $record['type'] = $type;
            $record['description'] = $description;
            $record['descriptionformat'] = "1";
            $record['sortorder'] = $sortorder ? $sortorder->sortorder + 1 : 1;
            $record['timecreated'] = time();
            $record['timemodified'] = time();

            $configdata += [
                "required" => $required ? "1" : "0",
                "uniquevalues" => $uniquevalues ? "1" : "0",
                "locked" => $locked ? "1" : "0",
                "visibility" => $visibility,
                "defaultvalue" => $defaultvalue,
                "defaultvalueformat" => $defaultvalueformat,
            ];

            $configdata = json_encode($configdata);
            $record['configdata'] = $configdata;
            $record = (object) $record;

            $field = field_controller::create(0, (object)['type' => $record->type], $category);
            $handler = $category->get_handler();
            $handler->save_field_configuration($field, $record);

            return $field->get('id');
        } else {
            // Return existing field id.
            return $record->id;
        }
    }

    public static function get_valid_dodid(int $userid): string {
        global $DB;

        $sql = "
            SELECT username
            FROM {user} u
            WHERE u.id = ?
        ";

        $dodid = $DB->get_field_sql($sql, [$userid]);
        if (strlen($dodid) == 10 && preg_match('/^[0-9]+$/', $dodid)) {
            return $dodid;
        }

        return '';
    }

    public static function get_skills_for_activity(int $cmid): array {
        global $DB;

        $sql = "
            SELECT c.idnumber
            FROM {competency_modulecomp} u
            LEFT JOIN {competency} c ON c.id = u.competencyid
            WHERE u.cmid = ?
        ";

        $list = $DB->get_records_sql($sql, [$cmid]);
        return array_filter(array_keys($list), 'is_int');
    }

    public static function get_skills_for_course(int $courseid): array {
        global $DB;

        $sql = "
            SELECT c.idnumber
            FROM {competency_coursecomp} u
            LEFT JOIN {competency} c ON c.id = u.competencyid
            WHERE u.courseid = ?
        ";

        $list = $DB->get_records_sql($sql, [$courseid]);
        return array_filter(array_keys($list), 'is_int');
    }
}
