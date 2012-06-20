<?php
defined('IN_PHPCMS') or exit('No permission resources.');
//ģ��ԭ�ʹ洢·��
define('MODEL_PATH',PC_PATH.'modules'.DIRECTORY_SEPARATOR.'formguide'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR);
//ģ�ͻ���·��
define('CACHE_MODEL_PATH',PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
/**
 * ����form��ģ����
 * @author 
 *
 */
class formguide {

	public function __construct() {
		
	}
	
	/**
	 * ����ģ�ͻ��淽��
	 */
	public function public_cache() {
		require MODEL_PATH.'fields.inc.php';
		//��������ģ���ࣺ�����ɡ���⡢���¡����
		$classtypes = array('form','input','update','output');
		foreach($classtypes as $classtype) {
			$cache_data = file_get_contents(MODEL_PATH.'formguide_'.$classtype.'.class.php');
			$cache_data = str_replace('}?>','',$cache_data);
			foreach($fields as $field=>$fieldvalue) {
				if(file_exists(MODEL_PATH.$field.DIRECTORY_SEPARATOR.$classtype.'.inc.php')) {
					$cache_data .= file_get_contents(MODEL_PATH.$field.DIRECTORY_SEPARATOR.$classtype.'.inc.php');
				}
			}
			$cache_data .= "\r\n } \r\n?>";
			file_put_contents(CACHE_MODEL_PATH.'formguide_'.$classtype.'.class.php',$cache_data);
			@chmod(CACHE_MODEL_PATH.'formguide_'.$classtype.'.class.php',0777);
		}
		return true;
	}
}
?>