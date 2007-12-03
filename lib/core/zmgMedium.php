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
    
    var $uid = null;
    
    var $members = null;
    
    var $_members = null; 
    
    function zmgMedium(&$db) {
        $this->zmgTable('#__zmg_media', 'mid', $db);
    }
}
?>
