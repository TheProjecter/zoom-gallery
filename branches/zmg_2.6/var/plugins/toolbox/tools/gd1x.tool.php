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

class zmgGD1Tool {
    /**
     * Resize an image to a prefered size using the GD1 library.
     *
     * @param string $src_file
     * @param string $dest_file
     * @param int $new_size
     * @param image $imgobj
     * @return boolean
     */
    function resize($src_file, $dest_file, $new_size, &$imgobj) {
        if ($imgobj->_size == null) {
            return parent::registerError($src_file, 'GD 1.x: No correct arguments supplied.');
        }
        if (!zmgGD1Tool::isSupportedType($imgobj->_type, $src_file)) {
            return false;
        }
        
        // height/width
        $ratio = max($imgobj->_size[0], $imgobj->_size[1]) / $new_size;
        $ratio = max($ratio, 1.0);
        $destWidth = (int)($imgobj->_size[0] / $ratio);
        $destHeight = (int)($imgobj->_size[1] / $ratio);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            $src_img = imagecreatefromjpeg($src_file);
        } else {
            $src_img = imagecreatefrompng($src_file);
        }
        if (!$src_img) {
            return parent::registerError($src_file, 'GD 1.x: Could not convert image.');
        }
        $dst_img = imagecreate($destWidth, $destHeight);
        imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $destWidth, (int)$destHeight, $imgobj->_size[0], $imgobj->_size[1]);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            imagejpeg($dst_img, $dest_file, $this->_JPEG_quality);
        } else {
            imagepng($dst_img, $dest_file);
        }
        imagedestroy($src_img);
        imagedestroy($dst_img);
        return true;
    }
    /**
     * Rotate an image with the prefered number of degrees using the GD1 library.
     *
     * @param string $src_file
     * @param string $dest_file
     * @param int $degrees
     * @param image $imgobj
     * @return boolean
     */
    function rotate($src_file, $dest_file, $degrees, &$imgobj) {
        if (!zmgGD1Tool::isSupportedType($imgobj->_type, $src_file)) {
            return false;
        }
        
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            $src_img = imagecreatefromjpeg($src_file);
        } else {
            $src_img = imagecreatefrompng($src_file);
        }
        if (!$src_img) {
            return parent::registerError($src_file, 'GD 1.x: Could not rotate image.');
        }
        // The rotation routine...
        $dst_img = imagerotate($src_img, $degrees, 0);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            imagejpeg($dst_img, $dest_file, $this->_JPEG_quality);
        } else {
            imagepng($dst_img, $dest_file);
        }
        imagedestroy($src_img);
        imagedestroy($dst_img);
        return true;
    }
    function isSupportedType($type, $src_file) {
        // GD1 can only handle JPG & PNG images
        if ($type !== "jpg" && $type !== "jpeg" && $type !== "png") {
            return parent::registerError($src_file, 'GD 1.x: Source file is not an image or image type is not supported.');
        }
        return true;
    }
}
?>
