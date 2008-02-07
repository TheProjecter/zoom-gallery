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
 * Class that assists Zoom in retrieving and storing application settings
 * @package zmg
 */
class zmgSessionHelper extends zmgError {
    /**
     * Internal flag set if the session has been started yet.
     *
     * @var boolean
     */
    var $_started = null;
    /**
     * Internal variable for holding the (restored) session variables 
     *
     * @var array
     */
    var $_vars = null;
    
    function zmgSessionHelper() {
        $this->start();
        $this->restore();
    }
    
    function start() {
        if (!$this->hasStarted()) {
            //session_name('session.zmg');
//            if (session_id()) {
//                session_destroy();
//            }
//            @ini_set('session.save_handler', 'files');
//            session_module_name('files');
            @session_start();
            
            $this->_started = true;
        }
    }
    
    function hasStarted() {
        return (bool)$this->_started;
    }
    
    function get($name) {
        if (!$this->hasStarted()) {
            return $this->throwError('zmgSessionHelper: session not started yet.');
        }
        if (empty($this->_vars)) {
            return $this->throwError('zmgSessionHelper: no variables to fetch.');
        }
        
        $name = trim($name);
        if ($this->_vars[$name]) {
            return $this->_vars[$name];
        }
        return null;
    }
    
    function put($name, $value, $serialize = false) {
        if (!$this->hasStarted()) {
            return $this->throwError('zmgSessionHelper: session not started yet.');
        }
        if (empty($name)) {
            return $this->throwError('zmgSessionHelper: no variable name specified.');
        }
        if (empty($value)) {
            return $this->throwError('zmgSessionHelper: no value to store.');
        }
        
        $name = trim($name);
        $this->_vars[$name] = $value;
        if ($serialize) {
            $name .= ".serialized";
            $value = serialize($value);
        }
        $_SESSION['zmg.session.' . (string)$name] = $value;
    }
    
    function restore() {
        $this->_vars = array();
        foreach ($_SESSION as $name => $value) {
            if (strstr($name, 'zmg.session.') && !empty($value)) {
                $varname = str_replace('zmg.session.', '', $name);
                if (strstr($varname, '.serialized')) {
                    $this->_vars[str_replace('.serialized', '', $varname)]
                      = unserialize($value);
                } else {
                    $this->_vars[$varname] = $value;
                }
            }
        }
    }
    
    function store() {
        //TODO
    }
}
?>
