<?php
/**
 *  base.php PHPCMS�������ļ�
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-6-7
 */
define('IN_PHPCMS', true);

//PHPCMS���·��
define('PC_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

if(!defined('PHPCMS_PATH')) define('PHPCMS_PATH', PC_PATH.'..'.DIRECTORY_SEPARATOR);

//�����ļ��е�ַ
define('CACHE_PATH', PC_PATH.'..'.DIRECTORY_SEPARATOR.'caches'.DIRECTORY_SEPARATOR);
//����Э��
define('SITE_PROTOCOL', isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://');
//��ǰ���ʵ�������
define('SITE_URL', (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ''));
//��Դ
define('HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');

//ϵͳ��ʼʱ��
define('SYS_START_TIME', microtime());

//���ع��ú�����
pc_base::load_sys_func('global');
pc_base::load_config('system','errorlog') ? set_error_handler('my_error_handler') : error_reporting(E_ERROR | E_WARNING | E_PARSE);
//���ñ���ʱ��
function_exists('date_default_timezone_set') && date_default_timezone_set(pc_base::load_config('system','timezone'));

define('CHARSET' ,pc_base::load_config('system','charset'));
//���ҳ���ַ���
header('Content-type: text/html; charset='.CHARSET);

define('SYS_TIME', time());
//������վ��·��
define('WEB_PATH',pc_base::load_config('system','web_path'));
//js ·��
define('JS_PATH',pc_base::load_config('system','js_path'));
//css ·��
define('CSS_PATH',pc_base::load_config('system','css_path'));
//img ·��
define('IMG_PATH',pc_base::load_config('system','img_path'));
//��̬����·��
define('APP_PATH',pc_base::load_config('system','app_path'));

if(pc_base::load_config('system','gzip') && function_exists('ob_gzhandler')) {
	ob_start('ob_gzhandler');
} else {
	ob_start();
}

class pc_base {
	
	/**
	 * ��ʼ��Ӧ�ó���
	 */
	public static function creat_app() {
		return self::load_sys_class('application');
	}
	/**
	 * ����ϵͳ�෽��
	 * @param string $classname ����
	 * @param string $path ��չ��ַ
	 * @param intger $initialize �Ƿ��ʼ��
	 */
	public static function load_sys_class($classname, $path = '', $initialize = 1) {
			return self::_load_class($classname, $path, $initialize);
	}
	
	/**
	 * ����Ӧ���෽��
	 * @param string $classname ����
	 * @param string $m ģ��
	 * @param intger $initialize �Ƿ��ʼ��
	 */
	public static function load_app_class($classname, $m = '', $initialize = 1) {
		$m = empty($m) && defined('ROUTE_M') ? ROUTE_M : $m;
		if (empty($m)) return false;
		return self::_load_class($classname, 'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'classes', $initialize);
	}
	
	/**
	 * ��������ģ��
	 * @param string $classname ����
	 */
	public static function load_model($classname) {
		return self::_load_class($classname,'model');
	}
		
	/**
	 * �������ļ�����
	 * @param string $classname ����
	 * @param string $path ��չ��ַ
	 * @param intger $initialize �Ƿ��ʼ��
	 */
	private static function _load_class($classname, $path = '', $initialize = 1) {
		static $classes = array();
		if (empty($path)) $path = 'libs'.DIRECTORY_SEPARATOR.'classes';
		$key = md5($path.$classname);
		if (isset($classes[$key])) {
			if (!empty($classes[$key])) {
				return $classes[$key];
			} else {
				return true;
			}
		}
		if (file_exists(PC_PATH.$path.DIRECTORY_SEPARATOR.$classname.'.class.php')) {
			include PC_PATH.$path.DIRECTORY_SEPARATOR.$classname.'.class.php';
			$name = $classname;
			if ($my_path = self::my_path(PC_PATH.$path.DIRECTORY_SEPARATOR.$classname.'.class.php')) {
				include $my_path;
				$name = 'MY_'.$classname;
			}
			if ($initialize) {
				$classes[$key] = new $name;
			} else {
				$classes[$key] = true;
			}
			return $classes[$key];
		} else {
			return false;
		}
	}
	
	/**
	 * ����ϵͳ�ĺ�����
	 * @param string $func ��������
	 */
	public static function load_sys_func($func) {
		return self::_load_func($func);
	}
	
	/**
	 * ����Ӧ�ú�����
	 * @param string $func ��������
	 * @param string $m ģ����
	 */
	public static function load_app_func($func, $m = '') {
		$m = empty($m) && defined('ROUTE_M') ? ROUTE_M : $m;
		if (empty($m)) return false;
		return self::_load_func($func, 'modules'.DIRECTORY_SEPARATOR.$m.DIRECTORY_SEPARATOR.'functions');
	}
	
	/**
	 * ���غ�����
	 * @param string $func ������
	 * @param string $path ��ַ
	 */
	private static function _load_func($func, $path = '') {
		static $funcs = array();
		if (empty($path)) $path = 'libs'.DIRECTORY_SEPARATOR.'functions';
		$path .= DIRECTORY_SEPARATOR.$func.'.func.php';
		$key = md5($path);
		if (isset($funcs[$key])) return true;
		if (file_exists(PC_PATH.$path)) {
			include PC_PATH.$path;
		} else {
			$funcs[$key] = false;
			return false;
		}
		$funcs[$key] = true;
		return true;
	}
	
	/**
	 * �Ƿ����Լ�����չ�ļ�
	 * @param string $filepath ·��
	 */
	public static function my_path($filepath) {
		$path = pathinfo($filepath);
		if (file_exists($path['dirname'].DIRECTORY_SEPARATOR.'MY_'.$path['basename'])) {
			return $path['dirname'].DIRECTORY_SEPARATOR.'MY_'.$path['basename'];
		} else {
			return false;
		}
	}
	
	/**
	 * ���������ļ�
	 * @param string $file �����ļ�
	 * @param string $key  Ҫ��ȡ�����ü�
	 * @param string $default  Ĭ�����á�����ȡ������Ŀʧ��ʱ��ֵ�������á�
	 * @param boolean $reload ǿ�����¼��ء�
	 */
	public static function load_config($file, $key = '', $default = '', $reload = false) {
		static $configs = array();
		if (!$reload && isset($configs[$file])) {
			if (empty($key)) {
				return $configs[$file];
			} elseif (isset($configs[$file][$key])) {
				return $configs[$file][$key];
			} else {
				return $default;
			}
		}
		$path = CACHE_PATH.'configs'.DIRECTORY_SEPARATOR.$file.'.php';
		if (file_exists($path)) {
			$configs[$file] = include $path;
		}
		if (empty($key)) {
			return $configs[$file];
		} elseif (isset($configs[$file][$key])) {
			return $configs[$file][$key];
		} else {
			return $default;
		}
	}
}