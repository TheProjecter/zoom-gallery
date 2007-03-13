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
| Filename: install.zoom.php                                          |
|                                                                     |
-----------------------------------------------------------------------
**/
function com_install(){
	global $mosConfig_live_site, $mosConfig_absolute_path, $mosConfig_lang, $database, $mosConfig_dbprefix;
	
	/* Set up new icons for admin menu */
	//$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/config.png' WHERE admin_menu_link='option=com_smf&task=config'");
	$database->setQuery("UPDATE #__components SET admin_menu_img='../administrator/components/com_zoom/images/zoom_menu.png' WHERE admin_menu_link='option=com_zoom'");
	$iconresult = $database->query();
	    
	// get language file
	if (file_exists($mosConfig_absolute_path."/components/com_zoom/lib/language/".$mosConfig_lang.".php")){
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/language/".$mosConfig_lang.".php");
	}else{
		include_once($mosConfig_absolute_path."/components/com_zoom/lib/language/english.php");
	}	
	//end language
	
	//Run the mySQL queries
	$tables = $database->getTableList();
	echo '<table align="left">';
	if(!in_array($mosConfig_dbprefix."zoom", $tables))
	{
		//Add tables if first time install	
		$query1 = "DROP TABLE #__zoom";
        $query2 = "CREATE TABLE #__zoom ("
	    . "\n catid int(11) NOT NULL auto_increment,"
	    . "\n catname varchar(50) default '0',"
	    . "\n catdescr varchar(255) default NULL,"
	    . "\n catdir varchar(50) default '0',"
	    . "\n catimg int(11) default '0',"
	    . "\n catpassword varchar(100) NOT NULL default '',"
	    . "\n catkeywords varchar(240) NOT NULL default '',"
	    . "\n subcat_id int(11) NOT NULL default '0',"
	    . "\n pos int(3) NOT NULL default '0',"
	    . "\n hideMsg tinyint(1) NOT NULL default '0',"
	    . "\n shared tinyint(1) NOT NULL default '0',"
	    . "\n published tinyint(1) NOT NULL default '1',"
	    . "\n uid int(11) NOT NULL default '0',"
	    . "\n catmembers varchar(240) NOT NULL default '',"
	    . "\n custom_order varchar(20) default NULL,"
	    . "\n PRIMARY KEY (catid),"
	    . "\n KEY catdir_search (catdir),"
	    . "\n KEY rel_subcats (subcat_id)"
        . "\n ) TYPE=MyISAM";
   		$database->setQuery($query1);
		$database->query();
   		$database->setQuery($query2);
		$database->query();		
   		echo '<tr><td style="color:red">#_zoom</td></tr>';
	    echo '<tr><td>'. _ZOOM_INSTALL_CREATE_DBASE_SUCC . '</td></tr>';
	}
	else
	{
		//Upgrade from RC1 or Beta 3
		$test = "SELECT `custom_order` FROM #__zoom";
		$database->setQuery($test);
		//if it fails test then apply upgrade
		if (!$database->query())
		{
				$upgrade = "ALTER TABLE `#__zoom` ADD `custom_order` varchar(20) AFTER `catmembers` ";
				$database->setQuery($upgrade);
				if(!$database->query())
				{
					//Upgrade failed
					print("<font color=red>Upgrade failed! SQL error:" . $database->stderr(true)."</font><br />");
					return;
				}
				else
				{
            		echo '<tr><td style="color:red">#_zoom</td></tr>';
            	    echo '<tr><td>'. _ZOOM_INSTALL_UPGRADED_DBASE_SUCC . '</td></tr>';
				}
		}

        $upgrade = "ALTER TABLE `#__zoom` MODIFY `catdescr` MEDIUMTEXT;";
		$database->setQuery($upgrade);
		$database->query();
		
        $upgrade = "ALTER TABLE `#__zoom` MODIFY `custom_order` INT(20);";
		$database->setQuery($upgrade);
		$database->query();
		
		
		//Clean out all the crap from the old versions of the gallery (quotes, bad utf chars, ect)
		$database->setQuery("SELECT * FROM #__zoom");
		$result = $database->query();
		$count = mysql_num_rows($result);
		$id = array();
		$names = array();
		$descr = array();
		$keys = array();
		while($rows=mysql_fetch_array($result)){
	        $id[] = $rows['catid'];
    	    
    	    $str = $rows['catname'];
            $names[] = htmlspecialchars(revnumericentities($str), ENT_QUOTES);
            
    	    $descr[] = $rows['catdescr'];
    	    
    	    $str = $rows['catkeywords'];
	        $keys[] = htmlspecialchars(revnumericentities($str), ENT_QUOTES);
		}

		for($i=0;$i<$count;$i++){
			$upgrade = "UPDATE `#__zoom` SET catname='$names[$i]', catdescr='$descr[$i]', catkeywords='$keys[$i]' WHERE catid='$id[$i]'";
			$database->setQuery($upgrade);
				if(!$database->query())
				{
					//Upgrade failed
					print("<font color=red>Cleanup failed! SQL error:" . $database->stderr(true)."</font><br />");
					return;
				}
		}
	}
	if(!in_array($mosConfig_dbprefix."zoom_comments", $tables))
	{	
		//Add tables if first time install
		$query3 = "DROP TABLE #__zoom_comments";
        $query4 = "CREATE TABLE #__zoom_comments ("
	    . "\n cmtid int(11) NOT NULL auto_increment,"
	    . "\n imgid int(11) NOT NULL default '0',"
	    . "\n cmtname varchar(40) NOT NULL default '',"
	    . "\n cmtcontent text NOT NULL,"
	    . "\n cmtdate timestamp(14) NOT NULL,"
	    . "\n PRIMARY KEY  (cmtid),"
	    . "\n KEY imgid (imgid)"
	    . "\n ) TYPE=MyISAM";
   		$database->setQuery($query3);
		$database->query();
   		$database->setQuery($query4);
		$database->query();
   		echo '<tr><td style="color:red">#_zoom_comments</td></tr>';
	    echo '<tr><td>'. _ZOOM_INSTALL_CREATE_DBASE_SUCC . '</td></tr>';
   		//RC1 and Beta 3 code identical
	}
	if(!in_array($mosConfig_dbprefix."zoom_ecards", $tables))
	{	
       $query5 = "DROP TABLE #__zoom_ecards";
       $query6 = "CREATE TABLE #__zoom_ecards ("
       . "\n ecdid varchar(25) NOT NULL default '',"
	   . "\n imgid int(11) NOT NULL default '0',"
	   . "\n to_name varchar(50) NOT NULL default '',"
	   . "\n from_name varchar(50) NOT NULL default '',"
	   . "\n to_email varchar(75) NOT NULL default '',"
	   . "\n from_email varchar(75) NOT NULL default '',"
	   . "\n message text NOT NULL,"
	   . "\n end_date date NOT NULL default '0000-00-00',"
	   . "\n user_ip varchar(25) NOT NULL default '',"
	   . "\n PRIMARY KEY (ecdid),"
	   . "\n KEY ecard_img (imgid)"
       . "\n ) TYPE=MyISAM";
       $database->setQuery($query5);
       $database->query();       
       $database->setQuery($query6);
       $database->query();
   		echo '<tr><td style="color:red">#_zoom_ecards</td></tr>';
	    echo '<tr><td>'. _ZOOM_INSTALL_CREATE_DBASE_SUCC . '</td></tr>';
       //RC1 and Beta 3 code identical
	}
	if(!in_array($mosConfig_dbprefix."zoom_editmon", $tables))
	{
		$query7 = "DROP TABLE #__zoom_editmon";
        $query8 = "CREATE TABLE #__zoom_editmon ("
	   . "\n edtid int(11) NOT NULL auto_increment,"
	   . "\n user_session varchar(200) NOT NULL default '0',"
	   . "\n vote_time varchar(14) default NULL,"
	   . "\n comment_time varchar(14) default NULL,"
	   . "\n pass_time varchar(14) default NULL,"
	   . "\n lightbox_time varchar(14) default NULL,"
	   . "\n lightbox_file varchar(40) default NULL,"
	   . "\n object_id int(11) NOT NULL default '0',"
	   . "\n PRIMARY KEY  (edtid),"
	   . "\n KEY edit_session (user_session),"
	   . "\n KEY object (object_id)"
	   . "\n ) TYPE=MyISAM";
       $database->setQuery($query7);
	   $database->query();
       $database->setQuery($query8);
	   $database->query();
   		echo '<tr><td style="color:red">#_zoom_editmon</td></tr>';
	    echo '<tr><td>'. _ZOOM_INSTALL_CREATE_DBASE_SUCC . '</td></tr>';
       //RC1 and Beta 3 code identical
	}
	if(!in_array($mosConfig_dbprefix."zoom_getid3_cache", $tables))
	{
		$query9 = "DROP TABLE #__zoom_getid3_cache";
       	$query10 = "CREATE TABLE #__zoom_getid3_cache ("
       . "\n filename varchar(255) NOT NULL DEFAULT '',"
       . "\n filesize INT(11) NOT NULL DEFAULT '0',"
       . "\n filetime INT(11) NOT NULL DEFAULT '0',"
       . "\n analyzetime INT(11) NOT NULL DEFAULT '0',"
       . "\n value TEXT NOT NULL,"
       . "\n PRIMARY KEY (filename, filesize, filetime)"
       . "\n ) TYPE=MyISAM";
       $database->setQuery($query9);
       $database->query();
       $database->setQuery($query10);
       $database->query();
   		echo '<tr><td style="color:red">#_zoom_getid3_cache</td></tr>';
	    echo '<tr><td>'. _ZOOM_INSTALL_CREATE_DBASE_SUCC . '</td></tr>';
       //RC1 and Beta 3 code identical
    }
    if(!in_array($mosConfig_dbprefix."zoomfiles", $tables))
	{
    	$query11 = "DROP TABLE #__zoomfiles";
	    $query12 = "CREATE TABLE #__zoomfiles ("
	   . "\n imgid int(11) NOT NULL auto_increment,"
	   . "\n imgname varchar(50) NOT NULL default '',"
	   . "\n imgfilename varchar(70) NOT NULL default '',"
	   . "\n imgdescr varchar(255) default NULL,"
	   . "\n imgkeywords varchar(255) default NULL,"
	   . "\n imgdate datetime NOT NULL default '0000-00-00 00:00:00',"
	   . "\n imghits bigint(20) NOT NULL default '0',"
	   . "\n votenum int(11) NOT NULL default '0',"
	   . "\n votesum int(11) NOT NULL default '0',"
	   . "\n published tinyint(1) NOT NULL default '1',"
	   . "\n catid int(11) NOT NULL default '0',"
	   . "\n uid int(11) NOT NULL default '0',"
	   . "\n imgmembers varchar(240) NOT NULL default '',"
	   . "\n PRIMARY KEY  (imgid),"
	   . "\n KEY img_catid (catid),"
	   . "\n KEY img_user (uid)"
	   . "\n ) TYPE=MyISAM";
       $database->setQuery($query11);
       $database->query();
       $database->setQuery($query12);
       $database->query();
   		echo '<tr><td style="color:red">#_zoomfiles</td></tr>';
	    echo '<tr><td>'. _ZOOM_INSTALL_CREATE_DBASE_SUCC . '</td></tr>';
       //All versions need an imgdescr update
	}
	    $upgrade = "ALTER TABLE `#__zoomfiles` MODIFY `imgdescr` MEDIUMTEXT";
   		$database->setQuery($upgrade);
		$database->query();
        
        $upgrade = "ALTER TABLE `#__zoomfiles` MODIFY `imgname` varchar(255)";
        $database->setQuery($upgrade);
		$database->query();

	    $upgrade = "ALTER TABLE `#__zoomfiles` MODIFY `imgfilename` varchar(255)";
		$database->setQuery($upgrade);
		$database->query();
		
	if(!in_array($mosConfig_dbprefix."zoom_priv", $tables))
	{
	   $query13 = "DROP TABLE #__zoom_priv";
       $query14 = "CREATE TABLE #__zoom_priv ("
       . "\n gid int(11) NOT NULL default '0',"
       . "\n priv_upload enum('0','1') NOT NULL default '1',"
       . "\n priv_editmedium enum('0','1') NOT NULL default '1',"
       . "\n priv_delmedium enum('0','1') NOT NULL default '1',"
       . "\n priv_creategal enum('0','1') NOT NULL default '1',"
       . "\n priv_editgal enum('0','1') NOT NULL default '1',"
       . "\n priv_delgal enum('0','1') NOT NULL default '1',"
       . "\n PRIMARY KEY  (gid)"
       . "\n ) TYPE=MyISAM";
       $query15 = "INSERT INTO #__zoom_priv VALUES (18, '0', '0', '0', '0', '0', '0')";
       $query16 = "INSERT INTO #__zoom_priv VALUES (19, '0', '0', '0', '0', '0', '0')";
       $query17 = "INSERT INTO #__zoom_priv VALUES (20, '0', '0', '0', '0', '0', '0')";
       $query18 = "INSERT INTO #__zoom_priv VALUES (21, '0', '0', '0', '0', '0', '0')";
       $query19 = "INSERT INTO #__zoom_priv VALUES (23, '0', '0', '0', '0', '0', '0')";
       $query20 = "INSERT INTO #__zoom_priv VALUES (24, '1', '1', '1', '1', '1', '1')";
       $query21 = "INSERT INTO #__zoom_priv VALUES (25, '1', '1', '1', '1', '1', '1')";
       $database->setQuery($query13);
       $database->query();
       $database->setQuery($query14);
       $database->query();
       $database->setQuery($query15);
       $database->query();
       $database->setQuery($query16);
       $database->query();
       $database->setQuery($query17);
       $database->query();
       $database->setQuery($query18);
       $database->query();
       $database->setQuery($query19);
       $database->query();
       $database->setQuery($query20);
       $database->query();
       $database->setQuery($query21);
       $database->query();
   		echo '<tr><td style="color:red">#_zoom_priv</td></tr>';
	    echo '<tr><td>'. _ZOOM_INSTALL_CREATE_DBASE_SUCC . '</td></tr>';
       //RC1 and Beta 3 Identical
    }
    echo '</table>';
	echo '<p>'._ZOOM_INSTALL_CREATE_DIR;
	if (@mkdir ($mosConfig_absolute_path."/images/zoom", 0777) || is_dir($mosConfig_absolute_path."/images/zoom")) {
    	@chmod ($mosConfig_absolute_path."/images/zoom", 0777);
    	@chmod ($mosConfig_absolute_path."/components/com_zoom/etc/audiolist.php", 0777);
    	@chmod ($mosConfig_absolute_path."/components/com_zoom/etc/safemode.php", 0777);
    	@chmod ($mosConfig_absolute_path."/components/com_zoom/etc/zoom_config.php", 0777);
    	@chmod ($mosConfig_absolute_path."/components/com_zoom/www/ajaxcallback.php", 0755);
    	@chmod ($mosConfig_absolute_path."/components/com_zoom/www/view.php", 0755);
		echo ('<font color="green">' . '&nbsp;' . _ZOOM_INSTALL_CREATE_DIR_SUCC . '</font></p>');
		
		echo '<p>'._ZOOM_INSTALL_CREATE_DIR_WM;
		if (@mkdir ($mosConfig_absolute_path."/images/zoom/watermarks", 0777) || is_dir($mosConfig_absolute_path."/images/zoom/watermarks")) {
    		@chmod ($mosConfig_absolute_path."/images/zoom/watermarks", 0777);
    		echo ('<font color="green">' . '&nbsp;' . _ZOOM_INSTALL_CREATE_DIR_SUCC . '</font></p>');
		} else {
			echo ('<font color="red"><strong>' . '&nbsp;' . _ZOOM_INSTALL_CREATE_DIR_FAIL . '</strong></font></p>');
		}
		
		copy($mosConfig_absolute_path."/components/com_zoom/www/images/zoom_logo_faded.png",$mosConfig_absolute_path."/images/zoom/watermarks/zoom_logo_faded.png");
		echo ('<table border="0" cellspacing="0" cellpadding="0" background="' . $mosConfig_live_site . '/components/com_zoom/www/images/zoom_logo_faded.gif" style="background-repeat:no-repeat; background-position:top right;" width="75%">'
	 	 . '<tr><td align="center">'
	 	 . '<p>' . _ZOOM_INSTALL_MESS1 . '</p>'
	 	 . '<p><strong>' . _ZOOM_INSTALL_MESS2 . '</strong></p>'
	 	 . '<p>' . _ZOOM_INSTALL_MESS3 . '</p>'
	 	 . '<p>' . _ZOOM_INSTALL_MESS4 . '</p>'
	 	 . '</td></tr></table><br /><br /><br /><br />');
		 
	} else {
		echo ('<font color="red"><strong>' . '&nbsp;' . _ZOOM_INSTALL_CREATE_DIR_FAIL . '</strong></font></p>'
		 . '<table border="0" cellspacing="0" cellpadding="0" background="' . $mosConfig_live_site . '/components/com_zoom/www/images/zoom_logo_faded.gif" style="background-repeat:no-repeat; background-position:top right;" width="75%">'
		 . '<tr><td align="left">'
		 . '<p><strong>' . _ZOOM_INSTALL_MESS_FAIL1 . '</strong></p>'
		 . '<p>' . _ZOOM_INSTALL_MESS_FAIL2 . '</p>'
		 . '<p>' . _ZOOM_INSTALL_MESS_FAIL3 . '</p'
		 . '</td></tr></table><br /><br /><br /><br />');
	}
}
	function revnumericentities($string) {
	      $string = str_replace ( '&amp;', '&', $string );
          $string = str_replace ( '&#039;', '\'', $string );
          $string = str_replace ( '&quot;', '"', $string );
          $string = str_replace ( '&lt;', '<', $string );
          $string = str_replace ( '&gt;', '>', $string );
          return $string;
	}
?>