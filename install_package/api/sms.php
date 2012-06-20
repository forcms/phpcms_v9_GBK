<?php
defined('IN_PHPCMS') or exit('No permission resources.'); 
/**
 * ���ŷ��ͽӿ�
 */
pc_base::load_app_class('smsapi', 'sms', 0); //����smsapi��
$sms_report_db = pc_base::load_model('sms_report_model');
$mobile = $_GET['mobile'];
$siteid = get_siteid() ? get_siteid() : 1 ;
$sms_setting = getcache('sms','sms');
$sitelist = getcache('sitelist', 'commons');
$sitename = $sitelist[$siteid]['name'];
if(!preg_match('/^1([0-9]{9})/',$mobile)) exit('mobile phone error');
$posttime = SYS_TIME-86400;
$where = "`mobile`='$mobile' AND `posttime`>'$posttime'";
$num = $sms_report_db->count($where);
if($num > 2) {
	exit('-1');//���շ��Ͷ��������������� 3 ��
}
//ͬһIP 24Сʱ��������������
$allow_max_ip = 10;//����ע���൱�� 10 ����
$ip = ip();
$where = "`ip`='$ip' AND `posttime`>'$posttime'";
$num = $sms_report_db->count($where);
if($num >= $allow_max_ip) {
	exit('-3');//���յ�IP ���Ͷ����������� $allow_max_ip
}
if(intval($sms_setting[$siteid]['sms_enable']) == 0) exit('-99'); //���Ź��ܹر�
$sms_uid = $sms_setting[$siteid]['userid'];//���Žӿ��û�ID
$sms_pid = $sms_setting[$siteid]['productid'];//��ƷID
$sms_passwd = $sms_setting[$siteid]['sms_key'];//32λ����

$id_code = random(6);//Ψһ��������չ��֤
$send_txt = '�𾴵��û����ã�����'.$sitename.'��ע����֤��Ϊ��'.$id_code.'����֤����Ч��Ϊ5���ӡ�';

$send_userid = intval($_GET['send_userid']);//������id


$smsapi = new smsapi($sms_uid, $sms_pid, $sms_passwd); //��ʼ���ӿ���
$smsapi->get_price(); //��ȡ����ʣ�����������ƶ��ŷ��͵�ip��ַ
$mobile = explode(',',$mobile);
$content = safe_replace($send_txt);
$sent_time = intval($_POST['sendtype']) == 2 && !empty($_POST['sendtime'])  ? trim($_POST['sendtime']) : date('Y-m-d H:i:s',SYS_TIME);

$smsapi->send_sms($mobile, $content, $sent_time, CHARSET,$id_code); //���Ͷ���

echo $smsapi->statuscode;
?>