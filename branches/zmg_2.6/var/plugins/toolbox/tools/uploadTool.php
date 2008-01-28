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

class zmgUploadTool {
    function upload($args) {
        $zoom = & zmgFactory::getZoom();
        $method = stristr($zoom->view->get(), 'jupload') ? "jupload" : "swf";
        
        if ($method == "jupload") {
            echo "JUpload!!!";
        } else {
            $files  = zmgGetParam($_FILES, 'Filedata');
            header('HTTP/1.0 415 Unsupported Media Type');
        }
    }
}
?>
