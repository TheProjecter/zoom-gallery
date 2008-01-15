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

class zmgCmsPlugin extends zmgError {
    function bindEvents() {
        //only the embed function needs to be bound to the 'onstartup' event.
        return array(
            "onstartup" => array(
                "embed" => array()
            )
        );
    }
    
    function embed() {
        $os = zmgCmsPlugin::_guessCMS();
        $os_dir = ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.'cms'.DS.$os;
        if (is_dir($os_dir)) {
            $os_path = 'org.zoomfactory.var.plugins.cms.' . $os;
            zmgimport($os_path . '.aclEmbed');
            zmgimport($os_path . '.databaseEmbed');
            zmgimport($os_path . '.envEmbed');
            return true;
        }
        zmgError::throwError('zmgCmsPlugin: no CMS found.');
        return false;
    }
    
    function _guessCMS() {
        //first built-in check: Joomla! 1.5
        if (function_exists('jimport')) {
            jimport('joomla.version');
            $version = new JVersion();
            if ($version->RELEASE == "1.5") {
                return "joomla_1_5";
            }
        }
        //legacy mode (?)
        return "joomla_1_0";
    }
}
?>
