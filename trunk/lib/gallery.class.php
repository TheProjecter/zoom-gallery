<?php
//zOOm Media Gallery//
/** 
-----------------------------------------------------------------------
|  zOOm Media Gallery! by Mike de Boer - a multi-gallery component    |
-----------------------------------------------------------------------

-----------------------------------------------------------------------
|                                                                     |
| Author: Mike de Boer, <http://www.mikedeboer.nl>                    |
| Copyright: copyright (C) 2007 by Mike de Boer                       |
| Description: zOOm Media Gallery, a multi-gallery component for      |
|              Joomla!. It's the most feature-rich gallery component  |
|              for Joomla!! For documentation and a detailed list     |
|              of features, check the zOOm homepage:                  |
|              http://www.zoomfactory.org                             |
| License: GPL                                                        |
| Filename: gallery.class.php                                         |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:gallery.class.php 113 2007-02-11 20:56:11Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Gallery class; creates an instance of a gallery.
 *
 * @access public
 */
class gallery extends zoom {
	/**
	 * @var int
	 * @access private
	 */
	var $_id = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_name = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_descr = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_dir = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_cat_img = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_password = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_keywords = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_subcat_id = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_pos = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_custom_order = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_hideMsg = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_shared = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_published = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_uid = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_sql = null;
	/**
	 * @var object
	 * @access private
	 */
	var $_result = null;
	/**
	 * @var array
	 * @access private
	 */
	var $_subcats = array();
	/**
	 * @var array
	 * @access private
	 */
	var $_images = array();
	/**
	 * @var array
	 * @access private
	 */
	var $_members = array();
	
	/**
	 * Gallery object contructor.
	 *
	 * @param int $gallery_id
	 * @param boolean $galleryview
	 * @return gallery
	 * @access public
	 */
	function gallery($gallery_id, $galleryview = false) {
		$this->_id = intval($this->escapeString($gallery_id));
		$this->_getInfo($galleryview);
		if (!$galleryview) {
			// getting sub-categories (sub-galleries...)
			$this->_getSubCats($galleryview);
			// getting gallery images...
			$this->_getImages($galleryview);
		}
	}
	/**
	 * Retrieves data from the 'mos_zoom' table and assigns it to
	 * the class variables...
	 *
	 * @param boolean $galleryview
	 * @access private
	 */
	function _getInfo($galleryview = false) {
		global $database;
		$database->setQuery("SELECT catid, catname, catdescr, catdir, catimg, subcat_id, catpassword, catkeywords, pos, custom_order, hideMsg, shared, published, uid, catmembers FROM #__zoom WHERE catid=".$this->_id." LIMIT 1");
		$this->_result = $database->query();
		$rows = $database->loadObjectList();
		if (count($rows) > 0){
		    foreach ($rows as $row) {
				$this->_name = utf8_decode($this->stripslashesSafe($row->catname));
				$this->_descr = utf8_decode($this->stripslashesSafe($row->catdescr));
				$this->_dir = $row->catdir;
				$this->_cat_img = $row->catimg;
				$this->_subcat_id = $row->subcat_id;
				$this->_password = $row->catpassword;
				$this->_keywords = $row->catkeywords;
				$this->_pos = $row->pos;
				$this->_custom_order = $row->custom_order;
				$this->_hideMsg = $row->hideMsg;
				$this->_shared = $row->shared;
				$this->_published = $row->published;
				$this->_uid = $row->uid;
				$members = explode(",", $row->catmembers);
				// gallery-members of type 1 are of access-level 'public'
				// and members of type 2 are 'registered'.
				if (in_array("1", $members)) {
					$this->_members[0] = 1;
				} elseif(in_array("2", $members)) {
					$this->_members[0] = 2;
				} else {
					$this->_members = $members;
				}
			}
		} else {
			die("This is not a valid gallery-id. Please return to the homepage and try again.");
		}
	}
	/**
	 * Get the sub-galleries of a gallery from the 'mos_zoom' table.
	 *
	 * @param boolean $galleryview
	 * @access private
	 */
	function _getSubCats($galleryview = false) {
		global $database, $zoom;
		$orderMethod = $zoom->getCatOrderMethod();
		$database->setQuery("SELECT catid FROM #__zoom WHERE subcat_id=".$this->_id." ORDER BY ".$orderMethod);
		$this->_result = $database->query();
		while ($row = mysql_fetch_object($this->_result)) {
			if ($galleryview == true) {
				$subcat = new stdClass();
			} else {
				$subcat = new gallery($row->catid, true);
			}
			$this->_subcats[] = $subcat;
		}
	}
	/**
	 * Get the images of a gallery from the 'mos_zoomfiles' table.
	 *
	 * @param boolean $galleryview
	 * @access private
	 */
	function _getImages($galleryview = false) {
		global $database, $zoom;
		$orderMethod = $zoom->getOrderMethod();
		if($zoom->_isAdmin) {
			$database->setQuery("SELECT imgid FROM #__zoomfiles WHERE catid = ".$this->_id." ORDER BY ".$orderMethod);
		} else {
			$database->setQuery("SELECT imgid FROM #__zoomfiles WHERE catid = ".$this->_id." AND published = 1 ORDER BY ".$orderMethod);
		}
		$this->_result = $database->query();
		while ($row = mysql_fetch_object($this->_result)) {
			if ($galleryview == true) {
				$image = new stdClass();
			} else {
				$image = new image($row->imgid);
			}
			$this->_images[] = $image;
		}
	}
	/**
	 * Change the gallery (sample-)image to a different image.
	 *
	 * Quick explanation:
	 *  - first, the function checks if a category-image
	 *    has been set for this gallery.
	 *    If not, and the gallery is one at the FIRST level
	 *    (one of the main galleries), the first image present
	 *    in the gallery is returned...
	 *  - second, the function retrieves the filename and CORRECT
	 *    directory of the category image from the database (in
	 *    case the category image is from a higher level gallery)
	 *    and returns the complete path in the end!
	 *
	 * @return void
	 * @access public
	 */
	function setCatImg() {
		global $database, $zoom;
		if (isset($this->_cat_img) && !empty($this->_cat_img)) {
			$this->_cat_img = new image($this->_cat_img);
			$this->_cat_img->getInfo();
			$this->_cat_img->_thumbnail = $zoom->hotlinkImage($this->_cat_img->_catid, '2', $this->_cat_img->_id, null );
		} else {
			$database->setQuery("SELECT pos FROM #__zoom WHERE catid = ".$this->_id." LIMIT 1");
			$this->_result = $database->query();
			$row = mysql_fetch_object($this->_result);
			//if ($row->pos == 0) {
				$database->setQuery("SELECT imgid FROM #__zoomfiles WHERE catid=".$this->_id." ORDER BY ".$zoom->getOrderMethod()." LIMIT 1");
				$this->_result = $database->query();
				$xxx = mysql_fetch_object($this->_result);
				if (isset($xxx->imgid) && !empty($xxx->imgid)) {
					$this->_cat_img = new image($xxx->imgid);
					$this->_cat_img->getInfo();
				    $this->_cat_img->_thumbnail = $zoom->hotlinkImage($this->_cat_img->_catid, '2', $this->_cat_img->_id, null );
				}
			//}
		}
		if (empty($this->_cat_img)) {
			$this->_cat_img = new image(0);
		}
	}
	/**
	 * Move this gallery up or down the hierarchial list of galleries.
	 *
	 * @param int $to
	 * @return boolean
	 * @access public
	 */
	function move($to = 0) {
		global $database, $zoom;
		$reordered  = true;
		
		if ($to != 0) {
		    // first, check if the target gallery has any children of its own, because in that case: dhtmlXTree places
		    // this gallery as a CHILD of the target inside the tree!
		    $database->setQuery("SELECT catid FROM #__zoom WHERE subcat_id = $to ORDER BY custom_order, ".$zoom->getCatOrderMethod()." LIMIT 1");
    		$result1 = $database->query();
		    while($row1 = mysql_fetch_object($result1)) {
		        $to = $row1->catid;
		        break;
		    }
    		// now it's safe to create a gallery object...
			$to_gallery =& new gallery($to);
			if ($this->_subcat_id != $to_gallery->_subcat_id) {
    		    // the target gallery has a different parent gallery than this one, so it has been moved (apparently).
    		    $this->_subcat_id = $to_gallery->_subcat_id;
    		    $this->_pos = $to_gallery->_pos;
    		    $this->_custom_order = "0";
    			$database->setQuery("UPDATE #__zoom SET subcat_id='".$this->_subcat_id."', pos='".$this->_pos."', custom_order='0' WHERE catid=".$this->_id);
    			//echo "\n".$database->_sql;
        		if (!$database->query()) {
        			$reordered = false;
        		}
    		}
		} else {
		    $this->_subcat_id = 0;
		    $this->_pos = 0;
		    $this->_custom_order = "0";
			$database->setQuery("UPDATE #__zoom SET subcat_id='".$this->_subcat_id."', pos='".$this->_pos."', custom_order='0' WHERE catid=".$this->_id);
			//echo "\n".$database->_sql;
    		if (!$database->query()) {
    			$reordered = false;
    		}
		}
		
		if ($reordered) {
			// we need to fetch all galleries that share a common 'pos' (ie. position in the hierarchy)
    		// to set all their 'custom_order's
    		$database->setQuery("SELECT catid, custom_order FROM #__zoom WHERE pos = $this->_pos AND subcat_id = ".$this->_subcat_id." ORDER BY custom_order, ".$zoom->getCatOrderMethod());
    		$result2 = $database->query();
    		$new_order = 1;
    		while ($row2 = mysql_fetch_object($result2)) {
    			if ($row2->catid != $this->_id) {
    			    $database->setQuery("UPDATE #__zoom SET custom_order = $new_order WHERE catid = $row2->catid");
					if (!$database->query()) {
						$reordered = false;
					}
					$new_order++;
    				if ($row2->catid == $to) {
    				 	$this->_custom_order = $new_order;
    					$database->setQuery("UPDATE #__zoom SET custom_order = $this->_custom_order WHERE catid = $this->_id");
    					if (!$database->query()) {
    						$reordered = false;
    					}
    					$new_order++;
    				}
    			}
    		}
		}
		
		return $reordered;
		
	}
	/**
	 * Change a gallery state to 'published'.
	 *
	 * @return boolean
	 * @access public
	 */
	function publish() {
		global $database;
		$database->setQuery("UPDATE #__zoom SET published = 1 WHERE catid = ".$this->_id);
		if ($database->query()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Change a gallery state to 'not published'.
	 *
	 * @return boolean
	 * @access public
	 */
	function unPublish(){
		global $database;
		$database->setQuery("UPDATE #__zoom SET published = 0 WHERE catid = ".$this->_id);
		if ($database->query()) {
			return true;
		}else{
			return false;
		}
	}
	/**
	 * Change a gallery state to 'shared'
	 *
	 * @return boolean
	 * @access public
	 */
	function share() {
		global $database;
		$database->setQuery("UPDATE #__zoom SET shared = 1 WHERE catid = ".$this->_id);
		if ($database->query()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Change a gallery state to 'not shared'.
	 *
	 * @return boolean
	 * @access public
	 */
	function unShare() {
		global $database;
		$database->setQuery("UPDATE #__zoom SET shared = 0 WHERE catid = ".$this->_id);
		if ($database->query()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Get the name of a sub-gallery.
	 *
	 * @return void
	 * @access public
	 */
	function getSubcatName() {
		global $database;
		$database->setQuery("SELECT catname FROM #__zoom WHERE catid=".$this->_subcat_id." LIMIT 1");
		$this->_result = $database->query();
		while ($row = mysql_fetch_object($this->_result)) {
			return $row->catname;
		}
	}
	/**
	 * Get the keywords of a gallery.
	 *
	 * @return void
	 * @access public
	 */
	function getKeywords() {
		global $database;
		$database->setQuery("SELECT catkeywords FROM #__zoom WHERE catid = ".$this->_id." LIMIT 1");
		if ($result = $database->query()) {
			$row = mysql_fetch_object($result);
			$this->_keywords = $row->catkeywords;
		}
	}
	/**
	 * Get the virtual path (eg. '>Gallery1 > Gallery2 > Gallery 3') of a gallery.
	 *
	 * @return string
	 * @access public
	 */
	function getCatVirtPath() {
		global $database;
		// first, get the pos of this category
		$pos = $this->_pos;
		$subcat_id = $this->_subcat_id;
		if ($pos==0 && $subcat_id==0) {
			return ">&nbsp;".$this->_name;
		} else {
			// second, get the different subcat-names in an array...
			$catnames = array();
			$i = $pos;
			while ($i>=1) {
				$database->setQuery("SELECT catname, subcat_id FROM #__zoom WHERE catid = ".$subcat_id." LIMIT 1");
				$this->_result = $database->query();
				$row2 = mysql_fetch_object($this->_result);
				$subcat_id = $row2->subcat_id;
				$catnames[$i] = $row2->catname;
				$i--;
			}
			// third, place the array (dirs) in a single return-string
			$retname = "";
			$i = 1;
			while ($i<=$pos) {
				if($i==1)
					$retname .= ">&nbsp;".$catnames[$i];
				else
					$retname .= "&nbsp;>&nbsp;".$catnames[$i];
				$i++;
			}
			return $retname."&nbsp;>&nbsp;".$this->_name;
		}
	} 
	/**
	 * Get the number of images that a gallery contains.
	 *
	 * @return int
	 * @access public
	 */
	function getNumOfImages() {
		global $database, $zoom;
		if (isset($this->_images) && !empty($this->_images)) {
			return count($this->_images);
		} else {
			// double check if there are no media in gallery AND resource saver for gallery-view!
			if($zoom->_isAdmin) {
				$database->setQuery("SELECT COUNT(imgid) AS no FROM #__zoomfiles WHERE catid = ".$this->_id." LIMIT 1");	
			}
			else {
				$database->setQuery("SELECT COUNT(imgid) AS no FROM #__zoomfiles WHERE catid = ".$this->_id." AND published=1 LIMIT 1");		
			}
			$result = $database->query();
			while ($row = mysql_fetch_object($result)) {
				return $row->no;
			}
		}
	}
	/**
	 * Get the number of images viewable by a user that a gallery contains.
	 *
	 * @return int
	 * @access public
	 */
	function getNumOfImagesUSER() {
		global $database, $zoom, $my, $backend;
		// double check if there are no media in gallery AND resource saver for gallery-view!
		if($zoom->_isAdmin) {
			$database->setQuery("SELECT imgmembers FROM #__zoomfiles WHERE catid = ".$this->_id);	
		}
		else {
			$database->setQuery("SELECT imgmembers FROM #__zoomfiles WHERE catid = ".$this->_id." AND published=1");		
		}
		$rows = $database->loadObjectList();
		$counter = 0;
		foreach ($rows as $row) {
            $members = explode(",", $row->imgmembers);
            if ($zoom->currGID) {
                $registered = true;
            } else {
                $registered = false;
            }
    		// Public / Registered User / ID of user / Admin
    		//0 CANNOT read null
            if ($my->id == "0") {
                $id = "zip";		
    	    } else {
                $id = $my->id;
    		}
    	    if (in_array("1", $members) || (in_array("2", $members) && $registered)
    	      || in_array($id, $members) || $zoom->_isAdmin) {
                $counter++;
    		} else {
                foreach ($members as $member) {
                    if (strstr($member, "gid-")) {
                        $m_gid = intval(str_replace("gid-", "", $member));
                        if ($zoom->currGID >= $m_gid) {
                            $counter++;
                            break;
                        }
                    }
                }
    	    } 
    	}
    	return $counter;
    }
	/**
	 * Get the number of sub-galleries a gallery contains.
	 *
	 * @return int
	 * @access public
	 */
	function getNumOfSubCats() {
		global $database;
		if (isset($this->_subcats) && !empty($this->_subcats)) {
			return count($this->_subcats);
		} else {
			$database->setQuery("SELECT COUNT(catid) AS no FROM #__zoom WHERE subcat_id = ".$this->_id." LIMIT 1");
			$result = $database->query();
			while ($row = mysql_fetch_object($result)) {
				return $row->no;			
			}
		}
	}
	/**
	 * Get the directory on the filesystem of a gallery
	 *
	 * @return string
	 * @access public
	 */
	function getDir() {
		return $this->_dir;
	}
	/**
	 * Check if an entered password of a password-protected gallery is correct.
	 *
	 * @param string $pass
	 * @return boolean
	 * @access public
	 */
	function checkPassword($pass) {
		global $zoom;
		$pass = md5($pass);
		if ($zoom->EditMon->isEdited($this->_id, 'pass')) {
			return true;
		} elseif ($this->_password == $pass) {
			$zoom->EditMon->setEditMon($this->_id, 'pass');
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Check if a gallery is shared (ie. if users may upload media to it).
	 *
	 * @return boolean
	 * @access public
	 */
	function isShared() {
		if($this->_shared == 1) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Check is a gallery is published.
	 *
	 * @return boolean
	 * @access public
	 */
	function isPublished() {
		if($this->_published == 1) {
			return true;
		} else {
			return false;
		}
	}
    /**
     * Check if a user may see the contents of a gallery.
     *
     * @return boolean
     * @access public
     */
    function isMember() {
        global $zoom, $my;
        if ($zoom->currGID) {
		  $registered = true;
        } else {
		  $registered = false;
        }
        // Don't forget strict mode looking for $my->id.  It will be 0 for unregistered!
    	if (in_array("1", $this->_members) || (in_array("2", $this->_members) && $registered) || in_array($my->id, $this->_members, true) || $zoom->_isAdmin) {
    		return true;
        } else {
        	$res = false;
        	foreach ($this->_members as $member) {
        		if (strstr($member, "gid-")) {
        			$m_gid = intval(str_replace("gid-", "", $member));
                    if ($zoom->currGID >= $m_gid) {
                    	$res = true;
                    }
        		}
        	}
            return $res;
    	}
    }
	/**
	 * Get an image by it's id instead of the usual array key.
	 *
	 * @param int $id
	 * @return image
	 * @access public
	 */
	function getImage($id) {
		foreach ($this->_images as $image) {
			if ($image->_id == $id) {
				return $image;
			}
		}
	}
	/**
	 * Get the array key of an image by it's id.
	 *
	 * @param int $id
	 * @return int
	 * @access public
	 */
	function getImageKey($id) {
		$count = 0;
		foreach ($this->_images as $image) {
			if ($image->_id == $id) {
				return $count;
			} else {
				$count++;
			}
		}
	}
	/**
	 * Return the data of this object in an XML formatted stream
	 *
	 * @return string in XML format
	 * @access public
	 */
	function toXML($list_subcats = false, $list_media = false) {
	    global $database, $acl;
       $this->getKeywords();
		$xml = ("<gallery id=\"".$this->_id."\" "
	     . "name=\"". $this->_name."\" "
	     . "dir=\"".$this->_dir."\" "
	     . "cat_img=\"".$this->_cat_img."\" "
	     . "password=\"".$this->_password."\" "
	     . "keywords=\"".$this->_keywords."\" "
	     . "subcat_id=\"".$this->_subcat_id."\" "
	     . "pos=\"".$this->_pos."\" "
	     . "custom_order=\"".$this->_custom_order."\" "
	     . "hide_msg=\"".$this->_hideMsg."\" "
	     . "shared=\"".$this->_shared."\" "
	     . "published=\"".$this->_published."\" "
	     . "uid=\"".$this->_uid."\">");
	    if (!empty($this->_subcats) && count($this->_subcats) > 0 && $list_subcats) {
	    	$xml .= ("<subcats>");
    	    foreach ($this->_subcats as $subcat) {
    	       $xml .= $subcat->toXML()."\n";
    	    }
    	    $xml .= ("</subcats>");
	    }
	    if (!empty($this->_images) && count($this->_images) > 0 && $list_media) {
	    	$xml .= ("<media>");
    	    foreach ($this->_images as $image) {
    	        $xml .= $image->toXML()."";
    	    }
    	    $xml .= ("</media>");
	    }
	    $members_arr = array();
	    foreach ($this->_members as $member) {   	    
            $type = split("-", $member);
	        if ($member == 1 || $member == 2) {
	        	$members_arr[] = "<member id=\"".$member."\">none</member>";
	        } else if ($type[0] == "gid") {
                $members_arr[] = "<member id=\"".$member."\" username=\"".$acl->get_group_name($type[1])."\">".$acl->get_group_name($type[1])."</member>";
            } else {
    	    	$database->setQuery("SELECT name, username FROM #__users WHERE id=$member LIMIT 1");
    	    	$this->_result = $database->query();
    	    	$rows = $database->loadObjectList();
    	    	if (count($rows) == 1) {
    	    		foreach ($rows as $row) {
    	    			$members_arr[] = "<member id=\"".$member."\" username=\"".$row->username."\">".$row->name."</member>";
    	    		}
    	    	}
	        }
	    }
	    $xml .= ("<members>".implode("", $members_arr)."</members><![CDATA["
	     . $this->_descr . "]]></gallery>");
	    return $xml;
	}
}