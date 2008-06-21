<?php
/**
 * zOOm Media Gallery! - a multi-gallery component 
 * 
 * @package zmg
 * @version $Revision$
 * @author Mike de Boer <mike AT zoomfactory.org>
 * @copyright Copyright &copy; 2007, Mike de Boer. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */

defined('_ZMG_EXEC') or die('Restricted access');

define('ZMG_JMT_VERSION', '1.11');

/**
 * The zmgJpeg_metadataPlugin class
 */
class zmgJpeg_metadataPlugin {
    function bindEvents() {
        return array(
            "onstartup" => array(
                "embed" => array()
            ),
            "ongetimagemetadata" => array(
                "getImageMetadata" => array('medium')
            ),
            "onputimagemetadata" => array(
                "putImageMetadata" => array('medium', 'metadata')
            )
        );
    }
    
    function embed() {
        $settings_file = ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.'jpeg_metadata'.DS.'settings.xml';
        if (file_exists($settings_file)) {
            $plugins = & zmgFactory::getPlugins();
            $plugin = & $plugins->get('jpeg_metadata');
            $plugins->embedSettings(&$plugin, $settings_file);
        }
    }
    
    function getImageMetadata($event) {
    	$medium = $event->getArgument('medium');
        
        $data = array(
            'headers' => null,
            'exif'    => null,
            'xmp'     => null,
            'irb'     => null,
            'finfo'   => null
        );
        
        $ext  = $medium->getExtension();
        $file = $medium->getAbsPath();
        if (zmgFactory::getConfig()->get('plugins/jpeg_metadata/general/readwrite')
          && ($ext == "jpg" || $ext == "jpeg") && !ZMG_SAFEMODE_ON) {
        	//import libs first (duh ;) )
            $jmt_dir = "v".str_replace('.', '_', ZMG_JMT_VERSION);
            zmgimport('org.zoomfactory.var.plugins.jpeg_metadata.'.$jmt_dir.'.EXIF'); //takes care of some deferred loading as well
            zmgimport('org.zoomfactory.var.plugins.jpeg_metadata.'.$jmt_dir.'.Photoshop_File_Info');
            
            // Retreive the EXIF, XMP and Photoshop IRB information from
            // the existing file, so that it can be updated later on...
            $data['headers'] = get_jpeg_header_data($file);
            $data['exif']    = get_EXIF_JPEG($file);
            $data['xmp']     = read_XMP_array_from_text(get_XMP_text($data['headers']));
            $data['irb']     = get_Photoshop_IRB($data['headers']);
            $data['finfo']   = get_photoshop_file_info($data['exif'], $data['xmp'], $data['irb']);
            // Check if there is a default for the date defined
            if ((!array_key_exists('date', $data['finfo'])) || ((array_key_exists('date', $data['finfo']))
              && ($data['finfo']['date'] == ''))) {
                // No default for the date defined
                // figure out a default from the file
                // Check if there is a EXIF Tag 36867 "Date and Time of Original"
                if (($data['exif'] != false) && (array_key_exists(0, $data['exif']))
                  && (array_key_exists(34665, $data['exif'][0])) && (array_key_exists(0, $data['exif'][0][34665]))
                  && (array_key_exists(36867, $data['exif'][0][34665][0]))) {
                    // Tag "Date and Time of Original" found - use it for the default date
                    $data['finfo']['date'] = $data['exif'][0][34665][0][36867]['Data'][0];
                    $data['finfo']['date'] = preg_replace("/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $data['finfo']['date']);
                } elseif (($data['exif'] != false) && (array_key_exists(0, $data['exif']))
                  && (array_key_exists(34665, $data['exif'][0])) && (array_key_exists(0, $data['exif'][0][34665]))
                  && (array_key_exists(36868, $data['exif'][0][34665][0]))) {
                    // Check if there is a EXIF Tag 36868 "Date and Time when Digitized"
                    // Tag "Date and Time when Digitized" found - use it for the default date
                    $data['finfo']['date'] = $data['exif'][0][34665][0][36868]['Data'][0];
                    $data['finfo']['date'] = preg_replace("/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $data['finfo']['date']);
                } else if ( ( $data['exif'] != false ) && (array_key_exists(0, $data['exif']))
                  && (array_key_exists(306, $data['exif'][0]))) {
                    // Check if there is a EXIF Tag 306 "Date and Time"
                    // Tag "Date and Time" found - use it for the default date
                    $data['finfo']['date'] = $data['exif'][0][306]['Data'][0];
                    $data['finfo']['date'] = preg_replace("/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $data['finfo']['date']);
                } else {
                    // Couldn't find an EXIF date in the image
                    // Set default date as creation date of file
                    $data['finfo']['date'] = date("Y-m-d", filectime($file));
                }
            }
        }
        
        return zmgJpeg_metadataPlugin::interpretImageData($data, $medium->filename);
    }
    
    function interpretImageData($data, $filename) {
        //for now, we are only interested in the EXIF data
        $exif_data = array();
        if ($data['exif']['Tags Name'] == "TIFF") {
            $exif_data['title'] = T_("Contains Exchangeable Image File Format (EXIF) Information");
        } else if ($data['exif']['Tags Name'] == "Meta") {
            $exif_data['title'] = T_("Contains META Information (APP3)");
        } else {
            $exif_data['title'] = T_("Contains Meta Information");
        }
        
        $exif_data['IFD'] = zmgJpeg_metadataPlugin::interpretIFD($data['exif'][0], $data['exif']['Byte_Align']);
        
        return $exif_data;
    }
    
    function interpretIFD($ifd_data) {
        // Check that the IFD array is valid
        if (($ifd_data === false) || ($ifd_data === null)) {
            // the IFD array is NOT valid - exit
            return null;
        }
        
        $ifd_interpreted = array();
        
        // Cycle through each tag in the IFD
        foreach ($ifd_data as $tagid => $exif_tag) {
            // Ignore the non numeric elements - they aren't tags
            if (!is_numeric($tagid)) {
                // Skip Tags Name
            } else if ($exif_tag['Decoded'] == true) { // Check if the Tag has been decoded successfully
                // This tag has been successfully decoded

                // Check if the tag is a sub-IFD
                if ($exif_tag['Type'] == "SubIFD") {
                    // This is a sub-IFD tag
                    // Add a sub-heading for the sub-IFD
                    $ifd_interpreted[$exif_tag['Tag Name']] = array();

                    // Cycle through each sub-IFD in the chain
                    foreach ($exif_tag['Data'] as $subIFD) {
                        // Interpret this sub-IFD and add the html to the secondary output
                        $ifd_interpreted[$exif_tag['Tag Name']][] = zmgJpeg_metadataPlugin::interpretIFD($subIFD, $filename);
                    }
                } else if ($exif_tag['Type'] == "Maker Note") { // Check if the tag is a makernote
                    // This is a Makernote Tag

                    // Interpret the Makernote and add the html to the secondary output
                    $ifd_interpreted['Makernote'] = Interpret_Makernote_to_HTML($exif_tag, $filename);
                } else if ($exif_tag['Type'] == "IPTC") { // Check if this is a IPTC/NAA Record within the EXIF IFD
                    // This is a IPTC/NAA Record, interpret it
                    $ifd_interpreted['IPTC'] = Interpret_IPTC_to_HTML($exif_tag['Data']);
                } else if ($exif_tag['Type'] == "XMP") { // Change: Check for embedded XMP as of version 1.11
                    // Check if this is a XMP Record within the EXIF IFD
                    // This is a XMP Record, interpret it
                    $ifd_interpreted['XMP'] = Interpret_XMP_to_HTML($exif_tag['Data']);
                } else if ($exif_tag['Type'] == "IRB") { // Change: Check for embedded IRB as of version 1.11
                    // Check if this is a Photoshop IRB Record within the EXIF IFD
                    // This is a Photoshop IRB Record, interpret it and output to the secondary html
                    $ifd_interpreted['IRB'] = Interpret_IRB_to_HTML($exif_tag['Data'], $filename);
                } else if ($exif_tag['Type'] == "Numeric") { // Check if the tag is Numeric
                    // Numeric Tag - Output text value as is.
                    $ifd_interpreted[$exif_tag['Tag Name']] = $exif_tag['Text Value'];
                } else {
                    // Other tag - Output text as preformatted
                    //$ifd_interpreted[$exif_tag['Tag Name']] = trim($exif_tag['Text Value']);
                }
            }
        }
        
        return $ifd_interpreted;
    }
    
    function putImageMetadata($event) {
    	return true;
    }
}
?>
