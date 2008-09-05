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
 * Class that assists Zoom in parsing the templates.
 * @package zmg
 */
class zmgTemplateHelper extends Smarty {
    var $_manifest = null;
    
    var $_type = null;
    
    var $_active_template = null;
    
    var $_template_name = null;
    
    var $_template_preview = null;
    
    var $_secret = null;
    
    var $_constants = null;

    /**
     * The class constructor.
     */
    function zmgTemplateHelper(&$config, $secret = '', $forceConfig = false) {
        //Smarty options:
        $this->template_dir = $config['templatedir'];
        $this->compile_dir  = $config['compiledir'];
        $this->cache_dir    = $config['cachedir'];
        $this->config_dir   = $config['configdir'];
        
        //Helper options:
        if (ZMG_ADMIN && !$forceConfig) {
            $this->_active_template = "admin";
        } else {
            $this->_active_template = $config['activetemplate'];
        }
        $this->_type      = "html"; //default type
        $this->_secret    = $secret;
        
        $json = & zmgFactory::getJSON();
        $this->_constants = array(
            "id"          => $json->encode(md5($this->_secret)),
            "result_ok"   => $json->encode(_ZMG_RPC_RESULT_OK),
            "result_ko"   => $json->encode(_ZMG_RPC_RESULT_KO),
            "active_view" => "''",//$this->_active_view,
            "is_admin"    => ((ZMG_ADMIN) ? "true" : "false"),
            "site_uri"    => "document.location.protocol + '//' + document.location.host + document.location.pathname.replace(/\/(administrator\/)?index(2)?\.php$/i, '')"
        );

        $this->_loadManifest();
    }
    
    function getActiveTemplate() {
    	return $this->_active_template;
    }
    
    function run($view, $subview, $viewtype) {
        //mootools & Ajax preparing stuff
        $this->_type = $viewtype;

        if (!zmgEnv::isRPC()) {
            if ($this->_type == "html") {
                if (ZMG_ADMIN) {
                    $this->_buildAdminToolbar();
                }
                zmgEnv::includeMootools();
                
                $json = & zmgFactory::getJSON();
                
                $lifetime = (zmgEnv::getSessionLifetime() * 60000); //in milliseconds
                //refresh time is 1 minute less than the lifetime assigned in the CMS configuration
                $refreshTime =  ($lifetime <= 60000) ? 30000 : $lifetime - 60000;
                $this->_constants = array_merge($this->_constants, array(
                    "req_uri"     => "ZMG.CONST.site_uri + '".zmgEnv::getAjaxURL()."'",
                    "res_path"    => "ZMG.CONST.site_uri + '/components/com_zoom/var/www/templates/"
                      . $this->_active_template."'",
                    "base_path"   => "'".zmgGetBasePath()."'",
                    "refreshtime" => $refreshTime,
                    "sessionid"   => $json->encode(zmgEnv::getSessionID()),
                    "sessionname" => $json->encode(zmgEnv::getSessionName())
                ));
                zmgEnv::appendPageHeader(zmgHTML::buildConstScript($this->_constants));
            }
        } else if ($this->_type == "xml") {
            zmgFactory::getRequest()->sendHeaders('xml');
        } 
        
        if ($this->_type == "html") {
            //put the HTML headers in the head section of the parent (hosting) document
            $headers = $this->getHTMLHeaders(zmgEnv::getSiteURL()
              . '/components/com_zoom/var/www/templates', $this->_type);
            foreach ($headers as $header) {
                zmgEnv::appendPageHeader($header);
            }
        }
        
        //get template file that belongs to the active view:
        $res = & $this->_getResource('template', $view, $this->_type);
        if ($res) {
            $tpl_file = trim($res->firstChild->getAttribute('href'));

            zmgimport('org.zoomfactory.lib.helpers.zmgAPIHelper');
            $api = new zmgAPIHelper();

            $api->setParam('subview', $subview);
            
            $this->assign('zmgAPI', $api); //the API is accessible for all Smarty templates
            
            $this->assign('mediapath', $api->getParam('mediapath'));

            $this->display($tpl_file);
        } else {
            return $this->throwError('No template resource found. Unable to run application.');
        }
    }
    
    function appendConstant($name = null, $value = null) {
        if (!$name) {
            return $this->throwError('No name specified for constant');
        }
        if ($value === null) {
            return $this->throwError('No value specified for constant ' . $name);
        }
        $this->_constants[trim($name)] = is_string($value) ? trim($value) : intval($value);
    }
    
    function _buildAdminToolbar() {
        if (!ZMG_ADMIN) {
            return $this->throwError('Function may only be called in admin mode.');
        }
            
        $assets = zmgEnv::getToolbarAssets();

        zmgCallAbstract($assets['classHelper'], $assets['commands']['title'],
          zmgFactory::getConfig()->get('meta/title'));
        zmgCallAbstract($assets['classHelper'], $assets['commands']['back']);
        zmgCallAbstract($assets['classHelper'], $assets['commands']['spacer']);
        
        $this->appendConstant('toolbar_node', '"'.$assets['node'].'"');
        $this->appendConstant('toolbar_buttonclass', '"'.$assets['classButton'].'"');
    }
    
    function _getInfo() {
        if (empty($this->_manifest)) {
            return $this->throwError('Template manifest not loaded yet.');
        }
            
        $els = & $this->_manifest->getElementsByTagName('template');
        if ($els->getLength() < 1)
            return;
        
        $info = array();
        $profile = & $els->item(0);
        for ($i = 0; $i < $profile->childCount; $i++) {
            $info[$profile->childNodes[$i]->nodeName] = 
              trim($profile->childNodes[$i]->firstChild->nodeValue); 
        }
        
        return $info;
    }
    
    function getTemplateName() {
        if (empty($this->_manifest)) {
            return $this->throwError('Template manifest not loaded yet.');
        }
        
        if (empty($this->_template_name)) {
            $els = & $this->_manifest->getElementsByTagName('template');
            if ($els->getLength() < 1)
                return;
            
            $profile = & $els->item(0);
            $this->_template_name = trim($profile->getAttribute('name'));
        }
        
        return $this->_template_name;
    }
    
    function getTemplatePreview() {
        if (empty($this->_manifest)) {
            return $this->throwError('Template manifest not loaded yet.');
        }
        
        if (empty($this->_template_preview)) {
            $preview = & $this->_getResource('preview');
            
            if ($preview) {
                $els = & $preview->getElementsByTagName('file');
                if ($els->getLength() > 0) {
                    $el  = $els->item(0);

                    $this->_template_preview = trim($preview->getAttribute('xml:base')) . trim($el->getAttribute('href'));
                }
            }
        }
        
        return $this->_template_preview;
    }
    
    function &_getView($name, $tpl_inherits) {
        if (empty($this->_manifest)) {
            return $this->throwError('Template manifest not loaded yet.');
        }
        
        $els = & $this->_manifest->getElementsByTagName('view');
        if ($els->getLength() <= 0) {
            return $this->throwError('Invalid manifest; no view(s) defined.');
        }
        
        $view_tokens      = split(':', $name);
        $view_token_count = count($view_tokens);
        
        //holds the resulting <view> tag resource pointer
        $view  = null;
        $views = array();
        
        //find the correct <view> tags for this specific view.
        for ($i = 0; $i < $els->getLength(); $i++) {
            $res = & $els->item($i);
            if ($res->hasAttribute('name')) {
                if ($res->getAttribute('name') == $name) {
                    $views[] = & $res;
                } else if ($res->hasAttribute('inherits')) {
                    $list = split(',', $res->getAttribute('inherits'));
                    foreach ($list as $inherit) {
                        $inh_tokens = split(':', $inherit);
                        //loop through the tokens FORWARD, to make wildcard
                        //matching possible for a <view> tag (i.e. 'gallery:*')
                        for ($j = 0; $j < count($inh_tokens) &&
                          isset($view_tokens[$j]); $j++) {
                            $val = trim($inh_tokens[$j]);
                            if ($val == "*") {
                                $views[] = & $res;
                                break;
                            } else if ($val == trim($view_tokens[$j])) {
                                if ($j == ($view_token_count - 1)) {
                                    $views[] = & $res;
                                    break;
                                } else if ($j >= $view_token_count) {
                                    break;
                                }
                                //only here the loop will continue forward
                                //(nothing is returned...yet)
                            } else {
                                break;
                            }//if
                        }//for
                    }//foreach
                }//if
            }//if
        }//for
        
        $tpl_tokens  = split(':', $tpl_inherits);
        $tpl_token_count = count($tpl_tokens);
        //now, search for THE ONE view that matches with one of the views we retrieved
        foreach ($views as &$a_view) {
            $a_view_tokens = split(':', $a_view->getAttribute('name'));
            //loop through the tokens FORWARD, to make wildcard
            //matching possible for a <view> tag (i.e. 'gallery:*')
            for ($k = 0; $k < count($a_view_tokens) && isset($view_tokens[$k]); $k++) {
                $val = trim($a_view_tokens[$k]);
                if ($val == "*") {
                    return $a_view;
                } else if ($val == trim($tpl_tokens[$k])) {
                    if ($k == ($tpl_token_count - 1)) {
                        return $a_view;
                    } else if ($k >= $tpl_token_count) {
                        break;
                    }
                    //only here the loop will continue forward
                    //(nothing is returned...yet)
                } else {
                    break;
                }
            }
        }

        $views = null;

        return $views;
    }
    
    function &_getResource($name, $view = null, $type = 'html') {
        if (empty($this->_manifest)) {
            return $this->throwError('Template manifest not loaded yet.');
        }

        $els = & $this->_manifest->getElementsByTagName('resource');
        if ($name == "html_head") {
            $files = array();
            for ($i = 0; $i < $els->getLength(); $i++) {
                $res = & $els->item($i);
                if ($res->hasAttribute('name')) {
                    if ($res->getAttribute('name') == "stylesheet"
                      || $res->getAttribute('name') == "javascript") {
                        $files[] = & $res;
                    }
                }
            }
            return $files;
        } else if ($name == "preview") {
            for ($i = 0; $i < $els->getLength(); $i++) {
                $res = & $els->item($i);
                if ($res->hasAttribute('name')) {
                    if ($res->getAttribute('name') == "preview") {
                        return $res;
                    }
                }
            }
            return "";
        } else if ($name == "template") {
            if (empty($view))
                $view = $this->_active_view;
                
            $tpls = array();
            //build an array of template resources that match our primary 
            //criteria (it MUST have a name attribute and MUST match a viewType).
            for ($i = 0; $i < $els->getLength(); $i++) {
                $res = & $els->item($i);
                if ($res->hasAttribute('name') && $res->hasAttribute('type')) {
                    if ($res->getAttribute('name') == "template"
                      && $res->getAttribute('type') == $type
                      && $res->hasAttribute('view')) {
                        $tpls[] = & $res;
                    }
                }
            }
            //first, look if the view is attached to a seperate template resource
            //(i.e. stand-alone, no view-inheritance)
            for ($j = 0; $j < count($tpls); $j++) {
                if ($tpls[$j]->getAttribute('view') == $view) {
                    return $tpls[$j];
                }
            }
            //not found. Try inheritance
            for ($j = 0; $j < count($tpls); $j++) {
                $inherited = & $this->_getView($view, $tpls[$j]->getAttribute('view'));
                if (isset($inherited) && !empty($inherited)) {
                    return $tpls[$j];
                }
            }
        }
        
        return $this->throwError('Invalid '.$name.'-resource type for view: '.$view);
    }

    function getHTMLHeaders($path_prefix = "") {
        $ret = array();
        
        if ($this->_type != "html")
            return $ret;
        
        $headers = & $this->_getResource('html_head');
        $path_prefix .= DS.$this->_manifest->documentElement->getAttribute('xml:base');
        foreach ($headers as &$res) {
            $name = $res->getAttribute('name');
            $dir  = rtrim($path_prefix . $res->getAttribute('xml:base'), '/\\');
            $url  = str_replace('\\', '/', $dir . '/' . $res->firstChild->getAttribute('href'));
            // Replace spaces
            $url = preg_replace('/\s/', '%20', $url);

            if ($name == "stylesheet") {
                $ret[] = '<link rel="stylesheet" href="' . $url
                 . '" type="text/css"/>';
            } else if ($name == "javascript") {
                $ret[] = '<script src="' . $url
                 . '" type="text/javascript"></script>';
            }
        }
        return $ret;
    }

    function _loadManifest() {
        $cachefile = $this->cache_dir .DS.$this->_active_template."_tpl.cache";
        if (file_exists($cachefile)) {
            $this->_manifest = & unserialize(file_get_contents($cachefile));
        } else {
            $tpl_file = $this->template_dir.DS.$this->_active_template.DS."manifest.xml";

            if (!file_exists($tpl_file))
                return $this->throwError('Template manifest not found ('.$tpl_file.').');

            zmgimport('org.zoomfactory.lib.domit.xml_domit_lite_include');
            $this->_manifest = & new DOMIT_Lite_Document();
            $this->_manifest->resolveErrors(true);
            
            if (!$this->_manifest->loadXML($tpl_file, false, true)) {
                unset($this->_manifest);
                return $this->throwError('DOMIT error: could not open document.');
            }
            
            if ($this->_manifest->documentElement->getTagName() != "manifest") {
                unset($this->_manifest);
                return $this->throwError('Invalid template manifest.');
            }
            
            //set the basedir of the template where we can find the .tpl files
            if ($this->_manifest->documentElement->hasAttribute('xml:base')) {
                //class variable '$this->template_dir' is inherited from Smarty
                $this->template_dir .= DS.$this->_manifest->documentElement->getAttribute('xml:base');
            }
        }
    }

    function _cacheManifest(&$doc) {
        //TODO: what if the manifest changed? Need to implement proper caching
        //Cache_Lite, maybe?
        if (is_writable($this->cache_dir) && !empty($this->_manifest)) {
            zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');
            zmgFileHelper::write($this->cache_dir .DS.$this->_active_template.'_tpl.cache',
              serialize($this->_manifest));
        } else {
            $this->throwError('ZMG cache directory not writable.');
        }
    }
    
    function throwError($message) {
        if (true) {//!zmgEnv::isRPC()) {
            return zmgError::throwError($message);
        } else {
            return zmgFactory::getRequest()->sendHeaders($this->_type, true, $message);
        }
    }
    
    function toJSON() {
        //TODO
    }
    
    /***************************************************************************
    * Start of abstract functions 
    ***************************************************************************/
    
    function getTemplates() {
        if (isset($this) && is_a($this, 'zmgTemplateHelper')) {
            return $this->throwError('This function may only be called statically!');
        }
        
        $basePath   = ZMG_ABS_PATH . DS.'var'.DS.'www'.DS.'templates';
        $baseConfig = & zmgFactory::getConfig()->get('smarty');
        
        
        zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');
        $dirs = zmgFileHelper::readDir($basePath, '[^index\.html]');
        
        $tpls = array();
        
        foreach($dirs as $dir) {
            if ($dir == "shared" || $dir == "admin") {
                continue; //TODO: catch this inside the regex above ('[^index\.html]')...
            }
            
            if (is_dir($basePath . DS.$dir) && zmgFileHelper::exists($basePath .DS.$dir.DS.'manifest.xml')) {
                $baseConfig['activetemplate'] = $dir;
                $tpls[] = new zmgTemplateHelper($baseConfig, '', true);
            }
        }
        return $tpls;
    }
}
?>
