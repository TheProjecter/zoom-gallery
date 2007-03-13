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
| Filename: galleryshow.php                                           |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:galleryshow.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once($mosConfig_absolute_path . "/components/com_zoom/lib/template/template.gallery.php");

$mainframe->addCustomHeadTag('<link href="'.$mosConfig_live_site.'/components/com_zoom/etc/zoom.css" rel="stylesheet" media="screen" type="text/css" />');
$mainframe->addCustomHeadTag('<script language="javascript" type="text/javascript" src="'.$mosConfig_live_site.'/components/com_zoom/lib/js/prototype.js"></script>');
$mainframe->addCustomHeadTag('<script language="javascript" type="text/javascript" src="'.$mosConfig_live_site.'/components/com_zoom/lib/js/scriptaculous.js?load=mm,effects"></script>');

if ($zoom->_CONFIG['popUpImages'] == 2 ) {
    $mainframe->addCustomHeadTag('<script language="javascript" type="text/javascript">var livesite = "'.$mosConfig_live_site.'"</script>');
	$mainframe->addCustomHeadTag('<script language="javascript" type="text/javascript" src="'.$mosConfig_live_site.'/components/com_zoom/lib/js/lightbox.js"></script>');
	$mainframe->addCustomHeadTag('<link href="'.$mosConfig_live_site.'/components/com_zoom/etc/lightbox.css" rel="stylesheet" media="screen" type="text/css" />');
}

if (!$catid) {
	//No gallery selected, show main screen
	$zoom->createSubmitScript('browse');
	
	$mainframe->setPageTitle($zoom->_CONFIG['zoom_title']);
	
	ZMG_Template_Gallery::showHeader($zoom->_CONFIG['viewtype'], $Itemid, $zoom->_CONFIG['zoom_title'], $zoom->_CONFIG['displaylogo'], 
	   $zoom->_CONFIG['showSearch'], $zoom->_CONFIG['showKeywords'], $zoom->_getKeywordsList());

	//Get every category from the database and echo it on the screen
	$zoom->_counter = 0;
	$orderMethod = $zoom->getCatOrderMethod();
	if ($zoom->_isAdmin) {
		$database->setQuery("SELECT catid FROM #__zoom WHERE subcat_id=0 AND pos=0 ORDER BY ".$orderMethod);
	} else {
		$database->setQuery("SELECT catid FROM #__zoom WHERE subcat_id=0 AND pos=0 AND published=1 ORDER BY ".$orderMethod);
	}
	$zoom->_result = $database->query();
	$galleries = array();
	while ($row = mysql_fetch_object($zoom->_result)) {
		$galleries[] = new gallery($row->catid, true);
	}
	ZMG_Template_Gallery::showGalleries($galleries, $zoom->_CONFIG['viewtype'], $Itemid, $zoom->_CONFIG['catcolsno'],
        $zoom->_CONFIG['galleryPrefix'], $zoom->_CONFIG['catImg'], $zoom->_CONFIG['showMetaBox']);
	
    ZMG_Template_Gallery::showFooter($zoom->_CONFIG['viewtype'], $Itemid, $zoom);
} else {
	$catpass = mosGetParam($_REQUEST, 'catpass', '');
	if (empty($catpass) && strlen($zoom->_gallery->_password) > 0 && !$zoom->_isAdmin && !$zoom->EditMon->isEdited($zoom->_gallery->_id, 'pass')) {
		$valid = false;
		
		ZMG_Template_Gallery::showPasswordForm($Itemid, $catid, $zoom->_tabclass);
		
	} elseif (!empty($catpass)) {
		if ($zoom->_gallery->checkPassword($catpass)) {
			$valid = true;
		} else {
			?>
			<script language="javascript" type="text/javascript">
				//<!--
				alert('<?php echo html_entity_decode(_ZOOM_PASS_INNCORRECT);?>');
				location = '<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$zoom->_gallery->_subcat_id);?>';
				//-->
			</script>
			<?php
		}
	} else {
		$valid = true;
	}
	if ($valid && $zoom->_gallery->isMember()) {
		$imagedir = $zoom->_gallery->_dir;
		$parent = $zoom->_gallery->getSubcatName();
		
		$mainframe->setPageTitle($zoom->revnumericentities($zoom->_gallery->_name));
	
		$i = 1;
		$startRow = 0;
	
		//Set the page no
		$PageNo = intval(trim(mosGetParam($_REQUEST, 'PageNo', 1)));
		if ($PageNo != 1) {
			$startRow = ($PageNo - 1) * $zoom->_CONFIG['PageSize'];
		}
		
		
		//Offset $startRow by images removed in past pages
		$offset = 0;
		if ($PageNo != 1) {
			for ($counterX = 0; $counterX <= $startRow; $counterX++) {
				$image = $zoom->_gallery->_images[$counterX];
				$image->getInfo();
				if (!$image->isMember()) {
					$offset++;
				}
			}
		$startRow = $startRow + $offset;
		}
		
		//Total of record
		$RecordCount = $zoom->_gallery->getNumOfImages();//Number of files in gallery
		$endRow = $startRow + $zoom->_CONFIG['PageSize'] -1;
		if ($endRow >= $RecordCount) {
			$endRow = $RecordCount - 1;
		}
		
		//Total of viewable records
		$ViewRecordCount = $zoom->_gallery->getNumOfImagesUSER();//Number of files in gallery that a user can view
		$ViewendRow = $startRow + $zoom->_CONFIG['PageSize'] -1;
		if ($ViewendRow >= $ViewRecordCount) {
			$ViewendRow = $ViewRecordCount - 1;
		}
		
		
		//Set Maximum Page
		$MaxPage = ceil($ViewRecordCount / $zoom->_CONFIG['PageSize']);
		
		
		//Set the counter start
		$CounterStart = 1;
		//Counter End
		$CounterEnd = $MaxPage;
		
		$zoom->prepareAjax(array("lb_title" => _ZOOM_LIGHTBOX));
		?>
		<script language="javascript" type="text/javascript">
			function submitform(pressbutton){
				document.adminForm.theButton.value=pressbutton;
				try {
				document.adminForm.onsubmit();
				} catch(e) {}
				document.adminForm.submit();
			} 
			function submitbutton(pressbutton, theKey) {
				var form = document.adminForm;
				form.key.value = theKey;
				if (pressbutton == 'cancel') {
					submitform( pressbutton );
					return;
				}
				if (pressbutton == 'lightbox') {
					form.page.value = 'lightbox';
					form.action.value = 'add';
				}
				if (pressbutton == 'edit') {
					form.page.value = 'editimg';
				}
				if (pressbutton == 'delete') {
				    form.action.value = 'delimg';
				}
				submitform(pressbutton);
			}
		</script>
		<div id="zoomgallery" class="catid-<?php echo $catid; ?>">
		<form name="adminForm" action="<?php echo sefReltoAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid); ?>" method="post">
		<table border="0" cellspacing="0" cellpadding="3" width="100%">
		<tr>
			<?php
			if ($zoom->_CONFIG['mainscreen']) {
			?>	
			<td width="30" class="sectiontableheader"></td>
			<td class="sectiontableheader">
				<a class="pagenav" href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid);?>">
				<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/home.gif" alt="<?php echo _ZOOM_MAINSCREEN;?>" border="0" />&nbsp;<?php echo _ZOOM_MAINSCREEN;?>
				</a> &gt;
				<?php
				if ($zoom->_gallery->_pos == 1) {
				    echo " <a class=\"pagenav\" href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$zoom->_gallery->_subcat_id)."\">".$parent."</a> &gt; ";
				    if (is_callable('appendPathWay', $mainframe)) {
                    	$mainframe->appendPathWay("<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$zoom->_gallery->_subcat_id)."\">".$parent."</a>");
                    }
				} elseif ($zoom->_gallery->_pos >= 2) {
				    echo "... &gt; <a class=\"pagenav\" href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$zoom->_gallery->_subcat_id)."\">".$parent."</a> &gt; ";
				    if (is_callable('appendPathWay', $mainframe)) {
				        $mainframe->appendPathWay("...");
                    	$mainframe->appendPathWay("<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$zoom->_gallery->_subcat_id)."\">".$parent."</a>");
                    }
				}
				echo $zoom->_gallery->_name;
				if (is_callable('appendPathWay', $mainframe)) {
					$mainframe->appendPathWay($zoom->_gallery->_name);
				}
				if (!$zoom->_gallery->_published) {
					echo " <span class=\"zmg-unpublished\">(unpublished)</span>";
				}
				if ($zoom->_isAdmin) {
					echo ' | <a class="pagenav" href="'.sefRelToAbs('index.php?option=com_zoom&amp;Itemid=' .$Itemid. '&amp;page=admin').'">'._ZOOM_ADMINSYSTEM.'</a>';
				} elseif ($zoom->privileges->hasPrivileges()) {
					echo ' | <a class="pagenav" href="'.sefRelToAbs('index.php?option=com_zoom&amp;Itemid=' .$Itemid. '&amp;page=admin').'">'._ZOOM_USERSYSTEM.'</a>';
				}
			}
			?>	
			</td>
			<?php
			if ($zoom->_CONFIG['lightbox'] && $zoom->_gallery->getNumOfImages() > 0) {
				?>
				<td align="right" class="sectiontableheader">
					<div align="right">
					<a href="javascript:void(0);" onmouseover="return overlib('<?php echo _ZOOM_LIGHTBOX_GALLERY;?>', CAPTION, '<?php echo _ZOOM_LIGHTBOX;?>');" onmouseout="return nd();">
					   <img src="<?php echo $mosConfig_live_site ?>/components/com_zoom/www/images/lightbox.png" border="0" name="lightbox" onclick="Zoom.lightboxAdd(<?php echo $zoom->_gallery->_id; ?>, 2, this);" alt="" />
				    </a>
					</div>
				</td>
				<?php
			}
			?>
		</tr>
		</table>
		<div align="center">
    		<table border="0" cellspacing="0" cellpadding="3" width="100%">
    			<tr>
    				<td colspan="<?php echo $zoom->_CONFIG['columnsno']?>" align="center">
						<div align="center">
							<h2><?php echo $zoom->_gallery->_name;?></h2>
								<?php
								if ($zoom->_CONFIG['descrInGal']) {
								    echo $zoom->_gallery->_descr."<br />";
								}								
								if ($zoom->_CONFIG['mediafound']) {
									if ($ViewRecordCount != 0) {
										echo sprintf(_ZOOM_IMGFOUND, $ViewRecordCount, $PageNo, $MaxPage);
									} elseif (!$zoom->_gallery->_hideMsg) {
										echo "<span class=\"small\">"._ZOOM_NOPICS."</span>";
									}
								}
								?>
						</div>
					</td>
				</tr>
				</table>
    			<?php
    			ZMG_Template_Gallery::showSubGalleries($zoom->_gallery->_subcats, $zoom->_CONFIG['viewtype'], $Itemid, $zoom->_CONFIG['catcolsno'],
        		$zoom->_CONFIG['galleryPrefix'], $zoom->_CONFIG['catImg'], $zoom->_CONFIG['showMetaBox']);	
    			?>
		</div>
		<table border="0" cellpadding="3" cellspacing="0" width="100%">
		<tr>
		<?php
		$columnwidth = round(100 / $zoom->_CONFIG['columnsno']);
		$inforow = "";
		$zoom->_counter = 0;
		$counter2 = $startRow;
		//$loop_end = count($zoom->_gallery->getNumOfImages());
		for ($counter = $startRow; $counter2 <= $ViewendRow; $counter++) {
			if (isset($zoom->_gallery->_images[$counter])) {
				$image = $zoom->_gallery->_images[$counter];
				$image->getInfo();
				if ($image->isMember()){
					// Basic and original multi-column compact style layout...
					$features =  "\t\t<td align=\"center\" valign=\"bottom\" width=\"".$columnwidth."%\" nowrap=\"nowrap\">\n";
					if ($zoom->isImage($image->_type) && !$image->_isBroken) {
						$size = $zoom->platform->getimagesize($zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$image->_filename);
					} else {
						$size = array(48, 48);
					}
					if($zoom->_CONFIG['lightbox']) {
						$features .= ("\t\t<a href=\"javascript:void(0);\" onmouseover=\"return overlib('"._ZOOM_LIGHTBOX_ITEM."', CAPTION, '"._ZOOM_LIGHTBOX."');\" onmouseout=\"return nd();\" class=\"\">\n"
						 . "\t\t<img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/lb_small.png\" border=\"0\" name=\"lb_small".$counter."\" onclick=\"Zoom.lightboxAdd(".$image->_id.", 1, this);\" alt=\"\" />\n"
						 . "\t\t</a>");
				    }
					if ($zoom->_isAdmin || ($zoom->privileges->hasPrivilege('priv_delmedium') && ($image->_uid == $zoom->currUID | $zoom->_gallery->isShared()))) {
						$features .= ("<a onclick=\"submitbutton('delete', ".$counter.");\" onmouseover=\"return overlib('"._ZOOM_DELETE."', CAPTION, '"._ZOOM_ACTION."');\" onmouseout=\"return nd();\" class=\"button\"><img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/delete.png\" border=\"0\" name=\"delimg".$counter."\" alt=\"\" /></a>");
					}
					if ($zoom->_isAdmin || ($zoom->privileges->hasPrivilege('priv_editmedium') && ($image->_uid == $zoom->currUID | $zoom->_gallery->isShared()))) {
						$features .= ("<a onclick=\"submitbutton('edit', ".$counter.");\" onmouseover=\"return overlib('"._ZOOM_BUTTON_EDIT."', CAPTION, '"._ZOOM_ACTION."');\" onmouseout=\"return nd();\" class=\"button\"><img src=\"".$mosConfig_live_site."/components/com_zoom/www/images/edit.png\" border=\"0\" name=\"editimg".$counter."\" alt=\"\" /></a>\n");
					}
					if ($zoom->_CONFIG['lightbox'] || $zoom->_isAdmin || (($zoom->privileges->hasPrivilege('priv_delmedium') || $zoom->privileges->hasPrivilege('priv_editmedium')) && $image->_uid == $zoom->currUID)) {
						$features .= "<br />\n";
					}
					echo $features;
					$descr = $zoom->removeTags($image->_descr);
					
					ZMG_Template_Gallery::showMediumListButton($zoom->_CONFIG['viewtype'], $Itemid, $PageNo,
					    $counter, $columnwidth, $size, $inforow, $image);
					
					//Counter to count the number of rows...
					$zoom->_counter++;
					$i++;
					if ($zoom->_counter % $zoom->_CONFIG['columnsno'] == 0) { 
						echo "</tr><tr>\n";
						$inforow .= "\t\t</tr><tr>\n";
						echo $inforow;
						$inforow = "";
					} elseif ($counter2 == $endRow && $zoom->_counter % $zoom->_CONFIG['columnsno'] != 0) {
						$remainder = $zoom->_CONFIG['columnsno'] - ($zoom->_counter % $zoom->_CONFIG['columnsno']);
						for ($x = 0; $x < $remainder; $x++) {
							echo "<td>&nbsp;</td>\n";
							$inforow .= "<td>&nbsp;</td>\n";
						}
						$inforow .= "\t\t</tr><tr>\n";
						echo "<td>&nbsp;</td></tr><tr>\n";
						echo $inforow;
					}
					$counter2++;
				}// END if isMember()
			}
		}// END for loop images.
		?>
		<td>&nbsp;</td></tr>
		<tr>
			<td colspan="<?php echo $zoom->_CONFIG['columnsno']; ?>" align="center">
			<br />
			<div align="center">
			<?php
			//Print First & Previous Link if necessary
			if ($PageNo != 1) {
				$PrevStart = $PageNo - 1;
				echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=$catid&amp;PageNo=1")."\">"._ZOOM_FIRST." </a>: ";
				echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=$catid&amp;PageNo=$PrevStart")."\">"._ZOOM_PREVIOUS." &lt;&lt; </a>\n";
			}
			$c = 0;
			//Print Page No
			for ($c = $CounterStart; $c <= $CounterEnd; $c++) {
				if($c < $MaxPage){
					if ($c == $PageNo) {
						if ($c % $RecordCount == 0) {
							echo "<u><strong>$c</strong></u> ";
						} else {
							echo "<u><strong>$c</strong></u> | ";
						}
					} elseif ($c % $RecordCount == 0) {
						echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=$catid&amp;PageNo=$c")."\"><strong>$c</strong></a> ";
					} else {
						echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=$catid&amp;PageNo=$c")."\"><strong>$c</strong></a> | ";
					}
				} else {
					if ($PageNo == $MaxPage) {
						echo "<u><strong>$c</strong></u> ";
					} else {
						echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=$catid&amp;PageNo=$c")."\"><strong>$c</strong></a> ";
					}
				}
			}
			if ($PageNo < $MaxPage) {
				$NextPage = $PageNo + 1;
				echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=$catid&amp;PageNo=$NextPage&amp;offset=$offset")."\">&gt;&gt; "._ZOOM_NEXT."</a>";
				echo " : <a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=$catid&amp;PageNo=$MaxPage")."\">"._ZOOM_LAST."</a>\n";
			}
			?>
			</div>
			<br />
			</td>
		</tr>
		</table>
		<input type="hidden" name="catid" value="<?php echo $zoom->_gallery->_id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
		<input type="hidden" name="lb_type" value="1" />
		<input type="hidden" name="page" value="" />
		<input type="hidden" name="key" value="" />
		<input type="hidden" name="action" value="" />
		<input type="hidden" name="PageNo" value="<?php echo $PageNo; ?>" />
		<input type="hidden" name="theButton" value="" />
		</form>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr><td>
		<?php
		if ($zoom->_CONFIG['showSearch'] && $zoom->_CONFIG['showKeywords']) {
			$zoom->createSubmitScript('browse');
		?></td>
			<td align="left" valign="top" class="sectiontableheader">
				<form action="<?php echo sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=search&amp;type=quicksearch"); ?>" method="post" name="browse">
				<?php echo ZMG_Template_Main::createKeywordsDropdown($zoom->_getKeywordsList(), 'sstring', '<option value="">&gt;&gt;'._ZOOM_SEARCH_KEYWORD.'&lt;&lt;</option>', 1);?>
				&nbsp;
				</form>
			</td>
			<td align="left" valign="top" class="sectiontableheader">
				<form name="searchzoom" action="<?php echo sefReltoAbs('index.php?option=com_zoom&amp;Itemid='. $Itemid) ?>" target="_top" method="post">
				<input type="hidden" name="option" value="com_zoom" />
				<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
				<input type="hidden" name="page" value="search" />
				<input type="hidden" name="type" value="quicksearch" />
				<input type="hidden" name="sorting" value="3" />
				<input type="text" name="sstring" onblur="if(this.value=='') this.value='<?php echo _ZOOM_SEARCH_BOX;?>';" onfocus="if(this.value=='<?php echo _ZOOM_SEARCH_BOX;?>') this.value='';" value="<?php echo _ZOOM_SEARCH_BOX;?>" class="inputbox" />
				<a href="javascript:document.forms.searchzoom.submit();">
					<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/find.png" border="0" width="16" height="16" alt="" /></a>
				</form>
			</td>
			<?php
			} 
			if ($zoom->_CONFIG['lightbox']) { 
			?>
			<td align="right" valign="top" class="sectiontableheader">
				<a href="<?php echo sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=lightbox&amp;catid=".$catid."&amp;PageNo=".$PageNo);?>">
					<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/lb_view.png" border="0" alt="" /><?php echo _ZOOM_LIGHTBOX_VIEW;?>
				</a>
			</td>
			<?php
			} ?>
		</tr>
		</table>
		</div>
		<?php
	} else {
		echo _NOT_AUTH;
	}
}
?>