<?php defined('IN_PHPCMS') or exit('No permission resources.');?>
<table cellpadding="2" cellspacing="1" width="98%">
	<tr> 
      <td>�����ϴ����ļ�����</td>
      <td><input type="text" name="setting[upload_allowext]" value="<?php echo $setting['upload_allowext'];?>" size="40" class="input-text"></td>
    </tr>
	<tr> 
      <td>�Ƿ�����ϴ���ѡ��</td>
      <td><input type="radio" name="setting[isselectimage]" value="1" <?php if($setting['isselectimage']) echo 'checked';?>> �� <input type="radio" name="setting[isselectimage]" value="0" <?php if(!$setting['isselectimage']) echo 'checked';?>> ��</td>
    </tr>
	<tr> 
      <td>����ͬʱ�ϴ��ĸ���</td>
      <td><input type="text" name="setting[upload_number]" value="<?php echo $setting['upload_number'];?>" size=3></td>
    </tr>
	<tr>
	<td>�ļ����ӷ�ʽ</td>
	<td>
      	<input name="setting[downloadlink]" value="0" type="radio" <?php if(!$setting['downloadlink']) echo 'checked';?>>
        ���ӵ���ʵ�����ַ 
        <input name="setting[downloadlink]" value="1" type="radio" <?php if($setting['downloadlink']) echo 'checked';?>>
        ���ӵ���תҳ��
      
	</td>
	</tr>
	<tr>
	<td>�ļ����ط�ʽ</td>
	<td>
      	<input name="setting[downloadtype]" value="0" type="radio" <?php if(!$setting['downloadtype']) echo 'checked';?>>
        �����ļ���ַ 
        <input name="setting[downloadtype]" value="1" type="radio" <?php if($setting['downloadtype']) echo 'checked';?>>
        ͨ��PHP��ȡ
      
	</td>
	</tr>	
</table>