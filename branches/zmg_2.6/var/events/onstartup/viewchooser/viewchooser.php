<?php
/**
 * zOOm Media Gallery! - a multi-gallery component 
 * 
 * @package zmg
 * @subpackage events
 * @version $Revision$
 * @author Mike de Boer <mdeboer AT ebuddy.com>
 * @copyright Copyright &copy; 2007, Mike de Boer. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */

defined('_ZMG_EXEC') or die('Restricted access');

/**
 * Class that assists Zoom in determining the currently active view
 * @package zmg
 * @static
 */
class viewchooser {
    function start(&$zoom) {
        $view = trim(zmgGetParam($_REQUEST, 'view', defined('ZMG_ADMIN') ? 'admin:home' : 'gallery'));
        $zoom->view->set($view);
        
        /*
        switch ($page) {
            case 'editimg':
                if ($zoom->privileges->hasPrivilege('priv_editmedium') || $zoom->_isAdmin) {
                    include(ZMG_ABS_PATH.'/components/com_zoom/www/admin/editimg.php');
                } else {
                    $zoom->notAuth();
                }
                break;
            case 'view':
                include(ZMG_ABS_PATH.'/components/com_zoom/www/view.php');
                break;
            case 'special':
                include(ZMG_ABS_PATH.'/components/com_zoom/www/special.php');
                break;
            // Admin module pages...
            case 'admin':
                if ($zoom->privileges->hasPrivileges()) {
                    include(ZMG_ABS_PATH.'/components/com_zoom/www/admin/admin.php');
                    $zoom->adminFooter();
                } else {
                    $zoom->notAuth();
                }
                break;
            case 'zoomthumb':
                include(ZMG_ABS_PATH.'/components/com_zoom/www/admin/zoomthumb.php');
                break;
            case 'catsmgr':
                if ($zoom->_isAdmin || ($zoom->privileges->hasPrivilege('priv_creategal') || $zoom->privileges->hasPrivilege('priv_editgal') || $zoom->privileges->hasPrivilege('priv_delgal'))) {
                    include(ZMG_ABS_PATH.'/components/com_zoom/www/admin/catsmgr.php');
                    $zoom->adminFooter();
                } else {
                    $zoom->notAuth();
                }
                break;
            case 'mediamgr':
                if ($zoom->_isAdmin || ($zoom->privileges->hasPrivilege('priv_upload') || $zoom->privileges->hasPrivilege('priv_editmedium') || $zoom->privileges->hasPrivilege('priv_delmedium'))) {
                    include(ZMG_ABS_PATH.'/components/com_zoom/www/admin/mediamgr.php');
                    $zoom->adminFooter();
                } else {
                    $zoom->notAuth();
                }
                break;
            case 'upload':
                if ($zoom->_isAdmin || $zoom->privileges->hasPrivilege('priv_upload')) {
                    include(ZMG_ABS_PATH.'/components/com_zoom/www/admin/upload.php');
                    $zoom->adminFooter();
                } else {
                    $zoom->notAuth();
                }
                break;
            case 'settings':
                if ($zoom->_isAdmin) {
                    include(ZMG_ABS_PATH.'/components/com_zoom/www/admin/settings.php');
                    $zoom->adminFooter();
                } else {
                    $zoom->notAuth();
                }
                break;
            case 'movefiles':
                if ($zoom->_isAdmin) {
                    include(ZMG_ABS_PATH.'/components/com_zoom/www/admin/movefiles.php');
                    $zoom->adminFooter();
                } else {
                    $zoom->notAuth();
                }
                break;
            case 'credits':
                if ($zoom->privileges->hasPrivileges()) {
                    include(ZMG_ABS_PATH.'/components/com_zoom/www/admin/credits.php');
                    $zoom->adminFooter();
                } else {
                    $zoom->notAuth();
                }
                break;
            case 'lightbox':
                if ($zoom->_CONFIG['lightbox']) {
                    include(ZMG_ABS_PATH.'/components/com_zoom/www/lightbox.php');
                } else {
                    $zoom->notAuth();
                }
                break;
            case 'ecard':
                if ($zoom->_CONFIG['ecards']) {
                    include(ZMG_ABS_PATH.'/components/com_zoom/www/ecard.php');
                } else {
                    $zoom->notAuth();
                }
                break;
            case 'search':
                include(ZMG_ABS_PATH.'/components/com_zoom/www/search.php');
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
                        $zoom->notAuth();
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
                        include(ZMG_ABS_PATH.'/components/com_zoom/www/galleryshow.php');
                    } else {
                        $zoom->notAuth();
                    }
                } else {
                    $zoom->notAuth();
                }
                break;
        }
        */
    }
}