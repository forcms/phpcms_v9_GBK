DROP TABLE IF EXISTS `phpcms_poster_space`;
CREATE TABLE IF NOT EXISTS `phpcms_poster_space` (
  `spaceid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` char(50) NOT NULL,
  `type` char(30) NOT NULL,
  `path` char(40) NOT NULL,
  `width` smallint(4) unsigned NOT NULL DEFAULT '0',
  `height` smallint(4) unsigned NOT NULL DEFAULT '0',
  `setting` char(100) NOT NULL,
  `description` char(100) NOT NULL,
  `items` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`spaceid`),
  KEY `disabled` (`disabled`,`siteid`)
) TYPE=MyISAM ;

INSERT INTO `phpcms_poster_space` (`siteid`, `spaceid`, `name`, `type`, `path`, `width`, `height`, `setting`, `description`, `items`, `disabled`) VALUES
(1, 1, '���������Ҳ���λ', 'banner', 'poster_js/1.js', 430, 63, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1, 0);
INSERT INTO `phpcms_poster_space` (`siteid`, `spaceid`, `name`, `type`, `path`, `width`, `height`, `setting`, `description`, `items`, `disabled`) VALUES
(1, 2, '��Ա��½ҳ���', 'banner', 'poster_js/2.js', 310, 304, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '��Ա��½ҳ����Ҳ�����ⲿͨ��֤���', 1, 0);
INSERT INTO `phpcms_poster_space` (`siteid`, `spaceid`, `name`, `type`, `path`, `width`, `height`, `setting`, `description`, `items`, `disabled`) VALUES
(1, 3, 'ͼƬƵ��ͼƬ�����·�', 'banner', 'poster_js/3.js', 249, 87, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1, 0);
INSERT INTO `phpcms_poster_space` (`siteid`, `spaceid`, `name`, `type`, `path`, `width`, `height`, `setting`, `description`, `items`, `disabled`) VALUES
(1, 4, '���������Ƽ��������', 'banner', 'poster_js/4.js', 748, 91, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1, 0);
INSERT INTO `phpcms_poster_space` (`siteid`, `spaceid`, `name`, `type`, `path`, `width`, `height`, `setting`, `description`, `items`, `disabled`) VALUES
(1, 5, '�����б�ҳ�Ҳඥ��', 'banner', 'poster_js/5.js', 248, 162, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1, 0);
INSERT INTO `phpcms_poster_space` (`siteid`, `spaceid`, `name`, `type`, `path`, `width`, `height`, `setting`, `description`, `items`, `disabled`) VALUES
(1, 6, '��������ҳ�Ҳඥ��', 'banner', 'poster_js/6.js', 248, 162, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1, 0);
INSERT INTO `phpcms_poster_space` (`siteid`, `spaceid`, `name`, `type`, `path`, `width`, `height`, `setting`, `description`, `items`, `disabled`) VALUES
(1, 7, '��������ҳ�Ҳ��²�', 'banner', 'poster_js/7.js', 248, 162, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1, 0);
INSERT INTO `phpcms_poster_space` (`siteid`, `spaceid`, `name`, `type`, `path`, `width`, `height`, `setting`, `description`, `items`, `disabled`) VALUES
(1, 8, '����Ƶ����ҳ', 'banner', 'poster_js/8.js', 698, 80, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1, 0);
INSERT INTO `phpcms_poster_space` (`siteid`, `spaceid`, `name`, `type`, `path`, `width`, `height`, `setting`, `description`, `items`, `disabled`) VALUES
(1, 9, '��������ҳ��ַ�б��Ҳ�', 'banner', 'poster_js/12.js', 330, 50, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1, 0);
INSERT INTO `phpcms_poster_space` (`siteid`, `spaceid`, `name`, `type`, `path`, `width`, `height`, `setting`, `description`, `items`, `disabled`) VALUES
(1, 10, '��ҳ��ע�·����', 'banner', 'poster_js/10.js', 307, 60, 'array (\n  ''paddleft'' => '''',\n  ''paddtop'' => '''',\n)', '', 1, 0);