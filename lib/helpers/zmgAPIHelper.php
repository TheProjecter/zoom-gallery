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
    
    function zmgAPIHelper() {
        $config = & zmgFactory::getConfig();

        $this->site_url  = zmgEnv::getSiteURL();
        $this->ajax_url  = zmgEnv::getAjaxURL();
        $this->rpc_url   = zmgEnv::getRpcURL();
        $this->mediapath = $config->get('filesystem/mediapath');
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
     *
     * @param string The name of the setting in the format of a pathname: 'group/setting'
     */
    function getConfig($path) {
        return zmgFactory::getConfig()->get($path);
    }
    
    /**
     * Call an abstract/ static function that resides within a static class.
     * Note: particularly useful within templates.
     *
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
        $json = & zmgFactory::getJSON();

        if ($type == "decode") {
            return $json->decode($input);
        }

        return $json->encode($input);
    }
    
    function getResult() {
        $request = & zmgFactory::getRequest();
        
        return $request->getResult();
    }
    
    function getMessages() {
        return zmgFactory::getMessages()->get();
    }
    
    function getPluginsHTML() {
        return zmgFactory::getPlugins()->embedHTML();
    }
    
    function getTemplates() {
        return zmgTemplateHelper::getTemplates();
    }
    
    function getGallery($gid, $ret_type = 'object') {
        $zoom = & zmgFactory::getEvents()->fire('ongetcore');
        
        return $zoom->getGallery($gid, $ret_type);
    }
    
    function getGalleries($sub_gid = 0, $pos = 0) {
        $zoom = & zmgFactory::getEvents()->fire('ongetcore');
        
        return $zoom->getGalleries($sub_gid, $pos);
    }
    
    function getMedium($mid, $ret_type = 'object') {
        $zoom = & zmgFactory::getEvents()->fire('ongetcore');
        
        return $zoom->getMedium($mid, $ret_type);
    }
    
    function getMedia($gid = 0, $offset = 0, $length = 0, $filter = 0) {
        $zoom = & zmgFactory::getEvents()->fire('ongetcore');
        return $zoom->getMedia($gid, $offset, $length, $filter);
    }
    
    function getMediaMetadata($mid) {
        $zoom = & zmgFactory::getEvents()->fire('ongetcore');
        
        $medium = $zoom->getMedium($mid);
        
        return array($medium->getMetadata());
    }
    
    function getViewToken($which = 'last') {
        $tokens = zmgFactory::getView()->getViewTokens();
        
        if (count($tokens) == 0) {
            return zmgError::throwError('No tokens available.');
        }
        
        $token = "";
        switch ($which) {
            case 'first':
                $token = $tokens[0];
                break;
            default:
            case 'last':
                $token = $tokens[count($tokens) - 1];
                break;
        }
        
        return $token;
    }
    
    function getActiveTemplate() {
        return zmgFactory::getView()->getActiveTemplate();
    }
    
    function getMediaFromRequest() {
        $token  = zmgAPIHelper::getViewToken(); //'last' by default
        
        $db     = & zmgDatabase::getDBO();
        
        $tokens = explode(',', $token); //will return an array, no matter how many commas
        $media  = array();
        foreach ($tokens as $mid) {
            $medium = new zmgMedium($db);
            $medium->load($mid);
            $media[] = $medium; //push
        }
        
        return $media;
    }
}

?>
