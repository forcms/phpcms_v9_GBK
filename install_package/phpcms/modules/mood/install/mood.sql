DROP TABLE IF EXISTS `phpcms_mood`;
CREATE TABLE IF NOT EXISTS `phpcms_mood` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `catid` int(10) unsigned NOT NULL default '0' COMMENT '��Ŀid',
  `siteid` mediumint(6) unsigned NOT NULL default '0' COMMENT 'վ��ID',
  `contentid` int(10) unsigned NOT NULL default '0' COMMENT '����id',
  `total` int(10) unsigned NOT NULL default '0' COMMENT '����',
  `n1` int(10) unsigned NOT NULL default '0',
  `n2` int(10) unsigned NOT NULL default '0',
  `n3` int(10) unsigned NOT NULL default '0',
  `n4` int(10) unsigned NOT NULL default '0',
  `n5` int(10) unsigned NOT NULL default '0',
  `n6` int(10) unsigned NOT NULL default '0',
  `n7` int(10) unsigned NOT NULL default '0',
  `n8` int(10) unsigned NOT NULL default '0',
  `n9` int(10) unsigned NOT NULL default '0',
  `n10` int(10) unsigned NOT NULL default '0',
  `lastupdate` int(10) unsigned NOT NULL default '0' COMMENT '������ʱ��',
  PRIMARY KEY  (`id`),
  KEY `total` (`total`),
  KEY `lastupdate` (`lastupdate`),
  KEY `catid` (`catid`,`siteid`,`contentid`)
) TYPE=MyISAM;
