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
| Filename: ecard.php                                                 |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:ecard.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once($mosConfig_absolute_path . "/components/com_zoom/lib/template/template.view.php");

// Get the posted variables...
$task = trim(mosGetParam($_REQUEST, 'task', ''));
$submit = trim(mosGetParam($_REQUEST, 'submit'));
$key = intval(trim(mosGetParam($_REQUEST, 'key', -1)));
if ($task == 'send' && !empty($submit)) {
    $mainframe->setPageTitle(_ZOOM_ECARD_SENDCARD);
    
	// Save data to dbase & send an e-mail to the friend...
	// Get the image with the corresponding key...
	$zoom->_gallery->_images[$key]->getInfo();
	$to_name = trim(mosGetParam($_REQUEST, 'to_name', ''));
	$from_name = trim(mosGetParam($_REQUEST, 'from_name', ''));
	$to_email = trim(mosGetParam($_REQUEST, 'to_email', ''));
	$from_email = trim(mosGetParam($_REQUEST, 'from_email', ''));
	$message = trim(mosGetParam($_REQUEST, 'message', ''));
	$imgid = $zoom->_gallery->_images[$key]->_id;
	$zoom->setEcard();
	?>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td width="30" class="<?php echo $zoom->_tabclass[1]; ?>">&nbsp;</td>
		<td class="<?php echo $zoom->_tabclass[1]; ?>" align="left" valign="top">
			<a href="<?php echo sefRelToAbs('index.php?option=com_zoom&amp;Itemid='.$Itemid); ?>">
			<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN;?>" border="0" />&nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN;?>
			</a> &gt; <a href="<?php echo sefRelToAbs('index.php?option=com_zoom&amp;Itemid='.$Itemid.'&amp;catid='.$zoom->_gallery->_id); ?>"><?php echo $zoom->_gallery->_name;?>
			</a> &gt; <strong><a href="<?php echo sefRelToAbs('index.php?option=com_zoom&amp;Itemid='.$Itemid.'&amp;page=view&amp;catid='.$zoom->_gallery->_id.'&amp;key='.$key); ?>"><?php echo (empty($zoom->_gallery->_images[$key]->_name)) ? $zoom->_gallery->_images[$key]->_filename : $zoom->_gallery->_images[$key]->_name;?></a></strong>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="left"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/ecard_big.png" border="0" alt="<?php echo _ZOOM_ECARD_SENDAS;?>" />&nbsp;<b><font size="4"><?php echo _ZOOM_ECARD_SENDAS;?></font></b></td>
	</tr>
	</table>
	<br /><br /><center><h5>
	<?php
	if ($zoom->ecard->save($imgid, $to_name, $from_name, $to_email, $from_email, $message)) {
		if ($zoom->ecard->send()) {
			echo (_ZOOM_ECARD_SUCCESS."<br />"
			 . "<a href=\"". sefReltoAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=ecard&amp;task=viewcard&amp;ecdid=".$zoom->ecard->_id) ."\"> "._ZOOM_ECARD_CLICKHERE."</a>");
		} else {
			echo _ZOOM_ECARD_ERROR." $to_email!";
		}
	} else {
		echo _ZOOM_ECARD_ERROR." $to_email!";
	}
	echo "\t</h5></center>\n";
} elseif ($task == 'viewcard') {
	// Delete overdue records of eCards from the database, before anyone can see them...
	$now = date("Y-m-d");
	$database->setQuery("DELETE FROM #__zoom_ecards WHERE end_date <= $now");
	$database->query();
	$ecdid = trim(mosGetParam($_REQUEST, 'ecdid'));
	$back = (bool)trim(mosGetParam($_REQUEST, 'back', false));
	$zoom->setEcard($ecdid);
	if ($zoom->ecard->getInfo()) {
		$zoom->ecard->_image->getInfo();
		$zoom->setGallery($zoom->ecard->_image->_catid);
		?>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td width="30" class="<?php echo $zoom->_tabclass[1]; ?>">&nbsp;</td>
			<td class="<?php echo $zoom->_tabclass[1]; ?>" align="left" valign="top">
				<a href="<?php echo sefRelToAbs('index.php?option=com_zoom&amp;Itemid='.$Itemid); ?>">
				<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN; ?>" border="0" />&nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN; ?>
				</a> &gt; <a href="<?php echo sefRelToAbs('index.php?option=com_zoom&amp;Itemid='.$Itemid.'&amp;catid='.$zoom->_gallery->_id); ?>"><?php echo $zoom->_gallery->_name; ?></a>
			</td>
		</tr>
		</table>
		<center>
		<?php
		if ($back) {
			// begin BACK HTML...
			?>
			<center>
				<a href="<?php echo sefRelToAbs('index.php?option=com_zoom&amp;Itemid='.$Itemid.'&amp;page=ecard&amp;task=viewcard&amp;ecdid='.$ecdid); ?>" onmouseover="return overlib('<?php echo _ZOOM_ECARD_TURN2; ?>');" onmouseout="return nd();">
				<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/ecard_turn.png" border="0" />
			</center>
			<table width="400" border="0" cellspacing="0" cellpadding="0" background="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/ecard_back.png" height="250">
			<tr height="250">
				<td align="center" valign="middle" width="198" height="250">
				    <center>
					<?php echo $zoom->ecard->getMessage();?>
					</center>
				</td>
				<td width="16" height="250"></td>
				<td height="250">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" height="250">
					<tr height="69">
						<td align="center" valign="middle" height="69"></td>
					</tr>
					<tr height="55">
						<td align="center" valign="middle" height="55">
							<center>
							<?php echo _ZOOM_ECARD_SENDER;?>
							</center>
						</td>
					</tr>
					<tr height="25">
						<td align="center" valign="middle" height="25">
							<center>
							<?php echo $zoom->ecard->getName("from");?>
							</center>
						</td>
					</tr>
					<tr height="30">
						<td align="center" valign="bottom" height="30">
							<center>
							<?php echo $zoom->ecard->getEmail("from");?>
							</center>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
			<?php
		} else {
			$img_path = $zoom->hotlinkImage($zoom->ecard->_image->_catid, '0', $zoom->ecard->_image->_id, null );
			//$img_path = $mosConfig_live_site."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$zoom->ecard->_image->_viewsize;
			// begin FRONT HTML...
			echo ("<a href=\"".sefRelToAbs('index.php?option=com_zoom&amp;Itemid='.$Itemid.'&amp;page=ecard&amp;task=viewcard&amp;ecdid='.$ecdid.'&amp;back=1')."\" onmouseover=\"return overlib('"._ZOOM_ECARD_TURN."');\" onmouseout=\"return nd();\">\n"
			 . "\t<img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/ecard_turn.png\" border=\"0\" alt=\"\" />\n"
			 . "</a><br />\n");
			if ($zoom->isImage($zoom->ecard->_image->_type)) {
				if (isset($destWidth) && isset($destHeight)) {
					?>
					<img src="<?php echo $img_path;?>" alt="" border="1" name="zImage" width="<?php echo $destWidth;?>" height="<?php echo $destHeight;?>" />
					<?php
				} else {
					?>
					<img src="<?php echo $img_path;?>" alt="" border="1" name="zImage" />
					<?php
				}
			} elseif ($zoom->isDocument($zoom->ecard->_image->_type)) {
			    ZMG_Template_View::showTypeDocument($zoom->hotlinkImage($zoom->ecard->_image->_catid, '2', $zoom->ecard->_image->_id, null ), false);
			} elseif ($zoom->isMovie($zoom->ecard->_image->_type)) {
			    ZMG_Template_View::showTypeMovie($zoom->ecard->_image, $img_path);
			} elseif ($zoom->isAudio($zoom->ecard->_image->_type)) {
			    //TODO: get imageKey
			    ZMG_Template_View::showTypeAudio($zoom->ecard->_image, 1, $img_path, false);
			}
		}
	} else {
		?>
		<script language="javascript" type="text/javascript">
		<!--
		alert('<?php echo _ZOOM_ECARD_ECARDEXPIRED ;?>');
		location = '<?php echo sefRelToAbs("index.php");?>';
		//-->
		</script>
		<?php
	}
} else {
    $mainframe->setPageTitle(_ZOOM_ECARD_SENDCARD);
    
	$zoom->_gallery->_images[$key]->getInfo();
	// Display form with image and userfields...
	$img_path = $zoom->hotlinkImage($catid, '0', $zoom->_gallery->_images[$key]->_id, null );
	//$img_path = $mosConfig_live_site."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$zoom->_gallery->_images[$key]->_viewsize;
	?>
	<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
	<td width="30" class="sectiontableheader"></td>
    	<td class="sectiontableheader" align="left" valign="middle">
    	<?php
    	if ($zoom->_CONFIG['mainscreen']) {
    		?>
    		<a class="pagenav" href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid); ?>">
    		<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN; ?>" border="0" />&nbsp;<?php echo _ZOOM_MAINSCREEN; ?>
    		</a> &gt; 
    		<?php
    	}
    	$medium_name = (empty($zoom->_gallery->_images[$key]->_name)) ? $zoom->_gallery->_images[$key]->_filename : $zoom->_gallery->_images[$key]->_name;
    	if (is_callable('appendPathWay', $mainframe)) {
        	$mainframe->appendPathWay("<a href=\"".sefRelToAbs('index.php?option=com_zoom&amp;Itemid='.$Itemid.'&amp;catid='.$zoom->_gallery->_id)."\">"
    	     . $zoom->_gallery->_name
    	     . "</a>");
    	    $mainframe->appendPathWay("<a href=\"".sefRelToAbs('index.php?option=com_zoom&amp;Itemid='.$Itemid.'&amp;page=view&amp;catid='.$zoom->_gallery->_id.'&amp;key='.$key)."\">"
		     . $medium_name
		     . "</a>");
        }
    	?>
    	<a class="pagenav" href="<?php echo sefRelToAbs('index.php?option=com_zoom&amp;Itemid='.$Itemid.'&amp;catid='.$zoom->_gallery->_id); ?>">
    	    <?php echo $zoom->_gallery->_name; ?>
		</a> &gt; <strong>
		<a class="pagenav" href="<?php echo sefRelToAbs('index.php?option=com_zoom&amp;Itemid='.$Itemid.'&amp;page=view&amp;catid='.$zoom->_gallery->_id.'&amp;key='.$key); ?>">
		    <?php echo $medium_name; ?>
		</a></strong>
    	</td>
	</tr>
	</table>
	<div align="center">
	<br />
	<?php
	if ($zoom->isImage($zoom->_gallery->_images[$key]->_type)) {
		if (isset($destWidth) && isset($destHeight)) {
			?>
			<img src="<?php echo $img_path;?>" alt="" border="1" name="zImage" width="<?php echo $destWidth;?>" height="<?php echo $destHeight;?>" />
			<?php
		} else {
			?>
			<img src="<?php echo $img_path;?>" alt="" border="1" name="zImage" />
			<?php
		}
	} elseif ($zoom->isDocument($zoom->_gallery->_images[$key]->_type)) {
	    ZMG_Template_View::showTypeDocument($zoom->_gallery->_images[$key]->_thumbnail, false);
	} elseif ($zoom->isMovie($zoom->_gallery->_images[$key]->_type)) {
	    ZMG_Template_View::showTypeMovie($zoom->_gallery->_images[$key], $img_path);
	} elseif ($zoom->isAudio($zoom->_gallery->_images[$key]->_type)) {
	    ZMG_Template_View::showTypeAudio($zoom->_gallery->_images[$key], $key, $img_path, false);
	}
	?>
	<br />
	<form name="ecard" method="post" action="<?php echo sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=ecard"); ?>" onSubmit="return validateCard(this)">
	<table border="0" cellspacing="0" cellpadding="3">
	<tr>
		<td nowrap="nowrap"><?php echo _ZOOM_ECARD_YOURNAME; ?>:</td>
		<td><input name="from_name"	type="text"	class="inputbox" /></td>
	</tr>
	<tr>
		<td nowrap="nowrap"><?php echo _ZOOM_ECARD_YOUREMAIL; ?>:</td>
		<td><input name="from_email" type="text" class="inputbox" /></td>
	</tr>
	<tr>
		<td nowrap="nowrap"><?php echo _ZOOM_ECARD_FRIENDSNAME; ?>:</td>
		<td><input name="to_name" type="text" class="inputbox" /></td>
	</tr>
	<tr>
		<td nowrap="nowrap"><?php echo _ZOOM_ECARD_FRIENDSEMAIL; ?>:</td>
		<td><input name="to_email" type="text" class="inputbox" /></td>
	</tr>
	<tr>
		<td nowrap="nowrap" valign="top"><?php echo _ZOOM_ECARD_MESSAGE; ?>:</td>
		<td><textarea name="message" id="message" class="inputbox" rows=3 cols=25></textarea></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="submit" value="<?php echo _ZOOM_ECARD_SENDCARD; ?>" class="button" />
		</td>
	</tr>
	</table>
	<input type="hidden" name="option" value="com_zoom" />
	<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
	<input type="hidden" name="page" value="ecard" />
	<input type="hidden" name="task" value="send" />
	<input type="hidden" name="catid" value="<?php echo $zoom->_gallery->_id; ?>" />
	<input type="hidden" name="key" value="<?php echo $key; ?>" />
	</form>
	</div>
	<?php
}