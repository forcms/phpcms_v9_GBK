<?php
/**
 * վ�����ӿ�
 * @author chenzhouyu
 *
 */
class sites {
	//���ݿ�����
	private $db;
	public function __construct() {
		$this->db = pc_base::load_model('site_model');
	}
	
	/**
	 * ��ȡվ���б�
	 * @param string $roleid ��ɫID ����Ϊ��ȡ����վ���б�
	 */
	public function get_list($roleid='') {
		$roleid = intval($roleid);
		if(empty($roleid)) {
			if ($data = getcache('sitelist', 'commons')) {
				return $data;
			} else {
				$this->set_cache();
				return $this->db->select();
			}			
		} else {
			$site_arr = $this->get_role_siteid($roleid);
			$sql = "`siteid` in($site_arr)";
			return $this->db->select($sql);
		}

	}
	
	/**
	 * ��ID��ȡվ����Ϣ
	 * @param integer $siteid վ��ID��
	 */
	public function get_by_id($siteid) {
		return siteinfo($siteid);
	}
	
	/**
	 * ����վ�㻺��
	 */
	public function set_cache() {
		$list = $this->db->select();
		$data = array();
		foreach ($list as $key=>$val) {
			$data[$val['siteid']] = $val;
			$data[$val['siteid']]['url'] = $val['domain'] ? $val['domain'] : pc_base::load_config('system', 'web_path').$val['dirname'].'/';
		}
		setcache('sitelist', $data, 'commons');
	}
	
	/**
	 * PC��ǩ�е���վ���б�
	 */
	public function pc_tag_list() {
		$list = $this->db->select('', 'siteid,name');
		$sitelist = array(''=>L('please_select_a_site', '', 'admin'));
		foreach ($list as $k=>$v) {
			$sitelist[$v['siteid']] = $v['name'];
		}
		return $sitelist;
	}
	
	/**
	 * ����ɫID��ȡվ���б�
	 * @param string $roleid ��ɫID
	 */	
	
	public function get_role_siteid($roleid) {
		$roleid = intval($roleid);
		if($roleid == 1) {
			$sitelists = $this->get_list();
			foreach($sitelists as $v) {
				$sitelist[] = $v['siteid'];
			}
		} else {
			$sitelist = getcache('role_siteid', 'commons');
			$sitelist = $sitelist[$roleid];
		}
		if(is_array($sitelist)) 
		{
			$siteid = implode(',',array_unique($sitelist));
			return $siteid;			
		} else {
			showmessage(L('no_site_permissions'),'?m=admin&c=index&a=login');
		}
	}
}