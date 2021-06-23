<?php
namespace wstmart\home\controller;
use think\Db;
use wstmart\home\model\Goods;
use wstmart\common\model\GoodsCats;
use wstmart\home\validate\Shops as Validate;
use think\Loader;
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
    protected $beforeActionList = [
          'checkAuth'=>['only'=>'join,joinstep1,joinstep2,savestep2,joinstep3,savestep3,joinstep4,savestep4,joinstep5,savestep5,joinsuccess']
    ];
    
    /**
     * 店铺街
     */
    public function shopStreet(){
    	$g = new GoodsCats();
    	$goodsCats = $g->listQuery(0);
    	$this->assign('goodscats',$goodsCats);
    	//店铺街列表
    	$s = model('shops');
    	$pagesize = 10;
    	$selectedId = input("get.id/d");
    	$this->assign('selectedId',$selectedId);
    	$list = $s->pageQuery($pagesize);
    	$this->assign('list',$list);
    	$this->assign('keyword',input('keyword'));
    	$this->assign('keytype',1);
    	return $this->fetch('shop_street');
    }
    /**
     * 店铺详情
     */
    public function index(){
    	$shopId = (int)input("param.shopId/d");
        hook("homeBeforeGoShopHome",["shopId"=>$shopId,"params"=>input()]);
    	$s = model('shops');
    	$data['shop'] = $s->getShopInfo($shopId);
    	if(empty($data['shop']))return $this->fetch('error_lost');
		$data['shopcats'] = model('ShopCats','model')->getShopCats($shopId);
    	$g = model('goods');
    	$data['list'] = $g->shopGoods($shopId);
		$data['goodsNum'] = $data['list']['goodsNum'];

        //关注
        $f = model('common/Favorites');
        $data['isfollow'] = $f->checkFavorite($shopId,1);

    	$this->assign('data',$data);
        $this->assign('msort',(int)input("param.msort",0));//筛选条件
        $this->assign('mdesc',(int)input("param.mdesc",1));//升降序
        $this->assign('sprice',input("param.sprice"));//价格范围
        $this->assign('eprice',input("param.eprice"));
        $this->assign('ct1',(int)input("param.ct1",0));//一级分类
        $this->assign('ct2',(int)input("param.ct2",0));//二级分类
		//hook("homeBeforeGoShopHomeTpl",["shopId"=>$shopId,"obj"=>$this,"type"=>0]);
    	return $this->fetch($data['shop']["shopHomeTheme"]);
    }
    
    /**
     * 店铺分类
     */
    public function goods(){
    	$s = model('shops');
    	$shopId = (int)input("param.shopId/d");
    	$data['shop'] = $s->getShopInfo($shopId);
    	$ct1 = input("param.ct1/d",0);
    	$ct2 = input("param.ct2/d",0);
    	$goodsName = input("param.goodsName");
    	if(empty($data['shop']))return $this->fetch('error_lost');
    	$g = model('goods');
    	$data['list'] = $g->shopGoods($shopId);
		$data['goodsNum'] = $data['list']['goodsNum'];
    	$this->assign('msort',input("param.msort/d",0));//筛选条件
    	$this->assign('mdesc',input("param.mdesc/d",1));//升降序
    	$this->assign('sprice',input("param.sprice"));//价格范围
    	$this->assign('eprice',input("param.eprice"));
    	$this->assign('ct1',$ct1);//一级分类
    	$this->assign('ct2',$ct2);//二级分类
    	$this->assign('goodsName',urldecode($goodsName));//搜索
    	$this->assign('data',$data);
    	return $this->fetch('shop_goods_list');
    }

    /**
     * 跳去商家入驻
     */
    public function join(){
        $rs = model('shops')->checkApply();
        $this->assign('isApply',(!empty($rs) && $rs['applyStatus']>=1)?1:0);
        $this->assign('applyStep',empty($rs)?1:$rs['applyStep']);
        $articles = model('Articles')->getArticlesByCat(53);
        $this->assign('artiles',$articles);
        $shopFlows = model('shops')->getShopFlows();
        $flowId = $shopFlows[0]['flowId'];
        session('tmpShopApplyFlow',$shopFlows);
        $tmpApplyStep = (int)session('tmpApplyStep');
        if($tmpApplyStep==0)session('tmpApplyStep',$flowId);
		$shopType = (int)input('type/d',0);
		$this->assign('shopType',$shopType);
        $this->assign('flowId',$flowId);
        return $this->fetch('shop_join');
    }

    /**
     * 检测有步骤有没有遗漏，不允许跳过步骤
     */
    public function checkStep($flowId){
        $tmpShopApplyFlow = session('tmpShopApplyFlow');
        $tmpApplyStep = (int)session('tmpApplyStep');
        //如果没有建立数组则强制重新开始
        if(!$tmpShopApplyFlow){
            return $this->redirect(Url('home/shops/join'));
        }
        $flowSteps = [];
        $isFind = false;
        foreach ($tmpShopApplyFlow as $key => $v) {
            $flowSteps[] = $v['flowId'];
            if($v['flowId']==$tmpApplyStep){
                $isFind = true;
                break;
            }
        }
        //没找到这个环节强制重新开始
        if(!$isFind){
            $this->redirect(Url('home/shops/joinStepNext',array('id'=>$tmpShopApplyFlow[0]['flowId'])));
            exit();
        }
        //如果找到则判断是否当前环节是否有效
        if(!in_array($flowId,$flowSteps)){
            $flowId = end($flowSteps);
            $this->redirect(Url('home/shops/joinStepNext',array('id'=>$flowId)));
            exit();
        }
    }
    /**
     * 申请流程页面
     */
    public function joinStepNext(){
        $flowId = (int)input('id');
        $this->checkStep($flowId);
        $shopFlows = model('shops')->getShopFlowDatas($flowId);
        $shopStep = $shopFlows['currStep'];
        $stepFields = model('shops')->getFlowFieldsById($flowId);
		$type = (int)input('type/d',0);
		$this->assign('shopType',$type);
        $this->assign('shopFlows',$shopFlows['flows']);
        $this->assign('flowId',$flowId);
        $this->assign('prevStep',$shopFlows['prevStep']);
        $this->assign('currStep',$shopFlows['currStep']);
        $this->assign('nextStep',$shopFlows['nextStep']);
        $this->assign('stepFields',$stepFields);
        $apply = model('shops')->getShopApply();
        $this->assign('apply',$apply);
        return $this->fetch('shop_join_step');
    }
    /**
     *保存流程表单信息
     */
    public function saveStep(){
        $data = input('post.');
        return model('shops')->saveStep($data);
    }
    /**
     * 入驻进度查询
     */
    public function checkapplystatus(){
        $apply = model('shops')->checkApply();
        if(empty($apply)){
            $flows = model('shops')->getShopFlowDatas();
            session('tmpApplyStep',$flows['currStep']['flowId']);
            $this->redirect(Url('home/shops/joinStepNext',array('id'=>$flows['currStep']['flowId'])));
            exit();
        }else{
            $flows = model('shops')->getShopFlowDatas($apply['applyStep']);
            if($flows['flows'][count($flows['flows'])-2]['flowId']==$apply['applyStep']){
                session('tmpApplyStep',$flows['nextStep']['flowId']);
                $this->redirect(url('home/shops/joinStepNext',array('id'=>$flows['nextStep']['flowId'])));
            }else{
                $this->redirect(url('home/shops/joinStepNext',array('id'=>$apply['applyStep'])));
            }
            exit();
        }
    }
	/**
     * 店铺分类
     */
    public function cat(){
    	$s = model('shops');
    	$shopId = (int)input("param.shopId/d");
    	$data['shop'] = $s->getShopInfo($shopId);
    
    	$ct1 = input("param.ct1/d",0);
    	$ct2 = input("param.ct2/d",0);
    	$goodsName = input("param.goodsName");
    	if(($data['shop']['shopId']==1 || $shopId==0) && $ct1==0 && !isset($goodsName)){
	    	 $params = input();
	    	 unset($params["shopId"]);
	    	 $this->redirect('shops/selfShop',$params);
    	}
    	if(empty($data['shop']))return $this->fetch('error_lost');
    	$data['shopcats'] = $f = model('ShopCats','model')->getShopCats($shopId);
    	$g = model('goods');
    	$data['list'] = $g->shopGoods($shopId);
		$data['goodsNum'] = $data['list']['goodsNum'];
    	$this->assign('msort',input("param.msort/d",0));//筛选条件
    	$this->assign('mdesc',input("param.mdesc/d",1));//升降序
    	$this->assign('sprice',input("param.sprice"));//价格范围
    	$this->assign('eprice',input("param.eprice"));
    	$this->assign('ct1',$ct1);//一级分类
    	$this->assign('ct2',$ct2);//二级分类
    	$this->assign('goodsName',urldecode($goodsName));//搜索
    	$this->assign('data',$data);
    	$this->assign('shopId',$shopId);
    	return $this->fetch('shop_home');
    }
	/**
     * 专题店铺
     */
    public function topicShop(){
    	$shopId = 1;
    	hook("homeBeforeGoShopHome",["shopId"=>$shopId]);
    	
    	$s = model('shops');
    	$data['shop'] = $s->getShopInfo($shopId);
        $ct1 = input("param.ct1/d",0);
        $ct2 = input("param.ct2/d",0);
		$ctName = input("param.ctName");
        $goodsName = input("param.goodsName");
    	if(empty($data['shop']))return $this->fetch('error_lost');
    	$data['shopcats'] = $f = model('ShopCats','model')->getShopCats($shopId);
    	$g = model('goods');
    	$data['list'] = $g->shopGoods($shopId);
		$data['goodsNum'] = sizeof($data['list']['data']);
    	$this->assign('msort',input("param.msort/d",0));//筛选条件
    	$this->assign('mdesc',input("param.mdesc/d",1));//升降序
    	$this->assign('sprice',input("param.sprice"));//价格范围
    	$this->assign('eprice',input("param.eprice"));
    	$this->assign('ct1',$ct1);//一级分类
    	$this->assign('ct2',$ct2);//二级分类
		$this->assign('ctName',$ctName);
    	$this->assign('goodsName',urldecode($goodsName));//搜索
    	$this->assign('data',$data);
    	hook("homeBeforeGoShopHomeTpl",["shopId"=>$shopId,"obj"=>$this]);
    	return $this->fetch('shop_topic');
    }
	//加载模板，供钩子调用
	public function hookFetch($template = '', $vars = [], $config = [])
    {
        return $this->fetch($template, $vars, $config);
    }
}
