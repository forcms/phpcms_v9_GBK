	function images($field, $value) {
		//ȡ��ͼƬ�б�
		$pictures = $_POST[$field.'_url'];
		//ȡ��ͼƬ˵��
		$pictures_alt = $_POST[$field.'_alt'];
		$array = $temp = array();
		if(!empty($pictures)) {
			foreach($pictures as $key=>$pic) {
				$temp['url'] = $pic;
				$temp['alt'] = $pictures_alt[$key];
				$array[$key] = $temp;
			}
		}
		$array = array2string($array);
		return $array;
	}
