<?php include PHPCMS_PATH.'install/step/header.tpl.php';?>
<script type="text/javascript">
  $(document).ready(function() {
	$.formValidator.initConfig({autotip:true,formid:"install",onerror:function(msg){}});
  	$("input:radio[name='install_phpsso']").formValidator({relativeid:"install_phpsso_2",tipid:"aiguoTip",tipcss :{"left":"60px"},onshow:"��ѡ��һ����װ����",onfocus:"��ѡ��һ����װ����",oncorrect:"ѡ�����"}).inputValidator({min:1,max:1,onerror:"��ѡ��һ����װ����"});
	$("#sso_url").formValidator({onshow:"������phpsso��ַ��������'/'����",onfocus:"������phpsso��ַ��������'/'����",empty:false}).inputValidator({onerror:"��ַ������'/'����"}).regexValidator({regexp:"http:\/\/(.+)\/$",onerror:"��ַ������'/'����"});	
  })
</script>
	<div class="body_box">
        <div class="main_box">
            <div class="hd">
            	<div class="bz a3"><div class="jj_bg"></div></div>
            </div>
            <div class="ct">
            	<div class="bg_t"></div>
                <div class="clr">
                    <div class="l"></div>
                    <div class="ct_box nobrd i6v">
                    <div class="nr">
					<form id="install" action="install.php?" method="post">
					<input type="hidden" name="step" value="4">
<fieldset>
	<legend>PHPSSO����</legend>
	<div class="content">
    	<input type="radio" name="install_phpsso" id="install_phpsso_1" value="1" onclick="set_sso_hidden()">&nbsp;&nbsp;ȫ�°�װPHPCMS V9 (�� PHPSSO)<br/>
        <input type="radio" name="install_phpsso" id="install_phpsso_2" value="2" onclick="set_sso()">&nbsp;&nbsp;����װPHPCMS V9 (�ֹ�ָ���Ѿ���װ��PHPSSO)
    </div>
	<div id="sso_cfg" class="d_n">
		<ul>
			<li>Phpsso��  ַ:<input type="text" name="sso[sso_url]" id="sso_url" value="http://127.0.0.1/phpsso_server/" class="w260"></li>
			<li>Phpsso�û���:<input type="text" name="sso[username]" value=""></li>
			<li>Phpsso��  ��:<input type="password" name="sso[password]" value=""></li>
		</ul>
	</div>
</fieldset>					
<fieldset>
	<legend>��ѡģ��</legend>
	<div class="content">
    	<label><input type="checkbox" name="admin" value="admin" checked disabled>��̨����ģ��</label>
        <label><input type="checkbox" name="content" value="content" checked disabled>����ģ��</label>
        <label><input type="checkbox" name="member" value="member" checked  disabled>��Աģ��</label>
       <label><input type="checkbox" name="pay" value="pay" checked  disabled>����ģ��</label>
       <label><input type="checkbox" name="special" value="special" checked  disabled>ר��ģ��</label>
       <label><input type="checkbox" name="search" value="search" checked  disabled>ȫ������</label>
	   <label><input type="checkbox" name="phpsso" value="phpsso" checked  disabled>PHPSSO</label>
    </div>
</fieldset>

<fieldset>
	<legend>��ѡģ��</legend>
	<div class="content"> 
<?php
	$count = count($PHPCMS_MODULES['name']);
	$j = 0;
	foreach($PHPCMS_MODULES['name'] as  $i=>$module)
	{
		if($j%5==0) echo "<tr >";
	?>
	<label><input type="checkbox" name="selectmod[]" value="<?php echo $module?>" checked><?php echo $PHPCMS_MODULES['modulename'][$i]?>ģ��</label>
	<?php
		if($j%5==4) echo "</tr>";
	$j++;
	}
?>
    </div>
</fieldset>
<fieldset>
	<legend>��ѡ����</legend>
	<div class="content">
    	<label style="width:auto"><input type="checkbox" name="testdata" value="1" checked>Ĭ�ϲ������� ���������ֺ͵����û���</label>
    </div>
</fieldset>
					</form>
					</div>
                    </div>
                </div>
                <div class="bg_b"></div>
            </div>
            <div class="btn_box"><a href="javascript:history.go(-1);" class="s_btn pre">��һ��</a><a href="javascript:void(0);"  onClick="$('#install').submit();return false;" class="x_btn">��һ��</a></div>
        </div>
    </div>
</body>
</html>
<script type="text/javascript">
	function set_sso() {
		$("#sso_cfg").show();
	}
	function set_sso_hidden() {
		$("#sso_cfg").hide();
	}	
</script>