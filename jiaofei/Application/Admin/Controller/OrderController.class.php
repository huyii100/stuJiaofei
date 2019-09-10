<?php

// 本类由系统自动生成，仅供测试用途

namespace Admin\Controller;
// 指定允许其他域名访问  
header('Access-Control-Allow-Origin:*');  
// 响应类型  
header('Access-Control-Allow-Methods:*');  
// 响应头设置  
header('Access-Control-Allow-Headers:x-requested-with,content-type');
class OrderController extends BaseController {

    
    
    //缴费记录列表
    function orderList($searchval=null,$status=status,$leimu_id=null,$leimu_name=null){
        $db = M();
        
        if(empty($searchval)){
            $sqlpin=" 1=1 ";
        }else{
            $sqlpin=" (user_name LIKE '%$searchval%' OR xue_num LIKE  '%$searchval%' OR phone LIKE '%$searchval%') ";
        }        
        
        if(empty($leimu_id)){
           $sqlpin2=" 1=1 ";
        }else{
           $sqlpin2=" payorder.leimu_id=$leimu_id "; 
        }
        
        
        
        $sqlcount = "SELECT count(*) FROM payorder,user WHERE payorder.user_id=user.user_id AND ".$sqlpin2." AND status=$status  AND".$sqlpin;
        $datacount = $db->query($sqlcount);
        $count = $datacount[0]['count(*)'];
        $page = new \Think\Page($count, 15);
        $show = fenye($page);

        $sql = "SELECT payorder.*,leimu_name,user_name,DATE_FORMAT(begin_time,'%Y-%m-%d') AS begin_date,DATE_FORMAT(end_time,'%Y-%m-%d') AS end_date FROM payorder,leimu,USER WHERE payorder.leimu_id=leimu.leimu_id AND payorder.user_id=user.user_id  AND ".$sqlpin2."  AND status=$status   AND ".$sqlpin."  ORDER BY payorder_createtime DESC   limit $page->firstRow,$page->listRows";
        $data = $db->query($sql);       
        
        
        $sql="SELECT sum(money) FROM payorder WHERE status=1";//统计已缴费的总金额
        $sql2="SELECT sum(money) FROM payorder WHERE status=2 AND NOW()<=end_time";//未缴费的总金额
        $sql3="SELECT sum(money) FROM payorder WHERE STATUS=2 AND NOW()>end_time";//已逾期的总金额
        $sql4="SELECT sum(money) FROM payorder";//总金额
        
        
        
        $result=$db->query($sql);
        $result2=$db->query($sql2);
        $result3=$db->query($sql3);
        $result4=$db->query($sql4);
        
        $money_yijiao=$result[0]['sum(money)'];
        $money_weijiao=$result2[0]['sum(money)'];
        $money_yuqi=$result3[0]['sum(money)'];
        $money_zong=$result4[0]['sum(money)'];
        
        $money_yijiao=empty($money_yijiao)?0:$money_yijiao;
        $money_weijiao=empty($money_weijiao)?0:$money_weijiao;
        $money_yuqi=empty($money_yuqi)?0:$money_yuqi;
        $money_zong=empty($money_zong)?0:$money_zong;
        
        $this->assign('data', $data);
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('leimu_name', $leimu_name);
        
         $this->assign('money_yijiao', $money_yijiao);
         $this->assign('money_weijiao', $money_weijiao);
         $this->assign('money_yuqi', $money_yuqi);
         $this->assign('money_zong', $money_zong);
        
        $this->display();        
    }
    
    
    
    //添加和修改缴费记录
    function addOrder($payorder_id=null){
        $db = M();
        
        if(!empty($_POST)){
            $user_id = I('post.user_id');
            $qishu =trim(I('post.qishu'));
            
            $begin_time = I('post.begin_time');
            $end_time = I('post.end_time');
            $money = I('post.money');
            $leimu_id = I('post.leimu_id');
            $beizhu = I('post.beizhu');
            $status=I('post.status');
      

                
              $begin_time=$begin_time.' 00:00:01';
              $end_time=$end_time.' 23:59:59';  
                
              //代表后台需要把订单变为已缴费状态  
              if($status==1){
                  $pay_time=date("Y-m-d H:i:s");
              }else{
                  $pay_time='';
              }
                
                
            if (empty($payorder_id)) {//代表是添加的逻辑
			
                $sql0="SELECT payorder_id FROM payorder WHERE user_id=$user_id AND qishu='$qishu'  AND leimu_id=$leimu_id";//查询缴费记录是否重复
                $result0=$db->query($sql0);
                if(!empty($result0)){
                    alertMes('您填写的缴费记录信息和其它缴费记录信息重复了');
                    jshtmlBackAndDie();
                }			
			
			
                $random=rand(10000000000000,99999999999999);//生成随机数
                $order_num=time().$random;
                
                $sql = "INSERT INTO payorder(user_id,qishu,order_num,begin_time,end_time,money,leimu_id,status,pay_time,beizhu) VALUES "
                                         . "($user_id,'$qishu','$order_num','$begin_time','$end_time',$money,$leimu_id,$status,'$pay_time','$beizhu')"; //插入数据
                $status = $db->execute($sql);
                if ($status > 0) {
                    alertMes('数据添加成功');
                } else {
                    alertMes('数据添加失败');
                }
            }else{
				
                $sql0="SELECT payorder_id FROM payorder WHERE user_id=$user_id AND qishu='$qishu'  AND leimu_id=$leimu_id AND payorder_id!=$payorder_id";//查询缴费记录是否重复
                $result0=$db->query($sql0);
                if(!empty($result0)){
                    alertMes('您填写的缴费记录信息和其它缴费记录信息重复了');
                    jshtmlBackAndDie();
                }				
				//代表是修改的逻辑
                   $sql="UPDATE payorder SET qishu='$qishu',begin_time='$begin_time',end_time='$end_time',money=$money,leimu_id=$leimu_id,status=$status,beizhu='$beizhu',pay_time='$pay_time' WHERE payorder_id=$payorder_id";
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
        
        
        
        
        $sql2="SELECT * FROM leimu";//查询出所有的类目
        $list2=$db->query($sql2);
        
        
        if(empty($payorder_id)){
            $this->assign('now_leimu_id', $list2[0]['leimu_id']);
        }else{
           $sql3="SELECT payorder.*,leimu_name,user_name,DATE_FORMAT(begin_time,'%Y-%m-%d') AS begin_date,DATE_FORMAT(end_time,'%Y-%m-%d') AS end_date FROM payorder,leimu,user WHERE payorder.leimu_id=leimu.leimu_id AND payorder.user_id=user.user_id AND payorder_id=$payorder_id"; 
           $result3=$db->query($sql3);
           $this->assign('result',$result3[0]);
           $this->assign('now_leimu_id', $result3[0]['leimu_id']);
        }
        
        
        
        $this->assign('leimuList', $list2);
        $this->display();
        
        
        
        
        
    }
 
    //缴费记录详情
    function orderdetails($payorder_id) {
        $db = M();
        $sql = "SELECT payorder.*,leimu_name,user_name FROM payorder,leimu,USER WHERE payorder.leimu_id=leimu.leimu_id AND payorder.user_id=user.user_id AND payorder_id=$payorder_id";
        $data = $db->query($sql);
        $this->assign('data', $data);
        $this->display();
    }    
    
    //删除缴费订单
   function delOrder($payorder_id){
        $db = M();
        $sql = "DELETE FROM payorder  WHERE payorder_id=$payorder_id";
        $status = $db->execute($sql);

        if ($status > 0) {
            alertMes('删除成功');
            backAndReload();
        } else {
            alertMes('删除失败');
        }      
       
       
       
   }    
    
    //订单已缴费未缴费 切换
    function orderKG($payorder_id, $status) {
        $db = M();
        $sql = "UPDATE payorder SET status=$status WHERE payorder_id=$payorder_id";
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
