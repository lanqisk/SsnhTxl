<?php
/**
 * FlxPHP防火墙 Ver1.0
 * 基于ngx_lua_waf的拦截规则制作的php应用防火墙
 * Author:拾年<211154860@qq.com>
 */

//防火墙根目录
define('WAFDIR', __DIR__ . '/');

require 'functions.php';/* 加载函数 */
require 'config.php';/* 加载配置 */
require 'rules/load.php';/* 加载规则 */
require 'interceptor.php';/* 加载拦截器 */

//执行完毕清除变量
unset($rules,$result);