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

define('_ZMG_RPC_RESULT_OK', 'OK');
define('_ZMG_RPC_RESULT_KO', 'KO');

/**
 * Class that assists Zoom in handling server request and takes care of
 * parsing and sending request headers
 *
 * @package zmg
 * @subpackage helpers
 */
class zmgRequestHelper {
    /**
     * Internal variable for storing rpc-results temporarily
     *
     * @var string
     */
    var $_result = null;

    function zmgRequestHelper() {
        $this->setResult(true);
    }

    function setResult($result = true) {
        if (is_bool($result)) {
            $result = ($result) ? _ZMG_RPC_RESULT_OK : _ZMG_RPC_RESULT_KO;
        }
        $this->_result = $result;
    }

    function getResult() {
        if ($this->_result == null) {
            return _ZMG_RPC_RESULT_OK;
        }
        $res = $this->_result;
        $this->_result = null;
        return $res;
    }

    /**
     * Send a set of headers to the client (i.e. browser) to tell it how to display
     * the data inside the response body.
     * @param string Specifies the contect type of the response body
     * @param boolean In case of an error message, this var will be set to TRUE
     * @param string Message describing the error in case of an error
     */
    function sendHeaders($type = "xml", $error = false, $error_msg = "") {
        //using 'echo @header()', because that seems to implicitely work in some
        //WAMP environments. Why? Pfff, beats me.
    	echo @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        //HTTP/1.1
        echo @header("Cache-Control: no-store, no-cache, must-revalidate");
        echo @header("Cache-Control: post-check=0, pre-check=0", false);
        //HTTP/1.0
        echo @header("Pragma: no-cache");

        $encoding = zmgFactory::getConfig()->get('locale/encoding');
        if (empty($encoding)) {
            $encoding = "UTF-8";
        }

        if ($error) {
            echo @header("zmg_result: " . _ZMG_RPC_RESULT_KO);
            echo @header("zmg_message: " . urlencode($error_msg));
        } else {
            echo @header("zmg_result: " . _ZMG_RPC_RESULT_OK);
        }

        if ($type == "xml") {
            echo @header("Content-type:text/xml; charset=" . $encoding);
    	} else if ($type == "plain") {
            echo @header("Content-type:text/plain; charset=" . $encoding);
    	} else if ($type == "js" || $type == "json") {
            echo @header("Content-type:text/javascript; charset=" . $encoding);
        }
    }
}

?>
