<?php defined('IN_PHPCMS') or exit('No permission resources.');?>
<table cellpadding="2" cellspacing="1" width="98%">
	<tr> 
      <td width="225">�༭����ʽ��</td>
      <td><input type="radio" name="setting[toolbar]" value="basic" <?php if($setting['toolbar']=='basic') echo 'checked';?>>����� <input type="radio" name="setting[toolbar]" value="full" <?php if($setting['toolbar']=='full') echo 'checked';?>> ��׼�� </td>
    </tr>
	<tr> 
      <td>Ĭ��ֵ��</td>
      <td><textarea name="setting[defaultvalue]" rows="2" cols="20" id="defaultvalue" style="height:100px;width:250px;"><?php echo $setting['defaultvalue'];?></textarea></td>
    </tr>
	<tr> 
      <td>�Ƿ����ù������ӣ�</td>
      <td><input type="radio" name="setting[enablekeylink]" value="1" <?php if($setting['enablekeylink']==1) echo 'checked';?>> �� <input type="radio" name="setting[enablekeylink]" value="0" <?php if($setting['enablekeylink']==0) echo 'checked';?>> ��  <input type="text" name="setting[replacenum]" value="<?php echo $setting['replacenum'];?>" size="4" class="input-text"> �滻���� ��������Ϊ�滻ȫ����</td>
    </tr>
	<tr> 
      <td>�������ӷ�ʽ��</td>
      <td><input type="radio" name="setting[link_mode]" value="1" <?php if($setting['link_mode']==1) echo 'checked';?>> �ؼ������� <input type="radio" name="setting[link_mode]" value="0" <?php if($setting['link_mode']==0) echo 'checked';?>> ��ַ����  </td>
    </tr>
	<tr> 
      <td>�Ƿ񱣴�Զ��ͼƬ��</td>
      <td><input type="radio" name="setting[enablesaveimage]" value="1" <?php if($setting['enablesaveimage']==1) echo 'checked';?>> �� <input type="radio" name="setting[enablesaveimage]" value="0"  <?php if($setting['enablesaveimage']==0) echo 'checked';?>> ��</td>
    </tr>
	<tr> 
      <td>�༭��Ĭ�ϸ߶ȣ�</td>
      <td><input type="text" name="setting[height]" value="<?php echo $setting['height'];?>" size="4" class="input-text"> px</td>
    </tr>
	<tr> 
      <td>��ֹ��ʾ�༭���·��ķ�ҳ�����ӱ��⣺</td>
      <td><input type="radio" name="setting[disabled_page]" value="1" <?php if($setting['disabled_page']==1) echo 'checked';?>> ��ֹ <input type="radio" name="setting[disabled_page]" value="0" <?php if($setting['disabled_page']==0) echo 'checked';?>> ��ʾ</td>
    </tr>
</table>