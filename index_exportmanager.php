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
 * The index of the page of managers export
 * @package    tool_catmanager
 * @copyright 2021 Yohan Erima <yohan.erima417@gmail.com>
 * @copyright based on work by 2016 Nakidine Houmadi <n.houmadi@rt-iut.re>
 * University of La Reunion, Person in charge : Didier Sebastien <didier.sebastien@univ-reunion.fr>.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require('../../../config.php');
require_once($CFG->libdir . '/adminlib.php'); // Moodle files.
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->libdir . '/filelib.php');
require_once('categoriesmanagers_form.php'); // Forms.
require_once('./getroleassign.php');

$idparam = optional_param('idparam', '', PARAM_INT);
require_login(); // A login is require.
admin_externalpage_setup('toolcatmanager_exportmanager'); // The admin page.
require_capability('moodle/category:manage', context_system::instance()); // Manager role.

if (empty($id)) {
    $aformexport = new export_manager_form(); // Instance of form.
    if ($formdata = $aformexport->get_data()) {
        $roleassigntable = new getroleassigntab(); // Create role assign table.
        $roleassigntable->createcsv('export'); // Create the csv file.
        $roleassigntable->downloadexportcsv(); // Download the csv file.
    } else {
        echo $OUTPUT->header();
        echo $OUTPUT->heading_with_help(get_string('exportmanager', 'tool_catmanager'),
            'exportmanager', 'tool_catmanager');
        $aformexport->display();
        echo $OUTPUT->footer();
        die;
    }
}
