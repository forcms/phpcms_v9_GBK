<?php
/**
 *���ܣ�֧�����ӿڹ��ú���
 *��ϸ����ҳ��������֪ͨ���������ļ������õĹ��ú������Ĵ����ļ�������Ҫ�޸�
 *�汾��3.0
 *�޸����ڣ�2010-05-24
 '˵����
 '���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 '�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

*/
 
/**
 * ����ǩ�����
 * @param $arrayҪ���ܵ�����
 * @param return ǩ������ַ���
*/
function build_mysign($sort_array,$security_code,$sign_type = "MD5") {
    $prestr = create_linkstring($sort_array);     	//����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
    $prestr = $prestr.$security_code;				//��ƴ�Ӻ���ַ������밲ȫУ����ֱ����������
    $mysgin = sign($prestr,$sign_type);			    //�����յ��ַ������ܣ����ǩ�����
    return $mysgin;
}	


/**
 * ����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
 * @param $array ��Ҫƴ�ӵ�����
 * @param return ƴ������Ժ���ַ���
*/
function create_linkstring($array) {
    $arg  = "";
    while (list ($key, $val) = each ($array)) {
        $arg.=$key."=".$val."&";
    }
    $arg = substr($arg,0,count($arg)-2);		     //ȥ�����һ��&�ַ�
    return $arg;
}

/********************************************************************************/

/**��ȥ�����еĿ�ֵ��ǩ������
 * @param $parameter ���ܲ�����
 * @param return ȥ����ֵ��ǩ����������¼��ܲ�����
 */
function para_filter($parameter) {
    $para = array();
    while (list ($key, $val) = each ($parameter)) {
        if($key == "sign" || $key == "sign_type" || $val == "")continue;
        else	$para[$key] = $parameter[$key];
    }
    return $para;
}

/********************************************************************************/

/**����������
 * @param $array ����ǰ������
 * @param return ����������
 */
function arg_sort($array) {
    ksort($array);
    reset($array);
    return $array;
}

/********************************************************************************/

/**�����ַ���
 * @param $prestr ��Ҫ���ܵ��ַ���
 * @param return ���ܽ��
 */
function sign($prestr,$sign_type) {
    $sign='';
    if($sign_type == 'MD5') {
        $sign = md5($prestr);
    }elseif($sign_type =='DSA') {
        //DSA ǩ����������������
        die(L('dsa', 'pay'));
    }else {
        die(L('alipay_error','pay'));
    }
    return $sign;
}

// ��־��Ϣ,��֧�������صĲ�����¼����
function  log_result($word) {
    $fp = fopen("log.txt","a");
    flock($fp, LOCK_EX) ;
    fwrite($fp, L('execute_date', 'pay')."��".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}	


/**ʵ�ֶ����ַ����뷽ʽ
 * @param $input ��Ҫ������ַ���
 * @param $_output_charset ����ı����ʽ
 * @param $_input_charset ����ı����ʽ
 * @param return �������ַ���
 */
function charset_encode($input,$_output_charset ,$_input_charset) {
    $output = "";
    if(!isset($_output_charset) )$_output_charset  = $_input_charset;
    if($_input_charset == $_output_charset || $input ==null ) {
        $output = $input;
    } elseif (function_exists("mb_convert_encoding")) {
        $output = mb_convert_encoding($input,$_output_charset,$_input_charset);
    } elseif(function_exists("iconv")) {
        $output = iconv($_input_charset,$_output_charset,$input);
    } else die("sorry, you have no libs support for charset change.");
    return $output;
}

/********************************************************************************/

/**ʵ�ֶ����ַ����뷽ʽ
 * @param $input ��Ҫ������ַ���
 * @param $_output_charset ����Ľ����ʽ
 * @param $_input_charset ����Ľ����ʽ
 * @param return �������ַ���
 */
function charset_decode($input,$_input_charset ,$_output_charset) {
    $output = "";
    if(!isset($_input_charset) )$_input_charset  = $_input_charset ;
    if($_input_charset == $_output_charset || $input ==null ) {
        $output = $input;
    } elseif (function_exists("mb_convert_encoding")) {
        $output = mb_convert_encoding($input,$_output_charset,$_input_charset);
    } elseif(function_exists("iconv")) {
        $output = iconv($_input_charset,$_output_charset,$input);
    } else die("sorry, you have no libs support for charset changes.");
    return $output;
}

/*********************************************************************************/

/**���ڷ����㣬���ýӿ�query_timestamp����ȡʱ����Ĵ�����
ע�⣺���ڵͰ汾��PHP���û�����֧��Զ��XML��������˱�������������ص�����װ�и߰汾��PHP���û��������鱾�ص���ʱʹ��PHP�������
 * @param $partner ���������ID
 * @param return ʱ����ַ���
*/
function query_timestamp($partner) {
    $URL = "https://mapi.alipay.com/gateway.do?service=query_timestamp&partner=".$partner;
	$encrypt_key = "";
    return $encrypt_key;
}
?>