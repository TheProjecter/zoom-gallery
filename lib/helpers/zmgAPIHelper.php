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
 * Class that is used to provide an API between the Zoom Core and templates.
 * Its primary use is to serve as a proxy between the Zoom object(s) and the
 * evil outside world.
 * @package zmg
 */
class zmgAPIHelper {
    var $subview = null;
    
    var $site_url = null;
    
    var $ajax_url = null;
    
    var $rpc_url = null;
    
    var $mediapath = null;
    
    var $result_ok = null;
    
    var $result_ko = null;
    
    var $abstract_whitelist = array('zmgHTML');
    
    function zmgAPIHelper(&$zoom) {
        $this->site_url  = zmgEnv::getSiteURL();
        $this->ajax_url  = zmgEnv::getAjaxURL();
        $this->rpc_url   = zmgEnv::getRpcURL();
        $this->mediapath = $zoom->getConfig('filesystem/mediapath');
        $this->result_ok = _ZMG_RPC_RESULT_OK;
        $this->result_ko = _ZMG_RPC_RESULT_KO;
    }
    
    function getParam($name) {
        if (empty($name) || empty($this->$name))
            return zmgError::throwError('zmgAPIHelper::getParam: invalid parameter.');
            
        return $this->$name;
    }
    
    function setParam($name, $value = '') {
        if (empty($name)) {// || !isset($this->$name)) {
            return zmgError::throwError('zmgAPIHelper::setParam: invalid parameter.');
        }
        if (empty($value)) {
            return zmgError::throwError('zmgAPIHelper::setParam: no value specified for parameter.');
        }
        
        $this->$name = $value;
    }
    
    /**
     * Retrieve a specific configuration setting.
     * @param string The name of the setting in the format of a pathname: 'group/setting'
     */
    function getConfig($path) {
        $zoom = & zmgFactory::getZoom();
        
        return $zoom->getConfig($path);
    }
    
    /**
     * Call an abstract/ static function that resides within a static class.
     * Note: particularly useful within templates.
     * @see zmgCallAbstract
     */
    function callAbstract($klass, $func, $args = array(0)) {
        if (!in_array($klass, $this->abstract_whitelist)) {
            return zmgError::throwError('zmgAPIHelper::callAbstract: illegal call.');
        }
        
        return zmgCallAbstract($klass, $func, $args);
    }
    
    function getRequestParamInt($name, $default = 0) {
        return intval(zmgGetParam($_REQUEST, $name, $default));
    }
    
    function getRequestParamFloat($name, $default = 0) {
        return floatval(zmgGetParam($_REQUEST, $name, $default));
    }
    
    function constructArray() {
        return func_get_args();
    }
    
    function jsonHelper($input, $type = 'encode') {
        $zoom = & zmgFactory::getZoom();
        
        return $zoom->jsonHelper($input, $type);
    }
    
    function getResult() {
        $zoom = & zmgFactory::getZoom();
        
        return $zoom->getResult();
    }
    
    function getMessages() {
        $zoom = & zmgFactory::getZoom();
        
        return $zoom->messages->get();
    }
    
    function getPluginsHTML() {
        $zoom = & zmgFactory::getZoom();
        
        return $zoom->plugins->embedHTML();
    }
    
    function getTemplates() {
        return zmgTemplateHelper::getTemplates();
    }
    
    function getGallery($gid, $ret_type = 'object') {
        $zoom = & zmgFactory::getZoom();
        
        return $zoom->getGallery($gid, $ret_type);
    }
    
    function getGalleries($sub_gid = 0, $pos = 0) {
        $zoom = & zmgFactory::getZoom();
        
        return $zoom->getGalleries($sub_gid, $pos);
    }
    
    function getMedium($mid, $ret_type = 'object') {
        $zoom = & zmgFactory::getZoom();
        
        return $zoom->getMedium($mid, $ret_type);
    }
    
    function getMedia($gid = 0, $offset = 0, $length = 0, $filter = 0) {
        $zoom = & zmgFactory::getZoom();
        
        return $zoom->getMedia($gid, $offset, $length, $filter);
    }
    
    function getActiveTemplate() {
    	$zoom = & zmgFactory::getZoom();
        
        return $zoom->view->getActiveTemplate();
    }
}
?>
