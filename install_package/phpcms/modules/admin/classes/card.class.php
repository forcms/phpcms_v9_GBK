<?php
class card {
	static $server_url = 'http://safe.phpcms.cn/index.php';

	/**
	 * ��Զ�̷�������ȥȡKEY
	 */
	public static function get_key() {
		
		return self::_get_data('?op=key&release='.self::get_release());
	}

	public static function get_release() {
		return pc_base::load_config('version','pc_release');
	}
	
	/**
	 * ��Ƭ����ʾ��ַ
	 * @param $sn ������
	 */
	public static function get_pic($sn) {
		$key = self::get_key();
		return self::$server_url.'?op=card&release='.self::get_release().'&key='.urlencode($key).'&code='.urlencode(self::sys_auth("sn=$sn", 'ENCODE', $key));
	}
	
	/**
	 * �����µĿ�Ƭ
	 * @return ���ؿ�Ƭ��sn
	 */
	public static function creat_card() {
		$key = self::get_key();
		return self::_get_data('?op=creat_card&release='.self::get_release().'&key='.urlencode($key));
	}
	
	/**
	 * ��������
	 * @param string $sn ������
	 */
	public static function remove_card($sn) {
		$key = self::get_key();
		return self::_get_data('?op=remove&release='.self::get_release().'&key='.urlencode($key).'&code='.urlencode(self::sys_auth("sn=$sn", 'ENCODE', $key)));
	}
	
	/**
	 * ���������֤��
	 * @param string $sn ������
	 */
	public static function authe_rand($sn) {
		$key = self::get_key();
		$data = self::_get_data('?op=authe_request&release='.self::get_release().'&key='.urlencode($key).'&code='.urlencode(self::sys_auth("sn=$sn", 'ENCODE', $key)));
		return array('rand'=>$data,'url'=>self::$server_url.'?op=show_rand&release='.self::get_release().'&key='.urlencode($key).'&code='.urlencode(self::sys_auth("rand=$data", 'ENCODE', $key)));
	}
	
	/**
	 * ��֤��̬����
	 * @param string $sn     ������
	 * @param string $code   �û��������
	 * @param string $rand   �����
	 */
	public static function verification($sn, $code, $rand) {
		$key = self::get_key();
		return self::_get_data('?op=verification&release='.self::get_release().'&key='.urlencode($key).'&code='.urlencode(self::sys_auth("sn=$sn&code=$code&rand=$rand", 'ENCODE', $key)), 'index.php?m=admin&c=index&a=public_card');
	} 
	
	/**
	 * ����Զ������
	 * @param string $url       ��Ҫ����ĵ�ַ��
	 * @param string $backurl   ���ص�ַ
	 */
	private static function _get_data($url, $backurl = '') {
		if ($data = @file_get_contents(self::$server_url.$url)) {
			$data = json_decode($data, true);
			
			//���ϵͳ��GBK��ϵͳ����UTF8ת��ΪGBK
			if (pc_base::load_config('system', 'charset') == 'gbk') {
				$data =  array_iconv($data, 'utf-8', 'gbk');
			}
			
			if ($data['status'] != 1) {
				showmessage($data['msg'], $backurl);
			} else {
				return $data['msg'];
			}
		} else {
			showmessage(L('your_server_it_may_not_have_access_to').self::$server_url.L('_please_check_the_server_configuration'));
		}
	}

	private function sys_auth($txt, $operation = 'ENCODE', $key = '') {
		$key	= $key ? $key : 'oqjtioxiWRWKLEQJLKj';
		$txt	= $operation == 'ENCODE' ? (string)$txt : base64_decode($txt);
		$len	= strlen($key);
		$code	= '';
		for($i=0; $i<strlen($txt); $i++){
			$k		= $i % $len;
			$code  .= $txt[$i] ^ $key[$k];
		}
		$code = $operation == 'DECODE' ? $code : base64_encode($code);
	return $code;
}
}