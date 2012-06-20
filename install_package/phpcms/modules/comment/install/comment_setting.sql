DROP TABLE IF EXISTS `phpcms_comment_setting`;
CREATE TABLE IF NOT EXISTS `phpcms_comment_setting` (
  `siteid` smallint(5) NOT NULL default '0' COMMENT 'վ��ID',
  `guest` tinyint(1) default '0' COMMENT '�Ƿ������ο�����',
  `check` tinyint(1) default '0' COMMENT '�Ƿ���Ҫ���',
  `code` tinyint(1) default '0' COMMENT '�Ƿ�����֤��',
  `add_point`  tinyint(3) UNSIGNED NULL DEFAULT 0 COMMENT '��ӵĻ�����' ,
  `del_point`  tinyint(3) UNSIGNED NULL DEFAULT 0 COMMENT '�۳��Ļ�����' ,
  PRIMARY KEY  (`siteid`)
) TYPE=MyISAM;
INSERT INTO `phpcms_comment_setting` (`siteid`, `guest`, `check`, `code`, `add_point`, `del_point`) VALUES (1, 0, 0, 0, 0, 0);
