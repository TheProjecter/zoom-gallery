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
//list of global constants:
define("_ZMG_EXEC", 1);
define("ZMG_ABS_PATH", dirname(__FILE__));
define("ZMG_ADMIN", 0);

//load all required libraries
include(ZMG_ABS_PATH . '/lib/initzoom.php');

$view   = & zmgFactory::getView();
$events = & zmgFactory::getEvents();

$view->setAndRun();

$events->fire('onfinish');

?>
