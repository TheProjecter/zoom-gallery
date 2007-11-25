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
| Filename: slovak.php                                                |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:slovak.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOm Media Gallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
//Language translation
define("_ZOOM_DATEFORMAT","%d.%m.%Y %H:%M"); // use the PHP strftime Format, more info at http://www.php.net
define("_ZOOM_PICK","Vybra? galériu");
define("_ZOOM_DELETE","Vymaza?");
define("_ZOOM_BACK","Naspä?");
define("_ZOOM_MAINSCREEN","Hlavná stránka");
define("_ZOOM_BACKTOGALLERY","Naspä? do galérie");
define("_ZOOM_INFO_DONE","hotovo!");
define("_ZOOM_TOOLTIP", "zOOm Tip");
define("_ZOOM_WARNING", "zOOm Upozornenie!");

//Gallery admin page
define("_ZOOM_ADMINSYSTEM","Admin System");
define("_ZOOM_USERSYSTEM","User System");
define("_ZOOM_ADMIN_TITLE","Media Gallery Admin System");
define("_ZOOM_USER_TITLE","Media Gallery User System");
define("_ZOOM_CATSMGR","Gallery Manager");
define("_ZOOM_CATSMGR_DESCR","create new galleries for your new media; create, edit and delete them here in the Gallery Manager.");
define("_ZOOM_SETTINGS_DDONOF","Enable Drag n Drop");
define("_ZOOM_NEW","New gallery");
define("_ZOOM_DEL","Delete gallery");
define("_ZOOM_MEDIAMGR","Media Manager");
define("_ZOOM_MEDIAMGR_DESCR","move, edit, delete, scan for media automatically or upload (multiple) new media manually.");
define("_ZOOM_THUMB", "Zoom Thumb Coder");
define("_ZOOM_THUMB_DESCR", "Compute your Zoom Thumb codes easily");
define("_ZOOM_UPLOAD","Upload file(s)");
define("_ZOOM_EDIT","Edit gallery");
define("_ZOOM_ADMIN_CREATE","Create database");
define("_ZOOM_ADMIN_CREATE_DESCR","build the required database tables so that you can start using the album");
define("_ZOOM_HD_PREVIEW","Preview");
define("_ZOOM_HD_CHECKALL","Check/Uncheck All");
define("_ZOOM_HD_CREATEDBY","Created by");
define("_ZOOM_HD_AFTER","Parent gallery");
define("_ZOOM_HD_HIDEMSG","Hide 'no media' text");
define("_ZOOM_HD_NAME","Title");
define("_ZOOM_HD_DIR","Directory");
define("_ZOOM_HD_NEW","New gallery");
define("_ZOOM_HD_SHARE","Share this gallery");
define("_ZOOM_SHARE","Share");
define("_ZOOM_UNSHARE","UnShare");
define("_ZOOM_PUBLISH","Publish");
define("_ZOOM_UNPUBLISH","UnPublish");
define("_ZOOM_TOPLEVEL","Top level");
define("_ZOOM_HD_UPLOAD","Upload file");
define("_ZOOM_A_ERROR_ERRORTYPE","Error type");
define("_ZOOM_A_ERROR_IMAGENAME","Image Name");
define("_ZOOM_A_ERROR_NOFFMPEG","<u>FFmpeg</u> not detected");
define("_ZOOM_A_ERROR_NOPDFTOTEXT","<u>PDFtoText</u> not detected");
define("_ZOOM_A_ERROR_NOTINSTALLED","Not installed");
define("_ZOOM_A_ERROR_CONFTODB","Error while saving configuration into database!");
define("_ZOOM_A_MESS_NOT_SHURE","* If you are not shure, use default \"auto\" ");
define("_ZOOM_A_MESS_SAFEMODE1","Note: \"Safe Mode\" is activated, therefore it is possible that uploads of bigger files will not work!<br />You should go to the Admin System and switch to FTP-Mode.");
define("_ZOOM_A_MESS_SAFEMODE2","Note: \"Safe Mode\" is activated, therefore it is possible that uploads of bigger files will not work!<br />zOOm recommends to activate the FTP-Mode in the Admin System.");
define("_ZOOM_A_MESS_PROCESSING_FILE","Processing file...");
define("_ZOOM_A_MESS_NOTOPEN_URL","Could not open url:");
define("_ZOOM_A_MESS_PARSE_URL","Parsing \"%s\" for images... "); // %s = $url
define("_ZOOM_A_MESS_NOJAVA","If you see above only a grey box or have troubles while uploading, it could be that<br />you haven't got the latest java run-time installed. Go to <a href=\"http://www.java.com\" target=\"_blank\">Java.com</a> <br /> and download the latest version.");
define("_ZOOM_SETTINGS","Settings");
define("_ZOOM_SETTINGS_DESCR","change and view all the available configuration settings here.");
define("_ZOOM_SETTINGS_TAB1","System");
define("_ZOOM_SETTINGS_TAB2","Media");
define("_ZOOM_SETTINGS_TAB3","Features");
define("_ZOOM_SETTINGS_TAB4","Layout");
define("_ZOOM_SETTINGS_TAB5","Watermarks");
define("_ZOOM_SETTINGS_TAB6","Safe Mode");
define("_ZOOM_SETTINGS_TAB7","Accessibility");
define("_ZOOM_SETTINGS_TAB8","Reset");
define("_ZOOM_SETTINGS_ERASE","Click to wipe all zoom gallery data and start anew. This resets settings and removes all images.");
define("_ZOOM_SETTINGS_CONVTYPE","Image Conversiontype");
define("_ZOOM_SETTINGS_GALLERY_FEATURES","Gallery View Features");
define("_ZOOM_SETTINGS_VIEW_FEATURES","Media View Features");

define("_ZOOM_SETTINGS_GALLERY","Gallery View");
define("_ZOOM_SETTINGS_VIEW","Media View");

define("_ZOOM_SETTINGS_GENERAL_FEATURES","General Features");
define("_ZOOM_SETTINGS_AUTODET","auto-detected: ");
define("_ZOOM_SETTINGS_IMGPATH","Path to media-files:");
define("_ZOOM_SETTINGS_TTIMGPATH","Current path to media is ");
define("_ZOOM_SETTINGS_CONVSETTINGS","Media conversion settings:");
define("_ZOOM_SETTINGS_IMPATH","Path to ImageMagick: ");
define("_ZOOM_SETTINGS_NETPBMPATH"," or NetPBM: ");
define("_ZOOM_SETTINGS_FFMPEGPATH","Path to FFmpeg");
define("_ZOOM_SETTINGS_FFMPEGTOOLTIP","FFmpeg is required to create thumbnails of your video files.<br />Supported extensions are: ");
define("_ZOOM_SETTINGS_OVERRIDE_FFMPEG","Use FFmpeg, even if zOOm is unable to verify its existence on this system.");
define("_ZOOM_SETTINGS_PDFTOTEXTPATH","Path to PDFtoText");
define("_ZOOM_SETTINGS_XPDFTOOLTIP","pdf2text, which is part of the Xpdf package, is required for PDF file indexing.");
define("_ZOOM_SETTINGS_OVERRIDE_PDF","Use PDFtoText, even if zOOm is unable to verify its existence on this system.");
define("_ZOOM_SETTINGS_MAXSIZE","Image max. size: ");
define("_ZOOM_SETTINGS_MAXSIZEKB","Medium - including images - max. size (in kB): "); //added: 08-08-2006
define("_ZOOM_SETTINGS_MAXSIZEKB_WARNING","The upload limit of this server, set by you ISP as part of the PHP configuration, is %s.<br />Thus, setting the limit higher than this value will have NO use!"); //added: 09-01-2006
define("_ZOOM_SETTINGS_THUMBSETTINGS","Thumbnail settings:");
define("_ZOOM_SETTINGS_QUALITY","NetPBM and GD2 JPEG quality: ");
define("_ZOOM_SETTINGS_SIZE","Thumbnail max. size: ");
define("_ZOOM_SETTINGS_TEMPNAME","Temporary Name: ");
define("_ZOOM_SETTINGS_AUTONUMBER","auto-number image names (eg. 1,2,3)");
define("_ZOOM_SETTINGS_TEMPDESCR","Temporary Description: ");
define("_ZOOM_SETTINGS_TITLE","Gallery title:");
define("_ZOOM_SETTINGS_SUBCATSPG","No. of (sub-)gallery columns");
define("_ZOOM_SETTINGS_COLUMNS","No. of thumbnail columns");
define("_ZOOM_SETTINGS_THUMBSPG","Thumbs per page");
define("_ZOOM_SETTINGS_CMTLENGTH","Comments max. length");
define("_ZOOM_SETTINGS_CHARS","chars");
define("_ZOOM_SETTINGS_GALLERYPREFIX","Gallery-title prefix");
define("_ZOOM_SETTINGS_SHOWOCCSPACE","Show occupied space in Media Manager");
define("_ZOOM_SETTINGS_TEMPLATE_TITLE","Template");
define("_ZOOM_SETTINGS_FEATURES_TITLE","Features ON/ OFF");
define("_ZOOM_SETTINGS_CSS_TITLE","Edit Stylesheets");
define("_ZOOM_SETTINGS_DISPLAY_TITLE","Data to display ON/ OFF");
define("_ZOOM_SETTINGS_TEMPLATE_CHOOSE","Select a template to define the look &amp; feel of your gallery");
define("_ZOOM_SETTINGS_TEMPLATE_TABLES","Classic (with tables)");
define("_ZOOM_SETTINGS_TEMPLATE_TABLELESS","Modern (tableless design)");
define("_ZOOM_SETTINGS_COMMENTS","Comments");
define("_ZOOM_SETTINGS_POPUP","PopUp Media");
define("_ZOOM_SETTINGS_CATIMG","Show gallery image");
define("_ZOOM_SETTINGS_SLIDESHOW","Prezentácia");
define("_ZOOM_SETTINGS_ZOOMLOGO","Display zOOm-logo");
define("_ZOOM_SETTINGS_SHOWHITS","Display no. of hits");
define("_ZOOM_SETTINGS_READEXIF","Read EXIF-data");
define("_ZOOM_SETTINGS_EXIFTOOLTIP","This feature will display additional EXIF and other IPTC data, without the need for the EXIF module for PHP to be installed on your system.");
define("_ZOOM_SETTINGS_READID3","Read mp3 ID3-data");
define("_ZOOM_SETTINGS_ID3TOOLTIP","This feature will display additional ID3 v1.1 and v2.0 data when viewing the details of an mp3 file.");
define("_ZOOM_SETTINGS_RATING","Rating");
define("_ZOOM_SETTINGS_CSS","Popup window");
define("_ZOOM_SETTINGS_CSSZOOM","zOOm gallery &amp; medium view");
define("_ZOOM_SETTINGS_SUCCESS","Configuration updated succesfully!");
define("_ZOOM_SETTINGS_ZOOMING","Image zoom");
define("_ZOOM_SETTINGS_ORDERBY","Thumbnail ordering method; order by");
define("_ZOOM_SETTINGS_CATORDERBY","(sub-)Gallery ordering method; order by");
define("_ZOOM_SETTINGS_DATE_ASC","DATE, ascending");
define("_ZOOM_SETTINGS_DATE_DESC","DATE, descending");
define("_ZOOM_SETTINGS_FLNM_ASC","FILENAME, ascending");
define("_ZOOM_SETTINGS_FLNM_DESC","FILENAME, descending");
define("_ZOOM_SETTINGS_NAME_ASC","NAME, ascending");
define("_ZOOM_SETTINGS_NAME_DESC","NAME, descending");
define("_ZOOM_SETTINGS_LBTOOLTIP","A lightbox is like a shopping cart filled with user-selected media, which may be downloaded as a ZIP file.");
define("_ZOOM_SETTINGS_SHOWNAME","Display Name");
define("_ZOOM_SETTINGS_SHOWDESCR","Display description");
define("_ZOOM_SETTINGS_SHOWKEYWORDS","Display keywords");
define("_ZOOM_SETTINGS_SHOWDATE","Display date");
define("_ZOOM_SETTINGS_SHOWUNAME","Display Username");
define("_ZOOM_SETTINGS_SHOWFILENAME","Display filename");
define("_ZOOM_SETTINGS_METABOX","Display floating box with details on gallery pages");
define("_ZOOM_SETTINGS_METABOXTOOLTIP","Unset this feature to increase the speed of your gallery. Efficient with large databases.");
define("_ZOOM_SETTINGS_ECARDS","E-cards");
define("_ZOOM_SETTINGS_ECARDS_LIFETIME","E-cards lifetime");
define("_ZOOM_SETTINGS_ECARDS_ONEWEEK","one week");
define("_ZOOM_SETTINGS_ECARDS_TWOWEEKS","two weeks");
define("_ZOOM_SETTINGS_ECARDS_ONEMONTH","one month");
define("_ZOOM_SETTINGS_ECARDS_THREEMONTHS","three months");
define("_ZOOM_SETTINGS_SHOWSEARCH","Search-field on ALL pages");
define("_ZOOM_SETTINGS_BOX_ANIMATE","Animate boxes");
define("_ZOOM_SETTINGS_BOX_PROPERTIES","Properties box visual state");
define("_ZOOM_SETTINGS_BOX_META","Metadata box visual state");
define("_ZOOM_SETTINGS_BOX_COMMENTS","Comments box visual state");
define("_ZOOM_SETTINGS_BOX_RATING","Rating box visual state");
define("_ZOOM_SETTINGS_TOPTEN","Display \"Top Ten\" link on main page");
define("_ZOOM_SETTINGS_LASTSUBM","Display \"Last Submitted Media\" link on main page");
define("_ZOOM_SETTINGS_SETMENUOPTION","Display \"Upload Media\" link in User Menu");
define("_ZOOM_SETTINGS_USEFTP","Use FTP mode?");
define("_ZOOM_SETTINGS_FTPHOST","Host name");
define("_ZOOM_SETTINGS_FTPUNAME","User name");
define("_ZOOM_SETTINGS_FTPPASS","Password");
define("_ZOOM_SETTINGS_FTPWARNING","Warning: Password is not saved secure!");
define("_ZOOM_SETTINGS_FTPHOSTDIR","Directory on host");
define("_ZOOM_SETTINGS_MESS_FTPHOSTDIR","Please provide the path to Joomla! from your ftp-root here. IMPORTANT: End <b>without</b> a slash or backslash!");
define("_ZOOM_SETTINGS_GROUP","Group");
define("_ZOOM_SETTINGS_PRIV_DESCR","You are able to change the privileges of each usergroup known in Joomla! and hereby change the privileges of
    each user that is a member of that group!<br />
    A user may, in theory, do the following actions: upload file(s), edit/ delete media, create/ edit/ delete (shared) galleries.<br />
    What they can and cannot do in the real world is up to you.");
define("_ZOOM_SETTINGS_CLOSE","Display \"Close\" link in popup");
define("_ZOOM_SETTINGS_MAINSCREEN","Display Mainscreen link in navigation breadcrumb");
define("_ZOOM_SETTINGS_NAVBUTTONS","Display Navigation buttons");
define("_ZOOM_SETTINGS_PROPERTIES","Display Properties below medium");
define("_ZOOM_SETTINGS_MEDIAFOUND","Display \"Media Found\" text in gallery");
define("_ZOOM_SETTINGS_ANONYMOUS_COMMENTS","Allow anonymous comment");
define("_ZOOM_SETTINGS_WM_ENABLE_TITLE","Enable Feature");
define("_ZOOM_SETTINGS_WM_TITLE", "Your watermarks");
define("_ZOOM_SETTINGS_WM_DESCR", "Your watermark appears on top of your images on this website. "
 . "The image will still be visible, but visitors will not be tempted to copy or print it."
 . "<br /><br />Suggestion: you can use your company logo as a watermark. "
 . "Please make sure that you set the background of the watermark image to be transparent!");
define("_ZOOM_SETTINGS_WM_IMG", "Image");
define("_ZOOM_SETTINGS_WM_NOWATERMARKS", "No watermarks found. You may upload a new watermark below.");
define("_ZOOM_SETTINGS_WM_PLACEMENT_TITLE", "Placement");
define("_ZOOM_SETTINGS_WM_PLACEMENT_DESCR", "You can define the position of the watermark on the target-image by "
 . "selecting one of the positions in the grey box below.");
define("_ZOOM_SETTINGS_WM_FILE","Upload watermark");
define("_ZOOM_SETTINGS_WM_UPLOAD_SUCCESS","Watermark successfully uploaded!");
define("_ZOOM_SETTINGS_WM_UPLOAD_FAIL","Watermark upload failed.");
define("_ZOOM_SETTINGS_WM_DEL_SUCCESS","Watermark deleted successfully!");
define("_ZOOM_SETTINGS_WM_DEL_FAIL","Watermark could not be deleted.");
define("_ZOOM_SYSTEM_TITLE","System Configurations");
define("_ZOOM_YES","yes");
define("_ZOOM_NO","no");
define("_ZOOM_VISIBLE","visible");
define("_ZOOM_HIDDEN","hidden");
define("_ZOOM_SAVE","Save");
define("_ZOOM_MOVEFILES","Move media");
define("_ZOOM_BUTTON_MOVE","Move");
define("_ZOOM_MOVEFILES_STEP1","Select target gallery &amp; move media");
define("_ZOOM_ALERT_MOVE","%s media succesfully moved, %s media could not be moved.");
define("_ZOOM_OPTIMIZE","Optimize tables");
define("_ZOOM_OPTIMIZE_DESCR","zOOm Media Gallery uses its tables a lot and thus creates overhead data, ie. 'junk data'. Click here to remove this junk.");
define("_ZOOM_OPTIMIZE_SUCCESS","zOOm Media Gallery tables optimized!");
define("_ZOOM_UPDATE","Update zOOm Media Gallery");
define("_ZOOM_UPDATE_DESCR","add new features, solve problems and resolve bugs! Check <a href=\"http://www.zoomfactory.org\" target=\"_blank\">www.zoomfactory.org</a> for the latest update!");
define("_ZOOM_UPDATE_XMLDATE","Date of last update");
define("_ZOOM_UPDATE_NOUPDATES","no updates yet!"); // added 11-08
define("_ZOOM_UPDATE_PACKAGE","Update ZIP-file: ");
define("_ZOOM_CREDITS","About zOOm Media Gallery &amp; Credits");

//Image actions
define("_ZOOM_DISKSPACEUSAGE","Diskspace %s is currently using");
define("_ZOOM_UPLOAD_SINGLE","single (ZIP-)file");
define("_ZOOM_UPLOAD_MULTIPLE","multiple files");
define("_ZOOM_UPLOAD_DRAGNDROP","Drag n Drop");
define("_ZOOM_UPLOAD_SCANDIR","scan directory");
define("_ZOOM_UPLOAD_INTRO","Click the <b>Browse</b> button to locate a medium to upload.");
define("_ZOOM_UPLOAD_STEP1","1. Select the number of files you want to upload: ");
define("_ZOOM_UPLOAD_STEP2","2. Select the gallery you want the files uploaded to: ");
define("_ZOOM_UPLOAD_STEP3","3. Use the Browse button to find the photos on your computer");
define("_ZOOM_SCAN_STEP1","Step 1: give a location to scan for media...");
define("_ZOOM_SCAN_STEP2","Step 2: select the files you want to upload...");
define("_ZOOM_SCAN_STEP3","Step 3: zOOm processes the files you selected...");
define("_ZOOM_SCAN_STEP1_DESCR","The location can either be a URL or a directory on the server.<br />&nbsp;   Tip: FTP media to a directory on your server then provide that path here!");
define("_ZOOM_SCAN_STEP2_DESCR1","Processing");
define("_ZOOM_SCAN_STEP2_DESCR2","as a local directory");
define("_ZOOM_FORMCREATE_NAME","Name");
define("_ZOOM_FORM_IMAGEFILE","Medium");
define("_ZOOM_FORM_IMAGEFILTER","Supported Media-types");
define("_ZOOM_FORM_INGALLERY","In gallery");
define("_ZOOM_FORM_SETFILENAME","Set media names with original filenames.");
define("_ZOOM_FORM_IGNORESIZES","Ignore preset maximum image dimensions"); //added: 12-08
define("_ZOOM_FORM_LOCATION","Location");
define("_ZOOM_BUTTON_SCAN","Submit URL or directory");
define("_ZOOM_BUTTON_UPLOAD","Upload");
define("_ZOOM_BUTTON_EDIT","Edit");
define("_ZOOM_BUTTON_CREATE","Create");
define("_ZOOM_CONFIRM_WIPE","WARNING!\\n Running this function will completely wipe your zOOm gallery and remove all images and galleries.\\n\\n Are you sure you want to continue?");
define("_ZOOM_CONFIRM_DEL","This option will remove a gallery completely, including media!\\nDo you want to proceed?");
define("_ZOOM_CONFIRM_DELMEDIUM","You are going to completely remove this medium!\\nDo you want to proceed?");
define("_ZOOM_ALERT_DEL","Gallery is deleted!");
define("_ZOOM_ALERT_NOCAT","No gallery selected!");
define("_ZOOM_ALERT_NOMEDIA","No media selected!");
define("_ZOOM_ALERT_EDITOK","Gallery fields have been edited succesfully!");
define("_ZOOM_ALERT_NEWGALLERY","New gallery created.");
define("_ZOOM_ALERT_NONEWGALLERY","Gallery not created!");
define("_ZOOM_ALERT_EDITIMG","Medium properties edited succesfully.");
define("_ZOOM_ALERT_DELPIC","Medium deleted successfully.");
define("_ZOOM_ALERT_NODELPIC","Medium could not be deleted!");
define("_ZOOM_ALERT_MOVEFAILURE","Medium could not be moved."); //added: 08-08-2006
define("_ZOOM_ALERT_NOPICSELECTED","No medium selected.");
define("_ZOOM_ALERT_NOPICSELECTED_MULT","No media selected.");
define("_ZOOM_ALERT_UPLOADOK","Medium uploaded succesfully!");
define("_ZOOM_ALERT_UPLOADSOK","media uploaded succesfully!");
define("_ZOOM_ALERT_WRONGFORMAT","Wrong image format.");
define("_ZOOM_ALERT_WRONGFORMAT_MULT","Wrong format.");
define("_ZOOM_ALERT_TOOBIG","Filesize of the medium is too big; %s is the allowed max."); //added 08-08-2006
define("_ZOOM_ALERT_IMGERROR","Error resizing image/ creating thumbnail.");
define("_ZOOM_ALERT_PCLZIPERROR","Error occured while extracting archive.");
define("_ZOOM_ALERT_INDEXERROR","Error occured while indexing document.");
define("_ZOOM_ALERT_WATERMARKERROR","Error occured while applying a watermark to the image.");
define("_ZOOM_ALERT_IMGFOUND","image(s) found.");
define("_ZOOM_INFO_CHECKCAT","Please pick a gallery before pressing the upload button!");
define("_ZOOM_BUTTON_ADDIMAGES","Add media");
define("_ZOOM_BUTTON_REMIMAGES","Remove media");
define("_ZOOM_INFO_PROCESSING","Processing file:");
define("_ZOOM_ITEMEDIT_TAB1","Properties");
define("_ZOOM_ITEMEDIT_TAB2","Members");
define("_ZOOM_ITEMEDIT_TAB3","Actions");
define("_ZOOM_USERSLIST_LINE1",">>Select members of this item<<");
define("_ZOOM_USERSLIST_ALLOWALL",">>Public access<<");
define("_ZOOM_USERSLIST_MEMBERSONLY",">>Members only<<");
define("_ZOOM_PUBLISHED","Published");
define("_ZOOM_SHARED","Shared");
define("_ZOOM_ROTATE","Rotate image 90 degrees");
define("_ZOOM_CLOCKWISE","clockwise");
define("_ZOOM_CCLOCKWISE","counter clockwise");
define("_ZOOM_FLIP_HORIZ","Flip image horizontally");
define("_ZOOM_FLIP_VERT","Flip image vertically");
define("_ZOOM_PROGRESS_DESCR","Your request is being processed... Please be patient.");

//Navigation (including Slideshow buttons)
define("_ZOOM_SLIDESHOW","Prezentácia:");
define("_ZOOM_PREV_IMG","dozadu");
define("_ZOOM_NEXT_IMG","dopredu");
define("_ZOOM_FIRST_IMG","prvý");
define("_ZOOM_LAST_IMG","posledný");
define("_ZOOM_PLAY","prehra? prezentáciu");
define("_ZOOM_STOP","zastavi? prehrávanie");
define("_ZOOM_RESET","reset");
define("_ZOOM_FIRST","Prvý");
define("_ZOOM_LAST","Posledný");
define("_ZOOM_PREVIOUS","Predchádzajúci");
define("_ZOOM_NEXT","Nasledujúci");
define("_ZOOM_IN_DESC", "Kurzorom myši ukážte na obrázok a pomocou šípiek nastavte mieru jeho zväèšenia.");

//Gallery actions
define("_ZOOM_SEARCH_BOX","Preh¾adáva? galérie");
define("_ZOOM_ADVANCED_SEARCH","Rozšírené vyh¾adávanie");
define("_ZOOM_SEARCH_KEYWORD","H¾ada? pomocou k¾úèových slov");
define("_ZOOM_IMAGES","obrázkov");
define("_ZOOM_IMGFOUND","bolo nájdených %s obrázkov - ste na strane %s z %s");
define("_ZOOM_SUBGALLERIES","pod-galérie");
define("_ZOOM_ALERT_COMMENTOK","Váš komentár bol úspešne pridaný!");
define("_ZOOM_ALERT_COMMENTERROR","Tento obrázok ste už okomentovali!");
define("_ZOOM_ALERT_VOTE_OK","Váš hlas bol zapoèítaný. Ïakujeme");
define("_ZOOM_ALERT_VOTE_ERROR","Za tento obrázok ste už hlasovali.");
define("_ZOOM_WINDOW_CLOSE","Zavrie?");
define("_ZOOM_NOPICS","Galéria je prázdna");
define("_ZOOM_PROPERTIES","Vlastnosti");
define("_ZOOM_COMMENTS","Komentár");
define("_ZOOM_NO_COMMENTS","V diskusii k tomuto obrázku nie sú pridané žiadne komentáre.");
define("_ZOOM_YOUR_NAME","Vaše meno");
define("_ZOOM_ADD","Prida?");
define("_ZOOM_NAME","Názov");
define("_ZOOM_DATE","Dátum pridania");
define("_ZOOM_UNAME","Pridal");
define("_ZOOM_DESCRIPTION","Popis");
define("_ZOOM_IMGNAME","Názov");
define("_ZOOM_FILENAME","Názov súboru");
define("_ZOOM_CLICKDOCUMENT","(kliknite na názov súboru a otvorte dokument)");
define("_ZOOM_KEYWORDS","K¾úèové slová");
define("_ZOOM_HITS","videní");
define("_ZOOM_CLOSE","Zavrie?");
define("_ZOOM_NOIMG", "Nebolo niè nájdené!");
define("_ZOOM_NONAME", "Musíte zada? meno!");
define("_ZOOM_NOCAT", "Nebola vybraná žiadna galéria!");
define("_ZOOM_EDITPIC", "Editova?");
define("_ZOOM_SETCATIMG","Nastavi? ako popisný obrázok galérie");
define("_ZOOM_SETPARENTIMG","Nastavi? ako popisný obrázok MATERSKEJ galérie.");
define("_ZOOM_PASS","Heslo");
define("_ZOOM_PASS_REQUIRED","Pre prístup k tejto galérii potrebujete heslo.<br />Prosím vyplòte políèko heslo<br />a stlaète tlaèidlo Vstúpi?. Ïakujeme.");
define("_ZOOM_PASS_BUTTON","Vstúpi?");
define("_ZOOM_PASS_GALLERY","Heslo");
define("_ZOOM_PASS_INNCORRECT","Nesprávne heslo");

//Hotlinking
define("_ZOOM_SETTINGS_HOTLINK","Zapnú? ochranu proti Hotlinkovaniu");
define("_ZOOM_SETTINGS_HPTOOLTIP","Ak je zapnutá ochrana proti Hotlinkovaniu, sú cesta k súboru a jeho názov skryté. Ak sa niekto pokúsi o nalinkovanie obrázka na inú stránku, tento sa na nej nezobrazí.");


//Lightbox
define("_ZOOM_LIGHTBOX","Lightbox");
define("_ZOOM_LIGHTBOX_GALLERY","Uloži? túto galériu do Lightboxu!");
define("_ZOOM_LIGHTBOX_ITEM","Uloži? tento obrázok do Lightboxu!");
define("_ZOOM_LIGHTBOX_VIEW","Pozrie? Lightbos");
define("_ZOOM_YOUR_LIGHTBOX","Obsah Vášho Lighboxu:");
define("_ZOOM_LIGHTBOX_EMPTY","Váš Lightbox je prázdny.");
define("_ZOOM_LIGHTBOX_ZIPBTN","Vytvori? ZIP-súbor");
define("_ZOOM_LIGHTBOX_PLAYLISTBTN","Vytvori? Playlist &amp; Prehra?");
define("_ZOOM_LIGHTBOX_CATS","Galérie");
define("_ZOOM_LIGHTBOX_TITLEDESCR","Titul &amp; Popis");
define("_ZOOM_ACTION","Akcia");
define("_ZOOM_LIGHTBOX_ADDED","Položka bola úspešne pridaná do Vášho Lightboxu!");
define("_ZOOM_LIGHTBOX_NOTADDED","Táto položka už bola do Vášho Lightboxu pridaná!");
define("_ZOOM_LIGHTBOX_EDITED","Položka bola úspešne zmenená!");
define("_ZOOM_LIGHTBOX_NOTEDITED","Chyba pri zmene položky!");
define("_ZOOM_LIGHTBOX_DEL","Položka bola úspešne odstránená z Vášho Lightboxu!");
define("_ZOOM_LIGHTBOX_NOTDEL","Pri odstraòovaní položky z Lightboxu sa vyskytla chyba!");
define("_ZOOM_LIGHTBOX_NOZIP","ZIP súbor bol už vytvorený. Lightbox neobsahuje žiadne položky!");
define("_ZOOM_LIGHTBOX_PARSEZIP","Parsujem obrázky z galérie...");
define("_ZOOM_LIGHTBOX_DOZIP","vytváram súbor ZIP...");
define("_ZOOM_LIGHTBOX_DLHERE","Teraz si môžete stiahnu? obsah Lightboxu");
define("_ZOOM_LIGHTBOX_PLSUCCESS","Playlist bol úspešne vytvorený. Prosím obnovte okno prehrávaèa.");
define("_ZOOM_LIGHTBOX_PLERROR","Chyba pri tvorbe Playlistu.");
define("_ZOOM_LIGHTBOX_NOAUDIO","Najskôr musíte do Lightboxu prida? zvukový súbor!");
define("_ZOOM_LIGHTBOX_NOITEMS","Lightbox je prázdny.");

//EXIF information
define("_ZOOM_EXIF","EXIF");
define("_ZOOM_EXIF_SHOWHIDE","Ukáza?/Skry? Metadata");

//MP3 id3 v1.1 or later information
define("_ZOOM_AUDIO_PLAYING","teraz prehrávam:");
define("_ZOOM_AUDIO_CLICKTOPLAY","Pre prehranie tohto súboru kliknite sem.");
define("_ZOOM_ID3","ID3");
define("_ZOOM_ID3_SHOWHIDE","Ukáza?/Skry? ID3-tag data");
define("_ZOOM_ID3_LENGTH","Dåžka");
define("_ZOOM_ID3_QUALITY","Kvalita");
define("_ZOOM_ID3_TITLE","Názov");
define("_ZOOM_ID3_ARTIST","Interpret");
define("_ZOOM_ID3_ALBUM","Album");
define("_ZOOM_ID3_YEAR","Rok");
define("_ZOOM_ID3_COMMENT","Komentár");
define("_ZOOM_ID3_GENRE","Žáner");

//Video metadata information
define("_ZOOM_VIDEO_SHOWHIDE","Ukáza?/Skry? Video data");
define("_ZOOM_VIDEO_PIXELRATIO","Rozlíšenie px");
define("_ZOOM_VIDEO_QUALITY","Kvalita obrazu");
define("_ZOOM_VIDEO_AUDIOQUALITY","Kvalita zvuku");
define("_ZOOM_VIDEO_CODEC","Codec");
define("_ZOOM_VIDEO_RESOLUTION","Rozlíšenie");

//rating
define("_ZOOM_RATING","Hodnotenie");
define("_ZOOM_NOTRATED","Nehodnotené!");
define("_ZOOM_VOTE","hlas");
define("_ZOOM_VOTES","hlasov");
define("_ZOOM_RATE0","odpad");
define("_ZOOM_RATE1","slabé");
define("_ZOOM_RATE2","priemer");
define("_ZOOM_RATE3","dobré");
define("_ZOOM_RATE4","ve¾mi dobré");
define("_ZOOM_RATE5","perfektné!");

//special
define("_ZOOM_TOPTEN","10 najlepšie hodnotených");
define("_ZOOM_LASTSUBM","Pridané ako posledné");
define("_ZOOM_LASTCOMM","Okomentované ako posledné");
define("_ZOOM_SEARCHRESULTS","Výsledky vyh¾adávania");
define("_ZOOM_TOPRATED","Najlepšie hodnotené");

//ecard
define("_ZOOM_ECARD_SENDAS","Posla? priate¾ovi ako virtuálnu poh¾adnicu!");
define("_ZOOM_ECARD_YOURNAME","Vaše meno");
define("_ZOOM_ECARD_YOUREMAIL","Váš email");
define("_ZOOM_ECARD_FRIENDSNAME","Meno priate¾a");
define("_ZOOM_ECARD_FRIENDSEMAIL","Email priate¾a");
define("_ZOOM_ECARD_MESSAGE","Text odkazu");
define("_ZOOM_ECARD_SENDCARD","Posla?");
define("_ZOOM_ECARD_SUCCESS","Vaša poh¾adnica bola úspešne odoslaná.");
define("_ZOOM_ECARD_CLICKHERE","Pre jej zobrazenie kliknite sem!");
define("_ZOOM_ECARD_ERROR","Pri posielaní poh¾adnice vznikla chyba. Poh¾adnica bola poslaná adresátovi");
define("_ZOOM_ECARD_TURN","Pozrite sa na zadnú stranu poh¾adnice!");
define("_ZOOM_ECARD_TURN2","Pozrite sa na prednú stranu poh¾adnice!");
define("_ZOOM_ECARD_SENDER","Poslané:");
define("_ZOOM_ECARD_SUBJ","Dostali ste virtuálnu poh¾adnicu od:");
define("_ZOOM_ECARD_MSG1","Vám poslal virtuálnu poh¾adnicu z");
define("_ZOOM_ECARD_MSG2","Kliknite na link nižšie a pozrite si Vašu virtuálnu poh¾adnicu!");
define("_ZOOM_ECARD_MSG3","Prosím neodpovedajte na tento mail/upozornenie. Jedná sa o automaticky generovaný email.");
define("_ZOOM_ECARD_ECARDEXPIRED","Je nám ¾úto, ale táto poh¾adnica už nie je k dispozícii, alebo vypršal èas jej platnosti.");

//installation-screen
define ('_ZOOM_INSTALL_CREATE_DIR','zOOm Installation is trying to create the Images-directory "images/zoom" ...');
define ('_ZOOM_INSTALL_CREATE_DIR_WM','zOOm Installation is trying to create the Images-directory "images/zoom/watermarks" ...');
define ('_ZOOM_INSTALL_CREATE_DIR_SUCC','done!');
define ('_ZOOM_INSTALL_CREATE_DIR_FAIL','failed!');
define ('_ZOOM_INSTALL_CREATE_DBASE_SUCC','Database created successfully!');
define ('_ZOOM_INSTALL_UPGRADED_DBASE_SUCC','Database upgraded successfully!');
define ('_ZOOM_INSTALL_MESS1','zOOm Image Gallery installed succesfully.<br>You are now ready to populate your albums!');
define ('_ZOOM_INSTALL_MESS2','NOTE: the first thing you should do now, is go to the components-menu above,<br>look for the entry "zOOm Media Gallery Admin", click it and<br>check the settings-page in the Admin-system.');
define ('_ZOOM_INSTALL_MESS3','Here you can change all the variables to fit zOOm into your configuration.');
define ('_ZOOM_INSTALL_MESS4','Don\'t forget to create a gallery and you\'re on your way!');
define ('_ZOOM_INSTALL_MESS_FAIL1','zOOM Gallery could not be installed successfully!');
define ('_ZOOM_INSTALL_MESS_FAIL2','Following directories must be created and afterwards the rights to be changed to  "0777":<br />'
. '"images/zoom"<br />'
. '/components/com_zoom/images"<br />'
. '"/components/com_zoom/admin"<br />'
. '"/components/com_zoom/classes"<br />'
. '"/components/com_zoom/images"<br />'
. '"/components/com_zoom/images/admin"<br />'
. '"/components/com_zoom/images/filetypes"<br />'
. '"/components/com_zoom/images/rating"<br />'
. '"/components/com_zoom/images/smilies"<br />'
. '"/components/com_zoom/language"<br />'
. '"/components/com_zoom/tabs"');
define ('_ZOOM_INSTALL_MESS_FAIL3','Once you have created those directories and changed the rights, go to <br /> "Components -> zOOm Media Gallery" and fit the settings to yours.');
?>