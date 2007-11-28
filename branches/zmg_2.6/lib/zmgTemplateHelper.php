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

    /**
     * The class constructor.
     */
    function zmgTemplateHelper(&$config) {
        //Smarty options:
        $this->template_dir = $config['template_dir'];
        $this->compile_dir  = $config['compile_dir'];
        $this->cache_dir    = $config['cache_dir'];
        $this->config_dir   = $config['config_dir'];
        
        //Helper options:
        $this->_active_template = $config['active_template'];
        $this->_loadManifest();
    }

    function set($view) {
        if (empty($view))
            return zmgError::throwError('No view specified.');
        $this->_active_view = $view;
    }

    function run() {
        if (empty($this->_active_view))
            return zmgError::throwError('No active view specified. Unable to run application.');

        //get template file that belongs to the active view:
        $res = & $this->_getResource('template', $this->_active_view);
        if ($res) {
            $tpl_file = trim($res->firstChild->getAttribute('href'));

            $this->display($tpl_file);
        } else {
            return zmgError::throwError('No template resource found. Unable to run application.');
        }
    }
    
    function _getInfo() {
        if (empty($this->_manifest))
            return zmgError::throwError('Template manifest not loaded yet.');
            
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
            return zmgError::throwError('Template manifest not loaded yet.');
        
        if (empty($this->_template_name)) {
            $els = & $this->_manifest->getElementsByTagName('template');
            if ($els->getLength() < 1)
                return;
            
            $profile = & $els->item(0);
            $this->_template_name = trim($profile->getAttribute('name'));
        }
        
        return $this->_template_name;
    }
    
    function &_getView($name, $inheritedBy = null) {
        if (empty($this->_manifest))
            return zmgError::throwError('Template manifest not loaded yet.');
        
        $els = & $this->_manifest->getElementsByTagName('view');
        if ($els->getLength() <= 0)
            return zmgError::throwError('Invalid manifest; no view(s) defined.');
        
        $view_tokens = split(':', $name);
        for ($i = 0; $i < $els->getLength(); $i++) {
            $res = & $els->item($i);
            if ($res->hasAttribute('name')) {
                if (!empty($inheritedBy) && $res->hasAttribute('inherits')) {
                    $list = split(',', $res->getAttribute('inherits'));
                    foreach ($list as $inherit) {
                        $inh_tokens = split(':', $inherit);
                        //loop through the tokens FORWARD, to make wildcard
                        //matching possible (i.e. 'gallery:*')
                        for ($j = 0; $j < count($inh_tokens) &&
                          isset($view_tokens[$j]); $j++) {
                            $val = trim($inh_tokens[$j]);
                            if ($val == "*") {
                                return $res;
                            } else if ($val == trim($view_tokens[$j])) {
                                return $res;
                            }
                        }
                    }
                } else if ($res->getAttribute('name') == $name) {
                    return $res;
                }
            }
        }
        
        return null;
    }
    
    function &_getResource($type, $view = null) {
        if (empty($this->_manifest))
            return zmgError::throwError('Template manifest not loaded yet.');

        $els = & $this->_manifest->getElementsByTagName('resource');
        if ($type == "html_head") {
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
        } else if ($type == "preview") {
            for ($i = 0; $i < $els->getLength(); $i++) {
                $res = & $els->item($i);
                if ($res->hasAttribute('name')) {
                    if ($res->getAttribute('name') == "preview") {
                        return $res;
                    }
                }
            }
            return "";
        } else if ($type == "template") {
            if (empty($view))
                $view = $this->_active_view;
                
            $tpls = array();
            for ($i = 0; $i < $els->getLength(); $i++) {
                $res = & $els->item($i);
                if ($res->hasAttribute('name')) {
                    if ($res->getAttribute('name') == "template"
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
        
        return zmgError::throwError('Invalid resource type.');
    }

    function getHTMLHeaders($path_prefix = "") {
        $headers = & $this->_getResource('html_head');
        $path_prefix .= DS.$this->_manifest->documentElement->getAttribute('xml:base');
        $ret = array();
        foreach ($headers as &$res) {
            $name = $res->getAttribute('name');
            $dir  = $res->getAttribute('xml:base');
            $file = $res->firstChild->getAttribute('href');
            if ($name == "stylesheet") {
                $ret[] = '<link rel="stylesheet" href="' . $path_prefix.$dir.$file . '" type="text/css"/>';
            } else if ($name == "javascript") {
                $ret[] = '<script src="' . $path_prefix.$dir.$file . '" type="text/javascript"></script>';
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
                return zmgError::throwError('Template manifest not found ('.$tpl_file.').');

            require_once(ZMG_ABS_PATH . DS.'lib'.DS.'domit'.DS.'xml_domit_lite_include.php');
            $this->_manifest = & new DOMIT_Lite_Document();
            $this->_manifest->resolveErrors(true);
            
            if (!$this->_manifest->loadXML($tpl_file, false, true)) {
                unset($this->_manifest);
                return zmgError::throwError('DOMIT error: could not open document.');
            }
            
            if ($this->_manifest->documentElement->getTagName() != "manifest") {
                unset($this->_manifest);
                return zmgError::throwError('Invalid template manifest.');
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
            zmgError::throwError('ZMG cache directory not writable.');
        }
    }
}
?>