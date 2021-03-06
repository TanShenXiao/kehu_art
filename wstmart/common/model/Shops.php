<?php
namespace wstmart\common\model;
use wstmart\shop\model\ShopConfigs;
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
 * 门店类
 */
class Shops extends Base{
	protected $pk = 'shopId';
    /**
     * 获取商家认证
     */
    public function shopAccreds($shopId){
        $accreds= Db::table("__SHOP_ACCREDS__")->alias('sa')
        ->join('__ACCREDS__ a','a.accredId=sa.accredId','left')
        ->field('a.accredName,a.accredImg')
        ->where(['sa.shopId'=> $shopId])
        ->select();
        return $accreds;
    }

    /**
     * 获取店铺评分
     */
    public function getShopScore($shopId){
        $shop = $this->alias('s')->join('__SHOP_SCORES__ cs','cs.shopId = s.shopId','left')
                    ->where(['s.shopId'=>$shopId,'s.shopStatus'=>1,'s.dataFlag'=>1])->field('s.userId,s.shopAddress,s.shopKeeper,s.shopImg,s.shopQQ,s.shopId,s.shopName,s.shopTel,s.freight,s.freight,s.areaId,cs.*')->find();
        if(empty($shop))return [];
        $shop->toArray();
        $shop['totalScore'] = WSTScore($shop['totalScore']/3,$shop['totalUsers']);
        $shop['goodsScore'] = WSTScore($shop['goodsScore'],$shop['goodsUsers']);
        $shop['serviceScore'] = WSTScore($shop['serviceScore'],$shop['serviceUsers']);
        $shop['timeScore'] = WSTScore($shop['timeScore'],$shop['timeUsers']);
        WSTUnset($shop, 'totalUsers,goodsUsers,serviceUsers,timeUsers');
        return $shop;
    }
    /**
     * 获取店铺首页信息
     */
    public function getShopInfo($shopId,$uId = 0){
    	$rs = Db::name('shops')->where(['shopId'=>$shopId,'shopStatus'=>1,'dataFlag'=>1])
    	->field('shopNotice,shopId,shopImg,shopName,shopAddress,shopQQ,shopWangWang,shopTel,serviceStartTime,longitude,latitude,serviceEndTime,shopKeeper,mapLevel,areaId,isInvoice,freight,invoiceRemarks,shopBrief,userId')
    	->find();
    	if(empty($rs)){
    		//如果没有传id就获取自营店铺
    		$rs = Db::name('shops')->where(['shopStatus'=>1,'dataFlag'=>1,'isSelf'=>1])
    		->field('shopNotice,shopId,shopImg,shopName,shopAddress,shopQQ,shopWangWang,shopTel,serviceStartTime,longitude,latitude,serviceEndTime,shopKeeper,mapLevel,areaId,isInvoice,freight,invoiceRemarks,shopBrief,userId')
    		->find();
    		if(empty($rs))return [];
    		$shopId = $rs['shopId'];
    	}
    	//评分
    	$score = $this->getShopScore($rs['shopId']);
    	$rs['scores'] = $score;
        //店铺地址
        $rs['areas'] = Db::name('areas')->alias('a')->join('__AREAS__ a1','a1.areaId=a.parentId','left')
        ->where([['a.areaId','=',$rs['areaId']]])->field('a.areaId,a.areaName areaName2,a1.areaName areaName1')->find();
    	//认证
    	$accreds = $this->shopAccreds($rs['shopId']);
    	$rs['accreds'] = $accreds;
    	//分类
    	$goodsCatMap = [];
    	$goodsCats = Db::name('cat_shops')->alias('cs')->join('__GOODS_CATS__ gc','cs.catId=gc.catId and gc.dataFlag=1','left')
    	->where(['shopId'=>$rs['shopId']])->field('cs.shopId,gc.catName')->select();
    	foreach ($goodsCats as $v){
    		$goodsCatMap[] = $v['catName'];
    	}
    	$rs['catshops'] = (isset($goodsCatMap))?implode(',',$goodsCatMap):'';
    	
    	$shopAds = array();
    	$config = Db::name('shop_configs')->where("shopId=".$rs['shopId'])->find();
    	//取出轮播广告
    	if($config["shopAds"]!=''){
    		$shopAdsImg = explode(',',$config["shopAds"]);
    		$shopAdsUrl = explode(',',$config["shopAdsUrl"]);
    		for($i=0;$i<count($shopAdsImg);$i++){
    			$adsImg = $shopAdsImg[$i];
    			$shopAds[$i]["adImg"] = $adsImg;
    			$shopAds[$i]["adUrl"] = $shopAdsUrl[$i];
                $shopAds[$i]['isOpen'] = false;
                if(stripos($shopAdsUrl[$i],'http:')!== false || stripos($shopAdsUrl[$i],'https:')!== false){
                    $shopAds[$i]['isOpen'] = true;
                }
    		}
            $rs['shopAds'] = $shopAds;
            unset($config['shopAds']);
    	}
        $rs = array_merge($rs,$config);
        //热搜关键词
        $rs['shopHotWords'] = ($rs['shopHotWords']!='')?explode(',',$rs['shopHotWords']):[];
		$rs['shopStreetImg'] = $config["shopStreetImg"];
    	//关注
    	$f = model('common/Favorites');
    	$rs['isfollow'] = $f->checkFavorite($shopId,1,$uId);
        $followNum = $f->followNum($shopId,1);
        $rs['followNum'] = $followNum;
    	return $rs;
    }

    /**
     * 获取店铺信息
     */
    public function getFieldsById($shopId,$fields){
        return $this->where(['shopId'=>$shopId,'dataFlag'=>1])->field($fields)->find();
    }
     public function getCatShopInfo($sId=0){
        $shopId = ($sId>0)?$sId:(int)session('WST_USER.shopId');
        //获取经营范围
        $trade = Db::name("trades t")->join("shops s","s.tradeId=t.tradeId","inner")
            ->field("s.tradeId,t.tradeFee")
            ->where(['s.shopId'=>$shopId])
            ->find();
        $rs['needPay'] = $trade['tradeFee'];
        $rs['tradeId'] = $trade['tradeId'];
        return $rs;
    }
    /*
     * 商家续费
     */
    public function renew($uId=0,$sId=0){
        $userId = ($uId>0)?$uId:(int)session('WST_USER.userId');
        $shopId = ($sId>0)?$sId:(int)session('WST_USER.shopId');
        $shops = $this->getCatShopInfo($shopId);
        if((int)$shops['needPay']>0)return WSTReturn('请缴纳年费');
        Db::startTrans();
        try{
            $shopExpireDate = $this->where(['userId'=>$userId])->value('expireDate');
            $shopData = [];
            $newExpireDate = date('Y-m-d',strtotime("$shopExpireDate +1 year"));
            $shopData['expireDate'] = $newExpireDate;
            $shopRes = $this->where(['userId'=>$userId])->update($shopData);
            $fee = [];
            $fee['userId'] = $userId;
            $fee['shopId'] = $shopId;
            $fee['money'] = 0;
            $fee['remark'] = "店铺缴纳年费";
            $fee['startDate'] = date('Y-m-d');
            $fee['endDate'] = date('Y-m-d',strtotime("$shopExpireDate +1 year"));;
            $fee['createTime'] = date('Y-m-d H:i:s');
            $result = Db::name('shop_fees')->insert($fee);
            if(false !== $shopRes && false !== $result){
                session('WST_USER.expireDate',$newExpireDate);
                Db::commit();
                return WSTReturn('续费成功',1);
            }
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('续费失败');
        }
    }
}
