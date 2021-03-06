<?php
/**
 * 发送短信验证码
 * @author zhaojie
 *
 */
namespace Api\Controller;

class IndexController extends BaseController {

	
	//发送短信验证码
	public function sendCode(){
		
		//获取参数
		$jsonstr = I('request.jsonstr');
		
		//RSA解密
		import("Api.Util.RSA");
		$rsa = new \RSA();
		$rs = $rsa->decrypt($jsonstr);	
		
				
		$data_str = $rs['data_str'];
		$MD5encrypt = $rs['MD5encrypt'];		
		
		$data_map = json_decode($data_str,true);
		$merchant_no = $data_map['merchant_no'];
		$merchant_tel = $data_map['merchant_tel'];
		$md5str = $data_map['md5str'];
		
		$check_map['merchant_no'] = $data_map['merchant_no'];
		$check_map['merchant_tel'] = $data_map['merchant_tel'];
		//md5校验
		$md5_check = '';
		if($MD5encrypt == 1){
			$md5_check = md5(json_encode($check_map));
		}
		
		if($md5str != $md5_check){
			$return_data = $rsa->Status(10002,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		$code = mt_rand("100000", "999999");
		
		//根据传过来的crm_kh_khjlID查询crm_kh_hykhID
		$merchant_no = M("tbl_crm_kh")->where(array('crm_kh_khjlID'=>$merchant_no))->getField("crm_kh_hykhID");
		
		
		//发送商户绑定激活码短信
		$sms_code = "SMS_13190848";
		$SmsParam = "{\"code\":\"{$code}\"}";
		
		$res_obj = sendSMS($merchant_tel, $code, $sms_code,$SmsParam);//返回对象
		$res_array = object_to_array($res_obj);
		if($res_array['result']['success'] == true){
			//发送成功存入数据库SMSCODE表
			$addData = array(
				"code" => $code,
				"store" => $merchant_no,
				"mobile" => $merchant_tel,
				"codetime" => time(),
				"crdate" => time(),
				"tstamp" => time()
			);
			$res = M('Smscode')->add($addData);
			$return_data = $rsa->Status(0,'E','Send SMS Success');
			$rsa->ajaxReturn($return_data,JSON);
		}else{
			$return_data = $rsa->Status(10003,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
	}
	
	
	
	public function demo(){
		$this->display();
	}
}

?>