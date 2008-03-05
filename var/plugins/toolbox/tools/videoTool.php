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
    function process(&$medium, &$gallery) {//$file, $size, $filename) {
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
    
    function getPath() {
         $zoom = & zmgFactory::getZoom();
         
         $path     = trim($zoom->getConfig('plugins/toolbox/ffmpeg/path'));
         $override = intval($zoom->getConfig('plugins/toolbox/ffmpeg/override'));
         
         if ($path == "auto") {
            $path = zmgVideoTool::detectPath();
         }
         
         return $path;
    }
    
    function detectPath() {
        $path = "";
        if (file_exists('/usr/bin/ffmpeg') && is_executable('/usr/bin/ffmpeg')) {
            $path = "/usr/bin/"; //Debian systems
        }
        return $path;
    }
    
    /**
     * Detect if FFmpeg is available on the system.
     *
     * @return void
     */
    function autoDetect() {
        static $output, $status;
        //get the absolute location first:
        $path = zmgVideoTool::detectPath();
        //execute test command
        @exec($path . 'ffmpeg',  $output, $status);
        
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
