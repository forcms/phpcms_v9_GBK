<?php defined('IN_PHPCMS') or exit('No permission resources.');?>
<table cellpadding="2" cellspacing="1" width="98%">
	<tr> 
      <td width="100">�ı��򳤶�</td>
      <td><input type="text" name="setting[size]" value="<?php echo $setting['size'];?>" size="10" class="input-text"></td>
    </tr>
	<tr> 
      <td>Ĭ��ֵ</td>
      <td><input type="text" name="setting[defaultvalue]" value="<?php echo $setting['defaultvalue'];?>" size="40" class="input-text"></td>
    </tr>
	<tr> 
      <td>����ʾģʽ</td>
      <td><input type="radio" name="setting[show_type]" value="1" <?php if($setting['show_type']) echo 'checked';?>/> ͼƬģʽ <input type="radio" name="setting[show_type]" value="0" <?php if(!$setting['show_type']) echo 'checked';?>/> �ı���ģʽ</td>
    </tr>
	<tr> 
      <td>�����ϴ���ͼƬ��С</td>
      <td><input type="text" name="setting[upload_maxsize]" value="<?php echo $setting['upload_maxsize'];?>" size="5" class="input-text">KB ��ʾ��1KB=1024Byte��1MB=1024KB *</td>
    </tr>
	<tr> 
      <td>�����ϴ���ͼƬ����</td>
      <td><input type="text" name="setting[upload_allowext]" value="<?php echo $setting['upload_allowext'];?>" size="40" class="input-text"></td>
    </tr>
	<tr> 
      <td>�Ƿ�����ϴ���ѡ��</td>
      <td><input type="radio" name="setting[isselectimage]" value="1" <?php if($setting['isselectimage']) echo 'checked';?>> �� <input type="radio" name="setting[isselectimage]" value="0" <?php if(!$setting['isselectimage']) echo 'checked';?>> ��</td>
    </tr>
	<tr> 
      <td>ͼ���С</td>
      <td>�� <input type="text" name="setting[images_width]" value="<?php echo $setting['images_width'];?>" size="3">px �� <input type="text" name="setting[images_height]" value="<?php echo $setting['images_height'];?>" size="3">px</td>
    </tr>
</table>