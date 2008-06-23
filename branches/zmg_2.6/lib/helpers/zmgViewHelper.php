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
 * Class that assists Zoom with controlling the views on the different models
 * that the Core exposes.
 * @package zmg
 */
class zmgViewHelper {
    /**
     * Public variable, containing the zmgTemplateViewHelper/Smarty
     * templating engine class.
     *
     * @var zmgTemplateHelper
     */
    var $_template = null;
    
    var $_active_view = null;
    
    var $_active_subview = null;
    
    var $_view_tokens = null;
    
    var $_viewtype = null;
    
    /**
     * The class constructor.
     */
    function zmgViewHelper(&$smarty_cfg, $secret) {
        zmgimport('org.zoomfactory.lib.helpers.zmgTemplateHelper');
        $this->_template = new zmgTemplateHelper(&$smarty_cfg, $secret);
    }
    
    function getActiveTemplate() {
    	return $this->_template->getActiveTemplate();
    }
    
    function setViewType($view = 'html') {
        $this->_viewtype = $view;
    }

    function set() {
        $view = trim(zmgGetParam($_REQUEST, 'view', ZMG_ADMIN ? 'admin:home' : 'gallery:show:home'));
        if (empty($view)) {
            $view = ZMG_ADMIN ? "admin:dispatchresult" : "dispatchresult";
            $this->throwError('No view specified.');
        }
        $this->_active_view = $view;
        $this->_view_tokens = split(':', $view);
        
        $events = & zmgFactory::getEvents();

        //check for dispatches that 'put' data (in contrary to 'get' requests)
        $isDataStore = $events->fire('onisdatastore', false, $this->_view_tokens);
        
        if ((bool)$isDataStore) {
        	$events->fire('ondatastore', false, $this->_view_tokens);
            
            $this->_active_view = (ZMG_ADMIN ? "admin:dispatchresult" : "dispatchresult")
             . ":" . str_replace(':', '_', str_replace('admin:', '', $view));
            $this->_view_tokens = split(':', $this->_active_view);
        }
        
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
            
        $events->fire('onviewset');
    }

    function appendConstant($name, $value) {
        if (empty($name) || !isset($value)) {
            return $this->throwError('zmgViewHelper::appendConstant:' . T_('dependencies not met, please check.'));
        }

        $this->_template->appendConstant($name, $value);
    }
    
    function get() {
        return $this->_active_view;
    }
    
    function getSubView() {
        if (!$this->_active_subview) {
            if (!is_array($this->_view_tokens)) {
                $this->_view_tokens = split(':', $this->_active_view);
            }
            $this->_active_subview = $this->_view_tokens[count($this->_view_tokens) - 1];
        }
        return $this->_active_subview;
    }
    
    function getViewTokens() {
        if (is_array($this->_view_tokens)) {
            return $this->_view_tokens;
        }
        return array();
    }

    function run() {
        if (empty($this->_active_view))
            return $this->throwError('No active view specified. Unable to run application.');
            
        if (zmgEnv::isRPC() && $this->_active_view == "ping") {
            zmgFactory::getRequest()->sendHeaders($this->_viewtype);
            echo "                                         ";
            return;
        }
        
        $this->_template->run($this->_active_view, $this->getSubView(),
          $this->_viewtype);
    }
    
    function setAndRun() {
        $this->set();

        $this->run();
    }
    
    function throwError($message) {
        if (true) {//!zmgEnv::isRPC()) {
            return zmgError::throwError($message);
        } else {
            return zmgFactory::getRequest()->sendHeaders($this->_viewtype, true, $message);
        }
    }
}
?>
