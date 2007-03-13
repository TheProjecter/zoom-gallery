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
| Filename: special.php                                               |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:special.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once($mosConfig_absolute_path . "/components/com_zoom/lib/template/template.search.php");

global $size;
/**
 There are three special image-display formats:
 0: Top ten viewed images (most hits)
 1: Ten last submitted images (last id's)
 2: Ten last commented images
 4: Top rated
**/
$sorting = intval(trim(mosGetParam($_REQUEST, 'sorting')));
if ($sorting == 1 || $sorting == 2) {
	$where_prefix = "\n WHERE ";
} else {
    $where_prefix = "\n AND ";
}
if ($zoom->_isAdmin) {
	$where_clause = "";
} else {
    $where_clause = ($where_prefix."cats.catpassword = '' "
     . "\n AND img.published = '1' "
     . "\n AND cats.published = '1' "
     . "\n AND img.imgmembers = '1' "
     . "\n AND cats.catmembers = '1' ");
}
switch($sorting){
  case 0:
    $database->setQuery("SELECT DISTINCT img.imgid AS id, img.catid AS gallery_id "
     . "\n FROM #__zoomfiles AS img"
     . "\n LEFT JOIN #__zoom AS cats ON img.catid = cats.catid"
     . "\n WHERE imghits > 0 $where_clause"
     . "\n ORDER BY imghits DESC LIMIT 10");
    break;

  case 1:
    $database->setQuery("SELECT DISTINCT img.imgid AS id, img.catid AS gallery_id "
     . "\n FROM #__zoomfiles AS img"
     . "\n LEFT JOIN #__zoom AS cats ON img.catid = cats.catid"
     . "\n $where_clause"
     . "\n ORDER BY id DESC LIMIT 10");
    break;

  case 2:
    $database->setQuery("SELECT DISTINCT com.imgid AS id, img.catid AS gallery_id, max(com.cmtid) AS maxcmt"
     . "\n FROM #__zoomfiles AS img"
     . "\n LEFT JOIN #__zoom AS cats ON img.catid = cats.catid"
     . "\n LEFT JOIN #__zoom_comments AS com ON img.imgid = com.imgid"
     . "\n $where_clause"
	 . "\n AND com.imgid > '0'"
     . "\n AND com.cmtid > '0'"
     . "\n GROUP BY id"
     . "\n ORDER BY maxcmt DESC LIMIT 10");
    break;
  
  case 4:
    $database->setQuery("SELECT DISTINCT img.imgid AS id, img.catid AS gallery_id, img.votenum, (img.votesum/img.votenum) AS rating "
     . "\n FROM #__zoomfiles AS img"
     . "\n LEFT JOIN #__zoom AS cats ON img.catid = cats.catid"
     . "\n WHERE img.votesum > '0'"
     . "\n AND img.votenum > '0'"
     . "\n $where_clause"
     . "\n ORDER BY rating DESC, img.votenum DESC LIMIT 10");
    break;
  
  default:
    die("You must visit this page the legit way, remember?");
}
$zoom->_result = $database->query();
if ($zoom->_CONFIG['ratingOn']) {
    $zoom->createRatingCSS();
}

$mainframe->addCustomHeadTag('<script language="javascript" type="text/javascript" src="'.$mosConfig_live_site.'/components/com_zoom/lib/js/prototype.js"></script>');

$section = _ZOOM_TOPTEN;
if( $sorting == 1 ) $section = _ZOOM_LASTSUBM;
else if( $sorting ==2 ) $section =_ZOOM_LASTCOMM;
else if( $sorting == 4 ) $section =_ZOOM_TOPRATED;

$mainframe->setPageTitle($section);

ZMG_Template_Main::showGenericPageHeader($zoom->_CONFIG['viewtype'], $section, $Itemid, $mosConfig_live_site.'/components/com_zoom/www/');
?>
<table border="0" cellspacing="0" cellpadding="3" width="100%">
<?php
$i = 0;
$imgcnt = 0;
$tabcnt = 0;
if (mysql_num_rows($zoom->_result) > 0) {
	while($row = mysql_fetch_object($zoom->_result)){
	    if (!empty($row->id)) {
	    	$imgcnt++;
    		$zoom->setGallery($row->gallery_id);
			$zoom->_counter = 0;
    		foreach($zoom->_gallery->_images as $image){
    			if($image->_id == $row->id){
    				$i = $zoom->_counter;
    			}
    			$zoom->_counter++;
    		}
    		$zoom->_gallery->_images[$i]->getInfo();
    		if ($zoom->_gallery->isMember() && $zoom->_gallery->_images[$i]->isMember()) {
    			echo '<tr class="'.$zoom->_tabclass[$tabcnt].'"><td width="20">&nbsp; '.$imgcnt.' &nbsp;</td>';
    			if (!$zoom->_CONFIG['popUpImages']) {
    				?>
    				<td align="left" width="<?php echo $zoom->_CONFIG['size'];?>">
    				<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=view&amp;catid=".$zoom->_gallery->_id."&amp;key=".$i."&amp;hit=1");?>">
    				<?php
    			} else {
    				$params = $zoom->encrypt("catid=".$zoom->_gallery->_id."&amp;key=".$i."&amp;isAdmin=".$zoom->_isAdmin."&amp;hit=1");
    				?>
    				<td align="left" width="<?php echo $zoom->_CONFIG['size'];?>">
    				<?php
    				$link = "<a href=\"javascript:void(0)\" onClick=\"window.open('".$mosConfig_live_site."/components/com_zoom/www/view.php?popup=1&amp;q=".$params."', 'win1', 'width=";
    				if ($size[0] < 550) {
    					$link .= "550";
    				} elseif ($size[0] > $zoom->_CONFIG['maxsize']) {
    					$link .= $zoom->_CONFIG['maxsize'] + 50;
    				} else {
    					$link .= $size[0] + 40;
    				}
    				$link .= ", height=";
    				if ($size[1] < 550) {
    					$link .= "550";
    				} elseif ($size[1] > $zoom->_CONFIG['maxsize']) {
    					$link .= $zoom->_CONFIG['maxsize'] + 50;
    				} else {
    					$link .= $size[1] + 100;
    				}
    				$link .= ", scrollbars=1').focus()\">\n";
    				echo $link;
    			}
    			echo '<img src="'.$zoom->hotlinkImage($zoom->_gallery->_images[$i]->_catid, '2', $zoom->_gallery->_images[$i]->_id, null).'" border="0" alt="" /></a></td><td width="10"></td>';
    			echo '<td align="left"><strong>'.$zoom->_gallery->_images[$i]->_name.'</strong><br />';
    			if ($zoom->_CONFIG['showHits'])
    				echo _ZOOM_HITS.' = '.$zoom->_gallery->_images[$i]->_hits.'<br />';
    			if ($zoom->_CONFIG['ratingOn']) {
    				echo $zoom->_gallery->_images[$i]->getStaticStars();
    			}
    			echo "<a href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$zoom->_gallery->_id)."\">".$zoom->_gallery->getCatVirtPath()."</a>";
    			//new feature
    			if ($sorting == 2){ 
    				$dir_prefix = $mosConfig_live_site."/components/com_zoom/www/";
    				$smilies = $zoom->_getSmiliesTable();
    				$lastelement = count($zoom->_gallery->_images[$i]->_comments);
    				
    				if ($lastelement <> ""){
    					$comment = $zoom->_gallery->_images[$i]->_comments[$lastelement-1];
    	
    					echo "<table style=\"border: 1px solid #cccccc\" width=\"98% \">";
    					echo "<tr><td><strong>".$comment->_name."(".$comment->_date.")</strong></td></tr>";
    					$theComment = $comment->_processSmilies($comment->_comment,$dir_prefix,$smilies);
    					
    					echo "<tr><td>".$theComment."</td></tr>";
    					echo "</table>";
    				}
    			}
    			echo "</td></tr>";
    			if ($tabcnt >= 1) {
    				$tabcnt = 0;
    			} else {
    				$tabcnt++;
    			}
    		} else {
    		?>
    				<tr>
    		<td><?php echo _ZOOM_NOIMG; ?></td>
    	</tr>
    	<?php
    		}
	    }
	}
} else {
	?>
	<tr>
		<td><?php echo _ZOOM_NOIMG; ?></td>
	</tr>
	<?php
}
?>
</table> 