<?php

class client {

	private $ps_api_url, $ps_auth_key, $ps_vsersion;
	/**
	 * ��������
	 * @param $ps_api_url �ӿ�����
	 * @param $ps_auth_key �����ܳ�
	 */
	public function __construct($ps_api_url='127.0.0.1', $ps_auth_key='', $ps_vsersion='1') {
		$this->ps_api_url = $ps_api_url;
		$this->ps_auth_key = $ps_auth_key;
		$this->ps_vsersion = $ps_vsersion;
	}
	
	/**
	 * �û�ע��
	 * @param string $username 	�û���
	 * @param string $password 	����
	 * @param string $email		email
	 * @param string $regip		ע��ip
	 * @param string $random	���������
	 * @return int {-1:�û����Ѿ����� ;-2:email�Ѵ���;-3:email��ʽ����;-4:�û�����ֹע��;-5:�����ֹע�᣻int(uid):�ɹ�}
	 */
	public function ps_member_register($username, $password, $email, $regip='', $random='') {
		if(!$this->_is_email($email)) {
			return -3;
		}
		 
		return $this->_ps_send('register', array('username'=>$username, 'password'=>$password, 'email'=>$email, 'regip'=>$regip, 'random'=>$random));
	}
	/**
	 * �û���½
	 * @param string $username 	�û���
	 * @param string $password 	����
	 * @param int $isemail	email
	 * @return int {-2;�������;-1:�û���������;array(userinfo):�û���Ϣ}
	 */
	public function ps_member_login($username, $password, $isemail=0) {
		if($isemail) {
			if(!$this->_is_email($username)) {
				return -3;
			}
			$return = $this->_ps_send('login', array('email'=>$username, 'password'=>$password));
		} else {
			$return = $this->_ps_send('login', array('username'=>$username, 'password'=>$password));
		}
		return $return;
	}
	
	/**
	 * ͬ����½
	 * @param string $uid
	 * @return string javascript�û�ͬ����½js
	 */
	public function ps_member_synlogin($uid) {
		$uid = intval($uid);
		return $this->_ps_send('synlogin', array('uid'=>$uid));
	}

	/**
	 * ͬ���˳�
	 * @param string $uid
	 * @return string javascript�û�ͬ���˳�js
	 */
	public function ps_member_synlogout() {
		return $this->_ps_send('synlogout', array());
	}
	
	/**
	 * �༭�û�
	 * @param string $username		�û���
	 * @param string $email			email
	 * @param string $password		������
	 * @param string $newpassword	������
	 * @param int $uid				phpsso�û�uid
	 * @param string $random	 	���������
	 * @return int {-1:�û�������;-2:���������;-3:email�Ѿ����� ;-4:email��ʽ����;1:�ɹ�;0:δ���޸�}
	 */
	public function ps_member_edit($username, $email, $password='', $newpassword='', $uid='', $random='') {
		if($email && !$this->_is_email($email)) {
			return -4;
		}
		return $this->_ps_send('edit', array('username'=>$username, 'password'=>$password, 'newpassword'=>$newpassword, 'email'=>$email, 'uid'=>$uid, 'random'=>$random));
	}

	/**
	 * ɾ���û�ͷ��
	 * @param int $uid				phpsso�û�uid
	 * @return int {1:�ɹ�;0:ʧ��}
	 */
	public function ps_deleteavatar($uid) {
		return $this->_ps_send('deleteavatar', array('uid'=>$uid));
	}
	
	/**
	 * ��ȡ�û���Ϣ
	 * @param $mix �û�id/�û���/email
	 * @param $type {1:�û�id;2:�û���;3:email}
	 * @return $mix {-1:�û�������;userinfo:�û���Ϣ}
	 */
	public function ps_get_member_info($mix, $type=1) {
		if($type==1) {
			$userinfo = $this->_ps_send('getuserinfo', array('uid'=>$mix));
		} elseif($type==2) {
			$userinfo = $this->_ps_send('getuserinfo', array('username'=>$mix));
		} elseif($type==3) {
			if(!$this->_is_email($mix)) {
				return -4;
			}
			$userinfo = $this->_ps_send('getuserinfo', array('email'=>$mix));
		}
		if($userinfo) {
			return $userinfo;
		} else {
			return -1;
		}
	}

	/**
	 * ɾ���û�
	 * @param mix {1:�û�id;2:�û���;3:email} ������û�id����Ϊ����
	 * @return int {-1:�û�������;1:ɾ���ɹ�}
	 */
	public function ps_delete_member($mix, $type=1) {
		if($type==1) {
			$res = $this->_ps_send('delete', array('uid'=>$mix));
		} elseif($type==2) {
			$res = $this->_ps_send('delete', array('username'=>$mix));
		} elseif($type==3) {
			if(!$this->_is_email($mix)) {
				return -4;
			}
			$res = $this->_ps_send('delete', array('email'=>$mix));
		}
		return $res;
	}
	
	/**
	 * ����û��Ƿ����ע��
	 * @param string $username
	 * @return int {-4���û�����ֹע��;-1:�û����Ѿ����� ;1:�ɹ�}
	 */
	public function ps_checkname($username) {
		return $this->_ps_send('checkname', array('username'=>$username));
	}

	/**
	 * ��������Ƿ����ע��
	 * @param string $email
	 * @return int {-1:email�Ѿ����� ;-5:�����ֹע��;1:�ɹ�}
	 */
	public function ps_checkemail($email) {
		return $this->_ps_send('checkemail', array('email'=>$email));
	}
	
	/**
	 * ��ȡӦ���б���Ϣ
	 */
	public function ps_getapplist() {
		return $this->_ps_send('getapplist', array());
	}

	/**
	 * ��ȡ���ֶһ������б�
	 */
	public function ps_getcreditlist() {
		return $this->_ps_send('getcredit', array());
	}

	/**
	 * �һ�����
	 * ���ں�����Ӧ��֮����ֶһ�
	 * @param int $uid			phpssouid
	 * @param int $from			��ϵͳ��������id
	 * @param int $toappid 		Ŀ��ϵͳӦ��appid
	 * @param int $to			Ŀ��ϵͳ��������id
	 * @param int $credit		��ϵͳ�۳�������
	 * @return bool 			{1:�ɹ�;0:ʧ��}
	 */
	public function ps_changecredit($uid, $from, $toappid, $to, $credit) {
		return $this->_ps_send('changecredit', array('uid'=>$uid, 'from'=>$from, 'toappid'=>$toappid, 'to'=>$to, 'credit'=>$credit));
	}
	
	/**
	 * ����phpsso uid��ȡͷ��url
	 * @param int $uid �û�id
	 * @return array �ĸ��ߴ��û�ͷ������
	 */
	public function ps_getavatar($uid) {
		$dir1 = ceil($uid / 10000);
		$dir2 = ceil($uid % 10000 / 1000);
		$url = $this->ps_api_url.'/uploadfile/avatar/'.$dir1.'/'.$dir2.'/'.$uid.'/';
		$avatar = array('180'=>$url.'180x180.jpg', '90'=>$url.'90x90.jpg', '45'=>$url.'45x45.jpg', '30'=>$url.'30x30.jpg');
		return $avatar;
	}

	/**
	 * ��ȡ�ϴ�ͷ��flash��html����
	 * @param int $uid �û�id
	 */
	public function ps_getavatar_upload_html($uid) {
		$auth_data = $this->auth_data(array('uid'=>$uid, 'ps_auth_key'=>$this->ps_auth_key), '', $this->ps_auth_key);
		$upurl = base64_encode($this->ps_api_url.'/index.php?m=phpsso&c=index&a=uploadavatar&auth_data='.$auth_data);
		$str = <<<EOF
				<div id="phpsso_uploadavatar_flash"></div>
				<script language="javascript" type="text/javascript" src="{$this->ps_api_url}/statics/js/swfobject.js"></script>
				<script type="text/javascript">
					var flashvars = {
						'upurl':"{$upurl}&callback=return_avatar&"
					}; 
					var params = {
						'align':'middle',
						'play':'true',
						'loop':'false',
						'scale':'showall',
						'wmode':'window',
						'devicefont':'true',
						'id':'Main',
						'bgcolor':'#ffffff',
						'name':'Main',
						'allowscriptaccess':'always'
					}; 
					var attributes = {
						
					}; 
					swfobject.embedSWF("{$this->ps_api_url}/statics/images/main.swf", "phpsso_uploadavatar_flash", "490", "434", "9.0.0","{$this->ps_api_url}/statics/images/expressInstall.swf", flashvars, params, attributes);

					function return_avatar(data) {
						if(data == 1) {
							window.location.reload();
						} else {
							alert('failure');
						}
					}
				</script> 
EOF;
		return $str;
	}
	/**
	* �ַ������ܡ����ܺ���
	*
	*
	* @param	string	$txt		�ַ���
	* @param	string	$operation	ENCODEΪ���ܣ�DECODEΪ���ܣ���ѡ������Ĭ��ΪENCODE��
	* @param	string	$key		��Կ�����֡���ĸ���»���
	* @param	string	$expiry		����ʱ��
	* @return	string
	*/
	function sys_auth($string, $operation = 'ENCODE', $key = '', $expiry = 0) {
		$key_length = 4;
		$key = md5($key != '' ? $key : $this->ps_auth_key);
		$fixedkey = hash('md5', $key);
		$egiskeys = md5(substr($fixedkey, 16, 16));
		$runtokey = $key_length ? ($operation == 'ENCODE' ? substr(hash('md5', microtime(true)), -$key_length) : substr($string, 0, $key_length)) : ''; 
		$keys = hash('md5', substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
		$string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length));
		
		$i = 0; $result = '';
		$string_length = strlen($string);
		for ($i = 0; $i < $string_length; $i++){
			$result .= chr(ord($string{$i}) ^ ord($keys{$i % 32}));
		}
		if($operation == 'ENCODE') {
			return $runtokey . str_replace('=', '', base64_encode($result));
		} else {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$egiskeys), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		}
	}

	/**
	* ������ת��Ϊ�ַ���
	*
	* @param	array	$data		����
	* @param	bool	$isformdata	���Ϊ0����ʹ��new_stripslashes������ѡ������Ĭ��Ϊ1
	* @return	string	�����ַ����������dataΪ�գ��򷵻ؿ�
	*/
	public function array2string($data, $isformdata = 1) {
		if($data == '') return '';
		if($isformdata) $data = new_stripslashes($data);
		return var_export($data, TRUE);
	}

	public function auth_data($data) {
		$s = $sep = '';
		foreach($data as $k => $v) {
			if(is_array($v)) {
				$s2 = $sep2 = '';
				foreach($v as $k2 => $v2) {
						$s2 .= "$sep2{$k}[$k2]=".$this->_ps_stripslashes($v2);
					$sep2 = '&';
				}
				$s .= $sep.$s2;
			} else {
				$s .= "$sep$k=".$this->_ps_stripslashes($v);
			}
			$sep = '&';
		}

		$auth_s = 'v='.$this->ps_vsersion.'&appid='.APPID.'&data='.urlencode($this->sys_auth($s));
		return $auth_s;
	}
	
	/**
	 * ��������
	 * @param $action ����
	 * @param $data ����
	 */
	private function _ps_send($action, $data = null) {
//echo $this->ps_api_url."/index.php?m=phpsso&c=index&a=".$action;exit;
		return $this->_ps_post($this->ps_api_url."/index.php?m=phpsso&c=index&a=".$action, 500000, $this->auth_data($data));
	}
	
	/**
	 *  post����
	 *  @param string $url		post��url
	 *  @param int $limit		���ص����ݵĳ���
	 *  @param string $post		post���ݣ��ַ�����ʽusername='dalarge'&password='123456'
	 *  @param string $cookie	ģ�� cookie���ַ�����ʽusername='dalarge'&password='123456'
	 *  @param string $ip		ip��ַ
	 *  @param int $timeout		���ӳ�ʱʱ��
	 *  @param bool $block		�Ƿ�Ϊ����ģʽ
	 *  @return string			�����ַ���
	 */
	
	private function _ps_post($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 15, $block = true) {
		$return = '';
		$matches = parse_url($url);
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		$siteurl = $this->_get_url();
		if($post) {
			$out = "POST $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: ".$siteurl."\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n" ;
			$out .= 'Content-Length: '.strlen($post)."\r\n" ;
			$out .= "Connection: Close\r\n" ;
			$out .= "Cache-Control: no-cache\r\n" ;
			$out .= "Cookie: $cookie\r\n\r\n" ;
			$out .= $post ;
		} else {
			$out = "GET $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: ".$siteurl."\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		if(!$fp) return '';
	
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
	
		if($status['timed_out']) return '';	
		while (!feof($fp)) {
			if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n"))  break;				
		}
		
		$stop = false;
		while(!feof($fp) && !$stop) {
			$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
			$return .= $data;
			if($limit) {
				$limit -= strlen($data);
				$stop = $limit <= 0;
			}
		}
		@fclose($fp);
		
		//������������������ֵ�����ݲ�ȷ��ԭ�򣬹��˷������ݸ�ʽ
		$return_arr = explode("\n", $return);
		if(isset($return_arr[1])) {
			$return = trim($return_arr[1]);
		}
		unset($return_arr);
		
		return $return;
	}

	/**
	 * �����ַ���
	 * @param $string
	 */
	private function _ps_stripslashes($string) {
		!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
		if(MAGIC_QUOTES_GPC) {
			return stripslashes($string);
		} else {
			return $string;
		}
	}
	
	
	/**
	 * ��ȡ��ǰҳ������URL��ַ
	 */
	private function _get_url() {
		$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
		$php_self = $_SERVER['PHP_SELF'] ? $this->_safe_replace($_SERVER['PHP_SELF']) : $this->_safe_replace($_SERVER['SCRIPT_NAME']);
		$path_info = isset($_SERVER['PATH_INFO']) ? $this->_safe_replace($_SERVER['PATH_INFO']) : '';
		$relate_url = isset($_SERVER['REQUEST_URI']) ? $this->_safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$this->_safe_replace($_SERVER['QUERY_STRING']) : $path_info);
		return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
	}
	/**
	 * ��ȫ���˺���
	 *
	 * @param $string
	 * @return string
	 */
	private function _safe_replace($string) {
		$string = str_replace('%20','',$string);
		$string = str_replace('%27','',$string);
		$string = str_replace('%2527','',$string);
		$string = str_replace('*','',$string);
		$string = str_replace('"','&quot;',$string);
		$string = str_replace("'",'',$string);
		$string = str_replace('"','',$string);
		$string = str_replace(';','',$string);
		$string = str_replace('<','&lt;',$string);
		$string = str_replace('>','&gt;',$string);
		$string = str_replace("{",'',$string);
		$string = str_replace('}','',$string);
		$string = str_replace('\\','',$string);
		return $string;
	}
	
	/**
	 * �ж�email��ʽ�Ƿ���ȷ
	 * @param $string email
	 */
	private function _is_email($email) {
		return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
	}
}



?>