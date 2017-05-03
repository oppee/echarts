<?php
/**
 * 用户接口
 *
 */
namespace Api\Controller;

class UserController extends BaseController {

	/**
	 * 修改密码
	 */
	public function changeUserPassword(){
		
		//获取参数
		$jsonstr = I('request.jsonstr');
		
		import("Api.Util.RSA");
		$rsa = new \RSA();
		
		
		if(empty($jsonstr)){
			$return_data = $rsa->Status(10001,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		//RSA解密
		$rs = $rsa->decrypt($jsonstr);
		
		$data_str = $rs['data_str'];
		$MD5encrypt = $rs['MD5encrypt'];
		
		$data_map = json_decode($data_str,true);
		
		//用户手机
		$tel = $data_map['tel'];
		//原密码
		$old_password = $data_map['old_password'];
		//新密码
		$new_password = $data_map['new_password'];
		$md5str  = $data_map['md5str'];
		
		//参数校验
		if(empty($tel) || empty($old_password) || empty($new_password)|| empty($md5str)){
			$return_data = $rsa->Status(10001,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		$regex = '/^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{6,16}$/';
		if(!preg_match($regex, $new_password, $matches)){
			//密码有误，请输入6-16位数字、字母或常用符号！
			$return_data = $rsa->Status(10007,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		$check_map['tel'] = $data_map['tel'];
		$check_map['old_password'] = $data_map['old_password'];
		$check_map['new_password'] = $data_map['new_password'];
		//md5校验
		$md5_check = '';
		if($MD5encrypt == '1'){
			$md5_check = md5(json_encode($check_map));
		}		
		if($md5str != $md5_check){
			$return_data = $rsa->Status(10002,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		//数据库校验
		if(!M('user')->where(array('hidden'=>0,'deleted'=>0,'mobile'=>$tel))->find()){
			//该手机号未注册账号
			$return_data = $rsa->Status(10005,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		if(!M('user')->where(array('hidden'=>0,'deleted'=>0,'mobile'=>$tel,'password'=>md5(base64_encode($old_password))))->find()){
			//原密码有误,请重新验证!
			$return_data = $rsa->Status(10006,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		//修改密码
		$data = array(
				"password" => md5(base64_encode($new_password)),
				"tstamp" => time(),
		);
		$result = M("user")->where(array('mobile'=>$tel))->save($data);
		if(false !== $result || 0 !== $result){
			$return_data = $rsa->Status(0,'E','修改密码成功');
			$rsa->ajaxReturn($return_data,JSON);
		}else{
			$return_data = $rsa->Status(10004,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		
	}
	
	
	
	/**
	 * 忘记密码
	 */
	public function forgotUserPassword(){
		
		//获取参数
		$jsonstr = I('request.jsonstr');
		
		import("Api.Util.RSA");
		$rsa = new \RSA();
		
		
		if(empty($jsonstr)){
			$return_data = $rsa->Status(10001,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		//RSA解密
		$rs = $rsa->decrypt($jsonstr);
		
		$data_str = $rs['data_str'];
		$MD5encrypt = $rs['MD5encrypt'];
		
		$data_map = json_decode($data_str,true);
		
		//用户手机
		$tel = $data_map['tel'];
		//验证码
		$code = $data_map['code'];
		//新密码
		$password = $data_map['password'];
		//标识  1、发送验证码	2、重置密码
		$flg = $data_map['flg'];
		$md5str  = $data_map['md5str'];
		
		$check_map['tel'] = $data_map['tel'];
		$check_map['code'] = $data_map['code'];
		$check_map['password'] = $data_map['password'];
		$check_map['flg'] = $data_map['flg'];
		//md5校验
		$md5_check = '';
		if($MD5encrypt == '1'){
			$md5_check = md5(json_encode($check_map));
		}
		if($md5str != $md5_check){
			$return_data = $rsa->Status(10002,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		if(empty($tel) || empty($flg)){
			$return_data = $rsa->Status(10001,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		if($flg == '1'){
			//数据库校验
			if(!M('user')->where(array('hidden'=>0,'deleted'=>0,'mobile'=>$tel))->find()){
				//该手机号未注册账号
				$return_data = $rsa->Status(10005,'E');
				$rsa->ajaxReturn($return_data,JSON);
			}
			//发送验证码
			$code = mt_rand(100000, 999999);
			$sms_code = "SMS_13181156";
			$SmsParam = "{\"code\":\"{$code}\"}";
			$res_obj = sendSMS($tel, $code, $sms_code,$SmsParam);
			$res_array = object_to_array($res_obj);
			if($res_array['result']['success'] == true){
				cookie('code',$code,180);
				$return_data = $rsa->Status(0,'E','Send SMS Success');
				$rsa->ajaxReturn($return_data,JSON);
			}else{
				$return_data = $rsa->Status(10003,'E');
				$rsa->ajaxReturn($return_data,JSON);
			}			
		}else if($flg == '2'){
			if(empty($code) || empty($password)){
				$return_data = $rsa->Status(10001,'E');
				$rsa->ajaxReturn($return_data,JSON);
			}
			$regex = '/^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{6,16}$/';
			if(!preg_match($regex, $password, $matches)){
				//密码有误，请输入6-16位数字、字母或常用符号！
				$return_data = $rsa->Status(10007,'E');
				$rsa->ajaxReturn($return_data,JSON);
			}
			//数据库校验
			if(!M('user')->where(array('hidden'=>0,'deleted'=>0,'mobile'=>$tel))->find()){
				//该手机号未注册账号
				$return_data = $rsa->Status(10005,'E');
				$rsa->ajaxReturn($return_data,JSON);
			}
			if(!cookie('code') || $code != cookie('code')){
				$return_data = $rsa->Status(10008,'E');
				$rsa->ajaxReturn($return_data,JSON);
			}
			
			//重置密码
			$data = array(
					"password" => md5(base64_encode($password)),
					"tstamp" => time(),
			);
			$result = M("user")->where(array('mobile'=>$tel))->save($data);
			if(false !== $result || 0 !== $result){
				$return_data = $rsa->Status(0,'E','重置密码成功');
				$rsa->ajaxReturn($return_data,JSON);
			}else{
				$return_data = $rsa->Status(10004,'E');
				$rsa->ajaxReturn($return_data,JSON);
			}
			
		}else{
			$return_data = $rsa->Status(10001,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		
	}
	
	
	/**
	 * 修改个人信息
	 */
	public function changeUserInfo(){
	
		//获取参数
		$jsonstr = I('request.jsonstr');
		
		//RSA解密
		import("Api.Util.RSA");
		$rsa = new \RSA();
		$rs = $rsa->decrypt($jsonstr);
		
		$data_str = $rs['data_str'];
		$MD5encrypt = $rs['MD5encrypt'];
		
		$data_map = json_decode($data_str,true);
		
		//用户手机
		$tel = $data_map['tel'];
		//用户名
		$username = $data_map['username'];
		//昵称
		$nickname = $data_map['nickname'];
		//性别    1男，2女
		$gender = $data_map['gender'];		
		$md5str  = $data_map['md5str'];
		
		$check_map['tel'] = $data_map['tel'];
		$check_map['username'] = $data_map['username'];
		$check_map['nickname'] = $data_map['nickname'];
		$check_map['gender'] = $data_map['gender'];
		//md5校验
		$md5_check = '';
		if($MD5encrypt == '1'){
			$md5_check = md5(json_encode($check_map,JSON_UNESCAPED_UNICODE));
		}
		if($md5str != $md5_check){
			$return_data = $rsa->Status(10002,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		if(empty($tel)){
			$return_data = $rsa->Status(10001,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		if(empty($username) && empty($nickname) && empty($gender)){
			$return_data = $rsa->Status(10001,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		if(!empty($username)){
			$regex = '/^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{6,16}$/';
			if(!preg_match($regex, $username, $matches)){
				//用户名有误，请输入6-16位数字、字母或常用符号！
				$return_data = $rsa->Status(10009,'E');
				$rsa->ajaxReturn($return_data,JSON);
			}
		}
		if(!empty($gender)){
			if($gender != '1' && $gender != '2'){
				$return_data = $rsa->Status(10001,'E');
				$rsa->ajaxReturn($return_data,JSON);
			}
		}
		if(!empty($nickname)){
			if(strlen($nickname) > 100){
				$return_data = $rsa->Status(10001,'E');
				$rsa->ajaxReturn($return_data,JSON);
			}
		}
		
		//数据库校验
		if(!M('user')->where(array('hidden'=>0,'deleted'=>0,'mobile'=>$tel))->find()){
			//该手机号未注册账号
			$return_data = $rsa->Status(10005,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}		
		if(M('User')->where(array('hidden'=>0,'username'=>$username,'deleted'=>0,'mobile'=>array('neq',$tel)))->find()){
			//该用户名已被注册！
			$return_data = $rsa->Status(10010,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
		//更新用户信息
		$data = array(
				"username" => $username,
				"nickname" => $nickname,
				"gender" => $gender,
				"tstamp" => time(),
		);
		$result = M("user")->where(array('mobile'=>$tel))->save($data);
		if(false !== $result || 0 !== $result){
			$return_data = $rsa->Status(0,'E','修改个人信息成功');
			$rsa->ajaxReturn($return_data,JSON);
		}else{
			$return_data = $rsa->Status(10004,'E');
			$rsa->ajaxReturn($return_data,JSON);
		}
		
	
	}
	
	
	
	
	
}

?>