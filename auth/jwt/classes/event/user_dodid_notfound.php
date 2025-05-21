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
 * The user_dodid_notfound event.
 *
 * @package    auth_jwt
 * @copyright  2025 BG Workforce
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace auth_jwt\event;
defined('MOODLE_INTERNAL') || die();
/**
 * The user_dodid_notfound event class.
 *
 * @property-read array $other {
 *      Extra information about event.
 *
 *      Event is logged if a user is not found with the dodid on the JWY token dodcert.
 * }
 *
 * @since     Moodle 2024100704
 * @copyright 2025 BG Workforce
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/
class user_dodid_notfound extends \core\event\base
{
    protected function init()
    {
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->context = \context_system::instance();
    }

    public static function get_name() {
        return get_string('eventuserdodidnotfound', 'auth_jwt');
    }

    public function get_description() {
        return "The user with dodid {$this->other['dodid']} could not be found.";
    }

    public function get_url()
    {
        return null;
    }
}