<?php
namespace addons\pintuan\controller;
use think\addons\Controller;
use addons\pintuan\model\Pintuans as M;
use wstmart\common\model\Payments;

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
 * 余额控制器
 */
class walletswe extends Controller{
	/**
	* 获取支付方式
	*/
	public function payType(){
		//获取支付方式
		$m = new M();
		$pa = new Payments();
		$payments = $pa->getByGroup('4', -1, true)[1]; // 只能在线支付
		$rs = $m->getPayInfo((int)input('auctionId/d',0),1);
		$rs['data']['payments'] = $payments;
		return jsonReturn('',1,$rs);
	}
	/*************************************************  余额支付start ******************************************************/
	/**
	 * 生成支付代码--跳转余额支付前调用，获取key
	 * payObj=bao
	 */
	function getWalletsUrl(){
		$pkey = input("pkey");
		$data = array();
		$data['status'] = 1;
		// 获取用户id
		$userId = model('weapp/Index')->getUserId();
		if($userId==0)return jsonReturn('您还未登录~',-999);
		$data['pkey'] = $pkey;
		return jsonReturn('',1,$data);
	}
	
	
	/**
	 * 跳去支付页面
	 * key
	 * 
	 */
	public function wallets(){
		
		// 获取用户id
		$userId = model('weapp/Index')->getUserId();
		if($userId==0)return jsonReturn('您还未登录~',-999);
		$pkey = input('pkey');
        $orderNo = WSTBase64urlDecode($pkey);
       
		$data = [];
        $data['orderNo'] = $orderNo;
        $data['userId'] = $userId;
		$m = new M();
		$data = $m->getTuanPay($orderNo,$userId);
		if($data["status"]==1){
			$user = model('common/users')->getFieldsById($userId,'userMoney,payPwd');
			$payPwd = $user['payPwd'];
			$payPwd = empty($payPwd)?0:1;
			$data["userMoney"] = $user['userMoney'];
			$data["payPwd"] = $payPwd;
			return jsonReturn('',1,$data);
		}else{
			return jsonReturn('获取支付信息失败',-1);
	    }
	}
	/**
	 * 执行余额支付
	 * 需要传递支付密码跟余额支付生成的key
	 * payPwd 
	 * key
	 */
	public function payByWallet(){
		$m = new M();
		$userId = model('weapp/Index')->getUserId();
		if($userId>0){
			$rs = $m->payByWallet($userId);
			return jsonReturn("",1,$rs);
		}
		return jsonReturn('您还未登录',-999);
	}
	/**********************************************  余额支付end  *********************************************************/
	
}
