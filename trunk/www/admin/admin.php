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
| Filename: admin.php                                                 |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:admin.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
$action = mosGetParam($_REQUEST,'action');
$Itemid = $zoom->getItemid($option);
if (isset($action)){
    $zoom->optimizeTables();
	if($zoom->_isBackend) {
		mosRedirect("index".$backend.".php?option=com_zoom&amp;page=admin&amp;Itemid=".$Itemid, _ZOOM_OPTIMIZE_SUCCESS);		
	} else {
		mosRedirect($mosConfig_live_site."/index.php?option=com_zoom&amp;page=admin&amp;Itemid=".$Itemid, _ZOOM_OPTIMIZE_SUCCESS);	
	}
}

function quickiconButton( $link, $image, $text, $path='/administrator/images/', $hover ) {
		$hover = str_replace("'", "&amp;#39;", $hover);
		?>
		<div style="float:left;">
			<div class="icon">
				<a href="<?php echo $link; ?>" onmouseover="return overlib('<?php echo $hover;?>', BGCOLOR, '#C64934', BORDER, 1, BELOW, RIGHT);" onmouseout="return nd();">
					<?php echo mosAdminMenus::imageCheckAdmin( $image, $path, NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}
?>
<link href="<?php echo $mosConfig_live_site ?>/components/com_zoom/etc/admin.css" rel="stylesheet" type="text/css" />
<div id="zmg-admin">
<table width="100%" border="0" cellpadding="2" cellspacing="2" class="zmg-admin-adminheading">
	<tr>
		<th width="100%" class="cpanel" align="left" colspan="2">
			<?php echo ($zoom->_isAdmin) ? _ZOOM_ADMIN_TITLE : _ZOOM_USER_TITLE; ?>
		</th>
	</tr>
	<?php
    if (!$zoom->_isBackend) {
    ?>
    <tr>
        <td align="left">
            <a href="<?php echo sefRelToAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid);?>">
            <img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/home.gif" alt="<?php echo _ZOOM_BACKTOGALLERY;?>" border="0" />&nbsp;&nbsp;<?php echo _ZOOM_BACKTOGALLERY;?>
            </a>
        </td>
    </tr>
    <?php
    }
    ?>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="zmg-admin-adminform">
<tr>
    <td align="center" valign="top">    
                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                	<tr>
						<td width="100%" valign="top">
							<div id="zmg-admin-cpanel">
							<?php
							if ($zoom->_isAdmin || ($zoom->privileges->hasPrivilege('priv_creategal') | $zoom->privileges->hasPrivilege('priv_editgal') | $zoom->privileges->hasPrivilege('priv_delgal'))) {
								if ($zoom->_isBackend) {
									$link = "index".$backend.".php?option=com_zoom&amp;page=catsmgr&amp;Itemid=".$Itemid;
								}
								else {
									$link = sefReltoAbs("index.php?option=com_zoom&amp;page=catsmgr&amp;Itemid=".$Itemid);
								}
								quickiconButton( $link, 'catsmgr_f2.png', _ZOOM_CATSMGR, '/components/com_zoom/www/images/admin/', _ZOOM_CATSMGR_DESCR );
							}
                			if ($zoom->_isAdmin || ($zoom->privileges->hasPrivilege('priv_upload') | $zoom->privileges->hasPrivilege('priv_editmedium') | $zoom->privileges->hasPrivilege('priv_delmedium'))) {
								if ($zoom->_isBackend) {
									$link = "index".$backend.".php?option=com_zoom&amp;page=mediamgr&amp;Itemid=".$Itemid;
								}
								else {
									$link = sefReltoAbs("index.php?option=com_zoom&amp;page=mediamgr&amp;Itemid=".$Itemid);
								}
								quickiconButton( $link, 'mediamgr_f2.png', _ZOOM_MEDIAMGR, '/components/com_zoom/www/images/admin/', _ZOOM_MEDIAMGR_DESCR );
               				}
							if ($zoom->_isBackend) {
								$link = "index".$backend.".php?option=com_zoom&amp;page=zoomthumb&amp;Itemid=".$Itemid;
							}
							else {
								$link = sefReltoAbs("index.php?option=com_zoom&amp;Itemid=".$Itemid."&amp;page=zoomthumb");
							}
							quickiconButton( $link, 'zoomthumb_f2.png', _ZOOM_THUMB, '/components/com_zoom/www/images/admin/', _ZOOM_THUMB_DESCR );
			                if ($zoom->_isAdmin) {
			                	if ($zoom->_isBackend) {	
									$link = "index".$backend.".php?option=com_zoom&amp;page=settings&amp;Itemid=".$Itemid;
								}
								else {
									$link = sefReltoAbs("index.php?option=com_zoom&amp;page=settings&amp;Itemid=".$Itemid);
								}
								quickiconButton( $link, 'settings_f2.png', _ZOOM_SETTINGS, '/components/com_zoom/www/images/admin/', _ZOOM_SETTINGS_DESCR );
								if ($zoom->_isBackend) {
									$link = "index".$backend.".php?option=com_zoom&amp;page=admin&amp;action=optimize&amp;Itemid=".$Itemid;
								} else {
									$link = sefReltoAbs("index.php?option=com_zoom&amp;page=admin&amp;action=optimize&amp;Itemid=".$Itemid);	
								}
								quickiconButton( $link, 'tables_f2.png', _ZOOM_OPTIMIZE, '/components/com_zoom/www/images/admin/', _ZOOM_OPTIMIZE_DESCR );
							}
                    ?>
                			</div>
        			    </td>
        			</tr>
	                <tr>
						<td align="left" style="text-align:left;">
                        	<a href="<?php echo ($zoom->_isBackend) ? "index2.php?option=com_zoom&amp;page=credits&amp;Itemid=".$Itemid : sefReltoAbs("index.php?option=com_zoom&amp;page=credits&amp;Itemid=".$Itemid);?>">
	    	                    <img src="<?php echo $mosConfig_live_site;?>/components/com_zoom/www/images/credits.gif" alt="<?php echo _ZOOM_CREDITS;?>" border="0" />&nbsp;&nbsp;<?php echo _ZOOM_CREDITS;?>
    	                    </a>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>
</div>