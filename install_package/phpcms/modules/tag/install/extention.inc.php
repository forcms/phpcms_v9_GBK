<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'tag', 'parentid'=>826, 'm'=>'tag', 'c'=>'tag', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'add_tag', 'parentid'=>$parentid, 'm'=>'tag', 'c'=>'tag', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'edit_tag', 'parentid'=>$parentid, 'm'=>'tag', 'c'=>'tag', 'a'=>'edit', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'delete_tag', 'parentid'=>$parentid, 'm'=>'tag', 'c'=>'tag', 'a'=>'del', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'tag_lists', 'parentid'=>$parentid, 'm'=>'tag', 'c'=>'tag', 'a'=>'lists', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('tag'=>'��ǩ��', 'add_tag'=>'��ӱ�ǩ��', 'edit_tag'=>'�޸ı�ǩ��', 'delete_tag'=>'ɾ����ǩ��', 'tag_lists'=>'��ǩ���б�');
?>