<?php
/**
 * 开户
 * @author zhaojie
 *
 */
namespace Mobile\Controller;
use Mobile\Controller\BaseController;

class AccountController extends BaseController{
	/**
	 * 开户列表
	 */
	public function index(){
		$this->assign('title',"下属账户列表");
		
		//授权获得用户信息
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
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/Account/".ACTION_NAME);
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
			//未登录，跳转至登陆页
			cookie('return_url','Account/index',3600);
			$this->redirect('Index/login');
			die;
		}
		
		//获取账户下属账号
		$where['deleted']=0;
		$where['super_userId']=$user_info['id'];
		$useridList=M('user')->field('id')->where($where)->select();
		
		$useridList=array_format($useridList);
		//p($useridList);
		if(!empty($useridList)){
			$useridStr=implode(',',$useridList);
			//echo $useridStr; die;
			
			$cwhere['dus.deleted']=0;
			$cwhere['dus.lock']=1;
			$cwhere['dus.users']=array('in',$useridStr);
			
			$cwhere['u.deleted']=0;
			
			$cwhere['kh.crm_kh_isdelete']=0;
			
			$list=M('user_store as dus')
			->field("u.nickname,u.mobile,dus.id,dus.cruser_id,dus.lock,dus.users,dus.store,dus.access,kh.crm_kh_khjlID,kh.crm_kh_dpmc")
			->join(C("DB_PREFIX")."user as u on dus.users = u.id")
			->join(C("DB_PREFIX")."tbl_crm_kh as kh on dus.store = kh.crm_kh_hykhID")
			->where($cwhere)
			->order("dus.id desc")
			->select();
			
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
		}
		
		//加载HTML模板
		$this->display();
	}
	
	/**
	 * 创建新账户
	 */
	public function createAccount(){
		$this->assign('title',"开通下属账户");
		
		//授权获得用户信息
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
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/Account/".ACTION_NAME);
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
			//未登录，跳转至登陆页
			cookie('return_url','Account/createAccount',3600);
			$this->redirect('Index/login');
			die;
		}
		
		//获取用户所包含商户信息
		$where['deleted']=0;
		$where['lock']=1;
		$where['users']=$user_info['id'];
		$list=M('user_store as dus')
			->field("dus.id,dus.cruser_id,dus.lock,dus.users,dus.store,dus.access,kh.crm_kh_khjlID,kh.crm_kh_dpmc")
			->join(C("DB_PREFIX")."tbl_crm_kh as kh on kh.crm_kh_hykhID = dus.store")
			->where($where)
			->order("dus.id desc")
			->select();
		if(empty($list)){
			$this->error("未获取到商户绑定数据",'/Index/bind',3);
		}
		$this->assign('list',$list);
		
		//获取权限
		$accessList=M('user_access')->field("id,title")->where('id!=1')->order('id asc')->select();
		$this->assign('accessList',$accessList);
		
		//加载HTML模板
		$this->display();
	}
	
	/*
	 * 添加下属账户
	* */
	public function addAccount(){
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
	
			//获取用户信息
			$where_u = array("openid"=>$openid,"deleted"=>0);
			$userid= M('User')->where($where_u)->getField('id');
			if(empty($userid)){
				$rd['status']=-4;
				$rd['info']="获取数据失败！";
				$this->ajaxReturn($rd);
			}
			
			$store = I('store');
			if(empty($store)){
				$rd['status']=-5;
				$rd['info']="请输入商户！";
				$this->ajaxReturn($rd);
			}else{
				//判断商户是否存在
				$cwhere['users']=$userid;
				$cwhere['store']=$store;
				$cwhere['deleted']=0;
				$cwhere['lock']=1;
				$check_kh=M('user_store')->where($cwhere)->count();
				if(empty($check_kh)){
					$rd['status']=-6;
					$rd['info']="选择的商户错误！";
					$this->ajaxReturn($rd);
				}
			}
			
			$phone = I('phone');
			if(!preg_match("/^1[34578]{1}\d{9}$/",$phone)){
				$rd['status']=-7;
				$rd['info']="请输入有效的手机号码！";
				$this->ajaxReturn($rd);
			}else{
				//判断手机号是否存在
				$uwhere['mobile']=$phone;
				$uwhere['deleted']=0;
				$u_check=M('user')->where($uwhere)->count();
				if($u_check>0){
					$rd['status']=-20;
					$rd['info']="该手机号码已被注册！";
					$this->ajaxReturn($rd);
				}
			}
			$accessStr = trim(I('accessStr'),',');
			if(empty($accessStr)){
				$rd['status']=-8;
				$rd['info']="请选择要开通的权限！";
				$this->ajaxReturn($rd);
			}
	
			$code = mt_rand(100000, 999999);
			
			$password = md5(base64_encode($code));
			
			$data['crdate']=time();
			$data['cruser_id']=$userid;
			$data['deleted']=0;
			$data['username']=$phone;
			$data['password']=$password;
			$data['mobile']=$phone;
			$data['super_userId']=$userid;
				
			$user=M('User');
			$user->	startTrans();
			$res=$user->add($data);
			if($res){
				//添加绑定
				$s_data['crdate']=time();
				$s_data['cruser_id']=$userid;
				$s_data['lock']=1;
				$s_data['users']=$res;
				$s_data['store']=$store;
				$s_data['access']=$accessStr;
				
				$sres=M('user_store')->add($s_data);
				if($sres){
					$sms_code = "SMS_14751738";
					$SmsParam = "{\"username\":\"{$phone}\",\"password\":\"{$code}\"}";
					
					$res_obj = sendSMS($phone, $code, $sms_code,$SmsParam);//返回对象
					\Think\Log::write('添加下属账户短信返回：'.$res_obj,'WARN');
					$res_array = object_to_array($res_obj);
					if($res_array['result']['success'] == true){
						$user->	commit();
						
						$rd['status']=1;
						$rd['url']=U('index');
						$rd['info']="添加成功！";
						$this->ajaxReturn($rd);
					}else{
						//$this->error("发送失败！");
						$user->	rollback();
							
						$rd['status']=-12;
						$rd['info']="添加下属账户失败！";
						$this->ajaxReturn($rd);
					}
				}else{
					$user->	rollback();
					
					$rd['status']=-11;
					$rd['info']="添加下属账户失败！";
					$this->ajaxReturn($rd);
				}
			}else{
				$user->	rollback();
				
				$rd['status']=-10;
				$rd['info']="添加失败！";
				$this->ajaxReturn($rd);
			}
		}
	}
	
	/*
	 * 解绑下属账户
	* */
	public function unAccountBind(){
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
			
			$uid=(int)I('ukey');  //要解绑的下属账户id
			if(empty($uid)){
				$rd['status']=-20;
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
			$userid= M('User')->where($where_u)->getField('id');
			if(empty($userid)){
				$rd['status']=-4;
				$rd['info']="获取数据失败！";
				$this->ajaxReturn($rd);
			}
			
			$uwhere_u = array("id"=>$uid,"super_userId"=>$userid,"deleted"=>0);
			$ucount= M('User')->where($uwhere_u)->count();
			if($ucount<1){
				$rd['status']=-21;
				$rd['info']="下属账户数据获取失败！";
				$this->ajaxReturn($rd);
			}
				
			$where['deleted']=0;
			$where['id']=$id;
			$where['users']=$uid;
			$info=M('user_store')->where($where)->find();
			if(empty($info)){
				$rd['status']=-5;
				$rd['info']="获取绑定数据失败！";
				$this->ajaxReturn($rd);
			}
			
			$user=M('user');
			$user->startTrans();
			$res=0;
			$sres=$user
				->where(array("id"=>$info['users'],'deleted'=>0))
				->save(array("deleted"=>1,'tstamp'=>time()));
			if($sres){
				$res=M("user_store")->where(array("id"=>$id))->save(array("lock"=>0,"deleted"=>1,'tstamp'=>time()));
			}
			if($res){
				$user->	commit();
				
				$rd['status']=1;
				$rd['info']="解绑成功！";
				$this->ajaxReturn($rd);
			}else{
				$user->rollback();
				
				$rd['status']=-10;
				$rd['info']="解绑失败！";
				$this->ajaxReturn($rd);
			}
		}
	}
}