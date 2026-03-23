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
 * Plugin version and other meta-data.
 *
 * @package    assignsubmission_gitrepo
 * @copyright  2024 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2024120103;                // The current plugin version (Date: YYYYMMDDXX).
$plugin->requires  = 2024100700;                // Requires Moodle 4.5.0+.
$plugin->component = 'assignsubmission_gitrepo'; // Full name of the plugin.
$plugin->maturity  = MATURITY_STABLE;
$plugin->release   = '1.0.3';
$plugin->dependencies = [
    'mod_assign' => ANY_VERSION,
];
