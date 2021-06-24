<?php
namespace wstmart\home\controller;
use wstmart\common\model\Orders as OM;
use wstmart\common\model\Users as UM;
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
 * 余额控制器
 */
class Wallets extends Base{
	/**
	 * 生成支付代码
	 */
	function getWalletsUrl(){
		$pkey = input('pkey');
		$userId = (int)session('WST_USER.userId');
		$payObj = input('payObj');
		$catId = input('catId/d');
        $data = [];
        $data['status'] = 1;
        $data['url'] = url('home/wallets/payment','pkey='.$pkey.'&catId='.$catId,'html',true);
		return $data;
	}
	
	/**
	 * 跳去支付页面
	 */
	public function payment(){
		if((int)session('WST_USER.userId')==0){
			$this->assign('message',"对不起，您尚未登录，请先登录!");
            return $this->fetch('error_msg');
		}
		$userId = (int)session('WST_USER.userId');
		$catId = input('catId/d');
		$m = new UM();
		$user = $m->getFieldsById($userId,["payPwd"]);
		$this->assign('hasPayPwd',($user['payPwd']!="")?1:0);
		$pkey = input('pkey');
		$this->assign('pkey',$pkey);
        $pkey = WSTBase64urlDecode($pkey);
        $pkey = explode('@',$pkey);
        $data = [];
		$data['userId'] = $userId;
        $data['orderNo'] = $pkey[0];
        if($pkey[1] == 'signup'){	//报名
			$signupSn = $pkey[0];
			$rs = Db::name('signup_lists')->where('signupSn',$signupSn)->find();
			if(empty($rs)){
				$this->assign('message',"系统错误，没找到该报名信息。");
				return $this->fetch('error_msg');
			}else if($rs['isPaid'] != 0){
				$this->assign('message',"您已缴过费，请勿重复支付~");
				return $this->fetch('error_msg');
			}
			$this->assign('needPay',$rs['signupFee']);
		}else{
			$data['isBatch'] = (int)$pkey[1];
			$m = new OM();
			$rs = $m->getOrderPayInfo($data);
			$this->assign('needPay',$rs['needPay']);
		}
		if(empty($rs)){
			$this->assign('message',"您的订单已支付，请勿重复支付~");
            return $this->fetch('error_msg');
		}else{
			//获取用户钱包
			$user = model('users')->getFieldsById($data['userId'],'userMoney');
			$this->assign('userMoney',$user['userMoney']);
			$this->assign('catId',$catId);
	        return $this->fetch('order_pay_wallets');
	    }
	}

	/**
	 * 钱包支付
	 */
	public function payByWallet(){
		$m = new OM();
        return $m->payByWallet();
	}

}
