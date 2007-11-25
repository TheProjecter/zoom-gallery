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
| Filename: template.gallery.php                                      |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:template.gallery.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @subpackage Template
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class ZMG_Template_Gallery {
    function showHeader($use_tables = true, $Itemid, $title = "zOOm Media Gallery", $show_logo, $show_search, $show_keywords, &$keywords) {
        global $mosConfig_live_site, $mainframe;
        if ($use_tables) {
        	?>
        	<table border="0" width="100%" cellspacing="0" cellpadding="0">
        	<tr>
        		<td align="left" class="componentheader" width="30%"><h3><?php echo $title; ?></h3></td>
        		<?php
        		if ($show_search && $show_keywords) {
        		?>
        		<td align="right" valign="bottom" class="componentheader">
        			<div align="right">
        				<form action="<?php echo sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=search&amp;type=quicksearch"); ?>" method="post" name="browse">
        				<?php
        				ZMG_Template_Main::createKeywordsDropdown($keywords, 'sstring', '<option value="">&gt;&gt;'._ZOOM_SEARCH_KEYWORD.'&lt;&lt;</option>', 1);
        				?>
        				</form>
        			</div>
        		</td>
        		<?php
        		}
        		?>
        		<td align="right" valign="bottom" class="componenentheader" width="200">
        			<div align="right">
        			<?php if ($show_logo) { ?>
        				<a href="http://www.zoomfactory.org" target="_blank"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/zoom_logo_small.gif" border="0" alt="" /></a>
        			<?php
        			}
        			if ($show_search) {
        			?>
        			<form name="searchzoom" action="<?php echo sefReltoAbs('index.php?option=com_zoom&amp;Itemid='. $Itemid) ?>" target="_top" method="post">
        			<input type="hidden" name="option" value="com_zoom" />
        			<input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
        			<input type="hidden" name="page" value="search" />
        			<input type="hidden" name="type" value="quicksearch" />
        			<input type="hidden" name="sorting" value="3" />
        			<input type="text" name="sstring" onblur="if(this.value=='') this.value='<?php echo _ZOOM_SEARCH_BOX;?>';" onfocus="if(this.value=='<?php echo _ZOOM_SEARCH_BOX;?>') this.value='';" value="<?php echo _ZOOM_SEARCH_BOX;?>" class="inputbox" />
        			<a href="javascript:document.forms.searchzoom.submit();">&nbsp;<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/find.png" border="0" alt="" width="16" height="16" /></a>
        			</form>
        			</div>
        			<?php
        			}
        			?>
        		</td>
        	</tr>
        	</table>
        	<?php
        } else {
            ?>
            <h2 class="componentheading"><?php echo $title; ?></h2>
            <?php
            if ($show_logo) {
                ?>
                <div class="zoom-logo">
                <a href="http://www.zoomfactory.org/" target="_blank"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/zoom_logo_small.gif" border="0" alt="" /></a>
                </div>
                <?php
            }
            if ($show_search && $show_keywords) {
                ?>
                <div class="zoom-keywords">
                    <form action="index.php?option=com_zoom&amp;Itemid=<?php echo $Itemid; ?>&amp;page=search&amp;type=quicksearch" method="post" name="browse">				
                    <?php
                    ZMG_Template_Main::createKeywordsDropdown($keywords, 'sstring', '<option value="" style="background-color:white;">&gt;&gt;'._ZOOM_SEARCH_KEYWORD.'&lt;&lt;</option>', 1);
                    ?>
                    </form>	
                </div>
                <?php
            }
            if ($show_search) {
                ?>
                <div class="zoom-search">	
                    <form name="searchzoom" action="<?php echo sefReltoAbs('index.php?option=com_zoom&amp;Itemid='. $Itemid); ?>" target="_top" method="post">
                    <input type="hidden" name="option" value="com_zoom" />
                    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
                    <input type="hidden" name="page" value="search" />
                    <input type="hidden" name="type" value="quicksearch" />
                    <input type="hidden" name="sorting" value="3" />
                    <span class="search-zg"><label for="search-this-gallery"><?php echo _ZOOM_SEARCH_BOX; ?></label></span><br />
                    <input type="text" name="sstring" id="search-this-gallery" style="background-color:white;" alt="Search this gallery" value="" class="inputbox" />
                    <input type="submit" value="Go" class="button" />
                    </form>
                </div>
                <?php
            }
            ?>
            <div class="clr"></div>
            <?php
        }
    }
    
    function showGalleries(&$galleries, $use_tables = true, $Itemid, $no_columns, $gallery_prefix, $show_catimg, $show_metabox) {
        global $zoom;
		if ($use_tables) {
        	?>
        	<br />
            <table border="0" width="100%" cellspacing="0" cellpadding="5">
            <tr>
        	<?php
        } else {
            ?>
            <div class="zmg-album">
            <?php
        }
        foreach ($galleries as $gallery) {
        	if ($gallery->isMember()) {
    
    		    ZMG_Template_Gallery::showGalleryListButton($use_tables, $Itemid, $gallery, $no_columns, $gallery_prefix, $show_catimg, $show_metabox);
    
    		}
        }
        if ($use_tables) {
        	?>
            </tr>
            </table>
        	<?php
        } else {
            echo "</div>";  
        }
    }
    
    function showSubGalleries(&$subgalleries, $use_tables = true, $Itemid, $no_columns, $gallery_prefix, $show_catimg, $show_metabox) {
        global $zoom;
        if($subgalleries) {
            $i=0;
            foreach ($subgalleries as $subgallery) {
	        	if ($subgallery->isMember()) {
	               $i++;
	        	}
	        }
	        if($i > 0) {
    			if ($use_tables) {
    	        	?>
    	            <table border="0" cellspacing="0" cellpadding="3" width="80%">
    	            <tr>
    					<td colspan="<?php echo $no_columns ?>">
    						<hr />
    					</td>
    				</tr>
    				<tr>
    			<?php
    	        } else {
    	        ?>
    			<hr />
    	        <div class="zmg-sub-album">
    	            <?php
    	        }
    	        foreach ($subgalleries as $subgallery) {
    	        	if ($subgallery->isMember()) {
    	    
    	    		    ZMG_Template_Gallery::showSubGalleryListButton($use_tables, $Itemid, $subgallery, $no_columns, $gallery_prefix, $show_catimg, $show_metabox);
    	    
    	    		}
    	        }
    	        if ($use_tables) {
    	        ?>
    				</tr>
    				<tr>
    					<td colspan="<?php echo $no_columns ?>">
    						<hr />
    					</td>
    				</tr>
                </table>
    	        	<?php
    	        } else {
    			?>
    			</div>
    			<div class="clr"></div>
    			<hr />
    			<?php
    	        }   
    	    }
	    }
    }
    
    function showGalleryListButton($use_tables = true, $Itemid, &$gallery, $no_columns, $gallery_prefix, $show_catimg, $show_metabox) {
        global $zoom, $backend;
        if ($show_catimg) {
            $gallery->setCatImg();
        }
        //select the first image from the gallery handled by the loop...
        if ($show_metabox) {
            // display category info, including image...
            $img_num = $gallery->getNumOfImages();
            $subcat_num = $gallery->getNumOfSubCats();
            $subcat_html = ($subcat_num <= 0) ? "" : ", ".$subcat_num." "._ZOOM_SUBGALLERIES;
        }
        if ($use_tables) {
            if ($zoom->_counter >= $no_columns) {
                echo "</tr><tr>";
                $zoom->_counter = 0;
            }
            if ($show_catimg) {
                ?>
                <td align="left" valign="top" width="<?php echo number_format((100/$no_columns), 0, '.', ''); ?>%"><a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$gallery->_id);?>" <?php echo ($show_metabox) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$subcat_html."', CAPTION, '".$zoom->overlibfix($gallery->_name)."');\" onmouseout=\"return nd();\"" : "";?>>
                <img border="0" hspace="0" src="<?php echo (empty($gallery->_cat_img->_thumbnail)) ? 'components/com_zoom/www/images/noimg.gif' : $gallery->_cat_img->_thumbnail;?>" align="right" alt="" />
                <?php
            } else {
                ?>
                <td align="left" valign="top" width="50%"><a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$gallery->_id);?>" <?php echo ($show_metabox) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$subcat_html."', CAPTION, '".$zoom->overlibfix($gallery->_name)."');\" onmouseout=\"return nd();\"" : "";?>>
                <?php
            }
            echo $gallery_prefix.$gallery->_name."</a><br />".$gallery->_descr;
            if (!$gallery->_published) {
                echo "<br /><font color=\"red\">(unpublished)</font>";
            }
            echo "</td>\n";
            $zoom->_counter++;
        } else {
            if ($zoom->_counter >= $no_columns) {
                echo '<br /><div class="clr"></div><br />';
				$zoom->_counter = 0;
            }
            if ($show_catimg) {
                ?>
                <div class="zmg-album-inner" style="width:<?php echo number_format(((100/$no_columns)-1), 0, '.', ''); ?>%">
                <div class="zmg-album-image">
                <a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$gallery->_id);?>" <?php echo ($show_metabox) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$subcat_html."', CAPTION, '".$zoom->overlibfix($gallery->_name)."');\" onmouseout=\"return nd();\"" : "";?>>
                <img src="<?php echo (empty($gallery->_cat_img->_thumbnail)) ? 'components/com_zoom/www/images/noimg.gif' : $gallery->_cat_img->_thumbnail;?>" class="zmg-image-of-album" alt="" /><br />
                <?php
            } else {
                ?>
                <div class="zmg-album-inner" style="width:<?php echo number_format(((100/$no_columns)-1), 0, '.', ''); ?>%">
                <div class="zmg-album-image">
				<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$gallery->_id);?>" <?php echo ($show_metabox) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$subcat_html."', CAPTION, '".$zoom->overlibfix($gallery->_name)."');\" onmouseout=\"return nd();\"" : "";?>>
                <?php
            }
            echo $gallery_prefix.$gallery->_name."</a><br />".$gallery->_descr . "</div>";
            if (!$gallery->_published) {
                echo "<br /><span class=\"zmg-unpublished\">(unpublished)</span>";
            }
            echo "</div>\n";
            $zoom->_counter++;
        }
    }
    
    
    
    function showSubGalleryListButton($use_tables = true, $Itemid, &$subgallery, $no_columns, $gallery_prefix, $show_catimg, $show_metabox) {
        global $zoom, $backend;
        if ($show_catimg) {
            $subgallery->setCatImg();
        }
        //select the first image from the gallery handled by the loop...
        if ($show_metabox) {
            // display category info, including image...
            $img_num = $subgallery->getNumOfImages();
            $subcat_num = $subgallery->getNumOfSubCats();
            $subcat_html = ($subcat_num <= 0) ? "" : ", ".$subcat_num." "._ZOOM_SUBGALLERIES;
        }
        if ($use_tables) {
            if ($zoom->_counter >= $no_columns) {
                echo "</tr><tr>";
                $zoom->_counter = 0;
            }
            if ($show_catimg) {
                ?>
                <td width="<?php echo number_format((100/$no_columns), 0, '.', ''); ?>%" valign="top" align="left"><a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$subgallery->_id); ?>" <?php echo ($show_metabox) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$zoom->overlibfix($subcat_html)."', CAPTION, '".$zoom->overlibfix($subgallery->_name)."');\" onmouseout=\"return nd();\"" : "";?>>
                <img border="0" hspace="0" src="<?php echo (empty($subgallery->_cat_img->_thumbnail)) ? 'components/com_zoom/www/images/folder.png' : $subgallery->_cat_img->_thumbnail;?>" align="left" alt="" />
                <?php
            } else {
                ?>
                <td width="<?php echo number_format((100/$no_columns), 0, '.', ''); ?>%" valign="top" align="left"><a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$subgallery->_id); ?>" <?php echo ($show_metabox) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$zoom->overlibfix($subcat_html)."', CAPTION, '".$zoom->overlibfix($subgallery->_name)."');\" onmouseout=\"return nd();\"" : "";?>>
                <?php
            }
            echo $gallery_prefix.$subgallery->_name."</a><br />".$subgallery->_descr;
            if (!$subgallery->_published) {
                echo "<br /><span class=\"zmg-unpublished\">(unpublished)</span>";
            }
            echo "</td>\n";
            $zoom->_counter++;
        } else {
            if ($zoom->_counter >= $no_columns) {
                echo '<div class="clr"></div>';
				$zoom->_counter = 0;
            }
            if ($show_catimg) {
                ?>
                <div class="zmg-sub-album-inner" style="width:<?php echo number_format(((100/$no_columns)-1), 0, '.', ''); ?>%">
                <div class="zmg-sub-album-image">
                <a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$subgallery->_id); ?>" <?php echo ($show_metabox) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$zoom->overlibfix($subcat_html)."', CAPTION, '".$zoom->overlibfix($subgallery->_name)."');\" onmouseout=\"return nd();\"" : "";?>>
				<img src="<?php echo (empty($subgallery->_cat_img->_thumbnail)) ? 'components/com_zoom/www/images/folder.png' : $subgallery->_cat_img->_thumbnail;?>" class="zmg-image-of-sub-album" alt="" /><br />
				
				<?php
            } else {
                ?>
				<div class="zmg-sub-album-inner" style="width:<?php echo number_format(((100/$no_columns)-1), 0, '.', ''); ?>%">
				<div class="zmg-sub-album-image">
				<a href="<?php echo sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;catid=".$subgallery->_id); ?>" <?php echo ($show_metabox) ? "onmouseover=\"return overlib('".$img_num." "._ZOOM_IMAGES.$zoom->overlibfix($subcat_html)."', CAPTION, '".$zoom->overlibfix($subgallery->_name)."');\" onmouseout=\"return nd();\"" : "";?>>
                <?php
            }
			echo $gallery_prefix.$subgallery->_name."</a><br />".$subgallery->_descr . "</div>";
            if (!$subgallery->_published) {
                echo "<br /><span class=\"zmg-unpublished\">(unpublished)</span>";
            }
            echo "</div>\n";
            $zoom->_counter++;
        }
    }
    
    
    function showPasswordForm($Itemid, $catid, &$tabclass) {
        ?>
        <div align="center">
        <form name="form1" method="post" action="index.php">
        <table cellspacing="0" cellpadding="0" border="0">
        <tr>
        	<td class="sectiontableheader" colspan="2">
        		<?php echo _ZOOM_PASS_REQUIRED;?>
        	</td>
        </tr>
        <tr height="50">
        	<td class="<?php echo $tabclass[0];?>">
        		<?php echo _ZOOM_PASS;?>:
        	</td>
        	<td class="<?php echo $tabclass[0];?>">
        		<input name="catpass" type="password" size="10" />
        	</td>
        </tr>
        <tr>
        	<td class="<?php echo $tabclass[1];?>" colspan="2" align="center">
        		<div align="center">
        		<input type="submit" name="submit" value="<?php echo _ZOOM_PASS_BUTTON; ?>" class="button">
        		<script language="javascript" type="text/javascript">
        			<!--
        			 form1.catpass.focus();
        			 form1.catpass.select();
        			 //-->
        		</script>
        		</div>
        	</td>
        </tr>
        </table>
        <input type="hidden" name="option" value="com_zoom" />
        <input type="hidden" name="Itemid" value="<?php echo $Itemid;?>" />
        <input type="hidden" name="catid" value="<?php echo $catid;?>" />
        </form>
        </div>
        <?php
    }
    
    function showMediumListButton($use_tables = true, $Itemid, $PageNo, $counter, $columnwidth, &$size, &$inforow, &$image) {
        global $mosConfig_live_site, $zoom, $my;
        $descr = $zoom->htmlnumericentities($image->_descr);
        $link = "";
        if ($use_tables) {
        	if ($zoom->_CONFIG['showMetaBox']) {
    			$link = "<a onmouseover=\"return overlib('".$zoom->htmlnumericentities($descr)."', CAPTION, '".$image->_name."');\" onmouseout=\"return nd();\"";
    		} else {
    			$link = "<a";
    		}
        } else {
            if ($zoom->_CONFIG['showMetaBox']) {
			    $link = "<div class=\"img-shadow\"><a onmouseover=\"return overlib('".$descr."', CAPTION, '".$image->_name."');\" onmouseout=\"return nd();\"";
    		} else {
    			$link = "<div class=\"img-shadow\"><a";
    		}
        }
        
		// No Popup - This means it will just pop straight up in your screen - Option 0
		if (!$zoom->_CONFIG['popUpImages']) {
			$link .= " href=\"".sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=view&amp;catid=".$zoom->_gallery->_id."&amp;PageNo=".$PageNo."&amp;key=".$counter."&amp;hit=1")."\">\n";
		// Lightbox popup - This popups in a fancy Javascript popup window - Option 2
		} elseif ($zoom->_CONFIG['popUpImages'] == 2 ) {	
		    if($zoom->isImage($image->_type)) {
                $ided = $zoom->_gallery->_images[$counter]->_id;
                $link .= " href=\"".$zoom->hotlinkImage($zoom->_gallery->_id, '0', $ided, null)."\"";
    			// Check to see if we want the slideshow function.
    			if ($zoom->_CONFIG['slideshow']) {
    				$link .= " rel=\"lightbox[".$zoom->_gallery->_name."]\"";
    			} else {
    				$link .= " rel=\"lightbox\"";
    			}
    			$link .= " title=\"".$image->_descr."\">\n";
    		} else {
				$params = $zoom->encrypt("catid=".$zoom->_gallery->_id."&amp;key=".$counter."&amp;isAdmin=".$zoom->_isAdmin."&amp;hit=1&amp;uid=".$my->id."");
    			$link .= " href=\"javascript:void(0)\" onClick=\"window.open('".$mosConfig_live_site."/components/com_zoom/www/view.php?popup=1&q=".$params."', 'win1', 'width=";
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
    			$link .= ", scrollbars=1, resizable=1').focus()\">\n";
			}
		// Standard popup - Your standard seperate window popup - Option 1
		} else {
			// encrypt the parameter string for optimal security...
			$params = $zoom->encrypt("catid=".$zoom->_gallery->_id."&amp;key=".$counter."&amp;isAdmin=".$zoom->_isAdmin."&amp;hit=1&amp;uid=".$my->id."");
			$link .= " href=\"javascript:void(0)\" onClick=\"window.open('".$mosConfig_live_site."/components/com_zoom/www/view.php?popup=1&q=".$params."', 'win1', 'width=";
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
			$link .= ", scrollbars=1, resizable=1').focus()\">\n";
		}
		if ($use_tables) {
            $link .= "<img border=\"1\" src=\"". $zoom->hotlinkImage($image->_catid, '2', $image->_id, null ) ."\" alt=\"\" />\n<br /></a>\n</td>\n";
            echo $link;
            // begin inforow here...
            $inforow .= "\t\t<td align=\"center\" valign=\"top\" width=\"".$columnwidth."%\">\n\t\t";
            if ($zoom->_CONFIG['showName']) {
                $inforow .= (empty($image->_name)) ? $image->_filename : $image->_name;
                $inforow .= "<br />\n";
            }
            if ($zoom->_CONFIG['commentsOn']) {
                // Adding comment-notification, eg. show a pic with last comment-author and date as alt-text.
                if ($mycom = $image->_comments[count($image->_comments)-1]) {
                    $inforow .= "\t\t<img border=\"0\" src=\"".$mosConfig_live_site."/components/com_zoom/www/images/comment.png\" onmouseover=\"return overlib('".$mycom->getName()." (".$mycom->getDate()."):<br>".$mycom->getCommentOrig()."', CAPTION, '"._ZOOM_COMMENTS."');\" onmouseout=\"return nd();\" alt=\"\" />= ".$image->getNumOfComments();
                    
                    if ($zoom->_CONFIG['showHits']) {
                        $inforow .= ", ";
                    }
                }
            }
            if ($zoom->_CONFIG['showHits']) {
                $inforow .= $image->_hits . 'x ' . _ZOOM_HITS . "\n";
            }
            $inforow .= "\t\t</td>\n";
        } else {
            $link .= "<img src=\"".$zoom->hotlinkImage($image->_catid, '2', $image->_id, null )."\"  alt=\"\" />\n</a></div><br />\n</td>\n";
            echo $link;
            // begin inforow here...
            $inforow .= "\t\t<td align=\"left\" valign=\"top\" width=\"".$columnwidth."%\">\n\t\t";
            if ($zoom->_CONFIG['showName']) {
                $inforow .= "<strong>";
                $inforow .= (empty($image->_name)) ? $image->_filename : $image->_name;
                $inforow .= "</strong>";
                $inforow .= "<br />\n";
            }
            if ($zoom->_CONFIG['commentsOn']) {
                // Adding comment-notification, eg. show a pic with last comment-author and date as alt-text.
                if ($mycom = $image->_comments[count($image->_comments)-1]) {
                    $inforow .= "\t\t<span class=\"zmg-comments-on\"><img border=\"0\" src=\"".$mosConfig_live_site."/components/com_zoom/www/images/comment.png\" onmouseover=\"return overlib('".$mycom->getName()." (".$mycom->getDate()."):<br />".$mycom->getCommentOrig()."', CAPTION, '"._ZOOM_COMMENTS."');\" onmouseout=\"return nd();\" alt=\"Image of comment\" /> ".$image->getNumOfComments()." ". _ZOOM_COMMENTS."</span><br />";
                    
                    if ($zoom->_CONFIG['showHits']) {
                        $inforow .= "<br />";
                    }
                }
            }
            if ($zoom->_CONFIG['showHits']) {
                $inforow .= "<span class=\"zmg-hits\">".$image->_hits . ' ' . _ZOOM_HITS . "</span>\n";
            }
            $inforow .= "\t\t<br /><br /></td>\n";
        }
        //return $inforow;
    }
    
    function showFooter($use_tables = true, $Itemid, &$zoom) {
        global $zoom, $backend;
		if ($use_tables) {
        	?>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <?php
                if ($zoom->_isAdmin) {
                    echo '<td align="left" class="'.$zoom->_tabclass[1].'"><a href="'.sefRelToAbs('index.php?option=com_zoom&amp;Itemid=' .$Itemid. '&amp;page=admin').'"><img src="images/M_images/arrow.png" border="0" alt="" /> '._ZOOM_ADMINSYSTEM.'</a></td>';
                } elseif ($zoom->privileges->hasPrivileges()) {
                    echo '<td align="left" class="'.$zoom->_tabclass[1].'"><a href="'.sefRelToAbs('index.php?option=com_zoom&amp;Itemid=' .$Itemid. '&amp;page=admin').'"><img src="images/M_images/arrow.png" border="0" alt="" /> '._ZOOM_USERSYSTEM.'</a></td>';
                }
                ?>
                <td align="right" colspan="3" class="<?php echo $zoom->_tabclass[1];?>">
                <div align="right">
                <?php
        } else {
            ?>
            <div class="clr"></div>
            <div class="zmg-bar">
            <div class="zmg-bar-left">
            <?php
			if ($zoom->_isAdmin) {
                echo '<span class="zmg-admin-system"><a href="'.sefRelToAbs('index.php?option=com_zoom&amp;Itemid=' .$Itemid. '&amp;page=admin').'"><img src="images/M_images/arrow.png" border="0" alt="" /> '._ZOOM_ADMINSYSTEM.'</a></span>';
            } elseif ($zoom->privileges->hasPrivileges()) {
                echo '<span class="zmg-admin-system"><a href="'.sefRelToAbs('index.php?option=com_zoom&amp;Itemid=' .$Itemid. '&amp;page=admin').'"><img src="images/M_images/arrow.png" border="0" alt="" /> '._ZOOM_USERSYSTEM.'</a></span>';
            }
            ?>
            </div>
            <div class="zmg-bar-right">
            <?php
        }
        if ($zoom->_CONFIG['toptenOn']) {
            echo "<a href=\"".sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=special&amp;sorting=0")."\">"._ZOOM_TOPTEN."</a>&nbsp;|&nbsp;\n";
        }
        if ($zoom->_CONFIG['lastsubmOn']) {
            echo "<a href=\"".sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=special&amp;sorting=1")."\">"._ZOOM_LASTSUBM."</a>&nbsp;|&nbsp;\n";
        }
        if ($zoom->_CONFIG['commentsOn']) {
            echo "<a href=\"".sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=special&amp;sorting=2")."\">"._ZOOM_LASTCOMM."</a>&nbsp;|&nbsp;\n";
        }
        if ($zoom->_CONFIG['ratingOn']) {
            echo "<a href=\"".sefRelToAbs("index".$backend.".php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=special&amp;sorting=4")."\">"._ZOOM_TOPRATED."</a>\n";
        }
        if ($use_tables) {
        	?>
                </div>
                </td>
            </tr>
            </table>
            <?php
        } else {
        	?>
            </div>
			</div>
            <?php
        }
    }
}
?>