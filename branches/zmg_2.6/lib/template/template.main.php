<?php
//zOOm Media Gallery//
/** 
-----------------------------------------------------------------------
|  zOOm Media Gallery! by Mike de Boer - a multi-gallery component    |
-----------------------------------------------------------------------

-----------------------------------------------------------------------
|                                                                     |
| Author: Mike de Boer, <http://www.mikedeboer.nl>                    |
| Copyright: copyright (C) 2007 by Mike de Boer                       |
| Description: zOOm Media Gallery, a multi-gallery component for      |
|              Joomla!. It's the most feature-rich gallery component  |
|              for Joomla!! For documentation and a detailed list     |
|              of features, check the zOOm homepage:                  |
|              http://www.zoomfactory.org                             |
| License: GPL                                                        |
| Filename: template.main.php                                         |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:template.main.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @subpackage Template
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class ZMG_Template_Main {
    /**
	* @return string
	* @param string $sel_name
	* @param string $first_opt
	* @param int $onchange
	* @param string $sel
	* @desc Create a HTML dropdown form element which contains a list of ALL keywords.
	* @access public
	*/
	function createKeywordsDropdown(&$keywords, $sel_name, $first_opt, $onchange=0, $sel="") {
		if ($onchange==0) {
			$html = "<select name=\"".$sel_name."\" class=\"inputbox\">\n";
		} elseif ($onchange==1) {
			$html = "<select name=\"".$sel_name."\" class=\"inputbox\" onchange=\"reloadPage()\">\n";
		}
		$html .= $first_opt;
		if (isset($keywords)) {
			foreach ($keywords as $keyword) {
		 		$html.= "<option value=\"".$keyword."\">".$keyword."</option>\n";
			}
		}
		echo $html."</select>\n";
	}
	
	function showPageHeader($use_tables = true, $Itemid, $PageNo, $key, $dir_prefix) {
	    global $mosConfig_live_site, $mainframe, $zoom, $backend;
	    $medium_name = (empty($zoom->_gallery->_images[$key]->_name)) ? $zoom->_gallery->_images[$key]->_filename : $zoom->_gallery->_images[$key]->_name;
	    if (is_callable('appendPathWay', $mainframe)) {
        	$mainframe->appendPathWay("<a href=\"".sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$zoom->_gallery->_id."&amp;PageNo=".$PageNo)."\">"
        	 . $zoom->_gallery->_name
        	 . "</a>");
        	$mainframe->appendPathWay($medium_name);
        }
	    if ($use_tables) {
	    	?>
    		<table border="0" cellspacing="0" cellpadding="0" width="100%">
    		<tr>
    		<td width="30" class="sectiontableheader"></td>
    		<td class="sectiontableheader" align="left" valign="middle">
    		<?php
    		if ($zoom->_CONFIG['mainscreen']) {
    			?>
    			<a class="pagenav" href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid); ?>">
    			<img src="<?php echo $dir_prefix; ?>images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN; ?>" border="0" />&nbsp;<?php echo _ZOOM_MAINSCREEN; ?>
    			</a> &gt; 
    			<?php
    		}
			?>
			<a class="pagenav" href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$zoom->_gallery->_id."&amp;PageNo=".$PageNo); ?>"><?php echo $zoom->_gallery->_name; ?>
			</a> &gt; <strong><?php echo $medium_name; ?></strong>
			</td>
			<?php
    		if ($zoom->_CONFIG['lightbox']) {
    			echo ("\t\t<td align=\"center\" valign=\"middle\" class=\"sectiontableheader\" width=\"40\">\n"
    			 . "\t\t<a href=\"javascript:void(0);\" onmouseover=\"return overlib('"._ZOOM_LIGHTBOX_ITEM."', CAPTION, '"._ZOOM_LIGHTBOX."');\" onmouseout=\"return nd();\">\n"
    			 . "\t\t<img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/lightbox.png\" border=\"0\" name=\"lightbox\" onclick=\"Zoom.lightboxAdd(".$zoom->_gallery->_images[$key]->_id.", 1, this);\" alt=\"\" /></a>&nbsp;\n"
    			 . "\t\t</td>\n");
    		}
    		if ($zoom->_CONFIG['ecards']) {
    			?>
    			<td align="center" valign="top" class="sectiontableheader" width="40">
    				<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=ecard&amp;catid=".$zoom->_gallery->_id."&amp;key=".$key."&amp;PageNo=".$PageNo); ?>" onmouseover="return overlib('<?php echo _ZOOM_ECARD_SENDAS; ?>', CAPTION, '<?php echo _ZOOM_ECARD_SENDCARD; ?>');" onmouseout="return nd();">
    				<img src="<?php echo $dir_prefix; ?>images/ecard.png" border="0" name="ecard" alt="" /></a>
    			</td>
    			<?php
    		} // end IF ecards
    		?>
    		</tr>
    		</table>
    		<?php
	    } else {
	        ?>
            <h3 class="sectiontableheader">
            <?php
            if ($zoom->_CONFIG['mainscreen']) {
                ?>
                <a class="pagenav" href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid); ?>">
                <img src="<?php echo $dir_prefix; ?>images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN; ?>" border="0" />&nbsp;<?php echo _ZOOM_MAINSCREEN; ?>
                </a> &gt; 
                <?php
            }
            ?>
            <a class="pagenav" href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$zoom->_gallery->_id."&amp;PageNo=".$PageNo); ?>"><?php echo $zoom->_gallery->_name; ?>
            </a> &gt; <strong><?php echo $medium_name; ?></strong>
            </h3>
            <?php
            if ($zoom->_CONFIG['lightbox']) {
                echo ("\t\t<span class=\"top-full-image-lightbox\">\n"
                 . "\t\t<a href=\"javascript:void(0);\" onmouseover=\"return overlib('"._ZOOM_LIGHTBOX_ITEM."', CAPTION, '"._ZOOM_LIGHTBOX."');\" onmouseout=\"return nd();\">\n"
                 . "\t\t<img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/lightbox.png\" border=\"0\" name=\"lightbox\" onclick=\"Zoom.lightboxAdd(".$zoom->_gallery->_images[$key]->_id.", 1, this);\" alt=\"\" /></a>&nbsp;\n"
                 . "\t\t</span>\n");
            }
            if ($zoom->_CONFIG['ecards']) {
                ?>
                <span class="top-full-image-ecard">
                <a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=ecard&amp;catid=".$zoom->_gallery->_id."&amp;key=".$key."&amp;PageNo=".$PageNo); ?>" onmouseover="return overlib('<?php echo _ZOOM_ECARD_SENDAS; ?>', CAPTION, '<?php echo _ZOOM_ECARD_SENDCARD; ?>');" onmouseout="return nd();">
                <img src="<?php echo $dir_prefix; ?>images/ecard.png" border="0" name="ecard" title="Image of ecard" alt="Image of ecard" /></a>
                </span>
                <?php
            } // end IF ecards
            ?>
            <div class="clr"></div>
            <?php
	    }
	}
	function showGenericPageHeader($use_tables = true, $section = 'Search', $Itemid, $dir_prefix) {
	    global $mosConfig_live_site, $mainframe, $zoom, $backend;
	    if (is_callable('appendPathWay', $mainframe)) {
			$mainframe->appendPathWay($section);
		}
	    if ($use_tables) {
	    	?>
    		<table border="0" cellspacing="0" cellpadding="0" width="100%">
    		<tr>
    		<td width="30" class="sectiontableheader"></td>
    		<td class="sectiontableheader" align="left" valign="middle">
    		<?php
    		if ($zoom->_CONFIG['mainscreen']) {
    			?>
    			<a class="pagenav" href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid); ?>">
    			<img src="<?php echo $dir_prefix; ?>images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN; ?>" border="0" />&nbsp;<?php echo _ZOOM_MAINSCREEN; ?>
    			</a> &gt; 
    			<?php 
    		}
			?>
			<span class="pagenav">&nbsp;<?php echo $section; ?></span>
			</td>
    		</tr>
    		</table>
    		<?php
	    } else {
	        ?>
            <h3 class="sectiontableheader">
            <?php
            if ($zoom->_CONFIG['mainscreen']) {
                ?>
                <a class="pagenav" href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid); ?>">
                <img src="<?php echo $dir_prefix; ?>images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN; ?>" border="0" />&nbsp;<?php echo _ZOOM_MAINSCREEN; ?>
                </a> &gt; 
                <?php
            }
            ?>
            <span class="pagenav">&nbsp;<?php echo $section; ?></span>
            </h3>
            <div class="clr"></div>
            <?php
	    }
	}
}
?>