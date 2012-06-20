<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'poster', 'parentid'=>29, 'm'=>'poster', 'c'=>'space', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'add_space', 'parentid'=>$parentid, 'm'=>'poster', 'c'=>'space', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_space', 'parentid'=>$parentid, 'm'=>'poster', 'c'=>'space', 'a'=>'edit', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_space', 'parentid'=>$parentid, 'm'=>'poster', 'c'=>'space', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'poster_list', 'parentid'=>$parentid, 'm'=>'poster', 'c'=>'poster', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'add_poster', 'parentid'=>$parentid, 'm'=>'poster', 'c'=>'poster', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_poster', 'parentid'=>$parentid, 'm'=>'poster', 'c'=>'poster', 'a'=>'edit', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'del_poster', 'parentid'=>$parentid, 'm'=>'poster', 'c'=>'poster', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'poster_stat', 'parentid'=>$parentid, 'm'=>'poster', 'c'=>'poster', 'a'=>'stat', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'poster_setting', 'parentid'=>$parentid, 'm'=>'poster', 'c'=>'space', 'a'=>'setting', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'create_poster_js', 'parentid'=>$parentid, 'm'=>'poster', 'c'=>'space', 'a'=>'create_js', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'poster_template', 'parentid'=>$parentid, 'm'=>'poster', 'c'=>'space', 'a'=>'poster_template', 'data'=>'', 'listorder'=>0, 'display'=>'1'));

$pdb = pc_base::load_model('poster_model');
$sql = "INSERT INTO `phpcms_poster` (`siteid`, `id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `hits`, `clicks`, `listorder`, `disabled`) VALUES
(1, 1, 'banner', 1, 'images', 'array (\n  1 => \n  array (\n    ''linkurl'' => ''http://www.phpcms.cn'',\n    ''imageurl'' => ''./uploadfile/poster/2.png'',\n    ''alt'' => '''',\n  ),\n)', 1285813808, 1446249600, 1285813833, 0, 1, 0, 0);
INSERT INTO `phpcms_poster` (`siteid`, `id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `hits`, `clicks`, `listorder`, `disabled`) VALUES
(1, 2, 'phpcmsv9', 2, 'images', 'array (\n  1 => \n  array (\n    ''linkurl'' => ''http://www.phpcms.cn'',\n    ''imageurl'' => ''./statics/images/v9/ad_login.jpg'',\n    ''alt'' => ''phpcmsרҵ��վϵͳ'',\n  ),\n)', 1285816298, 1446249600, 1285816310, 0, 1, 0, 0);
INSERT INTO `phpcms_poster` (`siteid`, `id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `hits`, `clicks`, `listorder`, `disabled`) VALUES
(1, 3, 'phpcms�����Ƽ�', 3, 'images', 'array (\n  1 => \n  array (\n    ''linkurl'' => ''http://www.phpcms.cn'',\n    ''imageurl'' => ''./uploadfile/poster/3.png'',\n    ''alt'' => ''phpcms�ٷ�'',\n  ),\n)', 1286504815, 1446249600, 1286504865, 0, 1, 0, 0);
INSERT INTO `phpcms_poster` (`siteid`, `id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `hits`, `clicks`, `listorder`, `disabled`) VALUES
(1, 4, 'phpcms���', 4, 'images', 'array (\n  1 => \n  array (\n    ''linkurl'' => ''http://www.phpcms.cn'',\n    ''imageurl'' => ''./uploadfile/poster/4.gif'',\n    ''alt'' => ''phpcms�ٷ�'',\n  ),\n)', 1286505141, 1446249600, 1286505178, 0, 0, 0, 0);
INSERT INTO `phpcms_poster` (`siteid`, `id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `hits`, `clicks`, `listorder`, `disabled`) VALUES
(1, 5, 'phpcms����', 5, 'images', 'array (\n  1 => \n  array (\n    ''linkurl'' => ''http://www.phpcms.cn'',\n    ''imageurl'' => ''./uploadfile/poster/5.gif'',\n    ''alt'' => ''�ٷ�'',\n  ),\n)', 1286509363, 1446249600, 1286509401, 0, 0, 0, 0);
INSERT INTO `phpcms_poster` (`siteid`, `id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `hits`, `clicks`, `listorder`, `disabled`) VALUES
(1, 6, 'phpcms�����Ƽ�1', 6, 'images', 'array (\n  1 => \n  array (\n    ''linkurl'' => ''http://www.phpcms.cn'',\n    ''imageurl'' => ''./uploadfile/poster/5.gif'',\n    ''alt'' => ''�ٷ�'',\n  ),\n)', 1286510183, 1446249600, 1286510227, 0, 0, 0, 0);
INSERT INTO `phpcms_poster` (`siteid`, `id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `hits`, `clicks`, `listorder`, `disabled`) VALUES
(1, 7, 'phpcms��������', 7, 'images', 'array (\n  1 => \n  array (\n    ''linkurl'' => ''http://www.phpcms.cn'',\n    ''imageurl'' => ''./uploadfile/poster/5.gif'',\n    ''alt'' => ''�ٷ�'',\n  ),\n)', 1286510314, 1446249600, 1286510341, 0, 0, 0, 0);
INSERT INTO `phpcms_poster` (`siteid`, `id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `hits`, `clicks`, `listorder`, `disabled`) VALUES
(1, 8, 'phpcms����ҳ', 8, 'images', 'array (\n  1 => \n  array (\n    ''linkurl'' => ''http://www.phpcms.cn'',\n    ''imageurl'' => ''./uploadfile/poster/1.jpg'',\n    ''alt'' => ''�ٷ�վ'',\n  ),\n)', 1286522084, 1446249600, 1286522125, 0, 0, 0, 0);
INSERT INTO `phpcms_poster` (`siteid`, `id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `hits`, `clicks`, `listorder`, `disabled`) VALUES
(1, 9, 'phpcms v9���', 9, 'images', 'array (\n  1 => \n  array (\n    ''linkurl'' => ''http://www.phpcms.cn'',\n    ''imageurl'' => ''./uploadfile/poster/4.gif'',\n    ''alt'' => '''',\n  ),\n)', 1287041759, 1446249600, 1287041804, 0, 0, 0, 0);
INSERT INTO `phpcms_poster` (`siteid`, `id`, `name`, `spaceid`, `type`, `setting`, `startdate`, `enddate`, `addtime`, `hits`, `clicks`, `listorder`, `disabled`) VALUES (1, 10, 'phpcms', 10, 'images', 'array (\n  1 => \n  array (\n    ''linkurl'' => ''http://www.phpcms.cn'',\n    ''imageurl'' => ''./uploadfile/poster/6.jpg'',\n    ''alt'' => ''phpcms�ٷ�'',\n  ),\n)', 1289270509, 1446249600, 1289270541, 1, 0, 0, 0);";
$sql = str_replace(array("phpcms_", './uploadfile/poster/', './statics/images'), array($pdb->db_tablepre, pc_base::load_config('system','upload_url').'poster/', APP_PATH.'statics/images'), $sql);
$sqls = explode(";", trim($sql));
unset($sql);
$sqls = array_filter($sqls);
if (is_array($sqls)) {
	foreach($sqls as $s) {
		$pdb->query($s);
	}
}
unset($pdb);

$language = array('poster'=>'���', 'add_space'=>'��Ӱ�λ', 'edit_space'=>'�༭��λ', 'del_space'=>'ɾ����λ', 'poster_list'=>'����б�', 'add_poster'=>'��ӹ��', 'edit_poster'=>'�༭���', 'del_poster'=>'ɾ�����', 'poster_stat'=>'���ͳ��', 'poster_setting'=>'ģ������', 'create_poster_js'=>'��������js', 'poster_template'=>'���ģ������');
?>