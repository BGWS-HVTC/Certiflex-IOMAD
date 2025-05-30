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
 * Certiflex plugin version details.
 *
 * @package   local_certiflex
 * @author    Bartosz, Solin
 * @copyright 2023 Solin
 */
defined('MOODLE_INTERNAL') || die;

// Certiflex settings.
$settings = new admin_settingpage('certiflex_header', new lang_string('certiflex_header', 'local_certiflex'));

if ($ADMIN->fulltree) {
    // Certiflex settings header.
    $settings->add(new admin_setting_heading(
        'local_certiflex/header1',
        new lang_string('certiflex_header1', 'local_certiflex'),
        ''
    ));

    // Endpoint URL.
    $settings->add(new admin_setting_configcheckbox(
        'local_certiflex/send_completions',
        new lang_string('send_completions', 'local_certiflex'),
        $CFG->SMARTS_ENDPOINT,
      1
    ));

    // API Key.
    $settings->add(new admin_setting_configpasswordunmask(
        'local_certiflex/api_key',
        new lang_string('api_key', 'local_certiflex'),
        new lang_string('api_key_description', 'local_certiflex'),
        ''
    ));

    // Retry count.
    $settings->add(new admin_setting_configtext(
        'local_certiflex/retry_count',
        new lang_string('retry_count', 'local_certiflex'),
        new lang_string('retry_count_description', 'local_certiflex'),
        '5'
    ));
}

$ADMIN->add('localplugins', $settings);

$ADMIN->add('reports', new admin_externalpage(
    'certiflexlog',
    get_string('reportname', 'local_certiflex'),
    new moodle_url('/local/certiflex/report.php'),
));
