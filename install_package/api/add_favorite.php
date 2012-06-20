<?php
/**
 * �ղ�url�������¼
 * @param url ��ַ����urlencode����ֹ�������
 * @param title ���⣬��urlencode����ֹ�������
 * @return {1:�ɹ�;-1:δ��¼;-2:ȱ�ٲ���}
 */
defined('IN_PHPCMS') or exit('No permission resources.');

if(empty($_GET['title']) || empty($_GET['url'])) {
	exit('-2');	
} else {
	$title = $_GET['title'];
	$title = urldecode($title);
	if(CHARSET != 'utf-8') {
		$title = iconv('utf-8', CHARSET, $title);
	}
	
	$title = htmlspecialchars($title);
	$url = safe_replace(urldecode($_GET['url']));
}
$_GET['callback'] = safe_replace($_GET['callback']);
//�ж��Ƿ��¼	
$phpcms_auth = param::get_cookie('auth');
if($phpcms_auth) {
	$auth_key = md5(pc_base::load_config('system', 'auth_key').$_SERVER['HTTP_USER_AGENT']);
	list($userid, $password) = explode("\t", sys_auth($phpcms_auth, 'DECODE', $auth_key));
	if($userid >0) {

	} else {
		exit(trim_script($_GET['callback']).'('.json_encode(array('status'=>-1)).')');
	} 
} else {
	exit(trim_script($_GET['callback']).'('.json_encode(array('status'=>-1)).')');
}

$favorite_db = pc_base::load_model('favorite_model');
$data = array('title'=>$title, 'url'=>$url, 'adddate'=>SYS_TIME, 'userid'=>$userid);
//����url�ж��Ƿ��Ѿ��ղع���
$is_exists = $favorite_db->get_one(array('url'=>$url, 'userid'=>$userid));
if(!$is_exists) {
	$favorite_db->insert($data);
}
exit(trim_script($_GET['callback']).'('.json_encode(array('status'=>1)).')');

?>