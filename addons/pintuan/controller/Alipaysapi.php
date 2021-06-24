<?php
namespace addons\pintuan\controller;
use think\addons\Controller;
use think\Loader;
use Env;
use wstmart\common\model\Payments as PM;
use addons\pintuan\model\Pintuans as M;
use wstmart\common\model\Orders as OM;
use wstmart\common\model\LogMoneys as LM;
use wstmart\common\model\LogPayParams as LPM;

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
 * app支付宝支付控制器
 */
class Alipaysapi extends Controller{

	/**
     * 支付宝支付跳转方法
     */
    public function aliPay(){
	    $m = new M();
    	$orderNo = input("orderNo/d",0);
    	$userId = model('app/index')->getUserId();
        $data = $m->getTuanPay($orderNo,$userId);
    	if($data["status"]==1){
    		$needPay =  $data["data"]["needPay"];
			if($needPay==0){
				return json_encode(WSTReturn('订单无需支付~',-1));
			}
	    	$obj = array();
            $obj["userId"] = $userId;
            $obj['orderNo'] = $data["data"]["orderNo"];
	    	require Env::get('root_path') . 'extend/alipay/aop/AopClient.php';
		   	require Env::get('root_path') . 'extend/alipay/aop/request/AlipayTradeAppPayRequest.php';
		    $m = new PM();
		    $payment = $m->getPayment("alipays");
		    $aop = new \AopClient;
			$aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
			$aop->appId = $payment["appId"];
			$aop->rsaPrivateKey = $payment["rsaPrivateKey"];
			$aop->format = "json";
			$aop->charset = "UTF-8";
			$aop->signType = "RSA2";
			$aop->alipayrsaPublicKey = $payment["alipayrsaPublicKey"];
			//实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
			$request = new \AlipayTradeAppPayRequest();

			$total_fee = $data["data"]["needPay"];
        	$subject = "支付拼团费用";
        	$out_trade_no = $data["data"]["orderNo"].'a'.$userId;
			//SDK已经封装掉了公共参数，这里只需要传入业务参数
			$bizcontent = "{\"body\":\"$subject\","
							. "\"subject\": \"$subject\","
							. "\"out_trade_no\": \"$out_trade_no\","
							. "\"timeout_express\": \"30m\","
							. "\"total_amount\": \"$total_fee\","
							. "\"product_code\":\"QUICK_MSECURITY_PAY\""
							. "}";
				
		
			$request->setNotifyUrl(addon_url("pintuan://alipaysapi/notify","",true,true));
			$request->setBizContent($bizcontent);

			//这里和普通的接口调用不同，使用的是sdkExecute
			$response = $aop->sdkExecute($request);
			//htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
            return json_encode(WSTReturn('ok',1,$response));
	    }else{
			return json_encode(WSTReturn($data["msg"],-1));
		}

    }
    
    /**
     * 服务器异步通知页面方法
     *
     */
    function notify() {
    	require Env::get('root_path') . 'extend/alipay/aop/AopClient.php';
    	$aop = new \AopClient;
        $m = new PM();
    	$payment = $m->getPayment("alipays");
		$aop->alipayrsaPublicKey = $payment["alipayrsaPublicKey"];
		$flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
    	
    	if ($flag) {
    		$notify_data = $_POST;
    		// 交易号
    		$trade_no = $_POST["trade_no"];
    		// 商户订单号
    		$out_trade_no = $_POST["out_trade_no"];
    		$total_fee = $_POST["total_amount"];
    		// 交易状态
    		$trade_status = $_POST["trade_status"];
    		if ($trade_status == 'TRADE_FINISHED' OR $trade_status  == 'TRADE_SUCCESS') {
    			$obj = array();
    			$obj["trade_no"] = $trade_no;
                $tradeNo = explode("a",$out_trade_no);
                $obj["out_trade_no"] = $tradeNo[0];
                $obj["total_fee"] = $total_fee;
                $obj["payFrom"] = "alipays";
                $obj["userId"] = $tradeNo[1];
                //支付成功业务逻辑
                $m = new M();
				$rs = $m->complatePay ( $obj );
                if($rs["status"]==1){
                    echo 'success';
                }else{
                    echo 'fail';
                }
    		}
    		echo "success"; // 请不要修改或删除
    		
    	} else {
    		echo "fail";
    	}
    }
}
