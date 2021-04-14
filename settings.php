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
 * @copyright 2021 Yohan Erima <yohan.erima417@gmail.com>
 * @copyright based on work by 2016 Nakidine Houmadi <n.houmadi@rt-iut.re>
 * University of La Reunion, Person in charge : Didier Sebastien <didier.sebastien@univ-reunion.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    // Put the plugin functionnality on the "site administration".
    $ADMIN->add('courses', new admin_category('toolcatmanager_manage', 'catmanager'));
    // Put "Categories->..." and "Managers->..." on the menu.
    $ADMIN->add('toolcatmanager_manage', new admin_category('toolcatmanager_category', 'Categories'));
    $ADMIN->add('toolcatmanager_manage', new admin_category('toolcatmanager_manager', 'Managers'));

    // Categories.

    // Upload/Edit Categories.
    // Put "Upload/Edit course categories" on the menu.
    // Course->Manage course categories->Upload/Edit course categories.
    $ADMIN->add('toolcatmanager_category', new admin_externalpage('toolcatmanager_upload',
        get_string('uploadeditcoursecategories', 'tool_catmanager'),
        "$CFG->wwwroot/$CFG->admin/tool/catmanager/index_upload.php"));

    // Export Categories.
    // Course->Manage course categories->Upload/EditExport course categories.
    $ADMIN->add('toolcatmanager_category', new admin_externalpage('toolcatmanager_export',
        get_string('exportcoursecategories', 'tool_catmanager'),
        "$CFG->wwwroot/$CFG->admin/tool/catmanager/index_export.php"));

    // Delete Categories.
    // Course->Manage course categories->Upload/EditDelete course categories.
    $ADMIN->add('toolcatmanager_category', new admin_externalpage('toolcatmanager_delete',
        get_string('deletecoursecategories', 'tool_catmanager'),
        "$CFG->wwwroot/$CFG->admin/tool/catmanager/index_delete.php"));

    // Categories Manager .

    // Upload/Edit Categories Manager.
    // Course->Manage course categories->Upload/Edit course categories.
    $ADMIN->add('toolcatmanager_manager', new admin_externalpage('toolcatmanager_uploadmanager',
        get_string('uploadeditmanager', 'tool_catmanager'),
        "$CFG->wwwroot/$CFG->admin/tool/catmanager/index_uploadmanager.php"));

    // Export Categories Manager.
    // Course->Manage course categories->Upload/EditExport course categories.
    $ADMIN->add('toolcatmanager_manager', new admin_externalpage('toolcatmanager_exportmanager',
        get_string('exportmanager', 'tool_catmanager'),
        "$CFG->wwwroot/$CFG->admin/tool/catmanager/index_exportmanager.php"));

    // Delete Categories Manager.
    // Course->Manage course categories->Upload/EditDelete course categories.
    $ADMIN->add('toolcatmanager_manager', new admin_externalpage('toolcatmanager_deletemanager',
        get_string('deletemanager', 'tool_catmanager'),
        "$CFG->wwwroot/$CFG->admin/tool/catmanager/index_deletemanager.php"));
}