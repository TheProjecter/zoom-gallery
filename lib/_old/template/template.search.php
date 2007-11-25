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
| Filename: template.search.php                                       |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:template.search.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @subpackage Template
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class ZMG_Template_Search {
	function showPageHeader($use_tables = true, $section = 'Search', $Itemid, $dir_prefix) {
	    global $mosConfig_live_site, $zoom, $backend;
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