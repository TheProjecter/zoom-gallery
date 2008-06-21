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
    function buildConstScript($constants) {
        $ret = ("<script language=\"javascript\" type=\"text/javascript\">\n"
         . "<!--\n"
         . "\tif (!window.ZMG) window.ZMG = {};\n"
         . "\tZMG.CONST = {};\n");
        
        if (count($constants)) {
            foreach ($constants as $name => $value) {
                $ret .= "\tZMG.CONST.$name = $value;\n";
            }
        }
        
        return $ret . ("//-->\n"
         . "</script>\n");
    }
    
    /**
     * Create a HTML dropdown form element which contains a list of galleries
     * (ordered and indented).
     * @param mixed $onchange 
     * @param string $sel_name
     * @param int $sel
     * @param int $exclude
     * @return void
     */
    function galleriesSelect($onchange = 0, $sel_name = "gid", $sel = 0, $exclude = 0) {
        $html = "<select name=\"$sel_name\" id=\"$sel_name\" class=\"inputbox\"";
        if ($onchange !== 0) {
            $html .= " onchange=\"$onchange\"";
        }
        $html .= ">\n\t<option value=\"0\">---&nbsp;".T_('Select a Gallery')."&nbsp;---</option>\n";

        $zoom      = & zmgFactory::getEvents()->fire('ongetcore');
        $galleries = & $zoom->getGalleryList();
        
        if (isset($galleries)) {
            foreach ($galleries as $set) {
                $gallery = $set['object'];
                if ($gallery->gid != $exclude || $exclude == 0) {
                    $html .= "\t<option value=\"".$gallery->gid."\""
                     . ($sel == $gallery->gid ? " selected": "").">".$set['path_name']
                     . "</option>\n";
                }
            }
        }

        echo $html."</select>\n";
    }
    
    function groupsACLSelect($onchange = 0, $sel_name = "acl_gid", $sel = 0, $exclude = 0) {
        $html = "<select name=\"$sel_name\" id=\"$sel_name\" size=\"14\" class=\"inputbox\"";
        if ($onchange !== 0) {
            $html .= " onchange=\"$onchange\"";
        }
        $html .= " style=\"width: 200px;\">\n"
         . "\t<optgroup label=\"----- " . T_('Global') . " -----\">\n"
         . "\t<option value=\"0\">" . T_('Public Access') . "</option>\n"
         . "\t<option value=\"1\">" . T_('Registered Users Only') . "</option>\n"
         . "\t</optgroup>\n"
         . "\t<optgroup label=\"----- " . T_('Specific Group') . " -----\"";
        
        $list = zmgACL::getGroupList();
        
        foreach ($list as $group) {
            $html .= "\t<option value=\"" . $group->value . "\">" . $group->text . "</option>\n";
        }
        
        echo $html."</optgroup>\n</select>\n";
    }
}
?>
