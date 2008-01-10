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

class zmgGd2xTool {
    /**
     * Resize an image to a prefered size using the GD2 library.
     *
     * @param string $src_file
     * @param string $dest_file
     * @param int $new_size
     * @param image $imgobj
     * @return boolean
     */
    function resize($src_file, $dest_file, $new_size, $imgobj) {
        if ($imgobj->_size == null) {
            return false;
        }
        if (!zmgGd2xTool::isSupportedType($imgobj->_type, $src_file)) {
            return false;
        }
        
        // height/width
        $ratio = max($imgobj->_size[0], $imgobj->_size[1]) / $new_size;
        $ratio = max($ratio, 1.0);
        $destWidth = (int)($imgobj->_size[0] / $ratio);
        $destHeight = (int)($imgobj->_size[1] / $ratio);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            $src_img = @imagecreatefromjpeg($src_file);
            $dst_img = imagecreatetruecolor($destWidth, $destHeight);
        } else if ($imgobj->_type == "png") {
            $src_img = @imagecreatefrompng($src_file);
            $dst_img = imagecreatetruecolor($destWidth, $destHeight);
            $img_white = imagecolorallocate($dst_img, 255, 255, 255); // set background to white
            $img_return = @imagefill($dst_img, 0, 0, $img_white);
        } else {
            $src_img = @imagecreatefromgif($src_file);
            $dst_img = imagecreatetruecolor($destWidth,$destHeight);
            $img_white = imagecolorallocate($dst_img, 255, 255, 255); // set background to white
            $img_return = @imagefill($dst_img, 0, 0, $img_white);
        }
        if (!$src_img) {
            return zmgToolboxPlugin::registerError($src_file, 'GD 2.x: Could not convert image.');
        }
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $destWidth, $destHeight, $imgobj->_size[0], $imgobj->_size[1]);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            imagejpeg($dst_img, $dest_file, $this->_JPEG_quality);
        } else if ($imgobj->_type == "png") {
            imagepng($dst_img, $dest_file);
        } else {
            imagegif($dst_img, $dest_file);
        }
        imagedestroy($src_img);
        imagedestroy($dst_img);
        return true;
    }
    /**
     * Rotate an image with the prefered number of degrees using the GD2 library.
     *
     * @param string $src_file
     * @param string $dest_file
     * @param int $degrees
     * @param image $imgobj
     * @return boolean
     */
    function rotate($src_file, $dest_file, $degrees, $imgobj) {
        if (!zmgGd2xTool::isSupportedType($imgobj->_type, $src_file)) {
            return false;
        }
        
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            $src_img = @imagecreatefromjpeg($src_file);
        } else if ($imgobj->_type == "png") {
            $src_img = @imagecreatefrompng($src_file);
            $dst_img = imagecreatetruecolor($imgobj->_size[0],$imgobj->_size[1]);
            $img_white = imagecolorallocate($dst_img, 255, 255, 255); // set background to white
            $img_return = imagefill($dst_img, 0, 0, $img_white);
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $imgobj->_size[0], $imgobj->_size[1], $imgobj->_size[0], $imgobj->_size[1]);
            $src_img = $dst_img;
        } else {
            $src_img = @imagecreatefromgif($src_file);
            $dst_img = imagecreatetruecolor($imgobj->_size[0],$imgobj->_size[1]);
            $img_white = imagecolorallocate($dst_img, 255, 255, 255); // set background to white
            $img_return = imagefill($dst_img, 0, 0, $img_white);
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $imgobj->_size[0], $imgobj->_size[1], $imgobj->_size[0], $imgobj->_size[1]);
            $src_img = $dst_img;
        }
        if (!$src_img) {
            return zmgToolboxPlugin::registerError($src_file, 'GD 2.x: Could not rotate image.');
        }
        // The rotation routine...
        $dst_img = imagerotate($src_img, $degrees, 0);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            imagejpeg($dst_img, $dest_file, $this->_JPEG_quality);
        } else if ($imgobj->_type == "png") {
            imagepng($dst_img, $dest_file);
        } else {
            imagegif($dst_img, $dest_file);
        }
        imagedestroy($src_img);
        imagedestroy($dst_img);
        return true;
    }
    function isSupportedType($type, $src_file) {
        // GD can only handle JPG, PNG & GIF images
        if ($type !== "jpg" && $type !== "jpeg" && $type !== "png" && $type !== "gif") {
            return zmgToolboxPlugin::registerError($src_file, 'GD 2.x: Source file is not an image or image type is not supported.');
        }
        if ($type == "gif" && !function_exists("imagecreatefromgif")) {
            return zmgToolboxPlugin::registerError($src_file, 'GD 2.x: Not able to convert *.gif images at this time. Please recompile PHP and GD with *.gif support.');
        }
        return true;
    }
    /**
     * Detect if GD is available on the system.
     *
     * @return void
     */
    function autoDetect() {
        $GDfuncList = get_extension_funcs('gd');
        ob_start();
        @phpinfo(INFO_MODULES);
        $output = ob_get_contents();
        ob_end_clean();
        $matches[1] = '';
        if (preg_match("/GD Version[ \t]*(<[^>]+>[ \t]*)+([^<>]+)/s",$output,$matches)) {
            $gdversion = $matches[2];
        }
        $res = false;
        if ($GDfuncList) {
            if (in_array('imagegd2', $GDfuncList)) {
                zmgToolboxPlugin::registerError('GD 2.x', $gdversion . ' ' . T_('is available.'));
                $res = true;
            }
        }
        if (!$res) {
            zmgToolboxPlugin::registerError('GD 2.x', T_('could not be detected on your system.'));
        }
    }
}
?>
