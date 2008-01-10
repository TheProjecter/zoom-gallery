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

class zmgHTML {
    /**
     * Create a HTML dropdown form element which contains a list of galleries
     * (ordered and indented).
     * @param mixed $onchange 
     * @param string $sel_name
     * @param int $sel
     * @param int $exclude
     * @return string
     */
    function galleriesSelect($onchange = 0, $sel_name = "gid", $sel = 0, $exclude = 0) {
        if ($onchange === 0) {
            $html = "<select name=\"$sel_name\" id=\"$sel_name\" class=\"inputbox\">";
        } else {
            $html = "<select name=\"$sel_name\" class=\"inputbox\" onchange=\"$onchange\">";
        }
        $html .= "<option value=\"0\">---&nbsp;".T_('Select a Gallery')."&nbsp;---</option>";

        $zoom      = & zmgFactory::getZoom();
        $galleries = & $zoom->getGalleryList();
        
        if (isset($galleries)) {
            foreach ($galleries as $set) {
                $gallery = $set['object'];
                if ($gallery->gid != $exclude || $exclude == 0) {
                    $html .= "<option value=\"".$gallery->gid."\""
                     . ($sel == $gallery->gid ? " selected": "").">".$set['path_name']
                     . "</option>\n";
                }
            }
        }
        echo $html."</select>";
    }
}
?>
