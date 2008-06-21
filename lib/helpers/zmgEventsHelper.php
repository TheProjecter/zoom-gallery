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
     * Load all available custom events from the /var/events folder.
     */
    function _load() {
        zmgimport('org.zoomfactory.lib.zmgEvent'); //will be used later
        zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');

        //TODO: move reading directory stuff to zmgConfigurationHelper class
        $event_cats = zmgFileHelper::readDir(ZMG_ABS_PATH . DS.'var'.DS.'events', '[^index\.html]');
        $this->_events = array();
        foreach ($event_cats as $cat) {
            if ($cat != "shared") {
                $events = zmgFileHelper::readDir(ZMG_ABS_PATH . DS.'var'.DS.'events'.DS . $cat, '[^index\.html]');
                if (count($events) > 0) {
                    $this->_events[$cat] = $events;
                }
            }
        }
    }

    /**
     * Launch all components that are bound to a specific custom event handler.
     *
     * @param string The name of the event that is fired
     * @param bool The event may or may not bubble down
     */
    function fire($type, $nobubble = false) {
        $event = new zmgEvent($type);

        $args = func_get_args();
        $newArgs = array_splice($args, 2, count($args));
        if (count($newArgs) == 1) {
        	$newArgs = $newArgs[0];
        }
        $event->pass($newArgs);
        /*if (!empty($this->events[$event])) {
            foreach ($this->events[$event] as $cmp) {
                zmgimport('org.zoomfactory.var.events.'.$event.'.'.$cmp.'.'.$cmp);
                if (class_exists($cmp)) {
                    eval($cmp . '::start(&$this);');
                }
            }
        }*/

        //bubble through to plugins:
        if (!(bool)$nobubble) {
            $plugins = & zmgFactory::getPlugins();
            return $plugins->bubbleEvent($event);
        }
    }
}

?>
