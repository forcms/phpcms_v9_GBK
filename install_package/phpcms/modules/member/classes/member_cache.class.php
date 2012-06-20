<?php
/**
 * ��Ա�������
 *
 */
class member_cache {
	
	/**
	 * ����ģ�ͻ���
	 */
	public static function update_cache_model() {
		$sitemodel_db = pc_base::load_model('sitemodel_model');
		$data = $sitemodel_db->select(array('type'=>2), "*", 1000, 'sort', '', 'modelid');
		setcache('member_model', $data, 'commons');
		if(!defined('MODEL_PATH')) {
			//ģ��ԭ�ʹ洢·��
			define('MODEL_PATH',PC_PATH.'modules'.DIRECTORY_SEPARATOR.'member'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR);
		}
		if(!defined('CACHE_MODEL_PATH')) {
			//ģ�ͻ���·��
			define('CACHE_MODEL_PATH',PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
		}

		require MODEL_PATH.'fields.inc.php';
		//��������ģ���ࣺ�����ɡ���⡢���¡����
		$classtypes = array('form','input','update','output');
		foreach($classtypes as $classtype) {
			$cache_data = file_get_contents(MODEL_PATH.'member_'.$classtype.'.class.php');
			$cache_data = str_replace('}?>','',$cache_data);
			foreach($fields as $field=>$fieldvalue) {
				if(file_exists(MODEL_PATH.$field.DIRECTORY_SEPARATOR.$classtype.'.inc.php')) {
					$cache_data .= file_get_contents(MODEL_PATH.$field.DIRECTORY_SEPARATOR.$classtype.'.inc.php');
				}
			}
			$cache_data .= "\r\n } \r\n?>";
			file_put_contents(CACHE_MODEL_PATH.'member_'.$classtype.'.class.php',$cache_data);
			chmod(CACHE_MODEL_PATH.'member_'.$classtype.'.class.php',0777);
		}
		return true;
	}
	
}