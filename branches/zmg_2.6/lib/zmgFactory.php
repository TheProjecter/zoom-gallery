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

class zmgFactory {
    function &getConfig() {
        static $zoom_config, $instance_config;

        if (!is_object($instance_config)) {
            //load the configuration file
            include(ZMG_ABS_PATH . DS.'etc'.DS.'app.config.php');
            zmgimport('org.zoomfactory.lib.helpers.zmgConfigurationHelper');

            $instance_config = new zmgConfigurationHelper($zoom_config);
        }

        return $instance_config;
    }

    function &getEvents() {
        static $instance_events;

        if (!is_object($instance_events)) {
            zmgimport('org.zoomfactory.lib.helpers.zmgEventsHelper');

            $instance_events = new zmgEventsHelper();
        }

        return $instance_events;
    }

    function &getJSON() {
        static $instance_json;

        if (!is_object($instance_json)) {
            zmgimport('org.zoomfactory.lib.zmgJson');

            $instance_json = new zmgJSON();
        }

        return $instance_json;
    }

    function &getLogger() {
        static $instance_logger;

        if (!is_object($instance_logger)) {
            zmgimport('org.zoomfactory.lib.zmgLogger');

            $instance_logger = new zmgLogger(ZMG_ABS_PATH . DS . "etc" . DS . "cache");
        }

        return $instance_logger;
    }

    /**
     * Public variable, containing the messaging center of ZMG.
     *
     * @return zmgMessageCenter
     */
    function &getMessages() {
        static $instance_messages;

        if (!is_object($instance_messages)) {
            zmgimport('org.zoomfactory.lib.helpers.zmgMessageCenter');

            $instance_messages = new zmgMessageCenter();
        }

        return $instance_messages;
    }

    /**
     * Public variable, containing the plugin system of ZMG.
     *
     * @return zmgPluginHelper
     */
    function &getPlugins() {
        static $instance_plugins;

        if (!is_object($instance_plugins)) {
            zmgimport('org.zoomfactory.lib.helpers.zmgPluginHelper');

            $instance_plugins = new zmgPluginHelper();
        }

        return $instance_plugins;
    }

    function &getRequest() {
        static $instance_request;

        if (!is_object($instance_request)) {
            zmgimport('org.zoomfactory.lib.helpers.zmgRequestHelper');

            $instance_request = new zmgRequestHelper();
        }

        return $instance_request;
    }

    function &getSession() {
        static $session;

        if (!is_object($session)) {
            zmgimport('org.zoomfactory.lib.zmgSession');

            $session = new zmgSession();
        }

        return $session;
    }

    /**
     * Public variable, containing the zmgViewHelper - helping ZMG with controlling
     * the views on the different models that the Core exposes.
     *
     * @return zmgViewHelper
     */
    function &getView() {
        static $instance_view;

        if (!is_object($instance_view)) {
            zmgimport('org.zoomfactory.lib.helpers.zmgViewHelper');

            $config = & zmgFactory::getConfig();

            $instance_view = new zmgViewHelper($config->get('smarty'),
              $config->get('app/secret'));
        }

        return $instance_view;
    }
}

?>
