<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>
<form name="myform" action="?m=admin&c=position&a=listorder" method="post">
<div class="pad_10">
<div class="explain-col search-form">
����ƽ̨API�ӿ��ࣺ<br />
sms/classes/smsapi.class.php<br /><br />
��ʼ����<br />
__construct($userid = '', $productid = '', $sms_key = '')<br /><br />
��ȡ���Ų�Ʒ�б���Ϣ��<br />
get_price()<br /><br />
��ȡ����ʣ�����������ƶ��ŷ��͵�ip��ַ��<br />
get_smsinfo()<br /><br />
��ȡ��ֵ��¼��<br />
get_buyhistory()<br /><br />
��ȡ���Ѽ�¼�ӿڣ�<br />
get_payhistory($page=1);<br /><br />
���Ͷ��Žӿڣ�<br />
send_sms($mobile='', $content='', $send_time='', $charset='gbk')
<br /><br />
ʹ�÷�����<br />

pc_base::load_app_class('smsapi', '', 0); //����smsapi��<br />

$userid = '199310'; //��phpcms����ע����û�id<br />
$productid = '10';	//��ȡ�Ĳ�Ʒid<br />
$sms_key = 'JSSJJjse123jj41x24jx87mzxvnio'; //��ȡ����Կ<br />
$smsapi = new smsapi($userid, $productid, $sms_key); //��ʼ���ӿ���<br />
$smsapi->get_price(); //��ȡ����ʣ�����������ƶ��ŷ��͵�ip��ַ<br />
$mobile = array('13911711111', '13634503861', '15810233333');<br />
$content = '����һ�����Զ��ţ��������255����ร�';<br />
$sent_time = date('Y-m-d H:i:s',SYS_TIME);//��ʱ���͸�ʽΪ���ڸ�ʽ��2011-9-10 11:08:03<br />
$status = $smsapi->send_sms($mobile='', $content='', $send_time='', $charset='gbk'); //���Ͷ���<br />
echo $status; //����״̬
</div>

</div>
</div>
</form>
</body>
</html>