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

//load the error handling base class
require_once(ZMG_ABS_PATH . '/lib/zmgError.php');

//load the configuration file
require(ZMG_ABS_PATH . '/etc/app.config.php');

//initialize Smarty template engine
require_once(ZMG_ABS_PATH . '/lib/smarty/Smarty.class.php');

//initialize the zoom (app) class
require_once(ZMG_ABS_PATH . '/lib/zmgConfigurationHelper.php');
require_once(ZMG_ABS_PATH . '/lib/zmgTemplateHelper.php');
require_once(ZMG_ABS_PATH . '/lib/Zoom.php');
$zoom = new Zoom();

$zoom->hasAccess() or die('Restricted access');

//load application classes
require_once(ZMG_ABS_PATH . '/lib/phpInputFilter/class.inputfilter.php');
//require_once(ZMG_ABS_PATH . '/lib/table.class.php');
require_once(ZMG_ABS_PATH . '/lib/zmgJson.php');

$zoom->fireEvents('onstartup');

//set error handling options
zmgError::setErrorHandling($zoom->getConfig('app/errors/defaultmode'),
  $zoom->getConfig('app/errors/defaultoption'));

//load php-gettext (used in zoom in 'fallback mode')
require_once(ZMG_ABS_PATH . '/lib/phpgettext/gettext.inc');
// gettext setup
T_setlocale(LC_MESSAGES, $zoom->getConfig('locale/default'));
// Set the text domain as 'messages'
$domain = $zoom->getConfig('locale/domain');
T_bindtextdomain($domain, ZMG_ABS_PATH . '/locale');
T_bind_textdomain_codeset($domain, $zoom->getConfig('locale/encoding'));
T_textdomain($domain);

//start the session
session_name('ZMG_' . md5(ZMG_ABS_PATH));
session_start();

//restore session data
$zoom->restoreSession();

$zoom->fireEvents('oncontentstart');

$zoom->template->template_dir = $zoom->getConfig('smarty/template_dir');
$zoom->template->compile_dir  = $zoom->getConfig('smarty/compile_dir');
$zoom->template->cache_dir    = $zoom->getConfig('smarty/cache_dir');
$zoom->template->config_dir   = $zoom->getConfig('smarty/config_dir');

$zoom->template->assign('pagetitle',    $zoom->getConfig('meta/title'));
$zoom->template->assign('pagedescr',    $zoom->getConfig('meta/description'));
//$zoom->template->assign('pagekeywords', $zoom->getConfig('meta/keywords'));
//$zoom->template->assign('pageauthor',   $zoom->getConfig('meta/author'));
//$zoom->template->assign('pageencoding', $zoom->getConfig('locale/encoding'));
//$zoom->template->assign('username',     $zoom->user->get('username'));

$zoom->fireEvents('oncontent');

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
        if (($file != ".") && ($file != "..")) {
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
 * @param boolean Add trailing slash
 */
function zmgPathName($p_path, $p_addtrailingslash = true) {
    $retval = "";

    $isWin = (substr(PHP_OS, 0, 3) == 'WIN');

    if ($isWin) {
        $retval = str_replace( '/', '\\', $p_path );
        if ($p_addtrailingslash) {
            if (substr( $retval, -1 ) != '\\') {
                $retval .= '\\';
            }
        }
        // Remove double \\
        $retval = str_replace( '\\\\', '\\', $retval );
    } else {
        $retval = str_replace( '\\', '/', $p_path );
        if ($p_addtrailingslash) {
            if (substr( $retval, -1 ) != '/') {
                $retval .= '/';
            }
        }
        // Remove double //
        $retval = str_replace('//','/',$retval);
    }

    return $retval;
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
 * Chmods files and directories recursively to given permissions. Available from 1.0.0 up.
 * @param path The starting file or directory (no trailing slash)
 * @param filemode Integer value to chmod files. NULL = dont chmod files.
 * @param dirmode Integer value to chmod directories. NULL = dont chmod directories.
 * @return TRUE=all succeeded FALSE=one or more chmods failed
 */
function zmgChmodRecursive($path, $filemode=NULL, $dirmode=NULL)
{
    $ret = TRUE;
    if (is_dir($path)) {
        $dh = opendir($path);
        while ($file = readdir($dh)) {
            if ($file != '.' && $file != '..') {
                $fullpath = $path.'/'.$file;
                if (is_dir($fullpath)) {
                    if (!zmgChmodRecursive($fullpath, $filemode, $dirmode))
                        $ret = FALSE;
                } else {
                    if (isset($filemode))
                        if (!@chmod($fullpath, $filemode))
                            $ret = FALSE;
                } // if
            } // if
        } // while
        closedir($dh);
        if (isset($dirmode))
            if (!@chmod($path, $dirmode))
                $ret = FALSE;
    } else {
        if (isset($filemode))
            $ret = @chmod($path, $filemode);
    } // if
    return $ret;
}

/**
 * Chmods files and directories recursively to Zoom global permissions. Available from 1.0.0 up.
 * @param path The starting file or directory (no trailing slash)
 * @param filemode Integer value to chmod files. NULL = dont chmod files.
 * @param dirmode Integer value to chmod directories. NULL = dont chmod directories.
 * @return TRUE=all succeeded FALSE=one or more chmods failed
 */
function zmgChmod($path) {
    global $zoom;
    $fileperms = $zoom->getConfig('filesystem/fileperms');
    $filemode  = NULL;
    if ($fileperms != '')
        $filemode = octdec($fileperms);
    $dirperms  = $zoom->getConfig('filesystem/dirperms');
    $dirmode   = NULL;
    if ($dirperms != '')
        $dirmode = octdec($dirperms);
    if (isset($filemode) || isset($dirmode))
        return zmgChmodRecursive($path, $filemode, $dirmode);
    return TRUE;
}

/**
 * Format a backtrace error
 */
function zmgBackTrace() {
    if (function_exists( 'debug_backtrace' )) {
        echo '<div align="left">';
        foreach( debug_backtrace() as $back) {
            if (@$back['file']) {
                echo '<br />' . str_replace( ABS_PATH, '', $back['file'] ) . ':' . $back['line'];
            }
        }
        echo '</div>';
    }
}
?>