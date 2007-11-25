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
| Filename: search.php                                                |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:search.php 108 2007-02-11 18:49:09Z spignataro $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once($mosConfig_absolute_path . "/components/com_zoom/lib/template/template.search.php");

$images = array();
$galleries = array();
$type = trim(mosGetParam($_REQUEST, 'type', 'quicksearch'));
$i = 1;
$zoom->_counter = 0;
$tabcnt = 0;
$startRow = 0;
$PageNo = intval(trim(mosGetParam($_REQUEST, 'PageNo', 1)));

//Set the page no
if ($PageNo != 1) {
    $startRow = ($PageNo - 1) * $zoom->_CONFIG['PageSize'];
}	
//Set the counter start
if ($PageNo % $zoom->_pageSize == 0) {
    $CounterStart = $PageNo - ($zoom->_CONFIG['PageSize']- 1);
} else {
    $CounterStart = $PageNo - ($PageNo % $zoom->_CONFIG['PageSize']) + 1;
}	
//Counter End
$CounterEnd = $CounterStart + ($zoom->_CONFIG['PageSize'] - 1);

if ($type == 'quicksearch') {
    $mainframe->setPageTitle(_ZOOM_SEARCHRESULTS);
    $suchstring = trim(strtolower(mosGetParam($_REQUEST, 'sstring')));
    if(!empty($suchstring)) {   
	    if ($zoom->_CONFIG['ratingOn']) {
	        $zoom->createRatingCSS();
	    }
	    ?>
	    <script language="javascript" type="text/javascript" src="<?php echo $mosConfig_live_site;?>/components/com_zoom/lib/js/prototype.js"></script>
	    <?php
		$database->setQuery("SELECT DISTINCT img.imgid AS id, img.catid AS gallery_id, users.name AS name, users.username AS username "
		 . "FROM #__zoomfiles AS img"
		 . " LEFT JOIN #__zoom AS cats ON img.catid = cats.catid"
		 . " LEFT JOIN #__users AS users ON img.uid = users.id"
		 . "   WHERE cats.catpassword = '' "
		 . "     AND img.published = 1"
		 . "     AND cats.published = 1"
		 . "     AND cats.catmembers = '1'"
		 . "     AND img.imgmembers = '1'"
		 . "     AND LOWER(img.imgdescr) LIKE '%$suchstring%' "
		 . "     OR LOWER(img.imgname) LIKE '%$suchstring%' "
		 . "     OR LOWER(img.imgfilename) LIKE '%$suchstring%' "
		 . "     OR img.imgdate LIKE '%$suchstring%'"
		 . "     OR LOWER(img.imgkeywords) LIKE '%$suchstring%'"
		 . "     OR LOWER(cats.catname) LIKE '%$suchstring%' "
		 . "     OR LOWER(cats.catdescr) LIKE '%$suchstring%' "
		 . "     OR LOWER(cats.catkeywords) LIKE '%$suchstring%'"
		 . "     OR LOWER(users.name) LIKE '%$suchstring%'"
		 . "     OR LOWER(users.username) LIKE '%$suchstring%'"
		 . " ORDER BY id DESC");
		// Displaying query-results:
		$zoom->_result = $database->query();
		while ($row1 = mysql_fetch_object($zoom->_result)) {
		    $images[] = new image($row1->id);
		    $galleries[] = $row1->gallery_id;
		}
	    //Then search through pdf-documents...
	    $database->setQuery("SELECT img.imgid AS imgid, img.catid AS catid "
	     . "FROM #__zoomfiles AS img"
	     . "  LEFT JOIN #__zoom AS cats ON img.catid = cats.catid"
	     . "    WHERE img.published = 1"
	     . "      AND cats.published = 1"
	     . "      AND cats.catpassword = ''"
	     . "      AND cats.catmembers = '1'"
		 . "      AND img.imgmembers = '1'"
	     . "      AND img.imgfilename LIKE '%.pdf'");
	    $zoom->_result = $database->query();
	    while ($row2 = mysql_fetch_object($zoom->_result)) {
	        $pdf_doc = new image($row2->imgid);
	        if ($zoom->toolbox->searchPdf($pdf_doc, $suchstring)) {
	            $images[] = $pdf_doc;
	            $galleries[] = $row2->catid;
	        }
	    }
	    $startRow = 0;
	    
	
	    //Set the page no
	    if ($PageNo != 1) {
			$startRow = ($PageNo - 1) * $zoom->_CONFIG['PageSize'];
	    }
	    
	    
	    //Total of record
	    $RecordCount = sizeof($images);	
	    $endRow = $startRow + $zoom->_CONFIG['PageSize'] - 1;
	    if ($endRow >= $RecordCount) {
		    $endRow = $RecordCount - 1;	
	    }
	    
	    //Get only viewable media for the counter
	    $counter = 0;
	    $imgUSER = array();
	    for ($counter = $startRow; $counter <= $endRow; $counter++) {	
			$zoom->_counter++;
			$image = $images[$counter];
			$zoom->setGallery($galleries[$counter]);
			$image->getInfo();
			$imgKey = $zoom->_gallery->getImageKey($image->_id);
			if ($zoom->_gallery->isMember() && $image->isMember()) {
				$imgUSER[] = $imgKey;		
			}
		}
	    
	    
		//Viewable records
		$ViewRecordCount = sizeof($imgUSER);//Number of files in the search that a user can view
		$ViewendRow = $startRow + $zoom->_CONFIG['PageSize'] -1;
		if ($ViewendRow >= $ViewRecordCount) {
			$ViewendRow = $ViewRecordCount - 1;
		}
	    
	    //Set Maximum Page
	    $MaxPage = $RecordCount % $zoom->_CONFIG['PageSize'];
	    if ($RecordCount % $zoom->_CONFIG['PageSize'] == 0) {
			if ($RecordCount != 0 && $zoom->_CONFIG['PageSize'] != 0) {
			    $MaxPage = $RecordCount / $zoom->_CONFIG['PageSize'];
			} else {
			    $MaxPage = 0;
			}		
	    } else {
			$MaxPage = ceil($RecordCount / $zoom->_CONFIG['PageSize']);
	    }
	    //Set the counter start
	    $CounterStart = 1;
	    //Counter End
	    $CounterEnd = $MaxPage;
	    
	    ZMG_Template_Main::showGenericPageHeader($zoom->_CONFIG['viewtype'], _ZOOM_SEARCHRESULTS.' "<b>'.$suchstring.'</b>"', $Itemid, $mosConfig_live_site.'/components/com_zoom/www/');
	    ?>
	    <table border="0" cellspacing="0" cellpadding="3" width="100%">
	    <tr>
		<td align="center" valign="middle" colspan="4">
		<?php
		if ($ViewRecordCount != 0) {
		    echo sprintf(_ZOOM_IMGFOUND, $ViewRecordCount, $PageNo, $MaxPage);
		} else {
		    echo "<span class=\"small\">"._ZOOM_NOPICS."</span>";
		}
		?>
		</td>
	    </tr>
	    <?php
	    $tabcnt = 0;
	    for ($counter = $startRow; $counter <= $endRow; $counter++) {
			$zoom->_counter++;
			$image = $images[$counter];
			$zoom->setGallery($galleries[$counter]);
			$image->getInfo();
			$imgKey = $zoom->_gallery->getImageKey($image->_id);
			if ($zoom->_gallery->isMember() && $image->isMember()) {
			    echo "\t<tr class=\"".$zoom->_tabclass[$tabcnt]."\"><td width=\"20\">&nbsp; ".($counter+1)." &nbsp;</td>\n";
			    if (!$zoom->_CONFIG['popUpImages']) {
					?>
					<td align="left" width="<?php echo $zoom->_CONFIG['size'];?>">
					<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=view&amp;catid=".$zoom->_gallery->_id."&amp;key=".$imgKey."&amp;hit=1");?>">
					<?php
			    } else {
					$params = $zoom->encrypt("catid=".$zoom->_gallery->_id."&amp;key=".$imgKey."&amp;isAdmin=".$zoom->_isAdmin."&amp;hit=1");
					?>
					<td align="left" width="<?php echo $zoom->_CONFIG['size'];?>">
					<a href="javascript:void(0);" onclick="window.open('components/com_zoom/www/view.php?popup=1&amp;q=<?php echo $params; ?>', 'win1', 'width=<?php if($image->_size[0]<550){ echo "550";}elseif($image->_size[0]>$zoom->_CONFIG['maxsize']){ echo $zoom->_CONFIG['maxsize'] + 50;}else{ echo $image->_size[0] + 40;} ?>, height=<?php if($image->_size[1]<550){ echo "550";}elseif($image->_size[1]>$zoom->_CONFIG['maxsize']){ echo $zoom->_CONFIG['maxsize'] + 50;}else{ echo $image->_size[1] + 100;} ?>,scrollbars=1').focus()">
					
					<?php
			    }
			    echo ("<img src=\"".$zoom->hotlinkImage($image->_catid, '2', $image->_id, null )."\" alt=\"\" border=\"0\" /></a></td>\n"
			     . "\t\t\t<td width=\"10\"></td>\n"
			     . "\t\t\t<td align=\"left\">\n"
			     . "\t\t\t\t<b>".$zoom->highlight($suchstring, $image->_filename)."</b><br />\n");
			    if ($zoom->_CONFIG['showHits']) {
					echo "\t\t\t\thits = ".$image->_hits."<br />\n\t\t\t\t";
			    }
			    if ($zoom->_CONFIG['ratingOn']) {
		    		echo $image->getStaticStars();
			    }
			    echo ("\t\t\t\t<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$zoom->_gallery->_id)."\">\n"
			     . "\t\t\t\t\t".$zoom->highlight($suchstring, $zoom->_gallery->getCatVirtPath())."</a>\n"
			     . "\t\t\t</td>\n"
			     . "\t\t</tr>\n");
			    if ($tabcnt >= 1) {
					$tabcnt = 0;
			    } else {
					$tabcnt++;
			    }
			}
	    }
	    ?>
	    <tr>
		<td align="left" valign="top" class="<?php echo $zoom->_tabclass[$tabcnt];?>" colspan="3">
		    <?php
		    if ($zoom->_CONFIG['showSearch'] && $zoom->_CONFIG['showKeywords']) {
				$zoom->createSubmitScript('browse');
		    }
		    $action_search = sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=search&amp;type=quicksearch");
		    ?>
		    <form action="<?php echo $action_search ?>" method="post" name="browse">
		    <?php
		    ZMG_Template_Main::createKeywordsDropdown($zoom->_getKeywordsList(), 'sstring', '<option value="">&gt;&gt;'._ZOOM_SEARCH_KEYWORD.'&lt;&lt;</option>', 1);
		    ?>&nbsp;
		    </form>
		</td>
		<td align="center" valign="top" class="<?php echo $zoom->_tabclass[$tabcnt];?>">
		    <form name="searchzoom" action="<?php echo sefReltoAbs('index.php?option=com_zoom&amp;Itemid='. $Itemid) ?>" target=_top method="post">
		    <input type="hidden" name="option" value="com_zoom" />
		    <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
		    <input type="hidden" name="page" value="search" />
		    <input type="hidden" name="type" value="quicksearch" />
		    <input type="hidden" name="sorting" value="3" />
		    <input type="text" name="sstring" style="border: 1px solid; font: 10px Arial" onblur="if(this.value=='') this.value='<?php echo $suchstring;?>';" onfocus="if(this.value=='<?php echo $suchstring;?>') this.value='';" value="<?php echo $suchstring;?>" />
		    <a href="javascript:document.forms.searchzoom.submit();"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/find.png" alt="Search" border="0" width="16" height="16" /></a>
		    </form>
		</td>
	    </tr>
	    <?php
	    echo "</table><center>";
	    //Print First & Previous Link if necessary
	    if ($PageNo != 1) {
			$PrevStart = $PageNo - 1;
			echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=search&amp;type=quicksearch&amp;sstring=".$suchstring."&amp;PageNo=1")."\">"._ZOOM_FIRST." </a>:	";
			echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=search&amp;type=quicksearch&amp;sstring=".$suchstring."&amp;PageNo=$PrevStart")."\">"._ZOOM_PREVIOUS." << </a>\n";
	    }
	    $c = 0;
	    //Print Page No
	    for ($c = $CounterStart; $c <= $CounterEnd; $c++) {
			if ($c < $MaxPage) {
			    if ($c == $PageNo) {
					if ($c % $RecordCount ==	0) {
					    echo "<u><strong>$c</strong></u> ";
					} else {
					    echo "<u><strong>$c</strong></u> | ";
					}
			    } elseif ($c % $RecordCount == 0) {
					echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=search&amp;type=quicksearch&amp;sstring=".$suchstring."&amp;PageNo=$c")."\"><strong>$c</strong></a> ";
			    } else {
					echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=search&amp;type=quicksearch&amp;sstring=".$suchstring."&amp;PageNo=$c")."\"><strong>$c</strong></a> | ";
			    }
			} else {
			    if ($PageNo == $MaxPage) {
					echo "<u><strong>$c</strong></u> ";
					break;
			    } else {
					echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=search&amp;type=quicksearch&amp;sstring=".$suchstring."&amp;PageNo=$c")."\"><strong>$c</strong></a> ";
			    }
			}
	    }
	    if ($PageNo < $MaxPage) {
			$NextPage = $PageNo + 1;
			echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=search&amp;type=quicksearch&amp;sstring=".$suchstring."&amp;PageNo=$NextPage")."\">>> "._ZOOM_NEXT."</a>";
			echo " : <a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=search&amp;type=quicksearch&amp;sstring=".$suchstring."&amp;PageNo=$MaxPage")."\">"._ZOOM_LAST."</a>\n";
	    }
	    echo "</center>\n";
	} else {
		 echo "<span class=\"small\">"._ZOOM_NOPICS."</span>";
	}
}