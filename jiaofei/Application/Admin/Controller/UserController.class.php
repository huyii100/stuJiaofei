<?php

// 本类由系统自动生成，仅供测试用途

namespace Admin\Controller;
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:*');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type');
class UserController extends BaseController {

    
    
    //学生列表
    function userList($isOrder=false,$searchval=null,$state=state){
        $db = M();
        
        if(empty($searchval)){
            $sqlpin=" 1=1 ";
        }else{
            $sqlpin=" (user_name LIKE '%$searchval%' OR xue_num LIKE  '%$searchval%' OR phone LIKE '%$searchval%') ";
        }
        
        
        
        $sqlcount = "SELECT count(*) FROM user WHERE ".$sqlpin."  AND state=$state";
        $datacount = $db->query($sqlcount);
        $count = $datacount[0]['count(*)'];
        $page = new \Think\Page($count, 15);
        $show = fenye($page);

        $sql = "SELECT user.*,xueyuan_name FROM user,xueyuan WHERE user.xueyuan_id=xueyuan.xueyuan_id AND".$sqlpin."   AND state=$state  ORDER BY user_createtime   limit $page->firstRow,$page->listRows";
        $data = $db->query($sql);       
 
        $this->assign('isOrder', $isOrder);
        $this->assign('data', $data);
        $this->assign('page', $show); // 赋值分页输出
        $this->display();        
    }
    
    
    
    //添加和修改用户
    function addUser($user_id=null){
        $db = M();
        
        if(!empty($_POST)){
            $user_name = I('post.user_name');
            $sfz_num = I('post.sfz_num');
            $xueyuan_id = I('post.xueyuan_id');
            $xue_num = I('post.xue_num');
            $xueji = I('post.xueji');
            $banji = I('post.banji');
            $zhuanye = I('post.zhuanye');
            $phone = I('post.phone');

            if (empty($user_id)) {//代表是添加的逻辑
                
                $sql0="SELECT user_id FROM USER WHERE sfz_num='$sfz_num'";//查询用户添加的身份证号，是否已存在
                $result0=$db->query($sql0);
                if(!empty($result0)){
                    alertMes('您填写的身份证号码和其它学生重复了');
                    jshtmlBack();
                }
 
                $sql = "INSERT INTO user(sfz_num,user_name,xue_num,xueyuan_id,xueji,banji,zhuanye,phone) VALUES ('$sfz_num','$user_name','$xue_num',$xueyuan_id,$xueji,'$banji','$zhuanye','$phone') "; //插入数据
                $status = $db->execute($sql);
                if ($status > 0) {
                    alertMes('数据添加成功');
                } else {
                    alertMes('数据添加失败');
                }
            }else{
                $sql0="SELECT user_id FROM USER WHERE sfz_num='$sfz_num' AND user_id!=$user_id";//查询用户添加的身份证号，是否已存在
                $result0=$db->query($sql0);
                if(!empty($result0)){
                    alertMes('您填写的身份证号码和其它学生重复了');
                    jshtmlBack();
                }				
				
				//代表是修改的逻辑
                   $sql=" UPDATE user SET sfz_num='$sfz_num',user_name='$user_name',xue_num='$xue_num',xueyuan_id=$xueyuan_id,xueji=$xueji,banji='$banji',zhuanye='$zhuanye',phone='$phone' WHERE user_id=$user_id";
                   $status = $db->execute($sql);
                   if ($status > 0) {
                       alertMes('数据修改成功');
                       reloadFarHtml();
                       closeHtml();
                   } else {
                       alertMes('您没有更改任何内容');
                   }                      
            }
            
            
        }
        
        
        
        
        $sql2="SELECT * FROM xueyuan";//查询出所有的学院
        $list2=$db->query($sql2);
        
        
        if(empty($user_id)){
            $this->assign('now_xueyuan_id', $list2[0]['xueyuan_id']);
        }else{
           $sql3="SELECT * FROM user WHERE user_id=$user_id"; 
           $result3=$db->query($sql3);
           $this->assign('result',$result3[0]);
           $this->assign('now_xueyuan_id', $result3[0]['xueyuan_id']);
        }
        
        
        
        $this->assign('xueyuanList', $list2);
        $this->display();
        
        
        
        
        
    }
 
    //用户详情
    function userdetails($user_id) {
        $db = M();
        $sql = "SELECT user.*,xueyuan_name FROM user,xueyuan WHERE  user.xueyuan_id=xueyuan.xueyuan_id AND  user_id=$user_id";
        $data = $db->query($sql);
        $this->assign('data', $data);
        $this->display();
    }    
    
    
    
    //用户启用和禁用
    function userKG($user_id, $state) {
        $db = M();
        $sql = "UPDATE user SET state=$state WHERE user_id=$user_id";
        $status = $db->execute($sql);
        backAndReload();
    }
    
    
    //重置用户密码
    function chongzhiPwd($user_id){
        $db = M();
        $sql = "UPDATE user SET password='e10adc3949ba59abbe56e057f20f883e' WHERE user_id=$user_id";
        $status = $db->execute($sql);    
//        if($status>0){
//            alertMes('密码重置成功');
//        }else{
//            alertMes('密码重置失败');
//        }
        alertMes('密码重置成功');
        jshtmlBack();
    }
    
    
}
