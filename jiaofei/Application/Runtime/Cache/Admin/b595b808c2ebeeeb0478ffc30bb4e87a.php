<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="renderer" content="webkit">
    <title>后台管理中心</title>  
    <link rel="stylesheet" href="<?php echo (PUBLIC_URL); ?>/css/pintuer.css">
    <link rel="stylesheet" href="<?php echo (PUBLIC_URL); ?>/css/admin.css">
    <script src="<?php echo (PUBLIC_URL); ?>/js/jquery.js"></script>  
    <script type="text/javascript" src="<?php echo (PUBLIC_URL); ?>/js/zepto.min.js"></script>
    <script type="text/javascript" src="<?php echo (PUBLIC_URL); ?>/js/hb.js"></script>
</head>

<style>
    
    .right_adminname{float: right;padding-right: 35px;padding-top: 25px;font-size: 21px;color: white}
    #tixingdiv{width: 20%;height:160px;position: fixed;bottom: 35px;right: 10px;background-color:#93FFAE;z-index: 999;border: 1px solid #E6E6E6;box-shadow: 2px 2px 1px #888888;}
    
</style>


<body style="background-color:#f2f9fd;">
<div class="header bg-main">
  <div class="logo margin-big-left fadein-top">
    <h1><img src="/jiaofei/Public/img/logo.jpg" class="radius-circle rotate-hover" height="50" alt="" />收费系统后台管理中心</h1>
  </div>
  <div class="head-l"><a class="button button-little bg-red" href="/jiaofei/index.php/Admin/manager/logout"><span class="icon-power-off"></span> 退出登录</a></div>
  <div class="right_adminname">欢迎您,<?php echo (session('admin_name')); ?></div>
</div>
<div class="leftnav" style="overflow:auto;">
  <div class="leftnav-title"><strong><span class="icon-list"></span>菜单列表</strong></div>
  
<!--  <h2><span class="icon-user"></span>单位管理</h2>
  <ul style="display:block">
    <li><a href="/jiaofei/index.php/Admin/Xueyuan/addXueyuan" target="right"><span class="icon-caret-right"></span>添加单位</a></li>
    <li><a href="/jiaofei/index.php/Admin/Xueyuan/xueyuanList" target="right"><span class="icon-caret-right"></span>单位列表</a></li>
  </ul>  
  
  <h2><span class="icon-user"></span>用户管理</h2>
  <ul style="display:block">
    <li><a href="/jiaofei/index.php/Admin/User/addUser" target="right"><span class="icon-caret-right"></span>添加用户</a></li>
    <li><a href="/jiaofei/index.php/Admin/User/userList" target="right"><span class="icon-caret-right"></span>用户列表</a></li>
  </ul>   

  <h2><span class="icon-pencil-square-o"></span>类目管理</h2>
  <ul>
    <li><a href="/jiaofei/index.php/Admin/Leimu/addLeimu" target="right"><span class="icon-caret-right"></span>添加类目</a></li>
    <li><a href="/jiaofei/index.php/Admin/Leimu/leimuList" target="right"><span class="icon-caret-right"></span>类目列表</a></li>
  </ul>  
  
  <h2><span class="icon-pencil-square-o"></span>缴费记录管理</h2>
  <ul>
    <li><a href="/jiaofei/index.php/Admin/Order/addOrder" target="right"><span class="icon-caret-right"></span>添加缴费记录</a></li>
    <li><a href="/jiaofei/index.php/Admin/Order/orderList" target="right"><span class="icon-caret-right"></span>缴费记录列表</a></li>
  </ul> 
  
 
 
  <h2><span class="icon-pencil-square-o"></span>设置</h2>
  <ul>
    <li><a href="/jiaofei/index.php/Admin/Manager/password" target="right"><span class="icon-caret-right"></span>修改密码</a></li>       
    <li><a href="/jiaofei/index.php/Admin/Index/guanliPhone" target="right"><span class="icon-caret-right"></span>管理处联系电话</a></li>  
  </ul>   -->


    <?php  for($i=0;$i<count($dataone);$i++){ ?>
 
   <h2 class="more"><span class="icon-user"></span><?php echo ($dataone[$i]['auth_name']); ?></h2>
  <ul style="display:none">
     
    <?php  for($j=0;$j<count($datatwo);$j++){ ?>
    <?php if($datatwo[$j]['auth_fid'] == $dataone[$i]['aid'] ): ?><li><a href="/jiaofei/index.php/Admin/<?php echo ($datatwo[$j]['auth_c']); ?>/<?php echo ($datatwo[$j]['auth_a']); ?>" target="right"><span class="icon-caret-right"></span><?php echo ($datatwo[$j]['auth_name']); ?></a></li><?php endif; ?>
    <?php  } ?>
    
  </ul> 
 
   <?php  } ?>



   
  <div style='height: 80px;'></div>
</div>
    
 
    
    
<script type="text/javascript">
$(function(){
  $(".leftnav h2").click(function(){
	  $(this).next().slideToggle(200);	
	  $(this).toggleClass("on"); 
  })
  $(".leftnav ul li a").click(function(){
	    $("#a_leader_txt").text($(this).text());
  		$(".leftnav ul li a").removeClass("on");
		$(this).addClass("on");
  })
});




</script>
<ul class="bread">
  <li><a href="/jiaofei/index.php/Admin/Xueyuan/xueyuanList" target="right" class="icon-home"> 首页</a></li>
  <li><a href="##" id="a_leader_txt">网站信息</a></li>
  <li><b>当前语言：</b><span style="color:red;">中文</php></span>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;切换语言：<a href="##">中文</a> &nbsp;&nbsp;<a href="##">英文</a> </li>
</ul>
<div class="admin">
  <iframe scrolling="auto" rameborder="0" src="/jiaofei/index.php/Admin/Xueyuan/xueyuanList" name="right" width="100%" height="100%"></iframe>
</div>
<div style="text-align:center;">

</div>
</body>
</html>