<?php
/**
 * zOOm Media Gallery! - a multi-gallery component 
 * 
 * @package zmg
 * @version $Revision$
 * @author Mike de Boer <mike AT zoomfactory.org>
 * @copyright Copyright &copy; 2007, Mike de Boer. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 * 
 * NOTE: the structure and parts of the implementation of the jsyError class
 *       has been derived from the PEAR class (PEAR.php, also available
 *       in this package). Copyrights of that code belongs to their respective
 *       owners.
 */

defined('_ZMG_EXEC') or die('Restricted access');

/**
 * Define all the error types that are supported 
 */
define('ZMG_ERROR_RETURN',     1);
define('ZMG_ERROR_PRINT',      2);
define('ZMG_ERROR_TRIGGER',    4);
define('ZMG_ERROR_DIE',        8);
define('ZMG_ERROR_CALLBACK',  16);
define('ZMG_ERROR_EXCEPTION', 32);


$GLOBALS['_ZMG_default_error_mode']    = ZMG_ERROR_TRIGGER;
$GLOBALS['_ZMG_default_error_options'] = E_USER_NOTICE;

@ini_set('track_errors', true);

/**
 * Jersey error handling class
 * @package jersey
 */
class zmgError {
    /**
     * Default error mode for this object.
     *
     * @var     int
     * @access  private
     */
    var $_default_error_mode = null;
    /**
     * Default error options used for this object when error mode
     * is ZMG_ERROR_TRIGGER or ZMG_ERROR_CALLBACK.
     *
     * @var     int
     * @access  private
     */
    var $_default_error_options = null;
    /**
     * zmgError constructor
     * 
     * @param int $mode     (optional) error mode, one of: ZMG_ERROR_RETURN,
     * ZMG_ERROR_PRINT, ZMG_ERROR_DIE, ZMG_ERROR_TRIGGER,
     * ZMG_ERROR_CALLBACK or ZMG_ERROR_EXCEPTION
     * @param mixed $options   (optional) error level, _OR_ in the case of
     * ZMG_ERROR_CALLBACK, the callback function or object/method
     * tuple.
     * @access public
     */
    function zmgError($mode = null, $options = null) {
        if ($mode === null) {
            $mode = $GLOBALS['_ZMG_default_error_mode'];
        }
        if ($options === null) {
            $options = $GLOBALS['_ZMG_default_error_options'];
        }
        $this->setErrorHandling($mode, $options);
    }
    /**
     * Sets how errors generated by this object should be handled.
     * Can be invoked both in objects and statically.  If called
     * statically, setErrorHandling sets the default behaviour for all
     * Jersey objects.  If called in an object, setErrorHandling sets
     * the default behaviour for that object.
     *
     * @param int $mode
     *        One of ZMG_ERROR_RETURN, ZMG_ERROR_PRINT,
     *        ZMG_ERROR_TRIGGER, ZMG_ERROR_DIE,
     *        ZMG_ERROR_CALLBACK or ZMG_ERROR_EXCEPTION.
     *
     * @param mixed $options
     *        When $mode is ZMG_ERROR_TRIGGER, this is the error level (one
     *        of E_USER_NOTICE, E_USER_WARNING or E_USER_ERROR).
     *
     *        When $mode is ZMG_ERROR_CALLBACK, this parameter is expected
     *        to be the callback function or method.  A callback
     *        function is a string with the name of the function, a
     *        callback method is an array of two elements: the element
     *        at index 0 is the object, and the element at index 1 is
     *        the name of the method to call in the object.
     *
     *        When $mode is ZMG_ERROR_PRINT or ZMG_ERROR_DIE, this is
     *        a printf format string used when printing the error
     *        message.
     */
    function setErrorHandling($mode = null, $options = null) {
        if (isset($this) && is_subclass_of($this, 'zmgError')) {
            $setmode     = &$this->_default_error_mode;
            $setoptions  = &$this->_default_error_options;
        } else {
            $setmode     = &$GLOBALS['_ZMG_default_error_mode'];
            $setoptions  = &$GLOBALS['_ZMG_default_error_options'];
        }

        switch ($mode) {
            case ZMG_ERROR_RETURN:
            case ZMG_ERROR_PRINT:
            case ZMG_ERROR_TRIGGER:
            case ZMG_ERROR_DIE:
            case ZMG_ERROR_EXCEPTION:
            case null:
                $setmode = $mode;
                $setoptions = $options;
                break;

            case ZMG_ERROR_CALLBACK:
                $setmode = $mode;
                // class/object method callback 
                if (is_callable($options)) {
                    $setoptions = $options;
                } else {
                    trigger_error("invalid error callback", E_USER_WARNING);
                }
                break;

            default:
                trigger_error("invalid error mode", E_USER_WARNING);
                break;
        }
    }
    /**
     * This method is a wrapper that returns a preconfigured error
     * with this object's default error handling applied (if present).
     * If the $mode and $options parameters are not
     * specified, the object's defaults are used.
     *
     * @param mixed $message A text error message
     * @param int $code A numeric error code (it is up to your class
     *                  to define these if you want to use codes)
     */
    function throwError($message = null, $code = null) {
        $mode    = $GLOBALS['_ZMG_default_error_mode'];
        $options = $GLOBALS['_ZMG_default_error_options'];
        if (isset($this) && is_subclass_of($this, 'zmgError')) {
            return $this->raiseError($message, $code,
              $GLOBALS['_ZMG_default_error_mode'], $GLOBALS['_ZMG_default_error_options']);
        } else {
            return zmgError::raiseError($message, $code, $mode, $options);
        }
    }
    /**
     * The actual function that handles all the errors. The implementation has been
     * kept abstract on purpose, so 3PD's may specify their own error callback
     * functions.
     *
     * @param string $message  message
     * @param int $code     (optional) error code
     * @param int $mode     (optional) error mode, one of: ZMG_ERROR_RETURN,
     * ZMG_ERROR_PRINT, ZMG_ERROR_DIE, ZMG_ERROR_TRIGGER,
     * ZMG_ERROR_CALLBACK or ZMG_ERROR_EXCEPTION
     * @param mixed $options   (optional) error level, _OR_ in the case of
     * ZMG_ERROR_CALLBACK, the callback function or object/method
     * tuple.
     * @param string $userinfo (optional) additional user/debug info
     * @access public
     */
    function raiseError($message, $code, $mode = null, $options = null) {
        if ($mode === null) {
            $mode = ZMG_ERROR_RETURN;
        }
        $error = array();
        $error['message']   = $message;
        $error['code']      = $code;
        $error['mode']      = $mode;
        $error['backtrace'] = zmgBackTrace();
        if ($mode & ZMG_ERROR_CALLBACK) {
            $error['level'] = E_USER_NOTICE;
            $error['callback'] = $options;
        } else {
            if ($options === null) {
                $options = E_USER_NOTICE;
            }
            $error['level']    = $options;
            $error['callback'] = null;
        }
        if ($error['mode'] & ZMG_ERROR_PRINT) {
            if (is_null($options) || is_int($options)) {
                $format = "%s";
            } else {
                $format = $options;
            }
            printf($format, $message);
        }
        if ($error['mode'] & ZMG_ERROR_TRIGGER) {
            trigger_error($error['message'], $error['level']);
        }
        if ($error['mode'] & ZMG_ERROR_DIE) {
            $msg = $error['message'];
            if (is_null($options) || is_int($options)) {
                $format = "%s";
                if (substr($msg, -1) != "\n") {
                    $msg .= "\n";
                }
            } else {
                $format = $options;
            }
            die(sprintf($format, $msg));
        }
        if ($error['mode'] & ZMG_ERROR_CALLBACK) {
            if (is_callable($error['callback'])) {
                call_user_func($error['callback'], $error);
            }
        }
    }
    
    function isError($data) {
    	if (is_a($data)) {
    		return true;
    	}
        return false;
    }
    
    /**
     * Get the name of this error/exception.
     *
     * @return string error/exception name (type)
     * @access public
     */
    function getType() {
        return "zmgError";
    }
}
?>
