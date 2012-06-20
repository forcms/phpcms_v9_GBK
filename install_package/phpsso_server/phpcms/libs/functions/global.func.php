<?php
/**
 *  global.func.php ����������
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-6-1
 */

/**
 * ���ؾ�addslashes��������ַ���������
 * @param $string ��Ҫ������ַ���������
 * @return mixed
 */
function new_addslashes($string) {
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = new_addslashes($val);
	return $string;
}

/**
 * ���ؾ�stripslashes��������ַ���������
 * @param $string ��Ҫ������ַ���������
 * @return mixed
 */
function new_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}

/**
 * ���ؾ�addslashe��������ַ���������
 * @param $obj ��Ҫ������ַ���������
 * @return mixed
 */
function new_html_special_chars($string) {
	if(!is_array($string)) return htmlspecialchars($string);
	foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
	return $string;
}
/**
 * ��ȫ���˺���
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
	$string = str_replace('%20','',$string);
	$string = str_replace('%27','',$string);
	$string = str_replace('%2527','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('"','&quot;',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace('"','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('<','&lt;',$string);
	$string = str_replace('>','&gt;',$string);
	$string = str_replace("{",'',$string);
	$string = str_replace('}','',$string);
	$string = str_replace('\\','',$string);
	return $string;
}



/**
 * ����ASCII���0-28�Ŀ����ַ�
 * @return String
 */
function trim_unsafe_control_chars($str) {
	$rule = '/[' . chr ( 1 ) . '-' . chr ( 8 ) . chr ( 11 ) . '-' . chr ( 12 ) . chr ( 14 ) . '-' . chr ( 31 ) . ']*/';
	return str_replace ( chr ( 0 ), '', preg_replace ( $rule, '', $str ) );
}

/**
 * ��ʽ���ı�������
 *
 * @param $string �ı�������
 * @return string
 */
function trim_textarea($string) {
	$string = nl2br ( str_replace ( ' ', '&nbsp;', $string ) );
	return $string;
}

/**
 * ���ı���ʽ���ʺ�js������ַ���
 * @param string $string ��Ҫ������ַ���
 * @param intval $isjs �Ƿ�ִ���ַ�����ʽ����Ĭ��Ϊִ��
 * @return string �������ַ���
 */
function format_js($string, $isjs = 1) {
	$string = addslashes(str_replace(array("\r", "\n"), array('', ''), $string));
	return $isjs ? 'document.write("'.$string.'");' : $string;
}

/**
 * ת�� javascript ������
 *
 * @param $str
 * @return mixed
 */
function trim_script($str) {
	$str = preg_replace ( '/\<([\/]?)script([^\>]*?)\>/si', '&lt;\\1script\\2&gt;', $str );
	$str = preg_replace ( '/\<([\/]?)iframe([^\>]*?)\>/si', '&lt;\\1iframe\\2&gt;', $str );
	$str = preg_replace ( '/\<([\/]?)frame([^\>]*?)\>/si', '&lt;\\1frame\\2&gt;', $str );
	$str = preg_replace ( '/]]\>/si', ']] >', $str );
	return $str;
}
/**
 * ��ȡ��ǰҳ������URL��ַ
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}
/**
 * �ַ���ȡ ֧��UTF8/GBK
 * @param $string
 * @param $length
 * @param $dot
 */
function str_cut($string, $length, $dot = '...') {
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array(' ', '&', '"', "'", '��', '��', '��', '<', '>', '��', '��'), $string);
	$strcut = '';
	if(strtolower(CHARSET) == 'utf-8') {
		$n = $tn = $noc = 0;
		while($n < $strlen) {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) break;
		}
		if($noc > $length) $n -= $tn;
		$strcut = substr($string, 0, $n);
	} else {
		$dotlen = strlen($dot);
		$maxi = $length - $dotlen - 1;
		for($i = 0; $i < $maxi; $i++)
		{
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}
	$strcut = str_replace(array('&', '"', "'", '<', '>'), array('&amp;', '&quot;', '&#039;', '&lt;', '&gt;'), $strcut);
	return $strcut.$dot;
}



/**
 * ��ȡ����ip
 *
 * @return ip��ַ
 */
function ip() {
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$ip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$ip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
}

function get_cost_time() {
	$microtime = microtime(TRUE);
	return $microtime - SYS_START_TIME;
}

/**
 * ����ִ��ʱ��
 *
 * @return	int	��λms
 */
function execute_time() {
	$stime = explode ( ' ', SYS_START_TIME );
	$etime = explode ( ' ', microtime () );
	return number_format ( ($etime [1] + $etime [0] - $stime [1] - $stime [0]), 6 );
}

/**
* ��������ַ���
*
* @param    int        $length  ������� 
* @param    string     $chars   ��ѡ�� ��Ĭ��Ϊ 0123456789
* @return   string     �ַ���
*/
function random($length, $chars = '0123456789') {
	$hash = '';
	$max = strlen($chars) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

/**
* ���ַ���ת��Ϊ����
*
* @param	string	$data	�ַ���
* @return	array	���������ʽ�������dataΪ�գ��򷵻ؿ�����
*/
function string2array($data) {
	if($data == '') return array();
	eval("\$array = $data;");
	return $array;
}

/**
* ������ת��Ϊ�ַ���
*
* @param	array	$data		����
* @param	bool	$isformdata	���Ϊ0����ʹ��new_stripslashes������ѡ������Ĭ��Ϊ1
* @return	string	�����ַ����������dataΪ�գ��򷵻ؿ�
*/
function array2string($data, $isformdata = 1) {
	if($data == '') return '';
	if($isformdata) $data = new_stripslashes($data);
	return addslashes(var_export($data, TRUE));
}

/**
* ת���ֽ���Ϊ������λ
*
*
* @param	string	$filesize	�ֽڴ�С
* @return	string	���ش�С
*/
function sizecount($filesize) {
	if ($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
	} elseif ($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 .' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize.' Bytes';
	}
	return $filesize;
}

/**
* �ַ������ܡ����ܺ���
*
*
* @param	string	$txt		�ַ���
* @param	string	$operation	ENCODEΪ���ܣ�DECODEΪ���ܣ���ѡ������Ĭ��ΪENCODE��
* @param	string	$key		��Կ�����֡���ĸ���»���
* @param	string	$expiry		����ʱ��
* @return	string
*/
function sys_auth($string, $operation = 'ENCODE', $key = '', $expiry = 0) {
	$key_length = 4;
	$key = md5($key != '' ? $key : pc_base::load_config('system', 'auth_key'));
	$fixedkey = hash('md5', $key);
	$egiskeys = md5(substr($fixedkey, 16, 16));
	$runtokey = $key_length ? ($operation == 'ENCODE' ? substr(hash('md5', microtime(true)), -$key_length) : substr($string, 0, $key_length)) : ''; 
	$keys = hash('md5', substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
	$string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length));
	
	$i = 0; $result = '';
	$string_length = strlen($string);
	for ($i = 0; $i < $string_length; $i++){
		$result .= chr(ord($string{$i}) ^ ord($keys{$i % 32}));
	}
	if($operation == 'ENCODE') {
		return $runtokey . str_replace('=', '', base64_encode($result));
	} else {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$egiskeys), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	}
}

/**
* �����ļ�����
*
* @param	string		$language	��ʾ��
* @param	array		$pars	ת�������,��ά���� ,'key1'=>'value1','key2'=>'value2',
* @param	string		$modules ���ģ��֮���ð�Ƕ��Ÿ������磺member,guestbook
* @return	string		�����ַ�
*/
function L($language = 'no_language',$pars = array(), $modules = '') {
	static $LANG = array();
	if(!$LANG) {
		$lang = pc_base::load_config('system','lang');
		require_once PC_PATH.'languages'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.'system.lang.php';
		if(defined('IN_ADMIN')) require_once PC_PATH.'languages'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.'system_menu.lang.php';
		if(file_exists(PC_PATH.'languages'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.ROUTE_M.'.lang.php')) require PC_PATH.'languages'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.ROUTE_M.'.lang.php';
		if(!empty($modules)) {
			$modules = explode(',',$modules);
			foreach($modules AS $m) {
				require PC_PATH.'languages'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.$m.'.lang.php';
			}
		}
	}
	if(!array_key_exists($language,$LANG)) {
		return $LANG['no_language'].'['.$language.']';
	} else {
		$language = $LANG[$language];
		if($pars) {
			foreach($pars AS $_k=>$_v) {
				$language = str_replace('{'.$_k.'}',$_v,$language);
			}
		}
		return $language;
	}
}

/**
 * ģ�����
 * 
 * @param $module
 * @param $template
 * @param $istag
 * @return unknown_type
 */
function template($module = 'content', $template = 'index', $style = 'default') {
	$template_cache = pc_base::load_sys_class('template_cache');
	
	$compiledtplfile = PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_template'.DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.'.php';
	
	if(file_exists(PC_PATH.'templates'.DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.'.html')) {
		if(!file_exists($compiledtplfile) || (@filemtime(PC_PATH.'templates'.DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.'.html') > @filemtime($compiledtplfile))) {	
			$template_cache->template_compile($module, $template, $style);
		}
	} else {
		$compiledtplfile = PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_template'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.'.default.php';
		if(file_exists(PC_PATH.'templates'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.'.html') && filemtime(PC_PATH.'templates'.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.'.html') > filemtime($compiledtplfile)) {
			$template_cache->template_compile($module, $template, 'default');
		} else {
			showmessage('Template does not exist.'.DIRECTORY_SEPARATOR.$style.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.$template.'.html');
		}
	}
	return $compiledtplfile;
}

/**
 * ����Զ������
 * 
 * @param $errno �����
 * @param $errstr ��������
 * @param $errfile �����ļ���ַ
 * @param $errline �����к�
 * @return string ������ʾ
 */

function my_error_handler($errno, $errstr, $errfile, $errline) {
	if($errno==8) return '';
	$errfile = str_replace(PHPCMS_PATH,'',$errfile);
	if(pc_base::load_config('system','errorlog')) {
		error_log(date('m-d H:i:s',SYS_TIME).' | '.$errno.' | '.str_pad($errstr,30).' | '.$errfile.' | '.$errline."\r\n", 3, CACHE_PATH.'error_log.php');
	} else {
		$str = '<div style="font-size:12px;text-align:left; border-bottom:1px solid #9cc9e0; border-right:1px solid #9cc9e0;padding:1px 4px;color:#000000;font-family:Arial, Helvetica,sans-serif;"><span>errorno:' . $errno . ',str:' . $errstr . ',file:<font color="blue">' . $errfile . '</font>,line' . $errline .'<br /><a href="http://faq.phpcms.cn/?type=file&errno='.$errno.'&errstr='.urlencode($errstr).'&errfile='.urlencode($errfile).'&errline='.$errline.'" target="_blank" style="color:red">Need Help?</a></span></div>';
		echo $str;
	}
}

/**
 * ��ʾ��Ϣҳ����ת����ת��ַ����������飬ҳ�����ʾ�����ַ���û�ѡ��Ĭ����ת��ַΪ����ĵ�һ��ֵ��ʱ��Ϊ5�롣
 * showmessage('��¼�ɹ�', array('Ĭ����ת��ַ'=>'http://www.phpcms.cn'));
 * @param string $msg ��ʾ��Ϣ
 * @param mixed(string/array) $url_forward ��ת��ַ
 * @param int $ms ��ת�ȴ�ʱ��
 */
function showmessage($msg, $url_forward = 'goback', $ms = 1250, $dialog = '') {
	if(defined('IN_ADMIN')) {
		include(admin::admin_tpl('showmessage', 'admin'));
	} else {
		include(template('content', 'message'));
	}
	exit;
}

/**
 * ��ѯ�ַ��Ƿ������ĳ�ַ���
 * 
 * @param $haystack �ַ���
 * @param $needle Ҫ���ҵ��ַ�
 * @return bool
 */
function str_exists($haystack, $needle)
{
	return !(strpos($haystack, $needle) === FALSE);
}

/**
 * ȡ���ļ���չ
 * 
 * @param $filename �ļ���
 * @return ��չ��
 */
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

/**
 * ����ģ���ǩ����
 * @param string $name ������
 * @param integer $times ����ʱ��
 */
function tpl_cache($name,$times = 0) {
	$filepath = 'tpl_data';
	$info = getcacheinfo($name, $filepath);
	if (SYS_TIME - $info['filemtime'] >= $times) {
		return false;
	} else {
		return getcache($name,$filepath);
	}
}

/**
 * д�뻺�棬Ĭ��Ϊ�ļ����棬�����ػ������á�
 * @param $name ��������
 * @param $data ��������
 * @param $filepath ����·����ģ�����ƣ� caches/cache_$filepath/
 * @param $type ��������[file,memcache,apc]
 * @param $config ��������
 * @param $timeout ����ʱ��
 */
function setcache($name, $data, $filepath='', $type='file', $config='', $timeout='') {
	pc_base::load_sys_class('cache_factory','',0);
	if($config) {
		$cacheconfig = pc_base::load_config('cache');
		$cache = cache_factory::get_instance($cacheconfig)->get_cache($config);
	} else {
		$cache = cache_factory::get_instance()->get_cache($type);
	}

	return $cache->set($name, $data, $timeout, '', $filepath);
}

/**
 * ��ȡ���棬Ĭ��Ϊ�ļ����棬�����ػ������á�
 * @param string $name ��������
 * @param $filepath ����·����ģ�����ƣ� caches/cache_$filepath/
 * @param string $config ��������
 */
function getcache($name, $filepath='', $type='file', $config='') {
	pc_base::load_sys_class('cache_factory','',0);
	if($config) {
		$cacheconfig = pc_base::load_config('cache');
		$cache = cache_factory::get_instance($cacheconfig)->get_cache($config);
	} else {
		$cache = cache_factory::get_instance()->get_cache($type);
	}
	return $cache->get($name, '', '', $filepath);
}

/**
 * ɾ�����棬Ĭ��Ϊ�ļ����棬�����ػ������á�
 * @param $name ��������
 * @param $filepath ����·����ģ�����ƣ� caches/cache_$filepath/
 * @param $type ��������[file,memcache,apc]
 * @param $config ��������
 */
function delcache($name, $filepath='', $type='file', $config='') {
	pc_base::load_sys_class('cache_factory','',0);
	if($config) {
		$cacheconfig = pc_base::load_config('cache');
		$cache = cache_factory::get_instance($cacheconfig)->get_cache($config);
	} else {
		$cache = cache_factory::get_instance()->get_cache($type);
	}
	return $cache->delete($name, '', '', $filepath);
}

/**
 * ��ȡ���棬Ĭ��Ϊ�ļ����棬�����ػ������á�
 * @param string $name ��������
 * @param $filepath ����·����ģ�����ƣ� caches/cache_$filepath/
 * @param string $config ��������
 */
function getcacheinfo($name, $filepath='', $type='file', $config='') {
	pc_base::load_sys_class('cache_factory');
	if($config) {
		$cacheconfig = pc_base::load_config('cache');
		$cache = cache_factory::get_instance($cacheconfig)->get_cache($config);
	} else {
		$cache = cache_factory::get_instance()->get_cache($type);
	}
	return $cache->cacheinfo($name, '', '', $filepath);
}

function url($url, $isabs = 0) {
	if(strpos($url, '://') !== FALSE || $url[0] == '?') return $url;
	$siteurl = get_url();
	if($isabs || defined('SHOWJS')) {
		$url = strpos($url, WEB_PATH) === 0 ? $siteurl.substr($url, strlen(WEB_PATH)) : $siteurl.$url;
	} else {
		$url = strpos($url, WEB_PATH) === 0 ? $url : WEB_PATH.$url;
	}
	return $url;
}

/**
 * ����sql��䣬�������$in_cloumn ���ɸ�ʽΪ IN('a', 'b', 'c')
 * @param $data ������������ַ���
 * @param $front ���ӷ�
 * @param $in_column �ֶ�����
 * @return string
 */
function to_sqls($data, $front = ' AND ', $in_column = false) {
	if($in_column && is_array($data)) {
		$ids = '\''.implode('\',\'', $data).'\'';
		$sql = "$in_column IN ($ids)";
		return $sql;
	} else {
		if ($front == '') {
			$front = ' AND ';
		}
		if(is_array($data) && count($data) > 0) {
			$sql = '';
			foreach ($data as $key => $val) {
				$sql .= $sql ? " $front `$key` = '$val' " : " `$key` = '$val' ";	
			}
			return $sql;
		} else {
			return $data;
		}
	}
}

/**
 * ��ҳ����
 * 
 * @param $num ��Ϣ����
 * @param $curr_page ��ǰ��ҳ
 * @param $perpage ÿҳ��ʾ��
 * @param $urlrule URL����
 * @param $array ��Ҫ���ݵ����飬�������Ӷ���ķ���
 * @return ��ҳ
 */
function pages($num, $curr_page, $perpage = 20, $urlrule = '', $array = array()) {
	if($urlrule == '') $urlrule = url_par('page={$page}');
	$multipage = '';
	if($num > $perpage) {
		$page = 11;
		$offset = 4;
		$pages = ceil($num / $perpage);
		$from = $curr_page - $offset;
		$to = $curr_page + $offset;
		$more = 0;
		if($page >= $pages) {
			$from = 2;
			$to = $pages-1;
		} else {
			if($from <= 1) {
				$to = $page-1;
				$from = 2;
			}  elseif($to >= $pages) { 
				$from = $pages-($page-2);  
				$to = $pages-1;  
			}
			$more = 1;
		} 
		$multipage .= L('total').'<b>'.$num.'</b>&nbsp;&nbsp;';
		if($curr_page>0) {
			$multipage .= ' <a href="'.pageurl($urlrule, $curr_page-1, $array).'" class="a1">'.L('previous').'</a>';
			if($curr_page==1) {
				$multipage .= ' <span>1</span>';
			} elseif($curr_page>6 && $more) {
				$multipage .= ' <a href="'.pageurl($urlrule, 1, $array).'">1</a>..';
			} else {
				$multipage .= ' <a href="'.pageurl($urlrule, 1, $array).'">1</a>';
			}
		}
		for($i = $from; $i <= $to; $i++) { 
			if($i != $curr_page) { 
				$multipage .= ' <a href="'.pageurl($urlrule, $i, $array).'">'.$i.'</a>'; 
			} else { 
				$multipage .= ' <span>'.$i.'</span>'; 
			} 
		} 
		if($curr_page<$pages) {
			if($curr_page<$pages-5 && $more) {
				$multipage .= ' ..<a href="'.pageurl($urlrule, $pages, $array).'">'.$pages.'</a> <a href="'.pageurl($urlrule, $curr_page+1, $array).'" class="a1">'.L('next').'</a>';
			} else {
				$multipage .= ' <a href="'.pageurl($urlrule, $pages, $array).'">'.$pages.'</a> <a href="'.pageurl($urlrule, $curr_page+1, $array).'" class="a1">'.L('next').'</a>';
			}
		} elseif($curr_page==$pages) {
			$multipage .= ' <span>'.$pages.'</span> <a href="'.pageurl($urlrule, $curr_page, $array).'" class="a1">'.L('next').'</a>';
		} else {
			$multipage .= ' <a href="'.pageurl($urlrule, $pages, $array).'">'.$pages.'</a> <a href="'.pageurl($urlrule, $curr_page+1, $array).'" class="a1">'.L('next').'</a>';		}

	}
	return $multipage;
}

/**
 * ���ط�ҳ·��
 * 
 * @param $urlrule ��ҳ����
 * @param $page ��ǰҳ
 * @param $array ��Ҫ���ݵ����飬�������Ӷ���ķ���
 * @return ������URL·��
 */
function pageurl($urlrule, $page, $array = array()) {
	if(strpos($urlrule, '#')) {
		$urlrules = explode('#', $urlrule);
		$urlrule = $page < 2 ? $urlrules[0] : $urlrules[1];
	}
	$findme = array('{$page}');
	$replaceme = array($page);
	if (is_array($array)) foreach ($array as $k=>$v) {
		$findme[] = '{$'.$k.'}';
		$replaceme[] = $v;
	}
	$url = str_replace($findme, $replaceme, $urlrule);
	return $url;
}

/**
 * URL·��������pages �����ĸ�������
 *
 * @param $par ������Ҫ�����ı��� Ĭ��Ϊ��page={$page}
 * @param $url URL��ַ
 * @return URL
 */
function url_par($par, $url = '') {
	if($url == '') $url = get_url();
	$pos = strpos($url, '?');
	if($pos === false) {
		$url .= '?'.$par;
	} else {
		$querystring = substr(strstr($url, '?'), 1);
		parse_str($querystring, $pars);
		$query_array = array();
		foreach($pars as $k=>$v) {
			$query_array[$k] = $v;
		}
		$querystring = http_build_query($query_array).'&'.$par;
		$url = substr($url, 0, $pos).'?'.$querystring;
	}
	return $url;
}

/**
 * �ж�email��ʽ�Ƿ���ȷ
 * @param $email
 */
function is_email($email) {
	return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

/**
 * iconv �༭ת��
 */
if (!function_exists('iconv')) {
	function iconv($in_charset, $out_charset, $str) {
		$in_charset = strtoupper($in_charset);
		$out_charset = strtoupper($out_charset);
		if (function_exists('mb_convert_encoding')) {
			return mb_convert_encoding($str, $out_charset, $in_charset);
		} else {

			pc_base::load_sys_func('iconv');
			$in_charset = strtoupper($in_charset);
			$out_charset = strtoupper($out_charset);
			if($in_charset == 'UTF-8' && ($out_charset == 'GBK' || $out_charset == 'GB2312')) {
				return utf8_to_gbk($str);
			}
			if(($in_charset == 'GBK' || $in_charset == 'GB2312') && $out_charset == 'UTF-8') {
				return gbk_to_utf8($str);
			}
			return $str;
		}
	}
}

/**
 * ������չʾ����
 * @param intval $siteid ����վ��
 * @param intval $id ���ID
 * @return ���ع�����
 */
function show_ad($siteid, $id) {
	$siteid = intval($siteid);
	$id = intval($id);
	if(!$id || !$siteid) return false;
	$p = pc_base::load_model('poster_model');
	$r = $p->get_one(array('spaceid'=>$id, 'siteid'=>$siteid), 'setting', '`id` ASC');
	if ($r['setting']) {
		$c = string2array($r['setting']);
	} else {
		$r['code'] = '';
	}
	return $c['code'];
}

/**
 * ��ȡ��ǰ��վ��ID
 */
function get_siteid() {
	static $siteid;
	if (!empty($siteid)) return $siteid;
	if (defined('IN_ADMIN')) {
		if ($d = param::get_cookie('siteid')) {
			$siteid = $d;
		} else {
			return '';
		}
	} else {
		$data = getcache('sitelist', 'commons');
		$site_url = SITE_PROTOCOL.SITE_URL;
		foreach ($data as $v) {
			if ($v['url'].'/' == $site_url) $siteid = $v['siteid'];
		}
	}
	return $siteid;
}

/**
 * ���ù����˵�
 * @param $linkageid
 * @param $id
 * @param $defaultvalue
 */
function menu_linkage($linkageid = 0, $id = 'linkid', $defaultvalue = 0) {
	$linkageid = intval($linkageid);
	$datas = array();
	$datas = getcache($linkageid,'linkage');
	$infos = $datas['data'];
	$title = $defaultvalue ? $infos[$defaultvalue]['name'] : $datas['title'];	
	$colObj = random(3).date('is');
	$string = '';
	if(!defined('LINKAGE_INIT')) {
		define('LINKAGE_INIT', 1);
		$string .= '<script type="text/javascript" src="'.JS_PATH.'linkage/js/mln.colselect.js"></script>';
		if(defined('IN_ADMIN')) {
			$string .= '<link href="'.JS_PATH.'linkage/style/admin.css" rel="stylesheet" type="text/css">';
		} else {
			$string .= '<link href="'.JS_PATH.'linkage/style/css.css" rel="stylesheet" type="text/css">';
		}
	}
	$string .= '<input type="hidden" name="info['.$id.']" value="1"><div id="'.$id.'"></div>';
	$string .= '<script type="text/javascript">';
	$string .= 'var colObj'.$colObj.' = {"Items":[';
	
	foreach($infos AS $k=>$v) {
		$s .= '{"name":"'.$v['name'].'","topid":"'.$v['parentid'].'","colid":"'.$k.'","value":"'.$k.'","fun":function(){}},';
	}

	$string .= substr($s, 0, -1);
	$string .= ']};';
	$string .= '$("#'.$id.'").mlnColsel(colObj'.$colObj.',{';
	$string .= 'title:"'.$title.'",';
	$string .= 'value:"'.$defaultvalue.'",';
	$string .= 'width:100';
	$string .= '});';
	$string .= '</script>';
	return $string;
}

/**
 * �ж��ַ����Ƿ�Ϊutf8���룬Ӣ�ĺͰ���ַ�����ture
 * @param $string
 * @return bool
 */
function is_utf8($string) {
	return preg_match('%^(?:
					[\x09\x0A\x0D\x20-\x7E] # ASCII
					| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
					| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
					| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
					| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
					| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
					| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
					| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
					)*$%xs', $string);
}

/**
 * ��ȡUCenter���ݿ�����
 */
function get_uc_database() {
	$config = pc_base::load_config('system');
	$config['uc_dbtablepre'] = str_replace('`'.$config['uc_dbname'].'`.','',$config['uc_dbtablepre']);
	return  array (
		'hostname' => $config['uc_dbhost'],
		'database' => $config['uc_dbname'],
		'username' => $config['uc_dbuser'],
		'password' => $config['uc_dbpw'],
		'tablepre' =>  $config['uc_dbtablepre'],
		'charset' => $config['uc_dbcharset'],
		'type' => 'mysql',
		'debug' => true,
		'pconnect' => 0,
		'autoconnect' => 0
		);
}
?>