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
| Filename: view.php                                                  |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:view.php 126 2007-02-17 06:05:09Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
if (array_key_exists('popup', $_REQUEST)) {
	$popup = intval($_REQUEST['popup']);
} else {
    $popup = false;
}
if ($popup) {
	define( "_VALID_MOS", 1 );
	include_once('../../../configuration.php');
	if (file_exists($mosConfig_absolute_path."/includes/joomla.php")) {
		require_once($mosConfig_absolute_path."/includes/joomla.php");
	} elseif (file_exists($mosConfig_absolute_path."/includes/mambo.php")) {
		require_once($mosConfig_absolute_path."/includes/mambo.php");
	}
	$mainframe = new mosMainFrame( $database, 'com_zoom', '..', true );
	
	error_reporting(0);  

	if (file_exists($mosConfig_absolute_path."/version.php")) {
		include_once($mosConfig_absolute_path."/version.php");
	} elseif (file_exists($mosConfig_absolute_path."/includes/version.php")) {
		include_once($mosConfig_absolute_path."/includes/version.php");
	}
	if (file_exists($mosConfig_absolute_path."/classes/database.php")) {
		include_once($mosConfig_absolute_path."/classes/database.php");
		require_once( $mosConfig_absolute_path . '/classes/gacl.class.php' );
	    require_once( $mosConfig_absolute_path . '/classes/gacl_api.class.php' ); 
	} elseif (file_exists($mosConfig_absolute_path."/includes/database.php")) {
		include_once($mosConfig_absolute_path."/includes/database.php");
	    require_once( $mosConfig_absolute_path . '/includes/gacl.class.php' );
	    require_once( $mosConfig_absolute_path . '/includes/gacl_api.class.php' ); 
	}
	$database = new database($mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix);
	if (file_exists( $mosConfig_absolute_path."/language/".$mosConfig_lang.".php" ) ) {
		include_once( $mosConfig_absolute_path."/language/".$mosConfig_lang.".php" );
	} else {
		include_once( $mosConfig_absolute_path."/language/english.php" );
	}
	if (file_exists( $mosConfig_absolute_path."/components/com_zoom/lib/language/".$mosConfig_lang.".php" ) ) {
		include_once( $mosConfig_absolute_path."/components/com_zoom/lib/language/".$mosConfig_lang.".php" );
	} else {
		include_once( $mosConfig_absolute_path."/components/com_zoom/lib/language/english.php" );
	}
	// Create zOOm Image Gallery object
	require_once($mosConfig_absolute_path."/components/com_zoom/lib/zoom.class.php");
	require_once($mosConfig_absolute_path."/components/com_zoom/lib/toolbox.class.php");
	require_once($mosConfig_absolute_path."/components/com_zoom/lib/editmon.class.php"); //like a common session-monitor...
	require_once($mosConfig_absolute_path."/components/com_zoom/lib/gallery.class.php");
	require_once($mosConfig_absolute_path."/components/com_zoom/lib/image.class.php");
	require_once($mosConfig_absolute_path."/components/com_zoom/lib/comment.class.php");
	require_once($mosConfig_absolute_path."/components/com_zoom/lib/privileges.class.php");
	// Load configuration file...
	include($mosConfig_absolute_path."/components/com_zoom/etc/zoom_config.php");
	$zoom = new zoom();
	$acl = new gacl_api();
	
	// get variables from HTTP request...
	$q = $zoom->decrypt($zoom->getParam($_REQUEST, 'q'));
	// Use &amp; for correct processing
	$params = split("&amp;", $q);
	foreach ($params as $param) {
		$var = split("=", $param);
		if (count($var) === 2) {
			$_REQUEST[$var[0]] = $var[1];
		}
	}
	$uid = intval($zoom->getParam($_REQUEST, 'uid'));
	
	if (isset($uid)) {
		$uid = intval(trim($zoom->getParam($_REQUEST, 'uid')));
	} else {
		$uid = '0';
	}
	
	//$my = new stdClass();
	
	session_start();
	
	$database->setQuery( "SELECT id, gid, username, usertype FROM #__users WHERE id=$uid");
	$row = null;
	if ($database->loadObject( $row )) {
		// fudge the group stuff
		$grp = $acl->getAroGroup( $row->id );
		$row->gid = 1;
	
		if ($acl->is_group_child_of( $grp->name, 'Registered', 'ARO' ) ||
		$acl->is_group_child_of( $grp->name, 'Public Backend', 'ARO' )) {
			// fudge Authors, Editors, Publishers and Super Administrators into the Special Group
			$row->gid = 2;
		}
		$row->usertype = $grp->name;
		
		$my->id = intval( $row->id );
	    $my->username = $row->username;
	    $my->usertype = $row->usertype;
	    $my->gid = intval( $row->gid );
	}
	
	
	$catid = intval($zoom->getParam($_REQUEST, 'catid'));
	$isAdmin = (bool)$zoom->getParam($_REQUEST, 'isAdmin', false);
	
	$zoom->toolbox = new toolbox(false);
	$zoom->setGallery($catid);
	if ($isAdmin) {
		$zoom->_isAdmin = true;
	}
	if ($zoom->isWin()) {
		require_once($mosConfig_absolute_path.'/components/com_zoom/lib/WinNtPlatform.class.php');
		$zoom->platform = new WinNtPlatform();
	} else {
		require_once($mosConfig_absolute_path.'/components/com_zoom/lib/UnixPlatform.class.php');
		$zoom->platform = new UnixPlatform();
	}
}
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once($mosConfig_absolute_path . "/components/com_zoom/lib/template/template.view.php");

// get variables from HTTP request...
$key = intval($zoom->getParam($_REQUEST, 'key'));
$PageNo = intval($zoom->getParam($_REQUEST, 'PageNo', 1));
$delComment = intval($zoom->getParam($_REQUEST, 'delComment'));
$submit = (bool)$zoom->getParam($_REQUEST, 'submit', false);
$vote = intval($zoom->getParam($_REQUEST, 'vote', -1));
$hit = (bool)$zoom->getParam($_REQUEST, 'hit', false);

// get the file-data for display
$zoom->_gallery->_images[$key]->getInfo(false);
if ($zoom->_gallery->_images[$key]->isMember($popup)) {
	if ($submit === true) {
		$uname = $zoom->getParam($_REQUEST, 'uname');
		$comment = ($zoom->getParam($_REQUEST, 'comment'));
		$zoom->_gallery->_images[$key]->addComment($uname,$comment);
	}
	if (isset($delComment)) {
		$zoom->_gallery->_images[$key]->delComment($delComment);
	}
	if (isset($vote)) {
		$zoom->_gallery->_images[$key]->rateImg($vote);
	}
	// update hitcounter for this image...
	if ($hit === true) {
		$zoom->_gallery->_images[$key]->hitPlus();
	}
	if ($zoom->_CONFIG['popUpImages']) {
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
		<link href="../etc/popup.css" rel="stylesheet" media="screen" type="text/css" />
		<?php
	}
	if ($zoom->_CONFIG['readEXIF']) {
		global $mainframe;
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/JPEG.php");
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/EXIF.php");
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/JFIF.php");
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/Photoshop_IRB.php");
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/PictureInfo.php");
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/iptc/XMP.php");
		if ($zoom->_CONFIG['popUpImages']) {
    		?>
    		<style type="text/css" media="all">
    			.EXIF_Table {  padding: 3px; border: solid 1px black; outline: solid 1px black }
    		</style>
    		<?php
        } else {
            $mainframe->addCustomHeadTag('<style type="text/css" media="all">.EXIF_Table {  padding: 3px; border: solid 1px black; outline: solid 1px black }</style>');
		}
	}
	if ($zoom->_CONFIG['popUpImages']) {
		?>
	   		<link href="<?php echo $mosConfig_live_site; ?>/components/com_zoom/etc/zoom.css" rel="stylesheet" media="screen" type="text/css" />
		<?php
	} else {
			$mainframe->setPageTitle($zoom->revnumericentities($zoom->_gallery->_images[$key]->_name));
			$mainframe->addCustomHeadTag('<link href="'.$mosConfig_live_site.'/components/com_zoom/etc/zoom.css" rel="stylesheet" media="screen" type="text/css" />');	
	}
	
	
    if (!$zoom->_gallery->_images[$key]->_isBroken) {
    	if ($zoom->isImage($zoom->_gallery->_images[$key]->_type)) {
            $img_path = $zoom->hotlinkImage($catid, '0', $zoom->_gallery->_images[$key]->_id, null );
        } else {
            $img_path = $mosConfig_live_site."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$zoom->_gallery->_images[$key]->_viewsize;
        }
    	$fullsize = $zoom->hotlinkImage($catid, '1', $zoom->_gallery->_images[$key]->_id, null );
        $img_loc = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$zoom->_gallery->_images[$key]->_viewsize;
        $img_loc_org = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$zoom->_gallery->_images[$key]->_filename;
    } else {
    	$img_path = $mosConfig_live_site."/components/com_zoom/www/images/filetypes/file_broken.png";
    	$fullsize = $mosConfig_live_site."/components/com_zoom/www/images/filetypes/file_broken.png";
        $img_loc = $mosConfig_absolute_path."/components/com_zoom/www/images/filetypes/file_broken.png";
        $img_loc_org = $mosConfig_absolute_path."/components/com_zoom/www/images/filetypes/file_broken.png";
    }
	
	$dir_prefix = $mosConfig_live_site."/components/com_zoom/www/";
	if ($zoom->_CONFIG['popUpImages']) {
		$url_prefix = "view.php?popup=1&amp;catid=".$zoom->_gallery->_id."&amp;uid=".$my->id;
	} else {
		$url_prefix = "index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=view&amp;catid=".$zoom->_gallery->_id;
	}
	if ($zoom->isImage($zoom->_gallery->_images[$key]->_type)) {
		$size = $zoom->platform->getimagesize($img_loc);
	} else {
		$size = $zoom->platform->getimagesize(str_replace($mosConfig_live_site, $mosConfig_absolute_path, $zoom->_gallery->_images[$key]->_thumbnail));
	}
	
	$i=0;
	$continue = true;
    while($continue) {
    	$zoom->_gallery->_images[$i]->getInfo(false);
        if($zoom->_gallery->_images[$i]->isMember($popup)) {
			$first_img = $i;
			$continue = false;
		} else {
			$i++;
		}
	}
	
	$i=count($zoom->_gallery->_images)-1;
    $continue = true;
    while($continue) {
        $zoom->_gallery->_images[$i]->getInfo(false);
        if($zoom->_gallery->_images[$i]->isMember($popup)) {
			$last_img = $i;
			$continue = false;
		} else {
			$i--;
		}
	}
    
	if ($key == $first_img) {
		$pid = $last_img;
	} else {
		if ($key == $last_img) {
			if($key == count($zoom->_gallery->_images)) {
				$i=$key;
                $continue = true;
                while($continue) {
                    $zoom->_gallery->_images[$i]->getInfo(false);
                    if($zoom->_gallery->_images[$i]->isMember($popup)) {
            			$pid = $i;
            			$continue = false;
            		} else {
            			$i--;
            		}
            	}
			} else {
				$i=$key-1;
                $continue = true;
                while($continue) {
                    $zoom->_gallery->_images[$i]->getInfo(false);
                    if($zoom->_gallery->_images[$i]->isMember($popup)) {
            			$pid = $i;
            			$continue = false;
            		} else {
            			$i--;
            		}
            	}
			}
		} else {
            if($key == count($zoom->_gallery->_images)-1) {
				$i=$key;
                $continue = true;
                while($continue) {
                    $zoom->_gallery->_images[$i]->getInfo(false);
                    if($zoom->_gallery->_images[$i]->isMember($popup)) {
            			$pid = $i;
            			$continue = false;
            		} else {
            			$i--;
            		}
            	}
			} else {
			    $i=$key-1;
                $continue = true;
                while($continue) {
                    $zoom->_gallery->_images[$i]->getInfo(false);
                    if($zoom->_gallery->_images[$i]->isMember($popup)) {
            			$pid = $i;
            			$continue = false;
            		} else {
            			$i--;
            		}
            	}
			}			
		}
	}
	if ($key == $last_img) {
		$nid = $first_img;
	} else {
	    if($key == (count($zoom->_gallery->_images) - 1)) {
			$i=$key;
            $continue = true;
            while($continue) {
                $zoom->_gallery->_images[$i]->getInfo(false);
                if($zoom->_gallery->_images[$i]->isMember($popup)) {
        			$nid = $i;
        			$continue = false;
        		} else {
        			$i++;
        		}
        	}
		} else {
            $i=$key+1;
            $continue = true;
            while($continue) {
                $zoom->_gallery->_images[$i]->getInfo(false);
                if($zoom->_gallery->_images[$i]->isMember($popup)) {
        			$nid = $i;
        			$continue = false;
        		} else {
        			$i++;
        		}
        	}	
		}
	    
	    
	}
	if ($zoom->_CONFIG['slideshow']) {
		$zoom->createSlideshow($key);
	}
		if ($zoom->_CONFIG['ratingOn']) {
		    $zoom->createRatingCSS();
		}
	if ($zoom->_CONFIG['popUpImages']) {
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function newLoc(id){
			window.document.location= 'view.php?imgid=<?php echo $imgid;?>&isAdmin=<?php echo $isAdmin;?>&delComment=' +id;
		}
		function lb_add(){
			window.opener.location = '<?php echo $mosConfig_live_site."/index.php?option=com_zoom&Itemid=".$Itemid."&page=lightbox&action=add&catid=".$zoom->_gallery->_id."&key=".$key."&PageNo=".$PageNo."&lb_type=3";?>'
		}
		function send_ecard(){
			window.opener.location = '<?php echo $mosConfig_live_site."/index.php?option=com_zoom&Itemid=".$Itemid."&page=ecard&catid=".$zoom->_gallery->_id."&key=".$key;?>';
			window.opener.focus();
			window.close();
		}
		function searchKeyword(keyword){
			window.opener.location = '<?php echo $mosConfig_live_site."/index.php?option=com_zoom&page=search&type=quicksearch&catid=".$zoom->_gallery->_id."&key=".$key;?>&sstring=' +keyword;
			window.opener.focus();
			window.close();
		}
		//-->
		</script>
		<script language="javascript" type="text/javascript" src="../../../includes/js/overlib_mini.js"></script>
		<script language="javascript" type="text/javascript" src="../lib/js/prototype.js"></script>
		<script language="javascript" type="text/javascript" src="../lib/js/scriptaculous.js?load=effects,mm,tjpzoom"></script>

		<title><?php echo $zoom->_CONFIG['zoom_title']." - ".$zoom->_gallery->_images[$key]->_filename;?></title>
		</head>
		<body>
		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
		<div align="center">
		<?php 
		if ($zoom->_CONFIG['close']) {
			?>
			<a href="javascript:window.close()"><?php echo _ZOOM_CLOSE; ?></a><br /><br />
			<?php 
		}
		?>
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
		<td width="100%">&nbsp;</td>
		<?php
		if($zoom->_CONFIG['lightbox']){
			echo ("\t\t<td align=\"right\" valign=\"top\" width=\"40\">\n"
			 . "\t\t<a href=\"javascript:void(0);\" onmouseover=\"return overlib('"._ZOOM_LIGHTBOX_ITEM."', CAPTION, '"._ZOOM_LIGHTBOX."');\" onmouseout=\"return nd();\">\n"
			 . "\t\t<img src=\"".$dir_prefix."images/lightbox.png\" border=\"0\" name=\"lightbox\" onclick=\"Zoom.lightboxAdd(".$zoom->_gallery->_images[$key]->_id.", 1, this);\" alt=\"\" /></a>&nbsp;\n"
			 . "\t\t</td>\n");
		}
		if($zoom->_CONFIG['ecards']){
			?>
			<td align="right" valign="top" width="40">
				<a href="javascript:send_ecard();" onmouseover="return overlib('<?php echo _ZOOM_ECARD_SENDAS; ?>', CAPTION, '<?php echo _ZOOM_ECARD_SENDCARD; ?>');" onmouseout="return nd();">
				<img src="<?php echo $dir_prefix; ?>images/ecard.png" border="0" name="ecard" alt="" /></a>
			</td>
			<?php
		} // end IF ecards
		?>
		</tr>
		</table>
		</div>
		<?php
	} else {
	    $mainframe->addCustomHeadTag('<script language="javascript" type="text/javascript" src="'.$mosConfig_live_site.'/components/com_zoom/lib/js/prototype.js"></script>');
	    $mainframe->addCustomHeadTag('<script language="javascript" type="text/javascript" src="'.$mosConfig_live_site.'/components/com_zoom/lib/js/scriptaculous.js?load=effects,mm,tjpzoom"></script>');
		ZMG_Template_Main::showPageHeader($zoom->_CONFIG['viewtype'], $Itemid, $PageNo, $key, $dir_prefix);
	}
	$zoom->prepareAjax(array("lb_title" => _ZOOM_LIGHTBOX));
	?>
	<div align="center">
	<?php
	
	ZMG_Template_View::showMediumNavigation($zoom->_CONFIG['viewtype'], $Itemid, $key, $dir_prefix, $url_prefix,
	   $first_img, $pid, $nid, $last_img, $hit);
	
	if ($zoom->isImage($zoom->_gallery->_images[$key]->_type)) {
	    $viewsize_info = getimagesize($img_loc);
		if ($fullsize !== $img_path) {
			$fullsize = ", '".$fullsize."'";	
		} else {
			$fullsize = "";
		}

		ZMG_Template_View::showTypeImage($zoom->_CONFIG['viewtype'], $img_path, $fullsize, $viewsize_info, $key);

	} elseif ($zoom->isDocument($zoom->_gallery->_images[$key]->_type)) {
		//require_once($mosConfig_absolute_path."/components/com_zoom/lib/pdf.class.php");
		//$pdf = new PDF_parser($img_path);
		//print_r($pdf->parse($img_path));
		ZMG_Template_View::showTypeDocument($zoom->_gallery->_images[$key]->_thumbnail, $zoom->_CONFIG['popUpImages'], $img_path);
	} elseif ($zoom->isMovie($zoom->_gallery->_images[$key]->_type)) {
		ZMG_Template_View::showTypeMovie($zoom->_gallery->_images[$key], $img_path);
	} elseif ($zoom->isAudio($zoom->_gallery->_images[$key]->_type)) {
		ZMG_Template_View::showTypeAudio($zoom->_gallery->_images[$key], $key, $img_path, $zoom->_CONFIG['popUpImages']);
	}
	if ($zoom->_CONFIG['zoomOn'] && $zoom->isImage($zoom->_gallery->_images[$key]->_type)) {
		?>
		<!-- what would zOOm Image Gallery be withouts its zoom-function!? -->
		<p><img src="<?php echo $dir_prefix;?>images/zoom_plus.png" border="0" alt="" />/<img src="<?php echo $dir_prefix; ?>images/zoom_minus.png" border="0" alt="" />: <?php echo _ZOOM_IN_DESC; ?></p>
		<?php
	} //END IF zoom?
	if ($zoom->_CONFIG['properties']) {
	    ?>
	    <br /><br />
            <!-- beginning of floating-box to hide details when the Slideshow has started... -->
            <div id="details">
        <?php
		
	    ZMG_Template_View::showPropertiesBox($zoom->_CONFIG['viewtype'], $img_path, $key);
	    
		// Display a link which enables the user to view EXIF-readings of the image, if readEXIF is set
		// to TRUE.
		$empty_span = "<span>&nbsp;</span>";
		if ($zoom->_CONFIG['readEXIF'] && $zoom->isImage($zoom->_gallery->_images[$key]->_type)) {
			// $exif = $zoom->_gallery->_images[$key]->exif_parse_file($img_loc);
			$prefix = ZMG_Template_View::createViewBlock(_ZOOM_EXIF_SHOWHIDE);
			$metadata_viewable = Interpret_EXIF_to_HTML( get_EXIF_JPEG( $img_loc_org ), $img_loc_org );
			if (!empty($metadata_viewable)) {
				echo $metadata_viewable;
			} else {
				echo $empty_span;
			}
			ZMG_Template_View::finishViewBlock($zoom->_CONFIG['meta_state'], $prefix);
		}
		// Do the same for the ID3 tag parser...
		if ($zoom->_CONFIG['readID3'] && $zoom->isAudio($zoom->_gallery->_images[$key]->_type)) {
			$prefix = ZMG_Template_View::createViewBlock(_ZOOM_ID3_SHOWHIDE);
			$id3_data = $zoom->_gallery->_images[$key]->_metadata;
			$metadata_viewable = $zoom->toolbox->interpret_ID3_to_HTML($id3_data);
			if (!empty($metadata_viewable)) {
				echo $metadata_viewable;
			} else {
				echo $empty_span;
			}
			ZMG_Template_View::finishViewBlock($zoom->_CONFIG['meta_state'], $prefix);
		}
		if ($zoom->isMovie($zoom->_gallery->_images[$key]->_type)) {
			$prefix = ZMG_Template_View::createViewBlock(_ZOOM_VIDEO_SHOWHIDE);
			$metadata = $zoom->_gallery->_images[$key]->_metadata;
			$metadata_viewable = $zoom->toolbox->interpret_META_to_HTML($metadata);
			if (!empty($metadata_viewable)) {
				echo $metadata_viewable;
			} else {
			    echo $empty_span;
			}
			ZMG_Template_View::finishViewBlock($zoom->_CONFIG['meta_state'], $prefix);
		}
		// Display comments-form for input of comments, if comments are allowed ofcourse...
		// The Edit-Monitor registers the user input and does not allow him/ her to add a comment again
		// that session.
		if ($zoom->_CONFIG['commentsOn']) {
			$prefix = ZMG_Template_View::createViewBlock(_ZOOM_COMMENTS);
			
			ZMG_Template_View::showCommentsBox($zoom->_CONFIG['viewtype'], $dir_prefix, $key);
			
			if (($zoom->_CONFIG['anonymous_comments']) || (!$zoom->_CONFIG['anonymous_comments'] && !empty($my->username))){
				if ($zoom->_CONFIG['popUpImages']) {
					$cmt_action = $url_prefix;
				} else {
					$cmt_action = sefRelToAbs($url_prefix);
				}
				ZMG_Template_View::showCommentsForm($zoom->_CONFIG['viewtype'], $cmt_action, $dir_prefix, $popup, $key);
			}
			ZMG_Template_View::finishViewBlock($zoom->_CONFIG['comments_state'], $prefix);
		} //END if commentsOn?
		?>
		</div>
		<?php //close properties boxes
	}
	?>
	</div></div>
	<?php
	if ($zoom->_CONFIG['popUpImages']) {
		echo "</body>\n</html>";
	}
} else {
	echo _NOT_AUTH;
}
?>