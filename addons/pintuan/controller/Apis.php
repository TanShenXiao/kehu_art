<?php
namespace addons\pintuan\controller;

use think\addons\Controller;
use addons\pintuan\model\Pintuans as M;
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
     * 拼团商品列表查询
     */
    public function pintuanListQuery(){
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
    * 拼团商品详情
    */
    public function getPinTuanDetail(){
        $m = new M();
        $userId = model('app/index')->getUserId();
    	$tuanId = input('id/d',0);
        $goods = $m->getBySale($tuanId,$userId);
        // 找不到商品记录
        if(empty($goods))return json_encode(WSTReturn('未找到商品记录',-1));
        // 删除无用字段
        WSTUnset($goods,'goodsSn,goodsDesc,productNo,isSale,isBest,isHot,isNew,isRecom,goodsCatIdPath,goodsCatId,shopCatId1,shopCatId2,brandId,goodsStatus,saleTime,goodsSeoKeywords,illegalRemarks,dataFlag,createTime,read');

        $goods['goodsName'] = htmlspecialchars_decode($goods['goodsName']);
		//分享信息
		$conf = $m->getConfigs();
		$shareInfo['link'] = addon_url('pintuan://goods/wxdetail',
										['id'=>$tuanId,'shareUserId'=>base64_encode($userId)],
										true,
										true);
		$shareInfo['title'] = $goods['goodsName'];
		$shareInfo['desc'] = (isset($conf["goodsShareTitle"]) && $conf["goodsShareTitle"]!="")?$conf["goodsShareTitle"]:WSTConf("CONF.mallSlogan");
		$shareInfo['imgUrl'] = WSTDomain()."/".$goods['goodsImg'];
		$goods['shareInfo'] = $shareInfo;

		// 正在拼团
        $pulist = $m->getPulist();
		$goods['pulist'] = $pulist;
        return json_encode(WSTReturn('请求成功',1,$goods));

    }
    /******************************************************************* 结算页面start ****************************************************************************/
    /**
     * 下单
     * bayNum:
     * id:tuanId
     * tokenId:
     */
    public function addCart(){
        $userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
        $m = new M();
        return json_encode($m->addCart($userId));
    }
    /**
     * 计算运费、积分和总商品价格
     */
    public function getCartMoney(){
        $userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
        $m = new M();
        $data = $m->getCartMoney($userId);
        return json_encode($data);
    }

    /**
     * 提交订单
     */
    public function submit(){
        $userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
        $m = new M();
        $orderSrc = input('orderSrc');
        $orderSrcArr = ['android'=>3,'ios'=>4];
        if(!isset($orderSrcArr[$orderSrc])){
            return json_encode(WSTReturn('非法订单来源~',-1));
        }
        $orderSrc = $orderSrcArr[$orderSrc];
        $rs = $m->submit($orderSrc,$userId);
        return json_encode($rs);
    }
    /**
     * 结算页面
     */
    public function settlement(){
        $CARTS = session('PINTUAN_CARTS');
        if(empty($CARTS)){
            return json_encode(WSTReturn('暂无拼团商品结算~',-1));
            exit;
        }
        $userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
        $m = new M();
        $carts = $m->getCarts();
        if(isset($carts['carts']['goods']) && isset($carts['carts']['goods']['goodsName'])){
            $carts['carts']['goods']['goodsName'] = htmlspecialchars_decode($carts['carts']['goods']['goodsName']);
        }
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
        $carts['domain'] = $this->domain();
        // 是否开启积分支付
        $carts['isOpenScorePay'] = WSTConf('CONF.isOpenScorePay');
        //获取支付方式
        $payments = model('common/payments')->getByGroup('4', 1, true);
        $carts['payments'] = $payments;
        return json_encode(WSTReturn('ok',1,$carts));
    }





    /******************************************************************* 结算页面end ****************************************************************************/

    /******************************************************************* 余额支付start ****************************************************************************/
    /**
	 * 跳去支付页面
	 */
	public function payment(){
		$userId = model('app/index')->getUserId();
		$orderNo = input('orderNo/s');
        $data = [];
        $data['orderNo'] = $orderNo;
        $data['userId'] = $userId;
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
		$m = new M();
		$data = $m->getTuanPay($orderNo,$userId);
		if($data["status"]==1){
			//获取用户钱包
			$user = model('common/users')->getFieldsById($userId,'userMoney,payPwd');
			$data['data']['userMoney'] = $user['userMoney'];
			 // 域名,用于显示图片
	    	$data['data']['domain'] = $this->domain();
	    	return json_encode(WSTReturn('请求成功', 1, $data['data']));
		}else{
			return json_encode(WSTReturn($data["msg"],-1));
	    }
	}

	/**
	 * 钱包支付
	 */
	public function payByWallet(){
		$userId = (int)model('app/index')->getUserId();
		if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
		$m = new M();
		return json_encode($m->payByWallet($userId));
	}
	/****************************************** 订单操作 *******************************************/
	/**
	* 拼团订单列表
	*/
	public function pageQuery(){
		$userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
        $m = new M();
        $rs = $m->pulist($userId);
        if(!empty($rs['data'])){
            foreach ($rs['data'] as $key =>$v){
                $rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
                $rs['data'][$key]['goodsName'] = htmlspecialchars_decode($v['goodsName']);
            }
        }
        return json_encode(WSTReturn('ok',1,$rs));
	}
	/**
     * 取消拼单
     */
    public function toCancel(){
    	$userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
    	$m = new M();
    	$rs = $m->delTuan($userId);
    	return json_encode($rs);
    }
    /**
     * 查看拼团
     */
    public function tuanDetail(){
    	$userId = model('app/index')->getUserId();
        if($userId<=0){
            return json_encode(WSTReturn('您还未登录~',-999));
        }
        $m = new M();
        $tuanNo = input('tuanNo/d',0);
        $tuan = $m->getTuanInfo($tuanNo,$userId);
        $data = [];
        if(!empty($tuan)){
            $goods = $m->getBySale($tuan['tuanId']);
			if(empty($goods)){
                return json_encode(WSTReturn('对不起你要找的拼团商品已下架~~o(>_<)o~~'));
            }
            $goods['goodsName'] = htmlspecialchars_decode($goods['goodsName']);

            $history = cookie("history_goods");
            $history = is_array($history)?$history:[];
            array_unshift($history, (string)$tuan['goodsId']);
            $history = array_values(array_unique($history));
    
            if(!empty($history)){
                cookie("history_goods",$history,25920000);
            }
            $data['tuan'] = $tuan;
            $data['goods'] = $goods;

            
            //分享信息
            $conf = $m->getConfigs();
            $shareInfo['link'] = addon_url('pintuan://goods/wxTuanDetail',
            							  ['tuanNo'=>$tuanNo,
            							   'shareUserId'=>base64_encode((int)$userId)],
            							   true,
            							   true);
            $shareInfo['title'] = $tuan['goodsName'];
            $shareInfo['desc'] = (isset($conf["goodsShareTitle"]) && $conf["goodsShareTitle"]!="")?$conf["goodsShareTitle"]:WSTConf("CONF.mallSlogan");
            $shareInfo['imgUrl'] = WSTDomain()."/".$tuan['goodsImg'];
            $data['shareInfo'] = $shareInfo;
            $data['domain'] = $this->domain();
            return json_encode(WSTReturn('',1,$data));
        }else{
            return json_encode(WSTReturn('对不起你要找的拼团商品已下架~~o(>_<)o~~'));
        }
        
    }
}