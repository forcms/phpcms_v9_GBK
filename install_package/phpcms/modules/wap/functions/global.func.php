<?php
/**
 * ���xmlͷ����Ϣ
 */
function wmlHeader() {
	echo "<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>\n";
}
/**
 * ��������url·��
 */
function list_url($typeid) {
    return WAP_SITEURL."&amp;a=lists&amp;typeid=$typeid";
}

function bigimg_url($url,$w='') {
	return WAP_SITEURL.'&amp;a=big_image&amp;url='.base64_encode($url).'&amp;w='.$w;
}
/**
 * ��������url·��
 * $catid ��Ŀid
 * $typeid wap����id
 * $id ����id
 */
function show_url($catid, $id, $typeid='') {
	global $WAP;
	if($typeid=='') {
		$types = getcache('wap_type','wap');
		foreach ($types as $type) {
			if($type['cat']==$catid) {
				$typeid = $type['typeid'];
				break;
			}
		}
	}
    return WAP_SITEURL."&amp;a=show&amp;catid=$catid&amp;typeid=$typeid&amp;id=$id";
}


/**
 * ��ǰ·�� 
 * ����ָ������·���㼶
 * @param $typeid ����id
 * @param $symbol ��������
 */
function wap_pos($typeid, $symbol=' > '){
	$type_arr = array();
	$type_arr = getcache('wap_type','wap');
	if(!isset($type_arr[$typeid])) return '';
	$pos = '';
	if($type_arr[$typeid]['parentid']!=0) {
		$pos = '<a href="'.list_url($type_arr[$typeid]['parentid']).'">'.$type_arr[$type_arr[$typeid]['parentid']]['typename'].'</a>'.$symbol;
	}
	$pos .= '<a href="'.list_url($typeid).'">'.$type_arr[$typeid]['typename'].'</a>'.$symbol;
	return $pos;
}

/**
 * ��ȡ�ӷ���
 */
function subtype($parentid = NULL, $siteid = '') {
	if (empty($siteid)) $siteid = $GLOBALS['siteid'];
	$types = getcache('wap_type','wap');
	foreach($types as $id=>$type) {
		if($type['siteid'] == $siteid && ($parentid === NULL || $type['parentid'] == $parentid)) {
			$subtype[$id] = $type;;
		}		
	}
	return $subtype;
}
/**
 * ��ҳ����
 * 
 * @param $num ��Ϣ����
 * @param $curr_page ��ǰ��ҳ
 * @param $perpage ÿҳ��ʾ��
 * @param $urlrule URL����
 * @param $array ��Ҫ���ݵ����飬�������Ӷ���ķ���
 * @return ��ҳ
 */
function wpa_pages($num, $curr_page, $perpage = 20, $urlrule = '', $array = array(),$setpages = 10) {
	if(defined('URLRULE')) {
		$urlrule = URLRULE;
		$array = $GLOBALS['URL_ARRAY'];
	} elseif($urlrule == '') {
		$urlrule = url_par('page={$page}');
	}
	$multipage = '';
	if($num > $perpage) {
		$page = $setpages+1;
		$offset = ceil($setpages/2-1);
		$pages = ceil($num / $perpage);
		if (defined('IN_ADMIN') && !defined('PAGES')) define('PAGES', $pages);
		$from = $curr_page - $offset;
		$to = $curr_page + $offset;
		$more = 0;
		if($page >= $pages) {
			$from = 2;
			$to = $pages-1;
		} else {
			if($from <= 1) {
				$to = $page-1;
				$from = 2;
			}  elseif($to >= $pages) { 
				$from = $pages-($page-2);  
				$to = $pages-1;  
			}
			$more = 1;
		} 
		$multipage .= $curr_page.'/'.$pages;
		if($curr_page>0) {
			$multipage .= ' <a href="'.pageurl($urlrule, $curr_page-1, $array).'">'.L('previous').'</a>';
		}
		if($curr_page==$pages) {
			$multipage .= ' <a href="'.pageurl($urlrule, $curr_page, $array).'">'.L('next').'</a>';
		} else {
			$multipage .= ' <a href="'.pageurl($urlrule, $curr_page+1, $array).'">'.L('next').'</a>';
		}
		
	}
	return $multipage;
}

/**
 * ��������Ϊwml��ʽ
 */
function wml_strip($string) {
    $string = str_replace(array('&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;', '&'), array(' ', '&', '"', "'", '��', '��', '��', '{<}', '{>}', '��', '��', '&amp;'), $string);
	return str_replace(array('{<}', '{>}'), array('&lt;', '&gt;'), $string);
}

/**
 * ������ͼƬ�滻
 */
function content_strip($content,$ishow=1) {
    
   $content = preg_replace('/<img[^>]*src=[\'"]?([^>\'"\s]*)[\'"]?[^>]*>/ie', "wap_img('$1',$ishow)", $content);
      
   //ƥ���滻����ͼƬ
      
   $content = strip_tags($content,'<b><br><img><p><div><a>');
   return $content;
}

/**
 * ͼƬ�����滻
 */
function wap_img($url,$ishow) {
	$wap_site = getcache('wap_site','wap');
	$wap_setting = string2array($wap_site[$GLOBALS['siteid']]['setting']);
	$show_big = bigimg_url($url);
	if($ishow==1) $show_tips = '<br><a href="'.$show_big.'">�����ͼ</a>';
	return '<img src="'.thumb($url,$wap_setting['thumb_w'],$wap_setting['thumb_h']).'">'.$show_tips;
}

function strip_selected_tags($text) {
    $tags = array('em','font','h1','h2','h3','h4','h5','h6','hr','i','ins','li','ol','p','pre','small','span','strike','strong','sub','sup','table','tbody','td','tfoot','th','thead','tr','tt','u','div','span');
    $args = func_get_args();
    $text = array_shift($args);
    $tags = func_num_args() > 2 ? array_diff($args,array($text)) : (array)$tags;
    foreach ($tags as $tag){
        if( preg_match_all( '/<'.$tag.'[^>]*>([^<]*)<\/'.$tag.'>/iu', $text, $found) ){
            $text = str_replace($found[0],$found[1],$text);
        }
    }
    return $text;
}

/**
 * �������·�ҳ����
 */

function content_pages($num, $curr_page,$pageurls,$showremain = 1) {
	$multipage = '';
	$page = 11;
	$offset = 4;
	$pages = $num;
	$from = $curr_page - $offset;
	$to = $curr_page + $offset;
	$more = 0;
	if($page >= $pages) {
		$from = 2;
		$to = $pages-1;
	} else {
		if($from <= 1) {
			$to = $page-1;
			$from = 2;
		} elseif($to >= $pages) {
			$from = $pages-($page-2);
			$to = $pages-1;
		}
		$more = 1;
	}
	$multipage .='('.$curr_page.'/'.$num.')';
	if($curr_page>0) {
		$perpage = $curr_page == 1 ? 1 : $curr_page-1;
		$multipage .= '<a class="a1" href="'.$pageurls[$perpage][1].'">'.L('previous').'</a>';
	}
	
	if($curr_page<$pages) {
		if($curr_page<$pages-5 && $more) {
			$multipage .= ' <a class="a1" href="'.$pageurls[$curr_page+1][1].'">'.L('next').'</a>';
		} else {
			$multipage .= ' <a class="a1" href="'.$pageurls[$curr_page+1][1].'">'.L('next').'</a>';
		}
	} elseif($curr_page==$pages) {
		$multipage .= ' <a class="a1" href="'.$pageurls[$curr_page][1].'">'.L('next').'</a>';
	}
	if($showremain) $multipage .="| <a href='".$pageurls[$curr_page][1]."&remains=true'>ʣ��ȫ��</a>";
	return $multipage;
}

/**
 * ��ͼ��ҳ����
 */

function pic_pages($array) {
	if(!is_array($array) || empty($array)) return false;
	foreach ($array as $k=>$p) {
		$photo_arr[$k]='<img src="'.$p['url'].'"><br>'.$p['alt'];
	}
	$photo_page = @implode('[page]', $photo_arr);
	$photo_page =content_strip(wml_strip($photo_page),0);
	return $photo_page;
}

/**
 * ��ȡ�ȴ�
 */
function hotword() {
	$site = getcache('wap_site','wap');
	$setting = string2array($site[$GLOBALS['siteid']]['setting']);
	$hotword = $setting['hotwords'];
	$hotword_arr = explode("\n", $hotword);
	if(is_array($hotword_arr) && count($hotword_arr) > 0) {
		foreach($hotword_arr as $_k) {
			$v = explode("|",$_k);
			$hotword_string .= '<a href="'.$v[1].'">'.$v[0].'</a>&nbsp';
		}		
	}
	return $hotword_string;
}
?>