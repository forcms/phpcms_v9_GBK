<?php

class cache_file {
	
	/*����Ĭ������*/
	protected $_setting = array(
								'suf' => '.cache.php',	/*�����ļ���׺*/
								'type' => 'array',		/*�����ʽ��array���飬serialize���л���null�ַ���*/
							);
	
	/*����·��*/
	protected $filepath = '';

	/**
	 * ���캯��
	 * @param	array	$setting	��������
	 * @return  void
	 */
	public function __construct($setting = '') {
		$this->get_setting($setting);
	}
	
	/**
	 * д�뻺��
	 * @param	string	$name		��������
	 * @param	mixed	$data		��������
	 * @param	array	$setting	��������
	 * @param	string	$type		��������
	 * @param	string	$module		����ģ��
	 * @return  mixed				����·��/false
	 */

	public function set($name, $data, $setting = '', $type = 'data', $module = ROUTE_M) {
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = ROUTE_M;
		$filepath = CACHE_PATH.'caches_'.$module.'/caches_'.$type.'/';
		$filename = $name.$this->_setting['suf'];
	    if(!is_dir($filepath)) {
			mkdir($filepath, 0777, true);
	    }
	    
	    if($this->_setting['type'] == 'array') {
	    	$data = "<?php\nreturn ".var_export($data, true).";\n?>";
	    } elseif($this->_setting['type'] == 'serialize') {
	    	$data = serialize($data);
	    }
	    
	    $file_size = file_put_contents($filepath.$filename, $data, LOCK_EX);
	    
	    return $file_size ? $file_size : 'false';
	}
	
	/**
	 * ��ȡ����
	 * @param	string	$name		��������
	 * @param	array	$setting	��������
	 * @param	string	$type		��������
	 * @param	string	$module		����ģ��
	 * @return  mixed	$data		��������
	 */
	public function get($name, $setting = '', $type = 'data', $module = ROUTE_M) {
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = ROUTE_M;
		$filepath = CACHE_PATH.'caches_'.$module.'/caches_'.$type.'/';
		$filename = $name.$this->_setting['suf'];
		if (!file_exists($filepath.$filename)) {
			return false;
		} else {
		    if($this->_setting['type'] == 'array') {
		    	$data = @require($filepath.$filename);
		    } elseif($this->_setting['type'] == 'serialize') {
		    	$data = unserialize(file_get_contents($filepath.$filename));
		    }
		    
		    return $data;
		}
	}
	
	/**
	 * ɾ������
	 * @param	string	$name		��������
	 * @param	array	$setting	��������
	 * @param	string	$type		��������
	 * @param	string	$module		����ģ��
	 * @return  bool
	 */
	public function delete($name, $setting = '', $type = 'data', $module = ROUTE_M) {
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = ROUTE_M;	
		$filepath = CACHE_PATH.'caches_'.$module.'/caches_'.$type.'/';
		$filename = $name.$this->_setting['suf'];
		if(file_exists($filepath.$filename)) {
			return @unlink($filepath.$filename) ? true : false;
		} else {
			return false;
		}
	}
	
	/**
	 * ��ϵͳ�������öԱȻ�ȡ�Զ��建������
	 * @param	array	$setting	�Զ��建������
	 * @return  array	$setting	��������
	 */
	public function get_setting($setting = '') {
		if($setting) {
			$this->_setting = array_merge($this->_setting, $setting);
		}
	}

	public function cacheinfo($name, $setting = '', $type = 'data', $module = ROUTE_M) {
		$this->get_setting($setting);
		if(empty($type)) $type = 'data';
		if(empty($module)) $module = ROUTE_M;
		$filepath = CACHE_PATH.'caches_'.$module.'/caches_'.$type.'/';
		$filename = $filepath.$name.$this->_setting['suf'];

		if(file_exists($filepath)) {
			$res['filename'] = $name.$this->_setting['suf'];
			$res['filepath'] = $filepath;
			$res['filectime'] = filectime($filename);
			$res['filemtime'] = filemtime($filename);
			$res['filesize'] = filesize($filename);
			return $res;
		} else {
			return false;
		}
	}

}

?>