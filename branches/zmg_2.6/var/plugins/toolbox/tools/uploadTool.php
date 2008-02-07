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
        
        zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');
        zmgimport('org.zoomfactory.lib.mime.zmgMimeHelper');
        
        if ($method == "jupload") {
            echo "JUpload!!!";
        } else {
            $file = zmgGetParam($_FILES, 'Filedata');
            
            print_r($file);
            $filename = $zoom->checkDuplicate(zmgFileHelper::makeSafe(urldecode($file['name'])));
            $ext      = zmgFileHelper::getExt($filename);
            $mime     = zmgMimeHelper::getMime($file['tmp_name'], $file['type'], $ext);
            
            if (zmgFileHelper::tooBig($file['tmp_name'])) {
                header('HTTP/1.0 415 Unsupported Media Type');
                die('Error. Unsupported Media Type!');
            }
            
            if (!zmgMimeHelper::acceptableFormat($mime)) {
                header('HTTP/1.0 415 Unsupported Media Type');
                die('Error. Unsupported Media Type!');
            }
            
            //try to move the file to a proper location:
            $dest = "";
            if (zmgFileHelper::exists($dest)) {
                header('HTTP/1.0 409 Conflict');
                die('Error. File already exists');
            }
            
            if (!zmgFileHelper::upload($file['tmp_name'], $dest)) {
                header('HTTP/1.0 400 Bad Request');
                die('Error. Unable to upload file');
            }
        }
    }
    function autoDetect() {
        return;
    }
}
?>
