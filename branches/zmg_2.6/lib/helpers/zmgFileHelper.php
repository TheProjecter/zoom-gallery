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
            $zoom = & zmgFactory::getZoom();
            $size = intval((filesize($file) / 1024));
            if ($size <= intval($zoom->getConfig('filesystem/upload/maxfilesize'))) {
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
        // Initialize variables
        //jimport('joomla.client.helper');
        //$FTPOptions = JClientHelper::getCredentials('ftp');

        // Prepend a base path if it exists
        if ($path) {
            $src  = zmgPathName($path.DS.$src);
            $dest = zmgPathName($path.DS.$dest);
        }

        //Check src path
        if (!is_readable($src)) {
            zmgError::throwError(T_('Cannot find or read file') . ": '$src'");
            return false;
        }

        if (false) {//$FTPOptions['enabled'] == 1) {
            // Connect the FTP client
            jimport('joomla.client.ftp');
            $ftp = & JFTP::getInstance($FTPOptions['host'], $FTPOptions['port'], null, $FTPOptions['user'], $FTPOptions['pass']);

            // If the parent folder doesn't exist we must create it
            if (!file_exists(dirname($dest))) {
                jimport('joomla.filesystem.folder');
                JFolder::create(dirname($dest));
            }

            //Translate the destination path for the FTP account
            $dest = zmgPathName(str_replace(zmgEnv::getRootPath(), $FTPOptions['root'], $dest), '/');
            if (!$ftp->store($src, $dest)) {
                // FTP connector throws an error
                return false;
            }
            $ret = true;
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
        // Initialize variables
        //jimport('joomla.client.helper');
        //$FTPOptions = JClientHelper::getCredentials('ftp');

        if (is_array($file)) {
            $files = $file;
        } else {
            $files[] = $file;
        }

        // Do NOT use ftp if it is not enabled
        if (false) {//$FTPOptions['enabled'] == 1) {
            // Connect the FTP client
            jimport('joomla.client.ftp');
            $ftp = & JFTP::getInstance($FTPOptions['host'], $FTPOptions['port'], null, $FTPOptions['user'], $FTPOptions['pass']);
        }

        foreach ($files as $file) {
            $file = zmgPathName($file);

            // Try making the file writeable first. If it's read-only, it can't be deleted
            // on Windows, even if the parent folder is writeable
            @chmod($file, 0777);

            // In case of restricted permissions we zap it one way or the other
            // as long as the owner is either the webserver or the ftp
            if (@unlink($file)) {
                // Do nothing
            } elseif ($FTPOptions['enabled'] == 1) {
                $file = zmgPathName(str_replace(zmgEnv::getRootPath(), $FTPOptions['root'], $file), '/');
                if (!$ftp->delete($file)) {
                    // FTP connector throws an error
                    return false;
                }
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
        // Initialize variables
        //jimport('joomla.client.helper');
        //$FTPOptions = JClientHelper::getCredentials('ftp');

        if ($path) {
            $src  = zmgPathName($path.DS.$src);
            $dest = zmgPathName($path.DS.$dest);
        }

        //Check src path
        if (!is_readable($src) && !is_writable($src)) {
            return T_('Cannot find source file');
        }

        if (false) {//$FTPOptions['enabled'] == 1) {
            // Connect the FTP client
            jimport('joomla.client.ftp');
            $ftp = & JFTP::getInstance($FTPOptions['host'], $FTPOptions['port'], null, $FTPOptions['user'], $FTPOptions['pass']);

            //Translate path for the FTP account
            $src    = zmgPathName(str_replace(JPATH_ROOT, $FTPOptions['root'], $src), '/');
            $dest   = zmgPathName(str_replace(JPATH_ROOT, $FTPOptions['root'], $dest), '/');

            // Use FTP rename to simulate move
            if (!$ftp->rename($src, $dest)) {
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
        // Initialize variables
        //jimport('joomla.client.helper');
        //$FTPOptions = JClientHelper::getCredentials('ftp');

        // If the destination directory doesn't exist we need to create it
        if (!file_exists(dirname($file))) {
            zmgFileHelper::createDir(dirname($file));
        }

        if (false) {//$FTPOptions['enabled'] == 1) {
            // Connect the FTP client
            jimport('joomla.client.ftp');
            $ftp = & JFTP::getInstance($FTPOptions['host'], $FTPOptions['port'], null, $FTPOptions['user'], $FTPOptions['pass']);

            // Translate path for the FTP account and use FTP write buffer to file
            $file = zmgPathName(str_replace(zmgEnv::getRootPath(), $FTPOptions['root'], $file), '/');
            $ret = $ftp->write($file, $buffer);
        } else {
            $file = zmgPathName($file);
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
        // Initialize variables
        //jimport('joomla.client.helper');
        //$FTPOptions = JClientHelper::getCredentials('ftp');
        $ret        = false;

        // Ensure that the path is valid and clean
        $dest = zmgPathName($dest);

        // Create the destination directory if it does not exist
        $baseDir = dirname($dest);
        if (!file_exists($baseDir)) {
            zmgFileHelper::createDir($baseDir);
        }

        if (false) {//$FTPOptions['enabled'] == 1) {
            // Connect the FTP client
            jimport('joomla.client.ftp');
            $ftp = & JFTP::getInstance($FTPOptions['host'], $FTPOptions['port'], null, $FTPOptions['user'], $FTPOptions['pass']);

            //Translate path for the FTP account
            $dest = zmgPathName(str_replace(zmgEnv::getRootPath(), $FTPOptions['root'], $dest), '/');

            // Copy the file to the destination directory
            if ($ftp->store($src, $dest)) {
                $ftp->chmod($dest, 0777);
                $ret = true;
            } else {
                zmgError::throwError(T_('Unable to move file.'));
            }
        } else {
            if (is_writeable($baseDir) && move_uploaded_file($src, $dest)) { // Short circuit to prevent file permission errors
                if (zmgChmod($dest, 0777)) {
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
        return is_file(zmgPathName($file));
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
    function createDir($path = '', $mode = 0755)
    {
        // Initialize variables
        //jimport('joomla.client.helper');
        //$FTPOptions = JClientHelper::getCredentials('ftp');
        static $nested = 0;

        // Check to make sure the path valid and clean
        $path = zmgPathName($path);

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

        // Check for safe mode NOT SUPPORTED BY ZMG (yet)
        if (false) {//''$FTPOptions['enabled'] == 1) {
            // Connect the FTP client
            jimport('joomla.client.ftp');
            $ftp = & JFTP::getInstance($FTPOptions['host'], $FTPOptions['port'], null, $FTPOptions['user'], $FTPOptions['pass']);

            // Translate path to FTP path
            $path = zmgPathName(str_replace(zmgEnv::getRootPath(), $FTPOptions['root'], $path), '/');
            $ret = $ftp->mkdir($path);
            $ftp->chmod($path, $mode);
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
                    $test = zmgPathName($test);
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
            $origmask = @ umask(0);

            // Create the path
            if (!$ret = @mkdir($path, $mode)) {
                @ umask($origmask);
                zmgError::throwError(T_('Could not create directory'));
                return false;
            }

            // Reset umask
            @ umask($origmask);
        }
        return $ret;
    }
}
?>
