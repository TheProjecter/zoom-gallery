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

class zmgACL {
    function check_defines() {
        return defined( '_JEXEC' );
    }
    
    function &getACL() {
        return JFactory::getACL();
    }
    
    function getGroupList() {
        $acl = & zmgACL::getACL();
        
        return $acl->get_group_children_tree(null, 'USERS', false);
    }
}
?>
