<?php
/**
 * PEAR2\Genealogy\Gedcom\Records\SubmitterRecord
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: SubmitterRecord.php 297 2010-03-31 14:53:58Z ed4becky $
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
namespace PEAR2\Genealogy\Gedcom\Records;
use PEAR2\Genealogy\Gedcom\Structures;
use PEAR2\Genealogy\Gedcom\Structures\EntityAbstract;
use PEAR2\Genealogy\Gedcom\Tags;

/**
 * SubmitterRecord class for PEAR2_Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Records
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
class SubmitterRecord extends EntityAbstract
{

    /**
     *
     * @var string
     */
    var $Id;
    /**
     *
     * @var string
     */
    var $Name;
    /**
     *
     * @var Address
     */
    var $Address;
    /**
     *
     * @var array
     */
    var $MediaLinks = array();
    /**
     *
     * @var string
     */
    var $Language;
    /**
     *
     * @var string
     */
    var $SubmitterRefNbr;
    /**
     *
     * @var string
     */
    var $AutoRecId;
    /**
     *
     * @var string
     */
    var $ChangeDate;
    /**
     *
     * @var array
     */
    var $Notes = array();

    /**
     * Initializes complex attributes
     *
     * @return none
     */
    public function __construct()
    {
        $this->ChangeDate = new Structures\ChangeDate();
        $this->Address = new Structures\Address();
    }

    /**
     * Flattens the object into a GEDCOM compliant format
     *
     * This method guarantees compliance, not re-creation of
     * the original order of the records.
     *
     * @param int    $lvl indicates the level at which this record
     *                    should be generated
     * @param string $ver represents the version of the GEDCOM standard
     *
     * @return string a return character delimited string of gedcom records
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function toGedcom($lvl, $ver)
    {
        $gedRec = '';
        if (!isset($ver) || $ver === '') {
            $ver = $this->Ver;
        }

        if (strpos($ver, '5.5.1') == 0) {
            $gedRec = $lvl . ' @' .$this->Id. '@ ' . Tags::SUBMITTER;
            $lvl2 = $lvl + 1;
            if (isset($this->Name) && $this->Name != '') {
                $gedRec .= "\n" . $lvl2 . ' ' . Tags::NAME .' ' .$this->Name;
            }
            $tmp = $this->Address->toGedcom($lvl2, $ver);
            if (isset($tmp) && $tmp != '') {
                $gedRec .= "\n" . $tmp;
            }
            if (isset($str) && $str !='') {
                $gedRec .= "\n" . $str;
            }
            for ($i=0; $i<count($this->MediaLinks); $i++) {
                $gedRec .= "\n" . $this->MediaLinks[$i]->toGedcom($lvl2, $ver);
            }
            if (isset($this->AutoRecId) && $this->AutoRecId != '') {
                $gedRec .= "\n" . $lvl2
                    . ' ' . Tags::AUTORECID
                    .' ' .$this->AutoRecId;
            }
            $tmp = $this->ChangeDate->toGedcom($lvl2, $ver);
            if (isset($tmp) && $tmp != '') {
                $gedRec .= "\n" . $tmp;
            }
            for ($i=0; $i<count($this->Notes); $i++) {
                $gedRec .= "\n" . $this->Notes[$i]->toGedcom($lvl2, $ver);
            }
        }
        return $gedRec;
    }

    /**
     * Extracts attribute contents from a parent tree object
     *
     * @param array  $tree an array containing an array from which the
     *                     object data should be extracted
     * @param string $ver  represents the version of the GEDCOM standard
     *                     data is being extracted from
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parseTree($tree, $ver)
    {
        $this->Id = parent::parseRefId($tree[0], Tags::SUBMITTER);
        if (isset($tree[0][1])) {
            $sub2 = $tree[0][1];
            if (($i1=parent::findTag($sub2, Tags::NAME))!==false) {
                $this->Name = parent::parseText($sub2 [$i1], Tags::NAME);
            }
            $this->Address->parseTree($sub2, $ver);

            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::MEDIA, $off))!==false) {
                $tmp = new Structures\MediaLink();
                $tmp->parseTree(array($sub2[$i1]), $ver);
                $this->MediaLinks[] = $tmp;
                $off = $i1 + 1;
            }

            if (($i1=parent::findTag($sub2, Tags::LANGUAGE))!==false) {
                $this->Language = parent::parseText($sub2 [$i1], Tags::LANGUAGE);
            }

            if (($i1=parent::findTag($sub2, Tags::RFN))!==false) {
                $this->SubmitterRefNbr = parent::parseText($sub2 [$i1], Tags::RFN);
            }

            if (($i1=parent::findTag($sub2, Tags::AUTORECID))!==false) {
                $this->AutoRecId = parent::parseText($sub2 [$i1], Tags::AUTORECID);
            }

            if (($i1=parent::findTag($sub2, Tags::CHANGEDATE))!==false) {
                $this->ChangeDate->parseTree(array($sub2[$i1]), $ver);
            }

            $off = 0;
            while (($i1=parent::findTag($sub2, Tags::NOTE, $off))!==false) {
                $tmp = new Structures\Note();
                $tmp->parseTree(array($sub2[$i1]), $ver);
                $this->Notes[] = $tmp;
                $off = $i1 + 1;
            }
        }
    }

    /**
     * Creates a string representation of the object
     *
     * @return string  contains string representation of each
     *                 public field in the object
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function __toString()
    {
        $str = __CLASS__
        . '(Id->' . $this->Id
        . ', Name->' . $this->Name
        . ', Address->' . $this->Address;

        for ($i=0; $i<count($this->MediaLinks); $i++) {
            $str .= "\n" . $this->MediaLinks[$i];
        }
        $str .= ', Language->' . $this->Language
        . ', SubmitterRefNbr->' . $this->SubmitterRefNbr
        . ', AutoRecId->' . $this->AutoRecId
        . ', ChangeDate->' . $this->ChangeDate
        . ', Notes->(';

        for ($i=0; $i<count($this->Notes); $i++) {
            $str .= "\n" . $this->Notes[$i];
        }
        $str .= '))';
        return $str;
    }
}
?>