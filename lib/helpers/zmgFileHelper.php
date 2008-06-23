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
 * A File handling class
 *
 * @static
 * @author      Louis Landry <louis.landry@joomla.org>
 * @author      Mike de Boer <mike AT zoomfactory DOT org>
 */
class zmgFileHelper
{
    /**
     * Gets the extension of a file name
     *
     * @param string $file The file name
     * @return string The file extension
     */
    function getExt($file) {
        $dot = strrpos($file, '.') + 1;
        return substr($file, $dot);
    }

    /**
     * Strips the last extension off a file name
     *
     * @param string $file The file name
     * @return string The file name without the extension
     */
    function stripExt($file) {
        return preg_replace('#\.[^.]*$#', '', $file);
    }

    /**
     * Makes file name safe to use
     *
     * @param string $file The name of the file [not full path]
     * @return string The sanitised string
     */
    function makeSafe($file) {
        $regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
        return preg_replace($regex, '', $file);
    }
    
    /**
     * Function to strip additional / or \ in a path name
     *
     * @static
     * @param   string  $path   The path to clean
     * @param   string  $ds     Directory separator (optional)
     * @return  string  The cleaned path
     * @since   1.5
     */
    function cleanPath($path, $ds=DS) {
        $path = trim($path);

        if (empty($path)) {
            $path = zmgEnv::getRootPath();
        } else {
            // Remove double slashes and backslahses and convert all slashes and backslashes to DS
            $path = preg_replace('#[/\\\\]+#', $ds, $path);
        }

        return $path;
    }
    
    /**
     * Check if a file is within the filesize limits, set by the administrator.
     * @return boolean
     * @param string $file
     * @access public
     */
    function tooBig($file) {
        if (zmgFileHelper::exists($file)) {
            $config = & zmgFactory::getConfig();
            $size   = intval((filesize($file) / 1024));
            if ($size <= intval($config->get('filesystem/upload/maxfilesize'))) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Copies a file
     *
     * @param string $src The path to the source file
     * @param string $dest The path to the destination file
     * @param string $path An optional base path to prefix to the file names
     * @return boolean True on success
     */
    function copy($src, $dest, $path = null) {
        // Prepend a base path if it exists
        if ($path) {
            $src  = zmgFileHelper::cleanPath($path.DS.$src);
            $dest = zmgFileHelper::cleanPath($path.DS.$dest);
        }

        //Check src path
        if (!is_readable($src)) {
            zmgError::throwError(T_('Cannot find or read file') . ": '$src'");
            return false;
        }

        if (zmgFactory::getConfig()->get('plugins/safemode/general/enable') == 1) {
            $ret = zmgFactory::getEvents()->fire('onfilecopy', $src, $dest);
        } else {
            if (!@ copy($src, $dest)) {
                zmgError::throwError(T_('Copy failed'));
                return false;
            }
            $ret = true;
        }
        return $ret;
    }

    /**
     * Delete a file or array of files
     *
     * @param mixed $file The file name or an array of file names
     * @return boolean  True on success
     */
    function delete($file) {
        if (is_array($file)) {
            $files = $file;
        } else {
            $files[] = $file;
        }
        
        $ftp_enabled = (bool)zmgFactory::getConfig()->get('plugins/safemode/general/enable');

        foreach ($files as $file) {
            $file = zmgFileHelper::cleanPath($file);

            // Try making the file writeable first. If it's read-only, it can't be deleted
            // on Windows, even if the parent folder is writeable
            @chmod($file, 0777);

            // In case of restricted permissions we zap it one way or the other
            // as long as the owner is either the webserver or the ftp
            if (@unlink($file)) {
                // Do nothing
            } elseif ($ftp_enabled) {
                return zmgFactory::getEvents()->fire('onfiledelete', $file);
            } else {
                $filename   = basename($file);
                zmgError::throwError(T_('Delete failed') . ": '$filename'");
                return false;
            }
        }

        return true;
    }

    /**
     * Moves a file
     *
     * @param string $src The path to the source file
     * @param string $dest The path to the destination file
     * @param string $path An optional base path to prefix to the file names
     * @return boolean True on success
     */
    function move($src, $dest, $path = '') {
        if ($path) {
            $src  = zmgFileHelper::cleanPath($path.DS.$src);
            $dest = zmgFileHelper::cleanPath($path.DS.$dest);
        }

        //Check src path
        if (!is_readable($src) && !is_writable($src)) {
            return T_('Cannot find source file');
        }
        
        if (zmgFactory::getConfig()->get('plugins/safemode/general/enable') == 1) {
            if (!zmgFactory::getEvents()->fire('onfilemove', $src, $dest)) {
                zmgError::throwError(T_('Rename failed'));
                return false;
            }
        } else {
            if (!@ rename($src, $dest)) {
                zmgError::throwError(T_('Rename failed'));
                return false;
            }
        }
        return true;
    }

    /**
     * Read the contents of a file
     *
     * @param string $filename The full file path
     * @param boolean $incpath Use include path
     * @param int $amount Amount of file to read
     * @param int $chunksize Size of chunks to read
     * @return mixed Returns file contents or boolean False if failed
     */
    function read($filename, $incpath = false, $amount = 0, $chunksize = 8192) {
        // Initialize variables
        $data = null;
        if($amount && $chunksize > $amount) { $chunksize = $amount; }
        if (false === $fh = fopen($filename, 'rb', $incpath)) {
            zmgError::throwError(T_('Unable to open file') . ": '$filename'");
            return false;
        }
        clearstatcache();
        if ($fsize = @ filesize($filename)) {
            if($amount && $fsize > $amount) {
                $data = fread($fh, $amount);
            } else {
                $data = fread($fh, $fsize);
            }
        } else {
            $data = '';
            $x = 0;
            // While its:
            // 1: Not the end of the file AND
            // 2a: No Max Amount set OR
            // 2b: The length of the data is less than the max amount we want
            while (!feof($fh) && (!$amount || strlen($data) < $amount)) {
                $data .= fread($fh, $chunksize);
            }
        }
        fclose($fh);

        return $data;
    }

    /**
     * Write contents to a file
     *
     * @param string $file The full file path
     * @param string $buffer The buffer to write
     * @return boolean True on success
     */
    function write($file, $buffer) {
        // If the destination directory doesn't exist we need to create it
        if (!file_exists(dirname($file))) {
            zmgFileHelper::createDir(dirname($file));
        }
        
        if (zmgFactory::getConfig()->get('plugins/safemode/general/enable') == 1) {
            $ret = zmgFactory::getEvents()->fire('onfilewrite', $file, $buffer);
        } else {
            $file = zmgFileHelper::cleanPath($file);
            $ret = file_put_contents($file, $buffer);
        }
        return $ret;
    }

    /**
     * Moves an uploaded file to a destination folder
     *
     * @param string $src The name of the php (temporary) uploaded file
     * @param string $dest The path (including filename) to move the uploaded file to
     * @return boolean True on success
     */
    function upload($src, $dest) {
        $ret = false;

        // Ensure that the path is valid and clean
        $dest = zmgFileHelper::cleanPath($dest);

        // Create the destination directory if it does not exist
        $baseDir = dirname($dest);
        if (!file_exists($baseDir)) {
            zmgFileHelper::createDir($baseDir);
        }
        
        if (zmgFactory::getConfig()->get('plugins/safemode/general/enable') == 1) {
            // Connect the FTP client
            if (zmgFactory::getEvents()->fire('onfileupload', $src, $dest)) {
                zmgFileHelper::chmod($dest);
                $ret = true;
            } else {
                zmgError::throwError(T_('Unable to move file.'));
            }
        } else {
            if (is_writeable($baseDir) && move_uploaded_file($src, $dest)) { // Short circuit to prevent file permission errors
                if (zmgFileHelper::chmod($dest)) {
                    $ret = true;
                } else {
                    zmgError::throwError(T_('Unable to change file permissions.'));
                }
            } else {
                zmgError::throwError(T_('Unable to move file'));
            }
        }
        return $ret;
    }

    /**
     * Wrapper for the standard file_exists function
     *
     * @param string $file File path
     * @return boolean True if path is a file
     */
    function exists($file) {
        return is_file(zmgFileHelper::cleanPath($file));
    }

    /**
     * Returns the name, sans any path
     *
     * param string $file File path
     * @return string filename
     * @since 1.5
     */
    function getName($file) {
        $slash = strrpos($file, DS) + 1;
        return substr($file, $slash);
    }
    
    /**
     * Create a folder -- and all necessary parent folders
     *
     * @param string $path A path to create from the base path
     * @param int $mode Directory permissions to set for folders created
     * @return boolean True if successful
     * @since 1.5
     */
    function createDir($path = '', $mode = 0755) {
        // Initialize variables
        //jimport('joomla.client.helper');
        //$FTPOptions = JClientHelper::getCredentials('ftp');
        static $nested = 0;

        // Check to make sure the path valid and clean
        $path = zmgFileHelper::cleanPath($path);

        // Check if parent dir exists
        $parent = dirname($path);
        if (!is_dir($parent)) {
            // Prevent infinite loops!
            $nested++;
            if (($nested > 20) || ($parent == $path)) {
                zmgError::throwError(T_('Infinite loop detected'));
                $nested--;
                return false;
            }

            // Create the parent directory
            if (zmgFileHelper::createDir($parent, $mode) !== true) {
                // zmgFileHelper::createDir throws an error
                $nested--;
                return false;
            }

            // OK, parent directory has been created
            $nested--;
        }

        // Check if dir already exists
        if (is_dir($path)) {
            return true;
        }
        
        // Check for safe mode
        if (zmgFactory::getConfig()->get('plugins/safemode/general/enable') == 1) {
            $ret = zmgFactory::getEvents()->fire('ondircreate', $path);
            zmgFileHelper::chmod($path);
        } else {
            // We need to get and explode the open_basedir paths
            $obd = ini_get('open_basedir');

            // If open_basedir is set we need to get the open_basedir that the path is in
            if ($obd != null)
            {
                if (ZMG_ISWIN) {
                    $obdSeparator = ";";
                } else {
                    $obdSeparator = ":";
                }
                // Create the array of open_basedir paths
                $obdArray = explode($obdSeparator, $obd);
                $inOBD = false;
                // Iterate through open_basedir paths looking for a match
                foreach ($obdArray as $test) {
                    $test = zmgFileHelper::cleanPath($test);
                    if (strpos($path, $test) === 0) {
                        $obdpath = $test;
                        $inOBD = true;
                        break;
                    }
                }
                if ($inOBD == false) {
                    // Return false for zmgFileHelper::createDir because the path to be created is not in open_basedir
                    zmgError::throwError(T_('Path not in open_basedir paths'));
                    return false;
                }
            }

            // First set umask
            $origmask = @umask(0);

            // Create the path
            if (!$ret = @mkdir($path, $mode)) {
                @umask($origmask);
                zmgError::throwError(T_('Could not create directory'));
                return false;
            }

            // Reset umask
            @umask($origmask);
        }
        return $ret;
    }
    
    /**
     * remove a gallery completely including sub-directories.
     * @param string $path
     * @return boolean
     */
    function deleteDir($path) {
        // Sanity check
        if (!$path) {
            // Bad programmer! Bad Bad programmer!
            zmgError::throwError('zmgFileHelper: ' . _('Attempt to delete base directory'));
            return false;
        }
        
        $res  = true;

        $current_dir = opendir($path);
        while ($entryname = readdir($current_dir)) {
            if (is_dir($path . DS . $entryname) && ($entryname != "." && $entryname != "..")) {
                $res = zmgFileHelper::deleteDir($path . DS . $entryname);
            } else if ($entryname != "." && $entryname != "..") {
                $res = zmgFileHelper::delete($path . DS . $entryname);
            }
        }
        closedir($current_dir);

        if (zmgFactory::getConfig()->get('plugins/safemode/general/enable') == 1) {
            $res = zmgFactory::getEvents()->fire('ondirdelete', $path);
        } else {
            $res = rmdir($path);
        }
        
        return $res;
    }
    
    /**
     * Utility function to read the files in a directory
     * @param string The file system path
     * @param string A filter for the names
     * @param boolean Recurse search into sub-directories
     * @param boolean True if to prepend the full path to the file name
     */
    function readDir($path, $filter='.', $recurse=false, $fullpath=false) {
        $arr = array();
        if (!@is_dir($path)) {
            return $arr;
        }
        $handle = opendir($path);
    
        while ($file = readdir($handle)) {
            $dir   = zmgFileHelper::cleanPath($path.DS.$file, false);
            $isDir = is_dir( $dir );
            if (($file != ".") && ($file != "..") && ($file != ".svn")) {
                if (preg_match( "/$filter/", $file )) {
                    if ($fullpath) {
                        $arr[] = trim(zmgFileHelper::cleanPath($path.DS.$file, false));
                    } else {
                        $arr[] = trim($file);
                    }
                }
                if ($recurse && $isDir) {
                    $arr2 = zmgFileHelper::readDir($dir, $filter, $recurse, $fullpath);
                    $arr  = array_merge($arr, $arr2);
                }
            }
        }
        closedir($handle);
        asort($arr);
        return $arr;
    }
    
    /**
     * Chmods files and directories recursively to given permissions. Available from 1.0.0 up.
     * @param path The starting file or directory (no trailing slash)
     * @param filemode Integer value to chmod files. NULL = dont chmod files.
     * @param dirmode Integer value to chmod directories. NULL = dont chmod directories.
     * @return TRUE=all succeeded FALSE=one or more chmods failed
     */
    function chmodRecursive($path, $filemode = 0644, $dirmode = 0777) {
        $ret = true;
        
        $config = & zmgFactory::getConfig();
        $events = & zmgFactory::getEvents();

        if (is_dir($path)) {
            $dh = opendir($path);
            while ($file = readdir($dh)) {
                if ($file != '.' && $file != '..') {
                    $fullpath = $path . DS . $file;
                    if (is_dir($fullpath)) {
                        $ret = zmgFileHelper::chmodRecursive($fullpath, $filemode, $dirmode);
                    } else {
                        if (isset($filemode)) {
                            if ($config->get('plugins/safemode/general/enable') == 1) {
                                $ret = $events->fire('onchmod', $fullpath, $filemode);
                            } else {
                                $ret = (bool) @chmod($fullpath, $filemode);
                            }
                        }
                    }
                }
            }
            closedir($dh);
            if (isset($dirmode)) {
                if ($config->get('plugins/safemode/general/enable') == 1) {
                    $ret = $events->fire('onchmod', $path, $dirmode);
                } else {
                    $ret = (bool) @chmod($path, $dirmode);
                }
            }
        } else if (isset($filemode)) {
            if ($config->get('plugins/safemode/general/enable') == 1) {
                $ret = $events->fire('onchmod', $path, $filemode);
            } else {
                $ret = (bool) @chmod($path, $filemode);
            }
        }

        return $ret;
    }
    
    /**
     * Chmods files and directories recursively to Zoom global permissions. Available from 1.0.0 up.
     * @param path The starting file or directory (no trailing slash)
     * @return TRUE=all succeeded FALSE=one or more chmods failed
     */
    function chmod($path) {
        $config = & zmgFactory::getConfig();
        $fileperms = octdec($config->get('filesystem/fileperms'));
        $dirperms  = octdec($config->get('filesystem/dirperms'));

        if (isset($fileperms) || isset($dirperms)) {
            return zmgFileHelper::chmodRecursive($path, $fileperms, $dirperms);
        }

        return true;
    }
}
?>
