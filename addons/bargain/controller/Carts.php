<?php
namespace addons\bargain\controller;

use think\addons\Controller;
use addons\bargain\model\Carts as M;
use addons\bargain\model\Bargains;
use wstmart\common\model\UserAddress;
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
 * 全民砍价插件
 */
class Carts extends Controller{
	/**
	 * 去下单
	 */
	public function addCart(){
		$m = new M();
		return $m->addCart();
	}
	/**
	 * 下单
	 */
	public function submit(){
		$m = new M();
		$rs = $m->submit(1);
		if($rs["status"]==1){
			$pkey = WSTBase64urlEncode($rs["data"]."@1");
			$rs["pkey"] = $pkey;
		}
		return $rs;
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
	 * 微信结算页面
	 */
	public function wxSettlement(){
		$CARTS = session('BARGAIN_CARTS');
		if(empty($CARTS)){
			session('wxdetail','对不起没有相关订单~~o(>_<)o~~');
			$this->redirect('wechat/error/message',['code'=>'wxdetail']);
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
		$onlineType = ($carts['goodsType']==1)?1:-1;
		$payments = model('common/payments')->getByGroup('3',$onlineType);
		$this->assign('payments',$payments);
		return $this->fetch("/wechat/index/settlement");
	}

    /**************************************小程序**********************************/
    /**
     * 去下单
     */
    public function weAddCart(){
        $this->checkWeappAuth();
        $userId= model('weapp/index')->getUserId();
        $m = new M();
        $rs = $m->addCart($userId);
        return jsonReturn('success',1,$rs);
    }
    /**
     * 下单
     */
    public function weSubmit(){
        $this->checkWeappAuth();
        $userId= model('weapp/index')->getUserId();
        $m = new M();
        $data = $m->submit(5,$userId);
        return jsonReturn('success',1,$data);
    }
    /**
     * 计算运费、积分和总商品价格
     */
    public function weGetCartMoney(){
        $this->checkWeappAuth();
        $userId= model('weapp/index')->getUserId();
        $m = new M();
        $data = $m->getCartMoney($userId);
        return jsonReturn('success',1,$data);
    }

    /**
     * 结算页面
     */
    public function weSettlement(){
        $this->checkWeappAuth();
        $userId= model('weapp/index')->getUserId();
        $CARTS = session('BARGAIN_CARTS');

        if(empty($CARTS)){
            return jsonReturn('请选择商品',-1);
            exit;
        }
        //获取一个用户地址
        $addressId = (int)input('addressId');
        $ua = new UserAddress();
        if($addressId>0){
            $userAddress = $ua->getById($addressId,$userId);
        }else{
            $userAddress = $ua->getDefaultAddress($userId);
        }
        //获取支付方式
        $payments = model('common/payments')->getByGroup('3',1);
        //获取已选的购物车商品
        $m = new M();
        $carts = $m->getCarts($userId);
        if(empty($carts['carts'])) return jsonReturn('砍价商品不存在',-1);
        if($carts['goodsTotalNum']>0){
            if(empty($carts['carts']))return jsonReturn('请选择商品',-1);
        }
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
        $user = model('common/users')->getFieldsById($userId, 'userScore');
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
}