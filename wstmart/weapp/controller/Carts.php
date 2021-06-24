<?php
namespace wstmart\weapp\controller;
use wstmart\weapp\model\Carts as MCarts;
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
 * 购物车控制器
 */
class Carts extends Base{
	// 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'  =>  ['except'=>'getcartnum']
    ];
	/**
	 * 查看购物车列表
	 */
	public function index(){
		$mc = new MCarts();
		$userId = model('weapp/index')->getUserId();
		$carts = $mc->getCarts(false,$userId);
		if(!empty($carts['carts'])){
			$carts['isCheck'] = 1;
			foreach ($carts['carts'] as $key =>$v){
				$isCheck = 1;
				foreach ($v['list'] as $keys =>$vs){
					$carts['check'][$v['shopId']]['list'][$vs['cartId']] = $vs['isCheck'];
					$carts['check'][$v['shopId']]['stock'][$vs['cartId']] = $vs['goodsStock'];
					if($vs['isCheck']==0)$isCheck = $carts['isCheck'] = 0;
					$carts['price'][$v['shopId']]['list'][$vs['cartId']] = $vs['shopPrice'];
					$carts['promotion'][$v['shopId']]['list'][$vs['cartId']] = $vs['promotion'];
					$carts['rewardCartIds'][$v['shopId']]['list'][$vs['cartId']] = (isset($vs['rewardCartIds']))?$vs['rewardCartIds']:[];
				}
				$carts['check'][$v['shopId']]['isCheck'] = $isCheck;
				$carts['price'][$v['shopId']]['money'] = sprintf("%.2f", $v['goodsMoney']);
			}
			$list = $mc->getCartInfo(false,$userId);
			$carts['num'] = [];
			foreach ($list['list'] as $key2 =>$v2){
				$carts['num'][$v2['cartId']] = $v2['cartNum'];
			}
			return jsonReturn('success',1,$carts);
		}
		return jsonReturn('暂无购物车数据',-1);
	}
    /**
    * 加入购物车
    */
	public function addCart(){
		$userId = model('weapp/index')->getUserId();
		$m = new MCarts();
		$rs = $m->addCart($userId);
		return json_encode($rs);
	}
	/**
	 * 修改购物车商品状态
	 */
	public function changeCartGoods(){
		$userId = model('weapp/index')->getUserId();
		$m = new MCarts();
		$rs = $m->changeCartGoods($userId);
		return json_encode($rs);
	}
	/**
	* 批量设置选中
	*/
	public function batchSetIsCheck(){
		$m = new MCarts();
		$rs = $m->batchSetIsCheck();
		return $rs;
	}
	/**
	 * 删除购物车里的商品
	 */
	public function delCart(){
		$userId = model('weapp/index')->getUserId();
		$m = new MCarts();
		$rs= $m->delCart($userId);
		return json_encode($rs);
	}
	/**
	 * 计算运费、积分和总商品价格
	 */
	public function getCartMoney(){
		$m = new MCarts();
		$userId = model('weapp/index')->getUserId();
		$data = $m->getCartMoney($userId);
		if($data['data']){
			$data['data']['maxScoreMoney'] = sprintf("%.2f", $data['data']['maxScoreMoney']);
			$data['data']['realTotalMoney'] = sprintf("%.2f", $data['data']['realTotalMoney']);
			$data['data']['scoreMoney'] = sprintf("%.2f", $data['data']['scoreMoney']);
			$data['data']['totalGoodsMoney'] = sprintf("%.2f", $data['data']['totalGoodsMoney']);
			$data['data']['totalMoney'] = sprintf("%.2f", $data['data']['totalMoney']);
			if ($data['data']['shops']){
				foreach ($data['data']['shops'] as $key =>$v){
					$data['data']['shops'][$key]['freight'] = sprintf("%.2f", $v['freight']);
					$data['data']['shops'][$key]['goodsMoney'] = sprintf("%.2f", $v['goodsMoney']);
					
				}
			}
		}
		return jsonReturn('',1,$data);
	}
	/**
	 * 计算运费、积分和总商品价格/虚拟商品
	 */
	public function getQuickCartMoney(){
		$m = new MCarts();
		$userId = model('weapp/index')->getUserId();
		$data = $m->getQuickCartMoney($userId);
		if($data['data']){
			$data['data']['maxScoreMoney'] = sprintf("%.2f", $data['data']['maxScoreMoney']);
			$data['data']['realTotalMoney'] = sprintf("%.2f", $data['data']['realTotalMoney']);
			$data['data']['scoreMoney'] = sprintf("%.2f", $data['data']['scoreMoney']);
			$data['data']['totalGoodsMoney'] = sprintf("%.2f", $data['data']['totalGoodsMoney']);
			$data['data']['totalMoney'] = sprintf("%.2f", $data['data']['totalMoney']);
			if ($data['data']['shops']){
				foreach ($data['data']['shops'] as $key =>$v){
					$data['data']['shops'][$key]['freight'] = sprintf("%.2f", $v['freight']);
					$data['data']['shops'][$key]['goodsMoney'] = sprintf("%.2f", $v['goodsMoney']);
						
				}
			}
		}
		return jsonReturn('',1,$data);
	}
	/**
	 * 结算页面数据
	 */
	public function settlement(){
		$mc = new MCarts();
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
		$payments = $pa->getByGroup('3');
		//获取已选的购物车商品
		$carts = $mc->getCarts(true,$userId);
		foreach ($carts['carts'] as $key =>$v){
			$carts['coupons'][$v['shopId']]['list'] = isset($v['coupons'])?$v['coupons']:[];
			$carts['coupons'][$v['shopId']]['couponId'] = 0;
			$carts['coupons'][$v['shopId']]['words'] = '不使用优惠';
			foreach ($v['list'] as $keys =>$vs){
				$carts['promotion'][$v['shopId']]['list'][$vs['cartId']] = $vs['promotion'];
			}
		}
		if(empty($carts['carts']))return jsonReturn('请选择商品',-1);
		$carts['userAddress'] = $userAddress;
		
		hook("mobileControllerCartsSettlement",["carts"=>$carts,"payments"=>&$payments]);

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
		$user = model('users')->getFieldsById($userId, 'userScore');
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
	 * 跳去虚拟商品购物车结算页面
	 */
	public function quickSettlement(){
		$userId = model('weapp/index')->getUserId();
		$m = new MCarts();
		//获取用户积分
		$user = model('users')->getFieldsById($userId,'userScore');

		//获取已选的购物车商品
		$carts = $m->getQuickCarts($userId);
        foreach ($carts['carts'] as $key =>$v){
			$carts['coupons'][$v['shopId']]['list'] = isset($v['coupons'])?$v['coupons']:[];
			$carts['coupons'][$v['shopId']]['couponId'] = 0;
			$carts['coupons'][$v['shopId']]['words'] = '不使用优惠';
			foreach ($v['list'] as $keys =>$vs){
				$carts['promotion'][$v['shopId']]['list'][$vs['cartId']] = $vs['promotion'];
			}
		}
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

		//获取支付方式
		$pa = new Payments();
		$payments = $pa->getByGroup('3');
		$carts['payments'] = $payments;
		$carts['payNames'] = $carts['payCodes'] = $carts['isOnline'] =  [];
		if(isset($payments[1])){
			foreach ($payments[1] as $key =>$v){
				$carts['payNames'][] = $v['payName'];
				$carts['payCodes'][] = $v['payCode'];
				$carts['isOnlines'][] = $v['isOnline'];
			}
		}else{
			$carts['payNames'] = ['没有支付方式'];
		}
		$carts['userOrderScore'] = $useOrderScore;
        $carts['userOrderMoney'] = $useOrderMoney;
        // 是否开启积分支付
		$carts['isOpenScorePay'] = WSTConf('CONF.isOpenScorePay');
		
		return jsonReturn('success',1,$carts);
	}
	/**
	* 获取购物车数量
	*/
	public function getCartNum(){
		$data = model('weapp/carts')->cartNum();
		return jsonReturn('success', 1, $data);
	}
}
