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

include('zmgFunctions.php');

//make the object factory available for all other classes
zmgimport('org.zoomfactory.lib.zmgFactory');

//load the error handling base class
zmgimport('org.zoomfactory.lib.zmgError');

//initialize Smarty template engine
zmgimport('org.zoomfactory.lib.smarty.Smarty');

//import other useful stuff
zmgimport('org.zoomfactory.lib.zmgHTML');

if (!class_exists('InputFilter')) {
    zmgimport('org.zoomfactory.lib.phpinputfilter.inputfilter');
}

$config  = & zmgFactory::getConfig();
$events  = & zmgFactory::getEvents();
$request = & zmgFactory::getRequest();
$view    = & zmgFactory::getView();

$events->fire('onstartup');

$events->fire('onstarted');

if (!$config->isInstalled()) $config->firstRun();

$view->setViewType(zmgEnv::getViewType());

//set error handling options
zmgError::setErrorHandling($config->get('app/errors/defaultmode'),
  $config->get('app/errors/defaultoption'));

//load php-gettext (used in zoom in 'fallback mode')
zmgimport('org.zoomfactory.lib.phpgettext.gettext_inc');
// gettext setup
T_setlocale(LC_ALL, $config->get('locale/default'));
// Set the text domain as 'messages'
$domain = $config->get('locale/domain');
T_bindtextdomain($domain, ZMG_ABS_PATH . '/locale');
T_bind_textdomain_codeset($domain, $config->get('locale/encoding'));
T_textdomain($domain);

$events->fire('oncontent');

?>
