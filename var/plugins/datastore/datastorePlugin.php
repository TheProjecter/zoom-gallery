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

class zmgDatastorePlugin {
    function bindEvents() {
        return array(
            "onisdatastore"   => array(
                "isDataStore" => array('view')
            ),
            "ondatastore"     => array(
                "storeDelegate" => array('view')
            )
        );
    }
    
    function isDataStore($args) {
    	$aView = $args[0];

        //check for dispatches that 'put' data (in contrary to 'get' requests)
        if (in_array('store', $aView) || in_array('update', $aView)
          || in_array('autodetect', $aView)) {
          	return 1;
        }
        return 0;
    }
    
    function storeDelegate($args) {
    	$aView = $args[0];
        $view  = implode(':', $aView);
        
        $zoom = & zmgFactory::getZoom();
        
        switch ($view) {
            case "admin:settings:store":
                $zoom->setResult($zoom->updateConfig($_POST));
                break;
            case stristr($view, "admin:settings:plugins:autodetect"):
                $tool = trim($aView[count($aView) - 1]);
                if ($tool == "autodetect") {
                    $tool = "all";
                } else {
                    $tool = array($tool);
                }
                $zoom->fireEvent('onautodetect', false, $tool);
                break;
            case stristr($view, "admin:update:mediacount"):
                $filter = intval(array_pop($aView));
                $zoom->setResult($zoom->getMediumCount($filter));
                break;
            case "admin:galleryedit:store":
                zmgimport('org.zoomfactory.var.plugins.datastore.stores.galleryStore');
                
                zmgGalleryStore::process($zoom);
                break;
            case "admin:mediumedit:store":
                break;
            case stristr($view, "admin:mediaupload:store"):
                //SWFUpload needs HTTP headers to signal the user...
                $method = stristr($view, "jupload") ? "jupload" : "swfupload";
                $zoom->fireEvent('onupload', false, $method);
                //exit;
                break;
            default:
                break;
        }
    }
}
?>
