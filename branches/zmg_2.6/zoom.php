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

defined('_ZMG_EXEC') or die('Restricted access');
 
//list of global constants:
define("_ZMG_EXEC", 1);
define("ZMG_ABS_PATH", dirname(__FILE__));

//load all required libraries
require(ABS_PATH . '/lib/initzoom.php');

$zoom->view->run();

$zoom->fireEvents('onfinish');
?>