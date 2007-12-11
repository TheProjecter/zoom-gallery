<?php
/**
 * zOOm Media Gallery! - a multi-gallery component 
 * 
 * @package zmg
 * @subpackage core
 * @version $Revision$
 * @author Mike de Boer <mdeboer AT ebuddy.com>
 * @copyright Copyright &copy; 2007, Mike de Boer. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */

defined('_ZMG_EXEC') or die('Restricted access');

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
    
    var $uid = null;
    
    var $members = null;
    
    var $_members = null; 
    
    function zmgMedium(&$db) {
        $this->zmgTable('#__zmg_media', 'mid', $db);
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
