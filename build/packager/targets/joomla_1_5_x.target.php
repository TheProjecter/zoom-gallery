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

class joomla_1_5_x_Target {
    function createHeader() {
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
         . "<install type=\"component\" version=\"1.5.0\">\n";
    }
    
    function createMetaBlock($details) {
        return "  <name>".$details['name']."</name>\n"
         . "  <description>".$details['descr']."</description>\n"
         . "  <author>".$details['author']."</author>\n"
         . "  <authoremail>".$details['authoremail']."</authoremail>\n"
         . "  <authorurl>".$details['authorurl']."</authorurl>\n"
         . "  <creationDate>".$details['date']."</creationDate>\n"
         . "  <copyright>".$details['copyright']."</copyright>\n"
         . "  <license>".$details['license']."</license>\n"
         . "  <version>".$details['version']."</version>\n";
    }
    
    function createAdminBlock($details) {
        return "  <administration>\n"
         . "    <menu img=\"".$details['menu.img']."\" link=\"".$details['menu.link']."\">".$details['menu.caption']."</menu>\n"
         . joomla_1_5_x_Target::_createAdminFileList($details['files'])
         . "  </administration>\n";
    }
    
    function _createAdminFilelist($files) {
        if (count($files) === 0) return "";
        
        $out = "    <files>\n";
        
        foreach ($files as $file) {
            $out .= "      <filename>" . $file . "</filename>\n";
        }
        
        return $out . "    </files>\n";
    }
    
    function createMiscBlock($data) {
        return "  <media folder=\"images\" destination=\"zoom\" />\n";
    }
    
    function createFileListBlock($files) {
        if (count($files) === 0) return "";
        
        $out = "  <files>\n";
        
        foreach ($files as $file) {
            if ($file) {
                $out .= "    <filename>" . $file . "</filename>\n";
            }
        }
        
        return $out . "  </files>\n";
    }
    
    function createInstallBlock($filename) {
        return "  <install>\n"
         . "    <sql>\n"
         . "      <file driver=\"mysql\" charset=\"utf8\">$filename</file>\n"
         . "    </sql>\n"
         . "  </install>\n";
    }
    
    function createUnInstallBlock($filename) {
        return "  <uninstall>\n"
         . "    <sql>\n"
         . "      <file driver=\"mysql\" charset=\"utf8\">$filename</file>\n"
         . "    </sql>\n"
         . "  </uninstall>\n";
    }
    
    function createFooter() {
        return "</install>\n";
    }
}

?>