<?php
defined('IN_PHPCMS') or exit('No permission resources.');

//�����ں�̨
define('IN_ADMIN',true);
class role_op {	
	public function __construct() {
		$this->db = pc_base::load_model('admin_role_model');
		$this->priv_db = pc_base::load_model('admin_role_priv_model');
	}
	/**
	 * ��ȡ��ɫ��������
	 * @param int $roleid ��ɫID
	 */
	public function get_rolename($roleid) {
		$roleid = intval($roleid);
		$search_field = '`roleid`,`rolename`';
		$info = $this->db->get_one(array('roleid'=>$roleid),$search_field);
		return $info;
	}
		
	/**
	 * ����ɫ�����ظ�
	 * @param $name ��ɫ������
	 */
	public function checkname($name) {
		$info = $this->db->get_one(array('rolename'=>$name),'roleid');
		if($info[roleid]){
			return true;
		}
		return false;
	}
	
	/**
	 * ��ȡ�˵�����Ϣ
	 * @param int $menuid �˵�ID
	 * @param int $menu_info �˵�����
	 */
	public function get_menuinfo($menuid,$menu_info) {
		$menuid = intval($menuid);
		unset($menu_info[$menuid][id]);
		return $menu_info[$menuid];
	}
	
	/**
	 *  ���ָ���˵��Ƿ���Ȩ��
	 * @param array $data menu��������
	 * @param int $roleid ��Ҫ���Ľ�ɫID
	 */
	public function is_checked($data,$roleid,$siteid,$priv_data) {
		$priv_arr = array('m','c','a','data');
		if($data['m'] == '') return false;
		foreach($data as $key=>$value){
			if(!in_array($key,$priv_arr)) unset($data[$key]);
		}
		$data['roleid'] = $roleid;
		$data['siteid'] = $siteid;
		$info = in_array($data, $priv_data);
		if($info){
			return true;
		} else {
			return false;
		}
		
	}
	/**
	 * �Ƿ�Ϊ����״̬
	 */
	public function is_setting($siteid,$roleid) {
		$siteid = intval($siteid);
		$roleid = intval($roleid);
		$sqls = "`siteid`='$siteid' AND `roleid` = '$roleid' AND `m` != ''";
		$result = $this->priv_db->get_one($sqls);
		return $result ? true : false;
	}
	/**
	 * ��ȡ�˵����
	 * @param $id
	 * @param $array
	 * @param $i
	 */
	public function get_level($id,$array=array(),$i=0) {
		foreach($array as $n=>$value){
			if($value['id'] == $id)
			{
				if($value['parentid']== '0') return $i;
				$i++;
				return $this->get_level($value['parentid'],$array,$i);
			}
		}
	}
}
?>