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

class zmgAudioTool {
	function process() {
		return true;
	}

    /**
     * Get the ID3v2.x tag from an mp3 file.
     *
     * @param string $file
     * @return array
     * @access public
     */
    function getid3($file) {
        global $mosConfig_absolute_path, $database;
        require_once($mosConfig_absolute_path."/components/com_zoom/lib/getid3/getid3.php");
        require_once($mosConfig_absolute_path."/components/com_zoom/lib/getid3/extension.cache.mysql.php");
        $getid3 = new getID3_cached_mysql($database);
        $fileInfo = $getid3->analyze($file);
        getid3_lib::CopyTagsToComments($fileInfo);
        return $fileInfo;
    }
    function autoDetect() {
        return;
    }
}
?>
