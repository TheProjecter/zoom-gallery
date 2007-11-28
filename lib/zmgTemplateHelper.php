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
    
    var $_active_view = null;

    /**
     * The class constructor.
     */
    function zmgTemplateHelper($template) {
        $this->_cache_dir = ZMG_ABS_PATH . DS."etc".DS."cache";
        $this->_active_template = $template;
        $this->_loadManifest();
    }

    function set() {
        
    }

    function run() {
        if (empty($this->_active_view))
            return zmgError::throwError('No active view specified. Unable to run application.');

        //TODO.
    }
    
    function _getInfo() {
        if (empty($this->_manifest))
            return zmgError::throwError('Template manifest not loaded yet.');
            
        $els = & $this->_manifest->getElementsByTagName('template');
        if ($els.getLength() < 1)
            return;
        
        $info = array();
        $profile = & $els->item(0);
        for ($i = 0; $i < $profile.childCount; $i++) {
            $info[$profile->childNodes[$i]->nodeName] = $profile->childNodes[$i]->nodeValue; 
        }
        
        return $info;
    }
    
    function &_getView($name, $inheritedBy = null) {
        if (empty($this->_manifest))
            return zmgError::throwError('Template manifest not loaded yet.');
        
        $els = & $this->_manifest->getElementsByTagName('view');
        if ($els.getLength() <= 0)
            return zmgError::throwError('Invalid manifest; no view(s) defined.');
        
        for ($i = 0; $i < $els.getLength(); $i++) {
            $res = & $els->item($i);
            if ($res->hasAttribute('name')) {
                if (!empty($inheritedBy) && $res->hasAttribute('inherits')) {
                    $list = split(',', $res->getAttribute('inherits'));
                    if (count($list) > 0) {
                        for ($j = 0; $j < count($list); $j++) {
                            if (trim($list[$j]) == $name) {
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
            for ($i = 0; $i < $els.getLength(); $i++) {
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
            for ($i = 0; $i < $els.getLength(); $i++) {
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
            for ($i = 0; $i < $els.getLength(); $i++) {
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
                if ($tpls[$j].getAttribute('view') == $view) {
                    return $tpls[$j];
                }
            }
            //not found. Try inheritance
            for ($j = 0; $j < count($tpls); $j++) {
                $inherited = & $this->_getView($view, $tpls[$j].getAttribute('view'));
                if (isset($inherited) && !empty($inherited)) {
                    return $tpls[$j];
                }
            }
        }
        
        return zmgError::throwError('Invalid resource type.');
    }

    function _getTemplate($tpl = 'default') {
        
    }

    function _loadManifest() {
        $cachefile = $this->_cache_dir .DS.$this->_template."_tpl.cache";
        if (file_exists($cachefile)) {
            $this->_manifest = & unserialize(file_get_contents($cachefile));
        } else {
            $tpl_file = $this->_cache_dir .DS."www".DS."var".DS."templates".DS
              . $this->_template . DS."manifest.xml";

            if (!file_exists($tpl_file))
                return zmgError::throwError('Template manifest not found.');

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
        }
    }

    function _cacheManifest(&$doc) {
        //TODO: what if the manifest changed? Need to implement proper caching
        if (is_writable($this->_cache_dir) && !empty($this->_manifest)) {
            zmgWriteFile($this->_cache_dir .DS.$this->_template.'_tpl.cache',
              serialize($this->_manifest));
        } else {
            zmgError::throwError('ZMG cache directory not writable.');
        }
    }
}
?>