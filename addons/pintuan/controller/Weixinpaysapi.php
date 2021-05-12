<?php
namespace addons\pintuan\controller;
use think\addons\Controller;
use think\Loader;
use Env;
use wstmart\common\model\Payments as PM;
use addons\pintuan\model\Pintuans as M;
use wstmart\common\model\Orders as OM;
use wstmart\common\model\LogMoneys as LM;

/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtao.net
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 微信支付控制器
 */
class Weixinpaysapi extends Controller{
	/**
	 * 初始化
	 */
	private $wxpayConfig;
	private $wxpay;
	public function initialize() {
		header ("Content-type: text/html; charset=utf-8");
		require_once Env::get('root_path') . 'extend/wxpay/WxPayConf.php';
		require_once Env::get('root_path') . 'extend/wxpay/WxJsApiPay.php';
		$this->wxpayConfig = array();
		$m = new PM();
		$this->wxpay = $m->getPayment("app_weixinpays");
		$this->wxpayConfig['appid'] = $this->wxpay['appId']; // 微信公众号身份的唯一标识
		$this->wxpayConfig['appsecret'] = $this->wxpay['appsecret']; // JSAPI接口中获取openid
		$this->wxpayConfig['mchid'] = $this->wxpay['mchId']; // 受理商ID
		$this->wxpayConfig['key'] = $this->wxpay['apiKey']; // 商户支付密钥Key
		$this->wxpayConfig['notifyurl'] = "";
		$this->wxpayConfig['curl_timeout'] = 30;
		$this->wxpayConfig['returnurl'] = "";
		// 初始化WxPayConf
		new \WxPayConf($this->wxpayConfig);
	}
	public function toPay(){
    	$m = new M();
    	$data = array();
    	$orderNo = input("orderNo/d",0);
    	$userId = model('app/index')->getUserId();

    	$data = $m->getTuanPay($orderNo,$userId);
    	$notify_url = addon_url("pintuan://weixinpaysapi/notify","",true,true);
    	if($data["status"]==1){
    		$needPay =  $data["data"]["needPay"];
    		// $openid = session('WST_USER.wxOpenId');
			$out_trade_no = $data["data"]["orderNo"];
			if($needPay==0){
				return json_encode(WSTReturn('订单无需支付~',-1));
			}
			//使用统一支付接口
			$unifiedOrder = new \UnifiedOrder();
			// $unifiedOrder->setParameter("openid",$openid);//商品描述

			$body = "支付拼团费用";
			//自定义订单号，此处仅作举例
			$unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号
			$unifiedOrder->setParameter("notify_url",$notify_url);//通知地址
			$unifiedOrder->setParameter("trade_type","APP");//交易类型
			$unifiedOrder->setParameter("body",$body);//商品描述
			
			$unifiedOrder->setParameter("total_fee", $needPay * 100);//总金额
			$attach = $userId;
			$unifiedOrder->setParameter("attach",$attach);//附加数据
			$prepay_id = $unifiedOrder->getPrepayId();

			
			$obj["prepayid"] = $prepay_id;
	    	$rs = $unifiedOrder->getParameters($obj);
	    	$data =array('msg'=>'success','status'=>1,'data'=>$rs);
	    	echo json_encode($data);
		}else{
			return json_encode(WSTReturn($data["msg"],-1));
		}
		
	}
	
	public function notify() {
		// 使用通用通知接口
		$notify = new \Notify();
		// 存储微信的回调
		$xml = file_get_contents('php://input');
		$notify->saveData ( $xml );
		if ($notify->checkSign () == FALSE) {
			$notify->setReturnParameter ( "return_code", "FAIL" ); // 返回状态码
			$notify->setReturnParameter ( "return_msg", "签名失败" ); // 返回信息
		} else {
			$notify->setReturnParameter ( "return_code", "SUCCESS" ); // 设置返回码
		}
		$returnXml = $notify->returnXml ();
		if ($notify->checkSign () == TRUE) {
			if ($notify->data ["return_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
			} elseif ($notify->data ["result_code"] == "FAIL") {
				// 此处应该更新一下订单状态，商户自行增删操作
			} else {
				$order = $notify->getData ();
				$rs = $this->process($order);
				if($rs["status"]==1){
					echo "SUCCESS";
				}else{
					echo "FAIL";
				}
			}
		}
	}
	
	//订单处理
	private function process($order) {
		$obj = array();
		$obj["trade_no"] = $order['transaction_id'];
		$obj["out_trade_no"] = $order['out_trade_no'];
		$obj["total_fee"] = (float)$order["total_fee"]/100;
		$userId =  (int)$order ["attach"];
		$obj["userId"] = $userId;
		$obj["payFrom"] = 'app_weixinpays';
		// 支付成功业务逻辑
		$m = new M();
		$rs = $m->complatePay ( $obj );
		return $rs;
	}

	// 团购退款回调
	public function tuanNotify(){
        $m = new \addons\pintuan\model\WeixinpaysApi();
        $m->tuanNotify();
    }

}
