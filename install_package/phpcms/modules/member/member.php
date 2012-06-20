<?php
/**
 * ����Ա��̨��Ա������
 */

defined('IN_PHPCMS') or exit('No permission resources.');
//ģ�ͻ���·��
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);

pc_base::load_app_class('admin', 'admin', 0);
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);
pc_base::load_app_func('util', 'content');

class member extends admin {
	
	private $db, $verify_db;
	
	function __construct() {
		parent::__construct();
		$this->db = pc_base::load_model('member_model');
		$this->_init_phpsso();
	}

	/**
	 * defalut
	 */
	function init() {
		$show_header = $show_scroll = true;
		pc_base::load_sys_class('form', '', 0);
		$this->verify_db = pc_base::load_model('member_verify_model');
		
		//������
		$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		$groupid = isset($_GET['groupid']) ? $_GET['groupid'] : '';
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : date('Y-m-d', SYS_TIME-date('t', SYS_TIME)*86400);
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : date('Y-m-d', SYS_TIME);
		$grouplist = getcache('grouplist');
		foreach($grouplist as $k=>$v) {
			$grouplist[$k] = $v['name'];
		}

		$memberinfo['totalnum'] = $this->db->count();
		$memberinfo['vipnum'] = $this->db->count(array('vip'=>1));
		$memberinfo['verifynum'] = $this->verify_db->count(array('status'=>0));

		$todaytime = strtotime(date('Y-m-d', SYS_TIME));
		$memberinfo['today_member'] = $this->db->count("`regdate` > '$todaytime'");
		
		include $this->admin_tpl('member_init');
	}
	
	/**
	 * ��Ա����
	 */
	function search() {

		//������
		$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		$groupid = isset($_GET['groupid']) ? $_GET['groupid'] : '';
		$modelid = isset($_GET['modelid']) ? $_GET['modelid'] : '';
		
		//վ����Ϣ
		$sitelistarr = getcache('sitelist', 'commons');
		$siteid = isset($_GET['siteid']) ? intval($_GET['siteid']) : '0';
		foreach ($sitelistarr as $k=>$v) {
			$sitelist[$k] = $v['name'];
		}
		
		$status = isset($_GET['status']) ? $_GET['status'] : '';
		$amount_from = isset($_GET['amount_from']) ? $_GET['amount_from'] : '';
		$amount_to = isset($_GET['amount_to']) ? $_GET['amount_to'] : '';
		$point_from = isset($_GET['point_from']) ? $_GET['point_from'] : '';
		$point_to = isset($_GET['point_to']) ? $_GET['point_to'] : '';
				
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : date('Y-m-d', SYS_TIME);
		$grouplist = getcache('grouplist');
		foreach($grouplist as $k=>$v) {
			$grouplist[$k] = $v['name'];
		}
		//��Ա����ģ��		
		$modellistarr = getcache('member_model', 'commons');
		foreach ($modellistarr as $k=>$v) {
			$modellist[$k] = $v['name'];
		}
				
		if (isset($_GET['search'])) {
			
			//Ĭ��ѡȡһ�����ڵ��û�����ֹ�û�������������������
			$where_start_time = strtotime($start_time) ? strtotime($start_time) : 0;
			$where_end_time = strtotime($end_time) + 86400;
			//��ʼʱ����ڽ���ʱ�䣬�û�����
			if($where_start_time > $where_end_time) {
				$tmp = $where_start_time;
				$where_start_time = $where_end_time;
				$where_end_time = $tmp;
				$tmptime = $start_time;
				
				$start_time = $end_time;
				$end_time = $tmptime;
				unset($tmp, $tmptime);
			}
			
			
			$where = '';
			
			//����ǳ�������Ա��ɫ����ʾ�����û���������ʾ��ǰվ���û�
			if($_SESSION['roleid'] == 1) {
				if(!empty($siteid)) {
					$where .= "`siteid` = '$siteid' AND ";
				}
			} else {
				$siteid = get_siteid();
				$where .= "`siteid` = '$siteid' AND ";
			}
				
			if($status) {
				$islock = $status == 1 ? 1 : 0;
				$where .= "`islock` = '$islock' AND ";
			}
		
			if($groupid) {
				$where .= "`groupid` = '$groupid' AND ";
			}
			
			if($modelid) {
				$where .= "`modelid` = '$modelid' AND ";
			}	
			$where .= "`regdate` BETWEEN '$where_start_time' AND '$where_end_time' AND ";

			//�ʽ�Χ
			if($amount_from) {
				if($amount_to) {
					if($amount_from > $amount_to) {
						$tmp = $amount_from;
						$amount_from = $amount_to;
						$amount_to = $tmp;
						unset($tmp);
					}
					$where .= "`amount` BETWEEN '$amount_from' AND '$amount_to' AND ";
				} else {
					$where .= "`amount` > '$amount_from' AND ";
				}
			}
			//������Χ
			if($point_from) {
				if($point_to) {
					if($point_from > $point_to) {
						$tmp = $amount_from;
						$point_from = $point_to;
						$point_to = $tmp;
						unset($tmp);
					}
					$where .= "`point` BETWEEN '$point_from' AND '$point_to' AND ";
				} else {
					$where .= "`point` > '$point_from' AND ";
				}
			}
		
			if($keyword) {
				if ($type == '1') {
					$where .= "`username` LIKE '%$keyword%'";
				} elseif($type == '2') {
					$where .= "`userid` = '$keyword'";
				} elseif($type == '3') {
					$where .= "`email` like '%$keyword%'";
				} elseif($type == '4') {
					$where .= "`regip` = '$keyword'";
				} elseif($type == '5') {
					$where .= "`nickname` LIKE '%$keyword%'";
				} else {
					$where .= "`username` like '%$keyword%'";
				}
			} else {
				$where .= '1';
			}
			
		} else {
			$where = '';
		}

		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$memberlist = $this->db->listinfo($where, 'userid DESC', $page, 15);
		$pages = $this->db->pages;
		$big_menu = array('?m=member&c=member&a=manage&menuid=72', L('member_research'));
		include $this->admin_tpl('member_list');
	}
	
	/**
	 * member list
	 */
	function manage() {
		$sitelistarr = getcache('sitelist', 'commons');
		foreach ($sitelistarr as $k=>$v) {
			$sitelist[$k] = $v['name'];
		}
	
		$groupid = isset($_GET['groupid']) ? intval($_GET['groupid']) : '';
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		
		//����ǳ�������Ա��ɫ����ʾ�����û���������ʾ��ǰվ���û�
		if($_SESSION['roleid'] == 1) {
			$where = '';
		} else {
			$siteid = get_siteid();
			$where .= "`siteid` = '$siteid'";
		}
		
		$memberlist_arr = $this->db->listinfo($where, 'userid DESC', $page, 15);
		$pages = $this->db->pages;

		//������
		$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		$start_time = isset($_GET['start_time']) ? $_GET['start_time'] : '';
		$end_time = isset($_GET['end_time']) ? $_GET['end_time'] : date('Y-m-d', SYS_TIME);
		$grouplist = getcache('grouplist');
		foreach($grouplist as $k=>$v) {
			$grouplist[$k] = $v['name'];
		}
		
		//��Ա����ģ��		
		$modellistarr = getcache('member_model', 'commons');
		foreach ($modellistarr as $k=>$v) {
			$modellist[$k] = $v['name'];
		}
		
		//��ѯ��Աͷ��
		foreach($memberlist_arr as $k=>$v) {
			$memberlist[$k] = $v;
			$memberlist[$k]['avatar'] = get_memberavatar($v['phpssouid']);
		}

		$big_menu = array('javascript:window.top.art.dialog({id:\'add\',iframe:\'?m=member&c=member&a=add\', title:\''.L('member_add').'\', width:\'700\', height:\'500\', lock:true}, function(){var d = window.top.art.dialog({id:\'add\'}).data.iframe;var form = d.document.getElementById(\'dosubmit\');form.click();return false;}, function(){window.top.art.dialog({id:\'add\'}).close()});void(0);', L('member_add'));
		include $this->admin_tpl('member_list');
	}
		
	/**
	 * add member
	 */
	function add() {
		header("Cache-control: private");
		if(isset($_POST['dosubmit'])) {
			$info = array();
			if(!$this->_checkname($_POST['info']['username'])){
				showmessage(L('member_exist'));
			}
			$info = $this->_checkuserinfo($_POST['info']);
			if(!$this->_checkpasswd($info['password'])){
				showmessage(L('password_format_incorrect'));
			}
			$info['regip'] = ip();
			$info['overduedate'] = strtotime($info['overduedate']);

			$status = $this->client->ps_member_register($info['username'], $info['password'], $info['email'], $info['regip']);

			if($status > 0) {
				unset($info[pwdconfirm]);
				$info['phpssouid'] = $status;
				//ȡphpsso���������
				$memberinfo = $this->client->ps_get_member_info($status);
				$memberinfo = unserialize($memberinfo);
				$info['encrypt'] = $memberinfo['random'];
				$info['password'] = password($info['password'], $info['encrypt']);
				$info['regdate'] = $info['lastdate'] = SYS_TIME;
				
				$this->db->insert($info);
				if($this->db->insert_id()){
					showmessage(L('operation_success'),'?m=member&c=member&a=add', '', 'add');
				}
			} elseif($status == -4) {
				showmessage(L('username_deny'), HTTP_REFERER);
			} elseif($status == -5) {
				showmessage(L('email_deny'), HTTP_REFERER);
			} else {
				showmessage(L('operation_failure'), HTTP_REFERER);
			}
		} else {
			$show_header = $show_scroll = true;
			$siteid = get_siteid();
			//��Ա�黺��
			$group_cache = getcache('grouplist', 'member');
			foreach($group_cache as $_key=>$_value) {
				$grouplist[$_key] = $_value['name'];
			}
			//��Աģ�ͻ���
			$member_model_cache = getcache('member_model', 'commons');
			foreach($member_model_cache as $_key=>$_value) {
				if($siteid == $_value['siteid']) {
					$modellist[$_key] = $_value['name'];
				}
			}
			
			include $this->admin_tpl('member_add');
		}
		
	}
	
	/**
	 * edit member
	 */
	function edit() {
		if(isset($_POST['dosubmit'])) {
			$memberinfo = $info = array();
			$basicinfo['userid'] = $_POST['info']['userid'];
			$basicinfo['username'] = $_POST['info']['username'];
			$basicinfo['nickname'] = $_POST['info']['nickname'];
			$basicinfo['email'] = $_POST['info']['email'];
			$basicinfo['point'] = $_POST['info']['point'];
			$basicinfo['password'] = $_POST['info']['password'];
			$basicinfo['groupid'] = $_POST['info']['groupid'];
			$basicinfo['modelid'] = $_POST['info']['modelid'];
			$basicinfo['vip'] = $_POST['info']['vip'];
			$basicinfo['overduedate'] = strtotime($_POST['info']['overduedate']);

			//��Ա������Ϣ
			$info = $this->_checkuserinfo($basicinfo, 1);

			//��Աģ����Ϣ
			$modelinfo = array_diff_key($_POST['info'], $info);
			//����vip����ʱ��
			unset($modelinfo['overduedate']);
			unset($modelinfo['pwdconfirm']);

			$userid = $info['userid'];
			
			//����ǳ�������Ա��ɫ����ʾ�����û���������ʾ��ǰվ���û�
			if($_SESSION['roleid'] == 1) {
				$where = array('userid'=>$userid);
			} else {
				$siteid = get_siteid();
				$where = array('userid'=>$userid, 'siteid'=>$siteid);
			}
			
		
			$userinfo = $this->db->get_one($where);
			if(empty($userinfo)) {
				showmessage(L('user_not_exist').L('or').L('no_permission'), HTTP_REFERER);
			}

			//ɾ���û�ͷ��
			if(!empty($_POST['delavatar'])) {
				$this->client->ps_deleteavatar($userinfo['phpssouid']);
			}

			$status = $this->client->ps_member_edit($info['username'], $info['email'], '', $info['password'], $userinfo['phpssouid'], $userinfo['encrypt']);
  			if($status >= 0) { 
				unset($info['userid']);
				unset($info['username']);
				
				//������벻Ϊ�գ��޸��û����롣
				if(isset($info['password']) && !empty($info['password'])) {
					$info['password'] = password($info['password'], $userinfo['encrypt']);
				} else {
					unset($info['password']);
				}

				$this->db->update($info, array('userid'=>$userid));
				
				require_once CACHE_MODEL_PATH.'member_input.class.php';
		        require_once CACHE_MODEL_PATH.'member_update.class.php';
				$member_input = new member_input($basicinfo['modelid']);
				$modelinfo = $member_input->get($modelinfo);

				//����ģ�ͱ�����������$this->table
				$this->db->set_model($info['modelid']);
				$userinfo = $this->db->get_one(array('userid'=>$userid));
				if($userinfo) {
					$this->db->update($modelinfo, array('userid'=>$userid));
				} else {
					$modelinfo['userid'] = $userid;
					$this->db->insert($modelinfo);
				}
				
				showmessage(L('operation_success'), '?m=member&c=member&a=manage', '', 'edit');
			} else {
				showmessage(L('operation_failure'), HTTP_REFERER);
			}
		} else {
			$show_header = $show_scroll = true;
			$siteid = get_siteid();
			$userid = isset($_GET['userid']) ? $_GET['userid'] : showmessage(L('illegal_parameters'), HTTP_REFERER);
			
			//��Ա�黺��
			$group_cache = getcache('grouplist', 'member');
			foreach($group_cache as $_key=>$_value) {
				$grouplist[$_key] = $_value['name'];
			}

			//��Աģ�ͻ���
			$member_model_cache = getcache('member_model', 'commons');
			foreach($member_model_cache as $_key=>$_value) {
				if($siteid == $_value['siteid']) {
					$modellist[$_key] = $_value['name'];
				}
			}
			
			//����ǳ�������Ա��ɫ����ʾ�����û���������ʾ��ǰվ���û�
			if($_SESSION['roleid'] == 1) {
				$where = array('userid'=>$userid);
			} else {
				$where = array('userid'=>$userid, 'siteid'=>$siteid);
			}

			$memberinfo = $this->db->get_one($where);
			
			if(empty($memberinfo)) {
				showmessage(L('user_not_exist').L('or').L('no_permission'), HTTP_REFERER);
			}
			
			$memberinfo['avatar'] = get_memberavatar($memberinfo['phpssouid'], '', 90);
			
			$modelid = isset($_GET['modelid']) ? $_GET['modelid'] : $memberinfo['modelid'];
			
			//��ȡ��Աģ�ͱ�
			require CACHE_MODEL_PATH.'member_form.class.php';
			$member_form = new member_form($modelid);
			
			$form_overdudate = form::date('info[overduedate]', date('Y-m-d H:i:s',$memberinfo['overduedate']), 1);
			$this->db->set_model($modelid);
			$membermodelinfo = $this->db->get_one(array('userid'=>$userid));
			$forminfos = $forminfos_arr = $member_form->get($membermodelinfo);
			
			//�����ֶι���
			foreach($forminfos as $field=>$info) {
				if($info['isomnipotent']) {
					unset($forminfos[$field]);
				} else {
					if($info['formtype']=='omnipotent') {
						foreach($forminfos_arr as $_fm=>$_fm_value) {
							if($_fm_value['isomnipotent']) {
								$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
							}
						}
						$forminfos[$field]['form'] = $info['form'];
					}
				}
			}
			$show_dialog = 1;
			include $this->admin_tpl('member_edit');		
		}
	}
	
	/**
	 * delete member
	 */
	function delete() {
		$uidarr = isset($_POST['userid']) ? $_POST['userid'] : showmessage(L('illegal_parameters'), HTTP_REFERER);
		$where = to_sqls($uidarr, '', 'userid');
		$phpsso_userinfo = $this->db->listinfo($where);
		$phpssouidarr = array();
		if(is_array($phpsso_userinfo)) {
			foreach($phpsso_userinfo as $v) {
				if(!empty($v['phpssouid'])) {
					$phpssouidarr[] = $v['phpssouid'];
				}
			}
		}
		//��ѯ�û���Ϣ
		$userinfo_arr = $this->db->select($where, "userid, modelid");
		$userinfo = array();
		if(is_array($userinfo_arr)) {
			foreach($userinfo_arr as $v) {
				$userinfo[$v['userid']] = $v['modelid'];
			}
		}
		//delete phpsso member first
		if(!empty($phpssouidarr)) {
			$status = $this->client->ps_delete_member($phpssouidarr, 1);
			if($status > 0) {
				if ($this->db->delete($where)) {
					
					//ɾ���û�ģ���û�����
					foreach($uidarr as $v) {
						if(!empty($userinfo[$v])) {
							$this->db->set_model($userinfo[$v]);
							$this->db->delete(array('userid'=>$v));
						}
					}
				
					showmessage(L('operation_success'), HTTP_REFERER);
				} else {
					showmessage(L('operation_failure'), HTTP_REFERER);
				}
			} else {
				showmessage(L('operation_failure'), HTTP_REFERER);
			}
		} else {
			if ($this->db->delete($where)) {
				showmessage(L('operation_success'), HTTP_REFERER);
			} else {
				showmessage(L('operation_failure'), HTTP_REFERER);
			}
		}
	}

	/**
	 * lock member
	 */
	function lock() {
		if(isset($_POST['userid'])) {
			$uidarr = isset($_POST['userid']) ? $_POST['userid'] : showmessage(L('illegal_parameters'), HTTP_REFERER);
			$where = to_sqls($uidarr, '', 'userid');
			$this->db->update(array('islock'=>1), $where);
			showmessage(L('member_lock').L('operation_success'), HTTP_REFERER);
		} else {
			showmessage(L('operation_failure'), HTTP_REFERER);
		}
	}
	
	/**
	 * unlock member
	 */
	function unlock() {
		if(isset($_POST['userid'])) {
			$uidarr = isset($_POST['userid']) ? $_POST['userid'] : showmessage(L('illegal_parameters'), HTTP_REFERER);
			$where = to_sqls($uidarr, '', 'userid');
			$this->db->update(array('islock'=>0), $where);
			showmessage(L('member_unlock').L('operation_success'), HTTP_REFERER);
		} else {
			showmessage(L('operation_failure'), HTTP_REFERER);
		}
	}

	/**
	 * move member
	 */
	function move() {
		if(isset($_POST['dosubmit'])) {
			$uidarr = isset($_POST['userid']) ? $_POST['userid'] : showmessage(L('illegal_parameters'), HTTP_REFERER);
			$groupid = isset($_POST['groupid']) && !empty($_POST['groupid']) ? $_POST['groupid'] : showmessage(L('illegal_parameters'), HTTP_REFERER);
			
			$where = to_sqls($uidarr, '', 'userid');
			$this->db->update(array('groupid'=>$groupid), $where);
			showmessage(L('member_move').L('operation_success'), HTTP_REFERER, '', 'move');
		} else {
			$show_header = $show_scroll = true;
			$grouplist = getcache('grouplist');
			foreach($grouplist as $k=>$v) {
				$grouplist[$k] = $v['name'];
			}
			
			$ids = isset($_GET['ids']) ? explode(',', $_GET['ids']): showmessage(L('illegal_parameters'), HTTP_REFERER);
			array_pop($ids);
			if(!empty($ids)) {
				$where = to_sqls($ids, '', 'userid');
				$userarr = $this->db->listinfo($where);
			} else {
				showmessage(L('illegal_parameters'), HTTP_REFERER, '', 'move');
			}
			
			include $this->admin_tpl('member_move');
		}
	}

	function memberinfo() {
		$show_header = false;
		
		$userid = !empty($_GET['userid']) ? intval($_GET['userid']) : '';
		$username = !empty($_GET['username']) ? trim($_GET['username']) : '';
		if(!empty($userid)) {
			$memberinfo = $this->db->get_one(array('userid'=>$userid));
		} elseif(!empty($username)) {
			$memberinfo = $this->db->get_one(array('username'=>$username));
		} else {
			showmessage(L('illegal_parameters'), HTTP_REFERER);
		}
		
		if(empty($memberinfo)) {
			showmessage(L('user').L('not_exists'), HTTP_REFERER);
		}
		
		$memberinfo['avatar'] = get_memberavatar($memberinfo['phpssouid'], '', 90);

		$grouplist = getcache('grouplist');
		//��Աģ�ͻ���
		$modellist = getcache('member_model', 'commons');

		$modelid = !empty($_GET['modelid']) ? intval($_GET['modelid']) : $memberinfo['modelid'];
		//վȺ����
		$sitelist =getcache('sitelist', 'commons');

		$this->db->set_model($modelid);
		$member_modelinfo = $this->db->get_one(array('userid'=>$userid));
		//ģ���ֶ�����
		$model_fieldinfo = getcache('model_field_'.$modelid, 'model');
	
		//ͼƬ�ֶ���ʾͼƬ
		foreach($model_fieldinfo as $k=>$v) {
			if($v['formtype'] == 'image') {
				$member_modelinfo[$k] = "<a href='.$member_modelinfo[$k].' target='_blank'><img src='.$member_modelinfo[$k].' height='40' widht='40' onerror=\"this.src='$phpsso_api_url/statics/images/member/nophoto.gif'\"></a>";
			} elseif($v['formtype'] == 'images') {
				$tmp = string2array($member_modelinfo[$k]);
				$member_modelinfo[$k] = '';
				if(is_array($tmp)) {
					foreach ($tmp as $tv) {
						$member_modelinfo[$k] .= " <a href='$tv[url]' target='_blank'><img src='$tv[url]' height='40' widht='40' onerror=\"this.src='$phpsso_api_url/statics/images/member/nophoto.gif'\"></a>";
					}
					unset($tmp);
				}
			} elseif($v['formtype'] == 'box') {	//box�ֶΣ���ȡ�ֶ����ƺ�ֵ������
				$tmp = explode("\n",$v['options']);
				if(is_array($tmp)) {
					foreach($tmp as $boxv) {
						$box_tmp_arr = explode('|', trim($boxv));
						if(is_array($box_tmp_arr) && isset($box_tmp_arr[1]) && isset($box_tmp_arr[0])) {
							$box_tmp[$box_tmp_arr[1]] = $box_tmp_arr[0];
							$tmp_key = intval($member_modelinfo[$k]);
						}
					}
				}
				if(isset($box_tmp[$tmp_key])) {
					$member_modelinfo[$k] = $box_tmp[$tmp_key];
				} else {
					$member_modelinfo[$k] = $member_modelinfo_arr[$k];
				}
				unset($tmp, $tmp_key, $box_tmp, $box_tmp_arr);
			} elseif($v['formtype'] == 'linkage') {	//���Ϊ�����˵�
				$tmp = string2array($v['setting']);
				$tmpid = $tmp['linageid'];
				$linkagelist = getcache($tmpid, 'linkage');
				$fullname = $this->_get_linkage_fullname($member_modelinfo[$k], $linkagelist);
				
				$member_modelinfo[$v['name']] = substr($fullname, 0, -1);
				unset($tmp, $tmpid, $linkagelist, $fullname);
			} else {
				$member_modelinfo[$k] = $member_modelinfo[$k];
			}
		}

		$member_fieldinfo = array();
		//��������keyֵ
		foreach($model_fieldinfo as $v) {
			if(!empty($member_modelinfo) && array_key_exists($v['field'], $member_modelinfo)) {
				$tmp = $member_modelinfo[$v['field']];
				unset($member_modelinfo[$v['field']]);
				$member_fieldinfo[$v['name']] = $tmp;
				unset($tmp);
			} else {
				$member_fieldinfo[$v['name']] = '';
			}
		}

		include $this->admin_tpl('member_moreinfo');
	}

	/*
	 * ͨ��linkageid��ȡ����·��
	 */
	private function _get_linkage_fullname($linkageid,  $linkagelist) {
		$fullname = '';
		if($linkagelist['data'][$linkageid]['parentid'] != 0) {
			$fullname = $this->_get_linkage_fullname($linkagelist['data'][$linkageid]['parentid'], $linkagelist);
		}
		//���ڵ�������
		$return = $fullname.$linkagelist['data'][$linkageid]['name'].'>';
		return $return;
	}
	
	private function _checkuserinfo($data, $is_edit=0) {
		if(!is_array($data)){
			showmessage(L('need_more_param'));return false;
		} elseif (!is_username($data['username']) && !$is_edit){
			showmessage(L('username_format_incorrect'));return false;
		} elseif (!isset($data['userid']) && $is_edit) {
			showmessage(L('username_format_incorrect'));return false;
		}  elseif (empty($data['email']) || !is_email($data['email'])){
			showmessage(L('email_format_incorrect'));return false;
		}
		return $data;
	}
		
	private function _checkpasswd($password){
		if (!is_password($password)){
			return false;
		}
		return true;
	}
	
	private function _checkname($username) {
		$username =  trim($username);
		if ($this->db->get_one(array('username'=>$username))){
			return false;
		}
		return true;
	}
	
	/**
	 * ��ʼ��phpsso
	 * about phpsso, include client and client configure
	 * @return string phpsso_api_url phpsso��ַ
	 */
	private function _init_phpsso() {
		pc_base::load_app_class('client', '', 0);
		define('APPID', pc_base::load_config('system', 'phpsso_appid'));
		$phpsso_api_url = pc_base::load_config('system', 'phpsso_api_url');
		$phpsso_auth_key = pc_base::load_config('system', 'phpsso_auth_key');
		$this->client = new client($phpsso_api_url, $phpsso_auth_key);
		return $phpsso_api_url;
	}
	
	/**
	 * ����û���
	 * @param string $username	�û���
	 * @return $status {-4���û�����ֹע��;-1:�û����Ѿ����� ;1:�ɹ�}
	 */
	public function public_checkname_ajax() {
		$username = isset($_GET['username']) && trim($_GET['username']) ? trim($_GET['username']) : exit(0);
		if(CHARSET != 'utf-8') {
			$username = iconv('utf-8', CHARSET, $username);
			$username = addslashes($username);
		}

		$status = $this->client->ps_checkname($username);
			
		if($status == -4 || $status == -1) {
			exit('0');
		} else {
			exit('1');
		}
		
	}
	
	/**
	 * �������
	 * @param string $email
	 * @return $status {-1:email�Ѿ����� ;-5:�����ֹע��;1:�ɹ�}
	 */
	public function public_checkemail_ajax() {
		$email = isset($_GET['email']) && trim($_GET['email']) ? trim($_GET['email']) : exit(0);
		
		$status = $this->client->ps_checkemail($email);
		if($status == -5) {	//��ֹע��
			exit('0');
		} elseif($status == -1) {	//�û����Ѵ��ڣ������޸��û���ʱ����Ҫ�ж������Ƿ��ǵ�ǰ�û���
			if(isset($_GET['phpssouid'])) {	//�޸��û�����phpssouid
				$status = $this->client->ps_get_member_info($email, 3);
				if($status) {
					$status = unserialize($status);	//�ӿڷ������л��������ж�
					if (isset($status['uid']) && $status['uid'] == intval($_GET['phpssouid'])) {
						exit('1');
					} else {
						exit('0');
					}
				} else {
					exit('0');
				}
			} else {
				exit('0');
			}
		} else {
			exit('1');
		}
	}
	
}
?>