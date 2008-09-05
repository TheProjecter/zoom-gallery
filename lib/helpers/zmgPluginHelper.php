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

class zmgPluginHelper {
    
    var $_plugins = null;
    
    var $_events  = null;
    
    function zmgPluginHelper() {
        $this->_events = array();
        
        $this->_loadPlugins();
    }
    
    function _loadPlugins() {
        zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');
        
        $plugin_cats = zmgFileHelper::readDir(ZMG_ABS_PATH . DS.'var'.DS.'plugins', '[^index\.html]');
        $this->_plugins = array();
        foreach ($plugin_cats as $plugin) {
            if ($plugin != "shared") {
                $content = zmgFileHelper::readDir(ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS . $plugin, '[^index\.html]');
                if (is_array($content) && count($content) > 0) {
                    $plugin_class = "zmg" . ucfirst($plugin) . "Plugin";
                    
                    zmgimport('org.zoomfactory.var.plugins.'.$plugin.'.'.$plugin.'Plugin');
                    
                    $events = zmgCallAbstract($plugin_class, 'bindEvents');
                    if (is_array($events)) {
                        // update the PluginHelper's event registry
                        $this->_bindEvents($plugin_class, $events);
                    }
                    
                    $this->_plugins[] = array(
                        'name'      => $plugin,
                        'classname' => $plugin_class,
                        'settings'  => null,
                        'events'    => $events
                    );
                }
            }
        }
    }
    
    function _bindEvents($klass, $events = null) {
        if ($events === null || !is_array($events)) {
            zmgError::throwError('Incorrect usage of bindEvents()');
        }
        
        foreach ($events as $event => $functions) {
            if (!array_key_exists($event, $this->_events)
              || !is_array($this->_events[$event])) {
                $this->_events[$event] = array();
            }
            $this->_events[$event][$klass] = $functions;
        }
    }
    
    function bubbleEvent(&$event) {
        $res = array();
        
        if (isset($this->_events[$event->type]) && is_array($this->_events[$event->type])) {
            foreach ($this->_events[$event->type] as $klass => $functions) {
                if (is_string($functions)) {
                    $res[] = zmgCallAbstract($klass, $functions, $event);
                } else if (is_array($functions)) {
                    foreach ($functions as $function => $args) {
                        if (!is_array($args)) {
                            $args = array($args);
                        }
                        $event->mapArguments($args);
                        
                        $res[] = zmgCallAbstract($klass, $function, $event);
                    }
                }
            }
        }

        if (count($res) == 1) {
        	return $res[0];
        }
        return $res;
    }
    
    function isLoaded($name) {
        if (empty($name)) {
            return false;
        }
        $plugin = $this->get($name);
        return ($plugin !== false);
    }
    
    function &get($name) {
        foreach ($this->_plugins as &$plugin) {
            if ($plugin['name'] == $name) {
                return $plugin;
            }
        }
        return false;
    }
    
    function embedSettings(&$plugin, $xml_path) {
        //echo "DEBUG: ".$xml_path;
        if (!empty($plugin)) {
            if (is_string($plugin)) {
                $plugin = & $this->get($plugin);
            }
        } else {
            return zmgError::throwError('embedSettings::Invalid plugin.');
        }
        
        if (!file_exists($xml_path))
            return zmgError::throwError('Settings file not found ('.$xml_path.').');

        require_once(ZMG_ABS_PATH . DS.'lib'.DS.'domit'.DS.'xml_domit_lite_include.php');
        $xmldoc = & new DOMIT_Lite_Document();
        $xmldoc->resolveErrors(true);
        
        if (!$xmldoc->loadXML($xml_path, false, true)) {
            unset($xmldoc);
            return zmgError::throwError('DOMIT error: could not open document.');
        }
        
        if ($xmldoc->documentElement->getTagName() != "settings") {
            unset($xmldoc);
            return zmgError::throwError('Invalid plugin settings file.');
        }
        
        $plugin['settings'] = array();
        if ($xmldoc->documentElement->hasAttribute('plugin')) {
            $plugin['settings_name'] = $xmldoc->documentElement->getAttribute('plugin');
        } else {
            $plugin['settings_name'] = $this->prettifyName($plugin['name']);
        }
        
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
        zmgFactory::getConfig()->fromPlugin($plugin);
    }
    
    function prettifyName($name) {
        $parts = explode('_', $name);
        for ($i = 0; $i < count($parts); $i++) {
        	$parts[$i] = ucfirst($parts[$i]);
        }
        
        return implode(' ', $parts);
    }
    
    function embedHTML() {
        $config = & zmgFactory::getConfig();
        
        $out  = "<div id=\"zmg_plugins_accordion\" class=\"zmg_halfsize\">\n";
        
        foreach ($this->_plugins as $plugin) {
            $name      = $plugin['settings_name'];
            $settings  = $plugin['settings'];
            if (is_array($settings)) {
                $out .= "<div class=\"zmg_accordion_panel\">\n" 
                 . "<h3 class=\"zmg_accordion_toggler zmg_accordion_start\">" . T_($name) . "</h3>\n"
                 . "<div class=\"zmg_accordion_element zmg_accordion_start\">\n";
                foreach ($settings as $cat => $sub_settings) {
                    $out .= "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n"
                     . "<tr>\n"
                     . "\t<td colspan\"2\">\n"
                     . "\t\t<h3>" . T_(ucfirst($cat)) . "</h3>\n"
                     . "\t</td>\n"
                     . "</tr>\n";
                    foreach ($sub_settings as $setting => $data) {
                        $setting_parts = array('plugins', $plugin['name'], $cat, $setting);
                        $setting_value = $config->get(implode('/', $setting_parts));
                        $input_name    = "zmg_" . implode('_', $setting_parts);
                        
                        $out .= "<tr>\n";
                        switch ($data['type']) {
                            case "checkbox":
                                $out .= "\t\t<td width=\"250\">" . T_($data['label']) . "</td><td>";
                                $checked = ($setting_value == $data['value']) ? true : false;
                                $out .= $this->_buildCheckboxInput($input_name, $data['value'], $checked)
                                 . "</td>\n";
                                break;
                            case "radio":
                                $out .= "\t\t<td width=\"250\">" . T_($data['label']) . "</td><td>";
                                $checked = ($setting_value == $data['value']) ? true : false;
                                $out .= $this->_buildRadioInput($input_name, $data['value'], $checked)
                                 . "</td>\n";
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
                            case "password":
                                $out .= "\t\t<td width=\"250\">" . T_($data['label']) . "</td><td>"
                                 . $this->_buildTextInput($input_name, $setting_value, $data['type'], $data['size']) . "</td>\n";
                                break;
                        }
                        $out .= "</tr>\n";
                    }
                    
                    $out .= "</table>\n"; 
                }
                $out .= "</div>\n</div>\n";
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
    
    function _buildTextInput($name, $value, $type = 'text', $size = 50, $disabled = false, $readonly = false) {
        return '<input type="' . $type . '" name="' . $name . '" size="' . $size . '" value="'
          . $value . '"' . ($disabled ? ' disabled="disabled"' : '')
          . ($readonly ? ' readonly="readonly"' : '') . '/>';
    }
}
?>