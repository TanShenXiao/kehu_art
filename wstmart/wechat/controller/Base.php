<?php
namespace wstmart\wechat\controller;
use think\Controller;
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
 * 基础控制器
 */
class Base extends Controller {

	public function __construct(){
		parent::__construct();
		hook('initConfigHook',['getParams'=>input()]);
		WSTConf('CONF',WSTConfig());
		WSTSwitchs();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wsthomeStyleId'));

		$this->view->filter(function($content){
            $style = WSTConf('CONF.wstwechatStyle')?WSTConf('CONF.wstwechatStyle'):'default';
            $content = str_replace("__RESOURCE_PATH__",WSTConf('CONF.resourcePath'),$content);
            $content = str_replace("__WECHAT__",str_replace('/index.php','',$this->request->root()).'/wstmart/wechat/view/'.$style,$content);
            return $content;
        });
		if(!(request()->module()=="wechat" && request()->controller()=="Weixinpays" && request()->action()=="notify")){
			WSTIsWeixin();//检测是否在微信浏览器上使用
		}
		$state = input('param.state');
		if($state==WSTConf('CONF.wxAppCode')){
			$type = input('param.type');
			if($type=='1'){
				WSTBindWeixin(1);
			}else{
				WSTBindWeixin(0);
			}
		}
        $wechatOneClickLogin = WSTConf('CONF.wechatOneClickLogin');
        $USER = session('WST_USER');
        if(empty($USER)){
            if($wechatOneClickLogin) {
                WSTOneClickLogin(1);
            }
        }
		if(WSTConf('CONF.seoMallSwitch')==0){
			$this->redirect('wechat/switchs/index');
			exit;
		}
	}
    // 权限验证方法
    protected function checkAuth(){
		$state = input('param.state');
		if($state==WSTConf('CONF.wxAppCode')){
			WSTBindWeixin(1);
		}
		$request = request();
       	$USER = session('WST_USER');
        if(empty($USER)){
            if(request()->isAjax()){
                die('{"status":-999,"msg":"还没关联帐号,正在关联帐号"}');
            }else{
                session('WST_WX_WlADDRESS',$request->url(true));
                $url=urlencode($request->url(true));
                $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WSTConf('CONF.wxAppId').'&redirect_uri='.$url.'&response_type=code&scope=snsapi_userinfo&state='.WSTConf('CONF.wxAppCode').'#wechat_redirect';
                header("location:".$url);
                exit;
            }
        }
    }

    // 店铺权限验证方法
    protected function checkShopAuth($opt){
       	$shopMenus = WSTShopOrderMenus();
       	if($opt=="list"){
       		if(count($shopMenus)==0){
       			session('wxshoporder','对不起,您无权进行该操作');
       			$this->redirect('wechat/error/message',['code'=>'wxshoporder']);
		    	exit;
       		}
       	}else{
       		if(!array_key_exists($opt,$shopMenus)){
	       		if(request()->isAjax()){
		    		die('{"status":-1,"msg":"您无权进行该操作"}');
		    	}else{
		    		session('wxshoporder','对不起,您无权进行该操作');
		    		$this->redirect('wechat/error/message',['code'=>'wxshoporder']);
		    		exit;
		    	}
	       	}
       	}
    }

	protected function fetch($template = '', $vars = [], $config = []){
		$style = WSTConf('CONF.wstwechatStyle')?WSTConf('CONF.wstwechatStyle'):'default';
		return $this->view->fetch($style."/".$template, $vars, $config);
		
	}
	/**
	 * 上传图片
	 */
	public function uploadPic(){
		$this->checkAuth();
		return WSTUploadPic(0);
	}
	/**
	 * 获取验证码
	 */
	public function getVerify(){
		WSTVerify();
	}
}