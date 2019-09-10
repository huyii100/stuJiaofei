<?php


/**
 *	微微校支付接口基类, 包含AES加解密、	http参数签名、验签
 *  @author  hzy <hzy@ctvc.tv>
 *  @link http://wx.weiweixiao.net
 *  @version 1.0
 *  @abstract:
 *  
 *  
 *  发送方:
		$option['id'] = 'partner1';
		$option['aes_key'] = 'AD42F6697B035B7580E4FEF93BE20B4D'; 
		$option['sign_key'] = 'weiweixiao';

		$wwx = new WeiweixiaoPay($option);
	 	$param['user_no'] = '001';
		$param['status'] = '1';
		$param['page_no'] = '1';
		$data = $wwx->post('http://localhost/pay_test', $param);
		$data = json_decode($data,true);
		$wwx->decrypt_result($data);
	接收方：
		$data['id'] = $_POST['id'];
		$data['param'] = $_POST['param'];
		$data['sign'] = $_POST['sign'];
		$option['id'] = $data['id'];
		$option['aes_key'] = 'AD42F6697B035B7580E4FEF93BE20B4D'; 
		$option['sign_key'] = 'weiweixiao';
		$wwx = new WeiweixiaoPay($option);
		$wwx->decrypt_data($data);
		$ret = $wwx->gen_result(CODE_OK, array('user_no'=>'001','page_no'=>'1'));
		echo json_encode($ret);
 */

class xyk {
	
	private $partner_id;
	private $sign_key;
	private $aes_key;
	private $sign_off = true;//是否验证返回数据的签名
	/**
	 * 初始化配置，包括合作方id、微微校和合作方两对AES密钥、签名key
	 * @param array $options
	 * {
	 * 		id:'xxx',
	 * 		aes_key:'xxx',
	 * 		sign_key:'xxx'
	 * }
	 */
	public function __construct($options)
	{
		$this->partner_id = isset($options['id'])?$options['id']:'';
		$this->aes_key = isset($options['aes_key'])?$options['aes_key']:'';
		$this->sign_key = isset($options['sign_key'])?$options['sign_key']:'';
		$this->sign_off = isset($options['sign_off'])?$options['sign_off']:true;
	}
	
	private function pkcs5_pad ($text, $blocksize) {
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text.str_repeat(chr($pad), $pad);
	}
	
	/**
	 * AES加密字符串
	 * @param string $str 待加密字符串
	 * @return boolean|string 失败返回false，成功则返回加密并base64编码后的字符串
	 */
	private function encrypt($str){
		$size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$str = $this->pkcs5_pad($str, $size);
		$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		$iv = substr($this->aes_key, 0, 16);
		mcrypt_generic_init($td, $this->aes_key, $iv);
		$data = mcrypt_generic($td, $str);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$data = base64_encode($data);
		return $data;
	}
	
	/**
	 * AES私钥加密字符串
	 * @param string $str 待解密字符串
	 * @return boolean|string 失败返回false，成功返回解密后的字符串
	 */
	private function decrypt($str){
    	$iv = substr($this->aes_key, 0, 16);
        //Open module
        $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, $iv);
 
        mcrypt_generic_init($module, $this->aes_key, $iv);
 
        $data = base64_decode($str);
        $data = mdecrypt_generic($module, $data);
        
        $len = strlen($data);
        $char = ord($data[$len-1]);
        $data = substr($data, 0, $len-$char);

        return $data;
	}
	
	/**
	 * 生成请求参数的sign签名字段
	 * @param array $data 
	 * { id:'xxx',param:'xxx'}
	 * @return string
	 */
	public function gen_data_sign($data){
		return md5($data['id'].$data['param'].$this->sign_key);
	}
	
	/**
	 * 生成响应参数的sign签名字段
	 * @param array $result
	 * {ret_code:'0',ret_content:'xxx'}
	 * @return string
	 */
	public function gen_result_sign($result){
		return md5($result['ret_code'].$result['ret_content'].$this->sign_key);
	}
	
	/**
	 * 向第三方接口发送http post请求
	 * @param string $url 第三方接口url
	 * @param array $param 请求参数
	 * @return Ambigous <string, boolean, mixed>
	 */
	public function post($url, $param){
		$data['id'] = $this->partner_id;
		if(!is_string($param)){
			$param = json_encode($param);
		}
		$data['param'] = $this->encrypt($param);
		$data['sign'] = $this->gen_data_sign($data);
		return $this->send_post($url, $data);
	}

	private function send_post($url, $post_data) {  
		 $postdata = http_build_query($post_data); 
		  $options = array(    
			  'http' => array(      
				  'method' => 'POST',     
				   'header' => 'Content-type:application/x-www-form-urlencoded',      
				   'content' => $postdata,  
					 )  );  
					$context = stream_context_create($options); 
					$result = file_get_contents($url, false, $context);   
					return $result;}
	
	/**
	 * http post请求参数验签
	 * @param array $data
	 * @param string $sign_key
	 * @return boolean
	 */
	public function verify_data($data,$sign_key){
		return $data['sign'] == md5($data['id'].$data['param'].$sign_key);
	}
	
	/**
	 * http post响应结果验签
	 * @param array $data
	 * @param string $sign_key
	 * @return boolean
	 */
	public function verify_result($data,$sign_key){
		if ($this->sign_off) {
			return true;
		}
		return $data['sign'] == md5($data['ret_code'].$data['ret_content'].$sign_key);
	}
	
	/**
	 * 验签并解密http post参数的param字段
	 * @param unknown $data
	 * @return boolean
	 */
	public function decrypt_data(&$data){
		if (!$this->verify_data($data, $this->sign_key)) {
			return false;
		}
		$decrypted = $this->decrypt($data['param']);
		if(!$decrypted){
			return false;
		}
		$data['param'] = json_decode($decrypted,true);
		if(empty($data['param'])){
			$data['param'] = $decrypted;
		}
		return true;
	}
	
	/**
	 * 验签并解密http post响应的ret_content字段
	 * @param array $data
	 * @return boolean
	 */
	public function decrypt_result(&$data){
		if (!$this->verify_result($data, $this->sign_key)) {
			dump('sign error');
			return false;
		}
		$decrypted = $this->decrypt($data['ret_content']);
		if(!$decrypted){
			dump('decrypt error');
			return false;
		}
		if($data['ret_code'] == 0){
			$data['ret_content'] = json_decode($decrypted,true);
		}
		else{
			$data['ret_content'] = $decrypted;
		}
		if(empty($data['param'])){
			$data['param'] = $decrypted;
		}
		return true;
	}
	
	/**
	 * 生成http post响应结果，加密&签名
	 * @param unknown $ret_code
	 * @param unknown $ret_content
	 * @return string
	 */
	public function gen_result($ret_code,$ret_content){
		$result['ret_code'] = $ret_code;
		if (!is_string($ret_content)) {
			$ret_content = json_encode($ret_content);
		}
		$result['ret_content'] = $this->encrypt($ret_content);
		$result['sign'] = $this->gen_result_sign($result);
		return $result;
	}
	
	/**
	 * 生成http post响应结果，加密&签名
	 * @param unknown $ret_code
	 * @param unknown $ret_content
	 * @return string
	 */
	public function gen_json_result($ret_code,$ret_content){
		return json_encode($this->gen_result($ret_code, $ret_content));
	}
}

?>