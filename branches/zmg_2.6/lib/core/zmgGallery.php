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

/**
 * Gallery class; creates an instance of a gallery.
 *
 * @access public
 */
class zmgGallery extends zmgTable {
    var $gid = null;
    
    var $name = null;
    
    var $descr = null;
    
    var $dir = null;
    
    var $cover_img = null;
    
    var $password = null;
    
    var $keywords = null;
    
    var $_keywords = null;
    
    var $subcat_id = null;
    
    var $pos = null;
    
    var $hide_msg = null;
    
    var $shared = null;
    
    var $published = null;
    
    var $uid = null;
    
    var $members = null;
    
    var $_members = null;
    
    var $custom_order = null;
    
    var $_media = null;
    
    function zmgGallery(&$db) {
        $this->zmgTable('#__zmg_galleries', 'gid', $db);
    }
}
?>
