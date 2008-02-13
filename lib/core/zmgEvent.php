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

class zmgEvent {
    /**
     * @var string
     * @access public
     */
    var $type = null;
    /**
     * @var array
     * @access private
     */
    var $_arguments = array();
    
    function zmgEvent($type) {
    	$this->type = $type;
    }
    
    function pass() {
    	$args = func_get_args();
        foreach ($args as $arg) {
        	//TODO: add some validation of some sort...
            $this->_arguments[] = $arg;
        }
    }
    function getArguments() {
        return $this->_arguments;
    }
}
?>
