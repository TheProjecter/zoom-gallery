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

/**
 * Load the Core Zoom assets, currently consisting of zmgComment, zngEditMonitor,
 * zmgGallery, zmgMedium
 */
class zmgCorePlugin {
    function bindEvents() {
        return array(
            "onstartup" => array(
                "embed" => array()
            ),
            "onstarted" => array(
                "checkAccess" => array()
            ),
            "onfinish" => array(
                "shutDown" => array()
            ),
            "ongetcore" => array(
                "getCore" => array()
            ),
            "onviewset" => array(
                "prepareAdmin" => array()
            )
        );
    }
    
    function embed() {
        zmgimport('org.zoomfactory.var.plugins.core.assets.*');

        $messages = & zmgFactory::getMessages();
        $session  = & zmgFactory::getSession(); //also restores the session if needed

        $from_session = $session->get('zmg.messagecenter.cache');
        if (!empty($from_session) && is_array($from_session)) {
            $messages->setAll($from_session);
        }
    }

    function &getCore() {
        static $instance;

        if (!is_object($instance)) {
            $instance = new zmgCore();
        }

        return $instance;
    }

    function checkAccess() {
        $zoom = & zmgCorePlugin::getCore();

        $zoom->hasAccess() or die('Restricted access');
    }

    function prepareAdmin() {
        if (ZMG_ADMIN) {
            $zoom = & zmgCorePlugin::getCore();
            zmgFactory::getView()->appendConstant('mediumcount', $zoom->getMediumCount());
        }
    }

    function shutDown() {
        $messages = & zmgFactory::getMessages();
        $session  = & zmgFactory::getSession();

        $session->put('zmg.messagecenter.cache', $messages->getAll(), true);
        $session->store();
    }
}
?>
