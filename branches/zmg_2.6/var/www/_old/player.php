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
| Filename: player.php                                                |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:player.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// Load MOS configuration file...
include('../../../configuration.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>zoomfactory.org - Home</title>
</head>
<body bgcolor="#FFFFFF">

<object type="application/x-shockwave-flash" data="<?php echo $mosConfig_live_site; ?>/components/com_zoom/www/zoomplayer.swf" width="300" height="300">
	<param name="movie" value="<?php echo $mosConfig_live_site; ?>/components/com_zoom/www/zoomplayer.swf">
	<param name="flashvars" value="config=<?php echo $mosConfig_live_site; ?>/components/com_zoom/www/config.xml&amp;file=<?php echo $mosConfig_live_site; ?>/components/com_zoom/www/playlist.php?keys=<?php echo $_REQUEST['params']; ?>" />
	<param name="wmode" value="transparent" />
</object>


</body>
</html>