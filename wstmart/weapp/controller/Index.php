<?php
namespace wstmart\weapp\controller;
use wstmart\weapp\model\Index as M;
use wstmart\common\model\Tags;
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
 * 默认控制器
 */
class Index extends Base{
     /**
     * 首页楼层数据
     */
    public function pageQuery(){
        $m = new M();
        $rs = $m->pageQuery();
        if(isset($rs['goods'])){
            foreach ($rs['goods'] as $key =>$v){
            	$rs['goods'][$key]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
            }
        }
        if(isset($rs['catId'])){
        	return jsonReturn('success',1,$rs);
        }else{
        	return jsonReturn('',-1);
        }
        
    }

    /*
     * 一键登录
     */
    public function oneClickLogin(){
        $weappOneClickLogin = WSTConf('CONF.weappOneClickLogin');
        $tokenId = input('tokenId');
        if(empty($tokenId)){
            if($weappOneClickLogin) {
                $tokenId = WSTOneClickLogin(5);
                return jsonReturn('success',1,$tokenId);
            }
        }
        return jsonReturn('',1);
    }
    /**
     * 首页数据
     */
    public function getIndexData(){
        $rs = [];
        $m = new M();
        // 获取轮播图
        $model = new Tags();
        $rs['swiper'] = $this->transitionImg($model->listAds('weapp-ads-index',99,86400));
        // 4张可横向循环滚动广告图
        $rs['ads'] = $this->transitionImg($model->listAds('weapp-index-small',4,86400));
        $rs['ads1'] = $this->transitionImg($model->listAds('weapp-index-1',1,86400));
        $rs['ads2'] = $this->transitionImg($model->listAds('weapp-index-2',1,86400));
        $rs['ads3'] = $this->transitionImg($model->listAds('weapp-index-3',1,86400));
        // 获取4张广告图
        $rs['indexAds'] = $this->transitionImg($model->listAds('weapp-index-large',4,86400));
        // 获取最新资讯
        $rs['news'] = $model->listByNewArticle(4, 86400);
        // 获取商城信息
        $rs['message'] = $m->getSysMsg('message');
        //按钮
        $rs['btns'] = WSTMobileBtns(2);
        return jsonReturn('success',1,$rs);
    }
    /**
     * 配置信息
     */
    public function confInfo(){
    	$data['mallName'] = WSTConf('CONF.mallName');//商城名称
        $data['smsOpen'] = WSTConf('CONF.smsOpen');//开启短信验证
    	$data['smsVerfy'] = WSTConf('CONF.smsVerfy');//发送短信前是否需要输入验证码
    	$data['userLogo'] = WSTConf('CONF.userLogo');//会员默认头像
    	$data['shopLogo'] = WSTConf('CONF.shopLogo');//店铺默认头像
    	$data['goodsLogo'] = WSTConf('CONF.goodsLogo');//商品默认图片
    	$data['isOrderScore'] = WSTConf('CONF.isOrderScore');//是否开启积分
    	$data['isCryptPwd'] = WSTConf('CONF.isCryptPwd');//是否密码加密
    	$data['pwdModulusKey'] = WSTConf('CONF.pwdModulusKey');//商城密匙
    	$data['weappOneClickLogin'] = WSTConf('CONF.weappOneClickLogin');//小程序一键登录
        $data['resourceDomain'] = (WSTConf('CONF.ossService')!='')?WSTProtocol().WSTConf('CONF.ossBucket').'.'.WSTConf('CONF.ossBucketDomain'):str_replace('index.php','',request()->root(true));//商城密匙
        $data['resourceDomain'] = $data['resourceDomain']."/";
    	$addons = Db::name('addons')->where(['dataFlag'=>1])->select();
        $data['addons'] = [];
    	if($addons){
    		foreach ($addons as $key =>$v){
    			$data['addons'][$v['name']] = $v['status'];//插件
    		}
    	}
    	session('sessionId','1');
    	$data['sessionId'] = session_id();//sessionId
    	return jsonReturn('success',1,$data);
    }
    public function hots(){
        $rec = WSTConf("CONF.hotWordsSearch");
        return jsonReturn('请求成功',1,explode(',',$rec));
    }
    /*
     * 获取商城是否开启首页自定义页面功能
     */
    public function getCustomPagesSetting(){
        $m = new M();
        $pageId = $m->getCustomPagesSetting();
        // 判断是否含有多店铺组件，需要在小程序端调取获取坐标的api
        $hasShop = $m->hasShopComponent($pageId);
        if(!$pageId)$pageId = 0;
        return jsonReturn('success',1,['pageId'=>$pageId,'hasShop'=>$hasShop]);
    }

    /*
     * 获取商城首页自定义页面数据
     */
    public function getCustomPageDecorationData(){
        $m = new M();
        $rs = $m->getCustomPageDecorationData();
        return jsonReturn('success',1,$rs);
    }

    /*
     * 获取后台自定义的底部导航栏菜单
     */
    public function getTabbarMenu(){
        $m = new M();
        $pageId = $m->getCustomPagesSetting();
        if($pageId > 0){
            $res = $m->getTabbarMenu($pageId);
            $res['cartNum'] = model('weapp/carts')->cartNum();
            if($res['tabbars']){
                return jsonReturn('success',1,$res);
            }else{
                return jsonReturn('未设置底部导航栏',-1);
            }
        }else{
            return jsonReturn('未开启小程序首页装修',-1);
        }
    }
    /*
	 * 通过接口注册
	 */
	public function apiRegister(){
		$params = input();
		$rtn = verifySinature($params);
		if($rtn['status'] == -1)
			return jsonReturn($rtn['msg'],-1,$rtn['data']);
		if(empty($params['src']) || empty($params['loginName']) || empty($params['password']) || empty($params['userPhone'])){
			return jsonReturn('参数不完整',-1);
		}else{
			$m = new M();
			return $m->registerByApi();
		}
		return jsonReturn('未知错误',-1);
	}
}
