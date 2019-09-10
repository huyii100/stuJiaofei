<?php

namespace Admin\Controller;
//角色控制器
class RoleController extends BaseController{
    
    //添加角色操作
    function  addRole(){ 
        $db=M();
        
        if(!empty($_POST)){
        $role_name=I('post.role_name');
        $auth_id=I('post.auth_id');//所有权限的id数组
        
        
        $list=$this->getHaveIds($auth_id);
        $ids=$list[0];
        $role_ca=$list[1];
        
        
        $sqladd="INSERT INTO role(role_name,ids,role_ca) VALUES ('$role_name','$ids','$role_ca')";
        $status=$db->execute($sqladd);
        
            if ($status > 0) {
                alertMes('角色添加成功');
            } else {
                alertMes('角色添加失败');
                jshtmlBack();
            }
        
 
        }
       
       $sqlone='SELECT * FROM auth WHERE auth_leave=0';//一级权限
       $sqltwo='SELECT * FROM auth WHERE auth_leave=1';//二级权限 
       $dataone=$db->query($sqlone); 
       $datatwo=$db->query($sqltwo); 
       $this->assign('dataone', $dataone);
       $this->assign('datatwo', $datatwo);
       $this->display();
       
       
       
    }

        
    
    //所有角色的列表
    function  roleList(){       
     $db=M();
     $sql="SELECT rid,role_name FROM role";
     $data=$db->query($sql);
     $this->assign('data',$data);
     $this->display();
    }
    
    
       //根据角色id修改角色
    function  roleByid($rid){     
        
        
     $db=M();
     
             if(!empty($_POST)){
        $role_name=I('post.role_name');
        $auth_id=I('post.auth_id');//所有权限的id数组
        
        $list=$this->getHaveIds($auth_id);
        $ids=$list[0];
        $role_ca=$list[1];
       
        $sql="UPDATE role SET role_name='$role_name',ids='$ids',role_ca='$role_ca' WHERE rid=$rid";
        $status=$db->execute($sql);
        
            if ($status) {
                alertMes('角色修改成功');
                reloadFarHtml();
                closeHtml();
            } else {
                alertMes('角色修改失败');
                jshtmlBack();
            }
        
 
        }
     
     
     
       $role="SELECT rid,role_name,ids FROM role WHERE rid=$rid";
       $sqlone='SELECT * FROM auth WHERE auth_leave=0';//一级权限
       $sqltwo='SELECT * FROM auth WHERE auth_leave=1';//二级权限 
       $datarole=$db->query($role);
       $dataone=$db->query($sqlone); 
       $datatwo=$db->query($sqltwo); 
       
       $haveids=explode(',',$datarole[0]['ids']);
       
       $this->assign('haveids', $haveids);
       $this->assign('datarole', $datarole);
       $this->assign('dataone', $dataone);
       $this->assign('datatwo', $datatwo);
       $this->display();
     
     
     
    }
    
  
    
    
        //删除角色
   function delrole($rid){
         $db=M();
         $sql="DELETE FROM role WHERE rid=$rid";
         $status=$db->execute($sql);
         $sql2="DELETE FROM admin WHERE role_id=$rid"; //删除该角色下所有管理员
         $status2=$db->execute($sql2);
         if($status>0){
             alertMes('删除成功');  
             backAndReload();
         }else{
           alertMes('删除失败');    
           jshtmlBack();
         }
             
       
       
   }
    
    
   
        //添加管理员

    function addManager() {

        if (!empty($_POST)) {
            $username = I('post.username');
            $realname = I('post.realname');
            $password = I('post.password');
            $md5password=  md5($password);         
            $role_id = I('post.role_id');

            $db = M();
            
            $sql0="SELECT * FROM admin WHERE username='$username'";//查询该用户名在数据库是否已存在
            $result=$db->query($sql0);
            if(!empty($result)){
                alertMes('您填写的管理员用户名已存在，请更换');
                jshtmlBack();    
                return;
            }else{
                $admin_name = session('admin_name');

                $sql = "INSERT INTO admin(username,password,realname,role_id) VALUES ('$username','$md5password','$realname',$role_id)";
                $status = $db->execute($sql);
                if ($status > 0) {
                    alertMes('添加管理员成功');
                } else {
                    alertMes('添加管理员失败');
                    jshtmlBack();
                }
            }     
        }
 
        $data=$this->getAllRole();
        $this->assign('data',$data);
        $this->display();
    }
   
       //管理员列表
       function managerList() {
        $db = M();

        
        $sqlcount = "SELECT admin.*,role_name FROM admin,role WHERE admin.role_id=role.rid AND username!='admin'";
        $datacount = $db->query($sqlcount);
        $count = $datacount[0]['count(*)'];
        $page = new \Think\Page($count, 15);
        $show = fenye($page);

        $sql = "SELECT admin.*,role_name FROM admin,role WHERE admin.role_id=role.rid AND username!='admin' ORDER BY admin_createtime DESC  limit $page->firstRow,$page->listRows ";
        $data = $db->query($sql);
        //dump($data);
        $this->assign('data', $data);
        $this->assign('page', $show); // 赋值分页输出      
        $this->display();
    }
    
    
            //修改管理员

    function managerByid($admin_id) {
        $db = M();
        if (!empty($_POST)) {
            $username = I('post.username');
            $password = I('post.password');
            $md5password=  md5($password);         
            $role_id = I('post.role_id');

            
            
            $sql0="SELECT * FROM admin WHERE username='$username'";//查询该用户名在数据库是否已存在
            $result=$db->query($sql0);
            if(!empty($result)){
                alertMes('您填写的管理员用户名已存在，请更换');
                jshtmlBack();    
                return;
            }else{
                $admin_name = session('admin_name');
                $nowtime=date("Y-m-d H:i:s");
                $sql = "UPDATE admin SET role_id=$role_id WHERE admin_id=$admin_id";
                $status = $db->execute($sql);
                if ($status > 0) {
                    alertMes('修改成功');
                    reloadFarHtml();
                    closeHtml();
                    return;
                } else { 
                    alertMes('修改失败,您没有更改任何内容');
                    jshtmlBack();
                }
            }     
        }
        $sql="SELECT * FROM admin WHERE admin_id=$admin_id";
        $result=$db->query($sql);
        $data=$this->getAllRole();
        
        $this->assign('result',$result);
        $this->assign('data',$data);
        $this->display();
    }
    
    
    
           //删除管理员
   function delManager($admin_id){
         $db=M();
         $sql="DELETE FROM admin WHERE admin_id=$admin_id";
         $status=$db->execute($sql);

         if($status>0){
             alertMes('删除成功');  
             backAndReload();
         }else{
           alertMes('删除失败');    
           jshtmlBack();
         }
             
       
       
   } 
    
          //重置管理员密码
   function reloadPwd($admin_id){
         $db=M();


        $code="ABCDEFGHIGKLMNOPQRSTUVWXYZ";
        $rand=$code[rand(0,25)].strtoupper(dechex(date('m')))
            .date('d').substr(time(),-5)
            .substr(microtime(),2,5).sprintf('%02d',rand(0,99));
        for(
            $a = md5( $rand, true ),
            $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
            $d = '',
            $f = 0;
            $f < 8;
            $g = ord( $a[ $f ] ), // ord（）函数获取首字母的 的 ASCII值
            $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],  //按位亦或，按位与。
            $f++
        );
        
         $newPwd= $d;  
         
         $md5newPwd=  md5($newPwd);
         $sql="UPDATE admin SET password='$md5newPwd' WHERE admin_id=$admin_id";
         $status=$db->execute($sql);
//         dump($sql);
//         die();
         if($status>0){
             alertMes('密码重置成功，新密码为:'.$newPwd);  
             backAndReload();
         }else{
           alertMes('密码重置失败');    
           jshtmlBack();
         }
             
       
       
   }    
   /***********************/
   
        //根据传入的ids数组，获取成需要的ids字符串、控制器和操作方法结合
    function getHaveIds($auth_id){
        $db=M();
        $role_ca="";
        $ids=  implode(',', $auth_id);
        
        $sql="SELECT auth_c,auth_a FROM auth WHERE aid IN ($ids)";
        $data=$db->query($sql);
        
        for($i=0;$i<count($data);$i++){
            if(!empty($data[$i]['auth_c'])&&!empty($data[$i]['auth_a'])){ 
            $role_ca.=$data[$i]['auth_c']."-".$data[$i]['auth_a'].",";
            }
        }
        
        $role_ca=  rtrim($role_ca, ',');  
        
        return array($ids,$role_ca);
               
    }
    

    //获取所有角色 权限组的 列表
    function getAllRole(){
      $db=M();  
      $sql="SELECT rid,role_name FROM role ORDER BY rid DESC";
      $data=$db->query($sql);  
      return $data; 
    }
   
   
   
   
    
}


?>