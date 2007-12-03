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
| Filename: toolbar.html.php                                          |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:toolbar.zoom.php 124 2007-02-17 03:35:03Z spignataro $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
global $zoom, $mainframe;

$page = zmgGetParam($_REQUEST, 'page');

//mosMenuBar::startTable();
switch ($page) {
	case 'catsmgr' :
		mosMenuBar::spacer();
		break;
    case 'mediamgr' :
        $task = mosGetParam($_REQUEST, 'task');
        $catid = mosGetParam($_REQUEST, 'catid');
        if (empty($task) && !empty($catid)) {
			if ($zoom->_isAdmin || $zoom->privileges->hasPrivilege('priv_upload')) {
				mosMenuBar::custom('upload', 'new_f2.png', 'new_f2.png', _ZOOM_UPLOAD, false);
				mosMenuBar::spacer();
				mosMenuBar::divider();
				mosMenuBar::spacer();
			}
			if($zoom->_isAdmin || $zoom->privileges->hasPrivilege('priv_editmedium')) {
				mosMenuBar::custom('edtimg', 'edit_f2.png', 'edit_f2.png', _ZOOM_BUTTON_EDIT, false);
				mosMenuBar::spacer();
			}
			if ($zoom->_isAdmin) {
				mosMenuBar::custom('move', '../../components/com_zoom/www/images/admin/move_f2.png', '../../components/com_zoom/www/images/admin/move_f2.png', _ZOOM_MOVEFILES, false);
				mosMenuBar::spacer();
			}
			if ($zoom->_isAdmin || $zoom->privileges->hasPrivilege('priv_delmedium')) {
				mosMenuBar::custom('delete', 'delete_f2.png', 'delete_f2.png', _ZOOM_DELETE, false);
			}      	
        } else if ($task == "edtimg") {
			mosMenuBar::save('save', _ZOOM_SAVE);
			mosMenuBar::spacer();
        }             
      	mosMenuBar::spacer();
     	mosMenuBar::back(_ZOOM_BACK);   
		break;
	case 'upload' :
		$url = "index2.php?option=com_zoom&page=mediamgr";
		if (!empty($zoom->_gallery)) {
			$url .= "&catid=".$zoom->_gallery->_id;
		}
		mosMenuBar::back(_ZOOM_BACK, $url);
		break;
	case 'settings' :
		mosMenuBar::save('save', _ZOOM_SAVE);
		mosMenuBar::spacer();
		mosMenuBar::cancel('cancel', _ZOOM_WINDOW_CLOSE);
		break;
	case 'credits' :
	case 'zoomthumb' :
		mosMenuBar::back(_ZOOM_BACK);
		break;
	default :
		//mosMenuBar::spacer();
		break;
}
//mosMenuBar::endTable();
?>