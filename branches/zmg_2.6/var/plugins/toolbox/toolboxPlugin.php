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


$GLOBALS['_ZMG_TOOLBOX_TOOLS'] = array(
    'document',
    'image',
    'gd1x',
    'gd2x',
    'imagemagick',
    'mime',
    'audio',
    'netpbm',
    'upload',
    'video',
    'watermark'
);

$GLOBALS['_ZMG_TOOLBOX_IMAGETOOLS'] = array(
    'imagemagick',
    'netpbm',
    'gd1x',
    'gd2x'
);

class zmgToolboxPlugin extends zmgError {
    function bindEvents() {
        return array(
            "onstartup" => array(
                "embed" => array()
            ),
            "onupload"   => array(
                "upload" => array('method')
            ),
            "onuploadupdate" => array(
                "finalizeUpload" => array('gid')
            ),
            "onautodetect" => array(
                "autoDetect" => array('selection')
            )
            //more Events to come...
        );
    }
    
    function embed() {
        $zoom = & zmgFactory::getZoom();
        /*$imagetools_loaded = false;
        foreach ($GLOBALS['_ZMG_TOOLBOX_TOOLS'] as $tool) {
            if (in_array($tool, $GLOBALS['_ZMG_TOOLBOX_IMAGETOOLS'])) {
                if (!$imagetools_loaded) {
                    $imagetools_loaded = true;
                    $imagetool = intval($zoom->getConfig('plugins/toolbox/general/conversiontool'));
                    zmgimport('org.zoomfactory.var.plugins.toolbox.tools.'
                     . $GLOBALS['_ZMG_TOOLBOX_IMAGETOOLS'][$imagetool - 1] . 'Tool');
                }
            } else {
                zmgimport('org.zoomfactory.var.plugins.toolbox.tools.'
                 . $tool . 'Tool');
            }
        }
        */
        $settings_file = ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.'toolbox'.DS.'settings.xml';
        if (file_exists($settings_file)) {
            $plugin = & $zoom->plugins->get('toolbox');
            $zoom->plugins->embedSettings(&$plugin, $settings_file);
        }
    }
    
    function upload(&$event) {
        $method = $event->getArgument('method');
        
        zmgimport('org.zoomfactory.var.plugins.toolbox.tools.uploadTool');
        return zmgUploadTool::upload($method);
    }
    
    function finalizeUpload(&$event) {
    	$gid = intval($event->getArgument('gid'));
        
        zmgimport('org.zoomfactory.var.plugins.toolbox.tools.uploadTool');
        return zmgUploadTool::finalizeUpload($gid);
    }
    
    function autoDetect(&$event) {
        $selection = $event->getArgument('selection');
        if (!is_array($selection)) {
        	$selection = "all";
        }
        
        $getall  = false;
        if (!is_array($selection) && $selection == "all") {
            $getall = true;
            $selection = $GLOBALS['_ZMG_TOOLBOX_TOOLS'];
        }
        $zoom = & zmgFactory::getZoom();

        $toolkey   = intval($zoom->getConfig('plugins/toolbox/general/conversiontool'));
        $imagetool = $GLOBALS['_ZMG_TOOLBOX_IMAGETOOLS'][$toolkey - 1];
        if ($getall) {
            //auto-detect currently selected imagetool first
            zmgimport('org.zoomfactory.var.plugins.toolbox.tools.'.$imagetool.'Tool');
            zmgCallAbstract('zmg'.ucfirst($imagetool).'Tool', 'autoDetect');
        }
        
        //auto-detect other tools as well
        foreach ($selection as $tool) {
            if (!in_array($tool, $GLOBALS['_ZMG_TOOLBOX_IMAGETOOLS'])) {
                zmgimport('org.zoomfactory.var.plugins.toolbox.tools.'.$tool.'Tool');
                zmgCallAbstract('zmg'.ucfirst($tool).'Tool', 'autoDetect');
            } else if (!$getall) {
                if ($tool != $imagetool) {
                    zmgimport('org.zoomfactory.var.plugins.toolbox.tools.'.$tool.'Tool');
                }
                zmgCallAbstract('zmg'.ucfirst($tool).'Tool', 'autoDetect');
            }
        }
        
        zmgToolboxPlugin::throwErrors();
    }
    
    function processMedium(&$medium, &$gallery) {
    	$mime = $medium->getMimeType();
        
        zmgimport('org.zoomfactory.lib.mime.zmgMimeHelper');
        
        $ok = true;
        
        if (zmgMimeHelper::isImage($mime, true)) {
        	zmgimport('org.zoomfactory.var.plugins.toolbox.tools.imageTool');

            $ok = zmgImageTool::process($medium, $gallery);
            if (!$ok) {
                zmgToolboxPlugin::registerError(T_('Upload medium'), T_('Could not create thumbnail of image file'));
                //TODO: cleanup
            }
        } else if (zmgMimeHelper::isDocument($mime, true)
          && zmgMimeHelper::isIndexable($mime, true)) {
        	zmgimport('org.zoomfactory.var.plugins.toolbox.tools.documentTool');
            
            $ok = zmgDocumentTool::process($medium, $gallery);
            if (!$ok) {
                zmgToolboxPlugin::registerError(T_('Upload medium'), T_('Could not index document'));
                //TODO: cleanup
            }
        } else if (zmgMimeHelper::isVideo($mime, true)
          && zmgMimeHelper::isThumbnailable($mime, true)) {
        	zmgimport('org.zoomfactory.var.plugins.toolbox.tools.videoTool');
            
            $ok = zmgVideoTool::process($medium, $gallery);
            if (!$ok) {
                zmgToolboxPlugin::registerError(T_('Upload medium'), T_('Could not create thumbnail of video file'));
                //TODO: cleanup
            }
        } else if (zmgMimeHelper::isAudio($mime, true)) {
        	zmgimport('org.zoomfactory.var.plugins.toolbox.tools.audioTool');
            
            $ok = zmgAudioTool::process($medium, $gallery);
            if (!$ok) {
            	zmgToolboxPlugin::registerError(T_('Upload medium'), T_('Audio file not supported'));
                //TODO: cleanup
            }
        } else {
        	zmgToolboxPlugin::registerError(T_('Upload medium'), T_('Unsupported medium type.'));
        }
        
        return $ok;
    }
    
    function &getErrors() {
        static $zmgToolboxErrors;
        
        if (!is_array($zmgToolboxErrors)) {
            $zmgToolboxErrors = array();
        }
        
        return $zmgToolboxErrors;
    }
    
    function throwErrors() {
        $errors = & zmgToolboxPlugin::getErrors();
        
        $zoom = & zmgFactory::getZoom();
        
        for ($i = 0; $i < count($errors); $i++) {
            $zoom->messages->append($errors[$i]['title'],
              $errors[$i]['description']);
        }
        //reset the process of collecting of errors
        $errors = null;
    }
    
    function registerError($title, $descr) {
        $errors = & zmgToolboxPlugin::getErrors();
        
        $i = count($errors);
        $errors[$i]['title']       = $title;
        $errors[$i]['description'] = $descr;
        
        //return 'FALSE' to the callee, because it's an error after all
        return false;
    }
}
?>
