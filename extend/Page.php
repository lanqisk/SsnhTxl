<?php
/**
 * FlxPHP 2.2
 * Author:FlxSNX<211154860@qq.com>
 * [FlxPHP] - 数据分页
 */

namespace extend;
class Page {
    public $page;//当前页数
    public $pages;//总页数
    public $pagesize;//每页显示的数量
    public $pagenum;//最大显示的页数
    public $start;//第几条开始取

    public function __construct($pages,$pagesize,$pagenum){
        $this->page =(isset($_GET['page'])&&$_GET['page']>0) ? intval($_GET['page']) : 1;
        $this->pages = ceil($pages/$pagesize);
        $this->pagesize = $pagesize;
        $this->pagenum = $pagenum;
        $this->start=($this->page-1)*$this->pagesize;
        if($this->pages == 0)$this->pages = 1;
    }

    public function getUrl(){
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $url = preg_replace('/\/page\/(\w)+/','',$url);
        return $url;
    }

    public function indexhtml(){
        $page = $this->page;
        $pages = $this->pages;
        if($page>$pages)$page=$pages;

        $p = $this->page;//$i的值
        $pn = $this->pagenum + $this->page;//循环最大值
        //这两个变量仅用于for循环
        if($pn >= $pages + $this->pagenum){
            $pn = $pages + 1;
        }elseif($pn >= $pages){
            $add = $pages - $page;
            $pn = $pages;
            $p = $p - $this->pagenum + $add;
        }
        if($p >= $pages){//当访问的页数大于总页数
            $p = $pages - $this->pagenum + 1;
        }
        if($p <= 0)$p = 1;
        if($pages - $page == 1)$pn++;
        $html = '';
        $url = $this->getUrl();
        if($page <= 1){
            $html.= '<a href="javascript:;" class="page-prev disabled"><i class="fa fa-angle-left"></i></a>';
        }else{
            $html.= '<a href="'.$url.'/page/'.($page-1).'" class="page-prev"><i class="fa fa-angle-left"></i></a>';
        }

        if($page >= 3){
            $html.= '<a href="'.$url.'/page/1" class="page-next">1</a><a href="javascript:;" class="page-next disabled">...</a>';
        }
        
        for($i=$p;$i<$pn;$i++){
            if($page == $i){
                $html.= '<a href="javascript:;" class="disabled">'.$i.'</a>';
            }else{
                $html.= '<a href="'.$url.'/page/'.$i.'">'.$i.'</a>';
            }
        }

        if($page <= $pages - 2){
            $html.= '<a href="javascript:;" class="page-next disabled">...</a><a href="'.$url.'/page/'.$pages.'" class="page-next">'.$pages.'</a>';
        }

        if($page >= $pages){
            $html.= '<a href="javascript:;" class="page-next disabled"><i class="fa fa-angle-right"></i></a>';
        }else{
            $html.= '<a href="'.$url.'/page/'.($page+1).'" class="page-next"><i class="fa fa-angle-right"></i></a>';
        }

        $html = '<div class="page-box">'.$html.'</div>';
        return $html;
    }

    public function adminhtml(){
        $page = $this->page;
        $pages = $this->pages;
        if($page>$pages)$page=$pages;

        $p = $this->page;//$i的值
        $pn = $this->pagenum + $this->page;//循环最大值
        //这两个变量仅用于for循环
        if($pn >= $pages + $this->pagenum){
            $pn = $pages + 1;
        }elseif($pn >= $pages){
            $add = $pages - $page;
            $pn = $pages;
            $p = $p - $this->pagenum + $add;
        }
        if($p >= $pages){//当访问的页数大于总页数
            $p = $pages - $this->pagenum + 1;
        }
        if($p <= 0)$p = 1;
        if($pages - $page == 1)$pn++;
        // var_dump($p,$pn);
        $html = '';
        $url = $this->getUrl();
        if($page <= 1){
            $html.= '<li class="disabled"><a href="javascript:;"><span>«</span></a></li>';
        }else{
            $html.= '<li><a href="'.$url.'/page/'.($page-1).'"><span>«</span></a></li>';
        }

        if($page >= 3){
            $html.= '<li><a href="'.$url.'/page/1"><span>1</span></a></li><li class="disabled"><a href="javascript:;"><span>...</span></a></li>';
        }

        for($i=$p;$i<$pn;$i++){
            if($page == $i){
                $html.= '<li class="disabled"><a href="javascript:;"><span>'.$i.'</span></a></li>';
            }else{
                $html.= '<li><a href="'.$url.'/page/'.$i.'"><span>'.$i.'</span></a></li>';
            }
        }

        if($page <= $pages - 2){
            $html.= '<li class="disabled"><a href="javascript:;"><span>...</span></a></li><li><a href="'.$url.'/page/'.$pages.'"><span>'.$pages.'</span></a></li>';
        }

        if($page >= $pages){
            $html.= '<li class="disabled"><a href="javascript:;"><span>»</span></a></li>';
        }else{
            $html.= '<li><a href="'.$url.'/page/'.($page+1).'"><span>»</span></a></li>';
        }

        $html = '<nav aria-label="Page navigation"><ul class="pagination">'.$html.'</nav></ul>';
        return $html;                          
    }
}