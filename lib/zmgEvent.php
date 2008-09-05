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

class zmgEvent {
    /**
     * @var string
     * @access public
     */
    var $type = null;
    /**
     * @var array
     * @access private
     */
    var $_arguments = array();

    function zmgEvent($type) {
        $this->type = $type;
    }

    function pass() {
        $args = func_get_args();
        foreach ($args as $arg) {
            //TODO: add some validation of some sort...
            $this->_arguments[] = $arg;
        }
    }

    function mapArguments($map) {
        if (!is_array($this->_arguments) || count($this->_arguments) == 0) {
            return;
        }

        $newArgs = array();

        for ($i = 0; $i < count($this->_arguments); $i++) {
            if (array_key_exists($i, $map) && is_string($map[$i])) {
                $newArgs[$map[$i]] = $this->_arguments[$i];
            } else {
                $newArgs[] = $this->_arguments[$i];
            }
        }

        $this->_arguments = $newArgs;

        return $this->_arguments;
    }

    function getArguments() {
        return $this->_arguments;
    }

    function getArgument($name) {
        if (isset($this->_arguments[$name]) && !empty($this->_arguments[$name])) {
            return $this->_arguments[$name];
        }

        return null;
    }
}
?>
