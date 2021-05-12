<?php
namespace addons\bargain\controller;
use think\addons\Controller;
use addons\bargain\model\Bargains as M;
use wstmart\common\model\Payments;
use addons\bargain\model\Carts;
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
 * 插件控制器
 */
class Apis extends Controller{
	/**
	* APP请求检测是否有安装插件
	*/
	public function index(){
		return json_encode(['status'=>1]);
	}
	/**
     * 域名
     */
    public function domain(){
    	if(!empty(WSTConf('WST_ADDONS.aliyunoss'))){
            return WSTConf('CONF.resourcePath').'/';
        }
        return url('/','','',true);
    }
	/**
	 * 砍价商品列表查询
	 */
    public function bargainListQuery(){
		$m = new M();
    	$rs = $m->pageQuery();
    	if(!empty($rs['data'])){
    		foreach ($rs['data'] as $key =>$v){
    			$rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
    			$rs['data'][$key]['goodsName'] = htmlspecialchars_decode($v['goodsName']);
    		}
    	}
		// 域名
		$rs['domain'] = $this->domain();
    	return json_encode(WSTReturn('ok',1,$rs));
    }
    /**
    * 砍价介绍【app端】
    */
    public function getBargainIntro(){
    	$m = new M();
		$userId = model('app/index')->getUserId();
    	$rs = $m->getBySale((int)input('id'),$userId);
    	$goodsDesc = htmlspecialchars_decode($rs['goodsDesc']);
    	$code = "<!DOCTYPE html><html><body>
    			<style>img{width:100%;height:100%;}html{font-size:90%}</style>
    			{$goodsDesc}
                <script>window.onload=function(){window.location.hash = 1;document.title = document.body.clientHeight;}</script>
                </body></html>";
        return $this->display($code);
    }
	/**
	* 砍价商品详情
	*/
	public function getBargainDetail(){
    	$m = new M();
        $userId = model('app/index')->getUserId();
    	$bargainId = input('id/d',0);
        $goods = $m->getBySale($bargainId,$userId);
        // 找不到商品记录
        if(empty($goods))return json_encode(WSTReturn('未找到商品记录',-1));
        // 删除无用字段
        WSTUnset($goods,'goodsSn,goodsDesc,productNo,isSale,isBest,isHot,isNew,isRecom,goodsCatIdPath,goodsCatId,shopCatId1,shopCatId2,brandId,goodsStatus,saleTime,goodsSeoKeywords,illegalRemarks,dataFlag,createTime,read');

		$goods['goodsName'] = htmlspecialchars_decode($goods['goodsName']);

		$bargainUserId = (int)base64_decode(input('bargainUserId'));
		$goods['bargainUserId'] = $bargainUserId;
		
		//砍价个人信息
		$user = ['userType'=>0];
		$user['bargainType'] = 0;
		if($userId>0 || $bargainUserId>0){
			$userIds = ($bargainUserId>0)?$bargainUserId:$userId;
			$u = model('common/users');
			$user = $u->getById($userIds);
			$user['bargain'] = $m->checkBargain($userIds,$bargainId);
			$user['bargainType']  = (empty($user['bargain']))?0:1;
			$user['userType'] = ($bargainUserId>0)?1:0;
			//标识
			if($userId>0)$subscribe = 1;
		}else{
			$subscribe = 2;
			$userIds = 0;
		}
		$goods['signType'] = $subscribe;
		$goods['user'] = $user;


		//分享信息
        $url = addon_url('bargain://goods/shareBargain',array('id'=>$bargainId,'bargainUserId'=>base64_encode($userIds)),true,true);
        $shareInfo['url'] = $url;
        $shareInfo['title'] = $goods['goodsName'];
        $shareInfo['desc'] = $goods['shareExplain'];
        $shareInfo['goodsImg'] = WSTDomain()."/".WSTImg($goods['goodsImg']);
        $goods['shareInfo'] = $shareInfo;
		// dump($goods);die;
		return json_encode(WSTReturn('请求成功',1,$goods));
	}
	/**
	 * 亲友团出刀
	 */
	public function helpsList(){
		$m = new M();
		$userId = model('app/index')->getUserId();
		$bargainUserId = (int)base64_decode(input('bargainUserId'));
		$rs = $m->helpsList($userId,$bargainUserId);
		return json_encode(WSTReturn('ok',1,$rs));
	}


	/**
	 * 第一刀
	 */
	public function firstKnife(){
		$m = new M();
		$userId = model('app/index')->getUserId();
		if($userId>0){
			$user = model('app/users')->getFieldsById($userId,['wxOpenId','userName','loginName','userPhoto']);
			$user['wxOpenId'] = empty($user['wxOpenId'])?'':$user['wxOpenId'];
			session('WST_USER',$user);
		}else{
			return json_encode(WSTReturn('你还未登录~',-999));
		}

		$rs = $m->firstKnife($userId);
		session('WST_USER',null);
		return json_encode($rs);
	}
	/**
	 * 补刀
	 */
	public function addKnife(){
		$m = new M();
		$userId = model('app/index')->getUserId();
		$rs = $m->addKnife($userId);
		return json_encode($rs);
	}



	/**
	 * 去下单
	 */
	public function addCart(){
		$userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
		$m = new Carts();
		return json_encode($m->addCart($userId));
	}
	/**
	 * 下单
	 */
	public function submit(){
		$userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
        $orderSrc = input('orderSrc');
		$orderSrcArr = ['android'=>3,'ios'=>4];
		if(!isset($orderSrcArr[$orderSrc])){
			return json_encode(WSTReturn('非法订单来源~',-1));
		}
		$orderSrc = $orderSrcArr[$orderSrc];
		$m = new Carts();
		$rs = $m->submit($orderSrc,$userId);
		return json_encode($rs);
	}
	/**
	 * 计算运费、积分和总商品价格
	 */
	public function getCartMoney(){
		$userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
		$m = new Carts();
		$data = $m->getCartMoney($userId);
		return json_encode($data);
	}
	/**
	 * 结算页面
	 */
	public function settlement(){
		$CARTS = session('BARGAIN_CARTS');
		if(empty($CARTS)){
			return json_encode(WSTReturn('暂无砍价商品结算~',-1));
            exit;
		}
		$userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
		$m = new Carts();
		$carts = $m->getCarts($userId);


		//获取一个用户地址
		$addressId = (int)input('addressId');
		$ua = model('common/userAddress');
		if($addressId>0){
			$userAddress = $ua->getById($addressId,$userId);
		}else{
			$userAddress = $ua->getDefaultAddress($userId);
		}
		$carts['userAddress'] = $userAddress;
		//获取用户积分
		$user = model('common/users')->getFieldsById($userId,'userScore');
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
		//获取支付方式
		$onlineType = ($carts['goodsType']==1)?1:-1;
		$payments = model('common/payments')->getByGroup('4',$onlineType,true);
		$carts['payments'] = $payments;
		// 是否开启积分支付
        $carts['isOpenScorePay'] = WSTConf('CONF.isOpenScorePay');
        return json_encode(WSTReturn('ok',1,$carts));

	}

	/**
	* 我参与的砍价
	*/
	public function pageQuery(){
		$m = new M();
		$userId = model('app/index')->getUserId();
		if($userId>0){
			$rs = $m->pageQueryByUser($userId);
			foreach($rs['data']['data'] as $k=>$v){
				$rs['data']['data'][$k]['goodsImg'] =  wstImg($v['goodsImg'], 3);
				$rs['data']['data'][$k]['goodsName'] = htmlspecialchars_decode($v['goodsName']);
			}
			return json_encode($rs);
		}
		return json_encode(WSTReturn('你还未登录~',-999));
	}
	
}