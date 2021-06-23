<?php
namespace wstmart\mobile\controller;
use think\Loader;
use Env;
use wstmart\common\model\Payments as M;
use wstmart\mobile\model\Orders as OM;
use wstmart\common\model\ChargeItems as CM;
use wstmart\common\model\LogMoneys as LM;
use wstmart\common\model\LogSms;
/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtaosoft.com
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 阿里支付控制器
 */
class Alipays extends Base{
    
    /**
     * 支付宝支付跳转方法
     */
    public function toAliPay(){

        $m = new M();
        $payment = $m->getPayment("alipays");
        require Env::get('root_path') . 'extend/alipay/aop/AopClient.php' ;
        require Env::get('root_path') . 'extend/alipay/aop/request/AlipayTradeWapPayRequest.php' ;

        $payObj = input("payObj/s");
        $passback_params = "";
        $returnUrl = "";
        $subject = "";
        $orderAmount = 0;
        $transId = 0;
        $userId = 0;
        $payParams = array();
        if($payObj=="recharge"){//充值
            $itemId = (int)input("itemId/d");
            if($itemId>0){
                $cm = new CM();
                $item = $cm->getItemMoney($itemId);
                $orderAmount = isSet($item["chargeMoney"])?$item["chargeMoney"]:0;
            }else{
                $orderAmount = (int)input("needPay/d");
            }
            $targetType = 0;
            $targetId = (int)session('WST_USER.userId');
            $userId = $targetId;
            $out_trade_no = WSTOrderNo();
            $transId = $out_trade_no;
            $passback_params = $targetId."@".$targetType."@".$itemId."@".$payObj;
            $returnUrl = url("mobile/logmoneys/usermoneys","",true,true);
            $subject = '钱包充值 ¥'.$orderAmount.'元';
            $body = '钱包充值';
        }else if($payObj=="signup"){//报名
			$passback_params = $payObj."@".$orderAmount."@".$catId."@".$listId."@".$userId;
			$subject = '支付报名费用'.$orderAmount.'元';
			$body = '支付报名费用';
            $returnUrl = url("home/alipays/paysignup","catId=".$catId,true,true);
			
            $isBatch = "1";
            $userId = (int)session('WST_USER.userId');
			$listId = (int)input("itmeId/d");
			$orderAmount = input('needPay');
			$catId = (int)input("catId/d");
			$sr = Db::name('signup_lists')->where('listId',$listId)->find();
			$transId = $sr['signupSn'];
			if($rs["isPaid"]==1){
				echo "<span style='font-size:40px;'>您已交过费，请勿重复支付！</span>";
				return;
			}

            $payRand = rand(100,999);
            $out_trade_no = $transId."a".$payRand;
            $passback_params = $userId."@".$isBatch."@".$listId."@".$payObj;
            $returnUrl = addon_url("signup://signup/mend",array("catId"=>$catId),true,true);
            $subject = '支付线上报名费用';
            $body = '支付线上报名费用';
        }else{
            $pkey = WSTBase64urlDecode(input("pkey"));
            $pkey = explode('@',$pkey);
            $orderNo = $pkey[0];
            $isBatch = (int)$pkey[1];
            $userId = (int)session('WST_USER.userId');
            $om = new OM();
            $obj = array();
            $obj["userId"] = $userId;
            $obj["orderNo"] = $orderNo;
            $obj["isBatch"] = $isBatch;
            $rs = $om->getOrderPayInfo($obj);

            if(empty($rs)){
                $this->assign('type','');
                return $this->fetch("users/orders/orders_list");
            }else{
                $data = $om->checkOrderPay2($obj);
                if($data["status"]==-1){
                    echo "<span style='font-size:40px;'>您的订单已支付，不要重复支付！</span>";
                    return;
                }else if($data["status"]==-2){
                    echo "<span style='font-size:40px;'>您的订单因商品库存不足，不能支付！</span>";
                    return;
                }
            }
            $order = $om->getPayOrders($obj);
            $orderAmount = $order["needPay"];
            $payRand = $order["payRand"];
            $out_trade_no = $obj["orderNo"]."a".$payRand;
            $transId = $obj["orderNo"];
            $passback_params = $userId."@".$isBatch."@".$orderNo."@".$payObj;
            $returnUrl = url("mobile/orders/index","",true,true);
            $subject = '支付购买商品费用';
            $body = '支付订单费用';
        }

        $aop = new \AopClient ();  
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';  
        $aop->appId = $payment["appId"];  
        $aop->rsaPrivateKey = $payment["rsaPrivateKey"]; 
        $aop->alipayrsaPublicKey = $payment["alipayrsaPublicKey"];
        $aop->apiVersion = '1.0';  
        $aop->signType = 'RSA2';  
        $aop->postCharset= "UTF-8";;  
        $aop->format='json';  
        $request = new \AlipayTradeWapPayRequest  ();  
        $request->setReturnUrl($returnUrl);  
        $request->setNotifyUrl(url("mobile/alipays/aliNotify","",true,true));
        $passback_params = urlencode($passback_params);
        $bizcontent = "{\"body\":\"$body\","
                    . "\"subject\": \"$subject\","
                    . "\"out_trade_no\": \"$out_trade_no\","
                    . "\"timeout_express\": \"90m\","
                    . "\"total_amount\": \"$orderAmount\","
                    . "\"passback_params\": \"$passback_params\","
                    . "\"product_code\":\"QUICK_WAP_WAY\""
                    . "}";
        $request->setBizContent($bizcontent);
        //请求  
        $result = $aop->pageExecute ($request);
        echo $result;
    }

    function aliCheck($params){
        require Env::get('root_path') . 'extend/alipay/aop/AopClient.php' ;
        $aop = new \AopClient;
        $m = new M();
        $payment = $m->getPayment("alipays");
        $aop->alipayrsaPublicKey = $payment["alipayrsaPublicKey"];
        $flag = $aop->rsaCheckV1($params, NULL, "RSA2");
        return $flag;
    }
    
    /**
     * 服务器异步通知方法
     */
    function aliNotify() {
        if($this->aliCheck($_POST)){
            if ($_POST['trade_status'] == 'TRADE_SUCCESS' || $_POST['trade_status'] == 'TRADE_FINISHED'){
                $extras = explode("@",urldecode($_POST['passback_params']));
                $payObj = isSet($extras[3])?$extras[3]:"";
                $obj["trade_no"] = $_POST['trade_no'];
                $tradeNo = explode("a",$_POST["out_trade_no"]);
                $obj["out_trade_no"] = $tradeNo[0];
                $obj["payFrom"] = 'alipays';
                $obj["total_fee"] = $_POST['total_amount'];
                if($payObj=='recharge'){
                    $obj["targetId"] = (int)$extras[0];
                    $obj["targetType"] = (int)$extras[1];
                    $obj["itemId"] = (int)$extras[2];
                    // 支付成功业务逻辑
                    $m = new LM();
                    $rs = $m->complateRecharge ( $obj );
                }else if($payObj=="signup"){//报名
					$out_trade_no = $tradeNo[0];
					$listId = (int)$extras[2];
					$userId = (int)$extras[0];
					Db::name('signup_lists')->where('listId',$listId)->update(['isPaid'=>1,'payCode'=>'alipays','tradeNo'=>$_POST['trade_no'],'payTime'=>date('Y-m-d H:i:s')]);
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
					$lm['payType'] = 'alipays';
					$lm['tradeNo'] = $_POST['trade_no'];
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
							$rv = $m->sendSMS(0,$userPhone,$params,'singupAlipay',"",$userId,0);
						}
					}
				}else{
                    $obj["userId"] = (int)$extras[0];
                    $obj["isBatch"] = (int)$extras[1];
                    //支付成功业务逻辑
                    $om = new OM();
                    $rs = $om->complatePay($obj);
                }
                if($rs["status"]==1){
                    echo 'success';
                }else{
                    echo 'fail';
                }
            }
        } else {
            // 验证失败
            echo "fail";
        }
    }

}
