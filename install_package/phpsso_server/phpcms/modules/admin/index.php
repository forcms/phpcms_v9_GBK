<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);

class index extends admin {
	
	private $db, $messagequeue_db;
	/**
	 * ��������
	 */
	public function __construct() {	
		parent::__construct();
	}
	
	public function init() {
		$userinfo = $this->get_userinfo();		
		include $this->admin_tpl('index');
	}

	public function right() {
		$this->member_db = pc_base::load_model('member_model');
		$this->messagequeue_db = pc_base::load_model('messagequeue_model');
		
		$total_member = $this->member_db->count();	//��Ա����
		
		$todaytime = strtotime(date('Y-m-d', SYS_TIME));	//���ջ�Ա��
		$today_member = $this->member_db->count("`regdate` > '$todaytime'");
		$total_messagequeue = $this->messagequeue_db->count();	//��Ϣ����
		
		$mysql_version = $this->member_db->get_version();	//mysql�汾
		
		$mysql_table_status = $this->member_db->get_table_status();
		$mysql_table_size = $mysql_table_index_size = '';
		foreach($mysql_table_status as $table) {
			$mysql_table_size += $table['Data_length'];
			$mysql_table_index_size += $table['Index_length'];
		}
		$mysql_table_size = sizecount($mysql_table_size);
		$mysql_table_index_size = sizecount($mysql_table_index_size);

		//Ӧ�ø���
		$applist = getcache('applist');
		$appnum = empty($applist) ? 0 : count($applist);
		
		include $this->admin_tpl('right');
	}
}
?>