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

class zmgMediumStore {
	function process() {
	    $mid     = intval(zmgGetParam($_REQUEST, 'zmg_edit_mid', 0));

        $medium  = new zmgMedium(zmgDatabase::getDBO());
        $res     = true;

        $events   = & zmgFactory::getEvents();
        $messages = & zmgFactory::getMessages();

        if ($mid > 0) {
            if (!($res = $medium->load($mid))) {
                $messages->append(T_('Medium could not be saved') . ': ' . $medium->getError());
            }
        }

        if ($res && $mid > 0) {
            $data    = array(
              'name'      => zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_edit_name', $medium->name)),
              'descr'     => zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_edit_descr', $medium->descr)),
              'keywords'  => zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_edit_keywords', $medium->keywords)),
              'shared'    => intval(zmgGetParam($_REQUEST, 'zmg_edit_shared', $medium->shared)),
              'published' => intval(zmgGetParam($_REQUEST, 'zmg_edit_published', $medium->published)),
              'uid'       => intval(zmgGetParam($_REQUEST, 'zmg_edit_acl_gid', $medium->uid))
            );
            //do some additional validation of strings
            $data['name']     = $events->fire('onvalidate', false, $data['name']);
            $data['descr']    = $events->fire('onvalidate', false, $data['descr']);
            $data['keywords'] = $events->fire('onvalidate', false, $data['keywords']);
            
            if (!$medium->bind($data)) {
                $messages->append(T_('Medium could not be saved') . ': ' . $medium->getError());
            } else {
                if (!$medium->store()) {
                    $messages->append(T_('Medium could not be saved') . ': ' . $medium->getError());
                } else {
                    $isGalleryImg = (intval(zmgGetParam($_REQUEST, 'zmg_edit_gimg', 0)) === 1);
                    $isParentImg  = (intval(zmgGetParam($_REQUEST, 'zmg_edit_pimg', 0)) === 1);
                    
                    if (!($isGalleryImg && $medium->setAsGalleryImage())) {
                        $messages->append(T_('Medium could not be saved') . ': ' . T_('unable to set as image of gallery'));
                        $res = false;
                    }
                    if (!($isParentImg && $medium->setAsGalleryImage(true))) {
                        $messages->append(T_('Medium could not be saved') . ': ' . T_('unable to set as image of parent gallery'));
                        $res = false;
                    }
                    
                    if ($res) {
                        $messages->append(T_('Medium saved successfully!'));
                    }
                }
            }
        } else {
            $messages->append(T_('Medium could not be saved') . ': ' . $mid);
        }
	}
}
?>
