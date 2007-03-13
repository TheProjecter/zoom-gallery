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
| Filename: playlist.php                                                  |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:playlist.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// Turn off Magic quotes runtime, because it interferes with saving info to the 
// database and vice versa.

define( "_VALID_MOS", 1 );

require_once( '../../../globals.php' );
include_once('../../../configuration.php');
require_once("../lib/inserts.class.php");

// Update the Edit Monitor, eg. delete unnecessary rows
$zoom->EditMon->updateEditMon();

echo $zoom->startXML('playlist');

$keys      = $zoom->getParam($_REQUEST, 'keys');
$playlist  = ''; 

$pieces = explode(",", $keys); 
$total = count($pieces);
for($x = 0; $x < $total; $x += 2){ 
	$zoom->setGallery($pieces[$x]);
	$playlist .= '<track>';
	$zoom->_gallery->_images[$pieces[$x+1]]->getInfo();
	$img_path = $mosConfig_live_site."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->_dir."/".$zoom->_gallery->_images[$pieces[$x+1]]->_viewsize;
	$id3_data = $zoom->_gallery->_images[$pieces[$x+1]]->_metadata;
	$artist = (!empty($id3_data["comments_html"]["artist"][0])) ? $id3_data["comments_html"]["artist"][0] : "no artist";
	$title = (!empty($id3_data["comments_html"]["title"][0])) ? $id3_data["comments_html"]["title"][0] : "no title";
	$playlist .= '<annotation>'.(!($artist == "no artist" && $title == "no title") ? $artist.' - '.$title : $zoom->_gallery->_images[$pieces[$x+1]]->_name).'</annotation>';
	$playlist .= '<location>'.$img_path.'</location>';
	$playlist .= '</track>';
}
?>
<playlist version="1" xmlns="http://xspf.org/ns/0/">
	<trackList>
		<?php echo $playlist ?>
	</trackList>
</playlist>