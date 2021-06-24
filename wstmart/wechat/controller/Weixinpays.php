<?php
namespace wstmart\wechat\controller;
use think\Loader;
use Env;
use wstmart\common\model\Payments as M;
use wstmart\common\model\Orders as OM;
use wstmart\common\model\LogMoneys as LM;
use wstmart\common\model\ChargeItems as CM;
use wstmart\common\model\LogSms;
use think\Db;
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
class Weixinpays extends Base{
	
	/**
	 * 初始化
	 */
	private $wxpayConfig;
	private $wxpay;
	public function initialize() {
		header ("Content-type: text/html; charset=utf-8");
	    require Env::get('root_path') . 'extend/wxpay/WxPayConf.php';
	    require Env::get('root_path') . 'extend/wxpay/WxQrcodePay.php';
	    require Env::get('root_path') . 'extend/wxpay/WxJsApiPay.php';
		$this->wxpayConfig = array();
		$m = new M();
		$this->wxpay = $m->getPayment("weixinpays");
		$this->wxpayConfig['appid'] = $this->wxpay['appId']; // 微信公众号身份的唯一标识
		$this->wxpayConfig['appsecret'] = $this->wxpay['appsecret']; // JSAPI接口中获取openid
		$this->wxpayConfig['mchid'] = $this->wxpay['mchId']; // 受理商ID
		$this->wxpayConfig['key'] = $this->wxpay['apiKey']; // 商户支付密钥Key
		$this->wxpayConfig['notifyurl'] = url("wechat/weixinpays/notify","",true,true);
		$this->wxpayConfig['returnurl'] = url("wechat/orders/index","",true,true);
		$this->wxpayConfig['curl_timeout'] = 30;
		
		// 初始化WxPayConf
		new \WxPayConf($this->wxpayConfig);
	}
	

	public function toPay(){
	    $data = [];
	    $payObj = input("payObj/s");
	    if($payObj=="recharge"){
	    	$cm = new CM();
	    	$itemId = (int)input("itemId/d");
	    	$targetType = 0;
	    	$targetId = (int)session('WST_USER.userId');
	    	$needPay = 0;
	    	if($itemId>0){
	    		$item = $cm->getItemMoney($itemId);
	    		$needPay = isSet($item["chargeMoney"])?$item["chargeMoney"]:0;
	    	}else{
	    		$needPay = (int)input("needPay/d");
	    	}
	    	$out_trade_no = WSTOrderNo();
	    	$body = "钱包充值";
	    	$data["status"] = $needPay>0?1:-1;
	    	$attach = $payObj."@".$targetId."@".$targetType."@".$needPay."@".$itemId;
	    	$returnurl = url("wechat/logmoneys/usermoneys","",true,true);
			if($needPay==0){ 
				header("Location:".$returnurl);
				exit();
			}
	    }else if($payObj=="signup"){
	        $out_trade_no = input('orderNo');
			$body = "报名缴费";
			$needPay = input("needPay");
			$listId = input("itemId");
			$catId = input("catId");
			$userId = (int)session('WST_USER.userId');
			$attach = $payObj."@".$needPay."@".$catId."@".$listId."@".$userId;
			$returnurl = addon_url("signup://signup/mend",array("catId"=>$catId),true,true);
			if($needPay==0){ 
				header("Location:".$returnurl);
				exit();
			}
		}else{
	    	$pkey = WSTBase64urlDecode(input("pkey"));
            $pkey = explode('@',$pkey);
            $orderNo = $pkey[0];
            $isBatch = (int)$pkey[1];
            
	        $data['orderNo'] = $orderNo;
	        $data['isBatch'] = $isBatch;
	        $data['userId'] = (int)session('WST_USER.userId');
			$m = new OM();
			$rs = $m->getOrderPayInfo($data);
			$returnurl = url("wechat/orders/index","",true,true);
			if(empty($rs)){
				header("Location:".$returnurl);
				exit();
			}else{
				$m = new OM();
				$userId = (int)session('WST_USER.userId');
				$obj["userId"] = $userId;
				$obj["orderNo"] = $orderNo;
				$obj["isBatch"] = $isBatch;
		
				$rs = $m->getByUnique($userId,$orderNo,$isBatch);
				$this->assign('rs',$rs);
				$body = "支付订单";
				$order = $m->getPayOrders($obj);
				$needPay = $order["needPay"];
				$payRand = $order["payRand"];
				$out_trade_no = $obj["orderNo"]."a1".$payRand;
				$attach = $userId."@".$obj["orderNo"]."@".$obj["isBatch"];
				
				if($needPay==0){ 
					header("Location:".$returnurl);
					exit();
				}
			}
	    }
	    //使用jsapi接口
	    $jsApi = new \JsApi();
	    //使用统一支付接口
	    $unifiedOrder = new \UnifiedOrder();
	    $openid = session('WST_USER.wxOpenId');
	    $unifiedOrder->setParameter("openid",$openid);//商品描述
	    	
	    //自定义订单号，此处仅作举例
	    $unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号
	    $unifiedOrder->setParameter("notify_url",$this->wxpayConfig ['notifyurl']);//通知地址
	    $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	    	
	    $unifiedOrder->setParameter("body",$body);//商品描述
	    $needPay = WSTBCMoney($needPay,0,2);
	    $unifiedOrder->setParameter("total_fee", $needPay * 100);//总金额
	    $userId = (int)session('WST_USER.userId');
	    
	    $this->assign('needPay',$needPay);
	    $this->assign('returnUrl',$returnurl );
	    $this->assign('payObj',$payObj);
	    	
	    $unifiedOrder->setParameter("attach",$attach);//附加数据
	    	
	    $prepay_id = $unifiedOrder->getPrepayId();
	    //=========步骤3：使用jsapi调起支付============
	    $jsApi->setPrepayId($prepay_id);
	    	
	    $jsApiParameters = $jsApi->getParameters();
	    $this->assign('jsApiParameters',$jsApiParameters);
		return $this->fetch('users/orders/orders_pay');
	}
	
	
	public function toAddonPay() {
		$this->assign('payObj',session("addonPay.payObj"));
		$this->assign('object',session("addonPay.object"));
		$this->assign('needPay',session("addonPay.needPay"));
		$this->assign('returnUrl',session("addonPay.returnUrl"));
		$this->assign('jsApiParameters',session("addonPay.jsApiParameters"));
		$ctr = new \think\addons\Controller();
		return $ctr->fetch(session("addonPay.showUrl"));
	}
	
	
	
	public function notify() {
		// 使用通用通知接口
		$notify = new \Notify();
		// 存储微信的回调
		$xml = file_get_contents("php://input");
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
		
		$obj["total_fee"] = (float)$order["total_fee"]/100;
		$extras =  explode ( "@", $order ["attach"] );
		if($extras[0]=="recharge"){//充值
			$targetId = (int)$extras [1];
			$targetType = (int)$extras [2];
			$itemId = (int)$extras [4];

			$obj["out_trade_no"] = $order['out_trade_no'];
			$obj["targetId"] = $targetId;
			$obj["targetType"] = $targetType;
			$obj["itemId"] = $itemId;
			$obj["payFrom"] = 'weixinpays';
			// 支付成功业务逻辑
			$m = new LM();
			$rs = $m->complateRecharge ( $obj );
		}else if($extras[0]=="signup"){
			$out_trade_no = $order["out_trade_no"];
			$listId = (int)$extras[3];
			$userId = (int)$extras[4];
			Db::name('signup_lists')->where('listId',$listId)->update(['isPaid'=>1,'payCode'=>'weixinpays','tradeNo'=>$order['transaction_id'],'payTime'=>date('Y-m-d H:i:s')]);
			$rs = array('status'=>1);

			$sl = Db::name('signup_lists')->where('listId',$listId)->find();
			$needPay = $sl['signupFee'];
			$signupSn = $sl['signupSn'];
			//创建一条充值流水记录
			$lm = [];
			$lm['targetType'] = 0;
			$lm['targetId'] = $userId;
			$lm['dataId'] = $listId;
			$lm['dataSrc'] = 100;
			$lm['remark'] = '报名【'.$signupSn.'】充值¥'.$needPay;
			$lm['moneyType'] = 1;
			$lm['money'] = $needPay;
			$lm['payType'] = 'weixinpays';
			$lm['tradeNo'] = $order['transaction_id'];
			$lm['createTime'] = date('Y-m-d H:i:s');
			model('LogMoneys')->create($lm);
			//创建一条支出流水记录
			$lm = [];
			$lm['targetType'] = 0;
			$lm['targetId'] = $userId;
			$lm['dataId'] = $listId;
			$lm['dataSrc'] = 100;
			$lm['remark'] = '报名【'.$signupSn.'】支出¥'.$needPay;
			$lm['moneyType'] = 0;
			$lm['money'] = $needPay;
			$lm['payType'] = 0;
			$lm['createTime'] = date('Y-m-d H:i:s');
			model('LogMoneys')->create($lm);

			// 报名成功后发送短信
			$rspay = Db::name('users')->field('loginName,userPhone,userId')->where('userId',$userId)->find();
			$userPhone = $rspay['userPhone'];
			if(strlen($userPhone)==11 && substr($userPhone,0,1)=="1"){	//检测手机号码
				$tpl = WSTMsgTemplates('PHONE_USER_SIGNUP');
				if( $tpl['tplContent']!='' && $tpl['status']=='1'){
					$params = ['tpl'=>$tpl,'params'=>['SIGNUP_TITLE'=>$sl["catName"],'LOGIN_NAME'=>$rspay['loginName']]];
					$m = new LogSms();
					$rv = $m->sendSMS(0,$userPhone,$params,'singupWxpay',"",$userId,0);
				}
			}
		}else{
			$obj["userId"] = $extras[0];
			$obj["out_trade_no"] = $extras[1];
			$obj["isBatch"] = $extras[2];
			$obj["payFrom"] = "weixinpays";
			// 支付成功业务逻辑
			$m = new OM();
			$rs = $m->complatePay ( $obj );
		}
		
		return $rs;
		
	}

}
