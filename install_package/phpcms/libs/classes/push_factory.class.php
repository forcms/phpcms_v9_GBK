<?php
/**
 *  push_factory.class.php ������Ϣ������
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-8-2
 */

final class push_factory {
	
	/**
	 *  ������Ϣ�����ྲ̬ʵ��
	 */
	private static $push_factory;
	
	/**
	 * �ӿ�ʵ�����б�
	 */
	protected $api_list = array();
	
	/**
	 * ���ص�ǰ�ռ�������ʵ��
	 * @return object
	 */
	public static function get_instance() {
		if(push_factory::$push_factory == '') {
			push_factory::$push_factory = new push_factory();
		}
		return push_factory::$push_factory;
	}
	
	/**
	 * ��ȡapi����ʵ��
	 * @param string $classname �ӿڵ��õ����ļ���
	 * @param sting  $module	 ģ����
	 * @return object	 
	 */
	public function get_api($module = 'admin') {
		if(!isset($this->api_list[$module]) || !is_object($this->api_list[$module])) {
			$this->api_list[$module] = pc_base::load_app_class('push_api', $module);
		}
		return $this->api_list[$module];
	}
}