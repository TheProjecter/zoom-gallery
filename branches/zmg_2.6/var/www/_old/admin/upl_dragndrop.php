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
| Filename: upl_dragndrop.php                                         |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:upl_dragndrop.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
if (!isset($catid)){
	echo "<p align=\"center\"><h3>"._ZOOM_INFO_CHECKCAT."</h3></p>";
}
$zoom->createFormControlScript("JUploadForm");
?>
<form name="choose_cat" method="post" action="<?php echo ($zoom->_isBackend) ? "index2.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=upload&amp;formtype=dragndrop" : sefReltoAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=upload&amp;formtype=dragndrop");?>">
    <div align="center">
	<?php
		echo "<p>"._ZOOM_FORM_INGALLERY.": \n";
		echo $zoom->createCatDropdown('catid', '<OPTION value="">---&nbsp;'._ZOOM_PICK.'&nbsp;---</OPTION>', 1, $catid).'</p>';
	?>
	</div>
</form>
<applet
		title="JUpload"
		name="JUpload"
		code="com.smartwerkz.jupload.classic.JUpload"
		codebase="."
		archive="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/admin/JUpload.jar,
				<?php echo $mosConfig_live_site;?>/components/com_zoom/lib/jupload/commons-codec-1.3.jar,
				<?php echo $mosConfig_live_site;?>/components/com_zoom/lib/jupload/commons-httpclient-3.0-rc4.jar,
				<?php echo $mosConfig_live_site;?>/components/com_zoom/lib/jupload/commons-logging.jar,
				<?php echo $mosConfig_live_site;?>/components/com_zoom/lib/jupload/skinlf/skinlf-6.2.jar"
		width="640"
		height="480"
		mayscript="mayscript"
		alt="JUpload by www.jupload.biz">

	<param name="Config" value="<?php echo $mosConfig_live_site;?>/components/com_zoom/lib/jupload/jupload.config.php?catid=<?php echo $catid; ?>">



 </applet>
 <form id="JUploadForm" name="JUploadForm" action="#">
    <table border="0" cellpadding="0" cellspacing="0">
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td colspan="2" align="center"><font color="red"><?php echo _ZOOM_A_MESS_NOJAVA; ?></font>
		</td>
	</tr>	
	<tr><td colspan="2">&nbsp;</td></tr>	
	<tr>
		<td><?php echo _ZOOM_NAME; ?>:&nbsp;</td>
		<td>
			<input type="text" name="dnd_name" size="50" value="<?php echo $zoom->_CONFIG['tempName'];?>" class="inputbox" />
		</td>
	</tr>	
	<tr><td colspan="2">&nbsp;</td></tr>	
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="checkbox" name="dnd_setFilename" value="1"<?php if($zoom->_CONFIG['autonumber']) echo " checked"; ?> />
			<?php echo _ZOOM_FORM_SETFILENAME; ?>
		</td>
	</tr>	
	<tr><td colspan="2">&nbsp;</td></tr>	
	<tr>
		<td valign="middle"><?php echo _ZOOM_KEYWORDS; ?>:&nbsp;</td>
		<td valign="middle">
			<input type="text" name="dnd_keywords" size="50" value="" class="inputbox" />
		</td>
	</tr>	
	<tr><td colspan="2">&nbsp;</td></tr>	
	<tr>
		<td><?php echo _ZOOM_DESCRIPTION;?>:&nbsp;</td>
    	<td>
        	<textarea class="inputbox" cols="50" rows="5" name="dnd_descr"><?php echo $zoom->_CONFIG['tempDescr']; ?></textarea>
        	<input type="hidden" name="dnd_mospath" value="<?php echo $mosConfig_absolute_path; ?>" />
        	<input type="hidden" name="dnd_uid" value="<?php echo $my->id; ?>" />
        	<input type="hidden" name="catid" value="<?php echo $catid; ?>" />
    	</td>
	</tr>
    </table>
</form>