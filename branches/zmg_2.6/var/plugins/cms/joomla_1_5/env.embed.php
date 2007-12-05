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
        global $mainframe;
        $mainframe->redirect($url, $msg, $msg_type);
    }
    
    function getSessionLifetime() {
        $session = & JFactory::getSession();
        return $session->getExpire();
    }
    
    function getSessionID() {
        $session = & JFactory::getSession();
        return $session->getToken();
    }
    
    function getSiteURL() {
        return substr_replace(JURI::root(), '', -1, 1);
    }
    
    function getViewType() {
        $no_html = intval(zmgGetParam($_GET, 'no_html', 0));
        if ($no_html === 1) {
            return "json";
        }
        return "html";
    }
    
    function getAjaxURL() {
        if (ZMG_ADMIN) {
            return "/administrator/index.php?option=com_zoom&no_html=1";
        }
        return "/index.php?option=com_zoom&no_html=1";
    }
    
    function sefRouteURL($value) {
        // Replace all &amp; with & as the router doesn't understand &amp;
        $url = str_replace('&amp;', '&', $value);
        
        $uri    = & JURI::getInstance();
        $prefix = $uri->toString(array('scheme', 'host', 'port'));
        return $prefix.JRoute::_($url);
    }
    
    function setPageTitle($title) {
        $document = & JFactory::getDocument();
        $document->setTitle($title);
    }
    
    function appendPageHeader($html) {
        $document = & JFactory::getDocument();
        if ($document->getType() == 'html') {
            $document->addCustomTag($html);
        }
    }
    
    function includeMootools() {
        JHTML::_('behavior.mootools');
    }
}
?>
