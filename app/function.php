<?php
/**
 * FlxPHP 2.2
 * Author:FlxSNX<211154860@qq.com>
 * [FlxPHP] - 应用函数
 */

function get_url($url,$post=false,$cookie=false,$header=false,$split=false,$referer=false){//curl
	$ch = curl_init();
	if($header){
		curl_setopt($ch,CURLOPT_HEADER, 1);
	}else{
		curl_setopt($ch,CURLOPT_HEADER, 0);
	}
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.62 Safari/537.36');//设置UA 否则会被拦截
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));
    }
    if($cookie){
		curl_setopt($ch, CURLOPT_COOKIE,$cookie);
    }
    if($referer){
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }
	$result = curl_exec($ch);
	if($split){
		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($result, 0, $headerSize);
		$body = substr($result, $headerSize);
		$result=array();
		$result['header']=$header;
		$result['body']=$body;
	}
	curl_close($ch);
	return $result;
}

function is_post(){
    return isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD'])=='POST';
}

function is_get(){
    return isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD'])=='GET';
}

function is_ajax(){
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'])=='XMLHTTPREQUEST';
}

/**
 * 获取IP
 */
function real_ip()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}