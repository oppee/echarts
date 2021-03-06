<?php
namespace Mobile\Controller;
use Mobile\Controller\BaseController;

class DasboardController extends BaseController{	
	/**
	 * 我的账本
	 */	
	public function index(){
		$this->assign('title',"我的账本");
		$this->assign('tab',I("tab"));
		
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
				$url = "http://" . $_SERVER['SERVER_NAME'] . "" . U("Mobile/Dasboard/".ACTION_NAME);
				$scope = I('request.scope')?I('request.scope'):'snsapi_userinfo';
				$this->autho_url($url, $scope);die;
			}
		}
		session('openid',$openid,3600);
		if(session('access_token')){
			$access_token = session("access_token");
		}
		$wechat_info = $this->getWechatUserinfo($openid,$access_token);
		$this->assign('wechat_info',$wechat_info);
		
		//获取用户信息
		$where_u = array("openid"=>$openid,"deleted"=>0);
		$user_info = M('User')->where($where_u)->find();
		if(empty($user_info)){
			//$this->error("获取微信数据失败！");
			//未登录，跳转至登陆页
			cookie('return_url','Dasboard/index',3600);
			$this->redirect('Index/login');
			die;		
		}
		
		//判断用户是否有进入POS页面的权限
		if($this->checkUserAccess($user_info['id'],2)===false){
			$this->redirect('Index/onAccess', array('pkey' => 2));
			die;
		}
		//p($user_info);
		//if(!$user_info['mobile']){$this->redirect("index/login");}	//如果没有注册账户，那就跳转到登录页面
		
		//搜索条件
		$searchData=$_POST['searchData'];
		$searchArray=json_decode($searchData,true);
		
		$types = '';
		$status = '';
		$store = '';
		$device_no = '';
		$begintime = date('Y-m',strtotime('-3 month')) . "-01";
		$endtime = date('Y-m-d');
		$keyword = '';
		
		if(empty($searchArray) || empty($searchArray['status'])){
			//交易状态 TS
			$transaction_status = M('mst_dict')->where(array('del_flag' => 0, 'id' => 'TS', 'code' => array('neq','WW')))->field('`code` as "0",`desc` as "1"')->select();
			foreach ($transaction_status as $k=>$v){
				$status .= $v['0'].',';
			}
			//echo $status; die;
			$status = substr($status,0,strlen($status)-1);
			
			$searchArray['status']=$status;
		}
		
		if(empty($searchArray) || empty($searchArray['types'])){
			//交易类型 TT  支付方式
			$transaction_type = M('mst_dict')->where(array('del_flag' => 0, 'id' => 'TT'))->field('`code` as "0",`desc` as "1"')->select();
			foreach ($transaction_type as $k=>$v){
				$types .= $v['0'].',';
			}
			$types = substr($types,0,strlen($types)-1);
			
			$searchArray['types']=$types;
		}
		
		if(empty($searchArray) || empty($searchArray['store'])){
			//企业名称
			$where_us=array("us.hidden"=>0,"us.deleted"=>0,"us.lock"=>1,"us.users"=>$user_info['id']);
			$where_us['_string'] = 'LOCATE(2,access)>0';
			$storeList = M('user_store as us')
			->join(C("DB_PREFIX")."tbl_crm_kh as kh on kh.crm_kh_hykhID = us.store")
			->where($where_us)->field("us.store as '0',kh.crm_kh_dpmc as '1'")
			->select();
			foreach ($storeList as $k=>$v){
				$store .= $v['0'].',';
			}
			$store = substr($store,0,strlen($store)-1);
			
			$searchArray['store']=$store;
		}
		
		if(empty($searchArray) || empty($searchArray['device'])){
			//机具
			$where_us=array("us.hidden"=>0,"us.deleted"=>0,"us.lock"=>1,'us.users'=>$user_info['id']);
			$where_us['_string'] = 'LOCATE(2,access)>0';
			$deviceList  = M('user_store as us')
			->join(C("DB_PREFIX")."tbl_merchant_data as m on us.store = m.merchant_no")
			->where($where_us)
			->field("m.device_no,us.store")
			->select();
			foreach ($deviceList as $k=>$v){
				$temp = explode("|", $v["device_no"]);
				foreach ($temp as $key=>$value){
					$device_no .= $value.',';
				}
			}
			$device_no = substr($device_no,0,strlen($device_no)-1);
			
			$searchArray['device']=$device_no;
		}
		
		if(empty($searchArray) || (empty($searchArray['begindate']) && empty($searchArray['enddate']))){
			//开始时间 - 结束时间
			$searchArray['begindate']=$begintime;
			$searchArray['enddate']=$endtime;
		}
		
		$searchData=json_encode($searchArray);
		
		$this->assign('searchData',$searchData);
		
// 		$_SESSION["data_types"] = explode(",", $types);
// 		$_SESSION["data_status"] = explode(",", $status);
// 		$_SESSION["data_store"] = explode(",", $store);
// 		$_SESSION["data_device_no"] = explode(",", $device_no);
		
		//获取用户所包含商户信息
		$where_store = array("users"=>$user_info['id'],"lock"=>1,"deleted"=>0);
		$where_store['_string'] = 'LOCATE(2,access)>0';
		if($searchArray['store']){  //商户过滤
			$map_store = array("store"=> array('in',$searchArray['store']));
		}
		$map_store['_complex'] = $where_store;
		//p($map_store);
		$user_store_list = M('user_store')->field('store')->where($map_store)->select();
		if(empty($user_store_list)){
			$this->error("未获取到商户绑定数据",'/Index/bind',3);
		}
		
		/*****概要统计 begin******************************/

		//交易记录统计
		$count_price = 0; //总收入
		
		//昨日统计
		$yesterday_count = 0; //日统计
		//昨天时间
		$lastday = date("Y-m-d",strtotime("-1 day"));
		
		//七日统计
		$week_count = 0; //周统计
		$weekday = date("Y-m-d",strtotime("-1 week"));

		//月统计
		$mothor_count = 0; //月统计
		$mothor = date("Y-m" . "-01");
		
		//总统计
		$count_data=date('Y-m',strtotime('-3 month')) . "-01 00:00:00";
		
		$yesterday_where = array(
			"UNIX_TIMESTAMP(transaction_datetime)" => array(
				array('egt',strtotime($lastday . " 00:00:00")),
				array('elt',strtotime($lastday . " 23:59:59")),
			),
			"transaction_status" => "00",
		);
		$week_count_where = array(
			"UNIX_TIMESTAMP(transaction_datetime)" => array(
					array('egt',strtotime($weekday . " 00:00:00")),
					array('elt',strtotime(date('Y-m-d') . " 23:59:59")),
			),
			"transaction_status" => "00",
		);
		$month_count_where = array(
			"UNIX_TIMESTAMP(transaction_datetime)" => array(
				array('egt',strtotime($mothor . " 00:00:00")),
				array('elt',strtotime(date('Y-m-d') . " 23:59:59")),
			),
			"transaction_status" => "00",
		);
		$count_where = array(
			"UNIX_TIMESTAMP(transaction_datetime)" => array(
					array('egt',strtotime($count_data)),
					array('elt',strtotime(date('Y-m-d') . " 23:59:59")),
			),
			"transaction_status" => "00",
		);
		
		$yesterday_where['del_flag']=0;
		$week_count_where['del_flag']=0;
		$month_count_where['del_flag']=0;
		$count_where['del_flag']=0;
		if($_POST['store']){
			$yesterday_where['merchant_no'] = array('in',$searchArray['store']);
			$week_count_where['merchant_no'] = array('in',$searchArray['store']);
			$month_count_where['merchant_no'] = array('in',$searchArray['store']);
			$count_where['merchant_no'] = array('in',$searchArray['store']);
		}else{
			$yesterday_where['merchant_no'] = array('in',array_format($user_store_list));
			$week_count_where['merchant_no'] = array('in',array_format($user_store_list));
			$month_count_where['merchant_no'] = array('in',array_format($user_store_list));
			$count_where['merchant_no'] = array('in',array_format($user_store_list));
		}
		if($_POST['device_no']){
			$yesterday_where['device_no'] = array('in',$searchArray['device_no']);
			$week_count_where['device_no'] = array('in',$searchArray['device_no']);
			$month_count_where['device_no'] = array('in',$searchArray['device_no']);
			$count_where['device_no'] = array('in',$searchArray['device_no']);
		}
		//昨日
		$yesterday_count = M('tbl_transaction_data')->where($yesterday_where)->sum('transaction_amount - transaction_commission');
		//echo "昨日统计".M('tbl_transaction_data')->getlastsql()."<br /><br />";
		//七日
		$week_count = M('tbl_transaction_data')->where($week_count_where)->sum('transaction_amount - transaction_commission');
		//echo "七日统计".M('tbl_transaction_data')->getlastsql()."<br /><br />";
		//月统计
		$mothor_count = M('tbl_transaction_data')->where($month_count_where)->sum('transaction_amount - transaction_commission');
		//echo "月统计".M('tbl_transaction_data')->getlastsql()."<br /><br />";
		//总统计
		$count_price =  M('tbl_transaction_data')->where($count_where)->sum('transaction_amount - transaction_commission');
		//echo "<br /><br />总统计".M('tbl_transaction_data')->getlastsql();
		
		//统计报表月数据
		//$month_json = $this->mothor($salse_data);

		//统计报表周数据
		//$week_json = $this->weekCount($salse_data);
		
		//传值前台
		//$this->assign("month_json",$month_json);
		
		$this->assign("yesterday_count",$yesterday_count);  //昨日收入
		$this->assign("week_count",$week_count);  //7日收入
		$this->assign("mothor_count",$mothor_count);  //月收入
		$this->assign("count_price",$count_price);  //总余额
		$this->assign("userinfo",$user_info);
		
		/*****概要统计 end******************************/
		
		//当天的交易明细  需要分页
		$json_data=json_decode($searchData,true);
		$today_data = $this->today($json_data['device'],$json_data['store'],$json_data['types'],$json_data['status'],$json_data['begindate'],$json_data['enddate'],$json_data['keyword']);
		$this->assign("today_data",$today_data['data']);
		$this->assign("noMore",$today_data['noMore']);
		//dump($today_data);exit;
		
		$this->assign('lastTime',$searchArray['enddate']);
		$this->assign("m",intval(date('m')));
		$this->display();
	}
	
	/**
	 * 当天的交易明细 by xizi  2016/8/29 
	 */
	public function today($device_no,$store,$types,$status,$begintime,$endtime,$keyword){
		if($keyword){
			$Where['transaction_amount'] = $keyword;
		}
		if($types){
			$Where['transaction_type'] = array('in',$types);
		}
		if($status){
			$Where['transaction_status'] = array('in',$status);
		}
		if($store){
			$Where['merchant_no'] = array('in',$store);
		}
		if($device_no){
			$Where['device_no'] = array('in',$device_no);
		}
		if(!$begintime){
			$year = date("Y");
			$month = date("m")-3;
			if($month < 0){
				$month = 12 + $month;
				$year -= 1;
			}
			$month = strlen($month)==1?'0'.$month:$month;
			$begintime = $year."-".$month."-01";
		}
		
		if($begintime && $endtime){
			$Where['UNIX_TIMESTAMP(transaction_datetime)'] = array(array('egt',strtotime($begintime)),array('elt',strtotime($endtime)));
		}else if($begintime){
			$Where['UNIX_TIMESTAMP(transaction_datetime)'] = array('egt',strtotime($begintime));
		}
		
		//当天数据
		$Where['del_flag']=0;
		$count = M('tbl_transaction_data')->where($Where)->count();// 查询满足要求的总记录数
		$page_num = 5;
		$Page = new \Mobile\Util\Page($count,$page_num); // 实例化分页类 传入总记录数
		$show = $Page->show();// 分页显示输出
		$objList = M('tbl_transaction_data')
		->where($Where)
		->order("transaction_datetime desc")
		->limit($Page->firstRow.','.$Page->listRows)
		->select();
		//echo "<br /><br />交易明细".M('tbl_transaction_data')->getlastsql();
		//dump($objList);//exit;
		//p($objList);
		$sql = M('tbl_transaction_data')->getlastsql();
		//查出来的5条记录中，日期对应的支付宝，微信，网银的交易数量
		$date_list = array();
		$date_count = array();
		foreach ($objList as $k=>$v){
			$temp_date = explode(" ", $v["transaction_datetime"]);
			if(!in_array($temp_date[0], $date_list)){
				$date_list[] = $temp_date[0];
			}
			
			$date_count[$temp_date[0]]['data'][] = $v;
			$date_count[$temp_date[0]]['dates'] = $temp_date[0];
		}
		//p($date_list);
		//echo $types; die;
		//dump($objList);dump($date_list);dump($date_count);
		$typess=explode(',',$types);
		foreach ($date_list as $k=>$v){
			//支付宝 微信 刷卡
			$card_num = 0;
			$wechat_num = 0;
			$alipay_num = 0;
			
			//当天(支付宝/微信/刷卡)的交易金额-交易手续费
			//微信02
			if(in_array("02", $typess)){
				//echo 'jinlail'; die;
				$t_day_where1 = array(
						'UNIX_TIMESTAMP(transaction_datetime)' => array(array('egt',strtotime($v . " 00:00:00")),array('elt',strtotime($v . " 23:59:59"))),
						"transaction_type" => "02",
						"transaction_status" => "00",
						"merchant_no" => array('in',$store),
						"device_no" => array('in',$device_no),
				);
				if($keyword){
					$t_day_where1['transaction_amount'] = $keyword;
				}
// 				if($types){
// 					$t_day_where1['transaction_type'] = array('in',$types);
// 				}
// 				if($status){
// 					$t_day_where1['transaction_status'] = array('in',$status);
// 				}
				$wechat_num = M('tbl_transaction_data')->where($t_day_where1)->sum('transaction_amount - transaction_commission');
				$date_count[$v]["wechat_num"] = $wechat_num?$wechat_num:0;
				//echo M('tbl_transaction_data')->getLastSql();
			}else{
				$date_count[$v]["wechat_num"] = 0;
			}
			
			//支付宝01
			if(in_array("01",$typess)){
				$t_day_where2 = array(
						'UNIX_TIMESTAMP(transaction_datetime)' => array(array('egt',strtotime($v . " 00:00:00")),array('elt',strtotime($v . " 23:59:59"))),
						"transaction_type" => "01",
						"transaction_status" => "00",
						"merchant_no" => array('in',$store),
						"device_no" => array('in',$device_no),
				);
				if($keyword){
					$t_day_where2['transaction_amount'] = $keyword;
				}
// 				if($types){
// 					$t_day_where2['transaction_type'] = array('in',$types);
// 				}
// 				if($status){
// 					$t_day_where2['transaction_status'] = array('in',$status);
// 				}
				$alipay_num = M('tbl_transaction_data')->where($t_day_where2)->sum('transaction_amount - transaction_commission');
				$date_count[$v]["alipay_num"] = $alipay_num?$alipay_num:0;
				//echo M('tbl_transaction_data')->getLastSql();
			}else{
				$date_count[$v]["alipay_num"] = 0;
			}
			
			//网银00
			if(in_array("00", $typess)){
				$t_day_where3 = array(
						'UNIX_TIMESTAMP(transaction_datetime)' => array(array('egt',strtotime($v . " 00:00:00")),array('elt',strtotime($v . " 23:59:59"))),
						"transaction_type" => "00",
						"transaction_status" => "00",
						"merchant_no" => array('in',$store),
						"device_no" => array('in',$device_no),
				);
				if($keyword){
					$t_day_where3['transaction_amount'] = $keyword;
				}
// 				if($types){
// 					$t_day_where3['transaction_type'] = array('in',$types);
// 				}
// 				if($status){
// 					$t_day_where3['transaction_status'] = array('in',$status);
// 				}
				$card_num = M('tbl_transaction_data')->where($t_day_where3)->sum('transaction_amount - transaction_commission');
				$date_count[$v]["card_num"] = $card_num?$card_num:0;
				//echo M('tbl_transaction_data')->getLastSql();
			}else{
				$date_count[$v]["card_num"] = 0;
			}
		}
		//dump($date_count);exit;
		//p($date_count);
		if(I("p")){
			//dump($date_count);exit;
			$this->assign("today_data",$date_count);
			$content = $this->fetch('page');//dump($content);exit;
			if(I("p")*$page_num >= $count){
				$noMore = true;
			}else{
				$noMore = false;
			}
			if($content){
				$this->ajaxReturn(array("status"=>1,"data"=>$content,"dates"=>$date_list,"noMore"=>$noMore,'sql'=>$sql),json);
			}else{
				$this->ajaxReturn(array("status"=>0),json);
			}
		}else{
			//dump($date_count);exit;
			//var_dump($sql);
			if($count<=$page_num){
				$data["noMore"] = true;
			}else{
				$data["noMore"] = false;
			}
			$data['data'] = $date_count;
			return $data;
		}
	}
	
	/**
	 * 周统计
	 */
	public function weekCount($data){
		//获取当月每周
		/* $weekarray = $this->get_weekinfo(date('Y-m',time()));
		$array_tmp = array(); */
		
		$week_tmp = array();
		
		$today = date('Y-m-d',time());
		//当前周
		$w = date('N',strtotime($today));
		$week_tmp = date('Y-m-d',strtotime($today.' -'.($w-1).' days'));
		
		$i = 1;
		while ($i <= 3){
			$week_data[] = date('m/d',strtotime($week_tmp));
			$week_tmp = date('Y-m-d',strtotime($week_tmp . " -7 day"));
			$i++;
		}
		sort($week_data);
		//存入cookie
		cookie('week',json_encode($week_data,JSON_UNESCAPED_UNICODE),600);
		$this->assign("weekJson1",json_encode($week_data,JSON_UNESCAPED_UNICODE));
		$weekarray = $week_data;
		$i = 0;
		foreach ($weekarray as $k => $v){
			//每月初始化值
			$alipay_num = 0;
			$wechat_num = 0;
			$card_num = 0;
			//去年
			$alipay_numlast = 0;
			$wechat_numlast = 0;
			$card_numlast = 0;
				
			//订单数统计
			$alipay_count = 0;
			$wechat_count = 0;
			$card_count = 0;
			
			foreach($data as $key => $val){
				//周
				if(strtotime(date('Y/').$v) <= strtotime($val['transaction_datetime']) && strtotime(date('Y/'). $v . " +7 day")-1 >= strtotime($val['transaction_datetime'])){
					//(支付宝/微信/刷卡)的交易金额-交易手续费
					switch ($val['transaction_type']){
						case "02":
							//支付宝
							$alipay_num = $alipay_num + $val['transaction_amount'] - $val['transaction_commission'];
							$alipay_count++;
							break;
						case "01":
							//微信
							$wechat_num = $wechat_num + $val['transaction_amount'] - $val['transaction_commission'];
							$wechat_count++;
							break;
						case "00":
							//线下
							$card_num = $card_num + $val['transaction_amount'] - $val['transaction_commission'];
							$card_count++;
							break;
					}
				}
				//去年周
				if(strtotime(date('Y/'). $v . " -1 year") <= strtotime($val['transaction_datetime']) && strtotime(date('Y/') . $v. " -1 year +7 day") -1 >= strtotime($val['transaction_datetime'])){
					//(支付宝/微信/刷卡)的交易金额-交易手续费
					switch ($val['transaction_type']){
						case "02":
							//支付宝
							$alipay_numlast = $alipay_num + $val['transaction_amount'] - $val['transaction_commission'];
							break;
						case "01":
							//微信
							$wechat_numlast = $wechat_num + $val['transaction_amount'] - $val['transaction_commission'];
							break;
						case "00":
							//线下
							$card_numlast = $card_num + $val['transaction_amount'] - $val['transaction_commission'];
							break;
					}
				}
			}
			$alipaynum_tmp[] = $alipay_num;
			$wechat_num_tmp[] = $wechat_num;
			$card_num_tmp[] = $card_num;
			$alipay_numlast_tmp[] = $alipay_numlast;
			$wechat_numlast_tmp[] = $wechat_numlast;
			$card_numlast_tmp[] = $card_numlast;
			//周列表统计
			//$week = date("m/d",strtotime($v));
			$dataTableWeek["$v"] = array(
				"slase_mount" => array($alipay_num,$wechat_num,$card_num),
				"slase_count" => array($alipay_count,$wechat_count,$card_count),
			);
		}
		
		$dataArr = $this->ArrData($alipaynum_tmp, $wechat_num_tmp, $card_num_tmp, $alipay_numlast_tmp, $wechat_numlast_tmp, $card_numlast_tmp);
		//周图表json
		$weekJson = json_encode($dataArr,JSON_UNESCAPED_UNICODE);
		$this->assign("weekJson",$weekJson);
		//周列表数据
		$this->assign("dataTableWeek",$dataTableWeek);
	}
	
	/**
	 * 月统计
	 */	
	public function mothor($data){
		$array_tmp = array();
		$i = 2;
		while ($i >= 0){
			//每月初始化值
			$alipay_num = 0;
			$wechat_num = 0;
			$card_num = 0;
			//去年
			$alipay_numlast = 0;
			$wechat_numlast = 0;
			$card_numlast = 0;
			
			//订单数统计
			$alipay_count = 0;
			$wechat_count = 0;
			$card_count = 0;
			$month_teday = date('Y-m-01',strtotime("-$i month"));
			$s_month = date("Y-m-d H:i:s",mktime(0, 0 , 0,date('m',strtotime($month_teday)),1,date('Y',strtotime($month_teday))));
			$e_month = date("Y-m-d H:i:s",mktime(23,59,59,date('m',strtotime($month_teday)),date('t',strtotime($month_teday)),date('Y',strtotime($month_teday))));
			foreach($data as $key => $val){
				//今年
				if(strtotime($s_month) <= strtotime($val['transaction_datetime']) && strtotime($e_month) >= strtotime($val['transaction_datetime'])){
					//当天(支付宝/微信/刷卡)的交易金额-交易手续费
					switch ($val['transaction_type']){
						case "01":
							//支付宝
							$alipay_num = $alipay_num + $val['transaction_amount'] - $val['transaction_commission'];
							$alipay_count++;
							break;
						case "02":
							//微信
							$wechat_num = $wechat_num + $val['transaction_amount'] - $val['transaction_commission'];
							$wechat_count++;
							break;
						case "00":
							//线下
							$card_num = $card_num + $val['transaction_amount'] - $val['transaction_commission'];
							$card_count++;
							break;
					}
				}
				//去年
				if(strtotime($s_month . " -1 year") <= strtotime($val['transaction_datetime']) && strtotime($e_month . " -1 year") >= strtotime($val['transaction_datetime'])){
					//当天(支付宝/微信/刷卡)的交易金额-交易手续费
					switch ($val['transaction_type']){
						case "01":
							//支付宝
							$alipay_numlast = $alipay_numlast + $val['transaction_amount'] - $val['transaction_commission'];
							break;
						case "02":
							//微信
							$wechat_numlast = $wechat_numlast + $val['transaction_amount'] - $val['transaction_commission'];
							break;
						case "00":
							//线下
							$card_numlast = $card_numlast + $val['transaction_amount'] - $val['transaction_commission'];
							break;
					}
				}
			}
			$to_monthalipay[] = $alipay_num;
			$to_monthwechat[] = $wechat_num;
			$to_monthcard[] = $card_num;
			$last_monthalipay[] = $alipay_numlast;
			$last_monthwechat[] = $wechat_numlast;
			$last_monthcard[] = $card_numlast;
			$i--;
			$m = intval(date("m",strtotime($month_teday)));
			$dataTableMonth[$m . "月"] = array(
				"slase_mount" => array($alipay_num,$wechat_num,$card_num),
				"slase_count" => array($alipay_count,$wechat_count,$card_count),
			);
		}
		$array_tmp = $this->ArrData($to_monthalipay, $to_monthwechat, $to_monthcard, $last_monthalipay, $last_monthwechat, $last_monthcard);

		$this->assign("dataTableMonth",$dataTableMonth);
		return json_encode($array_tmp,JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * Ajax月统计JSON
	 */
	public function mothorJson(){
		//if(IS_POST && IS_AJAX){
			$openid = session('openid')?session('openid'):$this->openid;
			//获取用户信息
			$where_u = array("openid"=>$openid);
			$user_info = M('User')->where($where_u)->find();
			//p($user_info);
		
			//获取用户所包含商户信息
			$where_store = array("users"=>$user_info['id']);
			if($_POST['store']){
				$map_store = array("store"=> array('in',$_POST['store']));
				//$map_store['_logic'] = 'or';
			}
			$map_store['_complex'] = $where_store;
			//p($map_store);
			$user_store_list = M('user_store')->where($map_store)->select();
			//p($user_store_list); die;
			//获取用户对应商户机具信息
			$device_data = array(); //机具ID数组
			foreach ($user_store_list as $k =>$v){
				$merchant_where = array("merchant_no" => $v['store'],"del_flag" => 0);
				if($_POST){
					$map_merchant_store = array("device_no"=> array('in',$_POST['device_no']));
					//$map_merchant_store['_logic'] = 'or';
				}
				$map_merchant['_complex'] = $merchant_where;
			
				$merchant_data_tmp = M('tbl_merchant_data')->where($map_merchant)->find();
				$device_data[$v['store']]['store'] = $v['store'];
				if($merchant_data_tmp){
					$device_data[$v['store']]['device_no'] = explode('|', $merchant_data_tmp['device_no']);
				}
			}
			//p($device_data);
			//获取用户交易记录信息
			$tmp_salse = array();
			foreach($device_data as $key => $val){
				foreach ($val['device_no'] as $k => $v){
					$where_salse = array("merchant_no" => $val['store'],"device_no" => $v,"del_flag" => 0);
					
					if($_POST['begindate'] && $_POST['enddate']){
						$map_slase["UNIX_TIMESTAMP(transaction_datetime)"] = array(
								array('egt',strtotime($_POST['begindate'])),array('ELT',strtotime($_POST['enddate']))
						);
					}
					if($_POST['types'] || $_POST['status']){
						$map_slase = array(
							"transaction_type" => array('in',$_POST['types']),
							"transaction_status" => array('in',$_POST['status'])
						);
					}
					$map_slase['_complex'] = $where_salse;
					$tmp_list =  M('tbl_transaction_data')->where($map_slase)->select();
					if(!empty($tmp_list)){
						$tmp_salse[] =$tmp_list;
					}
				}
			}
			//echo "<br /><br />月视图".M('tbl_transaction_data')->getlastsql();
			//p($tmp_salse);
			$data = array_format($tmp_salse);
			//p($data);

			$array_tmp = array();
			$i = 2;
			while ($i >= 0){
				$m = I('request.m');
				//每月初始化值
				$alipay_num = 0;
				$wechat_num = 0;
				$card_num = 0;
				//去年
				$alipay_numlast = 0;
				$wechat_numlast = 0;
				$card_numlast = 0;
				
				//订单数统计
				$alipay_count = 0;
				$wechat_count = 0;
				$card_count = 0;
				$num = $m - $i;
				//$month_teday = date('Y-m-01',strtotime("-$i month"));
				$month_teday = date('Y-') . $num . "-01";
				$s_month = date("Y-m-d H:i:s",mktime(0, 0 , 0,date('m',strtotime($month_teday)),1,date('Y',strtotime($month_teday))));
				$e_month = date("Y-m-d H:i:s",mktime(23,59,59,date('m',strtotime($month_teday)),date('t',strtotime($month_teday)),date('Y',strtotime($month_teday))));
				foreach($data as $key => $val){
					//今年
					if(strtotime($s_month) <= strtotime($val['transaction_datetime']) && strtotime($e_month) >= strtotime($val['transaction_datetime'])){
						//当天(支付宝/微信/刷卡)的交易金额-交易手续费
						switch ($val['transaction_type']){
							case "01":
								//支付宝
								$alipay_num = $alipay_num + $val['transaction_amount'] - $val['transaction_commission'];
								$alipay_count++;
								break;
							case "02":
								//微信
								$wechat_num = $wechat_num + $val['transaction_amount'] - $val['transaction_commission'];
								$wechat_count++;
								break;
							case "00":
								//线下
								$card_num = $card_num + $val['transaction_amount'] - $val['transaction_commission'];
								$card_count++;
								break;
						}
					}
					//去年
					if(strtotime($s_month . " -1 year") <= strtotime($val['transaction_datetime']) && strtotime($e_month . " -1 year") >= strtotime($val['transaction_datetime'])){
						//当天(支付宝/微信/刷卡)的交易金额-交易手续费
						switch ($val['transaction_type']){
							case "01":
								//支付宝
								$alipay_numlast = $alipay_numlast + $val['transaction_amount'] - $val['transaction_commission'];
								break;
							case "02":
								//微信
								$wechat_numlast = $wechat_numlast + $val['transaction_amount'] - $val['transaction_commission'];
								break;
							case "00":
								//线下
								$card_numlast = $card_numlast + $val['transaction_amount'] - $val['transaction_commission'];
								break;
						}
					}
				}
				$m = intval(date("m",strtotime($month_teday)));
				$dataTableMonth[$m . "月"] = array(
					"slase_mount" => array($alipay_num,$wechat_num,$card_num),
					"slase_count" => array($alipay_count,$wechat_count,$card_count),
				);
				$to_monthalipay[] = $alipay_num;
				$to_monthwechat[] = $wechat_num;
				$to_monthcard[] = $card_num;
				$last_monthalipay[] = $alipay_numlast;
				$last_monthwechat[] = $wechat_numlast;
				$last_monthcard[] = $card_numlast;
				$i--;
			}
			$array_tmp = $this->ArrData($to_monthalipay, $to_monthwechat, $to_monthcard, $last_monthalipay, $last_monthwechat, $last_monthcard);
			$this->ajaxReturn($array_tmp,JSON);
			//echo json_encode($array_tmp,JSON_UNESCAPED_UNICODE);
		//}
	}
	
	/**
	 * Ajax Week Json Data
	 */
	public function weekJson(){
		if(IS_POST && IS_AJAX){
			$openid = session('openid')?session('openid'):$this->openid;
			
			//获取用户信息
			$where_u = array("openid"=>$openid);
			$user_info = M('User')->where($where_u)->find();

			//获取用户所包含商户信息
			$where_store = array("users"=>$user_info['id']);
			if($_POST['store']){
				$map_store = array("store"=> array('in',$_POST['store']));
				//$map_store['_logic'] = 'or';
			}
			$map_store['_complex'] = $where_store;
			$user_store_list = M('user_store')->where($map_store)->select();
			//获取用户对应商户机具信息
			$device_data = array(); //机具ID数组
			foreach ($user_store_list as $k =>$v){
				$merchant_where = array("merchant_no" => $v['store'],"del_flag" => 0);
				if($_POST){
					$map_merchant_store = array("device_no"=> array('in',$_POST['device_no']));
					//$map_merchant_store['_logic'] = 'or';
				}
				$map_merchant['_complex'] = $merchant_where;
			
				$merchant_data_tmp = M('tbl_merchant_data')->where($map_merchant)->find();
				$device_data[$v['store']]['store'] = $v['store'];
				if($merchant_data_tmp){
					$device_data[$v['store']]['device_no'] = explode('|', $merchant_data_tmp['device_no']);
				}
			}
			//获取用户交易记录信息
			$tmp_salse = array();
			foreach($device_data as $key => $val){
				foreach ($val['device_no'] as $k => $v){
					$where_salse = array("merchant_no" => $val['store'],"device_no" => $v,"del_flag" => 0);
					
					if($_POST['begindate'] && $_POST['enddate']){
						$map_slase["UNIX_TIMESTAMP(transaction_datetime)"] = array(
								array('egt',strtotime($_POST['begindate'])),array('ELT',strtotime($_POST['enddate']))
						);
					}
					if($_POST['types'] || $_POST['status']){
						$map_slase = array(
							"transaction_type" => array('in',$_POST['types']),
							"transaction_status" => array('in',$_POST['status'])
						);
					}
					$map_slase['_complex'] = $where_salse;
					$tmp_list =  M('tbl_transaction_data')->where($map_slase)->select();
					if(!empty($tmp_list)){
						$tmp_salse[] =$tmp_list;
					}
				}
			}
			$data = array_format($tmp_salse);

			//获取当前图标数据最大周计算滑动周(取滑动方向周3周)
			$w = I('request.w');
			if($w == 1){
				//前一周
				$week_tmp_json = cookie('week');
				$array_tmp = json_decode($week_tmp_json,true);
				$array_tmp[] = date('m/d',strtotime(date('Y/').$array_tmp['2'] . " +7 day"));
				sort($array_tmp);
				$array_tmp = array_slice($array_tmp,1,3);
				//unset($array_tmp['0']);
			}else{
				//后一周
				$week_tmp_json = cookie('week');
				$array_tmp = json_decode($week_tmp_json,true);
				$array_tmp[] = date('m/d',strtotime(date('Y/').$array_tmp['0'] . " -7 day"));
				sort($array_tmp);
				unset($array_tmp['3']);
			}
			//当前日期
			$s = I('request.s');
			if(strtotime(date('Y/',time()).$array_tmp[2]) >= strtotime(date('Y/',time()).$s)){
				return false;
			}
			cookie("week",json_encode($array_tmp));
			$weekarray = $array_tmp;
			//获取当月每周
			//$weekarray = $this->get_weekinfo(date('Y-m',time()));
			foreach ($weekarray as $k => $v){
				//初始化值
				$alipay_num = 0;
				$wechat_num = 0;
				$card_num = 0;
				//去年
				$alipay_numlast = 0;
				$wechat_numlast = 0;
				$card_numlast = 0;
					
				foreach($data as $key => $val){
					//周
					if(strtotime(date('Y/') . $v) <= strtotime($val['transaction_datetime']) && strtotime(date('Y/') . $v . " +7 day") - 1 >= strtotime($val['transaction_datetime'])){
						//(支付宝/微信/刷卡)的交易金额-交易手续费
						switch ($val['transaction_type']){
							case "01":
								//支付宝
								$alipay_num = $alipay_num + $val['transaction_amount'] - $val['transaction_commission'];
								break;
							case "02":
								//微信
								$wechat_num = $wechat_num + $val['transaction_amount'] - $val['transaction_commission'];
								break;
							case "00":
								//线下
								$card_num = $card_num + $val['transaction_amount'] - $val['transaction_commission'];
								break;
						}
					}
					//去年周
					if(strtotime(date('Y/') . $v . " -1 year") <= strtotime($val['transaction_datetime']) && strtotime(date('Y/') . $v . "-1 year +7 day") - 1 >= strtotime($val['transaction_datetime'])){
						//(支付宝/微信/刷卡)的交易金额-交易手续费
						switch ($val['transaction_type']){
							case "01":
								//支付宝
								$alipay_numlast = $alipay_num + $val['transaction_amount'] - $val['transaction_commission'];
								break;
							case "02":
								//微信
								$wechat_numlast = $wechat_num + $val['transaction_amount'] - $val['transaction_commission'];
								break;
							case "00":
								//线下
								$card_numlast = $card_num + $val['transaction_amount'] - $val['transaction_commission'];
								break;
						}
					}
				}
				$alipaynum_tmp[] = $alipay_num;
				$wechat_num_tmp[] = $wechat_num;
				$card_num_tmp[] = $card_num;
				$alipay_numlast_tmp[] = $alipay_numlast;
				$wechat_numlast_tmp[] = $wechat_numlast;
				$card_numlast_tmp[] = $card_numlast;
			}
		}
		$return_array['week'] = $array_tmp;
		$return_array['weekJson'] = $this->ArrData($alipaynum_tmp, $wechat_num_tmp, $card_num_tmp, $alipay_numlast_tmp, $wechat_numlast_tmp, $card_numlast_tmp);
		$this->ajaxReturn($return_array);
	}
	
	/**
	 * 搜索
	 */
	public function search(){
		$this->assign('title',"我的账本");
		$this->assign('tab',I("tab"));
		
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
		
		//企业名称
		$where_store=array("us.hidden"=>0,"us.deleted"=>0,"us.lock"=>1,"us.users"=>$user['id']);
		$where_store['_string'] = 'LOCATE(2,access)>0';
		$storeList = M('user_store as us')
		->join(C("DB_PREFIX")."tbl_crm_kh as kh on kh.crm_kh_hykhID = us.store")
		->where($where_store)
		->field("us.store as '0',kh.crm_kh_dpmc as '1'")
		->select();
		$this->assign("storeList",$storeList);

		//机具
		$device  = M('user_store as us')
		->join(C("DB_PREFIX")."tbl_merchant_data as m on us.store = m.merchant_no")
		->where($where_store)
		->field("m.device_no,us.store")
		->select();

		$deviceList = array();
		$i = 0;
		foreach ($device as $k=>$v){
			$temp = explode("|", $v["device_no"]);
			foreach ($temp as $key=>$value){
				$deviceList[$i]["store"] = $v['store'];
				$deviceList[$i]["device_no"] = $value;
				$i++;
			}
		}
		$this->assign("deviceList",$deviceList);//dump($deviceList);exit;
		
		//交易状态 TS
		$transaction_status = M('mst_dict')->where(array('del_flag' => 0, 'id' => 'TS', 'code' => array('neq','WW')))->field('`code` as "0",`desc` as "1"')->select();
       	$this->assign('transaction_status', $transaction_status);
		
		//交易类型 TT  支付方式
		$transaction_type = M('mst_dict')->where(array('del_flag' => 0, 'id' => 'TT'))->field('`code` as "0",`desc` as "1"')->select();
       	$this->assign('transaction_type', $transaction_type);
		
		//交易日期
		$year = date("Y");
		$month = date("m")-3;
		if($month < 0){
			$month = 12 + $month;
			$year -= 1; 
		}
		$month = strlen($month)==1?'0'.$month:$month;
		$transaction_begindate = $year."-".$month."-01";
		$this->assign('transaction_begindate', $transaction_begindate);
		$this->assign('transaction_enddate', "至今");
		
		//搜索条件
		$searchData=$_POST['searchData'];
		//echo $searchData;
		//p(json_decode($searchData,true));
		//p($searchData);
		$this->assign('searchData',$searchData);
		
		$searchArray=json_decode($searchData,true);
		if(empty($searchArray)){
			
		}
		$this->assign('searchArray',$searchArray);
		
		$this->display();
	}
	
	public function get_weekinfo($month){
	    $weekinfo = array();
	    $end_date = date('d',strtotime($month.' +1 month -1 day'));
	    for ($i=1; $i <$end_date ; $i=$i+7) { 
	        $w = date('N',strtotime($month.'-'.$i));
	 
	        $weekinfo[] = array(date('Y-m-d',strtotime($month.'-'.$i.' -'.($w-1).' days')),date('Y-m-d',strtotime($month.'-'.$i.' +'.(7-$w).' days')));
	    }
	    return $weekinfo;
	}
	
	/**
	 * JSON 数组
	 * @param unknown $to_monthalipay
	 * @param unknown $to_monthwechat
	 * @param unknown $to_monthcard
	 * @param unknown $last_monthalipay
	 * @param unknown $last_monthwechat
	 * @param unknown $last_monthcard
	 * @return multitype:multitype:string number unknown
	 */
	private function ArrData($to_monthalipay,$to_monthwechat,$to_monthcard,$last_monthalipay,$last_monthwechat,$last_monthcard){
		$array_tmp = array(
				array(
						"name" => "去年支付宝",
						"type"=>'bar',
						"stack"=> "去年",
						"barWidth"=> 20,
						"data"=>$last_monthalipay
				),
				array(
						"name" => "去年微信",
						"type"=>'bar',
						"stack"=> "去年",
						"barWidth"=> 20,
						"data"=>$last_monthwechat
				),
				array(
						"name" => "去年线下支付",
						"type"=>'bar',
						"stack"=> "去年",
						"barWidth"=> 20,
						"data"=>$last_monthcard
				),
				array(
						"name" => "支付宝",
						"type"=>'bar',
						"stack"=> "今年",
						"barWidth"=> 20,
						"data"=>$to_monthalipay
				),
				array(
						"name" => "微信",
						"type"=>'bar',
						"stack"=> "今年",
						"barWidth"=> 20,
						"data"=>$to_monthwechat
				),
				array(
						"name" => "线下支付",
						"type"=>'bar',
						"stack"=> "今年",
						"barWidth"=> 20,
						"data"=>$to_monthcard
				)
		);
		return $array_tmp;
	}
	
	/**
	 * return week json Data
	 */
	public function getweekjson(){
		$s = I('request.start');
		$week_tmp = array();
		
		$day = date('d',time());
		//当前周
		$w = date('N',strtotime($month.'-'.$day));
		$week_tmp = date('Y-m-d',strtotime($month.'-'.$day.' -'.($w-1).' days'));
		
		$i = 1;
		while ($i <= 3){
			$week_data[] = date('m/d',strtotime($week_tmp));
			$week_tmp = date('Y-m-d',strtotime($week_tmp . " -7 day"));
			$i++;
		}
		sort($week_data);
		$return_data['week'] = $week_data;
		$return_data['weekJson'] = $this->weekJson();
		$this->ajaxReturn($return_data,JSON);
	}
	
	/**
	 * Ajax 按月统计JSON
	 */
	public function getMothorJson(){
		if(IS_POST && IS_AJAX){
			$openid = session('openid')?session('openid'):$this->openid;
			if(empty($openid)){
				$returnData['status']=-10;
				$returnData['info']="未获取到微信数据";
				$this->ajaxReturn($returnData,JSON);
			}
			//获取用户信息
			$where_u = array("openid"=>$openid,"deleted"=>0);
			$user_info = M('User')->where($where_u)->find();
			if(empty($user_info)){
				$returnData['status']=-10;
				$returnData['info']="未获取到用户数据";
				$this->ajaxReturn($returnData,JSON);
			}
			//p($user_info);
				
			//获取用户所包含商户信息
			$where_store = array("users"=>$user_info['id'],"lock"=>1,"deleted"=>0);
			$where_store['_string'] = 'LOCATE(2,access)>0';
			if($_POST['store']){  //商户过滤
				$map_store = array("store"=> array('in',$_POST['store']));
				//$map_store['_logic'] = 'or';
			}
			$map_store['_complex'] = $where_store;
			//p($map_store);
			$user_store_list = M('user_store')->field('store')->where($map_store)->select();
			if(empty($user_store_list)){
				$returnData['status']=-20;
				$returnData['info']="商户绑定数据错误";
				$this->ajaxReturn($returnData,JSON);
			}
			
			$tranwhere['del_flag']=0;
			if($_POST['keyword']){
				$tranwhere["transaction_amount"] = $_POST['keyword'];
			}
			if($_POST['store']){
				$tranwhere['merchant_no']= array('in',$_POST['store']);
			}else{
				$tranwhere['merchant_no']= array('in',array_format($user_store_list));
			}
			if($_POST['device']){
				$tranwhere['device_no']= array('in',$_POST['device']);
			}
			if($_POST['types']){
				$tranwhere['transaction_type']=array('in',$_POST['types']);
			}
			$tranwhere['transaction_status']="00";  //交易状态
			//if($_POST['status']){
			//$tranwhere['transaction_status']=array('in',$_POST['status']);  //交易状态
			//}
			
			$w = I('w');  //1：-月； 2：+月
			$enddate=I('str');
			if(strtotime($enddate)>time()){
				$enddate=I('enddate');
				if(strtotime($enddate)>time()){
					$enddate=date("Y-m-d");
				}
			}
			//echo $w.';'.$enddate; die;
			$enddate=date("Y-m-d",strtotime($enddate));
			//echo $enddate; die;
			
			$array_info=array();
			$array_tmp = array();
			$dataTableMonth=array();
			
			$monthData=$this->getMonthData($w,$enddate);
			//p($monthData);
			
			if($_POST['begindate'] && $_POST['enddate']){
				$le_month = date("Y-m-d H:i:s",mktime(23,59,59,date('m',strtotime($monthData[count($monthData)-1])),date('t',strtotime($monthData[count($monthData)-1])),date('Y',strtotime($monthData[count($monthData)-1]))));
				$tranwhere["UNIX_TIMESTAMP(transaction_datetime)"] =array(
						array(array('egt',strtotime($monthData[0]." 00:00:00")),array('ELT',strtotime($le_month)),'AND'),
						array(array('egt',strtotime($monthData[0]." 00:00:00"." -1 year")),array('elt',strtotime($le_month." -1 year")),'AND'),
						'OR'
				);
			}else{
				if((int)$_POST['loadNum']==3){
					$tranwhere["UNIX_TIMESTAMP(transaction_datetime)"] = array(
							array(array('egt',strtotime(date('Y-m',strtotime('-3 month')) . "-01 00:00:00")),array('elt',time()),'AND'),
							array(array('egt',strtotime(date('Y-m',strtotime('-3 month')) . "-01 00:00:00". " -1 year")),array('elt',strtotime(date('Y-m-d H:i:s',time()). " -1 year")),'AND'),
							'OR'
					);
				}
			}
			
			$data =  M('tbl_transaction_data')->where($tranwhere)->select();
			//echo M()->getLastSql(); die;
			
			foreach ($monthData as $mkey=>$mval){
				//每月初始化值
				$alipay_num = 0;
				$wechat_num = 0;
				$card_num = 0;
				
				//去年
				$alipay_numlast = 0;
				$wechat_numlast = 0;
				$card_numlast = 0;
					
				//订单数统计
				$alipay_count = 0;
				$wechat_count = 0;
				$card_count = 0;
				
				$month_teday = $mval;
				$s_month = date("Y-m-d H:i:s",mktime(0, 0 , 0,date('m',strtotime($month_teday)),1,date('Y',strtotime($month_teday))));
				$e_month = date("Y-m-d H:i:s",mktime(23,59,59,date('m',strtotime($month_teday)),date('t',strtotime($month_teday)),date('Y',strtotime($month_teday))));
				//echo 's_month:'.$s_month.';e_month:'.$e_month."<br />";
				foreach($data as $key => $val){
					//今年
					if(strtotime($s_month) <= strtotime($val['transaction_datetime']) && strtotime($e_month) >= strtotime($val['transaction_datetime'])){
						//当天(支付宝/微信/刷卡)的交易金额-交易手续费
						switch ($val['transaction_type']){
							case "01":
								//支付宝
								$alipay_num = $alipay_num + $val['transaction_amount'] - $val['transaction_commission'];
								$alipay_count++;
								break;
							case "02":
								//微信
								$wechat_num = $wechat_num + $val['transaction_amount'] - $val['transaction_commission'];
								$wechat_count++;
								break;
							case "00":
								//线下
								$card_num = $card_num + $val['transaction_amount'] - $val['transaction_commission'];
								$card_count++;
								break;
						}
					}
					//去年
					//echo 's_month:'.$s_month.';q_stime:'.strtotime($s_month . " -1 year").';e_month'.$e_month.";q_etime:".strtotime($e_month . " -1 year").';ttstr:'.$val['transaction_datetime'].';time:'.strtotime($val['transaction_datetime'])."<br />";
					//echo '1bool:'.(strtotime($s_month . " -1 year") <= strtotime($val['transaction_datetime']))."<br />";
					//echo '2bool:'.(strtotime($e_month . " -1 year") >= strtotime($val['transaction_datetime']))."<br />";
					if(strtotime($s_month . " -1 year") <= strtotime($val['transaction_datetime']) && strtotime($e_month . " -1 year") >= strtotime($val['transaction_datetime'])){
						//echo strtotime($val['transaction_datetime'])."<br />";
						//当天(支付宝/微信/刷卡)的交易金额-交易手续费
						switch ($val['transaction_type']){
							case "01":
								//支付宝
								$alipay_numlast = $alipay_numlast + $val['transaction_amount'] - $val['transaction_commission'];
								break;
							case "02":
								//微信
								$wechat_numlast = $wechat_numlast + $val['transaction_amount'] - $val['transaction_commission'];
								break;
							case "00":
								//线下
								$card_numlast = $card_numlast + $val['transaction_amount'] - $val['transaction_commission'];
								break;
						}
					}
				}
				$mm = intval(date("m",strtotime($month_teday)));
				$array_info[]=$mm.'月';
				$dataTableMonth[$mm . "月"] = array(
						"slase_mount" => array($alipay_num,$wechat_num,$card_num),
						"slase_count" => array($alipay_count,$wechat_count,$card_count),
				);
				$to_monthalipay[] = $alipay_num;
				$to_monthwechat[] = $wechat_num;
				$to_monthcard[] = $card_num;
				$last_monthalipay[] = $alipay_numlast;
				$last_monthwechat[] = $wechat_numlast;
				$last_monthcard[] = $card_numlast;
			}
			//p($dataTableMonth);
			$array_tmp = $this->ArrData($to_monthalipay, $to_monthwechat, $to_monthcard, $last_monthalipay, $last_monthwechat, $last_monthcard);
			//p($array_tmp);
			$returnData['lastTime']=date("Y-m-d",mktime(23,59,59,date('m',strtotime($monthData[count($monthData)-1])),date('t',strtotime($monthData[count($monthData)-1])),date('Y',strtotime($monthData[count($monthData)-1]))));
			$titleInfo=date('Y',strtotime($returnData['lastTime']."-1 year"))."年-".date('Y',strtotime($returnData['lastTime']))."年月数据对比";
			$returnData['titleInfo']=$titleInfo;
			$returnData['monthinfo']=$array_info;
			$returnData['series']=$array_tmp;
			$returnData['table']=$dataTableMonth;
			//p($returnData);
			$this->ajaxReturn($returnData,JSON);
			//echo json_encode($array_tmp,JSON_UNESCAPED_UNICODE);
		}
	}
	
	/*
	 * 根据自然月获取日期
	* $weekType: 1：上一周  2：下一周
	* */
	public function getMonthData($monthType,$monthStr){
		//echo $monthType.';'.$monthStr; die;
		$returnData=array();
	
		if(strtotime($monthStr)>time()){
			return null;
		}
		if($monthType==1 || $monthType==3){
			//推前
			if($monthType==1){
				$onumtime=mktime(0,0,0,date('m',strtotime($monthStr))-1,1,date('Y',strtotime($monthStr)));
				$monthStr =  date("Y-m-d",$onumtime);
			}
			
			$month_data=array();
			$i = 2;
			while ($i >= 0){
				$numtime=mktime(0,0,0,date('m',strtotime($monthStr))-$i,1,date('Y',strtotime($monthStr)));
				$month_teday =  date("Y-m-d",$numtime);
				$month_data[]=$month_teday;
				$i--;
			}
			//p($month_data); die;
			sort($month_data);
			$returnData=$month_data;
		}elseif($monthType==2){
			//下一月
			$i = 0;
			while ($i <= 1){
				$numtime=mktime(0,0,0,date('m',strtotime($monthStr))-$i,1,date('Y',strtotime($monthStr)));
				$month_teday =  date("Y-m-d",$numtime);
				$month_data[]=$month_teday;
				$i++;
			}
			sort($month_data);
			$snumtime=mktime(0,0,0,date('m',strtotime($monthStr))+1,1,date('Y',strtotime($monthStr)));
			if($snumtime<=time()){
				$smonth_teday =  date("Y-m-d",$snumtime);
				$month_data[]=$smonth_teday;
				sort($month_data);
			}
			$returnData=$month_data;
		}
		//p($returnData);
		return $returnData;
	}
	
	public function getWeekJsonByChart(){
		if(IS_POST && IS_AJAX){
			$openid = session('openid')?session('openid'):$this->openid;
			if(empty($openid)){
				$returnData['status']=-10;
				$returnData['info']="未获取到微信数据";
				$this->ajaxReturn($returnData,JSON);
			}
			//获取用户信息
			$where_u = array("openid"=>$openid,"deleted"=>0);
			$user_info = M('User')->where($where_u)->find();
			if(empty($user_info)){
				$returnData['status']=-10;
				$returnData['info']="未获取到用户数据";
				$this->ajaxReturn($returnData,JSON);
			}
			//p($user_info);
			
			//获取用户所包含商户信息
			$where_store = array("users"=>$user_info['id'],"lock"=>1,"deleted"=>0);
			$where_store['_string'] = 'LOCATE(2,access)>0';
			if($_POST['store']){  //商户过滤
				$map_store = array("store"=> array('in',$_POST['store']));
				//$map_store['_logic'] = 'or';
			}
			$map_store['_complex'] = $where_store;
			//p($map_store);
			$user_store_list = M('user_store')->field('store')->where($map_store)->select();
			if(empty($user_store_list)){
				$returnData['status']=-20;
				$returnData['info']="商户绑定数据错误";
				$this->ajaxReturn($returnData,JSON);
			}
			//p($user_store_list);
					
			$tranwhere['del_flag']=0;
			if($_POST['keyword']){
				$tranwhere["transaction_amount"] = $_POST['keyword'];
			}
			if($_POST['store']){
				$tranwhere['merchant_no']= array('in',$_POST['store']);
			}else{
				$tranwhere['merchant_no']= array('in',array_format($user_store_list));
			}
			if($_POST['device']){
				$tranwhere['device_no']= array('in',$_POST['device']);
			}
			if($_POST['types']){
				$tranwhere['transaction_type']=array('in',$_POST['types']);
			}
			$tranwhere['transaction_status']="00";  //交易状态
						
			//获取当前图标数据最大周计算滑动周(取滑动方向周3周)
			$w = I('w');
			$timeStr=I('str');
			if(strtotime($timeStr)>time()){
				$timeStr=I('enddate');
				if(strtotime($timeStr)>time()){
					$timeStr=date("Y-m-d");
				}
			}
			//echo $w.';'.$timeStr; die;
			$weekarray=$this->getWeekData($w,$timeStr);
			//p($weekarray);
			if(strtotime($weekarray[count($weekarray)-1]) > time()){
				return false;
			}
			
			$le_month = date('Y-m-d 23:59:59',strtotime($weekarray[count($weekarray)-1] . " +7 day"));
			if(strtotime($le_month) > time()){
				$le_month=date('Y-m-d 23:59:59');
			}
			$tranwhere["UNIX_TIMESTAMP(transaction_datetime)"] =array(
					array(array('egt',strtotime($weekarray[0]." 00:00:00")),array('ELT',strtotime($le_month)),'AND'),
					array(array('egt',strtotime($weekarray[0]." 00:00:00"." -1 year")),array('elt',strtotime($le_month." -1 year")),'AND'),
					'OR'
			);
			
			$data =  M('tbl_transaction_data')->where($tranwhere)->select();
			//echo M()->getLastSql(); die;
			
			//cookie("week",json_encode($array_tmp));
			//$weekarray = $array_tmp;
			//p($weekarray);
			$array_info=array();
			$dataTableWeek=array();
			//获取当月每周
			//$weekarray = $this->get_weekinfo(date('Y-m',time()));
			foreach ($weekarray as $k => $v){
				//初始化值
				$alipay_num = 0;
				$wechat_num = 0;
				$card_num = 0;
				//去年
				$alipay_numlast = 0;
				$wechat_numlast = 0;
				$card_numlast = 0;
				
				//订单数统计
				$alipay_count = 0;
				$wechat_count = 0;
				$card_count = 0;
				
				$weekW=date('W',strtotime($v." +1 day"));
				$array_info[]=$weekW.'周';
					
				foreach($data as $key => $val){
					//周
					if(strtotime($v) <= strtotime($val['transaction_datetime']) && strtotime($v . " +7 day") - 1 >= strtotime($val['transaction_datetime'])){
						//(支付宝/微信/刷卡)的交易金额-交易手续费
						switch ($val['transaction_type']){
							case "01":
								//支付宝
								$alipay_num = $alipay_num + $val['transaction_amount'] - $val['transaction_commission'];
								$alipay_count++;
								break;
							case "02":
								//微信
								$wechat_num = $wechat_num + $val['transaction_amount'] - $val['transaction_commission'];
								$wechat_count++;
								break;
							case "00":
								//线下
								$card_num = $card_num + $val['transaction_amount'] - $val['transaction_commission'];
								$card_count++;
								break;
						}
					}
					//去年周
					if(strtotime($v . " -1 year") <= strtotime($val['transaction_datetime']) && strtotime($v . "-1 year +7 day") - 1 >= strtotime($val['transaction_datetime'])){
						//(支付宝/微信/刷卡)的交易金额-交易手续费
						switch ($val['transaction_type']){
							case "01":
								//支付宝
								$alipay_numlast = $alipay_num + $val['transaction_amount'] - $val['transaction_commission'];
								break;
							case "02":
								//微信
								$wechat_numlast = $wechat_num + $val['transaction_amount'] - $val['transaction_commission'];
								break;
							case "00":
								//线下
								$card_numlast = $card_num + $val['transaction_amount'] - $val['transaction_commission'];
								break;
						}
					}
				}
				$dataTableWeek[$weekW . "周"] = array(
						"slase_mount" => array($alipay_num,$wechat_num,$card_num),
						"slase_count" => array($alipay_count,$wechat_count,$card_count),
				);
				$alipaynum_tmp[] = $alipay_num;
				$wechat_num_tmp[] = $wechat_num;
				$card_num_tmp[] = $card_num;
				$alipay_numlast_tmp[] = $alipay_numlast;
				$wechat_numlast_tmp[] = $wechat_numlast;
				$card_numlast_tmp[] = $card_numlast;
			}
			
			$return_array['cyear']=date('Y',strtotime($weekarray[count($weekarray)-1]))>=date('Y')?1:0;
			$titleInfo=date('Y',strtotime($weekarray[count($weekarray)-1]."-1 year"))."年-".date('Y',strtotime($weekarray[count($weekarray)-1]))."年周数据对比";
			$return_array['titleInfo']=$titleInfo;
			$return_array['lastTime']=$weekarray[count($weekarray)-1];
			$return_array['week'] = $weekarray;
			$return_array['cWeekNum']=trim($array_info[count($array_info)-1],'周');
			$return_array['weekinfo'] = $array_info;
			$return_array['table']=$dataTableWeek;
			$return_array['series'] = $this->ArrData($alipaynum_tmp, $wechat_num_tmp, $card_num_tmp, $alipay_numlast_tmp, $wechat_numlast_tmp, $card_numlast_tmp);
			//p($return_array);
			$this->ajaxReturn($return_array);
		}else{
			$return_array['status']=-1; 
			$return_array['info']="request error";
			$this->ajaxReturn($return_array);
		}
	}
	
	/*
	 * 根据自然周获取日期
	 * $weekType: 1：上一周  2：下一周
	 * */
	public function getWeekData($weekType,$weekStr){
		//echo 'weekType:'.$weekType.';weekStr:'.$weekStr; die;
		$returnData=array();
		
		$week_tmp = array();
		
		//当前周
		$w = date('N',strtotime($weekStr));
		$week_tmp = date('Y-m-d',strtotime($weekStr.' -'.($w-1).' days'));
		//echo $week_tmp; die;
		
		if($weekType==1 || $weekType==3){
			//推前
			if($weekType==1){
				$week_tmp = date('Y-m-d',strtotime($week_tmp . " -7 day"));
			}			
			
			$i = 1;
			while ($i <= 3){
				$week_data[] = date('Y/m/d',strtotime($week_tmp));
				$week_tmp = date('Y-m-d',strtotime($week_tmp . " -7 day"));
				$i++;
			}
			sort($week_data);
			$returnData=$week_data;
		}elseif($weekType==2){
			//下一周
			$i = 1;
			while ($i <= 2){
				$week_data[] = date('Y/m/d',strtotime($week_tmp));
				$week_tmp = date('Y-m-d',strtotime($week_tmp . " -7 day"));
				$i++;
			}
			sort($week_data);
			$week_data[] = date('Y/m/d',strtotime($week_data['1'] . " +7 day"));
			sort($week_data);
			$returnData=$week_data;
		}
		//p($returnData);
		return $returnData;
	}
}
?>