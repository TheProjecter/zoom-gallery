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

class zmgDatabase extends zmgError {
    function &getDBO() {
        return JFactory::getDBO();
    }
}

class zmgTable extends JTable {
    function zmgTable($table, $key, &$db) {
        parent::__construct($table, $key, $db);
    }
}
?>
