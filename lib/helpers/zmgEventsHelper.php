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
 * Class that assists Zoom in handling internal events
 * @package zmg
 * @subpackage helpers
 */
class zmgEventsHelper {

    var $_events = null;

    function zmgEventsHelper() {
        $this->_load();//TODO: use cached events list
    }

    /**
     * Deprecated: Load all available custom events from the /var/events folder.
     * Now only importing the zmgEvent class
     */
    function _load() {
        zmgimport('org.zoomfactory.lib.zmgEvent'); //will be used later
    }

    /**
     * Launch all components that are bound to a specific custom event handler.
     *
     * @param string The name of the event that is fired
     * @param bool The event may or may not bubble down
     * @return mixed
     */
    function fire($type, $nobubble = false) {
        $event = new zmgEvent($type);

        $args = func_get_args();
        $newArgs = array_splice($args, 2, count($args));
        if (count($newArgs) == 1) {
        	$newArgs = $newArgs[0];
        }
        $event->pass($newArgs);

        //bubble through to plugins:
        if (!(bool)$nobubble) {
            $plugins = & zmgFactory::getPlugins();
            return $plugins->bubbleEvent($event);
        }
    }
}

?>
