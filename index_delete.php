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
 * The index of the page of delete
 * @package    tool_catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_once($CFG->libdir . '/adminlib.php'); // Moodle file.
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/formslib.php');
require_once('./get.php');
require_once('coursecategory_form.php'); // Form.
global $DB;
global $SESSION;

$idparam = optional_param('idparam', '', PARAM_INT);
require_login(); // A login is require.
admin_externalpage_setup('toolcatmanager_delete'); // The admin page.
require_capability('moodle/category:manage', context_system::instance()); // Manager role.

$stringcategoriesdeleted         = get_string('categoriesdeleted', 'tool_catmanager');
$stringdoesnotexist              = get_string('doesnotexist', 'tool_catmanager');
$stringline                      = get_string('line', 'tool_catmanager');
$stringnochanges                 = get_string('nochanges', 'tool_catmanager');
$stringbadsyntaxcategories       = get_string('badsyntaxcategories', 'tool_catmanager');
$stringerror                     = get_string('error', 'tool_catmanager');
$stringdeleted                   = get_string('deleted', 'tool_catmanager');
$stringnofile                    = get_string('nofile', 'tool_catmanager');
$stringleaveonecategory          = get_string('leaveonecategory', 'tool_catmanager');
$stringtaddtionalinformations    = get_string('addtionalinformations', 'tool_catmanager');
$stringrecallidnumberemptydelete = get_string('recallidnumberemptydelete', 'tool_catmanager');


if (empty($idparam)) {
    require_sesskey();
    $aformdeletesuccess = new categories_manager_delete_form_success();
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
        echo $OUTPUT->heading_with_help(get_string('deletecoursecategories', 'tool_catmanager'),
            'deletecoursecategories', 'tool_catmanager');
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
    if ((sys_get_temp_dir().'/report.txt')) {
        unlink(sys_get_temp_dir().'/report.txt');
    }

    $aformdelete = new categories_manager_delete_form(); // Form instance.
    if ($formdata = $aformdelete->get_data()) {
        if (strcmp($formdata->deletetext, 'DELETE') == 0) {
            // If the admin write the good text "DELETE", the delete traitement can begin.
            // Contain the csv report, For futur fonctionnality (download a report changes).
            $reporttab     = array(
                array(
                    'idNumber',
                    'name'
                )
            );
            $reportdeleted = array(
                array(
                    'categories deleted',
                    ''
                )
            );
            // Report printed.
            $megastring    = '';
            $deleted       = '</br><strong>' . $stringcategoriesdeleted . '</strong></br>';
            $error         = '</br><strong>' . $stringerror . '</strong></br>';
            $addtionalinfo = '</br><strong>' . $stringtaddtionalinformations . '</strong></br>';
            $reporting     = '';
            // End of report printed.
            $filename = sys_get_temp_dir().'/import.csv';
            $content       = $aformdelete->get_file_content('coursefile'); // The file that we want to import.
            // Put the content on a internal file to allow easier access on the csv.
            $fp = fopen($filename, 'w');
            file_put_contents($filename, $content);
            fclose($fp);
            $datatab = array(); // Content of the csv.
            $catetab = new categories_manager_get_categorie_tab(); // All categories.
            $allid = $catetab->get_all_id(); // All categories ids.
            // We recuperates content.
            if (($handle = fopen($filename, "r")) !== false) {
                while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                    array_push($datatab, $data);
                }
                fclose($handle);
            }
            $counterror          = 0; // Number of error.
            $countdeleted        = 0; // Number of categories that are deleted.
            $countadditionalinfo = 0; // Number of categories that are deleted.
            if ($datatab) {
                $syntaxtest = $datatab[0];
                if ($catetab->delete_syntax_verification($syntaxtest) == 1) { // Check if the syntax is good.
                    /* Begin the process of delete */
                    for ($i = 1; $i < count($datatab); $i++) {
                        if (empty($datatab[$i][0])) {
                            // Check if the category have not idnumber, a message will be printed else we continue the program.
                            $addtionalinfo .= ' (' . $stringline . ' ' . ($i + 1) . ') ' . $datatab[$i][1] . '</br>';
                            $countadditionalinfo++;
                        } else {
                            $id    = $catetab->get_id_with_idnumber($datatab[$i][0]);
                            $child = $catetab->has_child($id); // For print all sons that we delete with the traitement.
                            // Only delete the categoy.
                            if (empty($child)) {
                                // If the category have not sons, we simply delete it.
                                $currentcattab = new categories_manager_get_categorie_tab(); // Current categories tables.
                                $currentallid  = $currentcattab->get_all_id(); // Current categories id tables.
                                if (in_array($id, $currentallid)) { // Check if the category exist to avoid system error.
                                    $deletecat = core_course_category::get($id, MUST_EXIST);
                                    // Make sure that we can delete and the current category is not the last category.
                                    if (($deletecat->can_delete_full()) && ($catetab->count_table() > 1)) {
                                        // Check if we can delete the categories and sons, context(...).
                                        $deletecat->delete_full(false); // Delete the categories, sons, context(...).
                                        $deleted .= $catetab->get_id_number_with_id($id) . ' ; '
                                        . $catetab->get_name_with_id($id) . '<br/>'; // Report.
                                        $countdeleted++; // Report.
                                        array_push($reportdeleted, array(
                                            $catetab->get_id_number_with_id($id),
                                            $catetab->get_name_with_id($id)
                                        )); // Csv report.
                                    } else if ($catetab->count_table() <= 1) {
                                        $error .= ' (' . $stringline . ' ' . ($i + 1) . ') '
                                            . $datatab[$i][0] . '-' . $datatab[$i][1] . ' ' . $stringleaveonecategory;
                                        $counterror++;
                                        break;
                                    }
                                } else if ((in_array($id, $allid) == false) && (in_array($id, $currentallid) == false)) {
                                    // Notify the category does not exist.
                                    $error .= $datatab[$i][0] . ' ' . $stringdoesnotexist .
                                        ' (' . $stringline . ' ' . ($i + 1) . ')</br>';
                                    $counterror++;
                                } // End of delete only the category.
                            } else {
                                // Delete the category and his sons.
                                $currentcattab = new categories_manager_get_categorie_tab();
                                $currentallid  = $currentcattab->get_all_id();
                                if (in_array($id, $currentallid)) {
                                    $deletecat = core_course_category::get($id, MUST_EXIST);
                                    if (($deletecat->can_delete_full()) && ($catetab->count_table() > 1)) {
                                        $deletecat->delete_full(false); // Delete the category and his sons.
                                        $deleted .= $catetab->get_id_number_with_id($id) . ' ; '
                                            . $catetab->get_name_with_id($id) . '<br/>'; // Report.
                                        $countdeleted++; // Report.
                                        array_push($reportdeleted, array(
                                            $catetab->get_id_number_with_id($id),
                                            $catetab->get_name_with_id($id)
                                        )); // Csv report.
                                        // Report the delete of all sons.
                                        for ($j = 0; $j < count($child); $j++) {
                                            if (in_array($child[$j], $currentallid)) { // Check if the child currently exist.
                                                $deleted .= $catetab->get_id_number_with_id($child[$j]) . ' ; '
                                                    . $catetab->get_name_with_id($child[$j]) . '<br/>'; // Report.
                                                $countdeleted++; // Report.
                                                array_push($reportdeleted, array(
                                                    $catetab->get_id_number_with_id($child[$j]),
                                                    $catetab->get_name_with_id($child[$j])
                                                )); // Csv report.
                                            }
                                        }
                                    } else if ($catetab->count_table() <= 1) {
                                        $error .= ' (' . $stringline . ' ' . ($i + 1) . ') ' .
                                            $datatab[$i][0] . '-' . $datatab[$i][1] . ' ' . $stringleaveonecategory;
                                        break;
                                    }
                                    // End of delete the category and his sons.
                                } else if ((in_array($id, $allid) == false)
                                && (in_array($id, $currentallid) == false)) { // Category not exist.
                                    $error .= $datatab[$i][0] . ' ' . $stringdoesnotexist . ' ('
                                        . $stringline . ' ' . ($i + 1) . ')</br>';
                                    $counterror++;
                                }
                            }
                        }
                    }
                    // Reporting.
                    $reporting .= $stringdeleted . ' ' . $countdeleted . '</br>'; // Reporting numbers.
                    $reporting .= $stringerror . ' ' . $counterror . '</br>';
                    $megastring .= $reporting;
                    // Report changes.
                    if ($counterror > 0) {
                        $error .= '</br>'; // To seperate error reporting and traitement.
                        $megastring .= $error;
                    }
                    if (($countdeleted) > 0) {
                        $megastring .= $deleted;
                        foreach ($reportdeleted as $line) {
                            array_push($reporttab, $line);
                        }
                    } // Avoid unnecessery space.
                    if ($countadditionalinfo > 0) {
                        $addtionalinfo .= $stringrecallidnumberemptydelete . '</br>';
                        $megastring .= $addtionalinfo;
                    }
                    if (($countdeleted + $counterror) == 0) {
                        $megastring .= $stringnochanges; // Notifie the admin that there are not changes.
                    }
                } else { // We notifie the admin that the syntax is bad.
                    $megastring .= $stringbadsyntaxcategories;
                }
            } else {
                $megastring .= $stringnofile;
            }
            // Erase import file content.
            unlink($filename);
            // Report changes printed.
            $fp = fopen(sys_get_temp_dir().'/report.txt', 'w');
            file_put_contents(sys_get_temp_dir().'/report.txt', $megastring);
            fclose($fp);
            // Report changes that you can download.
            $SESSION->vartable = $reporttab;
            $SESSION->str = 'yes';
            $sesskey = sesskey();
            header("location: index_delete.php?sesskey=$sesskey");
        } else { // We did not delete and display to the user that the text is wrong.
            $aformdeletenosuccess = new categories_manager_delete_form_no_success();
            echo $OUTPUT->header();
            echo $OUTPUT->heading_with_help(get_string('deletecoursecategories', 'tool_catmanager'),
                'deletecoursecategories', 'tool_catmanager');
            $aformdeletenosuccess->display();
            echo $OUTPUT->footer();
        }
    } else {
        echo $OUTPUT->header();
        echo $OUTPUT->heading_with_help(get_string('deletecoursecategories', 'tool_catmanager'),
            'deletecoursecategories', 'tool_catmanager');
        $aformdelete->display();
        echo $OUTPUT->footer();
        die;
    }
}
