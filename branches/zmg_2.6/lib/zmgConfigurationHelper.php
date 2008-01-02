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
                if ($i == (count($path_tokens) - 1)) {
                    $config_val[$path_tokens[$i]] = $value;
                    return true;
                }
                $config_val = & $config_val[$path_tokens[$i]];
            } else {
                return false;
            }
        }
        return false;
    }
    function update($vars, $isPlugin = false) {
        $updated = false;
        if (!$isPlugin) {
            foreach ($vars as $config => $value) {
                $config = trim($config);
                if (strstr($config, 'zmg_')) {
                    $real = str_replace('_', '/', str_replace('zmg_', '', $config));
                    if ($this->set($real, zmgSQLEscape(trim($value)))) {
                        $updated = true;
                    }
                }
            }
        } else {
            echo "updating plugin config..";
            $keys = array_keys($vars);
            if (!$this->_config['plugins'][$keys[0]]) {
                array_merge($this->_config, $vars);
                print_r($this->_config);
                $updated = true;
            }
        }
        
        $zoom = & zmgFactory::getZoom();
        
        if ($updated) {
            if ($this->save()) {
                $zoom->messages->append(T_('Settings'), T_('Your settings have been saved successfully.'));
                return true;
            }
        }
        $zoom->messages->append(T_('Settings'), T_('Your settings could not be saved.'));
        return false;
    }
    
    function fromPlugin($plugin) {
        if (isset($this->_config['plugins'][$plugin['name']])) {
            //zmgError::throwError('Config already exists!'); //TEMP ECHO
            return;
        }

        $this->_config['plugins'][$plugin['name']] = array();
        
        foreach ($plugin['settings'] as $cat => $settings) {
            $this->_config['plugins'][$plugin['name']][$cat] = array();
            foreach ($settings as $name => $setting) {
                $this->_config['plugins'][$plugin['name']][$cat][$name] = $setting['default'];
            }
        }
        
        $this->save();
    }
    
    function save() {
        $content = "<?php\n"
         . "/**\n"
         . " * zOOm Media Gallery! - a multi-gallery component\n" 
         . " * \n"
         . " * @package zmg\n"
         . " * @author Mike de Boer <mdeboer AT ebuddy.com>\n"
         . " * @copyright Copyright &copy; 2007, Mike de Boer. All rights reserved.\n"
         . " * @license http://www.gnu.org/copyleft/gpl.html GPL\n"
         . " */\n\n"
         . "defined('_ZMG_EXEC') or die('Restricted access');\n\n"
         . "\$zoom_config = array();\n"
         . $this->_buildMetaBlock() . $this->_buildLocaleBlock()
         . $this->_buildDatabaseBlock() . $this->_buildFilesystemBlock()
         . $this->_buildSmartyBlock() . $this->_buildLayoutBlock()
         . $this->_buildAppBlock() . "\n"
         . "\$zoom_config['events'] = array();\n\n"
         . $this->_buildPluginsBlock()
         . "?>\n";
        //echo $content; 
        return zmgWriteFile(ZMG_ABS_PATH .DS.'etc'.DS.'app.config.php', $content);
    }
    function _buildMetaBlock() {
        return $this->_generateBlock("\$zoom_config", 'meta',
          $this->_config['meta']);
    }
    function _buildLocaleBlock() {
        return $this->_generateBlock("\$zoom_config", 'locale',
          $this->_config['locale']);
    }
    function _buildDatabaseBlock() {
        return $this->_generateBlock("\$zoom_config", 'db',
          $this->_config['db']);
    }
    function _buildFilesystemBlock() {
        return $this->_generateBlock("\$zoom_config", 'filesystem',
          $this->_config['filesystem']);
    }
    function _buildSmartyBlock() {
        return $this->_generateBlock("\$zoom_config", 'smarty',
          $this->_config['smarty']);
    }
    function _buildLayoutBlock() {
        return $this->_generateBlock("\$zoom_config", 'layout',
          $this->_config['layout']);
    }
    function _buildAppBlock() {
        //TODO: find a way to process constants - how to put them back in the config file?
        return $this->_generateBlock("\$zoom_config", 'app',
          $this->_config['app']);
    }
    function _buildPluginsBlock() {
        return $this->_generateBlock("\$zoom_config", 'plugins',
          $this->_config['plugins']);
    }
    function _generateBlock($prefix, $title, $value) {
        $block = "";
        if (is_array($value)) {
            $prefix = $prefix . "['{$title}']";
            $block .= $prefix . " = array();\n";
            foreach ($value as $title2 => $value2) {
                $block .= zmgConfigurationHelper::_generateBlock($prefix,
                  $title2, $value2);
            }
        } else {
            if (is_string($value)) {
                $value = "\"{$value}\"";
            }
            $block .= "{$prefix}['{$title}'] = {$value};\n";
        }
        return $block;
    }
}
?>