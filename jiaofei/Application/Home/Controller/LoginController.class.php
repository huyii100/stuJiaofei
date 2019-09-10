<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
header("Content-type: text/html; charset=utf-8");
class LoginController extends Controller {
    public function login(){
       if(!empty($_POST)){
           
           $sfz_num=I('post.sfz_num');
           $password=I('post.password');
           if(empty($sfz_num)){
               alertMes('请填写身份证号');  
               jshtmlBackAndDie(); 
           }
           if(empty($password)){
               alertMes('请填写密码');  
               jshtmlBackAndDie();  
           }         
           
           
           $md5Password=  md5($password);
           $db=M();
           $sql="SELECT user_id FROM user WHERE sfz_num='$sfz_num' AND password='$md5Password'";
           $result=$db->query($sql);
           if(empty($result)){
               alertMes('身份证号或密码错误');  
               jshtmlBackAndDie();                 
           }
           
           session('user_id',$result[0]['user_id']);
           
            $user_id=session('user_id');
            $sql2="SELECT state FROM USER WHERE user_id=$user_id";//查询用户是否被禁用
            $result2=$db->query($sql2);
            if($result2[0]['state']==1){//表示被禁用了
                session('user_id',null);
                alertMes('您的账号已被管理员禁用');
                jshtmlBackAndDie(); 
            }          
           
           
           
           
           
           
           $this->redirect('Index/main1');
           
       } 
       
       
       $this->display();
       
    }
    
 
     function forgetpwd(){
      
        $quanju=$this->getQuanju();
        $this->assign('quanju', $quanju[0]);
        $this->display();           
         
     }
    
     private function getQuanju(){
        $db=M();
        $sql="SELECT * FROM quanju ";
        $result=$db->query($sql);
        return $result;           
    }   
    
    
}