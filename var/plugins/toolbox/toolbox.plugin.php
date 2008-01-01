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
    
    function throwErrors() {
        static $zmgToolboxErrors;
        
        if (!is_array($zmgToolboxErrors)) {
            return; //no errors present at all (well done!)
        }
        
        $zoom = & zmgFactory::getZoom();
        
        for ($i = 0; $i < count($zmgToolboxErrors); $i++) {
            $zoom->messages->append($zmgToolboxErrors[$i]['title'],
              $zmgToolboxErrors[$i]['description']);
        }
        //reset the process of collecting of errors
        $zmgToolboxErrors = null;
    }
    
    function registerError($title, $descr) {
        static $zmgToolboxErrors;
        
        if (!is_array($zmgToolboxErrors)) {
            $zmgToolboxErrors = array();
        }
        
        $i = count($zmgToolboxErrors);
        $zmgToolboxErrors[$i]['title']       = $title;
        $zmgToolboxErrors[$i]['description'] = $descr;
        
        //return 'FALSE' to the callee, because it's an error after all
        return false;
    }
}
?>
