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
 * References all categories managers traitement with two classes
 * @package    tool_catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
require_once('./get.php');
require_once('./getuser.php');

/**
 * Class categories_manager_roleassign
 *
 * Roleassign object to clone a role assignement trough the database
 * @package    tool_catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class categories_manager_roleassign {
    /**
     * Id assign copy
     *
     * @var int
     */
    public $id; // Id assign copy.
    /**
     * Roleid copy
     *
     * @var string
     */
    public $roleid; // Roleid copy.
    /**
     * Contextid copy
     *
     * @var string
     */
    public $contextid; // Contextid copy.
    /**
     * Userid copy
     *
     * @var string
     */
    public $userid; // Userid copy.

    /**
     * roleassign constructor
     *
     * @param int $id
     * @param string $roleid
     * @param string $contextid
     * @param string $userid
     */
    public function __construct($id, $roleid, $contextid, $userid) {
        $this->id        = $id;
        $this->roleid    = $roleid;
        $this->contextid = $contextid;
        $this->userid    = $userid;
    }
}

/**
 * Class getroleassigntab
 *
 * We can do all role_assignments traitement with this class
 * @package    tool_catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class categories_manager_get_role_assign_tab {
    /**
     * tab
     *
     * @var array
     */
    public $tab;
    /**
     * getroleassigntab constructor.
     * Get the data that we need from the database
     */
    public function __construct() {
        global $DB;
        $tmptab      = array(); // Temporary table that contain all assignements at managers and coursecreators.
        // Get alls managers ------.
        $themanagers = $DB->get_recordset('role_assignments', array(
            'roleid' => 1
        )); // 1 is the manager roleid.
        $counttab    = 0;
        foreach ($themanagers as $record) {
            $tmptab[$counttab] = new categories_manager_roleassign($record->id, $record->roleid, $record->contextid, $record->userid);
            $counttab++;
        }
        $themanagers->close();
        // Get alls coursecreators ------.
        $thecoursecreators = $DB->get_recordset('role_assignments', array(
            'roleid' => 2
        ));
        foreach ($thecoursecreators as $record) {
            $tmptab[$counttab] = new categories_manager_roleassign($record->id, $record->roleid, $record->contextid, $record->userid);
            $counttab++;
        }
        $thecoursecreators->close();
        $this->tab = $tmptab;
    }

    /**
     * Return the assignid through the context, user and role
     * @param string $contextid
     * @param int $userid
     * @param string $roleid
     * @return mixed
     */
    public function get_id_with_context_and_user_and_role($contextid, $userid, $roleid) {
        $tmptab = $this->tab;
        for ($i = 0; $i < count($this->tab); $i++) {
            if ((strcmp($tmptab[$i]->contextid, $contextid) == 0) && (strcmp($tmptab[$i]->userid, $userid) == 0)
                    && (strcmp($tmptab[$i]->roleid, $roleid) == 0)) {
                return $tmptab[$i]->id;
            }
        }
    }

    /**
     * Return the roleid through the role
     * @param string $role
     * @return int
     */
    public function get_role_id_with_role($role) {
        $roleid = 0;
        if (strcmp($role, 'manager') == 0) {
            $roleid = 1;
        } else {
            $roleid = 2; // Coursecreator is default.
        }

        return $roleid;
    }

    /**
     * Return table like idnumber;user;role for the csv
     * @return array
     */
    public function get_array() {
        $tmptab       = $this->tab;
        $catetab      = new categories_manager_get_categorie_tab();
        $usertab      = new categories_manager_get_user_tab();
        $contextarray = $catetab->get_array_context();
        $array        = array(
            array(
                'idNumber',
                'userName',
                'role'
            )
        );
        for ($i = 0; $i < count($tmptab); $i++) {
            // Username.
            $username = $usertab->get_user_name_with_id($tmptab[$i]->userid);
            // Role.
            $roleid   = $tmptab[$i]->roleid;
            $role     = '';
            if ($roleid == 1) {
                $role = 'manager';
            } else {
                $role = 'coursecreator';// Coursecreator is default.
            }
            // Idnumber.
            $contextid = $tmptab[$i]->contextid;
            $catid     = 0;
            // Get the id.
            for ($j = 0; $j < count($contextarray); $j++) {
                if ($contextid == $contextarray[$j][1]) {
                    $catid = $contextarray[$j][0];
                }
            }
            $idnumber = $catetab->get_id_number_with_id($catid);
            // Put the line in the table.
            $tmparray = array(
                $idnumber,
                $username,
                $role
            );
            array_push($array, $tmparray);
        }
        return $array;
    }

    /**
     * Return the same table but in a string version
     * @return array
     */
    public function get_array_string() {
        $tmptab      = $this->tab;
        $arraystring = array();
        for ($i = 0; $i < count($tmptab); $i++) {
            $line = $tmptab[$i]->contextid . $tmptab[$i]->userid . $tmptab[$i]->roleid; // String format is easier to check.
            array_push($arraystring, $line);
        }
        return $arraystring;
    }

    /**
     * Return the assign table
     * @return array
     */
    public function get_table() {
        $tmptab = $this->tab;
        $array  = array();
        for ($i = 0; $i < count($tmptab); $i++) {
            $roleassign            = new stdclass();
            $roleassign->contextid = $tmptab[$i]->contextid;
            $roleassign->userid    = $tmptab[$i]->userid;
            $roleassign->roleid    = $tmptab[$i]->roleid; // 1 is the manager role.
            array_push($array, $roleassign);
        }
        return $array;
    }

    /**
     * Source : http://nazcalabs.com/blog/convert-php-array-to-utf8-recursively/
     * Convert the table as utf-8
     * @param array $array
     * @return mixed
     */
    public function utf8_converter($array) {
        array_walk_recursive($array, function(&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    /**
     * Create the csv file (for export)
     * @param string $name
     */
    public function create_csv($name) {
        $list = $this->utf8_converter($this->get_array());
        $fp   = fopen(sys_get_temp_dir().'/' . $name . '.csv', 'w');
        foreach ($list as $fields) {
            fputcsv($fp, $fields, ";");
        }
        fclose($fp);
    }

    /**
     *  Create and download the csv file ( for export)
     */
    public function download_export_csv() {

        $list = $this->utf8_converter($this->get_array());
        $out = fopen('php://output', 'w');

        foreach ($list as $fields) {
            fputcsv($out, $fields, ";");
        }

        fclose($out);

        header('content-type: text/csv; charset=utf-8');
        header('content-disposition: attachment; filename=export2.csv');

    }

    /**
     * Check if the syntax is good
     * @param array $datatab
     * @return int
     */
    public function syntax_verification($datatab) {
        $result = 1;
        if (count($datatab) == 3) {
            $syntaxidnumber = trim($datatab[0]); // Drop space.
            $syntaxusername = trim($datatab[1]);
            $syntaxrole     = trim($datatab[2]);
            if (strcmp($syntaxidnumber, 'idNumber') + strcmp($syntaxusername, 'userName') + strcmp($syntaxrole, 'role') !== 0) {
                // The syntax is bad.
                $result = 0;
            }
        } else {
            $result = 0;
        }
        return $result;
    }

    /**
     * Csv report for future fonctionnality
     * @param array $data
     */
    public function create_report_csv($data) {
        $list = $this->utf8_converter($data);
        $fp   = fopen(sys_get_temp_dir().'/reportcsv.csv', 'w');
        foreach ($list as $fields) {
            fputcsv($fp, $fields, ";");
        }
        fclose($fp);
    }
}
