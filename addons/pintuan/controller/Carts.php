<?php
namespace addons\pintuan\controller;

use think\addons\Controller;
use addons\pintuan\model\Pintuans as M;
use wstmart\common\model\UserAddress;
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
 * 拼团商品插件
 */
class Carts extends Controller{
	protected $addonStyle = 'default';
	public function __construct(){
		parent::__construct();
		$m = new M();
        $data = $m->getConf('Pintuan');
        $this->addonStyle = ($data['addonsStyle']=='')?'default':$data['addonsStyle'];
        $this->assign("addonStyle",$this->addonStyle);
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}

    /**
     * 下单
     */
    public function addCart(){
        $m = new M();
        return $m->addCart();
    }


	/**
	 * 结算页面
	 */
	public function settlement(){
	    $CARTS = session('PINTUAN_CARTS'); 
		if(empty($CARTS)){
			header("Location:".addon_url('pintuan://goods/lists')); 
			exit;
		}
		//获取一个用户地址
		$userAddress = model('common/UserAddress')->getDefaultAddress();
		$this->assign('userAddress',$userAddress);
		//获取省份
		$areas = model('common/Areas')->listQuery();
		$this->assign('areaList',$areas);
		$m = new M();
		$carts = $m->getCarts();
		$this->assign('carts',$carts);
		//获取用户积分
        $user = model('common/users')->getFieldsById((int)session('WST_USER.userId'),'userScore');
        $this->assign('userScore',$user['userScore']);
        //获取支付方式
		$payments = model('common/payments')->getByGroup('1',1);
        $this->assign('payments',$payments);

		return $this->fetch($this->addonStyle."/home/index/settlement");
	}

	/**
	 * 计算运费、积分和总商品价格
	 */
	public function getCartMoney(){
		$m = new M();
		$data = $m->getCartMoney();
		return $data;
	}

	/**
	 * 下单
	 */
	public function submit(){
		$m = new M();
		$rs = $m->submit((int)input("orderSrc/d"));
		if($rs["status"]==1){
			$pkey = WSTBase64urlEncode($rs["data"]);
			$rs["pkey"] = $pkey;
		}
		return $rs;
	}
    
	/**
	 * 微信结算页面
	 */
	public function wxSettlement(){
		$CARTS = session('PINTUAN_CARTS');
		if(empty($CARTS)){
			header("Location:".addon_url('pintuan://goods/wxlists'));
			exit;
		}
		//获取一个用户地址
		$addressId = (int)input('addressId');
		$ua = new UserAddress();
		if($addressId>0){
			$userAddress = $ua->getById($addressId);
		}else{
			$userAddress = $ua->getDefaultAddress();
		}
		$this->assign('userAddress',$userAddress);
		//获取省份
		$areas = model('common/Areas')->listQuery();
		$this->assign('areaList',$areas);
		$m = new M();
		$carts = $m->getCarts();
		$this->assign('carts',$carts);
		//获取用户积分
		$user = model('common/users')->getFieldsById((int)session('WST_USER.userId'),'userScore');
		//计算可用积分和金额
		$goodsTotalMoney = $carts['goodsTotalMoney'];
		$goodsTotalScore = WSTScoreToMoney($goodsTotalMoney,true);
		$useOrderScore =0;
		$useOrderMoney = 0;
		if($user['userScore']>$goodsTotalScore){
			$useOrderScore = $goodsTotalScore;
			$useOrderMoney = $goodsTotalMoney;
		}else{
			$useOrderScore = $user['userScore'];
			$useOrderMoney = WSTScoreToMoney($useOrderScore);
		}
		$this->assign('userOrderScore',$useOrderScore);
		$this->assign('userOrderMoney',$useOrderMoney);
		//获取支付方式
		$payments = model('common/payments')->getByGroup('3',1);
		$this->assign('payments',$payments);
		return $this->fetch($this->addonStyle."/wechat/index/settlement");
	}
	
	/**
	 * 手机结算页面
	 */
	public function moSettlement(){
		$CARTS = session('PINTUAN_CARTS');
		if(empty($CARTS)){
			header("Location:".addon_url('pintuan://goods/molists'));
			exit;
		}
		//获取一个用户地址
		$addressId = (int)input('addressId');
		$ua = new UserAddress();
		if($addressId>0){
			$userAddress = $ua->getById($addressId);
		}else{
			$userAddress = $ua->getDefaultAddress();
		}
		$this->assign('userAddress',$userAddress);
		//获取省份
		$areas = model('common/Areas')->listQuery();
		$this->assign('areaList',$areas);
		$m = new M();
		$carts = $m->getCarts();
		$this->assign('carts',$carts);
		//获取用户积分
		$user = model('common/users')->getFieldsById((int)session('WST_USER.userId'),'userScore');
		//计算可用积分和金额
		$goodsTotalMoney = $carts['goodsTotalMoney'];
		$goodsTotalScore = WSTScoreToMoney($goodsTotalMoney,true);
		$useOrderScore =0;
		$useOrderMoney = 0;
		if($user['userScore']>$goodsTotalScore){
			$useOrderScore = $goodsTotalScore;
			$useOrderMoney = $goodsTotalMoney;
		}else{
			$useOrderScore = $user['userScore'];
			$useOrderMoney = WSTScoreToMoney($useOrderScore);
		}
		$this->assign('userOrderScore',$useOrderScore);
		$this->assign('userOrderMoney',$useOrderMoney);
		//获取支付方式
		$payments = model('common/payments')->getByGroup('2',1);
		$this->assign('payments',$payments);
		return $this->fetch($this->addonStyle."/mobile/index/settlement");
	}

	/**
     * 下单
     */
    public function weAddCart(){
        $m = new M();
        $userId = model('weapp/index')->getUserId();
        $rs = $m->addCart($userId);
        return jsonReturn('success',1,$rs);
    }


    /**
	 * 微信结算页面
	 */
	public function weSettlement(){

		//获取一个用户地址
		$addressId = (int)input('addressId');
		$ua = new UserAddress();
		$userId = model('weapp/index')->getUserId();
		if($addressId>0){
			$userAddress = $ua->getById($addressId, $userId);
		}else{
			$userAddress = $ua->getDefaultAddress($userId);
		}
		//获取支付方式
		$pa = new Payments();
		$payments = $pa->getByGroup('3',1);

		$carts = session('PINTUAN_CARTS');
		if(empty($carts)){
			return jsonReturn('请选择商品',-1);
		}
		$m = new M();
		$carts = $m->getCarts();
		$carts['userAddress'] = $userAddress;
		

		$carts['payments'] = $payments;
		$carts['payNames'] = $carts['payCodes'] = $carts['isOnline'] =  [];
		if($payments){
			foreach ($payments as $key =>$v){
				foreach ($v as $key2 =>$v2){
					$carts['payNames'][] = $v2['payName'];
					$carts['payCodes'][] = $v2['payCode'];
					$carts['isOnlines'][] = $v2['isOnline'];
				}
			}
		}else{
			$carts['payNames'] = ['没有支付方式'];
		}
		
		//获取用户积分
		$user = model('common/Users')->getFieldsById($userId, 'userScore');
		//计算可用积分和金额
        $goodsTotalMoney = $carts['goodsTotalMoney'];
        $goodsTotalScore = WSTScoreToMoney($goodsTotalMoney,true);
        $useOrderScore =0;
        $useOrderMoney = 0;
        if($user['userScore']>$goodsTotalScore){
            $useOrderScore = $goodsTotalScore;
            $useOrderMoney = $goodsTotalMoney;
        }else{
        	$useOrderScore = $user['userScore'];
            $useOrderMoney = WSTScoreToMoney($useOrderScore);
        }
        $carts['userOrderScore'] = $useOrderScore;

        $carts['userOrderMoney'] = $useOrderMoney;
		// 是否开启积分支付
		$carts['isOpenScorePay'] = WSTConf('CONF.isOpenScorePay');
		return jsonReturn('success',1,$carts);
	}

	/**
	 * 计算运费、积分和总商品价格
	 */
	public function weGetCartMoney(){
		$userId = model('weapp/index')->getUserId();
		$m = new M();
		$data = $m->getCartMoney($userId);
		return jsonReturn('success',1,$data);
	}

	/**
	 * 下单
	 */
	public function weSubmit(){
		$userId = model('weapp/index')->getUserId();
		$m = new M();
		$rs = $m->submit(4,$userId);
		if($rs["status"]==1){
			$pkey = WSTBase64urlEncode($rs["data"]);
			$rs["pkey"] = $pkey;
		}
		return jsonReturn('success',1,$rs);
	}
}