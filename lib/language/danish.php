<?php
//zOOm Media Gallery//
/** 
-----------------------------------------------------------------------
|  zOOm Media Gallery! by Mike de Boer - a multi-gallery component    |
-----------------------------------------------------------------------

-----------------------------------------------------------------------
|                                                                     |
| Author: Mike de Boer, <http://www.mikedeboer.nl>         
|
| Translated by: Morten Hald, <http://www.mortenhald.dk> and Lars Christensen <http://www.initpub.dk>
|                                                                     |
| Copyright: copyright (C) 2007 by Mike de Boer                       |
| Description: zOOm Media Gallery, a multi-gallery component for      |
|              Joomla!. It's the most feature-rich gallery component  |
|              for Joomla!! For documentation and a detailed list     |
|              of features, check the zOOm homepage:                  |
|              http://www.zoomfactory.org                             |
| License: GPL                                                        |
| Filename: danish.php                                                |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:danish.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOm Media Gallery
* @author Morten Hald <joomla@mortenhald.dk> and Lars Christensen <joomla@initpub.dk>
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
//Language translation
define("_ZOOM_DATEFORMAT","%d.%m.%Y %H:%M"); // use the PHP strftime Format, more info at http://www.php.net
define("_ZOOM_PICK","Vælg et galleri");
define("_ZOOM_DELETE","Slet");
define("_ZOOM_BACK","Tilbage");
define("_ZOOM_MAINSCREEN","Start");
define("_ZOOM_BACKTOGALLERY","Tilbage til galleriet");
define("_ZOOM_INFO_DONE","Færdig!");
define("_ZOOM_TOOLTIP", "zOOm Tip");
define("_ZOOM_WARNING", "zOOm advarsel!");

//Gallery admin page
define("_ZOOM_ADMINSYSTEM","Administrator system");
define("_ZOOM_USERSYSTEM","Bruger system");
define("_ZOOM_ADMIN_TITLE","Medie galleri administrator system");
define("_ZOOM_USER_TITLE","Medie galleri bruger system");
define("_ZOOM_CATSMGR","Galleriværktøj");
define("_ZOOM_CATSMGR_DESCR","opret nye gallerier til nye billeder; opret, rediger og slet dem her i galleriværktøjet.");
define("_ZOOM_SETTINGS_DDONOF","Tillad Drag n Drop");
define("_ZOOM_NEW","Nyt galleri");
define("_ZOOM_DEL","Slet galleri");
define("_ZOOM_MEDIAMGR","Galleriværktøj");
define("_ZOOM_MEDIAMGR_DESCR","flyt, rediger, slet, scan efter billeder automatisk eller upload (flere) nye billeder manuelt.");
define("_ZOOM_THUMB", "Zoom Thumb oprettelse");
define("_ZOOM_THUMB_DESCR", "Opret dine Zoom Thumbs let");
define("_ZOOM_UPLOAD","Upload fil(s)");
define("_ZOOM_EDIT","Rediger galleri");
define("_ZOOM_ADMIN_CREATE","Opret database");
define("_ZOOM_ADMIN_CREATE_DESCR","opret de nødvendige database tabeller så du kan benytte albummet");
define("_ZOOM_HD_PREVIEW","Preview");
define("_ZOOM_HD_CHECKALL","Vælg/fravælg alt");
define("_ZOOM_HD_CREATEDBY","Oprettet af");
define("_ZOOM_HD_AFTER","Nuværende galleri");
define("_ZOOM_HD_HIDEMSG","Skjul 'ingen billeder' tekst");
define("_ZOOM_HD_NAME","Titel");
define("_ZOOM_HD_DIR","Mappe");
define("_ZOOM_HD_NEW","Nyt galleri");
define("_ZOOM_HD_SHARE","Del dette galleri");
define("_ZOOM_SHARE","Del");
define("_ZOOM_UNSHARE","Fjern deling");
define("_ZOOM_PUBLISH","Offentlig");
define("_ZOOM_UNPUBLISH","Fjern offentliggørelse");
define("_ZOOM_TOPLEVEL","Top niveau");
define("_ZOOM_HD_UPLOAD","Upload fil");
define("_ZOOM_A_ERROR_ERRORTYPE","Fejl type");
define("_ZOOM_A_ERROR_IMAGENAME","Navn på billede");
define("_ZOOM_A_ERROR_NOFFMPEG","<u>FFmpeg</u> ikke fundet");
define("_ZOOM_A_ERROR_NOPDFTOTEXT","<u>PDFtoText</u> ikke fundet");
define("_ZOOM_A_ERROR_NOTINSTALLED","Ikke installeret");
define("_ZOOM_A_ERROR_CONFTODB","Fejl da konfigurationen skulle gemmes i databasen!");
define("_ZOOM_A_MESS_NOT_SHURE","* Er du ikke sikker, benyt standardopsætning \"auto\" ");
define("_ZOOM_A_MESS_SAFEMODE1","Note: \"Safe Mode\" er aktiveret, det er derfor muligt at uploading af større filer ikke fungerer!<br />Gå til Admin System and skift til FTP-Mode.");
define("_ZOOM_A_MESS_SAFEMODE2","Note: \"Safe Mode\" er aktiveret, det er derfor muligt at uploads af større filer ikke vil fungere!<br />zOOm anbefaler at aktivere FTP-tilstand i administrationsværktøjet.");
define("_ZOOM_A_MESS_PROCESSING_FILE","Bearbejder fil...");
define("_ZOOM_A_MESS_NOTOPEN_URL","Kunne ikke åbne url:");
define("_ZOOM_A_MESS_PARSE_URL","Ser efter \"%s\" efter billeder... "); // %s = $url
define("_ZOOM_A_MESS_NOJAVA","Hvis du kun ser en grå box ovenfor eller hvis du har problemer med at uploade, så kan det skyldes at<br />du ikke har seneste java run-time installeret. Gå til <a href=\"http://www.java.com\" target=\"_blank\">Java.com</a> <br /> og download den seneste version.");
define("_ZOOM_SETTINGS","Indstillinger");
define("_ZOOM_SETTINGS_DESCR","Skift og se alle de mulige konfigurationsindstillinger her.");
define("_ZOOM_SETTINGS_TAB1","System");
define("_ZOOM_SETTINGS_TAB2","Billeder");
define("_ZOOM_SETTINGS_TAB3","Layout");
define("_ZOOM_SETTINGS_TAB4","Vandmærker");
define("_ZOOM_SETTINGS_TAB5","Safe Mode");
define("_ZOOM_SETTINGS_TAB6","Tilgængelighed");
define("_ZOOM_SETTINGS_TAB7","Annuller");
define("_ZOOM_SETTINGS_ERASE","Klik for at slette alle billedgallerier og starte et nyt galleri. Hvis du slettes bliver alle billeder fjernet.");
define("_ZOOM_SETTINGS_CONVTYPE","Type af billedkonvertering");
define("_ZOOM_SETTINGS_AUTODET","fundet automatisk: ");
define("_ZOOM_SETTINGS_IMGPATH","Sti til billedfiler:");
define("_ZOOM_SETTINGS_TTIMGPATH","Nuværende sti til billeder er ");
define("_ZOOM_SETTINGS_CONVSETTINGS","Indstillinger til billedkonvertering:");
define("_ZOOM_SETTINGS_IMPATH","Sti til ImageMagick: ");
define("_ZOOM_SETTINGS_NETPBMPATH"," eller NetPBM: ");
define("_ZOOM_SETTINGS_FFMPEGPATH","Sti til FFmpeg");
define("_ZOOM_SETTINGS_FFMPEGTOOLTIP","FFmpeg er nødvendig for at oprette thumbnails af dine videofiler.<br />Følgende filtyper understøttes: ");
define("_ZOOM_SETTINGS_OVERRIDE_FFMPEG","Anvend FFmpeg, selv hvis zOOm ikke er i stand til at finde programmet på dette system.");
define("_ZOOM_SETTINGS_PDFTOTEXTPATH","Sti til PDFtoText");
define("_ZOOM_SETTINGS_XPDFTOOLTIP","pdf2text, som er en del af Xpdf pakken, er nødvendig for at kunne indeksere PDF filer.");
define("_ZOOM_SETTINGS_OVERRIDE_PDF","Anvend PDFtoText, selv om zOOm ikke kan bekræfte om programmet er tilgængeligt på systemet.");
define("_ZOOM_SETTINGS_MAXSIZE","Maksimal billedstørrelse: ");
define("_ZOOM_SETTINGS_THUMBSETTINGS","Thumbnail indstillinger:");
define("_ZOOM_SETTINGS_QUALITY","NetPBM og GD2 JPEG kvalitet: ");
define("_ZOOM_SETTINGS_SIZE","Maksimal thumbnail størrelse: ");
define("_ZOOM_SETTINGS_TEMPNAME","Midlertidigt navn: ");
define("_ZOOM_SETTINGS_AUTONUMBER","autonummerering af billednavne (eg. 1,2,3)");
define("_ZOOM_SETTINGS_TEMPDESCR","Midlertidig beskrivelse: ");
define("_ZOOM_SETTINGS_TITLE","Billedgalleriets titel:");
define("_ZOOM_SETTINGS_SUBCATSPG","Antal kolonner i undergallerier");
define("_ZOOM_SETTINGS_COLUMNS","Antal thumbnailkolonner");
define("_ZOOM_SETTINGS_THUMBSPG","Thumbs pr. side");
define("_ZOOM_SETTINGS_CMTLENGTH","Maksimal længde på kommentar");
define("_ZOOM_SETTINGS_CHARS","karakterer");
define("_ZOOM_SETTINGS_GALLERYPREFIX","Gallerititel");
define("_ZOOM_SETTINGS_SHOWOCCSPACE","Anvender følgende plads i billedhåndteringen");
define("_ZOOM_SETTINGS_FEATURES_TITLE","Indstillinger ON/ OFF");
define("_ZOOM_SETTINGS_CSS_TITLE","Rediger stylesheets");
define("_ZOOM_SETTINGS_DISPLAY_TITLE","Vis data ON/ OFF");
define("_ZOOM_SETTINGS_COMMENTS","Kommentarer");
define("_ZOOM_SETTINGS_POPUP","PopUp billeder");
define("_ZOOM_SETTINGS_CATIMG","Vis galleribillede");
define("_ZOOM_SETTINGS_SLIDESHOW","Lysbilledshow");
define("_ZOOM_SETTINGS_ZOOMLOGO","Vis zOOm-logo");
define("_ZOOM_SETTINGS_SHOWHITS","Vis antal hits");
define("_ZOOM_SETTINGS_READEXIF","Læs EXIF-data");
define("_ZOOM_SETTINGS_EXIFTOOLTIP","Denne funktion vil vise yderligere EXIF og andre IPTC data, uden at EXIF modulet til PHP skal være installeret på dit system.");
define("_ZOOM_SETTINGS_READID3","Læs mp3 ID3-data");
define("_ZOOM_SETTINGS_ID3TOOLTIP","Denne funktion vil vise yderligere ID3 v1.1 and v2.0 data når du ser detaljerne på en mp3 fil.");
define("_ZOOM_SETTINGS_RATING","Vurdering");
define("_ZOOM_SETTINGS_CSS","Popupvindue");
define("_ZOOM_SETTINGS_CSSZOOM","zOOm galleri &amp; billedvisning");
define("_ZOOM_SETTINGS_SUCCESS","Konfigurationen er nu blevet opdateret!");
define("_ZOOM_SETTINGS_ZOOMING","Billedzoom");
define("_ZOOM_SETTINGS_ORDERBY","Sortering af thumbnails, sorteret efter");
define("_ZOOM_SETTINGS_CATORDERBY","Metode til sortering af undergallerier");
define("_ZOOM_SETTINGS_DATE_ASC","DATO, stigende");
define("_ZOOM_SETTINGS_DATE_DESC","DATO, faldende");
define("_ZOOM_SETTINGS_FLNM_ASC","FILNAVN, stigende");
define("_ZOOM_SETTINGS_FLNM_DESC","FILNAVN, faldende");
define("_ZOOM_SETTINGS_NAME_ASC","NAVN, stigende");
define("_ZOOM_SETTINGS_NAME_DESC","NAVN, faldende");
define("_ZOOM_SETTINGS_LBTOOLTIP","En lysboks er som en indkøbskurv fyldt med de billeder du vælger. Billederne kan herefter downloades som en zip-fil.");
define("_ZOOM_SETTINGS_SHOWNAME","Vis navn");
define("_ZOOM_SETTINGS_SHOWDESCR","Vis beskrivelse");
define("_ZOOM_SETTINGS_SHOWKEYWORDS","Vis nøgleord");
define("_ZOOM_SETTINGS_SHOWDATE","Vis dato");
define("_ZOOM_SETTINGS_SHOWUNAME","´Vis brugernavn");
define("_ZOOM_SETTINGS_SHOWFILENAME","Vis filnavn");
define("_ZOOM_SETTINGS_METABOX","Vis boks med detaljer fra gallerisider");
define("_ZOOM_SETTINGS_METABOXTOOLTIP","Fravælg denne funktion for at forøge hastigheden på dit galleri. Effektivt ved større databaser.");
define("_ZOOM_SETTINGS_ECARDS","E-cards");
define("_ZOOM_SETTINGS_ECARDS_LIFETIME","E-cards uden slutdato");
define("_ZOOM_SETTINGS_ECARDS_ONEWEEK","en uge");
define("_ZOOM_SETTINGS_ECARDS_TWOWEEKS","to uger");
define("_ZOOM_SETTINGS_ECARDS_ONEMONTH","en måned");
define("_ZOOM_SETTINGS_ECARDS_THREEMONTHS","tre måneder");
define("_ZOOM_SETTINGS_SHOWSEARCH","Søgefelt på alle sider");
define("_ZOOM_SETTINGS_BOX_ANIMATE","Animerede bokse");
define("_ZOOM_SETTINGS_BOX_PROPERTIES","Egenskaber for boks");
define("_ZOOM_SETTINGS_BOX_META","Egenskaber for metadata boks");
define("_ZOOM_SETTINGS_BOX_COMMENTS","Egenskaber for kommentarboks");
define("_ZOOM_SETTINGS_BOX_RATING","Egenskaber for vurderingsboks");
define("_ZOOM_SETTINGS_TOPTEN","Display \"Top 10\" link til forside");
define("_ZOOM_SETTINGS_LASTSUBM","Vis \"Sidst offentliggjorte billeder\" link til forside");
define("_ZOOM_SETTINGS_SETMENUOPTION","Vis \"Upload billeder\" link til brugermenu");
define("_ZOOM_SETTINGS_USEFTP","Anvend FTP mode?");
define("_ZOOM_SETTINGS_FTPHOST","Navn på udbyder");
define("_ZOOM_SETTINGS_FTPUNAME","Brugernavn");
define("_ZOOM_SETTINGS_FTPPASS","Kodeord");
define("_ZOOM_SETTINGS_FTPWARNING","Advarsel: Kodeordet bliver ikke gemt sikkert!");
define("_ZOOM_SETTINGS_FTPHOSTDIR","Filmappe hos udbyder");
define("_ZOOM_SETTINGS_MESS_FTPHOSTDIR","Skriv venligst stien til Joomla! fra din ftp-root her. VIGTIGT: Afslut <b>uden</b> en skråstreg eller en backslash!");
define("_ZOOM_SETTINGS_GROUP","Gruppe");
define("_ZOOM_SETTINGS_PRIV_DESCR","Du har mulighed for at ændre rettigheder for hver Joomla brugergruppe og derigennem ændre rettigheder for hver enkelt bruger som er medlem af den grupp!<br/>
En bruger kan, teoretisk set, gøre det følgende: Uploade et billede(-r), rette el. slette medier, oprette/ ændre/ slette (delte) gallerier. <br />
Hvad de kan og ikke kan er helt op til dig.");
define("_ZOOM_SETTINGS_CLOSE","Vis \"Luk\" link i popup vindue");
//define("_ZOOM_SETTINGS_MAINSCREEN","Display Mainscreen link in navigation breadcrumb"); Kunne være et problem her //
define("_ZOOM_SETTINGS_MAINSCREEN","Vis link til Startsiden i navigations stien");
define("_ZOOM_SETTINGS_NAVBUTTONS","Vis navigations kanpper");
define("_ZOOM_SETTINGS_PROPERTIES","Vis egenskaber under middel");
define("_ZOOM_SETTINGS_MEDIAFOUND","Display \"Media Found\" text in gallery");
define("_ZOOM_SETTINGS_MEDIAFOUND","Vis \"Medier fundet\" tekst i galleriet");
define("_ZOOM_SETTINGS_ANONYMOUS_COMMENTS","Tillad anonyme kommentarer");
//define("_ZOOM_SETTINGS_WM_ENABLE_TITLE","Enable Feature"); Kunne være et problem //
define("_ZOOM_SETTINGS_WM_ENABLE_TITLE","Brug egenskaber");
define("_ZOOM_SETTINGS_WM_TITLE", "Dit vandmærke");
define("_ZOOM_SETTINGS_WM_DESCR", "Dit vandmærke vil være synligt på dine billeder i dit galleri. "
 . "Billedet vil stadigvæk være synligt, men besøgende vil være mindre fristet til at kopiere el. printe det."
 . "<br /><br />Forslag: Du kan bruge dit firmas logo som vandmærke. "
 . "Vær sikker på at dit vandmærke har en gennemsigtig baggrund!");
define("_ZOOM_SETTINGS_WM_IMG", "Billede");
define("_ZOOM_SETTINGS_WM_NOWATERMARKS", "Intet vandmærke fundet. Du kan uploade et nyt umiddelbart herunder.");
define("_ZOOM_SETTINGS_WM_PLACEMENT_TITLE", "Placering");
define("_ZOOM_SETTINGS_WM_PLACEMENT_DESCR", "Du kan bestemme placeringen af dit vandmærke, ved at vælge én "
 . "af positionerne i den grå boks umiddelbart herunder.");
define("_ZOOM_SETTINGS_WM_FILE","Upload vandmærke");
define("_ZOOM_SETTINGS_WM_UPLOAD_SUCCESS","Vandmærket er udloaded!");
define("_ZOOM_SETTINGS_WM_UPLOAD_FAIL","Vandmærket blev ikke uploaded.");
define("_ZOOM_SETTINGS_WM_DEL_SUCCESS","Vandmærket er slettet!");
define("_ZOOM_SETTINGS_WM_DEL_FAIL","Vandmærket kunne ikke slettes.");
define("_ZOOM_SYSTEM_TITLE","System konfiguration");
define("_ZOOM_YES","ja");
define("_ZOOM_NO","nej");
define("_ZOOM_VISIBLE","synlig");
define("_ZOOM_HIDDEN","gemt");
define("_ZOOM_SAVE","Gem");
define("_ZOOM_MOVEFILES","Flyt medie");
define("_ZOOM_BUTTON_MOVE","Flyt");
// define("_ZOOM_MOVEFILES_STEP1","Select target gallery &amp; move media"); //
define("_ZOOM_MOVEFILES_STEP1","Vælg hvilket galleri medierne skal flyttes &amp; flyt medier");
define("_ZOOM_ALERT_MOVE","%s medier er flyttet, %s medier kunne ikke flyttes.");
define("_ZOOM_OPTIMIZE","Optimer tabeller");
define("_ZOOM_OPTIMIZE_DESCR","zOOm Medie Galleri gør stor brug af tabeller og dette resulterer i spild data, dvs. 'junk data'. Klik her for at fjerne dette spild data.");
define("_ZOOM_OPTIMIZE_SUCCESS","zOOm Medie Galleriets tabeller er blevet optimeret!");
define("_ZOOM_UPDATE","Opdater zOOm Medie Galleri");
define("_ZOOM_UPDATE_DESCR","tilføj ny funktionalitet, løs problemer og fjern fejl! Tjek <a href=\"http://www.zoomfactory.org\" target=\"_blank\">www.zoomfactory.org</a> for de seneste opdateringer!");
define("_ZOOM_UPDATE_XMLDATE","Dato for seneste opdatering");
define("_ZOOM_UPDATE_NOUPDATES","ingen opdateringer endnu!"); // added 11-08
define("_ZOOM_UPDATE_PACKAGE","Opdater ZIP-filen: ");
define("_ZOOM_CREDITS","Om zOOm Medie Galleri");

//Image actions
// define("_ZOOM_DISKSPACEUSAGE","Diskspace %s is currently using"); //Kunne være et problem
define("_ZOOM_DISKSPACEUSAGE","Pladsforbrug %s bruger i øjeblikket");
define("_ZOOM_UPLOAD_SINGLE","enkelt (ZIP-)fil");
define("_ZOOM_UPLOAD_MULTIPLE","mange filer");
define("_ZOOM_UPLOAD_DRAGNDROP","Drag n Drop");
define("_ZOOM_UPLOAD_SCANDIR","Gennemsøg fil-bibliotek");
define("_ZOOM_UPLOAD_INTRO","Klik <b>Søg</b> knappen for at finde medie til upload.");
define("_ZOOM_UPLOAD_STEP1","1. Vælg det antal filer du ønsker at uploade: ");
define("_ZOOM_UPLOAD_STEP2","2. Vælg det galleri du ønsker at uploade medier til: ");
define("_ZOOM_UPLOAD_STEP3","3. Brug 'Søg' knappen til at finde medier på din computer");
define("_ZOOM_SCAN_STEP1","Step 1: angiv en kilde for at søge efter medier...");
define("_ZOOM_SCAN_STEP2","Step 2: vælg hvilke filer du ønsker at uploade...");
define("_ZOOM_SCAN_STEP3","Step 3: et øjeblik...");
define("_ZOOM_SCAN_STEP1_DESCR","Kilden kan enten være en URL eller bibliotek på en server.<br />&nbsp;   Tip: FTP medier til et bibliotek på din server og angiv stien her!");
define("_ZOOM_SCAN_STEP2_DESCR1","Et øjeblik...");
define("_ZOOM_SCAN_STEP2_DESCR2","som et lokalt bibliotek");
define("_ZOOM_FORMCREATE_NAME","Navn");
define("_ZOOM_FORM_IMAGEFILE","Medium");
define("_ZOOM_FORM_IMAGEFILTER","Understøttet medie typer");
define("_ZOOM_FORM_INGALLERY","I galleriet");
define("_ZOOM_FORM_SETFILENAME","Angiv medie navn som originale filnavn.");
define("_ZOOM_FORM_IGNORESIZES","Ignorer forvalget maksimum billede størrelse"); //added: 12-08
define("_ZOOM_FORM_LOCATION","Placering");
define("_ZOOM_BUTTON_SCAN","Angiv URL eller lokalt bibliotek");
define("_ZOOM_BUTTON_UPLOAD","Upload");
define("_ZOOM_BUTTON_EDIT","Rediger");
define("_ZOOM_BUTTON_CREATE","Opret");
define("_ZOOM_CONFIRM_WIPE","ADVARSEL!\\n Hvis du benytter denne funktion, vil samtlige gallerier og medier blive slettet.\\n\\n Er du sikker på at du vil foresætte?");
define("_ZOOM_CONFIRM_DEL","Du er ved at slette galleriet, inklusivt medier!\\nDo vil du foresætte?");
define("_ZOOM_CONFIRM_DELMEDIUM","Du er ved at slette mediet!\\nDo vil du foresætte?");
define("_ZOOM_ALERT_DEL","Galleriet er slettet!");
define("_ZOOM_ALERT_NOCAT","Intet galleri valgt!");
define("_ZOOM_ALERT_NOMEDIA","Intet medie valgt!");
define("_ZOOM_ALERT_EDITOK","Galleri felter er blevet redigeret!");
define("_ZOOM_ALERT_NEWGALLERY","Nyt galleri oprettet.");
define("_ZOOM_ALERT_NONEWGALLERY","Galleriet blev ikke oprettet!");
define("_ZOOM_ALERT_EDITIMG","Mediet egenskaber er ændret.");
define("_ZOOM_ALERT_DELPIC","Mediet er slettet.");
define("_ZOOM_ALERT_NODELPIC","Medium could not be deleted!");
define("_ZOOM_ALERT_NODELPIC","Mediet kunne ikke slettes!");
define("_ZOOM_ALERT_NOPICSELECTED","Intet medie valgt.");
define("_ZOOM_ALERT_NOPICSELECTED_MULT","Ingen medier er valgt.");
define("_ZOOM_ALERT_UPLOADOK","Mediet er uploaded!");
define("_ZOOM_ALERT_UPLOADSOK","Medier er uploaded!");
define("_ZOOM_ALERT_WRONGFORMAT","Forkert format.");
define("_ZOOM_ALERT_WRONGFORMAT_MULT","Forkert format.");
define("_ZOOM_ALERT_IMGERROR","Fejl ved redigering af billede/ oprette thumbnail.");
define("_ZOOM_ALERT_PCLZIPERROR","Der opstod en fejl ved udpakning af arkivet.");
define("_ZOOM_ALERT_INDEXERROR","Der opstod en fejl ved indeksering af dokumenter.");
define("_ZOOM_ALERT_WATERMARKERROR","Der opstod en fejl ved anvendelse af vandmærket.");
define("_ZOOM_ALERT_IMGFOUND","billede(-r) fundet.");
define("_ZOOM_INFO_CHECKCAT","Vælg et galleri før du klikker på 'Upload' knappen!");
define("_ZOOM_BUTTON_ADDIMAGES","Tilføj medier");
define("_ZOOM_BUTTON_REMIMAGES","Fjern medier");
// define("_ZOOM_INFO_PROCESSING","Processing file:");
define("_ZOOM_INFO_PROCESSING","Bearbejder filer:");
define("_ZOOM_ITEMEDIT_TAB1","Egenskaber");
define("_ZOOM_ITEMEDIT_TAB2","Medlemmer");
// define("_ZOOM_ITEMEDIT_TAB3","Actions");
define("_ZOOM_ITEMEDIT_TAB3","Funktioner");
define("_ZOOM_USERSLIST_LINE1",">>Vælg medlemmer for dette album<<");
define("_ZOOM_USERSLIST_ALLOWALL",">>Offentlig adgang<<");
define("_ZOOM_USERSLIST_MEMBERSONLY",">>Kun for medlemmer<<");
define("_ZOOM_PUBLISHED","Publiceret");
define("_ZOOM_SHARED","Del dette album");
define("_ZOOM_ROTATE","Rotér billede 90 grader");
define("_ZOOM_CLOCKWISE","Med uret");
define("_ZOOM_CCLOCKWISE","Mod uret");
define("_ZOOM_FLIP_HORIZ","Vend billede horisontalt");
define("_ZOOM_FLIP_VERT","Vend billede vertikalt");
//define("_ZOOM_PROGRESS_DESCR","Dit forespørgsel bliver behandling....Et øjeblik.");
define("_ZOOM_PROGRESS_DESCR","Din forespørgsel er ved at blive udført ... Et øjeblik.");

//Navigation (including Slideshow buttons)
define("_ZOOM_SLIDESHOW","lysbillede-show:");
define("_ZOOM_PREV_IMG","forrige billede");
define("_ZOOM_NEXT_IMG","næste billede");
define("_ZOOM_FIRST_IMG","første billede");
define("_ZOOM_LAST_IMG","sidste billede");
define("_ZOOM_PLAY","afspil");
define("_ZOOM_STOP","stop");
define("_ZOOM_RESET","nulstil");
define("_ZOOM_FIRST","Første");
define("_ZOOM_LAST","Sidste");
define("_ZOOM_PREVIOUS","Forrige");
define("_ZOOM_NEXT","Næste");
define("_ZOOM_IN_DESC", "hold musen over billede og klik pil ned el. pil op tasten.");

//Gallery actions
//define("_ZOOM_SEARCH_BOX","Quicksearch...");
define("_ZOOM_SEARCH_BOX","Søg...");
define("_ZOOM_ADVANCED_SEARCH","Advanceret søgning");
define("_ZOOM_SEARCH_KEYWORD","Søg pr. nøgleord");
define("_ZOOM_IMAGES","billede(er)");
define("_ZOOM_IMGFOUND","%s fundet - du er på side %s af %s");
define("_ZOOM_SUBGALLERIES","under-gallerier");
define("_ZOOM_ALERT_COMMENTOK","Din kommentar er blevet tilføjet!");
define("_ZOOM_ALERT_COMMENTERROR","Du har allerede kommenteret dette billede!");
define("_ZOOM_ALERT_VOTE_OK","Vi har modtaget dit valg, Tak for din deltagelse.");
define("_ZOOM_ALERT_VOTE_ERROR","Du har allerede stemt på dette billede!");
define("_ZOOM_WINDOW_CLOSE","Luk");
define("_ZOOM_NOPICS","Ingen billeder i dette galleri");
define("_ZOOM_PROPERTIES","Egenskaber");
define("_ZOOM_COMMENTS","Kommentar");
define("_ZOOM_NO_COMMENTS","Ingen kommentar er tilføjet endnu.");
define("_ZOOM_YOUR_NAME","Navn");
define("_ZOOM_ADD","Tilføj");
define("_ZOOM_NAME","Navn");
define("_ZOOM_DATE","Tilføjet den");
define("_ZOOM_UNAME","Tilføjet af");
define("_ZOOM_DESCRIPTION","Beskrivelse");
define("_ZOOM_IMGNAME","Navn");
define("_ZOOM_FILENAME","Filnavn");
define("_ZOOM_CLICKDOCUMENT","(klik på filnavn for at åbne dokument)");
define("_ZOOM_KEYWORDS","Nøgleord");
define("_ZOOM_HITS","hits");
define("_ZOOM_CLOSE","Luk");
define("_ZOOM_NOIMG", "Ingen billeder fundet!");
define("_ZOOM_NONAME", "Du skal angive et navn!");
define("_ZOOM_NOCAT", "Inget galleri valgt!");
define("_ZOOM_EDITPIC", "Rediger medie");
define("_ZOOM_SETCATIMG","Vælg til galleri billede");
define("_ZOOM_SETPARENTIMG","Vælg som galleri billede til det forrige galleri");
define("_ZOOM_PASS","Kodeord");
define("_ZOOM_PASS_REQUIRED","Dette galleri kræver at du er oprettet som bruger på siden.<br />Udfyld venligst kodeords feltet<br />og klik på godkend. TAK.");
define("_ZOOM_PASS_BUTTON","Godkend");
define("_ZOOM_PASS_GALLERY","Kodeord");
define("_ZOOM_PASS_INNCORRECT","Forkert kodeord");

//Lightbox = Kurv,  Playlist = Playlist
define("_ZOOM_LIGHTBOX","Kurv");
define("_ZOOM_LIGHTBOX_GALLERY","Overfør dette galleri til din kurv!");
define("_ZOOM_LIGHTBOX_ITEM","Overfør dette medie til din kurv!");
define("_ZOOM_LIGHTBOX_VIEW","Åben din kurv");
define("_ZOOM_YOUR_LIGHTBOX","Indholdet af din kurv:");
define("_ZOOM_LIGHTBOX_EMPTY","Din kurv er i øjeblikket tom.");
define("_ZOOM_LIGHTBOX_ZIPBTN","Opret ZIP-fil");
//define("_ZOOM_LIGHTBOX_PLAYLISTBTN","Create Playlist & Play");
define("_ZOOM_LIGHTBOX_PLAYLISTBTN","Opret Playliste og afspil");
define("_ZOOM_LIGHTBOX_CATS","Gallerier");
define("_ZOOM_LIGHTBOX_TITLEDESCR","Titel & Beskrivelse");
define("_ZOOM_ACTION","Funktioner");
define("_ZOOM_LIGHTBOX_ADDED","Mediet er blevet overført til din kurv!");
define("_ZOOM_LIGHTBOX_NOTADDED","Dette medie er allerede i din kurv - kan ikke overføres!");
define("_ZOOM_LIGHTBOX_EDITED","Mediet blev redigeret!");
define("_ZOOM_LIGHTBOX_NOTEDITED","Mediet blev ikke redigeret!");
define("_ZOOM_LIGHTBOX_DEL","Mediet er nu fjernet fra din kurv!");
//define("_ZOOM_LIGHTBOX_NOTDEL","Error removing item from your lightbox!");
define("_ZOOM_LIGHTBOX_NOTDEL","Der opstod en fejl. Mediet blev ikke fjernet fra din kurv!");
define("_ZOOM_LIGHTBOX_NOZIP","Du har allerede oprettet en ZIP-fil af din kurv, eller din kurv er tom!");
define("_ZOOM_LIGHTBOX_PARSEZIP","  medier fra galleriet...");
define("_ZOOM_LIGHTBOX_DOZIP","opretter ZIP-fil...");
define("_ZOOM_LIGHTBOX_DLHERE","Du kan nu downloade filen");
// define("_ZOOM_LIGHTBOX_PLSUCCESS","Playlist created successfully! You need to refresh the Player-window.");
define("_ZOOM_LIGHTBOX_PLSUCCESS","Din Playlist er oprettet! Du skal nu opdatere Playlist vinduet.");
define("_ZOOM_LIGHTBOX_PLERROR","Der opstod en fejl. Din Playliste er ikke oprettet.");
define("_ZOOM_LIGHTBOX_NOAUDIO","Du skal tilføje audio medier til din kurv først!");
define("_ZOOM_LIGHTBOX_NOITEMS","Din kurv er tom.");

//EXIF information
define("_ZOOM_EXIF","EXIF");
define("_ZOOM_EXIF_SHOWHIDE","Vis/ skjul EXIF-info");

//MP3 id3 v1.1 or later information
define("_ZOOM_AUDIO_PLAYING","afspiller:");
define("_ZOOM_AUDIO_CLICKTOPLAY","Klik her for at afspille denne fil.");
define("_ZOOM_ID3","ID3");
define("_ZOOM_ID3_SHOWHIDE","Vis/ skjul ID3-info");
define("_ZOOM_ID3_LENGTH","Længde");
define("_ZOOM_ID3_QUALITY","Kvalitet");
define("_ZOOM_ID3_TITLE","Titel");
define("_ZOOM_ID3_ARTIST","Kunstner");
define("_ZOOM_ID3_ALBUM","Album");
define("_ZOOM_ID3_YEAR","År");
define("_ZOOM_ID3_COMMENT","Kommentar");
define("_ZOOM_ID3_GENRE","Genre");

//Video metadata information
define("_ZOOM_VIDEO_SHOWHIDE","Vis/ skjul video data");
define("_ZOOM_VIDEO_PIXELRATIO","Pixel ratio");
define("_ZOOM_VIDEO_QUALITY","Video kvalitet");
define("_ZOOM_VIDEO_AUDIOQUALITY","Audio kvalitet");
define("_ZOOM_VIDEO_CODEC","Codec");
define("_ZOOM_VIDEO_RESOLUTION","Opløsning");

//rating
define("_ZOOM_RATING","Vurdering");
define("_ZOOM_NOTRATED","Ikke vurderet endnu!");
define("_ZOOM_VOTE","stem");
define("_ZOOM_VOTES","stemmer");
define("_ZOOM_RATE0","sludder");
define("_ZOOM_RATE1","dårlig");
define("_ZOOM_RATE2","gennemsnitlig");
define("_ZOOM_RATE3","god");
define("_ZOOM_RATE4","rigtig god");
define("_ZOOM_RATE5","perfekt!");

//special
define("_ZOOM_TOPTEN","Top 10");
define("_ZOOM_LASTSUBM","Sidst offenliggjort");
define("_ZOOM_LASTCOMM","Sidst kommenteret");
define("_ZOOM_SEARCHRESULTS","Søgeresultat");
define("_ZOOM_TOPRATED","De bedst vurderede");

//ecard
define("_ZOOM_ECARD_SENDAS","Send dette billede som et E-card til en ven!");
define("_ZOOM_ECARD_YOURNAME","Dit navn");
define("_ZOOM_ECARD_YOUREMAIL","Din epost adresse");
define("_ZOOM_ECARD_FRIENDSNAME","Din vens navn");
define("_ZOOM_ECARD_FRIENDSEMAIL","Din vens epost adresse");
define("_ZOOM_ECARD_MESSAGE","Besked");
define("_ZOOM_ECARD_SENDCARD","Send eCard");
define("_ZOOM_ECARD_SUCCESS","Dit kort er blevet afsendt.");
define("_ZOOM_ECARD_CLICKHERE","Klik her for at se det!");
define("_ZOOM_ECARD_ERROR","Der er opstået en fejl da du sendte et  E-card til");
define("_ZOOM_ECARD_TURN","Se på bagsiden af dette kort!");
define("_ZOOM_ECARD_TURN2","Se på forsiden af dette kort!");
define("_ZOOM_ECARD_SENDER","Er sent til dig fra:");
define("_ZOOM_ECARD_SUBJ","Du har modtaget et eCard fra:");
define("_ZOOM_ECARD_MSG1","sent et eCard til dig fra");
define("_ZOOM_ECARD_MSG2","klik på linket nedenfor for at se dit personlige eCard!");
define("_ZOOM_ECARD_MSG3","Besvar ikke denne email, da den er dannet automatisk.");
define("_ZOOM_ECARD_ECARDEXPIRED","Beklager, dette eCard er ikke længere tilgængeligt eller er udløbet.");

//installation-screen
define ('_ZOOM_INSTALL_CREATE_DIR','zOOm vil forsøge at oprette medie biblioteket "images/zoom" ...');
define ('_ZOOM_INSTALL_CREATE_DIR_WM','zOOm vil forsøge at oprette medie biblioteket "images/zoom/watermarks" ...');
define ('_ZOOM_INSTALL_CREATE_DIR_SUCC','færdig!');
define ('_ZOOM_INSTALL_CREATE_DIR_FAIL','kunne ikke oprettes!');
define ('_ZOOM_INSTALL_CREATE_DBASE_SUCC','zOOm har oprettet tabeller i databasen!');
define ('_ZOOM_INSTALL_UPGRADED_DBASE_SUCC','Databaseb blev opdateret!');
define ('_ZOOM_INSTALL_MESS1','zOOm Medie Galleri blev installeret.<br>Du kan nu uploade medier til galleriet!');
define ('_ZOOM_INSTALL_MESS2','NOTE: du skal nu gå til menuen med componenter ovenfor,<br>se efter "zOOm Media Gallery Admin", klik på linket<br>og check indstillingerne i Admin-system.');
define ('_ZOOM_INSTALL_MESS3','Her kan du ændre konfigurationen af zOOM gallery.');
define ('_ZOOM_INSTALL_MESS4','Glem ikke at oprette et billedgalleri, så er du igang!');
define ('_ZOOM_INSTALL_MESS_FAIL1','zOOM Galleriet blev ikke installeret korrekt!');
define ('_ZOOM_INSTALL_MESS_FAIL2','Følgende filmapper skal oprettes og rettigheder på filmapperne skal efterfølgende ændres til "0777":<br />'
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
define ('_ZOOM_INSTALL_MESS_FAIL3','Når du først har oprettet disse filmapper og ændret rettighederne på mapperne, så gå til<br /> "Components -> zOOm Media Gallery" og tilpas indstillingerne så de passer til dit behov.');
?>