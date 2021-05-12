<?php
namespace wstmart\weapp\controller;
use wstmart\common\model\GoodsCats;
use wstmart\common\model\Tags;
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
 * 门店控制器
 */
class Shops extends Base{
    /**
     * 店铺街头部: 广告及商品分类
     */
    public function shopStreet(){
    	$gc = new GoodsCats();
        $goodscats = $gc->listQuery(0);
        foreach ($goodscats as $k => $v) {
            $_this = $goodscats[$k];
            // 删除无用字段
            unset(
                $_this['parentId'],
                $_this['isShow'],
                $_this['isFloor'],
                $_this['catSort'],
                $_this['dataFlag'],
                $_this['createTime'],
                $_this['commissionRate']);
            
        }
    	$data['goodscats'] =  $goodscats;
    	$data['keyword'] = urldecode(input('keyword'));
    	$ta = new Tags();
        $swiper = $ta->listAds('app-ads-street',4);
        foreach ($swiper as $k1 => $v1) {
            WSTAllow($swiper[$k1],'adFile');
        }
    	$data['swiper'] = $swiper;
    	echo jsonReturn('店铺数据请求成功',1,$data);
    	die;
    }
    /**
     * 店铺首页
     */
    public function index(){
        $userId = model('weapp/index')->getUserId();
        $s = model('shops');
        $shopId = (int)input('shopId',1);
        $data = [];
        $data['shop'] = $s->getShopInfo($shopId,$userId);
        $data['shopAdtop'] = WSTConf('CONF.shopAdtop');
        echo jsonReturn('店铺数据请求成功',1,$data);
        die;
    }
    /**
    * 店铺详情
    */
    public function home(){
        $userId = model('weapp/index')->getUserId();
        $s = model('shops');
        $shopId = (int)input("param.shopId/d",1);
        $data['shop'] = $s->getShopInfo($shopId,$userId);
        $data['shopcats'] = model('ShopCats')->getShopCats($shopId);

        $ct1 = input("param.ct1/d",0);
        $ct2 = input("param.ct2/d",0);
        $goodsName = input("param.goodsName");
        /*搜索数据*/
        $data['ct1'] = $ct1;//一级分类
        $data['ct2'] = $ct2;//二级分类
        $data['goodsName'] = urldecode($goodsName);//搜索

        // 是否已关注
        $data['isFavor'] = $data['shop']['isfollow'];
        $data['followNum'] = $data['shop']['followNum'];
        $_rec = ['recom'=>[],'new'=>[],'hot'=>[],'best'=>[]];
        $resArr = ['recom','new','hot','best'];
        foreach($resArr as $key => $var){
            // 店主推荐
            $rec = model('Tags')->listShopGoods($var,$shopId,0,4);
            foreach($rec as $k=>$v){
                $_rec[$var][$k]['goodsId'] = $v['goodsId'];
                $_rec[$var][$k]['goodsName'] = $v['goodsName'];
                $_rec[$var][$k]['shopPrice'] = $v['shopPrice'];
                $_rec[$var][$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
            }

        }
        $data['rec'] = $_rec;
        $carts = model('carts')->getCartInfo();
        unset(
                $data['shop']['shopAddress'],
                $data['shop']['shopQQ'],
                $data['shop']['shopWangWang'],
                $data['shop']['serviceStartTime'],
                $data['shop']['serviceEndTime'],
                $data['shop']['catshops'],
                $data['shop']['shopTitle'],
                $data['shop']['shopDesc'],
                $data['shop']['shopKeywords'],
                $carts['list']);
        $data['shop']['shopAdtop'] = WSTConf('CONF.shopAdtop');
        $data['carts'] = $carts;
        echo jsonReturn('店铺数据请求成功',1,$data);
        die;
    }
    /**
    * 获取店铺商品
    */
    public function getShopGoods(){
        $shopId = (int)input('shopId',1);
        $g = model('goods');
        $rs = $g->shopGoods($shopId);
        if(empty($rs['data']))return jsonReturn('没有相关商品',-1);
        foreach($rs['data'] as $k=>$v){
            $rs['data'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
        }
        // 购物车信息
        $carts = model('carts')->getCartInfo();
        // 删除无用字段
        unset($carts['list']);
        $data['carts'] = $carts;
        return jsonReturn('请求成功',1,$rs);
    }

    /**
    * 店铺数据
    */
    public function selfShop(){
        $userId = model('weapp/index')->getUserId();
        $s = model('shops');
        $num = input('num');
        $shopId  = input('shopId');
        $data['shop'] = $s->getShopInfo($shopId,$userId);
        $data['shopcats'] = model('ShopCats')->getShopCats($shopId);
        if(empty($data['shop']))return jsonReturn('暂无店铺数据',-1);
        // 删除无用字段
        unset(
                $data['shop']['shopAddress'],
                $data['shop']['shopQQ'],
                $data['shop']['shopWangWang'],
                $data['shop']['serviceStartTime'],
                $data['shop']['serviceEndTime'],
                $data['shop']['catshops'],
                $data['shop']['shopTitle'],
                $data['shop']['shopDesc'],
                $data['shop']['shopKeywords']
            );
       
        if($num == 1){
        	//当请求商品列表时，只返回分类
        	
        }else{
        	 // 店长推荐
             $data['rec'] = model('Tags')->listShopGoods("recom",$shopId,0,10);
             // 热销商品
             $data['hot'] = model('Tags')->listShopGoods("hot",$shopId,0,10);
        }
        $data['shop']['shopAdtop'] = WSTConf('CONF.shopAdtop');
        echo jsonReturn('店铺数据请求成功',1,$data);
        die;
    }
    public function getFloorData(){
        $s = model('shops');
        $rs = $s->getFloorData();
        if(isset($rs['goods'])){
            foreach($rs['goods'] as $k=>$v){
                $rs['goods'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
            }
        }
        echo jsonReturn('success',1,$rs);
    }

    /**
     * 店铺街列表
     */
    public function pageQuery(){
    	$m = model('shops');
    	$rs = $m->pageQuery(input('pagesize/d'));
    	foreach ($rs['data'] as $key =>$v){
    		$rs['data'][$key]['shopImg'] = WSTImg($v['shopImg'],3,'shopLogo');
            // 删除无用字段
            unset(
                    $rs['data'][$key]['areaId'],
                    $rs['data'][$key]['areaIdPath'],
                    $rs['data'][$key]['timeUsers'],
                    $rs['data'][$key]['timeScore'],
                    $rs['data'][$key]['serviceUsers'],
                    $rs['data'][$key]['serviceScore'],
                    $rs['data'][$key]['goodsUsers'],
                    $rs['data'][$key]['goodsScore'],
                    $rs['data'][$key]['totalUsers'],
                    $rs['data'][$key]['shopCompany']);
            $_rec = [];
            foreach ($rs['data'][$key]['rec'] as $k1 => $v1) {
                $_rec[$k1]['goodsId'] = $v1['goodsId'];
                $_rec[$k1]['goodsName'] = $v1['goodsName'];
                $_rec[$k1]['shopPrice'] = $v1['shopPrice'];
                $_rec[$k1]['goodsImg'] = WSTImg($v1['goodsImg'],3,'goodsLogo');
            }
            $rs['data'][$key]['rec'] = $_rec;
    	}
    	echo jsonReturn('数据请求成功',1,$rs);
    	die;
    }

	public function getShopCats(){
		$sm = model("common/ShopCats");
		$shopId = (int)input('shopId');
		$rs = $sm->listQuery($shopId,0);
		echo jsonReturn('success',1,$rs);
	}

	public function createGoods(){
		$params = input();
		$rtn = verifySinature($params);
		if($rtn['status'] == -1)
			return jsonReturn($rtn['msg'],-1);
		if(empty($params['shopId']))
			return jsonReturn('参数不完整',-1);
		$m = model('common/Goods');
		$r = $m->add($params['shopId']);
		if($r['status'] == 1)
			echo jsonReturn('商品创建成功',1,$r['data']);
		else echo jsonReturn($r['msg'],$r['status']);
	}

	public function getShopApplyCats(){
		$sm = model("common/Goods");
		$shopId = (int)input('shopId/d');
		if($shopId==0)
			echo jsonReturn('参数不正确',-1);
		$rs = WSTShopApplyGoodsCats(0,$shopId);
		echo jsonReturn('success',1,$rs);
	}
}
