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
        $zoom = & zmgFactory::getZoom();
        $settings_file = ZMG_ABS_PATH . DS.'var'.DS.'plugins'.DS.'jpeg_metadata'.DS.'settings.xml';
        if (file_exists($settings_file)) {
            $plugin = & $zoom->plugins->get('jpeg_metadata');
            $zoom->plugins->embedSettings(&$plugin, $settings_file);
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
        
        $zoom = & zmgFactory::getZoom();
        
        $ext  = $medium->getExtension();
        $file = $medium->getAbsPath();
        if ($zoom->getConfig('plugins/jpeg_metadata/general/readwrite')
          && ($ext == "jpg" || $ext == "jpeg") && !ZMG_SAFEMODE_ON) {
        	//import libs first (duh ;) )
            zmgimport('org.zoomfactory.var.plugins.jpeg_metadata.v1_11.EXIF'); //takes care of some deferred loading as well
            zmgimport('org.zoomfactory.var.plugins.jpeg_metadata.v1_11.Photoshop_File_Info');
            
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
        
        return $data;
    }
    
    function putImageMetadata($event) {
    	return true;
    }
}
?>
