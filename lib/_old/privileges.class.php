<?php
//zOOm Media Gallery//
/** 
-----------------------------------------------------------------------
|  zOOm Media Gallery! by Mike de Boer - a multi-gallery component    |
-----------------------------------------------------------------------

-----------------------------------------------------------------------
|                                                                     |
| Author: Mike de Boer, <http://www.mikedeboer.nl>                    |
| Copyright: copyright (C) 2007 by Mike de Boer                       |
| Description: zOOm Media Gallery, a multi-gallery component for      |
|              Joomla!. It's the most feature-rich gallery component  |
|              for Joomla!! For documentation and a detailed list     |
|              of features, check the zOOm homepage:                  |
|              http://www.zoomfactory.org                             |
| License: GPL                                                        |
| Filename: privileges.class.php                                      |
|                                                                     |
-----------------------------------------------------------------------
* @version $Id:privileges.class.php 106 2007-02-10 22:30:30Z kevinuru $
* @package zOOmGallery
* @author Mike de Boer <mailme@mikedeboer.nl> 
**/
// MOS Intruder Alerts
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * Privileges class; creates an instance of a priviledge of a specified ACL ARO group,
 * which, in turn, defines what a user of a specific group may or may not do.
 *
 * @access public
 */
class privileges extends zoom {
    /**
    * @var int
    * @access public
    */
    var $gid = null;
    /**
    * @var enum
    * @access public
    */
    var $priv_upload = null;
    /**
    * @var enum
    * @access public
    */
    var $priv_editmedium = null;
    /**
    * @var enum
    * @access public
    */
    var $priv_delmedium = null;
    /**
    * @var enum
    * @access public
    */
    var $priv_creategal = null;
    /**
    * @var enum
    * @access public
    */
    var $priv_editgal = null;
    /**
    * @var enum
    * @access public
    */
    var $priv_delgal = null;
    
    /**
    * Comment object constructor
    *
    * @param database $db
    * @return privileges
    * @access public
    */
    function privileges(&$db, $gid) {
		global $my;
        $this->gid = intval($this->escapeString($gid));
		if (isset($my->gids) && $my->gid == $gid) {
        $db->setQuery("SELECT priv_upload, priv_editmedium, priv_delmedium, priv_creategal, priv_editgal, priv_delgal "
         . " FROM #__zoom_priv WHERE gid IN (".$this->escapeString($my->gids).")");
		} else {
        $db->setQuery("SELECT priv_upload, priv_editmedium, priv_delmedium, priv_creategal, priv_editgal, priv_delgal "
         . " FROM #__zoom_priv WHERE gid = '$this->gid'");
		}
        $result = $db->query();
        while ($row = mysql_fetch_object($result)) {
    	    foreach ($row as $priv => $value) {
    			if($value) $this->$priv = $value;
    	    }
        }
    }
    /**
    * Return the privileges a user (of a specific group) has or hasn't.
    *
    * @return array
    * @access public
    */
    function getPrivileges() {
        return array('priv_upload' => $this->priv_upload, 'priv_editmedium' => $this->priv_editmedium,
         'priv_delmedium' => $this->priv_delmedium, 'priv_creategal' => $this->priv_creategal,
         'priv_editgal' => $this->priv_editgal, 'priv_delgal' => $this->priv_delgal);
    }
    /**
    * Set a specific privilege to true or false
    *
    * @param string $priv_name
    * @param int $value
    * @return void
    * @access public
    */
    function setPrivilege($priv_name, $value) {
        $this->$priv_name = $value;
    }
    /**
    * Store the privileges set by the setPrivilege() function into the database
    *
    * @return boolean
    * @access public
    */
    function savePrivileges() {
        global $database;
        $database->setQuery("SELECT gid FROM #__zoom_priv WHERE gid = '$this->gid'");
        if ($database->loadResult()>0) {
	        $database->setQuery("UPDATE #__zoom_priv SET "
	         . "priv_upload = '$this->priv_upload', "
	         . "priv_editmedium = '$this->priv_editmedium', "
	         . "priv_delmedium = '$this->priv_delmedium', "
	         . "priv_creategal = '$this->priv_creategal', "
	         . "priv_editgal = '$this->priv_editgal', "
	         . "priv_delgal = '$this->priv_delgal' "
	         . "WHERE gid = '$this->gid'");
	        if ($database->query()) {
	            return true;
	        } else {
	            return false;
	        }
        } else {
			$database->setQuery("INSERT INTO #__zoom_priv SET "
			 . "priv_upload = '$this->priv_upload', "
			 . "priv_editmedium = '$this->priv_editmedium', "
			 . "priv_delmedium = '$this->priv_delmedium', "
			 . "priv_creategal = '$this->priv_creategal', "
			 . "priv_editgal = '$this->priv_editgal', "
			 . "priv_delgal = '$this->priv_delgal', "
			 . "gid = '$this->gid'");
			if ($database->query()) {
				return true;
			} else {
				return false;
			}
		}
    }
    /**
    * Check if a user has any privileges AT ALL to his/ her disposal.
    *
    * @return boolean
    * @access public
    */
    function hasPrivileges() {
        global $zoom, $my;
   		if (strtolower($my->usertype) == 'administrator' || strtolower($my->usertype) == 'superadministrator' || strtolower($my->usertype) == 'super administrator') {
			$this->_isAdmin = true;
		}
        if (($this->priv_creategal | 
            $this->priv_editgal | 
            $this->priv_delgal | 
            $this->priv_upload | 
            $this->priv_editmedium | 
            $this->priv_delmedium) || 
            $this->_isAdmin) {
            return true;
        } else {
            return false;
        }
    }
    /**
    * Check if a user currently trying to access a restricted zOOm function has the necessary privileges for it.
    *
    * @param string $priv_name
    * @return boolean
    * @access public
    */
    function hasPrivilege($priv_name) {
        if ($this->$priv_name == 1) {
            return true;
        } else {
            return false;
        }
    }
}
?>