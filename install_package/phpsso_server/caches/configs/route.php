<?php
/**
 * ·�������ļ�
 * Ĭ������Ϊdefault���£�
 * 'default'=>array(
 * 	'm'=>'phpcms', 
 * 	'c'=>'index', 
 * 	'a'=>'init', 
 * 	'data'=>array(
 * 		'POST'=>array(
 * 			'catid'=>1
 * 		),
 * 		'GET'=>array(
 * 			'contentid'=>1
 * 		)
 * 	)
 * )
 * ���С�m��Ϊģ��,��c��Ϊ����������a��Ϊ�¼�����data��Ϊ�������Ӳ�����
 * dataΪһ����ά���飬������POST��GET��Ĭ�ϲ�����POST��GET�ֱ��ӦPHP�е�$_POST��$_GET������ȫ�ֱ������ڳ�����������ʹ��$_POST['catid']���õ�data����POST�е������ֵ��
 * data�е������õĲ����ȼ��Ƚϵ͡�����ⲿ�������ύ��ͬ�����ֵı��������Ḳ�������ļ��������õ�ֵ���磺
 * �ⲿ����POST��һ������catid=2��ô���ڳ�����ʹ��$_POSTȡ����ֵ��2�������������ļ��������õ�1��
 */
return array(
	'default'=>array('m'=>'admin', 'c'=>'index', 'a'=>'init'),
);