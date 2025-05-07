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

declare(strict_types=1);

namespace local_certiflex;

use context_system;
use core_reportbuilder\system_report;
use core_reportbuilder\local\entities\user;
use core_reportbuilder\local\helpers\database;
use local_certiflex\entities\log;

/**
 * Certiflex report page.
 *
 * @package   local_certiflex
 * @author    Bartosz, Solin
 * @copyright 2023 Solin
 */
class logreport extends system_report {

    /**
     * Initialise report
     */
    protected function initialise(): void {
        global $DB;

        // Set our main table entity.
        $logentity = new log();
        $logtable = $logentity->get_table_alias('certiflex_log');

        $this->set_main_table('certiflex_log', $logtable);
        $this->add_entity($logentity);

        $paramplugin = database::generate_param_name();
        $select = $DB->sql_cast_to_char("{$logtable}.dodid")." != ''";
        $this->add_base_condition_sql($select, []);

        // Join the user entity.
        $userentity = new user();
        $usertable = $userentity->get_table_alias('user');
        $this->add_entity($userentity->add_join("LEFT JOIN {user} {$usertable} ON  {$usertable}.username = "
            .$DB->sql_cast_to_char("{$logtable}.dodid")));

        $this->add_columns();
        $this->add_filters();

        $this->set_downloadable(true, get_string('pluginname', 'local_certiflex'));
    }

    /**
     * Validates access to view this report
     *
     * @return bool
     */
    protected function can_view(): bool {
        return has_capability('certiflex/log:view', context_system::instance());
    }

    /**
     * Add report columns
     */
    protected function add_columns(): void {
        $this->add_columns_from_entities([
            'user:fullnamewithlink',
            'log:dodid',
            'log:skillid',
            'log:completiondate',
            'log:status',
            'log:description',
        ]);

        // Default sorting.
        $this->set_initial_sort_column('log:dodid', SORT_DESC);
    }

    /**
     * Add report filters
     */
    protected function add_filters(): void {
        $this->add_filters_from_entities([
            'user:fullname',
            'log:dodid',
            'log:skillid',
            'log:completiondate',
            'log:status',
            'log:description',
        ]);
    }
}
