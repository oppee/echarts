<?php

class RSA {
	
	private $public_key;
	
	public function __construct() {
		$this->public_key = C("PUBLIC_KEY");
	}
	
	/**
	 * RSA加密
	 */
	public function encrypted($jsonstr) {
		
		$pu_key = openssl_pkey_get_public($this->public_key);
		
		$encrypted_str="";
		
		foreach (str_split($jsonstr, 117) as $chunk) {
			openssl_public_encrypt($chunk, $encryptData, $pu_key);
			$encrypted_str .= $encryptData;
		}
		
		$rs['RSAdecrypt'] = 1;
		$rs['MD5encrypt'] = 1;
		$rs['gys_nm'] = C("GYS_NM");
		$rs['data'] = base64_encode($encrypted_str);
		
		return json_encode($rs);
	}
	
	
	
	/**
	 * RSA解密
	 */
	public function decrypt($jsonstr) {
		$fp = fopen("#runtime/Data/api_".date('Ymd').".log", "a+");
		fwrite($fp,date('Y-m-d H:i:s')." RSA decrypt jsonstr:".$jsonstr."\r\n");
		if(empty($jsonstr)){
			$return_data = $this->Status('10001');
			$this->ajaxReturn($return_data,JSON);
			die;
		}
		
		$json_map = json_decode($jsonstr,true);
		$RSAdecrypt = $json_map['RSAdecrypt'];
		$MD5encrypt = $json_map['MD5encrypt'];
		$data = $json_map['data'];
		
		if(empty($data)){
			$return_data = $this->Status('10001');
			$this->ajaxReturn($return_data,JSON);
		}
		
		$data_str = "";
		
		if($RSAdecrypt == 1){
			$pu_key = openssl_pkey_get_public($this->public_key);
			foreach (str_split(base64_decode($data), 128) as $chunk) {
            	openssl_public_decrypt($chunk, $decryptData, $pu_key);
            	$data_str .= $decryptData;
    		}	
		}else{
			$data_str = $data;
		}
		$rs['MD5encrypt'] = $MD5encrypt;
		$rs['data_str'] = $data_str;
		
		fwrite($fp,date('Y-m-d H:i:s')."RSA decrypt rs:".json_encode($rs,JSON_UNESCAPED_UNICODE)."\r\n");
		fclose($fp);
		
		return $rs;
	}
	
	
	
	
	
	
	/**
	 * 输出错误类型及说明
	 * @param integer $code  状态码
	 * @param text $t  输出HTTP状态信息
	 */
	public function Status($code,$t = '',$message = ''){
		if($t=='EA')$this->send_http_status('422');
		else $this->send_http_status('200');
		switch($code){
			case '10001';
			return array(
					'rs'=>'1',
					'errcode'=>'10001',
					'errmsg'=>'Invalid Parame'
			);
			break;
			case '10002';
			return array(
					'rs'=>'1',
					'errcode'=>'10002',
					'errmsg'=>'Md5 Check Error'
			);
			break;
			case '10003';
			return array(
					'rs'=>'1',
					'errcode'=>'10003',
					'errmsg'=>'Send SMS Error'
			);
			break;
			case '10004';
			return array(
					'rs'=>'1',
					'errcode'=>'10004',
					'errmsg'=>'DB Error'
			);
			break;
			case '10005';
			return array(
					'rs'=>'1',
					'errcode'=>'10005',
					'errmsg'=>'该手机号未注册账号，请确认!'
			);
			break;
			case '10006';
			return array(
					'rs'=>'1',
					'errcode'=>'10006',
					'errmsg'=>'原密码有误,请重新验证!'
			);
			break;
			case '10007';
			return array(
					'rs'=>'1',
					'errcode'=>'10007',
					'errmsg'=>'密码有误，请输入6-16位数字、字母或常用符号！'
			);
			break;
			case '10008';
			return array(
					'rs'=>'1',
					'errcode'=>'10008',
					'errmsg'=>'输入的验证码有误，请重新输入！'
			);
			break;
			case '10009';
			return array(
					'rs'=>'1',
					'errcode'=>'10009',
					'errmsg'=>'用户名有误，请输入6-16位数字、字母或常用符号！'
			);
			break;
			case '10010';
			return array(
					'rs'=>'1',
					'errcode'=>'10010',
					'errmsg'=>'该用户名已被注册！'
			);
			break;
			default :
				return array(
				'rs'=>'0',
				'errcode'=>'',
				'errmsg'=>''
				);
				break;
		}
	}
	
	/**
	 * 输出常见HTTP状态信息
	 * @author wanghuan
	 *
	 * @param integer $code 状态码
	 */
	public function send_http_status($code) {
		$status = array(
				'200' => 'OK',
				'301' => 'Moved Permanently',
				'302' => 'Moved Temporarily ',
				'400' => 'Bad Request',
				'401' => 'Unauthorized',
				'403' => 'Forbidden',
				'404' => 'Not Found',
				'422' => 'Unprocessible Entity',
				'500' => 'Internal Server Error',
				'503' => 'Service Unavailable',
		);
		$str = isset($status[$code]) ? (' ' . $status[$code]) : '';
		return header("HTTP/1.1 $code$str");
	}
	
	/**
	 * Ajax方式返回数据到客户端
	 * @access protected
	 * @param mixed $data 要返回的数据
	 * @param String $type AJAX返回数据格式
	 * @return void
	 */
	public function ajaxReturn($data,$type='') {
		if(empty($type)) $type  =   C('DEFAULT_AJAX_RETURN');
		switch (strtoupper($type)){
			case 'JSON' :
				// 返回JSON数据格式到客户端 包含状态信息
				header('Content-Type:application/json; charset=utf-8');
				exit(json_encode($data));
			case 'XML'  :
				// 返回xml格式数据
				header('Content-Type:text/xml; charset=utf-8');
				exit(xml_encode($data));
			case 'JSONP':
				// 返回JSON数据格式到客户端 包含状态信息
				header('Content-Type:application/json; charset=utf-8');
				$handler  =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
				exit($handler.'('.json_encode($data).');');
			case 'EVAL' :
				// 返回可执行的js脚本
				header('Content-Type:text/html; charset=utf-8');
				exit($data);
		}
	}
	
	
	
}

?>