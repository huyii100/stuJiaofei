<?php
/**
 * @Author:  
 * @Date:   2017-08-11 16:14:45
 * @Last Modified by:   Marte
 * @Last Modified time: 2017-08-25 11:43:43
 */
function upload_img()
{
    $upload = new \Think\Upload();// 实例化上传类
    $upload->maxSize   =     3145728 ;// 设置附件上传大小
    $upload->exts      =     array('xlsx', 'xls','jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    $upload->rootPath  =     './'; // 设置Excel/附件上传根目录
    $upload->savePath  =     ''; // 设置附件上传（子）目录
    $info   =   $upload->uploadOne($_FILES['file']);
    return $info;
}
function upload_excel($file_name=""){
    include './Public/PHPExcel/Classes/PHPExcel.php';
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
    
    
             $data=[];   
             for($i=2;$i<=$highestRow;$i++){
                    $data[$i-2]['A'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();//获取A列的值
                    $data[$i-2]['B']= $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();//获取B列的值
                    $data[$i-2]['C']= $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();//获取C列的值
                    $data[$i-2]['D'] = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();//获取D列的值
                    $data[$i-2]['E']= $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();//获取E列的值
                    $data[$i-2]['F']= $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();//获取F列的值
                    $data[$i-2]['G'] = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();//获取G列的值
                    $data[$i-2]['H'] = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();//获取H列的值
                    $data[$i-2]['I'] = $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();//获取I列的值
            }              
               
         
            return $data;
}
