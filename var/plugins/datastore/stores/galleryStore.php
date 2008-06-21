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

class zmgGalleryStore {
    function process() {
    	$gid     = zmgGetParam($_REQUEST, 'zmg_edit_gallery_gid', 0);

        $isNew = false;
        if ($gid === "new") {
            $isNew = true;
            $gid = 0;
        }

        $gid     = intval($gid);

        $events   = & zmgFactory::getEvents();
        $messages = & zmgFactory::getMessages();

        $gallery = new zmgGallery(zmgDatabase::getDBO());
        $res     = true;

        if ($gid > 0) {
            if (!($res = $gallery->load($gid))) {
                $messages->append(T_('Gallery could not be saved') . ': ' . $gallery->getError());
            }
        }

        if (($res && $gid > 0) || $isNew) {
            $data    = array(
              'name'      => zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_edit_gallery_name', $gallery->name)),
              'descr'     => zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_edit_gallery_descr', $gallery->descr)),
              'keywords'  => zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_edit_gallery_keywords', $gallery->keywords)),
              'hide_msg'  => intval(zmgGetParam($_REQUEST, 'zmg_edit_gallery_hidenm', $gallery->hide_msg)),
              'shared'    => intval(zmgGetParam($_REQUEST, 'zmg_edit_gallery_shared', $gallery->shared)),
              'published' => intval(zmgGetParam($_REQUEST, 'zmg_edit_gallery_published', $gallery->published)),
              'uid'       => intval(zmgGetParam($_REQUEST, 'zmg_edit_gallery_acl_gid', $gallery->uid))
            );
            if ($isNew) {
                $data['dir'] = zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_edit_gallery_dir', ''));
            }

            //do some additional validation of strings
            $data['name']     = $events->fire('onvalidate', false, $data['name']);
            $data['descr']    = $events->fire('onvalidate', false, $data['descr']);
            $data['keywords'] = $events->fire('onvalidate', false, $data['keywords']);

            if (!$gallery->bind($data)) {
                $messages->append(T_('Gallery could not be saved') . ': ' . $gallery->getError());
            } else {
                if (!$gallery->store()) {
                    $messages->append(T_('Gallery could not be saved') . ': ' . $gallery->getError());
                } else {
                    if ($isNew) {
                        $gallery->buildDirStructure();
                    }
                    $messages->append(T_('Gallery saved successfully!'));
                }
            }
        } else {
            $messages->append(T_('Gallery could not be saved') . ': ' . $gid);
        }
    }
    
    function delete() {
        
    }
}
?>
