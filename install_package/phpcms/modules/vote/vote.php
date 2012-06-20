<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
class vote extends admin {
	private $db2, $db;
	function __construct() {
		parent::__construct();
		$this->M = new_html_special_chars(getcache('vote', 'commons'));
		$this->db = pc_base::load_model('vote_subject_model');
		$this->db2 = pc_base::load_model('vote_option_model');
	}

	public function init() {
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->db->listinfo(array('siteid'=>$this->get_siteid()),'subjectid DESC',$page, '14');
		$pages = $this->db->pages;
		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=vote&c=vote&a=add\', title:\''.L('vote_add').'\', width:\'700\', height:\'450\'}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('vote_add'));
		include $this->admin_tpl('vote_list'); 
 	}

	/*
	 *�жϱ����ظ�����֤ 
	 */
	public function public_name() {
		$subject_title = isset($_GET['subject_title']) && trim($_GET['subject_title']) ? (pc_base::load_config('system', 'charset') == 'gbk' ? iconv('utf-8', 'gbk', trim($_GET['subject_title'])) : trim($_GET['subject_title'])) : exit('0');
		$subjectid = isset($_GET['subjectid']) && intval($_GET['subjectid']) ? intval($_GET['subjectid']) : '';
		$data = array();
		if ($subjectid) {
			$data = $this->db->get_one(array('subjectid'=>$subjectid), 'subject');
			if (!empty($data) && $data['subject'] == $subject_title) {
				exit('1');
			}
		}
		if ($this->db->get_one(array('subject'=>$subject_title), 'subjectid')) {
			exit('0');
		} else {
			exit('1');
		}
	}
	
	/*
	 *�жϽ���ʱ���Ƿ�ȵ�ǰʱ��С  
	 */
	public function checkdate() {
		$nowdate = date('Y-m-d',SYS_TIME);
		$todate = $_GET['todate'];
		if($todate > $nowdate){
			exit('1');
		}else {
			exit('0');
		}
	}
	
	/**
	 * ���ͶƱ
	 */
	public function add() {
		//��ȡ�����ļ�
		$data = array();
		$data = $this->M;
		$siteid = $this->get_siteid();//��ǰվ��
		if(isset($_POST['dosubmit'])) {
			
			$_POST['subject']['addtime'] = SYS_TIME;
			$_POST['subject']['siteid'] = $this->get_siteid();
			if(empty($_POST['subject']['subject'])) {
				showmessage(L('vote_title_noempty'),'?m=vote&c=vote&a=add');
			}
 			//��¼ѡ������ optionnumber 
			$_POST['subject']['optionnumber'] = count($_POST['option']);
			$_POST['subject']['template'] = $_POST['vote_subject']['vote_tp_template'];
 			
			$subjectid = $this->db->insert($_POST['subject'],true);
			if(!$subjectid) return FALSE; //����ͶƱIDֵ, �Ա�������Ӷ�Ӧѡ����,�����ڷ��ش���
			//���ѡ�����
			$this->db2->add_options($_POST['option'],$subjectid,$this->get_siteid());
			//����JS�ļ�
			$this->update_votejs($subjectid);
			if(isset($_POST['from_api'])&& $_POST['from_api']) {
				showmessage(L('operation_success'),'?m=vote&c=vote&a=add','100', '',"window.top.$('#voteid').val('".$subjectid."');window.top.art.dialog({id:'addvote'}).close();");
			} else {
				showmessage(L('operation_success'),'?m=vote&c=vote','','add');
 			}
		} else {
			$show_validator = $show_scroll = $show_header = true;
			pc_base::load_sys_class('form', '', 0);
			@extract($data[$siteid]);
			//ģ��
			pc_base::load_app_func('global', 'admin');
			$siteid = $this->get_siteid();
			$template_list = template_list($siteid, 0);
			$site = pc_base::load_app_class('sites','admin');
			$info = $site->get_by_id($siteid);
			foreach ($template_list as $k=>$v) {
				$template_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
				unset($template_list[$k]);
			}
			include $this->admin_tpl('vote_add');
		}

	}

	/**
	 * �༭ͶƱ
	 */
	public function edit() {

		if(isset($_POST['dosubmit'])){
			//��֤������ȷ��
				$subjectid = intval($_GET['subjectid']);
				if($subjectid < 1) return false;
				if(!is_array($_POST['subject']) || empty($_POST['subject'])) return false;
				if((!$_POST['subject']['subject']) || empty($_POST['subject']['subject'])) return false;
 				$this->db2->update_options($_POST['option']);//�ȸ������� ͶƱѡ��,�����������ͶƱѡ��
				if(is_array($_POST['newoption'])&&!empty($_POST['newoption'])){
					$siteid = $this->get_siteid();//�¼�ѡ��վ��ID
					$this->db2->add_options($_POST['newoption'],$subjectid,$siteid);
				}
				//ģ�� 
				$_POST['subject']['template'] = $_POST['vote_subject']['vote_tp_template'];
				
				$_POST['subject']['optionnumber'] = count($_POST['option'])+count($_POST['newoption']);
	 			$this->db->update($_POST['subject'],array('subjectid'=>$subjectid));//����ͶƱѡ������
				$this->update_votejs($subjectid);//����JS�ļ�
				showmessage(L('operation_success'),'?m=vote&c=vote&a=edit','', 'edit');
			}else{
				$show_validator = $show_scroll = $show_header = true;
				pc_base::load_sys_class('form', '', 0);
				
				//���ͶƱ����
				$info = $this->db->get_one(array('subjectid'=>$_GET['subjectid']));
				if(!$info) showmessage(L('operation_success'));
				extract($info);
					
				//���ͶƱѡ��
				$this->db2 = pc_base::load_model('vote_option_model');
				$options = $this->db2->get_options($_GET['subjectid']);
				
				//ģ��
				pc_base::load_app_func('global', 'admin');
				$siteid = $this->get_siteid();
				$template_list = template_list($siteid, 0);
				$site = pc_base::load_app_class('sites','admin');
				$info = $site->get_by_id($siteid);
				foreach ($template_list as $k=>$v) {
					$template_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
					unset($template_list[$k]);
				}
	
				include $this->admin_tpl('vote_edit');
		}

	}

	/**
	 * ɾ��ͶƱ 
	 * @param	intval	$sid	ͶƱ��ID���ݹ�ɾ��
	 */
	public function delete() {
		if((!isset($_GET['subjectid']) || empty($_GET['subjectid'])) && (!isset($_POST['subjectid']) || empty($_POST['subjectid']))) {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		} else {
				
			if(is_array($_POST['subjectid'])){
				foreach($_POST['subjectid'] as $subjectid_arr) {
					//ɾ����ӦͶƱ��ѡ��
					$this->db2 = pc_base::load_model('vote_option_model');
					$this->db2->del_options($subjectid_arr);
					$this->db->delete(array('subjectid'=>$subjectid_arr));
				}
				showmessage(L('operation_success'),'?m=vote&c=vote');
			}else{
				$subjectid = intval($_GET['subjectid']);
				if($subjectid < 1) return false;
				//ɾ����ӦͶƱ��ѡ��
				$this->db2 = pc_base::load_model('vote_option_model');
				$this->db2->del_options($subjectid);

				//ɾ��ͶƱ
				$this->db->delete(array('subjectid'=>$subjectid));
				$result = $this->db->delete(array('subjectid'=>$subjectid));
				if($result)
				{
					showmessage(L('operation_success'),'?m=vote&c=vote');
				}else {
					showmessage(L("operation_failure"),'?m=vote&c=vote');
				}
			}
				
			showmessage(L('operation_success'), HTTP_REFERER);
		}
	}
	/**
	 * ˵��:ɾ����ӦͶƱѡ��
	 * @param  $optionid
	 */
	public function del_option() {
		$result = $this->db2->del_option($_GET['optionid']);
		if($result) {
			echo 1;
		} else {
			echo 0;
		}
	} 
	
	
	/**
	 * ͶƱģ������
	 */
	public function setting() {
		//��ȡ�����ļ�
		$data = array();
 		$siteid = $this->get_siteid();//��ǰվ�� 
		//����ģ�����ݿ�,����setting ����. 
		$m_db = pc_base::load_model('module_model');
		$data = $m_db->select(array('module'=>'vote'));
		$setting = string2array($data[0]['setting']);
		$now_seting = $setting[$siteid]; 
 		if(isset($_POST['dosubmit'])) {
			//��վ��洢�����ļ�
			$siteid = $this->get_siteid();//��ǰվ��
			$setting[$siteid] = $_POST['setting'];
  			setcache('vote', $setting, 'commons');  
			//����ģ�����ݿ�,����setting ����. 
 			$set = array2string($setting);
			$m_db->update(array('setting'=>$set), array('module'=>ROUTE_M));
			showmessage(L('setting_updates_successful'), '?m=vote&c=vote&a=init');
		} else {
			@extract($now_seting);
			pc_base::load_sys_class('form', '', 0);
			//ģ��
			pc_base::load_app_func('global', 'admin');
			$siteid = $this->get_siteid();
			$template_list = template_list($siteid, 0);
			$site = pc_base::load_app_class('sites','admin');
			$info = $site->get_by_id($siteid);
			foreach ($template_list as $k=>$v) {
				$template_list[$v['dirname']] = $v['name'] ? $v['name'] : $v['dirname'];
				unset($template_list[$k]);
			}
			include $this->admin_tpl('setting');
		}
	}


	/**
	 * ��������
	 * @param	Array	$data	�����ݹ���������
	 * @return Array	���������
	 */
	private function check($data = array()) {
		if($data['name'] == '') showmessage(L('name_plates_not_empty'));
		if(!isset($data['width']) || $data['width']==0) {
			showmessage(L('plate_width_not_empty'), HTTP_REFERER);
		} else {
			$data['width'] = intval($data['width']);
		}
		if(!isset($data['height']) || $data['height']==0) {
			showmessage(L('plate_height_not_empty'), HTTP_REFERER);
		} else {
			$data['height'] = intval($data['height']);
		}
		return $data;
	}
		
	/**
	 * ͶƱ���ͳ��
	 */
	public function statistics() {
			$subjectid = intval($_GET['subjectid']);
			if(!$subjectid){
				showmessage(L('illegal_operation'));
			}
			$show_validator = $show_scroll = $show_header = true;
 			//��ȡͶƱ��Ϣ
			$sdb = pc_base::load_model('vote_data_model'); //����ͶƱͳ�Ƶ�����ģ��
        	$infos = $sdb->select("subjectid = $subjectid",'data');	
          	//�½�һ�������������������
        	$total = 0;
        	$vote_data =array();
			$vote_data['total'] = 0 ;//����ͶƱѡ������
			$vote_data['votes'] = 0 ;//ͶƱ����
			//ѭ��ÿ����Ա��ͶƱ��¼
			foreach($infos as $subjectid_arr) {
					extract($subjectid_arr);
 					$arr = string2array($data);
 					foreach($arr as $key => $values){
 						$vote_data[$key]+=1;
					}
  					$total += array_sum($arr);
					$vote_data['votes']++ ;
			}
 			$vote_data['total'] = $total ;
 			//ȡͶƱѡ��
			$options = $this->db2->get_options($subjectid);	
			include $this->admin_tpl('vote_statistics');	
	}
	
	/**
	 * ͶƱ��Աͳ��
	 */
	public function statistics_userlist() {
			$subjectid = $_GET['subjectid'];
			if(empty($subjectid)) return false;
 			$show_validator = $show_scroll = $show_header = true;
			$where = array ("subjectid" => $subjectid);
			$sdb = pc_base::load_model('vote_data_model'); //����ͳ�Ƶ�����ģ��
 			$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
			$infos = $sdb->listinfo($where,'time DESC',$page,'7');
			$pages = $sdb->pages;
			include $this->admin_tpl('vote_statistics_userlist');
	}
	
	/**
	 * ˵��:����JSͶƱ����
	 * @param $subjectid ͶƱID
	 */
	function update_votejs($subjectid){
 			if(!isset($subjectid)||intval($subjectid) < 1) return false;
			//���ͶƱ����
			$info = $this->db->get_subject($subjectid);
			if(!$info) showmessage(L('not_vote'));
			extract($info);
 			//���ͶƱѡ��
			$options = $this->db2->get_options($subjectid);
 			ob_start();
 			include template('vote', $template);
			$voteform = ob_get_contents();
			ob_clean() ;
	        @file_put_contents(CACHE_PATH.'vote_js/vote_'.$subjectid.'.js', $this->format_js($voteform));
	        
	}
	
	/**
	 * ����js
	 */
	public function create_js() {
 		$infos = $this->db->select(array('siteid'=>$this->get_siteid()), '*');
		if(is_array($infos)){
			foreach($infos as $subjectid_arr) {
				$this->update_votejs($subjectid_arr['subjectid']);
			}
		}
		showmessage(L('operation_success'),'?m=vote&c=vote');
	}
	
	/**
	 * ˵��:���ַ������д���
	 * @param $string ��������ַ���
	 * @param $isjs �Ƿ�����JS����
	 */
	function format_js($string, $isjs = 1){
		$string = addslashes(str_replace(array("\r", "\n"), array('', ''), $string));
		return $isjs ? 'document.write("'.$string.'");' : $string;
	}
	
	/**
	 * ͶƱ���ô���
	 * 
	 */ 
 	public function public_call() {
 		$_GET['subjectid'] = intval($_GET['subjectid']);
		if(!$_GET['subjectid']) showmessage(L('illegal_action'), HTTP_REFERER, '', 'call');
		$r = $this->db->get_one(array('subjectid'=>$_GET['subjectid']));
		include $this->admin_tpl('vote_call');
	}
	/**
	 * ��Ϣѡ��ͶƱ�ӿ�
	 */
	public function public_get_votelist() {
		$infos = $this->db->listinfo(array('siteid'=>$this->get_siteid()),'subjectid DESC',$page,'10');
		$target = isset($_GET['target']) ? $_GET['target'] : '';
		include $this->admin_tpl('get_votelist');
	}
	
}
?>