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
    'gd1x',
    'gd2x',
    'imagemagick',
    'mime',
    'music',
    'netpbm',
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
    function embed() {
        $zoom = & zmgFactory::getZoom();
        
        $imagetools_loaded = false;
        foreach ($GLOBALS['_ZMG_TOOLBOX_TOOLS'] as $tool) {
            if (in_array($tool, $GLOBALS['_ZMG_TOOLBOX_IMAGETOOLS'])) {
                if (!$imagetools_loaded) {
                    $imagetools_loaded = true;
                    $imagetool = intval($zoom->getConfig('plugins/toolbox/general/conversiontool'));
                    require_once(ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.'toolbox'
                     .DS.'tools'.DS.$GLOBALS['_ZMG_TOOLBOX_IMAGETOOLS'][$imagetool - 1].'.tool.php');
                }
            } else {
                require_once(ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.'toolbox'
                 .DS.'tools'.DS.$tool.'.tool.php');
            }
        }
    }
    
    function autoDetect($selection = 'all') {
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
            eval('zmg'.ucfirst($imagetool).'Tool::autoDetect();');
        }
        
        //auto-detect other tools as well
        foreach ($selection as $tool) {
            if (!in_array($tool, $GLOBALS['_ZMG_TOOLBOX_IMAGETOOLS'])) {
                eval('zmg'.ucfirst($tool).'Tool::autoDetect();');
            } else if (!$getall) {
                if ($tool != $imagetool) {
                    require_once(ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.'toolbox'
                      .DS.'tools'.DS.$tool.'.tool.php');
                }
                eval('zmg'.ucfirst($tool).'Tool::autoDetect();');
            }
        }
        
        zmgToolboxPlugin::throwErrors();
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
