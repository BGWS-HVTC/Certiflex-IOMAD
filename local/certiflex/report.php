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
 * Certiflex report page.
 *
 * @package   local_certiflex
 * @author    Bartosz, Solin
 * @copyright 2023 Solin
 */

use core_reportbuilder\system_report_factory;
use local_certiflex\logreport;
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('certiflexlog', '', null, '', ['pagelayout' => 'report']);

echo /** @var core_renderer $OUTPUT */ $OUTPUT->header();

echo $OUTPUT->heading_with_help(get_string('reportname', 'local_certiflex'), 'reportname', 'local_certiflex');

$report = system_report_factory::create(logreport::class, context_system::instance());
echo $report->output();

echo $OUTPUT->footer();
