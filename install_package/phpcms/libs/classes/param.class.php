<?php
/**
 *  param.class.php	����������
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-6-7
 */
class param {

	//·������
	private $route_config = '';
	
	public function __construct() {
		if(!get_magic_quotes_gpc()) {
			$_POST = new_addslashes($_POST);
			$_GET = new_addslashes($_GET);
			$_REQUEST = new_addslashes($_REQUEST);
			$_COOKIE = new_addslashes($_COOKIE);
		}

		$this->route_config = pc_base::load_config('route', SITE_URL) ? pc_base::load_config('route', SITE_URL) : pc_base::load_config('route', 'default');

		if(isset($this->route_config['data']['POST']) && is_array($this->route_config['data']['POST'])) {
			foreach($this->route_config['data']['POST'] as $_key => $_value) {
				if(!isset($_POST[$_key])) $_POST[$_key] = $_value;
			}
		}
		if(isset($this->route_config['data']['GET']) && is_array($this->route_config['data']['GET'])) {
			foreach($this->route_config['data']['GET'] as $_key => $_value) {
				if(!isset($_GET[$_key])) $_GET[$_key] = $_value;
			}
		}
		if(isset($_GET['page'])) {
			$_GET['page'] = max(intval($_GET['page']),1);
			$_GET['page'] = min($_GET['page'],1000000000);
		}
		return true;
	}

	/**
	 * ��ȡģ��
	 */
	public function route_m() {
		$m = isset($_GET['m']) && !empty($_GET['m']) ? $_GET['m'] : (isset($_POST['m']) && !empty($_POST['m']) ? $_POST['m'] : '');
		$m = $this->safe_deal($m);
		if (empty($m)) {
			return $this->route_config['m'];
		} else {
			return $m;
		}
	}

	/**
	 * ��ȡ������
	 */
	public function route_c() {
		$c = isset($_GET['c']) && !empty($_GET['c']) ? $_GET['c'] : (isset($_POST['c']) && !empty($_POST['c']) ? $_POST['c'] : '');
		$c = $this->safe_deal($c);
		if (empty($c)) {
			return $this->route_config['c'];
		} else {
			return $c;
		}
	}

	/**
	 * ��ȡ�¼�
	 */
	public function route_a() {
		$a = isset($_GET['a']) && !empty($_GET['a']) ? $_GET['a'] : (isset($_POST['a']) && !empty($_POST['a']) ? $_POST['a'] : '');
		$a = $this->safe_deal($a);
		if (empty($a)) {
			return $this->route_config['a'];
		} else {
			return $a;
		}
	}

	/**
	 * ���� cookie
	 * @param string $var     ������
	 * @param string $value   ����ֵ
	 * @param int $time    ����ʱ��
	 */
	public static function set_cookie($var, $value = '', $time = 0) {
		$time = $time > 0 ? $time : ($value == '' ? SYS_TIME - 3600 : 0);
		$s = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
		$var = pc_base::load_config('system','cookie_pre').$var;
		$_COOKIE[$var] = $value;
		if (is_array($value)) {
			foreach($value as $k=>$v) {
				setcookie($var.'['.$k.']', sys_auth($v, 'ENCODE'), $time, pc_base::load_config('system','cookie_path'), pc_base::load_config('system','cookie_domain'), $s);
			}
		} else {
			setcookie($var, sys_auth($value, 'ENCODE'), $time, pc_base::load_config('system','cookie_path'), pc_base::load_config('system','cookie_domain'), $s);
		}
	}

	/**
	 * ��ȡͨ�� set_cookie ���õ� cookie ���� 
	 * @param string $var ������
	 * @param string $default Ĭ��ֵ 
	 * @return mixed �ɹ��򷵻�cookie ֵ�����򷵻� false
	 */
	public static function get_cookie($var, $default = '') {
		$var = pc_base::load_config('system','cookie_pre').$var;
		return isset($_COOKIE[$var]) ? sys_auth($_COOKIE[$var], 'DECODE') : $default;
	}

	/**
	 * ��ȫ������
	 * ����m,a,c
	 */
	private function safe_deal($str) {
		return str_replace(array('/', '.'), '', $str);
	}

}
?>