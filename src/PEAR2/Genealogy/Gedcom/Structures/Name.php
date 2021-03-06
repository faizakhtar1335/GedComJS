<?php
/**
 * PEAR2\Genealogy\Gedcom\Structures\Name
 *
 * PHP version 5
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @version   SVN: $Id: Name.php 305 2010-04-13 18:40:26Z ed4becky $
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */
namespace PEAR2\Genealogy\Gedcom\Structures;
use  PEAR2\Genealogy\Gedcom\Tags;

/**
 * Name class for PEAR2_Genealogy_Gedcom
 *
 * @category  File_Formats
 * @package   PEAR2_Genealogy_Gedcom_Structures
 * @author    Ed Thompson <ed4becky@gmail.com>
 * @copyright 2010 Ed Thompson
 * @license   http://www.opensource.org/licenses/Apache2.0.php Apache License
 * @link      http://svn.php.net/repository/PEAR2/PEAR2_Genealogy_Gedcom
 */

class Name  extends EntityAbstract
{

    /**
     *
     * @var string
     */
    var $Full;
    /**
     *
     * @var string
     */
    var $Type;
    /**
     *
     * @var NamePieces
     */
    var $Pieces;

    /**
     * constructor
     */
    function __construct()
    {
        $this->Pieces = new NamePieces();
    }

    /**
     * Returns a concatenated string of a 'Full' name
     *
     * @return string The full name
     */
    public function getFullName() {
        if(isset($this->Full) && $this->Full != '')
        {
            $str = $this->Full;
        } else {
            $str = $this->Pieces->getFullName();
        }

        return $str;
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
     * @param string $top relevant top lvl tag name
     *
     * @return string a return character delimited string of gedcom records
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function toGedcom($lvl, $ver, $top=Tags::FULL)
    {
        if (!isset($ver) || $ver === '') {
            $ver = $this->Ver;
        }
        $gedRec = '';
        if (strpos($ver, '5.5.1') == 0) {
            if (isset($this->Full) && $this->Full != '') {
                $gedRec .= $lvl . ' ' . $top . ' ' . $this->Full;
            }
            $lvl2 = $lvl + 1;

            if (isset($this->Type) && $this->Type != '') {
                if ($gedRec != '') {
                    $gedRec .= "\n";
                }
                $gedRec .= $lvl2 . ' ' . Tags::TYPE . ' ' .  $this->Type;
            }
            $str = $this->Pieces->toGedcom($lvl2, $ver);
            if (isset($str) && $str !='') {
                $gedRec .= "\n" . $str;
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
     * @param string $top  relevant top lvl tag name
     *
     * @return void
     *
     * @access public
     * @since Method available since Release 0.0.1
     */
    public function parseTree($tree, $ver, $top=Tags::FULL)
    {
        $this->Ver =$ver;
        if (($i1=parent::findTag($tree, $top))!==false) {
            $this->Full = parent::parseText($tree[$i1], $top);
            if (isset($tree[$i1][1])) {
                $sub2 = $tree[$i1][1];
                if (($i2=parent::findTag($sub2, Tags::TYPE))!==false) {
                    $this->Type = parent::parseText($sub2[$i2], Tags::TYPE);
                }

                $this->Pieces->parseTree($sub2, $ver);
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
        $str = __CLASS__ . '(Version->' . $this->Ver
        . ', Full->' . $this->Full
        . ', Type->' . $this->Type
        . ', Pieces->' . $this->Pieces
        . ')';

        return $str;
    }
}
