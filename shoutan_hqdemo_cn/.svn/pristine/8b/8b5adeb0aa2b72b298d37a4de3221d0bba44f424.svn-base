<?php
/**
 * 用户管理
 * @author zhaojie
 */
namespace Mobile\Controller;
use Mobile\Controller\BaseController;

class UserController extends BaseController{
	public $image = array('jpg','png','jpeg','gif');
	
	/**
	 * 修改用户资料
	 */
	public function userinfo(){
		$this->assign('title',"修改个人信息");
	
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
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/User/".ACTION_NAME);
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
			cookie('return_url','User/userinfo',3600);
			$this->redirect('Index/login');
			die;
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
		
		$this->assign('info',$user_info);
		
		//加载HTML模板
		$this->display();
	}
	
	public function editPassword(){
		$this->assign('title',"修改密码");
		
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
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/User/".ACTION_NAME);
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
			cookie('return_url','User/userinfo',3600);
			$this->redirect('Index/login');
			die;
		}
		
		//加载HTML模板
		$this->display();
	}
	
	/**
	 * 头像上传
	 * */
	public function headImgUpload(){
		if (! empty ($_FILES)){
			$extension = end(explode('.', $_FILES['file']['name']));
			if(!in_array(strtolower($extension), $this->image)){
				$rd['status']=-2;
				$rd['info']="文件无效";
				$this->ajaxReturn($rd);
			}
			import("Org.Net.UploadFile");
			$savePath = $this->Config['path_upload'] .'HeadImg/'. date('Ym') . '/';
			$upload_obj = new \UploadFile();
			$res = $upload_obj->uploadOne($_FILES ['file'], $savePath);
	
			if(!$res){
				$rd['status']=-3;
				$rd['info']="文件上传失败！";
				$this->ajaxReturn($rd);
			}else{
				$data['title'] = $res[0]['name'];
				$data['path'] = $res[0]['savename'];
				
				$rd['status']=1;
				$rd['info']='http://'.$_SERVER['SERVER_NAME'].ltrim($savePath,'.').$res[0]['savename'];
				$this->ajaxReturn($rd, 'json');die;
			}
		}else{
			$rd['status']=-1;
			$rd['info']="请选择头像";
			$this->ajaxReturn($rd);
		}
	}
	
	/*
	 * 更新信息
	* */
	public function updateInfo(){
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
				
			$img=I('img');
			$uname=I('uname');
			$regex = '/^[\A-Za-z0-9\_]{6,16}$/';
			if(!preg_match($regex, $uname, $matches)){
				//用户名有误，请输入6-16位数字、字母或常用符号！
				$rd['status']=-6;
				$rd['info']="用户名有误，请输入6-16位数字、字母或下划线！";
				$this->ajaxReturn($rd);
			}
			$name=I('name');
			$sex=I('sex');
			
			//判断用户名是否已经存在
			$where_c['username']=$uname;
			$where_c['openid']=array('neq',$openid);
			$where_c['deleted']=0;
			$count= M('User')->where($where_c)->count();
			if($count>0){
				$rd['status']=-5;
				$rd['info']="用户名已经存在，请重新输入用户名！";
				$this->ajaxReturn($rd);
			}
			
			$data['image']=$img;
			$data['username']=$uname;
			$data['nickname']=$name;
			$data['gender']=$sex;
			$data['tstamp']=time();
			
			$res=M('User')->where($where_u)->save($data);
			if($res){
				$rd['status']=1;
				$rd['info']="更新成功！";
				$this->ajaxReturn($rd);
			}else{
				$rd['status']=-10;
				$rd['info']="更新失败！";
				$this->ajaxReturn($rd);
			}
		}
	}
	
	/*
	 * 修改密码
	* */
	public function passWordSave(){
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
			$userInfo= M('User')->field('id,password')->where($where_u)->find();
			if(empty($userInfo)){
				$rd['status']=-4;
				$rd['info']="获取数据失败！";
				$this->ajaxReturn($rd);
			}
	
			$opwd=I('opwd');
			if(empty($opwd)){
				$rd['status']=-5;
				$rd['info']="请输入密码！";
				$this->ajaxReturn($rd);
			}
			$npwd=I('npwd');  //新密码
			if(empty($npwd)){
				$rd['status']=-6;
				$rd['info']="请输入新密码！";
				$this->ajaxReturn($rd);
			}
			$regex = '/^[\@A-Za-z0-9\!\+\-\#\$\%\^\&\*\.\~]{6,16}$/';
			if(!preg_match($regex, $npwd, $matches)){
				//密码有误，请输入6-16位数字、字母或常用符号！
				$rd['status']=-7;
				$rd['info']="密码有误，请输入6-16位数字、字母或常用符号！";
				$this->ajaxReturn($rd);
			}
			if($opwd==$npwd){
				$rd['status']=-16;
				$rd['info']="新密码不能与旧密码一致哦！";
				$this->ajaxReturn($rd);
			}
			$rpwd=I('rpwd');  //确认密码
			if(empty($rpwd)){
				$rd['status']=-8;
				$rd['info']="请输入确认密码！";
				$this->ajaxReturn($rd);
			}elseif ($npwd!==$rpwd){
				$rd['status']=-9;
				$rd['info']="新密码与确认密码不一致！";
				$this->ajaxReturn($rd);
			}
			
			$md5Pwd=md5(base64_encode($opwd));
			if($userInfo['password'] != $md5Pwd){
				$rd['status']=-10;
				$rd['info']="原密码错误！";
				$this->ajaxReturn($rd);
			}
			$md5NPwd=md5(base64_encode($npwd));
			
			$data['password']=$md5NPwd;
			$data['tstamp']=time();
				
			$res=M('User')->where($where_u)->save($data);
			if($res){
				$rd['status']=1;
				$rd['info']="修改成功！";
				$this->ajaxReturn($rd);
			}else{
				$rd['status']=-11;
				$rd['info']="修改失败！";
				$this->ajaxReturn($rd);
			}
		}
	}
}