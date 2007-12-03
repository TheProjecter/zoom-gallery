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

class zmgCmsPlugin extends zmgError {
    function embed() {
        $os = zmgCmsPlugin::_guessCMS();
        $os_dir = ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.'cms'.DS.$os;
        if (is_dir($os_dir)) {
            require_once($os_dir.DS.'acl.embed.php');
            require_once($os_dir.DS.'database.embed.php');
            require_once($os_dir.DS.'env.embed.php');
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
        } else {
            //legacy mode (?)
            return "joomla_1_0";
        }
    }
}
?>
