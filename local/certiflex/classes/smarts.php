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

use curl;
use local_certiflex\log;
use local_certiflex\queue;

/*
 * Certiflex plugin Smarts integration library.
 *
 * @package   local_certiflex
 * @author    Bartosz, Solin
 * @copyright 2023 Solin
 */

class smarts {

    public static function send(): bool {
        global $CFG;
        $config = get_config('local_certiflex');

        if($config->send_completions) {
            $response = (object)['completions' => []];

            $queue = queue::get_queue();

            if ($queue) {
                $data = [];
                foreach ($queue as $id => $completiondata) {
                    $data[] = [
                        'dod_id' => (int)$completiondata->dodid,
                        'skill_id' => (int)$completiondata->skillid,
                        'completion_time' => date(DATE_ATOM, $completiondata->completiondate),
                    ];
                }

                $body = json_encode($data);

                // Send request to the Smarts API.
                if ($config->api_key && $CFG->SMARTS_ENDPOINT && $config->send_completions) {
                    $url = $CFG->SMARTS_ENDPOINT . '/External/Completions';
                    $curl = new curl();
                    $curl->setHeader("X-API-KEY: " . $config->api_key);
                    $response = $curl->post($url, $body);
                    $response = json_decode($response);
                }
            }

            foreach ($queue as $id => $completiondata) {
                $status = log::STATUS_FAIL;
                foreach ($response->completions as $res) {
                    if ($res->dod_id == $completiondata->dodid && $res->skill_id == $completiondata->skillid) {
                        $message = $res->message;
                        if (!empty($res->full_name)) {
                            $message .= ' / ' . $res->full_name;
                        }
                        if (!empty($res->skill_display_name)) {
                            $message .= ' / ' . $res->skill_display_name;
                        }

                        log::save_log(
                            $completiondata->dodid,
                            $completiondata->skillid,
                            $completiondata->completiondate,
                            $res->successful ? log::STATUS_SUCCESS : log::STATUS_FAIL,
                            $message
                        );

                        $status = $res->successful ? log::STATUS_SUCCESS : log::STATUS_FAIL;
                    }
                }

                queue::update($id, $status);
            }
        }

        return true;
    }
}
