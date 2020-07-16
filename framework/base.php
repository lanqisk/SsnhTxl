<?php
/**
 * FlxPHP 2.2
 * Author:FlxSNX<211154860@qq.com>
 * [FlxPHP] - 初始化
 */

//设置编码
header("Content-type: text/html; charset=utf-8");
//屏蔽NOTICE&WARNING报错
// error_reporting(E_ALL^E_WARNING^E_NOTICE);
//设置时区
date_default_timezone_set("PRC");
//根目录
define('ROOTDIR', dirname(__DIR__) . '/');
//应用目录
define('APPDIR', dirname(__DIR__) . '/app/');
//框架目录
define('SYSTEMDIR', __DIR__ . '/');
//扩展目录
define('EXTENDDIR', dirname(__DIR__). '/extend/');
//资源目录
define('ASSETSDIR', dirname(__DIR__). '/public/');
//框架版本
define('BKMPHP_VER','2.2 build2200');
//应用URL
define('APPURL',((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://'). $_SERVER['HTTP_HOST']);

include SYSTEMDIR.'function.php';

include SYSTEMDIR . 'flx.php';