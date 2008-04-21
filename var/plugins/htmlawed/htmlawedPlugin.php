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

class zmgHtmlawedPlugin {
    function bindEvents() {
        //only the embed function needs to be bound to the 'onstartup' event.
        return array(
            "onvalidate" => array(
                "htmLawed" => array('input')
            )
        );
    }
    
    function htmLawed($event) {
        $in = $event->getArgument('input');

        zmgimport('org.zoomfactory.var.plugins.htmlawed.htmLawed');
        return htmLawed($in, 1, array());
    }
}
?>
