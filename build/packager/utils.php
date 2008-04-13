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

class packagerUtils {
    /**
     * Utility function to read the files in a directory
     * @param string The file system path
     * @param string A filter for the names
     * @param boolean Recurse search into sub-directories
     * @param boolean True if to prepend the full path to the file name
     */
    function readDir($path, $filter='.', $recurse=false, $fullpath=false, $filesonly=false) {
        $arr = array();
        if (!@is_dir( $path )) {
            return $arr;
        }
        $handle = opendir($path);
    
        while ($file = readdir($handle)) {
            $dir   = $path.DS.$file;
            $isDir = is_dir($dir);
            if (($file != ".") && ($file != "..") && ($file != ".svn")) {
                if (preg_match( "/$filter/", $file ) && !($isDir && $filesonly)) {
                    if ($fullpath) {
                        $arr[] = trim($path.DS.$file);
                    } else {
                        $arr[] = trim($file);
                    }
                }
                if ($recurse && $isDir) {
                    $arr2 = packagerUtils::readDir($dir, $filter, $recurse, $fullpath, $filesonly);
                    $arr  = array_merge($arr, $arr2);
                }
            }
        }
        closedir($handle);
        asort($arr);
        return $arr;
    }
}
?>
