<?php
/**
 * zOOm Media Gallery! - a multi-gallery component 
 * 
 * @package zmg
 * @version $Revision$
 * @author Mike de Boer <mdeboer AT ebuddy.com>
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
    
    var $_active_template = null;
    
    var $_template_name = null;
    
    var $_active_view = null;
    
    var $_active_subview = null;
    
    var $_viewtype = null;
    
    var $_secret = null;

    /**
     * The class constructor.
     */
    function zmgTemplateHelper(&$config, $secret) {
        //Smarty options:
        $this->template_dir = $config['template_dir'];
        $this->compile_dir  = $config['compile_dir'];
        $this->cache_dir    = $config['cache_dir'];
        $this->config_dir   = $config['config_dir'];
        
        //Helper options:
        if (ZMG_ADMIN) {
            $this->_active_template = "admin";
        } else {
            $this->_active_template = $config['active_template'];
        }
        $this->_secret = $secret;
        
        $this->_loadManifest();
    }
    
    function setViewType($view = 'html') {
        $this->_viewtype = $view;
    }

    function set($view) {
        if (empty($view))
            return $this->throwError('No view specified.');
        $this->_active_view = $view;
    }
    
    function get() {
        return $this->_active_view;
    }
    
    function getSubView() {
        if (!$this->_active_subview) {
            $view_tokens = split(':', $this->_active_view);
            $this->_active_subview = $view_tokens[count($view_tokens) - 1];
        }
        return $this->_active_subview;
    }

    function run(&$zoom) {
        if (empty($this->_active_view))
            return $this->throwError('No active view specified. Unable to run application.');
            
        if (zmgEnv::isRPC() && $this->_active_view == "ping") {
            Zoom::sendHeaders($this->_viewtype);
            echo "                                         ";
            return;
        }

        //mootools & Ajax preparing stuff
        if ($this->_viewtype == "html") {
            zmgEnv::includeMootools();
            zmgEnv::appendPageHeader($this->_prepareAjax());
        }

        //put the HTML headers in the head section of the parent (hosting) document
        $headers = $this->getHTMLHeaders(zmgEnv::getSiteURL()
          . '/components/com_zoom/var/www/templates');
        foreach ($headers as $header) {
            zmgEnv::appendPageHeader($header);
        }
        
        //get template file that belongs to the active view:
        $res = & $this->_getResource('template', $this->_active_view,
          $this->_viewtype);
        if ($res) {
            $tpl_file = trim($res->firstChild->getAttribute('href'));

            $this->assign('zoom', $zoom);
            
            $this->assign('subview', $this->getSubView());
            
            $this->assign('site_url', zmgEnv::getSiteURL());

            $this->display($tpl_file);
        } else {
            return $this->throwError('No template resource found. Unable to run application.');
        }
    }
    
    function _prepareAjax() {
        return ("<script language=\"javascript\" type=\"text/javascript\">\n"
         . "<!--\n"
         . "\tif (!window.ZMG) window.ZMG = {};\n"
         . "\tZMG.CONST = {};\n"
         . "\tZMG.CONST.id          = '".md5($this->_secret)."';\n"
         . "\tZMG.CONST.active_view = '".$this->_active_view."';\n"
         . "\tZMG.CONST.is_admin    = ".((ZMG_ADMIN) ? "true" : "false")."\n"
         . "\tZMG.CONST.site_uri    = document.location.protocol + '//' + document.location.host + document.location.pathname.replace(/\/(administrator\/)?index(2)?\.php$/i, '');\n"
         . "\tZMG.CONST.req_uri     = ZMG.CONST.site_uri + \"".zmgEnv::getAjaxURL()."\";\n"
         . "\tZMG.CONST.res_path    = ZMG.CONST.site_uri + \"/components/com_zoom/var/www/templates/"
         . $this->_active_template."\";\n"
         . "//-->\n"
         . "</script>\n");
    }
    
    function _getInfo() {
        if (empty($this->_manifest))
            return $this->throwError('Template manifest not loaded yet.');
            
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
        if (empty($this->_manifest))
            return $this->throwError('Template manifest not loaded yet.');
        
        if (empty($this->_template_name)) {
            $els = & $this->_manifest->getElementsByTagName('template');
            if ($els->getLength() < 1)
                return;
            
            $profile = & $els->item(0);
            $this->_template_name = trim($profile->getAttribute('name'));
        }
        
        return $this->_template_name;
    }
    
    function &_getView($name, $tpl_inherits) {
        if (empty($this->_manifest))
            return $this->throwError('Template manifest not loaded yet.');
        
        $els = & $this->_manifest->getElementsByTagName('view');
        if ($els->getLength() <= 0)
            return $this->throwError('Invalid manifest; no view(s) defined.');
        
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
                            }
                        }
                    }
                }
            }
        }
        
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
        return null;
    }
    
    function &_getResource($name, $view = null, $type = 'html') {
        if (empty($this->_manifest))
            return $this->throwError('Template manifest not loaded yet.');

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
        
        if ($this->_viewtype != "html")
            return $ret;
        
        $headers = & $this->_getResource('html_head');
        $path_prefix .= DS.$this->_manifest->documentElement->getAttribute('xml:base');
        foreach ($headers as &$res) {
            $name = $res->getAttribute('name');
            $dir  = $res->getAttribute('xml:base');
            $file = $res->firstChild->getAttribute('href');
            if ($name == "stylesheet") {
                $ret[] = '<link rel="stylesheet" href="' . $path_prefix.$dir
                  .$file . '" type="text/css"/>';
            } else if ($name == "javascript") {
                $ret[] = '<script src="' . $path_prefix.$dir.$file
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

            require_once(ZMG_ABS_PATH . DS.'lib'.DS.'domit'.DS.'xml_domit_lite_include.php');
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
            zmgWriteFile($this->cache_dir .DS.$this->_active_template.'_tpl.cache',
              serialize($this->_manifest));
        } else {
            $this->throwError('ZMG cache directory not writable.');
        }
    }
    
    function throwError($message) {
        if (!zmgEnv::isRPC()) {
            return zmgError::throwError($message);
        } else {
            return Zoom::sendHeaders($this->_viewtype, true, $message);
        }
    }
}
?>