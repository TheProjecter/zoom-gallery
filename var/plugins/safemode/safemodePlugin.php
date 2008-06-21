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

class zmgSafemodePlugin extends zmgError {
    function bindEvents() {
        return array(
            "onstartup" => array(
                "embed" => array()
            ),
            "ongetftpclient" => array(
                "getFTPClient"=> array()
            ),
            "onfilecopy" => array(
                "fileCopy" => array("src", "dest")
            ),
            "onfiledelete" => array(
                "fileDelete" => array("file")
            ),
            "onfilemove" => array(
                "fileMove" => array("src", "dest")
            ),
            "onfilewrite" => array(
                "fileWrite" => array("file", "buffer")
            ),
            "onfileupload" => array(
                "fileCopy" => array("src", "dest")
            ),
            "ondircreate" => array(
                "dirCreate" => array("path")
            ),
            "ondirdelete" => array(
                "dirDelete" => array("path")
            ),
            "onchmod" => array(
                "fileChmod" => array("file", "perms")
            )
        );
    }
    
    function embed() {
        $settings_file = ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.'safemode'.DS.'settings.xml';
        if (file_exists($settings_file)) {
            $plugins = & zmgFactory::getPlugins();
            $plugin = & $plugins->get('safemode');
            $plugins->embedSettings(&$plugin, $settings_file);
        }
    }
    
    function &getFTPClient() {
        $cfg = zmgFactory::getConfig()->get('plugins/safemode/credentials');
        
        zmgimport('org.zoomfactory.var.plugins.ftp.zmgFTP');
        
        return zmgFTP::getInstance($cfg['host'], $cfg['port'], array('root' => $cfg['root']),
          $cfg['user'], $cfg['pass']);
    }
    
    function fileCopy($event) {
        $src  = $event->getArgument('src');
        $dest = $event->getArgument('dest');
        
        $ftp  = & zmgSafemodePlugin::getFTPClient();
        
        //Translate the destination path for the FTP account
        $dest = zmgFileHelper::cleanPath(str_replace(zmgEnv::getRootPath(), $ftp->getRoot(), $dest), '/');
        if (!$ftp->store($src, $dest)) {
            // FTP connector throws an error
            return false;
        }
        return true;
    }
    
    function fileDelete($event) {
        $file = $event->getArgument('file');
        
        $ftp  = & zmgSafemodePlugin::getFTPClient();
        
        $file = zmgFileHelper::cleanPath(str_replace(zmgEnv::getRootPath(), $ftp->getRoot(), $file), '/');
        if (!$ftp->delete($file)) {
            // FTP connector throws an error
            return false;
        }
        return true;
    }
    
    function fileMove($event) {
        $src  = $event->getArgument('src');
        $dest = $event->getArgument('dest');
        
        $ftp  = & zmgSafemodePlugin::getFTPClient();
        
        //Translate the destination path for the FTP account
        $src    = zmgFileHelper::cleanPath(str_replace(JPATH_ROOT, $ftp->getRoot(), $src), '/');
        $dest   = zmgFileHelper::cleanPath(str_replace(JPATH_ROOT, $ftp->getRoot(), $dest), '/');
        if (!$ftp->rename($src, $dest)) {
            // FTP connector throws an error
            return false;
        }
        return true;
    }
    
    function fileWrite($event) {
        $file   = $event->getArgument('file');
        $buffer = $event->getArgument('buffer');
        
        $ftp  = & zmgSafemodePlugin::getFTPClient();
        
        //Translate the destination path for the FTP account
        $file = zmgFileHelper::cleanPath(str_replace(zmgEnv::getRootPath(), $ftp->getRoot(), $file), '/');
        //Use FTP to write buffer to file
        if (!$ftp->write($file, $buffer)) {
            // FTP connector throws an error
            return false;
        }
        return true;
    }
    
    function fileChmod($event) {
        $file  = $event->getArgument('file');
        $perms = $event->getArgument('perms');
        
        $ftp  = & zmgSafemodePlugin::getFTPClient();
        
        //Translate the destination path for the FTP account
        $file = zmgFileHelper::cleanPath(str_replace(zmgEnv::getRootPath(), $ftp->getRoot(), $file), '/');
        if (!$ftp->chmod($file, $perms)) {
            // FTP connector throws an error
            return false;
        }
        return true;
    }
    
    function dirCreate($event) {
        $path = $event->getArgument('path');
        
        $ftp  = & zmgSafemodePlugin::getFTPClient();
        
        //Translate the destination path for the FTP account
        $path = zmgFileHelper::cleanPath(str_replace(zmgEnv::getRootPath(), $ftp->getRoot(), $path), '/');
        if (!$ftp->mkdir($path)) {
            // FTP connector throws an error
            return false;
        }
        return true;
    }
    
    function dirDelete($event) {
        $path = $event->getArgument('path');
        
        $ftp  = & zmgSafemodePlugin::getFTPClient();
        
        //Translate the destination path for the FTP account
        $path = zmgFileHelper::cleanPath(str_replace(zmgEnv::getRootPath(), $ftp->getRoot(), $path), '/');
        if (!$ftp->rmdir($path)) {
            // FTP connector throws an error
            return false;
        }
        return true;
    }
}
?>
