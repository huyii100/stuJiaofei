<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="renderer" content="webkit">
<title>单位列表</title>
<link rel="stylesheet" href="<?php echo (PUBLIC_URL); ?>/css/pintuer.css">
<link rel="stylesheet" href="<?php echo (PUBLIC_URL); ?>/css/admin.css">
    <script src="<?php echo (PUBLIC_URL); ?>/js/jquery-3.0.0.min.js"></script>
<script src="<?php echo (PUBLIC_URL); ?>/js/pintuer.js"></script>

<style>
    
    .greenfont{color: green}
    .goods_num{color: #0080FF}
</style>
</head>
<body>
<form method="post" action="" id="listform">
  <div class="panel admin-panel">
    <div class="panel-head"><strong class="icon-reorder">单位列表</strong></div>
    <div class="padding border-bottom" style="display:none">
      <!--       搜索部分-->   
      <ul class="search" style="padding-left:10px;">
        <li class="greenfont">搜索：</li>
        <li>
           手机号： 
          <input id="tel" type="number" placeholder="请输入手机号" name="nickname" class="input" style="width:120px; line-height:17px;display:inline-block" />
          <a href="javascript:void(0)" class="button border-main icon-search" onclick="changesearchTel()" > 搜索</a>
        </li>
        <li>
           名称： 
           <input id="name" type="text" placeholder="请输入" name="nickname" class="input" style="width:120px; line-height:17px;display:inline-block" />
          <a href="javascript:void(0)" class="button border-main icon-search" onclick="changesearchName()" > 搜索</a>
        </li>

      </ul>
       <!--        筛选部分-->
      <ul class="search" style="padding-left:10px;margin-top: 10px">
        <li class="greenfont">筛选：</li>
        <li>禁启用状态筛选：
            <select id="state" name="state" class="input"  style="width:120px; line-height:17px; display:inline-block" onchange="stateOnchange()">
            <option value="">选择</option>
            <option value="0">被禁用</option>
            <option value="1">已启用</option>
          </select>
        </li>   
        <li class="greenfont">分类筛选：</li>
         <li>
            <select id="type" name="" class="input"  style="width:auto; line-height:17px; display:inline-block" onchange="oneLevelChange()">
            <option value="0">请选择</option>
            <option value="1">智能家居</option>
            <option value="2">装修主材</option>
            <option value="3">装修辅材</option>
            <option value="4">家具</option>
            <option value="5">家电</option>
          </select>
        </li> 
         <li>
            <select id="bus_type_id" name="bus_type_id" class="input"  style="width:auto;"  value="">
               <option value="0">请选择</option>
            </select>
        </li> 
        <a href="javascript:void(0)" class="button border-main icon-search" onclick="shaixuan()" > 筛选</a>
      </ul> 
    </div>
    <table class="table table-hover text-center">
      <tr>
        <th style="text-align:left; padding-left:20px;">序号</th>
        <th>ID</th>
        <th>单位名称</th>
        <th>单位拥有学生</th>
        <th>单位数据创建时间</th>
        <th>操作</th>
      </tr>
        <?php  for($i=0;$i<count($data);$i++){ ?>
        <tr>
          <td style="text-align:left; padding-left:20px;"><?php echo ($i+1); ?></td>
          <td><?php echo ($data[$i]['xueyuan_id']); ?></td>
          <td><?php echo ($data[$i]['xueyuan_name']); ?></td>
          <td><?php echo ($data[$i]['user_count']); ?></td>
          <td><?php echo ($data[$i]['xueyuan_createtime']); ?></td>
 
                    <td><div class="button-group"> 
                     <?php if($data[$i]['user_count'] == 0): ?><a class="button border-red" onclick="del(<?php echo ($data[$i]['xueyuan_id']); ?>)" ><span class="icon-trash-o"></span> 删除</a><?php endif; ?>   
                       <a class="button border-main"  href="/jiaofei/index.php/Admin/Xueyuan/addxueyuan/xueyuan_id/<?php echo ($data[$i]['xueyuan_id']); ?>"  onclick="window.open(this.href,'_blank','scrollbars=1,resizable=0;height=900,width=800,top=190,left=350');return false" ><span class="icon-edit"></span>编辑</a>
              </div></td>
<!--          <td><div class="button-group"> <a class="button border-main" href="#" onclick="details(<?php echo ($data[$i]['company_id']); ?>)"><span class="icon-eye"></span> 详情</a> </div></td>-->
        </tr>
       <?php  } ?> 
 
      <tr>
        <td colspan="8">
            <div class="pagelist">
                <?php echo ($page); ?>
            </div>
        </td>
      </tr>
    </table>
  </div>
</form>
<script type="text/javascript">

 



//删除厂家
function del(xueyuan_id){
   	if(confirm("是否确定要删除该单位？")){
            var url="/jiaofei/index.php/Admin/Xueyuan/delXueyuan/xueyuan_id/"+xueyuan_id;
            window.location.href=url;	   
	}    
}



</script>
</body>
</html>