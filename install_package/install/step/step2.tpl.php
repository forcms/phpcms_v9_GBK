<?php include PHPCMS_PATH.'install/step/header.tpl.php';?>
	<div class="body_box">
        <div class="main_box">
            <div class="hd">
            	<div class="bz a2"><div class="jj_bg"></div></div>
            </div>
            <div class="ct">
            	<div class="bg_t"></div>
                <div class="clr">
                    <div class="l"></div>
                    <div class="ct_box nobrd i6v">
                    <div class="nr">
	 <table cellpadding="0" cellspacing="0" class="table_list">
                  <tr>
                    <th class="col1">�����Ŀ</th>
                    <th class="col2">��ǰ����</th>
                    <th class="col3">PHPCMS ����</th>
                    <th class="col4">����Ӱ��</th>
                  </tr>
                  <tr>
                    <td>����ϵͳ</td>
                    <td><?php echo php_uname();?></td>
                    <td>Windows_NT/Linux/Freebsd</td>
                    <td><span><img src="images/correct.gif" /></span></td>
                  </tr>
                  <tr>
                    <td>WEB ������</td>
                    <td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
                    <td>Apache/Nginx/IIS</td>
                    <td><span><img src="images/correct.gif" /></span></td>
                  </tr>
                  <tr>
                    <td>PHP �汾</td>
                    <td>PHP <?php echo phpversion();?></td>
                    <td>PHP 5.2.0 ������</td>
                    <td><?php if(phpversion() >= '5.2.0'){ ?><span><img src="images/correct.gif" /></span><?php }else{ ?><font class="red"><img src="images/error.gif" />&nbsp;�޷���װ</font><?php }?></font></td>
                  </tr>
                  <tr>
                    <td>MYSQL ��չ</td>
                    <td><?php if(extension_loaded('mysql')){ ?>��<?php }else{ ?>��<?php }?></td>
                    <td>���뿪��</td>
                    <td><?php if(extension_loaded('mysql')){ ?><span><img src="images/correct.gif" /></span><?php }else{ ?><font class="red"><img src="images/error.gif" />&nbsp;�޷���װ</font><?php }?></td>
                  </tr>
                  
                  <tr>
                    <td>ICONV/MB_STRING ��չ</td>
                    <td><?php if(extension_loaded('iconv') || extension_loaded('mbstring')){ ?>��<?php }else{ ?>��<?php }?></td>
                    <td>���뿪��</td>
                    <td><?php if(extension_loaded('iconv') || extension_loaded('mbstring')){ ?><span><img src="images/correct.gif" /></span><?php }else{ ?><font class="red"><img src="images/error.gif" />&nbsp;�ַ���ת��Ч�ʵ�</font><?php }?></td>
                  </tr>
                  
                  <tr>
                    <td>JSON��չ</td>
                    <td><?php if($PHP_JSON){ ?>��<?php }else{ ?>��<?php }?></td>
                    <td>���뿪��</td>
                    <td><?php if($PHP_JSON){ ?><span><img src="images/correct.gif" /></span><?php }else{ ?><font class="red"><img src="images/error.gif" />&nbsp;��ֻ��json,<a href="http://pecl.php.net/package/json" target="_blank">��װ PECL��չ</a></font><?php }?></td>
                  </tr>
                  <tr>
                    <td>GD ��չ</td>
                    <td><?php if($PHP_GD){ ?>�� ��֧�� <?php echo $PHP_GD;?>��<?php }else{ ?>��<?php }?></td>
                    <td>���鿪��</td>
                    <td><?php if($PHP_GD){ ?><span><img src="images/correct.gif" /></span><?php }else{ ?><font class="red"><img src="images/error.gif" />&nbsp;��֧������ͼ��ˮӡ</font><?php }?></td>
                  </tr>                                    
                  <tr>
                    <td>ZLIB ��չ</td>
                    <td><?php if(extension_loaded('zlib')){ ?>��<?php }else{ ?>��<?php }?></td>
                    <td>���鿪��</td>
                    <td><?php if(extension_loaded('zlib')){ ?><span><img src="images/correct.gif" /></span><?php }else{ ?><font class="red"><img src="images/error.gif" />&nbsp;��֧��Gzip����</font><?php }?></td>
                  </tr>
                  <tr>
                    <td>FTP ��չ</td>
                    <td><?php if(extension_loaded('ftp')){ ?>��<?php }else{ ?>��<?php }?></td>
                    <td>���鿪��</td>
                    <td><?php if(extension_loaded('ftp')){ ?><span><img src="images/correct.gif" /></span><?php }elseif(ISUNIX){ ?><font class="red"><img src="images/error.gif" />&nbsp;��֧��FTP��ʽ�ļ�����</font><?php }?></td>
                  </tr>
                                    
                  <tr>
                    <td>allow_url_fopen</td>
                    <td><?php if(ini_get('allow_url_fopen')){ ?>��<?php }else{ ?>��<?php }?></td>
                    <td>�����</td>
                    <td><?php if(ini_get('allow_url_fopen')){ ?><span><img src="images/correct.gif" /></span><?php }else{ ?><font class="red"><img src="images/error.gif" />&nbsp;��֧�ֱ���Զ��ͼƬ</font><?php }?></td>
                  </tr>
				  
				  <tr>
                    <td>fsockopen</td>
                    <td><?php if(ini_get('fsockopen')){ ?>��<?php }else{ ?>��<?php }?></td>
                    <td>�����</td>
                    <td><?php if($PHP_FSOCKOPEN=='1'){ ?><span><img src="images/correct.gif" /></span><?php }else{ ?><font class="red"><img src="images/error.gif" />&nbsp;��֧��fsockopen����</font><?php }?></td>
                  </tr>
				  
                  <tr>
                    <td>DNS����</td>
                    <td><?php if($PHP_DNS){ ?>��<?php }else{ ?>��<?php }?></td>
                    <td>����������ȷ</td>
                    <td><?php if($PHP_DNS){ ?><span><img src="images/correct.gif" /></span><?php }else{ ?><font class="red"><img src="images/error.gif" />&nbsp;��֧�ֲɼ��ͱ���Զ��ͼƬ</font><?php }?></td>
                  </tr>
                </table>
 					</div>
                    </div>
                </div>
                <div class="bg_b"></div>
            </div>
            <div class="btn_box"><a href="javascript:history.go(-1);" class="s_btn pre">��һ��</a>
            <?php if($is_right) { ?>
            <a href="javascript:void(0);"  onClick="$('#install').submit();return false;" class="x_btn">��һ��</a></div>
            <?php }else{ ?>
			<a onClick="alert('��ǰ���ò�����Phpcms��װ�����޷�������װ��');" class="x_btn pre">��ⲻͨ��</a>
 			<?php }?>
			<form id="install" action="install.php?" method="get">
			<input type="hidden" name="step" value="3">
			</form>
        </div>
    </div>
</body>
</html>
