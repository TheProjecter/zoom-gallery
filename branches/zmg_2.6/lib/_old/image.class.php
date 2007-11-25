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
| Filename: image.class.php                                           |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id: image.class.php 143 2007-03-12 23:49:03Z spignataro $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Gallery class; creates an instance of a medium.
 *
 * @access public
 */
class image extends gallery {
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
	var $_keywords = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_filename = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_descr = null;
	/**
	 * @var datetime
	 * @access private
	 */
	var $_date = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_hits = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_votenum = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_votesum = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_type = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_mimetype = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_thumbnail = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_thumbtype = null;
	/**
	 * @var string
	 * @access private
	 */
	var $_viewsize = null;
    /**
     * @var boolean
     * @access private
     */
    var $_isBroken = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_published = null;
	/**
	 * @var int
	 * @access private
	 */
	var $_catid = null;
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
	var $_size = array();
	/**
	 * @var array
	 * @access private
	 */
	var $_comments = array();
	/**
	 * @var array
	 * @access private
	 */
	var $_members = array();
	/**
	 * @var array
	 * @access private
	 */
	var $_metadata = null;
	/**
	 * Image object contructor.
	 *
	 * @param int $image_id
	 * @return image
	 * @access public
	 */
	function image($image_id){
		$this->_id = intval(zoom::escapeString($image_id));
        $this->_isBroken = false;
	}
	/**
	 * Retrieves data from the 'mos_zoomfiles' table and assigns it to
	 * the class variables...
	 *
	 * @param boolean $galleryview
	 * @return void
	 * @access public
	 */
	function getInfo($galleryview = true) {
		global $database, $mosConfig_absolute_path, $zoom;
		$database->setQuery("SELECT imgid, imgname, imgkeywords, imgfilename, imgdescr, date_format(imgdate, '%Y-%m-%d %H:%i:%s') AS date, imghits, votenum, votesum, published, catid, uid, imgmembers FROM #__zoomfiles WHERE imgid=".$zoom->escapeString($this->_id));
		$this->_result = $database->query();
		$rows = $database->loadObjectList();
		foreach ($rows as $row) {
			$this->_name = stripslashes($row->imgname);
			$this->_keywords = stripslashes($row->imgkeywords);
			$this->_filename = $row->imgfilename;
			$this->_descr = stripslashes($row->imgdescr);
			$this->_date = $row->date;
			$this->_hits = $row->imghits;
			$this->_votenum = $row->votenum;
			$this->_votesum = $row->votesum;
			$this->_published = $row->published;
			$this->_catid = $row->catid;
			$this->_uid = $row->uid;
			$this->_members = explode(",", $row->imgmembers);
			// gallery-members of type 1 are of access-level 'public'
			// and members of type 2 are 'registered'.
			//$this->_members = $members;
		}
		$this->_type = ereg_replace(".*\.([^\.]*)$", "\\1", $this->_filename);
		$this->_type = strtolower($this->_type);
        // check if this image is not broken (deleted/ symlink gone...)
        if (!$zoom->platform->file_exists($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/".$this->_filename)) {
        	$this->_isBroken = true;
            $this->_size = array(48, 48);
        } else {
        	$this->_isBroken = false;
        }
		$this->setThumbnail();
		// get comments of image...
		$this->getComments();
		$this->_viewsize = $this->getViewsize();
		if (!$galleryview) {
			$this->getMetaData();
		}
	}
	/**
	 * Change the data entries of a medium.
	 *
	 * @param string $filename
	 * @param string $name
	 * @param string $keywords
	 * @param string $descr
	 * @param int $catid
	 * @param int $uid
	 * @param int $published
	 * @param array $selections
	 * @return boolean
	 * @access public
	 */
	function setImgInfo($filename="", $name, $keywords, $descr, $catid=0, $uid=0, $published=0, $selections=1) {
	    global $zoom;
		if (!isset($this->_filename) && $filename != "") {
	    	$this->_filename = $filename;
	    	$this->_type = ereg_replace(".*\.([^\.]*)$", "\\1", $this->_filename);
			$this->_type = strtolower($this->_type);
	    }
	    $this->_name = str_replace("'", '&#39;', $zoom->htmlnumericentities($name));
	    $this->_keywords = str_replace("'", '&#39;', $zoom->htmlnumericentities($keywords));
	    $this->_descr = $descr;
	    if (!isset($this->_catid) && $catid != 0) {
	    	$this->_catid = $catid;
	    }
	    if (!isset($this->_uid) && $uid != 0) {
	    	$this->_uid = $uid;
	    }
	    if (!$this->_published && $published == 1) {
	    	$this->_published = 1;
	    } elseif ($this->_published && $published == 0) {
	        $this->_published = 0;
	    }
	    $this->_members = $selections;
	}
	/**
	 * Save a medium to the database.
	 *
	 * @return boolean
	 * @access public
	 */
	function save() {
		global $database;
		$uid = $this->currUID;
		$database->setQuery("INSERT INTO #__zoomfiles (imgfilename, imgname, imgkeywords, imgdescr, imgdate, catid, uid, imgmembers)"
		 . " VALUES "
		 . "('".zoom::escapeString($this->_filename)."', '".zoom::escapeString($this->_name)."', '".zoom::escapeString($this->_keywords)."','".zoom::escapeString($this->_descr)."', NOW(), '".zoom::escapeString($this->_catid)."', '".$this->_uid."', '1')");
		if ($database->query()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Update a medium to the database with newly set variables.
	 *
	 * @param boolean $catimg
	 * @param boolean $parentimg
	 * @return boolean
	 * @access public
	*/
	function update($catimg=0, $parentimg=0) {
		global $database, $zoom;
		if (isset($catimg) && $catimg == 1){
			// set new category image, override old one (if it ever existed)...
			$database->setQuery("UPDATE #__zoom SET catimg = ".$this->_id." WHERE catid=".$zoom->_gallery->_id);
			$database->query();
		}else{
			// unset category image, old setting also overridden...
			$database->setQuery("UPDATE #__zoom SET catimg = NULL WHERE catid=".$zoom->_gallery->_id." AND catimg=".$this->_id);
			$database->query();
		}
		if (isset($parentimg) && $parentimg == 1){
			// set new category image for parent-gallery, override old one (if it ever existed)...
			$database->setQuery("UPDATE #__zoom SET catimg = ".$this->_id." WHERE catid=".$zoom->_gallery->_subcat_id);
			$database->query();
		}else{
			// unset category image of parent gallery, old setting also overridden...
			$database->setQuery("UPDATE #__zoom SET catimg = NULL WHERE catid=".$zoom->_gallery->_subcat_id." AND catimg=".$this->_id);
			$database->query();
		}
		// replace space-character with 'air'...or nothing!
		$this->_keywords = ereg_replace(" ", "", $this->_keywords);
		$database->setQuery("UPDATE #__zoomfiles SET imgname = '".$zoom->escapeString($this->_name)."', imgkeywords='".$zoom->escapeString($this->_keywords)."', imgdescr = '".$zoom->escapeString($this->_descr)."', published = '".$zoom->escapeString($this->_published)."', imgmembers = '".$this->_members."' WHERE imgid=".$this->_id);
		if ($database->query()) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Delete an image from the filesystem and the database.
	 *
	 * @return boolean
	 * @access public
	 */
	function delete() {
		global $database, $mosConfig_absolute_path, $zoom;
		$file1 = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/".$this->_filename;
		if($zoom->isMovie($this->_type))
			$filename2 = ereg_replace("(.*)\.([^\.]*)$", "\\1", $this->_filename).".jpg";
		else
			$filename2 = $this->_filename;
		$file2 = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/thumbs/".$filename2;
		$file3 = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/viewsize/".$this->_filename;
		$error = false;
		if(file_exists($file1)){
			if(!@$zoom->platform->unlink($file1)){
				$error = true;				
			}
		}
		//If image is deleted, delete thumb (if it exists at all)
		if (!$error) {
			if ($zoom->isImage($this->_type) || $zoom->isMovie($this->_type)) {
				if($zoom->platform->file_exists($file2)){// NB: Documents and audiofiles don't have thumbmnails...
					if(!@$zoom->platform->unlink($file2)){
						$error = true;						
					}
				}
			}
		}
		//If thumbnail is deleted, delete the viewsize image (if it exists at all)
		if (!$error) {
			if ($zoom->isImage($this->_type)) {// Only images have viewsize versions of themselves...
				if ($zoom->platform->file_exists($file3)) {
					if (!@$zoom->platform->unlink($file3)) {
						$error = true;
					}
				}
			}
		}
		if (!$error) {
			//Delete record from mos_zoomfiles and comments from mos_zoom_comments
			$database->setQuery("DELETE FROM  #__zoomfiles WHERE imgid=".$this->_id);
			$database->query();
			$database->setQuery("DELETE FROM #__zoom_comments WHERE imgid=".$this->_id);
			$database->query();
			//Purge the EditMon records
			$zoom->EditMon->purgeComments($this->_id, false);
			// check if the image was a category image...
			$database->setQuery("SELECT catid FROM #__zoom WHERE catimg = ".$this->_id." LIMIT 1");
			$this->_result = $database->query();
			if (mysql_num_rows($this->_result) > 0){
				$row = mysql_fetch_object($this->_result);
				$database->setQuery("UPDATE #__zoom SET catimg = NULL WHERE catid = ".$row->catid);
				$database->query();
			}
		}
		if ($error) {
			return false;
		} else {
			return true;
		}
	}
	/**
	 * Set the relative path to a thumbnail.
	 *
	 * @return void
	 * @access public
	 */
	function setThumbnail() {
		global $mosConfig_live_site, $mosConfig_absolute_path, $zoom;
		if ($this->_isBroken) {
			$this->_thumbnail = $mosConfig_live_site."/components/com_zoom/www/images/filetypes/file_broken.png";
			$this->_thumbtype = "png";
		} else {
			$this->_thumbtype = $this->_type;
            if ($zoom->isImage($this->_type)) {
                $this->_thumbnail = $mosConfig_live_site."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/thumbs/".$this->_filename;
            } elseif($zoom->isDocument($this->_type)) {
                if ($this->_type == "pdf") {
                    $this->_thumbnail = $mosConfig_live_site."/components/com_zoom/www/images/filetypes/pdf.png";
                    $this->_thumbtype = "png";
                } else {
                    $this->_thumbnail = $mosConfig_live_site."/components/com_zoom/www/images/filetypes/document.png";
                    $this->_thumbtype = "png";
                }
            } elseif($zoom->isMovie($this->_type)) {
                if ($zoom->isThumbnailable($this->_type)) {
                    $file = ereg_replace("(.*)\.([^\.]*)$", "\\1", $this->_filename).".jpg";
                    if ($zoom->platform->file_exists($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/thumbs/".$file)) {
                        $this->_thumbnail = $mosConfig_live_site."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/thumbs/".$file;
                        $this->_thumbtype = "jpg";
                    } else {
                        $this->_thumbnail = $mosConfig_live_site."/components/com_zoom/www/images/filetypes/video.png";
                        $this->_thumbtype = "png";
                    }
                } else {
                    $this->_thumbnail = $mosConfig_live_site."/components/com_zoom/www/images/filetypes/video.png";
                    $this->_thumbtype = "png";
                }
            } elseif($zoom->isAudio($this->_type)) {
                $this->_thumbnail = $mosConfig_live_site."/components/com_zoom/www/images/filetypes/audio.png";
                $this->_thumbtype = "png";
            }
		}
	}
	/**
	 * Get the relative path to a medium viewsize image.
	 *
	 * @return boolean
	 * @access public
	 */
	function getViewsize() {
		global $mosConfig_absolute_path, $zoom;
		if ($zoom->platform->file_exists($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/viewsize/".$this->_filename)) {
			$this->_viewsize = "viewsize/".$this->_filename;
			return $this->_viewsize;
		} else {
			$this->_viewsize = $this->_filename;
			return $this->_filename;
		}
	}
	/**
	 * Check if a medium has been resized while it was uploaded.
	 *
	 * @return boolean
	 * @access public
	 */
	function isResized() {
		if (strstr($this->_viewsize, 'viewsize/')) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Get the comments a medium contains.
	 *
	 * @return void
	 * @access public
	 */
	function getComments() {
		global $database;
		$this->_comments = null;
		$database->setQuery('SELECT cmtid FROM #__zoom_comments WHERE imgid = '.$this->_id.' ORDER BY cmtdate ASC');
		$this->_result = $database->query();
		while ($row = mysql_fetch_object($this->_result)) {
			$comment = new comment($row->cmtid);
			$this->_comments[] = $comment;
		}
	}
	/**
	 * Get the metadata (i.e. id3 tag information) of a medium.
	 *
	 * @return void
	 * @access public
	 */
	function getMetaData() {
		global $mosConfig_absolute_path, $zoom;
        if (!$this->_isBroken) {
        	$file = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$this->getDir()."/".$this->_filename;
            if ($zoom->isImage($this->_type)) {
                $this->_size = $zoom->platform->getimagesize($file);
            } elseif (($zoom->isAudio($this->_type) || $zoom->isMovie($this->_type)) && $zoom->_CONFIG['readID3']) {
                $this->_metadata = $zoom->toolbox->getid3($file);
            }
        }
	}
	/**
	 * Update the hit counter of a medium
	 *
	 * @return void
	 * @access public
	 */
	function hitPlus() {
		global $database;
		$database->setQuery("UPDATE #__zoomfiles SET imghits=imghits+1 WHERE imgid=".$this->_id);
		$database->query();
		$this->_hits++;
	}
	/**
	 * Update the votes of a medium with a new vote casted by a user.
	 *
	 * @param int $vote
	 * @return void
	 * @access public
	 */
	function rateImg($vote) {
		global $database, $zoom;
		if (!$zoom->EditMon->isEdited($this->_id, 'vote') && $vote >= 0) {
			$database->setQuery("UPDATE #__zoomfiles SET votesum=votesum+$vote, votenum=votenum+1 WHERE imgid=".$this->_id);
			$database->query();
			$zoom->EditMon->setEditMon($this->_id, 'vote');
			$this->_votesum = $this->_votesum + $vote;
			$this->_votenum++;
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Check if this image has already been rated and draw the stars accordingly.
	 *
	 * @return string
	 */
	function getStars() {
	    global $zoom;
	    $html = "";
	    if (!$zoom->EditMon->isEdited($this->_id, 'vote')) {
	    	$html = $this->_drawVotingStars($this->_votenum, $this->_votesum);
	    } else {
	        $html = $this->_drawStars($this->_votenum, $this->_votesum);
	    }
	    return $html;
	}
	/**
	 * Check if this image has already been rated and draw the stars accordingly.
	 *
	 * @return string
	 */
	function getStaticStars() {
	    return $this->_drawStars($this->_votenum, $this->_votesum);
	}
	/**
	 * Draw the stars
	 *
	 * @param int $votes
	 * @param int $points
	 * @return string
	 */
	function _drawStars($votes, $points) {
		if ($votes > 0) {
			$rate = $points / $votes;
		} else {
			$rate = 0;
		}
		//$html = '<div class="PSR_container" onmouseout="Zoom.PSR_star_out(this)">';
		$html = '<div id="unit_long'.$this->_id.'">
		<ul class="unit-rating">
		<li class="current-rating" style="list-style:none;width:'.(@number_format($rate,2)*15).'px;"><span class="hide">'.@number_format($rate,2).'</span></li>';
		for ($ncount = 1; $ncount <= 5; $ncount++) {
			$html .= '<li class="starli" style="list-style:none;"><span title="'.constant('_ZOOM_RATE'.$ncount).'" class="r'.$ncount.'-unit"><span class="hide">'.$ncount.'</span></span></li>';
        }
		$ncount = 0; // resets the count
		$html .= '</ul>
		<strong> '.@number_format($rate, 2).'</strong>/ '.$votes.' '.(($this->_votenum == 1) ? _ZOOM_VOTE : _ZOOM_VOTES).'
		</div>';
		return $html;
	}

	/**
	 * Draw the voting stars
	 *
	 * @param int $votes
	 * @param int $points
	 * @return string
	 */
	function _drawVotingStars($votes, $points) {
		if ($votes > 0) {
			$rate = $points / $votes;
		} else {
			$rate = 0;
		}
		//$html = '<div class="PSR_container" onmouseout="Zoom.PSR_star_out(this)">';
		$html = '<div id="unit_long'.$this->_id.'">
		<ul class="unit-rating">
		<li class="current-rating" style="list-style:none; width:'.(@number_format($rate,2)*15).'px;"><span class="hide">'.@number_format($rate,2).'</span></li>';
		for ($ncount = 1; $ncount <= 5; $ncount++) {
			$html .= '<li class="starli" style="list-style:none;"><a href="javascript:Zoom.unit_save_vote(\''.$this->_id.'\', \''.$ncount.'\')" title="'.constant('_ZOOM_RATE'.$ncount).'" class="r'.$ncount.'-unit"><span class="hide">'.$ncount.'</span></a></li>';
        }
		$ncount = 0; // resets the count
		$html .= '</ul>
		<strong> '.@number_format($rate, 2).'</strong>/ '.$votes.' '.(($this->_votenum == 1) ? _ZOOM_VOTE : _ZOOM_VOTES).'
		</div>';
		return $html;
	}
	/**
	 * Add a comment to the medium.
	 *
	 * @param string $uname
	 * @param string $comment
	 * @return void
	 * @access public
	 */
	function addComment($uname, $comment) {
		global $database, $zoom, $Itemid, $catid, $key, $mainframe, $mosConfig_live_site;
		$comment = str_replace("'", "&#39;", $comment);
		if (!$zoom->EditMon->isEdited($this->_id, 'comment')) {
			$uname = $zoom->cleanString($uname);
			$comment = $zoom->cleanString($comment);
			if (strlen($comment) > $zoom->_CONFIG['cmtLength']) {
				$comment = substr($comment, 0, $zoom->_CONFIG['cmtLength']-4)."...";
			}
			$database->setQuery("INSERT INTO #__zoom_comments (imgid,cmtname,cmtcontent,cmtdate) VALUES ('".$this->_id."','".$zoom->escapeString($uname)."','".$zoom->escapeString($comment)."', NOW() )");
			$database->query();
			$zoom->EditMon->setEditMon($this->_id, 'comment');
			$subject = "New comments added!";
			$body = "Name: ".$uname."\n\nComment: ".$comment."\n\nLink: ".sefReltoAbs($mosConfig_live_site."/index.php?option=com_zoom&Itemid=".$Itemid."&page=view&catid=".$catid."&key=".$key);
			$database->setQuery("SELECT b.email AS email FROM #__zoomfiles a, #__users b WHERE a.imgid=".$this->_id." AND a.uid=b.id");
			$result = $database->loadResult();
			$sentmail = mosMail('', '', $result, $subject, $body);
			
            echo "<script language=\"JavaScript\" type=\"text/JavaScript\"> alert('" . html_entity_decode( _ZOOM_ALERT_COMMENTOK ) . "'); </script>";
		} else {
			echo "<script language=\"JavaScript\" type=\"text/JavaScript\"> alert('" . html_entity_decode( _ZOOM_ALERT_COMMENTERROR ) . "'); </script>";
		}
		// reload/ refill comments array...
		$this->getComments();
	}
	/**
	 * Delete a comment
	 *
	 * @param int $delComment
	 * @return void
	 * @access public
	 */
	function delComment($delComment) {
		global $database, $zoom;
		$database->setQuery("DELETE FROM #__zoom_comments WHERE cmtid=".intval(zoom::escapeString($delComment)));
		$database->query();
		$zoom->EditMon->purgeComments($this->_id);
		$this->getComments();
	}
	/**
	 * Check if a medium is the gallery-image of its parent gallery.
	 *
	 * @param int $parent_id
	 * @return boolean
	 * @access public
	 */
	function isParentImage($parent_id) {
		global $database;
		$database->setQuery("SELECT catid FROM #__zoom WHERE catimg=".$this->_id);
		$this->_result = $database->query();
		while ($cat = mysql_fetch_object($this->_result)) {
			if ($cat->catid == $parent_id) {
				return true;
			}
		}
		return false;
	}
	/**
	 * Check if a medium is published
	 *
	 * @return boolean
	 * @access public
	 */
	function isPublished() {
		if ($this->_published == 1) {
			return true;
		} else {
			return false;
		}
	}
	/**
	* Get the number of comments a medium contains.
	*
	* @return int
	* @access public
	*/
	function getNumOfComments(){
		return count($this->_comments);
	}
	/**
	* Get the directory of the gallery a medium resides in.
	*
	* @return string
	* @access public
	*/
	function getDir(){
		global $database;
		$database->setQuery("SELECT catdir FROM #__zoom WHERE catid=".$this->_catid);
		$catdir = $database->loadResult();
		return $catdir;
	}
	/**
	* Get the keywords of a medium in a HTML formatted string.
	*
	* @param int $method
	* @return string
	* @access public
	*/
	function getKeywords($method = 1) {
		global $Itemid, $zoom;
		// This function will return the list of keywords in two methods:
		// 1: plain list (comma seperated) of keywords.
		// 2: each keyword as hyperlink to the search-page...a search for
		//    items matching that keyword will be conducted automatically.
		if ($method == 1) {
			return $this->_keywords;
		} elseif ($method == 2) {
			// used for display purposes only (media view)...
			$keywords = explode(", ", $this->_keywords);
			$list = "";
			$counter = 0;
			foreach ($keywords as $keyword) {
				if ($counter != 0) {
					$list .= ", ";
				}
				if ($zoom->_CONFIG['popUpImages']) {
					$list .= "<a href=\"javascript:void(0);\" onclick=\"searchKeyword('".$keyword."')\">".$keyword."</a>";
				} else {
					$keyword_uri = sefRelToAbs("index.php?option=com_zoom&amp;page=search&amp;type=quicksearch&amp;sstring=".$keyword."&amp;Itemid=".$Itemid."");
					$list .= "<a href=\"".$keyword_uri."\">".$keyword."</a>";
				}
				$counter++;
			}
			return $list;
		}
	}
	/**
	 * Get the username of the user who uploaded a medium
	 *
	 * @param int $method
	 * @return string
	 * @access public
	 */
	function getUsername($method = 1) {
	    global $database, $Itemid, $zoom;
	    $database->setQuery("SELECT username FROM #__users WHERE id = $this->_uid LIMIT 1");
	    $user = $database->loadResult();
	    $username = "";
	    if ($method == 1) {
		    $username = $user;
	    } elseif ($method == 2) {
		    if ($zoom->_CONFIG['popUpImages']) {    		
			    $username = "<a href=\"javascript:void(0);\" onclick=\"searchKeyword('".$user."')\">".$user."</a>";
		    } else {
		    	$username_uri = sefRelToAbs("index.php?option=com_zoom&amp;page=search&amp;type=quicksearch&amp;sstring=".$user."&amp;Itemid=".$Itemid."");
			    $username = "<a href=\"".$username_uri."\">".$user."</a>";
		    }
	    }
	    return $username;
	}
	/**
	 * Figure out the MIME type of this medium
	 *
	 * @param string $user_path
	 * @return string
	 * @access public
	 */
	function getMimeType($user_type = null, $user_path = null) {
	    if (empty($this->_mimetype)) {
	    	if (empty($user_path)) {
    	    	global $mosConfig_absolute_path, $zoom;
    	    	$user_path = $mosConfig_absolute_path . "/" . $zoom->_CONFIG['imagepath'] . $this->getDir() . "/" . $this->_filename;
    	    }
    	    //$this->_mimetype = MIME_Type::autoDetect($user_path, $user_type, $this->_type);
    	    if (!$this->_mimetype | empty($this->_mimetype)) {
    	    	$ext = ereg_replace(".*\.([^\.]*)$", "\\1", $this->_filename);
    	    	$this->_mimetype = MIME_Helper::convertExtensionToMime($ext);
    	    }
    	    return $this->_mimetype;
	    } else {
	    	return $this->_mimetype;
	    }
	}
	/**
	 * Check if a user may see the a medium.
	 *
	 * @param boolean $popup
	 * @return boolean
	 * @access public
	 */
	function isMember($popup = false) {
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
                    //echo "DEBUG: ".$zoom->currGID." >= ".$m_gid."<br />";
                    if ($zoom->currGID >= $m_gid) {
                        $res = true;
                    }
                }
            }
            return $res;
	    } 
	}
}