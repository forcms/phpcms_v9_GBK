<?php
error_reporting(E_ALL);
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'formguide', 'parentid'=>29, 'm'=>'formguide', 'c'=>'formguide', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);
$menu_db->insert(array('name'=>'formguide_add', 'parentid'=>$parentid, 'm'=>'formguide', 'c'=>'formguide', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'formguide_edit', 'parentid'=>$parentid, 'm'=>'formguide', 'c'=>'formguide', 'a'=>'edit', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'form_info_list', 'parentid'=>$parentid, 'm'=>'formguide', 'c'=>'formguide_info', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'formguide_disabled', 'parentid'=>$parentid, 'm'=>'formguide', 'c'=>'formguide', 'a'=>'disabled', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'formguide_delete', 'parentid'=>$parentid, 'm'=>'formguide', 'c'=>'formguide', 'a'=>'delete', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'formguide_stat', 'parentid'=>$parentid, 'm'=>'formguide', 'c'=>'formguide', 'a'=>'stat', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$menu_db->insert(array('name'=>'add_public_field', 'parentid'=>$parentid, 'm'=>'formguide', 'c'=>'formguide_field', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'list_public_field', 'parentid'=>$parentid, 'm'=>'formguide', 'c'=>'formguide_field', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'));
$menu_db->insert(array('name'=>'module_setting', 'parentid'=>$parentid, 'm'=>'formguide', 'c'=>'formguide', 'a'=>'setting', 'data'=>'', 'listorder'=>0, 'display'=>'0'));

$language = array('formguide'=>'����', 'formguide_add'=>'��ӱ���', 'formguide_edit'=>'�޸ı���', 'form_info_list'=>'��Ϣ�б�', 'formguide_disabled'=>'���ñ�', 'formguide_delete'=>'ɾ����', 'formguide_stat'=>'��ͳ��', 'add_public_field'=>'��ӹ����ֶ�', 'list_public_field'=>'�������ֶ�', 'module_setting'=>'ģ������');
?>