<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
header("Content-type: text/html; charset=utf-8");
class PayController extends Controller {
   
    function test(){
            $data['test']=6666;
            $this->ajaxReturn($data, 'JSON');        
        
    }
 
    
}