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
 * Class that assists Zoom in retriving and storing application settings
 * @package zmg
 */
class zmgConfigurationHelper extends zmgError {
    /**
     * Internal variable for the configuration array.
     *
     * @var array
     */
    var $_config = null;
    /**
     * The class constructor.
     *
     * Load all the configuration settings as set in /etc/app.config.php into
     * a class variable (scoped). 
     */
    function zmgConfigurationHelper(&$config) {
        $this->_config = $config;
        $config = null;
    }
    /**
     * Retrieve a specific configuration setting.
     * @param string The name of the setting in the format of a pathname: 'group/setting'
     * @return string
     */
    function &get($path) {
        $path_tokens = explode("/", $path);
        $config_val  = & $this->_config;
        for ($i = 0; $i < count($path_tokens); $i++) {
            if (isset($config_val[$path_tokens[$i]])) {
                $config_val = & $config_val[$path_tokens[$i]];
            }
        }
        return $config_val;
    }
    /**
     * Set a specific configuration setting.
     * @param string The name of the setting in the format of a pathname: 'group/setting'
     * @param mixed The new value for the setting as defined in the path up.
     * @return boolean
     */
    function set($path, $value) {
        $path_tokens = explode("/", $path);
        $config_val  = &$this->_config;
        for ($i = 0; $i < count($path_tokens); $i++) {
            if (isset($config_val[$path_tokens[$i]])) {
                $config_val = & $config_val[$path_tokens[$i]];
            } else {
                //path appears to be invalid
                $config_val = null;
                break;
            }
        }
        if ($config_val) {
            $config_val = $value;
            return true;
        }
        return false;
    }
    function update() {
        
    }
    function save() {
        
    }
    function _buildMetaBlock() {
        
    }
    function _buildLocaleBlock() {
        
    }
    function _buildDatabaseBlock() {
        
    }
    function _buildFilesystemBlock() {
        
    }
    function _buildSmartyBlock() {
        
    }
    function _buildLayoutBlock() {
        
    }
    function _buildAppBlock() {
        
    }
}
?>