<?php
//zOOm Media Gallery//
/** 
-----------------------------------------------------------------------
|  zOOm Media Gallery! by Mike de Boer - a multi-gallery component    |
-----------------------------------------------------------------------

-----------------------------------------------------------------------
|                                                                     |
| Author: Mike de Boer, <http://www.mikedeboer.nl>         		      |
|											                          |
| Translated by: Daniel Røyert, <http://www.royert.com>		          |
|          									                          |
| Copyright: copyright (C) 2007 by Mike de Boer                       |
| Description: zOOm Media Gallery, a multi-gallery component for      |
|              Joomla!. It's the most feature-rich gallery component  |
|              for Joomla!! For documentation and a detailed list     |
|              of features, check the zOOm homepage:                  |
|              http://www.zoomfactory.org                             |
| License: GPL 									                      |
| Filename: norwegian.php                                             |
|                                                                     |
-----------------------------------------------------------------------
* @package zOOm Media Gallery
* @author Daniel Røyert <daniel_royert@hotmail.com>
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
//Language translation
define("_ZOOM_DATEFORMAT","%d.%m.%Y %H:%M"); // use the PHP strftime Format, more info at http://www.php.net
define("_ZOOM_PICK","Velg et galleri");
define("_ZOOM_DELETE","Slett");
define("_ZOOM_BACK","Gå Tilbake");
define("_ZOOM_MAINSCREEN","Hovedside");
define("_ZOOM_BACKTOGALLERY","Tilbake til galleriet");
define("_ZOOM_INFO_DONE","Ferdig!");
define("_ZOOM_TOOLTIP", "zOOm Verktøytips");
define("_ZOOM_WARNING", "zOOm Advarsel!");

//Gallery admin page
define("_ZOOM_ADMINSYSTEM","Administrator system");
define("_ZOOM_USERSYSTEM","Bruker system");
define("_ZOOM_ADMIN_TITLE","Media galleri administrator system");
define("_ZOOM_USER_TITLE","Media galleri bruker system");
define("_ZOOM_CATSMGR","Galleriverktøy");
define("_ZOOM_CATSMGR_DESCR","Opprett nye gallerier til nye medier; opprett, rediger og slett dem her i galleriverktøyet.");
define("_ZOOM_SETTINGS_DDONOF","Tillat Dra og Slipp");
define("_ZOOM_NEW","Nytt galleri");
define("_ZOOM_DEL","Slett galleri");
define("_ZOOM_MEDIAMGR","Mediaverktøy");
define("_ZOOM_MEDIAMGR_DESCR","Flytt, rediger, slett, scan etter medier automatisk eller last opp (flere) nye medier manuelt.");
define("_ZOOM_THUMB", "Zoom Miniatyrbilde kode-generator");
define("_ZOOM_THUMB_DESCR", "Opprett dine Zoom miniatyrbilde-koder lett");
define("_ZOOM_UPLOAD","Last opp fil(er)");
define("_ZOOM_EDIT","Rediger galleri");
define("_ZOOM_ADMIN_CREATE","Opprett database");
define("_ZOOM_ADMIN_CREATE_DESCR","Opprett de nødvendige databasetabeller slik at du kan benytte Media galleriet");
define("_ZOOM_HD_PREVIEW","Forhåndsvisning");
define("_ZOOM_HD_CHECKALL","Marker/Fjern alle");
define("_ZOOM_HD_CREATEDBY","Opprettet av:");
define("_ZOOM_HD_AFTER","Hovedkategori");
define("_ZOOM_HD_HIDEMSG","Skjul 'ingen midia' tekst");
define("_ZOOM_HD_NAME","Tittel");
define("_ZOOM_HD_DIR","Mappe");
define("_ZOOM_HD_NEW","Nytt galleri");
define("_ZOOM_HD_SHARE","Del dette galleri");
define("_ZOOM_SHARE","Del");
define("_ZOOM_UNSHARE","Fjern deling");
define("_ZOOM_PUBLISH","Publiser");
define("_ZOOM_UNPUBLISH","Ikke publisering");
define("_ZOOM_TOPLEVEL","Toppnivå");
define("_ZOOM_HD_UPLOAD","Last opp media");
define("_ZOOM_A_ERROR_ERRORTYPE","Feil type");
define("_ZOOM_A_ERROR_IMAGENAME","Navn på bildet");
define("_ZOOM_A_ERROR_NOFFMPEG","<u>FFmpeg</u> ikke funnet");
define("_ZOOM_A_ERROR_NOPDFTOTEXT","<u>PDFtoText</u> ikke funnet");
define("_ZOOM_A_ERROR_NOTINSTALLED","Ikke installert");
define("_ZOOM_A_ERROR_CONFTODB","Feil da konfigurationen skulle lagres i databasen!");
define("_ZOOM_A_MESS_NOT_SHURE","* Er du ikke sikker, benytt standardoppsett \"auto\" ");
define("_ZOOM_A_MESS_SAFEMODE1","Merk: \"Sikker modus\" er aktivert, det er derfor mulig at opplasting av større filer ikke fungerer!<br />Gå til administrationsverktøyet og skift til FTP-modus.");
define("_ZOOM_A_MESS_SAFEMODE2","Merk: \"Sikker modus\" er aktivert, det er derfor mulig at opplasting av større filer ikke vil fungere!<br />zOOm anbefaler å aktivere FTP-modus i administrationsverktøyet.");
define("_ZOOM_A_MESS_PROCESSING_FILE","Bearbeider fil...");
define("_ZOOM_A_MESS_NOTOPEN_URL","Kunne ikke åpne url:");
define("_ZOOM_A_MESS_PARSE_URL","Ser etter \"%s\" etter bilder... "); // %s = $url
define("_ZOOM_A_MESS_NOJAVA","Hvis du kun ser en grå boks ovenfor eller hvis du har problemer med å laste opp, så kan det skyldes at<br />du ikke har seneste java run-time installert. Gå til <a href=\"http://www.java.com\" target=\"_blank\">Java.com</a> <br /> og last ned den seneste versjonen.");
define("_ZOOM_SETTINGS","Innstillinger");
define("_ZOOM_SETTINGS_DESCR","Skift og se alle de mulige konfigurationsinnstillinger her.");
define("_ZOOM_SETTINGS_TAB1","System");
define("_ZOOM_SETTINGS_TAB2","Media");
define("_ZOOM_SETTINGS_TAB3","Egenskaper");
define("_ZOOM_SETTINGS_TAB4","Utseende");
define("_ZOOM_SETTINGS_TAB5","Vannmerker");
define("_ZOOM_SETTINGS_TAB6","Sikker modus");
define("_ZOOM_SETTINGS_TAB7","Tilgjengelighet");
define("_ZOOM_SETTINGS_TAB8","Tilbakestill");
define("_ZOOM_SETTINGS_ERASE","Klikk for å slette all zOOm Media Gallery data og starte på nytt. Dette tilbakestiller alle innstillinger og fjerner alle bilder.");
define("_ZOOM_SETTINGS_CONVTYPE","Type bildekonvertering:");
define("_ZOOM_SETTINGS_GALLERY_FEATURES","Egenskaper for galleri visning:");
define("_ZOOM_SETTINGS_VIEW_FEATURES","Egenskaper for media visning:");
define("_ZOOM_SETTINGS_GALLERY","Galleri visning:");
define("_ZOOM_SETTINGS_VIEW","Media visning:");
define("_ZOOM_SETTINGS_GENERAL_FEATURES","Generelle egenskaper:");
define("_ZOOM_SETTINGS_AUTODET","funnet automatisk: ");
define("_ZOOM_SETTINGS_IMGPATH","Sti til mediafiler:");
define("_ZOOM_SETTINGS_TTIMGPATH","Nåværende sti til medier er ");
define("_ZOOM_SETTINGS_CONVSETTINGS","Innstillinger til bildekonvertering:");
define("_ZOOM_SETTINGS_IMPATH","Sti til ImageMagick: ");
define("_ZOOM_SETTINGS_NETPBMPATH"," eller NetPBM: ");
define("_ZOOM_SETTINGS_FFMPEGPATH","Sti til FFmpeg");
define("_ZOOM_SETTINGS_FFMPEGTOOLTIP","FFmpeg er nødvendig for å opprette miniatyrbilder av dine videofiler.<br />Følgende filtyper støttes: ");
define("_ZOOM_SETTINGS_OVERRIDE_FFMPEG","Bruk FFmpeg, selv om zOOm ikke er i stand til å finne programmet på dette system.");
define("_ZOOM_SETTINGS_PDFTOTEXTPATH","Sti til PDFtoText");
define("_ZOOM_SETTINGS_XPDFTOOLTIP","pdf2text, som er en del af Xpdf pakken, er nødvendig for å kunne indeksere PDF filer.");
define("_ZOOM_SETTINGS_OVERRIDE_PDF","Bruk PDFtoText, selv om zOOm ikke kan bekrefte om programmet er tilgengeligt på systemet.");
define("_ZOOM_SETTINGS_MAXSIZE","Maksimal bildestørrelse: ");
define("_ZOOM_SETTINGS_MAXSIZEKB","Media - inkludert bilder - maks størrelse (i kB): "); //added: 08-08-2006
define("_ZOOM_SETTINGS_MAXSIZEKB_WARNING","Opplastningsgrensen på denne serveren, konfigurert av din ISP som en del av PHP konfigurasjonen, er %s.<br />Derfor vil IKKE en høyere grense enn denne verdien ha noen effekt!"); //added: 09-01-2006
define("_ZOOM_SETTINGS_THUMBSETTINGS","Miniatyrbilde innstillinger:");
define("_ZOOM_SETTINGS_QUALITY","NetPBM og GD2 JPEG kvalitet: ");
define("_ZOOM_SETTINGS_SIZE","Maksimal miniatyrbilde størrelse: ");
define("_ZOOM_SETTINGS_TEMPNAME","Midlertidig navn: ");
define("_ZOOM_SETTINGS_AUTONUMBER","autonummerering av bildenavn (eg. 1,2,3)");
define("_ZOOM_SETTINGS_TEMPDESCR","Midlertidig beskrivelse: ");
define("_ZOOM_SETTINGS_TITLE","Mediagalleri tittel:");
define("_ZOOM_SETTINGS_SUBCATSPG","Antall kolonner i undergallerier");
define("_ZOOM_SETTINGS_COLUMNS","Antall miniatyrbildekolonner");
define("_ZOOM_SETTINGS_THUMBSPG","Miniatyrbilder pr. side");
define("_ZOOM_SETTINGS_CMTLENGTH","Maksimal lengde på kommentar");
define("_ZOOM_SETTINGS_CHARS","karakterer");
define("_ZOOM_SETTINGS_GALLERYPREFIX","Galleri-tittel prefix");
define("_ZOOM_SETTINGS_SHOWOCCSPACE","Vis brukt plass i mediaverktøy");
define("_ZOOM_SETTINGS_TEMPLATE_TITLE","Mal:");
define("_ZOOM_SETTINGS_FEATURES_TITLE","Innstillinger ON/ OFF");
define("_ZOOM_SETTINGS_CSS_TITLE","Rediger stylesheets:");
define("_ZOOM_SETTINGS_DISPLAY_TITLE","Vis data ON/ OFF");
define("_ZOOM_SETTINGS_TEMPLATE_CHOOSE","Velg en mal for å forander utseende på galleriet ditt");
define("_ZOOM_SETTINGS_TEMPLATE_TABLES","Klassisk (med tabeller)");
define("_ZOOM_SETTINGS_TEMPLATE_TABLELESS","Moderne (uten tabeller)");
define("_ZOOM_SETTINGS_COMMENTS","Kommentarer");
define("_ZOOM_SETTINGS_POPUP","PopUp media");
define("_ZOOM_SETTINGS_CATIMG","Vis galleribilde");
define("_ZOOM_SETTINGS_SLIDESHOW","Lysbildeframvisning");
define("_ZOOM_SETTINGS_ZOOMLOGO","Vis zOOm-logo");
define("_ZOOM_SETTINGS_SHOWHITS","Vis antall treff");
define("_ZOOM_SETTINGS_READEXIF","Les EXIF-data");
define("_ZOOM_SETTINGS_EXIFTOOLTIP","Denne funksjonen vil vise ytterligere EXIF og andre IPTC data, uten at EXIF modulet til PHP skal være installert på ditt system.");
define("_ZOOM_SETTINGS_READID3","Les mp3 ID3-data");
define("_ZOOM_SETTINGS_ID3TOOLTIP","Denne funksjonen vil vise ytterligere ID3 v1.1 and v2.0 data når du ser detaljene på en mp3 fil.");
define("_ZOOM_SETTINGS_RATING","Vurdering");
define("_ZOOM_SETTINGS_CSS","Popupvinduet");
define("_ZOOM_SETTINGS_CSSZOOM","zOOm galleri &amp; bildevisning");
define("_ZOOM_SETTINGS_SUCCESS","Konfigurationen er oppdatert!");
define("_ZOOM_SETTINGS_ZOOMING","Bildezoom");
define("_ZOOM_SETTINGS_ORDERBY","Sortering av miniatyrbilder, sorter etter");
define("_ZOOM_SETTINGS_CATORDERBY","Metode til sortering av undergallerier");
define("_ZOOM_SETTINGS_DATE_ASC","DATO, stigene");
define("_ZOOM_SETTINGS_DATE_DESC","DATO, synkene");
define("_ZOOM_SETTINGS_FLNM_ASC","FILNAVN, stigene");
define("_ZOOM_SETTINGS_FLNM_DESC","FILNAVN, synkene");
define("_ZOOM_SETTINGS_NAME_ASC","NAVN, stigene");
define("_ZOOM_SETTINGS_NAME_DESC","NAVN, synkene");
define("_ZOOM_SETTINGS_LBTOOLTIP","En lysboks er som en handlekurv fylt med de mediene du velger. Innholdet på listen kan deretter lastes ned som en zip-fil.");
define("_ZOOM_SETTINGS_SHOWNAME","Vis navn");
define("_ZOOM_SETTINGS_SHOWDESCR","Vis beskrivelse");
define("_ZOOM_SETTINGS_SHOWKEYWORDS","Vis nøkkelord");
define("_ZOOM_SETTINGS_SHOWDATE","Vis dato");
define("_ZOOM_SETTINGS_SHOWUNAME","Vis brukernavn");
define("_ZOOM_SETTINGS_SHOWFILENAME","Vis filnavn");
define("_ZOOM_SETTINGS_METABOX","Vis en sprettopp-boks med informasjon i galleriene");
define("_ZOOM_SETTINGS_METABOXTOOLTIP","Velg bort denne funktionen for å øke hastigheten på ditt galleri. Effektivt ved større databaser.");
define("_ZOOM_SETTINGS_ECARDS","E-cards");
define("_ZOOM_SETTINGS_ECARDS_LIFETIME","E-cards levetid");
define("_ZOOM_SETTINGS_ECARDS_ONEWEEK","en uke");
define("_ZOOM_SETTINGS_ECARDS_TWOWEEKS","to uker");
define("_ZOOM_SETTINGS_ECARDS_ONEMONTH","en måned");
define("_ZOOM_SETTINGS_ECARDS_THREEMONTHS","tre måneder");
define("_ZOOM_SETTINGS_SHOWSEARCH","Søkefelt på alle sider");
define("_ZOOM_SETTINGS_BOX_ANIMATE","Animert boks");
define("_ZOOM_SETTINGS_BOX_PROPERTIES","Egenskaper for boks");
define("_ZOOM_SETTINGS_BOX_META","Egenskaper for metadata boks");
define("_ZOOM_SETTINGS_BOX_COMMENTS","Egenskaper for kommentarboks");
define("_ZOOM_SETTINGS_BOX_RATING","Egenskaper for vurderingsboks");
define("_ZOOM_SETTINGS_TOPTEN","Vis \"Top 10\" link på hovedsiden");
define("_ZOOM_SETTINGS_LASTSUBM","Vis \"Siste publiserte bilder\" link på hovedsiden");
define("_ZOOM_SETTINGS_SETMENUOPTION","Vis \"Last opp bilder\" link i brukermenyen");
define("_ZOOM_SETTINGS_USEFTP","Bruk FTP modus?");
define("_ZOOM_SETTINGS_FTPHOST","FTP Servernavn");
define("_ZOOM_SETTINGS_FTPUNAME","Brukernavn");
define("_ZOOM_SETTINGS_FTPPASS","Passord");
define("_ZOOM_SETTINGS_FTPWARNING","Advarsel: Passord blir ikke lagret sikkert!");
define("_ZOOM_SETTINGS_FTPHOSTDIR","Mappestruktur på server");
define("_ZOOM_SETTINGS_MESS_FTPHOSTDIR","Oppgi ftp-rot stien til Joomla! her. VIKTIG: Avslutt <b>uten</b> en skråstrek eller en backslash!");
define("_ZOOM_SETTINGS_GROUP","Gruppe");
define("_ZOOM_SETTINGS_PRIV_DESCR","Du har mulighet til å endre rettigheter for hver Joomla brukergruppe og endre rettigheter for hver enkelt bruker som er medlem av den gruppen!<br/>
En bruker kan, teoretisk sett, gjøre det følgende: Laste opp bilde(-r), rette el. slette medier, opprette/ endre/ slette (delte) gallerier. <br />
Hva de kan og ikke kan er helt opp til deg.");
define("_ZOOM_SETTINGS_CLOSE","Vis \"Lukk\" link i popup vinduet");
define("_ZOOM_SETTINGS_MAINSCREEN","Vis link til hovedsiden i navigasjonsstien");
define("_ZOOM_SETTINGS_NAVBUTTONS","Vis navigerings-knapper i sprettopp-vindu");
define("_ZOOM_SETTINGS_PROPERTIES","Vis egenskaper under media");
define("_ZOOM_SETTINGS_MEDIAFOUND","Vis \"Media funnet\" tekst i galleriet");
define("_ZOOM_SETTINGS_ANONYMOUS_COMMENTS","Tillat anonyme kommentarer");
define("_ZOOM_SETTINGS_WM_ENABLE_TITLE","Bruk egenskaper:");
define("_ZOOM_SETTINGS_WM_TITLE", "Ditt vannmerke");
define("_ZOOM_SETTINGS_WM_DESCR", "Ditt vannmerke vil være synlig på dine bilder i ditt galleri."
 . "Bildet vil fortsatt være synlig, men besøkende vil være mindre fristet til å kopiere el. printe det."
 . "<br /><br />Forslag: Du kan bruke ditt firmas logo som vannmerke."
 . "Vær sikker på at ditt vannmerke har en gjennomsiktig bakgrunn!");
define("_ZOOM_SETTINGS_WM_IMG", "Bildet");
define("_ZOOM_SETTINGS_WM_NOWATERMARKS", "Ingen vannmerke funnet. Du kan laste opp et nytt nedenfor.");
define("_ZOOM_SETTINGS_WM_PLACEMENT_TITLE", "Plassering:");
define("_ZOOM_SETTINGS_WM_PLACEMENT_DESCR", "Du kan bestemme plasseringen av ditt vannmerke, ved å velge én "
 . "av posisjonene i den grå boksen nedenfor.");
define("_ZOOM_SETTINGS_WM_FILE","Last opp vannmerke");
define("_ZOOM_SETTINGS_WM_UPLOAD_SUCCESS","Vannmerket er opplastet!");
define("_ZOOM_SETTINGS_WM_UPLOAD_FAIL","Vannmerket ble ikke opplastet.");
define("_ZOOM_SETTINGS_WM_DEL_SUCCESS","Vannmerket er slettet!");
define("_ZOOM_SETTINGS_WM_DEL_FAIL","Vannmerket kunne ikke slettes.");
define("_ZOOM_SYSTEM_TITLE","System konfiguration:");
define("_ZOOM_YES","ja");
define("_ZOOM_NO","nei");
define("_ZOOM_VISIBLE","synlig");
define("_ZOOM_HIDDEN","skjult");
define("_ZOOM_SAVE","Lagre");
define("_ZOOM_MOVEFILES","Flytt media");
define("_ZOOM_BUTTON_MOVE","Flytt");
define("_ZOOM_MOVEFILES_STEP1","Velg hvilket galleri mediene skal flyttes til &amp; flytt medier");
define("_ZOOM_ALERT_MOVE","%s medier er flyttet, %s medier kunne ikke flyttes.");
define("_ZOOM_OPTIMIZE","Optimaliser tabeller");
define("_ZOOM_OPTIMIZE_DESCR","zOOm Media Galleri bruker databasen mye og overflødige data forekommer. Klikk her for å fjerne unødvendige og overflødige data.");
define("_ZOOM_OPTIMIZE_SUCCESS","zOOm Media Galleriets tabeller har blitt optimalisert!");
define("_ZOOM_UPDATE","Oppdater zOOm Media Galleri");
define("_ZOOM_UPDATE_DESCR","legg til ny funktionalitet, løs problemer og fjern feil! Sjekk <a href=\"http://www.zoomfactory.org\" target=\"_blank\">www.zoomfactory.org</a> for de seneste oppdateringene!");
define("_ZOOM_UPDATE_XMLDATE","Dato for seneste oppdatering");
define("_ZOOM_UPDATE_NOUPDATES","ingen oppdateringer tilgjengelig!"); // added 11-08
define("_ZOOM_UPDATE_PACKAGE","Oppdater ZIP-filen: ");
define("_ZOOM_CREDITS","Om zOOm Media Galleri");

//Image actions
define("_ZOOM_DISKSPACEUSAGE","Plassforbruk: %s bruker i øyeblikket");
define("_ZOOM_UPLOAD_SINGLE","enkel (ZIP-)fil");
define("_ZOOM_UPLOAD_MULTIPLE","Flere filer");
define("_ZOOM_UPLOAD_DRAGNDROP","Dra og Slipp");
define("_ZOOM_UPLOAD_SCANDIR","Søk i mappe");
define("_ZOOM_UPLOAD_INTRO","Klikk <b>Søk</b> knappen for å finne media til opplasting.");
define("_ZOOM_UPLOAD_STEP1","1. Velg antall filer du ønsker å laste opp: ");
define("_ZOOM_UPLOAD_STEP2","2. Velg det galleriet du ønsker å laste opp medier til: ");
define("_ZOOM_UPLOAD_STEP3","3. Bruk 'Bla gjennom' knappen til å finne medier på din datamaskin");
define("_ZOOM_SCAN_STEP1","Step 1: angi en kilde for å søke etter medier...");
define("_ZOOM_SCAN_STEP2","Step 2: velg hvilke filer du ønsker å laste opp...");
define("_ZOOM_SCAN_STEP3","Step 3: vennligst vent...");
define("_ZOOM_SCAN_STEP1_DESCR","Kilden kan enten være en URL eller mappe på en server.<br />&nbsp;   Tips: Last opp media med et FTP-program til en mappe på serveren og oppgi stien her!");
define("_ZOOM_SCAN_STEP2_DESCR1","vennligst vent...");
define("_ZOOM_SCAN_STEP2_DESCR2","som en lokal mappe");
define("_ZOOM_FORMCREATE_NAME","Navn");
define("_ZOOM_FORM_IMAGEFILE","Medium");
define("_ZOOM_FORM_IMAGEFILTER","Kompatible media typer");
define("_ZOOM_FORM_INGALLERY","I galleriet");
define("_ZOOM_FORM_SETFILENAME","Bruk det originale filnavnet som navn på mediet.");
define("_ZOOM_FORM_IGNORESIZES","Ignorer forvalgt maksimal bildestørrelse"); //added: 12-08
define("_ZOOM_FORM_LOCATION","Plassering");
define("_ZOOM_BUTTON_SCAN","Angi URL eller lokal mappe");
define("_ZOOM_BUTTON_UPLOAD","Last opp");
define("_ZOOM_BUTTON_EDIT","Rediger");
define("_ZOOM_BUTTON_CREATE","Opprett");
define("_ZOOM_CONFIRM_WIPE","ADVARSEL!\\n Hvis du benytter denne funktionen, vil samtlige gallerier og medier bli slettet.\\n\\n Er du sikker på at du vil fortsette?");
define("_ZOOM_CONFIRM_DEL","Du er i ferd med å slette et gelleri, inklusivt medier!\\n Er du sikker på at du vil du fortsette?");
define("_ZOOM_CONFIRM_DELMEDIUM","Du er i ferd med å slette et medie!\\n Er du sikker på at du vil du fortsette?");
define("_ZOOM_ALERT_DEL","Galleriet er slettet!");
define("_ZOOM_ALERT_NOCAT","Intet galleri valgt!");
define("_ZOOM_ALERT_NOMEDIA","Intet media valgt!");
define("_ZOOM_ALERT_EDITOK","Galleri feltet er blitt redigert!");
define("_ZOOM_ALERT_NEWGALLERY","Nytt galleri opprettet.");
define("_ZOOM_ALERT_NONEWGALLERY","Galleriet ble ikke opprettet!");
define("_ZOOM_ALERT_EDITIMG","Media egenskaper er endret.");
define("_ZOOM_ALERT_DELPIC","Mediet er slettet.");
define("_ZOOM_ALERT_NODELPIC","Mediet kunne ikke slettes!");
define("_ZOOM_ALERT_NOPICSELECTED","Intet media valgt.");
define("_ZOOM_ALERT_NOPICSELECTED_MULT","Ingen medier er valgt.");
define("_ZOOM_ALERT_UPLOADOK","Mediet er opplastet!");
define("_ZOOM_ALERT_UPLOADSOK","Medier er opplastet!");
define("_ZOOM_ALERT_WRONGFORMAT","Formatet er ikke støttet.");
define("_ZOOM_ALERT_WRONGFORMAT_MULT","Formatet er ikke støttet.");
define("_ZOOM_ALERT_IMGERROR","Feil ved redigering av bildet/ opprette thumbnail.");
define("_ZOOM_ALERT_PCLZIPERROR","Det oppstod en feil ved utpakkingen av arkivet.");
define("_ZOOM_ALERT_INDEXERROR","Det oppstod en feil ved indeksering av dokumenter.");
define("_ZOOM_ALERT_WATERMARKERROR","Det oppstod en feil ved anvendelsen av vannmerket.");
define("_ZOOM_ALERT_IMGFOUND","bilde(-r) funnet.");
define("_ZOOM_INFO_CHECKCAT","Velg et galleri før du klikker på 'Opplast' knappen!");
define("_ZOOM_BUTTON_ADDIMAGES","Legg til medier");
define("_ZOOM_BUTTON_REMIMAGES","Fjern medier");
define("_ZOOM_INFO_PROCESSING","Bearbeider filer:");
define("_ZOOM_ITEMEDIT_TAB1","Egenskaper");
define("_ZOOM_ITEMEDIT_TAB2","Medlemmer");
define("_ZOOM_ITEMEDIT_TAB3","Funksjoner");
define("_ZOOM_USERSLIST_LINE1",">>Velg medlemmer for dette galleriet<<");
define("_ZOOM_USERSLIST_ALLOWALL",">>Offentlig tilgang<<");
define("_ZOOM_USERSLIST_MEMBERSONLY",">>Kun for medlemmer<<");
define("_ZOOM_PUBLISHED","Publisert");
define("_ZOOM_SHARED","Del dette galleriet");
define("_ZOOM_ROTATE","Roter bildet 90 grader");
define("_ZOOM_CLOCKWISE","Med klokken");
define("_ZOOM_CCLOCKWISE","Mot klokken");
define("_ZOOM_FLIP_HORIZ","Vend bildet horisontalt");
define("_ZOOM_FLIP_VERT","Vend bildet vertikalt");
define("_ZOOM_PROGRESS_DESCR","Din forespørsel blir behandlet....vennligst vent.");

//Navigation (including Slideshow buttons)
define("_ZOOM_SLIDESHOW","lysbildeframvisning:");
define("_ZOOM_PREV_IMG","forrige bilde");
define("_ZOOM_NEXT_IMG","neste bilde");
define("_ZOOM_FIRST_IMG","første bilde");
define("_ZOOM_LAST_IMG","siste bilde");
define("_ZOOM_PLAY","avspill");
define("_ZOOM_STOP","stopp");
define("_ZOOM_RESET","nullstill");
define("_ZOOM_FIRST","Første");
define("_ZOOM_LAST","Siste");
define("_ZOOM_PREVIOUS","Forrige");
define("_ZOOM_NEXT","Neste");
define("_ZOOM_IN_DESC", "hold musen over bildet og klikk pil ned eller pil opp tasten.");

//Gallery actions
define("_ZOOM_SEARCH_BOX","Hurtigsøk...");
define("_ZOOM_ADVANCED_SEARCH","Avansert søk");
define("_ZOOM_SEARCH_KEYWORD","Søk med nøkkelord");
define("_ZOOM_IMAGES","bilde(er)");
define("_ZOOM_IMGFOUND","%s funnet - du er på side %s av %s");
define("_ZOOM_SUBGALLERIES","under-gallerier");
define("_ZOOM_ALERT_COMMENTOK","Din kommentar har blitt lagret!");
define("_ZOOM_ALERT_COMMENTERROR","Du har allerede kommentert dette bildet!");
define("_ZOOM_ALERT_VOTE_OK","Vi har mottatt din stemme, Takk for din deltagelse.");
define("_ZOOM_ALERT_VOTE_ERROR","Du har allerede stemt på dette bildet!");
define("_ZOOM_WINDOW_CLOSE","Luk");
define("_ZOOM_NOPICS","Ingen billeder i dette galleri");
define("_ZOOM_PROPERTIES","Egenskaper");
define("_ZOOM_COMMENTS","Kommentar");
define("_ZOOM_NO_COMMENTS","Ingen kommentar er lagt til ennå.");
define("_ZOOM_YOUR_NAME","Navn");
define("_ZOOM_ADD","Legg til");
define("_ZOOM_NAME","Navn");
define("_ZOOM_DATE","Lagt til den");
define("_ZOOM_UNAME","Lagt til av");
define("_ZOOM_DESCRIPTION","Beskrivelse");
define("_ZOOM_IMGNAME","Navn");
define("_ZOOM_FILENAME","Filnavn");
define("_ZOOM_CLICKDOCUMENT","(klikk på filnavn for å åpne dokumentet)");
define("_ZOOM_KEYWORDS","Nøkkelord");
define("_ZOOM_HITS","treff");
define("_ZOOM_CLOSE","Lukk");
define("_ZOOM_NOIMG", "Ingen medier funnet!");
define("_ZOOM_NONAME", "Du må angi et navn!");
define("_ZOOM_NOCAT", "Intet galleri valgt!");
define("_ZOOM_EDITPIC", "Rediger media");
define("_ZOOM_SETCATIMG","Sett som galleri bilde");
define("_ZOOM_SETPARENTIMG","Sett som galleri bilde til det forrige galleri");
define("_ZOOM_PASS","Passord");
define("_ZOOM_PASS_REQUIRED","Dette galleri krever at du er lagt til som bruker på denne siden.<br />Vennligst fyll ut passord feltet<br />og klikk på Godkjenn.");
define("_ZOOM_PASS_BUTTON","Godkjenn");
define("_ZOOM_PASS_GALLERY","Passord");
define("_ZOOM_PASS_INNCORRECT","Feil passord!");

//Hotlinking
define("_ZOOM_SETTINGS_HOTLINK","Bruk media Hotlinking beskyttelse");
define("_ZOOM_SETTINGS_HPTOOLTIP","Når hotlinking beskyttelse er aktivert, blir media filnavn og stier skjult.I tillegg, hvis en bruker forsøket å bruke mediet på en annen side, vil det ikke synes.");

//Lightbox
define("_ZOOM_LIGHTBOX","Kurv");
define("_ZOOM_LIGHTBOX_GALLERY","Overfør dette galleri til din kurv!");
define("_ZOOM_LIGHTBOX_ITEM","Overfør dette mediet til din kurv!");
define("_ZOOM_LIGHTBOX_VIEW","Åpne din kurv");
define("_ZOOM_YOUR_LIGHTBOX","Innholdet av din kurv:");
define("_ZOOM_LIGHTBOX_EMPTY","Din kurv er i øyeblikket tom.");
define("_ZOOM_LIGHTBOX_ZIPBTN","Opprett ZIP-fil");
define("_ZOOM_LIGHTBOX_PLAYLISTBTN","Opprett Spilleliste og spill av");
define("_ZOOM_LIGHTBOX_CATS","Gallerier");
define("_ZOOM_LIGHTBOX_TITLEDESCR","Tittel & Beskrivelse");
define("_ZOOM_ACTION","Funksjoner");
define("_ZOOM_LIGHTBOX_ADDED","Mediet har blitt lagt til i din kurv!");
define("_ZOOM_LIGHTBOX_NOTADDED","Dette mediet er allerede i din kurv - kan ikke legges til!");
define("_ZOOM_LIGHTBOX_EDITED","Mediet ble redigert!");
define("_ZOOM_LIGHTBOX_NOTEDITED","Mediet ble ikke redigert!");
define("_ZOOM_LIGHTBOX_DEL","Mediet er nå fjernet fra din kurv!");
define("_ZOOM_LIGHTBOX_NOTDEL","Det oppstod en feil. Mediet ble ikke fjernet fra din kurv!");
define("_ZOOM_LIGHTBOX_NOZIP","Du har allerede opprettet en ZIP-fil av din kurv, eller din kurv er tom!");
define("_ZOOM_LIGHTBOX_PARSEZIP","  medier fra galleriet...");
define("_ZOOM_LIGHTBOX_DOZIP","oppretter ZIP-fil...");
define("_ZOOM_LIGHTBOX_DLHERE","Du kan nå laste ned filen");
define("_ZOOM_LIGHTBOX_PLSUCCESS","Din Spilleliste er opprettet! Du må nå oppdatere Spilleliste vinduet.");
define("_ZOOM_LIGHTBOX_PLERROR","Det oppstod en feil. Din Spilleliste er ikke opprettet.");
define("_ZOOM_LIGHTBOX_NOAUDIO","Du må legge til lyd medier til din kurv først!");
define("_ZOOM_LIGHTBOX_NOITEMS","Din kurv er tom.");

//EXIF information
define("_ZOOM_EXIF","EXIF");
define("_ZOOM_EXIF_SHOWHIDE","
/ skjul EXIF-info");

//MP3 id3 v1.1 or later information
define("_ZOOM_AUDIO_PLAYING","Spiller av:");
define("_ZOOM_AUDIO_CLICKTOPLAY","Klikk her for å spille av denne fil.");
define("_ZOOM_ID3","ID3");
define("_ZOOM_ID3_SHOWHIDE","Vis/ skjul ID3-info");
define("_ZOOM_ID3_LENGTH","Lengde");
define("_ZOOM_ID3_QUALITY","Kvalitet");
define("_ZOOM_ID3_TITLE","Tittel");
define("_ZOOM_ID3_ARTIST","Artist");
define("_ZOOM_ID3_ALBUM","Album");
define("_ZOOM_ID3_YEAR","År");
define("_ZOOM_ID3_COMMENT","Kommentar");
define("_ZOOM_ID3_GENRE","Genre");

//Video metadata information
define("_ZOOM_VIDEO_SHOWHIDE","Vis/ skjul video data");
define("_ZOOM_VIDEO_PIXELRATIO","Pixel forhold");
define("_ZOOM_VIDEO_QUALITY","Video kvalitet");
define("_ZOOM_VIDEO_AUDIOQUALITY","Lyd kvalitet");
define("_ZOOM_VIDEO_CODEC","Codec");
define("_ZOOM_VIDEO_RESOLUTION","Oppløsning");

//rating
define("_ZOOM_RATING","Vurdering");
define("_ZOOM_NOTRATED","Ikke vurdert ennå!");
define("_ZOOM_VOTE","stem");
define("_ZOOM_VOTES","stemmer");
define("_ZOOM_RATE0","elendig");
define("_ZOOM_RATE1","dårlig");
define("_ZOOM_RATE2","gjennomsnittlig");
define("_ZOOM_RATE3","bra");
define("_ZOOM_RATE4","veldig bra");
define("_ZOOM_RATE5","super!");

//special
define("_ZOOM_TOPTEN","Top 10");
define("_ZOOM_LASTSUBM","Sist publisert");
define("_ZOOM_LASTCOMM","Sist kommentert");
define("_ZOOM_SEARCHRESULTS","Søkeresultat");
define("_ZOOM_TOPRATED","De best vurderte");

//ecard
define("_ZOOM_ECARD_SENDAS","Send dette bildet som et eKort til en venn!");
define("_ZOOM_ECARD_YOURNAME","Ditt navn");
define("_ZOOM_ECARD_YOUREMAIL","Din e-post adresse");
define("_ZOOM_ECARD_FRIENDSNAME","Din venns navn");
define("_ZOOM_ECARD_FRIENDSEMAIL","Din venns e-post adresse");
define("_ZOOM_ECARD_MESSAGE","Melding");
define("_ZOOM_ECARD_SENDCARD","Send eKort");
define("_ZOOM_ECARD_SUCCESS","Ditt kort har blitt sendt.");
define("_ZOOM_ECARD_CLICKHERE","Klikk her for å se det!");
define("_ZOOM_ECARD_ERROR","Det oppstod en feil da du sendte et eKort til");
define("_ZOOM_ECARD_TURN","Se på baksiden av dette kortet!");
define("_ZOOM_ECARD_TURN2","Se på forsiden av dette kortet!");
define("_ZOOM_ECARD_SENDER","Er sent til deg fra:");
define("_ZOOM_ECARD_SUBJ","Du har mottatt et eKort fra:");
define("_ZOOM_ECARD_MSG1","sent et eKort til deg fra");
define("_ZOOM_ECARD_MSG2","klikk på linken nedenfor for å se ditt personlige eKort!");
define("_ZOOM_ECARD_MSG3","Ikke svar på denne e-posten, da den er automatisk generert.");
define("_ZOOM_ECARD_ECARDEXPIRED","Beklager, dette e-post-kortet er ikke tilgjengelig lenger.");

//installation-screen
define ('_ZOOM_INSTALL_CREATE_DIR','zOOm vil forsøke å opprette bilde-mappen "images/zoom" ...');
define ('_ZOOM_INSTALL_CREATE_DIR_WM','zOOm vil forsøke å opprette bilde-mappen "images/zoom/watermarks" ...');
define ('_ZOOM_INSTALL_CREATE_DIR_SUCC','ferdig!');
define ('_ZOOM_INSTALL_CREATE_DIR_FAIL','kunne ikke opprettes!');
define ('_ZOOM_INSTALL_CREATE_DBASE_SUCC','zOOm har opprettet tabeller i databasen!');
define ('_ZOOM_INSTALL_UPGRADED_DBASE_SUCC','Databasen ble oppdatert!');
define ('_ZOOM_INSTALL_MESS1','zOOm Media Galleri ble installert.<br>Du kan nå laste opp medier til dine gallerier!');
define ('_ZOOM_INSTALL_MESS2','Merk: Det første du bør gjøre nå, er å gå til komponentmenyen over, <br>og velg "zOOm Media Galleri Admin", <br>og sjekk oppføringene på innstillings-siden i Admin-systemet.');
define ('_ZOOM_INSTALL_MESS3','Her kan du forandre alle innstillinger og tilpasse zOOm til ditt bruk.');
define ('_ZOOM_INSTALL_MESS4','Ikke glem å opprett et galleri og du er igang!');
define ('_ZOOM_INSTALL_MESS_FAIL1','zOOM Galleri kunne ikke bli innstallert fullstendig!');
define ('_ZOOM_INSTALL_MESS_FAIL2','Følgende mapper må opprettes og rettighetene må settes til "0777":<br />' 
. '"images/zoom"<br />'
. '"/components/com_zoom/images"<br />'
. '"/components/com_zoom/admin"<br />'
. '"/components/com_zoom/classes"<br />'
. '"/components/com_zoom/images"<br />'
. '"/components/com_zoom/images/admin"<br />'
. '"/components/com_zoom/images/filetypes"<br />'
. '"/components/com_zoom/images/rating"<br />'
. '"/components/com_zoom/images/smilies"<br />'
. '"/components/com_zoom/language"<br />'
. '"/components/com_zoom/tabs"');
define ('_ZOOM_INSTALL_MESS_FAIL3','Når du har opprettet disse mappene og forandret rettighetene til dem, kan du gå til <br /> "Components -> zOOm Media Galleri" og forandre innstillingene etter ditt behov.');
?>