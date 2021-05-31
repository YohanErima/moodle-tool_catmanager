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
 * References all categories traitements with two classes
 * @package    tool_catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

/**
 * Class categoy
 *
 * category object to clone a category trough the database
 * @package    tool_catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class categoy {
    /**
     * Category id copy.
     *
     * @var int
     */
    public $id;
    /**
     * Category name.
     *
     * @var string
     */
    public $name;
    /**
     * Idnumber category
     *
     * @var string
     */
    public $idnumber;
    /**
     * Description category
     *
     * @var string
     */
    public $description;
    /**
     * Format description
     *
     * @var string
     */
    public $descriptionformat;
    /**
     * Parent copy
     *
     * @var int
     */
    public $parent;
    /**
     * Sort order
     *
     * @var int
     */
    public $sortorder;
    /**
     * Count of course copy
     *
     * @var int
     */
    public $coursecount;
    /**
     * Visible copy
     *
     * @var int
     */
    public $visible;
    /**
     * Visible old copy
     *
     * @var int
     */
    public $visibleold;
    /**
     * Time modified copy
     *
     * @var int
     */
    public $timemodified;
    /**
     * Depth copy
     *
     * @var int
     */
    public $depth;
    /**
     * Path copy
     *
     * @var string
     */
    public $path;
    /**
     * Theme copy
     *
     * @var string
     */
    public $theme;
    /**
     * Contextid that we add manually
     *
     * @var string
     */
    public $contextid;
     /**
      * category constructor
      *
      * @param int $id
      * @param string $name
      * @param int $idnumber
      * @param string $description
      * @param string $descriptionformat
      * @param int $parent
      * @param int $sortorder
      * @param int $coursecount
      * @param int $visible
      * @param int $visibleold
      * @param int $timemodified
      * @param int $depth
      * @param string $path
      * @param string $theme
      * @param string $contextid
      */
    public function __construct($id, $name, $idnumber, $description, $descriptionformat, $parent, $sortorder, $coursecount,
            $visible, $visibleold, $timemodified, $depth, $path, $theme, $contextid) {
        $this->id                = $id;
        $this->name              = $name;
        $this->idnumber          = $idnumber;
        $this->description       = $description;
        $this->descriptionformat = $descriptionformat;
        $this->parent            = $parent;
        $this->sortorder         = $sortorder;
        $this->coursecount       = $coursecount;
        $this->visible           = $visible;
        $this->visibleold        = $visibleold;
        $this->timemodified      = $timemodified;
        $this->depth             = $depth;
        $this->path              = $path;
        $this->theme             = $theme;
        $this->contextid         = $contextid;
    }
}


/**
 * Class getcatetab
 *
 * We can do all categories traitements with this class
 * @package    tool_catmanager
 * @copyright 2021, University of La Reunion, Person in charge:Didier Sebastien,
 * Developer:Yohan Erima <yohan.erima417@gmail.com>, Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class getcatetab {

    /**
     * Tab will contain array of categpries
     *
     * @var array
     */
    public $tab;
    /**
     * Depthmax a future fonctionnality
     *
     * @var int
     */
    public $depthmax;
    /**
     * Allidnumber a future fonctionnality
     *
     * @var array
     */
    public $allidnumber; // Future fonctionnality.
    /**
     * getcatetab constructor
     * get all data that we need from the database
     */
    public function __construct() {
        global $DB;
        $table          = 'course_categories';
        // Open DB.
        $thedb          = $DB->get_recordset($table); // Return a mysqli_native_moodle_recordset object.
        $this->depthmax = 0;
        $number         = array(); // Idnumber table.
        $tmptab         = array(); // Category table.
        $counttab       = 0;
        // Put all categories in a table.
        foreach ($thedb as $record) {
            // A category in each line.
            $contextid         = context_coursecat::instance($record->id);
            $tmptab[$counttab] = new categoy($record->id, $record->name, $record->idnumber, $record->description,
                $record->descriptionformat, $record->parent, $record->sortorder, $record->coursecount, $record->visible,
                $record->visibleold, $record->timemodified, $record->depth, $record->path, $record->theme, $contextid);
            $counttab++;
        }
        $this->tab = $tmptab;
        $thedb->close(); // Close the db connection.
        // Open a new db connection for allidnumber, future fonctionnality.
        $thedb = $DB->get_recordset($table); // Return mysqli_native_moodle_recordset.
        // Put all idnumbers in a table.
        foreach ($thedb as $record) {
            array_push($number, $record->idnumber);
            if ($record->depth > $this->depthmax) {
                $this->depthmax = $record->depth;
            }
        }
        $thedb->close();
        // Close DB.
        $this->allidnumber = $number;
        $this->depthmax    = 0;
    }

    /**
     * Return the id of a category through the idnumber
     *
     * @param string $idnumber
     * @return mixed
     */
    public function getidwithidnumber($idnumber) {
        $tmptab = $this->tab;
        for ($i = 0; $i < count($this->tab); $i++) {
            if (strcmp($tmptab[$i]->idnumber, $idnumber) == 0) {
                return $tmptab[$i]->id;
            }
        }
    }

    /**
     * Return the idnumber of a category through the id.
     *
     * @param int $id
     * @return mised
     */
    public function getidnumberwithid($id) {
        $tmptab = $this->tab;
        for ($i = 0; $i < count($this->tab); $i++) {
            if ($tmptab[$i]->id == $id) {
                $res = $tmptab[$i]->idnumber;
            }
        }
        if (isset($res)) {
            return $res;
        } else {
            return false;
        }
    }

    /**
     * Return the name of a category through the id
     *
     * @param int $id
     * @return mixed
     */
    public function getnamewithid($id) {
        $tmptab = $this->tab;
        for ($i = 0; $i < count($this->tab); $i++) {
            if ($tmptab[$i]->id == $id) {
                return $tmptab[$i]->name;
            }
        }
    }

    /**
     * Return the name of a category through the idnumber.
     *
     * @param string $idnumber
     * @return mixed
     */
    public function getnamewithidnumber($idnumber) {
        $tmptab = $this->tab;
        for ($i = 0; $i < count($this->tab); $i++) {
            if (strcmp($tmptab[$i]->idnumber, $idnumber) == 0) {
                return $tmptab[$i]->name;
            }
        }
    }

    /**
     * Return the parent of a category through the id.
     * @param int $id
     * @return mixed
     */
    public function getparentwithid($id) {
        $tmptab = $this->tab;
        for ($i = 0; $i < count($this->tab); $i++) {
            if ($tmptab[$i]->id == $id) {
                return $tmptab[$i]->parent;
            }
        }
    }

    /**
     * Transform the table like idnumber, name, parent, description for csv format
     * @return array
     */
    public function getarray() {
        $tmptab = $this->tab;
        $array  = array(
            array(
                'idNumber',
                'name',
                'parent',
                'description'
            )
        );
        for ($i = 0; $i < count($tmptab); $i++) {
            $tmparray = array();
            if ($tmptab[$i]->parent == 0) {
                // We dont want to export 'miscellaneous' because the are a risk to have 'miscellaneous' doublons
                // if we backup the export.
                array_push($tmparray, $tmptab[$i]->idnumber, $tmptab[$i]->name, '', $tmptab[$i]->description);
            } else {
                $parentid       = $tmptab[$i]->parent;
                $parentidnumber = $this->getidnumberwithid($parentid);
                array_push($tmparray, $tmptab[$i]->idnumber, $tmptab[$i]->name, $parentidnumber, $tmptab[$i]->description);
            }
            array_push($array, $tmparray);
        }
        return $array;
    }

    /**
     * Correspondence table for  catid and contextid
     * @return array
     */
    public function getarraycontext() {
        $tmptab = $this->tab;
        $array  = array();
        for ($i = 0; $i < count($tmptab); $i++) {
            $category = $tmptab[$i];
            $context  = context_coursecat::instance($category->id);
            $tmparray = array(
                $category->id,
                $context->id
            );
            array_push($array, $tmparray);
        }
        return $array;
    }

    /**
     * Return all categories id
     * @return array
     */
    public function getallid() {
        $tmptab = $this->tab;
        $array  = array();
        for ($i = 0; $i < count($tmptab); $i++) {
            array_push($array, $tmptab[$i]->id);
        }
        return $array;
    }

    /**
     * Source : http:// nazcalabs.com/blog/convert-php-array-to-utf8-recursively/
     * transform the elements of the array to utf-8 if it is on a different format
     * @param array $array
     * @return array
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
     * Create the csv file ( for export)
     * @param string $name
     */
    public function createcsv($name) {
        $list = $this->utf8_converter($this->getarray());
        $fp   = fopen('internal_file/' . $name . '.csv', 'w');
        foreach ($list as $fields) {
            fputcsv($fp, $fields, ";");
        }
        fclose($fp);
    }

    /**
     * Download the csv file ( for export)
     */
    public function downloadexportcsv() {
        $csvfile = 'internal_file/export.csv';
        if (file_exists($csvfile)) {
            header('content-type: text/csv; charset=utf-8');
            header('content-disposition: attachment; filename="' . basename($csvfile) . '"');
            readfile($csvfile);
            die;
        }
        // Erase export file content.
        file_put_contents($csvfile, '');
    }

    /**
     * Method test to display the csv file
     *
     * @param string $name
     */
    public function showcsv($name) {
        echo "<h5>csv :</h5>";
        $list = $this->getarray();
        $fp   = fopen($name . '.csv', 'w');
        foreach ($list as $fields) {
            $str = '';
            for ($i = 0; $i < count($fields); $i++) {
                if ($i == count($fields) - 1) {
                    $str .= $fields[$i] . "\n";
                } else {
                    $str .= $fields[$i] . ";";
                }
            }
            echo $str . '</br>';
            fwrite($fp, $str);
        }
        fclose($fp);
    }

    /**
     * Return the all sons of the category through the id
     * @param int $id
     * @return array
     */
    public function haschild($id) {
        $res    = array();
        $tmptab = $this->tab;
        foreach ($tmptab as $obj) {
            if (strpos($obj->path, '/' . $id) !== false && $obj->id != $id) {
                array_push($res, $obj->id);
            }
        }
        return $res;
    }

    /**
     * Check if the syntax is good
     * @param array $datatab
     * @return int
     */
    public function syntaxverification($datatab) {
        $result = 1; // 1 if syntax is good, 0 else.
        if (count($datatab) == 4) {
            $syntaxidnumber    = trim($datatab[0]); // Drop space.
            $syntaxname        = trim($datatab[1]);
            $syntaxparent      = trim($datatab[2]);
            $syntaxdescription = trim($datatab[3]);
            if (strcmp($syntaxidnumber, 'idNumber') + strcmp($syntaxname, 'name') + strcmp($syntaxparent, 'parent')
                    + strcmp($syntaxdescription, 'description') !== 0) { // The syntax is bad.
                $result = 0;
            }
        } else {
            $result = 0;
        }
        return $result;
    }

    /**
     * Check if the syntax for delete is good
     * @param array $datatab
     * @return int
     */
    public function deletesyntaxverification($datatab) {
        $result = 1; // 1 if syntax is good, 0 else.
        if (count($datatab) == 3 || (count($datatab) == 4)) {
            $syntaxidnumber    = trim($datatab[0]); // Drop space.
            $syntaxname        = trim($datatab[1]);
            $syntaxparent      = trim($datatab[2]);
            if (strcmp($syntaxidnumber, 'idNumber') + strcmp($syntaxname, 'name') + strcmp($syntaxparent, 'parent') !== 0) {
                // The syntax is bad.
                $result = 0;
            }
        } else {
            $result = 0;
        }
        return $result;
    }

    /**
     * Create a csv report changes to have a track of the changes (for future fonctionnality)
     * @param array $data
     */
    public function createreportcsv($data) {
        $list = $this->utf8_converter($data);
        $fp   = fopen('internal_file/reportcsv.csv', 'w');
        foreach ($list as $fields) {
            fputcsv($fp, $fields, ";");
        }
        fclose($fp);
    }

    /**
     * Download csv report changes
     */
    public function downloadreportcsv() {
        $csvfile = 'internal_file/reportcsv.csv';
        if (file_exists($csvfile)) {
            header('content-type: text/csv; charset=utf-8');
            header('content-disposition: attachment; filename="' . basename($csvfile) . '"');
            readfile($csvfile);
            die;
        }
    }

    /**
     * Number of elements in the tables
     * @return int
     */
    public function counttable() {
        return count($this->tab);
    }
}