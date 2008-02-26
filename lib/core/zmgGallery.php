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

/**
 * Gallery class; creates an instance of a gallery.
 *
 * @access public
 */
class zmgGallery extends zmgTable {
    /**
     * @var int
     * @access public
     */
    var $gid = null;
    /**
     * @var string
     * @access public
     */
    var $name = null;
    /**
     * @var string
     * @access public
     */
    var $descr = null;
    /**
     * @var string
     * @access public
     */
    var $dir = null;
    /**
     * @var int
     * @access public
     */
    var $cover_img = null;
    /**
     * @var zmgImage
     * @access private
     */
    var $_obj_cover_img = null;
    /**
     * @var string
     * @access public
     */
    var $password = null;
    /**
     * @var string
     * @access public
     */
    var $keywords = null;
    /**
     * @var array
     * @access public
     */
    var $_keywords = null;
    /**
     * @var int
     * @access public
     */
    var $sub_gid = null;
    /**
     * @var int
     * @access public
     */
    var $pos = null;
    /**
     * @var int
     * @access public
     */
    var $hide_msg = null;
    /**
     * @var int
     * @access public
     */
    var $shared = null;
    /**
     * @var int
     * @access public
     */
    var $published = null;
    /**
     * @var int
     * @access public
     */
    var $uid = null;
    /**
     * @var string
     * @access public
     */
    var $members = null;
    /**
     * @var array
     * @access public
     */
    var $_members = null;
    /**
     * @var int
     * @access public
     */
    var $ordering = null;
    /**
     * @var array
     * @access public
     */
    var $_media = null;
    
    function zmgGallery(&$db) {
        $this->zmgTable('#__zmg_galleries', 'gid', $db);
    }
    
    function getCoverImage() {
        if (empty($this->gid)) {
        	return zmgError::throwError('zmgGallery: gallery data not loaded yet!');
        }
        
        if (is_object($this->_obj_cover_img)) {
        	return $this->_obj_cover_img->getRelPath();
        }
        
        $db = & zmgDatabase::getDBO();
        if ($this->cover_img == null) {
        	// first, check if the gallery contains any media at all:
            $zoom = & zmgFactory::getZoom();
            $database->setQuery("SELECT mid FROM #__zmg_media WHERE gid = " . $this->gid
              . " ORDER BY " . $zoom->getMediaOrdering() . " LIMIT 1");
            $medium = intval($database->loadResult());
            if ($medium > 0) {
            	// get the first available medium
                $this->_obj_cover_img = new zmgMedium($db);
                $this->_obj_cover_img->load($medium);
    
                return $this->_obj_cover_img->getRelPath();
            }
        } else {
        	$this->_obj_cover_img = new zmgMedium($db);
            $this->_obj_cover_img->load($this->cover_img);

            return $this->_obj_cover_img->getRelPath();
        }
        
        //TODO: display an 'empty gallery' image...or let the client handle this?
        return "";
    }
    
    /**
     * Generate a random directory-name for a new gallery.
     * 
     * @return string
     * @access public
     * @static
     */
    function generateDir() {
    	$newdir = "";
        srand((double)microtime() * 1000000);
        for ($acc = 1; $acc <= 6; $acc++) {
            $newdir .= chr(rand (0,25) + 65);
        }
        $zoom = & zmgFactory::getZoom();
        $path = zmgEnv::getRootPath() .DS.$zoom->getConfig('filesystem/mediapath') . $newdir;
        if (is_dir($path)) {
        	return zmgGallery::generateDir();
        }
        return $newdir;
    }
    
    function buildDirStructure() {
    	zmgimport('org.zoomfactory.lib.helpers.zmgFileHelper');
        
        $html_file = "<html><body bgcolor=\"#FFFFFF\"></body></html>";
        
        $root = zmgEnv::getRootPath();
        $zoom = & zmgFactory::getZoom();
        $mediapath = $root .DS.$zoom->getConfig('filesystem/mediapath');
        $dirs = array(
          $mediapath . $this->dir,
          $mediapath . $this->dir .DS.'thumbs',
          $mediapath . $this->dir .DS.'viewsize'
        );
        
        foreach ($dirs as $dir) {
        	if (zmgFileHelper::createDir($dir, 0777)) {
        		if (!zmgFileHelper::write($dir.DS.'index.html', $html_file)) {
        			zmgError::throwError(T_('Unable to write to file: ') . $dir.DS.'index.html');
        		}
        	} else {
        		zmgError::throwError(T_('Unable to create directory: ') . $dir);
        	}
        }
        
        return true;
    }
    
    function getEmpty($ret_type = 'json') {
    	if ($ret_type == "json") {
            $json = new zmgJSON();
            return ("'gallery': {
                'name'     : 'New',
                'descr'    : 'New',
                'dir'      : ".$json->encode(zmgGallery::generateDir()).",
                'keywords' : '',
                'hide_msg' : false,
                'published': true,
                'shared'   : true
            }");
        }
    }
    
    function toJSON() {
        $json = new zmgJSON();
        return ("'gallery': {
            'gid'      : $this->gid,
            'name'     : ".$json->encode($this->name).",
            'descr'    : ".$json->encode($this->descr).",
            'cover_img': ".$json->encode($this->getCoverImage()).",
            'dir'      : ".$json->encode($this->dir).",
            'keywords' : ".$json->encode($this->keywords).",
            'sub_gid'  : $this->sub_gid,
            'pos'      : $this->pos,
            'hide_msg' : $this->hide_msg,
            'shared'   : $this->shared,
            'published': $this->published,
            'uid'      : $this->uid,
            'ordering' : $this->ordering,
            'members'  : ".$json->encode($this->members)."
        }");
    }
}
?>
