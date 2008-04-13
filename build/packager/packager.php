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
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

if ($argc > 0) {
    for ($i = 1; $i < $argc; $i++) {
        parse_str($argv[$i], $tmp);
        $_REQUEST = array_merge($_REQUEST, $tmp);
    }
}

include(ZMG_ABS_PATH . '/utils.php');

$klass = str_replace('.', '_', strtolower($_REQUEST['label'])) . "_Target";
include(ZMG_ABS_PATH . '/targets/' . str_replace('_Target', '.target', $klass) . '.php');

$sPath = ZMG_ABS_PATH . DS.'..'.DS.'application'.DS.$_REQUEST['label'].DS.'com_'.$_REQUEST['name'];

$aDetails = array(
  'name'        => $_REQUEST['name'],
  'descr'       => $_REQUEST['descr'],
  'author'      => $_REQUEST['author'],
  'authoremail' => $_REQUEST['authoremail'],
  'authorurl'   => $_REQUEST['authorurl'],
  'date'        => date('m/d/Y'),
  'copyright'   => $_REQUEST['copy'],
  'license'     => $_REQUEST['license'],
  'version'     => $_REQUEST['version']
);

$sHeader = call_user_func(array($klass, 'createHeader'));

$sMetaBlock = call_user_func_array(array($klass, 'createMetaBlock'), array($aDetails));

$aFiles = packagerUtils::readDir($sPath.DS.'images');
for ($i = 0; $i < count($aFiles); $i++) {
    $aFiles[$i] = "images/" . $aFiles[$i];
}
$aFiles = array_merge($aFiles, packagerUtils::readDir($sPath, '(admin.*)|(\.html)|(install.*)'));

$aDetails = array(
  'menu.img'     => 'components/com_'.$_REQUEST['name'].'/images/logo'.$_REQUEST['name'].'.gif',
  'menu.link'    => 'option=com_'.$_REQUEST['name'],
  'menu.caption' => $_REQUEST['fullname'],
  'files'        => $aFiles
);
$sAdminBlock = call_user_func_array(array($klass, 'createAdminBlock'), array($aDetails));

$sMiscBlock = call_user_func_array(array($klass, 'createMiscBlock'), array($aDetails));

$aFiles = packagerUtils::readDir($sPath, '.', true, true, true);
for ($i = 0; $i < count($aFiles); $i++) {
    $aFiles[$i] = str_replace($sPath.DS, '', $aFiles[$i]);
    //TODO: move this check up with a reqular expression for readDir()
    if (strpos($aFiles[$i], 'images') === 0 || strpos($aFiles[$i], 'admin.') === 0
      || strpos($aFiles[$i], 'install') === 0 || strpos($aFiles[$i], 'uninstall') === 0) {
        $aFiles[$i] = null;
    }
}
$sFileListBlock = call_user_func_array(array($klass, 'createFileListBlock'), array($aFiles));

$sInstallBlock = call_user_func_array(array($klass, 'createInstallBlock'),
  array('install.' . $_REQUEST['name'] . '.sql'));
  
$sUnInstallBlock = call_user_func_array(array($klass, 'createUnInstallBlock'),
  array('uninstall.' . $_REQUEST['name'] . '.sql'));
  
$sFooter = call_user_func(array($klass, 'createFooter'));

$sXML = $sHeader . $sMetaBlock . $sAdminBlock . $sFileListBlock . $sMiscBlock
 . $sInstallBlock . $sUnInstallBlock . $sFooter;

file_put_contents(ZMG_ABS_PATH . DS.'..'.DS.'application'.DS.$_REQUEST['label']
 .DS.'com_'.$_REQUEST['name'].DS.'install.'.$_REQUEST['name'].'.xml', $sXML);
?>
