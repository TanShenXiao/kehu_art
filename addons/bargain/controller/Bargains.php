<?php
namespace addons\bargain\controller;

use think\addons\Controller;
use addons\bargain\model\Bargains as M;
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
 * 全民砍价活动插件
 */
class Bargains extends Controller{
	/**
	 * 第一刀
	 */
	public function firstKnife(){
		$m = new M();
		return $m->firstKnife();
	}
	/**
	 * 补刀
	 */
	public function addKnife(){
        $userId= model('weapp/index')->getUserId();
		$m = new M();
		return $m->addKnife();
	}
	/**
	 * 获取砍价人信息
	 */
	public function bargainInfo(){
		$m = new M();
		$userId = (int)session('WST_USER.userId');
		$bargainUserId = (int)base64_decode(input('bargainUserId'));
		$bargainId = input('id/d',0);
		$userIds = ($bargainUserId>0)?$bargainUserId:$userId;
		return $m->checkBargain($userIds,$bargainId);
	}
	/**
	 * 亲友团
	 */
	public function helpsList(){
		$m = new M();
		$userId = (int)session('WST_USER.userId');
		$bargainUserId = (int)base64_decode(input('bargainUserId'));
		return $m->helpsList($userId,$bargainUserId);
	}

    /**************************************小程序**********************************/
    /**
     * 第一刀
     */
    public function weFirstKnife(){
        $this->checkWeappAuth();
        $userId= model('weapp/index')->getUserId();
        $m = new M();
        return jsonReturn('success',1,$m->firstKnife($userId));
    }
    /**
     * 补刀
     */
    public function weAddKnife(){
        $this->checkWeappAuth();
        $userId= model('weapp/index')->getUserId();
        $m = new M();
        return jsonReturn('success',1,$m->addKnife($userId));
    }

    /**
     * 获取砍价人信息
     */
    public function weBargainInfo(){
        $m = new M();
        $userId= model('weapp/index')->getUserId();
        $bargainUserId = (int)base64_decode(input('bargainUserId'));
        $bargainId = input('id/d',0);
        $userIds = ($bargainUserId>0)?$bargainUserId:$userId;
        return jsonReturn('success',1,$m->checkBargain($userIds,$bargainId));
    }
    /**
     * 亲友团
     */
    public function weHelpsList(){
        $m = new M();
        $userId= model('weapp/index')->getUserId();
        $bargainUserId = (int)base64_decode(input('bargainUserId'));
        $rs = $m->helpsList($userId,$bargainUserId);
        foreach ($rs['data'] as $key => $v) {
            $userPhoto = $v['userPhoto'];
            if (WSTMSubstr($userPhoto, 0, 4) != 'http') {
                $userPhoto = url('/','','',true).WSTMSubstr($userPhoto, 10, strlen($userPhoto));
            }
            $rs['data'][$key]['userPhoto'] = $userPhoto;
        }
        return jsonReturn('success',1,$rs);
    }
}