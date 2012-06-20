<?php
defined('IN_PHPCMS') or exit('No permission resources.');
class index {
	protected  $reviewsid, $modules, $siteid, $format;
	function __construct() {
		pc_base::load_app_func('global');
		pc_base::load_sys_class('format', '', 0);
		$this->dianping = pc_base::load_model('dianping_model');
		$this->dianping_data = pc_base::load_model('dianping_data_model');
		//list($this->modules, $contentid, $this->siteid) = decode_reviewsid($this->reviewsid);
		$this->username = param::get_cookie('_username');
		$this->userid = param::get_cookie('_userid');
		$this->siteid = get_siteid();
		define('SITEID', $this->siteid);
	}
	
	/**
	 * 
	 * Ĭ��ǰ̨��ʾ ...
	 */
	public function init() {
		$hot = isset($_GET['hot']) && intval($_GET['hot']) ? intval($_GET['hot']) : 0;
		$siteid =& $this->siteid; 
		$dianpingid = $_GET['dianpingid'];
		//��ȡ���ͻ��棬��ǰ̨���� 
		$dianping_type = $_GET['dianping_type'];
		$type_array = getcache('dianping_type','dianping');
		$module = $_GET['module'];
		$modelid = $_GET['modelid'];
 		$page = $_GET[page];
 		
 		
 		$contentid = $_GET['contentid'];
		if(empty($type_array[$dianping_type])){
			showmessage('�����̨���ã��Ƿ��д�����࣡');exit;
		}

		//��ȡ��ǰ�������ã��鿴�Ƿ����������ѡ��
		$setting = string2array($type_array[$dianping_type]['setting']); 
		$is_checkuserid = $setting['is_checkuserid'];
 		//�������ο͵�������������Ҫ�ٸ��ݴ��ݵĲ������ж��Ƿ�Ҫ�����û��������
 		if($is_checkuserid=='1'){
	 			$comment_relation = pc_base::load_model('comment_relation_model');
				$sql = array("userid"=>$this->userid,'module'=>$module,'contentid'=>$contentid);
	 			$allowdianping_array = $comment_relation->get_one($sql);
	 			if($allowdianping_array){
	 				$is_allowdianping = '1';
	 				$del_id = $allowdianping_array['id'];
	 			}else{
	 				$is_allowdianping = '0';
	 				$dianping_info = '���Ѿ���������Ϣ������δ����˲�Ʒ���޷�������';
	 			}
 	  	}else {
 	  			if($setting['guest']=='1'){
 	  				$is_allowdianping = '1';
  	  			}else{
 	  				if($this->userid){
 	  					$is_allowdianping = '1';
 	  				}else{
 	  					$is_allowdianping = '0';
 	  					$dianping_info ='�Բ��𣬲������ο͵�����';
 	  				}
 	  			}
 	  	} 
		
  		pc_base::load_sys_class('form', '', 0);
   		if (isset($_GET['iframe'])) { 
			if ($_GET['iframe'] ==1) {
  				include template('dianping', 'show_list');
			}elseif($_GET['iframe'] =='2') {
				include template('dianping', 'show_milist');
			}
 		}else {
			include template('dianping', 'list');
		}
	}
	
	/**
	 * 
	 * �����б�ҳ ...
	 */
	public function dianping_data_list(){
		//��ȡ����diapinID
		$page = intval($_GET['page']);
 		if($page<=0){
 			$page = 1;
 		}
		$dianpingid = $_GET['dianpingid'];
 		include template('dianping', 'dianping_data_list');
	}

	/**
	 * 
	 * �ύ���� ...
	 */
	public function post(){
   		//������������
 		if(!is_array($_POST['data'])){
			showmessage('������Դ�������飡',HTTP_REFERER);return false;
 		}
   		
   		$module = $_POST['module'];
 		$modelid = $_POST['modelid'];
 		$dianping_type = intval($_POST['dianping_type']);
 		$dianpingid = $_GET['dianpingid'];
 		$content = new_html_special_chars(iconv('UTF-8',CHARSET,$_POST['content']));
 		$addtime = SYS_TIME;
		
 		$new_array = array();
 		$dianping_type_array = getcache('dianping_type','dianping');
 		
 		//�ȸ���TYPEID���ж��Ƿ���������
  		$type_setting = string2array($dianping_type_array[$dianping_type]['setting']); 
		$is_checkuserid = $type_setting['is_checkuserid'];
		$is_guest = $type_setting['guest'];
		if(!$is_guest){
			//�������ο͵���
			if(!$this->userid){
				showmessage('����Ϣ�����¼�����ܵ�����',HTTP_REFERER);return false;
			}
		}
		if($is_checkuserid){
			//Ҫ����Ա��Ϣ
			$contentid = intval($_GET['contentid']);
			if (!$contentid) list($mod, $contentid, $siteid) = explode('-', $dianpingid);
			$comment_relation = pc_base::load_model('comment_relation_model');
			$sql = array("userid"=>$this->userid,'module'=>$module,'contentid'=>$contentid);
 			$allowdianping_array = $comment_relation->get_one($sql);
 			if(!$allowdianping_array){
 				showmessage('����Ϣ������ݲ��ܵ�������˲����Ƿ������������Ϣ��',HTTP_REFERER);return false;
 			}
		}
 		
 		//��Ҫ������ϵ���������������
		$post_nums = '0';
 		$dianping_type_data = explode('&&', $dianping_type_array[$dianping_type]['data']);
 		foreach ($_POST['data'] as $key=>$val){
 			$new_array[$dianping_type_data[$key-1]] = $val; 
			$post_nums +=$val;
 		}
		//�����ۺϵ÷�
		$all_points = count($dianping_type_data)*5;
		$new_array['ƽ���÷�'] = round(($post_nums/$all_points)*100);
		
 		$data_array = array2string($new_array); 
  		
		//�Ѹ��������ֵ���������������ݿ���
  		$insert_data = array('userid'=>$this->userid, 'username'=>$this->username, 'dianpingid'=>$dianpingid,'module'=>$module,'modelid'=>$modelid,'catid'=>$catid,'siteid'=>SITEID,'content'=>$content,'dianping_typeid'=>$dianping_type, 'status'=>'1','is_useful'=>'','data'=>$data_array, 'addtime'=>$addtime);
 		
 		$return_dianpingid = $this->dianping_data->insert($insert_data);
		if(!$return_dianpingid){
			showmessage('����ʧ�ܣ����飡',HTTP_REFERER);
		}else {
 			//Ϊע���Ա�������ӻ���
 			$setting = string2array($dianping_type_array[$dianping_type]['setting']);
 			if($this->userid && $setting['add_point']>0){
 				//�����ɹ����������ã�Ϊ��Ա�ӷ�
 	 			$member_user_db = pc_base::load_app_class('member_interface','member');
 	 			$member_user_db->add_point($this->userid,$setting['add_point']);
 			}
  			if(intval($_GET['is_checkuserid']) == '1' && intval($_GET['del_id'])){
				//���м���û����������ݹ������ύ�ɹ���Ҫɾ����Ӧ��¼�comment_relation ��
				$coment_relation = pc_base::load_model('comment_relation_model');
				$sql = array('id'=>intval($_GET['del_id']));
				$coment_relation->delete($sql);
			}
 			
			//���ɹ�����v9_dianping ��
			$dianping_data = array();
			$dianping_data['dianpingid'] = $dianpingid;
			$dianping_data['siteid'] = SITEID;
 	 		
			//�Ȳ�ѯ�Ƿ��Ѿ����ڴ����ݣ���������£��������
			$dianping_sql = array('dianpingid'=>$dianpingid);
			$dianping_result = $this->dianping->get_one($dianping_sql);
			if($dianping_result){
				//�������ݣ�����֮
				$update_data = array();
				foreach ($_POST['data'] as $key=>$val){
	 			$dianping_data['data'.$key] = $val+$dianping_result['data'.$key]; 
	 			}
	 			$dianping_data['dianping_nums'] = $dianping_result['dianping_nums'] + 1;
				$update_where = array('dianpingid'=>$dianpingid);
 				$return_id = $this->dianping->update($dianping_data,$update_where);
 			}else {
				//�����ݣ������֮
				foreach ($_POST['data'] as $key=>$val){
		 			$dianping_data['data'.$key] = $val; 
		 		}
		 		$dianping_data['dianping_typeid'] = $dianping_type;
		 		$dianping_data['dianping_nums'] = 1;
 		 		$dianping_data['addtime'] = SYS_TIME;
				$return_id = $this->dianping->insert($dianping_data);
			}
 			if($return_id){
				echo 1;
			}else{
				echo 0;
			}
		}
 	}

}
?>
