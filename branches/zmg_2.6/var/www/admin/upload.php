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
| Filename: upload.php                                                |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:upload.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$Itemid = mosGetParam($_REQUEST,'Itemid');
$formtype = mosGetParam($_REQUEST,'formtype');
if ($zoom->_CONFIG['readEXIF'] && !(bool) ini_get('safe_mode')) {
	include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/JPEG.php");
	include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/EXIF.php");
	include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/Photoshop_IRB.php");
	include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/XMP.php");
	include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/Photoshop_File_Info.php");
}
$zoom->createProgressScript('upload');
?>
<script language="javascript" type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/components/com_zoom/lib/js/prototype.js"></script>
<script language="Javascript" type="text/javascript">
<!--
function submitCount() {
	document.count_form.submit();
	return false;
}
var disabled = false;

function disable(theForm, elmnt) {
	document.forms[theForm].elements[elmnt].disabled = true;
	disabled = true;
}
function enable(theForm, elmnt) {
	document.forms[theForm].elements[elmnt].disabled = false;
	disabled = false;
}
function toggleDisabled(theForm, elmnt) {
	if (disabled == true) {
		enable(theForm, elmnt);
	} else {
		disable(theForm, elmnt);
	}
}
// -->
</script>
<!-- Begin header -->
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<?php
if (!$zoom->_isBackend) {
?>
	<tr>
		<td align="center" width="100%"><a href="<?php echo ($zoom->_isBackend) ? "index2.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=admin" : sefReltoAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=admin"); ?>">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN;?>" border="0" />&nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN;?>
		</a>&nbsp; | &nbsp;
			<a href="<?php echo ($zoom->_isBackend) ? "index2.php?option=com_zoom&amp;page=mediamgr&amp;catid=".$catid."&amp;Itemid=".$Itemid : sefReltoAbs("index.php?option=com_zoom&amp;page=mediamgr&amp;catid=".$catid."&amp;Itemid=".$Itemid);?>">
				<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/back.png" alt="<?echo _ZOOM_BACK;?>" border="0" />&nbsp;&nbsp;<?php echo _ZOOM_BACK;?>
			</a>
		</td>
	</tr>
<?php
}
?>
<tr>
	<td align="left"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/admin/upload_f2.png" border="0" alt="<?php echo _ZOOM_HD_UPLOAD;?>" />&nbsp;<b><font size="4"><?php echo _ZOOM_HD_UPLOAD;?></font></b></td>
</tr>
<tr>
	<td height="10">&nbsp;</td>
</tr>
<tr>
	<td align="center" width="100%">
	  <?php 
        $tabs = new mosTabs(0);
        $tabs->startPane("modules-cpanel"); 
        $tabs->startTab(_ZOOM_UPLOAD_SINGLE ,"module19");
		
        include_once($mosConfig_absolute_path.'/components/com_zoom/www/admin/upl_single.php');
		
        $tabs->endTab(); 
        $tabs->startTab(_ZOOM_UPLOAD_MULTIPLE ,"module20");
        
		include_once($mosConfig_absolute_path.'/components/com_zoom/www/admin/upl_multiple.php');				
				
        $tabs->endTab(); 
        if ($zoom->_CONFIG['dragdrop'])
			{
                $tabs->startTab(_ZOOM_UPLOAD_DRAGNDROP ,"module21");        
				include_once($mosConfig_absolute_path.'/components/com_zoom/www/admin/upl_dragndrop.php');
		        $tabs->endTab(); 
		    }
		
        $tabs->startTab(_ZOOM_UPLOAD_SCANDIR ,"module22");
        	
		include_once($mosConfig_absolute_path.'/components/com_zoom/www/admin/upl_scan.php');
		
		$tabs->endTab(); 
        // start Tabbar
        $tabs->endPane("modules-cpanel");
		?>		
</tr>
<tr>
	<td height="10">&nbsp;</td>
</tr>
</table>
<script language="javascript" type="text/javascript">
<!--
<?php
// switch between single file and multiple files form.
switch ($formtype){
    case 'single':
        echo "tabPane1.setSelectedIndex(0);\n";
        break;
    case 'multiple':
        echo "tabPane1.setSelectedIndex(1);\n";
        break;
    case 'dragndrop':
        echo "tabPane1.setSelectedIndex(2);\n";
        break;
    case 'scan':
        if ($zoom->_CONFIG['dragdrop']) {
            echo "tabPane1.setSelectedIndex(3);\n";
        } else {
            echo "tabPane1.setSelectedIndex(2);\n";
        }
        break;
    default:
        echo "tabPane1.setSelectedIndex(0);\n";
        break;
}
?>
// -->
</script>
<?php
$zoom->hideProgress();
?>