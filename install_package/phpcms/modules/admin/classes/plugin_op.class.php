<?php
defined('IN_PHPCMS') or exit('No permission resources.');

//�����ں�̨
define('IN_ADMIN',true);
class plugin_op {
	private $db,$db_var;
	public function __construct(){
		$this->db_var = pc_base::load_model('plugin_var_model');
		$this->db = pc_base::load_model('plugin_var_model');
	}
	/**
	 * �����̨ģ�����
	 */	
	public function plugin_tpl($file,$identification) {
		return PC_PATH.'plugin'.DIRECTORY_SEPARATOR.$identification.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.$file.'.tpl.php';
	}
	
	/**
	 * ��ȡ����Զ��������Ϣ
	 * @param  $pluginid ���id
	 */
	public function getpluginvar($pluginid){
		if(empty($pluginid)) return flase;
		if($info_var = $this->db_var->select(array('pluginid'=>$pluginid))) {
			foreach ($info_var as $var) {
				$pluginvar[$var['fieldname']] = $var['value'];
			}
		}
		return 	$pluginvar;	
	}
	
	/**
	 * ��ȡ�������
	 * @param  $pluginid ���id
	 */
	function getplugincfg($pluginid) {
		$info = $this->db->get_one(array('pluginid'=>$pluginid));
		return $info;
	}
}
?>