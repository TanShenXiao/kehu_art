<?php
namespace wstmart\mobile\controller;
use think\Db;
use wstmart\common\model\GoodsCats;
use wstmart\mobile\model\Goods;
use wstmart\mobile\model\Index as M;
use wstmart\common\model\Articles;

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

        $m = model('shops');
    	$tjlist = $m->tjList();
        
    	$this->assign('goodscats',$goodsCats);
    	$this->assign("keyword", input('keyword'));
		$this->assign("type", input('type'));
		$this->assign("tjlist", $tjlist);
    	return $this->fetch('shop_street');
    }

    /**
     *保存流程表单信息
     */
    public function saveStep(){
        $data = input('post.');
        return model('shops')->saveStep($data);
    }

    /**
     * 作品征集
     */
    public function artCollect(){
        $articleId = input('articleId') ?: 424;
    	$gc = new Articles();
    	$goodsCats = $gc->getNewsByMyId($articleId);
        
        $m = model('shops');
    	$tjlist = $m->tjList();
        
    	$this->assign('goodscats',$goodsCats);
    	$this->assign("keyword", input('keyword'));
		$this->assign("type", input('type'));
		$this->assign("tjlist", $tjlist);
        $m = new M();
    	hook('mobileControllerIndexIndex',['getParams'=>input()]);
    	$news = $m->getSysMsg('msg');
    	$this->assign('news',$news);
    	$ads['count'] =  count(model("common/Tags")->listAds("mo-ads-index",99,86400));
    	$ads['width'] = 'width:'.$ads['count'].'00%';
    	$this->assign("ads", $ads);
    	// 首页自定义页面
        $pageId = $this->getCustomPagesSetting();
        $customPageTitle = $m->getCustomPageTitle($pageId);
    	$this->assign("pageId", $pageId);
    	$this->assign("customPageTitle", $customPageTitle);

    	return $this->fetch('art_collect');
    }

    /**
     * 艺术服务
     */
    public function artService(){
    	$gc = new Articles();
    	$goodsCats = $gc->getMyArticles(402);
        $m = model('shops');
    	$tjlist = $m->tjList();
        
    	$this->assign('goodscats',$goodsCats);
    	$this->assign("keyword", input('keyword'));
		$this->assign("type", input('type'));
		$this->assign("tjlist", $tjlist);
        $m = new M();
    	hook('mobileControllerIndexIndex',['getParams'=>input()]);
    	$news = $m->getSysMsg('msg');
    	$this->assign('news',$news);
    	$ads['count'] =  count(model("common/Tags")->listAds("mo-ads-index",99,86400));
    	$ads['width'] = 'width:'.$ads['count'].'00%';
    	$this->assign("ads", $ads);
    	// 首页自定义页面
        $pageId = $this->getCustomPagesSetting();
        $customPageTitle = $m->getCustomPageTitle($pageId);
    	$this->assign("pageId", $pageId);
    	$this->assign("customPageTitle", $customPageTitle);

    	return $this->fetch('art_service');
    }

    /*
     * 获取商城是否开启首页自定义页面功能
     */
    public function getCustomPagesSetting(){
        $m = new M();
        $pageId = $m->getCustomPagesSetting();
        if(!$pageId)$pageId = 0;
        return $pageId;
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
        $g = model('goods');
    	$data['list'] = $g->shopGoods($shopId);
		$data['goodsNum'] = $data['list']['goodsNum'];
        
        $this->assign('data',$data);
        $this->assign("goodsName", input('goodsName'));
        $this->assign('ct1',(int)input("param.ct1/d",0));//一级分类
        $this->assign('ct2',(int)input("param.ct2/d",0));//二级分类
        $this->assign('shopId',$shopId);//店铺id

        return $this->fetch($data['shop']["mobileShopHomeTheme"]);
    }

    /**
     * 店铺详情
     */
    public function index2(){

        $shopId = (int)input("param.shopId/d");
        $s = model('RecommendedArtists');
        $data['shop'] = $s->getById($shopId);
        if(empty($data['shop'])){
            return $this->fetch('error_lost');
        }
        $data['shopcats'] = model('ShopCats','model')->getShopCats($shopId);
        $g = model('goods');
        $data['list'] = $g->shopGoods($shopId);
        $data['goodsNum'] = $data['list']['goodsNum'];

        //关注
        $f = model('common/Favorites');
        $data['isfollow'] = $f->checkFavorite($shopId,1);

        $this->assign('data',$data);
        $this->assign("goodsName", input('goodsName'));
        $this->assign('ct1',(int)input("param.ct1/d",0));//一级分类
        $this->assign('ct2',(int)input("param.ct2/d",0));//二级分类
        $this->assign('shopId',$shopId);//店铺id
        return $this->fetch("shop_home2");
    }

    /**
     * 店铺 作家部分详情
     */
    public function index2_detail(){

        $shopId = (int)input("param.shopId/d");
        $type = input("param.type",'analysis');
        if(!in_array($type,['analysis','story'])){
            return $this->fetch('error_lost');
        }
        $title = '';
        if($type == 'analysis'){
            $title = '作品分析';
        }
        if($type == 'story'){
            $title = '作品故事';
        }
        $s = model('RecommendedArtists');
        $shop = $s->getById($shopId);
        if(empty($shop)){
            return $this->fetch('error_lost');
        }

        $this->assign('shop',$shop);
        $this->assign('content',$shop[$type]);
        $this->assign('title',$title);

        return $this->fetch("shop_home2_detail");
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
        $s = model('shops');
        $shopId = (int)input("param.shopId/d",1);
        $ct1 = input("param.ct1/d",0);
        $ct2 = input("param.ct2/d",0);
        $goodsName = input("param.goodsName");
        $gcModel = model('ShopCats');
        $data = [];
        $data['shop'] = $s->getShopInfo($shopId);
        $data['shopcats'] = $gcModel->getShopCats($shopId);
        $this->assign('shopId',$shopId);//店铺id
        $this->assign('ct1',$ct1);//一级分类
        $this->assign('ct2',$ct2);//二级分类
        $this->assign('goodsName',urldecode($goodsName));//搜索
        $this->assign('data',$data);

        return $this->fetch('shop_goods_list');
    }
    public function home(){
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
