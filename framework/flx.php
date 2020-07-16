<?php
/**
 * FlxPHP 2.2
 * Author:FlxSNX<211154860@qq.com>
 * [FlxPHP] - 请求分发
 */

namespace FlxPHP;
 class Flx{
    public $_CFG;//config下所有配置
    public $_SYS;//系统相关变量

    public function run(){
         //设置加载目录
        set_include_path(APPDIR.PATH_SEPARATOR.EXTENDDIR.PATH_SEPARATOR.ROOTDIR.PATH_SEPARATOR.get_include_path());

        //注册自动加载
        spl_autoload_register(function($classname){
            if(substr($classname,0,3) == 'app'){
                $classname = substr($classname,4);
            }
            $classname = str_replace("\\","/",$classname);
            include_once $classname . '.php';
        });

        /* //屏蔽腾讯电脑管家模块
        include SYSTEMDIR.'txprotect.php'; */

        //临时防火墙模块
        include SYSTEMDIR.'waf/init.php';

        //检测加载应用目录的函数文件
        if(file_exists(APPDIR.'function.php'))include APPDIR.'function.php';

        //加载配置
        $this->loadcfg();

        //处理请求
        $this->request();
    }

    private function request(){
        $s = !empty($_GET['s']) ? $_GET['s'] : '/';
        //去除首尾的/
        if($s != '/' and substr($s,0,1) == '/'){
            $s = substr($s,1);
        }
        if($s != '/' and substr($s,-1) == '/'){
            $s = substr($s,0,-1);
        }
        
        //检查模块路由文件
        if(file_exists(APPDIR.'route.php')){
            //获取路由
            $this->_SYS['route'] = include APPDIR.'route.php';
            
            //解析路由
            $i = 0;
            foreach ($this->_SYS['route'] as $k => $v){
                if(!preg_match('/{(.+)}/',$k)){
                    $routes[$i]['type'] = 1;
                }else{
                    $routes[$i]['type'] = 2;
                }
                $routes[$i]['param'] = $k;
                $routes[$i]['method'] = $v;
                $i++;
            }
        
            //匹配路由
            foreach($routes as $route){
                if($route['type'] == 2){
                    //转义+去除{}
                    $route['param'] = str_replace('/','\\/',$route['param']);
                    $route['param'] = str_replace('{','(',$route['param']);
                    $route['param'] = str_replace('}',')',$route['param']);
                    $pattern = '/^'.$route['param'].'$/';
                    if(preg_match($pattern,$s,$val)){//匹配成功执行方法(method)
                        //解析method
                        $route['method'] = explode('@',$route['method']);
                        $this->_SYS['controller'] = $route['method'][0];
                        $this->_SYS['action'] = $route['method'][1];
                        //实例化类并执行对应的类方法
                        $run_class_name = 'app\\controller\\'. $this->_SYS['controller'];
                        $run_action = $this->_SYS['action'];
                        $run = new $run_class_name;
                        if(method_exists($run,$run_action)) {//检查是否存在对应的方法
                            //检查是否存在__init方法 用于防止子类重写父类的__construct方法
                            if(method_exists($run,'__init'))$run->__init();
                            $run->$run_action(...$val);
                        }else{
                            error_page([
                                'info' => $this->_SYS['module'].'模块的'.$this->_SYS['controller'].'控制器缺少'.$this->_SYS['action'].'方法',
                                'text' => APPDIR.$this->_SYS['module'].'/'.$this->_SYS['controller'].'.php'.'缺少'.$this->_SYS['action'].'方法'
                            ]);
                        }
                    }
                }else{
                    if($s == $route['param']){
                        //解析method
                        $route['method'] = explode('@',$route['method']);
                        $this->_SYS['controller'] = $route['method'][0];
                        $this->_SYS['action'] = $route['method'][1];
                        //实例化类并执行对应的类方法
                        $run_class_name = 'app\\controller\\'. $this->_SYS['controller'];
                        $run_action = $this->_SYS['action'];
                        $run = new $run_class_name;
                        if(method_exists($run,$run_action)) {//检查是否存在对应的方法
                            //检查是否存在__init方法 用于防止子类重写父类的__construct方法
                            if(method_exists($run,'__init'))$run->__init();
                            $run->$run_action($route['param']);
                        }else{
                            error_page([
                                'info' => $this->_SYS['module'].'模块的'.$this->_SYS['controller'].'控制器缺少'.$this->_SYS['action'].'方法',
                                'text' => APPDIR.$this->_SYS['module'].'/'.$this->_SYS['controller'].'.php'.'缺少'.$this->_SYS['action'].'方法'
                            ]);
                        }
                    }
                }
            }
            //未寻找到匹配项显示404错误页面
            if(!$run)error_page(['info' => '未匹配到路由',]);
        }else{
            error_page([
                'info' => '应用缺失route',
                'text' => '缺失'.APPDIR.'route.php'.'文件'
            ]);
        }
    }

    private function loadcfg(){
        $this->_CFG = [];
        /**
         * 获取指定目录所有配置文件
         */
        function get_cfg($path)
        {
            if (!file_exists($path)) {
                error_page([
                    'info' => '检测到系统已损坏,请重新部署应用!',
                    'text' => '请检查系统文件完整性或是否修改系统文件'
                ]);
            }
            $cfg = [];
            chdir($path);
            $cfgs = glob('*.cfg.php');
            if ($cfgs) {
                foreach ($cfgs as $c) {
                    $content = include($path . $c);
                    if (is_array($content)) {
                        $cfg = array_merge($content, $cfg);
                    }
                }
                return $cfg;
            } else {
                return [];
            }
        }
        
        //获取系统配置
        $syscfg = get_cfg(ROOTDIR . 'config/');
        //获取应用配置
        $appcfg = get_cfg(APPDIR);
        //合并配置数组
        $this->_CFG = array_merge($syscfg, $appcfg, $this->_CFG);
    }
 }