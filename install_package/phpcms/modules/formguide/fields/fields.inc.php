<?php
$fields = array('text'=>'�����ı�',
				'textarea'=>'�����ı�',
				'editor'=>'�༭��',
				'box'=>'ѡ��',
				'image'=>'ͼƬ',
				'images'=>'��ͼƬ',
				'number'=>'����',
				'datetime'=>'���ں�ʱ��',
				'linkage'=>'�����˵�',
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