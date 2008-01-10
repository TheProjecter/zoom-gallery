<?php
/**
 * zOOm Media Gallery! - a multi-gallery component 
 * 
 * @package zmg
 * @subpackage core
 * @version $Revision$
 * @author Mike de Boer <mike AT zoomfactory.org>
 * @copyright Copyright &copy; 2007, Mike de Boer. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GPL
 */

defined('_ZMG_EXEC') or die('Restricted access');

class zmgComment extends zmgTable {
    /**
     * @var int
     * @access public
     */
    var $cid = null;
    /**
     * @var int
     * @access public
     */
    var $mid = null;
    /**
     * @var string
     * @access public
     */
    var $name = null;
    /**
     * @var string
     * @access public
     */
    var $content = null;
    /**
     * @var datetime
     * @access public
     */
    var $date_added = null;
    
    function zmgComment(&$db) {
        $this->zmgTable('#__zmg_comments', 'cid', $db);
    }
    /**
     * Replace phpBB smilies-code with relatively located images
     *
     * @param string $message
     * @param string $url_prefix
     * @param array $smilies
     * @return string
     * @access private
     */
    function _processSmilies($message, $url_prefix='', $smilies) { 
        global $orig, $repl; 
        if (!isset($orig)) { 
            $orig = $repl = array(); 
            for($i = 0; $i < count($smilies); $i++) { 
                $orig[] = "/(?<=.\W|\W.|^\W)" . preg_quote($smilies[$i][0], "/") . "(?=.\W|\W.|\W$)/"; 
                $repl[] = '<img src="'. $url_prefix .'images/smilies' . '/' . ($smilies[$i][1]) . '" alt="' . ($smilies[$i][2]) . '" border="0" />'; 
            } 
        }
        if (count($orig)) { 
            $message = preg_replace($orig, $repl, ' ' . $message . ' '); 
            $message = substr($message, 1, -1); 
        }
        return $message; 
    }
}
?>
