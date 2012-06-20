<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('model', '', 0);
class ipbanned_model extends model {
	public $table_name = '';
	public function __construct() {
			$this->db_config = pc_base::load_config('database');
			$this->db_setting = 'default';
			$this->table_name = 'ipbanned';
			parent::__construct();
	}
	
  	/**
 	 * 
 	 * ��IP���и�ʽ����ͳһΪIPV4�� ������$op --�������� max ��ʾ��ʽΪ�öε����ֵ�����磺192.168.1.* ��ʽ��Ϊ��192.168.1.255 ����������ֵ��ʾ��ʽ����Сֵ�� 192.168.1.1
 	 * @param $op	��������,ֵΪ(min,max)
 	 * @param $ip	Ҫ�����IP��(127.0.0.*) ����IPֵ (127.0.0.5)
 	 */
	public function convert_ip($op,$ip){
		  $arr_ip = explode(".",$ip); 
		  $arr_temp = array();
		  $i = 0;
		  $ip_val= $op== "max" ? "255":"1"; 
		  foreach($arr_ip as $key => $val ){ 
		    $i++; 
		    $val = $val== "*" ? $ip_val:$val; 
		    $arr_temp[]= $val; 
		  } 
		  for($i=4-$i;$i>0;$i--){ 
		    $arr_temp[]=$ip_val; 
		  } 
		  $comma = ""; 
		  foreach($arr_temp as $v){ 
		    $result.= $comma.$v; 
		    $comma = "."; 
		  } 
		  return $result; 
	}
	
	/**
	 * 
	 * �ж�IP�Ƿ��޲�����
	 * @param $ip		��ǰIP	
	 * @param $ip_from	��ʼIP��
	 * @param $ip_to	����IP��
	 */
	public function ipforbidden($ip,$ip_from,$ip_to){ 
		$from = strcmp($ip,$ip_from); 
		$to = strcmp($ip,$ip_to); 
		if($from >=0 && $to <= 0){ 
		return 0; 
		} else {
		return 1; 
		}
	}
	
	/**
	 * 
	 * IP��ֹ�жϽӿ�,���ⲿ���� ...
	 */
	public function check_ip(){
		$ip_array = array();
		//��ǰIP
		$ip = ip();
 		//����IP��ֹ����
		$ipbanned_cache = getcache('ipbanned','commons');
		if(!empty($ipbanned_cache)) {
			foreach($ipbanned_cache as $data){
				$ip_array[$data['ip']] = $data['ip'];
				//�Ƿ���IP��
				if(strpos($data['ip'],'*')){
					$ip_min = $this->convert_ip("min",$data['ip']);
					$ip_max = $this->convert_ip("max",$data['ip']);
					$result = $this->ipforbidden($ip,$ip_min,$ip_max);
					if($result==0 && $data['expires']>SYS_TIME){
						//����
						showmessage('����IP��ֹ����,���Խ�ֹ�����');
					}
				} else {
					//����IP��,�þ���ƥ��
					if($ip==$data['ip']&& $data['expires']>SYS_TIME){
						showmessage('IP��ַ����ƥ��,��ֹ�����');
					}
				}
			}
		}
	}
}
?>