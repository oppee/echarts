<?php
/**
 * 个人中心
 * @author zhaojie
 *
 */
namespace Mobile\Controller;

class IndexController extends BaseController {
	/**
	 * 手机端个人中心
	 */	
    public function index() {
    	//授权 获取openid
    	$openid = session('openid');
    	if(empty($openid)){
    		$openid = I('request.openid');
    	}
    	if(empty($openid) || $openid == '-1'){
    		$code = I('request.code');
    		if($code){
    			$openid = $this->getOpenid($code);
    			session('openid',$openid,3600);
    		}else{
    			$url = "http://" . $_SERVER['SERVER_NAME'] . "/" . U("Mobile/Index/".ACTION_NAME);
    			$scope = I('request.scope')?I('request.scope'):'snsapi_base';
    			$this->autho_url($url, $scope);die;
    		}
    	}
    	if(empty($userinfo)){
    		$this->redirect("login");
    	}else{
			$this->display();
    	}
    }

    /**
     * 微信绑定页面
     */
	public function bind(){
		$this->assign('title',"绑定账号");
		
		//授权获得用户信息
		$openid = session('openid');
		if(empty($openid)){
			$openid = I('request.openid');
		}

		if(empty($openid)){
			$code = I('request.code');
			if($code){
				$openid_array = $this->getOpenid($code);
				$openid = $openid_array['openid'];
				$access_token = $openid_array['access_token'];
				session('openid',$openid_array['openid'],3600);
				session('access_token',$access_token,3600);
			}else{
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/Index/".ACTION_NAME);
				$scope = I('request.scope')?I('request.scope'):'snsapi_userinfo';
				$this->autho_url($url, $scope);die;
			}
		}
		/*
		if(empty($_SESSION['FEUSER']['id'])){
// 			$this->redirect("login");
			//未登录，跳转至登陆页
			cookie('return_url','bind',3600);
			$this->redirect('login');
			die;
		}*/
		
		//获取用户信息
		$where_u = array("openid"=>$openid,"deleted"=>0);
		$userid= M('User')->where($where_u)->getField('id');
		if(empty($userid)){
			//$this->error("未获取到用户数据");
			//未登录，跳转至登陆页
			cookie('return_url','bind',3600);
			$this->redirect('login');
			die;
		}
		
		if(IS_POST){
			$data = I('request.');
			//$user_info = M('user')->where(array('mobile'=>$data['phone'],"openid"=>$openid,"deleted"=>0,"hidden"=>0))->find();
			//$store_info = M('user_store')->where(array('users'=>$user_info['id']))->find();
			//验证激活码
			$where_code = array(
				"mobile" => $data['phone'],
				"store" => $data['store'],
				"hidden" => 0,
				"deleted" => 0,
				//"code" => $data['code'],
			);
			$activateCode = M('Smscode')->where($where_code)->find();
			//echo M('Smscode')->getLastSql();
			//$str = M('Smscode')->getLastSql();
			//$this->debug($str, 'debug.txt');
			//exit;
			if($activateCode['is_use'] == 1){
				$this->error("商户已绑定，请勿重复绑定！");
			}
			
			if(empty($activateCode)){
				$this->error("未找到商户信息，请确认手机号是否正确！");
			}
	
			if(time()-$activateCode['codetime'] >= 60*60){
				//重新发送验证码
				$phone = $data['phone'];
				$code = mt_rand(100000, 999999);
				$sms_code = "SMS_13190848";
				$SmsParam = "{\"code\":\"{$code}\"}";
				
				$res_obj = sendSMS($phone, $code, $sms_code,$SmsParam);//返回对象
				$res_array = object_to_array($res_obj);
				//cookie('code',$code,180);
				
				$where_code = array(
					"mobile" => $data['phone'],
					"store" => $data['store'],
					"hidden" => 0,
					"deleted" => 0,
				);
				M('Smscode')->where($where_code)->save(array("lock"=>0,'error_number'=>0,'code'=>$code,'codetime'=>time(),'tstamp'=>time(),'is_use'=>0));
				//$str = M('Smscode')->getLastSql();
				//$this->debug($str, 'debug.txt');
				$this->error("激活码已过期，已将准确的激活码发送到登记手机上，请注意查收！");
			}		
		
			if($activateCode['error_number'] >= 3){
				$this->error("激活码多次输入错误，为了保证您的数据安全，请一小时后重试！");
			}
							
			if($activateCode['code'] != $data['code']){
				//错误次数+1
				$error_number = M('Smscode')->where($where_code)->setInc('error_number');
				if($error_number<4){
					M('Smscode')->where($where_code)->save(array('users'=>$userid,"error_time"=>time(),'tstamp'=>time()));
				}
				$this->error("激活码有误，请重新输入！");
			}
			
/*			if($activateCode['lock'] == 1 && time() - $activateCode['codetime'] >= 60*60){
				M('Smscode')->where($where_code)->save(array("lock"=>0,'error_number'=>0));
			}*/
			
/*			//验证错误次数
			if($activateCode['error_number'] >= 3){
				M('Smscode')->where($where_code)->save(array('lock'=>1));
				$this->error("重试次数过多，请1小时后重试。");
			}*/
			
			/*if($data['phone'] != $activateCode['mobile'] || $data['store'] != $activateCode['store']){
				//错误次数+1
				M('Smscode')->where($where_code)->setInc('error_number');
				M('Smscode')->where($where_code)->save(array("codetime"=>time()));
				$this->error("请输入正确的绑定信息"); 
			}*/
			
			$accessList=M('user_access')->field("id")->order('id asc')->select();
			$accessStr=implode(',',array_format($accessList));
			
			$addData = array(
				"store" => $data['store'],
				"users" => $userid,	
				"lock" => 1,
				"crdate" => time(),
				'tstamp' => time(),
				'access' => $accessStr
			);
			//绑定商户的时候创建一条账户和商户的关系
			$res = M('user_store')->add($addData);
			M('Smscode')->where($where_code)->save(array("is_use"=>1,'tstamp'=>time(),'users'=>$userid));
			if($res){
				$this->success("绑定成功！");
			}else{
				$this->error("绑定失败！");
			}
		}else{
		
			$code_info =M('smscode')->where(array("users"=>$userid))->find();
			
			if($code_info["error_number"] >= 3){
				
				if(time() - $code_info["error_time"] > 60*60){
					M('smscode')->where(array("users"=>$userid))->save(array('error_number'=>0,'tstamp'=>time()));
					$this->assign("error",0);
				}else{
					//需要弹出错误层
					$this->assign("error",1);
				}
				
			}else{
				$this->assign("error",0);
			}
			
			$where = array("openid"=>$openid);
			$userinfo = M('user')->where($where)->find();
			import("Mobile.Util.JSSDK");
			//微信获取JSSDK
			$jssdk = new \JSSDK(C('WECHAT_APPID'), C('WECHAT_SECRET'));
			$signPackage = $jssdk->GetSignPackage();
			S('jsdk_ticket',$signPackage,7200);
			$jssdk = $signPackage;
				
			$this->assign('jssdk',$jssdk);
			
			/* //选择商户
			$join = "left join hq_tbl_merchant_data as m on m.seq = s.store";
			$where = array(
				"s.hidden"=>0,
				"s.deleted"=>0,
				"s.mobile"=>$_SESSION["FEUSER"]["mobile"]?$_SESSION["FEUSER"]["mobile"]:$userinfo['mobile'],
				"s.is_use"=>array("neq","1")
			);
			$store = M("smscode")->alias('s')->join($join)->where($where)->field("s.store,m.merchant_name")->select();
			$this->assign("store",$store);  */
			
			//加载页面
			$this->display();
		}
	}
	
	/**
	 * Bind List (绑定列表)
	 * @author zhaojie
	 */
	public function bindList(){
		$this->assign('title',"绑定商户");
		//微信授权获取openid
		$openid = session('openid')?session('openid'):I('openid');
		//echo $openid; die;
		if(empty($openid) || $openid == '-1'){
			$code = I('request.code');
			if($code){
				$openid_array = $this->getOpenid($code);
				$openid = $openid_array['openid'];
				$access_token = $openid_array['access_token'];
				session('openid',$openid_array['openid'],3600);
				session('access_token',$access_token,3600);
			}else{
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/Index/".ACTION_NAME);
				$scope = I('request.scope')?I('request.scope'):'snsapi_userinfo';
				$this->autho_url($url, $scope);die;
			}
		}
		session('openid',$openid,3600);
		
		//获取用户信息
		$where_u = array("openid"=>$openid,"deleted"=>0);
		$user_info = M('User')->where($where_u)->find();
		if(empty($user_info)){
			//$this->error("获取微信数据失败！");
			cookie('return_url','bindList',3600);
			$this->redirect('login');
			die;
		}
		$this->assign('userInfo',$user_info);
		//p($user_info);
		
		//根据userid去获取绑定的商户信息
		$where['deleted']=0;
		$where['lock']=1;
		$where['users']=$user_info['id'];
		$list=M('user_store as dus')
			->field("dus.id,dus.cruser_id,dus.lock,dus.users,dus.store,dus.access,kh.crm_kh_khjlID,kh.crm_kh_dpmc")
			->join(C("DB_PREFIX")."tbl_crm_kh as kh on kh.crm_kh_hykhID = dus.store")
			->where($where)
			->order("dus.id desc")
			->select();
		//p($list);
		$accessList=M('user_access')->field("id,title")->order('id asc')->select();
		//p($accessList);
		foreach ($list as $key=>$val){
			$access=trim($val['access'],',');
			$accessStr="";
			if($access){
				$accessArray=explode(',',$access);
				foreach ($accessArray as $akey=>$aval){
					foreach ($accessList as $alkey=>$alval){
						if($aval == $alval['id']){
							$accessStr.="<span>".$alval['title']."</span>";
						}
					}
				}
			}
			$list[$key]['accessStr']=$accessStr;
		}
		//p($list);
		$this->assign('list',$list);
		
		//加载HTML模板
		$this->display();
	}
	
	/**
	 * 手机验证码登陆
	 */
	public function loginMobile(){
		$openid = session('openid')?session('openid'):I('openid');
		
		if(empty($openid)){
			$code = I('request.code');
			if($code){
				$openid_array = $this->getOpenid($code);
				$openid = $openid_array['openid'];
				$access_token = $openid_array['access_token'];
				session('openid',$openid_array['openid'],3600);
				session('access_token',$access_token,3600);
			}else{
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/Index/".ACTION_NAME);
				$scope = I('request.scope')?I('request.scope'):'snsapi_userinfo';
				$this->autho_url($url, $scope);die;
			}
		}
		session('openid',$openid,3600);
		
		//根据openid查询用户是否有注册
		$user = M('User')->where(array('hidden'=>0,'deleted'=>0,'openid'=>$openid))->find();
		if($user['mobile']!=""){
			$_SESSION['FEUSER'] = array(
					"id" =>$user['id'],
					"username" => $user["username"],
					"mobile" => $user["mobile"],
					"openid" => $user["openid"],
			);
		}
		
		//如果登录调到绑定页面       //20160912改为   如果登录则提示
		if($user['id']){
			//$this->redirect("bind");die;
			$this->assign('logged_in',1);
		}else{
			$this->assign('logged_in',0);
		}
		
		import("Mobile.Util.JSSDK");
		$jssdk = S('jsdk_ticket');
		//获取JSSDK
		if(empty($jssdk)){
			$jssdk = new \JSSDK(C('WECHAT_APPID'), C('WECHAT_SECRET'));
			$signPackage = $jssdk->GetSignPackage();
			S('jsdk_ticket',$signPackage,7200);
			$jssdk = $signPackage;
		}
			
		$this->assign('jssdk',$jssdk);
		
		$this->assign('title',"登录");
		$this->assign("openid",$openid);
		$this->display();
	}
	
	//获取手机号对应的商户号
	public function getStores(){
		$phone = I("phone");
		$stores = M("smscode as s")->join(C("DB_PREFIX")."tbl_crm_kh as kh on kh.crm_kh_hykhID = s.store")->where(array("s.hidden"=>0,"s.deleted"=>0,"s.mobile"=>$phone))->field("kh.crm_kh_qymc,s.store")->select();
		//echo M("smscode as s")->getlastsql();dump($stores);exit;
		//$this->debug(M("smscode as s")->getlastsql(), code_log.txt);
		if($stores){
			$stores_ky = M("smscode as s")->join(C("DB_PREFIX")."tbl_crm_kh as kh on kh.crm_kh_hykhID = s.store")->where(array("s.hidden"=>0,"s.deleted"=>0,"s.mobile"=>$phone,"s.is_use"=>array("neq","1")))->field("kh.crm_kh_qymc as 'title',s.store as 'value'")->select();
			if(!$stores_ky){
				$this->ajaxReturn(array("status"=>2),json);
			}else{
				$this->ajaxReturn(array("status"=>1,"data"=>$stores_ky),json);
			}
		}else{
			$this->ajaxReturn(array("status"=>0),json);
		}
	}
	
	/**
	 * 会员名登陆
	 */
	public function login(){
		$openid = session('openid');
		if(empty($openid)){
			$openid = I('request.openid');
		}
		
		if(empty($openid)){
			$code = I('request.code');
			if($code){
				$openid_array = $this->getOpenid($code);
				$openid = $openid_array['openid'];
				$access_token = $openid_array['access_token'];
				session('openid',$openid_array['openid'],3600);
				session('access_token',$access_token,3600);
			}else{
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/Index/".ACTION_NAME);
				$scope = I('request.scope')?I('request.scope'):'snsapi_userinfo';
				$this->autho_url($url, $scope);die;
			}
		}
		session('openid',$openid,3600);
		
		//根据openid查询用户是否有注册
		$user = M('User')->where(array('hidden'=>0,'deleted'=>0,'openid'=>$openid))->find();
		if($user['mobile']!=""){
			$_SESSION['FEUSER'] = array(
					"id" =>$user['id'],
					"username" => $user["username"],
					"mobile" => $user["mobile"],
					"openid" => $user["openid"],
			);
		}
		
		//如果登录调到绑定页面       //20160912改为   如果登录则提示
		if($user['id']){
			//$this->redirect("bind");die;
			$this->assign('logged_in',1);
		}else{
			$this->assign('logged_in',0);
		}
		
		if(IS_POST){
			$data = I('request.');
			$password = md5(base64_encode($data['password']));
			//这里面应该username和mobile都可以登录的，但是mobile是唯一不能修改的
			$where['hidden']=0;
			$where['deleted']=0;
			$where['password']=$password;
			$where['_query'] = 'username='.$data['username'].'&mobile='.$data['username'].'&_logic=or';
			$info = M('user')->where($where)->find();
		
			if(empty($info)){
				$this->error("用户名或密码错误！");
			}else{
				if($info["openid"]){
					$this->error("该账号已被其他微信用户登录！");
				}
				//$_SESSION['FEUSER']['username'] = $data['username'];
				//登录成功就绑定openid
				M('user')->where(array('hidden'=>0,'deleted'=>0,"id"=>$info['id']))->save(array("openid"=>$openid,'tstamp'=>time()));
				
				$_SESSION['FEUSER'] = array(
					"id" => $info["id"],
					"username" => $info["username"],
					"mobile" => $info["mobile"],
					"openid" => $openid,
				);
				
				//$this->success("登陆成功",U('bind',array("openid"=>$info["openid"])));
				
				
				if(!empty(cookie('return_url'))){
					$return_url = cookie('return_url');
					cookie('return_url','');
					$this->success($info["username"]."，欢迎您回家！",U($return_url));
				}else{
					$this->success($info["username"]."，欢迎您回家！",U('bind',array("openid"=>$info["openid"])));
				}
				
			}
		}else{
			
			import("Mobile.Util.JSSDK");
			$jssdk = S('jsdk_ticket');
			//获取JSSDK
			if(empty($jssdk)){
				$jssdk = new \JSSDK(C('WECHAT_APPID'), C('WECHAT_SECRET'));
				$signPackage = $jssdk->GetSignPackage();
				S('jsdk_ticket',$signPackage,7200);
				$jssdk = $signPackage;
			}
				
			$this->assign('jssdk',$jssdk);
			
			$this->assign('title',"登录");
			$this->assign("openid",$openid);
			$this->display();
		}
	}
	
	/**
	 * 找回密码
	 */
	public function forgotpwd(){
		$this->assign('title',"忘记密码");
		if(IS_POST){
			$data = I('request.');
			
			if(!M('user')->where(array('hidden'=>0,'deleted'=>0,'mobile'=>$data['phone']))->find()){
				$this->success("该手机号未注册账号，请确认!",U('register'));	
			}
			
			$code = I('request.code');

			if(!cookie('code')){
				$this->error("输入的验证码有误，请重新输入！");
			}
			if($code != cookie('code')){
				$this->error("输入的验证码有误，请重新输入！");
			}
			
			if($data['password'] == $data['confirm_password']){
				$save_data = array('password'=>md5(base64_encode($data['password'])),"tstamp"=>time());
				$res = M('user')->where(array("mobile"=>$data['phone']))->save($save_data);
			}
			if($res){
				$this->success("重置密码成功!",U('login'));
			}else{
				$this->error("重置密码失败，请重新提交!",U('forgotpwd'));
			}
			//var_dump($_POST);die;
		}else{
			$this->display();
		}
	}
	
	public function agree(){
		if(IS_POST){
			$check_agb = $_POST['check'];
			if(empty($check_agb)){
				$this->success("注册失败");
			}
			
			/*$save_data = array("is_agb" => 1,'tstamp'=>time());
			$Obj = M('user');
			$openid = session("openid");
			$res = $Obj->where(array("openid"=>$openid))->save($save_data);
			if($res){
				$this->success("感谢您加入一皆通大家庭！",U('login',array("openid"=>$openid)));	//用户创建成功!
			}else{
				$this->success("注册失败");
			}*/
			
			$data = cookie('post_data');
			$data["is_agb"] = 1;
			$Obj = M('user');
			$data['crdate']=time();
			$data['tstamp']=time();
			$res = $Obj->add($data);
			if($res){
				//注册之后默认登录
				$_SESSION['FEUSER'] = array(
					"id" => $res,
					"username" => $data["username"],
					"mobile" => $data["mobile"],
					"openid" => $data["openid"],
				);
				$this->success("感谢您加入一皆通大家庭！",U('login',array("openid"=>$data['openid'])));
			}else{
				$this->success("注册失败");
			}
			
			
		}else{
			import("Mobile.Util.JSSDK");
			$jssdk = S('jsdk_ticket');
			//获取JSSDK
			if(empty($jssdk)){
				$jssdk = new \JSSDK(C('WECHAT_APPID'), C('WECHAT_SECRET'));
				$signPackage = $jssdk->GetSignPackage();
				S('jsdk_ticket',$signPackage,7200);
				$jssdk = $signPackage;
			}
			
			$this->assign('jssdk',$jssdk);
			
			//$config = $this->Config;
			$this->assign('title',"注册");
			$this->display();
		}
	}

	//验证手机号是否被注册
	public function checkPhone(){
		$phone = I('request.phone');
		if(preg_match("/^1[34578]{1}\d{9}$/",$phone)){
			if(M('User')->where(array('hidden'=>0,'mobile'=>$phone,'deleted'=>0))->count()){
				//$this->error("该手机号码已被注册！");
				$this->ajaxReturn(array("status"=>1),json);
			}else{
				$this->ajaxReturn(array("status"=>0),json);
			}	
		}else{
			$this->ajaxReturn(array("status"=>-1),json);
		}
	}

				
	//发送短信验证码
	public function sendCode(){
		$c = I('request.sendc');
		//$phone = "17091645504";
// 		$sms_code = "SMS_13250620"; //模板ID
		$phone = I('request.phone');
		if(empty($phone)){
			$this->error("手机号码不能为空！");
		}
		
		//注册
		/*if($c==1){
			if(M('user')->where(array('hidden'=>0,'deleted'=>0,'mobile'=>$phone))->find()){
				$this->error("该手机号码已被注册!");	
			}	
		}*/
				
		//找回密码的时候 验证手机号是否注册
		if($c==2){
			if(!M('user')->where(array('hidden'=>0,'deleted'=>0,'mobile'=>$phone))->find()){
				$this->error("该手机号未注册账号，请确认!",U('register'));	
			}	
		}
	
		$code = mt_rand(100000, 999999);
		switch ($c){
			case "1":
				//注册
				$sms_code = "SMS_13261153";
				$SmsParam = "{\"code\":\"{$code}\"}";
				break;
			case "2":
				//忘记密码
				$sms_code = "SMS_13181156";
				$SmsParam = "{\"code\":\"{$code}\"}";
				break;
			case "3":
				//发送商户激活码
				$sms_code = "SMS_13190848";
				$SmsParam = "{\"code\":\"{$code}\"}";
				break;
		}
		
		//echo $phone."|".$code."|".$sms_code."|".$SmsParam;
		//exit;
		$res_obj = sendSMS($phone, $code, $sms_code,$SmsParam);//返回对象
		$res_array = object_to_array($res_obj);
		if($res_array['result']['success'] == true){
			cookie('code',$code,180);
			$this->success("发送成功！");
		}else{
			$this->error("发送失败！");
		}
	}
	
	/**
	 * 注册
	 */
	public function register(){
		$openid = session('openid');
		if(empty($openid)){
			$openid = I('request.openid');
		}
		if(empty($openid) || $openid == '-1'){
			$code = I('request.code');
			if($code){
				$openid_array = $this->getOpenid($code);
				$openid = $openid_array['openid'];
				session('openid',$openid_array['openid'],3600);
				$access_token = $openid_array['access_token'];
				session('access_token',$access_token,3600);
			}else{
				$url = "http://" . $_SERVER['SERVER_NAME'] . "/" . U("Mobile/Index/".ACTION_NAME);
				$scope = I('request.scope')?I('request.scope'):'snsapi_base';
				$this->autho_url($url, $scope);die;
			}
		}
	
		if(session('access_token')){
			$access_token = session("access_token");
		}
		$wechat_info = $this->getWechatUserinfo($openid,$access_token); 
		
		if(IS_POST){
			$code = I('request.code');
			$phone = I('request.phone');
			
			if(!cookie('code')){
				$this->error("输入的验证码有误，请重新输入！");
			}
			if($code != cookie('code')){
				$this->error("输入的验证码有误，请重新输入！");
			}
			
			if(M('User')->where(array('hidden'=>0,'mobile'=>$phone,'deleted'=>0))->find()){
			
				$this->error("该手机号码已被注册！");
			}
		
/*			$data = array(
				"crdate" => time(),
				'merchant_name' => $_POST["merchant_name"],
				"username" => $_POST['phone'],
				"mobile" => $_POST['phone'],
				"password" => md5(base64_encode($_POST['password'])),
				"openid" => $_POST['openid'],
				"is_agb" => 0
			);*/
			
			$data = array(
				"crdate" => time(),
				"username" => $phone,
				"mobile" => $phone,
				"password" => md5(base64_encode($_POST['password'])),
				"openid" => $_POST['openid'],
				"image" => $wechat_info['headimgurl'],
				"nickname" => $wechat_info['nickname'],
			);
			cookie('post_data',$data);
			
			//$Obj = M('user');
			//$res = $Obj->add($data);
			
			//注册之后默认登录
			/*$_SESSION['FEUSER'] = array(
					"id" => $res,
					"username" => $data["username"],
					"mobile" => $data["mobile"],
					"openid" => $data["openid"],
			);*/
			
			$this->success("",U('agree'));
			//$this->redirect('agree');die;
		}else{
			
			import("Mobile.Util.JSSDK");
			$jssdk = S('jsdk_ticket');
			//获取JSSDK
			if(empty($jssdk)){
				$jssdk = new \JSSDK(C('WECHAT_APPID'), C('WECHAT_SECRET'));
				$signPackage = $jssdk->GetSignPackage();
				S('jsdk_ticket',$signPackage,7200);
				$jssdk = $signPackage;
			}
				
			$this->assign('jssdk',$jssdk);
			
			$Obj = M('user');
			$user = $Obj->where(array('hidden'=>0,'deleted'=>0,'openid'=>$openid))->find();
		
			//如果用户已经绑定过了就需要需要弹出错误层
			///if(M('User_store')->where(array('hidden'=>0,'deleted'=>0,'users'=>$user["id"]))->find()){
			if($user['is_agb'] == 1){
				$this->assign("bind",1);
			}else{
				$this->assign("bind",0);
			}
			
			
			if($user['is_agb'] == 0 && $user){
				$this->redirect("agree");die;
			}
			
			$this->assign("openid",$openid);
			$this->assign('title',"注册");
			$this->display();
		}
	}
	
	/**
	 * 验证刷新商户邀请码
	 */
		public function reInviteCode(){
			$c = 3;
			$phone = I('request.phone');
			$data = I('request.');
			if(empty($data['phone'])){
				$this->error("手机号码不能为空！");
			}
			
			$code = mt_rand(100000, 999999);
			switch ($c){
				case "1":
					//注册
					$sms_code = "SMS_13261153";
					$SmsParam = "{\"code\":\"{$code}\"}";
					break;
				case "2":
					//忘记密码
					$sms_code = "SMS_13181156";
					$SmsParam = "{\"code\":\"{$code}\"}";
					break;
				case "3":
					//发送商户激活码
					$sms_code = "SMS_13190848";
					$SmsParam = "{\"code\":\"{$code}\"}";
					break;
			}
			
			//echo $phone."|".$code."|".$sms_code."|".$SmsParam;
			//exit;
			
			$where_code = array(
				"mobile" => $data['phone'],
				"store" => $data['store'],
				"hidden" => 0,
				"deleted" => 0,
			);
			$smsCode = M('Smscode')->where($where_code)->find();
			//手机号不存在
			if(empty($smsCode)){
				$this->error("未找到商户信息，请确认手机号是否正确！");
			}
			//未绑定，已过期
			if($smsCode['is_use'] != 1 && time()-$smsCode['codetime'] >= 60*60){
				//重新发送验证码
				//$phone = $data['phone'];
				$code = mt_rand(100000, 999999);
				$sms_code = "SMS_13190848";
				$SmsParam = "{\"code\":\"{$code}\"}";
				
				$res_obj = sendSMS($phone, $code, $sms_code,$SmsParam);//返回对象
				$res_array = object_to_array($res_obj);
				cookie('code',$code,180);
				M('Smscode')->where($where_code)->save(array("lock"=>0,'error_number'=>0,'code'=>$code,'codetime'=>time(),'tstamp'=>time(),'is_use'=>0));
				$this->success("已将准确的激活码发送到登记手机上，请注意查收！");exit;
			}
			
			$msg = "激活码已于".date("H:i:s",$smsCode["codetime"])."发送到登记手机上，请注意查收!";
			$this->success($msg);
				
		/* 	$res_obj = sendSMS($phone, $code, $sms_code,$SmsParam);//返回对象
			$res_array = object_to_array($res_obj);
			if($res_array['result']['success'] == true){
				cookie('code',$code,180);
				$this->success("发送成功！");
			}else{
				$this->error("发送失败！");
			} */
	}
	 
	/*
	 * 解绑商户
	* */
	public function unBind(){
		if(!IS_AJAX || !IS_POST){
			$rd['status']=-1;
			$rd['info']="请求方式错误";
			$this->ajaxReturn($rd);
		}else{
			$id=(int)I('key');  //获取id
			if(empty($id)){
				$rd['status']=-2;
				$rd['info']="参数错误";
				$this->ajaxReturn($rd);
			}
	
			$openid = session('openid')?session('openid'):$this->openid;
			if(empty($openid)){
				$rd['status']=-3;
				$rd['info']="获取微信数据失败！";
				$this->ajaxReturn($rd);
			}
				
			//获取用户信息
			$where_u = array("openid"=>$openid,"deleted"=>0);
			$user= M('User')->field('id,super_userId')->where($where_u)->find();
			if(empty($user)){
				$rd['status']=-4;
				$rd['info']="获取数据失败！";
				$this->ajaxReturn($rd);
			}
			$userid=$user['id'];
			$suserId=$user['super_userId'];
			if(empty($suserId)){
				$rd['status']=-20;
				$rd['info']="不允许解绑操作哦！";
				$this->ajaxReturn($rd);
			}
			
			$where['deleted']=0;
			$where['id']=$id;
			$where['users']=$userid;
			$info=M('user_store')->where($where)->find();
			if(empty($info)){
				$rd['status']=-5;
				$rd['info']="获取绑定数据失败！";
				$this->ajaxReturn($rd);
			}
			
			$MsmsCode=M('Smscode');
			$MsmsCode->startTrans();
			$res=0;
			$sres=$MsmsCode
				->where(array("users"=>$info['users'],'store'=>$info['store']))
				->save(array("lock"=>0,"is_use"=>0,"users"=>'','error_number'=>0,'tstamp'=>time()));
			if($sres){
				$res=M("user_store")->where(array("id"=>$id))->save(array("lock"=>0,"deleted"=>1,'tstamp'=>time()));
			}
			if($res){
				$MsmsCode->	commit();
				$rd['status']=1;
				$rd['info']="解绑成功！";
				$this->ajaxReturn($rd);
			}else{
				$MsmsCode->rollback();
				$rd['status']=-10;
				$rd['info']="解绑失败！";
				$this->ajaxReturn($rd);
			}
		}
	}
	
	public function onAccess(){
		$pkey=(int)I('pkey');
		$title="该页面";
		if($pkey==2){
			$title="POS";
		}elseif($pkey==3){
			$title="电商平台";
		}elseif($pkey==4){
			$title="微社区";
		}
		$this->assign('title',$title);
		
		import("Mobile.Util.JSSDK");
		$jssdk = S('jsdk_ticket');
		//获取JSSDK
		if(empty($jssdk)){
			$jssdk = new \JSSDK(C('WECHAT_APPID'), C('WECHAT_SECRET'));
			$signPackage = $jssdk->GetSignPackage();
			S('jsdk_ticket',$signPackage,7200);
			$jssdk = $signPackage;
		}
		
		$this->assign('jssdk',$jssdk);
		
		$this->display();
	}
	
	/**
	 * 手机登陆验证码发送
	 * */
	public function loginSendCode(){
		if(!IS_AJAX || !IS_POST){
			$rd['status']=-1;
			$rd['info']="请求方式错误";
			$this->ajaxReturn($rd);
		}else{
			$openid = session('openid');
			if(empty($openid)){
				$rd['status']=-3;
				$rd['info']="获取微信数据失败！";
				$this->ajaxReturn($rd);
			}
			
			$phone = I('phone');
			if(empty($phone)){
				$rd['status']=-2;
				$rd['info']="手机号码不能为空！";
				$this->ajaxReturn($rd);
			}elseif(preg_match("/^1[34578]{1}\d{9}$/",$phone)){
				$userInfo=M('user')->where(array('hidden'=>0,'mobile'=>$phone,'deleted'=>0))->find();
				if(!empty($userInfo)){
					if(!empty($userInfo["openid"]) && $userInfo["openid"]!=$openid){
						$rd['status']=-12;
						$rd['info']="该账号已被其他微信用户登录!";
						$this->ajaxReturn($rd);
					}
					//更新openid
					//$sUser=M('user');
					//$sUser->startTrans();
					//$ures = $sUser->where(array('hidden'=>0,'mobile'=>$phone,'deleted'=>0))->save(array("openid"=>$openid,'tstamp'=>time()));
					//if(!$ures){
						//$sUser->rollback();
						
						//$rd['status']=-13;
						//$rd['info']="发送失败!";
						//$this->ajaxReturn($rd);
					//}
					
					$logincode = mt_rand(100000, 999999);
					$sms_code = "SMS_14555070";
					$SmsParam = "{\"code\":\"{$logincode}\"}";
					
					$res_obj = sendSMS($phone, $logincode, $sms_code,$SmsParam);//返回对象
					$res_array = object_to_array($res_obj);
					if($res_array['result']['success'] == true){
						//$sUser -> commit();
						cookie('logincode',$logincode,180);
						
						$rd['status']=1;
						$rd['ukey']=$userInfo['id'];
						$rd['info']="登陆验证码发送成功，请注意查收!";
						$this->ajaxReturn($rd);
					}else{
						//$sUser->rollback();
						
						$rd['status']=-10;
						$rd['info']="登陆验证码发送失败!";
						$this->ajaxReturn($rd);
					}
				}else{
					$rd['status']=-4;
					$rd['info']="该手机号未注册账号，请确认!";
					$this->ajaxReturn($rd);
				}
			}else{
				$rd['status']=-3;
				$rd['info']="请输入有效的手机号码！";
				$this->ajaxReturn($rd);
			}
		}
	}
	
	/**
	 * 验证码登陆
	 * */
	public function mobileLogin(){
		if(!IS_AJAX || !IS_POST){
			$rd['status']=-1;
			$rd['info']="请求方式错误";
			$this->ajaxReturn($rd);
		}else{
			$openid = session('openid');
			if(empty($openid)){
				$rd['status']=-3;
				$rd['info']="获取微信数据失败！";
				$this->ajaxReturn($rd);
			}
			$phone = I('phone');
			if(empty($phone)){
				$rd['status']=-4;
				$rd['info']="手机号码不能为空！";
				$this->ajaxReturn($rd);
			}elseif(preg_match("/^1[34578]{1}\d{9}$/",$phone)){
				$ukey=(int)I('ukey');
				if(empty($ukey) || $ukey<1){
					$rd['status']=-20;
					$rd['info']="验证码错误！";
					$this->ajaxReturn($rd);
				}
				$code = I('code');
				if(empty($code)){
					$rd['status']=-6;
					$rd['info']="验证码不能为空！";
					$this->ajaxReturn($rd);
				}
								
				if(!cookie('logincode')){
					$rd['status']=-7;
					$rd['info']="输入的验证码有误，请重新输入！";
					$this->ajaxReturn($rd);
				}
				if($code != cookie('logincode')){
					$rd['status']=-8;
					$rd['info']="输入的验证码有误，请重新输入！";
					$this->ajaxReturn($rd);
				}
				
				//判断手机号是否注册
				$user = M('User')->where(array('hidden'=>0,'mobile'=>$phone,'id'=>$ukey,'deleted'=>0))->find();
				if(!empty($user)){
					$uresRes = M('User')->where(array('hidden'=>0,'id'=>$ukey,'deleted'=>0))->save(array("openid"=>$openid,'tstamp'=>time()));
					if($uresRes){
						cookie('logincode',null);
						if(!empty(cookie('return_url'))){
							$return_url = cookie('return_url');
							cookie('return_url','');
						
							$rd['status']=1;
							$rd['info']=$user["username"]."，欢迎您回家！";
							$rd['url']=$return_url;
							$this->ajaxReturn($rd);
						}else{
							$rd['status']=2;
							$rd['info']=$user["username"]."，欢迎您回家！";
							$rd['url']=U('bind',array("openid"=>$user["openid"]));
							$this->ajaxReturn($rd);
						}
					}else{
						$rd['status']=-22;
						$rd['info']="登陆失败!";
						$this->ajaxReturn($rd);
					}
				}else{
					$rd['status']=-4;
					$rd['info']="该手机号未注册账号，请确认!";
					$this->ajaxReturn($rd);
				}
			}else{
				$rd['status']=-5;
				$rd['info']="请输入有效的手机号码！";
				$this->ajaxReturn($rd);
			}
		}
	}
}

?>