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
    
    var $_extension = null;
    
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
    
    var $_metadata = null;
    
    function zmgMedium(&$db) {
        $this->zmgTable(zmgFactory::getConfig()->getTableName('media'), 'mid', $db);
    }
    
    function getAbsPath($type = ZMG_MEDIUM_ORIGINAL, $mediapath = '') {
        if (!$this->filename) {
            zmgError::throwError('zmgMedium: medium data not loaded yet;'.$this->_gallery_dir.' '.$this->filename);
        }
        
        $path = zmgEnv::getRootPath() . DS;
        
        if (empty($mediapath)) {
            $mediapath = zmgFactory::getConfig()->get('filesystem/mediapath');
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
        if (!$this->filename) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }
        
        if (empty($mediapath)) {
            $mediapath = zmgFactory::getConfig()->get('filesystem/mediapath');
        }
        
        //TODO: add hotlinking protection
        //'DS' constant is not used here, because this is a URL --> '/'
        $gallery_path = zmgEnv::getSiteURL() . "/" . $mediapath
         . $this->getGalleryDir() . "/";

        $file = array(
            'path' => $gallery_path,
            'name' => $this->filename
        );

        $ext = $this->getExtension();
        zmgimport('org.zoomfactory.lib.mime.zmgMimeHelper');
        if (!zmgMimeHelper::isImage($ext)) {
            $file = $this->getViewableFile($gallery_path, $smallthumb);
        }
        
        if ($type & ZMG_MEDIUM_ORIGINAL) {
        	$file['path'] = $gallery_path; //back to the original
            $file['name'] = $this->filename;
        } else if ($type & ZMG_MEDIUM_VIEWSIZE) {
        	if (zmgMimeHelper::isAudio($ext)) {
                $file['path'] = "";
                $file['name'] = $this->mid . "." . $ext;
            } else if (zmgMimeHelper::isVideo($ext)) {
                $file['path'] = $gallery_path;
                $file['name'] = $this->filename;
            } else if ($file['path'] == $gallery_path) {
                //$file['path'] .= "viewsize"; //TODO: more case coverage
            }
        } else if ($type & ZMG_MEDIUM_THUMBNAIL) {
        	if ($file['path'] == $gallery_path) {
        	    $file['path'] .= "thumbs";
        	}
        }

        return implode('/', $file);
    }
    
    function getViewableFile($gallery_path, $smallthumb = false) {
        if (!$this->filename) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }

        $file = array(
            'path' => $gallery_path,
            'name' => null
        );
        
        $template_path = zmgEnv::getSiteURL() . "/components/com_zoom/var/www/templates/"
          . zmgFactory::getView()->getActiveTemplate() . "/images/mimetypes";
        if ($smallthumb) {
            $template_path .= "/small";
        }
        
        $ext = $this->getExtension();
        zmgimport('org.zoomfactory.lib.mime.zmgMimeHelper');
        
        if (zmgMimeHelper::isDocument($ext)) {
            $file['path'] = $template_path;
            if (strstr($ext, 'pdf')) {
                $file['name'] = "pdf.png";
            } else {
                $file['name'] = "doc.png";
            }
        } else if (zmgMimeHelper::isVideo($ext)) {
            if (zmgMimeHelper::isThumbnailable($ext)) {
                zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');
                $filename = ereg_replace("(.*)\.([^\.]*)$", "\\1", $this->filename).".jpg";
                if (zmgFileHelper::exists(str_replace($this->filename, $filename, $this->getAbsPath(ZMG_MEDIUM_THUMBNAIL)))) {
                    $file['name'] = $filename;
                }
            }
            if (!$file['name']) {
                $file['path'] = $template_path;
                $file['name'] = (strstr('flv', $ext)) ? "flv.png" : "video.png";
            }
        } else if (zmgMimeHelper::isAudio($ext)) {
            $file['path'] = $template_path;
            $file['name'] = "audio.png";
        }
        
        return $file;
    }
    
    function getGalleryDir() {
        if (empty($this->_gallery_dir)) {
            $this->setGalleryDir();
        }
        return $this->_gallery_dir;
    }
    
    function setGalleryDir($dir = null) {
        if ($dir === null) {
    		$db    = & zmgDatabase::getDBO();
            $table = zmgFactory::getConfig()->getTableName('galleries');
            $db->setQuery("SELECT dir FROM " . $table . " WHERE gid=".$this->gid);

            if ($db->query()) {
                $dir = trim($db->loadResult());
            }
    	}
        
        $this->_gallery_dir = $dir;
    }
    
    function setAsGalleryImage($ofParent = false) {
        return true;//TODO;
    }
    
    function getExtension() {
    	if (!$this->filename) {
            zmgError::throwError('zmgMedium: medium data not loaded yet');
        }
        
        if (empty($this->_extension)) {
            $dot = strrpos($this->filename, '.') + 1;
            $this->_extension = substr($this->filename, $dot);
        }
        
        return $this->_extension;
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
    
    /**
     * Get the comments a medium contains.
     *
     * @return void
     * @access public
     */
    function getComments() {
        $comments = array();
        
        $db    = & zmgDatabase::getDBO();
        $table = zmgFactory::getConfig()->getTableName('comments');
        $db->setQuery("SELECT cid FROM " . $table . " WHERE mid = " . $this->mid
         . " ORDER BY date_added ASC");

        $_result = $db->loadObjectList();
        foreach ($_result as $row) {
            $comment = new zmgComment($db);
            $comment->load(intval($row->cmtid));
            $comments[] = $comment;
        }
        
        return $comments;
    }
    
    function getMetadata() {
        if ($this->_metadata === null) {
            $this->_metadata = new zmgMediumMetadata(&$this);
        }
        
        return $this->_metadata;
    }
    
    function toJSON() {
        $json = & zmgFactory::getJSON();
        return ("'medium': {
            'mid'      : $this->mid,
            'name'     : ".$json->encode($this->name).",
            'filename' : ".$json->encode($this->filename).",
            'descr'    : ".$json->encode($this->descr).",
            'keywords' : ".$json->encode($this->keywords).",
            'date_add' : ".$json->encode($this->date_add).",
            'url_thumb': ".$json->encode($this->getRelPath(ZMG_MEDIUM_THUMBNAIL, '', false)).",
            'url_view' : ".$json->encode($this->getRelPath(ZMG_MEDIUM_VIEWSIZE, '', false)).",
            'type'     : ".$json->encode($this->getExtension()).",
            'hits'     : $this->hits,
            'votenum'  : $this->votenum,
            'votesum'  : $this->votesum,
            'published': $this->published,
            'gid'      : $this->gid,
            'uid'      : $this->uid,
            'members'  : ".$json->encode($this->members)."
        }");
    }
    
    function toXML($type = 'playlist') {
        if ($type === "playlist") {
            $events = & zmgFactory::getEvents();
            
            $artist = T_('no artist');
            $title  = T_('no title');

            $id3_data = $events->fire('ongetaudiometadata', false, $this->getAbsPath(ZMG_MEDIUM_ORIGINAL));
            if (is_array($id3_data) && !empty($id3_data['tags']['id3v1']['artist'][0])) {
                $artist = $id3_data['tags']['id3v1']['artist'][0];
            }
            if (is_array($id3_data) && !empty($id3_data['tags']['id3v1']['title'][0])) {
                $title  = $id3_data['tags']['id3v1']['title'][0];
            }

            return ("<track>"
             . "<title><![CDATA[" . (!($artist == "no artist" && $title == "no title") ? $artist.' - '.$title : $this->name) . "]]></title>"
             . "<location><![CDATA[" . $this->getRelPath(ZMG_MEDIUM_ORIGINAL) . "]]></location>"
             . "</track>");
        }
    }
}

class zmgMediumMetadata {

    var $_raw = null;
    
    var $_ext = null;
    
    var $_mid = null;
    
    function zmgMediumMetadata(&$medium) {
        $config = & zmgFactory::getConfig();
        $events = & zmgFactory::getEvents();
        
        $this->_mid = $medium->mid;
        
        $this->_ext = $medium->getExtension();
        zmgimport('org.zoomfactory.lib.mime.zmgMimeHelper');
        
        $path = $medium->getAbsPath(ZMG_MEDIUM_ORIGINAL. $config->get('filesystem/mediapath'));
        
        if (zmgMimeHelper::isImage($this->_ext)) {
            $this->_raw = $events->fire('ongetimagemetadata', false, $medium);
        } else if (zmgMimeHelper::isAudio($this->_ext)) {
            $this->_raw = $events->fire('ongetaudiometadata', false, $path);
        } else if (zmgMimeHelper::isVideo($this->_ext)) {
            $this->_raw = $events->fire('ongetvideometadata', false, $path);
        }
    }
    
    function interpretJpegMeta($name, $data) {
        $json = & zmgFactory::getJSON();
        $out  = "";
        
        if (!is_array($data)) {
            if (is_string($data) && strpos($data, '<p class="') === false) {
                $out .= $json->encode($name) . ": " . $json->encode($data) . ",";
            }
        } else {
            foreach ($data as $sName => $sData) {
                $out .= $this->interpretJpegMeta($sName, $sData);
            }
        }
        
        return $out;
    }
    
    function interpretId3($data) {
        $artist = T_('no artist');
        $title  = T_('no title');
        $album  = T_('no album');
        $year   = T_('no year');
        $length = T_('not available');
        $data   = T_('no audio data');

        if (is_array($data)) {
            if (!empty($data['tags']['id3v1']['artist'][0])) {
                $artist = $data['tags']['id3v1']['artist'][0];
            }
            if (!empty($data['tags']['id3v1']['title'][0])) {
                $title  = $data['tags']['id3v1']['title'][0];
            }
            if (!empty($data['tags']['id3v1']['album'][0])) {
                $album  = $data['tags']['id3v1']['album'][0];
            }
            if (!empty($data['tags']['id3v1']['year'][0])) {
                $year  = $data['tags']['id3v1']['year'][0];
            }
            if (!empty($data['playtime_string'])) {
                $length = $data['playtime_string'];
            }
            if (!empty($data['audio'])) {
                $data  = $data['audio']['dataformat'] . ", "
                 . $data['audio']['sample_rate'] . " Hz, "
                 . $data['audio']['bitrate'] . " bps " . $data['audio']['channelmode'];
            }
        }
        
        return array($artist, $title, $album, $year, $length, $data);
    }
    
    function toJSON() {
        $json = & zmgFactory::getJSON();
        
        $out = "'metadata': {
          'mid': " . $this->_mid;
        if ($this->_raw === null || empty($this->_raw)) {
            return $out . ",'title': " . $json->encode(T_('No Metadata available.')) . "}";
        }
        
        zmgimport('org.zoomfactory.lib.mime.zmgMimeHelper');
        if (zmgMimeHelper::isImage($this->_ext)) {
            //TODO
            //print_r($this->_raw);
            if (empty($this->_raw['title'])) {
                $out .= ",'title': " . $json->encode(T_('No Metadata available.')) . "}";
            } else {
                $out .= ",
                  'title' : " . $json->encode($this->_raw['title']);
                if (is_array($this->_raw['IFD'])) {
                    $out .= ",";
                    foreach ($this->_raw['IFD'] as $name => $exif_val) {
                        $out .= $this->interpretJpegMeta($name, $exif_val);
                    }
                    $out = substr($out, 0, -1);
                }

                $out .= "}";
            }
        } else if (zmgMimeHelper::isAudio($this->_ext)) {
            list($artist, $title, $album, $year, $length, $data)
              = $this->interpretId3($this->_raw);
            
            $out .= ",
              'title' : " . $json->encode($title) . ","
              . $json->encode(T_('Artist')) . ": " . $json->encode($artist) . ","
              . $json->encode(T_('Song'))   . ": " . $json->encode($title)  . ","
              . $json->encode(T_('Album'))  . ": " . $json->encode($album)  . ","
              . $json->encode(T_('Year'))   . ": " . $json->encode($year)   . ","
              . $json->encode(T_('Length')) . ": " . $json->encode($length) . ","
              . $json->encode(T_('Data'))   . ": " . $json->encode($data)   . "}";
        } else if (zmgMimeHelper::isVideo($this->_ext)) {
            //TODO
            $out .= ",'title': " . $json->encode(T_('No Metadata available.')) . "}";
        }
        
        return $out;
    }

}

?>
