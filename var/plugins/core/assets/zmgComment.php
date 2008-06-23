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
        $this->zmgTable(zmgFactory::getConfig()->getTableName('comments'), 'cid', $db);
    }
    
    /**
     * Get the formatted comment itself.
     *
     * @param string $dir_prefix
     * @return string
     * @access public
     */
    function getComment($dir_prefix = "") {
        return $this->_processSmilies($this->content, $dir_prefix);
    }
    
    /**
     * Get the date of a comment (of when it was submitted). Properly formatted.
     *
     * @return datetime
     * @access public
     */
    function getDate() {
        return $this->date; //TODO: date formatting!
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
    function _processSmilies($message, $url_prefix = '') { 
        static $orig, $repl; 

        if (!isset($orig)) { 
            $orig = $repl = array();
            $smilies = & zmgCommentHelper::getSmiliesTable(); 
            for ($i = 0; $i < count($smilies); $i++) {
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
    
    function toJSON() {
    	$json = & zmgFactory::getJSON();
        return ("'comment': {
            'cid'      : $this->cid,
            'mid'      : $this->mid,
            'name'     : ".$json->encode($this->name).",
            'content'  : ".$json->encode($this->getContent()).",
            'date_add' : ".$json->encode($this->getDate())."
        }");
    }
}

class zmgCommentHelper {
	function &getSmiliesTable() {
		static $smilies;
        
        if (!isset($smilies)) {
        	$smilies = array(
                array(':!:', 'icon_exclaim.gif', 'Exclamation'),
                array(':?:', 'icon_question.gif', 'Question'),
                array(':D', 'icon_biggrin.gif', 'Very Happy'),
                array(':d', 'icon_biggrin.gif', 'Very Happy'),
                array(':-D', 'icon_biggrin.gif', 'Very Happy'),
                array(':grin:', 'icon_biggrin.gif', 'Very Happy'),
                array(':)', 'icon_smile.gif', 'Smile'),
                array(':-)', 'icon_smile.gif', 'Smile'),
                array(':smile:', 'icon_smile.gif', 'Smile'),
                array(':(', 'icon_sad.gif', 'Sad'),
                array(':-(', 'icon_sad.gif', 'Sad'),
                array(':sad:', 'icon_sad.gif', 'Sad'),
                array(':o', 'icon_surprised.gif', 'Surprised'),
                array(':-o', 'icon_surprised.gif', 'Surprised'),
                array(':eek:', 'icon_surprised.gif', 'Surprised'),
                array(':shock:', 'icon_eek.gif', 'Shocked'),
                array(':?', 'icon_confused.gif', 'Confused'),
                array(':-?', 'icon_confused.gif', 'Confused'),
                array(':???:', 'icon_confused.gif', 'Confused'),
                array('8)', 'icon_cool.gif', 'Cool'),
                array('8-)', 'icon_cool.gif', 'Cool'),
                array(':cool:', 'icon_cool.gif', 'Cool'),
                array(':lol:', 'icon_lol.gif', 'Laughing'),
                array(':x', 'icon_mad.gif', 'Mad'),
                array(':-x', 'icon_mad.gif', 'Mad'),
                array(':mad:', 'icon_mad.gif', 'Mad'),
                array(':P', 'icon_razz.gif', 'Razz'),
                array(':p', 'icon_razz.gif', 'Razz'),
                array(':-P', 'icon_razz.gif', 'Razz'),
                array(':razz:', 'icon_razz.gif', 'Razz'),
                array(':oops:', 'icon_redface.gif', 'Embarassed'),
                array(':cry:', 'icon_cry.gif', 'Crying or Very sad'),
                array(':evil:', 'icon_evil.gif', 'Evil or Very Mad'),
                array(':twisted:', 'icon_twisted.gif', 'Twisted Evil'),
                array(':roll:', 'icon_rolleyes.gif', 'Rolling Eyes'),
                array(':wink:', 'icon_wink.gif', 'Wink'),
                array(';)', 'icon_wink.gif', 'Wink'),
                array(';-)', 'icon_wink.gif', 'Wink'),
                array(':idea:', 'icon_idea.gif', 'Idea'),
                array(':arrow:', 'icon_arrow.gif', 'Arrow'),
                array(':|', 'icon_neutral.gif', 'Neutral'),
                array(':-|', 'icon_neutral.gif', 'Neutral'),
                array(':neutral:', 'icon_neutral.gif', 'Neutral'),
                array(':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green')
            );
        }
        
        return $smilies;
 	}
}

?>
