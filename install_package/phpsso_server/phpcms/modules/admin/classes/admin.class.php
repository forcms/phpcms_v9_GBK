<?php
define('IN_ADMIN', true);
class admin {
	//���ݿ�����
	private $db;
	//�������
	private $err_code;
	
	/**
	 * ���캯��
	 * @param integer $issuper �Ƿ�Ϊ��������Ա
	 */
	public function __construct($issuper = 0) {
		$this->db = pc_base::load_model('admin_model');	
		$this->check_admin($issuper);
		pc_base::load_app_func('global');
	}
	
	/**
	 * ����ԱȨ���ж�
	 * @param integer $issuper �Ƿ�Ϊ��������Ա
	 */
	public function check_admin($issuper = 0) {
		if (ROUTE_C != 'login') {
			if (!$this->get_userid() || !$this->get_username()) {
				$forward = isset($_GET['forward']) ? urlencode($_GET['forward']) : '';
				showmessage(L('relogin'),'?m=admin&c=login&a=init&forward='.$forward);
				unset($forward);
			}
			if ($issuper) {
				$r = $this->get_userinfo();
				if ($r['issuper'] != 1) {
					showmessage(L('eaccess'));
				}
			}
		}
	}
	
	/**
	 * ����Ա��½
	 * @param string $username �û���
	 * @param string $password ����
	 * @return boolean
	 */
	public function login($username, $password) {
		if (!$this->db) {
			$this->db = pc_base::load_model('admin_model');			
		}
		if ($data = $this->db->get_one(array('username'=>$username))) {
			$password = md5(md5($password).$data['encrypt']);
			if ($password != $data['password']) {
				$this->err_code = 2;
				return false;
			} elseif ($password == $data['password']) {
				$this->db->update(array('ip'=>ip(), 'lastlogin'=>SYS_TIME),array('id'=>$data['id']));
				param::set_cookie('username', $username);
				param::set_cookie('userid', $data['id']);
				return true;
			}
			$this->err_code = 0;
			return false;
		} else {
			$this->err_code = 1;
			return false;
		}
	}
	
	public function log_out() {
		param::set_cookie('username', '');
		param::set_cookie('userid', '');
	}
	
	/**
	 * ��ȡ��ǰ�û�ID��
	 */
	public function get_userid() {
		return param::get_cookie('userid');
	}
	
	/**
	 * ��ȡ��ǰ�û���
	 */
	public function get_username() {
		return param::get_cookie('username');
	}
	
	/**
	 * ��ȡ��ǰ�û���Ϣ
	 * @param string $filed ��ȡָ���ֶ�
	 * @param string $enforce ǿ�Ƹ���
	 */
	public function get_userinfo($filed = '', $enforce = 0) {
		static $data;
		if ($data && !$enforce) {
			if($filed && isset($data[$filed])) {
				return $data[$filed];
			} elseif ($filed && !isset($data[$filed])) {
				return false;
			} else {
				return $data;
			}
		}
		$data = $this->db->get_one(array('username'=>$this->get_username(),'id'=>$this->get_userid()));
		if($filed && isset($data[$filed])) {
			return $data[$filed];
		} elseif ($filed && !isset($data[$filed])) {
			return false;
		} else {
			return $data;
		}
	}
	
	/**
	 * ��ȡ����ԭ��
	 */
	public function get_err() {
		$msg = array(
		'-1'=>L('database_error'),
		'0'=>L('unknown_error'),
		'1'=>L('User_name_could_not_find'),
		'2'=>L('incorrect_password'),
		);
		return $msg[$this->err_code];
	}

	/**
	 * ���غ�̨ģ��
	 * @param string $file �ļ���
	 * @param string $m ģ����
	 */
	public static function admin_tpl($file, $m = '') {
		$m = empty($m) ? ROUTE_M : $m;
		if(empty($m)) return false;
		return PC_PATH.'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$file.'.tpl.php';
	}
}