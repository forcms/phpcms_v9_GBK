<?php
defined('IN_PHPCMS') or exit('No permission resources.');
class index {
	
	function __construct() {
		pc_base::load_app_func('global');
		$this->vote = pc_base::load_model('vote_subject_model');//ͶƱ����
		$this->vote_option = pc_base::load_model('vote_option_model');//ͶƱѡ��
		$this->vote_data = pc_base::load_model('vote_data_model'); //ͶƱͳ�Ƶ�����ģ��
		$this->username = param::get_cookie('_username');
		$this->userid = param::get_cookie('_userid'); 
		$this->groupid = param::get_cookie('_groupid'); 
		$this->ip = ip();
		
		$siteid = isset($_GET['siteid']) ? intval($_GET['siteid']) : get_siteid();
  		define("SITEID",$siteid);
 	}
	
	public function init() {
		$siteid = SITEID;
 		$page = intval($_GET['page']);
 		if($page<=0){
 			$page = 1;
 		}
     	include template('vote', 'list_new');
	}
	
	 /**
	 *	ͶƱ�б�ҳ
	 */
	public function lists() {
 		$siteid = SITEID;
 		$page = intval($_GET['page']);
 		if($page<=0){
 			$page = 1;
 		}
     	include template('vote', 'list_new');
	}
	
	/**
	 * ͶƱ��ʾҳ
	 */
	public function show(){
		$type = intval($_GET['type']);//���÷�ʽID
		$subjectid = abs(intval($_GET['subjectid']));
		if(!$subjectid) showmessage(L('vote_novote'),'blank');
 		//ȡ��ͶƱ����
		$subject_arr = $this->vote->get_subject($subjectid);
		
		$siteid = $subject_arr['siteid'];

		//�����жϣ���ֹģ����ò�����ͶƱʱjs���� wangtiecheng
		if(!is_array($subject_arr)) {
			if(isset($_GET['action']) && $_GET['action'] == 'js') {
				exit;
			} else {
				showmessage(L('vote_novote'),'blank');
			}
		}
		extract($subject_arr);
		//��ʾģ��
		$template = $template ? $template: 'vote_tp';
 		//��ȡͶƱѡ��
		$options = $this->vote_option->get_options($subjectid);
		
		//�½�һ�������������������
        $total = 0;
        $vote_data =array();
		$vote_data['total'] = 0 ;//����ͶƱѡ������
		$vote_data['votes'] = 0 ;//ͶƱ����
		
		//��ȡͶƱ�����Ϣ
        $infos = $this->vote_data->select(array('subjectid'=>$subjectid),'data');	
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
 		
 		
		//ȡ��ͶƱ����ʱ�䣬���С�ڵ�ǰʱ�䣬��ѡ���Ҳ���ѡ
		if(date("Y-m-d",SYS_TIME)>$todate){
			$check_status = 'disabled';
			$display = 'display:none;';
 		}else {
 			$check_status = '';
 		}
 		
 		
  		//JS���� 
		if($_GET['action']=='js'){
		 	if(!function_exists('ob_gzhandler')) ob_clean();
			ob_start();
 			//$template = 'submit';
 			$template = $subject_arr['template'];
 			//����TYPEֵ���жϵ���ģ��
 			switch ($type){
				case 3://��ҳ����Ŀҳ����
				$true_template = 'vote_tp_3';
				break; 
				case 2://����ҳ����
				$true_template = 'vote_tp_2';	
				break;
				default:
				$true_template = $template;
 			}
  		 	include template('vote',$true_template);
			$data=ob_get_contents();
			ob_clean();
			exit(format_js($data));
		}
		
 		//SEO���� 
		$SEO = seo(SITEID, '', $subject, $description, $subject);
 		//ǰ̨ͶƱ�б����Ĭ��ҳ��,����ҳ����ʽ����.
 		if($_GET['show_type']==1){
 			include template('vote', 'vote_tp');
 		}else {
 			include template('vote', $template);
 		}
 		
	} 
	
	/**
	 * ����ͶƱ
	 */
	public function post(){
		$subjectid = intval($_POST['subjectid']);
		if(!$subjectid)	showmessage(L('vote_novote'),'blank');
		//��ǰվ��
		$siteid = SITEID;
		//�ж��Ƿ���Ͷ��Ʊ,������δ���ڶ���ͶƱ��
		$return = $this->check($subjectid);
 		switch ($return) {
		case 0:
		  showmessage(L('vote_voteyes'),"?m=vote&c=index&a=result&subjectid=$subjectid&siteid=$siteid");
		  break;
		case -1:
		  showmessage(L('vote_voteyes'),"?m=vote&c=index&a=result&subjectid=$subjectid&siteid=$siteid");
		  break;
		}
		if(!is_array($_POST['radio'])) showmessage(L('vote_nooption'),'blank');
    	$time = SYS_TIME;
 		
   		$data_arr = array();
  		foreach($_POST['radio'] as $radio){
  			$data_arr[$radio]='1';
  		}
  		$new_data = array2string($data_arr);//ת���ַ����������ݿ���  
  		//��ӵ����ݿ�
		$this->vote_data->insert(array('userid'=>$this->userid,'username'=>$this->username,'subjectid'=>$subjectid,'time'=>$time,'ip'=>$this->ip,'data'=>$new_data));
 		//��ѯͶƱ���������������»�Ա����
 		$vote_arr = $this->vote->get_one(array('subjectid'=>$subjectid));
  		pc_base::load_app_class('receipts','pay',0);
		receipts::point($vote_arr['credit'],$this->userid, $this->username, '','selfincome',L('vote_post_point'));
 		//����ͶƱ���� 
 		$this->vote->update(array('votenumber'=>'+=1'),array('subjectid'=>$subjectid));
		showmessage(L('vote_votesucceed'), "?m=vote&c=index&a=result&subjectid=$subjectid&siteid=$siteid");
	}
	
	/**
	 * 
	 * ͶƱ�����ʾ 
	 */
	public function result(){
		$siteid = SITEID;
		$subjectid = abs(intval($_GET['subjectid']));
		if(!$subjectid)	showmessage(L('vote_novote'),'blank');
		//ȡ��ͶƱ����
		$subject_arr = $this->vote->get_subject($subjectid);
		if(!is_array($subject_arr)) showmessage(L('vote_novote'),'blank');
		extract($subject_arr);
		//��ȡͶƱѡ��
		$options = $this->vote_option->get_options($subjectid);
		
		//�½�һ�������������������
        $total = 0;
        $vote_data =array();
		$vote_data['total'] = 0 ;//����ͶƱѡ������
		$vote_data['votes'] = 0 ;//ͶƱ����
		
		//��ȡͶƱ�����Ϣ
        $infos = $this->vote_data->select(array('subjectid'=>$subjectid),'data');	
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
 		//SEO���� 
		$SEO = seo(SITEID, '', $subject, $description, $subject);
   		include template('vote','vote_result');
	}
	
	/**
	 * 
	 * ͶƱǰ���
	 * @param $subjectid ͶƱID 
	 * @return ����ֵ (1:��ͶƱ  0: ��Ͷ,ʱ����ڲ���ͶƱ  -1:��Ͷ,��ͶƱ,�����ظ�ͶƱ)
	 */
	public function check($subjectid){
		//��ѯ��ͶƱ����
 		$siteid = SITEID;
		$subject_arr = $this->vote->get_subject($subjectid);
		if($subject_arr['enabled']==0){
			showmessage(L('vote_votelocked'),"?m=vote&c=index&a=result&subjectid=$subjectid&siteid=$siteid");
 		}
 		if(date("Y-m-d",SYS_TIME)>$subject_arr['todate']){
			showmessage(L('vote_votepassed'),"?m=vote&c=index&a=result&subjectid=$subjectid&siteid=$siteid");
 		}
 		//�ο��Ƿ����ͶƱ
		if($subject_arr['allowguest']==0 ){
			if(!$this->username){
				showmessage(L('vote_votenoguest'),"?m=vote&c=index&a=result&subjectid=$subjectid&siteid=$siteid");
 			}elseif($this->groupid == '7'){
				showmessage('�Բ��𣬲������ʼ�����֤�û�ͶƱ��',"?m=vote&c=index&a=result&subjectid=$subjectid&siteid=$siteid");
			}
 		}
		
 		//�Ƿ���ͶƱ��¼ 
		$user_info = $this->vote_data->select(array('subjectid'=>$subjectid,'ip'=>$this->ip,'username'=>$this->username),'*','1',' time DESC'); 
		if(!$user_info){
			return 1;
		} else {
			if($subject_arr['interval']==0){
				return -1;
			}
			if($subject_arr['interval']>0){ 
 				$condition = (SYS_TIME - $user_info[0]['time'])/(24*3600)> $subject_arr['interval'] ? 1	: 0;
 				return $condition;
 			}
		}  
	}
	
}
?>