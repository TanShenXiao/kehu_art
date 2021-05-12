<?php
namespace addons\guarantee\controller;

use think\addons\Controller;
use addons\guarantee\model\Guarantee as M;
use wstmart\home\model\Attributes as AT;
use think\Db;
/**
 * 保底交易插件
 */
class guarantee extends Controller{
	public function __construct(){
		parent::__construct();
        $m = new M();
        $data = $m->getConf('Guarantee');
        $this->assign("seoGuaranteeKeywords",$data['seoGuaranteeKeywords']);
        $this->assign("seoGuaranteeDesc",$data['seoGuaranteeDesc']);
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	
	/**
	 * 保底交易商品列表
	 */
	public function mlists(){
    	$this->assign("keyword", input('keyword'));
    	$this->assign("catId", input('catId/d'));
    	$this->assign("brandId", input('brandId/d'));
    	return $this->fetch('mobile/goods_list');
	}
	
	/**
	 * 保底交易商品列表
	 */
	public function wlists(){
    	$this->assign("keyword", input('keyword'));
    	$this->assign("catId", input('catId/d'));
    	$this->assign("brandId", input('brandId/d'));
    	return $this->fetch('wechat/goods_list');
	}
	
	/**
	 * 保底交易商品列表
	 */
	public function lists(){
    	$catId = Input('cat/d');
    	$goodsCatIds = model('home/GoodsCats')->getParentIs($catId);
    	reset($goodsCatIds);
    	//填充参数
    	$data = [];
    	$data['catId'] = $catId;
    	$data['isStock'] = Input('isStock/d');
    	$data['isNew'] = Input('isNew/d');
        $data['isFreeShipping'] = input('isFreeShipping/d');
    	$data['orderBy'] = Input('orderBy/d',3);	// 默认按点赞数排序
    	$data['order'] = Input('order/d',1);
    	$data['sprice'] = Input('sprice');
    	$data['eprice'] = Input('eprice');
    	$data['attrs'] = [];

        $data['areaId'] = (int)Input('areaId');
        $aModel = model('home/areas');

        // 获取地区
        $data['area1'] = $data['area2'] = $data['area3'] = $aModel->listQuery(); // 省级

        // 如果有筛选地区 获取上级地区信息
        if($data['areaId']!==0){
            $areaIds = $aModel->getParentIs($data['areaId']);
            /*
              2 => int 440000
              1 => int 440100
              0 => int 440106
            */
            $selectArea = [];
            $areaName = '';
            foreach($areaIds as $k=>$v){
                $a = $aModel->getById($v);
                $areaName .=$a['areaName'];
                $selectArea[] = $a;
            }
            // 地区完整名称
            $selectArea['areaName'] = $areaName;
            // 当前选择的地区
            $data['areaInfo'] = $selectArea;

            $data['area2'] = $aModel->listQuery($areaIds[2]); // 广东的下级
 
            $data['area3'] = $aModel->listQuery($areaIds[1]); // 广州的下级
        }
        
    	$vs = input('vs');
    	$vs = ($vs!='')?explode(',',$vs):[];
    	foreach ($vs as $key => $v){
    		if($v=='' || $v==0)continue;
    		$v = (int)$v;
    		$data['attrs']['v_'.$v] = input('v_'.$v);
    	}
    	$data['vs'] = $vs;

    	$brandIds = Input('brand');


        $bgIds = [];// 品牌下的商品Id
        if(!empty($vs)){
            // 存在筛选条件,取出符合该条件的商品id,根据商品id获取可选品牌
            $goodsId = model('home/goods')->filterByAttributes();
            $data['brandFilter'] = model('Brands')->canChoseBrands($goodsId);
        }else{
           // 取出分类下包含商品的品牌
           $data['brandFilter'] = model('home/Brands')->goodsListQuery((int)current($goodsCatIds));
        }
        if(!empty($brandIds))$bgIds = model('home/Brands')->getGoodsIds($brandIds);


    	$data['price'] = Input('price');
    	//封装当前选中的值
    	$selector = [];
    	//处理品牌
        $brandIds = explode(',',$brandIds);
        $bIds = $brandNames = [];
        foreach($brandIds as $bId){
        	if($bId>0){
        		foreach ($data['brandFilter'] as $key =>$v){
        			if($v['brandId']==$bId){
                        array_push($bIds, $v['brandId']);
                        array_push($brandNames, $v['brandName']);
                    }
        		}
                $selector[] = ['id'=>join(',',$bIds),'type'=>'brand','label'=>"品牌","val"=>join('、',$brandNames)];
            }
        }
        // 当前是否有品牌筛选
        if(!empty($selector)){
            $_s[] = $selector[count($selector)-1];
            $selector = $_s;
            unset($data['brandFilter']);
        }
        $data['brandId'] = Input('brand');

    	//处理价格
    	if($data['sprice']!='' && $data['eprice']!=''){
    		$selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>$data['sprice']."-".$data['eprice']];
    	}
        if($data['sprice']!='' && $data['eprice']==''){
        	$selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>$data['sprice']."以上"];
    	}
        if($data['sprice']=='' && $data['eprice']!=''){
        	$selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>"0-".$data['eprice']];
    	}
    	//处理已选属性
        $at = new AT();
    	$goodsFilter = $at->listQueryByFilter($catId);
    	$ngoodsFilter = [];
        if(!empty($vs)){
            // 存在筛选条件,取出符合该条件的商品id,根据商品id获取可选属性进行拼凑
            $goodsId = model('home/goods')->filterByAttributes();
                // 如果同时有筛选品牌,则与品牌下的商品Id取交集
            if(!empty($bgIds))$goodsId = array_intersect($bgIds,$goodsId);


            $attrs = model('home/Attributes')->getAttribute($goodsId);
            // 去除已选择属性
            foreach ($attrs as $key =>$v){
                if(!in_array($v['attrId'],$vs))$ngoodsFilter[] = $v;
            }
        }else{
            if(!empty($bgIds))$goodsFilter = model('home/Attributes')->getAttribute($bgIds);// 存在品牌筛选
            // 当前无筛选条件,取出分类下所有属性
        	foreach ($goodsFilter as $key =>$v){
        		if(!in_array($v['attrId'],$vs))$ngoodsFilter[] = $v;
            }
        }
        if(count($vs)>0){
            $_vv = [];
            $_attrArr = [];
    		foreach ($goodsFilter as $key =>$v){
    			if(in_array($v['attrId'],$vs)){
    				foreach ($v['attrVal'] as $key2 =>$vv){
    					if(strstr(input('v_'.$v['attrId']),$vv)!==false){
                            array_push($_vv, $vv);
                            $_attrArr[$v['attrId']]['attrName'] = $v['attrName'];
                            $_attrArr[$v['attrId']]['val'] = $_vv;
                        }
    				}
                    $_vv = [];
    			}
    		}
            foreach($_attrArr as $k1=>$v1){
                $selector[] = ['id'=>$k1,'type'=>'v_'.$k1,'label'=>$v1['attrName'],"val"=>join('、',$v1['val'])];
            }
    	}
    	$data['selector'] = $selector;
        $attrs = [];
        foreach ($ngoodsFilter as $k => $val) {
           $result = array_unique($ngoodsFilter[$k]['attrVal']);
           $ngoodsFilter[$k]['attrVal'] = $result;
        }
    	$data['goodsFilter'] = $ngoodsFilter;
    	//获取商品记录
    	$m = new M();
    	$data['goodsPage'] = $m->pageQuery($goodsCatIds,2);
        $catPaths = model('home/goodsCats')->getParentNames($catId);

        $data['catNamePath'] = '全部商品分类';
        if(!empty($catPaths))$data['catNamePath'] = implode(' - ',$catPaths);
    	return $this->fetch("home/goods_list",$data);
	}

	public function config(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			//$this->redirect("/home/users/index");
		}
		$rs = Db::name('guarantee_config')->find();
		$this->assign('config',$rs);
		return $this->fetch('/admin/config');
	}
	
	public function pageQueryDelivery(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQueryDelivery());
	}
	
	public function pageQueryBackSale(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQueryBackSale());
	}
	
	//保底交易提货
	public function delivery(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			//$this->redirect("/home/users/index");
		}
		return $this->fetch('/admin/delivery');
	}
	
	//保底交易回购
	public function backsale(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			//$this->redirect("/home/users/index");
		}
		$rs = Db::name('guarantee_config')->find();
		$this->assign('config',$rs);
		return $this->fetch('/admin/backsale');
	}

	function editConfig(){
		$userId = (int)session('WST_USER.userId');
		$m = new M();
		return $m->editConfig();
	}
	
	function verifyCode(){
		$m = new M();
		return $m->verifyCode();
	}
	
	function confirmBackSale(){
		$m = new M();
		return $m->confirmBackSale();
	}
}