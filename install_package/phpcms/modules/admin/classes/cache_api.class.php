<?php
/**
 * 
 * ���»�����
 *
 */

defined('IN_PHPCMS') or exit('No permission resources.');
class cache_api {
	
	private $db;
	
	public function __construct() {
		$this->db = '';
		$this->siteid = get_siteid();
	}
	
	/**
	 * ���»���
	 * @param string $model ������
	 * @param string $param ����
	 */
	public function cache($model = '', $param = '') {
		if (file_exists(PC_PATH.'model'.DIRECTORY_SEPARATOR.$model.'_model.class.php')) {
			$this->db = pc_base::load_model($model.'_model');
			if ($param) {
				$this->$model($param);
			} else {
				$this->$model();
			}
		} else {
			$this->$model();
		}
	}
	
	
	/**
	 * ����վ�㻺�淽��
	 */
	public function cache_site() {
		$site = pc_base::load_app_class('sites', 'admin');
		$site->set_cache();
	}
	
	/**
	 * ������Ŀ���淽��
	 */
	public function category() {
		$categorys = array();
		$models = getcache('model','commons');
		foreach ($models as $modelid=>$model) {
			$datas = $this->db->select(array('modelid'=>$modelid),'catid,type,items',10000);
			$array = array();
			foreach ($datas as $r) {
				if($r['type']==0) $array[$r['catid']] = $r['items'];
			}
			setcache('category_items_'.$modelid, $array,'commons');
		}
		$array = array();
		$categorys = $this->db->select('`module`=\'content\'','catid,siteid',20000,'listorder ASC');
		foreach ($categorys as $r) {
			$array[$r['catid']] = $r['siteid'];
		}
		setcache('category_content',$array,'commons');
		$categorys = $this->categorys = array();
		$this->categorys = $this->db->select(array('siteid'=>$this->siteid, 'module'=>'content'),'*',10000,'listorder ASC');
		foreach($this->categorys as $r) {
			unset($r['module']);
			$setting = string2array($r['setting']);
			$r['create_to_html_root'] = $setting['create_to_html_root'];
			$r['ishtml'] = $setting['ishtml'];
			$r['content_ishtml'] = $setting['content_ishtml'];
			$r['category_ruleid'] = $setting['category_ruleid'];
			$r['show_ruleid'] = $setting['show_ruleid'];
			$r['workflowid'] = $setting['workflowid'];
			$r['isdomain'] = '0';
			if(!preg_match('/^(http|https):\/\//', $r['url'])) {
				$r['url'] = siteurl($r['siteid']).$r['url'];
			} elseif ($r['ishtml']) {
				$r['isdomain'] = '1';
			}
			$categorys[$r['catid']] = $r;
		}
		setcache('category_content_'.$this->siteid,$categorys,'commons');
		return true;
	}
	
	/**
	 * �������ط��������淽��
	 */
	public function downservers () {
		$infos = $this->db->select();
		foreach ($infos as $info){
			$servers[$info['id']] = $info;
		}
		setcache('downservers', $servers,'commons');
		return $infos;
	}
	
	/**
	 * �������дʻ��淽��
	 */
	public function badword() {
		$infos = $this->db->select('','badid,badword','','badid ASC');
		setcache('badword', $infos, 'commons');
		return true;
	}
	
	/**
	 * ����ip��ֹ���淽��
	 */
	public function ipbanned() {
		$infos = $this->db->select('', '`ip`,`expires`', '', 'ipbannedid desc');
		setcache('ipbanned', $infos, 'commons');
		return true;
	}
	
	/**
	 * ���¹������ӻ��淽��
	 */
	public function keylink() {
		$infos = $this->db->select('','word,url','','keylinkid ASC');
		$datas = $rs = array();
		foreach($infos as $r) {
			$rs[0] = $r['word'];
			$rs[1] = $r['url'];
			$datas[] = $rs;
		}
		setcache('keylink', $datas, 'commons');
		return true;
	}
	
	/**
	 * ���������˵����淽��
	 */
	public function linkage() {
		$infos = $this->db->select(array('keyid'=>0));
		foreach ($infos as $r) {
			$linkageid = intval($r['linkageid']);
			$r = $this->db->get_one(array('linkageid'=>$linkageid),'name,siteid,style');
			$info['title'] = $r['name'];
			$info['style'] = $r['style'];
			$info['siteid'] = $r['siteid'];
			$info['data'] = $this->submenulist($linkageid);
			setcache($linkageid, $info,'linkage');
		}
		return true;
	}
	
	/**
	 * �Ӳ˵��б�
	 * @param intval $keyid �˵�id
	 */
	public function submenulist($keyid=0) {
		$keyid = intval($keyid);
		$datas = array();
		$where = ($keyid > 0) ? array('keyid'=>$keyid) : '';
		$result = $this->db->select($where,'*','','listorder ,linkageid');
		foreach($result as $r) {
			$datas[$r['linkageid']] = $r;
		}
		return $datas;
	}
	
	/**
	 * �����Ƽ�λ���淽��
	 */
	public function position () {
		$infos = $this->db->select('','*',1000,'listorder DESC');
		foreach ($infos as $info){
			$positions[$info['posid']] = $info;
		}
		setcache('position', $positions,'commons');
		return $infos;
	}
	
	/**
	 * ����ͶƱ����
	 */
	public function vote_setting() {
		$m_db = pc_base::load_model('module_model');
		$data = $m_db->select(array('module'=>'vote'));
		$setting = string2array($data[0]['setting']);
		setcache('vote', $setting, 'commons');
	}
	
	/**
	 * ����������������
	 */
	public function link_setting() {
		$m_db = pc_base::load_model('module_model');
		$data = $m_db->select(array('module'=>'link'));
		$setting = string2array($data[0]['setting']);
		setcache('link', $setting, 'commons');
	}
	
	/**
	 * ���¹���Ա��ɫ���淽��
	 */
	public function admin_role() {
		$infos = $this->db->select(array('disabled'=>'0'), $data = '`roleid`,`rolename`', '', 'roleid ASC');
		foreach ($infos as $info){
			$role[$info['roleid']] = $info['rolename'];
		}
		$this->cache_siteid($role);
		setcache('role', $role,'commons');
		return $infos;
	}
	
	/**
	 * ���¹���Ա��ɫ���淽��
	 */
	public function cache_siteid($role) {
		$priv_db = pc_base::load_model('admin_role_priv_model');
		$sitelist = array();
		foreach($role as $n=>$r) {
			$sitelists = $priv_db->select(array('roleid'=>$n),'siteid', '', 'siteid');
			foreach($sitelists as $site) {
				foreach($site as $v){
					$sitelist[$n][] = intval($v);
				}
			}
		}
		$sitelist = @array_map("array_unique", $sitelist);
		setcache('role_siteid', $sitelist,'commons');
		return $sitelist;
	}
	
	/**
	 * ����url���򻺴淽��
	 */
	public function urlrule() {
		$datas = $this->db->select('','*','','','','urlruleid');
		$basic_data = array();
		foreach($datas as $roleid=>$r) {
			$basic_data[$roleid] = $r['urlrule'];;
		}
		setcache('urlrules_detail',$datas,'commons');
		setcache('urlrules',$basic_data,'commons');
	}
	
	/**
	 * ����ģ�黺�淽��
	 */
	public function module() {
		$modules = array();
		$modules = $this->db->select(array('disabled'=>0), '*', '', '', '', 'module');
		setcache('modules', $modules, 'commons');
		return true;
	}
	
	/**
	 * ����ģ�ͻ��淽��
	 */
	public function sitemodel() {
		define('MODEL_PATH', PC_PATH.'modules'.DIRECTORY_SEPARATOR.'content'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR);
		define('CACHE_MODEL_PATH', PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
		require MODEL_PATH.'fields.inc.php';
		//��������ģ���ࣺ�����ɡ���⡢���¡����
		$classtypes = array('form','input','update','output');
		foreach($classtypes as $classtype) {
			$cache_data = file_get_contents(MODEL_PATH.'content_'.$classtype.'.class.php');
			$cache_data = str_replace('}?>','',$cache_data);
			foreach($fields as $field=>$fieldvalue) {
				if(file_exists(MODEL_PATH.$field.DIRECTORY_SEPARATOR.$classtype.'.inc.php')) {
					$cache_data .= file_get_contents(MODEL_PATH.$field.DIRECTORY_SEPARATOR.$classtype.'.inc.php');
				}
			}
			$cache_data .= "\r\n } \r\n?>";
			file_put_contents(CACHE_MODEL_PATH.'content_'.$classtype.'.class.php',$cache_data);
			chmod(CACHE_MODEL_PATH.'content_'.$classtype.'.class.php',0777);
		}
		//����ģ�����ݻ���
		$model_array = array();
		$datas = $this->db->select(array('type'=>0));
		foreach ($datas as $r) {
			$model_array[$r['modelid']] = $r;
			$this->sitemodel_field($r['modelid']);
		}
		setcache('model', $model_array, 'commons');
		return true;
	}
	
	/**
	 * ����ģ���ֶλ��淽��
	 */
	public function sitemodel_field($modelid) {
		$field_array = array();
		$db = pc_base::load_model('sitemodel_field_model');
		$fields = $db->select(array('modelid'=>$modelid,'disabled'=>0),'*',100,'listorder ASC');
		foreach($fields as $_value) {
			$setting = string2array($_value['setting']);
			$_value = array_merge($_value,$setting);
			$field_array[$_value['field']] = $_value;
		}
		setcache('model_field_'.$modelid,$field_array,'model');
		return true;
	}
	
	/**
	 * ������𻺴淽��
	 */
	public function type($param = '') {
		$datas = array();
		$result_datas = $this->db->select(array('siteid'=>get_siteid(),'module'=>$param),'*',1000,'listorder ASC,typeid ASC');
		foreach($result_datas as $_key=>$_value) {
			$datas[$_value['typeid']] = $_value;
		}
		if ($param=='search') {
			$this->search_type();
		} else {
			setcache('type_'.$param, $datas, 'commons');
		}
		return true;
	}
	
	/**
	 * ���¹��������淽��
	 */
	public function workflow() {
		$datas = array();
		$workflow_datas = $this->db->select(array('siteid'=>get_siteid()),'*',1000);
		foreach($workflow_datas as $_k=>$_v) {
			$datas[$_v['workflowid']] = $_v;
		}
		setcache('workflow_'.get_siteid(),$datas,'commons');
		return true;
	}
	
	/**
	 * ��������Դ���淽��
	 */
	public function dbsource() {
		$db = pc_base::load_model('dbsource_model');
		$list = $db->select();
		$data = array();
		if ($list) {
			foreach ($list as $val) {
				$data[$val['name']] = array('hostname'=>$val['host'].':'.$val['port'], 'database' =>$val['dbname'] , 'db_tablepre'=>$val['dbtablepre'], 'username' =>$val['username'],'password' => $val['password'],'charset'=>$val['charset'],'debug'=>0,'pconnect'=>0,'autoconnect'=>0);
			}
		} else {
			return false;
		}
		return setcache('dbsource', $data, 'commons');
	}
	
	/**
	 * ���»�Ա�黺�淽��
	 */
	public function member_group() {
		$grouplist = $this->db->listinfo('', '', 1, 100, 'groupid');
		setcache('grouplist', $grouplist,'member');
		return true;
	}
	
	/**
	 * ���»�Ա���û��淽��
	 */
	public function member_setting() {
		$this->db = pc_base::load_model('module_model');
		$member_setting = $this->db->get_one(array('module'=>'member'), 'setting');
		$member_setting = string2array($member_setting['setting']);
		setcache('member_setting', $member_setting, 'member');
		return true;
	}
	
	/**
	 * ���»�Աģ�ͻ��淽��
	 */
	public function membermodel() {
		define('MEMBER_MODEL_PATH',PC_PATH.'modules'.DIRECTORY_SEPARATOR.'member'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR);
		//ģ�ͻ���·��
		define('MEMBER_CACHE_MODEL_PATH',PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
		
		$sitemodel_db = pc_base::load_model('sitemodel_model');
		$data = $sitemodel_db->select(array('type'=>2), "*", 1000, 'sort', '', 'modelid');
		setcache('member_model', $data, 'commons');
		
		require MEMBER_MODEL_PATH.'fields.inc.php';
		//��������ģ���ࣺ�����ɡ���⡢���¡����
		$classtypes = array('form','input','update','output');
		foreach($classtypes as $classtype) {
			$cache_data = file_get_contents(MEMBER_MODEL_PATH.'member_'.$classtype.'.class.php');
			$cache_data = str_replace('}?>','',$cache_data);
			foreach($fields as $field=>$fieldvalue) {
				if(file_exists(MEMBER_MODEL_PATH.$field.DIRECTORY_SEPARATOR.$classtype.'.inc.php')) {
					$cache_data .= file_get_contents(MEMBER_MODEL_PATH.$field.DIRECTORY_SEPARATOR.$classtype.'.inc.php');
				}
			}
			$cache_data .= "\r\n } \r\n?>";
			file_put_contents(MEMBER_CACHE_MODEL_PATH.'member_'.$classtype.'.class.php',$cache_data);
			chmod(MEMBER_CACHE_MODEL_PATH.'member_'.$classtype.'.class.php',0777);
		}
		
		return true;
	}
	
	/**
	 * ���»�Աģ���ֶλ��淽��
	 */
	public function member_model_field() {
		$member_model = getcache('member_model', 'commons');
		$this->db = pc_base::load_model('sitemodel_field_model');
		foreach ($member_model as $modelid => $m) {
			$field_array = array();
			$fields = $this->db->select(array('modelid'=>$modelid,'disabled'=>0),'*',100,'listorder ASC');
			foreach($fields as $_value) {
				$setting = string2array($_value['setting']);
				$_value = array_merge($_value,$setting);
				$field_array[$_value['field']] = $_value;
			}
			setcache('model_field_'.$modelid,$field_array,'model');
		}
		return true;
	}
	
	/**
	 * �����������û��淽��
	 */
	public function search_setting() {	
		$this->db = pc_base::load_model('module_model');
		$setting = $this->db->get_one(array('module'=>'search'), 'setting');
		$setting = string2array($setting['setting']);
		setcache('search', $setting, 'search');
		return true;
	}
	
	/**
	 * �����������ͻ��淽��
	 */
	public function search_type() {
		$sitelist = getcache('sitelist','commons');
		foreach ($sitelist as $siteid=>$_v) {
			$datas = $search_model = array();
			$result_datas = $result_datas2 = $this->db->select(array('siteid'=>$siteid,'module'=>'search'),'*',1000,'listorder ASC');
			foreach($result_datas as $_key=>$_value) {
				if(!$_value['modelid']) continue;
				$datas[$_value['modelid']] = $_value['typeid'];
				$search_model[$_value['modelid']]['typeid'] = $_value['typeid'];
				$search_model[$_value['modelid']]['name'] = $_value['name'];
				$search_model[$_value['modelid']]['sort'] = $_value['listorder'];
			}
			setcache('type_model_'.$siteid,$datas,'search');
			$datas = array();	
			foreach($result_datas2 as $_key=>$_value) {
				if($_value['modelid']) continue;
				$datas[$_value['typedir']] = $_value['typeid'];
				$search_model[$_value['typedir']]['typeid'] = $_value['typeid'];
				$search_model[$_value['typedir']]['name'] = $_value['name'];
			}
			setcache('type_module_'.$siteid,$datas,'search');
			//����headerͷ��ʹ�����ͻ���
			setcache('search_model_'.$siteid,$search_model,'search');
		}
		return true;
	}
	
	/**
	 * ����ר�⻺�淽��
	 */
	public function special() {
		$specials = array();
		$result = $this->db->select(array('disabled'=>0), '`id`, `siteid`, `title`, `url`, `thumb`, `banner`, `ishtml`', '', '`listorder` DESC, `id` DESC');
		foreach($result as $r) {
			$specials[$r['id']] = $r;
		}
		setcache('special', $specials, 'commons');
		return true;
	}
	
	/**
	 * ������վ���÷���
	 */
	public function setting() {
		$this->db = pc_base::load_model('module_model');
		$result = $this->db->get_one(array('module'=>'admin'));
		$setting = string2array($result['setting']);
		setcache('common', $setting,'commons');
		return true;
	}
	
	/**
	 * ��������Դģ�ͻ��淽��
	 */
	public function database() {
		$module = $M = array();
		$M = getcache('modules', 'commons');
		if (is_array($M)) {
			foreach ($M as $key => $m) {
				if (file_exists(PC_PATH.'modules'.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.$key.'_tag.class.php') && !in_array($key, array('message', 'block'))) {
					$module[$key] = $m['name'];
				}
			}
		}
		$filepath = CACHE_PATH.'configs/';
		$module = "<?php\nreturn ".var_export($module, true).";\n?>";
		return $file_size = pc_base::load_config('system','lock_ex') ? file_put_contents($filepath.'modules.php', $module, LOCK_EX) : file_put_contents($filepath.'modules.php', $module);
	}
	
	/**
	 * �������ݿ��¼���»���
	 */
	public function cache2database() {
		$cache = pc_base::load_model('cache_model');
		if (!isset($_GET['pages']) && empty($_GET['pages'])) {
			$r = $cache->get_one(array(), 'COUNT(*) AS num');
			if ($r['num']) {
				$total = $r['num'];
				$pages = ceil($total/20);
			} else {
				$pages = 1;
			}
		} else {
			$pages = intval($_GET['pages']);
		}
		$currpage = max(intval($_GET['currpage']), 1);
		$offset = ($currpage-1)*20;
		$result = $cache->select(array(), '*', $offset.', 20', 'filename ASC');
		if (is_array($result) && !empty($result)) {
			foreach ($result as $re) {
				if (!file_exists(CACHE_PATH.$re['path'].$re['filename'])) {
					$filesize = pc_base::load_config('system','lock_ex') ? file_put_contents(CACHE_PATH.$re['path'].$re['filename'], $re['data'], LOCK_EX) : file_put_contents(CACHE_PATH.$re['path'].$re['filename'], $re['data']);
				} else {
					continue;
				}
			}
		}
		$currpage++;
		if ($currpage>$pages) {
			return true;
		} else {
			echo '<script type="text/javascript">window.parent.addtext("<li>'.L('part_cache_success').($currpage-1).'/'.$pages.'..........</li>");</script>';
			showmessage(L('part_cache_success'), '?m=admin&c=cache_all&a=init&page='.$_GET['page'].'&currpage='.$currpage.'&pages='.$pages.'&dosubmit=1',0);
		}
	}
	
	/**
	 * ����ɾ�������ļ�����
	 */
	public function del_file() {
		$path = PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_template'.DIRECTORY_SEPARATOR;
		$files = glob($path.'*');
		pc_base::load_sys_func('dir');
		if (is_array($files)) {
			foreach ($files as $f) {
				$dir = basename($f);
				if (!in_array($dir, array('block', 'dbsource'))) {
					dir_delete($path.$dir);
				}
			}
		}
		$path = PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR.'caches_tpl_data'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR;
		$files = glob($path.'*');
		if (is_array($files)) {
			foreach ($files as $f) {
				$dir = basename($f);
				@unlink($path.$dir);
			}
		}
		return true;
	}

	/**
	 * ������Դ���淽��
	 */
	public function copyfrom() {
		$infos = $this->db->select('','*','','listorder DESC','','id');
		setcache('copyfrom', $infos, 'admin');
		return true;
	}
}