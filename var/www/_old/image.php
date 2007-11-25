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
| Filename: image.php                                                  |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:image.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/

define( "_VALID_MOS", 1 );

require_once( '../../../globals.php' );
include_once('../../../configuration.php');

$ref = $_SERVER['HTTP_REFERER'];
if (strpos($ref,$mosConfig_live_site) === 0 || strpos($ref, 'http') !== 0) {

require_once("../lib/inserts.class.php");
    
    // get variables from HTTP request...
    $q = $zoom->decrypt($zoom->getParam($_REQUEST, 'q'));
    // Use &amp; for correct processing
    $params = split("&amp;", $q);
    foreach ($params as $param) {
        $var = split("=", $param);
        if (count($var) === 2) {
            $_REQUEST[$var[0]] = $var[1];
        }
    }
    
    // SSL check - $http_host returns <live site url>:<port number if it is 443>
    $http_host = explode(':', $_SERVER['HTTP_HOST'] );
    if( (!empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) != 'off' || isset( $http_host[1] ) && $http_host[1] == 443) && substr( $mosConfig_live_site, 0, 8 ) != 'https://') {
        $mosConfig_live_site = 'https://'.substr( $mosConfig_live_site, 7 );
    }

    
    //Get all the stuff for the picture
    $key   = intval($zoom->getParam($_REQUEST, 'key'));
    $catid = intval($zoom->getParam($_REQUEST, 'catid'));
    $type  = intval($zoom->getParam($_REQUEST, 'type'));
    $zoom->setGallery($catid);
    $zoom->_gallery->_images[$key]->getInfo();
    
    //Lets show some pictures!
    $img_type = $zoom->_gallery->_images[$key]->_type;
    if ($type == 0) { //Viewsize
        $img = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$zoom->_gallery->_images[$key]->_viewsize;
    } elseif ($type == 1) { //Fullsize
        $img = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$zoom->_gallery->_images[$key]->_filename;
    } elseif ($type == 2) { //Thumbnail
        $img = $zoom->_gallery->_images[$key]->_thumbnail;
        $img = str_replace($mosConfig_live_site, $mosConfig_absolute_path, $img);
        $img_type = $zoom->_gallery->_images[$key]->_thumbtype;
    }
    $img = str_replace(' ','%20',$img); 
	echo header("Content-Type: image/".$img_type."");
    @readfile($img);
} else {
    echo header("Content-Type: image/png"); 
    $img = $mosConfig_live_site."/components/com_zoom/www/images/hotlink.png";
    @readfile($img);
}
?>