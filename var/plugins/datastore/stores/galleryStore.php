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
    function process(&$zoom) {
    	$gid     = zmgGetParam($_REQUEST, 'zmg_edit_gallery_gid', 0);
        
        $isNew = false;
        if ($gid === "new") {
            $isNew = true;
            $gid = 0;
        }
        
        $gid     = intval($gid);
        
        $gallery = new zmgGallery(zmgDatabase::getDBO());
        
        $data    = array(
          'name'      => zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_edit_gallery_name', $gallery->name)),
          'descr'     => zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_edit_gallery_descr', $gallery->descr)),
          'keywords'  => zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_edit_gallery_keywords', $gallery->keywords)),
          'hide_msg'  => intval(zmgGetParam($_REQUEST, 'zmg_edit_gallery_hidenm', $gallery->hide_msg)),
          'shared'    => intval(zmgGetParam($_REQUEST, 'zmg_edit_gallery_shared', $gallery->shared)),
          'published' => intval(zmgGetParam($_REQUEST, 'zmg_edit_gallery_published', $gallery->published))
        );
        if ($isNew) {
            $data['dir'] = zmgSQLEscape(zmgGetParam($_REQUEST, 'zmg_edit_gallery_dir', ''));
        }
        //do some additional validation of strings
        $data['name']     = $zoom->fireEvent('onvalidate', $data['name'])     || $data['name'];
        $data['descr']    = $zoom->fireEvent('onvalidate', $data['descr'])    || $data['descr'];
        $data['keywords'] = $zoom->fireEvent('onvalidate', $data['keywords']) || $data['keywords'];
        
        $res = true;

        if ($gid > 0) {
            if (!($res = $gallery->load($gid))) {
                $zoom->messages->append(T_('Gallery could not be saved') . ': ' . $gallery->getError());
            }
        }
        
        if (($res && $gid > 0) || $isNew) {
            if (!$gallery->bind($data)) {
                $zoom->messages->append(T_('Gallery could not be saved') . ': ' . $gallery->getError());
            } else {
                if (!$gallery->store()) {
                    $zoom->messages->append(T_('Gallery could not be saved') . ': ' . $gallery->getError());
                } else {
                    if ($isNew) {
                        $gallery->buildDirStructure();
                    }
                    $zoom->messages->append(T_('Gallery saved successfully!'));
                }
            }
        } else {
            $zoom->messages->append(T_('Gallery could not be saved') . ': ' . $gid);
        }
    }
}
?>
