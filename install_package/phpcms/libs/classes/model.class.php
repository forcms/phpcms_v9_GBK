<?php 
/**
 *  model.class.php ����ģ�ͻ���
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-6-7
 */

pc_base::load_sys_class('db_factory', '', 0);
class model {
	
	//���ݿ�����
	protected $db_config = '';
	//���ݿ�����
	protected $db = '';
	//�������ݿ��������
	protected $db_setting = 'default';
	//���ݱ���
	protected $table_name = '';
	//��ǰ׺
	public  $db_tablepre = '';
	
	public function __construct() {
		if (!isset($this->db_config[$this->db_setting])) {
			$this->db_setting = 'default';
		}
		$this->table_name = $this->db_config[$this->db_setting]['tablepre'].$this->table_name;
		$this->db_tablepre = $this->db_config[$this->db_setting]['tablepre'];
		$this->db = db_factory::get_instance($this->db_config)->get_database($this->db_setting);
	}
		
	/**
	 * ִ��sql��ѯ
	 * @param $where 		��ѯ����[��`name`='$name']
	 * @param $data 		��Ҫ��ѯ���ֶ�ֵ[��`name`,`gender`,`birthday`]
	 * @param $limit 		���ؽ����Χ[����10��10,10 Ĭ��Ϊ��]
	 * @param $order 		����ʽ	[Ĭ�ϰ����ݿ�Ĭ�Ϸ�ʽ����]
	 * @param $group 		���鷽ʽ	[Ĭ��Ϊ��]
	 * @param $key          �������鰴��������
	 * @return array		��ѯ���������
	 */
	final public function select($where = '', $data = '*', $limit = '', $order = '', $group = '', $key='') {
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->select($data, $this->table_name, $where, $limit, $order, $group, $key);
	}

	/**
	 * ��ѯ�������ݲ���ҳ
	 * @param $where
	 * @param $order
	 * @param $page
	 * @param $pagesize
	 * @return unknown_type
	 */
	final public function listinfo($where = '', $order = '', $page = 1, $pagesize = 20, $key='', $setpages = 10,$urlrule = '',$array = array()) {
		$where = to_sqls($where);
		$this->number = $this->count($where);
		$page = max(intval($page), 1);
		$offset = $pagesize*($page-1);
		$this->pages = pages($this->number, $page, $pagesize, $urlrule, $array, $setpages);
		$array = array();
		if ($this->number > 0) {
			return $this->select($where, '*', "$offset, $pagesize", $order, '', $key);
		} else {
			return array();
		}
	}

	/**
	 * ��ȡ������¼��ѯ
	 * @param $where 		��ѯ����
	 * @param $data 		��Ҫ��ѯ���ֶ�ֵ[��`name`,`gender`,`birthday`]
	 * @param $order 		����ʽ	[Ĭ�ϰ����ݿ�Ĭ�Ϸ�ʽ����]
	 * @param $group 		���鷽ʽ	[Ĭ��Ϊ��]
	 * @return array/null	���ݲ�ѯ�����,��������ڣ��򷵻ؿ�
	 */
	final public function get_one($where = '', $data = '*', $order = '', $group = '') {
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->get_one($data, $this->table_name, $where, $order, $group);
	}
	
	/**
	 * ֱ��ִ��sql��ѯ
	 * @param $sql							��ѯsql���
	 * @return	boolean/query resource		���Ϊ��ѯ��䣬������Դ��������򷵻�true/false
	 */
	final public function query($sql) {
		$sql = str_replace('phpcms_', $this->db_tablepre, $sql);
		return $this->db->query($sql);
	}
	
	/**
	 * ִ����Ӽ�¼����
	 * @param $data 		Ҫ���ӵ����ݣ�����Ϊ���顣����keyΪ�ֶ�ֵ������ֵΪ����ȡֵ
	 * @param $return_insert_id �Ƿ񷵻��½�ID��
	 * @param $replace �Ƿ���� replace into�ķ�ʽ�������
	 * @return boolean
	 */
	final public function insert($data, $return_insert_id = false, $replace = false) {
		return $this->db->insert($data, $this->table_name, $return_insert_id, $replace);
	}
	
	/**
	 * ��ȡ���һ����Ӽ�¼��������
	 * @return int 
	 */
	final public function insert_id() {
		return $this->db->insert_id();
	}
	
	/**
	 * ִ�и��¼�¼����
	 * @param $data 		Ҫ���µ��������ݣ���������Ϊ����Ҳ����Ϊ�ַ������������顣
	 * 						Ϊ����ʱ����keyΪ�ֶ�ֵ������ֵΪ����ȡֵ
	 * 						Ϊ�ַ���ʱ[����`name`='phpcms',`hits`=`hits`+1]��
	 *						Ϊ����ʱ[��: array('name'=>'phpcms','password'=>'123456')]
	 *						�������һ��ʹ��array('name'=>'+=1', 'base'=>'-=1');������Զ�����Ϊ`name` = `name` + 1, `base` = `base` - 1
	 * @param $where 		��������ʱ������,��Ϊ������ַ���
	 * @return boolean
	 */
	final public function update($data, $where = '') {
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->update($data, $this->table_name, $where);
	}
	
	/**
	 * ִ��ɾ����¼����
	 * @param $where 		ɾ����������,������Ϊ�ա�
	 * @return boolean
	 */
	final public function delete($where) {
		if (is_array($where)) $where = $this->sqls($where);
		return $this->db->delete($this->table_name, $where);
	}
	
	/**
	 * �����¼��
	 * @param string/array $where ��ѯ����
	 */
	final public function count($where = '') {
		$r = $this->get_one($where, "COUNT(*) AS num");
		return $r['num'];
	}
	
	/**
	 * ������ת��ΪSQL���
	 * @param array $where Ҫ���ɵ�����
	 * @param string $font ���Ӵ���
	 */
	final public function sqls($where, $font = ' AND ') {
		if (is_array($where)) {
			$sql = '';
			foreach ($where as $key=>$val) {
				$sql .= $sql ? " $font `$key` = '$val' " : " `$key` = '$val'";
			}
			return $sql;
		} else {
			return $where;
		}
	}
	
	/**
	 * ��ȡ������ݿ����Ӱ�쵽������
	 * @return int
	 */
	final public function affected_rows() {
		return $this->db->affected_rows();
	}
	
	/**
	 * ��ȡ���ݱ�����
	 * @return array
	 */
	final public function get_primary() {
		return $this->db->get_primary($this->table_name);
	}
	
	/**
	 * ��ȡ���ֶ�
	 * @param string $table_name    ����
	 * @return array
	 */
	final public function get_fields($table_name = '') {
		if (empty($table_name)) {
			$table_name = $this->table_name;
		} else {
			$table_name = $this->db_tablepre.$table_name;
		}
		return $this->db->get_fields($table_name);
	}
	
	/**
	 * �����Ƿ����
	 * @param $table ����
	 * @return boolean
	 */
	final public function table_exists($table){
		return $this->db->table_exists($this->db_tablepre.$table);
	}
	
	/**
	 * ����ֶ��Ƿ����
	 * @param $field �ֶ���
	 * @return boolean
	 */
	public function field_exists($field) {
		$fields = $this->db->get_fields($this->table_name);
		return array_key_exists($field, $fields);
	}
	
	final public function list_tables() {
		return $this->db->list_tables();
	}
	/**
	 * �������ݽ����
	 * @param $query ��mysql_query����ֵ��
	 * @return array
	 */
	final public function fetch_array() {
		$data = array();
		while($r = $this->db->fetch_next()) {
			$data[] = $r;		
		}
		return $data;
	}
	
	/**
	 * �������ݿ�汾��
	 */
	final public function version() {
		return $this->db->version();
	}
}