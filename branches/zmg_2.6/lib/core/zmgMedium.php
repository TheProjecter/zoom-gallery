<?php
/**
 * zOOm Media Gallery! - a multi-gallery component 
 * 
 * @package zmg
 * @subpackage core
 * @version $Revision$
 * @author Mike de Boer <mike AT zoomfactory.org>
 * @copyright Copyright &copy; 2007, Mike de Boer. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */

defined('_ZMG_EXEC') or die('Restricted access');

define('ZMG_MEDIUM_ORIGINAL',  0x0001);
define('ZMG_MEDIUM_VIEWSIZE',  0x0002);
define('ZMG_MEDIUM_THUMBNAIL', 0x0004);

class zmgMedium extends zmgTable {
    var $mid = null;
    
    var $name = null;
    
    var $filename = null;
    
    var $descr = null;
    
    var $keywords = null;
    
    var $_keywords = null;
    
    var $date_add = null;
    
    var $hits = null;
    
    var $votenum = null;
    
    var $votesum = null;
    
    var $published = null;
    
    var $gid = null;
    
    var $_gallery_dir = null;
    
    var $_mime_type = null;
    
    var $uid = null;
    
    var $members = null;
    
    var $_members = null; 
    
    function zmgMedium(&$db) {
        $this->zmgTable('#__zmg_media', 'mid', $db);
    }
    
    function getAbsPath($type = ZMG_MEDIUM_ORIGINAL, $mediapath = '') {
        if (!$this->_gallery_dir || !$this->filename) {
            zmgError::throwError('zmgMedium: medium data not loaded yet;'.$this->dir.' '.$this->filename);
        }
        
        $path = zmgEnv::getRootPath() . DS;
        
        if (empty($mediapath)) {
            $zoom = & zmgFactory::getZoom();
            $mediapath = $zoom->getConfig('filesystem/mediapath');
        }
        $path .= $mediapath . $this->getGalleryDir() . DS;
        
        if ($type & ZMG_MEDIUM_ORIGINAL) {
            $path .= "";
        } else if ($type & ZMG_MEDIUM_VIEWSIZE) {
            $path .= "viewsize" . DS;
        } else if ($type & ZMG_MEDIUM_THUMBNAIL) {
            $path .= "thumbs" . DS;
        }
        
        return $path . $this->filename;
    }
    
    function getRelPath($type = ZMG_MEDIUM_THUMBNAIL, $mediapath = '', $smallthumb = true) {
        if (!$this->_gallery_dir || !$this->filename) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }
        
        $zoom = & zmgFactory::getZoom();
        
        if (empty($mediapath)) {
            $mediapath = $zoom->getConfig('filesystem/mediapath');
        }
        
        //TODO: add hotlinking protection
        //'DS' constant is not used here, because this is an URL --> '/'
        $path = zmgEnv::getSiteURL() . "/" . $mediapath
         . $this->getGalleryDir() . "/";
         
        $filename = $this->filename;
        
        if ($type & ZMG_MEDIUM_ORIGINAL) {
        	$path .= "";
        } else if ($type & ZMG_MEDIUM_VIEWSIZE) {
        	$path .= "viewsize";
        } else if ($type & ZMG_MEDIUM_THUMBNAIL) {
        	$path .= "thumbs";
            
            $template_path = zmgEnv::getSiteURL() . "/components/com_zoom/var/www/templates/"
              . $zoom->view->getActiveTemplate() . "/images/mimetypes";
            if ($smallthumb) {
            	$template_path .= "/small";
            }
              
            $ext = $this->getExtension();
            zmgimport('org.zoomfactory.lib.mime.zmgMimeHelper');
            if (!zmgMimeHelper::isImage($ext)) {
            	if (zmgMimeHelper::isDocument($ext)) {
            		$path = $template_path;
                    if (strstr($ext, 'pdf')) {
                        $filename = "pdf.png";
            		} else {
            			$filename = "doc.png";
            		}
            	} else if (zmgMimeHelper::isMovie($ext)) {
            		if (zmgMimeHelper::isThumbnailable($ext)) {
            			$filename = ereg_replace("(.*)\.([^\.]*)$", "\\1", $this->filename).".jpg";
                        //TODO: improve error-checking
            		} else {
            			$path = $template_path;
                        $filename = (strstr('flv', $ext)) ? "flv.png" : "video.png";
            		}
            	} else if (zmgMimeHelper::isAudio($ext)) {
            		$path = $template_path;
                    $filename = "audio.png";
            	}
            }
        }
        
        return $path . "/" . $filename;
    }
    
    function getGalleryDir() {
        if (empty($this->_gallery_dir)) {
            $this->setGalleryDir();
        }
        return $this->_gallery_dir;
    }
    
    function setGalleryDir($dir = null) {
        if ($dir === null) {
    		$db = & zmgDatabase::getDBO();
            $db->setQuery("SELECT dir FROM #__zmg_galleries WHERE gid=".$this->gid);
            if ($db->query()) {
                $dir = trim($db->loadResult());
            }
    	}
        
        $this->_gallery_dir = $dir;
    }
    
    function getExtension() {
    	if (!$this->filename) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }
        
        $dot = strrpos($this->filename, '.') + 1;
        return substr($this->filename, $dot);
    }
    
    function getMimeType() {
        if (empty($this->_mime_type)) {
        	$this->setMimeType();
        }
        
        return $this->_mime_type;
    }
    
    function setMimeType($mime = null) {
    	if (!$this->filename) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }
        
        if ($mime === null) {
    		$path = $this->getAbsPath();
            
            zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');
            zmgimport('org.zoomfactory.lib.mime.zmgMimeHelper');

            $mime = zmgMimeHelper::getMime($path, null, $this->getExtension());
    	}
        
        $this->_mime_type = $mime;
    }
    
    function toJSON() {
        $json = new zmgJSON();
        return ("'medium': {
            'mid'      : $this->mid,
            'name'     : ".$json->encode($this->name).",
            'filename' : ".$json->encode($this->filename).",
            'descr'    : ".$json->encode($this->descr).",
            'keywords' : ".$json->encode($this->keywords).",
            'date_add' : ".$json->encode($this->date_add).",
            'url'      : ".$json->encode($this->getRelPath(ZMG_MEDIUM_THUMBNAIL, '', false)).",
            'hits'     : $this->hits,
            'votenum'  : $this->votenum,
            'votesum'  : $this->votesum,
            'published': $this->published,
            'gid'      : $this->gid,
            'uid'      : $this->uid,
            'members'  : ".$json->encode($this->members)."
        }");
    }
}
?>
