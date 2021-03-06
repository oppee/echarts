<?php
/**
 * 申请
 * @author zhaojie
 */
namespace Mobile\Controller;
use Mobile\Controller\BaseController;

class ApplyController extends BaseController{
	private $openid=""; //'obGCpjiUK6wR2AARzHZ7eHZMNcaU';
		
	/**
	 * 申请列表
	 */
	public function index(){
		$this->assign('title',"申请列表");
		//获取微信授权openid
		$openid = session('openid')?session('openid'):$this->openid;
// 		if(empty($openid)){
// 			$this->error("获取微信数据失败！");
// 		}
		
		if(empty($openid)){
			$openid = I('request.openid');
		}
		if(empty($openid) || $openid == '-1'){
			$code = I('request.code');
			if($code){
				$openid_array = $this->getOpenid($code);
				$openid = $openid_array['openid'];
				$access_token = $openid_array['access_token'];
				session('openid',$openid_array['openid'],3600);
				session('access_token',$access_token,3600);
			}else{
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/Apply/".ACTION_NAME);
				$scope = I('request.scope')?I('request.scope'):'snsapi_userinfo';
				$this->autho_url($url, $scope);die;
			}
		}
		session('openid',$openid,3600);
		
		//获取用户信息
		$where_u = array("openid"=>$openid,"deleted"=>0);
		$userid= M('User')->where($where_u)->getField('id');
		//echo $userid . M()->getLastSql(); die;
		if(empty($userid)){
			//$this->error("未获取到用户数据");
			//未登录，跳转至登陆页
			cookie('return_url','Apply/index',3600);
			$this->redirect('Index/login');
			die;
		}
		
		//获取该账户下的申请数据
		$field="id,service_item,company_name,contact_name,contact_status,remind_time,application_time";
		$where['deleted']=0;
		$where['cruser_id']=$userid;
		$list=M('user_fwxm')->field($field)->where($where)->order('id desc')->select();
		//echo $userid . M()->getLastSql(); die;
		$this->assign('list',$list);
		
		//加载HTML模板
		$this->display();
	}
	
	public function service(){
		$this->assign('title',"申请服务");
		
		$openid = session('openid')?session('openid'):$this->openid;
// 		if(empty($openid)){
// 			$this->error("获取微信数据失败！");
// 		}
		
		if(empty($openid)){
			$openid = I('request.openid');
		}
		if(empty($openid) || $openid == '-1'){
			$code = I('request.code');
			if($code){
				$openid_array = $this->getOpenid($code);
				$openid = $openid_array['openid'];
				$access_token = $openid_array['access_token'];
				session('openid',$openid_array['openid'],3600);
				session('access_token',$access_token,3600);
			}else{
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/Apply/".ACTION_NAME);
				$scope = I('request.scope')?I('request.scope'):'snsapi_userinfo';
				$this->autho_url($url, $scope);die;
			}
		}
		session('openid',$openid,3600);
		
		//获取用户信息
		$where_u = array("openid"=>$openid,"deleted"=>0);
		$userid= M('User')->where($where_u)->getField('id');
		//echo $userid . M()->getLastSql(); die;
		if(empty($userid)){
// 			$this->error("未获取到用户数据");
			//未登录，跳转至登陆页
			cookie('return_url','Apply/service',3600);
			$this->redirect('Index/login');
			die;
		}
		
		//获取该账户下的申请数据
		$field="crm_fwxm_jlID,crm_fwxm_mc";
		$where['crm_fwxm_isdelete']=0;
		$fwxmList=M('tbl_crm_fwxm')->field($field)->where($where)->select();
		//echo $userid . M()->getLastSql(); die;
		$this->assign('fwxmList',$fwxmList);
		
		//获取微信授权openid		
		/* $openid = session('openid');
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
		} */
		//加载HTML模板
		$this->display();
	}
	
	/*
	 * 更新提醒
	* */
	public function updateRemind(){
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
			$userid= M('User')->where($where_u)->getField('id');
			if(empty($userid)){
				$rd['status']=-4;
				$rd['info']="获取数据失败！";
				$this->ajaxReturn($rd);
			}
			
			$where['deleted']=0;
			$where['id']=$id;
			$where['cruser_id']=$userid;
			$info=M('user_fwxm')->where($where)->find();
			if(empty($info)){
				$rd['status']=-5;
				$rd['info']="获取服务数据失败！";
				$this->ajaxReturn($rd);
			}
			
			$post_data['crm_fwxm_ids']=$info['crm_fwxm_ids'];
			//$post_data['service_item']=$info['service_item'];
			$post_data['company_name']=$info['company_name'];
			$post_data['company_address']=$info['company_address'];
			$post_data['contact_name']=$info['contact_name'];
			$post_data['contact_tel']=$info['contact_tel'];
			$post_data['user_fwxm_id']=$info['id'];
			$post_data['contact_time']=$info['contact_time'];
			
			//$post_string = "service_item=".$info['service_item']."&company_name=".$code."&password=".$pwd;
			$url = C('UPDATEUSERFWXM');
			if(empty($url)){
				$rd['status']=-6;
				$rd['info']="获取Api URL 失败！";
				$this->ajaxReturn($rd);
			}
	
			$responseData=https_request($url,$post_data);
			\Think\Log::write('服务提醒返回日志：'.$responseData,'INFO');
			$data = json_decode($responseData,true);
			//$res=json_decode('{"pk":"0ADC3BD23629C277D0B4EA715A89D012","username":"18616861602"}',true);
			if(!empty($data['code'])){
				$rd['status']=-10;
				$rd['info']=$data['message'];
				$this->ajaxReturn($rd);
			}else{
				$rd['status']=1;
				$rd['info']="提醒成功！";
				$this->ajaxReturn($rd);
			}
		}
	}
	
	/*
	 * 服务申请
	* */
	public function addService(){
		if(!IS_AJAX || !IS_POST){
			$rd['status']=-1;
			$rd['info']="请求方式错误";
			$this->ajaxReturn($rd);
		}else{
			//服务项目
			$crm_fwxm_ids= trim(I('servicekey'),',');
			if(empty($crm_fwxm_ids)){
				$rd['status']=-20;
				$rd['info']="请选择服务项目";
				$this->ajaxReturn($rd);
			}
			$service_item = trim(I('service'),',');
			if(empty($service_item)){
				$rd['status']=-2;
				$rd['info']="请选择服务项目";
				$this->ajaxReturn($rd);
			}
			//公司名称
			$company_name = I('name');
			if(empty($company_name)){
				$rd['status']=-3;
				$rd['info']="请输入公司名称";
				$this->ajaxReturn($rd);
			}
			//公司地址
			$company_address = I('address');
			if(empty($company_address)){
				$rd['status']=-4;
				$rd['info']="请输入公司地址";
				$this->ajaxReturn($rd);
			}
			//联系人
			$contact_name = I('contact');
			if(empty($contact_name)){
				$rd['status']=-5;
				$rd['info']="请输入联系人";
				$this->ajaxReturn($rd);
			}
			//联系电话
			$contact_tel = I('tel');
			if(empty($contact_tel)){
				$rd['status']=-6;
				$rd['info']="请输入联系电话";
				$this->ajaxReturn($rd);
			}elseif(strlen($contact_tel)!=11){
				$rd['status']=-7; //$contact_tel;
				$rd['info']="请输入有效的手机号码";  //.strlen($contact_tel);
				$this->ajaxReturn($rd);
			}			
			//方便联系时间
			$contact_time = trim(I('time'),',');
			if(empty($contact_time)){
				$rd['status']=-8;
				$rd['info']="请选择方便的联系时间";
				$this->ajaxReturn($rd);
			}
			
			$openid = session('openid')?session('openid'):$this->openid;
			if(empty($openid)){
				$rd['status']=-9;
				$rd['info']="获取微信数据失败！";
				$this->ajaxReturn($rd);
			}
			
			//获取用户信息
			$where_u = array("openid"=>$openid,"deleted"=>0);
			$userid= M('User')->where($where_u)->getField('id');
			if(empty($userid)){
				$rd['status']=-10;
				$rd['info']="获取数据失败！";
				$this->ajaxReturn($rd);
			}
			
			$data['crm_fwxm_ids']=$crm_fwxm_ids;
			$data['service_item']=$service_item;
			$data['contact_time']=$contact_time;
			$data['company_name']=$company_name;
			$data['company_address']=$company_address;
			$data['contact_name']=$contact_name;
			$data['contact_tel']=$contact_tel;
			$data['cruser_id']=$userid;
			$data['application_time']=date('Y-m-d H:i:s');
			$data['crdate']=date('Y-m-d H:i:s');
			$data['tstamp']=date('Y-m-d H:i:s');
			
			
			$serverid=M('user_fwxm')->add($data);
			if($serverid<1){
				$rd['status']=-11;
				$rd['info']="保存数据失败！";
				$this->ajaxReturn($rd);
			}else{
				$user_fwxm_id = $serverid; //用户申请表主键
				
				$post_data['crm_fwxm_ids']=$crm_fwxm_ids;
				//$post_data['service_item']=$service_item;
				$post_data['company_name']=$company_name;
				$post_data['company_address']=$company_address;
				$post_data['contact_name']=$contact_name;
				$post_data['contact_tel']=$contact_tel;
				$post_data['user_fwxm_id']=$user_fwxm_id;
				$post_data['contact_time']=$contact_time;
				
				//$post_string = "service_item=".$info['service_item']."&company_name=".$code."&password=".$pwd;
				$url = C('ADDSERVICE');
				if(empty($url)){
					$rd['status']=-12;
					$rd['info']="获取Api URL 失败！";
					$this->ajaxReturn($rd);
				}
				
				$responseData=https_request($url,$post_data);
				\Think\Log::write('添加服务返回日志：'.$responseData,'INFO');
				$datar = json_decode($responseData,true);
				//$res=json_decode('{"pk":"0ADC3BD23629C277D0B4EA715A89D012","username":"18616861602"}',true);
				if(!empty($datar['code'])){
					M('user_fwxm')->where('id='.(int)$serverid)->delete(); 
					$rd['status']=-20;
					$rd['info']=$datar['message'];
					$this->ajaxReturn($rd);
				}else{
					$rd['status']=1;
					$rd['info']="申请成功！";
					$this->ajaxReturn($rd);
				}
			}
		}
	}
}