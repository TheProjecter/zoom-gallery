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
}
?>
