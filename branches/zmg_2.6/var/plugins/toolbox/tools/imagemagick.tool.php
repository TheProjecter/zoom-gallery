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

class zmgImagemagickTool {
    /**
     * Resize an image to a prefered size using the ImageMagick library.
     *
     * @param string $src_file
     * @param string $dest_file
     * @param int $new_size
     * @param image $imgobj
     * @return boolean
     */
    function resize($src_file, $dest_file, $new_size) {
        $retval = $output = null;
        $cmd = $this->_IM_path."convert -resize $new_size \"$src_file\" \"$dest_file\"";
        exec($cmd, $output, $retval);
        if($retval) {
            return parent::registerError($src_file, 'ImageMagick: Could not convert image: ' . $output);
        }
        return true;
    }
    /**
     * Rotate an image with the prefered number of degrees using the ImageMagick library.
     *
     * @param string $src_file
     * @param string $dest_file
     * @param int $degrees
     * @return boolean
     */
    function rotate($src_file, $dest_file, $degrees) {
        $cmd = $this->_IM_path."convert -rotate $degrees \"$src_file\" \"$dest_file\"";
        $output = $retval = null;
        exec($cmd, $output, $retval);
        if($retval) {
            return parent::registerError($src_file, 'ImageMagick: Could not rotate image: ' . $output);
        }
        return true;
    }
    /**
     * Apply a watermark to an image using the ImageMagick library
     *
     * @param string $file
     * @param string $desfile
     * @param string $wm_file The absolute location to the watermark image.
     * @param string $position Position of the watermark on the Destination image.
     * @param Image $imgobj
     * @return boolean
     * @access private
     */
    function watermark($src_file, $dest_file, $wm_file, $position, &$imgobj) {
        $imginfo_wm = getimagesize($wm_file);

        $imagewidth = $imgobj->_size[0];
        $imageheight = $imgobj->_size[1];
        $watermarkwidth = $imginfo_wm[0];
        $watermarkheight = $imginfo_wm[1];      
        $width_left = $imagewidth - $watermarkwidth;
        $height_left = $imageheight - $watermarkheight;
        switch ($position) {
            case "TL": // Top Left
                $startwidth = $width_left >= 5 ? 4 : $width_left;
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "TM": // Top middle 
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "TR": // Top right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "CL": // Center left
                $startwidth = $width_left >= 5 ? 4 : $width_left;
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            default:
            case "C": // Center (the default)
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            case "CR": // Center right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            case "BL": // Bottom left
                $startwidth = $width_left >= 5 ? 5 : $width_left;
                $startheight = $imageheight - $watermarkheight-5;
                break;
            case "BM": // Bottom middle
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = $imageheight - $watermarkheight-5;
                break;
            case "BR": // Bottom right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = $imageheight - $watermarkheight-5;
                break;
        }

        $cmd = $this->_IM_path."convert -draw \"image over  $startwidth,$startheight 0,0 '$wm_file'\" \"$src_file\" \"$dest_file\"";
        $output = $retval = null;
        exec($cmd, $output, $retval);
        
        if($retval) {
            return false;
        } else {
            return true;
        }
    }
}
?>
