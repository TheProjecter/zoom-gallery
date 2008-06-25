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
    function upload($method) {
        $zoom = & zmgFactory::getEvents()->fire('ongetcore');
        
        zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');
        zmgimport('org.zoomfactory.lib.mime.zmgMimeHelper');
        
        if ($method == "jupload") {
            echo "JUpload!!!";
        } else {
            $file = zmgGetParam($_FILES, 'Filedata');
            
            $filename = $zoom->checkDuplicate(zmgFileHelper::makeSafe(urldecode($file['name'])));
            $ext      = zmgFileHelper::getExt($filename);
            $mime     = zmgMimeHelper::getMime($file['tmp_name'], $file['type'], $ext);
            
            if (zmgFileHelper::tooBig($file['tmp_name'])) {
                header('HTTP/1.0 415 Unsupported Media Type');
                die('Error. File too big!');
            }
            
            if (!zmgMimeHelper::acceptableFormat($mime, true)) {
                header('HTTP/1.0 415 Unsupported Media Type');
                die('Error. Unsupported Media Type!');
            }
            
            //try to move the file to a proper location:
            $dest = ZMG_ABS_PATH . DS."etc".DS."cache".DS.$filename;
            if (zmgFileHelper::exists($dest)) {
                header('HTTP/1.0 409 Conflict');
                die('Error. File already exists');
            }
            
            if (!zmgFileHelper::upload($file['tmp_name'], $dest)) {
                header('HTTP/1.0 400 Bad Request');
                die('Error. Unable to upload file');
            }
            
            // store the filename into the session (the data is sent to the backend
            // after the file has been uploaded).
            $session = & zmgFactory::getSession();
            $session->update('uploadtool.fancyfiles', $filename, ZMG_DATATYPE_ARRAY);
            
            $session->store();
        }
    }
    
    function finalizeUpload($gid = 0) {
        //finish the SwfUpload sequence...
        if ($gid === 0) {
        	return zmgToolboxPlugin::registerError(T_('Upload media'), T_('No valid gallery ID provided'));
        }

        $session = & zmgFactory::getSession();
        $events  = & zmgFactory::getEvents();
        $config  = & zmgFactory::getConfig();
        $db      = & zmgDatabase::getDBO();
        
        $gallery = new zmgGallery($db);
        $gallery->load($gid);
        
        //now we got the gallery and its data, retrieve the uploaded media
        $media = $session->get('uploadtool.fancyfiles');
        if (!is_array($media) || count($media) == 0) {
            return zmgToolboxPlugin::registerError(T_('Upload media'), T_('No media have been uploaded; nothing to do.'));
        }

        zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');
        $src_path  = ZMG_ABS_PATH . DS."etc".DS."cache".DS;
        $dest_path = zmgEnv::getRootPath() .DS.$config->get('filesystem/mediapath').$gallery->dir.DS;
        
        foreach ($media as $medium) {
        	$obj   = new zmgMedium($db);

            $name  = zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_upload_name', ''));
            $descr = zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_upload_descr', ''));
            $data  = array(
              'name'      => $name,
              'filename'  => $medium,
              'descr'     => $descr,
              'published' => 1,
              'gid'       => $gallery->gid
            );

            $obj->setGalleryDir($gallery->dir); //saves a SQL query later on...
            //do some additional validation of strings
            $data['name']  = $events->fire('onvalidate', $data['name']);
            if (!$data['name']) {
                $data['name'] = $name;
            }
            $data['descr'] = $events->fire('onvalidate', $data['descr']);
            if (!$data['descr']) {
                $data['descr'] = $descr;
            }
            
            if (!$obj->bind($data)) {
                zmgToolboxPlugin::registerError(T_('Upload media'), T_('Medium could not be saved') . ': ' . $obj->getError());
            } else if (!zmgFileHelper::copy($src_path . $medium, $dest_path . $medium)) {
        		zmgToolboxPlugin::registerError(T_('Upload media'), T_('Unable to copy file') . ' ' . $medium);
        	} else if (!zmgFileHelper::delete($src_path . $medium)) {
        		zmgToolboxPlugin::registerError(T_('Upload media'), T_('Unable to delete temporary file') . ' ' . $medium);
        	} else if (!zmgToolboxPlugin::processMedium($obj, $gallery)) {
        		zmgToolboxPlugin::registerError(T_('Upload media'), T_('Medium could not be processed') . ' ' . $medium);
        	} else if (!$obj->store()) { //now save this medium in our DB
        		zmgToolboxPlugin::registerError(T_('Upload media'), T_('Medium could not be saved') . ': ' . $obj->getError());
        	}
            //delete medium from session data: fourth parameter as TRUE 
            $session->update('uploadtool.fancyfiles', $medium, ZMG_DATATYPE_ARRAY, true);
        }
        
        zmgToolboxPlugin::throwErrors();
    }
    
    function autoDetect() {
        return;
    }
}
?>
