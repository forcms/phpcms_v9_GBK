<?php
/**
 *  db_factory.class.php ���ݿ⹤����
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-6-1
 */

final class db_factory {
	
	/**
	 * ��ǰ���ݿ⹤���ྲ̬ʵ��
	 */
	private static $db_factory;
	
	/**
	 * ���ݿ������б�
	 */
	protected $db_config = array();
	
	/**
	 * ���ݿ����ʵ�����б�
	 */
	protected $db_list = array();
	
	/**
	 * ���캯��
	 */
	public function __construct() {
	}
	
	/**
	 * ���ص�ǰ�ռ�������ʵ��
	 * @param $db_config ���ݿ�����
	 * @return object
	 */
	public static function get_instance($db_config = '') {
		if($db_config == '') {
			$db_config = pc_base::load_config('database');
		}
		if(db_factory::$db_factory == '') {
			db_factory::$db_factory = new db_factory();
		}
		if($db_config != '' && $db_config != db_factory::$db_factory->db_config) db_factory::$db_factory->db_config = array_merge($db_config, db_factory::$db_factory->db_config);
		return db_factory::$db_factory;
	}
	
	/**
	 * ��ȡ���ݿ����ʵ��
	 * @param $db_name ���ݿ���������
	 */
	public function get_database($db_name) {
		if(!isset($this->db_list[$db_name]) || !is_object($this->db_list[$db_name])) {
			$this->db_list[$db_name] = $this->connect($db_name);
		}
		return $this->db_list[$db_name];
	}
	
	/**
	 *  �������ݿ�����
	 * @param $db_name 	���ݿ���������
	 * @return object
	 */
	public function connect($db_name) {
		$object = null;
		switch($this->db_config[$db_name]['type']) {
			case 'mysql' :
				pc_base::load_sys_class('mysql', '', 0);
				$object = new mysql();
				break;
			case 'mysqli' :
				$object = pc_base::load_sys_class('mysqli');
				break;
			case 'access' :
				$object = pc_base::load_sys_class('db_access');
				break;
			default :
				pc_base::load_sys_class('mysql', '', 0);
				$object = new mysql();
		}
		$object->open($this->db_config[$db_name]);
		return $object;
	}

	/**
	 * �ر����ݿ�����
	 * @return void
	 */
	protected function close() {
		foreach($this->db_list as $db) {
			$db->close();
		}
	}
	
	/**
	 * ��������
	 */
	public function __destruct() {
		$this->close();
	}
}
?>