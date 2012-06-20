<?php
/**
 * ����Ա��̨��Ա���Ĳ˵�������
 */

defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin', 'admin', 0);

class member_menu extends admin {
	function __construct() {
		parent::__construct();
		$this->db = pc_base::load_model('member_menu_model');
	}
	
	function manage() {
		$tree = pc_base::load_sys_class('tree');
		$tree->icon = array('&nbsp;&nbsp;&nbsp;�� ','&nbsp;&nbsp;&nbsp;���� ','&nbsp;&nbsp;&nbsp;���� ');
		$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$userid = $_SESSION['userid'];
		$admin_username = param::get_cookie('admin_username');

		$result = $this->db->select('','*','','listorder ASC,id DESC');

		foreach($result as $r) {
			$r['cname'] = L($r['name'], '', 'member_menu');
			$r['str_manage'] = '<a href="?m=member&c=member_menu&a=edit&id='.$r['id'].'&menuid='.$_GET['menuid'].'">'.L('edit').'</a> | <a href="javascript:confirmurl(\'?m=member&c=member_menu&a=delete&id='.$r['id'].'&menuid='.$_GET['menuid'].'\',\''.L('confirm',array('message'=>$r['cname'])).'\')">'.L('delete').'</a> ';
			$array[] = $r;
		}

		$str  = "<tr>
					<td align='center'><input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input-text-c'></td>
					<td align='center'>\$id</td>
					<td >\$spacer\$cname</td>
					<td align='center'>\$str_manage</td>
				</tr>";
		$tree->init($array);
		$categorys = $tree->get_tree(0, $str);
		include $this->admin_tpl('member_menu');
	}
	function add() {
		if(isset($_POST['dosubmit'])) {
			$this->db->insert($_POST['info']);
			//���������������Զ��������԰�
			$file = PC_PATH.'languages'.DIRECTORY_SEPARATOR.'zh-cn'.DIRECTORY_SEPARATOR.'member_menu.lang.php';
			if(file_exists($file)) {
				$content = file_get_contents($file);
				$content = substr($content,0,-2);
				$key = $_POST['info']['name'];
				$data = $content."\$LANG['$key'] = '$_POST[language]';\r\n?>";
				file_put_contents($file,$data);
			} else {
				
				$key = $_POST['info']['name'];
				$data = "<?php\r\n\$LANG['$key'] = '$_POST[language]';\r\n?>";
				file_put_contents($file,$data);
			}
			//����
			showmessage(L('add_success'));
		} else {
			$show_validator = '';
			$tree = pc_base::load_sys_class('tree');
			$result = $this->db->select();
			foreach($result as $r) {
				$r['cname'] = L($r['name'], '', 'member_menu');
				$r['selected'] = $r['id'] == $_GET['parentid'] ? 'selected' : '';
				$array[] = $r;
			}
			$str  = "<option value='\$id' \$selected>\$spacer \$cname</option>";
			$tree->init($array);
			$select_categorys = $tree->get_tree(0, $str);
			
			include $this->admin_tpl('member_menu');
		}
	}
	function delete() {
		$_GET['id'] = intval($_GET['id']);
		$menu = $this->db->get_one(array("id"=>$_GET['id']));
		if(!$menu)showmessage('�˵������ڣ��뷵�أ�',HTTP_REFERER);
		$this->db->delete(array('id'=>$_GET['id']));
		//ɾ��member_menu���԰�
		$file = PC_PATH.'languages'.DIRECTORY_SEPARATOR.'zh-cn'.DIRECTORY_SEPARATOR.'member_menu.lang.php';
		require $file;
		$content = file_get_contents($file);
 		$str = "\$LANG['".$menu['name']."'] = '".$LANG[$menu['name']]."';\r\n";
 		$content = str_replace($str,'',$content);
		file_put_contents($file,$content);
		
 		showmessage(L('operation_success'));
	}
	
	function edit() {
		if(isset($_POST['dosubmit'])) {
			$id = intval($_POST['id']);
			$this->db->update($_POST['info'],array('id'=>$id));
			//�޸������ļ�
			$file = PC_PATH.'languages'.DIRECTORY_SEPARATOR.'zh-cn'.DIRECTORY_SEPARATOR.'member_menu.lang.php';
			require $file;
			$key = $_POST['info']['name'];
			if(!isset($LANG[$key])) {
				$content = file_get_contents($file);
				$content = substr($content,0,-2);
				$data = $content."\$LANG['$key'] = '$_POST[language]';\r\n?>";
				file_put_contents($file,$data);
			} elseif(isset($LANG[$key]) && $LANG[$key]!=$_POST['language']) {
				$content = file_get_contents($file);
				$content = str_replace($LANG[$key],$_POST['language'],$content);
				file_put_contents($file,$content);
			}
			
			//���������ļ��޸�
			showmessage(L('operation_success'));
		} else {
			$show_validator = '';
			$tree = pc_base::load_sys_class('tree');
			$id = intval($_GET['id']);
			$r = $this->db->get_one(array('id'=>$id));
			if($r) extract($r);
			$result = $this->db->select();
			foreach($result as $r) {
				$r['cname'] = L($r['name'], '', 'member_menu');
				$r['selected'] = $r['id'] == $parentid ? 'selected' : '';
				$array[] = $r;
			}
			$str  = "<option value='\$id' \$selected>\$spacer \$cname</option>";
			$tree->init($array);
			$select_categorys = $tree->get_tree(0, $str);
			include $this->admin_tpl('member_menu');
		}
	}
	
	/**
	 * ����
	 */
	function listorder() {
		if(isset($_POST['dosubmit'])) {
			foreach($_POST['listorders'] as $id => $listorder) {
				$this->db->update(array('listorder'=>$listorder),array('id'=>$id));
			}
			showmessage(L('operation_success'));
		} else {
			showmessage(L('operation_failure'));
		}
	}
}
?>