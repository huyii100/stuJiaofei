<?php

namespace Admin\Controller;
use Think\Controller;
header("Content-type: text/html; charset=utf-8");
class ExcelController extends Controller {
       
    
    //导入学生
    public function uploadExcelUser(){
         
            // 上传文件
            $info = upload_img();
            if(!$info) {// 上传错误提示错误信息
               // $this->error($upload->getError());
            }else{// 上传成功
                $file_name = "./uploads/";
                $file_name  .= $info["savepath"];
                $file_name .= $info["savename"];//文件路径
               // echo $file_name;
                //上传excel
                $data = upload_excel($file_name);
                //$data为返回的excel数据
                
                
                
                $db=M();
                $failData=[];//导入失败的数据
               //循环插入数据 
               for($i=0;$i<count($data);$i++){
                   $sfz_num=trim($data[$i]['A']);
                   $user_name=trim($data[$i]['B']);
                   $xue_num=trim($data[$i]['C']);
                   $xueyuan_name=trim($data[$i]['D']);
                   $xueji=trim($data[$i]['E']);
                   $zhuanye=trim($data[$i]['F']);
                   $banji=trim($data[$i]['G']);
                   $phone=trim($data[$i]['H']);
                   $state=trim($data[$i]['I']);
                   
                   //判断导入的数据是否符合，不符合则忽略
                   

                   $sql="SELECT sfz_num FROM user WHERE sfz_num='$sfz_num'";//查询导入的身份证和数据库中是否有重复
                   $result=$db->query($sql);
                   $sql2="SELECT xueyuan_id FROM xueyuan WHERE xueyuan_name='$xueyuan_name'";//查询学院是否存在
                   $result2=$db->query($sql2);                 
                   
                    if(empty($sfz_num)){
                       $data[$i]['liyou']='该条数据的身份证号为空';
                       $failData[]=$data[$i];
                   }                  
                   else if(!empty($result)){
                       $data[$i]['liyou']='该条数据的身份证号为与其他用户的身份证号重复了';
                       $failData[]=$data[$i];                       
                   }
                   else if(empty($result2)){
                       $data[$i]['liyou']='该条数据的学院在数据库中不存在';
                       $failData[]=$data[$i];                       
                   }                  
                   else{
                       $xueyuan_id=$result2[0]['xueyuan_id'];
                       $sql4="INSERT INTO USER(sfz_num,user_name,xue_num,xueyuan_id,xueji,banji,zhuanye,phone,state) VALUES ('$sfz_num','$user_name','$xue_num',$xueyuan_id,'$xueji','$banji','$zhuanye','$phone',$state)";
                       $status4=$db->execute($sql4); 
                   }
 
               }
               
               
              
                $this->assign('isdaook', 1);
                $this->assign('failData', $failData);
                $this->display('exceljieguo');   
                
            }        
        
        
        
     }     
    
    
   
     //导入学生缴费信息
     function uploadExcelOrder(){
           // 上传文件
            $info = upload_img();
            if(!$info) {// 上传错误提示错误信息
               // $this->error($upload->getError());
            }else{// 上传成功
                $file_name = "./uploads/";
                $file_name  .= $info["savepath"];
                $file_name .= $info["savename"];//文件路径
               // echo $file_name;
                //上传excel
                $data = upload_excel($file_name);
                //$data为返回的excel数据
                
                
                $db=M();
                $failData=[];//导入失败的数据
               //循环插入数据 
               for($i=0;$i<count($data);$i++){
                   $sfz_num=trim($data[$i]['A']);
                   $qishu=trim($data[$i]['B']);
                   $begin_time=trim($data[$i]['C']);
                   $end_time=trim($data[$i]['D']);
                   $money=trim($data[$i]['E']);
                   $leimu_name=trim($data[$i]['F']);
                   $status=trim($data[$i]['G']);                   
                   //判断导入的数据是否符合，不符合则忽略
                   

                   $sql="SELECT user_id FROM user WHERE sfz_num='$sfz_num'";//查询导入的身份证和数据库中是否存在
                   $result=$db->query($sql);
                   
                   $sql2="SELECT leimu_id FROM leimu WHERE leimu_name='$leimu_name'";//查询类目是否存在
                   $result2=$db->query($sql2);                 
                   
                   $user_id=$result[0]['user_id'];
                   $leimu_id=$result2[0]['leimu_id'];
                   $sql3="SELECT * FROM payorder WHERE user_id=$user_id AND qishu='$qishu' AND leimu_id=$leimu_id";//查询缴费记录是否重复
                   $result3=$db->query($sql3);
                   
                   
                   
                    if(empty($sfz_num)){
                       $data[$i]['liyou']='该条数据的身份证号为空';
                       $failData[]=$data[$i];
                   }                  
                   else if(empty($result)){
                       $data[$i]['liyou']='该条数据的身份证号对应的用户不存在';
                       $failData[]=$data[$i];                       
                   }
                   else if(empty($result2)){
                       $data[$i]['liyou']='该条数据的类目在数据库中不存在';
                       $failData[]=$data[$i];                       
                   } 
                   else if(!empty($result3)){
                       $data[$i]['liyou']='该条数据与数据库中现有的缴费记录重复了';
                       $failData[]=$data[$i];                           
                   }
                   else{
                       $leimu_id=$result2[0]['leimu_id'];
                       $user_id=$result[0]['user_id'];
                       $begin_time=$begin_time.' 00:00:00';
                       $end_time=$end_time.' 23:59:59';
              
                        if($status==1){
                            $pay_time=date("Y-m-d H:i:s");
                        }else{
                            $pay_time='';
                        }                      
                        $random=rand(10000000000000,99999999999999);//生成随机数
                        $order_num=time().$random;                      
                       
                       $sql4="INSERT INTO payorder(user_id,qishu,order_num,begin_time,end_time,money,leimu_id,paytype,status,pay_time) VALUES ($user_id,'$qishu','$order_num','$begin_time','$end_time',$money,$leimu_id,2,$status,'$pay_time')";
                       $status4=$db->execute($sql4); 
                   }
 
               }
               
               
        
                $this->assign('isdaook', 1);
                $this->assign('failData', $failData);
                $this->display('exceljieguo2');   
                
            }        
        
 
         
     }
     
     
    
    
    
    
       //导出缴费信息
       function exportOrderExcel(){
                $db=M();
                $sql="SELECT payorder.*,leimu_name,user_name,sfz_num,DATE_FORMAT(begin_time,'%Y-%m-%d') AS begin_date,DATE_FORMAT(end_time,'%Y-%m-%d') AS end_date FROM payorder,USER,leimu WHERE payorder.user_id=user.user_id AND payorder.leimu_id=leimu.leimu_id ORDER BY payorder_createtime";
                $Data=$db->query($sql);
		$Title ='学生缴费信息';
		$Title1 = '缴费人';
		$Title2 = '缴费身份证';
		$Title3 = '学年';
		$Title4 = '所属类目';
		$Title5 = '订单编号';
		$Title6 = '缴费开始时间';
                $Title7 = '缴费结束时间';
                $Title8 = '缴费金额';
                $Title9 = '支付类型';
                $Title10 = '支付时间';
                $Title11 = '备注';
                $Title12 = '缴费状态';
                $Title13 = '缴费记录生成时间';
                
		include './Public/PHPExcel/Classes/PHPExcel.php';
		$objPHPExcel = new \PHPExcel();                
		$objPHPExcel->getProperties()->setCreator("Da")
			->setLastModifiedBy("Da")
			->setTitle("Office 2007 XLSX Test Document")
			->setSubject("Office 2007 XLSX Test Document")
			->setDescription("Test document for Office 2007 XLSX,generated using PHP classes.")
			->setKeywords("office 2007 openxml php")
			->setCategory("Test result file");

		$objPHPExcel->setActiveSheetIndex(0);        
		$objPHPExcel->getActiveSheet(0)->setTitle($Title);//标题
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);//单元格宽度
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');//设置字体
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);//设置字体大小
		$objPHPExcel->getActiveSheet(0)->setCellValue('A1',$Title1);
		$objPHPExcel->getActiveSheet(0)->setCellValue('B1',$Title2);
		$objPHPExcel->getActiveSheet(0)->setCellValue('C1',$Title3);
		$objPHPExcel->getActiveSheet(0)->setCellValue('D1',$Title4);
		$objPHPExcel->getActiveSheet(0)->setCellValue('E1',$Title5);
		$objPHPExcel->getActiveSheet(0)->setCellValue('F1',$Title6); 
                $objPHPExcel->getActiveSheet(0)->setCellValue('G1',$Title7); 
                $objPHPExcel->getActiveSheet(0)->setCellValue('H1',$Title8); 
                $objPHPExcel->getActiveSheet(0)->setCellValue('I1',$Title9); 
                $objPHPExcel->getActiveSheet(0)->setCellValue('J1',$Title10); 
                $objPHPExcel->getActiveSheet(0)->setCellValue('K1',$Title11); 
                $objPHPExcel->getActiveSheet(0)->setCellValue('L1',$Title12); 
                $objPHPExcel->getActiveSheet(0)->setCellValue('M1',$Title13); 

                
                
		$j = 2;
		for($i=0 ;$i<count($Data) ;$i++){
                    
                            $data_i=$Data[$i];
            
			    $objPHPExcel->getActiveSheet(0)->setCellValue('A' . $j, $Data[$i]['user_name']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('B' . $j, " ".$Data[$i]['sfz_num']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('C' . $j, $Data[$i]['qishu'] );
			    $objPHPExcel->getActiveSheet(0)->setCellValue('D' . $j, $Data[$i]['leimu_name']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('E' . $j, " ".$Data[$i]['order_num']);	
                            $objPHPExcel->getActiveSheet(0)->setCellValue('F' . $j, $Data[$i]['begin_date'] );
                            $objPHPExcel->getActiveSheet(0)->setCellValue('G' . $j, $Data[$i]['end_date']);
                            $objPHPExcel->getActiveSheet(0)->setCellValue('H' . $j, $Data[$i]['money']);
                            $objPHPExcel->getActiveSheet(0)->setCellValue('I' . $j, $Data[$i]['paytype']==1?'线上支付':'线下支付');
                            $objPHPExcel->getActiveSheet(0)->setCellValue('J' . $j, $Data[$i]['pay_time']=='0000-00-00 00:00:00'?'无':$Data[$i]['pay_time']);
                            $objPHPExcel->getActiveSheet(0)->setCellValue('K' . $j, $Data[$i]['beizhu']);
                            $objPHPExcel->getActiveSheet(0)->setCellValue('L' . $j, $Data[$i]['status']==1?'已缴费':'未缴费');
                            $objPHPExcel->getActiveSheet(0)->setCellValue('M' . $j, $Data[$i]['payorder_createtime']);
                            
			$j++;
		}
                
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$Title.date("YmdHis").'.xls"');
		header('Cache-Control: max-age=0');
		ob_clean();//关键
		   flush();//关键
		$objWriter =\ PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
                   
          
                 
                   
		exit;                      
           
           
           
       }

       
       
       //导出学生信息
       function exportUserExcel(){
                $db=M();
                $sql="SELECT user.*,xueyuan_name  FROM USER,xueyuan WHERE  user.xueyuan_id=xueyuan.xueyuan_id ORDER BY user_createtime";
                $Data=$db->query($sql);
		$Title ='学生信息';
		$Title1 = '身份证号';
		$Title2 = '姓名';
		$Title3 = '学号';
		$Title4 = '所属单位';
		$Title5 = '学年';
		$Title6 = '班级';
                $Title7 = '专业';
                $Title8 = '联系电话';
                $Title9 = '禁用状态';
                $Title10 = '学生被创建时间';
                
		include './Public/PHPExcel/Classes/PHPExcel.php';
		$objPHPExcel = new \PHPExcel();                
		$objPHPExcel->getProperties()->setCreator("Da")
			->setLastModifiedBy("Da")
			->setTitle("Office 2007 XLSX Test Document")
			->setSubject("Office 2007 XLSX Test Document")
			->setDescription("Test document for Office 2007 XLSX,generated using PHP classes.")
			->setKeywords("office 2007 openxml php")
			->setCategory("Test result file");

		$objPHPExcel->setActiveSheetIndex(0);        
		$objPHPExcel->getActiveSheet(0)->setTitle($Title);//标题
		$objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);//单元格宽度
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setName('Arial');//设置字体
		$objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);//设置字体大小
		$objPHPExcel->getActiveSheet(0)->setCellValue('A1',$Title1);
		$objPHPExcel->getActiveSheet(0)->setCellValue('B1',$Title2);
		$objPHPExcel->getActiveSheet(0)->setCellValue('C1',$Title3);
		$objPHPExcel->getActiveSheet(0)->setCellValue('D1',$Title4);
		$objPHPExcel->getActiveSheet(0)->setCellValue('E1',$Title5);
		$objPHPExcel->getActiveSheet(0)->setCellValue('F1',$Title6); 
                $objPHPExcel->getActiveSheet(0)->setCellValue('G1',$Title7); 
                $objPHPExcel->getActiveSheet(0)->setCellValue('H1',$Title8); 
                $objPHPExcel->getActiveSheet(0)->setCellValue('I1',$Title9); 
                $objPHPExcel->getActiveSheet(0)->setCellValue('J1',$Title10); 

		$j = 2;
		for($i=0 ;$i<count($Data) ;$i++){
                    
                            $data_i=$Data[$i];
            
			    $objPHPExcel->getActiveSheet(0)->setCellValue('A' . $j, " ".$Data[$i]['sfz_num']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('B' . $j, $Data[$i]['user_name']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('C' . $j, " ".$Data[$i]['xue_num']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('D' . $j, $Data[$i]['xueyuan_name']);
			    $objPHPExcel->getActiveSheet(0)->setCellValue('E' . $j, $Data[$i]['xueji']);	
                            $objPHPExcel->getActiveSheet(0)->setCellValue('F' . $j, $Data[$i]['banji'] );
                            $objPHPExcel->getActiveSheet(0)->setCellValue('G' . $j, $Data[$i]['zhuanye']);
                            $objPHPExcel->getActiveSheet(0)->setCellValue('H' . $j, $Data[$i]['phone']);
                            $objPHPExcel->getActiveSheet(0)->setCellValue('I' . $j, $Data[$i]['state']==1?'被禁用':'已启用');
                            $objPHPExcel->getActiveSheet(0)->setCellValue('J' . $j, $Data[$i]['user_createtime']);
                            
			$j++;
		}
                
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$Title.date("YmdHis").'.xls"');
		header('Cache-Control: max-age=0');
		ob_clean();//关键
		   flush();//关键
		$objWriter =\ PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
                   
          
                   
                   
		exit;                      
           
           
           
       }       
       
       
       
       
       
       
       
  /**  示例***/
      public function touris_save_edit()
    {
        $admin_id = session('admin_id');
        $touris_id = I('get.touris_id');//接收行程id，用来判断更新数据
        $title = I('post.title');//旅游标题
        $address = I('post.address');//旅游目的地
        $sta_time = I('post.sta_time');//开始时间
        $end_time = I('post.end_time');//结束时间
        $info_1 = I('post.info_1');//主领队
        $info_2 = I('post.info_2');//副领队
        $car = I('post.car');//车牌号
        $money = I('post.money');//应付金额
        $content = I('post.content');//旅游行程
        if($touris_id)
        {
            // 上传文件
            $info = upload_img();
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功
                $data  = array('title'=>$title,'address'=>$address,'sta_time'=>$sta_time,'end_time'=>$end_time,'car'=>$car,'trip'=>$content,'admin_id'=>$admin_id);
                $touris = M('touris');
                $map['touris_id']=$touris_id;
                $result = $touris->where($map)->save($data);
                $user = M('user')->where($map)->delete();
                if($info_1)
                {
                    $leader = M('leader');
                    $map="";
                    $map['leader_id'] = $info_1;
                    $data="";
                    $data = array('touris_id' => $touris_id);
                    $res = $leader->where($map)->save($data);
                    if($info_2)
                    {
                        $map="";
                        $map['leader_id'] = $info_2;
                        $data="";
                        $data = array('touris_id' => $touris_id);
                        $leader->where($map)->save($data);
                    }
                }
                $file_name = "./Public/upload/";
                $file_name  .= $info["savepath"];
                $file_name .= $info["savename"];//文件路径
                //上传excel
                $data = upload_excel($file_name,$touris_id,$money,$admin_id);
                if($data['count']==$data['highestRow']-1)
                    {
                        $a = $data['highestRow']-$data['count']-1;
                        $this->success('导入成功'.$data['count'].'条数据'.'导入失败'.$a.'条数据',"",10);
                    }else
                    {
                        $this->error('导入失败',"",10);
                    }
            }
        }

    }
   
    
       
       
       
       
       function upload_excel($file_name="",$result=0,$money=0,$admin_id){
    vendor('PHPExcel.Classes.PHPExcel');
    $objPHPExcel = new \PHPExcel();
    $objReader = \PHPExcel_IOFactory::createReader('Excel5');
    $objPHPExcel = $objReader->load($file_name,$encode='utf-8');
    $extension = strtolower( pathinfo($file_name, PATHINFO_EXTENSION) );
    if ($extension =='xlsx') {
        $objReader = new \PHPExcel_Reader_Excel2007();
        $objExcel = $objReader ->load($file_name);
    } else if ($extension =='xls') {
        $objReader = new \PHPExcel_Reader_Excel5();
        $objExcel = $objReader ->load($file_name);
    } else if ($extension=='csv') {
        $PHPReader = new \PHPExcel_Reader_CSV();
        //默认输入字符集
        $PHPReader->setInputEncoding('GBK');
        //默认的分隔符
        $PHPReader->setDelimiter(',');
        //载入文件
        $objPHPExcel = $PHPReader->load($file_name);
    }
    
    
    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow(); // 取得总行数
    $highestColumn = $sheet->getHighestColumn(); // 取得总列数
    if($highestRow>=1)
    {
        $count=0;
        $a=1;
        $b=1;
            for($i=2;$i<=$highestRow;$i++)
            {
                    $A = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();//获取A(用户名)列的值
                    $B= $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();//获取B(人数)列的值
                    if(!$B)
                    {
                        $B= $objPHPExcel->getActiveSheet()->getCell("B".($i-$a))->getValue();//获取B(人数)列的值
                        $a++;
                        $B = $b;
                    }else
                    {
                        $a=1;
                        if($B!=1)
                        {
                             $b++;
                            $B = $b;
                        }

                    }
                    $C= $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();//获取C(手机号码)列的值
                    $D = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();//获取G(预付金额)列的值
                    if($D=="" || $D==NULL){$D=0;}
                    $data  = array('touris_id' => $result,'name'=>$A,'tel'=>$C,'cate'=>$B,'prepay'=>$D,'money'=>$money,'admin_id'=>$admin_id);
                    $msg= M('user')->add($data);
                    if($msg)
                    {
                        $count = $count+1;
                    }
            }
    }
    return $info = array('highestRow' => $highestRow,'count'=>$count );
}
       
       
       
       
       
}
