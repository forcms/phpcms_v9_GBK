function confirmurl(url,message)
{
	if(confirm(message)) redirect(url);
}
function redirect(url) {
	if(url.indexOf('://') == -1 && url.substr(0, 1) != '/' && url.substr(0, 1) != '?') url = $('base').attr('href')+url;
	location.href = url;
}
//������
$(function(){
	//inputStyle
	$(":text").addClass('input-text');
})

/**
 * ȫѡcheckbox,ע�⣺��ʶcheckbox id�̶�ΪΪcheck_box
 * @param string name �б�check����,�� uid[]
 */
function selectall(name) {
	if ($("#check_box").attr("checked")==false) {
		$("input[name='"+name+"']").each(function() {
			this.checked=false;
		});
	} else {
		$("input[name='"+name+"']").each(function() {
			this.checked=true;
		});
	}
}