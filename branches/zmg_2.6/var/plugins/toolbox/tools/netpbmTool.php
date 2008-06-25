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

class zmgNetpbmTool {
    /**
     * Resize an image to a prefered size using the NetPBM library.
     *
     * @param string $src_file
     * @param string $dest_file
     * @param int $new_size
     * @param array $img_meta
     * @return boolean
     */
    function resize($src_file, $dest_file, $new_size, $img_meta) {
        // height/width
        $ratio      = max($img_meta['width'], $img_meta['height']) / $new_size;
        $ratio      = max($ratio, 1.0);
        $destWidth  = (int)($img_meta['width']  / $ratio);
        $destHeight = (int)($img_meta['height'] / $ratio);
        
        $path = zmgNetpbmTool::getPath();
        if ($img_meta['extension'] == "png") {
            $cmd = $path . "pngtopnm " . $src_file . " | " . $path . "pnmscale -xysize "
             . $destWidth . " " . $destHeight . " | " . $path . "pnmtopng > " . $dest_file;
        } else if ($img_meta['extension'] == "jpg" || $img_meta['extension'] == "jpeg") {
            $cmd = $path . "jpegtopnm " . $src_file . " | " . $path . "pnmscale -xysize "
             . $destWidth . " " . $destHeight . " | " . $path . "ppmtojpeg -quality="
             . $img_meta['jpeg_qty'] . " > " . $dest_file;
        } else if ($img_meta['extension'] == "gif") {
            $cmd = $path . "giftopnm " . $src_file . " | " . $path . "pnmscale -xysize "
             . $destWidth . " " . $destHeight . " | " . $path . "ppmquant 256 | "
             . $path . "ppmtogif > " . $dest_file;
        } else {
            return zmgToolboxPlugin::registerError($src_file, 'NetPBM: Source file is not an image or image type is not supported.');
        }

        $output = $retval = null;
        exec($cmd, $output, $retval);
        if ($retval) {
            return zmgToolboxPlugin::registerError($src_file, 'NetPBM: Could not convert image: ' . $output);
        }

        return true;
    }

    /**
     * Rotate an image with the prefered number of degrees using the NetPBM library.
     *
     * @param string $src_file
     * @param string $dest_file
     * @param int $degrees
     * @param array $img_meta
     * @return boolean
     */
    function rotate($src_file, $dest_file, $degrees, $img_meta) {
        $fileOut = $src_file . ".1";
        @copy($src_file, $fileOut);
        
        $path = zmgNetpbmTool::getPath();
        if ($img_meta['extension'] == "png") {
            $cmd = $path . "pngtopnm " . $src_file . " | " . $path . "pnmrotate "
             . $degrees . " | " . $path . "pnmtopng > " . $fileOut;
        } else if ($img_meta['extension'] == "jpg" || $img_meta['extension'] == "jpeg") {
            $cmd = $path . "jpegtopnm " . $src_file . " | " . $path . "pnmrotate "
             . $degrees . " | " . $path . "ppmtojpeg -quality=" . $img_meta['jpeg_qty']
             . " > " . $fileOut;
        } else if ($img_meta['extension'] == "gif") {
            $cmd = $path . "giftopnm " . $src_file . " | " . $path . "pnmrotate "
             . $degrees . " | " . $path . "ppmquant 256 | " . $path . "ppmtogif > "
             . $fileOut;
        } else {
            return zmgToolboxPlugin::registerError($src_file, 'NetPBM: Source file is not an image or image type is not supported.');
        }

        $output = $retval = null;
        exec($cmd, $output, $retval);
        if ($retval) {
            return zmgToolboxPlugin::registerError($src_file, 'NetPBM: Could not rotate image: ' . $output);
        }
        $erg = @rename($fileOut, $dest_file);

        return true;
    }
    
    function getPath() {
         $config = & zmgFactory::getConfig();
         
         $path     = trim($config->get('plugins/toolbox/netpbm/path'));
         $override = intval($config->get('plugins/toolbox/netpbm/override'));
         
         if ($path == "auto") {
            $path = zmgNetpbmTool::detectPath();
         }
         
         return $path;
    }
    
    function detectPath() {
        $path = "";
        if (file_exists('/usr/bin/jpegtopnm') && is_executable('/usr/bin/jpegtopnm')) {
            $path = "/usr/bin/"; //Debian systems
        }
        return $path;
    }
    
    /**
     * Detect if NetPBM is available on the system.
     *
     * @return void
     */
    function autoDetect() {
        static $output, $status;
        //get the absolute location first:
        $path = zmgNetpbmTool::detectPath();
        //execute test command
        @exec($path . 'jpegtopnm -version 2>&1',  $output, $status);
        
        $res = false;
        if (!$status) {
            if (preg_match("/netpbm[ \t]+([0-9\.]+)/i", $output[0], $matches)) {
                zmgToolboxPlugin::registerError('NetPBM', $matches[0] . ' ' . T_('is available.'));
                $res = true;
            }
        }
        if (!$res) {
            zmgToolboxPlugin::registerError('NetPBM', T_('could not be detected on your system.'));
        }
        unset($output, $status);
    }
}
?>
