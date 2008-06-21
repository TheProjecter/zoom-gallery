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

define('ZMG_GETID3_VERSION', '1.7.7');

/**
 * The zmgGetid3Plugin class
 */
class zmgGetid3Plugin {
    function bindEvents() {
        return array(
            "onstartup" => array(
                "embed" => array()
            ),
            "ongetaudiometadata" => array(
                "getAudioMetadata" => array('file')
            ),
            "ongetvideometadata" => array(
                "getVideoMetadata" => array('file')
            ),
            "onputmusicmetadata" => array(
                "putMusicMetadata" => array('file', 'metadata')
            )
        );
    }
    
    function embed() {
        $settings_file = ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.'getid3'.DS.'settings.xml';
        if (file_exists($settings_file)) {
            $plugins = & zmgFactory::getPlugins();
            $plugin = & $plugins->get('getid3');
            $plugins->embedSettings(&$plugin, $settings_file);
        }
    }
    
    function getId3Instance() {
        static $getid3_instance;
        
        if (!is_object($getid3_instance)) {
            $getid3_dir = "v".str_replace('.', '_', ZMG_GETID3_VERSION);
            zmgimport('org.zoomfactory.var.plugins.getid3.'.$getid3_dir.'.getid3');
            $getid3_instance = new getID3();
        }

        return $getid3_instance;
    }
    
    function getAudioMetadata($event) {
        $file   = $event->getArgument('file');
        $getid3 = & zmgGetid3Plugin::getId3Instance();
        return $getid3->analyze($file);
    }
    
    function getVideoMetadata($event) {
        $medium = $event->getArgument('file');
        //TODO
    }
    
    function putMusicMetadata($event) {
    	return true;
    }
}
?>
