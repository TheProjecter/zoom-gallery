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
| Filename: germanf.php                                               |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id: germanf.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOm Media Gallery
* @author Jan Erik Zassenhaus <jan.zassenhaus@joomlaportal.ch> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
//Language translation
define("_ZOOM_DATEFORMAT","%d.%m.%Y %H:%M"); // use the PHP strftime Format, more info at http://www.php.net
define("_ZOOM_PICK","Wählen Sie ein Album");
define("_ZOOM_DELETE","Löschen");
define("_ZOOM_BACK","Zurück");
define("_ZOOM_MAINSCREEN","Hauptseite");
define("_ZOOM_BACKTOGALLERY","Zurück zum Album");
define("_ZOOM_INFO_DONE","Fertig!");
define("_ZOOM_TOOLTIP", "zOOm-ToolTip");
define("_ZOOM_WARNING", "zOOm-Warnung!");

//Gallery admin page
define("_ZOOM_ADMINSYSTEM","Admin-System");
define("_ZOOM_USERSYSTEM","Benutzer-System");
define("_ZOOM_ADMIN_TITLE","Media Gallery Admin-System");
define("_ZOOM_USER_TITLE","Media Gallery Benutzer-System");
define("_ZOOM_CATSMGR","Album-Manager");
define("_ZOOM_CATSMGR_DESCR","Erstellen Sie neue Alben für Ihre Bilder. Sie können sie hier im Album-Manager bearbeiten und löschen.");
define("_ZOOM_SETTINGS_DDONOF","Den \"Drag n Drop\"-Tab anzeigen:"); 
define("_ZOOM_NEW","Neues Album");
define("_ZOOM_DEL","Album löschen");
define("_ZOOM_MEDIAMGR","Medien-Manager");
define("_ZOOM_MEDIAMGR_DESCR","Hier können Sie Ihre Medien einfach verschieben, bearbeiten, löschen oder neue Medien automatisch oder manuell suchen lassen, um Ihr Album zu ergänzen.");
define("_ZOOM_THUMB", "Zoom Thumb Coder");
define("_ZOOM_THUMB_DESCR", "Berechnen Sie so Ihre 'Zoom Thumb codes' einfacher.");
define("_ZOOM_UPLOAD","Datei(en) hochladen");
define("_ZOOM_EDIT","Album bearbeiten");
define("_ZOOM_ADMIN_CREATE","Datenbank erzeugen");
define("_ZOOM_ADMIN_CREATE_DESCR","Erzeugt die benötigten Tabellen in der Datenbank, damit Sie Ihr Album anlegen können");
define("_ZOOM_HD_PREVIEW","Vorschau");
define("_ZOOM_HD_CHECKALL","Alle auswählen/abwählen");
define("_ZOOM_HD_CREATEDBY","Erstellt von");
define("_ZOOM_HD_AFTER","Aktuelles Album");
define("_ZOOM_HD_HIDEMSG","Den Text 'keine Medien' verbergen");
define("_ZOOM_HD_NAME","Titel");
define("_ZOOM_HD_DIR","Verzeichnis");
define("_ZOOM_HD_NEW","Neues Album");
define("_ZOOM_HD_SHARE","Dieses Album freigeben");
define("_ZOOM_SHARE","Freigeben");
define("_ZOOM_UNSHARE","Zurückziehen");
define("_ZOOM_PUBLISH","Veröffentlichen");
define("_ZOOM_UNPUBLISH","Zurückziehen");
define("_ZOOM_TOPLEVEL","Hauptseite");
define("_ZOOM_HD_UPLOAD","Datei hochladen");
define("_ZOOM_A_ERROR_ERRORTYPE","Fehlertyp");
define("_ZOOM_A_ERROR_IMAGENAME","Medienname");
define("_ZOOM_A_ERROR_NOFFMPEG","<u>FFmpeg</u> nicht gefunden");
define("_ZOOM_A_ERROR_NOPDFTOTEXT","<u>PDFtoText</u> nicht gefunden");
define("_ZOOM_A_ERROR_NOTINSTALLED","Nicht installiert");
define("_ZOOM_A_ERROR_CONFTODB","Fehler beim Speichern der Konfiguration in der Datenbank!");
define("_ZOOM_A_MESS_NOT_SHURE","* Wenn Sie sich nicht sicher sind, dann benutzen Sie bitte default \"auto\" ");
define("_ZOOM_A_MESS_SAFEMODE1","Hinweis: \"Safe Mode\" ist aktiviert. Deshalb kann es möglich sein, dass das Hochladen von großen Dateien fehlschlägt!<br />Sie sollten zu 'Einstellungen' wechsel und den FTP-Modus aktivieren.");
define("_ZOOM_A_MESS_SAFEMODE2","Hinweis: \"Safe Mode\" ist aktiviert. Deshalb kann es möglich sein, dass das Hochladen von großen Dateien fehlschlägt!<br />zOOm kann nur funktionieren, wenn Sie den FTP-Modus unter 'Einstellungen' aktivieren.");
define("_ZOOM_A_MESS_PROCESSING_FILE","Datei wird bearbeitet...");
define("_ZOOM_A_MESS_NOTOPEN_URL","Die folgende URL konnte nicht geöffnet werden:");
define("_ZOOM_A_MESS_PARSE_URL","Syntaxanalyse \"%s\" für die Datei... "); // %s = $url
define("_ZOOM_A_MESS_NOJAVA","Wenn Sie hier nur eine graue Box sehen oder Probleme beim Hochladen haben, dann kann es sein, dass<br />Sie nicht die aktuelle Version des Java Runtime Environment (JRE) installiert haben. Laden Sie sich bitte die aktuelle Version bei <a href=\"http://www.java.com\" target=\"_blank\">Java.com</a> <br /> herunter.");
define("_ZOOM_SETTINGS","Einstellungen");
define("_ZOOM_SETTINGS_DESCR","Hier können Sie die aktuellen Einstellungen ansehen und ggf. ändern.");
define("_ZOOM_SETTINGS_TAB1","System");
define("_ZOOM_SETTINGS_TAB2","Medien");
define("_ZOOM_SETTINGS_TAB3","Eigenschaften");
define("_ZOOM_SETTINGS_TAB4","Layout");
define("_ZOOM_SETTINGS_TAB5","Wasserzeichen");
define("_ZOOM_SETTINGS_TAB6","Safe Mode");
define("_ZOOM_SETTINGS_TAB7","Rechte");
define("_ZOOM_SETTINGS_TAB8","Reset");
define("_ZOOM_SETTINGS_ERASE","Klicken Sie auf \"Löschen\", um alle Daten von zOOm Gallery zu löschen und neu anzufangen. Hierbei werden alle Einstellungen und Medien unwiederruflich gelöscht!");
define("_ZOOM_SETTINGS_CONVTYPE","Bildverarbeitung:");
define("_ZOOM_SETTINGS_GALLERY_FEATURES","Eigenschaften der Albumanzeige");
define("_ZOOM_SETTINGS_VIEW_FEATURES","Eigenschaften der Medienanzeige");

define("_ZOOM_SETTINGS_GALLERY","Album Anzeige");
define("_ZOOM_SETTINGS_VIEW","Medien Anzeige");

define("_ZOOM_SETTINGS_GENERAL_FEATURES","Allgemeine Eigenschaften");
define("_ZOOM_SETTINGS_AUTODET","Automatisch erkannt: ");
define("_ZOOM_SETTINGS_IMGPATH","Pfad zu den Medien:");
define("_ZOOM_SETTINGS_TTIMGPATH","Der aktuelle Pfad zu den Medien ist ");
define("_ZOOM_SETTINGS_CONVSETTINGS","Medien Konvertierung:");
define("_ZOOM_SETTINGS_IMPATH","Pfad zu ImageMagick: ");
define("_ZOOM_SETTINGS_NETPBMPATH"," oder zu NetPBM: ");
define("_ZOOM_SETTINGS_FFMPEGPATH","Pfad zu FFmpeg");
define("_ZOOM_SETTINGS_FFMPEGTOOLTIP","FFmpeg ist die Vorraussetzung dafür, dass Thumbnails von Ihren Videos erstellt werden können.<br />Unterstützte Formate sind: ");
define("_ZOOM_SETTINGS_OVERRIDE_FFMPEG","Benutze FFmpeg, wenn zOOm nicht in der Lage ist dieses automatisch zu erkennen.");
define("_ZOOM_SETTINGS_PDFTOTEXTPATH","Pfad zu PDFtoText");
define("_ZOOM_SETTINGS_XPDFTOOLTIP","PDFtoText ist ein Teil vom Xpdf package und ist erforderlich um PDF-Dateien zu indexieren.");
define("_ZOOM_SETTINGS_OVERRIDE_PDF","Benutze PDFtoText, wenn zOOm nicht in der Lage ist dieses automatisch zu erkennen.");
define("_ZOOM_SETTINGS_MAXSIZE","Max. Bildgröße: ");
define("_ZOOM_SETTINGS_MAXSIZEKB","Medien - Bilder eingeschlossen - max. Größe (in kB): "); //added: 08-08-2006
define("_ZOOM_SETTINGS_MAXSIZEKB_WARNING","Das Upload-Limit dieses Servers, dass Sie oder Ihr Webspace-Anbieter über die PHP-Konfiguration gesetzt haben, liegt bei %s.<br />Deshalb wird jeder von Ihnen eingegebene größere Wert keinen Effekt haben!"); //added: 09-01-2006
define("_ZOOM_SETTINGS_THUMBSETTINGS","Thumbnail-Einstellungen:");
define("_ZOOM_SETTINGS_QUALITY","NetPBM und GD2 JPEG-Qualität: ");
define("_ZOOM_SETTINGS_SIZE","Max. Thumbnail-Größe: ");
define("_ZOOM_SETTINGS_TEMPNAME","Temporärer Name: ");
define("_ZOOM_SETTINGS_AUTONUMBER","Automatische Medienbennenung (z.B. 1,2,3)");
define("_ZOOM_SETTINGS_TEMPDESCR","Temporäre Beschreibung: ");
define("_ZOOM_SETTINGS_TITLE","Albumname:");
define("_ZOOM_SETTINGS_SUBCATSPG","Anzahl der Spalten in (Unter-)Alben");
define("_ZOOM_SETTINGS_COLUMNS","Anzahl der Thumbnail-Spalten");
define("_ZOOM_SETTINGS_THUMBSPG","Thumbnails pro Seite");
define("_ZOOM_SETTINGS_CMTLENGTH","Max. Länge der Kommentare:");
define("_ZOOM_SETTINGS_CHARS","Zeichen");
define("_ZOOM_SETTINGS_GALLERYPREFIX","Präfix für den Titel:");
define("_ZOOM_SETTINGS_SHOWOCCSPACE","Zeige belegten Speicher im Medien-Manager:");
define("_ZOOM_SETTINGS_TEMPLATE_TITLE","Template");
define("_ZOOM_SETTINGS_FEATURES_TITLE","Eigenschaften EIN/ AUS");
define("_ZOOM_SETTINGS_CSS_TITLE","Stylesheets bearbeiten");
define("_ZOOM_SETTINGS_DISPLAY_TITLE","Anzeigeoptionen EIN/ AUS");
define("_ZOOM_SETTINGS_TEMPLATE_CHOOSE","Wählen Sie ein Template aus, um das Aussehen &amp; den Eindruck Ihres Albums zu beeinflussen");
define("_ZOOM_SETTINGS_TEMPLATE_TABLES","Klassisch (mit Tabellen)");
define("_ZOOM_SETTINGS_TEMPLATE_TABLELESS","Modern (ohne Tabellen)");
define("_ZOOM_SETTINGS_COMMENTS","Kommentare");
define("_ZOOM_SETTINGS_POPUP","PopUp-Medien");
define("_ZOOM_SETTINGS_CATIMG","Albumbild anzeigen");
define("_ZOOM_SETTINGS_SLIDESHOW","Diashow");
define("_ZOOM_SETTINGS_ZOOMLOGO","Zeige zOOm-Logo");
define("_ZOOM_SETTINGS_SHOWHITS","Zeige Anzahl der Zugriffe");
define("_ZOOM_SETTINGS_READEXIF","Lese EXIF-Daten");
define("_ZOOM_SETTINGS_EXIFTOOLTIP","Mir dieser Funktion werden zusätzliche EXIF- und andere IPTC-Daten angezeigt, ohne das Sie das EXIF-Modul für PHP installieren müssen.");
define("_ZOOM_SETTINGS_READID3","Lese mp3 ID3-Informationen");
define("_ZOOM_SETTINGS_ID3TOOLTIP","Mit dieser Funktion werden zusätzliche ID3 v.1.1 und v.2.0-Daten angezeigt, wenn Sie sich die Details einer mp3-Datei angucken.");
define("_ZOOM_SETTINGS_RATING","Bewertung");
define("_ZOOM_SETTINGS_CSS","PopUp-Fenster");
define("_ZOOM_SETTINGS_CSSZOOM","zOOm Media Gallery &amp; Medien Ansicht");
define("_ZOOM_SETTINGS_SUCCESS","Konfiguration erfolgreich gespeichert!");
define("_ZOOM_SETTINGS_ZOOMING","Bildvergrößerung");
define("_ZOOM_SETTINGS_ORDERBY","Thumbnail-Sortierung; sortiert nach");
define("_ZOOM_SETTINGS_CATORDERBY","(Unter-)Alben Sortierung; sortiert nach");
define("_ZOOM_SETTINGS_DATE_ASC","DATUM, aufsteigend");
define("_ZOOM_SETTINGS_DATE_DESC","DATUM, absteigend");
define("_ZOOM_SETTINGS_FLNM_ASC","DATEINAME, aufsteigend");
define("_ZOOM_SETTINGS_FLNM_DESC","DATEINAME, absteigend");
define("_ZOOM_SETTINGS_NAME_ASC","NAME, aufsteigend");
define("_ZOOM_SETTINGS_NAME_DESC","NAME, absteigend");
define("_ZOOM_SETTINGS_LBTOOLTIP","Eine Lightbox kann man mit einem Einkaufswagen vergleichen. Hier legt Ihr Benutzer die Bilder rein, die er sich am Ende als ZIP-Datei herunterladen möchte.");
define("_ZOOM_SETTINGS_SHOWNAME","Zeige Name");
define("_ZOOM_SETTINGS_SHOWDESCR","Zeige Beschreibung");
define("_ZOOM_SETTINGS_SHOWKEYWORDS","Zeige Schlüsselwörter");
define("_ZOOM_SETTINGS_SHOWDATE","Zeige Datum");
define("_ZOOM_SETTINGS_SHOWUNAME","Zeige Benutzername");
define("_ZOOM_SETTINGS_SHOWFILENAME","Zeige Dateiname");
define("_ZOOM_SETTINGS_METABOX","Zeige Informationsbox für Medieninformationen");
define("_ZOOM_SETTINGS_METABOXTOOLTIP","Deaktivieren Sie diese Funktion, um das Anzeigen des Albums zu beschleunigen. Bei großen Datenbanken empfohlen!");
define("_ZOOM_SETTINGS_ECARDS","E-Cards");
define("_ZOOM_SETTINGS_ECARDS_LIFETIME","E-Cards Gültigkeit");
define("_ZOOM_SETTINGS_ECARDS_ONEWEEK","eine Woche");
define("_ZOOM_SETTINGS_ECARDS_TWOWEEKS","zwei Wochen");
define("_ZOOM_SETTINGS_ECARDS_ONEMONTH","einen Monat");
define("_ZOOM_SETTINGS_ECARDS_THREEMONTHS","drei Monate");
define("_ZOOM_SETTINGS_SHOWSEARCH","Zeige Suchfeld auf ALLEN Seiten");
define("_ZOOM_SETTINGS_BOX_ANIMATE","Boxen animieren");
define("_ZOOM_SETTINGS_BOX_PROPERTIES","Zeige Box \"Eigenschaften\"");
define("_ZOOM_SETTINGS_BOX_META","Zeige Box \"Metadata\"");
define("_ZOOM_SETTINGS_BOX_COMMENTS","Zeige Box \"Kommentare\"");
define("_ZOOM_SETTINGS_BOX_RATING","Zeige Box 'Bewertungen'");
define("_ZOOM_SETTINGS_TOPTEN","Zeige \"Die Besten 10\"-Link auf der Hauptseite");
define("_ZOOM_SETTINGS_LASTSUBM","Zeige \"Zuletzt übermittelt\"-Link auf der Hauptseite");
define("_ZOOM_SETTINGS_SETMENUOPTION","Zeige \"Hochladen\"-Link im Benutzermenü");
define("_ZOOM_SETTINGS_USEFTP","FTP-Modus benutzen?");
define("_ZOOM_SETTINGS_FTPHOST","Servername");
define("_ZOOM_SETTINGS_FTPUNAME","Benutzername");
define("_ZOOM_SETTINGS_FTPPASS","Passwort");
define("_ZOOM_SETTINGS_FTPWARNING","Warnung: Das Passwort wird nicht sicher gespeichert!");
define("_ZOOM_SETTINGS_FTPHOSTDIR","Verzeichnis auf dem Server");
define("_ZOOM_SETTINGS_MESS_FTPHOSTDIR","Bitte geben Sie hier den Pfad von Ihrem FTP-Hauptverzeichnis zu Joomla! ein. WICHTIG: Das Ende <b>ohne</b> einen Slash oder Backslash!");
define("_ZOOM_SETTINGS_GROUP","Gruppe");
define("_ZOOM_SETTINGS_PRIV_DESCR","Sie sind in der Lage die Rechte für jede bekannte Joomla!-Gruppe zu ändern und 
somit jedem Benutzer in einer der Gruppen Benutzerrechte zu geben!<br />
    Ein Benutzer könnte zum Beispiel folgendes tun: Medien hochladen, sie bearbeiten/löschen oder neue Alben erstellen/veröffentlichen/bearbeiten/löschen.<br />
    Was Ihre Benutzer dürfen und was nicht, können Sie hier einstellen.");
define("_ZOOM_SETTINGS_CLOSE","Zeige \"Schließen\"-Link in einem PopUp");
define("_ZOOM_SETTINGS_MAINSCREEN","Zeige Pfad zur Hauptseite in der oberen Leiste");
define("_ZOOM_SETTINGS_NAVBUTTONS","Zeige eine Navigation in PopUp-Fenstern");
define("_ZOOM_SETTINGS_PROPERTIES","Zeige Eigenschaften unter den Medien");
define("_ZOOM_SETTINGS_MEDIAFOUND","Zeige \"Medien gefunden\" im Album");
define("_ZOOM_SETTINGS_ANONYMOUS_COMMENTS","Erlaube anonyme Kommentare");
define("_ZOOM_SETTINGS_WM_ENABLE_TITLE","Wasserzeichen aktivieren");
define("_ZOOM_SETTINGS_WM_TITLE", "Ihre Wasserzeichen");
define("_ZOOM_SETTINGS_WM_DESCR", "Ihr Wasserzeichen wird im oberen Teil Ihrer Bilder angezeigt. "
 . "Ihre Bilder können weiterhin angeschaut werden, aber Ihre Besucher können die Bilder nicht speichern oder drucken."
 . "<br /><br />Vorschlag: Sie können Ihr Firmenlogo als Wasserzeichen benutzen. "
 . "Bitte stellen Sie sicher, dass Sie den Hintergrund Ihres Wasserzeichens transparent eingestellt haben!");
define("_ZOOM_SETTINGS_WM_IMG", "Bild");
define("_ZOOM_SETTINGS_WM_NOWATERMARKS", "Keine Wasserzeichen gefunden. Sie können ein neues Wasserzeichen unten hochladen.");
define("_ZOOM_SETTINGS_WM_PLACEMENT_TITLE", "Plazierung");
define("_ZOOM_SETTINGS_WM_PLACEMENT_DESCR", "Sie können die Position Ihres Wasserzeichens mit den Feldern unten bestimmen, indem Sie "
 . "eine der Positionen in der grauen Box auswählen.");
define("_ZOOM_SETTINGS_WM_FILE","Wasserzeichen hochladen");
define("_ZOOM_SETTINGS_WM_UPLOAD_SUCCESS","Wasserzeichen erfolgreich hochgeladen!");
define("_ZOOM_SETTINGS_WM_UPLOAD_FAIL","Wasserzeichen kann <b>nicht</b> hochgelden werden.");
define("_ZOOM_SETTINGS_WM_DEL_SUCCESS","Wassserzeichen erfolgreich gelöscht!");
define("_ZOOM_SETTINGS_WM_DEL_FAIL","Wasserzeichen kann nicht entfernt werden.");
define("_ZOOM_SYSTEM_TITLE","Systemeinstellungen");
define("_ZOOM_YES","Ja");
define("_ZOOM_NO","Nein");
define("_ZOOM_VISIBLE","Sichbar");
define("_ZOOM_HIDDEN","Versteckt");
define("_ZOOM_SAVE","Speichern");
define("_ZOOM_MOVEFILES","Medium verschieben");
define("_ZOOM_BUTTON_MOVE","Verschieben");
define("_ZOOM_MOVEFILES_STEP1","Wählen Sie das Zielalbum");
define("_ZOOM_ALERT_MOVE","%s erfolgreich verschoben, %s konnte nicht verschoben werden.");
define("_ZOOM_OPTIMIZE","Tabellen optimieren");
define("_ZOOM_OPTIMIZE_DESCR","zOOm Media Gallery benutzt viele Tabellen, die überflüssige Daten speichern. Klicken Sie hier und entfernen Sie so diese Dateien.");
define("_ZOOM_OPTIMIZE_SUCCESS","Tabellen wurden optimiert!");
define("_ZOOM_UPDATE","Update zOOm Media Gallery");
define("_ZOOM_UPDATE_DESCR","Fügen Sie neue Funktionen ein und lösen Sie Probleme! Gehen Sie auf <a href=\"http://www.zoomfactory.org\" target=\"_blank\">www.zoomfactory.org</a> für die neuesten Updates!");
define("_ZOOM_UPDATE_XMLDATE","Datum des letzten Updates");
define("_ZOOM_UPDATE_NOUPDATES","zur Zeit keine Updates!"); // added 11-08
define("_ZOOM_UPDATE_PACKAGE","Update ZIP-Datei: ");
define("_ZOOM_CREDITS","Über zOOm Media Gallery &amp; Credits");

//Image actions
define("_ZOOM_DISKSPACEUSAGE","Speicherplatz der von %s genutzt wird");
define("_ZOOM_UPLOAD_SINGLE","einzelne Datei");
define("_ZOOM_UPLOAD_MULTIPLE","mehrere Dateien");
define("_ZOOM_UPLOAD_DRAGNDROP","Drag n Drop");
define("_ZOOM_UPLOAD_SCANDIR","Medien suchen");
define("_ZOOM_UPLOAD_INTRO","Klicken Sie auf 'Durchsuchen', um Ihre Datei zum Hochladen auszuwählen.");
define("_ZOOM_UPLOAD_STEP1","1. Wählen Sie die Anzahl an Dateien, die Sie hochladen möchten: ");
define("_ZOOM_UPLOAD_STEP2","2. Wählen Sie das Album aus, wo Ihre Dateien abgespeichert werden sollen: ");
define("_ZOOM_UPLOAD_STEP3","3. Benutzen Sie 'Durchsuchen', um die Dateien auf Ihrem Rechner zu finden");
define("_ZOOM_SCAN_STEP1","Schritt 1: Geben Sie einen Ort zum Suchen nach Medien ein...");
define("_ZOOM_SCAN_STEP2","Schritt 2: Wählen Sie die Dateien aus, die Sie hochladen wollen...");
define("_ZOOM_SCAN_STEP3","Schritt 3: zOOm verarbeitet Ihre Anfrage...");
define("_ZOOM_SCAN_STEP1_DESCR","Der Ort kann sowohl eine URL sein als auch ein Verzeichnis auf dem Server.<br />&nbsp; Tipp: Über FTP Medien hochladen und dann den Pfad hier eingeben!");
define("_ZOOM_SCAN_STEP2_DESCR1","Verarbeitung");
define("_ZOOM_SCAN_STEP2_DESCR2","als ein lokales Verzeichnis");
define("_ZOOM_FORMCREATE_NAME","Name");
define("_ZOOM_FORM_IMAGEFILE","Medien");
define("_ZOOM_FORM_IMAGEFILTER","Unterstützte Medientypen");
define("_ZOOM_FORM_INGALLERY","Im Album");
define("_ZOOM_FORM_SETFILENAME","Setzte die Mediennamen gleich den Dateinamen.");
define("_ZOOM_FORM_IGNORESIZES","Voreingestellte Maximalgröße der Medien ignorieren"); //added: 12-08
define("_ZOOM_FORM_LOCATION","Ort");
define("_ZOOM_BUTTON_SCAN","Übermitteln Sie eine URL oder ein Verzeichnis");
define("_ZOOM_BUTTON_UPLOAD","Hochladen");
define("_ZOOM_BUTTON_EDIT","Bearbeiten");
define("_ZOOM_BUTTON_CREATE","Erstellen");
define("_ZOOM_CONFIRM_WIPE","WARNUNG!\\nDas Ausführen dieser Funktion wird alle Daten von zOOm gallery und den erstellten Alben incl. der Bilder löschen.\\n\\nWollen Sie wirklich alles löschen?");
define("_ZOOM_CONFIRM_DEL","Diese Einstellung wird das Album incl. der enthaltenen Medien löschen!\\nWollen Sie das wirklich?");
define("_ZOOM_CONFIRM_DELMEDIUM","Sie sind gerade dabei dieses Medium komplett zu löschen!\\nWollen Sie das wirklich?");
define("_ZOOM_ALERT_DEL","Album wurde gelöscht!");
define("_ZOOM_ALERT_NOCAT","Kein Album ausgewählt!");
define("_ZOOM_ALERT_NOMEDIA","Keine Medien ausgewählt!");
define("_ZOOM_ALERT_EDITOK","Albumdateien wurden erfolgreich bearbeitet!");
define("_ZOOM_ALERT_NEWGALLERY","Neues Album erstellt.");
define("_ZOOM_ALERT_NONEWGALLERY","Album nicht erstellt!");
define("_ZOOM_ALERT_EDITIMG","Medieneinstellungen wurden erfolgreich berarbeitet.");
define("_ZOOM_ALERT_DELPIC","Medium erfolgreich gelöscht.");
define("_ZOOM_ALERT_NODELPIC","Medium kann nicht gelöscht werden!");
define("_ZOOM_ALERT_MOVEFAILURE","Medium kann nicht verschoben werden."); //added: 08-08-2006
define("_ZOOM_ALERT_NOPICSELECTED","Kein Medium ausgewählt.");
define("_ZOOM_ALERT_NOPICSELECTED_MULT","Keine Medien ausgewählt.");
define("_ZOOM_ALERT_UPLOADOK","Medien erfolgreich hochgeladen!");
define("_ZOOM_ALERT_UPLOADSOK","Medium erfolgreich hochgeladen!");
define("_ZOOM_ALERT_WRONGFORMAT","Falsches Bildformat.");
define("_ZOOM_ALERT_WRONGFORMAT_MULT","Falsches Format.");
define("_ZOOM_ALERT_TOOBIG","Die Dateigröße des Mediums ist zu groß; %s ist das erlaubte Maximum."); //added 08-08-2006
define("_ZOOM_ALERT_IMGERROR","Fehler bei der Größenänderung des Bildes/ Thumbnail wird erstellt.");
define("_ZOOM_ALERT_PCLZIPERROR","Fehler beim Öffnen des Archivs.");
define("_ZOOM_ALERT_INDEXERROR","Fehler bei der Indexierung des Dokuments.");
define("_ZOOM_ALERT_WATERMARKERROR","Fehler beim Hinzufügen eines Wasserzeichens auf ein Bild.");
define("_ZOOM_ALERT_IMGFOUND","Bild(er) gefunden.");
define("_ZOOM_INFO_CHECKCAT","Bitte wählen Sie erst ein Album, bevor Sie etwas hochladen!");
define("_ZOOM_BUTTON_ADDIMAGES","Medien hinzufügen");
define("_ZOOM_BUTTON_REMIMAGES","Medien entfernen");
define("_ZOOM_INFO_PROCESSING","Verarbeitung der Dateien:");
define("_ZOOM_ITEMEDIT_TAB1","Einstellungen");
define("_ZOOM_ITEMEDIT_TAB2","Mitglieder");
define("_ZOOM_ITEMEDIT_TAB3","Aktionen");
define("_ZOOM_USERSLIST_LINE1",">>Wählen Sie Mitglieder für diesen Bereich<<");
define("_ZOOM_USERSLIST_ALLOWALL",">>Allg. Zugriff<<");
define("_ZOOM_USERSLIST_MEMBERSONLY",">>Nur für Mitglieder<<");
define("_ZOOM_PUBLISHED","Veröffentlicht");
define("_ZOOM_SHARED","Album freigeben");
define("_ZOOM_ROTATE","Drehe das Bild um 90 Grad");
define("_ZOOM_CLOCKWISE","im Uhrzeigersinn");
define("_ZOOM_CCLOCKWISE","gegen den Uhrzeigensinn");
define("_ZOOM_FLIP_HORIZ","Bild horizontal spiegeln");
define("_ZOOM_FLIP_VERT","Bild vertikal spiegeln");
define("_ZOOM_PROGRESS_DESCR","Ihre Anfrage wird berabeitet... Bitte haben Sie einen Augenblick Geduld.");

//Navigation (including Slideshow buttons)
define("_ZOOM_SLIDESHOW","Diashow:");
define("_ZOOM_PREV_IMG","vorheriges Medium");
define("_ZOOM_NEXT_IMG","nächstes Medium");
define("_ZOOM_FIRST_IMG","erstes Medium");
define("_ZOOM_LAST_IMG","letztes Medium");
define("_ZOOM_PLAY","Abspielen");
define("_ZOOM_STOP","Stop");
define("_ZOOM_RESET","Reset");
define("_ZOOM_FIRST","Erstes");
define("_ZOOM_LAST","Letztes");
define("_ZOOM_PREVIOUS","Vorherigesb");
define("_ZOOM_NEXT","Nächstes");
define("_ZOOM_IN_DESC", "Gehen Sie mit Ihrer Maus über das Bild. Sie können das Bild mit der Pfeil-nach-unten-Taste rauszoomen und mit der Pfeil-nach-oben-Taste hineinzoomen.");

//Gallery actions
define("_ZOOM_SEARCH_BOX","Dieses Album durchsuchen");
define("_ZOOM_ADVANCED_SEARCH","Erweiterte Suche");
define("_ZOOM_SEARCH_KEYWORD","Suche nach Schlüsselwort");
define("_ZOOM_IMAGES","Medien");
define("_ZOOM_IMGFOUND","%s Medien gefunden - Sie sind auf Seite %s von %s");
define("_ZOOM_SUBGALLERIES","Unteralbum");
define("_ZOOM_ALERT_COMMENTOK","Ihr Kommentar wurde erfolgreich gespeichert!");
define("_ZOOM_ALERT_COMMENTERROR","Sie haben bereits ein Kommentar für dieses Medium abgegeben!");
define("_ZOOM_ALERT_VOTE_OK","Ihre Stimme wurde gezählt! Danke.");
define("_ZOOM_ALERT_VOTE_ERROR","Sie haben bereits für dieses Medium gestimmt!");
define("_ZOOM_WINDOW_CLOSE","Schließen");
define("_ZOOM_NOPICS","Keine Medien im Album vorhanden");
define("_ZOOM_PROPERTIES","Eigenschaften");
define("_ZOOM_COMMENTS","Kommentare");
define("_ZOOM_NO_COMMENTS","Bisher wurden keine Kommentare abgegeben.");
define("_ZOOM_YOUR_NAME","Name");
define("_ZOOM_ADD","Hinzufügen");
define("_ZOOM_NAME","Name");
define("_ZOOM_DATE","Datum");
define("_ZOOM_UNAME","Hinzugefügt von");
define("_ZOOM_DESCRIPTION","Beschreibung");
define("_ZOOM_IMGNAME","Name");
define("_ZOOM_FILENAME","Dateiname");
define("_ZOOM_CLICKDOCUMENT","(zum Öffnen auf den Dateinamen klicken)");
define("_ZOOM_KEYWORDS","Schlüsselwörter");
define("_ZOOM_HITS","Aufrufe");
define("_ZOOM_CLOSE","Schließen");
define("_ZOOM_NOIMG", "Keine Medien gefunden!");
define("_ZOOM_NONAME", "Sie müssen einen Namen eingeben!");
define("_ZOOM_NOCAT", "Kein Album ausgewählt!");
define("_ZOOM_EDITPIC", "Medien bearbeiten");
define("_ZOOM_SETCATIMG","Als Albumbild festlegen");
define("_ZOOM_SETPARENTIMG","Als Albumbild des darüberliegenden Albums festlegen");
define("_ZOOM_PASS","Passwort");
define("_ZOOM_PASS_REQUIRED","Sie benötigen ein Passwort, um das Album ansehen zu können.<br />Bitte geben Sie ein Passwort ein<br /> und klicken Sie dann auf 'Weiter'. Danke.");
define("_ZOOM_PASS_BUTTON","Weiter");
define("_ZOOM_PASS_GALLERY","Passwort");
define("_ZOOM_PASS_INNCORRECT","Passwort falsch");

//Hotlinking
define("_ZOOM_SETTINGS_HOTLINK","Aktiviere Schutz vor externen Verlinkungen");
define("_ZOOM_SETTINGS_HPTOOLTIP","Wenn Sie diese Funktion aktivieren, werden die Dateinamen der Bilder und ihr Pfad auf dem Server versteckt. Genauso, wenn ein Benutzer versucht Ihre Bilder auf anderen Seiten zu verwenden, so werden sie nicht angezeigt.");


//Lightbox
define("_ZOOM_LIGHTBOX","Lightbox");
define("_ZOOM_LIGHTBOX_GALLERY","Dieses Album in die Lightbox!");
define("_ZOOM_LIGHTBOX_ITEM","Dieses Medium in die Lightbox!");
define("_ZOOM_LIGHTBOX_VIEW","Lightbox anschauen");
define("_ZOOM_YOUR_LIGHTBOX","Ihr Lightbox-Inhalt:");
define("_ZOOM_LIGHTBOX_EMPTY","Ihre Lightbox ist zur Zeit leer.");
define("_ZOOM_LIGHTBOX_ZIPBTN","ZIP-Datei erstellen");
define("_ZOOM_LIGHTBOX_PLAYLISTBTN","Playliste erstellen &amp; abspielen");
define("_ZOOM_LIGHTBOX_CATS","Alben");
define("_ZOOM_LIGHTBOX_TITLEDESCR","Titel &amp; Beschreibung");
define("_ZOOM_ACTION","Ausführen");
define("_ZOOM_LIGHTBOX_ADDED","Medium erfolgreich zu Ihrer Lightbox hinzugefügt!");
define("_ZOOM_LIGHTBOX_NOTADDED","Dieses Medium wurde bereits in die Lighbox aufgenommen!");
define("_ZOOM_LIGHTBOX_EDITED","Medium erfolgreich bearbeitet!");
define("_ZOOM_LIGHTBOX_NOTEDITED","Fehler bei der Bearbeitung!");
define("_ZOOM_LIGHTBOX_DEL","Medium wurde erfolgreich aus Ihrer Lighbox gelöscht!");
define("_ZOOM_LIGHTBOX_NOTDEL","Medium konnte nicht von Ihrer Lighbox entfernt werden!");
define("_ZOOM_LIGHTBOX_NOZIP","Sie haben bereits eine ZIP-Datei erzeugt oder Ihre Lightbox ist leer!");
define("_ZOOM_LIGHTBOX_PARSEZIP","Füge Medien aus Album ein...");
define("_ZOOM_LIGHTBOX_DOZIP","ZIP-Datei wird erstellt...");
define("_ZOOM_LIGHTBOX_DLHERE","Sie können die Lighbox nun herunterladen");
define("_ZOOM_LIGHTBOX_PLSUCCESS","Playliste wurde erfolgreich erstellt! Sie müssen nun das Player-Fenster aktualisieren.");
define("_ZOOM_LIGHTBOX_PLERROR","Fehler bei der Erstellung der Playliste.");
define("_ZOOM_LIGHTBOX_NOAUDIO","Sie müssen erst Audiodaten zu Ihrer Lightbox hinzufügen!");
define("_ZOOM_LIGHTBOX_NOITEMS","Ihre Lightbox schein leer zu sein.");

//EXIF information
define("_ZOOM_EXIF","EXIF");
define("_ZOOM_EXIF_SHOWHIDE","Zeige/ verstecke EXIF-Info");

//MP3 id3 v1.1 or later information
define("_ZOOM_AUDIO_PLAYING","gerade spielt:");
define("_ZOOM_AUDIO_CLICKTOPLAY","Klicken Sie hier, um sich die Datei anzuhören.");
define("_ZOOM_ID3","ID3");
define("_ZOOM_ID3_SHOWHIDE","Zeige/ verberge ID3-tag-Informationen");
define("_ZOOM_ID3_LENGTH","Länge");
define("_ZOOM_ID3_QUALITY","Qualität");
define("_ZOOM_ID3_TITLE","Titel");
define("_ZOOM_ID3_ARTIST","Interpret");
define("_ZOOM_ID3_ALBUM","Album");
define("_ZOOM_ID3_YEAR","Jahr");
define("_ZOOM_ID3_COMMENT","Kommentar");
define("_ZOOM_ID3_GENRE","Genre");

//Video metadata information
define("_ZOOM_VIDEO_SHOWHIDE","Zeige/verstecke Videoeigenschaften");
define("_ZOOM_VIDEO_PIXELRATIO","Pixelverhältnis");
define("_ZOOM_VIDEO_QUALITY","Videoqualität");
define("_ZOOM_VIDEO_AUDIOQUALITY","Audioqualität");
define("_ZOOM_VIDEO_CODEC","Codec");
define("_ZOOM_VIDEO_RESOLUTION","Auflösung");

//rating
define("_ZOOM_RATING","Bewertung");
define("_ZOOM_NOTRATED","Keine Bewertungen verfügbar!");
define("_ZOOM_VOTE","Stimme");
define("_ZOOM_VOTES","Stimmen");
define("_ZOOM_RATE0","schlecht");
define("_ZOOM_RATE1","schwach");
define("_ZOOM_RATE2","befriedigend");
define("_ZOOM_RATE3","gut");
define("_ZOOM_RATE4","sehr gut");
define("_ZOOM_RATE5","perfekt!");

//special
define("_ZOOM_TOPTEN","Die Besten 10");
define("_ZOOM_LASTSUBM","Zuletzt übermittelt");
define("_ZOOM_LASTCOMM","Zuletzt kommentiert");
define("_ZOOM_SEARCHRESULTS","Suchergebnisse");
define("_ZOOM_TOPRATED","Am Besten bewertet");

//ecard
define("_ZOOM_ECARD_SENDAS","Senden Sie dieses Medium als E-Card an einen Freund!");
define("_ZOOM_ECARD_YOURNAME","Ihr Name");
define("_ZOOM_ECARD_YOUREMAIL","Ihre E-Mail Adresse");
define("_ZOOM_ECARD_FRIENDSNAME","Name Ihres Freundes");
define("_ZOOM_ECARD_FRIENDSEMAIL","E-Mail Adresse Ihres Freundes");
define("_ZOOM_ECARD_MESSAGE","Nachricht");
define("_ZOOM_ECARD_SENDCARD","Sende E-Card");
define("_ZOOM_ECARD_SUCCESS","Ihre E-Card wurde erfolgreich gesendet.");
define("_ZOOM_ECARD_CLICKHERE","Klicken Sie hier, um Ihre E-Card zu sehen!");
define("_ZOOM_ECARD_ERROR","Fehler beim Versenden");
define("_ZOOM_ECARD_TURN","Rückseite ansehen");
define("_ZOOM_ECARD_TURN2","Vorderseite ansehen");
define("_ZOOM_ECARD_SENDER","An Sie gesandt von:");
define("_ZOOM_ECARD_SUBJ","Sie haben eine E-Card erhalten von:");
define("_ZOOM_ECARD_MSG1","hat Ihnen eine E-Card geschickt von");
define("_ZOOM_ECARD_MSG2","Klicken Sie auf den unten stehenden Link, um sich Ihre persönliche E-Card anzusehen!");
define("_ZOOM_ECARD_MSG3","Bitte antworten Sie nicht auf diese E-Mail, da sie automatisch generiert worden ist.");
define("_ZOOM_ECARD_ECARDEXPIRED","Entschuldigung, aber diese E-Card ist nicht mehr verfügbar oder ist abgelaufen.");

//installation-screen
define ('_ZOOM_INSTALL_CREATE_DIR','Die zOOm-Installation versucht das Medienverzeichnis zu erstellen "images/zoom" ...');
define ('_ZOOM_INSTALL_CREATE_DIR_WM','Die zOOm-Installation versucht das Medienverzeichnis für Ihre Wasserzeichen zu erstellen "images/zoom/watermarks" ...');
define ('_ZOOM_INSTALL_CREATE_DIR_SUCC','Fertig!');
define ('_ZOOM_INSTALL_CREATE_DIR_FAIL','Fehlgeschlagen!');
define ('_ZOOM_INSTALL_CREATE_DBASE_SUCC','Datenbank erfolgreich erstellt!');
define ('_ZOOM_INSTALL_UPGRADED_DBASE_SUCC','Datenbank erfolgreich aktualisiert!');
define ('_ZOOM_INSTALL_MESS1','Die zOOm Image Gallery wurde erfolgreich installiert.<br>Sie können nun Ihre Alben und Fotos veröffentlichen!');
define ('_ZOOM_INSTALL_MESS2','HINWEIS: Die erste Sache die Sie tun sollten, ist im Menü "Components" nach dem Eintrag <br> "zOOm Media Gallery" zu schauen und es anzuklicken.<br>Stellen Sie dann unter "Einstellung" alles nach Ihren Bedürfnissen ein.');
define ('_ZOOM_INSTALL_MESS3','Hier können Sie alle Variablen für zOOm ändern, damit sie optimal in Ihre Konfiguration passt.');
define ('_ZOOM_INSTALL_MESS4','Vergessen Sie nicht ein Album zu erstellen!');
define ('_ZOOM_INSTALL_MESS_FAIL1','zOOm Gallery konnte nicht komplett installiert werden!');
define ('_ZOOM_INSTALL_MESS_FAIL2','Folgende Verzeichnisse müssen noch erstellt werden und benötigen die Rechte "0777":<br />'
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
define ('_ZOOM_INSTALL_MESS_FAIL3','Wenn Sie diese Verzeichnisse erstellt haben, gehen Sie zu <br /> "Components -> zOOm Media Gallery" und ändern die Einstellungen fü sich ab.');

//RC4 - From Meru in the forums
define("_ZOOM_SETTINGS_NO_POP","Keine Popups");
define("_ZOOM_SETTINGS_STANDARD_POP","Standard Popups");
define("_ZOOM_SETTINGS_LIGHTBOX_POP","Lightbox JS Popup");
define("_ZOOM_SETTINGS_DESCRINGAL","Zeigt Albumbeschreibung in der Galerieansicht");
define("_ZOOM_ORDERSAVE", "Speichere Reihenfolge");
define("_ZOOM_COMMENTS_INTRO", "Kommentar abgeben");
define("_ZOOM_SAYS", "Sagt");
define("_ZOOM_SETTINGS_LIGHTBOX_SLIDESHOW","<strong><i>Schalten Sie dies ein, wenn Sie wollen das die Slideshow im LIGHTBOX JS Popup arbeitet</i></strong>");

?>