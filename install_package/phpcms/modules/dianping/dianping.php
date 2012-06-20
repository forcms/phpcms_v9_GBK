<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_app_func('global','dianping');//�����������
class dianping extends admin {
	function __construct() {
		parent::__construct();
		$this->dianping_type = pc_base::load_model('dianping_type_model');
		$this->dianping_data = pc_base::load_model('dianping_data_model');
		$this->dianping = pc_base::load_model('dianping_model');
		$this->module = pc_base::load_model('module_model');
		pc_base::load_sys_class('form');
  	}
 	
	public function init() {
		//ģ������
		$module_arr = array();
		$modules = getcache('modules','commons');
 		foreach($modules as $module=>$m) $module_arr[$m['module']] = $m['name'];
 		
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->dianping_type->listinfo($where,$order = 'id DESC',$page, $pages = '4');
		$pages = $this->dianping_type->pages; 
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=dianping&c=dianping&a=add_type\', title:\'��ӵ�������\', width:\'500\', height:\'350\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '��ӵ�������');
 		include $this->admin_tpl('dianping_type_list');
 	}
 	
	/**
 	 * 
 	 * ������Ϣ�б� ...
 	 */
 	public function dianping_data() {
 		$where = '';
 		$search = $_GET['search'];
	 	if(!empty($search)){
	 		extract($_GET['search']); 
	 		if($username){
				$where .= $where ?  " AND username='$username'" : " username='$username'";
			}
			if ($module){
				$where .= $where ?  " AND module='$module'" : " module='$module'";
			}
			if($start_time && $end_time) {
				$start = strtotime($start_time);
				$end = strtotime($end_time);
				$where .= "AND `addtime` >= '$start' AND `addtime` <= '$end' ";
			}
  		}
 		
		//���������
		$typeid = intval($_GET['typeid']);
		if($typeid){
			$where .= $where ?  " AND dianping_typeid='$typeid'" : " dianping_typeid='$typeid'";
		}
 		$default = $module ? $module : '����ģ��';//δ�趨����ʾ ����ģ�� ���趨����ʾָ����
 		
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->dianping_data->listinfo($where,$order = 'id DESC',$page, $pages = '8');
		$pages = $this->dianping_data->pages; 
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=dianping&c=dianping&a=add_type\', title:\'��ӵ�������\', width:\'500\', height:\'250\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '��ӵ�������');
 		pc_base::load_sys_class('format','', 0);
 		
 		//ģ������
		$module_arr = array();
		$modules = getcache('modules','commons');
 		foreach($modules as $module=>$m) $module_arr[$m['module']] = $m['name'];
		//������������
		$dianping_type_array = getcache('dianping_type','dianping');
		
 		include $this->admin_tpl('dianping_data_list');
 	}
 	
 	/**
 	 * 
 	 * ɾ������ ...
 	 */
 	public function ajax_checks(){
 		//��ȡ����
 		$id = intval($_GET['id']);
 		if($id<0){
 			return false;
 		}
 		$type = $_GET['type'];
 		$dianpingid = $_GET['dianpingid'];
 		$result = $this->delete_dianpingdata_update($id);
  		if($result == 1){
 			//ͬ������v9_dianping ����������
 			$where = array('id'=>$id);
 			$dianping_data = $this->dianping_data->get_one($where);
 			$queryid = $this->dianping_data->delete($where);
 			
 			//�����Ҫ�ۻ�Ա����
 			$dianping_type_array = getcache('dianping_type','dianping');
 			$setting = string2array($dianping_type_array[$dianping_data['dianping_typeid']]['setting']);
 			$member_user_db = pc_base::load_app_class('member_interface','member');
 	 		$member_user_db->add_point($dianping_data['userid'],'-'.$setting['del_point']);
 			exit('1');
 		}else {
 			exit('0');
 		}
  	}
  	
  	/**
  	 * 
  	 * ɾ������ʱ����v9_dianping �� ���� ...
  	 */
  	public function delete_dianpingdata_update($id){
  		$where = array('id'=>$id);
  		$dianping_data = $this->dianping_data->get_one($where);
  		
  		//ͬ������v9_dianping ����������
 		$update = array();
 		$result_data_info = string2array($dianping_data['data']);
 		$i = 1;
   		foreach ($result_data_info as $key=>$val){
	  		$update['data'.$i] = $val; 
	 		$i++;
 		}
  		//ȡ��v9_dianping ���Ӧ����
 		$result = $this->dianping->get_one(array('dianpingid'=>$dianping_data['dianpingid']));
  		$result_update['dianping_nums'] = $result['dianping_nums'] -1;
  		for($k=1;$k<7;$k++){
 			$result_update['data'.$k] = $result['data'.$k] - $update['data'.$k];
 		}
    	$returnid = $this->dianping->update($result_update,array('id'=>$result['id']));
  		if($returnid){
  			return '1';
  		}
  	}
  	
 	/**
 	 * 
 	 * ������Ϣ�б� ...
 	 */
 	public function dianping_lists() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->dianping_type->listinfo($where,$order = 'id DESC',$page, $pages = '4');
		$pages = $this->dianping_type->pages; 
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=dianping&c=dianping&a=add_type\', title:\'��ӵ�������\', width:\'500\', height:\'250\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', '��ӵ�������');
 		include $this->admin_tpl('dianping_type_list');
 	}
 	
	/**
	 * ɾ������������Ϣ  
	 * @param	intval	$id	��¼ID���ݹ�ɾ��
	 */
	public function delete_dianping_type() {
  		if(!isset($_POST['dianpingid']) || empty($_POST['dianpingid'])) {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		} else {
			if(is_array($_POST['dianpingid'])){
				foreach($_POST['dianpingid'] as $dianpingid_arr) {
					$this->dianping_type->delete(array('id'=>$dianpingid_arr));
				}
				showmessage(L('operation_success'),'?m=dianping&c=dianping');
			}
		} 
	}
	
	/**
	 * ��ӵ�������
	 */
	public function add_type() {
		if(isset($_POST['dosubmit'])){ 
			if(empty($_POST['type']['name'])) {
				showmessage('�������Ʋ���Ϊ��',HTTP_REFERER);
			}
			if(empty($_POST['type']['data'])) {
				showmessage('�������ݲ���Ϊ��',HTTP_REFERER);
			}
			if($_POST['setting']){
				$_POST['type']['setting'] = array2string($_POST['setting']);
			}
			$typeid = $this->dianping_type->insert($_POST['type'],true);
			if(!$typeid) return FALSE;
			//��ѯ���ñ������»���
			$type_cache_array = array();
			$type_array = $this->dianping_type->select();
			if(is_array($type_array)){
				foreach ($type_array as $array){
					$type_cache_array[$array['id']]['type_name'] = $array['name'];
					$type_cache_array[$array['id']]['data'] = $array['data'];
					$type_cache_array[$array['id']]['setting'] = $array['setting'];
				}
			}
			setcache('dianping_type', $type_cache_array, 'dianping');
			
 			showmessage(L('operation_success'),HTTP_REFERER,'', 'add');
 		}else{
 			$show_validator = $show_scroll = $show_header = true; 
			include $this->admin_tpl('dianping_add_type');
		}
 	}
 	
	/**
	 * �������ͻ���
	 */
	public function do_js() {
			//��ѯ���ñ������»���
			$type_cache_array = array();
			$type_array = $this->dianping_type->select();
			if(is_array($type_array)){
				foreach ($type_array as $array){
					$type_cache_array[$array['id']]['type_name'] = $array['name'];
					$type_cache_array[$array['id']]['data'] = $array['data'];
					$type_cache_array[$array['id']]['setting'] = $array['setting'];
 				}
			}
			setcache('dianping_type', $type_cache_array, 'dianping');
 			showmessage(L('operation_success'),HTTP_REFERER); 
 	}
	
	/**
	 * �޸ĵ�������
	 */
	public function edit_type() {
		if(isset($_POST['dosubmit'])){ 
			$typeid = intval($_GET['typeid']); 
			if($typeid < 1) return false;
 			if((!$_POST['type']['name']) || empty($_POST['type']['name'])) return false;
 			if((!$_POST['type']['data']) || empty($_POST['type']['data'])) return false;
  			if($_POST['setting']){
 				$_POST['type']['setting'] = array2string($_POST['setting']);
 			}
			$this->dianping_type->update($_POST['type'],array('id'=>$typeid));
			//���»���
			$type_cache_array = array();
			$type_array = $this->dianping_type->select();
			if(is_array($type_array)){
				foreach ($type_array as $array){
					$type_cache_array[$array['id']]['type_name'] = $array['name'];
					$type_cache_array[$array['id']]['data'] = $array['data'];
					$type_cache_array[$array['id']]['setting'] = $array['setting'];
 				}
			}
			setcache('dianping_type', $type_cache_array, 'dianping');
 			showmessage(L('operation_success'),'?m=dianping&c=dianping&a=init','', 'edit');
 		}else{
 			$show_validator = $show_scroll = $show_header = true;
			//�����������
			$info = $this->dianping_type->get_one(array('id'=>$_GET['dianpingid']));
			if(!$info) showmessage('�õ������Ͳ����ڣ�');
			extract($info);
			$setting = string2array($info['setting']);
			include $this->admin_tpl('dianping_type_edit');
		}
 	}
 	
	
	/**
	 * ��ȡ����
	 * 
	 */ 
 	public function public_call() {
  		$_GET['typeid'] = intval($_GET['typeid']);
		if(!$_GET['typeid']) showmessage('����ȷѡ���ȡ���룡', HTTP_REFERER, '', 'call');
		$r = $this->dianping_type->get_one(array('id'=>$_GET['typeid']));
		include $this->admin_tpl('dianping_call');
	}
	 
}
?>