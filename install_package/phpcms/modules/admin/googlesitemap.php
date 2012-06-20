<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form', '', 0);

class googlesitemap extends admin {
	function __construct() {
		parent::__construct();
		$this->header = "<\x3Fxml version=\"1.0\" encoding=\"UTF-8\"\x3F>\n\t<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
	    $this->charset = "UTF-8";
	    $this->footer = "\t</urlset>\n";
	    $this->baidunews_footer = "</document>";
		$this->items = array();
		$this->baidunew_items = array();
		//������Ŀ����ѡ��
		$this->siteid = $this->get_siteid();
		$this->categorys = getcache('category_content_'.$this->siteid,'commons');
	}
	
	function add_item2($new_item) {
        $this->items[] = $new_item;
    }
    
	function build( $file_name = null ) {
        $map = $this->header . "\n";
        foreach ($this->items AS $item){
            $map .= "\t\t<url>\n\t\t\t<loc>$item[loc]</loc>\n";
            $map .= "\t\t\t<lastmod>$item[lastmod]</lastmod>\n";
            $map .= "\t\t\t<changefreq>$item[changefreq]</changefreq>\n";
            $map .= "\t\t\t<priority>$item[priority]</priority>\n";
            $map .= "\t\t</url>\n\n";
        }
        $map .= $this->footer . "\n";
        if (!is_null($file_name)){
            	return file_put_contents($file_name, $map);
        	} else {
            	return $map;
        }
    }
  
	function google_sitemap_item($loc, $lastmod = '', $changefreq = '', $priority = '') {
		$data = array();
		$data['loc'] =  $loc;
		$data['lastmod'] =  $lastmod;
		$data['changefreq'] =  $changefreq;
		$data['priority'] =  $priority;
		return $data;
    } 
    /**
     * 
     * �ٶ��������� ���
     * @param $title
     * @param $link
     * @param $description
     * @param $text
     * @param $image
     * @param $keywords
     * @param $category
     * @param $author
     * @param $source
     * @param $pubDate
     */
	function baidunews_item($title, $link = '', $description = '',$text = '',$image = '', $keywords = '',$category = '',$author = '',$source='',$pubDate='') {
		$data = array();
		$data['title'] =  $title;
		$data['link'] =  $link;
		$data['description'] =  $description;
		$data['text'] =  $text;
		$data['image'] =  $image;
		$data['keywords'] =  $keywords;
		$data['category'] =  $category;
		$data['author'] =  $author;
		$data['source'] =  $source;
		$data['pubDate'] =  $pubDate;
		return $data;
    }
    
    function add_baidunews_item($new_item){
    	 $this->baidunew_items[] = $new_item;
    }
    
	function baidunews_build( $file_name = null ,$this_domain,$email,$time) {
		//�ٶ�ͷ��
			$this->baidunews = '';
			$this->baidunews = "<?xml version=\"1.0\" encoding=\"".CHARSET."\" ?>\n";
			$this->baidunews .= "<document>\n";
			$this->baidunews .= "<webSite>".$this_domain."</webSite>\n";
			$this->baidunews .= "<webMaster>".$email."</webMaster>\n";
			$this->baidunews .= "<updatePeri>".$time."</updatePeri>\n";
         	foreach ($this->baidunew_items AS $item){ 
				$this->baidunews .= "<item>\n";
				$this->baidunews .= "<title>".$item['title']."</title>\n";
				$this->baidunews .= "<link>".$item['link']."</link>\n";
				$this->baidunews .= "<description>".$item['description'] ."</description>\n";
				$this->baidunews .= "<text>".$item['text']."</text>\n";
				$this->baidunews .= "<image>".$item['image']."</image>\n";
				$this->baidunews .= "<keywords>".$item['keywords']."</keywords>\n";
				$this->baidunews .= "<category>".$item['category']."</category>\n";
				$this->baidunews .= "<author>".$item['author']."</author>\n";
				$this->baidunews .= "<source>".$item['source']."</source>\n";
				$this->baidunews .= "<pubDate>".$item['pubDate']."</pubDate>\n";
				$this->baidunews .= "</item>\n";
       	    } 
         $this->baidunews .= $this->baidunews_footer . "\n";
         if (!is_null($file_name)){
            	return file_put_contents($file_name, $this->baidunews);
        	} else {
            	return $this->baidunews;
        }
    }
	  
	/**
	 * 
	 * Enter ����google sitemap, �ٶ�����Э��
	 */
	function set () {
		$hits_db = pc_base::load_model('hits_model');
		$dosubmit = isset($_POST['dosubmit']) ? $_POST['dosubmit'] : $_GET['dosubmit'];
		
		//��վ�㻺��
		$siteid = $this->siteid;
		$sitecache = getcache('sitelist','commons');
		//���ݵ�ǰվ��,ȡ���ļ����·��
  		$systemconfig = pc_base::load_config('system');
 		$html_root = substr($systemconfig['html_root'], 1);
 		//�жϵ�ǰվ��Ŀ¼,��PHPCMS����ļ�д����Ŀ¼��, ������д����վĿ¼��.(��վĿ¼���ɾ�̬�ļ�·��html_root�ͷ�վĿ¼dirname���)
 		if($siteid==1){
 			$dir = PHPCMS_PATH;
 		}else {
 			$dir = PHPCMS_PATH.$html_root.DIRECTORY_SEPARATOR.$sitecache[$siteid]['dirname'].DIRECTORY_SEPARATOR;
 		}
 		//ģ�ͻ���
 		$modelcache = getcache('model','commons');
 		
 		//��ȡ��ǰվ������,��������URLʱ���õ�.
 		$this_domain = substr($sitecache[$siteid]['domain'], 0,strlen($sitecache[$siteid]['domain'])-1);
   		if($dosubmit) {
				//���ɰٶ�����
				if($_POST['mark']) {
 					$baidunum = $_POST['baidunum'] ? intval($_POST['baidunum']) : 20;
  					if($_POST['catids']=="")showmessage(L('choose_category'), HTTP_REFERER);
  					$catids = $_POST['catids'];
 					$catid_cache = $this->categorys;//��Ŀ����
					$this->content_db = pc_base::load_model('content_model');
 					foreach ($catids as $catid) {
 						$modelid = $catid_cache[$catid]['modelid'];//������ĿID���modelid ����ȷ������,�������ĿID:catid ��������Ӧ��Ŀ�µ���������
 						$this->content_db->set_model($modelid);
 						$result = $this->content_db->select(array('catid'=>$catid,'status'=>99), '*', $limit = "0,$baidunum", 'id desc');
 						//�����ǰ׺,forѭ��ʱ������,�������� 
 						$this->content_db->table_name = $this->content_db->table_name.'_data';
 						foreach ($result as $arr){
 							//��ÿһ�����ݶ�װ��������
 							extract($arr);
 	 						if(!preg_match('/^(http|https):\/\//', $url)){
 								$url = $this_domain.$url;
							}
							if($thumb != ""){
								if(!preg_match('/^(http|https):\/\//', $thumb)){
									$thumb = $this_domain.$thumb;
								}
							}
							//ȡ��ǰ����ģ�� ������ ȡ ��������
							$url = htmlspecialchars($url);
							$description = htmlspecialchars(strip_tags($description));
							//���ݱ���ID,�Ӷ�Ӧtablename_dataȡ����������
   							$content_arr = $this->content_db->get_one(array('id'=>$id),'content');
   							$content = htmlspecialchars(strip_tags($content_arr['content']));
   							//�������
   	 						$smi = $this->baidunews_item($title,$url,$description,$content,$thumb, $keywords,$category,$author,$source,date('Y-m-d', $inputtime));//�Ƽ��ļ�
							$this->add_baidunews_item($smi);
  						} 
 					}
  					$baidunews_file = $dir.'baidunews.xml';
  					
   					@mkdir($dir,0777,true);
 					$this->baidunews_build($baidunews_file,$this_domain,$_POST['email'],$_POST['time']); 
 			    }
			    
				//������վ��ͼ
				$content_priority = $_POST['content_priority'];
				$content_changefreq = $_POST['content_changefreq']; 
				$num = $_POST['num'] ? intval($_POST['num']) : 100;
				
				$today = date('Y-m-d');
 			    $domain = $this_domain;
 			    //���ɵ�ͼͷ��������һ��
				$smi = $this->google_sitemap_item($domain, $today, 'daily', '1.0');
     			$this->add_item2($smi);
     			
			    $this->content_db = pc_base::load_model('content_model');
			    //ֻ��ȡ��վ���ģ��.��ѭ��ȡ����,����վ���ͼ.
				$modelcache = getcache('model','commons');
 				$new_model = array();
				foreach ($modelcache as $modelid => $mod){
					if($mod['siteid']==$siteid){
						$new_model[$modelid]['modelid'] = $modelid;
						$new_model[$modelid]['name'] = $mod['name'];						
					}
 				}
				foreach($new_model as $modelid=>$m) {//ÿ��ģ��ȡ��num������ 
					$this->content_db->set_model($modelid);// ���� $this->conetnt_db->set_model($modelid);
					$result = $this->content_db->select(array('status'=>99), '*', $limit = "0,$num", $order = 'inputtime desc');
					foreach ($result as $arr){
						if(substr($arr['url'],0,1)=='/'){
							$url = htmlspecialchars(strip_tags($domain.$arr['url']));
						}else {
							$url = htmlspecialchars(strip_tags($arr['url']));
						}
						$hit_r = $hits_db->get_one(array('hitsid'=>'c-'.$modelid.'-'.$arr['id']));
						if($hit_r['views']>1000) $content_priority = 0.9;
						$smi    = $this->google_sitemap_item($url, $today, $content_changefreq, $content_priority);//�Ƽ��ļ�
						$this->add_item2($smi);
					}
				}
				
			     $sm_file = $dir.'sitemaps.xml';
				 if($this->build($sm_file)){
					showmessage(L('create_success'), HTTP_REFERER);
			     } 
			} else { 
				$tree = pc_base::load_sys_class('tree');
				$tree->icon = array('&nbsp;&nbsp;&nbsp;�� ','&nbsp;&nbsp;&nbsp;���� ','&nbsp;&nbsp;&nbsp;���� ');
				$tree->nbsp = '&nbsp;&nbsp;&nbsp;';
				$categorys = array();
				foreach($this->categorys as $catid=>$r) {
					if($this->siteid != $r['siteid']) continue;
					if($r['type'] && $r['child']=='0'){//����ǵ���ҳ���ң�û��������
						continue;
 					}
					if($modelid && $modelid != $r['modelid']) continue;
					$r['disabled'] = $r['child'] ? 'disabled' : '';
					$categorys[$catid] = $r;
				}
				$str  = "<option value='\$catid' \$selected \$disabled>\$spacer \$catname</option>";
				$tree->init($categorys);
				$string .= $tree->get_tree(0, $str);
 				include $this->admin_tpl('googlesitemap');
			}
	}
	
	 
	
} 

?>