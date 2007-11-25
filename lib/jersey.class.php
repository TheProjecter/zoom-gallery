<?php
/**
 * Jersey, doing scrum the easy way.
 * 
 * @package jersey
 * @version $Revision$
 * @author Mike de Boer <mdeboer AT ebuddy.com>
 * @copyright Copyright &copy; 2007, Mike de Boer.
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */

defined("_VALID_JERSEY") or die("Direct access.");

/**
 * Main application class
 * @package jersey
 */
class jersey extends jsyError {
	/**
     * Internal variable for the configuration array.
     *
     * @var array
     */
	var $_config = null;
    /**
     * Public variable, containing the ADODB database engine class.
     *
     * @var ADODB
     */
    var $db = null;
    /**
     * Public variable, containing the phpGACL Access Control List class.
     *
     * @var gacl
     */
    var $acl = null;
    /**
     * Public variable, containing the Smarty templating engine class.
     *
     * @var Smarty
     */
    var $template = null;
    /**
     * Public variable, containing the Project objects.
     *
     * @var array
     */
    var $projects = null;
    /**
     * Public variable, containing the current user.
     * 
     * @var jsyUser
     */
    var $user = null;
	/**
     * The class constructor.
     */
	function jersey() {
		$this->loadConfig();
	}
	/**
	 * Load all the configuration settings as set in /etc/app.config/php into
	 * a class variable (scoped). 
	 */
    function loadConfig() {
    	global $jersey_config;
        $this->_config = $jersey_config;
        $jersey_config = null;
    }
    function login($username, $password, $remember = false) {
        if (!empty($username) && !empty($password)) {
            $this->db->SetFetchMode(ADODB_FETCH_ASSOC);
            $password = md5($password);
            $recordSet = $this->db->Execute( "SELECT id FROM jersey_users
              WHERE username = '$username'
                AND password = '$password'" );
            if (!$recordSet) {
                jsyError::throwError('Incorrect username and/ or password');
                $ret = false;
            } else {
                while (!$recordSet->EOF) {
                    $this->user = new jsyUser(&$this->db);
                    $this->user->load($recordSet->fields['id']);
                    $recordSet->MoveNext();
                }
                $this->storeSession();
            }
        } else {
            return jsyError::throwError('No username and password specified');
        }
    }
    function logout() {
        
    }
    function restoreSession() {
        $username = jsyGetParam($_SESSION, 'jersey.session.username', '');
        if (!empty($username)) {
            $this->user = new jsyUser(&$this->db);
            $this->user->id       = jsyGetParam($_SESSION, 'jersey.session.id', '');
            $this->user->username = $username;
            $this->user->usertype = jsyGetParam($_SESSION, 'jersey.session.usertype', '');
            $this->user->gid      = jsyGetParam($_SESSION, 'jersey.session.gid', '');
            $this->user->params   = jsyGetParam($_SESSION, 'jersey.session.params', '');
        }
    }
    function storeSession() {
        $_SESSION['jersey.session.id']       = $this->user->id;
        $_SESSION['jersey.session.username'] = $this->user->username;
        $_SESSION['jersey.session.usertype'] = $this->user->usertype;
        $_SESSION['jersey.session.gid']      = $this->user->gid;
        $_SESSION['jersey.session.params']   = $this->user->params; 
    }
    /**
     * Retrieve a specific configuration setting.
     * @param string The name of the setting in the format of a pathname: 'group/setting'
     */
    function getConfig($path) {
    	$path_tokens = explode("/", $path);
        $config_val  = &$this->_config;
        for ($i = 0; $i < count($path_tokens); $i++) {
        	if (isset($config_val[$path_tokens[$i]])) {
                $config_val = &$config_val[$path_tokens[$i]];
            }
        }
        return $config_val;
    }
    /**
     * Load all available custom events from the /var/events folder.
     */
    function loadEvents() {
        $event_cats = jsyReadDirectory(ABS_PATH . '/var/events');
        $this->events = array();
        foreach ($event_cats as $cat) {
            if ($cat != "shared") {
                $events = jsyReadDirectory(ABS_PATH . '/var/events/' . $cat);
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
                require_once(ABS_PATH . "/var/events/$event/$cmp/$cmp.php");
                if (class_exists($cmp)) { 
                    eval($cmp . '::start();');
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
            echo @header("jersey_result: KO");
            echo @header("jersey_msg: " + $error_msg);
        } else {
            echo @header("jersey_result: OK");
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