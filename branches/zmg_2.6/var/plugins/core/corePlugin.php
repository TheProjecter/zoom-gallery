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

/**
 * Load the Core Zoom assets, currently consisting of zmgComment, zngEditMonitor,
 * zmgGallery, zmgMedium
 */
class zmgCorePlugin {
    function bindEvents() {
        return array(
            "onstartup" => array(
                "embed" => array()
            )
        );
    }
    
    function embed() {
        zmgimport('org.zoomfactory.var.plugins.core.assets.*');
    }
}
?>
