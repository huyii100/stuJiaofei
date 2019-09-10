<?php

// 本类由系统自动生成，仅供测试用途

namespace Admin\Controller;

class LeimuController extends BaseController {

    
    
    //类目列表
    function leimuList(){
        $db = M();
        $sqlcount = "SELECT count(*) FROM leimu";
        $datacount = $db->query($sqlcount);
        $count = $datacount[0]['count(*)'];
        $page = new \Think\Page($count, 15);
        $show = fenye($page);

        $sql = "SELECT * FROM leimu ORDER BY leimu_createtime DESC   limit $page->firstRow,$page->listRows";
        $data = $db->query($sql);       
        for($i=0;$i<count($data);$i++){
            $leimu_id=$data[$i]['leimu_id'];
            $sql2="SELECT count(*) FROM payorder WHERE leimu_id=$leimu_id";
            $result2=$db->query($sql2);
            $data[$i]['order_count']=$result2[0]['count(*)'];
        }

        $this->assign('data', $data);
        $this->assign('page', $show); // 赋值分页输出
        $this->display();        
    }
    
    
    
    //添加和修改类目
    function addLeimu($leimu_id=null){
        $db = M();
        
        if(!empty($_POST)){
            $leimu_name = I('post.leimu_name');
            if (empty($leimu_id)) {//代表是添加的逻辑
                $sql = "INSERT INTO leimu(leimu_name) VALUES ('$leimu_name')  "; //插入数据
                $status = $db->execute($sql);
                if ($status > 0) {
                    alertMes('数据添加成功');
                } else {
                    alertMes('数据添加失败');
                }
            }else{//代表是修改的逻辑
                   $sql="UPDATE leimu SET leimu_name='$leimu_name' WHERE leimu_id=$leimu_id";
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
        
        
        if(empty($leimu_id)){
            
        }else{
           $sql2="SELECT * FROM leimu WHERE leimu_id=$leimu_id"; 
           $result2=$db->query($sql2);
           $this->assign('result',$result2[0]);
        }
        
        
        
        
        $this->display();
        
        
        
        
        
    }
    
    //删除学院
   function delLeimu($leimu_id){
        $db = M();
        $sql = "DELETE FROM leimu  WHERE leimu_id=$leimu_id";
        $status = $db->execute($sql);

        if ($status > 0) {
            alertMes('删除成功');
            backAndReload();
        } else {
            alertMes('删除失败');
        }      
       
       
       
   }
    
    
    
    
    
}
