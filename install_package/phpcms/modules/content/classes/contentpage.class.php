<?php
/**
 *  contentpage.class.php ��������ҳ��ҳ��
 *  
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-8-12
 */

class contentpage {
	private $additems = array (); //������Ҫ��ȫ�Ŀ�ͷhtml����
	private $bottonitems = array (); //������Ҫ��ȫ�Ľ�βHTML����
	private $html_tag = array (); //HTML�������
	private $surplus; //ʣ���ַ���
	public $content; //���巵�ص��ַ�
	
	public function __construct() {
		//����HTML����
		$this->html_tag = array ('p', 'div', 'h', 'span', 'strong', 'ul', 'ol', 'li', 'table', 'tr', 'tbody', 'dl', 'dt', 'dd');
		$this->html_end_tag = array ('/p', '/div', '/h', '/span', '/strong', '/ul', '/ol', '/li', '/table', '/tr', '/tbody', '/dl', '/dt', '/dd');
		$this->content = ''; //��ʱ���ݴ洢��
		$this->data = array(); //���ݴ洢
	}
	
	/**
	 * ���������ַ���
	 * 
	 * @param string $content ��������ַ���
	 * @param intval $maxwords ÿҳ����ַ�����ȥ��HTML��Ǻ��ַ���
	 * @return �������ַ���
	 */
	public function get_data($content = '', $maxwords = 10000) {
		if (!$content) return '';
		$this->data = array();
		$this->content = '';
		//exit($maxwords);
		$this->surplus = $maxwords; //��ʼʱ��ʣ���ַ�����Ϊ���
		//�ж��Ƿ����html��ǣ�������ֱ�Ӱ��ַ�����ҳ���������HTML��ǣ���Ҫ��ȫȱʧ��HTML���
		if (strpos($content, '<')!==false) {
			$content_arr = explode('<', $content); //���ַ�������<���ָ������
			$this->total = count($content_arr); //��������ֵ�ĸ��������ڼ����Ƿ�ִ�е��ַ�����β��
			foreach ($content_arr as $t => $c) {
				if ($c) {
					$s = strtolower($c); //��Сд������
					//$isadd = 0;
					
					if ((strpos($c, ' ')!==false) && (strpos($c, '>')===false)) {
						$min_point = intval(strpos($c, ' '));
					} elseif ((strpos($c, ' ')===false) && (strpos($c, '>')!==false)) {
						$min_point = intval(strpos($c, '>'));
					} elseif ((strpos($c, ' ')!==false) && (strpos($c, '>')!==false)) {
						$min_point = min(intval(strpos($c, ' ')), intval(strpos($c, '>')));
					}
					$find = substr($c, 0, $min_point);
					//if ($t>26) echo $s.'{}'.$find.'<br>';
					//preg_match('/(.*)([^>|\s])/i', $c, $matches);
					if (in_array(strtolower($find), $this->html_tag)) {
						$str = '<'.$c;
						$this->bottonitems[$t] = '</'.$find.'>'; //���ڶ����HTML��Χ����������Ǵ��벹ȫ�Ľ�β����
						if(preg_match('/<'.$find.'(.*)>/i', $str, $match)) {
							$this->additems[$t] = $match[0]; //ƥ�����ʼ��ǣ����벹ȫ�Ŀ�ʼ����
						}
						$this->separate_content($str, $maxwords, $match[0], $t); //���뷵���ַ�����
					} elseif (in_array(strtolower($find), $this->html_end_tag)) { //�ж��Ƿ����ڶ����HTML��β���
						ksort($this->bottonitems); 
						ksort($this->additems);
						if (is_array($this->bottonitems) && !empty($this->bottonitems)) array_pop($this->bottonitems); //�������ǣ�����ʼ�ͽ�β�Ĳ�ȫ����ȡ��һ��
						if (is_array($this->additems) && !empty($this->additems)) array_pop($this->additems);
						$str = '<'.$c;
						$this->separate_content($str, $maxwords, '', $t); //���뷵���ַ�����
					} else {
						$tag = '<'.$c;
						if ($this->surplus >= 0) {
							$this->surplus = $this->surplus-strlen(strip_tags($tag));
							if ($this->surplus<0) {
								$this->surplus = 0;
							}
						}
						$this->content .= $tag; //���ڶ����HTML��Ƿ�Χ������׷�ӵ������ַ�����
						if (intval($t+1) == $this->total) { //�ж��Ƿ���ʣ���ַ�
							$this->content .= $this->bottonitem();
							$this->data[] = $this->content;
						}
					}
				}
			}
		} else {
			$this->content .= $this->separate_content($content, $maxwords); //������ʱ
		}
		return implode('[page]', $this->data);
	}
	
	/**
	 * ����ÿ������
	 * @param string $str ÿ������
	 * @param intval $max ÿҳ������ַ�
	 * @param string $tag HTML���
	 * @param intval $t ����ڼ�������,�����ж��Ƿ��ַ�����ĩβ
	 * @param intval $n ����Ĵ���
	 * @param intval $total �ܹ��Ĵ�������ֹ��ѭ��
	 * @return boolen
	 */
	private function separate_content($str = '', $max, $tag = '', $t = 0, $n = 1, $total = 0) {
		$html = $str;
		$str = strip_tags($str);
		if ($str) $str = @str_replace(array('��'), '', $str);
		if ($str) {
			if ($n == 1) {
				$total = ceil((strlen($str)-$this->surplus)/$max)+1;
			}
			if ($total<$n) {
				return true;
			} else {
				$n++;
			}
			if (strlen($str)>$this->surplus) { //��ǰ�ַ�����������ҳ��ʱ
				$remove_str = str_cut($str, $this->surplus, '');
				$this->content .= $tag.$remove_str; //��ͬ��Ǽ��뷵���ַ���
				$this->content .= $this->bottonitem(); //��ȫβ�����
				$this->data[] = $this->content; //����ʱ�����ݷ���������
				$this->content = ''; //����Ϊ��
				$this->content .= $this->additem(); //��ȫ��ʼ���
				$str = str_replace($remove_str, '', $str); //ȥ���Ѽ���
				$this->surplus = $max;
				return $this->separate_content($str, $max, '', $t, $n, $total); //�ж�ʣ���ַ�
			} elseif (strlen($str)==$this->surplus) { //��ǰ�ַ��պõ���ʱ(��Ʊ����)
				$this->content .= $html;
				$this->content .= $this->bottonitem();
				if (intval($t+1) != $this->total) { //�ж��Ƿ���ʣ���ַ�
					$this->data[] = $this->content; //����ʱ�����ݷ���������
					$this->content = ''; //����Ϊ��
					$this->content .= $this->additem();
				}
				$this->surplus = $max;
			} else { //��ǰ�ַ�����������ҳ��
				$this->content .= $html;
				if (intval($t+1) == $this->total) { //�ж��Ƿ���ʣ���ַ�
					$this->content .= $this->bottonitem();
					$this->data[] = $this->content;
				}
				$this->surplus = $this->surplus-strlen($str);
			}
		} else {
			$this->content .= $html;
			if ($this->surplus == 0) {
				$this->content .= $this->bottonitem();
				if (intval($t+1) != $this->total) { //�ж��Ƿ���ʣ���ַ�
					$this->data[] = $this->content; //����ʱ�����ݷ���������
					$this->content = ''; //����Ϊ��
					$this->surplus = $max;
					$this->content .= $this->additem();
				}
			}
		}
		if ($t==($this->total-1)) {
			$pop_arr = array_pop($this->data);
			if ($pop = strip_tags($pop_arr)) {
				$this->data[] = $pop_arr;
			}
		}
		return true;
	}
	
	/**
	 * ��ȫ��ʼHTML���
	 */
	private function additem() {
		$content = '';
		if (is_array($this->additems) && !empty($this->additems)) {
			ksort($this->additems);
			foreach ($this->additems as $add) {
				$content .= $add;
			}
		}
		return $content;
	}
	
	/**
	 * ��ȫ��βHTML���
	 */
	private function bottonitem() {
		$content = '';
		if (is_array($this->bottonitems) && !empty($this->bottonitems)) {
			krsort($this->bottonitems);
			foreach ($this->bottonitems as $botton) {
				$content .= $botton;
			}
		}
		return $content;
	}
	
}
?> 