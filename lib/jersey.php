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

//load compatibility file to bridge different PHP versions
require_once(ABS_PATH . '/lib/compat.php');

//load the error handling base class
require_once(ABS_PATH . '/lib/error.class.php');

//load the configuration file
require(ABS_PATH . '/etc/app.config.php');

//load application classes
require_once(ABS_PATH . '/lib/phpInputFilter/class.inputfilter.php');
require_once(ABS_PATH . '/lib/table.class.php');
require_once(ABS_PATH . '/lib/notifier.class.php');
require_once(ABS_PATH . '/lib/postit.class.php');
require_once(ABS_PATH . '/lib/project.class.php');
require_once(ABS_PATH . '/lib/user.class.php');

//initialize the jersey (app) class
require_once(ABS_PATH . '/lib/jersey.class.php');
$jersey = new jersey();

$jersey->fireEvents('onstartup');

//set error handling options
jsyError::setErrorHandling($jersey->getConfig('app/errors/defaultmode'),
  $jersey->getConfig('app/errors/defaultoption'));

//initialize the database connection
require_once(ABS_PATH . '/lib/adodb/adodb.inc.php');
$ADODB_CACHE_DIR = ABS_PATH . "/etc/cache/";
$jersey->db = NewADOConnection($jersey->getConfig('db/driver'));
$jersey->db->PConnect($jersey->getConfig('db/host'), $jersey->getConfig('db/user'),
    $jersey->getConfig('db/password'), $jersey->getConfig('db/database'));

//initialize phpGACL (Access Control Lists)
require_once(ABS_PATH . '/lib/phpgacl/gacl.class.php');
$jersey->acl = new gacl(array(
    'db'                 => &$jersey->db,
    'caching'            => false,
    'force_cache_expire' => true,
    'cache_dir'          => ABS_PATH . "/etc/cache",
    'cache_expire_time'  => 600
));

//load GeSHi
require_once(ABS_PATH . '/lib/geshi/geshi.php');

//load php-gettext (used in Jersey in 'fallback mode')
require_once(ABS_PATH . '/lib/phpgettext/gettext.inc');
// gettext setup
T_setlocale(LC_MESSAGES, $jersey->getConfig('locale/default'));
// Set the text domain as 'messages'
$domain = $jersey->getConfig('locale/domain');
T_bindtextdomain($domain, ABS_PATH . '/locale');
T_bind_textdomain_codeset($domain, $jersey->getConfig('locale/encoding'));
T_textdomain($domain);

//start the session
session_name(md5(ABS_PATH));
session_start();

//restore session data
$jersey->restoreSession();
if (!$jersey->user) {
    $jersey->login('mdeboer', 'mike1324');
}

$jersey->fireEvents('oncontentstart');

//initialize Smarty template engine
require_once(ABS_PATH . '/lib/smarty/Smarty.class.php');
$jersey->template = new Smarty();

$jersey->template->template_dir = $jersey->getConfig('smarty/template_dir');
$jersey->template->compile_dir  = $jersey->getConfig('smarty/compile_dir');
$jersey->template->cache_dir    = $jersey->getConfig('smarty/cache_dir');
$jersey->template->config_dir   = $jersey->getConfig('smarty/config_dir');

$jersey->template->assign('pagetitle',    $jersey->getConfig('meta/title'));
$jersey->template->assign('pagedescr',    $jersey->getConfig('meta/description'));
$jersey->template->assign('pagekeywords', $jersey->getConfig('meta/keywords'));
$jersey->template->assign('pageauthor',   $jersey->getConfig('meta/author'));
$jersey->template->assign('pageencoding', $jersey->getConfig('locale/encoding'));
$jersey->template->assign('username',     $jersey->user->get('username'));

$jersey->fireEvents('oncontent');

/**
 * Utility function to return a value from a named array or a specified default
 * @param array A named array
 * @param string The key to search for
 * @param mixed The default value to give if no key found
 * @param int An options mask: _JSY_NOTRIM prevents trim, _JSY_ALLOWHTML allows safe html, _JSY_ALLOWRAW allows raw input
 */
define( "_JSY_NOTRIM",    0x0001 );
define( "_JSY_ALLOWHTML", 0x0002 );
define( "_JSY_ALLOWRAW",  0x0004 );
function jsyGetParam( &$arr, $name, $def=null, $mask=0 ) {
    static $noHtmlFilter = null;
    static $safeHtmlFilter = null;

    $return = null;
    if (isset( $arr[$name] )) {
        if (is_string( $arr[$name] )) {
            if (!($mask&_JSY_NOTRIM)) {
                $arr[$name] = trim( $arr[$name] );
            }
            if ($mask&_JSY_ALLOWRAW) {
                // do nothing
            } else if ($mask&_JSY_ALLOWHTML) {
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
function jsyStripslashes( &$value ) {
    $ret = '';
    if (is_string( $value )) {
        $ret = stripslashes( $value );
    } else {
        if (is_array( $value )) {
            $ret = array();
            foreach ($value as $key => $val) {
                $ret[$key] = jsyStripslashes( $val );
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
function jsyBindArrayToObject( $array, &$obj, $ignore='', $prefix=NULL, $checkSlashes=true ) {
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
                    $obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ? jsyStripslashes( $array[$ak] ) : $array[$ak];
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
function jsyReadDirectory( $path, $filter='.', $recurse=false, $fullpath=false  ) {
    $arr = array();
    if (!@is_dir( $path )) {
        return $arr;
    }
    $handle = opendir( $path );

    while ($file = readdir($handle)) {
        $dir = jsyPathName( $path.'/'.$file, false );
        $isDir = is_dir( $dir );
        if (($file != ".") && ($file != "..")) {
            if (preg_match( "/$filter/", $file )) {
                if ($fullpath) {
                    $arr[] = trim( jsyPathName( $path.'/'.$file, false ) );
                } else {
                    $arr[] = trim( $file );
                }
            }
            if ($recurse && $isDir) {
                $arr2 = jsyReadDirectory( $dir, $filter, $recurse, $fullpath );
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
function jsyRedirect( $url, $msg='' ) {
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
            $url .= '&jsymsg=' . urlencode( $msg );
        } else {
            $url .= '?jsymsg=' . urlencode( $msg );
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
function jsyPathName($p_path, $p_addtrailingslash = true) {
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
function jsyAmpReplace( $text ) {
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
function jsyChmodRecursive($path, $filemode=NULL, $dirmode=NULL)
{
    $ret = TRUE;
    if (is_dir($path)) {
        $dh = opendir($path);
        while ($file = readdir($dh)) {
            if ($file != '.' && $file != '..') {
                $fullpath = $path.'/'.$file;
                if (is_dir($fullpath)) {
                    if (!jsyChmodRecursive($fullpath, $filemode, $dirmode))
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
 * Chmods files and directories recursively to Jersey global permissions. Available from 1.0.0 up.
 * @param path The starting file or directory (no trailing slash)
 * @param filemode Integer value to chmod files. NULL = dont chmod files.
 * @param dirmode Integer value to chmod directories. NULL = dont chmod directories.
 * @return TRUE=all succeeded FALSE=one or more chmods failed
 */
function jsyChmod($path) {
    global $jersey;
    $fileperms = $jersey->getConfig('filesystem/fileperms');
    $filemode  = NULL;
    if ($fileperms != '')
        $filemode = octdec($fileperms);
    $dirperms  = $jersey->getConfig('filesystem/dirperms');
    $dirmode   = NULL;
    if ($dirperms != '')
        $dirmode = octdec($dirperms);
    if (isset($filemode) || isset($dirmode))
        return jsyChmodRecursive($path, $filemode, $dirmode);
    return TRUE;
}

/**
 * Format a backtrace error
 */
function jsyBackTrace() {
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