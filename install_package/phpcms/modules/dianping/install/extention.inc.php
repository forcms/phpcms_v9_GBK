<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');


$parentid = $menu_db->insert(array('name'=>'dianping', 'parentid'=>29, 'm'=>'dianping', 'c'=>'dianping', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'add_dianping_type', 'parentid'=>$parentid, 'm'=>'dianping', 'c'=>'dianping', 'a'=>'add_type', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'dianping_data', 'parentid'=>$parentid, 'm'=>'dianping', 'c'=>'dianping', 'a'=>'dianping_data', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'dianping_do_js', 'parentid'=>$parentid, 'm'=>'dianping', 'c'=>'dianping', 'a'=>'do_js', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$language = array('dianping'=>'����', 'add_dianping_type'=>'��ӵ�������', 'dianping_data'=>'������Ϣ�б�','dianping_do_js'=>'���µ������û���');

?>