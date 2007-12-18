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
| Filename: toolbox.class.php                                         |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:toolbox.class.php 139 2007-03-12 09:21:44Z mikedeboer $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
*  -----------------------------------------------------------------------
* |                                                                     |
* | What is the toolbox? --> well, it's an object that bundles all      |
* | medium-manipulation tools into one convenient class.                |
* | These tools would include:                                          |
* |                                                                     |
* | - Image resizing                                                    |
* | - Image rotating                                                    |
* | - Image watermarking                                                |
* | - Image EXIF data processing (reading AND writing)                  |
* | - Parse Directories for media types                                 |
* | - PDF/ documents searching                                          |
* | - Video JPEG capturing                                              |
* | - MP3 id3 tag processing                                            |
* | - Streaming video file metadata processing                          |
* | - ALL image manipulation tools have implementations for the         |
* |   following software: ImageMagick, NetPBM, GD1.x and GD2.x.         |
* |                                                                     |
* -----------------------------------------------------------------------
* @access public
*/
class toolbox extends zoom {
    /**
    * @var int
    * @access private
    */
    var $_conversiontype = null;
    /**
    * @var string
    * @access private
    */
    var $_IM_path = null;
    /**
    * @var string
    * @access private
    */
    var $_NETPBM_path = null;
    /**
    * @var string
    * @access private
    */
    var $_FFMPEG_path = null;
    /**
    * @var int
    * @access private
    */
    var $_JPEG_quality = null;
    /**
    * @var string
    * @access private
    */
    var $_PDF_path = null;
    /**
    * @var boolean
    * @access private
    */
    var $_use_FFMPEG = null;
    /**
    * @var boolean
    * @access private
    */
    var $_use_PDF = null;
    /**
    * @var int
    * @access private
    */
    var $_err_num = null;
    /**
    * @var array
    * @access private
    */
    var $_err_names = array();
    /**
    * @var array
    * @access private
    */
    var $_err_types = array();
    /**
    * @var string
    * @access private
    */
    var $_wm_file = null;
    /**
    * @var string
    * @access private
    */
    var $_wm_position = null;
    /**
    * @var byte
    * @access private
    */
    var $_buffer = null;
    
    /**
    * Toolbox object constructor.
    *
    * @return toolbox
    * @access public
    */
    function toolbox($warnuser = true) {
        // constructor of the toolbox - primary init...
        global $mosConfig_absolute_path, $zoom;
        $this->_conversiontype = $zoom->_CONFIG['conversiontype'];
        $this->_IM_path = $zoom->_CONFIG['IM_path'];
        $this->_NETPBM_path = $zoom->_CONFIG['NETPBM_path'];
        $this->_FFMPEG_path = $zoom->_CONFIG['FFMPEG_path'];
        $this->_PDF_path = $zoom->_CONFIG['PDF_path'];
        $this->_JPEG_quality = $zoom->_CONFIG['JPEGquality'];
        // load watermark settings...
        $this->_wm_file = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath']."watermarks/".$zoom->_CONFIG['wm_file'];
        if (!isset($zoom->_CONFIG['wm_position']))
            $this->_wm_position = "C";
        else
            $this->_wm_position = $zoom->_CONFIG['wm_position'];
        if (!isset($zoom->_CONFIG['wmfont']))
            $this->_wmfont = "ARIAL.TTF";
        
        if ($this->_FFMPEG_path == 'auto') {
            $this->_FFMPEG_path = '';
        } else {
            if ($this->_FFMPEG_path) {
                if (@$zoom->platform->is_dir($this->_FFMPEG_path) | $zoom->_CONFIG['override_FFMPEG']) {
                    $this->_use_FFMPEG = true;
                } else {
                    $this->_use_FFMPEG = false;
                }
            }
        }
        if ($this->_PDF_path == 'auto') {
            $this->_PDF_path = '';
        } else {
            if ($this->_PDF_path) {
                if (@$zoom->platform->is_dir($this->_PDF_path) | $zoom->_CONFIG['override_PDF']) {
                    $this->_use_PDF = true;
                } else {
                    $this->_use_PDF = false;
                }
            }
        }
        if ($zoom->privileges->hasPrivileges()) {
            switch ($this->_conversiontype) {
                //Imagemagick
                case 1:
                    if ($this->_IM_path == 'auto') {
                        $this->_IM_path = '';
                    } else {
                        if ($this->_IM_path) {
                            if (!@$zoom->platform->is_dir($this->_IM_path) && $warnuser) {
                                echo "<div align=\"center\"><font color=\"red\">Warning: your ImageMagick path is not correct! Please (re)specify it in the Admin-system under 'Settings'</font><br />";
                            }
                        }
                    }
                    break;
                //NetPBM
                case 2:
                    if ($this->_NETPBM_path == 'auto') {
                        $this->_NETPBM_path ='';
                    } else {
                        if ($this->_NETPBM_path) {
                            if (!@$zoom->platform->is_dir($this->_NETPBM_path && $warnuser)) {
                                echo "<div align=\"center\"><font color=\"red\">Warning: your NetPBM path is not correct! Please (re)specify it in the Admin-system under 'Settings'</font><br /></div>";
                            }
                        }
                    }
                    break;
                //GD1
                case 3:
                    if (!function_exists('imagecreatefromjpeg') && $warnuser) {
                        echo "<div align=\"center\"><font color=\"red\">Warning: PHP running on your server does not fully support the GD image library. Please compile GD with jpeg support, or switch to ImageMagick.</font><br /></div>";
                    }
                    break;
                //GD2
                case 4:
                    if (!function_exists('imagecreatefromjpeg') && $warnuser) {
                        echo "<div align=\"center\"><font color=\"red\">Warning: PHP running on your server does not fully support the GD image library. Please compile GD with jpeg support, or switch to ImageMagick.</font><br /></div>";
                    }
                    if (!function_exists('imagecreatetruecolor') && $warnuser) {
                        echo "<div align=\"center\"><font color=\"red\">Warning: PHP running on your server does not support GD version 2.x, please switch to GD version 1.x on the config page</font><br /></div>";
                    }
                    break;
            }
        }
        $this->_err_num = 0;
        // toolbox ready for use...
    }
    /**
    * Make a newly uploaded medium ready for use in the gallery.
    *
    * @param string $image
    * @param string $filename
    * @param string $filetype
    * @param string $keywords
    * @param string $name
    * @param string $descr
    * @param int $rotate
    * @param int $degrees
    * @param int $ignoresizes
    * @return boolean
    * @access public
    */
    function processImage($image, $filename, $filetype, $keywords, $name, $descr, $rotate, $degrees = 0, $ignoresizes = 0) {
        global $mosConfig_absolute_path, $zoom;
        // reset script execution time limit (as set in MAX_EXECUTION_TIME ini directive)...
        // requires SAFE MODE to be OFF!
        if (ini_get('safe_mode') != 1 ) {
            set_time_limit(0);
        }
        $imagepath = $zoom->_CONFIG['imagepath'];
        $catdir = $zoom->_gallery->getDir();
        $filename = urldecode($filename);
		// replace every space-character with a single "_"
		$filename = ereg_replace(" ", "_", $filename);
		$filename = stripslashes($filename);
        $filename = ereg_replace("'", "_", $filename);
        // Get rid of extra underscores
        $filename = ereg_replace("_+", "_", $filename);
        $filename = ereg_replace("(^_|_$)", "", $filename);
        $zoom->checkDuplicate($filename, 'filename');
        $filename = $zoom->_tempname;
        // replace space-characters in combination with a comma with 'air'...or nothing!
        $keywords = $zoom->cleanString($keywords);
        //$keywords = $zoom->htmlnumericentities($keywords);
		$name = $zoom->cleanString($name);
        //$name = $zoom->htmlnumericentities($name);
        //$descr = $zoom->cleanString($descr);
        //$descr = $zoom->htmlnumericentities($descr);
        if (empty($name)) {
            $name = $zoom->_CONFIG['tempName'];
        }
        $imgobj = new image(0); //create a new image object with a foo imgid
        $imgobj->setImgInfo($filename, $name, $keywords, $descr, $zoom->_gallery->_id, $zoom->currUID, 1, 1);
        $imgobj->getMimeType($filetype, $image);
        unset($filename, $name, $keywords, $descr); //clear memory, just in case...
        if (!$zoom->acceptableSize($image)) {
        	// the file is simply too big, register this...
            $this->_err_num++;
            $this->_err_names[$this->_err_num] = $imgobj->_filename;
            $this->_err_types[$this->_err_num] = sprintf(_ZOOM_ALERT_TOOBIG, $zoom->_CONFIG['maxsizekb'].'kB');
            return false;
        }
        /* IMPORTANT CHEATSHEET:
	     * If we don't get useful data from that or its a type we don't
	     * recognize, take a swing at it using the file name.
	    if ($mimeType == 'application/octet-stream' ||
		    $mimeType == 'application/unknown' ||
		    GalleryCoreApi::convertMimeToExtension($mimeType) == null) {
		$extension = GalleryUtilities::getFileExtension($file['name']);
		$mimeType = GalleryCoreApi::convertExtensionToMime($extension);
	    }
	    */
        if ($zoom->acceptableFormat($imgobj->getMimeType(), true)) {
            // File is an image/ movie/ document...
            $file = "$mosConfig_absolute_path/$imagepath$catdir/".$imgobj->_filename;
            $desfile = "$mosConfig_absolute_path/$imagepath$catdir/thumbs/".$imgobj->_filename;
            if (is_uploaded_file($image)) {
                if (!move_uploaded_file("$image", $file)) {
                    // some error occured while moving file, register this...
                    $this->_err_num++;
                    $this->_err_names[$this->_err_num] = $imgobj->_filename;
                    $this->_err_types[$this->_err_num] = _ZOOM_ALERT_MOVEFAILURE;
                    return false;
                }
            } elseif (!$zoom->platform->copy("$image", $file) && !$zoom->platform->file_exists($file)) {
                // some error occured while moving file, register this...
                $this->_err_num++;
                $this->_err_names[$this->_err_num] = $imgobj->_filename;
                $this->_err_types[$this->_err_num] = _ZOOM_ALERT_MOVEFAILURE;
                return false;
            }
            @$zoom->platform->chmod($file, '0777');
            $viewsize = $mosConfig_absolute_path."/".$imagepath.$catdir."/viewsize/".$imgobj->_filename;
            if ($zoom->acceptableFormat($imgobj->getMimeType(), true)) {
	            if ($zoom->isImage($imgobj->getMimeType(), true)) {
					$imgobj->_size = $zoom->platform->getimagesize($file);
	                // get image EXIF & IPTC data from file to save it in viewsize image and get a thumbnail...
	                if ($zoom->_CONFIG['readEXIF'] && ($imgobj->_type === "jpg" || $imgobj->_type === "jpeg") && !(bool)ini_get('safe_mode')) {
	                    // Retreive the EXIF, XMP and Photoshop IRB information from
	                    // the existing file, so that it can be updated later on...
	                    $jpeg_header_data = get_jpeg_header_data($file);
	                    $EXIF_data = get_EXIF_JPEG($file);
	                    $XMP_data = read_XMP_array_from_text( get_XMP_text( $jpeg_header_data ) );
	                    $IRB_data = get_Photoshop_IRB( $jpeg_header_data );
	                    $new_ps_file_info = get_photoshop_file_info($EXIF_data, $XMP_data, $IRB_data);
	                    // Check if there is a default for the date defined
	                    if ((!array_key_exists('date', $new_ps_file_info)) || ((array_key_exists('date', $new_ps_file_info)) && ($new_ps_file_info['date'] == ''))) {
	                        // No default for the date defined
	                        // figure out a default from the file
	                        // Check if there is a EXIF Tag 36867 "Date and Time of Original"
	                        if (($EXIF_data != FALSE) && (array_key_exists(0, $EXIF_data)) && (array_key_exists(34665, $EXIF_data[0])) && (array_key_exists(0, $EXIF_data[0][34665])) && (array_key_exists(36867, $EXIF_data[0][34665][0]))) {
	                            // Tag "Date and Time of Original" found - use it for the default date
	                            $new_ps_file_info['date'] = $EXIF_data[0][34665][0][36867]['Data'][0];
	                            $new_ps_file_info['date'] = preg_replace( "/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $new_ps_file_info['date'] );
	                        } elseif (($EXIF_data != FALSE) && (array_key_exists(0, $EXIF_data)) && (array_key_exists(34665, $EXIF_data[0])) && (array_key_exists(0, $EXIF_data[0][34665])) && (array_key_exists(36868, $EXIF_data[0][34665][0]))) {
	                            // Check if there is a EXIF Tag 36868 "Date and Time when Digitized"
	                            // Tag "Date and Time when Digitized" found - use it for the default date
	                            $new_ps_file_info['date'] = $EXIF_data[0][34665][0][36868]['Data'][0];
	                            $new_ps_file_info['date'] = preg_replace( "/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $new_ps_file_info['date'] );
	                        } else if ( ( $EXIF_data != FALSE ) && (array_key_exists(0, $EXIF_data)) && (array_key_exists(306, $EXIF_data[0]))) {
	                            // Check if there is a EXIF Tag 306 "Date and Time"
	                            // Tag "Date and Time" found - use it for the default date
	                            $new_ps_file_info['date'] = $EXIF_data[0][306]['Data'][0];
	                            $new_ps_file_info['date'] = preg_replace( "/(\d\d\d\d):(\d\d):(\d\d)( \d\d:\d\d:\d\d)/", "$1-$2-$3", $new_ps_file_info['date'] );
	                        } else {
	                            // Couldn't find an EXIF date in the image
	                            // Set default date as creation date of file
	                            $new_ps_file_info['date'] = date ("Y-m-d", filectime( $file ));
	                        }
	                    }
	                }
	                // First, rotate the image (if that's mentioned in the 'job description')...
		            if ($rotate) {
						$tmpdir = $mosConfig_absolute_path."/".$zoom->createTempDir();
						$new_source = $tmpdir."/".$imgobj->_filename;
						if (!$this->rotateImage($file, $new_source, $degrees, $imgobj)) {
	                        $this->_err_num++;
	                        $this->_err_names[$this->_err_num] = $imgobj->_filename;
	                        $this->_err_types[$this->_err_num] = "Error rotating image";
	                        return false;
	                    } else {
							@$zoom->platform->unlink($file);
							if ($zoom->platform->copy($new_source, $file)) {
								$imgobj->_size = $zoom->platform->getimagesize($file);
							}
						}
	                }
	                // resize to thumbnail...
	                // 1-31-2006: fix #0000151
	                if (!$zoom->platform->file_exists($desfile)) {
	                	if (!$this->resizeImage($file, $desfile, $zoom->_CONFIG['size'], $imgobj)) {
		                    $this->_err_num++;
		                    $this->_err_names[$this->_err_num] = $imgobj->_filename;
		                    $this->_err_types[$this->_err_num] = _ZOOM_ALERT_IMGERROR;
		                    return false;
		                }
	                }
	                
	                // if the image size is greater than the given maximum: resize it!
	                if (!$zoom->platform->file_exists($viewsize)) {
		                //If the image is larger than the max size
						if (($imgobj->_size[0] > $zoom->_CONFIG['maxsize'] || $imgobj->_size[1] > $zoom->_CONFIG['maxsize']) && !$ignoresizes) {
		                    //Resizes the file. If successful, continue
							if ($this->resizeImage($file, $viewsize, $zoom->_CONFIG['maxsize'], $imgobj)) {
								//Watermark?
								if ((bool)$zoom->_CONFIG['wm_apply']) {
									//Watermark. Return errors if not successuful
									if (!$this->watermarkImage($viewsize, $viewsize, $imgobj)) {
										$this->_err_num++;
										$this->_err_names[$this->_err_num] = $imgobj->_filename;
										$this->_err_types[$this->_err_num] = _ZOOM_ALERT_WATERMARKERROR;
										return false;
									}
								}
		                    } else {
		                        $this->_err_num++;
		                        $this->_err_names[$this->_err_num] = $imgobj->_filename;
		                        $this->_err_types[$this->_err_num] = _ZOOM_ALERT_IMGERROR;
		                        return false;
		                    }
		                } else {
		                   //Watermark?
							if ((bool)$zoom->_CONFIG['wm_apply']) {
								//Watermark. Return errors if not successuful
								if (!$this->watermarkImage($file, $file, $imgobj)) {
									$this->_err_num++;
									$this->_err_names[$this->_err_num] = $imgobj->_filename;
									$this->_err_types[$this->_err_num] = _ZOOM_ALERT_WATERMARKERROR;
									return false;
								}
							}
		                }
	                }
	            } elseif ($zoom->isDocument($imgobj->getMimeType(), true)) {
	                if ($zoom->isIndexable($imgobj->getMimeType(), true) && $this->_use_PDF) {
	                    if (!$this->indexDocument($file, $imgobj->_filename)) {
	                        $this->_err_num++;
	                        $this->_err_names[$this->_err_num] = $imgobj->_filename;
	                        $this->_err_types[$this->_err_num] = _ZOOM_ALERT_INDEXERROR;
	                        return false;
	                    }
	                } else {
	                	if($zoom->platform->copy($file, $viewsize)) {
	                		if ((bool)$zoom->_CONFIG['wm_apply']) {
								// put a watermark on the source image...
								if (!$this->watermarkImage($viewsize, $viewsize, $imgobj)) {
									$this->_err_num++;
										$this->_err_names[$this->_err_num] = $imgobj->_filename;
										$this->_err_types[$this->_err_num] = _ZOOM_ALERT_WATERMARKERROR;
										return false;
								}
							}
	                	} else {
							// some error occured while moving file, register this...
							$this->_err_num++;
							$this->_err_names[$this->_err_num] = $imgobj->_filename;
							$this->_err_types[$this->_err_num] = _ZOOM_ALERT_MOVEFAILURE;
							return false;
						}
	            	}
	            } elseif ($zoom->isMovie($imgobj->getMimeType(), true)) {
	                //if movie is 'thumbnailable' -> make a thumbnail then!
	                if ($zoom->isThumbnailable($imgobj->_type) && $this->_use_FFMPEG) {
	                    if (!$this->createMovieThumb($file, $zoom->_CONFIG['size'], $imgobj->_filename)) {
	                        $this->_err_num++;
	                        $this->_err_names[$this->_err_num] = $imgobj->_filename;
	                        $this->_err_types[$this->_err_num] = _ZOOM_ALERT_IMGERROR;
	                        return false;
	                    }
	                }
	            } elseif ($zoom->isAudio($imgobj->getMimeType(), true)) {
	                // TODO: indexing audio files (mp3-files, etc.) properties, e.g. id3vX tags...
	            }
	            if (!$imgobj->save()) {
	                $this->_err_num++;
	                $this->_err_names[$this->_err_num] = $imgobj->_filename;
	                $this->_err_types[$this->_err_num] = "Database failure";
	            }
	        }
        } else {
            //Not the right format, register this...
            $this->_err_num++;
            $this->_err_names[$this->_err_num] = $imgobj->_filename;
            $this->_err_types[$this->_err_num] = _ZOOM_ALERT_WRONGFORMAT_MULT;
            return false;
        }
        return true;
    }
    /**
    * Resize an image to a prefered size.
    *
    * @param string $file
    * @param string $desfile
    * @param int $size
    * @param image $imgobj
    * @return boolean
    * @access public
    */
    function resizeImage($file, $desfile, $size, $imgobj) {
        switch ($this->_conversiontype){
            //Imagemagick
            case 1:
                if($this->_resizeImageIM($file, $desfile, $size))
                    return true;
                else
                    return false;
                break;
            //NetPBM
            case 2:
                if($this->_resizeImageNETPBM($file, $desfile, $size, $imgobj))
                    return true;
                else
                    return false;
                break;
            //GD1
            case 3:
                if($this->_resizeImageGD1($file, $desfile, $size, $imgobj))
                    return true;
                else
                    return false;
                break;
            //GD2
            case 4:
                if($this->_resizeImageGD2($file, $desfile, $size, $imgobj))
                    return true;
                else
                    return false;
                break;
        }
        return true;
    }
    /**
    * Resize an image to a prefered size using the ImageMagick library.
    *
    * @param string $src_file
    * @param string $dest_file
    * @param int $new_size
    * @return boolean
    * @access private
    */
    function _resizeImageIM($src_file, $dest_file, $new_size) {
    	$retval = $output = null;
        $cmd = $this->_IM_path."convert -resize $new_size \"$src_file\" \"$dest_file\"";
        exec($cmd, $output, $retval);
        if($retval) {
            return false;
        } else {
            return true;
        }
    }
    /**
    * Resize an image to a prefered size using the NetPBM library.
    *
    * @param string $src_file
    * @param string $des_file
    * @param int $new_size
    * @param image $imgobj
    * @return boolean
    * @access private
    */
    function _resizeImageNETPBM($src_file, $des_file, $new_size, $imgobj) {
        if ($imgobj->_size == null) {
            return false;
        }
        // height/width
        $ratio = max($imgobj->_size[0], $imgobj->_size[1]) / $new_size;
        $ratio = max($ratio, 1.0);
        $destWidth = (int)($imgobj->_size[0] / $ratio);
        $destHeight = (int)($imgobj->_size[1] / $ratio);
        if (eregi("\.png", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "pngtopnm $src_file | " . $this->_NETPBM_path . "pnmscale -xysize $destWidth $destHeight | " . $this->_NETPBM_path . "pnmtopng > $des_file" ; 
        } elseif (eregi("\.(jpg|jpeg)", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "jpegtopnm $src_file | " . $this->_NETPBM_path . "pnmscale -xysize $destWidth $destHeight | " . $this->_NETPBM_path . "ppmtojpeg -quality=" . $this->_JPEG_quality . " > $des_file" ;
        } elseif (eregi("\.gif", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "giftopnm $src_file | " . $this->_NETPBM_path . "pnmscale -xysize $destWidth $destHeight | " . $this->_NETPBM_path . "ppmquant 256 | " . $this->_NETPBM_path . "ppmtogif > $des_file" ; 
        } else {
            return false;
        }
        $output = $retval = null;
        exec($cmd, $output, $retval);
        if ($retval) {
            return false;
        } else {
            return true;
        }
    }
    /**
    * Resize an image to a prefered size using the GD1 library.
    *
    * @param string $src_file
    * @param string $dest_file
    * @param int $new_size
    * @param image $imgobj
    * @return boolean
    * @access private
    */
    function _resizeImageGD1($src_file, $dest_file, $new_size, $imgobj) {
        if ($imgobj->_size == null) {
            return false;
        }
        // GD1 can only handle JPG & PNG images
        if ($imgobj->_type !== "jpg" && $imgobj->_type !== "jpeg" && $imgobj->_type !== "png") {
            return false;
        }
        // height/width
        $ratio = max($imgobj->_size[0], $imgobj->_size[1]) / $new_size;
        $ratio = max($ratio, 1.0);
        $destWidth = (int)($imgobj->_size[0] / $ratio);
        $destHeight = (int)($imgobj->_size[1] / $ratio);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            $src_img = imagecreatefromjpeg($src_file);
        } else {
            $src_img = imagecreatefrompng($src_file);
        }
        if (!$src_img) {
            return false;
        }
        $dst_img = imagecreate($destWidth, $destHeight);
        imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $destWidth, (int)$destHeight, $imgobj->_size[0], $imgobj->_size[1]);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            imagejpeg($dst_img, $dest_file, $this->_JPEG_quality);
        } else {
            imagepng($dst_img, $dest_file);
        }
        imagedestroy($src_img);
        imagedestroy($dst_img);
        return true;
    }
    /**
    * Resize an image to a prefered size using the GD2 library.
    *
    * @param string $src_file
    * @param string $dest_file
    * @param int $new_size
    * @param image $imgobj
    * @return boolean
    * @access private
    */
    function _resizeImageGD2($src_file, $dest_file, $new_size, $imgobj) {
        if ($imgobj->_size == null) {
            return false;
        }
        // GD can only handle JPG, PNG & GIF images
        if ($imgobj->_type !== "jpg" && $imgobj->_type !== "jpeg" && $imgobj->_type !== "png"  && $imgobj->_type !== "gif") {
            return false;
        }
        if ($imgobj->_type == "gif" && !function_exists("imagecreatefromgif")) {
        	return false;
        }
        
        // height/width
        $ratio = max($imgobj->_size[0], $imgobj->_size[1]) / $new_size;
        $ratio = max($ratio, 1.0);
        $destWidth = (int)($imgobj->_size[0] / $ratio);
        $destHeight = (int)($imgobj->_size[1] / $ratio);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            $src_img = @imagecreatefromjpeg($src_file);
            $dst_img = imagecreatetruecolor($destWidth, $destHeight);
        } else if ($imgobj->_type == "png") {
            $src_img = @imagecreatefrompng($src_file);
            $dst_img = imagecreatetruecolor($destWidth, $destHeight);
   			$img_white = imagecolorallocate($dst_img, 255, 255, 255); // set background to white
			$img_return = @imagefill($dst_img, 0, 0, $img_white);
        } else {
        	$src_img = @imagecreatefromgif($src_file);
        	$dst_img = imagecreatetruecolor($destWidth,$destHeight);
			$img_white = imagecolorallocate($dst_img, 255, 255, 255); // set background to white
			$img_return = @imagefill($dst_img, 0, 0, $img_white);
        }
        if (!$src_img) {
            return false;
        }
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $destWidth, $destHeight, $imgobj->_size[0], $imgobj->_size[1]);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            imagejpeg($dst_img, $dest_file, $this->_JPEG_quality);
        } else if ($imgobj->_type == "png") {
            imagepng($dst_img, $dest_file);
        } else {
        	imagegif($dst_img, $dest_file);
        }
        imagedestroy($src_img);
        imagedestroy($dst_img);
        return true;
    }
    /**
    * Rotate an image with the prefered number of degrees.
    *
    * @param string $file
    * @param string $desfile
    * @param int $degrees
    * @param image $imgobj
    * @return boolean
    * @access public
    */
    function rotateImage($file, $desfile, $degrees, $imgobj) {
        $degrees = intval($degrees);
        switch ($this->_conversiontype){
            //Imagemagick
            case 1:
                if($this->_rotateImageIM($file, $desfile, $degrees))
                    return true;
                else
                    return false;
                break;
            //NetPBM
            case 2:
                if($this->_rotateImageNETPBM($file, $desfile, $degrees, $imgobj))
                    return true;
                else
                    return false;
                break;
            //GD1
            case 3:
                if($this->_rotateImageGD1($file, $desfile, $degrees, $imgobj))
                    return true;
                else
                    return false;
                break;
            //GD2
            case 4:
                if($this->_rotateImageGD2($file, $desfile, $degrees, $imgobj))
                    return true;
                else
                    return false;
                break;
        }
        return true;
    }
    /**
    * Rotate an image with the prefered number of degrees using the ImageMagick library.
    *
    * @param string $file
    * @param string $desfile
    * @param int $degrees
    * @return boolean
    * @access private
    */
    function _rotateImageIM($file, $desfile, $degrees) {
        $cmd = $this->_IM_path."convert -rotate $degrees \"$file\" \"$desfile\"";
        $output = $retval = null;
        exec($cmd, $output, $retval);
        if($retval) {
            return false;
        } else {
            return true;
        }
    }
    /**
    * Rotate an image with the prefered number of degrees using the NetPBM library.
    *
    * @param string $file
    * @param string $desfile
    * @param int $degrees
    * @param image $imgobj
    * @return boolean
    * @access private
    */
    function _rotateImageNETPBM($file, $desfile, $degrees, $imgobj) {
        $fileOut = "$file.1";
        $zoom->platform->copy($file, $fileOut); 
        if (eregi("\.png", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "pngtopnm $file | " . $this->_NETPBM_path . "pnmrotate $degrees | " . $this->_NETPBM_path . "pnmtopng > $fileOut" ; 
        } elseif (eregi("\.(jpg|jpeg)", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "jpegtopnm $file | " . $this->_NETPBM_path . "pnmrotate $degrees | " . $this->_NETPBM_path . "ppmtojpeg -quality=" . $this->_JPEG_quality . " > $fileOut" ;
        } elseif (eregi("\.gif", $imgobj->_filename)) {
            $cmd = $this->_NETPBM_path . "giftopnm $file | " . $this->_NETPBM_path . "pnmrotate $degrees | " . $this->_NETPBM_path . "ppmquant 256 | " . $this->_NETPBM_path . "ppmtogif > $fileOut" ; 
        } else {
            return false;
        }
        $output = $retval = null;
        exec($cmd, $output, $retval);
        if ($retval) {
            return false;
        } else {
            $erg = $zoom->platform->rename($fileOut, $desfile); 
            return true;
        }
    }
    /**
    * Rotate an image with the prefered number of degrees using the GD1 library.
    *
    * @param string $file
    * @param string $desfile
    * @param int $degrees
    * @param image $imgobj
    * @return boolean
    * @access private
    */
    function _rotateImageGD1($file, $desfile, $degrees, $imgobj) {
        // GD can only handle JPG & PNG images
        if ($imgobj->_type !== "jpg" && $imgobj->_type !== "jpeg" && $imgobj->_type !== "png") {
            return false;
        }
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            $src_img = imagecreatefromjpeg($file);
        } else {
            $src_img = imagecreatefrompng($file);
        }
        if (!$src_img) {
            return false;
        }
        // The rotation routine...
        $dst_img = imagerotate($src_img, $degrees, 0);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            imagejpeg($dst_img, $desfile, $this->_JPEG_quality);
        } else {
            imagepng($dst_img, $desfile);
        }
        imagedestroy($src_img);
        imagedestroy($dst_img);
        return true;
    }
    /**
    * Rotate an image with the prefered number of degrees using the GD2 library.
    *
    * @param string $file
    * @param string $desfile
    * @param int $degrees
    * @param image $imgobj
    * @return boolean
    * @access private
    */
    function _rotateImageGD2($file, $desfile, $degrees, $imgobj) {
        // GD can only handle JPG & PNG images
        if ($imgobj->_type !== "jpg" && $imgobj->_type !== "jpeg" && $imgobj->_type !== "png" && $imgobj->_type !== "gif") {
            return false;
        }
        if ($imgobj->_type == "gif" && !function_exists("imagecreatefromgif")) {
        	return false;
        }
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            $src_img = @imagecreatefromjpeg($file);
        } else if ($imgobj->_type == "png") {
            $src_img = @imagecreatefrompng($file);
           	$dst_img = imagecreatetruecolor($imgobj->_size[0],$imgobj->_size[1]);
			$img_white = imagecolorallocate($dst_img, 255, 255, 255); // set background to white
			$img_return = imagefill($dst_img, 0, 0, $img_white);
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $imgobj->_size[0], $imgobj->_size[1], $imgobj->_size[0], $imgobj->_size[1]);
			$src_img = $dst_img;
        } else {
        	$src_img = @imagecreatefromgif($file);
        	$dst_img = imagecreatetruecolor($imgobj->_size[0],$imgobj->_size[1]);
			$img_white = imagecolorallocate($dst_img, 255, 255, 255); // set background to white
			$img_return = imagefill($dst_img, 0, 0, $img_white);
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $imgobj->_size[0], $imgobj->_size[1], $imgobj->_size[0], $imgobj->_size[1]);
			$src_img = $dst_img;
        }
        if (!$src_img) {
            return false;
        }
        // The rotation routine...
        $dst_img = imagerotate($src_img, $degrees, 0);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            imagejpeg($dst_img, $desfile, $this->_JPEG_quality);
        } else if ($imgobj->_type == "png") {
            imagepng($dst_img, $desfile);
        } else {
        	imagegif($dst_img, $desfile);
        }
        imagedestroy($src_img);
        imagedestroy($dst_img);
        return true;
    }
    /**
    * this creates creates a watermarked image from an image file - can be a .jpg .gif or .png file
    * where $wm_file is a mostly transparent gif image with the watermark
    *
    * @param string $file
    * @param string $desfile
    * @param Image $imgobj
    * @return boolean
    * @access public
    */
    function watermarkImage($file, $desfile, $imgobj) {
        switch ($this->_conversiontype){
            //Imagemagick
            case 1:
                if($this->_watermarkImageIM($file, $desfile, $this->_wm_file, $this->_wm_position, $imgobj))
                    return true;
                else
                    return false;
                break;
            //NetPBM
            case 2:
                if($this->_watermarkImageNETPBM($file, $desfile, $this->_wm_file, $this->_wm_position, $imgobj))
                    return true;
                else
                    return false;
                break;
            //GD1 & GD2
            case 3 || 4:
                if($this->_watermarkImageGD($file, $desfile, $this->_wm_file, $this->_wm_position, $imgobj))
                    return true;
                else
                    return false;
                break;
        }
        return true;
    }
    /**
    * Apply a watermark to an image using the ImageMagick library
    *
    * @param string $file
    * @param string $desfile
    * @param string $wm_file The absolute location to the watermark image.
    * @param string $position Position of the watermark on the Destination image.
    * @param Image $imgobj
    * @return boolean
    * @access private
    */
    function _watermarkImageIM($file, $desfile, $wm_file, $position, $imgobj) {
        $imginfo_wm = getimagesize($wm_file);

        $imagewidth = $imgobj->_size[0];
        $imageheight = $imgobj->_size[1];
        $watermarkwidth = $imginfo_wm[0];
        $watermarkheight = $imginfo_wm[1];		
        $width_left = $imagewidth - $watermarkwidth;
        $height_left = $imageheight - $watermarkheight;
        switch ($position) {
            case "TL": // Top Left
                $startwidth = $width_left >= 5 ? 4 : $width_left;
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "TM": // Top middle 
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "TR": // Top right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "CL": // Center left
                $startwidth = $width_left >= 5 ? 4 : $width_left;
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            default:
            case "C": // Center (the default)
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            case "CR": // Center right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            case "BL": // Bottom left
                $startwidth = $width_left >= 5 ? 5 : $width_left;
                $startheight = $imageheight - $watermarkheight-5;
                break;
            case "BM": // Bottom middle
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = $imageheight - $watermarkheight-5;
                break;
            case "BR": // Bottom right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = $imageheight - $watermarkheight-5;
                break;
        }

		$cmd = $this->_IM_path."convert -draw \"image over  $startwidth,$startheight 0,0 '$wm_file'\" \"$file\" \"$desfile\"";
		$output = $retval = null;
        exec($cmd, $output, $retval);
        
        if($retval) {
            return false;
        } else {
            return true;
        }
    }
    /**
    * Apply a watermark to an image using the NetPBM library
    *
    * @param string $file
    * @param string $desfile
    * @param string $wm_file The absolute location to the watermark image.
    * @param string $position Position of the watermark on the Destination image.
    * @param Image $imgobj
    * @return boolean
    * @access private
    */
    function _watermarkImageNetPBM($file, $desfile, $wm_file, $position, $imgobj) {
        return true; //TODO
    }
    /**
    * Apply a watermark to an image using the GD 1.x/ 2.x library
    *
    * @param string $file
    * @param string $desfile
    * @param string $wm_file The absolute location to the watermark image.
    * @param string $position Position of the watermark on the Destination image.
    * @param Image $imgobj
    * @return boolean
    * @access private
    */
    function _watermarkImageGD($file, $desfile, $wm_file, $position, $imgobj) {
        global $zoom;
        if ($imgobj->_size == null) {
            return false;
        }
        if ($imgobj->_type !== "jpg" && $imgobj->_type !== "jpeg" && $imgobj->_type !== "png" && $imgobj->_type !== "gif") {
            return false;
        }
        if ($imgobj->_type == "gif" && !function_exists("imagecreatefromgif")) {
        	return false;
        }
        
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            $image = @imagecreatefromjpeg($file);
        } else if ($imgobj->_type == "png") {
            $image = @imagecreatefrompng($file);
        } else {
        	$image = @imagecreatefromgif($file);
        }
        if (!$image) { return false; }
        
        $imginfo_wm = getimagesize($wm_file);
        $imgtype_wm = ereg_replace(".*\.([^\.]*)$", "\\1", $wm_file);
        if ($imgtype_wm == "jpg" || $imgtype_wm == "jpeg") {
            $watermark = @imagecreatefromjpeg($wm_file);
        } else {
            $watermark = @imagecreatefrompng($wm_file);
        }
        if (!$watermark) { return false; }
        
        $imagewidth = imagesx($image);
        $imageheight = imagesy($image);
        $watermarkwidth = $imginfo_wm[0];
        $watermarkheight = $imginfo_wm[1];		
        $width_left = $imagewidth - $watermarkwidth;
        $height_left = $imageheight - $watermarkheight;
        switch ($position) {
            case "TL": // Top Left
                $startwidth = $width_left >= 5 ? 4 : $width_left;
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "TM": // Top middle 
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "TR": // Top right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = $height_left >= 5 ? 5 : $height_left;
                break;
            case "CL": // Center left
                $startwidth = $width_left >= 5 ? 4 : $width_left;
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            default:
            case "C": // Center (the default)
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            case "CR": // Center right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = intval(($imageheight - $watermarkheight) / 2);
                break;
            case "BL": // Bottom left
                $startwidth = $width_left >= 5 ? 5 : $width_left;
                $startheight = $imageheight - $watermarkheight-5;
                break;
            case "BM": // Bottom middle
                $startwidth = intval(($imagewidth - $watermarkwidth) / 2);
                $startheight = $imageheight - $watermarkheight-5;
                break;
            case "BR": // Bottom right
                $startwidth = $imagewidth - $watermarkwidth-4;
                $startheight = $imageheight - $watermarkheight-5;
                break;
        }
        imagecopy($image, $watermark, $startwidth, $startheight, 0, 0, $watermarkwidth, $watermarkheight);
        @$zoom->platform->unlink($desfile);
        if ($imgobj->_type == "jpg" || $imgobj->_type == "jpeg") {
            imagejpeg($image, $desfile, $this->_JPEG_quality);
        } else if ($imgobj->_type == "png") {
            imagepng($image, $desfile);
        } else {
        	imagegif($image, $desfile);
        }
        imagedestroy($image);
        imagedestroy($watermark);
        return true;
    }
    /**
    * Generate a thumbnail from a video stream using the FFMpeg library.
    *
    * @param string $file
    * @param string $size
    * @param string $filename
    * @return boolean
    * @access public
    */
    function createMovieThumb($file, $size, $filename) {
        global $mosConfig_absolute_path, $zoom;
        if ($this->_FFMPEG_path == 'auto') {
            $this->_FFMPEG_path = '';
        } else {
            if (!empty($this->_FFMPEG_path) && !$zoom->_CONFIG['override_FFMPEG']) {
                if (!$zoom->platform->is_dir($this->_FFMPEG_path)) {
                    echo ("<div align=\"center\"><font color=\"red\">Error: your FFmpeg path is not correct! Please (re)specify it in the Admin-system under 'Settings'</font></div>");
                    return false;
                }
            }
        }
        $desfile = ereg_replace("(.*)\.([^\.]*)$", "\\1", $filename).".jpg";
        if ($tempdir = $zoom->createTempDir()) {
            $gen_path = $mosConfig_absolute_path."/".$tempdir;
            $cmd = $this->_FFMPEG_path."ffmpeg -an -y -t 0:0:0.001 -i \"$file\" -f mjpeg \"$gen_path/file.jpg\"";
            $output = $retval = null;
            exec($cmd, $output, $retval);
            if (!$retval) {
                if ($zoom->platform->file_exists($gen_path."/file.jpg")) {
                    $the_thumb = $gen_path."/file.jpg";
                    $imgobj = new image(0);
                    $imgobj->_filename = $desfile;
                    $imgobj->_type = "jpg";
                    $imgobj->_size = $zoom->platform->getimagesize($the_thumb);
                    $target = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->getDir()."/thumbs/".$desfile;
                    if (!$this->resizeImage($the_thumb, $target, $size, $imgobj)) {
                        return false;
                    } else {
                        @$zoom->deldir($gen_path);
                        return true;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /**
    * Perform a search with a given search-string in PDF-index files generated by zOOm.
    *
    * @param string $file
    * @param string $searchText
    * @return boolean
    * @access public
    */
    function searchPdf($file, $searchText) {
        global $mosConfig_absolute_path, $zoom;
        if (empty($file->_filename)) {
            $file->getInfo();
        }
        $source = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$file->getDir()."/".ereg_replace("(.*)\.([^\.]*)$", "\\1", $file->_filename).".txt";
        if ($zoom->platform->is_file($source)) {
            $txt = strtolower(file_get_contents($source));
            if (preg_match("/$searchText/", $txt)) {
                return true;
            }else{
                return false;
            }
            unset($txt);
        } else {
            return false;
        }
    }
    /**
    * Extract the raw text from a PDF document (format: '{filename}.txt') with the Xpdf library (pdftotext)
    *
    * @param string $file
    * @param string $filename
    * @return boolean
    * @access public
    */
    function indexDocument($file, $filename) {
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
                    echo ("<div align=\"center\"><font color=\"red\">Error: your PDFtoText path is not correct! Please (re)specify it in the Admin-system under 'Settings'</font></div>");
                    return false;
                }
            }
        }
        $desfile = ereg_replace("(.*)\.([^\.]*)$", "\\1", $filename).".txt";
        $target = $mosConfig_absolute_path."/".$zoom->_CONFIG['imagepath'].$zoom->_gallery->getDir()."/".$desfile;
        $cmd = $this->_PDF_path."pdftotext \"$file\" \"$target\"";
        $output = $retval = null;
        exec($cmd, $output, $retval);
        if(!$retval) {
            return true;
        } else {
            return false;
        }
    }
    /**
    * Search a local directory - including subdirectories - for media.
    *
    * @param string $dir
    * @return array
    * @access public
    */
    function parseDir($dir) {
        global $zoom;
        // start the scan...(open the local dir)
        $images = array();
        $handle = $zoom->platform->opendir($dir);
        while (($file = $zoom->platform->readdir($handle)) != false) {
            if ($file != "." && $file != "..") {
                $tag = ereg_replace(".*\.([^\.]*)$", "\\1", $file);
                $tag = strtolower($tag);
                if ($zoom->acceptableFormat($tag)) {
                    // Tack it onto images...
                    $images[] = $file;
                }
            }
        }
        $zoom->platform->closedir($handle);
        return $images;
    }
    function gettag($dir) {
		global $zoom;
        // start the scan...(open the local dir)
        $tags = array();
        $handle = $zoom->platform->opendir($dir);
        while (($file = $zoom->platform->readdir($handle)) != false) {
            if ($file != "." && $file != "..") {
                $tag = ereg_replace(".*\.([^\.]*)$", "\\1", $file);
                $tag = strtolower($tag);
                if ($zoom->acceptableFormat($tag)) {
                    // Tack it onto images...
                    $tags[] = $tag;
                }
            }
        }
        $zoom->platform->closedir($handle);
        return $tags;
    }
    /**
    * Image Libraries auto-detection.
    *
    * @return array
    * @access public
    */
    function getImageLibs() {
        // do auto-detection on the available graphics libraries
        // This assumes the executables are within the shell's path
        $imageLibs= array();
        // do various tests:
        if (!(bool)ini_get('safe_mode')) {
            if ($testIM = $this->_testIM()) {
                $imageLibs['imagemagick'] = $testIM;
            }
            if ($testNetPBM = $this->_testNetPBM()) {
                $imageLibs['netpbm'] = $testNetPBM;
            }
            if ($testFFmpeg = $this->_testFFmpeg()) {
                $imageLibs['ffmpeg'] = $testFFmpeg;
            }
            if ($testXpdf = $this->_testXpdf()) {
                $imageLibs['pdftotext'] = $testXpdf;
            }
        }			
        $imageLibs['gd'] = $this->_testGD();		
        return $imageLibs;
    }
    /**
    * Detect if ImageMagick is available on the system.
    *
    * @return string
    * @access private
    */
    function _testIM() {
    	global $output, $status;
        @exec('convert -version', $output, $status);
        if (!$status) {
            if(preg_match("/imagemagick[ \t]+([0-9\.]+)/i",$output[0],$matches)) {
                return $matches[0];
            }
        }
        unset($output, $status);
    }
    /**
    * Detect if NetPBM is available on the system.
    *
    * @return string
    * @access private
    */
    function _testNetPBM() {
    	global $output, $status;
        @exec('jpegtopnm -version 2>&1',  $output, $status);
        if (!$status) {
            if (preg_match("/netpbm[ \t]+([0-9\.]+)/i",$output[0],$matches)) {
                return $matches[0];
            }
        }
        unset($output, $status);
    }
    /**
    * Detect if GD is available on the system.
    *
    * @return string
    * @access private
    */
    function _testGD() {
        $gd = array();
        $GDfuncList = get_extension_funcs('gd');
        ob_start();
        @phpinfo(INFO_MODULES);
        $output=ob_get_contents();
        ob_end_clean();
        $matches[1]='';
        if (preg_match("/GD Version[ \t]*(<[^>]+>[ \t]*)+([^<>]+)/s",$output,$matches)) {
            $gdversion = $matches[2];
        }
        if ($GDfuncList) {
            if (in_array('imagegd2', $GDfuncList)) {
                $gd['gd2'] = $gdversion;
            } else {
                $gd['gd1'] = $gdversion;
            }
        }
        return $gd;
    }
    /**
    * Detect if FFmpeg is available on the system.
    *
    * @return string
    * @access private
    */
    function _testFFmpeg() {
    	global $output, $status;
        @exec('ffmpeg -h',  $output, $status);
        if (!empty($output[0])) {
            if (preg_match("/ffmpeg.*(\.[0-9])/i",$output[0],$matches)) {
                return $matches[0];
            }
        }
        unset($output, $status);
    }
    /**
    * Detect if Xpdf is available on the system.
    *
    * @return string
    * @access private
    */
    function _testXpdf() {
    	global $output, $status;
        @exec('pdftotext',  $output, $status);
        if (!empty($output[0])) {
            if (preg_match("/pdftotext/i",$output[0], $matches)) {
                return "pdftotext";
            }
        }
        unset($output, $status);
    }
    /**
    * Get the ID3v2.x tag from an mp3 file.
    *
    * @param string $file
    * @return array
    * @access public
    */
    function getid3($file) {
        global $mosConfig_absolute_path, $database;
        require_once($mosConfig_absolute_path."/components/com_zoom/lib/getid3/getid3.php");
        require_once($mosConfig_absolute_path."/components/com_zoom/lib/getid3/extension.cache.mysql.php");
        $getid3 = new getID3_cached_mysql($database);
        $fileInfo = $getid3->analyze($file);
        getid3_lib::CopyTagsToComments($fileInfo);
        return $fileInfo;
    }
    /**
    * Give a fancy HTML layout to the found ID3 data.
    *
    * @param array $id3_data
    * @return string
    * @access public
    */
    function interpret_ID3_to_HTML($id3_data){
        $title = (!empty($id3_data["comments_html"]["title"][0])) ? $id3_data["comments_html"]["title"][0] : "no title";
        $artist = (!empty($id3_data["comments_html"]["artist"][0])) ? $id3_data["comments_html"]["artist"][0] : "no artist";
        $album = (!empty($id3_data["comments_html"]["album"][0])) ? $id3_data["comments_html"]["album"][0] : "no album";
        $year = (!empty($id3_data["id3v1"]["year"])) ? $id3_data["id3v1"]["year"] : "no year";
        $comment = (!empty($id3_data["comment"])) ? $id3_data["comment"] : "no comment";
        $genre = (!empty($id3_data["comments_html"]["genre"][0])) ? $id3_data["comments_html"]["genre"][0] : "no genre";
        $html = ("\t\t<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"70%\">\n"
            . "\t\t<tr>\n"
            . "\t\t<td align=\"left\">"._ZOOM_ID3_LENGTH."</td>\n"
            . "\t\t<td align=\"left\">".$id3_data["playtime_string"]."</td>\n"
            . "\t\t</tr>"
            . "\t\t<tr>\n"
            . "\t\t<td align=\"left\">"._ZOOM_ID3_QUALITY."</td>\n"
            . "\t\t<td align=\"left\">".$id3_data["bitrate"]." bit/s @ ".$id3_data['audio']['sample_rate']." Hz ".$id3_data['audio']['channelmode']."</td>\n"
            . "\t\t</tr>"
            . "\t\t<tr>\n"
            . "\t\t<td align=\"left\">"._ZOOM_ID3_TITLE."</td>\n"
            . "\t\t<td align=\"left\">".$title."</td>\n"
            . "\t\t</tr>"
            . "\t\t<tr>\n"
            . "\t\t<td align=\"left\">"._ZOOM_ID3_ARTIST."</td>\n"
            . "\t\t<td align=\"left\">".$artist."</td>\n"
            . "\t\t</tr>"
            . "\t\t<tr>\n"
            . "\t\t<td align=\"left\">"._ZOOM_ID3_ALBUM."</td>\n"
            . "\t\t<td align=\"left\">".$album."</td>\n"
            . "\t\t</tr>"
            . "\t\t<tr>\n"
            . "\t\t<td align=\"left\">"._ZOOM_ID3_YEAR."</td>\n"
            . "\t\t<td align=\"left\">".$year."</td>\n"
            . "\t\t</tr>"
            . "\t\t<tr>\n"
            . "\t\t<td align=\"left\">"._ZOOM_ID3_COMMENT."</td>\n"
            . "\t\t<td align=\"left\">".$comment."</td>\n"
            . "\t\t</tr>"
            . "\t\t<tr>\n"
            . "\t\t<td align=\"left\">"._ZOOM_ID3_GENRE."</td>\n"
            . "\t\t<td align=\"left\">".$genre."</td>\n"
            . "\t\t</tr>\n"
            . "\t\t</table>");
        return $html;
    }
    /**
    * Give a fancy HTML layout to the found video meta-data.
    *
    * @param array $metadata
    * @return string
    * @access public
    */
    function interpret_META_to_HTML($metadata) {
        $title = (!empty($id3_data["comments_html"]["title"][0])) ? $id3_data["comments_html"]["title"][0] : "no title";
        $artist = (!empty($id3_data["comments_html"]["artist"][0])) ? $id3_data["comments_html"]["artist"][0] : "no artist";
        $album = (!empty($id3_data["comments_html"]["album"][0])) ? $id3_data["comments_html"]["album"][0] : "no album";
        $year = (!empty($id3_data["id3v1"]["year"])) ? $id3_data["id3v1"]["year"] : "no year";
        $comment = (!empty($id3_data["comment"])) ? $id3_data["comment"] : "no comment";
        $genre = (!empty($metadata["comments_html"]["genre"][0])) ? $metadata["comments_html"]["genre"][0] : "no genre";
        $html = ("\t\t<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"70%\">\n"
            . "\t\t<tr>\n"
            . "\t\t<td align=\"left\">"._ZOOM_ID3_LENGTH."</td>\n"
            . "\t\t<td align=\"left\">".$metadata["playtime_string"]."</td>\n"
            . "\t\t</tr>"
            . "\t\t<tr>\n"
            . "\t\t<td align=\"left\">"._ZOOM_VIDEO_QUALITY."</td>\n"
            . "\t\t<td align=\"left\">".$metadata["bitrate"]." bit/s @ ".$metadata['video']['frame_rate']." frame/s</td>\n"
            . "\t\t</tr>"
            . "\t\t<tr>\n"
            . "\t\t<td align=\"left\">"._ZOOM_VIDEO_RESOLUTION."</td>\n"
            . "\t\t<td align=\"left\">".$metadata["video"]["resolution_x"]." x ".$metadata["video"]["resolution_y"]."</td>\n"
            . "\t\t</tr>"
            . "\t\t</table>");
        return $html;
    }
    //--------------------Error handling functions-------------------------//
    /**
    * Display the errors the ToolBox encountered in a HTML table.
    *
    * @access public
    */
    function displayErrors(){
        global $zoom;
		if ($this->_err_num <> 0){
            echo "<center><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"70%\">";
            echo "<tr class=\"sectiontableheader\"><td width=\"100\" align=\"left\">Medium Name</td><td align=\"left\">Error type</td></tr>";
            $tabcnt = 0;
            for ($x = 1; $x <= $this->_err_num; $x++){
                echo "<tr class=\"".$zoom->_tabclass[$tabcnt]."\" align=\"left\"><td>".$this->_err_names[$x]."</td><td align=\"left\">".$this->_err_types[$x]."</td></tr>";
                if ($tabcnt == 1){
                    $tabcnt = 0;
                } else {
                    $tabcnt++;
                }
            }
            echo "</table></center>";
        }
    }
    //--------------------END error handling functions----------------------//
}