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
| Filename: template.view.php                                         |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:template.view.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @subpackage Template
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class ZMG_Template_View {
    function showMediumNavigation($use_tables = true, $Itemid, $key, $dir_prefix, $url_prefix, $first_img, $pid, $nid, $last_img, $hit) {
        global $zoom, $my;
        if ($use_tables) {
            ?>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <?php
            if ($zoom->_CONFIG['slideshow'] && $zoom->isImage($zoom->_gallery->_images[$key]->_type)) {
                ?>
                <tr>
                    <td align="center" valign="middle">
                    <div align="center">
                        <?php echo _ZOOM_SLIDESHOW; ?>
                    </div>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td align="center" valign="middle">
                <div align="center">
                <?php
                if ($zoom->_CONFIG['navbuttons']) {
                    if ($zoom->_CONFIG['popUpImages']) {
                        $first = $url_prefix.'&amp;key='.$first_img.'&amp;hit='.$hit.'&amp;uid='.$my->id;
                        $previous = $url_prefix.'&amp;key='.$pid.'&amp;hit='.$hit.'&amp;uid='.$my->id;
                        $next = $url_prefix.'&amp;key='.$nid.'&amp;hit='.$hit.'&amp;uid='.$my->id;
                        $last = $url_prefix.'&amp;key='.$last_img.'&amp;hit='.$hit.'&amp;uid='.$my->id;
                    } else {
                        $first = sefRelToAbs(''.$url_prefix.'&amp;key='.$first_img.'&amp;hit='.$hit.'');
                        $previous = sefRelToAbs(''.$url_prefix.'&amp;key='.$pid.'&amp;hit='.$hit.'');
                        $next = sefRelToAbs(''.$url_prefix.'&amp;key='.$nid.'&amp;hit='.$hit.'');
                        $last = sefRelToAbs(''.$url_prefix.'&amp;key='.$last_img.'&amp;hit='.$hit.'');
                    }
                    ?>
                    <a href="<?php if($first_img==$key) echo "javascript:void(0);"; else echo $first; ?>" onmouseover="return overlib('<?php echo _ZOOM_FIRST_IMG; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/first_img.png" border="0" alt="" /></a>
                    <a href="<?php if($pid==$key) echo "javascript:void(0);"; else echo $previous; ?>" onmouseover="return overlib('<?php echo _ZOOM_PREV_IMG; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/prev.png" border="0" alt="" /></a>
                    <?php
                }
                if ($zoom->_CONFIG['slideshow'] && $zoom->isImage($zoom->_gallery->_images[$key]->_type)) { //Display play & stop buttons?
                    ?>
                    <a href="javascript:startSlideShow();" onmouseover="return overlib('<?php echo _ZOOM_PLAY; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/play.png" border="0" alt="" /></a>
                    <a href="javascript:endSlideShow();" onmouseover="return overlib('<?php echo _ZOOM_STOP; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/stop.png" border="0" alt="" /></a>
                    <?php
                }
                if ($zoom->_CONFIG['navbuttons']) {
                    ?>
                    <a href="<?php if($nid==$key) echo "javascript:void(0);"; else echo $next ?>" onmouseover="return overlib('<?php echo _ZOOM_NEXT_IMG; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/next.png" border="0" alt="" /></a>
                    <a href="<?php if($last_img==$key) echo "javascript:void(0);"; else echo $last ?>" onmouseover="return overlib('<?php echo _ZOOM_LAST_IMG; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/last_img.png" border="0" alt="" /></a>
                    <?php 
                }
                ?>
                </div>
                </td>
            </tr>
            </table>
            <?php
        } else {
    		?>
            <div class="zmg-slideshow-control">
            <?php
            if ($zoom->_CONFIG['slideshow'] && $zoom->isImage($zoom->_gallery->_images[$key]->_type)) {
                ?>
                <h3 class="zmg-slideshow-control-title"><?php echo _ZOOM_SLIDESHOW; ?></h3>
                <?php
            }
            if ($zoom->_CONFIG['navbuttons']) {
                if ($zoom->_CONFIG['popUpImages']) {
                    $first = $url_prefix.'&amp;key='.$first_img.'&amp;hit='.$hit.'&amp;uid='.$my->id;
                    $previous = $url_prefix.'&amp;key='.$pid.'&amp;hit='.$hit.'&amp;uid='.$my->id;
                    $next = $url_prefix.'&amp;key='.$nid.'&amp;hit='.$hit.'&amp;uid='.$my->id;
                    $last = $url_prefix.'&amp;key='.$last_img.'&amp;hit='.$hit.'&amp;uid='.$my->id;
                } else {
                    $first = sefRelToAbs(''.$url_prefix.'&amp;key='.$first_img.'&amp;hit='.$hit.'');
                    $previous = sefRelToAbs(''.$url_prefix.'&amp;key='.$pid.'&amp;hit='.$hit.'');
                    $next = sefRelToAbs(''.$url_prefix.'&amp;key='.$nid.'&amp;hit='.$hit.'');
                    $last = sefRelToAbs(''.$url_prefix.'&amp;key='.$last_img.'&amp;hit='.$hit.'');
                }
                ?>
                <a href="<?php if($first_img==$key) echo "javascript:void(0);"; else echo $first; ?>" onmouseover="return overlib('<?php echo _ZOOM_FIRST_IMG; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/first_img.png" border="0" alt="" /></a>
                <a href="<?php if($pid==$key) echo "javascript:void(0);"; else echo $previous; ?>" onmouseover="return overlib('<?php echo _ZOOM_PREV_IMG; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/prev.png" border="0" alt="" /></a>
                <?php
            }
            if ($zoom->_CONFIG['slideshow'] && $zoom->isImage($zoom->_gallery->_images[$key]->_type)) { //Display play & stop buttons?
                ?>
                <a href="javascript:startSlideShow();" onmouseover="return overlib('<?php echo _ZOOM_PLAY; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/play.png" border="0" alt="" /></a>
                <a href="javascript:endSlideShow();" onmouseover="return overlib('<?php echo _ZOOM_STOP; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/stop.png" border="0" alt="" /></a>
                <?php
            }
            if ($zoom->_CONFIG['navbuttons']) {
                ?>
                <a href="<?php if($nid==$key) echo "javascript:void(0);"; else echo $next ?>" onmouseover="return overlib('<?php echo _ZOOM_NEXT_IMG; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/next.png" border="0" alt="" /></a>
                <a href="<?php if($last_img==$key) echo "javascript:void(0);"; else echo $last ?>" onmouseover="return overlib('<?php echo _ZOOM_LAST_IMG; ?>');" onmouseout="return nd();"><img src="<?php echo $dir_prefix; ?>images/last_img.png" border="0" alt="" /></a>
                <?php
            }
            ?>
            <br />
            <?php
        }
    }
    
    function showTypeImage($use_tables = true, $img_path, $fullsize, &$viewsize_info, $key) {
        global $zoom;
        if ($use_tables) {
            if ($zoom->_CONFIG['zoomOn']) {
                ?>
                <div align="center" id="zImageBox">
                <div style="float:center" onmouseover="tjpZoom.on(event,<?php echo $viewsize_info[0].",".$viewsize_info[1].",'".$img_path."'".$fullsize.""; ?>);" onmousemove="tjpZoom.move(event);" onmouseout="tjpZoom.off();">
                <?php
            }
        	?>
        	<img src="<?php echo $img_path; ?>" alt="" id="zImage" name="zImage" style="padding:0;margin:0;border:0" />
        	<?php
        	if ($zoom->_CONFIG['zoomOn']) {
        		?>
        		</div>
        		</div>
        		<?php
        	}
        	?>        	
            <img src="<?php echo $img_path; ?>" alt="<?php echo $zoom->_gallery->_images[$key]->_name; ?>" id="slideImage" name="slideImage" style="display:none;"  title="<?php echo $zoom->_gallery->_images[$key]->_name; ?>" />
            <?php
        } else {
            ?>
            <div class="zmg-image-full-outer" style="width:<?php echo $viewsize_info[0]+23?>px;">
                <div class="img-shadow-full-border">
                    <div class="img-shadow-full">
                    	<div id="zImageBox">
                        	<div style="float:center;margin:0 auto;" <?php echo ($zoom->_CONFIG['zoomOn']) ? "onmouseover=\"tjpZoom.on(event,".$viewsize_info[0].",".$viewsize_info[1].",'".$img_path."'".$fullsize.");\" onmousemove=\"tjpZoom.move(event);\" onmouseout=\"tjpZoom.off();\"" : ""; ?>>
                        		<img src="<?php echo $img_path; ?>" alt="<?php echo $zoom->_gallery->_images[$key]->_name; ?>" id="zImage" name="zImage"  title="<?php echo $zoom->_gallery->_images[$key]->_name; ?>" />
                        	</div>
                        </div>
                        <img src="<?php echo $img_path; ?>" alt="<?php echo $zoom->_gallery->_images[$key]->_name; ?>" id="slideImage" name="slideImage" style="display:none;"  title="<?php echo $zoom->_gallery->_images[$key]->_name; ?>" />
                    </div>
                </div>
             </div>
            <?php
        }
        if ($zoom->_CONFIG['zoomOn']) {
			?>
			<div style="clear:both;"></div>
			<?php
		}
    }
    
    function showTypeDocument($thumbnail, $popup, $img_path) {
        ?>
		<a href="<?php echo $img_path; ?>" target="_blank"><img src="<?php echo $thumbnail; ?>" alt="" border="1" name="zImage" /></a>
		<?php
    }
    
    function showTypeMovie(&$movie, $img_path) {
        global $mosConfig_live_site, $zoom;
		if ($zoom->isRealMedia($movie->_type)) {
			?>
			<object classid="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" height="304" width="320">
				<param name="controls" value="ImageWindow" />
				<param name="autostart" value="true" />
				<param name="src" value="<?php echo $img_path; ?>" />
				<embed height="320" src="<?php echo $img_path; ?>" type="audio/x-pn-realaudio-plugin" width="304" controls="ImageWindow" autostart="true" /> 
			</object>
			<?php
		} elseif ($zoom->isQuicktime($movie->_type)) {
			?>
			<object classid="CLSID:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="304" width="320">
				<param name="src" value="<?php echo $img_path;?>" />
				<param name="autoplay" value="true" />
				<param name="controller" value="true" />
				<embed height="304" pluginspage="http://www.apple.com/quicktime/download/" src="<?php echo $img_path;?>" type="video/quicktime" width="320" controller="true" autoplay="true" /> 
			</object>
			<?php
	    } elseif ($zoom->isFlashvideo($movie->_type)) {
            ?>
            <object classid="CLSID:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://active.macromedia.com/flash2/cabs/swflash.cab#version=6,0,0,0" width="320" height="240">
                <param name="movie" value="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/flvplayer.swf?file=<?php echo $img_path;?>" />
                <param name="quality" value="high" />
                <param name="scale" value="exactfit" />
                <param name="menu" value="true" />
                <param name="bgcolor" value="#ffffff" />
                <embed src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/flvplayer.swf?file=<?php echo $img_path;?>" quality="high" scale="exactfit" menu="false" bgcolor="#ffffff" width="320" height="240" swliveconnect="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"> </embed>
            </object>
            <?php
		} else {
			?>
			<object id="MediaPlayer1" width="320" height="304" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" type="application/x-oleobject">
				<param name="URL" value="<?php echo $img_path;?>" />
				<param name="ShowControls" value="0"> 
				<embed src="<?php echo $img_path; ?>" height="304" width="320" border="0" type="application/x-mplayer2"/></embed>
			</object>
			<?php
		}
    }
    
    function showTypeAudio(&$audio, $key, $img_path, $popup) {
        global $mosConfig_live_site, $zoom;
		if ($zoom->isPlayable($audio->_type)) {
			?>
			<object type="application/x-shockwave-flash" data="<?php echo $mosConfig_live_site; ?>/components/com_zoom/www/zoomplayer.swf" width="300" height="300">
				<param name="movie" value="<?php echo $mosConfig_live_site; ?>/components/com_zoom/www/zoomplayer.swf">
				<param name="flashvars" value="config=<?php echo $mosConfig_live_site; ?>/components/com_zoom/www/config.xml&amp;file=<?php echo $mosConfig_live_site; ?>/components/com_zoom/www/playlist.php?keys=<?php echo $audio->_catid; ?>,<?php echo $key; ?>" />
				<param name="wmode" value="transparent" />
			</object>
			<?php
		} else {
			?>
			<img src="<?php echo $audio->_thumbnail; ?>" alt="" border="1" name="zImage"<?php echo ($popup && !$zoom->isAudio($audio->_type)) ? " onClick=\"javscript:window.close()\"" : ""; ?> />
			<br />
			<?php
		}
    }
    
    /**
     * Create a block that will contain descriptive data of a medium. The layout is similar to the WinXP/ Longhorn blocks.
     *
	 * @return string
	 * @param string $caption
	 * @param int $width
	 * @access public
	 */
	function createViewBlock($caption) {
        global $mosConfig_live_site, $zoom;
        
        $prefix = "z".ZMG_Template_View::get_rand_id(4); //create a unique prefix for each block...
        
        ?>
        <div class="blockContainer" id="<?php echo $prefix; ?>Container">
            <div class="blockHeader" id="<?php echo $prefix; ?>Header" onmouseover="Zoom.changeArrow('<?php echo $prefix."','white', '".$mosConfig_live_site; ?>');" onmouseout="Zoom.changeArrow('<?php echo $prefix."','grey', '".$mosConfig_live_site; ?>');"
        <?php
        if ($zoom->_CONFIG['animate_box']) {
            echo "\tonclick=\"Zoom.slide('".$prefix."', '".$mosConfig_live_site."');\">\n";
        } else {
            echo "\tonclick=\"Zoom.toggleDisplay('".$prefix."', '".$mosConfig_live_site."');\">\n";
        }
        ?>
            <a href="javascript:void(0);" class="blockHeader">
                <img src="<?php echo $mosConfig_live_site; ?>/components/com_zoom/www/images/blocks/arrow_up_grey.png" alt="" border="0" align="right" name="<?php echo $prefix; ?>Image" id="<?php echo $prefix; ?>Image" />
                <?php echo $caption; ?>
            </a>
            </div>
            <div class="blockBody" id="<?php echo $prefix; ?>Body">
        <?php
        return $prefix;
	}
	
	/**
	 * Generate the closing tags of the ViewBlock.
	 *
	 * @return void
	 * @see zoom::createViewBlock()
	 * @access public
	 */
	function finishViewBlock($def_state, $prefix) {
	    global $mosConfig_live_site, $zoom;
	    ?>
	       </div>
	    </div>
	    <br />
	    <?php
	    if ($def_state == 0) {
	        ?>
	        <script language="javascript" type="text/javascript">
	        <?php
	    	if ($zoom->_CONFIG['animate_box']) {
	    		echo ("Zoom.slide('".$prefix."', '".$mosConfig_live_site."');");
	    	} else {
	    	    echo ("Zoom.toggleDisplay('".$prefix."', '".$mosConfig_live_site."');");
	    	}
	    	?>
	    	Zoom.changeArrow('<?php echo $prefix."', 'white', '".$mosConfig_live_site; ?>');
	    	</script>
	    	<?php
	    }
	}
	
	/**
	 * Generate a random prefix for the viewBlock layer.
	 *
	 * @return string
	 * @param int $length
	 * @see ZMG_Template_View::createViewBlock()
	 * @access public
	 */
	function get_rand_id($length) {
		if ($length > 0) { 
			$rand_id = "";
			for ($i = 1; $i <= $length; $i++) {
				mt_srand((double)microtime() * 1000000);
				$num = mt_rand(1, 36);
				$rand_id .= ZMG_Template_View::assign_rand_value($num);
			}
		}
		return $rand_id;
	}
	
	/**
	 * Turn a integer value into a #random# string.
	 *
	 * @return void
	 * @param int $num
	 * @see ZMG_Template_View::get_rand_id()
	 * @access public
	 */
	function assign_rand_value($num) {
		switch ($num) {
			case "1":
			 $rand_value = "a";
			break;
			case "2":
			 $rand_value = "b";
			break;
			case "3":
			 $rand_value = "c";
			break;
			case "4":
			 $rand_value = "d";
			break;
			case "5":
			 $rand_value = "e";
			break;
			case "6":
			 $rand_value = "f";
			break;
			case "7":
			 $rand_value = "g";
			break;
			case "8":
			 $rand_value = "h";
			break;
			case "9":
			 $rand_value = "i";
			break;
			case "10":
			 $rand_value = "j";
			break;
			case "11":
			 $rand_value = "k";
			break;
			case "12":
			 $rand_value = "l";
			break;
			case "13":
			 $rand_value = "m";
			break;
			case "14":
			 $rand_value = "n";
			break;
			case "15":
			 $rand_value = "o";
			break;
			case "16":
			 $rand_value = "p";
			break;
			case "17":
			 $rand_value = "q";
			break;
			case "18":
			 $rand_value = "r";
			break;
			case "19":
			 $rand_value = "s";
			break;
			case "20":
			 $rand_value = "t";
			break;
			case "21":
			 $rand_value = "u";
			break;
			case "22":
			 $rand_value = "v";
			break;
			case "23":
			 $rand_value = "w";
			break;
			case "24":
			 $rand_value = "x";
			break;
			case "25":
			 $rand_value = "y";
			break;
			case "26":
			 $rand_value = "z";
			break;
			case "27":
			 $rand_value = "0";
			break;
			case "28":
			 $rand_value = "1";
			break;
			case "29":
			 $rand_value = "2";
			break;
			case "30":
			 $rand_value = "3";
			break;
			case "31":
			 $rand_value = "4";
			break;
			case "32":
			 $rand_value = "5";
			break;
			case "33":
			 $rand_value = "6";
			break;
			case "34":
			 $rand_value = "7";
			break;
			case "35":
			 $rand_value = "8";
			break;
			case "36":
			 $rand_value = "9";
			break;
		}
		return $rand_value;
	}
    
    function showPropertiesBox($use_tables = true, $img_path, $key) {
        global $zoom;
        if($zoom->_CONFIG['showName'] || $zoom->_CONFIG['showFilename'] || $zoom->_CONFIG['showKeywords'] || $zoom->_CONFIG['showDate'] || $zoom->_CONFIG['showDescr'] || $zoom->_CONFIG['showUsername'] || $zoom->_CONFIG['showHits'] || $zoom->_CONFIG['ratingOn']) {
	        $prefix = ZMG_Template_View::createViewBlock(_ZOOM_PROPERTIES);
			if ($use_tables) {
	            ?>
	            <table border="0" cellspacing="0" cellpadding="3">
	            <?php
	            if ($zoom->_CONFIG['showName']) {
	            ?>
	            <tr>
	                <td align="left" width="100"><?php echo _ZOOM_IMGNAME; ?>: </td>
	                <td align="left"><?php echo $zoom->_gallery->_images[$key]->_name; ?></td>
	            </tr>
	            <?php
	            }
	            if ($zoom->_CONFIG['showFilename']) {
	            ?>
	            <tr>
	                <td align="left"><?php echo _ZOOM_FILENAME; ?>: </td>
	                <td align="left">
	                <?php
	                if ($zoom->isDocument($zoom->_gallery->_images[$key]->_type)) {
	                    echo "<a href=\"".$img_path."\" target=\"_TOP\">".$zoom->_gallery->_images[$key]->_filename."</a> "._ZOOM_CLICKDOCUMENT;
	                } else {
	                    echo $zoom->_gallery->_images[$key]->_filename;
	                }
	                ?>
	                </td>
	            </tr>
	            <?php
	            }
	            if ($zoom->_CONFIG['showKeywords']) {
	            ?>
	            <tr>
	                <td align="left"><?php echo _ZOOM_KEYWORDS; ?>: </td>
	                <td align="left"><?php echo $zoom->_gallery->_images[$key]->getKeywords(2); ?></td>
	            </tr>
	            <?php
	            }
	            if ($zoom->_CONFIG['showDate']) {
	            ?>
	            <tr>
	                <td align="left"><?php echo _ZOOM_DATE; ?>: </td>
	                <td align="left"><?php echo $zoom->convertDate($zoom->_gallery->_images[$key]->_date); ?></td>
	            </tr>
	            <?php
	            }
	            if ($zoom->_CONFIG['showUsername']) {
	            ?>
	            <tr>
	                <td align="left"><?php echo _ZOOM_UNAME; ?>: </td>
	                <td align="left"><?php echo $zoom->_gallery->_images[$key]->getUsername(2); ?></td>
	            </tr>
	            <?php
	            }
	            if ($zoom->_CONFIG['showDescr']) {
	            ?>
	            <tr>
	                <td align="left"><?php echo _ZOOM_DESCRIPTION; ?>: </td>
	                <td align="left"><?php echo $zoom->_gallery->_images[$key]->_descr; ?></td>
	            </tr>
	            <?php
	            }
	            if ($zoom->_CONFIG['showHits']) {
	            ?>
	            <tr>
	                <td align="left"><?php echo _ZOOM_HITS; ?>: </td>
	                <td align="left"><?php echo $zoom->_gallery->_images[$key]->_hits; ?></td>
	            </tr>
	            <?php
	            }
	            if ($zoom->_CONFIG['ratingOn']) {
	            ?>
	            <tr>
	                <td align="left"><?php echo _ZOOM_RATING; ?></td>
	                <td align="left">
	                <?php
	                echo $zoom->_gallery->_images[$key]->getStars();
	                ?>
	                </td>
	            </tr>
	            <?php
	            }
	            ?>
	            </table>
	            <?php
	        } else {
	            ?>
	            <div class="detail-box">
	            <?php
	            if ($zoom->_CONFIG['showName']) {
	            ?>
	            <span class="detail-box-img-name-title"><?php echo _ZOOM_IMGNAME; ?>: </span>
	            <span class="detail-box-img-name"><?php echo $zoom->_gallery->_images[$key]->_name; ?></span><br />
	            <?php
	            }
	            if ($zoom->_CONFIG['showFilename']) {
	            ?>
	            <span class="detail-box-filename-title"><?php echo _ZOOM_FILENAME; ?>: </span>
	            <span class="detail-box-filename">
	            <?php
	            if ($zoom->isDocument($zoom->_gallery->_images[$key]->_type)) {
	                echo "<a href=\"".$img_path."\" target=\"_top\">".$zoom->_gallery->_images[$key]->_filename."</a> "._ZOOM_CLICKDOCUMENT;
	            } else {
	                echo $zoom->_gallery->_images[$key]->_filename;
	            }
	            ?>
	            </span><br />
	            <?php
	            }
	            if ($zoom->_CONFIG['showKeywords']) {
	            ?>
	            <span class="detail-box-keyword-title"><?php echo _ZOOM_KEYWORDS; ?>: </span>
	            <span class="detail-box-keyword"><?php echo $zoom->_gallery->_images[$key]->getKeywords(2); ?></span><br />
	            <?php
	            }
	            if ($zoom->_CONFIG['showDate']) {
	            ?>
	            <span class="detail-box-date-title"><?php echo _ZOOM_DATE; ?>: </span>
	            <span class="detail-box-date"><?php echo $zoom->convertDate($zoom->_gallery->_images[$key]->_date); ?></span><br />
	            <?php
	            }
	            if ($zoom->_CONFIG['showUsername']) {
	            ?>
	            <span class="detail-box-username-title"><?php echo _ZOOM_UNAME; ?>: </span>
	            <span class="detail-box-username"><?php echo $zoom->_gallery->_images[$key]->getUsername(2); ?></span><br />
	            <?php
	            }
	            if ($zoom->_CONFIG['showDescr']) {
	            ?>
	            <span class="detail-box-desc-title"><?php echo _ZOOM_DESCRIPTION; ?>: </span>
	            <span class="detail-box-desc"><?php echo $zoom->_gallery->_images[$key]->_descr; ?></span><br />
	            <?php
	            }
	            if ($zoom->_CONFIG['showHits']) {
	            ?>
	            <span class="detail-box-hits-title"><?php echo _ZOOM_HITS; ?>: </span>
	            <span class="detail-box-hits"><?php echo $zoom->_gallery->_images[$key]->_hits; ?></span><br />
	            <?php
	            }
	            if ($zoom->_CONFIG['ratingOn']) {
	            ?>
	            <span class="detail-box-ratings-title"><?php echo _ZOOM_RATING; ?>: </span>
	            <div class="detail-box-ratings">
	            <?php echo $zoom->_gallery->_images[$key]->getStars();?>
	            </div><br />
	            <?php
	            }
	            ?>
	            </div>
	            <?php
	        }
        	ZMG_Template_View::finishViewBlock($zoom->_CONFIG['properties_state'], $prefix);
        }
    }
    
    function showCommentsBox($use_tables = true, $dir_prefix, $key) {
        global $zoom, $Itemid, $my;
        if ($use_tables) {
        ?>
        <table border="0" cellpadding="3" cellspacing="0" width="90%">
        <?php
        }
        if (empty($zoom->_gallery->_images[$key]->_comments)) {
            if ($use_tables) {
            	echo ("<tr>\n"
                 . "\t<td>&nbsp;</td>\n"
                 . "\t<td align=\"center\" valign=\"bottom\">"._ZOOM_NO_COMMENTS."</td>\n"
                 . "</tr>\n");
            } else {
                echo ("\n"
                 . "\t<div class=\"zmg-comment-none\">"._ZOOM_NO_COMMENTS."</div>\n"
                 . "\n");
            }
        } else {
            // Display comments found in the database.
            $count = '';
            foreach ($zoom->_gallery->_images[$key]->_comments as $comment) {
                if ($count>1) {
                    $colour=$zoom->_tabclass[0];
                    $count=0;
                } else {
                    $colour=$zoom->_tabclass[1];
                }
                $cmtrow = "";
                if ($use_tables) {
                	$cmtrow = "<tr><td width=\"125\" align=\"left\" valign=\"top\">".$comment->getName().":&nbsp;</td><td align=\"left\"><font color=\"#ff0000\">".$comment->getComment($dir_prefix)."</font>&nbsp;(".$comment->getDate().")</td>\n";
                } else {
                    $cmtrow = "<div class=\"zmg-comment-block\">\n<div class=\"comments-added-date\">\n".$comment->getDate()."</div>\n<div class=\"zmg-comments-added-name\"><img src=\"".$dir_prefix."images/comment.png\" border=\"0\" title=\"Comment\" alt=\"Image of comment\" />".$comment->getName()." <span class=\"zmg-comments-says\">"._ZOOM_SAYS.":</span> </div>\n<div class=\"zmg-comments-added-text\">".$comment->getComment($dir_prefix)."</div>\n</div>\n";
                }
                if ($zoom->_isAdmin) {
                	if ($use_tables) {
                		$cmtrow .= "<td width=\"50\">";
                	}
                	if (!$zoom->_CONFIG['popUpImages']) {
                        $cmtrow .= "<a href=\"index.php?option=com_zoom&amp;page=view&amp;Itemid=".$Itemid;
                    } else {
                        $cmtrow .= "<a href=\"view.php?popup=1&amp;uid=".$my->id;
                    }
                    $cmtrow .= "&amp;catid=".$zoom->_gallery->_id."&amp;key=".$key."&amp;delComment=".$comment->getId()."&amp;isAdmin=".$zoom->_isAdmin."\"><img src=\"".$dir_prefix."images/delete.png\" border=\"0\" title=\""._ZOOM_DELETE."\" alt=\"\" /></a>";
                    if ($use_tables) {
                    	$cmtrow .= "</td>";
                    }
                }
                if ($use_tables) {
                	$cmtrow .= "</tr>\n";
                }
                echo $cmtrow;
                $count++;
            }
        }
        if ($use_tables) {
        ?>
        </table>
        <?php
        }
    }
    
    function showCommentsForm($use_tables = true, $cmt_action, $dir_prefix, $popup, $key) {
        global $zoom, $my;
        ?>
        <form method="post" name="post" action="<?php echo $cmt_action ?>" onsubmit="MM_validateForm('uname','','R','comment','','R');return document.MM_returnValue">
        <?php
        if ($use_tables) {
        	?>
        	<table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="left" valign="top" width="80"><?php echo _ZOOM_YOUR_NAME;?>:&nbsp;</td>
                <td align="left" valign="top">
                <?php
                if (!empty($my->username)) {
                    ?>
                    <input type="hidden" name="uname" value="<?php echo $my->username;?>" />
                    <?php
                    echo $my->username;
                } else {
                    ?>
                    <input class="inputbox" type="text" name="uname" size="25" value="" />
                    <?php
                }
                ?>
                <br />
                </td>
            </tr>
            <tr>
                <td align="left" valign="top" width="80"><?php echo _ZOOM_COMMENTS;?>: </td>
                <td align="left" valign="top">
                    <textarea name="comment" class="inputbox" rows="2" cols="35" style="white-space:wrap;" onselect="Zoom.storeCaret(this);" onclick="Zoom.storeCaret(this);" onkeyup="Zoom.storeCaret(this);"></textarea>
                    <input type="hidden" name="popup" value="<?php echo $popup;?>" />
                    <input type="hidden" name="key" value="<?php echo $key;?>" />
                    <input type="hidden" name="isAdmin" value="<?php echo $zoom->_isAdmin; ?>" />
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="submit" value="<?php echo _ZOOM_ADD;?>" class="button" /></td>
            </tr>
            </table>
            <table border="0" cellspacing="5" cellpadding="0">
            <tr>
                <td width="80">&nbsp;</td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':D')"><img title="Very Happy" src="<?php echo $dir_prefix; ?>images/smilies/icon_biggrin.gif" alt="Very Happy" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':)')"><img title="Smile" src="<?php echo $dir_prefix; ?>images/smilies/icon_smile.gif" alt="Smile" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':(')"><img title="Sad" src="<?php echo $dir_prefix; ?>images/smilies/icon_sad.gif" alt="Sad" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':o')"><img title="Surprised" src="<?php echo $dir_prefix; ?>images/smilies/icon_surprised.gif" alt="Surprised" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':shock:')"><img title="Shocked" src="<?php echo $dir_prefix; ?>images/smilies/icon_eek.gif" alt="Shocked" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':?')"><img title="Confused" src="<?php echo $dir_prefix; ?>images/smilies/icon_confused.gif" alt="Confused" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon('8)')"><img title="Cool" src="<?php echo $dir_prefix; ?>images/smilies/icon_cool.gif" alt="Cool" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':lol:')"><img title="Laughing" src="<?php echo $dir_prefix; ?>images/smilies/icon_lol.gif" alt="Laughing" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':x')"><img title="Mad" src="<?php echo $dir_prefix; ?>images/smilies/icon_mad.gif" alt="Mad" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':P')"><img title="Razz" src="<?php echo $dir_prefix; ?>images/smilies/icon_razz.gif" alt="Razz" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':oops:')"><img title="Embarassed" src="<?php echo $dir_prefix; ?>images/smilies/icon_redface.gif" alt="Embarassed" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':cry:')"><img title="Crying or Very sad" src="<?php echo $dir_prefix; ?>images/smilies/icon_cry.gif" alt="Crying or Very sad" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':evil:')"><img title="Evil or Very Mad" src="<?php echo $dir_prefix; ?>images/smilies/icon_evil.gif" alt="Evil or Very Mad" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':twisted:')"><img title="Twisted Evil" src="<?php echo $dir_prefix; ?>images/smilies/icon_twisted.gif" alt="Twisted Evil" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':roll:')"><img title="Rolling Eyes" src="<?php echo $dir_prefix; ?>images/smilies/icon_rolleyes.gif" alt="Rolling Eyes" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':wink:')"><img title="Wink" src="<?php echo $dir_prefix; ?>images/smilies/icon_wink.gif" alt="Wink" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':!:')"><img title="Exclamation" src="<?php echo $dir_prefix; ?>images/smilies/icon_exclaim.gif" alt="Exclamation" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':?:')"><img title="Question" src="<?php echo $dir_prefix; ?>images/smilies/icon_question.gif" alt="Question" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':idea:')"><img title="Idea" src="<?php echo $dir_prefix; ?>images/smilies/icon_idea.gif" alt="Idea" border="0" /></a></td>
                <td width="15" height="15"><a href="javascript:Zoom.emoticon(':arrow:')"><img title="Arrow" src="<?php echo $dir_prefix; ?>images/smilies/icon_arrow.gif" alt="Arrow" border="0" /></a></td>
            </tr>
            </table>
        	<?php
        } else {
        	?>
            <div class="zmg-comments">
                <h4 class="zmg-comment-intro"><?php echo _ZOOM_COMMENTS_INTRO;?></h4>
                <span class="zmg-comments-name"><label for="zmg-comments-name"><?php echo _ZOOM_YOUR_NAME;?>:  </label></span>
                <?php
                if (!empty($my->username)) {
                    ?>
                    <input type="hidden" name="uname" id="zmg-comments-name" value="<?php echo $my->username;?>" />
                    <?php
                    echo $my->username;
                } else {
                    ?>
                    <input class="inputbox-zmg-comment" type="text" id="zmg-comments-name" name="uname" size="25" value="" style="background-color:white;" onfocus="hilite(this)" onblur="delite(this)" />
                    <?php
                }
                ?>
                <br />
                <p>
                <span class="zmg-comments-textarea"><label for="zmg-comments-text"><?php echo _ZOOM_COMMENTS;?>: </label></span>	
                <textarea name="comment" id="zmg-comments-text" class="inputbox-zmg-comment" rows="6" cols="32" style="background-color:white;" onselect="Zoom.storeCaret(this);" onclick="Zoom.storeCaret(this);" onkeyup="Zoom.storeCaret(this);"></textarea>
                <input type="hidden" name="popup" value="<?php echo $popup;?>" />
                <input type="hidden" name="key" value="<?php echo $key;?>" />
                <input type="hidden" name="isAdmin" value="<?php echo $zoom->_isAdmin; ?>" />
                </p>
                <span class="zmg-comments-emoticons">
                    <a href="javascript:Zoom.emoticon(':D')"><img title="Very Happy" src="<?php echo $dir_prefix; ?>images/smilies/icon_biggrin.gif" alt="Very Happy" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':)')"><img title="Smile" src="<?php echo $dir_prefix; ?>images/smilies/icon_smile.gif" alt="Smile" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':(')"><img title="Sad" src="<?php echo $dir_prefix; ?>images/smilies/icon_sad.gif" alt="Sad" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':o')"><img title="Surprised" src="<?php echo $dir_prefix; ?>images/smilies/icon_surprised.gif" alt="Surprised" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':shock:')"><img title="Shocked" src="<?php echo $dir_prefix; ?>images/smilies/icon_eek.gif" alt="Shocked" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':?')"><img title="Confused" src="<?php echo $dir_prefix; ?>images/smilies/icon_confused.gif" alt="Confused" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon('8)')"><img title="Cool" src="<?php echo $dir_prefix; ?>images/smilies/icon_cool.gif" alt="Cool" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':lol:')"><img title="Laughing" src="<?php echo $dir_prefix; ?>images/smilies/icon_lol.gif" alt="Laughing" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':x')"><img title="Mad" src="<?php echo $dir_prefix; ?>images/smilies/icon_mad.gif" alt="Mad" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':P')"><img title="Razz" src="<?php echo $dir_prefix; ?>images/smilies/icon_razz.gif" alt="Razz" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':oops:')"><img title="Embarassed" src="<?php echo $dir_prefix; ?>images/smilies/icon_redface.gif" alt="Embarassed" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':cry:')"><img title="Crying or Very sad" src="<?php echo $dir_prefix; ?>images/smilies/icon_cry.gif" alt="Crying or Very sad" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':evil:')"><img title="Evil or Very Mad" src="<?php echo $dir_prefix; ?>images/smilies/icon_evil.gif" alt="Evil or Very Mad" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':twisted:')"><img title="Twisted Evil" src="<?php echo $dir_prefix; ?>images/smilies/icon_twisted.gif" alt="Twisted Evil" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':roll:')"><img title="Rolling Eyes" src="<?php echo $dir_prefix; ?>images/smilies/icon_rolleyes.gif" alt="Rolling Eyes" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':wink:')"><img title="Wink" src="<?php echo $dir_prefix; ?>images/smilies/icon_wink.gif" alt="Wink" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':!:')"><img title="Exclamation" src="<?php echo $dir_prefix; ?>images/smilies/icon_exclaim.gif" alt="Exclamation" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':?:')"><img title="Question" src="<?php echo $dir_prefix; ?>images/smilies/icon_question.gif" alt="Question" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':idea:')"><img title="Idea" src="<?php echo $dir_prefix; ?>images/smilies/icon_idea.gif" alt="Idea" border="0" /></a>&nbsp;
                    <a href="javascript:Zoom.emoticon(':arrow:')"><img title="Arrow" src="<?php echo $dir_prefix; ?>images/smilies/icon_arrow.gif" alt="Arrow" border="0" /></a>
                </span>
                <div class="zmg-comments-submit">
                    <input type="submit" name="submit" value="<?php echo _ZOOM_ADD;?>" class="button" />
                </div>	
            </div>
        	<?php
        }
        ?>        
        </form>
        <?php
    }
}
?>