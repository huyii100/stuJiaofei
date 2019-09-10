<?php

namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {

    function __construct() {
        parent::__construct();
        
        $acname = CONTROLLER_NAME . '-' . ACTION_NAME; //当前的ac

        $admin_id = session('admin_id');
        $admin_name = session('admin_name');
        
 

        // $hos_ac="Index-hosindex,Hospital-password,Room-addselfroom,Room-selfroomlist,Doctor-selfdoctorlist,Zhen-outlist,Zhen-givelist"; //医院的所有ac
        //  $logac="Hospital-hoslogin,Manager-adminlogin";
        //session('admin_id',null);
        if ($admin_id == null && $acname != 'Manager-adminlogin') {
               //alertMes($acname) ;
              //alertMes('请先登录');
              if($acname=='Index-index'){
                $this->redirect('Manager/adminlogin');
              }else{
                  $SRRVICE=$Think.SERVICE;
                  echo '<script>top.location.href="'.$SRRVICE.'/jiaofei/index.php/Admin/Manager/adminlogin.html"</script>';
              }
            //echo '<script>top.location.href="Manager/adminlogin"</script>';
        }
    }


    
}
