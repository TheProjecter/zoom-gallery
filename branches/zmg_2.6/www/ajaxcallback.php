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
| Filename: ajaxcallback.php                                          |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:ajaxcallback.php 125 2007-02-17 04:51:16Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// Turn off Magic quotes runtime, because it interferes with saving info to the 
// database and vice versa.
define( "_VALID_MOS", 1 );
require_once('../../../globals.php');
include_once('../../../configuration.php');
include_once("../lib/inserts.class.php");

// Update the Edit Monitor, eg. delete unnecessary rows
$zoom->EditMon->updateEditMon();

//start processing the AJAX callback:
$id = $zoom->getParam($_REQUEST, 'id', 0);
if (md5($zoom->_CONFIG['secret']) == $id) {
	
    $task = $zoom->getParam($_REQUEST, 'task');
    if (!in_array($task,array('view_vote','view_lightbox')) && !$zoom->_isAdmin ) {
        die('Invalid Request!!');
    }
    
    echo $zoom->startXML($task);
	switch ($task) {
	    case 'view_vote' :
	       $imgid = intval($zoom->getParam($_REQUEST, 'imgid'));
	       $vote  = intval($zoom->getParam($_REQUEST, 'vote', 0));
	       if (!empty($imgid)) {
	       	  $img = new image($imgid);
	       	  if ($img->rateImg($vote)) {
	       	  	  echo $zoom->callbackResult(html_entity_decode(_ZOOM_ALERT_VOTE_OK));
	       	  } else {
	       	      echo $zoom->callbackResult(html_entity_decode(_ZOOM_ALERT_VOTE_ERROR), true);
	       	  }
	       }
	       break;
	    case 'view_lightbox' :
	       $imgid = intval($zoom->getParam($_REQUEST, 'imgid'));
	       $type = intval($zoom->getParam($_REQUEST, 'type'));
	       $result = true;
	       if (!empty($imgid) && $imgid > 0) {
	       	  if (!$_SESSION['lightbox']->addItem($imgid, $type)) {
	       	  	 $result = false;
	       	  }
	       } else {
	           $result = false;
	       }
	       if ($result) {
	           echo $zoom->callbackResult(html_entity_decode(_ZOOM_LIGHTBOX_ADDED));
	       } else {
	           echo $zoom->callbackResult(html_entity_decode(_ZOOM_LIGHTBOX_NOTADDED), true);
	       }
	       break;
	    case 'catsmgr_reorder' :
	       $newlist = $zoom->getParam($_REQUEST, 'cats_list');
	       if (is_array($newlist)) {
		       $i = 1;
		       foreach ($newlist as $catid) {
		           $database->setQuery("UPDATE #__zoom SET custom_order = '$i' WHERE catid = $catid");
		           $database->query();
		           $i++;
		       }
	       }
	       break;
	   case 'catsmgr_getlist' :
	       //echo $zoom->callbackResult();
	       
	       $c_array = $zoom->getCatListMulti();
	       echo ("<tree id=\"0\">"
	        //. "<item text=\""._ZOOM_HD_NEW."\" id=\"0\" im0=\"page.gif\" im1=\"page.gif\" im2=\"page.gif\"></item>"
	        . $zoom->catsArrayToXML($c_array)
	        . "</tree>");
	       
	       break;
	   case 'catsmgr_editform' :
	       $catid = intval($zoom->getParam($_REQUEST, 'catid'));
	       $zoom->setGallery($catid);
	       
	       echo $zoom->callbackResult();
	       
           echo $zoom->_gallery->toXML();
	       break;
	   case 'catsmgr_resort' :
	       /*$catid  = intval($zoom->getParam($_REQUEST, 'catid'));
	       $target = intval($zoom->getParam($_REQUEST, 'target'));
	       $zoom->setGallery($catid);
	       if (!$zoom->_gallery->move($target)) {
	           echo $zoom->callbackResult(_ZOOM_A_ERROR_CONFTODB, true);
	       } else {
	       	   echo $zoom->callbackResult(_ZOOM_ALERT_EDITOK);
	       	   echo $zoom->_gallery->toXML();
	       }*/
	       $tree = trim($zoom->getParam($_REQUEST, 'tree'));
	       $coll = split(",", $tree);
	       $counter = 0;
	       $errors = false;
	       foreach ($coll as $catparent) {
	       	   $counter++;
	       	   $catparent = split("-", $catparent);
	       	   $gallery = intval($catparent[0]); // the gallery
	       	   $parent  = intval($catparent[1]); // its parent in the tree on the user interface
	       	   if ($parent !== 0) {
	       	   	   // first we get the pos of the parent...
	       	   	   $database->setQuery("SELECT pos FROM #__zoom WHERE catid = ".$parent);
	       	   	   $rowpos = intval($database->loadResult());
	       	   	   $pos = $rowpos + 1;
	       	   } else {
	       	   	   $pos = 0;
	       	   }
   	   	   	   $database->setQuery("UPDATE #__zoom SET "
   	   	   	    . "pos = ".$pos.", "
   	   	   	    . "subcat_id = ".$parent.", "
   	   	   	    . "custom_order = ".$counter." WHERE catid = ".$gallery);
   	   	   	   if (!$database->query()) {
   	   	   	   	    $errors = true;
   	   	   	   }
	       }
	       if ($errors) {
	           echo $zoom->callbackResult(_ZOOM_A_ERROR_CONFTODB, true);
	       } else {
	       	   echo $zoom->callbackResult(_ZOOM_ALERT_EDITOK);
	       }
	       break;
	   case 'catsmgr_new' :
	       $parent     = intval($zoom->getParam($_REQUEST, 'parent'));
           $name       = $zoom->htmlnumericentities($zoom->getParam($_REQUEST, 'name'));
           $dir        = $zoom->getParam($_REQUEST, 'dir');
           $catpass    = $zoom->getParam($_REQUEST, 'password');
           $keywords   = $zoom->htmlnumericentities($zoom->getParam($_REQUEST, 'keywords'));
           $descr      = $zoom->getParam($_REQUEST, 'descr', null, _MOS_ALLOWHTML);
           $hidemsg    = intval($zoom->getParam($_REQUEST, 'hide_msg'));
           $shared     = intval($zoom->getParam($_REQUEST, 'shared'));
           $published  = intval($zoom->getParam($_REQUEST, 'published'));
           $selections = $zoom->getParam($_REQUEST, 'members_sel');
           if (!empty($name)){
            	//Create directory
            	$zoom->checkDuplicate($dir, 'directory');
            	$mkdir = $zoom->_tempname;
            	$html_file = "<html><body bgcolor=\"#FFFFFF\"></body></html>";
            	if ($zoom->createdir($zoom->_CONFIG['imagepath'].$mkdir, '777')){
            		$zoom->createdir($zoom->_CONFIG['imagepath'].$mkdir."/thumbs", '777');
            		$zoom->createdir($zoom->_CONFIG['imagepath'].$mkdir."/viewsize", '777');
            		$zoom->writefile($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$mkdir."/index.html", $html_file);
            		$zoom->writefile($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$mkdir."/thumbs/index.html", $html_file);
            		$zoom->writefile($mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$mkdir."/viewsize/index.html", $html_file);
            		//Save data in the database
            		$database->setQuery("SELECT pos FROM #__zoom WHERE catid=$parent");
            		$result1 = $database->query();
            		$row = mysql_fetch_object($result1);
            		$pos = $row->pos;
            		if ($parent == 0) {
            			$pos = 0;
            		} else {
            			$pos++;
            		}
            		if (!isset($hidemsg)) {
            			$hidemsg = 0;
            		}
            		if ($pass != "" || !empty($pass)) {
            			//hash the password with md5 encryption...
            			$pass = md5($pass);
            		} else {
            			$pass = "";
            		}
            		$uid = $zoom->currUID;
            		if (empty($selections))
            		    $selections = 1;
            		else
            		    $selections = implode(',', $selections);
            		// replace space-character with 'air'...or nothing!
            		$keywords = ereg_replace(" ", "", $keywords);
            		
            		$database->setQuery("INSERT INTO #__zoom (catname, catdescr, catdir, catpassword, catkeywords, "
            		 . "subcat_id, pos, hideMsg, shared, published, uid, catmembers)"
            		 . " VALUES "
            		 . "('".$zoom->escapeString($name)."', '".$zoom->escapeString($descr)."', '".$zoom->escapeString($mkdir)."', '"
            		 . $zoom->escapeString($pass)."', '".$zoom->escapeString($keywords)."', '$parent', '$pos', '$hidemsg', "
            		 . "'$shared', '$published', '$uid', '".$zoom->escapeString($selections)."')");
            		if ($database->query()) {
            		    echo $zoom->callbackResult(html_entity_decode(_ZOOM_ALERT_NEWGALLERY));
            		    $database->setQuery("SELECT MAX(catid) AS catid FROM #__zoom");
            		    $result = $database->query();
            		    $row = mysql_fetch_object($result);
            		    $gallery = new gallery($row->catid, true);
            		    echo $gallery->toXML();
            		} else {
            		    echo $zoom->callbackResult(html_entity_decode(_ZOOM_ALERT_NONEWGALLERY), true);
            		}
            	} else {
            	    echo $zoom->callbackResult(html_entity_decode(_ZOOM_ALERT_NONEWGALLERY), true); 
            	}
           }
	       break;
	   case 'catsmgr_newdir' :
	   	   //echo $zoom->callbackResult();
	   	   echo "<newdir>".$zoom->newdir()."</newdir>";
	   	   break;
       case 'catsmgr_rename' :
            $catid  = intval($zoom->getParam($_REQUEST, 'catid'));
            if (!empty($catid)) {
				$newname = $zoom->htmlnumericentities($zoom->escapeString(trim($zoom->getParam($_REQUEST, 'newname'))));
                if (!empty($newname)) {
                	$database->setQuery("UPDATE #__zoom SET catname = '".$newname."' WHERE catid = ".$catid);
                    if ($database->query()) {
                    	echo $zoom->callbackResult(html_entity_decode(_ZOOM_ALERT_EDITOK));
                    } else {
                    	//TODO: provide proper user feedback here...
                    }
                } else {
                	//TODO: provide proper user feedback here...
                }
			} else {
				echo $zoom->callbackResult(html_entity_decode(_ZOOM_ALERT_NOCAT), true);
			}
            break;
	   case 'catsmgr_save' :
	       $catid = intval($zoom->getParam($_REQUEST, 'catid'));
	       $zoom->setGallery($catid);
	       
	       // get the variables to save...
	       $parent    = intval($zoom->getParam($_REQUEST, 'parent'));
           $newname   = $zoom->htmlnumericentities($zoom->getParam($_REQUEST, 'name'));
           $catpass   = $zoom->getParam($_REQUEST, 'password');
           $keywords  = $zoom->htmlnumericentities($zoom->getParam($_REQUEST, 'keywords'));
           $newdescr  = $zoom->getParam($_REQUEST, 'descr', null, _MOS_ALLOWHTML);
           $hidemsg   = intval($zoom->getParam($_REQUEST, 'hide_msg'));
           $shared    = intval($zoom->getParam($_REQUEST, 'shared'));
           $published = intval($zoom->getParam($_REQUEST, 'published'));
           $selections = $zoom->getParam($_REQUEST, 'members_sel');
           if (isset($newname)) {
               //Save changes
               $database->setQuery("SELECT pos FROM #__zoom WHERE catid=$parent");
               $result1 = $database->query();
               $row = mysql_fetch_object($result1);
               $pos = $row->pos;
               if ($parent == 0) {
                  $pos = 0;
               } else {
                  $pos++;
               }
               if (!isset($hidemsg)) {
                  $hidemsg = 0;
               }
               if ($catpass != "" || !empty($catpass)) {
                  $catpass = md5($catpass);
               } else {
                  $catpass = "";
               }
               if (empty($selections)) {
                  $selections = 1;
               } else {
                  $selections = implode(',', $selections);
               }
               // replace space-character with 'air'...or nothing!
               $keywords = trim(ereg_replace(" ", "", $keywords));
               $database->setQuery("UPDATE #__zoom "
                . "SET catname='".$zoom->escapeString($newname)."', catdescr='".$zoom->escapeString($newdescr)."', "
                . " catpassword='".$zoom->escapeString($catpass)."', catkeywords='".$zoom->escapeString($keywords)."', "
                . " subcat_id='$parent', pos='$pos', hideMsg='$hidemsg', shared = '$shared', published='$published', "
                . " catmembers='".$zoom->escapeString($selections)."' "
                . "WHERE catid=".$zoom->escapeString($catid));
               $database->query();
               //Unpublish/ publish the images of a gallery too...
               //Check if there are ANY images in the gallery...
               $database->setQuery("SELECT imgid FROM #__zoomfiles WHERE catid=$catid");
               $result = $database->query();
           }
           $zoom->_gallery->_getInfo();
	       
	       echo $zoom->callbackResult(html_entity_decode(_ZOOM_ALERT_EDITOK));
	       
           echo $zoom->_gallery->toXML();
	       break;
        case 'catsmgr_delete' :
             $catid  = intval($zoom->getParam($_REQUEST, 'catid'));
             if (!empty($catid)) {
                 removeGalleryRecursive($catid);
                 echo $zoom->callbackResult(html_entity_decode(_ZOOM_ALERT_DEL));
             } else {
                 echo $zoom->callbackResult(html_entity_decode(_ZOOM_ALERT_NOCAT), true);
             }
             break;
	   case 'get_toolbar' :
	       $zoom->callbackResult();
	       $action = $zoom->getParam($_REQUEST, 'action', 'catsmgr');
	       if (!empty($action)) {
	           if ($action == "catsmgr_editform") {
                    $buttons = array('delete', 'save');
	           } else if ($action == "catsmgr_newform") {
                    $buttons = array('cancel', 'save');
	           } else if ($action == "catsmgr") {
                    $buttons = array('new');
	           }
	           
	           echo $zoom->callbackResult();
	           
    	       require_once($mosConfig_absolute_path.'/components/com_zoom/www/admin/toolbar.xml.php');
    	       XML_toolbar::buildToolbar($buttons);
	       }
	       break;
	   case 'get_galleries' :
	       echo $zoom->createCatXML();
	       break;
	}
	echo $zoom->finishXML($task);
} else {
    die('Direct Access to this location is not allowed.');
}
function removeGalleryRecursive($catid) {
    global $database, $mosConfig_absolute_path, $zoom;
    
    //The recursive part
    $database->setQuery("SELECT catname, catdir, catid FROM #__zoom WHERE subcat_id=".$catid);
    if(!$database->query()) {
		echo $database->stderr();
	} else {
        $result2 = $database->query();
        if($database->loadObjectList() != null) {
            $rows = $database->loadObjectList();
            foreach ($rows as $recursive) {
                removeGalleryRecursive($recursive->catid);
            }	
		}
    }

    
    //Fetch directoryname
    $database->setQuery("SELECT catname, catdir FROM #__zoom WHERE catid=".$catid);
    $result = $database->query();
    $row = mysql_fetch_object($result);
    $dir = $row->catdir;
    $gallery = $row->catname;
    $dir = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$dir;
    if ($zoom->platform->is_dir($dir)) {
        //Delete comments from database
        $database->setQuery("SELECT * FROM #__zoomfiles WHERE catid=".$catid);
        $result1 = $database->query();
        while ($row1 = mysql_fetch_object($result1)) {
         $database->setQuery("DELETE FROM #__zoom_comments WHERE imgid=".$row1->imgid);
         $database->query();
        }
        //Delete files from database
        $database->setQuery("DELETE FROM #__zoomfiles WHERE catid=".$catid);
        $database->query();
        //Finally, delete category from database
        $database->setQuery("DELETE FROM #__zoom WHERE catid=".$catid);
        $database->query();
        //Empty and delete directory
        $zoom->deldir($dir);
    }
}
?>