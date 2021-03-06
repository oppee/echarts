<?php
/**
 * 获取数据的分类名称
 * @param $pid 位置ID
 */

function getCategoryName($id, $tbName = CONTROLLER_NAME, $field = 'name')
{
	if (!$str = M("$tbName")->where("id =$id ")->getField($field)) {
		$str = '==顶级==';
	}

	return $str;
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){
	if(function_exists("mb_substr")){
		if($suffix && strlen($str)>$length){
			return mb_substr($str, $start, $length, $charset)."...";
		}else{
			return mb_substr($str, $start, $length, $charset);
		}
	}elseif(function_exists('iconv_substr')) {
		if($suffix && strlen($str)>$length){
			return iconv_substr($str,$start,$length,$charset)."...";
		}else{
			return iconv_substr($str,$start,$length,$charset);
		}
	}
	$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("",array_slice($match[0], $start, $length));
	if($suffix && strlen($str)>$length){
		return $slice."...";
	}else{
		return $slice;
	}
}

function get_type_name($type,$send_type){
	$array=array(
			'1' => array('1' => '消费送积分', '2' => '积分兑换受益权'),
			'2' => array('11' => '受益权兑换', '12' => '受益权消耗'),
			'3' => array('21' => '鲸币赠送', '22' => '鲸币抵扣'),
	);
	return $array[$type][$send_type];
}

function graydate($date){
	$tmp_num = time() - $date;
	$tmp_s = $tmp_num / 60;
	$tmp_day = $tmp_s / (60 * 24);
	if($tmp_s < 60){
		return intval($tmp_s) . "分钟前";
	}elseif($tmp_s >= 60 && $tmp_s <= 24*60){
		$tmp_i = intval($tmp_s) / 60;
		return intval($tmp_i) . "小时前";
	}elseif($tmp_day > 1 && $tmp_day < 90){
		$tmp_day = intval($tmp_s) / (60 * 24);
		return intval($tmp_day) . "天前";
	}else{
		return "3个月前";
	}
}


/**
 * curl请求
 * @Author nuchect@qq.cn
 * @param $url string 请求服务器
 * @param $data mix 请求数组（存在 post请求 不存在 get请求）
 */
function https_request($url,$data = null){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	if (!empty($data)){
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	}
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($curl);
	$status = curl_getinfo($curl);
	curl_close($curl);
	if(intval($status["http_code"])==200){
		return $output;
	}else{
		return false;
	}
}

/**
 * 发送短信验证码
 */
function sendSMS($phone,$code,$sms_code,$SmsParam){
// 	include "TopSdk.php";
	import("Common.Util.Alsms.TopSdk");
	date_default_timezone_set('Asia/Shanghai');
	
	$c = new TopClient;
	$c->appkey = C('SMS_APPKEY');
	$c->secretKey = C('SMS_SECRETKEY');
	$req = new AlibabaAliqinFcSmsNumSendRequest;
	$req->setExtend("123456");
	$req->setSmsType("normal");
	$req->setSmsFreeSignName(C('SMS_SIGN'));//签名
	$req->setSmsParam($SmsParam);//参数
	$req->setRecNum($phone);//手机号 多个用，号隔开
	$req->setSmsTemplateCode($sms_code);//模板ID
	$resp = $c->execute($req);
	return $resp;
// 	var_dump($resp);
}

//对象转数组
function object_to_array($d)
{
	if (is_object($d)) $d = get_object_vars($d);
	if (is_array($d)){
		return array_map('object_to_array', $d);
	}else{
		return $d;
	}
}

/**
 * Get Wechat Access_token
 * @return Ambigous <mixed, \Think\mixed, object>|mixed
 */
function get_access_token(){
	$appid = C("WECHAT_APPID");	
	$secret = C('WECHAT_SECRET');
	$key = 'access_token';
	$res = S ( $key );
	if ($res !== false)
		return $res;
	
	$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $secret;
	
	$tempArr = json_decode ( file_get_contents ( $url ), true );
	if (@array_key_exists ( 'access_token', $tempArr )) {
		S ( $key, $tempArr ['access_token'], 7200 );
		return $tempArr ['access_token'];
	}
}

//二维数组转一维数组
function array_format($arr) {
    $tmp=array();
    if(is_array($arr)){
    	foreach ($arr as $key => $val){
    		foreach ($val as $k => $v){
    			$tmp[] = $v;
    		}
    	}
	}  
    return $tmp;
}

//获取支付方式
function getPayType($code){
	$Obj = M('mst_dict');
	$where = array("id"=>"TT","code"=>$code);
	$info = $Obj->where($where)->find();
	switch ($code){
		case "00":
			//线下
			$result = '<img src="'.C('TMPL_PARSE_STRING')['__TMPL__'].'Public/images/unionpay.png"/>';
			break;
		case "01":
			//支付宝
			$result = '<img src="'.C('TMPL_PARSE_STRING')['__TMPL__'].'Public/images/alipay.png"/>';	//&#xe605;
			break;
		case "02":
			//微信
			$result = '<img src="'.C('TMPL_PARSE_STRING')['__TMPL__'].'Public/images/weixin.png"/>';
			break;
	}
	return $result;
}

function transaction_status($code){
	$Obj = M('mst_dict');
	$where = array("id"=>"TS","code"=>$code);
	$info = $Obj->where($where)->find();
	return $info['desc'];
}