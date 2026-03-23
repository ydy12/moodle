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
 * Library functions for assignsubmission_gitrepo.
 *
 * @package    assignsubmission_gitrepo
 * @copyright  2024 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Serves files for the plugin.
 *
 * @param stdClass $course Course object
 * @param stdClass $cm Course module object
 * @param context $context Context
 * @param string $filearea File area
 * @param array $args Extra arguments
 * @param bool $forcedownload Whether to force download
 * @param array $options Additional options
 * @return bool
 */
function assignsubmission_gitrepo_pluginfile(
    $course,
    $cm,
    $context,
    $filearea,
    $args,
    $forcedownload,
    array $options = []
) {
    // This plugin doesn't serve files currently.
    return false;
}

/**
 * Callback function for privacy API.
 *
 * @return array
 */
function assignsubmission_gitrepo_get_fontawesome_icon_map(): array {
    return [
        'assignsubmission_gitrepo:github' => 'fa-github',
        'assignsubmission_gitrepo:gitlab' => 'fa-gitlab',
        'assignsubmission_gitrepo:git' => 'fa-git',
    ];
}

/**
 * Add navigation nodes.
 *
 * @param settings_navigation $settingsnav
 * @param context $context
 * @return void
 */
function assignsubmission_gitrepo_extend_settings_navigation(
    settings_navigation $settingsnav,
    context $context
) {
    // No additional navigation nodes needed.
}

/**
 * Fragment API for AJAX operations.
 *
 * @param array $args Arguments
 * @return string
 */
function assignsubmission_gitrepo_output_fragment_oauth_status(array $args): string {
    global $DB, $USER, $OUTPUT;

    $platform = $args['platform'] ?? '';
    if (empty($platform)) {
        return '';
    }

    $oauth = $DB->get_record('assignsubmission_gitrepo_oauth', [
        'userid' => $USER->id,
        'platform' => $platform,
    ]);

    $data = [
        'platform' => $platform,
        'connected' => !empty($oauth),
        'username' => $oauth->platform_username ?? '',
    ];

    return $OUTPUT->render_from_template('assignsubmission_gitrepo/oauth_status', $data);
}

/**
 * Fragment API for repository validation.
 *
 * @param array $args Arguments
 * @return string
 */
function assignsubmission_gitrepo_output_fragment_validate_repo(array $args): string {
    global $OUTPUT;

    $platform = $args['platform'] ?? '';
    $url = $args['url'] ?? '';

    if (empty($platform) || empty($url)) {
        return json_encode(['valid' => false, 'error' => 'Missing parameters']);
    }

    $classname = '\\assignsubmission_gitrepo\\service\\' . $platform . '_service';
    if (!class_exists($classname)) {
        return json_encode(['valid' => false, 'error' => 'Unknown platform']);
    }

    $service = new $classname();
    $valid = $service->validate_repo_url($url);

    if ($valid) {
        $info = $service->parse_repo_url($url);
        return json_encode([
            'valid' => true,
            'owner' => $info['owner'] ?? '',
            'repo' => $info['repo'] ?? '',
        ]);
    }

    return json_encode(['valid' => false, 'error' => get_string('invalidrepourl', 'assignsubmission_gitrepo')]);
}
