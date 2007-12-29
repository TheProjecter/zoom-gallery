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
        $zoom = & zmgFactory::getZoom();
        foreach ($this->_plugins as &$plugin) {
            $plugin_path = ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.$plugin['name'];
            if (file_exists($plugin_path . DS.$plugin['name'].'.plugin.php')) {
                require_once($plugin_path . DS.$plugin['name'].'.plugin.php');
                $class = 'zmg' . ucfirst($plugin['name']) . 'Plugin';
                if (class_exists($class)) {
                    eval($class . '::embed();');
                    if (file_exists($plugin_path .DS.'settings.xml')) {
                        $this->_embedSettings(&$plugin, $plugin_path . DS.'settings.xml');
                    }
                } else {
                    zmgError::throwError('zmgPluginHelper: class does not exist!');
                }
            } else {
                //TODO: implement support for other plugin types
            }
        }
    }
    
    function _embedSettings(&$plugin, $xml_path) {
        //echo "DEBUG: ".$xml_path;
        
        if (!file_exists($xml_path))
            return $this->throwError('Settings file not found ('.$xml_path.').');

        require_once(ZMG_ABS_PATH . DS.'lib'.DS.'domit'.DS.'xml_domit_lite_include.php');
        $xmldoc = & new DOMIT_Lite_Document();
        $xmldoc->resolveErrors(true);
        
        if (!$xmldoc->loadXML($xml_path, false, true)) {
            unset($xmldoc);
            return $this->throwError('DOMIT error: could not open document.');
        }
        
        if ($xmldoc->documentElement->getTagName() != "settings") {
            unset($xmldoc);
            return $this->throwError('Invalid plugin settings file.');
        }
        
        $zoom = & zmgFactory::getZoom();
        
        $plugin['settings'] = array();
        
        $els = & $xmldoc->getElementsByTagName('category');
        for ($i = 0; $i < $els->getLength(); $i++) {
            $res = & $els->item($i);
            if ($res->hasAttribute('name')) {
                $cat = $res->getAttribute('name');
                $plugin['settings'][$cat] = array();
                $settings = & $res->getElementsByTagName('setting');
                for ($j = 0; $j < $settings->getLength(); $j++) {
                    $setting = & $settings->item($j);
                    if (!$setting->hasAttribute('name')) {
                        zmgError::throwError('zmgPluginHelper::invalid setting in '.$xml_path);
                        continue;
                    }
                    $name = trim($setting->getAttribute('name'));
                    $plugin['settings'][$cat][$name] = array();
                    if ($setting->hasAttribute('type')) {
                        $type = $setting->getAttribute('type');
                        $plugin['settings'][$cat][$name]['type'] = $type;
                        if ($type == "select") {
                            $options = & $setting->getElementsByTagName('option');
                            
                            for ($k = 0; $k < $options->getLength(); $k++) {
                                $option = & $options->item($k);
                                $plugin['settings'][$cat][$name]['option' . $k]
                                  = array('value' => $option->getAttribute('value'),
                                   'caption' => $option->firstChild->nodeValue);
                            }
                        }
                    }
                    if ($setting->hasAttribute('size')) {
                        $plugin['settings'][$cat][$name]['size']
                          = $setting->getAttribute('size');
                    }
                    if ($setting->hasAttribute('default')) {
                        $plugin['settings'][$cat][$name]['default']
                          = $setting->getAttribute('default');
                    }
                    if ($setting->hasAttribute('value')) {
                        $plugin['settings'][$cat][$name]['value']
                          = $setting->getAttribute('value');
                    }
                    if ($setting->hasAttribute('label')) {
                        $plugin['settings'][$cat][$name]['label']
                          = $setting->getAttribute('label');
                    }
                    if ($setting->hasAttribute('description')) {
                        $plugin['settings'][$cat][$name]['description']
                          = $setting->getAttribute('description');
                    }
                    if ($setting->hasAttribute('disabled')) {
                        $plugin['settings'][$cat][$name]['disabled']
                          = ($setting->getAttribute('description') == "true") ? true : false;
                    } else {
                        $plugin['settings'][$cat][$name]['disabled'] = false;
                    }
                    if ($setting->hasAttribute('readonly')) {
                        $plugin['settings'][$cat][$name]['readonly']
                          = ($setting->getAttribute('readonly') == "true") ? true : false;
                    } else {
                        $plugin['settings'][$cat][$name]['readonly'] = false;
                    }
                }
            }
        }
        
        //TODO: access fromXML with a public API call
        $zoom->_config->fromPlugin($plugin);
    }
    
    function embedHTML() {
        $zoom = & zmgFactory::getZoom();
        
        $out  = "<div class=\"zmg_halfsize\">\n";
        
        foreach ($this->_plugins as $plugin) {
            $name      = ucfirst($plugin['name']);
            $settings  = $plugin['settings'];
            if (is_array($settings)) {
                $out .= "<fieldset><legend>" . T_($name) . "</legend>\n";
                foreach ($settings as $cat => $sub_settings) {
                    $out .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\" class=\"adminform\">\n"
                     . "<tr>\n"
                     . "\t<td colspan\"2\">\n"
                     . "\t\t<h3>" . T_(ucfirst($cat)) . "</h3>\n"
                     . "\t</td>\n"
                     . "</tr>\n";
                    foreach ($sub_settings as $setting => $data) {
                        $setting_parts = array('plugins', $plugin['name'], $cat, $setting);
                        $setting_value = $zoom->getConfig(implode('/', $setting_parts));
                        $input_name    = "zmg_" . implode('_', $setting_parts);
                        
                        $out .= "<tr>\n";
                        switch ($data['type']) {
                            case "checkbox":
                                $checked = ($setting_value == $data['value']) ? true : false;
                                $out .= "\t\t<td>&nbsp;</td><td>" . $this->_buildCheckboxInput($input_name, $data['value'], $checked)
                                 . T_($data['label']) . "</td>\n";
                                break;
                            case "radio":
                                $checked = ($setting_value == $data['value']) ? true : false;
                                $out .= "\t\t<td>&nbsp;</td><td>" . $this->_buildRadioInput($input_name, $data['value'], $checked)
                                 . T_($data['label']) . "</td>\n";
                                break;
                            case "select":
                                $options = array();
                                foreach ($data as $key => $value) {
                                    if (stristr($key, 'option')) {
                                        $options[] = $value;
                                    }
                                }
                                $out .= "\t\t<td width=\"250\">" . T_($data['label']) . "</td><td>"
                                 . $this->_buildSelectInput($input_name, $options, $setting_value) . "</td>\n";
                                break;
                            case "text":
                                $out .= "\t\t<td width=\"250\">" . T_($data['label']) . "</td><td>"
                                 . $this->_buildTextInput($input_name, $setting_value, $data['size']) . "</td>\n";
                                break;
                        }
                        $out .= "</tr>\n";
                    }
                    
                    $out .= "</table>\n"; 
                }
                $out .= "</fieldset>\n";
            } else {
                //$out .= T_('No settings found for this plugin.');
            }
        }
        
        return $out . "</div>\n";
    }
    
    function _buildCheckboxInput($name, $value, $checked, $disabled = false) {
        return '<input type="checkbox" name="' . $name . '" value="'
          . $value . '"' . ($checked ? ' checked="checked"' : '')
          . ($disabled ? ' disabled="disabled"' : '') . '/>';
    }
    
    function _buildRadioInput($name, $value, $checked, $disabled = false) {
        return '<input type="radio" name="' . $name . '" value="'
          . $value . '"' . ($checked ? ' checked="checked"' : '')
          . ($disabled ? ' disabled="disabled"' : '') . '/>';
    }
    
    function _buildSelectInput($name, $options, $selected, $disabled = false) {
        $html = '<select name="' . $name . '"'
          . ($disabled ? ' disabled="disabled"' : '') . '>';
        foreach ($options as $option) {
            $html .= '<option value="' . $option['value'] . '"' . (($option['value'] == $selected) ? ' selected' : '') . '>'
              . T_($option['caption']) . '</option>';
        }
        return $html . '</select>';
    }
    
    function _buildTextInput($name, $value, $size = 50, $disabled = false, $readonly = false) {
        return '<input type="text" name="' . $name . '" size="' . $size . '" value="'
          . $value . '"' . ($disabled ? ' disabled="disabled"' : '')
          . ($readonly ? ' readonly="readonly"' : '') . '/>';
    }
    
    function _loadPlugins() {
        $plugin_cats = zmgReadDirectory(ZMG_ABS_PATH . DS.'var'.DS.'plugins', '[^index\.html]');
        $this->_plugins = array();
        foreach ($plugin_cats as $plugin) {
            if ($plugin != "shared") {
                $content = zmgReadDirectory(ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS . $plugin, '[^index\.html]');
                if (is_array($content) && count($content) > 0) {
                    $this->_plugins[] = array('name' => $plugin, 'settings' => null);
                }
            }
        }
    }
}
?>