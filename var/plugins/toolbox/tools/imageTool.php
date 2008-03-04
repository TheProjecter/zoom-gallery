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

class zmgImageTool {
    function process(&$medium, &$gallery) {
    	$ok = true; //innocent, until proven guilty ;)
        
        $zoom      = & zmgFactory::getZoom();
        $toolkey   = intval($zoom->getConfig('plugins/toolbox/general/conversiontool'));
        $imagetool = $GLOBALS['_ZMG_TOOLBOX_IMAGETOOLS'][$toolkey - 1];
        
        zmgimport('org.zoomfactory.var.plugins.toolbox.tools.'.$imagetool.'Tool');
        $klass = 'zmg' . ucfirst($imagetool) . 'Tool';
        
        $file = $medium->getAbsPath();
        $size = getimagesize($file);
        
        $metadata = $zoom->fireEvent('ongetimagemetadata', false, $medium);
        
        //rotate image
        //TODO
        
        //resize to thumbnail
        if ($ok && !file_exists($medium->getAbsPath(ZMG_MEDIUM_THUMBNAIL))) {
            $ok = call_user_func_array(array($klass, 'resize'), array($file, 
              $medium->getAbsPath(ZMG_MEDIUM_THUMBNAIL),
              intval($zoom->getConfig('plugins/toolbox/general/imagesizethumbnail'))));
        }
        
        //resize to viewsize format
        $maxSize = intval($zoom->getConfig('plugins/toolbox/general/imagesizemax'));
        if ($ok && !file_exists($medium->getAbsPath(ZMG_MEDIUM_VIEWSIZE))
          && ($size[0] > $maxSize || $size[1] > $maxSize)) {
        	$ok = call_user_func_array(array($klass, 'resize'), array($file, 
              $medium->getAbsPath(ZMG_MEDIUM_VIEWSIZE),
              intval($zoom->getConfig('plugins/toolbox/general/imagesizethumbnail'))));
        }
        
        //apply watermarks
        //TODO
        
        return $ok;
    }
}
?>
