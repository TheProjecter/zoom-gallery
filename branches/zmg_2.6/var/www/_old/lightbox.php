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
| Filename: lightbox.php                                              |
|                                                                     |
-----------------------------------------------------------------------
-----------------------------------------------------------------------
|                                                                     |
| Ok, what is a lightbox? According to wikipedia: "In photography, a  | 
| lightbox is a container with several lightbulbs and a pane of       |
| frosted glass on the top. It is used by professionals viewing       |
| translucent films, such as slides. This device was originally used  |
| to sort photographic plates with ease.                              |
|                                                                     |
| Generally, a lightbox uses light similar to daylight (5000 -        |
| 6000 K), has uniform light strength on the glass pane, and has      |
| adequate light strength (in order to not strain the vision).        |
|                                                                     |
| A lightbox may also refer to a folder used on stock photograpy      |
| websites to allow a user to organize digital photos. Photos can be  |
| assigned to a viewable lightbox folder by subject, for later        |
| convenience, or used to compile unrelated photos for a specific     |
| project layout. Lightboxes also allow graphic designers to show     |
| clients options for a project in one simple uncluttered folder."    |
| Source: http://en.wikipedia.org/wiki/Lightbox                       |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:lightbox.php 136 2007-02-25 21:51:04Z mikedeboer $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$PageNo = intval(trim(mosGetParam($_REQUEST,'PageNo')));
$action = trim(mosGetParam($_REQUEST,'action'));

$mainframe->setPageTitle(_ZOOM_LIGHTBOX);

ZMG_Template_Main::showGenericPageHeader($zoom->_CONFIG['viewtype'], _ZOOM_LIGHTBOX, $Itemid, $mosConfig_live_site.'/components/com_zoom/www/');

// get files into array...
if ($action == 'add') {
	$key = intval(trim(mosGetParam($_REQUEST,'key')));
	$type = intval(trim(mosGetParam($_REQUEST, 'lb_type')));
	if ($type == 1 || $type == 3) {
		$object_id = $zoom->_gallery->_images[$key]->_id;
	} elseif ($type == 2) {
		$object_id = $zoom->_gallery->_id;
	}
	if ($type == 3) {
		$type = 1;
		$url_params = "&page=view&catid=".$zoom->_gallery->_id."&key=".$key;
		if (!empty($PageNo)) {
			$url_params .= "&PageNo=".$PageNo;
		}
	} else {
		$url_params = "&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo;
	}
	if ($_SESSION['lightbox']->addItem($object_id, $type)) {
		mosRedirect(sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid.$url_params), _ZOOM_LIGHTBOX_ADDED);
	} else {
		mosRedirect(sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid.$url_params), _ZOOM_LIGHTBOX_NOTADDED);
	}
} elseif ($action == 'edit') {
	$lb_id = intval(trim(mosGetParam($_REQUEST,'lb_id')));
	$qty = intval(trim(mosGetParam($_REQUEST,'qty'))); 
	if ($_SESSION['lightbox']->editItem($lb_id, $qty)) {
		mosRedirect(sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo), _ZOOM_LIGHTBOX_EDITED);
	} else {
		mosRedirect(sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo), _ZOOM_LIGHTBOX_NOTEDITED);
	}
} elseif ($action == 'delete') {
	$lb_id = intval(trim(mosGetParam($_REQUEST,'lb_id')));
	if ($_SESSION['lightbox']->getNoOfItems() == 1) {
		$lightbox = $_SESSION['lightbox'];
		unset($_SESSION['lightbox'], $lightbox);
		mosRedirect(sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo), _ZOOM_LIGHTBOX_DEL);
	} elseif ($_SESSION['lightbox']->removeItem($lb_id)) {
		mosRedirect(sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo), _ZOOM_LIGHTBOX_DEL);
	} else {
		mosRedirect(sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo), _ZOOM_LIGHTBOX_NOTDEL);
	}
} elseif ($action == 'zip') {
	// tell the lightbox to output the zip-file...
	if (!$_SESSION['lb_checked_out'] && $_SESSION['lightbox']->getNoOfItems() > 0) {
	  	$_SESSION['lightbox']->createZipFile();
	  	$_SESSION['lb_checked_out'] = true;
	  	$lightbox = $_SESSION['lightbox'];
	  	unset($_SESSION['lightbox'], $lightbox);
	} else {
		?>
		<script language="JavaScript" type="text/JavaScript">
			alert("<?php echo html_entity_decode(_ZOOM_LIGHTBOX_NOZIP); ?>");
			location = "<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo); ?>";
		</SCRIPT>
		<?php
	}
} elseif ($action == 'playlist') {
	// create a playlist and launch the zOOm player...
	if ($_SESSION['lightbox']->getNoOfItems() > 0) {
		$counter = 0;
		$files = array();
		$file_prepend = $mosConfig_live_site."/".$zoom->_CONFIG['imagepath'];
		$artists = array();
		$titles = array();
		$params = '';
		foreach ($_SESSION['lightbox']->_items as $item) {
			$image = $item->getImage();
			// item could be just a gallery, check this...
			if (!empty($image)) {
			    $image->getInfo();
				if ($zoom->isAudio($image->_type)) {
					$files[] = $file_prepend.$image->getDir()."/".$image->_filename;
					$zoom->setGallery($image->_catid);
					$params .= ''.$image->_catid.','.$zoom->_gallery->getImageKey($image->_id).',';
					$counter++;
				}
			}
		}
		$params = substr($params,'',-1);
		//	mosRedirect(sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo), _ZOOM_LIGHTBOX_PLERROR);
		if ($counter == 0) {
			mosRedirect(sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo), _ZOOM_LIGHTBOX_NOAUDIO);
		} else {
			mosRedirect(sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo."&action=play&params=".$params), _ZOOM_LIGHTBOX_PLSUCCESS);
		}
	} else {
		mosRedirect(sefRelToAbs("index".$backend.".php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&catid=".$zoom->_gallery->_id."&PageNo=".$PageNo), _ZOOM_LIGHTBOX_NOITEMS);
	}
	
} elseif ($action == 'play') {
	?>
		<script language="javascript" type="text/javascript">
		function submitform(pressbutton){
			document.adminForm.action.value=pressbutton;
			try {
				document.adminForm.onsubmit();
				}
			catch(e){}
			document.adminForm.submit();
		} 
		function submitbutton(pressbutton, lb_id) {
			var form = document.adminForm;
			if (pressbutton == 'delete') {
				form.lb_id.value = lb_id;
			}
			submitform(pressbutton);
		}
				player = window.open('<?php echo $mosConfig_live_site;?>/components/com_zoom/www/player.php?params=<?php echo mosGetParam($_REQUEST,"params") ?>', 'zoomplayer', 'width=420, height=200, scrollbars=1');
				player.focus();
	</script>
	
	<h3><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/lightbox.png" alt="" border="0" />
	<?php echo _ZOOM_YOUR_LIGHTBOX; ?></h3>
	<br /><br />
	<form name="adminForm" method="post" action="<?php echo sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=lightbox"); ?>">
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="sectiontableheader">
		<td align="left" valign="middle" width="<?php echo $zoom->_CONFIG['size'];?>"><?php echo _ZOOM_LIGHTBOX_CATS; ?></td>
		<td align="left" valign="middle"><?php echo _ZOOM_LIGHTBOX_TITLEDESCR; ?></td>
		<td align="left" valign="middle"><?php echo _ZOOM_ACTION; ?></td>
	</tr>
	<?php
	// first, iterate over all the galleries in the current lightbox...
	$zoom->_counter = 0;
  	foreach ($_SESSION['lightbox']->_items as $item) {
  		if (isset($item)) {
  			if (isset($item->_gallery) && !empty($item->_gallery)) {
  				echo ("<tr>\n"
  				 . "\t<td align=\"left\" valign=\"middle\"><img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/folder.png\" alt=\"\" border=\"0\" /></td>\n"
  				 . "\t<td align=\"left\" valign=\"middle\">\n"
  				 . "\t\t".$item->_gallery->_name."<br />".$item->_gallery->_descr."\n"
  				 . "\t</td>\n"
  				 . "\t<td>\n"
  				 . "\t\t<button onclick=\"submitbutton('delete', ".$zoom->_counter.");\" onmouseover=\"return overlib('"._ZOOM_DELETE."', CAPTION, '"._ZOOM_ACTION."');\" onmouseout=\"return nd();\" class=\"button\">\n"
  				 . "\t\t<img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/delete.png\" alt=\"\" border=\"0\" name=\"delimgA".$zoom->_counter."\" /></button>"
  				 . "</td>\n"
  				 . "</tr>\n");
			}
		}
  		$zoom->_counter++;
  	}
	$zoom->_counter = 0;
	$audiofiles = 0;
	// NOW, list all images...
	foreach ($_SESSION['lightbox']->_items as $item) {
		if (isset($item)) {
			if (isset($item->_image) && !empty($item->_image)) {
				$item->_image->getInfo();
				$zoom->setGallery($item->_image->_catid);
				if ($zoom->isAudio($item->_image->_type)) {
					$audiofiles++;
				}
				echo ("<tr>\n"
				 . "\t<td align=\"left\" valign=\"middle\">n.a.&nbsp;</td>\n"
				 . "\t<td align=\"left\" valign=\"middle\">\n"
				 . "\t\t<img src=\"".$zoom->hotlinkImage($item->_image->_catid, '2', $item->_image->_id, null )."\" align=\"left\" hspace=\"3\" alt=\"\" border=\"0\" />\n"
				 . "\t\t".$item->_image->_name."<br />".$item->_image->_descr."\n"
				 . "\t</td>\n"
				 . "\t<td align=\"left\" valign=\"middle\">\n"
				 . "\t\t<button onclick=\"submitbutton('delete', ".$zoom->_counter.");\" onmouseover=\"return overlib('"._ZOOM_DELETE."', CAPTION, '"._ZOOM_ACTION."');\" onmouseout=\"return nd();\" class=\"button\">\n"
				 . "\t\t<img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/delete.png\" alt=\"\" border=\"0\" name=\"delimgB".$zoom->_counter."\" /></button>"
				 . "\t</td>\n"
				 . "</tr>\n");
			}
		}
		$zoom->_counter++;
	}
	?>
	</table><br />
	<center>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
		<input type="hidden" name="catid" value="<?php echo $catid;?>" />
		<input type="hidden" name="PageNo" value="<?php echo $PageNo;?>" />
		<input type="hidden" name="page" value="<?php echo $page;?>" />
		<input type="hidden" name="action" value="" />
		<input type="hidden" name="lb_id" value="" />
		<button onclick="submitbutton('zip');" class="button"><?php echo _ZOOM_LIGHTBOX_ZIPBTN;?></button>
		<?php 
		if ($audiofiles > 0) {
			echo "\t\t<button onclick=\"submitbutton('playlist');\" class=\"button\">"._ZOOM_LIGHTBOX_PLAYLISTBTN."</button>\n";
		}
		?>
	</center>
	</form>
	<?php
} elseif ($_SESSION['lightbox']->getNoOfItems() == 0) {
	?>
	<h3><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/lightbox.png" alt="" border="0" />
	<?php echo _ZOOM_YOUR_LIGHTBOX; ?></h3>
	<br /><br />
	<?php
	echo _ZOOM_LIGHTBOX_EMPTY;
} else {
	//no action is set, so view the contents of the lightbox...
	?>
	<script language="javascript" type="text/javascript">
		function submitform(pressbutton){
			document.adminForm.action.value=pressbutton;
			try {
				document.adminForm.onsubmit();
				}
			catch(e){}
			document.adminForm.submit();
		} 
		function submitbutton(pressbutton, lb_id) {
			var form = document.adminForm;
			if (pressbutton == 'delete') {
				form.lb_id.value = lb_id;
			}
			submitform(pressbutton);
		}
	</script>
	<h3><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/lightbox.png" alt="" border="0" />
	<?php echo _ZOOM_YOUR_LIGHTBOX; ?></h3>
	<br /><br />
	<form name="adminForm" method="post" action="<?php echo sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=lightbox"); ?>">
	<table border="0" cellspacing="0" cellpadding="3" width="100%">
	<tr class="sectiontableheader">
		<td align="left" valign="middle" width="<?php echo $zoom->_CONFIG['size'];?>"><?php echo _ZOOM_LIGHTBOX_CATS; ?></td>
		<td align="left" valign="middle"><?php echo _ZOOM_LIGHTBOX_TITLEDESCR; ?></td>
		<td align="left" valign="middle"><?php echo _ZOOM_ACTION; ?></td>
	</tr>
	<?php
	// first, iterate over all the galleries in the current lightbox...
	$zoom->_counter = 0;
  	foreach ($_SESSION['lightbox']->_items as $item) {
  		if (isset($item)) {
  			if (isset($item->_gallery) && !empty($item->_gallery)) {
  				echo ("<tr>\n"
  				 . "\t<td align=\"left\" valign=\"middle\"><img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/folder.png\" alt=\"\" border=\"0\" /></td>\n"
  				 . "\t<td align=\"left\" valign=\"middle\">\n"
  				 . "\t\t".$item->_gallery->_name."<br />".$item->_gallery->_descr."\n"
  				 . "\t</td>\n"
  				 . "\t<td>\n"
  				 . "\t\t<button onclick=\"submitbutton('delete', ".$zoom->_counter.");\" onmouseover=\"return overlib('"._ZOOM_DELETE."', CAPTION, '"._ZOOM_ACTION."');\" onmouseout=\"return nd();\" class=\"button\">\n"
  				 . "\t\t<img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/delete.png\" alt=\"\" border=\"0\" name=\"delimgA".$zoom->_counter."\" /></button>"
  				 . "</td>\n"
  				 . "</tr>\n");
			}
		}
  		$zoom->_counter++;
  	}
	$zoom->_counter = 0;
	$audiofiles = 0;
	// NOW, list all images...
	foreach ($_SESSION['lightbox']->_items as $item) {
		if (isset($item)) {
			if (isset($item->_image) && !empty($item->_image)) {
				$item->_image->getInfo();
				$zoom->setGallery($item->_image->_catid);
				if ($zoom->isAudio($item->_image->_type)) {
					$audiofiles++;
				}
				echo ("<tr>\n"
				 . "\t<td align=\"left\" valign=\"middle\">n.a.&nbsp;</td>\n"
				 . "\t<td align=\"left\" valign=\"middle\">\n"
				 . "\t\t<img src=\"".$zoom->hotlinkImage($item->_image->_catid, '2', $item->_image->_id, null )."\" align=\"left\" hspace=\"3\" alt=\"\" border=\"0\" />\n"
				 . "\t\t".$item->_image->_name."<br />".$item->_image->_descr."\n"
				 . "\t</td>\n"
				 . "\t<td align=\"left\" valign=\"middle\">\n"
				 . "\t\t<button onclick=\"submitbutton('delete', ".$zoom->_counter.");\" onmouseover=\"return overlib('"._ZOOM_DELETE."', CAPTION, '"._ZOOM_ACTION."');\" onmouseout=\"return nd();\" class=\"button\">\n"
				 . "\t\t<img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/delete.png\" alt=\"\" border=\"0\" name=\"delimgB".$zoom->_counter."\" /></button>"
				 . "\t</td>\n"
				 . "</tr>\n");
			}
		}
		$zoom->_counter++;
	}
	?>
	</table><br />
	<center>
		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
		<input type="hidden" name="catid" value="<?php echo $catid;?>" />
		<input type="hidden" name="PageNo" value="<?php echo $PageNo;?>" />
		<input type="hidden" name="page" value="<?php echo $page;?>" />
		<input type="hidden" name="action" value="" />
		<input type="hidden" name="lb_id" value="" />
		<button onclick="submitbutton('zip');" class="button"><?php echo _ZOOM_LIGHTBOX_ZIPBTN;?></button>
		<?php 
		if ($audiofiles > 0) {
			echo "\t\t<button onclick=\"submitbutton('playlist');\" class=\"button\">"._ZOOM_LIGHTBOX_PLAYLISTBTN."</button>\n";
		}
		?>
	</center>
	</form>
	<?php
}
?>