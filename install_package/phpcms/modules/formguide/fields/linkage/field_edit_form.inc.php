<?php defined('IN_PHPCMS') or exit('No permission resources.');?>
<table cellpadding="2" cellspacing="1" width="98%">
	<tr> 
      <td>�˵�ID</td>
      <td><input type="text" id="linkageid" name="setting[linkageid]" value="<?php echo $setting['linkageid'];?>" size="5"> 
	  <input type='button' value="���б���ѡ��" onclick="omnipotent('selectid','?m=admin&c=linkage&a=public_get_list','���б���ѡ��')" class="button">
		�뵽���� ��չ > �����˵� > ��������˵�</td>
    </tr>
	<tr>
	<td>��ʾ��ʽ</td>
	<td>
      	<input name="setting[showtype]" value="0" type="radio" <?php if($setting['showtype']==0) echo 'checked';?>>
        ֻ��ʾ����
        <input name="setting[showtype]" value="1" type="radio" <?php if($setting['showtype']==1) echo 'checked';?>>
        ��ʾ����·��  
        <input name="setting[showtype]" value="2" type="radio" <?php if($setting['showtype']==2) echo 'checked';?>>
        ���������˵�id 		
	</td></tr>
	<tr> 
      <td>·���ָ���</td>
      <td><input type="text" name="setting[space]" value="<?php echo $setting['space'];?>" size="5" class="input-text"> ��ʾ����·��ʱ��Ч</td>
    </tr>	
</table>