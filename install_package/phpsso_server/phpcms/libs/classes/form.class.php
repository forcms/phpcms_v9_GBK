<?php
class form {
	/**
	 * �༭��
	 * @param int $textareaid
	 * @param int $toolbar 
	 * @param string $module ģ������
	 * @param int $catid ��Ŀid
	 * @param int $color �༭����ɫ
	 * @param boole $allowupload  �Ƿ������ϴ�
	 * @param boole $allowbrowser �Ƿ���������ļ�
	 * @param string $alowuploadexts �����ϴ�����
	 */
	public static function editor($textareaid = 'content', $toolbar = 'basic', $module='', $catid='', $color = '', $allowupload = 0, $allowbrowser = 1,$alowuploadexts = '') {
		$str ='';
		if(!defined('EDITOR_INIT')) {
			$str = '<script type="text/javascript" src="statics/js/ckeditor/ckeditor.js"></script>';
			define('EDITOR_INIT', 1);
		}
		if($toolbar=='basic') {
			$toolbar = "['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],\r\n";
		} elseif($toolbar=='full') {
			$toolbar = "['Source','-','Save','NewPage','Preview','-','Templates'],
		    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print'],
		    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],['ShowBlocks','Maximize'],
		    '/',
		    ['Bold','Italic','Underline','Strike','-'],
		    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		    ['Link','Unlink','Anchor'],
		    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		    '/',
		    ['Styles','Format','Font','FontSize'],
		    ['TextColor','BGColor'],
		    ['attachment'],\r\n";
		} else {
			$toolbar = '';
		}
		$str .= "<script type=\"text/javascript\">\r\n";
		$str .= "//<![CDATA[\r\n";
		$str .= "CKEDITOR.replace( '$textareaid',{";
		//$str .= "skin : 'v2',";
		$str .="pages:true,subtitle:true,textareaid:'".$textareaid."',module:'".$module."',catid:'".$catid."',\r\n";
		//if($allowbrowser) $str .= "filebrowserBrowseUrl : '/browser/browse.php',\r\n";
		if($allowupload) $str .="flashupload:true,alowuploadexts:'".$alowuploadexts."',allowbrowser:'".$allowbrowser."',\r\n";
        if($allowupload) $str .= "filebrowserUploadUrl : '?m=attachment&c=attachments&a=upload&dosubmit=1',\r\n";
		if($color) {
			$str .= "extraPlugins : 'uicolor',uiColor: '$color',";
		}
		$str .= "toolbar :\r\n";
		$str .= "[\r\n";
		$str .= $toolbar;
		$str .= "]\r\n";
		//$str .= "fullPage : true";
		$str .= "});\r\n";
		$str .= "//]]>\r\n";
		$str .= '</script>';
		$ext_str = "<div class='editor_bottom'>";
		if(!defined('IMAGES_INIT')) {
			$ext_str .= '<script type="text/javascript" src="statics/js/swfupload/swf2ckeditor.js"></script>';
			define('IMAGES_INIT', 1);
		}
		$ext_str .= "<div id='page_title_div'>
		<table cellpadding='0' cellspacing='1' border='0'><tr><td class='title'>".L('paging')."<span id='msg_page_title_value'></span></td><td>
		<a class='close' href='javascript:;' onclick='javascript:$(\"#page_title_div\").hide();'><span>��</span></a></td>
		<tr><td colspan='2'><input name='page_title_value' id='page_title_value' class='input-text' value='' size='40'>&nbsp;<input type='button' class='button' value='".L('submit')."' onclick=insert_page_title(\"$textareaid\",1)></td></tr>
		</table></div>";
		$ext_str .= "<div id=\"MM_file_list_".$textareaid."\" style=\"text-align:left\"></div><div id='FilePreview' style='Z-INDEX: 1000; LEFT: 0px; WIDTH: 10px; POSITION: absolute; TOP: 0px; HEIGHT: 10px; display: none;'></div><div id='".$textareaid."_save'></div>";
		$ext_str .= "</div>";
		$str .= $ext_str;
		return $str;
	}
	
	/**
	 * ͼƬ�ϴ�
	 * @param $name ������
	 * @param $id   ��ID
	 * @param $value ��ֵ
	 * @param $size ��size
	 * @param $class �����
	 * @param $ext ����չ
	 * @param $modelid 
	 * @param $fieldid
	 * @param $alowexts ����������
	 */
	public static function images($name, $id = '', $value = '', $moudle='', $catid='', $size = 50, $class = '', $ext = '', $alowexts = '') {
		if(!$id) $id = $name;
		if(!$size) $size= 50;
		if(!$alowexts) $alowexts = 'jpg|jpeg|gif|bmp|png';
		if(!defined('IMAGES_INIT')) {
			$str = '<script type="text/javascript" src="statics/js/swfupload/swf2ckeditor.js"></script>';
			define('IMAGES_INIT', 1);
		}
		return $str."<input type=\"text\" name=\"$name\" id=\"$id\" value=\"$value\" size=\"$size\" class=\"$class\" $ext/>  <input type=\"button\" class=\"button\" onclick=\"javascript:flashupload('{$id}_images', '�����ϴ�','{$id}',submit_images,'1,{$alowexts}','{$moudle}','{$catid}')\"/ value=\"�ϴ�ͼƬ\">";
	}
	
	public static function date($name, $value = '', $isdatetime = 0) {
		if($value == '0000-00-00 00:00:00') $value = '';
		$id = preg_match("/\[(.*)\]/", $name, $m) ? $m[1] : $name;
		if($isdatetime) {
			$size = 21;
			$format = '%Y-%m-%d %H:%M:%S';
			$showsTime = 'true';
		} else {
			$size = 10;
			$format = '%Y-%m-%d';
			$showsTime = 'false';
		}
		$str = '';
		if(!defined('CALENDAR_INIT')) {
			define('CALENDAR_INIT', 1);
			$str .= '<link rel="stylesheet" type="text/css" href="statics/js/calendar/calendar-blue.css"/>
			        <script type="text/javascript" src="statics/js/calendar/calendar.js"></script>';
		}
		$str .= '<input type="text" name="'.$name.'" id="'.$id.'" value="'.$value.'" size="'.$size.'" class="date" readonly>&nbsp;';
		$str .= '<script language="javascript" type="text/javascript">
					date = new Date();document.getElementById ("'.$id.'").value="'.$value.'";
					Calendar.setup({
						inputField     :    "'.$id.'",
						ifFormat       :    "'.$format.'",
						showsTime      :    '.$showsTime.',
						timeFormat     :    "24"
					});
				 </script>';
		return $str;
	}

	/**
	 * ��֤��
	 * @param string $id            ���ɵ���֤��ID
	 * @param integer $code_len     ���ɶ���λ��֤��
	 * @param integer $font_size    ��֤�������С
	 * @param integer $width        ��֤ͼƬ�Ŀ�
	 * @param integer $height       ��֤��ͼƬ�ĸ�
	 * @param string $font          ʹ��ʲô���壬���������URL
	 * @param string $font_color    ����ʹ��ʲô��ɫ
	 * @param string $background    ����ʹ��ʲô��ɫ
	 */
	public static function checkcode($id = 'checkcode',$code_len = 4, $font_size = 20, $width = 130, $height = 50, $font = '', $font_color = '', $background = '') {
		return "<img id='$id' onclick='this.src=this.src+\"&\"+Math.random()' src='".APP_PATH."api.php?op=checkcode&code_len=$code_len&font_size=$font_size&width=$width&height=$height&font=".urlencode($font)."&font_color=".urlencode($font_color)."&background=".urlencode($background)."'>";
	}
	
	/**
	 * ��Ŀѡ��
	 * @param string $file ��Ŀ�����ļ���
	 * @param intval/array $catid ��ѡ�е�ID����ѡ�ǿ���������
	 * @param string $str ����
	 * @param string $default_option Ĭ��ѡ��
	 * @param intval $modelid ������ģ��ɸѡ
	 */
	public static function select_category($file = 'category_content',$catid = 0, $str = '', $default_option = '', $modelid = 0) {
		$tree = pc_base::load_sys_class('tree');
		$result = getcache($file,'commons');
		$string = '<select '.$str.'>';
		if($default_option) $string .= "<option value='0'>$default_option</option>";
		foreach($result as $r) {
			if(is_array($catid)) {
				$r['selected'] = in_array($r['catid'], $catid) ? 'selected' : '';
			} elseif(is_numeric($catid)) {
				$r['selected'] = $catid==$r['catid'] ? 'selected' : '';
			}
			$categorys[$r['catid']] = $r;
			//$string .= '<option >'.$r['catname'].'</option>';
			if($modelid && $r['modelid']!= $modelid ) unset($categorys[$r['catid']]);
		}
		$str  = "<option value='\$catid' \$selected>\$spacer \$catname</option>";

		$tree->init($categorys);
		$string .= $tree->get_tree(0, $str);
			
		$string .= '</select>';
		return $string;
	}

	public static function select_linkage($keyid = 0, $parentid = 0, $name = 'parentid', $id ='', $alt = '', $linkageid = 0, $property = '') {
		$tree = pc_base::load_sys_class('tree');
		$result = getcache($keyid,'linkage');
		$id = $id ? $id : $name;
		$string = "<select name='$name' id='$id' $property>\n<option value='0'>$alt</option>\n";
		if($result['data']) {
			foreach($result['data'] as $area) {	
				$categorys[$area['linkageid']] = array('id'=>$area['linkageid'], 'parentid'=>$area['parentid'], 'name'=>$area['name']);	
			}
		}
		$str  = "<option value='\$id' \$selected>\$spacer \$name</option>";

		$tree->init($categorys);
		$string .= $tree->get_tree($parentid, $str, $linkageid);
			
		$string .= '</select>';
		return $string;
	}
	/**
	 * ����ѡ���
	 */
	public static function select($array = array(), $id = 0, $str = '', $default_option = '') {
		$string = '<select '.$str.'>';
		$default_selected = (empty($id) && $default_option) ? 'selected' : '';
		if($default_option) $string .= "<option value='' $default_selected>$default_option</option>";
		foreach($array as $key=>$value) {
			$selected = $id==$key ? 'selected' : '';
			$string .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
		}
		$string .= '</select>';
		return $string;
	}
	
	/**
	 * ��ѡ��
	 * 
	 * @param $array ѡ�� ��ά����
	 * @param $id Ĭ��ѡ��ֵ������� '����'�ָ�
	 * @param $str ����
	 * @param $defaultvalue �Ƿ�����Ĭ��ֵ Ĭ��ֵΪ -99
	 * @param $width ���
	 */
	public static function checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $width = 0) {
		$string = '';
		if($id != '') $id = strpos($id, ',') ? explode(',', $id) : array($id);
		if($defaultvalue) $string .= '<input type="hidden" '.$str.' value="-99">';
		foreach($array as $key=>$value) {
			$checked = ($id && in_array($key, $id)) ? 'checked' : '';
			if($width) $string .= '<span class="ib" style="width:'.$width.'px"><label>';
			$string .= '<input type="checkbox" '.$str.' '.$checked.' value="'.$key.'"> '.$value;
			if($width) $string .= '</label></span>';
		}
		return $string;
	}

	/**
	 * ��ѡ��
	 * 
	 * @param $array ѡ�� ��ά����
	 * @param $id Ĭ��ѡ��ֵ
	 * @param $str ����
	 */
	public static function radio($array = array(), $id = 0, $str = '') {
		$string = '';
		foreach($array as $key=>$value) {
			$checked = $id==$key ? 'checked' : '';
			$string .= '<input type="radio" '.$str.' '.$checked.' value="'.$key.'"> '.$value;
		}
		return $string;
	}
	/**
	 * ģ��ѡ��
	 * 
	 * @param $module ģ��
	 * @param $id Ĭ��ѡ��ֵ
	 * @param $str ����
	 * @param $pre ģ��ǰ׺
	 */
	public static function select_template($module, $id = '', $str = '', $pre = '') {
		if(!$id) $id = $name;
		$tpl_root = pc_base::load_config('system','tpl_root');
		$tpl_name = pc_base::load_config('system','tpl_name');
		$templatedir = PC_PATH.$tpl_root.DIRECTORY_SEPARATOR.$tpl_name.DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR;
		$files = @array_map('basename', glob($templatedir.$pre.'*.html'));
		$names = array();
		if(file_exists($templatedir.'name.inc.php')) $names = include $templatedir.'name.inc.php';
		$templates = array();
		if(is_array($files)) {
			foreach($files as $file) {
				$key = substr($file, 0, -5);
				$templates[$key] = isset($names[$file]) ? $names[$file].'('.$file.')' : $file;
			}
		}
		ksort($templates);
		return self::select($templates, $id, $str,L('please_select'));
	}
}

?>