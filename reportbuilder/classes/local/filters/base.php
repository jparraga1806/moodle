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

namespace core_reportbuilder\local\filters;

use MoodleQuickForm;
use core_reportbuilder\local\report\filter;

/**
 * Base class for all report filters
 *
 * Filters provide a form for collecting user input, and then return appropriate SQL fragments based on these values
 *
 * @package     core_reportbuilder
 * @copyright   2021 Paul Holden <paulh@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class base {

    /** @var filter $filter */
    protected $filter;

    /** @var string $name */
    protected $name;

    /**
     * Do not allow the constructor to be called directly or overridden
     *
     * @param filter $filter
     */
    private function __construct(filter $filter) {
        $this->filter = $filter;
        $this->name = $filter->get_unique_identifier();
    }

    /**
     * Creates an instance of a filter type, based on supplied report filter instance
     *
     * The report filter instance is used by reports/entities to define what should be filtered against, e.g. a SQL fragment
     *
     * @param filter $filter The report filter instance
     * @return static
     */
    final public static function create(filter $filter): self {
        $filterclass = $filter->get_filter_class();

        return new $filterclass($filter);
    }

    /**
     * Returns the filter header
     *
     * @return string
     */
    final public function get_header(): string {
        return $this->filter->get_header();
    }

    /**
     * Adds filter-specific form elements
     *
     * @param MoodleQuickForm $mform
     */
    abstract public function setup_form(MoodleQuickForm $mform): void;

    /**
     * Returns the filter clauses to be used with SQL where
     *
     * @param array $values
     * @return array [$sql, [...$params]]
     */
    abstract public function get_sql_filter(array $values): array;
}