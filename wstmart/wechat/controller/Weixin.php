<?php
namespace wstmart\wechat\controller;
use think\Controller;
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
 * 微信接入接口控制器
 */
class Weixin extends Controller{
    public function index(){
        if(isset($_GET['echostr'])){
		    $this->first();
		}else{
		    $wechat = new \wechat\WSTWechat(WSTConf('CONF.wxAppId'),WSTConf('CONF.wxAppKey'));
		    $wechat->responseMsg();
		}
    }

    public function first()
    {
        $echoStr = input("echostr");
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }	
	private function checkSignature()
	{	
        $signature = input("signature");
        $timestamp = input("timestamp");
        $nonce = input("nonce");

		$token = WSTConf('CONF.wxAppCode');

		$tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

	/******************** 调用模板消息接口 *************************/
	public function getTemplates(){
		$wechat = new \wechat\WSTWechat(WSTConf('CONF.wxAppId'),WSTConf('CONF.wxAppKey'));
		$rs = $wechat->getTemplates();
		dump(json_decode($rs,true));
	}
	public function sendTemplate(){
		$wechat = new \wechat\WSTWechat(WSTConf('CONF.wxAppId'),WSTConf('CONF.wxAppKey'));
		$wechat->sendTemplate();
	}
}
