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

namespace local_certiflex\entities;

use lang_string;
use core_reportbuilder\local\entities\base;
use core_reportbuilder\local\helpers\format;
use core_reportbuilder\local\report\{column, filter};
use core_reportbuilder\local\filters\{date, number, text, boolean_select};

/**
 * Certiflex log entity class implementation.
 *
 * @package   local_certiflex
 * @author    Bartosz, Solin
 * @copyright 2023 Solin
 */
class log extends base {

    /**
     * Database tables that this entity uses
     *
     * To ensure backwards compatibility, return those defined by {@see get_default_table_aliases}
     *
     * @return string[]
     */
    protected function get_default_tables(): array {
        return array_keys($this->get_default_table_aliases());
    }

    /**
     * Database tables that this entity uses and their default aliases
     *
     * @return array
     */
    protected function get_default_table_aliases(): array {
        return ['certiflex_log' => 'cl'];
    }

    /**
     * The default title for this entity
     *
     * @return lang_string
     */
    protected function get_default_entity_title(): lang_string {
        return new lang_string('reportname', 'local_certiflex');
    }

    /**
     * Initialize the entity
     *
     * @return base
     */
    public function initialise(): base {
        $columns = $this->get_all_columns();
        foreach ($columns as $column) {
            $this->add_column($column);
        }

        $filters = $this->get_all_filters();
        foreach ($filters as $filter) {
            $this
                ->add_filter($filter)
                ->add_condition($filter);
        }

        return $this;
    }

    /**
     * Returns list of all available columns
     *
     * @return column[]
     */
    protected function get_all_columns(): array {
        global $DB;

        $table = $this->get_table_alias('certiflex_log');

        // Dod ID.
        $columns[] = (new column(
            'dodid',
            new lang_string('dodid', 'local_certiflex'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_TEXT)
            ->add_fields("{$table}.dodid")
            ->set_is_sortable(true, [$DB->sql_order_by_text("{$table}.dodid")]);

        // Dod ID.
        $columns[] = (new column(
            'skillid',
            new lang_string('skillid', 'local_certiflex'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_TEXT)
            ->add_fields("{$table}.skillid")
            ->set_is_sortable(true, [$DB->sql_order_by_text("{$table}.skillid")]);

        // Complation date.
        $columns[] = (new column(
            'completiondate',
            new lang_string('completiondate', 'local_certiflex'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_TIMESTAMP)
            ->add_fields("{$table}.completiondate")
            ->set_is_sortable(true, [$DB->sql_order_by_text("{$table}.completiondate")])
            ->add_callback([format::class, 'userdate']);;

        // Status.
        $columns[] = (new column(
            'status',
            new lang_string('successful', 'local_certiflex'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_TEXT)
            ->add_fields("{$table}.status")
            ->set_is_sortable(true, [$DB->sql_order_by_text("{$table}.status")])
            ->add_callback(static function(string $status): lang_string {
                return new lang_string($status ? 'yes' : 'no', 'local_certiflex');
            });

        // Description.
        $columns[] = (new column(
            'description',
            new lang_string('description', 'local_certiflex'),
            $this->get_entity_name()
        ))
            ->add_joins($this->get_joins())
            ->set_type(column::TYPE_TEXT)
            ->add_fields("{$table}.description")
            ->set_is_sortable(true, [$DB->sql_order_by_text("{$table}.description")]);

        return $columns;
    }

    /**
     * Return list of all available filters
     *
     * @return filter[]
     */
    protected function get_all_filters(): array {
        $table = $this->get_table_alias('certiflex_log');

        // Dod id.
        $filters[] = (new filter(
            number::class,
            'dodid',
            new lang_string('dodid', 'local_certiflex'),
            $this->get_entity_name(),
            "{$table}.dodid"
        ))
            ->add_joins($this->get_joins())
            ->set_limited_operators([
                number::ANY_VALUE,
                number::EQUAL_TO,
                number::EQUAL_OR_LESS_THAN,
                number::EQUAL_OR_GREATER_THAN,
            ]);

        // Skill id.
        $filters[] = (new filter(
            number::class,
            'skillid',
            new lang_string('skillid', 'local_certiflex'),
            $this->get_entity_name(),
            "{$table}.skillid"
        ))
            ->add_joins($this->get_joins())
            ->set_limited_operators([
                number::ANY_VALUE,
                number::EQUAL_OR_LESS_THAN,
                number::EQUAL_OR_GREATER_THAN,
            ]);

        // Completion date.
        $filters[] = (new filter(
            date::class,
            'completiondate',
            new lang_string('completiondate', 'local_certiflex'),
            $this->get_entity_name(),
            "{$table}.completiondate"
        ))
            ->add_joins($this->get_joins())
            ->set_limited_operators([
                date::DATE_ANY,
                date::DATE_RANGE,
                date::DATE_PREVIOUS,
                date::DATE_CURRENT,
            ]);

        // Status.
        $filters[] = (new filter(
            boolean_select::class,
            'status',
            new lang_string('successful', 'local_certiflex'),
            $this->get_entity_name(),
            "{$table}.status"
        ))
            ->add_joins($this->get_joins());

        // Description.
        $filters[] = (new filter(
            text::class,
            'description',
            new lang_string('description', 'local_certiflex'),
            $this->get_entity_name(),
            "{$table}.description"
        ))
            ->add_joins($this->get_joins());

        return $filters;
    }
}
