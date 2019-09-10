<?php

function alertMes($mes, $url = null)
{
    echo "<script>alert('{$mes}')</script>";
        if($url != null){
        echo "<script>window.location='{$url}'</script>";
    }
}

//返回历史上一页(并结束运行下面的代码)
function jshtmlBackAndDie(){
    echo "<script>history.back();</script>";
    die;
}


//返回历史上一页
function jshtmlBack(){
    echo "<script>history.back();</script>";
}

//子页面刷新父页面
function reloadFarHtml(){
    echo "<script>self.opener.location.reload();</script>";
}
//关闭当前页面
function closeHtml(){
    echo "<script>window.close();</script>";
}
//刷新当前页面
function reloadHtml(){
    echo "<script>self.location.reload();</script>";
}
//返回上一次并刷新
function backAndReload(){
//    echo "<script>history.back();</script>";
//    echo "<script>self.location.reload(); </script>";
    echo "<script>window.location.href = document.referrer;</script>"; //document.referrer 可获取上一个页面的url路径
    
}

//分页

function fenye($page){
    
    
    
                  //设置分页样式
       $page->setConfig('header', '<li class="rows">共<b>%TOTAL_ROW%</b>条记录&nbsp;第<b>%NOW_PAGE%</b>页/共<b>%TOTAL_PAGE%</b>页</li>');
            $page->setConfig('prev', '上一页');
            $page->setConfig('next', '下一页');
            $page->setConfig('last', '末页');
            $page->setConfig('first', '首页');
            $page->setConfig('theme', '%FIRST%%UP_PAGE%%LINK_PAGE%%DOWN_PAGE%%END%%HEADER%');
            $page->lastSuffix = false;//最后一页不显示为总页数
       $show = $page->show();// 分页显示输出
       return $show;
    
    
    
}


?>