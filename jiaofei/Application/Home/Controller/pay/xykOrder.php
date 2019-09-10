<?php
namespace Home\Controller;
use xyk;
use utils;
require ('xyk.class.php');
require ('utils.class.php');
	class xykOrder{
		protected $options = array(
			"id" => utils::id,
			"aes_key" => utils::aes_key,
			"sign_key" => utils::sign_key,
		);
                
                function test(){
                    $data['test']='haha';
                    $this->ajaxReturn($data, 'JSON');
                    
                }
                
                
                
		//创建订单
		public function createOrder(){
			$req['body'] = '校园卡支付测试';
			$req['out_trade_no'] = uniqid(mt_rand());
			$req['total_fee'] = '1';
			$req['return_url'] =utils::returnUrl;
			$req['notify_url'] =utils::notifyUrl;
			$pay = new xyk($this->options);
			$content = $pay->post(utils::creatrOrderUrl, $req);
			$result = json_decode($content, true);
			$pay->decrypt_result($result);
			echo (json_encode($result));
		}
		//查询订单
		public function selectOrder(){
			$req['out_trade_no'] = $_GET['order_id'];
			$pay = new xyk($this->options);
			$content = $pay->post(utils::selectOrderUrl, $req);
			$result = json_decode($content, true);
			$pay->decrypt_result($result);
			echo (json_encode($result));
			}
		//取消订单
		public function cancelOrder(){
			$req['out_trade_no'] = $_POST['order_id'];
			$pay = new xyk($this->options);
			$content = $pay->post(utils::cancleOrderUrl, $req);
			$result = json_decode($content, true);
			$pay->decrypt_result($result);
			echo (json_encode($result));
			}
		//通知
		public function notify(){
			$req['out_trade_no'] =$_GET['order_id'];
			$req['order_id'] = '1514270584761306';
			$req['time_end'] = '2017-12-26 14:43:21';
			$req['uid'] = '2088412828744974';
			$pay = new xyk($this->options);
			$content = $pay->post($this->notifyUrl,$req);
			echo $content;
			// $result = json_decode($content, true);
			// $pay->decrypt_result($result);
			// echo (json_encode($result));
			}
		//回调
		public function testCallBack(){
			$pay = new xyk($this->options);
			$data['param']=$_POST['param'];
			$data['id']=$_POST['id'];
			$data['sign']=$_POST['sign'];
			$pay->decrypt_data($data);
			$order_id=$data['param']['out_trade_no'];
			$result= header("Location: http://127.0.0.1/xykOrder.php?action=check_notice&&order_id=$order_id");
			echo $result;
			}
	}
 
?>