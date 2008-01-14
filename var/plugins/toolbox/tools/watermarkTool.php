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

class zmgWatermarkTool {
    function apply($src_file, $dest_file, $wm_file, $position, &$imgobj) {
        $imginfo_wm = getimagesize($wm_file);

        $imagewidth = $imgobj->_size[0];
        $imageheight = $imgobj->_size[1];
        $watermarkwidth = $imginfo_wm[0];
        $watermarkheight = $imginfo_wm[1];      
        $width_left = $imagewidth - $watermarkwidth;
        $height_left = $imageheight - $watermarkheight;
        switch ($position) {
            case "TL": // Top Left
                $startwidth = $width_left >= 5 ? 4 : $width_left;
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "TM": // Top middle 
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "TR": // Top right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "CL": // Center left
                $startwidth = $width_left >= 5 ? 4 : $width_left;
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            default:
            case "C": // Center (the default)
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            case "CR": // Center right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            case "BL": // Bottom left
                $startwidth = $width_left >= 5 ? 5 : $width_left;
                $startheight = $imageheight - $watermarkheight-5;
                break;
            case "BM": // Bottom middle
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = $imageheight - $watermarkheight-5;
                break;
            case "BR": // Bottom right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = $imageheight - $watermarkheight-5;
                break;
        }
        //TODO: implement call to image library too watermark() function
    }
    function autoDetect() {
        return;
    }
}
?>
