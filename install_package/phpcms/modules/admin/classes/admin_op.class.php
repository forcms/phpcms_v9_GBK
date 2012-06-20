<?php
defined('IN_PHPCMS') or exit('No permission resources.');

//�����ں�̨
define('IN_ADMIN',true);
class admin_op {
	public function __construct() {
		$this->db = pc_base::load_model('admin_model');
	}
	/*
	 * �޸�����
	 */
	public function edit_password($userid, $password){
		$userid = intval($userid);
		if($userid < 1) return false;
		if(!is_password($password))
		{
			showmessage(L('pwd_incorrect'));
			return false;
		}
		$passwordinfo = password($password);
		return $this->db->update($passwordinfo,array('userid'=>$userid));
	}
	/*
	 * ����û�������
	 */	
	public function checkname($username) {
		$username =  trim($username);
		if ($this->db->get_one(array('username'=>$username),'userid')){
			return false;
		}
		return true;
	}	
}
?>