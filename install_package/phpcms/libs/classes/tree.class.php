<?php
/**
* ͨ�õ������࣬���������κ����ͽṹ
*/
class tree {
	/**
	* �������ͽṹ����Ҫ��2ά����
	* @var array
	*/
	public $arr = array();

	/**
	* �������ͽṹ�������η��ţ����Ի���ͼƬ
	* @var array
	*/
	public $icon = array('��','��','��');
	public $nbsp = "&nbsp;";

	/**
	* @access private
	*/
	public $ret = '';

	/**
	* ���캯������ʼ����
	* @param array 2ά���飬���磺
	* array(
	*      1 => array('id'=>'1','parentid'=>0,'name'=>'һ����Ŀһ'),
	*      2 => array('id'=>'2','parentid'=>0,'name'=>'һ����Ŀ��'),
	*      3 => array('id'=>'3','parentid'=>1,'name'=>'������Ŀһ'),
	*      4 => array('id'=>'4','parentid'=>1,'name'=>'������Ŀ��'),
	*      5 => array('id'=>'5','parentid'=>2,'name'=>'������Ŀ��'),
	*      6 => array('id'=>'6','parentid'=>3,'name'=>'������Ŀһ'),
	*      7 => array('id'=>'7','parentid'=>3,'name'=>'������Ŀ��')
	*      )
	*/
	public function init($arr=array()){
       $this->arr = $arr;
	   $this->ret = '';
	   return is_array($arr);
	}

    /**
	* �õ���������
	* @param int
	* @return array
	*/
	public function get_parent($myid){
		$newarr = array();
		if(!isset($this->arr[$myid])) return false;
		$pid = $this->arr[$myid]['parentid'];
		$pid = $this->arr[$pid]['parentid'];
		if(is_array($this->arr)){
			foreach($this->arr as $id => $a){
				if($a['parentid'] == $pid) $newarr[$id] = $a;
			}
		}
		return $newarr;
	}

    /**
	* �õ��Ӽ�����
	* @param int
	* @return array
	*/
	public function get_child($myid){
		$a = $newarr = array();
		if(is_array($this->arr)){
			foreach($this->arr as $id => $a){
				if($a['parentid'] == $myid) $newarr[$id] = $a;
			}
		}
		return $newarr ? $newarr : false;
	}

    /**
	* �õ���ǰλ������
	* @param int
	* @return array
	*/
	public function get_pos($myid,&$newarr){
		$a = array();
		if(!isset($this->arr[$myid])) return false;
        $newarr[] = $this->arr[$myid];
		$pid = $this->arr[$myid]['parentid'];
		if(isset($this->arr[$pid])){
		    $this->get_pos($pid,$newarr);
		}
		if(is_array($newarr)){
			krsort($newarr);
			foreach($newarr as $v){
				$a[$v['id']] = $v;
			}
		}
		return $a;
	}

    /**
	* �õ����ͽṹ
	* @param int ID����ʾ������ID�µ������Ӽ�
	* @param string �������ͽṹ�Ļ������룬���磺"<option value=\$id \$selected>\$spacer\$name</option>"
	* @param int ��ѡ�е�ID���������������������ʱ����Ҫ�õ�
	* @return string
	*/
	public function get_tree($myid, $str, $sid = 0, $adds = '', $str_group = ''){
		$number=1;
		$child = $this->get_child($myid);
		if(is_array($child)){
		    $total = count($child);
			foreach($child as $id=>$value){
				$j=$k='';
				if($number==$total){
					$j .= $this->icon[2];
				}else{
					$j .= $this->icon[1];
					$k = $adds ? $this->icon[0] : '';
				}
				$spacer = $adds ? $adds.$j : '';
				$selected = $id==$sid ? 'selected' : '';
				@extract($value);
				$parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
				$this->ret .= $nstr;
				$nbsp = $this->nbsp;
				$this->get_tree($id, $str, $sid, $adds.$k.$nbsp,$str_group);
				$number++;
			}
		}
		return $this->ret;
	}
    /**
	* ͬ��һ��������,�������ѡ
	*/
	public function get_tree_multi($myid, $str, $sid = 0, $adds = ''){
		$number=1;
		$child = $this->get_child($myid);
		if(is_array($child)){
		    $total = count($child);
			foreach($child as $id=>$a){
				$j=$k='';
				if($number==$total){
					$j .= $this->icon[2];
				}else{
					$j .= $this->icon[1];
					$k = $adds ? $this->icon[0] : '';
				}
				$spacer = $adds ? $adds.$j : '';
				
				$selected = $this->have($sid,$id) ? 'selected' : '';
				@extract($a);
				eval("\$nstr = \"$str\";");
				$this->ret .= $nstr;
				$this->get_tree_multi($id, $str, $sid, $adds.$k.'&nbsp;');
				$number++;
			}
		}
		return $this->ret;
	}
	 /**
	* @param integer $myid Ҫ��ѯ��ID
	* @param string $str   ��һ��HTML���뷽ʽ
	* @param string $str2  �ڶ���HTML���뷽ʽ
	* @param integer $sid  Ĭ��ѡ��
	* @param integer $adds ǰ׺
	*/
	public function get_tree_category($myid, $str, $str2, $sid = 0, $adds = ''){
		$number=1;
		$child = $this->get_child($myid);
		if(is_array($child)){
		    $total = count($child);
			foreach($child as $id=>$a){
				$j=$k='';
				if($number==$total){
					$j .= $this->icon[2];
				}else{
					$j .= $this->icon[1];
					$k = $adds ? $this->icon[0] : '';
				}
				$spacer = $adds ? $adds.$j : '';
				
				$selected = $this->have($sid,$id) ? 'selected' : '';
				@extract($a);
				if (empty($html_disabled)) {
					eval("\$nstr = \"$str\";");
				} else {
					eval("\$nstr = \"$str2\";");
				}
				$this->ret .= $nstr;
				$this->get_tree_category($id, $str, $str2, $sid, $adds.$k.'&nbsp;');
				$number++;
			}
		}
		return $this->ret;
	}
	
	/**
	 * ͬ��һ�෽����jquery treeview ��񣬿�������ʽ����Ҫtreeview���֧�֣�
	 * @param $myid ��ʾ������ID�µ������Ӽ�
	 * @param $effected_id ��Ҫ����treeviewĿ¼����id
	 * @param $str ĩ����ʽ
	 * @param $str2 Ŀ¼������ʽ
	 * @param $showlevel ֱ����ʾ�㼶��������Ϊ�첽��ʾ��0Ϊȫ������
	 * @param $style Ŀ¼��ʽ Ĭ�� filetree ������������ʽ��'filetree treeview-famfamfam'
	 * @param $currentlevel ���㵱ǰ�㼶���ݹ�ʹ�� ���øĺ���ʱ����Ҫ�øò���
	 * @param $recursion �ݹ�ʹ�� �ⲿ����ʱΪFALSE
	 */
    function get_treeview($myid,$effected_id='example',$str="<span class='file'>\$name</span>", $str2="<span class='folder'>\$name</span>" ,$showlevel = 0 ,$style='filetree ' , $currentlevel = 1,$recursion=FALSE) {
        $child = $this->get_child($myid);
        if(!defined('EFFECTED_INIT')){
           $effected = ' id="'.$effected_id.'"';
           define('EFFECTED_INIT', 1);
        } else {
           $effected = '';
        }
		$placeholder = 	'<ul><li><span class="placeholder"></span></li></ul>';
        if(!$recursion) $this->str .='<ul'.$effected.'  class="'.$style.'">';
        foreach($child as $id=>$a) {

        	@extract($a);
			if($showlevel > 0 && $showlevel == $currentlevel && $this->get_child($id)) $folder = 'hasChildren'; //��������ʾ�㼶ģʽ@2011.07.01
        	$floder_status = isset($folder) ? ' class="'.$folder.'"' : '';		
            $this->str .= $recursion ? '<ul><li'.$floder_status.' id=\''.$id.'\'>' : '<li'.$floder_status.' id=\''.$id.'\'>';
            $recursion = FALSE;
            if($this->get_child($id)){
            	eval("\$nstr = \"$str2\";");
            	$this->str .= $nstr;
                if($showlevel == 0 || ($showlevel > 0 && $showlevel > $currentlevel)) {
					$this->get_treeview($id, $effected_id, $str, $str2, $showlevel, $style, $currentlevel+1, TRUE);
				} elseif($showlevel > 0 && $showlevel == $currentlevel) {
					$this->str .= $placeholder;
				}
            } else {
                eval("\$nstr = \"$str\";");
                $this->str .= $nstr;
            }
            $this->str .=$recursion ? '</li></ul>': '</li>';
        }
        if(!$recursion)  $this->str .='</ul>';
        return $this->str;
    }
	
	/**
	 * ��ȡ����Ŀjson
	 * Enter description here ...
	 * @param unknown_type $myid
	 */
	public function creat_sub_json($myid, $str='') {
		$sub_cats = $this->get_child($myid);
		$n = 0;
		if(is_array($sub_cats)) foreach($sub_cats as $c) {			
			$data[$n]['id'] = iconv(CHARSET,'utf-8',$c['catid']);
			if($this->get_child($c['catid'])) {
				$data[$n]['liclass'] = 'hasChildren';
				$data[$n]['children'] = array(array('text'=>'&nbsp;','classes'=>'placeholder'));
				$data[$n]['classes'] = 'folder';
				$data[$n]['text'] = iconv(CHARSET,'utf-8',$c['catname']);
			} else {				
				if($str) {
					@extract(array_iconv($c,CHARSET,'utf-8'));
					eval("\$data[$n]['text'] = \"$str\";");
				} else {
					$data[$n]['text'] = iconv(CHARSET,'utf-8',$c['catname']);
				}
			}
			$n++;
		}
		return json_encode($data);		
	}
	private function have($list,$item){
		return(strpos(',,'.$list.',',','.$item.','));
	}
}
?>