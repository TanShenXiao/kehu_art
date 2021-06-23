<?php
namespace addons\signup\controller;

use think\addons\Controller;
use addons\signup\model\Signup as M;
use addons\signup\model\Cats as MC;
use addons\signup\model\Lists as ML;
use addons\signup\model\Extras as ME;
/**
 * 线上报名插件
 */
class signup extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}

	public function lists(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			//$this->redirect("/home/users/index");
		}
		// 获取报名项目清单
		$m = new MC();
		$data = $m->getCats();
		$this->assign('cats',$data);
		$catId = input('catId/d');
		$step = input('step/d');
		if($catId==0 && !empty($data))
			$catId = $data[0]['catId'];
		$this->assign('curCatId',$catId);
		if(empty($step))
			$step = 0;
		$this->assign('step',$step);
		$this->assign('out_trade_no',input('out_trade_no'));
		$this->assign('code_url',base64_decode(input('code_url')));
		
		// 获取扩展字段信息
		$me = new ME();
		$extras = $me->getExtrasByCatId($catId);
		if(empty($extras))
			$extras = array();
		$this->assign('extras',$extras);
		// 获取会员报名信息
		$userExtrasVal = array();
		if($userId==0) {
			$this->assign('isLogin',0);
			$data['isSigned'] = 0;
			$data['isPaid'] = 0;
			$data['pkey'] = '';
			$this->assign('userSignupInfo',$data);
		}else{
			$this->assign('isLogin',1);
			if($catId!=0){
				$m = new ML();
				$data = $m->getUserSignup($userId,$catId);
			}else{
				$data['isSigned'] = 0;
				$data['isPaid'] = 0;
				$data['pkey'] = '';
			}
			$this->assign('userSignupInfo',$data);
			if(isset($data['listId'])){
				$userExtrasVal = $me->getUserExtrasVal($data['listId']);
			}
		}
		$this->assign('userExtrasVal',$userExtrasVal);
		// 获取支付方式
    	$payments = model('common/payments')->getOnlinePayments();
    	$this->assign('payments',$payments);
		return $this->fetch('/home/list');
	}
	
	public function mlists(){
		$platform = $this->checkPlatForm();
		// 获取报名项目清单
		$m = new MC();
		$data = $m->getCats();
		$this->assign('cats',$data);
		$catId = input('catId/d');
		$step = input('step/d');
		if($catId==0 && !empty($data))
			$catId = $data[0]['catId'];
		$this->assign('curCatId',$catId);
		if(empty($step))
			$step = 0;
		$this->assign('step',$step);
		if($platform == 0){//wap
			return $this->fetch('/mobile/list');
		}else{//wechat
			return $this->fetch('/wechat/list');
		}
	}

	public function mdesc(){
		$platform = $this->checkPlatForm();
		$catId = input('catId/d');
		if($catId==0) {
			return;
		}
		// 获取报名项目清单
		$m = new MC();
		$data = $m->getCatById($catId);
		$this->assign('cat',$data);
		if($platform == 0){//wap
			return $this->fetch('/mobile/catdesc');
		}else{//wechat
			if(WSTConf('CONF.wxenabled')==1){
				$we = WSTWechat();
				$datawx = $we->getJsSignature(request()->scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				$this->assign("datawx", $datawx);
			}
			return $this->fetch('/wechat/catdesc');
		}
	}

	public function mend(){
		$platform = $this->checkPlatForm();
		$catId = input('catId/d');
		if($catId==0) {
			return;
		}
		// 获取报名项目清单
		$m = new MC();
		$data = $m->getCatById($catId);
		$this->assign('cat',$data);
		if($platform==0){//wap
			return $this->fetch('/mobile/end');
		}else{//wechat
			return $this->fetch('/wechat/end');
		}
	}

	public function mpay(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			return;
		}
		$platform = $this->checkPlatForm();
		$catId = input('catId/d');
		if($catId==0) {
			return;
		}// 获取报名项目清单
		$m = new MC();
		$data = $m->getCatById($catId);
		$this->assign('cat',$data);
		$ml = new ML();
		$signupInfo = $ml->getUserSignup($userId,$catId);
    	$this->assign('signupInfo',$signupInfo);
		if($platform==0){//wap
			$payments = model('common/payments')->getOnlinePayments('4');
			$this->assign('payments',$payments);
			return $this->fetch('/mobile/pay');
		}else{//wechat
			$payments = model('common/payments')->getOnlinePayments('3');
			$this->assign('payments',$payments);
			return $this->fetch('/wechat/pay');
		}
	}
	
	public function mpayWallets(){
		$platform = $this->checkPlatForm();
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			return;
		}
		$catId = input('catId/d');
		$listId = input('itemId/d');

		$ml = new ML();
		$signupInfo = $ml->getUserSignup($userId,$catId);

		$this->assign('needPay',$signupInfo['signupFee']);
		$this->assign('signupSn',$signupInfo['signupSn']);
		$this->assign('catId',$catId);
		//获取用户钱包
		$user = model('common/users')->getFieldsById($userId,'userMoney,payPwd');
		$this->assign('userMoney',$user['userMoney']);
		$payPwd = $user['payPwd'];
		$payPwd = empty($payPwd)?0:1;
		$this->assign('payPwd',$payPwd);

		if($platform == 0){//wap
			return $this->fetch('/mobile/pay_wallets');
		}else{//wechat
			return $this->fetch('/wechat/pay_wallets');
		}
	}
	
	public function msubmit(){
		$catId = input('catId/d');
		if($catId==0) {
			return;
		}
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			//$this->redirect("/home/users/index");
		}
		$platform = $this->checkPlatForm();
		// 获取扩展字段信息
		$me = new ME();
		$extras = $me->getExtrasByCatId($catId);
		if(empty($extras))
			$extras = array();
		$this->assign('extras',$extras);
		// 获取会员报名信息
		$userExtrasVal = array();
		if($userId==0) {
			$this->assign('isLogin',0);
			$data['isSigned'] = 0;
			$data['isPaid'] = 0;
			$this->assign('userSignupInfo',$data);
		}else{
			$this->assign('isLogin',1);
			if($catId!=0){
				$m = new ML();
				$data = $m->getUserSignup($userId,$catId);
			}else{
				$data['isSigned'] = 0;
				$data['isPaid'] = 0;
			}
			$this->assign('userSignupInfo',$data);
			if(isset($data['listId'])){
				$userExtrasVal = $me->getUserExtrasVal($data['listId']);
			}
		}
		$this->assign('userExtrasVal',$userExtrasVal);
		// 获取报名项目清单
		$m = new MC();
		$data = $m->getCatById($catId);
		$this->assign('cat',$data);
		if($platform == 0){//wap
			return $this->fetch('/mobile/signupsubmit');
		}else{//wechat
			return $this->fetch('/wechat/signupsubmit');
		}
	}
	
	public function getmenus(){
        $m = new M();
        return $m->getCatById(Input("id/d",0));
	}
	
	public function checkPlatForm(){
		$request = request();
		$isMobile = $request->isMobile();
		$isWeChat = (strpos(isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'', 'MicroMessenger') !== false);
		if($isWeChat)
			return 1;
		else return 0;
	}
}