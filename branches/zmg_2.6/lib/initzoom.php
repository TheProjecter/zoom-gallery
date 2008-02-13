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

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

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

//load the error handling base class
zmgimport('org.zoomfactory.lib.zmgError');

//initialize Smarty template engine
zmgimport('org.zoomfactory.lib.smarty.Smarty');

//initialize the zoom (app) class
zmgimport('org.zoomfactory.lib.Zoom');
//we need the events now
zmgimport('org.zoomfactory.lib.core.zmgEvent');
//require_once(ZMG_ABS_PATH . DS.'lib'.DS.'Zoom.php');
$zoom = & zmgFactory::getZoom();

if (!class_exists('InputFilter')) {
    zmgimport('org.zoomfactory.lib.phpinputfilter.inputfilter');
}

$zoom->fireEvent('onstartup', false, 'doing');

$zoom->hasAccess() or die('Restricted access');

$zoom->view->setViewType(zmgEnv::getViewType());

//load core classes
zmgimport('org.zoomfactory.lib.zmgHTML');
zmgimport('org.zoomfactory.lib.zmgJson');
zmgimport('org.zoomfactory.lib.core.*');

//set error handling options
zmgError::setErrorHandling($zoom->getConfig('app/errors/defaultmode'),
  $zoom->getConfig('app/errors/defaultoption'));

//load php-gettext (used in zoom in 'fallback mode')
zmgimport('org.zoomfactory.lib.phpgettext.gettext_inc');
// gettext setup
T_setlocale(LC_MESSAGES, $zoom->getConfig('locale/default'));
// Set the text domain as 'messages'
$domain = $zoom->getConfig('locale/domain');
T_bindtextdomain($domain, ZMG_ABS_PATH . '/locale');
T_bind_textdomain_codeset($domain, $zoom->getConfig('locale/encoding'));
T_textdomain($domain);

$zoom->fireEvent('oncontentstart');

$zoom->fireEvent('oncontent');

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
 * Utility function to return a value from a named array or a specified default
 * @param array A named array
 * @param string The key to search for
 * @param mixed The default value to give if no key found
 * @param int An options mask: _ZMG_NOTRIM prevents trim, _ZMG_ALLOWHTML allows safe html, _ZMG_ALLOWRAW allows raw input
 */
define( "_ZMG_NOTRIM",    0x0001 );
define( "_ZMG_ALLOWHTML", 0x0002 );
define( "_ZMG_ALLOWRAW",  0x0004 );
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
            } else {
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
 * Utility function to read the files in a directory
 * @param string The file system path
 * @param string A filter for the names
 * @param boolean Recurse search into sub-directories
 * @param boolean True if to prepend the full path to the file name
 */
function zmgReadDirectory( $path, $filter='.', $recurse=false, $fullpath=false  ) {
    $arr = array();
    if (!@is_dir( $path )) {
        return $arr;
    }
    $handle = opendir( $path );

    while ($file = readdir($handle)) {
        $dir = zmgPathName( $path.'/'.$file, false );
        $isDir = is_dir( $dir );
        if (($file != ".") && ($file != "..") && ($file != ".svn")) {
            if (preg_match( "/$filter/", $file )) {
                if ($fullpath) {
                    $arr[] = trim( zmgPathName( $path.'/'.$file, false ) );
                } else {
                    $arr[] = trim( $file );
                }
            }
            if ($recurse && $isDir) {
                $arr2 = zmgReadDirectory( $dir, $filter, $recurse, $fullpath );
                $arr = array_merge( $arr, $arr2 );
            }
        }
    }
    closedir($handle);
    asort($arr);
    return $arr;
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

/**
 * Chmods files and directories recursively to given permissions. Available from 1.0.0 up.
 * @param path The starting file or directory (no trailing slash)
 * @param filemode Integer value to chmod files. NULL = dont chmod files.
 * @param dirmode Integer value to chmod directories. NULL = dont chmod directories.
 * @return TRUE=all succeeded FALSE=one or more chmods failed
 */
function zmgChmodRecursive($path, $filemode = "0644", $dirmode = "0777")
{
    $ret = true;
    if (is_dir($path)) {
        $dh = opendir($path);
        while ($file = readdir($dh)) {
            if ($file != '.' && $file != '..') {
                $fullpath = $path.'/'.$file;
                if (is_dir($fullpath)) {
                    if (!zmgChmodRecursive($fullpath, $filemode, $dirmode))
                        $ret = false;
                } else {
                    if (isset($filemode))
                        if (!@chmod($fullpath, octdec($filemode)))
                            $ret = false;
                } // if
            } // if
        } // while
        closedir($dh);
        if (isset($dirmode))
            if (!@chmod($path, octdec($dirmode)))
                $ret = false;
    } else {
        if (isset($filemode))
            $ret = @chmod($path, octdec($filemode));
    } // if
    return $ret;
}

/**
 * Chmods files and directories recursively to Zoom global permissions. Available from 1.0.0 up.
 * @param path The starting file or directory (no trailing slash)
 * @return TRUE=all succeeded FALSE=one or more chmods failed
 */
function zmgChmod($path) {
    global $zoom;
    $fileperms = $zoom->getConfig('filesystem/fileperms');
    $dirperms  = $zoom->getConfig('filesystem/dirperms');
    if (isset($filemode) || isset($dirmode))
        return zmgChmodRecursive($path, $fileperms, $dirperms);
    return true;
}

/**
 * Write content to a file on the filesystem. $filename needs to be a FULL path. 
 * @param string $filename
 * @param string $content
 * @return boolean
 */
function zmgWriteFile($filename, $content) {
    $res = true;
    if ($fp = @fopen($filename, 'w+')) {
        fputs($fp, $content, strlen($content));
        fclose($fp);
    } else {
        $res = false;
    }
    return $res;
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
        foreach( debug_backtrace() as $back) {
            if (@$back['file']) {
                $out .= '<br />' . str_replace( ABS_PATH, '', $back['file'] ) . ':' . $back['line'];
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
?>