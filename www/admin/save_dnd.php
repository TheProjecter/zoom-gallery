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
| Filename: save_dnd.php                                              |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:save_dnd.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
define( "_VALID_MOS", 1 );
require_once( '../../../../globals.php' );
include_once('../../../../configuration.php');
require_once("../../lib/inserts.class.php");

echo "Processing images from list...<br /><br />";	

    if (ini_get('safe_mode') != 1 ) {
        set_time_limit(0);
    }
	
	$catid = intval($zoom->getParam($_REQUEST, 'catid'));
	if (empty($catid)) {
		echo "No gallery specified, please select one from the list.";
		exit();
	} else {
		$zoom->setGallery($catid, true);
	}
 	if ($zoom->_CONFIG['readEXIF'] && !(bool)ini_get('safe_mode')) {
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/JPEG.php");
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/EXIF.php");
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/Photoshop_IRB.php");
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/XMP.php");
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/Photoshop_File_Info.php");
	}
 	
 	
 	foreach ($_FILES as $key => $value) {
        $tag = ereg_replace(".*\.([^\.]*)$", "\\1", urldecode($value['name']));
        $tag = strtolower($tag);
        $filetype = $value['type']; //used for MIME type based check
        $setFilename = $zoom->getParam($_REQUEST, 'dnd_setFilename');
        if($setFilename) {
        	$name = urldecode($value['name']);
        } else {
        	$name = $zoom->getParam($_GET, 'dnd_name');
        }
        $keywords = $zoom->getParam($_GET, 'dnd_keywords');
        $descr = $zoom->getParam($_REQUEST, 'dnd_descr', null, _MOS_ALLOWHTML);
        //Check for right format
        if ($zoom->acceptableFormat($tag)) {
        	$imagepath = $zoom->_CONFIG['imagepath'];
        	$catdir = $zoom->_gallery->getDir();
        	$filename = urldecode($value['name']);
        	if (!isset($descr)) {
        		$descr = $zoom->_CONFIG['tempDescr'];
        	} 
        	$zoom->toolbox->processImage($value['tmp_name'], $filename, $filetype, $keywords, $name, $descr, false, '0', '0', $uid);
        	if ($zoom->toolbox->_err_num > 0) {
        		$zoom->toolbox->displayErrors();
        	} else {
        			echo "<b>1 "._ZOOM_ALERT_UPLOADSOK."</b><br />";
        	}
        } else {
        	//Not the right format, back to uploadscreen-->
        	echo "<b>"._ZOOM_ALERT_WRONGFORMAT."</b><br />";
        }
    }
?>