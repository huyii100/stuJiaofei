<?php

namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {

    function __construct() {
        parent::__construct();
 

        $user_id = session('user_id');
 
        if ($user_id == null) {
              $this->redirect('Login/login');
     
                 // echo '<script>location.href="'.$url.'"</script>';
                   
            //echo '<script>top.location.href="Manager/adminlogin"</script>';
        } 
    }


    
}
