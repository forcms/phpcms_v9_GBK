<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('foreground','member');//����foreground Ӧ����. �Զ��ж��Ƿ��½.
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);

class index extends foreground {
	function __construct() {
		parent::__construct();
		$this->message_db = pc_base::load_model('message_model');
		$this->message_group_db = pc_base::load_model('message_group_model');
		$this->message_data_db = pc_base::load_model('message_data_model');
		$this->_username = param::get_cookie('_username');
		$this->_userid = param::get_cookie('_userid');
		$this->_groupid = get_memberinfo($this->_userid,'groupid');
		pc_base::load_app_func('global');
		//����վ��ID������ѡ��ģ��ʹ��
		$siteid = isset($_GET['siteid']) ? intval($_GET['siteid']) : get_siteid();
  		define("SITEID",$siteid);
  	}

	public function init() {
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$where = array('send_to_id'=>$this->_username,'replyid'=>'0');
 		$infos = $this->message_db->listinfo($where,$order = 'messageid DESC',$page, 10);
 		$infos = new_html_special_chars($infos);
 		$pages = $this->message_db->pages;
		include template('message', 'inbox');
	}
	
	
	/**
	 * ������Ϣ 
	 */
	public function send() {
		if(isset($_POST['dosubmit'])) {
			$username = $this->_username;
			$tousername = $_POST['info']['send_to_id'];
			$subject = new_html_special_chars($_POST['info']['subject']);
			$content = new_html_special_chars($_POST['info']['content']);
			$this->message_db->add_message($tousername,$username,$subject,$content,true);
			showmessage(L('operation_success'),HTTP_REFERER);
		} else {
			//�жϵ�ǰ��Ա���Ƿ�ɷ�������Ϣ��
			$this->message_db->messagecheck($this->_userid);
			$show_validator = $show_scroll = $show_header = true;
			include template('message', 'send');
		}
	}
	
	/*
	 *�ж��ռ����Ƿ���� 
	 */
	public function public_name() {
		$username = isset($_GET['username']) && trim($_GET['username']) ? (pc_base::load_config('system', 'charset') == 'gbk' ? iconv('utf-8', 'gbk', trim($_GET['username'])) : trim($_GET['username'])) : exit('0');
		$member_interface = pc_base::load_app_class('member_interface', 'member');
		if ($username) {
			$username = safe_replace($username);
			//�ж��ռ��˲���Ϊ�Լ�
			if($username == $this->_username){
				exit('0');
			}
			$data = $member_interface->get_member_info($username, 2);
			if ($data!='-1') {
				exit('1');
			} else {
				exit('0');
			}
		} else {
			exit('0');
		}
		
	}
	
	/**
	 * ������
	 */
	public function outbox() { 
		$where = array('send_from_id'=>$this->_username,'del_type'=>'0');
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->message_db->listinfo($where,$order = 'messageid DESC',$page, $pages = '8');
		$infos = new_html_special_chars($infos);
		$pages = $this->message_db->pages;
		include template('message', 'outbox');
	}
	
	/**
	 * �ռ���
	 */
	public function inbox() { 
		$where = array('send_to_id'=>$this->_username,'folder'=>'inbox');
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->message_db->listinfo($where,$order = 'messageid DESC',$page, $pages = '8'); 
		$infos = new_html_special_chars($infos);
		if (is_array($infos) && !empty($infos)) {
			foreach ($infos as $infoid=>$info){ 
				$reply_num = $this->message_db->count(array("replyid"=>$info['messageid']));
				$infos[$infoid]['reply_num'] = $reply_num;
	 		}
		}
		$pages = $this->message_db->pages;
		include template('message', 'inbox');
	}
	
	/**
	 * Ⱥ���ʼ�
	 */
	public function group() { 
		//��ѯ�Լ���Ȩ�޿�����Ϣ
  		$where = array('typeid'=>1,'groupid'=>$this->_groupid,'status'=>1);
		$page = isset($_GET['page']) && intval($_GET['page']) ? intval($_GET['page']) : 1;
		$infos = $this->message_group_db->listinfo($where,$order = 'id DESC',$page, $pages = '8');
		$infos = new_html_special_chars($infos);
		$status = array();
		if (is_array($infos) && !empty($infos)) {
			foreach ($infos as $info){
				$d = $this->message_data_db->select(array('userid'=>$this->_userid,'group_message_id'=>$info['id']));
	 			if(!$d){
	 				$status[$info['id']] = 0;//δ�� ��ɫ
	 			}else {
	 				$status[$info['id']] = 1;
	 			}
			}
		}
 		$pages = $this->message_group_db->pages;
		include template('message', 'group');
	}
	
	/**
	 * ɾ���ռ���-����Ϣ 
	 * @param	intval	$sid	����ϢID���ݹ�ɾ��(�޸�״̬Ϊoutbox)
	 */
	public function delete() {
		if((!isset($_GET['messageid']) || empty($_GET['messageid'])) && (!isset($_POST['messageid']) || empty($_POST['messageid']))) {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		} else {
			if(is_array($_POST['messageid'])){
				foreach($_POST['messageid'] as $messageid_arr) {
					$messageid_arr = intval($messageid_arr);
					$this->message_db->update(array('folder'=>'outbox'),array('messageid'=>$messageid_arr,'send_to_id'=>$this->_username));
				}
				showmessage(L('operation_success'), HTTP_REFERER);
			}
 		}
	}
	
	/**
	 * ɾ�������� - ����Ϣ 
	 * @param	intval	$sid	����ϢID���ݹ�ɾ��( �޸�״̬Ϊdel_type =1 )
	 */
	public function del_type() {
		if((!isset($_POST['messageid']) || empty($_POST['messageid']))) {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		} else {
				if(is_array($_POST['messageid'])){
					foreach($_POST['messageid'] as $messageid_arr) {
						$messageid_arr = intval($messageid_arr);
 						$this->message_db->update(array('del_type'=>'1'),array('messageid'=>$messageid_arr,'send_from_id'=>$this->_username));
					}
					showmessage(L('operation_success'), HTTP_REFERER);
				} 
		}
	}
	
	/**
	 * �鿴����Ϣ - �Ե�ǰ�û��Ƿ���Ȩ�޲鿴
	 */
	public function check_user($messageid,$where){
		$username = $this->_username;
		$messageid = intval($messageid);
		if($where=="to"){
			$result = $this->message_db->get_one(array("send_to_id"=>$username,"messageid"=>$messageid));
		}else{
			$result = $this->message_db->get_one(array("send_from_id"=>$username,"messageid"=>$messageid));
		}
 		if(!$result){//���ǵ�ǰ�û�����Ϣ�����ܲ鿴
			showmessage('����Ƿ����ʣ�', HTTP_REFERER);echo '0';
 		} 
	}
	
	
	/**
	 * �鿴����Ϣ
	 */
	public function read() { 
		if((!isset($_GET['messageid']) || empty($_GET['messageid'])) && (!isset($_POST['messageid']) || empty($_POST['messageid']))) return false;
		$messageid = $_GET['messageid'] ? $_GET['messageid'] : $_POST['messageid'];
		$messageid = intval($messageid);
		//�ж��Ƿ����ڵ�ǰ�û�
		$check_user = $this->check_user($messageid,'to'); 
		
 		//�鿴���޸�״̬ Ϊ 0 
		$this->message_db->update(array('status'=>'0'),array('messageid'=>$messageid));
		//��ѯ��Ϣ����
		$infos = $this->message_db->get_one(array('messageid'=>$messageid));
		if($infos['send_from_id']!='SYSTEM') $infos = new_html_special_chars($infos);
		//��ѯ�ظ���Ϣ
		$where = array('replyid'=>$infos['messageid']);
		$reply_infos = $this->message_db->listinfo($where,$order = 'messageid ASC',$page, $pages = '10');
		$show_validator = $show_scroll = $show_header = true;
		include template('message', 'read');
	}
	
	/**
	 * �鿴�Լ����Ķ���Ϣ
	 */
	public function read_only() { 
		$messageid = $_GET['messageid'] ? $_GET['messageid'] : $_POST['messageid'];
		$messageid = intval($messageid);
		if(!$messageid || empty($messageid)){
			showmessage('����Ƿ����ʣ�', HTTP_REFERER);
		}
		//�ж��Ƿ����ڵ�ǰ�û�
		$check_user = $this->check_user($messageid,'from'); 
		
		//��ѯ��Ϣ����
		$infos = $this->message_db->get_one(array('messageid'=>$messageid));
		$infos = new_html_special_chars($infos);
		//��ѯ�ظ���Ϣ
		$where = array('replyid'=>$infos['messageid']);
		$reply_infos = $this->message_db->listinfo($where,$order = 'messageid ASC',$page, $pages = '10');
		$show_validator = $show_scroll = $show_header = true;
		include template('message', 'read_only');
	}
	
	/**
	 * �鿴ϵͳ����Ϣ
	 */
	public function read_group(){
		if((!isset($_GET['group_id']) || empty($_GET['group_id'])) && (!isset($_POST['group_id']) || empty($_POST['group_id']))) return false;
		//��ѯ��Ϣ����
		$infos = $this->message_group_db->get_one(array('id'=>$_GET['group_id']));
		$infos = new_html_special_chars($infos);
		if(!is_array($infos))showmessage(L('message_not_exist'),'blank');
		//���鿴���Ƿ��м�¼,������message_data ���������¼  
		$check = $this->message_data_db->select(array('userid'=>$this->_userid,'group_message_id'=>$_GET['group_id']));
		if(!$check){
			$this->message_data_db->insert(array('userid'=>$this->_userid,'group_message_id'=>$_GET['group_id']));
		}
 		include template('message', 'read_group');
	}
	
	/**
	 * �ظ�����Ϣ 
	 */
	public function reply() {
		if(isset($_POST['dosubmit'])) {
			$messageid = intval($_POST['info']['replyid']);
			//�жϵ�ǰ��Ա���Ƿ�ɷ�������Ϣ��
			$this->message_db->messagecheck($this->_userid);
			//������Ϣ�Ƿ���Ȩ�޻ظ� 
			$this->check_user($messageid,'to');
			
 			$_POST['info']['send_from_id'] = $this->_username;
			$_POST['info']['message_time'] = SYS_TIME;
			$_POST['info']['status'] = '1';
			$_POST['info']['folder'] = 'inbox';
			if(empty($_POST['info']['send_to_id'])) {
				showmessage(L('user_noempty'),HTTP_REFERER);
			}
			$messageid = $this->message_db->insert($_POST['info'],true);
			if(!$messageid) return FALSE; 
			showmessage(L('operation_success'),HTTP_REFERER);
			
		} else {
			$show_validator = $show_scroll = $show_header = true; 
			include template('message', 'send');
		}

	}
	 
	
}	
?>	