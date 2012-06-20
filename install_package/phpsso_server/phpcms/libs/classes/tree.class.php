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
			foreach($child as $id=>$a){
				$j=$k='';
				if($number==$total){
					$j .= $this->icon[2];
				}else{
					$j .= $this->icon[1];
					$k = $adds ? $this->icon[0] : '';
				}
				$spacer = $adds ? $adds.$j : '';
				$selected = $id==$sid ? 'selected' : '';
				@extract($a);
				$parentid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
				$this->ret .= $nstr;
				$this->get_tree($id, $str, $sid, $adds.$k.'&nbsp;',$str_group);
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
	
	private function have($list,$item){
		return(strpos(',,'.$list.',',','.$item.','));
	}
}
?>