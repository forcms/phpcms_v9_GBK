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
      <td>�Ƿ������ϴ�������</td>
      <td><input type="radio" name="setting[allowupload]" value="1" <?php if($setting['allowupload']==1) echo 'checked';?>> �� <input type="radio" name="setting[allowupload]" value="0"  <?php if($setting['allowupload']==0) echo 'checked';?>> ��</td>
    </tr>
	<tr> 
      <td>�Ƿ񱣴�Զ��ͼƬ��</td>
      <td><input type="radio" name="setting[enablesaveimage]" value="1" <?php if($setting['enablesaveimage']==1) echo 'checked';?>> �� <input type="radio" name="setting[enablesaveimage]" value="0"  <?php if($setting['enablesaveimage']==0) echo 'checked';?>> ��</td>
    </tr>
	<tr> 
      <td>�༭��Ĭ�ϸ߶ȣ�</td>
      <td><input type="text" name="setting[height]" value="<?php echo $setting['height'];?>" size="4" class="input-text"> px</td>
    </tr>

</table>