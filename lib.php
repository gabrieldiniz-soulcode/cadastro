<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Lib for the CAD SoulCode plugin
 *
 * @package     local_cadastro
 * @copyright   2024 Gabriel Diniz gabrieldiniz.contato@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/user/lib.php');

/**
 * Cadastra um novo usuário no Moodle.
 *
 * @param stdClass $userdata O objeto com dados do usuario.
 * @return int|false O ID do novo usuário ou false em caso de falha.
 */
function cadastro_create_user($userdata) {
    global $DB, $CFG;

    if ($DB->record_exists('user', ['username' => $userdata->email])) {
        return false;
    }

    $user = new stdClass();
    $user->username = $userdata->email;
    $user->password = $userdata->password;
    $user->firstname = cadastro_extract_firstname($userdata->fullname);
    $user->lastname = cadastro_extract_lastname($userdata->fullname);
    $user->email = $userdata->email;
    $user->confirmed = 1;
    $user->mnethostid = $CFG->mnet_localhost_id;
    $user->country = 'BR';
    $user->institution = 'tiktok';
    $user->auth = 'manual';

    $user->id = user_create_user($user);

    cadastro_create_user_preference($user->id);

    generate_session($user->id);

    return $user->id;
}

/**
 * Creates or updates the 'auth_emailconfirmed' user preference for the specified user ID.
 *
 * @param int $userid The ID of the user.
 */
function cadastro_create_user_preference($userid) {
    global $DB;
    if ($DB->record_exists('user_preferences', ['userid' => $userid, 'name' => 'auth_emailconfirmed'])) {
        $DB->set_field('user_preferences', 'value', '1', ['userid' => $userid, 'name' => 'auth_emailconfirmed']);
    } else {
        $preference = new stdClass();
        $preference->userid = $userid;
        $preference->name = 'auth_emailconfirmed';
        $preference->value = '1';
        $DB->insert_record('user_preferences', $preference);
    }
}

/**
 * Generates a session for the specified user ID and logs the user in.
 *
 * @param int $userid The ID of the user to generate a session for.
 */
function generate_session($userid) {
    global $DB;
    $user = $DB->get_record('user', ['id' => $userid]);
    complete_user_login($user);

    redirect(new moodle_url('/'));
}

/**
 * Extracts the first name from a full name.
 *
 * @param string $fullname The full name.
 * @return string The last name extracted from the full name.
 */
function cadastro_extract_firstname($fullname) {
    $names = explode(' ', $fullname, 2);
    return $names[0];
}

/**
 * Extracts the last name from a full name.
 *
 * @param string $fullname The full name.
 * @return string The last name extracted from the full name.
 */
function cadastro_extract_lastname($fullname) {
    $names = explode(' ', $fullname, 2);
    return $names[1] ?? '';
}

/**
 * Extends the navigation to include custom JavaScript for the login page.
 *
 * @param global_navigation $navigation The global navigation object.
 */
function local_cadastro_extend_navigation($navigation) {
    global $PAGE;

    if ($PAGE->pagetype === 'login-index') {
        $PAGE->requires->js(new moodle_url('/local/cadastro/amd/src/inject_button.js'));
    }
}

