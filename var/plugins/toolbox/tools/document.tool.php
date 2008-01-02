<?php
/**
 * zOOm Media Gallery! - a multi-gallery component 
 * 
 * @package zmg
 * @version $Revision$
 * @author Mike de Boer <mdeboer AT ebuddy.com>
 * @copyright Copyright &copy; 2007, Mike de Boer. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */

defined('_ZMG_EXEC') or die('Restricted access');

class zmgDocumentTool {
    /**
     * Extract the raw text from a PDF document (format: '{filename}.txt') with the Xpdf library (pdftotext)
     *
     * @param string $file
     * @param string $filename
     * @return boolean
     */
    function index($file, $filename) {
        global $mosConfig_absolute_path, $zoom;
        // this function will contain the algorithm to index a document (like a pdf)...
        // Method: use PDFtoText to create a plain ASCII text-file, which can be easily
        //         searched through. The text-file will be placed into the same dir as the
        //         original pdf.
        // Note: support for MS Word, Excel and Powerpoint indexing will be added later.
        if ($this->_PDF_path == 'auto') {
            $this->_PDF_path = '';
        } else {
            if (!empty($this->_PDF_path) && !$zoom->_CONFIG['override_PDF']) {
                if (!$zoom->platform->is_dir($this->_PDF_path)) {
                    return zmgToolboxPlugin::registerError($file, 'Xpdf: Your PDFtoText path is not correct! Please (re)specify it in the Admin-system under \'Settings\'');
                }
            }
        }
        $desfile = ereg_replace("(.*)\.([^\.]*)$", "\\1", $filename).".txt";
        $target = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->getDir()."/".$desfile;
        $cmd = zmgDocumentTool::detectPath() . "pdftotext \"$file\" \"$target\"";
        $output = $retval = null;
        exec($cmd, $output, $retval);
        if ($retval) {
            return zmgToolboxPlugin::registerError($file, 'Xpdf: Could not index document: ' . $output);
        }
        return true;
    }
    /**
     * Perform a search with a given search-string in PDF-index files generated by zOOm.
     *
     * @param string $file
     * @param string $searchText
     * @return boolean
     */
    function search($file, $searchText) {
        global $mosConfig_absolute_path, $zoom;
        if (empty($file->_filename)) {
            $file->getInfo();
        }
        $source = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$file->getDir()."/".ereg_replace("(.*)\.([^\.]*)$", "\\1", $file->_filename).".txt";
        if (!$zoom->platform->is_file($source)) {
            return zmgToolboxPlugin::registerError($file, 'Xpdf: File is not indexed yet.');
        }
        $txt = strtolower(file_get_contents($source));
        if (preg_match("/$searchText/", $txt)) {
            unset($txt);
            return true;
        }
        unset($txt);
        return zmgToolboxPlugin::registerError($file, 'Search: Term not found in this document.');
    }
    function detectPath() {
        $path = "";
        if (file_exists('/usr/bin/pdftotext') && is_executable('/usr/bin/pdftotext')) {
            $path = "/usr/bin/"; //Debian systems
        }
        return $path;
    }
    /**
     * Detect if Xpdf is available on the system.
     *
     * @return void
     */
    function autoDetect() {
        static $output, $status;
        $bar = @exec(zmgDocumentTool::detectPath() . 'pdftotext -v', $output, $status);
        
        $res = false;
        if (!empty($output[0])) {
            if (preg_match("/pdftotext/i", $output[0], $matches)) {
                zmgToolboxPlugin::registerError(T_('Xpdf (or pdftotext)'), T_('is available.'));
                $res = true;
            }
        }
        if (!$res) {
            zmgToolboxPlugin::registerError(T_('Xpdf (or pdftotext)'), T_('could not be detected on your system.'));
        }
        unset($output, $status);
    }
}
?>
