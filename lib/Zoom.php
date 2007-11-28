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

/**
 * Main application class
 * @package zmg
 */
class Zoom extends zmgError {
	/**
     * Internal variable for the configuration array.
     *
     * @var array
     */
	var $_config = null;
    /**
     * Public variable, containing the zmgTemplateViewHelper/Smarty
     * templating engine class.
     *
     * @var zmgTemplateHelper
     */
    var $view = null;
    /**
     * Public variable, containing the current user.
     * 
     * @var zmgUser
     */
    var $user = null;
	/**
     * The class constructor.
     */
	function Zoom(&$config) {
        $this->_config = new zmgConfigurationHelper($config);
        $this->view    = new zmgTemplateHelper($this->getConfig('smarty'));

        $this->loadEvents(); //TODO: use cached events list
	}
    function hasAccess() {
        //TODO: implement!
        return true;
    }
    function notAuth() {
        //TODO: implement!
        die('Restricted access');
    }
    function restoreSession() {
        $username = zmgGetParam($_SESSION, 'zmg.session.username', '');
        if (!empty($username)) {
            $this->user = new zmgUser(&$this->db);
            $this->user->id       = zmgGetParam($_SESSION, 'zmg.session.id', '');
            $this->user->username = $username;
            $this->user->usertype = zmgGetParam($_SESSION, 'zmg.session.usertype', '');
            $this->user->gid      = zmgGetParam($_SESSION, 'zmg.session.gid', '');
            $this->user->params   = zmgGetParam($_SESSION, 'zmg.session.params', '');
        }
    }
    function storeSession() {
        $_SESSION['zmg.session.id']       = $this->user->id;
        $_SESSION['zmg.session.username'] = $this->user->username;
        $_SESSION['zmg.session.usertype'] = $this->user->usertype;
        $_SESSION['zmg.session.gid']      = $this->user->gid;
        $_SESSION['zmg.session.params']   = $this->user->params; 
    }
    /**
     * Retrieve a specific configuration setting.
     * @param string The name of the setting in the format of a pathname: 'group/setting'
     */
    function getConfig($path) {
    	return $this->_config->get($path);
    }
    /**
     * Load all available custom events from the /var/events folder.
     */
    function loadEvents() {
        //TODO: move reading directory stuff to zmgConfigurationHelper class
        $event_cats = zmgReadDirectory(ZMG_ABS_PATH . DS.'var'.DS.'events', '[^index\.html]');
        $this->events = array();
        foreach ($event_cats as $cat) {
            if ($cat != "shared") {
                $events = zmgReadDirectory(ZMG_ABS_PATH . DS.'var'.DS.'events'.DS . $cat, '[^index\.html]');
                if (count($events) > 0) {
                    $this->events[$cat] = $events;
                }
            }
        }
    }
    /**
     * Launch all components that are bound to a specific custom event handler.
     * @param string The name of the event that is fired
     */
    function fireEvents($event) {
        if (!empty($this->events[$event])) {
            foreach ($this->events[$event] as $cmp) {
                require_once(ZMG_ABS_PATH . DS.'var'.DS.'events'.DS.$event.DS.$cmp.DS.$cmp.'.php');
                if (class_exists($cmp)) { 
                    eval($cmp . '::start(&$this);');
                }
            }
        }
    }
    /**
     * Send a set of headers to the client (i.e. browser) to tell it how to display
     * the data inside the response body.
     * @param string Specifies the contect type of the response body
     * @param boolean In case of an error message, this var will be set to TRUE
     * @param string Message describing the error in case of an error
     */
    function sendHeaders($type = "xml", $error = false, $error_msg = "") {
        //using 'echo @header()', because that seems to implicitely work in some
        //WAMP environments. Why? Pfff, beats me.
    	echo @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        //HTTP/1.1
        echo @header("Cache-Control: no-store, no-cache, must-revalidate");
        echo @header("Cache-Control: post-check=0, pre-check=0", false);
        //HTTP/1.0
        echo @header("Pragma: no-cache");
        
        $encoding = $this->getConfig('locale/encoding');
        
        if ($error) {
            echo @header("zmg_result: KO");
            echo @header("zmg_msg: " + $error_msg);
        } else {
            echo @header("zmg_result: OK");
        }
        
        if ($type == "xml") {
    		echo @header("Content-type:text/xml; charset=" . $encoding);
    	} else if ($type == "plain") {
    		echo @header("Content-type:text/plain; charset=" . $encoding);
    	} else if ($type == "js") {
            echo @header("Content-type:text/javascript; charset=" . $encoding);
        }
    }
}
?>