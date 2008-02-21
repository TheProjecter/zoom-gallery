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

class zmgSessionHelper {
	function &getSession() {
		static $instance;
        
        if (!is_object($instance)) {
            $instance = new zmgSession();
        }

        return $instance;
	}
    
    function &init() {
    	$session = & zmgSessionHelper::getSession();
        
        return $session;
    }
    
    function start() {
    	$session = & zmgSessionHelper::getSession();
        
        return $session->start();
    }
    
    function hasStarted() {
        $session = & zmgSessionHelper::getSession();
        
        return $session->hasStarted();
    }
    
    function get($name) {
        $session = & zmgSessionHelper::getSession();
        
        return $session->get($name);
    }
    
    function update($name, $value, $vartype = ZMG_DATATYPE_ARRAY) {
        $session = & zmgSessionHelper::getSession();
        
        return $session->update($name, $value, $vartype);
    }
    
    function put($name, $value, $serialize = false) {
        $session = & zmgSessionHelper::getSession();
        
        return $session->put($name, $value, $serialize);
    }
}

/**
 * Class that assists Zoom in retrieving and storing application settings
 * @package zmg
 */
class zmgSession {
    /**
     * Internal variable that sets the prefix for each session variable.
     *
     * @var string
     */
    var $_var_prefix = "zmg.session.";
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
    
    function zmgSession() {
        $this->start();
        $this->restore();
    }
    
    function start() {
        if (!$this->hasStarted()) {
            //session_name('session.zmg');
//            if (session_id()) {4
//                session_destroy();
//            }
//            @ini_set('session.save_handler', 'files');
//            session_module_name('files');
            @session_start();
            
            $this->_started = true;
            
            $this->put('started', true);
        }
    }
    
    function hasStarted() {
        return (bool)$this->_started;
    }
    
    function get($name) {
        if (!$this->hasStarted()) {
            return zmgError::throwError('zmgSessionHelper: session not started yet.');
        }
        if (empty($this->_vars)) {
            return zmgError::throwError('zmgSessionHelper: no variables to fetch.');
        }
        
        $name = trim($name);
        if ($this->_vars[$this->_var_prefix . $name]) {
        	return $this->_vars[$this->_var_prefix . $name];
        } else if ($this->_vars[$name]) {
            return $this->_vars[$name];
        }
        return null;
    }
    
    function update($name, $value, $vartype = ZMG_DATATYPE_ARRAY) {
    	if (!$this->hasStarted()) {
            return zmgError::throwError('zmgSessionHelper: session not started yet.');
        }
        if (empty($name)) {
            return zmgError::throwError('zmgSessionHelper: no variable name specified.');
        }
        if (empty($value)) {
            return zmgError::throwError('zmgSessionHelper: no value to update.');
        }
        
        $name = trim($name);
        $old_value = $this->get($name);
        $new_value = null;
        
        if ($vartype & ZMG_DATATYPE_STRING) {
            if (!empty($old_value)) {
        		$new_value = $old_value;
        	} else {
        		$new_value = "";
        	}
            $new_value .= strval($value);
        } else if ($vartype & ZMG_DATATYPE_NUMBER) {
            if (!is_int($value) | !is_float($value)) {
            	$value = intval($value);
            }
            if (isset($old_value)) {
            	$new_value = $old_value;
            } else {
            	$new_value = 0;
            }
            $new_value += $value;
        } else if ($vartype & ZMG_DATATYPE_ARRAY) {
            if (!empty($old_value) && is_array($old_value)) {
                $new_value = $old_value;
            } else {
            	$new_value = array();
            }
            $new_value[] = $value;
        }
        
        if ($new_value !== null) {
        	$this->put($name, $new_value);
        }
    }
    
    function put($name, $value, $serialize = false) {
        if (!$this->hasStarted()) {
            return zmgError::throwError('zmgSessionHelper: session not started yet.');
        }
        if (empty($name)) {
            return zmgError::throwError('zmgSessionHelper: no variable name specified.');
        }
        if (empty($value)) {
            return zmgError::throwError('zmgSessionHelper: no value to store.');
        }
        
        $name = trim($name);
        $this->_vars[$name] = $value;
        if ($serialize) {
            $name .= ".serialized";
            $value = serialize($value);
        }
        $_SESSION[$this->_var_prefix . (string)$name] = $value;
        return true;
    }
    
    function restore() {
        $this->_vars = array();
        foreach ($_SESSION as $name => $value) {
            if (strstr($name, $this->_var_prefix) && !empty($value)) {
                $varname = str_replace($this->_var_prefix, '', $name);
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
        foreach ($this->_vars as $varname => $value) {
        	if (strstr($varname, '.serialized')) {
        		//variable already serialized
                $_SESSION[$varname] = $value;
        	} else {
        		if (!is_object($value) || is_array($value)) {
        			if (isset($_SESSION[$varname])) {
        				unset($_SESSION[$varname]);
        			}
                    $_SESSION[$varname . '.serialized'] = serialize($value);
        		} else {
        			$_SESSION[$varname] = $value;
        		}
        	}
        }
    }
}
?>
