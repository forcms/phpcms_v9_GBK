<?php
/**
 * ���۲�����
 * @author chenzhouyu
 *
 */
class comment {
	//���ݿ�����
	private $comment_db, $comment_setting_db, $comment_data_db, $comment_table_db, $comment_check_db;
	
	public $msg_code = 0;
	
	public function __construct() {
		$this->comment_db = pc_base::load_model('comment_model');
		$this->comment_setting_db = pc_base::load_model('comment_setting_model');
		$this->comment_data_db = pc_base::load_model('comment_data_model');
		$this->comment_table_db = pc_base::load_model('comment_table_model');
		$this->comment_check_db = pc_base::load_model('comment_check_model');
	}
	
	/**
	 * �������
	 * @param string $commentid ����ID
	 * @param integer $siteid վ��ID
	 * @param array $data ��������Ӧ�ð���array('userid'=>�û�ID��'username'=>�û���,'content'=>����,'direction'=>����0:û�з��� ,1:����,2:����,3:������)
	 * @param string $id �ظ����۵�����
	 * @param string $title ���±���
	 * @param string $url ����URL��ַ
	 */
	public function add($commentid, $siteid, $data, $id = '', $title = '', $url = '') {
		//��ʼ��ѯ�������������Ƿ���ڡ�
		$title = new_addslashes($title);
		if (!$comment = $this->comment_db->get_one(array('commentid'=>$commentid, 'siteid'=>$siteid), 'tableid, commentid')) { //���۲�����
			//ȡ�õ�ǰ����ʹ�õ��������ݱ�
			$r = $this->comment_table_db->get_one('', 'tableid, total', 'tableid desc');
			$tableid = $r['tableid'];
			if ($r['total'] >= 1000000) {
				//����һ�����ݱ��������Ѿ��ﵽ1000000ʱ�������µ����ݴ洢���洢���ݡ�
				if (!$tableid = $this->comment_table_db->creat_table()) {
					$this->msg_code = 4;
					return false;
				}
			}
			//�½����۵������ܱ��С�
			$comment_data = array('commentid'=>$commentid, 'siteid'=>$siteid, 'tableid'=>$tableid, 'display_type'=>($data['direction']>0 ? 1 : 0));
			if (!empty($title)) $comment_data['title'] = $title;
			if (!empty($url)) $comment_data['url'] = $url;
			if (!$this->comment_db->insert($comment_data)) {
				$this->msg_code = 5;
				return false;
			}
		} else {//���۴���ʱ
			$tableid = $comment['tableid'];
		}
		if (empty($tableid)) {
			$this->msg_code = 1;
			return false;
		}
		//Ϊ���ݴ洢����ģ������ ���ݱ�����
		$this->comment_data_db->table_name($tableid);
		//������ݴ洢��
		if (!$this->comment_data_db->table_exists('comment_data_'.$tableid)) {
			//���洢���ݱ���ʱ�����Դ������ݱ�
			if (!$tableid = $this->comment_table_db->creat_table($tableid)) {
				$this->msg_code = 2;
				return false;
			}
		}
		//�����ݴ洢����д�����ݡ�	
		$data['commentid'] = $commentid;
		$data['siteid'] = $siteid;
		$data['ip'] = ip();
		$data['status'] = 1;
		$data['creat_at'] = SYS_TIME;
		//�����۵����ݽ��йؼ��ʹ��ˡ�
		$data['content'] = strip_tags($data['content']);
		$badword = pc_base::load_model('badword_model');
		$data['content'] = $badword->replace_badword($data['content']);
		if ($id) {
			$r = $this->comment_data_db->get_one(array('id'=>$id));
			if ($r) {
				pc_base::load_sys_class('format', '', 0);
				if ($r['reply']) {
					$data['content'] = '<div class="content">'.str_replace('<span></span>', '<span class="blue f12">'.$r['username'].' '.L('chez').' '.format::date($r['creat_at'], 1).L('release').'</span>', $r['content']).'</div><span></span>'.$data['content'];
				} else {
					$data['content'] = '<div class="content"><span class="blue f12">'.$r['username'].' '.L('chez').' '.format::date($r['creat_at'], 1).L('release').'</span><pre>'.$r['content'].'</pre></div><span></span>'.$data['content'];
				}
				$data['reply'] = 1;
			}
		}
		//�жϵ�ǰվ���Ƿ���Ҫ���
		$site = $this->comment_setting_db->site($siteid);
		if ($site['check']) {
			$data['status'] = 0;
		}
		if ($comment_data_id = $this->comment_data_db->insert($data, true)) {
			//��Ҫ��ˣ����뵽��˱�
			if ($data['status']==0) {
				$this->comment_check_db->insert(array('comment_data_id'=>$comment_data_id, 'siteid'=>$siteid,'tableid'=>$tableid));
			} elseif (!empty($data['userid']) && !empty($site['add_point']) && module_exists('pay')) { //����Ҫ���ֱ�Ӹ��û���ӻ���
				pc_base::load_app_class('receipts', 'pay', 0);
				receipts::point($site['add_point'], $data['userid'], $data['username'], '', 'selfincome', 'Comment');
			}
			//��ʼ�������ݴ洢������������
			$this->comment_table_db->edit_total($tableid, '+=1');
			//��ʼ���������ܱ���������
			$sql['lastupdate'] = SYS_TIME;
			//ֻ��������ͨ����ʱ��Ÿ������������������
			if ($data['status'] == 1) {
				$sql['total'] = '+=1';
				switch ($data['direction']) {
					case 1: //����
						$sql['square'] = '+=1';
						break;
					case 2://����
						$sql['anti'] = '+=1';
						break;
					case 3://������
						$sql['neutral'] = '+=1';
						break;
				}
			}
			$this->comment_db->update($sql, array('commentid'=>$commentid));
			if ($site['check']) {
				$this->msg_code = 7;
			} else {
				$this->msg_code = 0;
			}
			return true;
		} else {
			$this->msg_code = 3;
			return false;
		}
	}
	
	/**
	 * ֧������
	 * @param integer $commentid    ����ID
	 * @param integer $id           ����ID
	 */
	public function support($commentid, $id) {
		if ($data = $this->comment_db->get_one(array('commentid'=>$commentid), 'tableid')) {
			$this->comment_data_db->table_name($data['tableid']);
			if ($this->comment_data_db->update(array('support'=>'+=1'), array('id'=>$id))) {
				$this->msg_code = 0;
				return true;
			} else {
				$this->msg_code = 3;
				return false;
			}
		} else {
			$this->msg_code = 6;
			return false;
		}
	}
	
	/**
	 * �������۵�״̬
	 * @param string $commentid      ����ID 
	 * @param integer $id            ����ID
	 * @param integer $status        ״̬{1:ͨ�� ,0:δ��ˣ� -1:��ͨ��,����ɾ������}
	 */
	public function status($commentid, $id, $status = -1) {
		if (!$comment = $this->comment_db->get_one(array('commentid'=>$commentid), 'tableid, commentid')) {
			$this->msg_code = 6;
			return false;
		}
		
		//Ϊ���ݴ洢����ģ������ ���ݱ�����
		$this->comment_data_db->table_name($comment['tableid']);
		if (!$comment_data = $this->comment_data_db->get_one(array('id'=>$id, 'commentid'=>$commentid))) {
			$this->msg_code = 6;
			return false;
		}
		
		//��ȡ���۵�վ��������Ϣ
		$site = $this->comment_setting_db->get_one(array('siteid'=>$comment_data['siteid']));
		
		
		if ($status == 1) {//ͨ����ʱ��
			$sql['total'] = '+=1';
			switch ($comment_data['direction']) {
				case 1: //����
					$sql['square'] = '+=1';
					break;
				case 2://����
					$sql['anti'] = '+=1';
					break;
				case 3://������
					$sql['neutral'] = '+=1';
					break;
			}
			
			//�����۱�����Ϊͨ����ʱ�򣬸��������ܱ��������
			$this->comment_db->update($sql, array('commentid'=>$comment['commentid']));
			//������������״̬
			$this->comment_data_db->update(array('status'=>$status), array('id'=>$id, 'commentid'=>$commentid));
			
			//�������û�ID��Ϊ�գ�����վ�������˻�������֧��ģ��Ҳ���ڵ�ʱ��Ϊ�û���ӻ��֡�
			if (!empty($comment_data['userid']) && !empty($site['add_point']) && module_exists('pay')) {
				pc_base::load_app_class('receipts', 'pay', 0);
				receipts::point($site['add_point'], $comment_data['userid'], $comment_data['username'], '', 'selfincome', 'Comment');
			}
			
		} elseif ($status == -1) { //ɾ������
			//�������ԭ��״̬Ϊ�Ѿ�ͨ������Ҫɾ�������ܱ��е�����
			if ($comment_data['status'] == 1) {
				$sql['total'] = '-=1';
				switch ($comment_data['direction']) {
					case '1': //����
						$sql['square'] = '-=1';
						break;
					case '2'://����
						$sql['anti'] = '-=1';
						break;
					case '3'://������
						$sql['neutral'] = '-=1';
						break;
				}
				$this->comment_db->update($sql, array('commentid'=>$comment['commentid']));
			}
			
			//ɾ���洢�������
			$this->comment_data_db->delete(array('id'=>$id, 'commentid'=>$commentid));
			//ɾ���洢��������¼,�ж������Ƿ�Ϊ0����������ɾ���ˡ�
			$this->comment_table_db->edit_total($comment['tableid'], '-=1');
			
			//������ID��Ϊ�գ�վ��������ɾ���ĵ�����֧��ģ����ڵ�ʱ��ɾ���û��ĵ�����
			if (!empty($comment_data['userid']) && !empty($site['del_point']) && module_exists('pay')) {
				pc_base::load_app_class('spend', 'pay', 0);
				$op_userid = param::get_cookie('userid');
				$op_username = param::get_cookie('admin_username');
				spend::point($site['del_point'], L('comment_point_del', '', 'comment'), $comment_data['userid'], $comment_data['username'], $op_userid, $op_username);
			}
		}
		
		//ɾ����˱��е�����
		$this->comment_check_db->delete(array('comment_data_id'=>$id));
		
		$this->msg_code = 0;
		return true;
	}
	
	/**
	 * 
	 * ɾ������
	 * @param string $commentid ����ID
	 * @param intval $siteid վ��ID
	 * @param intval $id ����ID
	 * @param intval $catid ��ĿID
	 */
	public function del($commentid, $siteid, $id, $catid) {
		if ($commentid != id_encode('content_'.$catid, $id, $siteid)) return false;
		//ѭ���������ݱ�ɾ��commentid����������
		for ($i=1; ;$i++) {
			$table = 'comment_data_'.$i; //�����������ݴ洢����
			if ($this->comment_data_db->table_exists($table)) { //��鹹���ı����Ƿ���ڣ��������ִ��ɾ������
				$this->comment_data_db->table_name($i);
				$this->comment_data_db->delete(array('commentid'=>$commentid));
			} else { //�����ڣ����˳�ѭ��
				break;
			}
		}
		$this->comment_db->delete(array('commentid'=>$commentid)); //ɾ���������������
		return true;
	}
	
	/**
	 * 
	 * ��ȡ�������ϸ��Ϣ��
	 */
	public function get_error() {
		$msg = array('0'=>L('operation_success'),
		'1'=>L('coment_class_php_1'),
		'2'=>L('coment_class_php_2'),
		'3'=>L('coment_class_php_3'),
		'4'=>L('coment_class_php_4'),
		'5'=>L('coment_class_php_5'),
		'6'=>L('coment_class_php_6'),
		'7'=>L('coment_class_php_7'),
		);
		return $msg[$this->msg_code];
	}
}