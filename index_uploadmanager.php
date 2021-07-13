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
 * The index of the page of managers upload
 * @package    tool_catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_once('./get.php');
require_once($CFG->libdir . '/adminlib.php'); // Moodle files.
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->libdir . '/filelib.php');
require_once('categoriesmanagers_form.php'); // Forms.
require_once('./get.php');
require_once('./getuser.php');
require_once('./getroleassign.php');

global $DB;
global $SESSION;
$idparam = optional_param('idparam', '', PARAM_INT);
require_login(); // A login is require.
admin_externalpage_setup('toolcatmanager_uploadmanager'); // The admin page.
require_capability('moodle/category:manage', context_system::instance()); // Manager role.

$stringdoesnotexist        = get_string('doesnotexist', 'tool_catmanager');
$stringexist               = get_string('exist', 'tool_catmanager');
$stringline                = get_string('line', 'tool_catmanager');
$stringnochanges           = get_string('nochanges', 'tool_catmanager');
$stringerror               = get_string('error', 'tool_catmanager');
$stringbadsyntaxmanager    = get_string('badsyntaxmanager', 'tool_catmanager');
$stringnewmanagers         = get_string('newmanagers', 'tool_catmanager');
$stringnewcoursecreators   = get_string('newcoursecreators', 'tool_catmanager');
$stringmanagersadded       = get_string('managersadded', 'tool_catmanager');
$stringcoursecreatorsadded = get_string('coursecreatorsadded', 'tool_catmanager');

if (empty($idparam)) {
    require_sesskey();
    $aformuploadsuccess = new upload_manager_form_sucess();
    // Check if the user have upload a file and if we need to display a report.
    if ($formreturn = $aformuploadsuccess->get_data()) { // The user has clicked in the button of download csv report changes.
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
    if (isset($SESSION->str)) { // Print this form is the uploading of managers is a success.
        echo $OUTPUT->header();
        echo $OUTPUT->heading_with_help(get_string('uploadeditmanager', 'tool_catmanager'),
            'uploadeditmanager', 'tool_catmanager');
        $aformuploadsuccess->display();
        echo $OUTPUT->footer();
        unset($SESSION->str);
        die;
    }

    // Delete file if the user don't choose to download the file.
    if (file_exists(sys_get_temp_dir().'/reportcsv.csv')) {
        unlink(sys_get_temp_dir().'/reportcsv.csv');
    }

    // Delete the file if we have a upload error.
    if (file_exists(sys_get_temp_dir().'/report.txt')) {
        unlink(sys_get_temp_dir().'/report.txt');
    }

    // Upload.
    $aformupload = new upload_manager_form(); // Form instance.
    if ($formdata = $aformupload->get_data()) {
        // Contain the csv report, For futur fonctionnality (download a report changes).
        $reporttab           = array(
            array(
                'idNumber',
                'userName'
            )
        );
        $reportmanager       = array(
            array(
                'managers added',
                ''
            )
        );
        $reportcoursecreator = array(
            array(
                'coursecreators added',
                ''
            )
        );
        // Reporting.
        $reporting           = '';
        $manager             = '</br><strong>' . $stringnewmanagers . '</strong> </br>';
        $coursecreator       = '</br> <strong>' . $stringnewcoursecreators . '</strong></br>';
        $error               = '</br><strong>' . $stringerror . '</strong></br>';
        $megastring          = '';
        $filename = sys_get_temp_dir().'/import.csv';
        $content             = $aformupload->get_file_content('coursefile'); // The file to upload managers.
        // Put the content on a internal file to allow easier access on the csv.
        $fp = fopen($filename, 'w');
        file_put_contents($filename, $content);
        fclose($fp);
        $datatab       = array(); // Content of the csv.
        $tabcat        = new getcatetab; // All categories.
        $tabuser       = new getusertab(); // All users.
        $tabroleassign = new getroleassigntab(); // All assignments.
        // Get the content.
        if (($handle = fopen($filename, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                array_push($datatab, $data);
            }
            fclose($handle);
        }
        // Reporting.
        $counterror         = 0; // Number of error.
        $countmanager       = 0; // Number of coursecreator that the csv file has added.
        $countcoursecreator = 0; // Number of coursecreator that the csv file has added.
        $errordetection     = false; // Error detection to notifie the admin that there are error(s) at a line.
        $syntaxtest         = $datatab[0];
        if ($tabroleassign->syntaxverification($syntaxtest) == 1) { // Check if the syntax is good.
            // Begin of the uploading traitement.
            for ($i = 1; $i < count($datatab); $i++) {
                $currenttabroleassign = new getroleassigntab(); // Current table assignments.
                $errordetection       = false; // Reset errordetection.
                // ------Get the indipensable value-------.
                $idnumber             = $datatab[$i][0]; // Idnumber of the category.
                $username             = $datatab[$i][1]; // Username of the user, one-off.
                $role                 = $datatab[$i][2]; // Role.
                $roleid               = $tabroleassign->getroleidwithrole($role); // Student is the default value.
                $catid                = $tabcat->getidwithidnumber($idnumber); // We need the id to found the contextid.
                $userid               = $tabuser->getidwithusername($username); // Same.
                if ($catid && $userid) {
                    $context                = context_coursecat::instance($catid); // We need the context to have the contextid.
                    $contextid              = $context->id; // The value that we want to insert in the assignments table.
                    // Creating the assignment.
                    $roleassign            = new stdclass();
                    $roleassign->contextid = $contextid;
                    $roleassign->userid    = $userid;
                    $roleassign->roleid    = $roleid; // 1 is the manager role.
                    $roleassignid         = $currenttabroleassign->getidwithcontextanduserandrole($contextid, $userid, $roleid);
                    // Check if the role exist.
                    if (!$roleassignid) {
                        // Insert the assignments into the database.
                        $DB->insert_record('role_assignments', $roleassign);
                        // Print the list of managers and coursecreators.
                        if ($roleid == 1) { // Manager role.
                            $manager .= $tabuser->getfirstnamewithusername($username) . ' '
                                . $tabuser->getlastnamewithusername($username) . ' - '
                                . $tabcat->getnamewithidnumber($idnumber) . '</br>';
                            $countmanager++;
                            array_push($reportmanager, array(
                                $idnumber,
                                $username,
                                $role
                            ));
                        } else { // Role.
                            $coursecreator .= $tabuser->getfirstnamewithusername($username) . ' '
                                . $tabuser->getlastnamewithusername($username) . ' - '
                                . $tabcat->getnamewithidnumber($idnumber) . '</br>';
                            $countcoursecreator++;
                            array_push($reportcoursecreator, array(
                                $idnumber,
                                $username,
                                $role
                            ));
                        }
                    } else {
                        $error .= '"' . $idnumber . ';' . $username . ';' . $role . '" '
                            . $stringexist . ' (' . $stringline . ' ' . ($i + 1) . ')</br>';
                        $errordetection = true;
                        $counterror++; // Add error.
                    }
                }
                if ($catid == null) { // Error detection, detect if the categorie or the user exist.
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
            // Reporting.
            $reporting .= $stringmanagersadded . ' ' . $countmanager . '</br>'; // Reporting numbers.
            $reporting .= $stringcoursecreatorsadded . ' ' . $countcoursecreator . '</br>';
            $reporting .= $stringerror . ' ' . $counterror . '</br>';
            $megastring .= $reporting;
            // Report changes.
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
        // Erase import file content.
        unlink(sys_get_temp_dir().'/import.csv');
        // Report changes file.
        $fp = fopen(sys_get_temp_dir().'/report.txt', 'w');
        file_put_contents(sys_get_temp_dir().'/report.txt', $megastring);
        fclose($fp);
        // Report changes that you can download.
        $SESSION->vartable = $reporttab;
        $SESSION->str = 'yes';
        $sesskey = sesskey();
        header("location: index_uploadmanager.php?sesskey=$sesskey");
    } else {
        echo $OUTPUT->header();
        echo $OUTPUT->heading_with_help(get_string('uploadeditmanager', 'tool_catmanager'),
            'uploadeditmanager', 'tool_catmanager');
        $aformupload->display();
        echo $OUTPUT->footer();
        die;
    }
}
