<?php include PHPCMS_PATH.'install/step/header.tpl.php';?>
<script type="text/javascript">
  $(document).ready(function() {
	$.formValidator.initConfig({autotip:true,formid:"install",onerror:function(msg){}});
	$("#username").formValidator({onshow:"2��20���ַ��������Ƿ��ַ���",onfocus:"�������û���3��20λ"}).inputValidator({min:3,max:20,onerror:"�û�������ӦΪ3��20λ"})
	$("#password").formValidator({onshow:"6��20���ַ�<font color='FFFF00'>��Ĭ��Ϊ phpcms��</font>",onfocus:"����Ϸ�����Ϊ6��20λ"}).inputValidator({min:6,max:20,onerror:"����Ϸ�����Ϊ6��20λ"});
	$("#pwdconfirm").formValidator({onshow:"���ٴ���������",onfocus:"������ȷ������",oncorrect:"����������ͬ"}).compareValidator({desid:"password",operateor:"=",onerror:"�����������벻ͬ"});
		
	$("#email").formValidator({onshow:"������email",onfocus:"������email",oncorrect:"email��ʽ��ȷ"}).regexValidator({regexp:"email",datatype:"enum",onerror:"email��ʽ����"})
	$("#dbhost").formValidator({onshow:"���ݿ��������ַ, һ��Ϊ localhost",onfocus:"���ݿ��������ַ, һ��Ϊ localhost",oncorrect:"���ݿ��������ַ��ȷ",empty:false}).inputValidator({min:1,onerror:"���ݿ��������ַ����Ϊ��"});

  })
</script>
	<div class="body_box">
        <div class="main_box">
            <div class="hd">
            	<div class="bz a5"><div class="jj_bg"></div></div>
            </div>
            <div class="ct">
            	<div class="bg_t"></div>
                <div class="clr">
                    <div class="l"></div>
                    <div class="ct_box nobrd i6v">
                    <div class="nr">
			<form id="install" name="myform" action="install.php?" method="post">	
			<input type="hidden" name="step" value="6">	
            
<fieldset>
	<legend>��д���ݿ���Ϣ</legend>
	<div class="content">
    	<table width="100%" cellspacing="1" cellpadding="0" >
			<tr>
			<th width="20%" align="right" >���ݿ�������</th>
			<td>
			<input name="dbhost" type="text" id="dbhost" value="<?php echo $hostname?>" class="input-text" />
			</td>
			</tr>
			<tr>
			<th align="right">���ݿ��ʺţ�</th>
			<td><input name="dbuser" type="text" id="dbuser" value="<?php echo $username?>" class="input-text" /></td>
			</tr>
			<tr>
			<th align="right">���ݿ����룺</th>
			<td><input name="dbpw" type="password" id="dbpw" value="<?php echo $password?>" class="input-text" /></td>
			</tr>
			<tr>
			<th align="right">���ݿ����ƣ�</th>
			<td><input name="dbname" type="text" id="dbname" value="<?php echo $database?>" class="input-text" /></td>
			</tr>
			<tr>
			<th align="right">���ݱ�ǰ׺��</th>
			<td><input name="tablepre" type="text" id="tablepre" value="<?php echo $tablepre?>" class="input-text" />  <img src="./images/help.png" style="cursor:pointer;" title="���һ�����ݿⰲװ���phpcms�����޸ı�ǰ׺" align="absmiddle" />
			<span id='helptablepre'></span></td>
			</tr>
			<tr>
			<th align="right">���ݿ��ַ�����</th>
			<td>
			<input name="dbcharset" type="radio" id="dbcharset" value="" <?php if(strtolower($charset)=='') echo ' checked="checked" '?>/>Ĭ��
			<input name="dbcharset" type="radio" id="dbcharset" value="gbk" <?php if(strtolower($charset)=='gbk') echo '  checked="checked" '?> <?php if(strtolower($charset)=='utf8') echo 'disabled'?>/>GBK
			<input name="dbcharset" type="radio" id="dbcharset" value="utf8" <?php if(strtolower($charset)=='utf8') echo '  checked="checked" '?> <?php if(strtolower($charset)=='gbk') echo 'disabled'?>/>utf8 
			<input name="dbcharset" type="radio" id="dbcharset" value="latin1" <?php if(strtolower($charset)=='latin1') echo ' checked '?> />latin1 
			<img src="./images/help.png" style="cursor:pointer;" title="���Mysql�汾Ϊ4.0.x������ѡ��Ĭ�ϣ�&#10;���Mysql�汾Ϊ4.1.x�����ϣ�����ѡ�������ַ�����һ��ѡGBK��" align="absmiddle" />
			<span id='helpdbcharset'></span>
			</td>
			</tr>
			<tr>
			<th align="right">���ó־����ӣ�</th>
			<td><input name="pconnect" type="radio" id="pconnect" value="1" 
			<?php if($pconnect==1) echo '  checked="checked" '?>/>��&nbsp;&nbsp;
			<input name="pconnect" type="radio" id="pconnect" value="0" 
			<?php if($pconnect==0) echo '  checked="checked" '?>/>��
			<img src="./images/help.png" style="cursor:pointer;" title="������ó־����ӣ������ݿ������Ϻ��ͷţ�����һֱ����״̬����������ã���ÿ�����󶼻������������ݿ⣬ʹ�����Զ��ر����ӡ�" align="absmiddle" /><span id='helppconnect'></span>
			<span id='helptablepre'></span></td>
			</tr>
			</table>
    </div>
</fieldset>

<fieldset>
	<legend>��д�ʺ���Ϣ</legend>
	<div class="content">
    	<table width="100%" cellspacing="1" cellpadding="0">
			  <tr>
				<th width="20%" align="right">��������Ա�ʺţ�</th>
				<td><input name="username" type="text" id="username" value="phpcms" class="input-text" /></td>
			  </tr>
			  <tr>
				<th align="right">����Ա���룺</th>
				<td><input name="password" type="password" id="password" value="phpcms" class="input-text" /></td>
			  </tr>
			  <tr>
				<th align="right">ȷ�����룺</th>
				<td><input name="pwdconfirm" type="password" id="pwdconfirm" value="phpcms" class="input-text" /></td>
			  </tr>
			  <tr>
				<th align="right">����ԱE-mail��</th>
				<td><input name="email" type="text" id="email" class="input-text" />
					<input type="hidden" name="selectmod" value="<?php echo $selectmod?>" />
					<input type="hidden" name="testdata" value="<?php echo $testdata?>" />
					<input type="hidden" id="install_phpsso" name="install_phpsso" value="<?php echo $install_phpsso?>" />
			  </tr>

			</table>
    </div>
</fieldset>
			</form>
                   </div>
                   </div>
                </div>
                <div class="bg_b"></div>
            </div>
            <div class="btn_box"><a href="javascript:history.go(-1);" class="s_btn pre">��һ��</a><a href="javascript:void(0);"  onClick="checkdb();return false;" class="x_btn">��һ��</a></div>
        </div>
    </div>
</body>
</html>
<script language="JavaScript">
<!--
var errmsg = new Array();
errmsg[0] = '���Ѿ���װ��Phpcms��ϵͳ���Զ�ɾ�������ݣ��Ƿ������';
errmsg[2] = '�޷��������ݿ���������������ã�';
errmsg[3] = '�ɹ��������ݿ⣬����ָ�������ݿⲻ���ڲ����޷��Զ�����������ͨ��������ʽ�������ݿ⣡';
errmsg[6] = '���ݿ�汾����Mysql 4.0���޷���װPhpcms�����������ݿ�汾��';

function checkdb() 
{
	var url = '?step=dbtest&dbhost='+$('#dbhost').val()+'&dbuser='+$('#dbuser').val()+'&dbpw='+$('#dbpw').val()+'&dbname='+$('#dbname').val()+'&tablepre='+$('#tablepre').val()+'&sid='+Math.random()*5;
    $.get(url, function(data){
		if(data > 1) {
			alert(errmsg[data]);
			return false;
		}
		else if(data == 1 || (data == 0 && confirm(errmsg[0]))) {
			$('#install').submit();
		}
	});
    return false;
}
//-->
</script>