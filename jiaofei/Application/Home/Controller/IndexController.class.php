<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
header("Content-type: text/html; charset=utf-8");
class IndexController extends BaseController {
    public function index(){
        $this->redirect('main1');
    }
    
    //支付成功的回调
    function payokReturn($user_id=null,$payorder_id=null){
       
       $db=M(); 
       $pay_time=date("Y-m-d H:i:s");
       $sql="UPDATE payorder SET paytype=1,status=1,pay_time='$pay_time' WHERE payorder_id=$payorder_id";
       $status=$db->execute($sql); 
        $this->display();
    }
    
    //退出登录
    function layout(){
        session('user_id',null);
        $this->redirect('Login/login');
        
    }
    
    
    
    //未缴费列表
    function main1(){
        $user_id=session('user_id');
        $list=$this->getOrderListData($user_id,2);
        
        $this->assign('data', $list);
        $this->display();
        
    }
    
    //已缴费列表
    function main2(){
        $user_id=session('user_id');
        $list=$this->getOrderListData($user_id,1);
        $this->assign('data', $list);
        $this->display();
        
    }    
    
    //个人中心
    function main3(){
        $user_id=session('user_id');
        $result=$this->getUserInfo($user_id);
        $quanju=$this->getQuanju();
        
        
        $this->assign('result', $result[0]);
        $this->assign('quanju', $quanju[0]);
        $this->display();       
    }
    
    //费用
    function payfeiyong($payorder_id){
        $result=$this->getOrderDetailsData($payorder_id);
        $this->assign('result', $result[0]);
        $this->display();
        
    }
    
    //已缴费凭证
    function orderpingzheng($payorder_id){
        $result=$this->getOrderDetailsData($payorder_id);
        $this->assign('result', $result[0]);
        $this->display();        
    }
    
    //忘记密码
    function forgetpwd(){
        $quanju=$this->getQuanju();
        $this->assign('quanju', $quanju[0]);
        $this->display();         
    }
 
    //用户信息
    function userinfo(){
        $user_id=session('user_id');
        $result=$this->getUserInfo($user_id);
        $this->assign('result', $result[0]);
        $this->display();     
    }
    
    //修改密码
    function updatepwd(){
        if(!empty($_POST)){
           
           $oldPwd=I('post.oldPwd');
           $newPwd=I('post.newPwd');
           $reNewPwd=I('post.reNewPwd');
           
 
           if(empty($oldPwd)){
               alertMes('请填写旧密码');  
               jshtmlBackAndDie(); 
           }
           if(empty($newPwd)){
               alertMes('请填写新密码');  
               jshtmlBackAndDie();  
           }         
           if(empty($reNewPwd)){
               alertMes('请再次填写新密码');  
               jshtmlBackAndDie();  
           }              
           if($newPwd!=$reNewPwd){
               alertMes('您两次输入的密码不一致');  
               jshtmlBackAndDie();  
           }          

           $new_md5Password=  md5($newPwd);
           $old_md5Password=  md5($oldPwd);
           
           $db=M();
           $user_id=session('user_id');
           $sql="SELECT password FROM user WHERE user_id=$user_id";//查询用户旧密码
           $result=$db->query($sql);
           if($old_md5Password!=$result[0]['password']){
               alertMes('您的旧密码不正确');  
               jshtmlBackAndDie();                 
           }
           
           $sql2="UPDATE user SET password='$new_md5Password' WHERE user_id=$user_id";
           $status2=$db->execute($sql2);
           alertMes('密码修改成功'); 
           $this->display('main3'); 
           
       }else{
          $this->display();  
       } 
       
       
             
        
        
        
    }
    
    
    
    //获取缴费订单数据(被调用)
    /**
     * 
     * @param type $user_id   查询谁的缴费记录
     * @param type $status  缴费状态  1、已缴费 2、未缴费
     */
    private function getOrderListData($user_id=null,$status=null){
        $db=M();
        $sql="SELECT payorder.*,leimu_name,DATE_FORMAT(begin_time,'%Y-%m-%d') AS begin_date,DATE_FORMAT(end_time,'%Y-%m-%d') AS end_date,NOW()>end_time AS is_yuqi FROM payorder,leimu WHERE  "
                . "payorder.leimu_id=leimu.leimu_id AND user_id=$user_id AND status=$status ORDER BY payorder_createtime DESC";
        $list=$db->query($sql);
        return $list;
        
    }
    
     //获取缴费订单详情数据(被调用)
    private function getOrderDetailsData($payorder_id){
        $db=M();
        $sql="SELECT payorder.*,leimu_name,user_name,sfz_num,headimg,DATE_FORMAT(begin_time,'%Y-%m-%d') AS begin_date,DATE_FORMAT(end_time,'%Y-%m-%d') AS end_date FROM payorder,leimu,user WHERE payorder.leimu_id=leimu.leimu_id AND payorder.user_id=user.user_id AND payorder_id=$payorder_id";
        $result=$db->query($sql);
        return $result;        
    }






    //获取用户信息 (被调用)
    private function getUserInfo($user_id=null){
        $db=M();
        $sql="SELECT user.*,xueyuan_name FROM user,xueyuan WHERE user.xueyuan_id=xueyuan.xueyuan_id AND user_id=$user_id";
        $result=$db->query($sql);
        return $result;       
    }
    
    
    private function getQuanju(){
        $db=M();
        $sql="SELECT * FROM quanju ";
        $result=$db->query($sql);
        return $result;           
    }
    
    
    
    
}