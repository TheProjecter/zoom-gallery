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

class zmgPluginHelper extends zmgError {
    
    var $_plugins = null;
    
    function zmgPluginHelper() {
        $this->_loadPlugins();
    }
    
    function embed() {
        foreach ($this->_plugins as $cat => $plugins) {
            $plugin_path = ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.$cat;
            if (file_exists($plugin_path . DS.$cat.'.plugin.php')) {
                require_once($plugin_path . DS.$cat.'.plugin.php');
                $class = 'zmg' . ucfirst($cat) . 'Plugin';
                if (class_exists($class)) {
                    eval($class . '::embed();');
                } else {
                    zmgError::throwError('zmgPluginHelper: class does not exist!');
                }
            } else {
                //TODO: implement support for other plugin types
                if (is_array($plugins)) {
                    foreach ($plugins as $plugin) {
                        //TODO
                    }
                }
            }
        }
    }
    
    function _loadPlugins() {
        $plugin_cats = zmgReadDirectory(ZMG_ABS_PATH . DS.'var'.DS.'plugins', '[^index\.html]');
        $this->_plugins = array();
        foreach ($plugin_cats as $cat) {
            if ($cat != "shared") {
                $plugins = zmgReadDirectory(ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS . $cat, '[^index\.html]');
                if (is_array($plugins) && count($plugins) > 0) {
                    $this->_plugins[$cat] = $plugins;
                }
            }
        }
    }
}
?>