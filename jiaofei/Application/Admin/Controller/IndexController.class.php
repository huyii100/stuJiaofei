<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController {
   
    
    
     //显示首页操作
    function  index(){ 
        
       $admin_id=session('admin_id'); 
        
       $db=M();
       $sql="SELECT ids FROM role WHERE rid=(SELECT role_id FROM admin WHERE admin_id=$admin_id)";
       $data=$db->query($sql);
       $ids=$data[0]['ids'];
       
       
       if($admin_id==1){
       $sqlone="SELECT * FROM auth WHERE  auth_leave=0 "; //一级权限
       $sqltwo="SELECT * FROM auth WHERE  auth_leave=1";  //二级权限   
       }else{       
       $sqlone="SELECT * FROM auth WHERE aid IN ($ids) AND auth_leave=0 "; //一级权限
       $sqltwo="SELECT * FROM auth WHERE aid IN ($ids) AND auth_leave=1";  //二级权限  
       } 
                 
       $dataone=$db->query($sqlone);
       $datatwo=$db->query($sqltwo);
//       dump($datatwo);
       $this->assign('dataone', $dataone);
       $this->assign('datatwo', $datatwo);
       $this->display();     
    }   
    
    
    
    
    //管理处联系电话
    function guanliPhone(){
         $db = M();
        if (!empty($_POST)) {
            $guanli_phone = I('post.guanli_phone');
            $sql = "UPDATE quanju SET guanli_phone='$guanli_phone' WHERE quanju_id=1";
            $status = $db->execute($sql);
            if ($status > 0) {
                alertMes('修改成功');
            } else {
                alertMes('您没有更改任何内容');
            }
        }

        $sql = "SELECT * FROM quanju WHERE quanju_id=1";
        $result = $db->query($sql);
        $this->assign('result', $result[0]);
        $this->display();       
    }
    
 
    
}