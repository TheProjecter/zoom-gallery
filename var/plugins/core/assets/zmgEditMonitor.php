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

/**
 * EditMon class; the zOOm Edit Monitor, which keeps track of user actions like
 * commenting / rating of a medium, sending eCards and creating lightboxes.
 *
 * @access public
 */
class zmgEditMonitor {
    /**
     * Register that a user has performed an action which may not be repeated
     * (specified by the admin).
     *
     * @param int $id
     * @param string $which
     * @param string $filename
     * @return boolean
     * @access public
     */
    function set($id, $which, $filename='') {
        $db = & zmgDatabase::getDBO();
        $table = zmgFactory::getConfig()->getTableName('editmon');

        $today = time() + intval(zmgEnv::getSessionLifetime());
        $sid = md5(zmgEnv::getSessionToken());

        if (!zmgEditMonitor::isEdited($id, $which, $filename)) {
            switch ($which){
                case 'comment':
                    $db->setQuery("INSERT INTO " . $table . " (user_session, "
                     . "comment_time, object_id) VALUES ('$sid', '$today','"
                     . zmgSQLEscape($id) . "')");
                    break;
                case 'vote':
                    $db->setQuery("INSERT INTO " . $table . " (user_session, "
                     . "vote_time, object_id) VALUES ('$sid', '$today', '"
                     . zmgSQLEscape($id) . "')");
                    break;
                case 'pass':
                    $db->setQuery("INSERT INTO " . $table . " (user_session, "
                     . "pass_time, object_id) VALUES ('$sid', '$today', '"
                     . zmgSQLEscape($id) . "')");
                    break;
                case 'lightbox':
                    $db->setQuery("INSERT INTO " . $table . " (user_session, "
                     . "lightbox_time, lightbox_file) VALUES ('$sid', '$today', '"
                     . zmgSQLEscape($filename) . "')");
                    break;
            }
            if (@$db->query()) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Delete rows of the '#__zmg_editmon' table which are out-of-date.
     *
     * @return void
     * @access public
     */
    function update() {
        $db = & zmgDatabase::getDBO();
        $table = zmgFactory::getConfig()->getTableName('editmon');
        
        $now = time();
        
        // first, delete rows containing vote, commment and gallery-pass times...
        $db->setQuery("DELETE FROM " . $table . " WHERE vote_time < '$now' OR "
         . "comment_time < '$now' OR pass_time < '$now'");
        @$db->query();
        // second, delete lightbox rows and files...
        $db->setQuery("SELECT lightbox_file FROM " . $table . " WHERE "
         . "lightbox_time < '$now'");
        $result = $db->query();
        if (mysql_num_rows($result) > 0) {
            while ($lightbox = mysql_fetch_object($result)) {
                @unlink($lightbox->lightbox_file);
                $db->setQuery("DELETE FROM " . $table . " WHERE lightbox_time < '$now'");
                $db->query();
            }
        }
    }

    /**
     * When an image or comment has been deleted, its EditMon record should be deleted.
     *
     * @param int $imgid
     * @return void
     * @access public
     */
    function purgeComments($mid, $limit_session = true) {
        $db = & zmgDatabase::getDBO();
        $table = zmgFactory::getConfig()->getTableName('editmon');

        $sid = md5(zmgEnv::getSessionToken());
        
        $db->setQuery("DELETE FROM " . $table . " WHERE "
         . ($limit_session ? "user_session = '$sid' AND " : "") . "object_id = $mid");
        return (bool) @$db->query();
    }

    /**
     * Checks if a user has the right to edit a medium, or if he/ she already
     * edited the medium before.
     *
     * @param int $id
     * @param string $which
     * @param string $filename
     * @return boolean
     * @access public
     */
    function isEdited($id, $which, $filename='') {
        $db = & zmgDatabase::getDBO();
        $table = zmgFactory::getConfig()->getTableName('editmon');

        $today = time() + intval(zmgEnv::getSessionLifetime());
        $sid = md5(zmgEnv::getSessionToken());
        
        switch ($which) {
            case 'comment':
                $db->setQuery("SELECT edtid FROM " . $table . " WHERE "
                 . "user_session = '$sid' AND comment_time > '$now' AND "
                 . "object_id = " . zmgSQLEscape($id));
                break;
            case 'vote';
                $db->setQuery("SELECT edtid FROM " . $table . " WHERE "
                 . "user_session = '$sid' AND vote_time > '$now' AND "
                 . "object_id = " . zmgSQLEscape($id));
                break;
            case 'pass':
                $db->setQuery("SELECT edtid FROM " . $table . " WHERE "
                 . "user_session = '$sid' AND pass_time > '$now' AND "
                 . "object_id = " . zmgSQLEscape($id));
                break;
            case 'lightbox':
                $db->setQuery("SELECT edtid FROM " . $table . " WHERE "
                 . "user_session = '$sid' AND lightbox_time > '$now' AND "
                 . "lightbox_file = '" . zmgSQLEscape($filename) . "'");
                break;
        }
        $result = $db->query();
        if (mysql_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }
}
?>
