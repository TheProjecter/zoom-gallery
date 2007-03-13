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
| Filename: italian.php                                               |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:italian.php 123 2007-02-17 00:38:07Z kevinuru $
* @package zOOm Media Gallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );
//Language translation
define("_ZOOM_DATEFORMAT","%d.%m.%Y %H:%M"); // use the PHP strftime Format, more info at http://www.php.net
define("_ZOOM_PICK","Scegli la galleria");
define("_ZOOM_DELETE","Elimina");
define("_ZOOM_BACK","Torna indietro");
define("_ZOOM_MAINSCREEN","Pagina principale");
define("_ZOOM_BACKTOGALLERY","Torna alla galleria");
define("_ZOOM_INFO_DONE","fatto!");
define("_ZOOM_TOOLTIP", "Suggerimento zOOm");
define("_ZOOM_WARNING", "Avviso zOOm!");

//Gallery admin page
define("_ZOOM_ADMINSYSTEM","Sistema Amministrazione");
define("_ZOOM_USERSYSTEM","Sistema Utente");
define("_ZOOM_ADMIN_TITLE","Media Gallery Sistema Amministrazione");
define("_ZOOM_USER_TITLE","Media Gallery Sistema Utente");
define("_ZOOM_CATSMGR","Gestore gallerie");
define("_ZOOM_CATSMGR_DESCR","crea nuove gallerie per i tuoi media; creali, modificali ed eliminali qu&#236; nel Gestore gallerie.");
define("_ZOOM_SETTINGS_DDONOF","Abilita Caricamento Drag n Drop");
define("_ZOOM_NEW","Registra Nuova galleria");
define("_ZOOM_DEL","Elimina galleria");
define("_ZOOM_ORDERSAVE", "Registra Ordinamento");
define("_ZOOM_MEDIAMGR","Gestore Media");
define("_ZOOM_MEDIAMGR_DESCR","sposta, modifica, elimina, ricerca media automaticamente o carica (invio multiplo) nuovi media manualmente.");
define("_ZOOM_THUMB", "Codificatore Zoom Thumb");
define("_ZOOM_THUMB_DESCR", "Elabora facilmente il codice Zoom Thumb");
define("_ZOOM_UPLOAD","Carica file");
define("_ZOOM_EDIT","Modifica galleria");
define("_ZOOM_ADMIN_CREATE","Crea base di dati");
define("_ZOOM_ADMIN_CREATE_DESCR","crea le tabelle della base di dati richieste per l&#146;album cos&#236; che tu possa iniziare ad usarlo");
define("_ZOOM_HD_PREVIEW","Anteprima");
define("_ZOOM_HD_CHECKALL","Seleziona/Deseleziona Tutti");
define("_ZOOM_HD_CREATEDBY","Creato da");
define("_ZOOM_HD_AFTER","Galleria superiore");
define("_ZOOM_HD_HIDEMSG","Nascondi testo 'no media'");
define("_ZOOM_HD_NAME","Titolo");
define("_ZOOM_HD_DIR","Cartella");
define("_ZOOM_HD_NEW","Nuova galleria");
define("_ZOOM_HD_SHARE","Condividi questa galleria");
define("_ZOOM_SHARE","Condividi");
define("_ZOOM_UNSHARE","Non condividere");
define("_ZOOM_PUBLISH","Pubblica");
define("_ZOOM_UNPUBLISH","Non pubblicare");
define("_ZOOM_TOPLEVEL","Livello massimo");
define("_ZOOM_HD_UPLOAD","Carica file");
define("_ZOOM_A_ERROR_ERRORTYPE","Tipo errore");
define("_ZOOM_A_ERROR_IMAGENAME","Nome Immagine");
define("_ZOOM_A_ERROR_NOFFMPEG","<u>FFmpeg</u> non trovato");
define("_ZOOM_A_ERROR_NOPDFTOTEXT","<u>PDFtoText</u> non trovato");
define("_ZOOM_A_ERROR_NOTINSTALLED","Non installato");
define("_ZOOM_A_ERROR_CONFTODB","Errore durante la registrazione nella base di dati!");
define("_ZOOM_A_MESS_NOT_SHURE","* Se non sei sicuro, usa il valore predefinito \"auto\" ");
define("_ZOOM_A_MESS_SAFEMODE1","Nota: \"Modo Sicuro\" &#232; attivo, quindi &#232; possibile che il caricamento di grandi file non funzioni!<br />Dovresti portarti sul Sistema Amministrazione e passare a Modo-FTP.");
define("_ZOOM_A_MESS_SAFEMODE2","Nota: \"Modo Sicuro\" &#232; attivo, quindi &#232; possibile che il caricamento di grandi file non funzioni!<br />zOOm raccomanda di attivare Modo-FTP nel Sistema Amministrazione.");
define("_ZOOM_A_MESS_PROCESSING_FILE","Elaborazione file...");
define("_ZOOM_A_MESS_NOTOPEN_URL","Url non raggiungibile:");
define("_ZOOM_A_MESS_PARSE_URL","Ricerca in \"%s\" per immagini... "); // %s = $url
define("_ZOOM_A_MESS_NOJAVA","Se vedi sopra solo una casella grigia o hai problemi con l&#146;invio, potrebbe essere che<br/>non hai l&#146;ultima java run-time installata. Vai su <a href=\"http://www.java.com\" target=\"_blank\">Java.com</a> <br /> e scarica l&#146;ultima versione.");
define("_ZOOM_SETTINGS","Impostazioni");
define("_ZOOM_SETTINGS_DESCR","modifica e vedi qui tutte le impostazioni disponibili.");
define("_ZOOM_SETTINGS_TAB1","Sistema");
define("_ZOOM_SETTINGS_TAB2","Media");
define("_ZOOM_SETTINGS_TAB3","Funzionalit&#224;");
define("_ZOOM_SETTINGS_TAB4","Aspetto");
define("_ZOOM_SETTINGS_TAB5","Filigrane");
define("_ZOOM_SETTINGS_TAB6","Modo sicuro");
define("_ZOOM_SETTINGS_TAB7","Accessibilit&#224;");
define("_ZOOM_SETTINGS_TAB8","Azzeramento");
define("_ZOOM_SETTINGS_ERASE","Clicca per eliminare i dati delle gallerie zoom e iniziare da capo. Questo elimina le impostazioni e tutte le immagini.");
define("_ZOOM_SETTINGS_CONVTYPE","Tipo conversione");
define("_ZOOM_SETTINGS_GALLERY_FEATURES","Vedi Funzionalit&#224; Galleria");
define("_ZOOM_SETTINGS_VIEW_FEATURES","Vedi Funzionalit&#224; Media");

define("_ZOOM_SETTINGS_GALLERY","Vedi Galleria");
define("_ZOOM_SETTINGS_VIEW","Vedi Media");

define("_ZOOM_SETTINGS_GENERAL_FEATURES","Funzionalit&#224; Generali");
define("_ZOOM_SETTINGS_AUTODET","auto-rilevato: ");
define("_ZOOM_SETTINGS_IMGPATH","Percorso ai file media:");
define("_ZOOM_SETTINGS_TTIMGPATH","Il percorso ai media &eacute; ");
define("_ZOOM_SETTINGS_CONVSETTINGS","Impostazioni conversione immagini:");
define("_ZOOM_SETTINGS_IMPATH","Percorso a ImageMagick: ");
define("_ZOOM_SETTINGS_NETPBMPATH"," o NetPBM: ");
define("_ZOOM_SETTINGS_FFMPEGPATH","Percorso a FFmpeg");
define("_ZOOM_SETTINGS_FFMPEGTOOLTIP","FFmpeg &#232; richiesto per creare le miniature dei tuoi filmati.<br />Le estensioni gestite sono: ");
define("_ZOOM_SETTINGS_OVERRIDE_FFMPEG","Usa FFmpeg, sebbene zOOm non sia in grado di verificarne la presenza in questo sistema.");
define("_ZOOM_SETTINGS_PDFTOTEXTPATH","Percorso a PDFtoText");
define("_ZOOM_SETTINGS_XPDFTOOLTIP","pdf2text, che fa parte del pacchetto Xpdf, &#232; richiesto per indicizzare i file PDF.");
define("_ZOOM_SETTINGS_OVERRIDE_PDF","Usa PDFtoText, sebbene zOOm non sia in grado di verificarne la presenza in questo sistema.");
define("_ZOOM_SETTINGS_MAXSIZE","Massima dimensione immagine: ");
define("_ZOOM_SETTINGS_MAXSIZEKB","Media - immagine inclusa - massima dimensione (in kB): "); //added: 08-08-2006
define("_ZOOM_SETTINGS_MAXSIZEKB_WARNING","Il limite di caricamento di questo server, impostato dal tuo ISP come parte della configurazione di PHP, &#232; %s.<br />Quindi, impostare un limite pi&#249; alto di questo valore non ha senso!"); //added: 09-01-2006
define("_ZOOM_SETTINGS_THUMBSETTINGS","Impostazioni miniatura:");
define("_ZOOM_SETTINGS_QUALITY","Qualit&#224; NetPBM e GD2 JPEG: ");
define("_ZOOM_SETTINGS_SIZE","max dimensione miniatura: ");
define("_ZOOM_SETTINGS_TEMPNAME","Nome provvisorio: ");
define("_ZOOM_SETTINGS_AUTONUMBER","numerazione automatica dei nomi immagine (es. 1,2,3)");
define("_ZOOM_SETTINGS_TEMPDESCR","Descrizione provvisoria: ");
define("_ZOOM_SETTINGS_TITLE","Titolo Galleria:");
define("_ZOOM_SETTINGS_SUBCATSPG","Numero di colonne delle (sotto-)gallerie ");
define("_ZOOM_SETTINGS_COLUMNS","Numero di colonne delle miniature");
define("_ZOOM_SETTINGS_THUMBSPG","Miniature per pagina");
define("_ZOOM_SETTINGS_CMTLENGTH","Lunghezza massima commenti");
define("_ZOOM_SETTINGS_CHARS","caratteri");
define("_ZOOM_SETTINGS_GALLERYPREFIX","Prefisso titolo-galleria");
define("_ZOOM_SETTINGS_SHOWOCCSPACE","Mostra lo spazio occupato nel Gestore Media");
define("_ZOOM_SETTINGS_TEMPLATE_TITLE","Template");
define("_ZOOM_SETTINGS_FEATURES_TITLE","Funzionalit&#224; SI/NO");
define("_ZOOM_SETTINGS_CSS_TITLE","Modifica Foglio di Stile");
define("_ZOOM_SETTINGS_DISPLAY_TITLE","Dati da mostrare SI/NO");
define("_ZOOM_SETTINGS_TEMPLATE_CHOOSE","Scegli un template per definire l&#146;aspetto della tua galleria");
define("_ZOOM_SETTINGS_TEMPLATE_TABLES","Classica (con tabelle)");
define("_ZOOM_SETTINGS_TEMPLATE_TABLELESS","Moderna (design senza tabelle)");
define("_ZOOM_SETTINGS_COMMENTS","Commenti");
define("_ZOOM_SETTINGS_POPUP","Popup Media");
define("_ZOOM_SETTINGS_CATIMG","Mostra immagine galleria");
define("_ZOOM_SETTINGS_SLIDESHOW","Presentazione");
define("_ZOOM_SETTINGS_ZOOMLOGO","Mostra zOOm-logo");
define("_ZOOM_SETTINGS_DESCRINGAL","Mostra la descrizione album nella galleria");

define("_ZOOM_SETTINGS_SHOWHITS","Mostra numero visite");
define("_ZOOM_SETTINGS_READEXIF","Leggi dati EXIF");
define("_ZOOM_SETTINGS_EXIFTOOLTIP","Questa opzione mostrer&#224; ulteriori informazioni EXIF e altri dati IPTC, senza bisogno di installare il modulo EXIF per PHP nel sistema.");
define("_ZOOM_SETTINGS_READID3","Leggi dati mp3 ID3");
define("_ZOOM_SETTINGS_ID3TOOLTIP","Questa opzione mostrer&#224; ulteriori informazioni ID3 v1.1 e v2.0 durante la visualizzazione dettagli di un file mp3.");
define("_ZOOM_SETTINGS_RATING","Valutazione");
define("_ZOOM_SETTINGS_CSS","Finestra popup");
define("_ZOOM_SETTINGS_CSSZOOM","Vista zOOm gallery e media");
define("_ZOOM_SETTINGS_SUCCESS","Configurazione aggiornata correttamente!");
define("_ZOOM_SETTINGS_ZOOMING","Zoom immagine");
define("_ZOOM_SETTINGS_ORDERBY","Ordinamento miniature; ordinate per");
define("_ZOOM_SETTINGS_CATORDERBY","Ordinamento (sub-)Gallery; ordinate per");

define("_ZOOM_SETTINGS_NO_POP","Disattiva tutti i Popup");
define("_ZOOM_SETTINGS_STANDARD_POP","Popup Standard");
define("_ZOOM_SETTINGS_LIGHTBOX_POP","Popup Raccoglitore JS");
define("_ZOOM_SETTINGS_LIGHTBOX_SLIDESHOW","<strong><i>ATTIVA QUESTO SE VUOI CHE LE ANTEPRIME LAVORINO CON IL RACCOGLITORE JS</i></strong>");

define("_ZOOM_SETTINGS_DATE_ASC","DATA, ascendente");
define("_ZOOM_SETTINGS_DATE_DESC","DATA, discendente");
define("_ZOOM_SETTINGS_FLNM_ASC","NOMEFILE, ascendente");
define("_ZOOM_SETTINGS_FLNM_DESC","NOMEFILE, discendente");
define("_ZOOM_SETTINGS_NAME_ASC","NOME, ascendente");
define("_ZOOM_SETTINGS_NAME_DESC","NOME, discendente");
define("_ZOOM_SETTINGS_LBTOOLTIP","Una raccoglitore &#232; come un contenitore riempito con le immagini scelte dall&#146;utente, che possono essere scaricate come file ZIP.");
define("_ZOOM_SETTINGS_SHOWNAME","Mostra nome");
define("_ZOOM_SETTINGS_SHOWDESCR","Mostra descrizione");
define("_ZOOM_SETTINGS_SHOWKEYWORDS","Mostra parole chiave");
define("_ZOOM_SETTINGS_SHOWDATE","Mostra data");
define("_ZOOM_SETTINGS_SHOWUNAME","Mostra nome utente");
define("_ZOOM_SETTINGS_SHOWFILENAME","Mostra nome file");
define("_ZOOM_SETTINGS_METABOX","Mostra riquadri fluttuanti con i dettagli nelle pagine della galleria");
define("_ZOOM_SETTINGS_METABOXTOOLTIP","Deseleziona questa funzionalit&#224; per migliorare la velocit&#224; della tua galleria. Funziona bene per grandi basi di dati.");
define("_ZOOM_SETTINGS_ECARDS","E-cards");
define("_ZOOM_SETTINGS_ECARDS_LIFETIME","durata E-cards");
define("_ZOOM_SETTINGS_ECARDS_ONEWEEK","una settimana");
define("_ZOOM_SETTINGS_ECARDS_TWOWEEKS","due settimane");
define("_ZOOM_SETTINGS_ECARDS_ONEMONTH","un mese");
define("_ZOOM_SETTINGS_ECARDS_THREEMONTHS","tre mesi");
define("_ZOOM_SETTINGS_SHOWSEARCH","Campo-ricerca su TUTTE le pagine");
define("_ZOOM_SETTINGS_BOX_ANIMATE","Riquadri animati");
define("_ZOOM_SETTINGS_BOX_PROPERTIES","Riquadro Propriet&#224;");
define("_ZOOM_SETTINGS_BOX_META","Riquadro Metadata");
define("_ZOOM_SETTINGS_BOX_COMMENTS","Riquadro Commenti");
define("_ZOOM_SETTINGS_BOX_RATING","Riquadro Valutazione");
define("_ZOOM_SETTINGS_TOPTEN","Mostra collegamento \"Pi&#249; visitati\" nella pagina principale");
define("_ZOOM_SETTINGS_LASTSUBM","Mostra collegamento \"Ultimi inserimenti\" nella pagina principale");
define("_ZOOM_SETTINGS_SETMENUOPTION","Mostra collegamento \"Carica Media\" in User Menu");
define("_ZOOM_SETTINGS_USEFTP","Usa FTP?");
define("_ZOOM_SETTINGS_FTPHOST","Nome host");
define("_ZOOM_SETTINGS_FTPUNAME","Username");
define("_ZOOM_SETTINGS_FTPPASS","Password");
define("_ZOOM_SETTINGS_FTPWARNING","Attenzione: la Password non è al sicuro!");
define("_ZOOM_SETTINGS_FTPHOSTDIR","Cartella dell&#146;host");
define("_ZOOM_SETTINGS_MESS_FTPHOSTDIR","Per favore fornisci qui il percorso a Joomla della cartella base ftp. IMPORTANTE: Termina <b>senza</b> la barra o la barra rovesciata!");
define("_ZOOM_SETTINGS_GROUP","Gruppo");
define("_ZOOM_SETTINGS_PRIV_DESCR","Puoi cambiare i permessi di ogni gruppo utenti conosciuto in Joomla e quindi impostare i privilegi per
    ogni utente di quel gruppo!<br />
    Come utente potresti, in teoria, fare queste operazioni: caricare file, modificare/eliminare media, creare/ modificare/ eliminare gallerie (condivise).<br />
    Cosa possono e non possono fare quindi &#232; una tua scelta.");
define("_ZOOM_SETTINGS_CLOSE","Mostra collegamento \"Chiudi\" nel popup");
define("_ZOOM_SETTINGS_MAINSCREEN","Mostra collegamento alla pagina principale nel percorso di navigazione");
define("_ZOOM_SETTINGS_NAVBUTTONS","Mostra Pulsanti Navigazione nel popup");
define("_ZOOM_SETTINGS_PROPERTIES","Mostra Propriet&#224; sotto i media");
define("_ZOOM_SETTINGS_MEDIAFOUND","Mostra testo \"Media Trovati\" nelle gallerie");
define("_ZOOM_SETTINGS_ANONYMOUS_COMMENTS","Permetti commenti anonimi");
define("_ZOOM_SETTINGS_WM_ENABLE_TITLE","Abilita funzione");
define("_ZOOM_SETTINGS_WM_TITLE", "Tue filigrane");
define("_ZOOM_SETTINGS_WM_DESCR", "La filigrana digitale appare in cima alle tue immagini su questo sito. "
 . "Le immagini saranno ancora visibili, ma i visitatori non saranno tentati a copiarle o stamparle."
 . "<br /><br />Suggerimento: puoi usare il logo della tua società come filigrana digitale. "
 . "Assicurati di aver impostato lo sfondo della filigrana digitale per essere trasparente!");
define("_ZOOM_SETTINGS_WM_IMG", "Immagine");
define("_ZOOM_SETTINGS_WM_NOWATERMARKS", "Nessuna filigrana trovata. Puoi caricare una nuova filigrana in basso.");
define("_ZOOM_SETTINGS_WM_PLACEMENT_TITLE", "Posizionamento");
define("_ZOOM_SETTINGS_WM_PLACEMENT_DESCR", "Puoi definire la posizione della filigrana digitale sulla immagine-destinazione tramite "
 . "scegliendo una delle posizioni nel contenitore in basso.");
define("_ZOOM_SETTINGS_WM_FILE","Carica filigrana");
define("_ZOOM_SETTINGS_WM_UPLOAD_SUCCESS","Filigrana caricata correttamente!");
define("_ZOOM_SETTINGS_WM_UPLOAD_FAIL","Caricamento filigrana non riuscito.");
define("_ZOOM_SETTINGS_WM_DEL_SUCCESS","Filigrana eliminata correttamente!");
define("_ZOOM_SETTINGS_WM_DEL_FAIL","La filigrana non pu&#242; essere eliminata.");
define("_ZOOM_SYSTEM_TITLE","Configurazione Sistema");
define("_ZOOM_YES","s&#236;");
define("_ZOOM_NO","no");
define("_ZOOM_VISIBLE","visibile");
define("_ZOOM_HIDDEN","nascosto");
define("_ZOOM_SAVE","Registra");
define("_ZOOM_MOVEFILES","Sposta media");
define("_ZOOM_BUTTON_MOVE","Sposta");
define("_ZOOM_MOVEFILES_STEP1","Scegli la galleria di destinazione e sposta i media");
define("_ZOOM_ALERT_MOVE","%s media spostati correttamente, %s media non possono essere spostati.");
define("_ZOOM_OPTIMIZE","Ottimizza tabelle");
define("_ZOOM_OPTIMIZE_DESCR","zOOm Media Gallery usa molto le sue tabelle e quindi crea dati in eccesso, per es. 'scarti'. Clicca quì per eliminare questi scarti.");
define("_ZOOM_OPTIMIZE_SUCCESS","Tabelle zOOm Media Gallery ottimizzate!");
define("_ZOOM_UPDATE","Aggiorna zOOm Media Gallery");
define("_ZOOM_UPDATE_DESCR","aggiungi nuove funzionilit&#224;, risolvi problemi ed errori! Controlla <a href=\"http://www.zoomfactory.org\" target=\"_blank\">www.zoomfactory.org</a> per gli ultimi aggiornamenti!");
define("_ZOOM_UPDATE_XMLDATE","Data dell&#146;ultimo aggiornamento");
define("_ZOOM_UPDATE_NOUPDATES","non ci sono aggiornamenti!"); // added 11-08
define("_ZOOM_UPDATE_PACKAGE","Aggiorna ZIP-file: ");
define("_ZOOM_CREDITS","Informazioni su zOOm Media Gallery e Crediti");

//Image actions
define("_ZOOM_DISKSPACEUSAGE","Spazio disco %s in uso");
define("_ZOOM_UPLOAD_SINGLE","singolo (o ZIP)");
define("_ZOOM_UPLOAD_MULTIPLE","file multipli");
define("_ZOOM_UPLOAD_DRAGNDROP","trascinamento");
define("_ZOOM_UPLOAD_SCANDIR","cerca percorso");
define("_ZOOM_UPLOAD_INTRO","Premi il pulsante <b>Sfoglia</b> per individuare il media da caricare.");
define("_ZOOM_UPLOAD_STEP1","1. Scegli il numero di file che vuoi caricare: ");
define("_ZOOM_UPLOAD_STEP2","2. Scegli la galleria dove inserire le foto: ");
define("_ZOOM_UPLOAD_STEP3","3. Usa il pulsante Sfoglia per cercare le foto nel tuo computer");
define("_ZOOM_SCAN_STEP1","Passo 1: indica un percorso per cercare i media...");
define("_ZOOM_SCAN_STEP2","Passo 2: scegli i file che vuoi inviare...");
define("_ZOOM_SCAN_STEP3","Passo 3: zOOm elabora i file che hai scelto...");
define("_ZOOM_SCAN_STEP1_DESCR","Il percorso pu&#242; essere un URL o una cartella sul server.<br />&nbsp;   Suggerimento: invia i media con FTP ad una cartella del tuo server e poi inserisci il suo percorso qui!");
define("_ZOOM_SCAN_STEP2_DESCR1","Elaborazione");
define("_ZOOM_SCAN_STEP2_DESCR2","come cartella locale");
define("_ZOOM_FORMCREATE_NAME","Nome");
define("_ZOOM_FORM_IMAGEFILE","Media");
define("_ZOOM_FORM_IMAGEFILTER","Tipi-media supportati");
define("_ZOOM_FORM_INGALLERY","Nella galleria");
define("_ZOOM_FORM_SETFILENAME","Imposta per i media i nomi originali dei file.");
define("_ZOOM_FORM_IGNORESIZES","Ignora le dimensioni massime preimpostate"); //added: 12-08
define("_ZOOM_FORM_LOCATION","Origine");
define("_ZOOM_BUTTON_SCAN","Inserisci una cartella o un URL");
define("_ZOOM_BUTTON_UPLOAD","Carica");
define("_ZOOM_BUTTON_EDIT","Modifica");
define("_ZOOM_BUTTON_CREATE","Crea");
define("_ZOOM_CONFIRM_WIPE","ATTENZIONE!\\n Usando questa funzione distruggerai completamente la tua zOOm gallery ed eliminer&#224; tutte le immagini e le gallerie.\\n\\n Sei sicuro di voler continuare?");
define("_ZOOM_CONFIRM_DEL","Questa opzione eliminer&#224; completamente una galleria, compresi i media contenuti!\\nSei sicuro di voler procedere?");
define("_ZOOM_CONFIRM_DELMEDIUM","Stai per eliminare definitivamente questi media!\\nSei sicuro di voler procedere?");
define("_ZOOM_ALERT_DEL","La galleria &#232; stata eliminata!");
define("_ZOOM_ALERT_NOCAT","Nessuna galleria selezionata!");
define("_ZOOM_ALERT_NOMEDIA","Nessun media selezionato!");
define("_ZOOM_ALERT_EDITOK","I campi della galleria sono stati modificati correttamente!");
define("_ZOOM_ALERT_NEWGALLERY","Nuova galleria creata.");
define("_ZOOM_ALERT_NONEWGALLERY","Galleria non creata!");
define("_ZOOM_ALERT_EDITIMG","Propriet&#224; del media modificate correttamente.");
define("_ZOOM_ALERT_DELPIC","Media eliminato correttamente.");
define("_ZOOM_ALERT_NODELPIC","Il media non pu&#242; essere eliminato!");
define("_ZOOM_ALERT_MOVEFAILURE","Il media non pu&#242; essere spostato."); //added: 08-08-2006
define("_ZOOM_ALERT_NOPICSELECTED","Nessun media selezionato.");
define("_ZOOM_ALERT_NOPICSELECTED_MULT","Nessun media selezionato.");
define("_ZOOM_ALERT_UPLOADOK","Media caricato correttamente!");
define("_ZOOM_ALERT_UPLOADSOK","media caricati correttamente!");
define("_ZOOM_ALERT_WRONGFORMAT","Formato immagine errato.");
define("_ZOOM_ALERT_WRONGFORMAT_MULT","Formato errato.");
define("_ZOOM_ALERT_TOOBIG","La dimensione del file &#232; troppo grande; %s &#232; il limite concesso."); //added 08-08-2006
define("_ZOOM_ALERT_IMGERROR","Si &#232; verificato un errore durante il ridimensionamento dell&#146;immagine o la creazione della miniatura.");
define("_ZOOM_ALERT_PCLZIPERROR","Si &#232; verificato un errore durante l&#146;estrazione dell&#146;archivio.");
define("_ZOOM_ALERT_INDEXERROR","Si &#232; verificato un errore durante l&#146;indicizzazione del documento.");
define("_ZOOM_ALERT_WATERMARKERROR","Si &#232; verificato un errore durante l&#146; della filigrana all&#146;immagine.");
define("_ZOOM_ALERT_IMGFOUND","immagini trovate.");
define("_ZOOM_INFO_CHECKCAT","Per favore scegli una galleria prima di premere il pulsante di invio!");
define("_ZOOM_BUTTON_ADDIMAGES","Aggiungi media");
define("_ZOOM_BUTTON_REMIMAGES","Elimina media");
define("_ZOOM_INFO_PROCESSING","Elaborazione file:");
define("_ZOOM_ITEMEDIT_TAB1","Propriet&#224;");
define("_ZOOM_ITEMEDIT_TAB2","Membri");
define("_ZOOM_ITEMEDIT_TAB3","Azioni");
define("_ZOOM_USERSLIST_LINE1",">>Scegli membri per questo oggetto<<");
define("_ZOOM_USERSLIST_ALLOWALL",">>Accesso pubblico<<");
define("_ZOOM_USERSLIST_MEMBERSONLY",">>Solo membri<<");
define("_ZOOM_PUBLISHED","Pubblicata");
define("_ZOOM_SHARED","Condivisa");
define("_ZOOM_ROTATE","Ruota immagine di 90 gradi");
define("_ZOOM_CLOCKWISE","senso orario");
define("_ZOOM_CCLOCKWISE","senso antiorario");
define("_ZOOM_FLIP_HORIZ","Inverti immagine orizzontalmente");
define("_ZOOM_FLIP_VERT","Inverti immagine verticalmente");
define("_ZOOM_PROGRESS_DESCR","La tua richiesta &eacute; in esecuzione... per favore attendi.");

//Navigation (including Slideshow buttons)
define("_ZOOM_SLIDESHOW","Presentazione:");
define("_ZOOM_PREV_IMG","media precedente");
define("_ZOOM_NEXT_IMG","media successivo");
define("_ZOOM_FIRST_IMG","primo media");
define("_ZOOM_LAST_IMG","ultimo media");
define("_ZOOM_PLAY","riproduci");
define("_ZOOM_STOP","ferma");
define("_ZOOM_RESET","azzera");
define("_ZOOM_FIRST","Primo");
define("_ZOOM_LAST","Ultimo");
define("_ZOOM_PREVIOUS","Precedente");
define("_ZOOM_NEXT","Successivo");
define("_ZOOM_IN_DESC", "Porta il mouse sull&#146;immagine e usa i tasti freccia per modificare lo zoom.");

//Gallery actions
define("_ZOOM_SEARCH_BOX","Cerca in questa galleria");
define("_ZOOM_ADVANCED_SEARCH","Ricerca avanzata");
define("_ZOOM_SEARCH_KEYWORD","Ricerca per parola chiave");
define("_ZOOM_IMAGES","media");
define("_ZOOM_IMGFOUND","%s media trovati - sei alla pagina %s di %s");
define("_ZOOM_SUBGALLERIES","sub-gallerie");
define("_ZOOM_ALERT_COMMENTOK","Il tuo commento è stato aggiunto!");
define("_ZOOM_ALERT_COMMENTERROR","Hai già commentato questa foto!");
define("_ZOOM_ALERT_VOTE_OK","Il tuo voto &#232; stato conteggiato, grazie.");
define("_ZOOM_ALERT_VOTE_ERROR","Hai gi&#224; votato per questa foto!");
define("_ZOOM_WINDOW_CLOSE","Chiudi");
define("_ZOOM_NOPICS","Nessun media nella galleria");
define("_ZOOM_PROPERTIES","Propriet&#224;");
define("_ZOOM_COMMENTS","Commenti");
define("_ZOOM_COMMENTS_INTRO","Inserisci il tuo commento Enter your comments below:");

define("_ZOOM_NO_COMMENTS","Nessun commento ancora aggiunto.");
define("_ZOOM_SAYS","dice");
define("_ZOOM_YOUR_NAME","Nome");
define("_ZOOM_ADD","Aggiungi");
define("_ZOOM_NAME","Nome");
define("_ZOOM_DATE","Data");
define("_ZOOM_UNAME","Aggiunto da");
define("_ZOOM_DESCRIPTION","Descrizione");
define("_ZOOM_IMGNAME","Nome");
define("_ZOOM_FILENAME","Nome file");
define("_ZOOM_CLICKDOCUMENT","(clicca il nome file per aprire il documento)");
define("_ZOOM_KEYWORDS","Parole chiave");
define("_ZOOM_HITS","visite");
define("_ZOOM_CLOSE","Chiudi");
define("_ZOOM_NOIMG", "Nessun media trovato!");
define("_ZOOM_NONAME", "Devi inserire un nome!");
define("_ZOOM_NOCAT", "Nessuna galleria selezionata!");
define("_ZOOM_EDITPIC", "Modifica Media");
define("_ZOOM_SETCATIMG","Imposta come Immagine Galleria");
define("_ZOOM_SETPARENTIMG","Imposta come immagine per la galleria padre");
define("_ZOOM_PASS","Password");
define("_ZOOM_PASS_REQUIRED","Questa galleria richiede una password.<br />Perfavore compila il campo Password<br />e premi il pulsante Vai. Grazie.");
define("_ZOOM_PASS_BUTTON","Vai");
define("_ZOOM_PASS_GALLERY","Password");
define("_ZOOM_PASS_INNCORRECT","Password Errata");

//Hotlinking
define("_ZOOM_SETTINGS_HOTLINK","Abilita Collegamento Immagine Protetto");
define("_ZOOM_SETTINGS_HPTOOLTIP","Quando Collegamento Protetto &#232; abilitato, i nomi dei file immagine e i percorsi saranno nascosti. Altres&#236;, se un utente tenta di usare l&#146;immagine su un altro sito, essa non apparir&#224;.");


//Lightbox
define("_ZOOM_LIGHTBOX","Raccoglitore");
define("_ZOOM_LIGHTBOX_GALLERY","Metti nel Raccoglitore questa galleria!");
define("_ZOOM_LIGHTBOX_ITEM","Metti nel Raccoglitore questo oggetto!");
define("_ZOOM_LIGHTBOX_VIEW","Vedi il tuo Raccoglitore");
define("_ZOOM_YOUR_LIGHTBOX","Il tuo Raccoglitore contiene:");
define("_ZOOM_LIGHTBOX_EMPTY","Il tuo Raccoglitore &eacute; vuoto.");
define("_ZOOM_LIGHTBOX_ZIPBTN","Crea file-ZIP");
define("_ZOOM_LIGHTBOX_PLAYLISTBTN","Crea Playlist e riproduci");
define("_ZOOM_LIGHTBOX_CATS","Gallerie");
define("_ZOOM_LIGHTBOX_TITLEDESCR","Titolo e Descrizione");
define("_ZOOM_ACTION","Azione");
define("_ZOOM_LIGHTBOX_ADDED","Oggetto aggiunto al tuo raccoglitore!");
define("_ZOOM_LIGHTBOX_NOTADDED","Questo oggetto &#232; gi&#224; stato aggiunto al tuo raccoglitore!");
define("_ZOOM_LIGHTBOX_EDITED","Oggetto modificato!");
define("_ZOOM_LIGHTBOX_NOTEDITED","Errore durante la modifica dell&#146;oggetto!");
define("_ZOOM_LIGHTBOX_DEL","Oggetto rimosso correttamente dal tuo raccoglitore!");
define("_ZOOM_LIGHTBOX_NOTDEL","Errore rimuovendo l&#146;oggetto dal tuo raccoglitore!");
define("_ZOOM_LIGHTBOX_NOZIP","Hai gi&#224; creato un file Zip del tuo raccoglitore oppure il tuo raccoglitore &#232; vuoto!");
define("_ZOOM_LIGHTBOX_PARSEZIP","Ricerca di immagini nella galleria...");
define("_ZOOM_LIGHTBOX_DOZIP","sto creando l&#146;archivio ZIP...");
define("_ZOOM_LIGHTBOX_DLHERE","Adesso puoi scaricare il raccoglitore");
define("_ZOOM_LIGHTBOX_PLSUCCESS","Playlist creata correttamente! Devi aggiornare la finestra Player.");
define("_ZOOM_LIGHTBOX_PLERROR","Errore creando la Playlist.");
define("_ZOOM_LIGHTBOX_NOAUDIO","Devi prima aggiungere file audio al tuo raccoglitore!");
define("_ZOOM_LIGHTBOX_NOITEMS","Il tuo raccoglitore sembra essere vuoto.");

//EXIF information
define("_ZOOM_EXIF","EXIF");
define("_ZOOM_EXIF_SHOWHIDE","Mostra/nascondi Metadata");

//MP3 id3 v1.1 or later information
define("_ZOOM_AUDIO_PLAYING","in riproduzione:");
define("_ZOOM_AUDIO_CLICKTOPLAY","Clicca per eseguire questo file.");
define("_ZOOM_ID3","ID3");
define("_ZOOM_ID3_SHOWHIDE","Mostra/ nascondi dati ID3-tag");
define("_ZOOM_ID3_LENGTH","Lunghezza");
define("_ZOOM_ID3_QUALITY","Qualit&#224;");
define("_ZOOM_ID3_TITLE","Titolo");
define("_ZOOM_ID3_ARTIST","Artista");
define("_ZOOM_ID3_ALBUM","Album");
define("_ZOOM_ID3_YEAR","Anno");
define("_ZOOM_ID3_COMMENT","Commento");
define("_ZOOM_ID3_GENRE","Genere");

//Video metadata information
define("_ZOOM_VIDEO_SHOWHIDE","Mostra/nascondi dati Video");
define("_ZOOM_VIDEO_PIXELRATIO","Rapporto Pixel");
define("_ZOOM_VIDEO_QUALITY","Qualit&#224; video");
define("_ZOOM_VIDEO_AUDIOQUALITY","Qualit&#224; audio");
define("_ZOOM_VIDEO_CODEC","Codec");
define("_ZOOM_VIDEO_RESOLUTION","Risoluzione");

//rating
define("_ZOOM_RATING","Valutazione:");
define("_ZOOM_NOTRATED","Ancora non espressa!");
define("_ZOOM_VOTE","voto");
define("_ZOOM_VOTES","voti");
define("_ZOOM_RATE0","spazzatura");
define("_ZOOM_RATE1","scarso");
define("_ZOOM_RATE2","medio");
define("_ZOOM_RATE3","buono");
define("_ZOOM_RATE4","ottimo");
define("_ZOOM_RATE5","eccellente!");

//special
define("_ZOOM_TOPTEN","Pi&#249; visitati");
define("_ZOOM_LASTSUBM","Ultimi inserimenti");
define("_ZOOM_LASTCOMM","Ultimi commentati");
define("_ZOOM_SEARCHRESULTS","Risultati ricerca");
define("_ZOOM_TOPRATED","Pi&#249; votati");

//ecard
define("_ZOOM_ECARD_SENDAS","Spedisci questo media come eCard ad un amico!");
define("_ZOOM_ECARD_YOURNAME","Il tuo nome");
define("_ZOOM_ECARD_YOUREMAIL","Il tuo indirizzo email");
define("_ZOOM_ECARD_FRIENDSNAME","Nome del tuo amico");
define("_ZOOM_ECARD_FRIENDSEMAIL","Indirizzo email del tuo amico");
define("_ZOOM_ECARD_MESSAGE","Messaggio");
define("_ZOOM_ECARD_SENDCARD","Spedisci eCard");
define("_ZOOM_ECARD_SUCCESS","La tua eCard è stata spedita correttamente.");
define("_ZOOM_ECARD_CLICKHERE","Clicca qui per vederla!");
define("_ZOOM_ECARD_ERROR","Errore invio eCard a");
define("_ZOOM_ECARD_TURN","Guarda il retro di questa cartolina!");
define("_ZOOM_ECARD_TURN2","Guarda il fronte di questa cartolina!");
define("_ZOOM_ECARD_SENDER","Spedita a te da:");
define("_ZOOM_ECARD_SUBJ","Hai ricevuto una eCard da:");
define("_ZOOM_ECARD_MSG1","ti ha inviato una eCard da");
define("_ZOOM_ECARD_MSG2","Clicca sul collegamento in basso per vedere la tua eCard personale!");
define("_ZOOM_ECARD_MSG3","Non rispondere a questa email di notifica perché è stata inviata automaticamente.");
define("_ZOOM_ECARD_ECARDEXPIRED","Spiacente, questa eCard non &#232; pi&#249; disponibile o &#232; scaduta.");

//installation-screen
define ("_ZOOM_INSTALL_CREATE_DIR","L&#146;installazione di zOOm sta provando a creare la cartella immagini 'images/zoom' ...");
define ("_ZOOM_INSTALL_CREATE_DIR_WM","L&#146;installazione di zOOm sta provando a creare la cartella immagini 'images/zoom/watermarks' ...");
define ("_ZOOM_INSTALL_CREATE_DIR_SUCC","fatto!");
define ("_ZOOM_INSTALL_CREATE_DIR_FAIL","fallito!");
define ("_ZOOM_INSTALL_CREATE_DBASE_SUCC","Base di dati creata correttamente!");
define ("_ZOOM_INSTALL_UPGRADED_DBASE_SUCC","Base di dati aggiornata correttamente!");
define ("_ZOOM_INSTALL_MESS1","zOOm Image Gallery installato correttamente.<br>Adesso puoi popolare le tue gallerie!");
define ("_ZOOM_INSTALL_MESS2","NOTA: la prima cosa che dovresti fare adesso, &#232; andare al menu componenti qui sopra, <br>cercare la voce 'zOOm Media Gallery Admin', cliccarla e<br>controllare la pagina delle impostazioni impostazioni nel Sistema di Amministrazione.");
define ("_ZOOM_INSTALL_MESS3","Qui puoi cambiare tutte le variabili per adattare zOOm alla tua configurazione.");
define ("_ZOOM_INSTALL_MESS4","Non dimenticare di creare una galleria e sei sulla strada giusta!");
define ("_ZOOM_INSTALL_MESS_FAIL1","zOOm Gallery non pu&#242; essere installato correttamente!");
define ("_ZOOM_INSTALL_MESS_FAIL2","Dopo la creazione delle seguenti cartelle, i permessi devono essere impostati a '0777':<br />"
. "'images/zoom'<br />"
. "'/components/com_zoom/etc'<br />"
. "'/components/com_zoom/lib'<br />"
. "'/components/com_zoom/lib/js'<br />"
. "'/components/com_zoom/lib/language'<br />"
. "'/components/com_zoom/www'<br />"
. "'/components/com_zoom/www/admin'<br />"
. "'/components/com_zoom/www/images'<br />"
. "'/components/com_zoom/www/images/admin'<br />"
. "'/components/com_zoom/www/images/blocks'<br />"
. "'/components/com_zoom/www/images/filetypes'<br />"
. "'/components/com_zoom/www/images/rating'<br />"
. "'/components/com_zoom/www/images/smilies'<br />"
. "'/components/com_zoom/www/tabs'");
define ("_ZOOM_INSTALL_MESS_FAIL3","Una volta create queste cartelle e modificati i permessi di accesso, vai a <br /> 'Componenti -> zOOm Media Gallery' e personalizza la configurazione.");
?>