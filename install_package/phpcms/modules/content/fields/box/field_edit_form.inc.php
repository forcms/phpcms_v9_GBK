<?php defined('IN_PHPCMS') or exit('No permission resources.');?>
<table cellpadding="2" cellspacing="1" width="98%">
	<tr> 
      <td width="100">ѡ���б�</td>
      <td><textarea name="setting[options]" rows="2" cols="20" id="options" style="height:100px;width:200px;"><?php echo $setting['options'];?></textarea></td>
    </tr>
	<tr> 
      <td>ѡ������</td>
      <td>
	  <input type="radio" name="setting[boxtype]" value="radio" <?php if($setting['boxtype']=='radio') echo 'checked';?> onclick="$('#setcols').show();$('#setsize').hide();"/> ��ѡ��ť 
	  <input type="radio" name="setting[boxtype]" value="checkbox" <?php if($setting['boxtype']=='checkbox') echo 'checked';?> onclick="$('#setcols').show();$('setsize').hide();"/> ��ѡ�� 
	  <input type="radio" name="setting[boxtype]" value="select" <?php if($setting['boxtype']=='select') echo 'checked';?> onclick="$('#setcols').hide();$('setsize').show();" /> ������ 
	  <input type="radio" name="setting[boxtype]" value="multiple" <?php if($setting['boxtype']=='multiple') echo 'checked';?> onclick="$('#setcols').hide();$('setsize').show();" /> ��ѡ�б��
	  </td>
    </tr>
	<tr> 
      <td>�ֶ�����</td>
      <td>
	  <select name="setting[fieldtype]" onchange="javascript:fieldtype_setting(this.value);">
	  <option value="varchar" <?php if($setting['fieldtype']=='varchar') echo 'selected';?>>�ַ� VARCHAR</option>
	  <option value="tinyint" <?php if($setting['fieldtype']=='tinyint') echo 'selected';?>>���� TINYINT(3)</option>
	  <option value="smallint" <?php if($setting['fieldtype']=='smallint') echo 'selected';?>>���� SMALLINT(5)</option>
	  <option value="mediumint" <?php if($setting['fieldtype']=='mediumint') echo 'selected';?>>���� MEDIUMINT(8)</option>
	  <option value="int" <?php if($setting['fieldtype']=='int') echo 'selected';?>>���� INT(10)</option>
	  </select> <span id="minnumber" style="display:none"><input type="radio" name="setting[minnumber]" value="1" <?php if($setting['minnumber']==1) echo 'checked';?>/> <font color='red'>������</font> <input type="radio" name="setting[minnumber]" value="-1" <?php if($setting['minnumber']==-1) echo 'checked';?>/> ����</span>
	  </td>
    </tr>
<tbody id="setcols" style="display:">
	<tr> 
      <td>ÿ�п��</td>
      <td><input type="text" name="setting[width]" value="<?php echo $setting['width'];?>" size="5" class="input-text"> px</td>
    </tr>
</tbody>
<tbody id="setsize" style="display:none">
	<tr> 
      <td>�߶�</td>
      <td><input type="text" name="setting[size]" value="<?php echo $setting['size'];?>" size="5" class="input-text"> ��</td>
    </tr>
</tbody>
	<tr> 
      <td>Ĭ��ֵ</td>
      <td><input type="text" name="setting[defaultvalue]" size="40" class="input-text" value="<?php echo $setting['defaultvalue'];?>"></td>
    </tr>
	<tr> 
      <td>�����ʽ</td>
      <td>
	  <input type="radio" name="setting[outputtype]" value="1" <?php if($setting['outputtype']) echo 'checked';?> /> ���ѡ��ֵ
	  <input type="radio" name="setting[outputtype]" value="0" <?php if(!$setting['outputtype']) echo 'checked';?> /> ���ѡ������
	  </td>
    </tr>
	<tr> 
      <td>�Ƿ���Ϊɸѡ�ֶ�</td>
      <td>
	  <input type="radio" name="setting[filtertype]" value="1" <?php if($setting['filtertype']) echo 'checked';?> /> �� 
	  <input type="radio" name="setting[filtertype]" value="0" <?php if(!$setting['filtertype']) echo 'checked';?>/> ��
	  </td>
    </tr>
</table>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function fieldtype_setting(obj) {
	if(obj!='varchar') {
		$('#minnumber').css('display','');
	} else {
		$('#minnumber').css('display','none');
	}
}
//-->
</SCRIPT>