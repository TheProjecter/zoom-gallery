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

class zmgFactory {
    function &getZoom(&$config = null) {
        static $instance;
        
        if (!is_object($instance)) {
            if (!$config) {
                $config = & zmgFactory::getConfig();
            }

            $instance = new Zoom($config);
        }

        return $instance;
    }
    
    function &getJSON() {
        static $instance_json;
        
        if (!is_object($instance_json)) {
            zmgimport('org.zoomfactory.lib.zmgJson');
            
            $instance_json = new zmgJSON();
        }

        return $instance_json;
    }
    
    function &getConfig() {
        static $zoom_config;
        
        //load the configuration file
        require(ZMG_ABS_PATH . DS.'etc'.DS.'app.config.php');
        
        return $zoom_config;
    }
    
    function &getSession() {
        static $session;
        
        if (!is_object($session)) {
            $session = new zmgSession();
        }

        return $session;
    }
}

?>
