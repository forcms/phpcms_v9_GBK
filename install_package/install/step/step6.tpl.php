<?php include PHPCMS_PATH.'install/step/header.tpl.php';?>
	<div class="body_box">
        <div class="main_box">
            <div class="hd">
            	<div class="bz a6"><div class="jj_bg"></div></div>
            </div>
            <div class="ct">
            	<div class="bg_t"></div>
                <div class="clr">
                    <div class="l"></div>
                    <div class="ct_box">
                     <div class="nr">
                  	<div id="installmessage" >����׼����װ ...<br /></div>
                     </div>
                    </div>
                </div>
                <div class="bg_b"></div>
            </div>
            <div class="btn_box"><a href="javascript:history.go(-1);" class="s_btn pre">��һ��</a><a href="javascript:void(0);"  onClick="$('#install').submit();return false;" class="x_btn pre" id="finish">��װ��..</a></div>            
        </div>
    </div>
    <div id="hiddenop"></div>
	<form id="install" action="install.php?" method="post">
	<input type="hidden" name="module" id="module" value="<?php echo $module?>" />
	<input type="hidden" name="testdata" id="testdata" value="<?php echo $testdata?>" />
	<input type="hidden" id="selectmod" name="selectmod" value="<?php echo $selectmod?>" />
	<input type="hidden" name="step" value="7">
	</form>
</body>
<script language="JavaScript">
<!--
$().ready(function() {
reloads();
})
var n = 0;
var setting =  new Array();
setting['admin'] = '��̨������ģ�鰲װ�ɹ�......';
setting['phpsso'] = 'PHPSSO�����¼ϵͳ��װ�ɹ�......';
setting['comment'] = '����ģ�鰲װ�ɹ�......';
setting['announce'] = '����ģ�鰲װ�ɹ�......';
setting['poster'] = '���ģ�鰲װ�ɹ�......';
setting['link'] = '��������ģ�鰲װ�ɹ�......';
setting['vote'] = 'ͶƱģ�鰲װ�ɹ�......';
setting['mood'] = '����ָ��ģ�鰲װ�ɹ�......';
setting['message'] = '����Ϣģ�鰲װ�ɹ�......';
setting['formguide'] = '����ģ�鰲װ�ɹ�......';
setting['wap'] = '�ֻ��Ż�ģ�鰲װ�ɹ�......';
setting['tag'] = '��ǩģ�鰲װ�ɹ�......';
setting['sms'] = '����ģ�鰲װ�ɹ�......';

var dbhost = '<?php echo $dbhost?>';
var dbuser = '<?php echo $dbuser?>';
var dbpw = '<?php echo $dbpw?>';
var dbname = '<?php echo $dbname?>';
var tablepre = '<?php echo $tablepre?>';
var dbcharset = '<?php echo $dbcharset?>';
var pconnect = '<?php echo $pconnect?>';
var username = '<?php echo $username?>';
var password = '<?php echo $password?>';
var email = '<?php echo $email?>';
var ftp_user = '<?php echo $dbuser?>';
var password_key = '<?php echo $password_key?>';
function reloads() {
	var module = $('#selectmod').val();
	m_d = module.split(',');
	$.ajax({
		   type: "POST",
		   url: 'install.php',
		   data: "step=installmodule&module="+m_d[n]+"&dbhost="+dbhost+"&dbuser="+dbuser+"&dbpw="+dbpw+"&dbname="+dbname+"&tablepre="+tablepre+"&dbcharset="+dbcharset+"&pconnect="+pconnect+"&username="+username+"&password="+password+"&email="+email+"&ftp_user="+ftp_user+"&password_key="+password_key+"&sid="+Math.random()*5,
		   success: function(msg){
			   if(msg==1) {
				   alert('ָ�������ݿⲻ���ڣ�ϵͳҲ�޷�����������ͨ��������ʽ���������ݿ⣡');
			   } else if(msg==2) {
				   $('#installmessage').append("<font color='#ff0000'>"+m_d[n]+"/install/mysql.sql ���ݿ��ļ�������</font>");
			   } else if(msg.length>20) {
				   $('#installmessage').append("<font color='#ff0000'>������Ϣ��</font>"+msg);
			   } else {
				   $('#installmessage').append(setting[m_d[n]] + msg + "<img src='images/correct.gif' /><br>");				   
					n++;
					if(n < m_d.length) {
						reloads();
					} else {
						var testdata = $('#testdata').val();
						if(testdata == 1) {
							$('#hiddenop').load('?step=testdata&sid='+Math.random()*5);
							$('#installmessage').append("<font color='yellow'>�������ݰ�װ���</font><br>");
						}						
						$('#hiddenop').load('?step=cache_all&sid='+Math.random()*5);						
						$('#installmessage').append("<font color='yellow'>������³ɹ�</font><br>");
						$('#installmessage').append("<font color='yellow'>��װ���</font>");
						$('#finish').removeClass('pre');
						$('#finish').html('��װ���');
						setTimeout("$('#install').submit();",1000); 						
					}
					document.getElementById('installmessage').scrollTop = document.getElementById('installmessage').scrollHeight;
			   }	
		}	
		});
}
//-->
</script>
</html>
