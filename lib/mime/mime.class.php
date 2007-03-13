<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Ian Eure <ieure@php.net>                                    |
// +----------------------------------------------------------------------+
//
// $Id:mime.class.php 139 2007-03-12 09:21:44Z mikedeboer $
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_absolute_path;
require_once($mosConfig_absolute_path . "/components/com_zoom/lib/mime/mime_helper.class.php");

/**
 * Class for working with MIME types
 *
 * @version @version@
 * @package zOOmGallery
 * @subpackage MIME
 * @author Ian Eure <ieure@php.net>
 * @author Mike de Boer <mike@zoomfactory.org>
 */
class MIME_Type {
    /**
     * The MIME media type
     *
     * @var string
     */
    var $media = '';
    
    /**
     * The MIME media sub-type
     *
     * @var string
     */
    var $subType = '';
    
    /**
     * Optional MIME parameters
     *
     * @var array
     */
    var $parameters = array();
    
    /**
     * List of valid media types
     *
     * @var array
     */
    var $validMediaTypes = array(
        'text',
        'image',
        'audio',
        'video',
        'application',
        'multipart',
        'message'
    );


    /**
     * Constructor.
     *
     * If $type is set, if will be parsed and the appropriate class vars set. If not,
     * you get an empty class. This is useful, but not quite as useful as parsing a
     * type.
     *
     * @param  string $type MIME type
     * @return void
     */
    function MIME_Type($type = false)
    {
        if ($type) {
            $this->parse($type);
        }
    }


    /**
     * Parse a mime-type
     *
     * @param  $type string MIME type to parse
     * @return void
     */
    function parse($type)
    {
        global $mosConfig_absolute_path;
        $this->media = $this->getMedia($type);
        $this->subType = $this->getSubType($type);
        if (MIME_Type::hasParameters($type)) {
            require_once $mosConfig_absolute_path.'/components/com_zoom/lib/mime/mime_parameter.php';
            foreach (MIME_Type::getParameters($type) as $param) {
                $param = &new MIME_Type_Parameter($param);
                $this->parameters[$param->name] = $param;
            }
        }
    }


    /**
     * Does this type have any parameters?
     *
     * @param  $type   string MIME type to check
     * @return boolean true if $type has parameters, false otherwise
     * @static
     */
    function hasParameters($type)
    {
        if (strstr($type, ';')) {
            return true;
        }
        return false;
    }


    /**
     * Get a MIME type's parameters
     *
     * @param  $type string MIME type to get parameters of
     * @return array $type's parameters
     * @static
     */
    function getParameters($type)
    {
        $params = array();
        $tmp = explode(';', $type);
        for ($i = 1; $i < count($tmp); $i++) {
            $params[] = trim($tmp[$i]);
        }
        return $params;
    }
    

    /**
     * Strip paramaters from a MIME type string
     *
     * @param  string $type MIME type string
     * @return string MIME type with parameters removed
     * @static
     */
    function stripParameters($type)
    {
        if (strstr($type, ';')) {
            return substr($type, 0, strpos($type, ';'));
        }
        return $type;
    }


    /**
     * Get a MIME type's media
     *
     * @note   'media' refers to the portion before the first slash
     * @param  $type  string MIME type to get media of
     * @return string $type's media
     * @static
     */
    function getMedia($type)
    {
        $tmp = explode('/', $type);
        return strtolower($tmp[0]);
    }


    /**
     * Get a MIME type's subtype
     *
     * @param  $type string MIME type to get subtype of
     * @return string $type's subtype
     * @static
     */
    function getSubType($type)
    {
        $tmp = explode('/', $type);
        $tmp = explode(';', $tmp[1]);
        return strtolower(trim($tmp[0]));
    }


    /**
     * Create a textual MIME type from object values
     *
     * This function performs the opposite function of parse().
     *
     * @return string MIME type string
     */
    function get()
    {
        $type = strtolower($this->media.'/'.$this->subType);
        if (count($this->parameters)) {
            foreach ($this->parameters as $key => $null) {
                $type .= '; '.$this->parameters[$key]->get();
            }
        }
        return $type;
    }


    /**
     * Is this type experimental?
     *
     * @note   Experimental types are denoted by a leading 'x-' in the media or
     *         subtype, e.g. text/x-vcard or x-world/x-vrml.
     * @param  string $type MIME type to check
     * @return boolean true if $type is experimental, false otherwise
     * @static
     */
    function isExperimental($type)
    {
        if (substr(MIME_Type::getMedia($type), 0, 2) == 'x-' ||
            substr(MIME_Type::getSubType($type), 0, 2) == 'x-') {
            return true;
        }
        return false;
    }


    /**
     * Is this a vendor MIME type?
     *
     * @note   Vendor types are denoted with a leading 'vnd. in the subtype.
     * @param  string  $type MIME type to check
     * @return boolean true if $type is a vendor type, false otherwise
     * @static
     */
    function isVendor($type)
    {
        if (substr(MIME_Type::getSubType($type), 0, 4) == 'vnd.') {
            return true;
        }
        return false;
    }


    /**
     * Is this a wildcard type?
     *
     * @param  string  $type MIME type to check
     * @return boolean true if $type is a wildcard, false otherwise
     * @static
     */
    function isWildcard($type)
    {
        if ($type == '*/*' || MIME_Type::getSubtype($type) == '*') {
            return true;
        }
        return false;
    }


    /**
     * Perform a wildcard match on a MIME type
     *
     * Example:
     * MIME_Type::wildcardMatch('image/*', 'image/png')
     *
     * @param  string  $card Wildcard to check against
     * @param  string  $type MIME type to check
     * @return boolean true if there was a match, false otherwise
     */
    function wildcardMatch($card, $type)
    {
        if (!MIME_Type::isWildcard($card)) {
            return false;
        }
        
        if ($card == '*/*') {
            return true;
        }
        
        if (MIME_Type::getMedia($card) ==
            MIME_Type::getMedia($type)) {
            return true;
        }
        return false;
    }


    /**
     * Add a parameter to this type
     *
     * @param  string $name    Attribute name
     * @param  string $value   Attribute value
     * @param  string $comment Comment for this parameter
     * @return void
     */
    function addParameter($name, $value, $comment = false)
    {
        $tmp = &new MIME_Type_Parameter;
        $tmp->name = $name;
        $tmp->value = $value;
        $tmp->comment = $comment;
        $this->parameters[$name] = $tmp;
    }


    /**
     * Remove a parameter from this type
     *
     * @param  string $name Parameter name
     * @return void
     */
    function removeParameter($name)
    {
        unset ($this->parameters[$name]);
    }


    /**
     * Autodetect a file's MIME-type
     *
     * This function may be called staticly.
     *
     * @param  string $file        Path to the file to get the type of
     * @param  string $custom_mime An optional custom 'default' value, mostly used for the filetype sent by the users' browser
     * @param  bool   $params      Append MIME parameters if true
     * @return string $file's      MIME-type on success, false boolean otherwise
     * @since 1.0.0beta1
     * @static
     */
    function autoDetect($file, $custom_mime = null, $custom_ext = null, $params = false)
    {
        if (function_exists('mime_content_type')) {
            $type = mime_content_type($file);
            if ($type == "application/octet-stream" || $type == "application/unknown") {
                if (!empty($custom_mime)) {
                	$type = $custom_mime;
                } elseif (!empty($custom_ext)) {
                    $type = MIME_Helper::convertExtensionToMime($custom_ext);
                }
            }
        } elseif (!empty($custom_mime)) {
            $type = $custom_mime;
        } else {
            $type = MIME_Type::_fileAutoDetect($file);
        }
        
        // _fileAutoDetect() may have returned an error.
        if ($type === false) {
            return $type;
        }
        //return PEAR::raiseError("Sorry, can't autodetect; you need the mime_magic extension or System_Command and 'file' installed to use this function.");

        if (!MIME_Helper::convertMimeToExtension($type)) {
        	$type = MIME_Helper::convertExtensionToMime($custom_mime);
        }
        
        // flv (Flash Video format) files need exceptional handling (for now I'll provide a fictional mimetype)
        if ($custom_ext == "flv") {
        	$type = "video/x-flv";
        }

        // Don't return an empty string
        if (!$type || !strlen($type)) {
            //return PEAR::raiseError("Sorry, couldn't determine file type.");
            return false;
        }

        // Strip parameters if present & requested
        if (MIME_Type::hasParameters($type) && !$params) {
            $type = MIME_Type::stripParameters($type);
        }

        return $type;
    }

    /**
     * Autodetect a file's MIME-type with 'file' and System_Command
     *
     * This function may be called staticly.
     *
     * @param  string $file   Path to the file to get the type of
     * @return string $file's MIME-type
     * @since 1.0.0beta1
     * @static
     */
    function _fileAutoDetect($file)
    {
        // Sanity checks
        if (!file_exists($file)) {
            //return PEAR::raiseError("File \"$file\" doesn't exist");
            return false;
        }
        
        if (!is_readable($file)) {
            //return PEAR::raiseError("File \"$file\" is not readable");
            return false;
        }
		$output = '';
		$status = null;
        @exec("file -bi '{$file}'", $output, $status);

        if (@$status) {
            return false;
        } else {
        	return $output;	
		}
    }
}