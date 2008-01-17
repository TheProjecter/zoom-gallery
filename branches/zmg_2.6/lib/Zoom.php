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

define('_ZMG_RPC_RESULT_OK', 'OK');
define('_ZMG_RPC_RESULT_KO', 'KO');

class zmgFactory {
    function &getZoom(&$config = null) {
        static $instance;
        
        if (!is_object($instance)) {
            if (!$config) {
                $config = & zmgFactory::getConfig();
            }

            $instance = new Zoom($config);
        }

        return $instance;
    }
    
    function &getJSON() {
        static $instance_json;
        
        if (!is_object($instance_json)) {
            $instance_json = new zmgJSON();
        }

        return $instance_json;
    }
    
    function &getConfig() {
        static $zoom_config;
        
        //load the configuration file
        require(ZMG_ABS_PATH . DS.'etc'.DS.'app.config.php');
        
        return $zoom_config;
    }
}

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
     * Internal variable for storing rpc-results temporarily
     *
     * @var string
     */
    var $_result = null;
    /**
     * Internal variable, a holder for an array of galleries
     *
     * @var array
     */
    var $_gallerylist = null;
    /**
     * Public variable, containing the zmgViewHelper - helping ZMG with controlling
     * the views on the different models that the Core exposes.
     *
     * @var zmgViewHelper
     */
    var $view = null;
    /**
     * Public variable, containing the current user.
     * 
     * @var zmgUser
     */
    var $user = null;
    /**
     * Public variable, containing the messaging center of ZMG.
     * 
     * @var zmgMessageCenter()
     */
    var $messages = null;
    /**
     * Public variable, containing the plugin system of ZMG.
     * 
     * @var zmgPluginHelper()
     */
    var $plugins = null;
    /**
     * Public variable, containing the ZMG session variables.
     * 
     * @var zmgSessionHelper()
     */
    var $session = null;
    
	/**
     * The class constructor.
     */
	function Zoom(&$config) {
        zmgimport('org.zoomfactory.lib.helpers.zmgConfigurationHelper');
        zmgimport('org.zoomfactory.lib.helpers.zmgMessageCenter');
        zmgimport('org.zoomfactory.lib.helpers.zmgPluginHelper');
        zmgimport('org.zoomfactory.lib.helpers.zmgSessionHelper');
        zmgimport('org.zoomfactory.lib.helpers.zmgViewHelper');
        
        $this->_config  = new zmgConfigurationHelper($config);
        $this->view     = new zmgViewHelper($this->getConfig('smarty'),
          $this->getConfig('app/secret'));
        $this->messages = new zmgMessageCenter();
        $this->plugins  = new zmgPluginHelper();
        $this->session  = new zmgSessionHelper();

        $this->loadEvents(); //TODO: use cached events list
	}
    function hasAccess() {
        if (!zmgACL::check_defines())
            return false;
        return true;
    }
    function notAuth() {
        //TODO: implement!
        die('Restricted access');
    }
    /**
     * Retrieve a specific configuration setting.
     * @param string The name of the setting in the format of a pathname: 'group/setting'
     */
    function getConfig($path) {
    	return $this->_config->get($path);
    }
    function getTableName($name) {
        $prefix = $this->_config->get('db/prefix');
        $table  = $this->_config->get('db/tables/' . $name);
        
        if (!empty($prefix) && !empty($table)) {
            return "#__" . $prefix . $table;
        }
        return null;
    }
    function updateConfig($vars, $isPlugin = false) {
        return $this->_config->update($vars, $isPlugin);
    }
    /**
     * Call an abstract/ static function that resides within a static class.
     * Note: particularly useful within templates.
     * @see zmgCallAbstract
     */
    function callAbstract($klass, $func, $args) {
        return zmgCallAbstract($klass, $func, $args);
    }
    function jsonHelper($input, $type = 'encode') {
        $json = new zmgJSON();
        if ($type == "decode") {
            return $json->decode($input);
        }
        return $json->encode($input);
    }
    function getGalleryCount() {
        $db = & zmgDatabase::getDBO();
        $db->setQuery('SELECT COUNT(gid) AS total FROM #__zmg_galleries');
        if ($db->query()) {
            return intval($db->loadResult());
        }
        return 0;
    }
    function getMediumCount($gid = 0) {
        $db = & zmgDatabase::getDBO();
        $query = "SELECT COUNT(mid) AS total FROM #__zmg_media";
        if ($gid > 0) {
            $query .= " WHERE gid = $gid";
        }
        $db->setQuery($query);
        if ($db->query()) {
            return intval($db->loadResult());
        }
        return 0;
    }
    function getGalleries($sub_gid = 0, $pos = 0) {
        $ret  = array();

        $db   = & zmgDatabase::getDBO();
        $db->setQuery("SELECT gid FROM #__zmg_galleries WHERE sub_gid=$sub_gid AND pos=$pos");
        $rows = $db->loadRowList();
        if ($rows) {
            foreach ($rows as $row) {
                $gallery = new zmgGallery(&$db);
                $gallery->load($row[0]);
                $ret[]   = $gallery;
            }
        }
        return $ret;
    }
    /**
     * Create a list of all galleries.
     * @param int $parent
     * @param string $ident
     * @param string $ident2
     * @return void
     */
    function &getGalleryList($parent = 0, $indent_l1 = '->', $indent_l2 = '->') {
        if (!is_array($this->_gallerylist)) {
            $db   = & zmgDatabase::getDBO();
            $db->setQuery("SELECT gid FROM #__zmg_galleries WHERE sub_gid=$parent ORDER BY pos, "
             . $this->getGalleriesOrdering());
            $rows = $db->loadRowList();
            if ($rows) {
                foreach ($rows as $row) {
                    $gallery = new zmgGallery(&$db);
                    $gallery->load($row[0]);
                    $ret[]   = $gallery;
                    $this->_gallerylist[] = array(
                      'object' => $gallery,
                      'path_name' => $indent_l1 . $gallery->name,
                      'path_virtual' => $indent_l2 . $gallery->name);
                    $this->getGalleryList($gallery->gid, $indent_l1 . '->',
                      $indent_l2 . $gallery->name . '->');
                }
            }
        }
        return $this->_gallerylist;
    }
    function getMedia($gid = 0, $offset = 0, $length = 0, $filter = 0) {
        $filter = intval($filter);
        if ($filter > 0) {
            $gid = $filter;
        }
        
        $ret  = array();

        $db   = & zmgDatabase::getDBO();
        
        $query = "SELECT mid FROM #__zmg_media";
        if ($gid === 0) {
            $query .= " ORDER BY gid, " . $this->getMediaOrdering();
        } else {
            $query .= " WHERE gid=$gid ORDER BY " . $this->getMediaOrdering();
        }
        if ($length > 0) {
            $query .= " LIMIT $offset, $length";
        }
        
        $a_gallery = null;
        $a_gallery_dir = "";
        
        $db->setQuery($query);
        $rows = $db->loadRowList();
        if ($rows) {
            foreach ($rows as $row) {
                $medium = new zmgMedium(&$db);
                $gid = intval($row[0]);
                $medium->load($gid);
                if ($a_gallery !== $gid) {
                    $a_gallery = $gid;
                    $a_gallery_dir = $medium->getGalleryDir();
                } else {
                    $medium->gallery_dir = $a_gallery_dir; 
                }
                $ret[] = $medium;
            }
        }
        return $ret;
    }
    function getGallery($gid, $ret_type = 'object') {
        $gid = intval($gid);
        $gallery = new zmgGallery(zmgDatabase::getDBO());
        $gallery->load($gid);
        if ($ret_type == "json") {
            return $gallery->toJSON();
        } else if ($ret_type == "xml") {
            return $gallery->toXML();
        }
        return $gallery;
    }
    function getMedium($mid, $ret_type = 'object') {
        $mid = intval($mid);
        $medium = new zmgMedium(zmgDatabase::getDBO());
        $medium->load($mid);
        if ($ret_type == "json") {
            return $medium->toJSON();
        } else if ($ret_type == "xml") {
            return $medium->toXML();
        }
        return $medium;
    }
    function getParamInt($name, $default = 0) {
        return intval(zmgGetParam($_REQUEST, $name, $default));
    }
    function concat() {
        $args = func_get_args();
        return join($args, '');
    }
    /**
     * Return the method of ordering for galleries.
     * @return string
     * @access public
     */
    function getGalleriesOrdering() {
        $methods = array("", "ordering, gid ASC", "ordering, gid DESC",
          "ordering, name ASC", "ordering, name DESC");
          
        return $methods[intval($this->getConfig('layout/ordering/galleries'))];
    }
    /**
     * Return the method of ordering for media.
     * @return string
     * @access public
     */
    function getMediaOrdering() {
        $methods = array("", "date_add ASC", "date_add DESC", "filename ASC",
          "filename DESC", "name ASC", "name DESC");
          
        return $methods[intval($this->getConfig('layout/ordering/media'))];
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
     * @param bool The event may or may not bubble down
     */
    function fireEvents($event, $nobubble = false) {
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
            $this->plugins->bubbleEvent($event);
        }
    }
    function setResult($result = true) {
        if (is_bool($result)) {
            $result = ($result) ? _ZMG_RPC_RESULT_OK : _ZMG_RPC_RESULT_KO;
        }
        $this->_result = $result;
    }
    function getResult() {
        if ($this->_result == null) {
            return _ZMG_RPC_RESULT_OK;
        }
        $res = $this->_result;
        $this->_result = null;
        return $res;
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
        
        $encoding = "UTF-8";
        if (method_exists($this, 'getConfig')) {
            $encoding = $this->getConfig('locale/encoding');
        }
        
        if ($error) {
            echo @header("zmg_result: " . _ZMG_RPC_RESULT_KO);
            echo @header("zmg_message: " . urlencode($error_msg));
        } else {
            echo @header("zmg_result: " . _ZMG_RPC_RESULT_OK);
        }
        
        if ($type == "xml") {
    		echo @header("Content-type:text/xml; charset=" . $encoding);
    	} else if ($type == "plain") {
    		echo @header("Content-type:text/plain; charset=" . $encoding);
    	} else if ($type == "js" || $type == "json") {
            echo @header("Content-type:text/javascript; charset=" . $encoding);
        }
    }
}
?>