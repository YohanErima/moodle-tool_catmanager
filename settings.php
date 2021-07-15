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
 * File settings
 *
 * @package    tool_catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    // Put the plugin functionnality on the "site administration".
    $ADMIN->add('courses', new admin_category('toolcatmanager_manage', 'Categories Manager'));
    // Put "Categories->..." and "Managers->..." on the menu.
    $ADMIN->add('toolcatmanager_manage', new admin_category('toolcatmanager_category', 'Categories'));
    $ADMIN->add('toolcatmanager_manage', new admin_category('toolcatmanager_manager', 'Managers'));

    // Categories.

    // Upload/Edit Categories.
    // Put "Upload/Edit course categories" on the menu.
    // Course->Manage course categories->Upload/Edit course categories.
    $actionupload = new moodle_url("$CFG->wwwroot/$CFG->admin/tool/catmanager/index_upload.php", [
        'sesskey' => sesskey()]);
    $ADMIN->add('toolcatmanager_category', new admin_externalpage('toolcatmanager_upload',
        get_string('uploadeditcoursecategories', 'tool_catmanager'),
        $actionupload));

    // Export Categories.
    // Course->Manage course categories->Upload/EditExport course categories.
    $actionexport = new moodle_url("$CFG->wwwroot/$CFG->admin/tool/catmanager/index_export.php", [
        'sesskey' => sesskey()]);
    $ADMIN->add('toolcatmanager_category', new admin_externalpage('toolcatmanager_export',
        get_string('exportcoursecategories', 'tool_catmanager'),
        $actionexport));

    // Delete Categories.
    // Course->Manage course categories->Upload/EditDelete course categories.
    $actiondelete = new moodle_url("$CFG->wwwroot/$CFG->admin/tool/catmanager/index_delete.php", [
        'sesskey' => sesskey()]);
    $ADMIN->add('toolcatmanager_category', new admin_externalpage('toolcatmanager_delete',
        get_string('deletecoursecategories', 'tool_catmanager'),
        $actiondelete));

    // Categories Manager .

    // Upload/Edit Categories Manager.
    // Course->Manage course categories->Upload/Edit course categories.
    $actionuploadmanager = new moodle_url("$CFG->wwwroot/$CFG->admin/tool/catmanager/index_uploadmanager.php", [
        'sesskey' => sesskey()]);
    $ADMIN->add('toolcatmanager_manager', new admin_externalpage('toolcatmanager_uploadmanager',
        get_string('uploadeditmanager', 'tool_catmanager'),
        $actionuploadmanager));

    // Export Categories Manager.
    // Course->Manage course categories->Upload/EditExport course categories.
    $actionexportmanager = new moodle_url("$CFG->wwwroot/$CFG->admin/tool/catmanager/index_exportmanager.php", [
        'sesskey' => sesskey()]);
    $ADMIN->add('toolcatmanager_manager', new admin_externalpage('toolcatmanager_exportmanager',
        get_string('exportmanager', 'tool_catmanager'),
        $actionexportmanager));

    // Delete Categories Manager.
    // Course->Manage course categories->Upload/EditDelete course categories.
    $actiondeletecat = new moodle_url("$CFG->wwwroot/$CFG->admin/tool/catmanager/index_deletemanager.php", [
        'sesskey' => sesskey()
    ]);
    $ADMIN->add('toolcatmanager_manager', new admin_externalpage('toolcatmanager_deletemanager',
        get_string('deletemanager', 'tool_catmanager'),
        $actiondeletecat));
}
