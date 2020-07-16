<?php
/* get检查 */
if(!empty($_GET)){
    $result  = intercept($_GET,$rules['get']);
    if($result !== false)intercept_view();
}

/* post检查 */
if(!empty($_POST)){
    $result  = intercept($_POST,$rules['post']);
    if($result !== false)intercept_view();
}

/* cookie检查 */
if(!empty($_COOKIE)){
    $result  = intercept($_COOKIE,$rules['cookie']);
    if($result !== false)intercept_view();
}

/* useragent检查 */
if(!empty($_SERVER['HTTP_USER_AGENT'])){
    $result  = intercept($_SERVER['HTTP_USER_AGENT'],$rules['ua']);
    if($result !== false)intercept_view();
}