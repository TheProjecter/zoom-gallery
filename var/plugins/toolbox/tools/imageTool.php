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
    function process() {
    	$zoom      = & zmgFactory::getZoom();
        $imagetool = intval($zoom->getConfig('plugins/toolbox/general/conversiontool'));
        $tool      = $GLOBALS['_ZMG_TOOLBOX_IMAGETOOLS'][$imagetool - 1];
        zmgimport('org.zoomfactory.var.plugins.toolbox.tools.' . $tool . 'Tool');
    }
}
?>
