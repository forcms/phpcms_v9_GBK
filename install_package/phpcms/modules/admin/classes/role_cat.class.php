<?php
defined('IN_PHPCMS') or exit('No permission resources.');

class role_cat {
	//���ݿ�����
	static $db;
	
	private static function _connect() {
		self::$db = pc_base::load_model('category_priv_model');
	}
	
	/**
	 * ��ȡ��ɫ����Ȩ��
	 * @param integer $roleid  ��ɫID
	 * @param integer $siteid  վ��ID
	 */
	public static function get_roleid($roleid, $siteid) {
		if (empty(self::$db)) {
			self::_connect();
		}
		if ($data = self::$db->select("`roleid` = '$roleid' AND `is_admin` = '1' AND `siteid` IN ('$siteid') ")) {
			$priv = array();
			foreach ($data as $k=>$v) {
				$priv[$v['catid']][$v['action']] = true;
			}
			return $priv;
		} else {
			return false;
		}
	}
	
	/**
	 * ��ȡվ����Ŀ�б�
	 * @param integer $siteid  վ��ID
	 * @return array()         ����Ϊ����
	 */
	public static function get_category($siteid) {
		$category = getcache('category_content_'.$siteid, 'commons');
		foreach ($category as $k=>$v) {
			if (!in_array($v['type'], array(0,1))) unset($category[$k]); 
		}
		return $category;
	}
	
	/**
	 * �������ݿ���Ϣ 
	 * @param integer $roleid   ��ɫID
	 * @param integer $siteid   վ��ID
	 * @param array $data       ��Ҫ���µ�����
	 */
	public static function updata_priv($roleid, $siteid, $data) {
		if (empty(self::$db)) {
			self::_connect();
		}
		//ɾ���ý�ɫ��ǰ��Ȩ��
		self::$db->delete(array('roleid'=>$roleid, 'siteid'=>$siteid, 'is_admin'=>1));
		foreach ($data as $k=>$v) {
			if (is_array($v) && !empty($v[0])) {
				foreach ($v as $key=>$val) {
					self::$db->insert(array('siteid'=>$siteid, 'catid'=>$k, 'is_admin'=>1, 'roleid'=>$roleid, 'action'=>$val));
				}
			}
		}
	}
}