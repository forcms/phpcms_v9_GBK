<?php defined('IN_PHPCMS') or exit('No permission resources.');?>
<table cellpadding="2" cellspacing="1" bgcolor="#ffffff">
	<tr> 
      <td><strong>ʱ���ʽ��</strong></td>
      <td>
	  <input type="radio" name="setting[fieldtype]" value="date" <?php if($setting['fieldtype']=='date') echo 'checked';?>>���ڣ�<?=date('Y-m-d')?>��<br />
	  <input type="radio" name="setting[fieldtype]" value="datetime_a" <?php if($setting['fieldtype']=='datetime_a') echo 'checked';?>>����+12Сʱ��ʱ�䣨<?=date('Y-m-d h:i:s')?>��<br />
	  <input type="radio" name="setting[fieldtype]" value="datetime" <?php if($setting['fieldtype']=='datetime') echo 'checked';?>>����+24Сʱ��ʱ�䣨<?=date('Y-m-d H:i:s')?>��<br />
	  <input type="radio" name="setting[fieldtype]" value="int" <?php if($setting['fieldtype']=='int') echo 'checked';?>>���� ��ʾ��ʽ��
	  <select name="setting[format]">
	  <option value="Y-m-d Ah:i:s" <?php if($setting['format']=='Y-m-d Ah:i:s') echo 'selected';?>>12Сʱ��:<?php echo date('Y-m-d h:i:s')?></option>
	  <option value="Y-m-d H:i:s" <?php if($setting['format']=='Y-m-d H:i:s') echo 'selected';?>>24Сʱ��:<?php echo date('Y-m-d H:i:s')?></option>
	  <option value="Y-m-d H:i" <?php if($setting['format']=='Y-m-d H:i') echo 'selected';?>><?php echo date('Y-m-d H:i')?></option>
	  <option value="Y-m-d" <?php if($setting['format']=='Y-m-d') echo 'selected';?>><?php echo date('Y-m-d')?></option>
	  <option value="m-d" <?php if($setting['format']=='m-d') echo 'selected';?>><?php echo date('m-d')?></option>
	  </select>
	  </td>
    </tr>
	<tr> 
      <td><strong>Ĭ��ֵ��</strong></td>
      <td>
	  <input type="radio" name="setting[defaulttype]" value="0" checked/>��<br />
	 </td>
    </tr>
</table>