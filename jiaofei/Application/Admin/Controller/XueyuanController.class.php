<?php

// 本类由系统自动生成，仅供测试用途

namespace Admin\Controller;

class XueyuanController extends BaseController {

    
    
    //学院列表
    function xueyuanList(){
        $db = M();
        $sqlcount = "SELECT count(*) FROM xueyuan";
        $datacount = $db->query($sqlcount);
        $count = $datacount[0]['count(*)'];
        $page = new \Think\Page($count, 15);
        $show = fenye($page);

        $sql = "SELECT * FROM xueyuan ORDER BY xueyuan_createtime DESC   limit $page->firstRow,$page->listRows";
        $data = $db->query($sql);       
        for($i=0;$i<count($data);$i++){
            $xueyuan_id=$data[$i]['xueyuan_id'];
            $sql2="SELECT count(*) FROM USER WHERE xueyuan_id=$xueyuan_id";
            $result2=$db->query($sql2);
            $data[$i]['user_count']=$result2[0]['count(*)'];
        }

        $this->assign('data', $data);
        $this->assign('page', $show); // 赋值分页输出
        $this->display();        
    }
    
    
    
    //添加和修改学院
    function addXueyuan($xueyuan_id=null){
        $db = M();
        
        if(!empty($_POST)){
            $xueyuan_name = I('post.xueyuan_name');
            if (empty($xueyuan_id)) {//代表是添加的逻辑
                $sql = "INSERT INTO xueyuan(xueyuan_name) VALUES ('$xueyuan_name')  "; //插入数据
                $status = $db->execute($sql);
                if ($status > 0) {
                    alertMes('数据添加成功');
                } else {
                    alertMes('数据添加失败');
                }
            }else{//代表是修改的逻辑
                   $sql="UPDATE xueyuan SET xueyuan_name='$xueyuan_name' WHERE xueyuan_id=$xueyuan_id";
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
        
        
        if(empty($xueyuan_id)){
            
        }else{
           $sql2="SELECT * FROM xueyuan WHERE xueyuan_id=$xueyuan_id"; 
           $result2=$db->query($sql2);
           $this->assign('result',$result2[0]);
        }
        
        
        
        
        $this->display();
        
        
        
        
        
    }
    
    //删除学院
   function delXueyuan($xueyuan_id){
        $db = M();
        $sql = "DELETE FROM xueyuan  WHERE xueyuan_id=$xueyuan_id";
        $status = $db->execute($sql);

        if ($status > 0) {
            alertMes('删除成功');
            backAndReload();
        } else {
            alertMes('删除失败');
        }      
       
       
       
   }
    
    
    
    
    
}
