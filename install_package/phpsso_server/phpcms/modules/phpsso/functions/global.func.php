<?php 

/**
 * ��������ַ���
 * @param string $lenth ����
 * @return string �ַ���
 */
function create_randomstr($lenth = 6) {
	return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}

/**
 * 
 * @param $password ����
 * @param $random �����
 */
function create_password($password='', $random='') {
	if(empty($random)) {
		$array['random'] = create_randomstr();
		$array['password'] = md5(md5($password).$array['random']);
		return $array;
	}
	return md5(md5($password).$random);
}

?>