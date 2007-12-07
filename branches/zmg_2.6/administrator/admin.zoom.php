<?php
/**
 * zOOm Media Gallery! - a multi-gallery component 
 * 
 * @package zmg
 * @version $Revision$
 * @author Mike de Boer <mdeboer AT ebuddy.com>
 * @copyright Copyright &copy; 2007, Mike de Boer. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */
//list of global constants:
define("_ZMG_EXEC", 1);
define("ZMG_ABS_PATH", str_replace('/administrator', '', dirname(__FILE__)));

define("ZMG_ADMIN", 1);

//load all required libraries
require(ZMG_ABS_PATH . '/lib/initzoom.php');

$zoom->view->run(&$zoom);

$zoom->fireEvents('onfinish');
?>