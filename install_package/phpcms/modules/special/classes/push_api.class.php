<?php
/**
 * push_api.class.php ר�����ͽӿ���
 * 
 */
defined('IN_PHPCMS') or exit('No permission resources.');

class push_api {
	private $special_api;
	
	public function __construct() {
		$this->special_api = pc_base::load_app_class('special_api', 'special');
	}
	
	/**
	 * ��Ϣ�Ƽ���ר��ӿ�
	 * @param array $param ���� ����ʱ��Ϊģ�͡���Ŀ���顣 ����array('modelid'=>1, 'catid'=>12); �ύ���Ϊ��ά��Ϣ���� ������array(1=>array('title'=>'�෢���ͷ���', ....))
	 * @param array $arr ���� �����ݣ�ֻ���������ʱ���ݡ�
	 * @return ����ר��������б� 
	 */
	public function _push_special($param = array(), $arr = array()) {
		return $this->special_api->_get_special($param, $arr);
	}
	
	public function _get_type($specialid) {
		return $this->special_api->_get_type($specialid);
	}
}
?>