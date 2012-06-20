<?php

class messagequeue {
	
	private $db;
	
	private static function get_db() {
		return pc_base::load_model('messagequeue_model');
	}
	
	/**
	 * ��Ӷ�����Ϣ
	 */
	public static function add($operation, $noticedata_send) {
		$db = self::get_db();
		$noticedata_send['action'] = $operation;
		$noticedata_send_string = array2string($noticedata_send);
		
		if ($noticeid = $db->insert(array('operation'=>$operation, 'noticedata'=>$noticedata_send_string, 'dateline'=>SYS_TIME), 1)) {
			self::notice($operation, $noticedata_send, $noticeid);
			return 1;
		} else {
			return 0;
		}
	}

	/**
	 * ֪ͨӦ��
	 */
	public static function notice($operation, $noticedata, $noticeid) {
		$db = self::get_db();
		$applist = getcache('applist', 'admin');
		foreach($applist as $k=>$v) {
			//���ڱ���ת����ı�notice_send��ֵ������ÿ��ѭ����Ҫ���¸�ֵnoticedate_send
			$noticedata_send = $noticedata;
			
			//Ӧ������û�ʱ���ظ�֪ͨ��Ӧ��
			if(isset($noticedata_send['appname']) && $noticedata_send['appname'] == $v['name']) {
				$appstatus[$k] = 1;
				continue;
			}
			
			$url = $v['url'].$v['apifilename'];

			if (CHARSET != $v['charset'] && isset($noticedata_send['action']) && $noticedata_send['action'] == 'member_add') {
				if(isset($noticedata_send['username']) && !empty($noticedata_send['username'])) {
					if(CHARSET == 'utf-8') {	//�ж�phpsso�ַ����Ƿ�Ϊutf-8����
						//Ӧ���ַ��������utf-8�������û�����utf-8���룬ת���û���Ϊphpsso�ַ��������ΪӢ�ģ�is_utf8����false��������ת��
						if(!is_utf8($noticedata_send['username'])) {
							$noticedata_send['username'] = iconv(CHARSET, $v['charset'], $noticedata_send['username']);
						}
					} else {
						if(!is_utf8($noticedata_send['username'])) {
							$noticedata_send['username'] = iconv(CHARSET, $v['charset'], $noticedata_send['username']);
						}
					}
				}
			}
			$tmp_s = strstr($url, '?') ? '&' : '?';
			$status = ps_send($url.$tmp_s.'appid='.$k, $noticedata_send, $v['authkey']);
			if ($status == 1) {
				$appstatus[$k] = 1;
			} else {
				$appstatus[$k] = 0;
			}
		}

		$db->update(array('totalnum'=>'+=1', 'appstatus'=>json_encode($appstatus)), array('id'=>$noticeid));
	}
}
?>