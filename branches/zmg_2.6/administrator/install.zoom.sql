-- 
-- Table structure for table `jos_zmg_cache_getid3`
-- 

CREATE TABLE IF NOT EXISTS `#__zmg_cache_getid3` (
  `filename` varchar(255) NOT NULL default '',
  `filesize` int(11) NOT NULL default '0',
  `filetime` int(11) NOT NULL default '0',
  `analyzetime` int(11) NOT NULL default '0',
  `value` text NOT NULL,
  PRIMARY KEY  (`filename`,`filesize`,`filetime`)
) TYPE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

-- 
-- Table structure for table `#__zmg_comments`
-- 

CREATE TABLE IF NOT EXISTS `#__zmg_comments` (
  `cid` int(11) NOT NULL auto_increment,
  `mid` int(11) NOT NULL default '0',
  `name` varchar(40) NOT NULL,
  `content` text NOT NULL,
  `date_added` timestamp NOT NULL,
  PRIMARY KEY  (`cid`),
  KEY `imgid` (`mid`)
) TYPE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

-- 
-- Table structure for table `#__zmg_ecards`
-- 

CREATE TABLE IF NOT EXISTS `#__zmg_ecards` (
  `ecdid` varchar(25) NOT NULL default '',
  `mid` int(11) NOT NULL default '0',
  `to_name` varchar(50) NOT NULL default '',
  `from_name` varchar(50) NOT NULL default '',
  `to_email` varchar(75) NOT NULL default '',
  `from_email` varchar(75) NOT NULL default '',
  `message` text NOT NULL,
  `end_date` date NOT NULL default '0000-00-00',
  `user_ip` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`ecdid`),
  KEY `ecard_img` (`mid`)
) TYPE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

-- 
-- Table structure for table `#__zmg_editmon`
-- 
CREATE TABLE IF NOT EXISTS `#__zmg_editmon` (
  `edtid` int(11) NOT NULL auto_increment,
  `user_session` varchar(200) NOT NULL default '0',
  `vote_time` varchar(14) default NULL,
  `comment_time` varchar(14) default NULL,
  `pass_time` varchar(14) default NULL,
  `lightbox_time` varchar(14) default NULL,
  `lightbox_file` varchar(40) default NULL,
  `object_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`edtid`),
  KEY `edit_session` (`user_session`),
  KEY `object` (`object_id`)
) TYPE=MyISAM CHARACTER SET `utf8`;

-- --------------------------------------------------------

-- 
-- Table structure for table `#__zmg_galleries`
-- 

CREATE TABLE IF NOT EXISTS `#__zmg_galleries` (
  `gid` int(11) NOT NULL auto_increment,
  `name` varchar(240) default '0',
  `descr` mediumtext,
  `dir` varchar(50) default '0',
  `cover_img` int(11) default '0',
  `password` varchar(100) NOT NULL,
  `keywords` varchar(240) NOT NULL,
  `sub_gid` int(11) NOT NULL default '0',
  `pos` int(3) NOT NULL default '0',
  `hide_msg` tinyint(1) NOT NULL default '0',
  `shared` tinyint(1) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '1',
  `uid` int(11) NOT NULL default '0',
  `members` varchar(240) NOT NULL,
  `ordering` int(20) NOT NULL default '0',
  PRIMARY KEY  (`gid`),
  KEY `catdir_search` (`dir`),
  KEY `rel_subcats` (`sub_gid`)
) TYPE=MyISAM CHARACTER SET `utf8` ;

-- --------------------------------------------------------

-- 
-- Table structure for table `#__zmg_media`
-- 

CREATE TABLE IF NOT EXISTS `#__zmg_media` (
  `mid` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `filename` varchar(255) default NULL,
  `descr` mediumtext,
  `keywords` varchar(255) default NULL,
  `date_add` datetime NOT NULL default '0000-00-00 00:00:00',
  `hits` bigint(20) NOT NULL default '0',
  `votenum` int(11) NOT NULL default '0',
  `votesum` int(11) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '1',
  `gid` int(11) NOT NULL default '0',
  `uid` int(11) NOT NULL default '0',
  `members` varchar(240) NOT NULL,
  PRIMARY KEY  (`mid`),
  KEY `img_catid` (`gid`),
  KEY `img_user` (`uid`)
) TYPE=MyISAM CHARACTER SET `utf8` ;

-- --------------------------------------------------------

-- 
-- Table structure for table `#__zmg_plugins`
-- 

CREATE TABLE IF NOT EXISTS `#__zmg_plugins` (
  `pid` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL default '',
  `title` text NOT NULL,
  `ordering` int(11) NOT NULL default '0',
  `iscore` tinyint(4) NOT NULL default '0',
  `enabled` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`pid`)
) TYPE=MyISAM CHARACTER SET `utf8` ;

-- --------------------------------------------------------

-- 
-- Table structure for table `#__zmg_privs`
-- 

CREATE TABLE IF NOT EXISTS `#__zmg_privs` (
  `gid` int(11) NOT NULL default '0',
  `priv_upload` enum('0','1') NOT NULL default '1',
  `priv_editmedium` enum('0','1') NOT NULL default '1',
  `priv_delmedium` enum('0','1') NOT NULL default '1',
  `priv_creategal` enum('0','1') NOT NULL default '1',
  `priv_editgal` enum('0','1') NOT NULL default '1',
  `priv_delgal` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`gid`)
) TYPE=MyISAM CHARACTER SET `utf8`;