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
    
    function toJSON() {
        $json = new zmgJSON();
        return ("'gallery': {
            'gid'      : $this->gid,
            'name'     : ".$json->encode($this->name).",
            'descr'    : ".$json->encode($this->descr).",
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
