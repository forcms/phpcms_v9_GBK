<?php
	/**
	 * ���ظ�������ͼ��
	 * @param $file ��������
	 * @param $type pngΪ��ͼ�꣬gifΪСͼ��
	 */
	function file_icon($file,$type = 'png') {
		$ext_arr = array('doc','docx','ppt','xls','txt','pdf','mdb','jpg','gif','png','bmp','jpeg','rar','zip','swf','flv');
		$ext = fileext($file);
		if($type == 'png') {
			if($ext == 'zip' || $ext == 'rar') $ext = 'rar';
			elseif($ext == 'doc' || $ext == 'docx') $ext = 'doc';
			elseif($ext == 'xls' || $ext == 'xlsx') $ext = 'xls';
			elseif($ext == 'ppt' || $ext == 'pptx') $ext = 'ppt';
			elseif ($ext == 'flv' || $ext == 'swf' || $ext == 'rm' || $ext == 'rmvb') $ext = 'flv';
			else $ext = 'do';
		}
		if(in_array($ext,$ext_arr)) return 'statics/images/ext/'.$ext.'.'.$type;
		else return 'statics/images/ext/blank.'.$type;
	}
	
	/**
	 * ����Ŀ¼�б���ʱû��
	 * @param $dirpath Ŀ¼·��
	 * @param $currentdir ��ǰĿ¼
	 */
	function file_list($dirpath,$currentdir) {
		$filepath = $dirpath.$currentdir;
		$list['list'] = glob($filepath.DIRECTORY_SEPARATOR.'*');
		if(!empty($list['list'])) rsort($list['list']);
		$list['local'] = str_replace(array(PC_PATH, DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR), array('',DIRECTORY_SEPARATOR), $filepath);
		return $list;
	}
	
	/**
	 * flash�ϴ���ʼ��
	 * ��ʼ��swfupload�ϴ�����Ҫ�Ĳ���
	 * @param $module ģ������
	 * @param $catid ��Ŀid
	 * @param $args ���ݲ���
	 * @param $userid �û�id
	 * @param $groupid �û���id
	 * @param $isadmin �Ƿ�Ϊ����Աģʽ
	 */
	function initupload($module, $catid,$args, $userid, $groupid = '8', $isadmin = '0'){
		$grouplist = getcache('grouplist','member');
		if($isadmin==0 && !$grouplist[$groupid]['allowattachment']) return false;
		extract(getswfinit($args));
		$siteid = param::get_cookie('siteid');
		$site_setting = get_site_setting($siteid);
		$file_size_limit = $site_setting['upload_maxsize'];
		$sess_id = SYS_TIME;
		$swf_auth_key = md5(pc_base::load_config('system','auth_key').$sess_id);
		$init =  'var swfu = \'\';
		$(document).ready(function(){
		swfu = new SWFUpload({
			flash_url:"'.JS_PATH.'swfupload/swfupload.swf?"+Math.random(),
			upload_url:"'.APP_PATH.'index.php?m=attachment&c=attachments&a=swfupload&dosubmit=1",
			file_post_name : "Filedata",
			post_params:{"SWFUPLOADSESSID":"'.$sess_id.'","module":"'.$module.'","catid":"'.$_GET['catid'].'","userid":"'.$userid.'","siteid":"'.$siteid.'","dosubmit":"1","thumb_width":"'.$thumb_width.'","thumb_height":"'.$thumb_height.'","watermark_enable":"'.$watermark_enable.'","filetype_post":"'.$file_types_post.'","swf_auth_key":"'.$swf_auth_key.'","isadmin":"'.$isadmin.'","groupid":"'.$groupid.'"},
			file_size_limit:"'.$file_size_limit.'",
			file_types:"'.$file_types.'",
			file_types_description:"All Files",
			file_upload_limit:"'.$file_upload_limit.'",
			custom_settings : {progressTarget : "fsUploadProgress",cancelButtonId : "btnCancel"},
	 
			button_image_url: "",
			button_width: 75,
			button_height: 28,
			button_placeholder_id: "buttonPlaceHolder",
			button_text_style: "",
			button_text_top_padding: 3,
			button_text_left_padding: 12,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,

			file_dialog_start_handler : fileDialogStart,
			file_queued_handler : fileQueued,
			file_queue_error_handler:fileQueueError,
			file_dialog_complete_handler:fileDialogComplete,
			upload_progress_handler:uploadProgress,
			upload_error_handler:uploadError,
			upload_success_handler:uploadSuccess,
			upload_complete_handler:uploadComplete
			});
		})';
		return $init;
	}		
	/**
	 * ��ȡվ��������Ϣ
	 * @param  $siteid վ��id
	 */
	function get_site_setting($siteid) {
		$siteinfo = getcache('sitelist', 'commons');
		return string2array($siteinfo[$siteid]['setting']);
	}
	/**
	 * ��ȡswfupload��������
	 * @param array $args flash�ϴ�������Ϣ
	 */
	function getswfinit($args) {
		$siteid = get_siteid();
		$site_setting = get_site_setting($siteid);
		$site_allowext = $site_setting['upload_allowext'];
		$args = explode(',',$args);
		$arr['file_upload_limit'] = intval($args[0]) ? intval($args[0]) : '8';
		$args['1'] = ($args[1]!='') ? $args[1] : $site_allowext;
		$arr_allowext = explode('|', $args[1]);
		foreach($arr_allowext as $k=>$v) {
			$v = '*.'.$v;
			$array[$k] = $v;
		}
		$upload_allowext = implode(';', $array);
		$arr['file_types'] = $upload_allowext;
		$arr['file_types_post'] = $args[1];
		$arr['allowupload'] = intval($args[2]);
		$arr['thumb_width'] = intval($args[3]);
		$arr['thumb_height'] = intval($args[4]);
		$arr['watermark_enable'] = ($args[5]=='') ? 1 : intval($args[5]);
		return $arr;
	}	
	/**
	 * �ж��Ƿ�ΪͼƬ
	 */
	function is_image($file) {
		$ext_arr = array('jpg','gif','png','bmp','jpeg','tiff');
		$ext = fileext($file);
		return in_array($ext,$ext_arr) ? $ext_arr :false;
	}
	
	/**
	 * �ж��Ƿ�Ϊ��Ƶ
	 */
	function is_video($file) {
		$ext_arr = array('rm','mpg','avi','mpeg','wmv','flv','asf','rmvb');
		$ext = fileext($file);
		return in_array($ext,$ext_arr) ? $ext_arr :false;
	}

?>