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
    
    var $gallery_dir = null;
    
    var $mime_type = null;
    
    var $uid = null;
    
    var $members = null;
    
    var $_members = null; 
    
    function zmgMedium(&$db) {
        $this->zmgTable('#__zmg_media', 'mid', $db);
    }
    
    function getAbsPath($type = ZMG_MEDIUM_ORIGINAL, $mediapath = '') {
        if (!$this->gid) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }
        
        if (empty($mediapath)) {
            $zoom = & zmgFactory::getZoom();
            $mediapath = $zoom->getConfig('filesystem/mediapath');
        }
        
        if ($type & ZMG_MEDIUM_ORIGINAL) {
            $path .= "";
        } else if ($type & ZMG_MEDIUM_VIEWSIZE) {
            $path .= "viewsize";
        } else if ($type & ZMG_MEDIUM_THUMBNAIL) {
            $path .= "thumbs";
        }
        
        $path = zmgEnv::getRootPath() .DS.$mediapath;
        
        return $path.DS.$this->getGalleryDir();
    }
    
    function getRelPath($type = ZMG_MEDIUM_ORIGINAL, $mediapath = '') {
        if (!$this->gid) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }
        if (empty($mediapath)) {
            $zoom = & zmgFactory::getZoom();
            $mediapath = $zoom->getConfig('filesystem/mediapath');
        }
        
        //TODO: add hotlinking protection
        //'DS' constant is not used here, because this is an URL --> '/'
        $path = zmgEnv::getSiteURL() . "/" . $mediapath
         . $this->getGalleryDir() . "/";
         
        if ($type & ZMG_MEDIUM_ORIGINAL) {
        	$path .= "";
        } else if ($type & ZMG_MEDIUM_VIEWSIZE) {
        	$path .= "viewsize";
        } else if ($type & ZMG_MEDIUM_THUMBNAIL) {
        	$path .= "thumbs";
        }
        
        return $path . "/" . $this->filename;
    }
    
    function getGalleryDir() {
        if (!$this->gid) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }
        
        if (empty($this->gallery_dir)) {
            $this->setGalleryDir();
        }
        return $this->gallery_dir;
    }
    
    function setGalleryDir($dir = null) {
    	if (!$this->gid) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }
        
        if ($dir === null) {
    		$db = & zmgDatabase::getDBO();
            $db->setQuery("SELECT dir FROM #__zmg_galleries WHERE gid=".$this->gid);
            if ($db->query()) {
                $dir = trim($db->loadResult());
            }
    	}
        
        $this->gallery_dir = $dir;
    }
    
    function getMimeType() {
    	if (!$this->gid) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }
        
        if (empty($this->mime_type)) {
        	$this->setMimeType();
        }
        
        return $this->mime_type;
    }
    
    function setMimeType($mime = null) {
    	if (!$this->gid) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }
        
        if ($mime === null) {
    		$path = $this->getAbsPath();
            
            zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');
            zmgimport('org.zoomfactory.lib.mime.zmgMimeHelper');
            
            $ext  = zmgFileHelper::getExt($this->filename);
            $mime = zmgMimeHelper::getMime($path, null, $ext);
    	}
        
        $this->mime_type = $mime;
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
            'url'      : ".$json->encode($this->getRelPath(ZMG_MEDIUM_THUMBNAIL)).",
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
