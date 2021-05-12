<?php
namespace wstmart\wechat\controller;
use think\Db;
use wstmart\common\model\GoodsCats;
use wstmart\wechat\model\Goods;
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
     * 店铺街
     */
    public function shopStreet(){
    	$gc = new GoodsCats();
    	$goodsCats = $gc->listQuery(0);
    	$this->assign('goodscats',$goodsCats);
    	$this->assign("keyword", input('keyword'));
        if(WSTConf('CONF.mapKey')!=''){
            $we = WSTWechat();
            $datawx = $we->getJsSignature(request()->scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            $this->assign("datawx", $datawx);
        }
		$this->assign("type", input('type'));
    	return $this->fetch('shop_street');
    }
    /**
     * 店铺首页
     */
    public function index(){
        $s = model('shops');
        $shopId = (int)input('shopId',1);
        $data = [];
        $data['shop'] = $s->getShopInfo($shopId);
		$data['shopcats'] = model('ShopCats','model')->getShopCats($shopId);
        $this->assign('data',$data);
        $this->assign("goodsName", input('goodsName'));
        $this->assign('ct1',(int)input("param.ct1/d",0));//一级分类
        $this->assign('ct2',(int)input("param.ct2/d",0));//二级分类
        $this->assign('shopId',$shopId);//店铺id
        return $this->fetch($data['shop']["wechatShopHomeTheme"]);
    }
    /**
    * 店铺详情
    */
    public function view(){
        $s = model('shops');
        $shopId = (int)input("param.shopId/d",1);
        $data = [];
        $data['shop'] = $s->getShopInfo($shopId);
        $this->assign('data',$data);
        $cart = model('carts')->getCartInfo();
        $this->assign('cart',$cart);
        return $this->fetch('shop_view');
    }
    /**
    * 店铺商品列表
    */
    public function goods(){
        $shopId = (int)input("param.shopId/d",1);
        $s = model('shops');
        $data = [];
        $data['shop'] = $s->getShopInfo($shopId);
        $goodsName = input("param.goodsName");
        $this->assign('shopId',$shopId);//店铺id
        $this->assign('ct1',(int)input("param.ct1/d",0));//一级分类
        $this->assign('ct2',(int)input("param.ct2/d",0));//二级分类
        $this->assign('goodsName',urldecode($goodsName));//搜索
        $this->assign('data',$data);
        return $this->fetch('shop_goods_list');
    }
    /**
    * 获取店铺商品
    */
    public function getShopGoods(){
        $shopId = (int)input('shopId',1);
        $g = model('goods');
        $rs = $g->shopGoods($shopId);
        foreach($rs['data'] as $k=>$v){
            $rs['data'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
        }
        return $rs;
    }

    public function getFloorData(){
        $s = model('shops');
        $rs = $s->getFloorData();
        if(isset($rs['goods'])){
            foreach($rs['goods'] as $k=>$v){
                $rs['goods'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
            }
        }
        return $rs;
    }

    /**
     * 店铺街列表
     */
    public function pageQuery(){
    	$m = model('shops');
    	$rs = $m->pageQuery(input('pagesize/d'));
    	foreach ($rs['data'] as $key =>$v){
    		$rs['data'][$key]['shopImg'] = WSTImg($v['shopImg'],3,'shopLogo');
    	}
    	return $rs;
    }

}
