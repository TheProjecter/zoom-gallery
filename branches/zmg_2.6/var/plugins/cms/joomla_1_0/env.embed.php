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
        $sessioncookie = zmgGetParam($_COOKIE, $sessionCookieName, null);
        // Session ID / `value`
        return mosMainFrame::sessionCookieValue($sessioncookie);
    }
    
    function getSiteURL() {
        global $mosConfig_live_site;
        return $mosConfig_live_site;
    }
    
    function getAjaxURL() {
        if (ZMG_ADMIN) {
            return "/administrator/index2.php?option=com_zoom&no_html=1";
        }
        return "/index.php?option=com_zoom&no_html=1";
    }
    
    function sefRouteURL($value) {
        return sefRelToAbs($value);
    }
    
    function setPageTitle($title) {
        global $mainframe;
        $mainframe->setPageTitle($title);
    }
    
    function appendPageHeader($html) {
        global $mainframe;
        $mainframe->addCustomHeadTag($html);
    }
    
    function includeMootools() {
        zmgEnv::appendPageHeader('<script src="' . zmgEnv::getSiteURL()
         . '/components/com_zoom/var/www/shared/mootools.js" type="text/javascript"></script>');
    }
}
?>
