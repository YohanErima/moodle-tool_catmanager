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
 * The index of the page of categories delete
 * @package    tool_catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/formslib.php');
require_once('./get.php');
require_once('./getuser.php');
require_once('./getroleassign.php');
require_once('categoriesmanagers_form.php');

global $DB;
global $SESSION;
$idparam = optional_param('idparam', '', PARAM_INT);
require_login(); // A login is require.
admin_externalpage_setup('toolcatmanager_deletemanager'); // The admin page.
require_capability('moodle/category:manage', context_system::instance()); // Manager role.

$stringdoesnotexist          = get_string('doesnotexist', 'tool_catmanager');
$stringline                  = get_string('line', 'tool_catmanager');
$stringnochanges             = get_string('nochanges', 'tool_catmanager');
$stringerror                 = get_string('error', 'tool_catmanager');
$stringbadsyntaxmanager      = get_string('badsyntaxmanager', 'tool_catmanager');
$stringoldmanagers           = get_string('oldmanagers', 'tool_catmanager');
$stringoldcoursecreators     = get_string('oldcoursecreators', 'tool_catmanager');
$stringmanagersdeleted       = get_string('managersdeleted', 'tool_catmanager');
$stringcoursecreatorsdeleted = get_string('coursecreatorsdeleted', 'tool_catmanager');
$stringnofile                = get_string('nofile', 'tool_catmanager');

if (empty($idparam)) {
    require_sesskey();
    $aformdeletesuccess = new categories_manager_delete_manager_form_success();
    // Check if the user have upload a file and if we need to display a report.
    if ($formreturn = $aformdeletesuccess->get_data()) { // The user has clicked in the button of download csv report changes.
        if (isset($formreturn->downloadbutton)) {
            if (isset($SESSION->vartable)) {
                $fp = fopen(sys_get_temp_dir()."/reportcsv.csv", "w");
                foreach ($SESSION->vartable as $line) {
                    fputcsv($fp, $line, ';');
                }
                readfile(sys_get_temp_dir()."/reportcsv.csv");
                fclose($fp);

                header('content-type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename="reportcsv.csv"');
                unlink(sys_get_temp_dir()."/reportcsv.csv");
                unset($SESSION->vartable);
                die; // Stop the script.
            }
        }
    }
    if (isset($SESSION->str)) { // Print if the traitement is a success.
        echo $OUTPUT->header();
        echo $OUTPUT->heading_with_help(get_string('deletemanager', 'tool_catmanager'),
            'deletemanager', 'tool_catmanager');
        $aformdeletesuccess->display();
        echo $OUTPUT->footer();
        unset($SESSION->str);
        die;
    }

    // Need to delete file if user don't choose to download the file.
    if (file_exists(sys_get_temp_dir().'/reportcsv.csv')) {
        unlink(sys_get_temp_dir().'/reportcsv.csv');
    }

    // Delete the file if we have a upload error.
    if (file_exists(sys_get_temp_dir().'/report.txt')) {
        unlink(sys_get_temp_dir().'/report.txt');
    }

    $aformdelete = new categories_manager_delete_manager_form(); // Instance of form.
    if ($formdata = $aformdelete->get_data()) {
        if (strcmp($formdata->deletetext, 'DELETE') == 0) {
            // If the admin write the good text "DELETE", the delete traitement can begin.
            // Contain the csv report, For futur fonctionnality (download a report changes).
            $reporttab           = array(
                array(
                    'idNumber',
                    'username'
                )
            );
            $reportmanager       = array(
                array(
                    'managers deleted',
                    ''
                )
            );
            $reportcoursecreator = array(
                array(
                    'coursecreators deleted',
                    ''
                )
            );
            // Report printed.
            $reporting           = '';
            $manager             = '</br><strong>' . $stringoldmanagers . '</strong> </br>';
            $coursecreator       = '</br> <strong>' . $stringoldcoursecreators . '</strong></br>';
            $error               = '</br><strong>' . $stringerror . '</strong></br>';
            $megastring          = '';
            // End of report printed.
            $filename            = sys_get_temp_dir().'/import.csv';
            $content             = $aformdelete->get_file_content('coursefile'); // The file to delete managers.
            // Put the content on a internal file to allow easier access on the csv.
            $fp = fopen($filename, 'w');
            file_put_contents($filename, $content);
            fclose($fp);
            $datatab       = array(); // Content of the csv.
            $tabcat        = new categories_manager_get_categorie_tab(); // All categories.
            $tabuser       = new categories_manager_getusertab(); // All users.
            $tabroleassign = new categories_manager_get_role_assign_tab(); // All assignments.
            // Get the content.
            if (($handle = fopen($filename, "r")) !== false) {
                while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                    array_push($datatab, $data);
                }
                fclose($handle);
            }
            // Reporting.
            $counterror         = 0; // Number of error.
            $countmanager       = 0; // Number of coursecreator that the csv file has deleted.
            $countcoursecreator = 0; // Number of coursecreator that the csv file has deleted.
            $errordetection     = false; // Error detection to notifie the admin that there are error(s) at a line.
            if ($datatab) {
                $syntaxtest = $datatab[0];
                if ($tabroleassign->syntax_verification($syntaxtest) == 1) { // Check if the syntax is good.
                    /* Begin of the delete traitement */
                    for ($i = 1; $i < count($datatab); $i++) {
                        $currenttabroleassign = new categories_manager_get_role_assign_tab(); // Current table assignments.
                        $errordetection       = false; // Reset errordetection.
                        // Get the indipensable value.
                        $idnumber             = $datatab[$i][0]; // Idnumber of the category.
                        $username             = $datatab[$i][1]; // Username of the user, one-off.
                        $role                 = $datatab[$i][2]; // Role.
                        $roleid               = $tabroleassign->get_role_id_with_role($role); // Student is the default value.
                        $catid                = $tabcat->get_id_with_idnumber($idnumber); // We need the id to found the contextid.
                        $userid               = $tabuser->get_id_with_username($username); // Same.
                        // Adding the role.
                        if ($catid && $userid) {
                            $context        = context_coursecat::instance($catid); // We need the context to have the contextid.
                            $contextid      = $context->id; // The value that we want to insert in the assignments table.
                            $roleassignid = $currenttabroleassign->get_id_with_context_and_user_and_role($contextid, $userid, $roleid);
                            if ($roleassignid) {
                                // Delete the assignments into the database.
                                $DB->delete_records('role_assignments', array(
                                    'id' => $roleassignid
                                ));
                                if ($roleid == 1) {
                                    $manager .= $tabuser->gget_first_name_with_username($username) . ' '
                                        . $tabuser->get_last_name_with_username($username) . ' - '
                                        . $tabcat->get_name_with_idnumber($idnumber) . '</br>';
                                    $countmanager++;
                                    array_push($reportmanager, array(
                                        $idnumber,
                                        $username
                                    ));
                                } else {
                                    $coursecreator .= $tabuser->get_first_name_with_username($username) . ' '
                                        . $tabuser->get_last_name_with_username($username) . ' - '
                                        . $tabcat->get_name_with_idnumber($idnumber) . '</br>';
                                    $countcoursecreator++;
                                    array_push($reportcoursecreator, array(
                                        $idnumber,
                                        $username
                                    ));
                                }
                            } else {
                                $error .= '"' . $idnumber . ';' . $username . ';' . $role . '" '
                                    . $stringdoesnotexist . ' (' . $stringline . ' ' . ($i + 1) . ')</br>';
                                $errordetection = true;
                                $counterror++; // Add error.
                            }
                        }
                        // Check if the parameters are wright.
                        if ($catid == null) { // Error.
                            $error .= $idnumber . ' ' . $stringdoesnotexist . ' (' . $stringline . ' ' . ($i + 1) . ')</br>';
                            if (!$errordetection) {
                                $errordetection = true;
                            }
                            $counterror++;
                        }
                        if ($userid == null) {
                            $error .= $username . ' ' . $stringdoesnotexist . ' (' . $stringline . ' ' . ($i + 1) . ')</br>';
                            if (!$errordetection) {
                                $errordetection = true;
                            }
                            $counterror++;
                        }
                    }
                    // Report changes.
                    // Reporting.
                    $reporting .= '</br>' . $stringmanagersdeleted . ' ' . $countmanager . '</br>'; // Reporting numbers.
                    $reporting .= $stringcoursecreatorsdeleted . ' ' . $countcoursecreator . '</br>';
                    $reporting .= $stringerror . ' ' . $counterror . '</br>';
                    $megastring .= $reporting;
                    if ($counterror > 0) {
                        $error .= '</br>'; // To seperate error reporting and traitement.
                        $megastring .= $error;
                    }
                    if ($countmanager > 0) {
                        $megastring .= $manager;
                        foreach ($reportmanager as $line) {
                            array_push($reporttab, $line);
                        }
                    } // Avoid unnecessery space.
                    if ($countcoursecreator > 0) {
                        $megastring .= $coursecreator;
                        foreach ($reportcoursecreator as $line) {
                            array_push($reporttab, $line);
                        }
                    }
                    if (($countmanager + $countcoursecreator + $counterror) == 0) {
                        $megastring .= $stringnochanges; // Notify the admin that there are not changes.
                    }
                } else { // We notify the admin that the syntax is bad.
                    $megastring .= $stringbadsyntaxmanager;
                }
            } else {
                $megastring .= $stringnofile;
            } // If !datatab, the admin have not insert csv file.
            // Erase import file content.
            unlink($filename);
            // Put the report change into a file.
            $fp = fopen(sys_get_temp_dir().'/report.txt', 'w');
            file_put_contents(sys_get_temp_dir().'/report.txt', $megastring);
            fclose($fp);
            // Report changes that you can download.
            $SESSION->vartable = $reporttab;
            $SESSION->str = 'yes';
            $sesskey = sesskey();
            header("location: index_deletemanager.php?sesskey=$sesskey");
        } else { // The admin has writed a bad text.
            $aformdeletenosuccess = new categories_manager_delete_manager_form_no_success();
            echo $OUTPUT->header();
            echo $OUTPUT->heading_with_help(get_string('deletemanager', 'tool_catmanager'),
                'deletemanager', 'tool_catmanager');
            $aformdeletenosuccess->display();
            echo $OUTPUT->footer();
        }
    } else {
        echo $OUTPUT->header();
        echo $OUTPUT->heading_with_help(get_string('deletemanager', 'tool_catmanager'),
            'deletemanager', 'tool_catmanager');
        $aformdelete->display();
        echo $OUTPUT->footer();
        die;
    }
}
