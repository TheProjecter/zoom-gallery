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

class zmgVideoTool {
    /**
     * Generate a thumbnail from a video stream using the FFMpeg library.
     *
     * @param string $file
     * @param string $size
     * @param string $filename
     * @return boolean
     */
    function createThumbnail($file, $size, $filename) {
        global $mosConfig_absolute_path, $zoom;
        if ($this->_FFMPEG_path == 'auto') {
            $this->_FFMPEG_path = '';
        } else {
            if (!empty($this->_FFMPEG_path) && !$zoom->_CONFIG['override_FFMPEG']) {
                if (!$zoom->platform->is_dir($this->_FFMPEG_path)) {
                    return zmgToolboxPlugin::registerError($file, 'FFMpeg: Your FFMpeg path is not correct! Please (re)specify it in the Admin-system under \'Settings\'');
                }
            }
        }
        $desfile = ereg_replace("(.*)\.([^\.]*)$", "\\1", $filename).".jpg";
        if ($tempdir = $zoom->createTempDir()) {
            $gen_path = $mosConfig_absolute_path."/".$tempdir;
            $cmd = $this->_FFMPEG_path."ffmpeg -an -y -t 0:0:0.001 -i \"$file\" -f mjpeg \"$gen_path/file.jpg\"";
            $output = $retval = null;
            exec($cmd, $output, $retval);
            if ($retval || !$zoom->platform->file_exists($gen_path."/file.jpg")) {
                return zmgToolboxPlugin::registerError($file, 'FFMpeg: Could not create thumbnail: ' . $output);
            }
            $the_thumb = $gen_path."/file.jpg";
            $imgobj = new image(0);
            $imgobj->_filename = $desfile;
            $imgobj->_type = "jpg";
            $imgobj->_size = $zoom->platform->getimagesize($the_thumb);
            $target = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->getDir()."/thumbs/".$desfile;
            if (!$this->resizeImage($the_thumb, $target, $size, $imgobj)) {
                return false;
            } else {
                @$zoom->deldir($gen_path);
                return true;
            }
        } else {
            return zmgToolboxPlugin::registerError($file, 'FFmpeg: Could not create temporary directory.');
        }
    }
    /**
     * Detect if FFmpeg is available on the system.
     *
     * @return void
     */
    function autoDetect() {
        static $output, $status;
        exec('ffmpeg', $output, $status);
        
        $res = false;
        if (!empty($output[0])) {
            if (preg_match("/(ffmpeg).*/i",$output[0],$matches)) {
                zmgToolboxPlugin::registerError('FFmpeg', 'FFmpeg ' . T_('is available.'));
                $res = true;
            }
        }
        if (!$res) {
            zmgToolboxPlugin::registerError('FFmpeg', T_('could not be detected on your system.'));
        }
        unset($output, $status);
    }
}
?>
