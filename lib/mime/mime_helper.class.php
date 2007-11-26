<?php
/**
 * @version $Revision: 1.4 $ $Date: 2006/11/26 04:18:22 $
 * @package zOOmGallery
 * @subpackage MIME
 * @author Bharat Mediratta <bharat@menalto.com>
 * @author Mike de Boer <mike@zoomfactory.org>
 */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * A collection of useful mime type related utilities
 *
 * @static
 */
class MIME_Helper {
    /**
     * Return a singleton copy of a map of file extensions to mime types
     *
     * @return array
     * @access private
     * @static
     * @staticvar array extensionMap Maps file extentions to equivalent MIME-types
     */
    function &_getExtensionMap() {
        static $extensionMap;
        
        if (!isset($extensionMap)) {
            /* This data was lifted from Apache's mime.types listing. */
            $extensionMap['forward'] = array(
                "3gp"     => array("video/3gpp", "audio/3gpp"),
                "ai"      => array("application/postscript"),
                "aif"     => array("audio/aiff", "audio/x-aiff", "sound/aiff", "audio/rmf", "audio/x-rmf", "audio/x-pn-aiff", "audio/x-gsm", "audio/x-midi", "audio/vnd.qcelp"),
                "aifc"    => array("audio/aiff", "audio/x-aiff", "audio/x-aifc", "sound/aiff", "audio/rmf", "audio/x-rmf", "audio/x-pn-aiff", "audio/x-gsm", "audio/x-midi", "audio/mid", "audio/vnd.qcelp"),
                "aiff"    => array("audio/aiff", "audio/x-aiff", "sound/aiff", "audio/rmf", "audio/x-rmf", "audio/x-pn-aiff", "audio/x-gsm", "audio/mid", "audio/x-midi", "audio/vnd.qcelp"),
                "asc"     => array("text/plain"),
                "asf"     => array("audio/asf", "application/asx", "video/x-ms-asf-plugin", "application/x-mplayer2", "video/x-ms-asf", "application/vnd.ms-asf", "video/x-ms-asf-plugin", "video/x-ms-wm", "video/x-ms-wmx"),
                "asx"     => array("video/asx", "application/asx", "video/x-ms-asf-plugin", "application/x-mplayer2", "video/x-ms-asf", "application/vnd.ms-asf", "video/x-ms-asf-plugin", "video/x-ms-wm", "video/x-ms-wmx", "video/x-la-asf"),
                "au"      => array("audio/basic", "audio/x-basic", "audio/au", "audio/x-au", "audio/x-pn-au", "audio/rmf", "audio/x-rmf", "audio/x-ulaw", "audio/vnd.qcelp", "audio/x-gsm", "audio/snd"),
                "avi"     => array("video/avi", "video/msvideo", "video/x-msvideo", "image/avi", "video/xmpg2", "application/x-troff-msvideo", "audio/aiff", "audio/avi"),
                "bak"     => array("application/x-trash"),
                "bat"     => array("application/bat", "application/x-bat", "application/x-msdos-program", "application/textedit", "application/octet-stream"),
                "bcpio"   => array("application/x-bcpio"),
                "bin"     => array("application/octet-stream", "application/bin", "application/binary", "application/x-msdownload"),
                "bmp"     => array("image/bmp", "image/x-bmp", "image/x-bitmap", "image/x-xbitmap", "image/x-win-bitmap", "image/x-windows-bmp", "image/ms-bmp", "image/x-ms-bmp", "application/bmp", "application/x-bmp", "application/x-win-bitmap", "application/preview"),
                "bz2"     => array("application/bzip2", "application/octet-stream", "application/x-bz2", "application/x-bzip", "application/x-compressed"),
                "cdf"     => array("application/x-netcdf"),
                "cdr"     => array("application/cdr", "application/coreldraw", "application/x-cdr", "application/x-coreldraw", "image/cdr", "image/x-cdr", "zz-application/zz-winassoc-cd"),
                "chm"     => array("application/octet-stream"),
                "chrt"    => array("application/x-kchart"),
                "class"   => array("application/octet-stream", "application/x-java", "application/java-byte-code", "application/x-java-class", "application/x-java-vm", "application/x-java-bean", "application/x-jinit-bean", "application/x-jinit-applet"),
                "com"     => array("application/x-msdos-program"),
                "cpio"    => array("application/x-cpio"),
                //"cpt"     => array("application/mac-compactpro", "image/bmp", "image/gif", "image/tiff"),
                "csh"     => array("application/x-csh"),
                "css"     => array("text/css", "application/css-stylesheet"),
                "csv"     => array("text/plain", "text/comma-separated-values", "text/csv", "application/csv", "application/excel", "application/vnd.ms-excel", "application/vnd.msexcel", "text/anytext"),
                "dcr"     => array("application/x-director"),
                "deb"     => array("application/x-debian-package"),
                "diff"    => array("text/plain"),
                "dir"     => array("application/x-director"),
                "dl"      => array("video/dl"),
                "dll"     => array("application/x-msdownload", "application/octet-stream", "application/x-msdos-program"),
                "dms"     => array("application/octet-stream"),
                "doc"     => array("application/msword", "application/doc", "appl/text","application/vnd.msword", "application/vnd.ms-word", "application/winword", "application/word", "application/x-msw6", "application/x-msword", "zz-application/zz-winassoc-doc"),
                "dot"     => array("application/msword", "application/dot", "application/x-dot", "application/doc", "application/microsoft_word", "application/mswor2c", "sc2", "application/x-msword", "zz-application/zz-winassoc-dot"),
                "dvi"     => array("application/x-dvi"),
                "dxr"     => array("application/x-director"),
                "eps"     => array("application/postscript", "application/eps", "application/x-eps", "image/eps", "image/x-eps"),
                "etx"     => array("text/x-setext", "text/anytext"),
                "exe"     => array("application/octet-stream","application/x-msdos-program"),
                "ez"      => array("application/andrew-inset", "application/x-ease"),
                "fla"     => array("application/octet-stream"),
                "fli"     => array("video/flc", "video/fli", "video/x-fli", "image/fli", "video/x-acad-anim"),
                "flv"     => array("video/x-flv"),
                "gif"     => array("image/gif", "image/x-xbitmap", "image/gi_"),
                "gl"      => array("video/gl"),
                "gsm"     => array("audio/x-gsm"),
                "gtar"    => array("application/x-gtar"),
                "gz"      => array("application/gzip", "application/x-gzip", "application/x-gunzip", "application/gzipped", "application/gzip-compressed", "application/x-compressed", "application/x-compress", "gzip/document", "application/octet-stream"),
                "hdf"     => array("application/x-hdf"),
                "hqx"     => array("application/binhex", "application/mac-binhex", "application/mac-binhex40", "application/octet-stream", "application/x-gzip", "application/x-stuffit", "application/x-tar", "application/x-winzip", "application/x-zip-compressed"),
                "htm"     => array("text/plain","text/html"),
                "html"    => array("text/plain","text/html"),
                "ice"     => array("x-conference/x-cooltalk"),
                "ico"     => array("image/ico", "image/x-icon", "application/ico", "application/x-ico", "application/x-win-bitmap", "image/x-win-bitmap", "application/octet-stream"),
                "ief"     => array("image/ief"),
                "iges"    => array("model/iges"),
                "igs"     => array("model/iges"),
                "jpg"     => array("image/jpeg", "image/jpg", "image/jp_", "application/jpg", "application/x-jpg", "image/pjpeg", "image/pipeg", "image/vnd.swiftview-jpeg", "image/x-xbitmap", "image/x-citrix-pjpeg"),
                "jpe"     => array("image/jpeg"),
                "jpeg"    => array("image/jpeg", "image/jpg", "image/jpe_", "image/pjpeg", "image/vnd.swiftview-jpeg", "image/x-citrix-pjpeg"),
                "js"      => array("application/x-javascript", "text/javascript"),
                "kar"     => array("audio/midi", "audio/x-midi", "audio/mid", "x-music/x-midi"),
                "kil"     => array("application/x-killustrator"),
                "kpr"     => array("application/x-kpresenter"),
                "kpt"     => array("application/x-kpresenter", "application/vnd.kde.kpresenter"),
                "ksp"     => array("application/vnd.kde.kspread", "application/x-kspread"),
                "kwd"     => array("application/x-kword"),
                "kwt"     => array("application/vnd.kde.kword", "application/x-kword"),
                "latex"   => array("application/x-latex", "text/x-latex"),
                "lha"     => array("application/lha", "application/x-lha", "application/octet-stream", "application/x-compress", "application/x-compressed", "application/maclha"),
                "log"     => array("text/plain"),
                "lzh"     => array("application/lzh", "application/x-lzh", "application/x-lha", "application/x-compress", "application/x-compressed", "application/x-lzh-archive", "zz-application/zz-winassoc-lzh", "application/maclha", "application/octet-stream"),
                "m3u"     => array("audio/x-mpegurl", "audio/mpeg-url", "application/x-winamp-playlist", "audio/scpls", "audio/x-scpls"),
                "man"     => array("application/x-troff-man", "application/x-troff-man-compressed"),
                "mdb"     => array("application/msaccess", "application/x-msaccess", "application/vnd.msaccess", "application/vnd.ms-access", "application/mdb", "application/x-mdb", "zz-application/zz-winassoc-mdb"),
                "me"      => array("application/x-troff-me"),
                "mesh"    => array("model/mesh"),
                "mid"     => array("audio/mid", "audio/m", "audio/midi", "audio/x-midi", "application/x-midi", "audio/soundtrack"),
                "midi"    => array("audio/mid", "audio/m", "audio/midi", "audio/x-midi", "application/x-midi"),
                "mif"     => array("application/vnd.mif"),
                "mng"     => array("video/x-mng", "video/mng"),
                "mov"     => array("video/quicktime", "video/x-quicktime", "image/mov", "audio/aiff", "audio/x-midi", "audio/x-wav", "video/avi"),
                "movie"   => array("video/sgi-movie", "video/x-sgi-movie"),
                "mp3"     => array("audio/mpeg", "audio/x-mpeg", "audio/mp3", "audio/x-mp3", "audio/mpeg3", "audio/x-mpeg3", "audio/mpg", "audio/x-mpg", "audio/x-mpegaudio"),
                "mp2"     => array("video/mpeg", "audio/mpeg"),
                "mpe"     => array("video/mpe", "video/mpeg", "video/mpg", "video/x-mpe", "video/x-mpeg", "video/x-mpeg2a"),
                "mpeg"    => array("application/x-pn-mpg", "audio/mpeg", "audio/x-mpeg", "image/pict", "image/x-bmp", "image/x-macpaint", "video/mpeg", "video/mpeg", "video/mpeg2", "video/mpg", "video/msvideo", "video/x-mpeg", "video/x-mpeg2a", "video/x-msvideot-stream"),
                "mpg"     => array("video/mpeg", "video/mpg", "video/x-mpg", "video/mpeg2", "application/x-pn-mpg", "video/x-mpeg", "video/x-mpeg2a", "audio/mpeg", "audio/x-mpeg", "image/mpg"),
                "mpga"    => array("audio/mpeg", "audio/mp3", "audio/mgp", "audio/m-mpeg", "audio/x-mp3", "audio/x-mpeg", "audio/x-mpg", "video/mpeg"),
                "ms"      => array("application/x-troff-ms"),
                "msh"     => array("model/mesh"),
                "msi"     => array("application/x-msi", "application/molecular-viewer", "text/mspg-legacyinfo", "text/mspg-legacyinfo"),
                "nc"      => array("application/x-netcdf", "text/x-cdf"),
                "oda"     => array("application/oda"),
                "ogg"     => array("audio/x-ogg", "application/x-ogg"),
                "old"     => array("application/x-trash"),
                "pbm"     => array("image/portable bitmap", "image/x-portable-bitmap", "image/pbm", "image/x-pbm"),
                "pcx"     => array("application/pcx", "application/x-pcx", "image/pcx", "image/x-pc-paintbrush", "image/x-pcx", "zz-application/zz-winassoc-pcx"),
                "pdb"     => array("chemical/x-pdb", "application/vnd.palm", "application/molecular-viewer", "application/RasMac", "application/x-viewer-pdb", "zz-application/zz-winassoc-PDB"),
                "pdf"     => array("application/pdf", "application/x-pdf", "application/acrobat", "applications/vnd.pdf", "text/pdf", "text/x-pdf"),
                "pgm"     => array("image/x-portable-graymap", "image/x-pgm"),
                "pgn"     => array("application/x-chess-pgn", "application/da-chess-pgn"),
                "pgp"     => array("application/pgp", "application/pgp-keys", "application/pgp-signature", "application/x-pgp-plugin", "application/pgp-encrypted"),
                "php"     => array("application/x-httpd-php", "text/php", "application/php", "magnus-internal/shellcgi", "application/x-php"),
                "pls"     => array("audio/x-scpls"),
                "png"     => array("image/png", "image/x-png", "application/png", "application/x-png"),
                "pnm"     => array("image/x-portable-anymap", "image/x-portable/anymap", "image/pbm"),
                "pot"     => array("application/mspowerpoint", "application/vnd.ms-powerpoint", "application/x-mspowerpoint", "application/powerpoint", "application/x-powerpoint", "application/x-dos_ms_powerpnt", "application/pot", "application/x-soffice"),
                "ppm"     => array("image/x-portable-pixmap", "application/ppm", "application/x-ppm", "image/x-p", "image/x-ppm"),
                "pps"     => array("application/vnd.ms-powerpoint"),
                "ppt"     => array("application/mspowerpoint", "application/ms-powerpoint", "application/mspowerpnt", "application/vnd-mspowerpoint", "application/vnd.ms-powerpoint", "application/powerpoint", "application/x-powerpoint", "application/x-mspowerpoint"),
                "ps"      => array("application/postscript", "application/ps", "application/x-postscript", "application/x-ps", "text/postscript", "application/x-postscript-not-eps"),
                "psd"     => array("image/photoshop", "image/x-photoshop", "image/psd", "application/photoshop", "application/psd", "zz-application/zz-winassoc-psd"),
                "qt"      => array("video/quicktime", "audio/aiff", "audio/x-wav", "video/flc"),
                "ra"      => array("audio/vnd.rn-realaudio", "audio/x-pn-realaudio", "audio/x-realaudio", "audio/x-pm-realaudio-plugin", "video/x-pn-realvideo"),
                "ram"     => array("audio/x-pn-realaudio", "audio/vnd.rn-realaudio", "audio/x-pm-realaudio-plugin", "audio/x-pn-realvideo", "audio/x-realaudio", "video/x-pn-realvideo", "text/plain"),
                "rar"     => array("application/octet-stream","application/x-rar", "application/x-rar-compressed"),
                "ras"     => array("application/ras", "application/x-ras", "image/ras"),
                "rgb"     => array("image/rgb", "image/x-rgb"),
                "rm"      => array("application/vnd.rn-realmedia", "audio/vnd.rn-realaudio", "audio/x-pn-realaudio", "audio/x-realaudio", "audio/x-pm-realaudio-plugin"),
                "roff"    => array("application/x-troff"),
                "rpm"     => array("audio/x-pn-realaudio", "audio/x-pn-realaudio-plugin", "audio/x-pnrealaudio-plugin", "video/x-pn-realvideo-plugin", "audio/x-mpegurl", "application/octet-stream", "application/x-rpm", "application/x-redhat packet manager"),
                "rtf"     => array("application/rtf", "application/x-rtf", "text/rtf", "text/richtext", "application/msword", "application/doc", "application/x-soffice"),
                "rtx"     => array("text/richtext"),
                "sgm"     => array("text/sgml", "text/x-sgml", "application/sgml", "application/x-sgml"),
                "sgml"    => array("text/sgml"),
                "sh"      => array("application/x-shar", "application/x-sh"),
                "shar"    => array("application/x-sh", "application/x-shar"),
                "si"      => array("text/vnd.wap.si"),
                "sic"     => array("application/vnd.wap.sic"),
                "sid"     => array("audio/prs.sid", "audio/psid", "audio/x-psid", "audio/sidtune", "audio/x-sidtune"),
                "sik"     => array("application/x-trash"),
                "silo"    => array("model/mesh"),
                "sit"     => array("application/stuffit", "application/x-stuffit", "application/x-sit"),
                "skd"     => array("application/x-koan", "application/vnd-koan", "koan/x-skm", "application/vnd.koan"),
                "skm"     => array("application/x-koan", "application/vnd-koan", "koan/x-skm", "application/vnd.koan"),
                "skp"     => array("application/x-koan", "application/vnd-koan", "koan/x-skm", "application/vnd.koan"),
                "skt"     => array("application/x-koan", "application/vnd-koan", "koan/x-skm", "application/vnd.koan"),
                "sl"      => array("text/vnd.wap.sl"),
                "slc"     => array("application/vnd.wap.slc", " application/vnd.wap-slc", "application/x-salsa"),
                "smi"     => array("application/smil", "application/smil+xml", "chemical/x-daylight-smiles", "audio/x-pn-realaudio", "application/smil"),
                "smil"    => array("application/smil", "application/smil+xml"),
                "snd"     => array("audio/basic", "audio/x-basic"),
                "spl"     => array("application/futuresplash", "application/x-futuresplash"),
                "sql"     => array("text/plain", "application/soffice", "application/x-soffice", "application/x-staroffice", "zz-application/zz-winassoc-SQL"),
                "src"     => array("application/x-wais-source"),
                "sv4cpio" => array("application/x-sv4cpio"),
                "sv4crc"  => array("application/x-sv4crc", "application/x-svrcrc"),
                "svg"     => array("image/svg", "image/svg-xml", "image/svg+xml", "text/xml-svg", "image/vnd.adobe.svg+xml", "image/svg-xml", "text/xml"),
                "svgz"    => array("image/svg", "image/svg-xml", "image/svg+xml", "text/xml-svg", "image/vnd.adobe.svg+xml", "image/svg-xml"),
                "swf"     => array("application/x-shockwave-flash", "application/x-shockwave-flash2-preview", "application/futuresplash", "image/vnd.rn-realflash"),
                "swfl"    => array("application/x-shockwave-flash"),
                "t"       => array("application/x-troff"),
                "tar"     => array("application/tar", "application/x-tar", "applicaton/x-gtar", "multipart/x-tar", "application/x-compress", "application/x-compressed", "application/x-gzip", "application/x-tar; application/x-winzip", "application/x-winzip", "application/x-zip-compressed"),
                "taz"     => array("application/x-gtar", " application/taz", "application/x-compress", "application/x-gzip", "application/x-tar", "application/x-taz", "application/x-winzip", "application/x-winzip", "application/x-zip-compressed", "multipart/x-tar-gz", "zz-application/zz-winassoc-TAZ"),
                "tcl"     => array("application/x-tcl", "text/x-script.tcl", "text/x-tcl"),
                "tex"     => array("application/x-latex", "application/x-tex", "application/x-tex", "application/x-latex", "text/x-tex", "text/anytext", "text/plain", "text/x-tex"),
                "texi"    => array("application/x-texinfo"),
                "texinfo" => array("application/x-texinfo", "application/x-txinfo"),
                "text"    => array("text/plain"),
                "tgz"     => array("application/octet-stream", "application/tgz", "application/x-compressed", "application/x-compressed", "application/x-winzip", "application/x-compressed-gtar", "application/x-gtar", "application/x-gzip", "application/x-stuffit", "application/x-tar", "application/x-tgz", "application/x-zip-compressed", "file/tgz", "multipart/x-tar-gz", "zz-application/zz-winassoc-TGZ"),
                "tif"     => array("image/tif", "image/x-tif", "image/tiff", "image/x-tiff", "application/tif", "application/x-tif", "application/tiff", "application/x-tiff"),
                "tiff"    => array("application/x-cif-tif-tiff", "image/tif", "image/tiff", "image/tiff", "image/vnd.SwiftView-tiff", "image/x-tiff"),
                "torrent" => array("application/x-bittorrent"),
                "tr"      => array("application/x-troff"),
                "tsv"     => array("text/anytext", "text/tabseparated-values", "text/tab-separated-values"),
                "ttf"     => array("application/Finder"),
                "txt"     => array("text/plain", "application/txt", "browser/internal", "text/anytext", "widetext/plain", "widetext/paragraph"),
                "ustar"   => array("application/x-ustar", "multipart/x-ustar"),
                "vcd"     => array("application/x-cdlink"),
                "vcf"     => array("text/x-vcard", "application/vcard", "text/anytext", "text/directory", "application/x-versit", "text/x-versit", "text/x-vcalendar"),
                "vcs"     => array("application/hbs-vcs", "text/calendar", "text/x-vcalendar"),
                "vmm"     => array("application/vmm"),
                "vrml"    => array("x-world/x-vrml", "model/vrml"),
                "wav"     => array("audio/wav", "audio/x-wav", "audio/wave", "audio/x-pn-wav"),
                "wbmp"    => array("image/vnd.wap.wbmp"),
                "wma"     => array("audio/x-ms-wma"),
                "wmd"     => array("application/x-ms-wmd"),
                "wmf"     => array("application/x-msmetafile", "application/wmf", "application/x-wmf", "image/wmf", "image/x-wmf", "image/x-win-metafile", "zz-application/zz-winassoc-wmf"),
                "wml"     => array("text/vnd.wap.wml", "text/wml"),
                "wmlc"    => array("application/vnd.wap.wmlc"),
                "wmls"    => array("text/vnd.wap.wmlscript"),
                "wmlsc"   => array("application/vnd.wap.wmlscriptc"),
                "wmv"     => array("video/x-ms-wmv"),
                "wrl"     => array("x-world/x-vrml", "model/vrml"),
                "xbm"     => array("image/xbitmap", "image/xbm", "image/x-xbitmap", "text/html", "image/x-xbm"),
                "xhtml"   => array("application/xhtml+xml"),
                "xlb"     => array("application/excel", "application/msexcel", "application/vnd.ms-excel", "application/x-excel"),
                "xls"     => array("application/msexcel", "application/x-msexcel", "application/x-ms-excel", "application/vnd.ms-excel", "application/x-excel", "application/x-dos_ms_excel", "application/xls", "application/x-xls", "zz-application/zz-winassoc-xls"),
                "xml"     => array("text/xml", "application/xml", "application/x-xml"),
                "xpm"     => array("image/x-xpixmap", "image/x-xbitmap", "image/xpm", "image/x-xpm"),
                "xsl"     => array("application/xml", "text/xml", "text/xsl"),
                "xwd"     => array("image/x-xwindowdump", "image/xwd", "image/x-xwd", "application/xwd", "application/x-xwd"),
                "xyz"     => array("chemical/x-xyz", "chemical/x-pdb", "application/x-rn-xyzzly"),
                "zip"     => array("application/zip", "application/x-zip", "application/x-zip-compressed", "application/octet-stream", "application/x-compress", "application/x-compressed", "multipart/x-zip", "application/x-gzip", "application/x-stuffit", "application/x-winzip", "applicaton/x-gtar")
            );
            
            /* JPEG 2000: From RFC 3745: http://www.faqs.org/rfcs/rfc3745.html */
            $extensionMap['forward']['jp2'] = array('image/jp2');
            $extensionMap['forward']['jpg2'] = array('image/jp2');
            $extensionMap['forward']['jpf'] = array('image/jpx');
            $extensionMap['forward']['jpx'] = array('image/jpx');
            $extensionMap['forward']['mj2'] = array('video/mj2');
            $extensionMap['forward']['mjp2'] = array('video/mj2');
            $extensionMap['forward']['jpm'] = array('image/jpm');
            $extensionMap['forward']['jpgm'] = array('image/jpgm');
            
            $mimeMap = array();
            foreach ($extensionMap['forward'] as $ext => $mime) {
                if (!isset($mimeMap[$mime[0]])) {
                    $mimeMap[$mime[0]] = array();
                }
                $mimeMap[$mime[0]][] = $ext;
            }
            $extensionMap['reverseToArray'] = $mimeMap;
        }
        
        return $extensionMap;
    }

    /**
     * Convert a file extension to a mime type
     *
     * @param string a file extension
     * @return string a mime type 
     * @static
     */
    function convertExtensionToMime($extension) {
        $extensionMap =& MIME_Helper::_getExtensionMap();
        
        $extension = strtolower($extension);
        if (empty($extensionMap['forward'][$extension])) {
            return 'application/unknown';
        } else {
            return $extensionMap['forward'][$extension][0];
        }
    }

    /**
     * Convert a mime type to a file extension
     *
     * @param string a mime type
     * @return string a file extension
     * @static
     */
    function convertMimeToExtension($mimeType) {
        $extensionMap =& MIME_Helper::_getExtensionMap();
        
        $mimeType = strtolower($mimeType);
        foreach ($extensionMap['forward'] as $ext => $mime) {
        	foreach ($mime as $value) {
        		if ($value == $mimeType) {
        			return $ext;
        		}
        	}
        }
        return null;
    }

    /**
     * Return mime types and applicable file extensions
     *
     * @return array (string mime type => array(string extension))
     * @static
     */
    function getMimeTypeMap() {
        $extensionMap =& MIME_Helper::_getExtensionMap();
        return $extensionMap['reverseToArray'];
    }

    /**
     * Return true if the given mime type is viewble in a web browser
     *
     * @param string the mime type
     * @return true or false
     */
    function isViewableMimeType($mimeType) {
        static $viewableMimeTypes;
    
        if (!isset($viewableMimeTypes)) {
            $viewableMimeTypes = array('image/jpeg' => 1,
              'image/pjpeg' => 1,
              'image/gif' => 1,
              'image/png' => 1);
        }
        
        return isset($viewableMimeTypes[$mimeType]);
    }
}
?>