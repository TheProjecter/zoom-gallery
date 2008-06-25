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
        
        $events     = & zmgFactory::getEvents();
        $config     = & zmgFactory::getConfig();
        $imagetools = & zmgToolboxConstants::getImageTools();
        $toolkey    = intval($config->get('plugins/toolbox/general/conversiontool'));
        $imagetool  = $imagetools[$toolkey - 1];
        
        zmgimport('org.zoomfactory.var.plugins.toolbox.tools.'.$imagetool.'Tool');
        $klass = 'zmg' . ucfirst($imagetool) . 'Tool';
        
        $file = $medium->getAbsPath();
        $size = getimagesize($file);
        $img_meta = array(
            'width'     => $size[0],
            'height'    => $size[1],
            'extension' => $medium->getExtension(),
            'jpeg_qty'  => $config->get('plugins/toolbox/general/jpegquality')
        );
        
        $metadata = $events->fire('ongetimagemetadata', false, $medium);
        
        //rotate image
        //TODO
        
        //resize to thumbnail
        if ($ok && !file_exists($medium->getAbsPath(ZMG_MEDIUM_THUMBNAIL))) {
            $ok = call_user_func_array(array($klass, 'resize'), array($file, 
              $medium->getAbsPath(ZMG_MEDIUM_THUMBNAIL),
              intval($config->get('plugins/toolbox/general/imagesizethumbnail')),
              $img_meta));
        }
        
        //resize to viewsize format
        $maxSize = intval($config->get('plugins/toolbox/general/imagesizemax'));
        if ($ok && !file_exists($medium->getAbsPath(ZMG_MEDIUM_VIEWSIZE))
          && ($img_meta['width'] > $maxSize || $img_meta['height'] > $maxSize)) {
        	$ok = call_user_func_array(array($klass, 'resize'), array($file, 
              $medium->getAbsPath(ZMG_MEDIUM_VIEWSIZE),
              intval($config->get('plugins/toolbox/general/imagesizethumbnail')),
              $img_meta));
        }
        
        //apply watermarks
        //TODO
        
        if ($ok) {
        	$ok = $events->fire('onputimagemetadata', false, $medium, $metadata);
        }
        
        return $ok;
    }
}
?>
