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
| Filename: catsmgr.php                                               |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:catsmgr.php 112 2007-02-11 20:10:11Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

require_once($mosConfig_absolute_path."/components/com_zoom/www/admin/catsmgr.html.php");
$Itemid = mosGetParam($_REQUEST,'Itemid');
HTML_catsmgr::headers($mosConfig_live_site);
$zoom->createCheckAllScript();
$zoom->createProgressScript('search');
$zoom->prepareAjax(array(
    "LANG_GALLERIES"     => _ZOOM_LIGHTBOX_CATS,
    "LANG_TOPLEVEL"      => _ZOOM_TOPLEVEL,
    "LANG_PUBLISHED"     => _ZOOM_PUBLISHED,
    "LANG_SHARED"        => _ZOOM_SHARED,
    "LANG_YES"           => _ZOOM_YES,
    "LANG_NO"            => _ZOOM_NO,
    "LANG_NEW_TITLE"     => _ZOOM_HD_NEW,
    "LANG_EDIT_TITLE"    => _ZOOM_EDIT,
    "LANG_ALERT_EDITOK"  => _ZOOM_ALERT_EDITOK,
    "LANG_CONFIRM_DEL"   => _ZOOM_CONFIRM_DEL,
    "LANG_BTN_ORDERSAVE" => _ZOOM_ORDERSAVE,
    "LANG_BTN_BACK"      => _ZOOM_BACK
));
?>
<script language="Javascript" type="text/javascript">
  <!--
  /* setting the new description is a little bit more difficult, because we have to deal
   * with the Editor mosBot. So, we have to support the following flavors:
   * - TinyMCE
   * - FCKeditor
   * - TMEdit
   * - Standard (no editor)
   */
  <?php
  if (isset($mosConfig_editor)) {
	 global $mosConfig_editor;
     if ($mosConfig_editor == '') {
        $editor = 'none';
     } else {
        $editor = $mosConfig_editor;
     }
     if ($zoom->cmstype >= 2) {
        // Per User Editor selection
        $params = new mosParameters( $my->params );
        $editor = $params->get( 'editor', '' );
        if (!$editor) {
            $editor = $mosConfig_editor;
        }
     }
  } else {
    $editor = 'none';
  }
  echo "var crtEditor = '$editor';\n";
  ?>
  
  var loadTimer = null;
  
  function catsmgrStart() {
    if (Zoom && $('cats_tree') && (IS_BACKEND || $('ZMG_toolbar')) && $('members_buttons')) {
        clearTimeout(loadTimer);
        Draggables.addObserver({
            onStart: function(name, draggable, e) {
                try {
                    Zoom.dragTree(draggable);
                }catch(e){}
            },
            onDrag: function(name, draggable, e) {
                try {
                    Zoom.dragTree(draggable);
                }catch(e){}
            },
            onEnd: function(name, draggable, e) {
                try {
                    Zoom.dragTree(draggable);
                }catch(e){}
            }
        });
        Zoom.buildMembersButtons($('members_buttons'), 'members_opt', 'members_sel');
        Zoom.getGalleryTree('cats_tree');
        Zoom.getToolbar();
        hideMe();
    } else {
        loadTimer = setTimeout('catsmgrStart();', 500);
    }
  };
  
  function submitForm(theTask, catid) {
    document.catsmgr.elements['task'].value = theTask;
    if( catid > 0 ){
    	document.catsmgr.elements['altid'].value = catid;
    }
    if (theTask == 'reorder') {
        showMe();
        var params = 'id=' + id + '&task=catsmgr_reorder&' + Sortable.serialize('cats_list');
        new Ajax.Request(Zoom.req_uri,
            {method: "post", parameters: params,
            onSuccess: endCallback, onFailure: endCallback});
    } else {
        <?php getEditorContents('zOOmEditor1', 'catdescr'); echo "\n"; ?>
        document.catsmgr.submit();
        return false;
    }
  };
  function setFormValues(id, name, dir, password, keywords, descr, subcat_id, pos, hidemsg, published, shared) {
      var oForm = document.catsmgr;
      /* first, change the title to an apropriate value: */
      $('editform_title').innerHTML = LANG_EDIT_TITLE;
      $('editform_img').src = Zoom.site_uri + "/components/com_zoom/www/images/admin/edit_f2.png";
      oForm.elements['catid'].value       = id;
      oForm.elements['catname'].value     = name;
      oForm.elements['catdir'].value      = dir;
      oForm.elements['catpassword'].value = password;
      oForm.elements['catkeywords'].value = keywords;
      /* set checkbox values: */
      if (hidemsg == "1")
        oForm.elements['hidemsg'].checked = true;
      else
        oForm.elements['hidemsg'].checked = false;
      if (published == "1")
        oForm.elements['published'].checked = true;
      else
        oForm.elements['published'].checked = false;
      if (shared == "1")
        oForm.elements['shared'].checked = true;
      else
        oForm.elements['shared'].checked = false;
      setEditorContent(descr);
  };
  function resetFormValues() {
      Zoom.getToolbar();
      $('editform_title').innerHTML = LANG_NEW_TITLE;
      $('editform_img').src = Zoom.site_uri + "/components/com_zoom/www/images/admin/new_f2.png";
      document.catsmgr.reset();
      setEditorContent('<?php echo $zoom->_CONFIG['tempDescr'] ?>');
      Zoom.getNewDir();
      Zoom.getParentOptions();
      Zoom.buildMembersList('', 'members_opt', 'members_sel');
      //rebuildParentSelect();
      Zoom.activeCat = "-1";
      Zoom.activeSubcat = "0";
  };
  function getEditorContent() {
      <?php getEditorContents('zOOmEditor1', 'catdescr'); echo "\n"; ?>
      if (crtEditor == "tmedit") {
          return editorzOOmEditor1.getHTML();
      }
      if (crtEditor == "fckeditor") {
          var return_tmp = FCKeditorAPI.GetInstance('catdescr').GetXHTML();
          return return_tmp;
      }
      else {
          return null;
      }
  };
  function setEditorContent(content) {
      if (crtEditor != "") {
          switch (crtEditor.toLowerCase()) {
              case "tinymce":
              tinyMCE.execCommand('mceSetContent', false, content);
                break;
              case "jce":
              tinyMCE.execCommand('mceSetContent', false, content);
                break;
              case "fckeditor":
                var edit_tmp = FCKeditorAPI.GetInstance('catdescr');
                edit_tmp.SetHTML(content);
                break;
              case "htmlarea":
                HTMLArea.setHTML('zOOmEditor1', content);
                break;
              case "xinha":
                HTMLArea.setHTML('zOOmEditor1', content);
                break;              
              case "tmedit":
                editorzOOmEditor1.setHTML(content);
                break;
              case 'wysiwygpro':
                catdescr.html_edit_area.value = content;
                wp_send_to_edit_object(catdescr);
                break;
              case 'xstandard':
                $('zOOmEditor1').value = content;
              case 'none':
                document.catsmgr.elements['catdescr'].value = content;
                break;
          }
      }
  }
  function endCallback(callback) {
      hideMe();
  }
  catsmgrStart();
  //-->
</script>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
<?php
if (!$zoom->_isBackend) {
?>
<tr>
<td align="center" width="100%"><a href="<?php echo ($zoom->_isBackend) ? "index2.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=admin" : sefReltoAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=admin");?>">
    <img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/home.gif" alt="<?echo _ZOOM_MAINSCREEN;?>" border="0" />&nbsp;&nbsp;<?php echo _ZOOM_MAINSCREEN;?></a>
</td>
</tr>
<?php
}
?>
<tr>
<td align="left"><img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/admin/catsmgr_f2.png" border="0" alt="<?php echo _ZOOM_CATSMGR;?>" />&nbsp;<b><font size="4"><?php echo _ZOOM_CATSMGR;?></font></b></td>
</tr>
</table>
<br />
<?php
if (!$zoom->_isBackend) {
?>
<table width="95%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="right">
    <div align="right" id="ZMG_toolbar"></div>
</td>
</tr>
</table>
<?php
}
?>
<form name="catsmgr" action="index<?php echo ($zoom->_isBackend) ? "2" : "";?>.php" method="post">
<input type="hidden" name="option" value="<?php echo $option; ?>" />
<input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="altid" value="0" />
<table id="catsmgr_hold">
<tr>
	<td id="cats_tree_table">
	   <span id="cats_tree_caption">
	       <?php echo _ZOOM_LIGHTBOX_CATS; ?>
	       <a href="javascript:void(0);" onmousedown="Zoom.doPinTree()">
	           <img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/unpin.gif" id="cats_tree_pinimg" border="0" alt="" />
	       </a><br />
	       <a href="javascript:void(0);" onmousedown="resetFormValues()">
	       		<?php echo _ZOOM_HD_NEW; ?>
	       		<img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/dhtmlgoodies/sheet.gif" id="new" border="0" alt="" />
		   </a>
	   </span>
	   <div id="cats_tree_container">
	   <ul id="cats_tree" class="dhtmlgoodies_tree">Loading...</ul>
	   </div>
	</td>
	<td id="cats_edit">
        <?php
	    HTML_catsmgr::catEditForm($zoom, $mosConfig_live_site, $mosConfig_absolute_path);
    	?>
    </td>
</tr>
</table>
</form>