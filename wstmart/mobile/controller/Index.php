<?php
namespace wstmart\mobile\controller;
use wstmart\mobile\model\Index as M;
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
 * 默认控制器
 */
class Index extends Base{
	/**
     * 首页
     */
    public function index(){
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
    	return $this->fetch('index');
    }
    /**
     * 楼层
     */
    public function pageQuery(){
    	$m = new M();
    	$rs = $m->pageQuery();
    	if(isset($rs['goods'])){
    		foreach ($rs['goods'] as $key =>$v){
    			$rs['goods'][$key]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
    		}
    	}
        return $rs;
    }

    /**
     * 转换url
     */
    public function transfor(){
        $data = input('param.');
        $url = $data['url'];
        unset($data['url']);
        echo Url($url,$data);
    }
    /**
     * 跳去登录之前的地址
     */
    public function sessionAddress(){
    	session('WST_MO_WlADDRESS',input('url'));
    	return WSTReturn("", 1);
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

    /*
     * 获取商城首页自定义页面多店铺组件数据
     */
    public function customPageShopsList(){
        $m = new M();
        return $m->customPageShopsList();
    }

    /*
     * 获取后台自定义的底部导航栏菜单
     */
    public function getTabbarMenu(){
        $m = new M();
        $pageId = $m->getCustomPagesSetting();
        if($pageId > 0){
            $res = $m->getTabbarMenu($pageId);
            if($res){
                return WSTReturn('success',1,$res);
            }else{
                return WSTReturn('未设置底部导航栏',-1);
            }
        }else{
            return WSTReturn('未开启小程序首页装修',-1);
        }
    }

    /*
     * 加载首页自定义页面
     */
    public function loadCustomPage($pageId){
        $filePath = 'tpl/custom_page_index_'.$pageId;
        $fileName = $filePath.'.html';
        if(file_exists(WSTRootPath().'/wstmart/mobile/view/default/'.$fileName)){
            return $this->fetch($filePath);
        }
    }
	
	public function listByGoods(){
		$m = new M();
		$rs = $m->listByGoods();
		return $rs;
	}
}
