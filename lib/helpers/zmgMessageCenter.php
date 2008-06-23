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

class zmgMessageCenter {
    
    var $_messages = array();
    
    
    function zmgMessageCenter() {
        
    }
    
    function append($title, $description = '') {
        $this->_messages[] = array($title, $description);
    }
    
    function get($number = 0, $output = 'json') {
        $messages = array();
        
        if ($number === 0) {
            $messages = $this->_messages;
            $this->clear();
        } else if (intval($number) > 0) {
            $counter = 0;
            for ($i = count($this->_messages) - 1; $i >= 0
              && $counter < $number; $i--) {
                $messages[] = array_pop($this->_messages);
                $counter++;
            }
        } else {
            return zmgError::throwError('zmgMessageCenter::invalid number of messages requested');
        }
        
        if ($output == "json") {
            $out = "'messagecenter': {\n"
             . "  'messages': [\n";
            $count = 0;
            $msg_count = count($messages);
            foreach ($messages as $msg) {
                $count++;
                $out .= $this->_toJSON($msg) . (($count != $msg_count) ? ",\n" : "\n"); 
            }
            return $out . "  ]\n"
             . "}\n";
        } else if ($output == "xml") {
            //TODO: convert messages to XML
        }

        // throw-out the object if no output method is given
        return $messages;
    }

    function getAll() {
        return $this->get(0, 'raw');
    }

    function setAll($messages) {
        if (empty($messages) || !is_array($messages)) {
            return zmgError::throwError('Illegal access to zmgMessageCenter::setAll');
        }

        $this->_messages = $messages;
    }

    function clear() {
        $this->_messages = array();
    }

    function countMessages() {
        return count($this->_messages);
    }
    
    function _toJSON($msg) {
        $json = & zmgFactory::getJSON();
        return ("{
            'title'    : ".$json->encode($msg[0]).",
            'descr'    : ".$json->encode($msg[1])."
        }");
    }
}

?>

