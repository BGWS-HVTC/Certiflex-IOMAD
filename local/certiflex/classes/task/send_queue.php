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

/**
 * Certiflex plugin cron task.
 *
 * @package   local_certiflex
 * @author    Bartosz, Solin
 * @copyright 2023 Solin
 */

namespace local_certiflex\task;

use local_certiflex\queue;
use local_certiflex\smarts;

/**
 * Certiflex plugin cron task.
 *
 * @package   local_certiflex
 * @author    Bartosz, Solin
 * @copyright 2023 Solin
 */
class send_queue extends \core\task\scheduled_task {
    /**
     * Returns the name of this task.
     */
    public function get_name() {
        // Shown in admin screens.
        return get_string('sendqueuetask', 'local_certiflex');
    }

    /**
     * Execute task.
     */
    public function execute() {
        smarts::send();
    }
}
