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

class zmgEnv extends zmgError {
    function redirect($url, $msg = '', $msg_type = 'message') {
        mosRedirect($url, $msg, $msg_type);
    }
    
    function getSessionLifetime() {
        
    }
    
    function getSessionID() {
        // Session Cookie `name`
        $sessionCookieName = mosMainFrame::sessionCookieName();
        // Get Session Cookie `value`
        $sessioncookie = jsyGetParam($_COOKIE, $sessionCookieName, null);
        // Session ID / `value`
        return mosMainFrame::sessionCookieValue($sessioncookie);
    }
    
    function getSiteURL() {
        global $mosConfig_live_site;
        return $mosConfig_live_site;
    }
    
    function sefRouteURL($value) {
        return sefRelToAbs($value);
    }
}
?>
