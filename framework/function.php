<?php
/**
 * FlxPHP 2.2
 * Author:FlxSNX<211154860@qq.com>
 * [FlxPHP] - 框架函数
 */

/**
 * 错误提示页
 */
function error_page($errorinfo = [])
{
    global $Flx;
    if($Flx->_CFG['debug'] == true){
        $errorinfo['title'] = !empty($errorinfo['title']) ? $errorinfo['title'] : 'BKM Framework Error';
        $errorinfo['info'] = !empty($errorinfo['info']) ? $errorinfo['info'] : '你的页面走丢辣(＞﹏＜)';
        $errorinfo['text'] = !empty($errorinfo['text']) ? $errorinfo['text'] : '请确认地址是否正确或返回首页';
    }else{
        $errorinfo['title'] = '啊哦~ 页面走丢辣~~~';
        $errorinfo['info'] = '你的页面走丢辣(＞﹏＜)';
        $errorinfo['text'] = '请确认地址是否正确或返回首页';
    }
    $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>$errorinfo[title]</title>
    <style>
        html,body,div,h1,* {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #fefefe;
            color: #333
        }

        .box {
            width: 580px;
            margin: 0 auto;
        }

        h1 {
            font-size: 17px;
            text-align: center;
            background: url(https://ae01.alicdn.com/kf/U84506e9e597f498784a4820c5eed4b4el.png) no-repeat top center;
            padding-top: 140px;
            margin-top: 30%;
            font-weight: 700;
        }

        p {
            font-size: 14px;
            text-align: center;
            line-height: 25px;
        }

    </style>
</head>
<body>
    <div class="box">
        <h1>$errorinfo[info]</h1>
        <p>$errorinfo[text]</p>
    </div>
</body>
</html>
HTML;
    exit($html);
}

/**
 * 将变量传入模板中
 */
function assign($name, $value = '')
{
    global $Flx;
    if (empty($Flx->_SYS['tplval'])) {
        $Flx->_SYS['tplval'] = [];
    }
    if (is_array($name)) {
        $Flx->_SYS['tplval'] = array_merge($Flx->_SYS['tplval'], $name);
    } else {
        $Flx->_SYS['tplval'][$name] = $value;
    }
}

/**
 * 显示模板
 */
function view($t = 'default')
{
    global $Flx;
    if (!empty($Flx->_SYS['tplval'])) extract($Flx->_SYS['tplval']);
    if ($t == 'default') {
        if (!file_exists(APPDIR . $Flx->_SYS['module'] . '/view/' . $Flx->_SYS['controller'] . '/' . $Flx->_SYS['action'] . '.php')) {
            error_page([
                'info' => '缺失' . $Flx->_SYS['controller'] . '/' . $Flx->_SYS['action'] . '模板',
                'text' => '请检查系统文件完整性或是否修改系统文件'
            ]);
        }
        include_once APPDIR . $Flx->_SYS['module'] . '/view/' . $Flx->_SYS['controller'] . '/' . $Flx->_SYS['action'] . '.php';
    } else {
        if (!file_exists(APPDIR . $Flx->_SYS['module'] . '/view/' . $t . '.php')) {
            error_page([
                'info' => '缺失' . $t . '模板',
                'text' => '请检查系统文件完整性或是否修改系统文件'
            ]);
        }
        include_once APPDIR . $Flx->_SYS['module'] . '/view/' . $t . '.php';
    }
}

/**
 * 模板路径助手函数
 */
function template($t)
{
    global $Flx;
    $template = APPDIR . $Flx->_SYS['module'] . '/view/' . $t . '.php';
    if (!file_exists($template)) {
        error_page([
            'info' => '缺失' . $t . '模板',
            'text' => '请检查系统文件完整性或是否修改系统文件'
        ]);
    }
    return $template;
}

/**
 *  过滤字符
 */
function authstr($string, $force = 0, $strip = FALSE)
{
    !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
    if (!MAGIC_QUOTES_GPC || $force) {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = authstr($val, $force, $strip);
            }
        } else {
            $string = addslashes($strip ? stripslashes($string) : $string);
        }
    }
    return $string;
}