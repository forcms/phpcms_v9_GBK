<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('model', '', 0);
class vote_option_model extends model {
	function __construct() {
		$this->db_config = pc_base::load_config('database');
		$this->db_setting = 'default';
		//$this->db_tablepre = $this->db_config[$this->db_setting]['tablepre'];
		$this->table_name = 'vote_option';
		parent::__construct();
	}
	/**
	 * ˵��:���ͶƱѡ�����
	 * @param $data ѡ������
	 * @param $subjectid ͶƱ����ID
	 */
	function add_options($data, $subjectid,$siteid)
	{
		//�жϴ��ݵ����������Ƿ���ȷ 
		if(!is_array($data)) return FALSE;
		if(!$subjectid) return FALSE;
		foreach($data as $key=>$val)
		{
			if(trim($val)=='') continue;
			$newoption=array(
					'subjectid'=>$subjectid,
					'siteid'=>$siteid,
					'option'=>$val,
					'image'=>'',
					'listorder'=>0
			);
			$this->insert($newoption);

		}
		return TRUE;
	}

	/**
	 * ˵��:����ѡ��  
	 * @param $data ����  Array ( [44] => 443 [43(optionid)] => 334(option ֵ) )
	 * @param $subjectid
	 */
	function update_options($data)
	{
		//�жϴ��ݵ����������Ƿ���ȷ 
		if(!is_array($data)) return FALSE;
		foreach($data as $key=>$val)
		{
			if(trim($val)=='') continue;
			$newoption=array(
					'option'=>$val,
			);
			$this->update($newoption,array('optionid'=>$key));

		}
		return TRUE;
	}
	/**
	 * ˵��:ѡ������
	 * @param  $data ѡ������
	 */
	function set_listorder($data)
	{
		if(!is_array($data)) return FALSE;
		foreach($data as $key=>$val)
		{
			$val = intval($val);
			$key = intval($key);
			$this->db->query("update $tbname set listorder='$val' where {$keyid}='$key'");
		}
		return $this->db->affected_rows();
	}
	/**
	 * ˵��:ɾ��ָ�� ͶƱID��Ӧ��ѡ�� 
	 * @param $data
	 * @param $subjectid
	 */
	function del_options($subjectid)
	{
		if(!$subjectid) return FALSE;
		$this->delete(array('subjectid'=>$subjectid));
		return TRUE;
			
	}

	/**
	 * ˵��: ��ѯ ��ͶƱ�� ѡ��
	 * @param $subjectid ͶƱID 
	 */
	function get_options($subjectid)
	{
		if(!$subjectid) return FALSE;
		return $this->select(array('subjectid'=>$subjectid),'*','',$order = 'optionid ASC');
			
	}
	/**
	 * ˵��:ɾ��������ӦID��ѡ���¼ 
	 * @param $optionid ͶƱѡ��ID
	 */
	function del_option($optionid)
	{
		if(!$optionid) return FALSE;
		return $this->delete(array('optionid'=>$optionid));
	}
}
?>