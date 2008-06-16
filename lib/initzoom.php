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

//initialize the zoom (app) class
zmgimport('org.zoomfactory.lib.Zoom');
//import other useful stuff
zmgimport('org.zoomfactory.lib.zmgEvent');
zmgimport('org.zoomfactory.lib.zmgSession');
zmgimport('org.zoomfactory.lib.zmgHTML');

$zoom = & zmgFactory::getZoom();

if (!class_exists('InputFilter')) {
    zmgimport('org.zoomfactory.lib.phpinputfilter.inputfilter');
}

$zoom->fireEvent('onstartup', false);

$zoom->hasAccess() or die('Restricted access');

$zoom->view->setViewType(zmgEnv::getViewType());

//set error handling options
zmgError::setErrorHandling($zoom->getConfig('app/errors/defaultmode'),
  $zoom->getConfig('app/errors/defaultoption'));

//load php-gettext (used in zoom in 'fallback mode')
zmgimport('org.zoomfactory.lib.phpgettext.gettext_inc');
// gettext setup
T_setlocale(LC_MESSAGES, $zoom->getConfig('locale/default'));
// Set the text domain as 'messages'
$domain = $zoom->getConfig('locale/domain');
T_bindtextdomain($domain, ZMG_ABS_PATH . '/locale');
T_bind_textdomain_codeset($domain, $zoom->getConfig('locale/encoding'));
T_textdomain($domain);

$zoom->fireEvent('oncontentstart');

$zoom->fireEvent('oncontent');

?>
