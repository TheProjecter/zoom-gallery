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
| Filename: catsmgr.html.php                                          |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:catsmgr.html.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
/**
* Utility class for writing the HTML for content
*/ 
class HTML_catsmgr {
    function headers($live_site) {
        global $mainframe, $zoom;
        $html = ('
        <style type="text/css">
        #catsmgr_hold {
            vertical-align: top;
            text-align: left;
            width:100%;
        }
        #cats_tree_table {
            width:200px;
            border:1px inset #cccccc;
            vertical-align:top;
            background-color:#ffffff;
        }
        #cats_tree_container {
            background-color:#ffffff;
        }
        #cats_tree_caption {
            top:0px;
            width:200px;
            height:35px;
            background-color:#d4d4dc;
            display:block;
            color:#666666;
            font-size:10px;
            text-decoration:inset;
        }
        #cats_tree_caption img {
            margin-right: 0px;
            vertical-align:top;
        }
        #cats_tree {
            top:12px;
            width:200px;
            max-height:500px;
        	height:440px;
            display:block;
            overflow:auto;
            vertical-align:top;
        }
        .cats_tree_caption_unpinned {
            border-top: 1px outset #cccccc;
            border-left: 1px outset #cccccc;
            border-right: 1px outset #cccccc;
        }
        .cats_tree_unpinned {
            background-color:#ffffff;
            border-bottom:1px outset #cccccc;
            border-left: 1px outset #cccccc;
            border-right: 1px outset #cccccc;
        }
        .cats_hoverbox {
            margin: 0;
            padding: 0;
            vertical-align: top;
            text-align: left;
            display: block;
        }
        #cats_edit {
            border:1px outset #cccccc;
            background-color:white;
            height:100%;
            vertical-align:top;
        }
        .adminlist {
            background-color: #FFFFFF;
        	margin: 0px;
        	padding: 0px;
        	border: 1px solid #CCCCCC;
        	border-spacing: 0px;
        	width: 95%;
        	text-align: left;
        	border-collapse: collapse;
        	/*display: block;*/
        }
        .title {
            width: 95%;
            margin: 0px;
        	padding: 6px 4px 2px 4px;
        	height: 25px;
        	background: url(../images/background.gif);
        	background-repeat: repeat;
        	font-size: 11px;
        	color: #ffffff;
        	/*display: block;*/
        }
        .row0 {
            width: 95%;
        	background-color: #F5F5F5;
        	border-bottom: 1px solid #e5e5e5;
        	padding: 4px;
        	/*display: block;*/
        }
        .row1 {
            width: 95%;
        	background-color: #FFF;
        	border-bottom: 1px solid #e5e5e5;
        	padding: 4px;
        	/*display: block;*/
        }
        .row0:hover {
        	background-color: #f1f1f1;
        }
        .row1:hover {
        	background-color: #f1f1f1;
        }
        .draggable {
            cursor: move;
        }
        .col1 { width: 10px; }
        .col2 { width: 80%; }
        .col3 { width: 60px; }
        .entry {
            text-align: left;
            display: table-cell;
        }
        #ZMG_toolbar {
            height: 32px;
            display: inline;
            z-index: 200;
        }
        #members_buttons {
            text-align: center;
            vertical-align: middle;
        }
        .zmg_tb_button {
            display: inline;
            position: relative;
            margin-left: 5px;
            float: right;
            cursor: hand;
            cursor: pointer; 
        }
        .zmg_nav_button {
            display: block;
            cursor: hand;
            cursor: pointer;
        }
        .zmg_tb_btn_save, .zmg_tb_btn_new {
            background: url("'.$live_site.'/images/save.png") no-repeat center;
        }
        .zmg_tb_btn_save_hover, .zmg_tb_btn_new_hover {
            background-image: url("'.$live_site.'/images/save_f2.png");
        }
        .zmg_tb_btn_delete {
            background: url("'.$live_site.'/components/com_zoom/www/images/admin/delete.png") no-repeat;
        }
        .zmg_tb_btn_delete_hover {
            background-image: url("'.$live_site.'/components/com_zoom/www/images/admin/delete_f2.png");
        }
        .zmg_tb_btn_edit {
            background: url("'.$live_site.'/components/com_zoom/www/images/admin/edit.png") no-repeat; 
        }
        .zmg_tb_btn_edit_hover {
            background-image: url("'.$live_site.'/components/com_zoom/www/images/admin/edit_f2.png");
        }
        .zmg_tb_btn_ordersave {
            background: url("'.$live_site.'/components/com_zoom/www/images/admin/ordersave.png") no-repeat; 
        }
        .zmg_tb_btn_ordersave_hover {
            background-image: url("'.$live_site.'/components/com_zoom/www/images/admin/ordersave_f2.png");
        }
        .zmg_nav_btn_right {
            background-image: url("'.$live_site.'/components/com_zoom/www/images/admin/arrow_right.png");
        }
        .zmg_nav_btn_right_hover {
            background-image: url("'.$live_site.'/components/com_zoom/www/images/admin/arrow_right_f2.png");
        }
        .zmg_nav_btn_left {
            background-image: url("'.$live_site.'/components/com_zoom/www/images/admin/arrow_left.png");
        }
        .zmg_nav_btn_left_hover {
            background-image: url("'.$live_site.'/components/com_zoom/www/images/admin/arrow_left_f2.png");
        }
        </style>
        <link href="'.$live_site.'/components/com_zoom/www/dhtmlgoodies/contextmenu.css" type="text/css" rel="stylesheet" media="screen" />
        <link href="'.$live_site.'/components/com_zoom/www/dhtmlgoodies/tree.css" type="text/css" rel="stylesheet" media="screen" />
        <script language="javascript" type="text/javascript" src="'.$live_site.'/includes/js/joomla.javascript.js"></script> 	
        <script language="javascript" type="text/javascript" src="'.$live_site.'/components/com_zoom/lib/js/prototype.js"></script>
        <script language="javascript" type="text/javascript" src="'.$live_site.'/components/com_zoom/lib/js/scriptaculous.js?load=button,effects,dragdrop,mm,contextmenu,tree"></script>
        ');
        if (false) {
        	$mainframe->addCustomHeadTag($html);
        } else {
            echo $html;
        }
    }
    function catEditForm(&$zoom, $live_site, $absolute_path) {
    $tabs = new mosTabs(0); 
        ?>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<tr>
    <td align="left">
        <img src="<?php echo $live_site;?>/components/com_zoom/www/images/admin/new_f2.png" border="0" alt="<?php echo _ZOOM_EDIT;?>" id="editform_img" />
        &nbsp;<b><span style="font-size:15pt;" id="editform_title"><?php echo _ZOOM_HD_NEW;?></span></b>
   </td>
</tr>
</table>

<?php $tabs->startPane("catsmgr_tabs"); 
$tabs->startTab(_ZOOM_ITEMEDIT_TAB1 ,"catsmgr_tabs_page1");
?>
	<table border="0" width="100%">
	<tr>
	    <td><?php echo _ZOOM_HD_AFTER;?>: </td>
	    <td>
	    <?php
	    if (!empty($zoom->_gallery)) {
	    	$dd_subcat = $zoom->_gallery->_subcat_id;
	    	$dd_catid = $zoom->_gallery->_id;
	    } else {
	    	$dd_subcat = 0;
	    	$dd_catid = 0;
	    }
	    echo $zoom->createCatDropdown('parent','<option value="0" selected>&gt;&nbsp;'._ZOOM_TOPLEVEL.'</option>', 0, $dd_subcat, $dd_catid);
	    ?>
	    </td>
	</tr>
	<tr>
	    <td><?php echo _ZOOM_HD_NAME;?>:</td>
	    <td>
		<input class="inputbox" type="text" name="catname" id="catname" value="<?php echo (!empty($zoom->_gallery)) ? $zoom->_gallery->_name : $zoom->_CONFIG['tempName']; ?>" size="50" />
	    </td>
	</tr>
	<tr>
		<td><?php echo _ZOOM_HD_DIR;?>:</td>
		<td>
			<input class="inputbox" type="text" name="catdir" id="catdir" value="<?php echo (!empty($zoom->_gallery)) ? $zoom->_gallery->_dir : $zoom->newdir(); ?>" size="50" />
		</td>
	</tr>
	<tr>
	<td><?php echo _ZOOM_PASS;?>:</td>
	<td>
	    <input class="inputbox" type="text" name="catpassword" id="catpassword" value="" onClick="javascript:this.focus();this.select();" size="50" />
	</td>
	</tr>
	<tr>
	    <td><?php echo _ZOOM_KEYWORDS;?>: </td>
	    <td valign="middle">
		<input type="text" name="catkeywords" id="catkeywords" size="50" value="<?php echo (!empty($zoom->_gallery)) ? $zoom->_gallery->_keywords : ""; ?>" class="inputbox" />
	    </td>
	</tr>
	<tr>
	    <td><?php echo _ZOOM_DESCRIPTION;?>:</td>
	    <td>
		<?php
		// parameters : areaname, content, hidden field, width, height, rows, cols
		$content = (!empty($zoom->_gallery)) ? $zoom->_gallery->_descr : $zoom->_CONFIG['tempDescr'];
		editorArea( 'zOOmEditor1', $content, 'catdescr', '500', '200', '45', '5' );
		?>
	    </td>
	</tr>
	</table>
    <?php $tabs->endTab(); 
    $tabs->startTab(_ZOOM_ITEMEDIT_TAB2 ,"catsmgr_tabs_page2");
    ?>
		<table border="0" width="300" cellpadding="4">
		<tr>
		    <td>
		    <?php
		    if (!empty($zoom->_gallery)) {
		    	$userlist = $zoom->getUsersList($zoom->_gallery->_members);
		    } else {
		        $userlist = $zoom->getUsersList();
		    }
		    foreach($userlist as $item){
			    echo $item."\n";
		    }
		    ?>
		    </td>
		    <td id="members_buttons" align="center" valign="middle">&nbsp;</td>
		    <td>
		    <!-- Selected users list -->
		    <select name="members_sel[]" id="members_sel" class="inputbox" size="20" multiple="multiple">
		    </select>
		    </td>
		</tr>
		</table>
		<?php
    $tabs->endTab(); 
    $tabs->startTab(_ZOOM_ITEMEDIT_TAB3 ,"catsmgr_tabs_page3");
    ?>
		<table border="0" width="300" cellpadding="4">
		<tr>
		    <td><?php echo _ZOOM_HD_HIDEMSG;?>:</td>
		    <td>
			<input type="checkbox" name="hidemsg" id="hidemsg" value="1"<?php if (!empty($zoom->_gallery)) { echo ($zoom->_gallery->_hideMsg) ? " checked" : ""; } ?> />
		    </td>
		</tr>
		<tr>
		    <td>
			<?php echo _ZOOM_PUBLISHED;?>:
		    </td>
		    <td>
			<input type="checkbox" name="published" id="published" value="1"<?php if (!empty($zoom->_gallery)) { echo ($zoom->_gallery->isPublished()) ? " checked" : ""; } else { echo " checked"; } ?> />
		    </td>
		</tr>
		<tr>
		    <td>
			<?php echo _ZOOM_HD_SHARE;?>:
		    </td>
		    <td>
			<input type="checkbox" name="shared" id="shared" value="1"<?php if (!empty($zoom->_gallery)) { echo ($zoom->_gallery->isShared()) ? " checked" : ""; }?> />
		    </td>
		</tr>
		</table>
<?php 
$tabs->endTab();
// start Tabbar
$tabs->endPane("catsmgr_tabs");
?>
<input type="hidden" name="catid" id="catid" value="<?php echo (!empty($zoom->_gallery)) ? $zoom->_gallery->_id : 0; ?>" /><br />
        <?php
    }
}
?>