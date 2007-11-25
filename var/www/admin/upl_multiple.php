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
| Filename: upl_multiple.php                                          |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:upl_multiple.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$mult_submit = mosGetParam($_REQUEST, 'mult_submit');
if (isset($mult_submit) && !empty($mult_submit)) {
	if (!$catid) {
		mosRedirect("index".$backend.".php?option=com_zoom&page=upload&catid=".$catid."&formtype=multiple&Itemid=".$Itemid, _ZOOM_NOCAT);
	}
	// counters:
	$i = 0;
	$zoom->_counter = 0;
	
	$setFilename = (bool)trim(mosGetParam($_REQUEST, 'mult_setFilename', false));
	$ignoresizes = (bool)trim(mosGetParam($_REQUEST, 'mult_ignoresizes', false));
	$theName = mosGetParam($_REQUEST, 'mult_imgname');
	$theDescr = mosGetParam($_REQUEST, 'mult_descr', null, _MOS_ALLOWHTML);
	$keywords = mosGetParam($_REQUEST, 'mult_keywords');
	$rotate = mosGetParam($_REQUEST, 'mult_rotate');
	    
	foreach ($_FILES as $key => $value) {
	    // get the temporary name (e.g. /tmp/php34634.tmp)
	    $temp_files = array();
	    $temp_filenames = array();
	    $temp_filetypes = array();
 		if (!is_array($value['tmp_name']) && !is_array($value['name'])) {
 		    $temp_files[0] = $value['tmp_name'];
 		    $temp_filenames[0] = $value['name'];
 		    $temp_filetypes[0] = $value['type'];
		} else {
		    $temp_files = $value['tmp_name'];
		    $temp_filenames = $value['name'];
		    $temp_filetypes = $value['type'];
		}
		
	    foreach ($temp_files as $temp_file) {
	        if(!empty($temp_file)) {
				$file = urldecode($temp_file);
		        $filename = array_shift($temp_filenames);
		        $filetype = array_shift($temp_filetypes);
				if ($setFilename) {
	    			$name = $filename;
	    		} else{
					$name = $theName[$i];	
				}
	    		if ($zoom->_CONFIG['autonumber']) {
	    			$name .= " ".($i + 1);
	    		}
	    		if (is_array($keywords)) {
	    			$indkeywords = $keywords[$i];
	    		}
	    		$indrotate = '';
	    		if (is_array($rotate)) {
	    			$indrotate = $rotate[$i];
	    		}
	    		$degrees = '';
	    		if ($rotate) {
	    	        $indrotate = true;
	    	        $key = "mult_rotate$i";
	    	        $degrees = intval(trim(mosGetParam($_REQUEST, $key, 0)));
	    	    } else {
					$degrees = 0;
				}
	    		if (!empty($theDescr) && is_array($theDescr)) {
	    	    	$indtheDescr = $theDescr[$i];
	    		}
				$theDescr = str_replace("'", "&#39;", $theDescr);
					if ($zoom->toolbox->processImage($file, $filename, $filetype, $indkeywords, $name, $indtheDescr, $indrotate, $degrees, $ignoresizes)) {
                        $zoom->_counter++;
                    }
				$i++;
	    	}
	    }
		
	} // end of foreach-loop
	if ($zoom->toolbox->_err_num > 0) {
		$zoom->toolbox->displayErrors();
	}
	echo "<br /><center><h4>".$zoom->_counter." "._ZOOM_ALERT_UPLOADSOK."</h4></center><br /><br />";
	$formtype = 'multiple';
}else{
	$boxes = intval(trim(mosGetParam($_REQUEST, 'boxes')));
	if (!$boxes) {
		$boxes = 5;
	}
	?>
	<form enctype="multipart/form-data" name="count_form" method="post" action="<?php echo $zoom->_isBackend ? "index2.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=upload&amp;catid=".$catid."&amp;formtype=multiple" : sefReltoAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=upload&amp;catid=".$catid."&amp;formtype=multiple");?>" onsubmit="showMe();">
	<table width="90%" border="0" cellpadding="3" cellspacing="3">
		<tr><td colspan="2">&nbsp;</td></tr>
		<?php
		// if php safe_mode restriction is in use, warn the user! -> added by mic
		if( ini_get( 'safe_mode' ) == 1 ){ ?>
			<tr>
				<td>&nbsp;</td>
		 		<td><strong><font color="red"><?php echo _ZOOM_A_MESS_SAFEMODE1; ?></font></strong></td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<?php
		} ?>
		<tr>
			<td width="60%" align="left"><strong><?php echo _ZOOM_UPLOAD_STEP1;?></strong>&nbsp;</td>
			<td>
				<select name="boxes" onChange="submitCount()" class="inputbox">
					<?php 
					for( $i = 1; $i <= 10; $i++ ) {
						echo "<option ";
						if ($i == $boxes) {
							echo "selected ";
						}
						echo "value=\"$i\">$i\n";

					} ?>
				</select>
			</td>
		</tr>
	</table>	
	</form>
	<form enctype="multipart/form-data" name="upload_form" method="post" action="<?php echo $zoom->_isBackend ? "index2.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=upload&amp;formtype=save" : sefReltoAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=upload&amp;formtype=save");?>">
	<table width="90%" border="0" cellpadding="3" cellspacing="3">
		<tr><td style="border-bottom: 1px dashed #CCCCCC;" colspan="2">&nbsp;</td></tr>
		<tr>
			<td width="60%"><strong><?php echo _ZOOM_UPLOAD_STEP2;?></strong></td>
			<td>
				<?php
				echo $zoom->createCatDropdown('catid', '<OPTION value="">---&nbsp;'._ZOOM_PICK.'&nbsp;---</OPTION>', 0, $catid); ?>
			</td>
		</tr>
		<tr><td style="border-bottom: 1px dashed #CCCCCC;" colspan="2">&nbsp;</td></tr>
		<tr>
			<td><strong><?php echo _ZOOM_UPLOAD_STEP3;?></strong>&nbsp;</td>
			<td>
				<input type="checkbox" name="mult_setFilename" id="mult_setFilename" value="1"<?php if( $zoom->_CONFIG['autonumber'] ) echo " checked";?> /><label for="mult_setFilename">&nbsp;<?php echo _ZOOM_FORM_SETFILENAME;?></label>
				<br />
				<input type="checkbox" name="mult_ignoresizes" id="mult_ignoresizes" value="1" /><label for="mult_ignoresizes">&nbsp;<?php echo _ZOOM_FORM_IGNORESIZES; ?></label>
			</td>
		</tr>
	</table>	
	<table border="0" cellpadding="0" cellspacing="0">
		<?php
		$tabcnt=1;
		for ($i = 0; $i < $boxes;  $i++) { ?>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr class="<?php echo $zoom->_tabclass[$tabcnt]; ?>">
				<td><?php echo _ZOOM_FORM_IMAGEFILE;?>:&nbsp;</td>
				<td>
					<input class="inputbox" type="file" name="mult_userfile[]" size="50" />
				</td>
			</tr>			
			<tr class="<?php echo $zoom->_tabclass[$tabcnt]; ?>">
				<td>&nbsp;</td>
				<td>
					<input type="checkbox" name="mult_rotate[]" value="1" /><?php echo _ZOOM_ROTATE;?>
					<input type="radio" name="mult_rotate<?php echo $i;?>" value="90" /><?php echo _ZOOM_CLOCKWISE;?>
					<input type="radio" name="mult_rotate<?php echo $i;?>" value="-90" /><?php echo _ZOOM_CCLOCKWISE;?>
				</td>
			</tr>			
			<tr class="<?php echo $zoom->_tabclass[$tabcnt];?>">
				<td valign="top"><?php echo _ZOOM_NAME;?>: </td>
				<td valign="top">
					<input type="text" name="mult_imgname[]" size="50" value="<?php echo $zoom->_CONFIG['tempName'];?>" class="inputbox" />
				</td>
			</tr>
			<tr class="<?php echo $zoom->_tabclass[$tabcnt];?>">
				<td valign="top"><?php echo _ZOOM_KEYWORDS;?>:&nbsp;</td>
				<td valign="top">
					<input type="text" name="mult_keywords[]" size="50" value="" class="inputbox" />
				</td>
			</tr>
			<tr class="<?php echo $zoom->_tabclass[$tabcnt]; ?>">
				<td valign="top"><?php echo _ZOOM_DESCRIPTION;?>:&nbsp;</td>
				<td valign="top">
					<!--<textarea class="inputbox" cols="50" rows="5" name="mult_descr[]"><?php echo $zoom->_CONFIG['tempDescr'];?></textarea>-->
					<?php
            		  // parameters : areaname, content, hidden field, width, height, rows, cols
            		  editorArea( 'mult_descr[]', $zoom->_CONFIG['tempDescr'], 'mult_descr[]', '100%;', '150', '20', '20' ) ; ?>
				</td>
			</tr>
			<tr><td style="border-bottom: 1px dashed #CCCCCC;" colspan="2">&nbsp;</td></tr>
			<?php
			if ($tabcnt == 1){
				$tabcnt = 0;
			} else {
				$tabcnt++;
			}
		} ?>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="<?php echo _ZOOM_BUTTON_UPLOAD; ?>" name="mult_submit" class="button" />
			</td>
		</tr>
	</table>
	</form>
	<?php
}
?>