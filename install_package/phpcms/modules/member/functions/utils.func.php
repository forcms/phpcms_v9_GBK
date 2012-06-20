<?php
/**
 * PHP SDK for QQ��¼ OpenAPI
 *
 * @version 1.3
 * @author connect@qq.com
 * @copyright ? 2011, Tencent Corporation. All rights reserved.
 */

/**
 * @brief ���ļ�������OAuth��֤�����л��õ��Ĺ��÷��� 
 */


/**
 * @brief �Բ��������ֵ���������
 *
 * @param $params �����б�
 *
 * @return �������&���ӵ�key-value�ԣ�key1=value1&key2=value2...)
 */
function get_normalized_string($params)
{
    ksort($params);
    $normalized = array();
    foreach($params as $key => $val)
    {
        $normalized[] = $key."=".$val;
    }

    return implode("&", $normalized);
}

/**
 * @brief ʹ��HMAC-SHA1�㷨����oauth_signatureǩ��ֵ 
 *
 * @param $key  ��Կ
 * @param $str  Դ��
 *
 * @return ǩ��ֵ
 */

function get_signature($str, $key)
{
    $signature = "";
    if (function_exists('hash_hmac'))
    {
        $signature = base64_encode(hash_hmac("sha1", $str, $key, true));
    }
    else
    {
        $blocksize	= 64;
        $hashfunc	= 'sha1';
        if (strlen($key) > $blocksize)
        {
            $key = pack('H*', $hashfunc($key));
        }
        $key	= str_pad($key,$blocksize,chr(0x00));
        $ipad	= str_repeat(chr(0x36),$blocksize);
        $opad	= str_repeat(chr(0x5c),$blocksize);
        $hmac 	= pack(
            'H*',$hashfunc(
                ($key^$opad).pack(
                    'H*',$hashfunc(
                        ($key^$ipad).$str
                    )
                )
            )
        );
        $signature = base64_encode($hmac);
    }

    return $signature;
} 

/**
 * @brief ���ַ�������URL���룬��ѭrfc1738 urlencode
 *
 * @param $params
 *
 * @return URL�������ַ���
 */
function get_urlencode_string($params)
{
    ksort($params);
    $normalized = array();
    foreach($params as $key => $val)
    {
        $normalized[] = $key."=".rawurlencode($val);
    }

    return implode("&", $normalized);
}

/**
 * @brief ���openid�Ƿ�Ϸ�
 *
 * @param $openid  ���û�QQ����һһ��Ӧ
 * @param $timestamp��ʱ���
 * @param $sig����ǩ��ֵ
 *
 * @return true or false
 */
function is_valid_openid($appkey,$openid, $timestamp, $sig)
{
    $key = $appkey;
    $str = $openid.$timestamp;
    $signature = get_signature($str, $key);

    //echo "sig:$sig\n";
    //echo "str:$str\n";

    return $sig == $signature; 
}

/**
 * @brief ����Get���󶼿���ʹ���������
 *
 * @param $url
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 * @return true or false
 */
function do_get($url, $appid, $appkey, $access_token, $access_token_secret, $openid)
{
    $sigstr = "GET"."&".rawurlencode("$url")."&";

    //��Ҫ����, ��Ҫ������!!
    $params = $_GET;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = time();
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);

    //����������ĸ���������л�
    $normalized_str = get_normalized_string($params);
    $sigstr        .= rawurlencode($normalized_str);

    //ǩ��,ȷ��php�汾֧��hash_hmac����
    $key = $appkey."&".$access_token_secret;
    $signature = get_signature($sigstr, $key);
    $url      .= "?".$normalized_str."&"."oauth_signature=".rawurlencode($signature);

    //echo "$url\n";
    return file_get_contents($url);
}

/**
 * @brief ����multi-part post ���󶼿���ʹ���������
 *
 * @param $url
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 */
function do_multi_post($url, $appid, $appkey, $access_token, $access_token_secret, $openid)
{
    //����ǩ����.Դ��:����[GET|POST]&uri&����������ĸ��������
    $sigstr = "POST"."&"."$url"."&";

    //��Ҫ����,��Ҫ������!!
    $params = $_POST;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = time();
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);


    //��ȡ�ϴ�ͼƬ��Ϣ
    foreach ($_FILES as $filename => $filevalue)
    {
        if ($filevalue["error"] != UPLOAD_ERR_OK)
        {
            //echo "upload file error $filevalue['error']\n";
            //exit;
        } 
        $params[$filename] = file_get_contents($filevalue["tmp_name"]);
    }

    //�Բ���������ĸ���������л�
    $sigstr .= get_normalized_string($params);

    //ǩ��,��Ҫȷ��php�汾֧��hash_hmac����
    $key = $appkey."&".$access_token_secret;
    $signature = get_signature($sigstr, $key);
    $params["oauth_signature"] = $signature; 

    //�����ϴ�ͼƬ
    foreach ($_FILES as $filename => $filevalue)
    {
        $tmpfile = dirname($filevalue["tmp_name"])."/".$filevalue["name"];
        move_uploaded_file($filevalue["tmp_name"], $tmpfile);
        $params[$filename] = "@$tmpfile";
    }

    /*
    echo "len: ".strlen($sigstr)."\n";
    echo "sig: $sigstr\n";
    echo "key: $appkey&\n";
    */

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);
    //$httpinfo = curl_getinfo($ch);
    //print_r($httpinfo);

    curl_close($ch);
    //ɾ���ϴ���ʱ�ļ�
    unlink($tmpfile);
    return $ret;

}


/**
 * @brief ����post ���󶼿���ʹ���������
 *
 * @param $url
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 */
function do_post($url, $appid, $appkey, $access_token, $access_token_secret, $openid)
{
    //����ǩ����.Դ��:����[GET|POST]&uri&����������ĸ��������
    $sigstr = "POST"."&".rawurlencode($url)."&";

    //��Ҫ����,��Ҫ������!!
    $params = $_POST;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = time();
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);

    //�Բ���������ĸ���������л�
    $sigstr .= rawurlencode(get_normalized_string($params));

    //ǩ��,��Ҫȷ��php�汾֧��hash_hmac����
    $key = $appkey."&".$access_token_secret;
    $signature = get_signature($sigstr, $key); 
    $params["oauth_signature"] = $signature; 

    $postdata = get_urlencode_string($params);

    //echo "$sigstr******\n";
    //echo "$postdata\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); 
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);

    curl_close($ch);
    return $ret;

}

?>
