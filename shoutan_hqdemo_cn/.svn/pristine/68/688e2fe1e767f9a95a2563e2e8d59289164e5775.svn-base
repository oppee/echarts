<?php
/*
 * 微信支付自定义配置项
 */
class WxPayMy
{
	public function configData()
	{
		$config = C('Config');
		$data = array(
			"APPID" =>$config['wx_appid'],
			"MCHID" =>$config['merchant_number'],
			"KEY" =>$config['wx_key'],
			"APPSECRET" =>$config['wx_secert'],
		);
		return $data;
	}
	
	//微信支付--下单成码记录
	public function wxpayOrder($data)
	{
		$model = M('wxpay_order');
		if (! empty($data)) {
			$data['ctime'] = date('Y-m-d H:i:s');
			$id = $model->add($data);
		}
	}

	//微信支付--储存微信端支付成功数据
	public function wxpayPayResult($data)
	{
		$model = M('wxpay_result');
		$id = 0;
		// M('province')->add(array("code"=>3,"name"=>json_encode($data)));
		//记录微信扫码支付后原始数据--安全起见
		M('wxpay_order')->where("out_trade_no='".$data['out_trade_no']."'")->save(array("return_original_data"=>json_encode($data)));
		//记录微信扫码支付后正式数据--便于调用
		if (! empty($data)) {
			$data['ctime'] = date('Y-m-d H:i:s');
			$id = $model->add($data);
		}
		return $id;
	}
}
?>