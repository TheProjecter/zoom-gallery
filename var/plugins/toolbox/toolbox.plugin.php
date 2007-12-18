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

class zmgToolboxPlugin extends zmgError {
    function embed() {
        
    }
    function registerError($title, $descr) {
        static $zmgToolboxErrors;
        
        if (!is_array($zmgToolboxErrors)) {
            $zmgToolboxErrors = array();
        }
        
        $i = count($zmgToolboxErrors);
        $zmgToolboxErrors[$i]['title']       = $title;
        $zmgToolboxErrors[$i]['description'] = $descr;
        
        //return 'FALSE' to the callee, because it's an error after all
        return false;
    }
}
?>
