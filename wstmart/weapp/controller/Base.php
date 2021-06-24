<?php
namespace wstmart\weapp\controller;
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
 * 默认控制器
 */
class Base extends Controller{
	public function __construct(){
        header('Access-Control-Allow-Origin:*'); 
		parent::__construct();
        $sessionId = input("sessionId");
		if($sessionId)session_id($sessionId);
	}
    /**
     * 域名
     */
    public function domain(){
        return url('/','','',true);
    }
    /**
	 * 获取验证码
	 */
	public function getVerify(){
		WSTVerify();
	}
    // 权限验证方法
    protected function checkAuth(){
        $tokenId = input('tokenId');
        if($tokenId==''){
            $rs = jsonReturn('您还未登录',-999);
            die($rs);
        }
        $userId = Db::name('weapp_session')->alias('as')
        ->join('__USERS__ u','u.userId=as.userId','inner')
        ->where("as.tokenId='{$tokenId}' and u.dataflag=1 and u.userStatus=1")
        ->value('as.userId');
        if(empty($userId)){
            $rs = jsonReturn('登录信息已过期,请重新登录',-999);
            die($rs);
        }
        return true;
    }
    /**
     * 上传图片
     */
    public function uploadPic(){
        $this->checkAuth();
        return WSTUploadPic(0);
    }
    /**
     * 转换图片即删除无用字段
     */
    public function transitionImg($img){
    	if(empty($img))return [];
    	// 图片转换及删除无用字段
    	$_img = [];
    	foreach ($img as $k => $v) {
    		$_img[$k]['adId'] = $v['adId'];
    		$_img[$k]['adURL'] = $v['adURL'];
    		$_img[$k]['adFile'] = WSTImg($v['adFile'],2);
    	}
    	return $_img;
    }
}
