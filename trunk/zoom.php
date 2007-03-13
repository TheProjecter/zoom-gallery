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
| Filename: zoom.php                                                  |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:zoom.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

// Turn off Magic quotes runtime, because it interferes with saving info to the 
// database and vice versa.
set_magic_quotes_runtime(0);

//error_reporting(E_ALL); //turned on while debugging

// Create zOOm Image Gallery object
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/zoom.class.php');
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/toolbox.class.php');
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/ftplib.class.php');
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/editmon.class.php'); //like a common session-monitor...
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/gallery.class.php');
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/image.class.php');
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/comment.class.php');
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/ecard.class.php');
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/lightbox.class.php');
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/privileges.class.php');
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/template/template.main.php');
require_once($mosConfig_absolute_path.'/components/com_zoom/lib/mime/mime.class.php');
// Load configuration file...
require($mosConfig_absolute_path.'/components/com_zoom/etc/zoom_config.php');

$zoom = new zoom();
if ($zoom->isWin()) {
    require_once($mosConfig_absolute_path.'/components/com_zoom/lib/WinNtPlatform.class.php');
    $zoom->platform = new WinNtPlatform();
} else {
    require_once($mosConfig_absolute_path.'/components/com_zoom/lib/UnixPlatform.class.php');
    $zoom->platform = new UnixPlatform();
}
// now create an instance of the ToolBox!
$zoom->toolbox = new toolbox();

// Start session for the LightBox...
if ($zoom->_CONFIG['lightbox']) {
    @ini_set('session.save_handler', 'files'); 
    session_name('zoom');
    if(session_id()) {
        session_destroy();
    }
    ini_set('session.save_handler', 'files');
    session_module_name("files");
	session_start();
    
    if (!isset($_SESSION['lightbox'])) {
        $_SESSION['lightbox'] = new lightbox();
        // for test purposes:
        $_SESSION['lb_checked_out'] = false;
    }
}

// list of common inclusions:
if (file_exists($mosConfig_absolute_path."/components/com_zoom/lib/language/".$mosConfig_lang.".php")) { 
    include_once($mosConfig_absolute_path."/components/com_zoom/lib/language/".$mosConfig_lang.".php");
} else { 
    include_once($mosConfig_absolute_path."/components/com_zoom/lib/language/english.php");
}

// Update the Edit Monitor, eg. delete unnecessary rows
$zoom->EditMon->updateEditMon();

// prevent Itemid missing...
if (!isset($Itemid) || empty($Itemid)) {
    $Itemid = $zoom->getItemid($option);
}

// load gallery object if a catid is specified...
$catid = intval(mosGetParam($_REQUEST,'catid'));
//if (isset($catid) && !is_array($catid) && !empty($catid) && !($catid == 0)) { // RC3
if (isset($catid) && !is_array(mosGetParam($_REQUEST,'catid')) && !empty($catid) && !($catid == 0)) { //RC4
    $zoom->setGallery($catid);
}
if ($zoom->_isBackend) {
    $backend = "2";
} else {
    $backend = "";
}
// Standard (D)HTML...
mosCommonHTML::loadOverlib();

$page = trim(mosGetParam($_REQUEST,'page'));
switch ($page) {
    case 'editimg':
        if ($zoom->privileges->hasPrivilege('priv_editmedium') || $zoom->_isAdmin) {
            include($mosConfig_absolute_path.'/components/com_zoom/www/admin/editimg.php');
        } else {
            mosNotAuth();
        }
        break;
    case 'view':
        include($mosConfig_absolute_path.'/components/com_zoom/www/view.php');
        break;
    case 'special':
        include($mosConfig_absolute_path.'/components/com_zoom/www/special.php');
        break;
    // Admin module pages...
    case 'admin':
        if ($zoom->privileges->hasPrivileges()) {
            include($mosConfig_absolute_path.'/components/com_zoom/www/admin/admin.php');
            $zoom->adminFooter();
        } else {
            mosNotAuth();
        }
        break;
    case 'zoomthumb':
        include($mosConfig_absolute_path.'/components/com_zoom/www/admin/zoomthumb.php');
        break;
    case 'catsmgr':
        if ($zoom->_isAdmin || ($zoom->privileges->hasPrivilege('priv_creategal') || $zoom->privileges->hasPrivilege('priv_editgal') || $zoom->privileges->hasPrivilege('priv_delgal'))) {
            include($mosConfig_absolute_path.'/components/com_zoom/www/admin/catsmgr.php');
            $zoom->adminFooter();
        } else {
            mosNotAuth();
        }
        break;
    case 'mediamgr':
        if ($zoom->_isAdmin || ($zoom->privileges->hasPrivilege('priv_upload') || $zoom->privileges->hasPrivilege('priv_editmedium') || $zoom->privileges->hasPrivilege('priv_delmedium'))) {
            include($mosConfig_absolute_path.'/components/com_zoom/www/admin/mediamgr.php');
            $zoom->adminFooter();
        } else {
            mosNotAuth();
        }
        break;
    /** Deprecated:
    case 'new':
        if ($zoom->_isAdmin || $zoom->privileges->hasPrivilege('priv_creategal')) {
            include($mosConfig_absolute_path.'/components/com_zoom/www/admin/new.php');
            $zoom->adminFooter();
        } else {
            mosNotAuth();
        }
        break;
    */
    case 'upload':
        if ($zoom->_isAdmin || $zoom->privileges->hasPrivilege('priv_upload')) {
            include($mosConfig_absolute_path.'/components/com_zoom/www/admin/upload.php');
            $zoom->adminFooter();
        } else {
            mosNotAuth();
        }
        break;
    case 'settings':
        if ($zoom->_isAdmin) {
            include($mosConfig_absolute_path.'/components/com_zoom/www/admin/settings.php');
            $zoom->adminFooter();
        } else {
            mosNotAuth();
        }
        break;
    case 'movefiles':
        if ($zoom->_isAdmin) {
            include($mosConfig_absolute_path.'/components/com_zoom/www/admin/movefiles.php');
            $zoom->adminFooter();
        } else {
            mosNotAuth();
        }
        break;
    case 'credits':
        if ($zoom->privileges->hasPrivileges()) {
            include($mosConfig_absolute_path.'/components/com_zoom/www/admin/credits.php');
            $zoom->adminFooter();
        } else {
            mosNotAuth();
        }
        break;
    case 'lightbox':
        if ($zoom->_CONFIG['lightbox']) {
            include($mosConfig_absolute_path.'/components/com_zoom/www/lightbox.php');
        } else {
            mosNotAuth();
        }
        break;
    case 'ecard':
        if ($zoom->_CONFIG['ecards']) {
            include($mosConfig_absolute_path.'/components/com_zoom/www/ecard.php');
        } else {
            mosNotAuth();
        }
        break;
    case 'search':
        include($mosConfig_absolute_path.'/components/com_zoom/www/search.php');
        break;
    default:
        $action = trim(mosGetParam($_REQUEST,'action'));
        if ($action === 'delimg') {
            if ($zoom->_isAdmin || $zoom->privileges->hasPrivilege('priv_delmedium')) {
                $key = mosGetParam($_REQUEST,'key');
                $PageNo = mosGetParam($_REQUEST,'PageNo');
                if ($key || $key == 0) {
                    $zoom->_gallery->_images[$key]->getInfo();
                    if ($zoom->_gallery->_images[$key]->delete()) {
                        mosRedirect(sefRelToAbs("index.php?option=$option&catid=".$zoom->_gallery->_id."&PageNo=$PageNo&Itemid=$Itemid"), _ZOOM_ALERT_DELPIC);
                    } else {
                        mosRedirect(sefRelToAbs("index.php?option=$option&catid=".$zoom->_gallery->_id."&PageNo=$PageNo&Itemid=$Itemid"), _ZOOM_ALERT_NODELPIC);
                    }
                }
            } else {
                mosNotAuth();
            }
        }
        if (!empty($zoom->_gallery) || $zoom->_isAdmin || $catid == 0) {
            $valid = true;
            if (!empty($zoom->_gallery)) {
            	if (!$zoom->_gallery->_published  && !$zoom->_isAdmin) {
            		$valid = false;
            	}
            }
            if ($valid) {
            	include($mosConfig_absolute_path.'/components/com_zoom/www/galleryshow.php');
            } else {
                mosNotAuth();
            }
        } else {
            mosNotAuth();
        }
        break;
}
?>