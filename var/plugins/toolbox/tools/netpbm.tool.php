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

class zmgNetpbmTool {
    /**
     * Resize an image to a prefered size using the NetPBM library.
     *
     * @param string $src_file
     * @param string $dest_file
     * @param int $new_size
     * @param image $imgobj
     * @return boolean
     */
    function resize($src_file, $dest_file, $new_size, &$imgobj) {
        if ($imgobj->_size == null) {
            return zmgToolboxPlugin::registerError($src_file, 'NetPBM: No correct arguments supplied.');
        }
        // height/width
        $ratio = max($imgobj->_size[0], $imgobj->_size[1]) / $new_size;
        $ratio = max($ratio, 1.0);
        $destWidth = (int)($imgobj->_size[0] / $ratio);
        $destHeight = (int)($imgobj->_size[1] / $ratio);
        if (eregi("\.png", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "pngtopnm $src_file | " . $this->_NETPBM_path . "pnmscale -xysize $destWidth $destHeight | " . $this->_NETPBM_path . "pnmtopng > $dest_file" ; 
        } elseif (eregi("\.(jpg|jpeg)", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "jpegtopnm $src_file | " . $this->_NETPBM_path . "pnmscale -xysize $destWidth $destHeight | " . $this->_NETPBM_path . "ppmtojpeg -quality=" . $this->_JPEG_quality . " > $dest_file" ;
        } elseif (eregi("\.gif", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "giftopnm $src_file | " . $this->_NETPBM_path . "pnmscale -xysize $destWidth $destHeight | " . $this->_NETPBM_path . "ppmquant 256 | " . $this->_NETPBM_path . "ppmtogif > $dest_file" ; 
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
     * @param image $imgobj
     * @return boolean
     */
    function rotate($src_file, $dest_file, $degrees, $imgobj) {
        $fileOut = "$src_file.1";
        $zoom->platform->copy($src_file, $fileOut); 
        if (eregi("\.png", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "pngtopnm $src_file | " . $this->_NETPBM_path . "pnmrotate $degrees | " . $this->_NETPBM_path . "pnmtopng > $fileOut" ; 
        } elseif (eregi("\.(jpg|jpeg)", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "jpegtopnm $src_file | " . $this->_NETPBM_path . "pnmrotate $degrees | " . $this->_NETPBM_path . "ppmtojpeg -quality=" . $this->_JPEG_quality . " > $fileOut" ;
        } elseif (eregi("\.gif", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "giftopnm $src_file | " . $this->_NETPBM_path . "pnmrotate $degrees | " . $this->_NETPBM_path . "ppmquant 256 | " . $this->_NETPBM_path . "ppmtogif > $fileOut" ; 
        } else {
            return zmgToolboxPlugin::registerError($src_file, 'NetPBM: Source file is not an image or image type is not supported.');
        }
        $output = $retval = null;
        exec($cmd, $output, $retval);
        if ($retval) {
            return zmgToolboxPlugin::registerError($src_file, 'NetPBM: Could not rotate image: ' . $output);
        }
        $erg = $zoom->platform->rename($fileOut, $dest_file); 
        return true;
    }
    /**
     * Detect if NetPBM is available on the system.
     *
     * @return void
     */
    function autoDetect() {
        static $output, $status;
        @exec('jpegtopnm -version 2>&1',  $output, $status);
        
        $res = false;
        if (!$status) {
            if (preg_match("/netpbm[ \t]+([0-9\.]+)/i",$output[0],$matches)) {
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
