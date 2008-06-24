<?php
/**
 * zOOm Media Gallery! - a multi-gallery component 
 * 
 * @package zmg
 * @version $Revision$
 * @author Mike de Boer <mike AT zoomfactory.org>
 * @copyright Copyright &copy; 2007, Mike de Boer. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */

defined('_ZMG_EXEC') or die('Restricted access');

/**
 * Main application class
 * @package zmg
 */
class zmgCore {
    /**
     * Internal variable, a holder for an array of galleries
     *
     * @var array
     */
    var $_gallerylist = null;
    /**
     * Public variable, containing the current user.
     * 
     * @var zmgUser
     */
    var $user = null;
    
    /**
     * The class constructor.
     */
     function zmgCore() {
        //just make sure the session initializes:
        zmgFactory::getSession();
    }

    function hasAccess() {
        if (!zmgACL::check_defines())
            return false;
        return true;
    }

    function notAuth() {
        //TODO: implement!
        die('Restricted access');
    }

    /**
     * Check if a filename OR gallery-directory already exists and if it does;
     * do something about it!
     * 
     * @return void
     * @param string $checkThis
     * @param string $checkWhat
     * @access public
     */
    function checkDuplicate($checkThis, $checkWhat = 'filename') {
        $db = & zmgDatabase::getDBO();
        $table = zmgFactory::getConfig()->getTableName('media');
        $db->setQuery("SELECT mid FROM " . $table . " WHERE filename = '"
         . $checkThis . "' AND gid = '" . $this->_gallery->_id . "'");

        if ($this->_result = $db->query()) {
            if (mysql_num_rows($this->_result) > 0) {
                // filename exists already for this gallery, so change the filename and test again...
                // the filename will be changed accordingly:
                // if a filename exists, add the suffix _{number} incrementally,
                // thus 'afile_1.jpg' will become 'afile_2.jpg' and so on...
                return $this->checkDuplicate(preg_replace( "/^(.+?)(_?)(\d*)(\.[^.]+)?$/e",
                  "'\$1_'.(\$3+1).'\$4'", $checkThis));
            } else {
                return $checkThis;
            }
        }
        return $checkThis;
    }
    
    function getGalleryCount() {
        $db = & zmgDatabase::getDBO();
        $db->setQuery("SELECT COUNT(gid) AS total FROM "
         . zmgFactory::getConfig()->getTableName('galleries'));

        if ($db->query()) {
            return intval($db->loadResult());
        }
        return 0;
    }

    function getMediumCount($gid = 0) {
        $db = & zmgDatabase::getDBO();
        $query = "SELECT COUNT(mid) AS total FROM "
         . zmgFactory::getConfig()->getTableName('media');
        if ($gid > 0) {
            $query .= " WHERE gid = $gid";
        }

        $db->setQuery($query);
        if ($db->query()) {
            return intval($db->loadResult());
        }

        return 0;
    }

    function getGalleries($sub_gid = 0, $pos = 0) {
        $ret  = array();

        $db   = & zmgDatabase::getDBO();
        $db->setQuery("SELECT gid FROM " . zmgFactory::getConfig()->getTableName('galleries')
         . " WHERE sub_gid = " . $sub_gid . " AND pos= " . $pos);

        $rows = $db->loadRowList();
        if ($rows) {
            foreach ($rows as $row) {
                $gallery = new zmgGallery(&$db);
                $gallery->load($row[0]);
                $ret[]   = $gallery;
            }
        }

        return $ret;
    }

    /**
     * Create a list of all galleries.
     * @param int $parent
     * @param string $ident
     * @param string $ident2
     * @return void
     */
    function &getGalleryList($parent = 0, $indent_l1 = '.', $indent_l2 = '.') {
        if (!is_array($this->_gallerylist)) {
            $db   = & zmgDatabase::getDBO();
            $db->setQuery("SELECT gid FROM " . zmgFactory::getConfig()->getTableName('galleries')
             . " WHERE sub_gid= " . $parent . " ORDER BY pos, "
             . $this->getGalleriesOrdering());

            $rows = $db->loadRowList();
            if ($rows) {
                foreach ($rows as $row) {
                    $gallery = new zmgGallery(&$db);
                    $gallery->load($row[0]);
                    $ret[]   = $gallery;
                    $this->_gallerylist[] = array(
                      'object' => $gallery,
                      'path_name' => $indent_l1 . " - " . $gallery->name,
                      'path_virtual' => $indent_l2 . $gallery->name);
                    $this->getGalleryList($gallery->gid, $indent_l1 . '    ',
                      $indent_l2 . $gallery->name . '    ');
                }
            }
        }

        return $this->_gallerylist;
    }

    function getMedia($gid = 0, $offset = 0, $length = 0, $filter = 0) {
        $filter = intval($filter);
        if ($filter > 0) {
            $gid = $filter;
        }
        $gid = intval($gid);
        $ret = array();

        $db  = & zmgDatabase::getDBO();
        
        $query = "SELECT mid FROM " . zmgFactory::getConfig()->getTableName('media');
        if ($gid === 0) {
            $query .= " ORDER BY gid, " . $this->getMediaOrdering();
        } else {
            $query .= " WHERE gid=$gid ORDER BY " . $this->getMediaOrdering();
        }
        if ($length > 0) {
            $query .= " LIMIT $offset, $length";
        }
        
        $a_gallery = null;
        $a_gallery_dir = "";
        
        $db->setQuery($query);
        $rows = $db->loadRowList();
        if ($rows) {
            foreach ($rows as $row) {
                $medium = new zmgMedium(&$db);
                $gid = intval($row[0]);
                $medium->load($gid);
                if ($a_gallery !== $gid) {
                    $a_gallery = $gid;
                    $a_gallery_dir = $medium->getGalleryDir();
                } else {
                    $medium->gallery_dir = $a_gallery_dir; 
                }
                $ret[] = $medium;
            }
        }

        return $ret;
    }

    function getGallery($gid, $ret_type = 'object') {
        if ($gid === "new") {
        	return zmgGallery::getEmpty($ret_type);
        } else {
        	$gid = intval($gid);
            $gallery = new zmgGallery(zmgDatabase::getDBO());
            $gallery->load($gid);
            if ($ret_type == "json") {
                return $gallery->toJSON();
            } else if ($ret_type == "xml") {
                return $gallery->toXML();
            }
            return $gallery;
        }
    }

    function getMedium($mid, $ret_type = 'object') {
        $mid = intval($mid);
        $medium = new zmgMedium(zmgDatabase::getDBO());
        $medium->load($mid);
        if ($ret_type == "json") {
            return $medium->toJSON();
        } else if ($ret_type == "xml") {
            return $medium->toXML();
        }
        return $medium;
    }

    /**
     * Return the method of ordering for galleries.
     * @return string
     * @access public
     */
    function getGalleriesOrdering() {
        $methods = array("", "ordering, gid ASC", "ordering, gid DESC",
          "ordering, name ASC", "ordering, name DESC");
          
        return $methods[intval(zmgFactory::getConfig()->get('layout/ordering/galleries'))];
    }

    /**
     * Return the method of ordering for media.
     * @return string
     * @access public
     */
    function getMediaOrdering() {
        $methods = array("", "date_add ASC", "date_add DESC", "filename ASC",
          "filename DESC", "name ASC", "name DESC");
          
        return $methods[intval(zmgFactory::getConfig()->get('layout/ordering/media'))];
    }
}

?>
