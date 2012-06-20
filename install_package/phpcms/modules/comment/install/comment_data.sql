DROP TABLE IF EXISTS `phpcms_comment_data_1`;
CREATE TABLE IF NOT EXISTS `phpcms_comment_data_1` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '����ID',
  `commentid` char(30) NOT NULL default '' COMMENT '����ID��',
  `siteid` smallint(5) NOT NULL default '0' COMMENT 'վ��ID',
  `userid` int(10) unsigned default '0' COMMENT '�û�ID',
  `username` varchar(20) default NULL COMMENT '�û���',
  `creat_at` int(10) default NULL COMMENT '����ʱ��',
  `ip` varchar(15) default NULL COMMENT '�û�IP��ַ',
  `status` tinyint(1) default '0' COMMENT '����״̬{0:δ���,-1:δͨ�����,1:ͨ�����}',
  `content` text COMMENT '��������',
  `direction` tinyint(1) default '0' COMMENT '���۷���{0:�޷���,1:����,2:����,3:����}',
  `support` mediumint(8) unsigned default '0' COMMENT '֧����',
  `reply` tinyint(1) NOT NULL default '0' COMMENT '�Ƿ�Ϊ�ظ�',
  PRIMARY KEY  (`id`),
  KEY `commentid` (`commentid`),
  KEY `direction` (`direction`),
  KEY `siteid` (`siteid`),
  KEY `support` (`support`)
) TYPE=MyISAM;
