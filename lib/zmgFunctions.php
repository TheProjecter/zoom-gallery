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

/** boolean True if a Windows based host */
define('ZMG_ISWIN', (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'));
/** boolean True if a Mac based host */
define('ZMG_ISMAC', (strtoupper(substr(PHP_OS, 0, 3)) === 'MAC'));

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

define('ZMG_SAFEMODE_ON', (bool)ini_get('safe_mode'));

//define datatype shorthands as well
define('ZMG_DATATYPE_STRING', 0x0001);
define('ZMG_DATATYPE_NUMBER', 0x0002);
define('ZMG_DATATYPE_ARRAY',  0x0004);
define('ZMG_DATATYPE_OBJECT', 0x0008);

/**
 * Loads a class from specified directories.
 *
 * @param string $name  The class name to look for ( dot notation ).
 * @param string $base  Search this directory for the class.
 * @param string $key   String used as a prefix to denote the full path of the file ( dot notation ).
 * @return boolean True if the requested class has been successfully included
 */
function zmgimport($keyPath, $base = ZMG_ABS_PATH, $key = 'org.zoomfactory.') {
    static $paths;
    if (!isset($paths)) {
        $paths = array();
    }

    if (empty($key) || !strstr($keyPath, $key)) {
        return false;
    }
    $filePath = str_replace($key, '', $keyPath);
    $trs     = 1;
    if (!isset($paths[$keyPath])) {
        $parts = explode('.', $filePath);
        if (!$base) {
            $base = dirname( __FILE__ );
        }
        if (array_pop($parts) == '*') {
            $path = $base . DS . implode(DS, $parts);
            if (!is_dir( $path )) {
                return false;
            }
            $dir = dir($path);
            while ($file = $dir->read()) {
                if (preg_match('#(.*?)\.php$#', $file, $m)) {
                    $nPath   = str_replace('*', $m[1], $filePath);
                    $keyPath = $key . $nPath;
                    // we need to check each file again incase one has a jimport
                    if (!isset($paths[$keyPath])) {
                        $rs = include($path . DS . $file);
                        $paths[$keyPath] = $rs;
                        $trs =& $rs;
                    }
                }
            }
            $dir->close();
        } else {
            $path = str_replace( '.', DS, $filePath );
            $trs  = include($base . DS . $path . '.php');
        }
        $paths[$keyPath] = $trs;
    }
    return $trs;
}
    
/**
 * Call an abstract/ static function that resides within a static class.
 * Note: particularly useful within templates.
 * @param string Name of the static class
 * @param string Name of the function to call
 * @param mixed The arguments that should be passed to the function call
 * @return mixed
 */
function zmgCallAbstract($klass, $func, $args = null) {
    if (is_callable(array($klass, $func))) {
        if (!is_array($args)) {
        	$args = array($args);
        }
        return call_user_func_array(array($klass, $func), $args);
    }
    return null;
}

/**
 * Clone an object, backward-compatible with php4.
 * @param object The object to clone
 * @return object The clone.
 */
function zmgClone($object) {
    if (version_compare(phpversion(), '5.0') < 0) {
        return $object;
    }
    return @clone($object);
}

/**
 * Encrypt given URI parameters, so admin functions will not be available to hackers.
 * @param string $string
 * @return string
 */
function zmgEncrypt($string) {
    if (isset($string) && substr($string, 1, 4) != 'obfs') {
        $convert = "";
        for ($i = 0; $i < strlen($string); $i++) {
            $dec = ord(substr($string, $i, 1));
            if (strlen($dec) == 2) $dec = 0 . $dec;
            $dec = 324 - $dec;
            $convert .= $dec;
        }
        $convert = '{obfs:' . $convert . '}';
        return $convert;
    } else {
        return $string;
    }
}
/**
 * Decrypt a given URI parameter (which has to encrypted first!), so we can use
 * the original parameters again.
 * @param string $string
 * @return string
 */
function zmgDecrypt($string) {
    if (isset($string) && substr($string, 1, 4) == 'obfs') {
        $convert = '';
        for ($i = 6; $i < strlen($string) - 1; $i = $i + 3) {
            $dec = substr($string, $i, 3);
            $dec = 324 - $dec;
            $dec = chr($dec);			
            $convert .= $dec;
        }
        return $convert;
    } else {
        return $string;
    }
} 

/**
 * Utility function to return a value from a named array or a specified default
 * @param array A named array
 * @param string The key to search for
 * @param mixed The default value to give if no key found
 * @param int An options mask: _ZMG_NOTRIM prevents trim, _ZMG_ALLOWHTML allows safe html, _ZMG_ALLOWRAW allows raw input
 */
define( "_ZMG_NOTRIM",    0x0001 );
define( "_ZMG_ALLOWHTML", 0x0002 );
define( "_ZMG_ALLOWRAW",  0x0004 );
define( "_ZMG_NOFILTER",  0x0008 );
function zmgGetParam( &$arr, $name, $def=null, $mask=0 ) {
    static $noHtmlFilter = null;
    static $safeHtmlFilter = null;

    $return = null;
    if (isset( $arr[$name] )) {
        if (is_string( $arr[$name] )) {
            if (!($mask&_ZMG_NOTRIM)) {
                $arr[$name] = trim( $arr[$name] );
            }
            if ($mask&_ZMG_ALLOWRAW) {
                // do nothing
            } else if ($mask&_ZMG_ALLOWHTML) {
                // do nothing - compatibility mode
            } else if (!($mask&_ZMG_NOFILTER)) {
                if (is_null( $noHtmlFilter )) {
                    $noHtmlFilter = new InputFilter( /* $tags, $attr, $tag_method, $attr_method, $xss_auto */ );
                }
                $arr[$name] = $noHtmlFilter->process( $arr[$name] );
            }
            if (!get_magic_quotes_gpc()) {
                $arr[$name] = addslashes( $arr[$name] );
            }
        }
        return $arr[$name];
    } else {
        return $def;
    }
}

/**
 * Strip slashes from strings or arrays of strings
 * @param mixed The input string or array
 * @return mixed String or array stripped of slashes
 */
function zmgStripslashes( &$value ) {
    $ret = '';
    if (is_string( $value )) {
        $ret = stripslashes( $value );
    } else {
        if (is_array( $value )) {
            $ret = array();
            foreach ($value as $key => $val) {
                $ret[$key] = zmgStripslashes( $val );
            }
        } else {
            $ret = $value;
        }
    }
    return $ret;
}

/**
* Copy the named array content into the object as properties
* only existing properties of object are filled. when undefined in hash, properties wont be deleted
* @param array the input array
* @param obj byref the object to fill of any class
* @param string
* @param boolean
*/
function zmgBindArrayToObject( $array, &$obj, $ignore='', $prefix=NULL, $checkSlashes=true ) {
    if (!is_array( $array ) || !is_object( $obj )) {
        return (false);
    }

    foreach (get_object_vars($obj) as $k => $v) {
        if( substr( $k, 0, 1 ) != '_' ) {           // internal attributes of an object are ignored
            if (strpos( $ignore, $k) === false) {
                if ($prefix) {
                    $ak = $prefix . $k;
                } else {
                    $ak = $k;
                }
                if (isset($array[$ak])) {
                    $obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? zmgStripslashes( $array[$ak] ) : $array[$ak];
                }
            }
        }
    }

    return true;
}

/**
 * Utility function redirect the browser location to another url
 *
 * Can optionally provide a message.
 * @param string The file system path
 * @param string A filter for the names
 */
function zmgRedirect( $url, $msg='' ) {
    // specific filters
    $iFilter = new InputFilter();
    $url = $iFilter->process( $url );
    if (!empty($msg)) {
        $msg = $iFilter->process( $msg );
    }

    if ($iFilter->badAttributeValue( array( 'href', $url ))) {
        $url = $_SERVER['PHP_SELF'];
    }

    if (trim( $msg )) {
        if (strpos( $url, '?' )) {
            $url .= '&zmgmsg=' . urlencode( $msg );
        } else {
            $url .= '?zmgmsg=' . urlencode( $msg );
        }
    }

    if (headers_sent()) {
        echo "<script>document.location.href='$url';</script>\n";
    } else {
        @ob_end_clean(); // clear output buffer
        header( 'HTTP/1.1 301 Moved Permanently' );
        header( "Location: ". $url );
    }
    exit();
}

/**
 * Function to strip additional / or \ in a path name
 * @param string The path
 */
function zmgPathName($path, $ds = DS) {
    $path = trim($path);

    if (empty($path)) {
        $path = ZMG_ABS_PATH;
    } else {
        // Remove double slashes and backslahses and convert all slashes and backslashes to DS
        $path = preg_replace('#[/\\\\]+#', $ds, $path);
    }

    return $path;
}

/**
 * Replaces &amp; with & for xhtml compliance
 *
 * Needed to handle unicode conflicts due to unicode conflicts
 */
function zmgAmpReplace( $text ) {
    $text = str_replace( '&&', '*--*', $text );
    $text = str_replace( '&#', '*-*', $text );
    $text = str_replace( '&amp;', '&', $text );
    $text = preg_replace( '|&(?![\w]+;)|', '&amp;', $text );
    $text = str_replace( '*-*', '&#', $text );
    $text = str_replace( '*--*', '&&', $text );

    return $text;
}

/**
  * @author Chris Tobin
  * @author Daniel Morris
  * @param String $source
  * @return String $source
  */
function zmgSQLEscape($string) {
    // depreciated function
    if (version_compare(phpversion(),"4.3.0", "<")) mysql_escape_string($string);
    // current function
    else mysql_real_escape_string($string);
    return $string;
}

function zmgGetBasePath() {
    $path = "";
    if (strpos(php_sapi_name(), 'cgi') !== false && !empty($_SERVER['REQUEST_URI'])) {
        //Apache CGI
        $path =  rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    } else {
        //Others
        $path =  rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }
    return str_replace('/administrator', '', $path) . "/components/com_zoom";
}

/**
 * Format a backtrace error
 * @param int An output mask
 */
define("ZMG_OUT_DIRECT", 0x0001);
define("ZMG_OUT_STRING", 0x0002);
function zmgBackTrace($ret_mask = 0) {
    if ($ret_mask === 0) {
        $ret_mask = ZMG_OUT_DIRECT;
    }
    $out = '';
    if (function_exists( 'debug_backtrace' )) {
        $out .= '<div align="left">';
        foreach(debug_backtrace() as $back) {
            if (@$back['file']) {
                $out .= '<br />' . str_replace(ZMG_ABS_PATH, '', $back['file']) . ':' . $back['line'];
            }
        }
        $out .= '</div>';
    }
    if ($ret_mask & ZMG_OUT_DIRECT) {
        echo $out;
    } else if ($ret_mask & ZMG_OUT_STRING) {
        return $out;
    }
    return;
}