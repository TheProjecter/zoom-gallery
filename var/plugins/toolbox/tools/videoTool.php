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
        zmgimport('org/.zoomfactory.lib.helpers.zmgFileHelper');
        
        //$temp_dir = zmgEnv::getTempDir() . DS.substr(uniqid("zoom_"), 0, 13); //support filesystems which only support 14 char dirnames
        //if (zmgFileHelper::createDir($temp_dir, 0777)) {
            $thumb_file = ereg_replace("(.*)\.([^\.]*)$", "\\1", $medium->filename).".jpg";
            $thumb_path = str_replace($medium->filename, $thumb_file, $medium->getAbsPath());//$temp_dir.DS.$thumb_file;

            $cmd = zmgVideoTool::getPath() . "ffmpeg -an -y -t 0:0:0.001 -i \""
              . $medium->getAbsPath() . "\" -f mjpeg \"" . $thumb_path . "\"";
            $output = $retval = null;
            exec($cmd, $output, $retval);

            if ($retval || !zmgFileHelper::exists($thumb_path)) {
                return zmgToolboxPlugin::registerError($medium->filename, 'FFMpeg: Could not create thumbnail: ' . $output);
            }
            
            $thumb_obj = new zmgMedium(zmgDatabase::getDBO()); //temp obj
            $thumb_obj->filename = $thumb_file;
            $thumb_obj->setGalleryDir($medium->getGalleryDir());
            
            $ret = true;
            zmgimport('org.zoomfactory.var.plugins.toolbox.tools.imageTool');
            if (!zmgImageTool::process($thumb_obj, $gallery)) {
                $ret = false;
            }
            
            //clean up!
            zmgFileHelper::delete($thumb_path);
            
            return $ret;
        //} else {
        //    return zmgToolboxPlugin::registerError($medium->filename, 'FFmpeg: Could not create temporary directory.');
        //}
    }
    
    function getPath() {
         $config = & zmgFactory::getConfig();
         
         $path     = trim($config->get('plugins/toolbox/ffmpeg/path'));
         $override = intval($config->get('plugins/toolbox/ffmpeg/override'));
         
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
