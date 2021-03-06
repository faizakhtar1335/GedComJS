<?php
/**
 * PEAR2\Genealogy\Gedcom\Structures\NamePieces
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Corporation.php 291 2010-03-30 19:38:34Z ed4becky $
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
namespace PEAR2\Genealogy\Gedcom\Structures;
use PEAR2\Genealogy\Gedcom\Structures;
use  PEAR2\Genealogy\Gedcom\Tags;

/**
 * NamePieces class for PEAR2_Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */

class Corporation extends EntityAbstract
{
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
     * constructor
     */
    function __construct()
    {
        $this->Address = new Structures\Address();
    }

    /**
     * Flattens the object into a GEDCOM compliant format
     *
     * This method guarantees compliance, not re-creation of
     * the original order of the records.
     *
     * @param int    $lvl indicates the level at which this record
     * should be generated
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
        if (isset($this->Name) && $this->Name != '') {
            $gedRec .= $lvl . ' ' . Tags::CORP . ' ' . $this->Name;
        }
        $lvl2 = $lvl + 1;
        $str = $this->Address->toGedcom($lvl2, '5.5.1');
        if (isset($str) && $str !='') {
            $gedRec .= "\n" . $str;
        }
        return $gedRec;
    }

    /**
     * Extracts attribute contents from a parent tree object
     *
     * @param array  $tree an array containing an array from which the
     * object data should be extracted
     * @param string $ver  represents the version of the GEDCOM standard
     * data is being extracted from
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parseTree($tree, $ver)
    {
        $this->Ver = $ver;
        if (($i1 = parent::findTag($tree, Tags::CORP)) !== false) {
            $this->Name = parent::parseText($tree [$i1], Tags::CORP);

            $sub2 = $tree[$i1][1];
            if (isset($sub2)) {
                $this->Address->parseTree($sub2, $ver);
            }
        }

    }

    /**
     * Creates a string representation of the object
     *
     * @return string  contains string representation of each
     * public field in the object
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function __toString()
    {
        $str = __CLASS__
            . '(Name->' . $this->Name
            . ", Address->" .$this->Address
            . ')';
        return $str;
    }
}
