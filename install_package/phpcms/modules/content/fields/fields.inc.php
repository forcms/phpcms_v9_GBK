<?php
$fields = array('text'=>'�����ı�',
				'textarea'=>'�����ı�',
				'editor'=>'�༭��',
				'catid'=>'��Ŀ',
				'title'=>'����',
				'box'=>'ѡ��',
				'image'=>'ͼƬ',
				'images'=>'��ͼƬ',
				'number'=>'����',
				'datetime'=>'���ں�ʱ��',
				'posid'=>'�Ƽ�λ',
				'keyword'=>'�ؼ���',
				'author'=>'����',
				'copyfrom'=>'��Դ',
				'groupid'=>'��Ա��',
				'islink'=>'ת������',
				'template'=>'ģ��',
				'pages'=>'��ҳѡ��',
				'typeid'=>'���',
				'readpoint'=>'���֡�����',
				'linkage'=>'�����˵�',
				'downfile'=>'��������',
				'downfiles'=>'���ļ��ϴ�',
				'map'=>'��ͼ�ֶ�',
				'omnipotent'=>'�����ֶ�',
				);
//������ɾ�����ֶΣ���Щ�ֶν��������ֶ���Ӵ���ʾ
$not_allow_fields = array('catid','typeid','title','keyword','posid','template','username');
//������ӵ�����Ψһ���ֶ�
$unique_fields = array('pages','readpoint','author','copyfrom','islink');
//��ֹ�����õ��ֶ��б�
$forbid_fields = array('catid','title','updatetime','inputtime','url','listorder','status','template','username');
//��ֹ��ɾ�����ֶ��б�
$forbid_delete = array('catid','typeid','title','thumb','keywords','updatetime','inputtime','posids','url','listorder','status','template','username');
//����׷�� JS��CSS ���ֶ�
$att_css_js = array('text','textarea','box','number','keyword','typeid');
?>