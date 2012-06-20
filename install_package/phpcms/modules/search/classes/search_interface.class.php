<?php
/**
 * �����ӿ�
 *
 */
class search_interface {
	
	public function __construct() {
		//��ʼ��sphinx
		pc_base::load_app_class('sphinxapi', '', 0);
		$this->cl = new SphinxClient();
		$siteid = get_siteid();
		$search_setting = getcache('search');
		$setting = $search_setting[$siteid];
		
		$mode = SPH_MATCH_EXTENDED2;			//ƥ��ģʽ
		$host = $setting['sphinxhost'];			//����ip
		$port = intval($setting['sphinxport']);	//����˿�
		$ranker = SPH_RANK_PROXIMITY_BM25;		//ͳ����ضȼ���ģʽ����ʹ��BM25���ּ���

		$this->cl->SetServer($host, $port);
		$this->cl->SetConnectTimeout(1);
		$this->cl->SetArrayResult(true);
		$this->cl->SetMatchMode($mode);
		$this->cl->SetRankingMode($ranker);
	}
	
	/**
	 * ����
	 * @param string $q			�ؼ���	    	����sql like'%$q%'
	 * @param array $siteids	վ��id����
	 * @param array $typeids	����ids  		����sql IN('')
	 * @param array $adddate	ʱ�䷶Χ���� 		����sql between start AND end		 ��ʽ:array('start','end');
	 * @param int $offset 		ƫ����
	 * @param int $limit  		ƥ������Ŀ����	����sql limit $offset, $limit
	 * @param string $orderby	�����ֶ�		����sql order by $orderby {id:����id,weight:Ȩ��}
	 */
	public function search($q, $siteids=array(1), $typeids='', $adddate='', $offset=0, $limit=20, $orderby='@id desc') {

		if(CHARSET != 'utf-8') {
			$utf8_q = iconv(CHARSET, 'utf-8', $q);
		}
		
		if($orderby) {
			//��һ������SQL�ķ�ʽ�����������������������С�
			$this->cl->SetSortMode(SPH_SORT_EXTENDED, $orderby);
		}
		if($limit) {
			$this->cl->SetLimits($offset, $limit, ($limit>1000) ? $limit : 1000);
		}
		
		//��������
		if($typeids) {
			$this->cl->SetFilter('typeid', $typeids);
		}
		
		//����վ��
		if($siteids) {
			$this->cl->SetFilter('siteid', $siteids);
		}
		
		//����ʱ��
		if($adddate) {
			$this->cl->SetFilterRange('adddate', $adddate[0], $adddate[1], false);
		}
		
		$res = $this->cl->Query($utf8_q, 'main, delta');

		return $res;
	}


	
	
}