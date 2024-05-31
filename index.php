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
 * Index page for the CAD SoulCode plugin
 *
 * @package     local_cadastro
 * @copyright   2024 Gabriel Diniz gabrieldiniz.contato@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/local/cadastro/lib.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/cadastro/index.php'));
$PAGE->set_pagelayout('login');
$PAGE->set_title(get_string('pluginname', 'local_cadastro'));

$cadastroform = new \local_cadastro\form\cadastro_form();

if ($cadastroform->is_cancelled()) {
    redirect(new moodle_url('/login'));
} else if ($data = $cadastroform->get_data()) {
    cadastro_create_user($data);
} else {
    $cadastroform->set_data($toform);
}

echo $OUTPUT->header();
$cadastroform->display();
echo $OUTPUT->footer();
