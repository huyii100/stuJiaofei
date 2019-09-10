<?php

// 本类由系统自动生成，仅供测试用途

namespace Admin\Controller;

class ManagerController extends BaseController {

    function test() {
        echo '部署成功';
    }

    //管理员登录操作
    function adminLogin() {

        if (!empty($_POST)) {
            $username = I('post.username');
            $password = I('post.password');
            $md5password = md5($password);


            $db = M();
            $sql = "SELECT admin_id,username FROM  admin WHERE username='$username' AND password='$md5password' ";
            $data = $db->query($sql);
            //dump($data);
            if (!empty($data)) {
                alertMes('登陆成功');
                session('admin_id', $data[0]['admin_id']);
                session('admin_name', $data[0]['username']);
                $this->redirect('Admin/Index/index');
            } else {
                alertMes('用户名或密码错误');
            }
        }


        $this->display();
    }

    //管理员退出登录
    function logout() {
        session('admin_id', null);
        session('admin_name', null);
        $this->redirect('adminlogin');
    }

    //管理员修改密码操作

    function password() {

        $db = M();
        
        if (!empty($_POST)) {
            $admin_id=session('admin_id');
            $mpass = I('post.mpass'); //旧密码
            $newpass = I('post.newpass'); //新密码
            $md5password = md5($mpass);
            $newmd5password = md5($newpass);

            $sql = "select * from admin where admin_id=$admin_id and password='$md5password'";

            $data = $db->query($sql);

            if (empty($data)) {
                alertMes('原始密码错误');
            } else {
                $sql = "UPDATE admin SET password='$newmd5password' WHERE admin_id=$admin_id";
                $status = $db->execute($sql);

                if ($status > 0) {
                    alertMes('修改密码成功');
                } else {
                    alertMes('修改密码失败');
                }
            }
        }

        $this->display();
    }

}
