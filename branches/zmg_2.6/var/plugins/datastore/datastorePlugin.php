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
 * The zmgDataStorePlugin class handles all the view requests that need specific
 * action. It delegates each view request to an appropriate store
 * ('save gallery' -> 'galleryStore', 'upload' -> 'toolbox plugin', etc.).
 */
class zmgDatastorePlugin {
    function bindEvents() {
        return array(
            "onisdatastore" => array(
                "isDataStore" => array('view')
            ),
            "ondatastore" => array(
                "storeDelegate" => array('view')
            )
        );
    }
    
    function isDataStore(&$event) {
    	$aView = $event->getArgument('view');
        
        //check for dispatches that 'put' data (in contrary to 'get' requests)
        if (in_array('store', $aView) || in_array('update', $aView)
          || in_array('autodetect', $aView)) {
          	return 1;
        }
        return 0;
    }
    
    function storeDelegate(&$event) {
    	$aView = $event->getArgument('view');
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
            case "admin:galleryedit:delete":
                zmgimport('org.zoomfactory.var.plugins.datastore.stores.galleryStore');
                
                zmgGalleryStore::delete($zoom);
                break;
            case "admin:mediumedit:store":
                zmgimport('org.zoomfactory.var.plugins.datastore.stores.mediumStore');
                
                zmgMediumStore::process($zoom);
                break;
            case stristr($view, "admin:mediaupload:store"):
                //SWFUpload needs HTTP headers to signal the user...
                $method = stristr($view, "jupload") ? "jupload" : "swfupload";
                $zoom->fireEvent('onupload', false, $method);
                //exit;
                break;
            case stristr($view, "admin:mediaupload:update"):
                $gid = array_pop($aView);
                $zoom->fireEvent('onuploadupdate', false, $gid);
                break;
            case stristr($view, "admin:update:mediacount"):
                $gid = intval(array_pop($aView));
                echo $zoom->getMediumCount($gid);
                break;
            default:
                break;
        }
    }
}
?>
