<?php
/**
 * zOOm Media Gallery! - a multi-gallery component
 * 
 * @package zmg
 * @author Mike de Boer <mdeboer AT ebuddy.com>
 * @copyright Copyright &copy; 2007, Mike de Boer. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */

defined('_ZMG_EXEC') or die('Restricted access');

$zoom_config = array();
$zoom_config['meta'] = array();
$zoom_config['meta']['title'] = "zOOm Media Gallery";
$zoom_config['meta']['description'] = "...delivering beautiful content";
$zoom_config['meta']['keywords'] = "";
$zoom_config['locale'] = array();
$zoom_config['locale']['default'] = "nl_NL";
$zoom_config['locale']['encoding'] = "UTF-8";
$zoom_config['locale']['domain'] = "messages";
$zoom_config['db'] = array();
$zoom_config['db']['prefix'] = "zmg_";
$zoom_config['db']['tables'] = array();
$zoom_config['db']['tables']['galleries'] = "galleries";
$zoom_config['db']['tables']['media'] = "media";
$zoom_config['db']['tables']['comments'] = "comments";
$zoom_config['db']['tables']['editmon'] = "editmon";
$zoom_config['db']['tables']['getid3cache'] = "getid3_cache";
$zoom_config['db']['tables']['priviliges'] = "priviliges";
$zoom_config['filesystem'] = array();
$zoom_config['filesystem']['mediapath'] = "images/zoom/";
$zoom_config['filesystem']['dirperms'] = "";
$zoom_config['filesystem']['fileperms'] = "";
$zoom_config['filesystem']['upload'] = array();
$zoom_config['filesystem']['upload']['maxfilesize'] = 2048;
$zoom_config['filesystem']['upload']['tempdescr'] = "Temporary description, please change...";
$zoom_config['filesystem']['upload']['tempname'] = "Temporary name, please change.";
$zoom_config['filesystem']['upload']['autonumber'] = 1;
$zoom_config['smarty'] = array();
$zoom_config['smarty']['template_dir'] = "/home/mike/workspace/zoom-gallery/var/www/templates";
$zoom_config['smarty']['compile_dir'] = "/home/mike/workspace/zoom-gallery/etc/compiled";
$zoom_config['smarty']['cache_dir'] = "/home/mike/workspace/zoom-gallery/etc/cache";
$zoom_config['smarty']['config_dir'] = "/home/mike/workspace/zoom-gallery/etc";
$zoom_config['smarty']['active_template'] = "default";
$zoom_config['layout'] = array();
$zoom_config['layout']['columnsno'] = 2;
$zoom_config['layout']['pagesize'] = 9;
$zoom_config['layout']['viewtype'] = 1;
$zoom_config['layout']['galleryprefix'] = "";
$zoom_config['layout']['showoccspace'] = 1;
$zoom_config['layout']['showtopten'] = 1;
$zoom_config['layout']['showlastsubm'] = 1;
$zoom_config['layout']['showcloselink'] = 1;
$zoom_config['layout']['showmainscreen'] = 1;
$zoom_config['layout']['shownavbuttons'] = 1;
$zoom_config['layout']['showproperties'] = 1;
$zoom_config['layout']['showmediafound'] = 1;
$zoom_config['layout']['usepopup'] = 1;
$zoom_config['layout']['showgalleryimg'] = 1;
$zoom_config['layout']['showzmglogo'] = 1;
$zoom_config['layout']['showgallerydsc'] = 1;
$zoom_config['layout']['showsearchbox'] = 1;
$zoom_config['layout']['medium'] = array();
$zoom_config['layout']['medium']['columnsno'] = 3;
$zoom_config['layout']['medium']['showhits'] = 1;
$zoom_config['layout']['medium']['showname'] = 1;
$zoom_config['layout']['medium']['showdescr'] = 1;
$zoom_config['layout']['medium']['showkeywords'] = 1;
$zoom_config['layout']['medium']['showdate'] = 1;
$zoom_config['layout']['medium']['showusername'] = 1;
$zoom_config['layout']['medium']['showfilename'] = 1;
$zoom_config['layout']['medium']['showmetadata'] = 1;
$zoom_config['layout']['ordering'] = array();
$zoom_config['layout']['ordering']['media'] = 1;
$zoom_config['layout']['ordering']['galleries'] = 1;
$zoom_config['app'] = array();
$zoom_config['app']['secret'] = "OI8euC5USKvSgkf4";
$zoom_config['app']['version'] = "2.6 Alpha";
$zoom_config['app']['features'] = array();
$zoom_config['app']['features']['hotlinkprotection'] = 1;
$zoom_config['app']['features']['comments'] = 1;
$zoom_config['app']['features']['dragndrop'] = 1;
$zoom_config['app']['features']['rating'] = 1;
$zoom_config['app']['features']['imagezoom'] = 1;
$zoom_config['app']['features']['slideshow'] = 1;
$zoom_config['app']['features']['lightbox'] = 1;
$zoom_config['app']['errors'] = array();
$zoom_config['app']['errors']['defaultmode'] = 4;
$zoom_config['app']['errors']['defaultoption'] = 1024;

$zoom_config['events'] = array();

$zoom_config['plugins'] = array();
$zoom_config['plugins']['toolbox'] = array();
$zoom_config['plugins']['toolbox']['general'] = array();
$zoom_config['plugins']['toolbox']['general']['imagesizemax'] = "600";
$zoom_config['plugins']['toolbox']['general']['imagesizethumbnail'] = "100";
$zoom_config['plugins']['toolbox']['general']['jpegquality'] = "100";
$zoom_config['plugins']['toolbox']['general']['conversiontool'] = "4";
$zoom_config['plugins']['toolbox']['imagemagick'] = array();
$zoom_config['plugins']['toolbox']['imagemagick']['path'] = "auto";
$zoom_config['plugins']['toolbox']['netpbm'] = array();
$zoom_config['plugins']['toolbox']['netpbm']['path'] = "auto";
$zoom_config['plugins']['toolbox']['pdftotext'] = array();
$zoom_config['plugins']['toolbox']['pdftotext']['path'] = "auto";
$zoom_config['plugins']['toolbox']['pdftotext']['override'] = "0";
$zoom_config['plugins']['toolbox']['ffmpeg'] = array();
$zoom_config['plugins']['toolbox']['ffmpeg']['path'] = "auto";
$zoom_config['plugins']['toolbox']['ffmpeg']['override'] = "0";
?>
