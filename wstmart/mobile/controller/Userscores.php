<?php
namespace wstmart\mobile\controller;
use wstmart\common\model\UserScores as MUserscores;
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
 * 地区控制器
 */
class Userscores extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'
    ];
	/**
    * 查看
    */
	public function index(){
		$rs = model('Users')->getFieldsById((int)session('WST_USER.userId'),['userScore','userTotalScore']);
		$this->assign('object',$rs);
		return $this->fetch('users/userscores/list');
	}
    /**
    * 获取数据
    */
    public function pageQuery(){
        $userId = (int)session('WST_USER.userId');
        $data = model('UserScores')->pageQuery($userId);
        return WSTReturn("", 1,$data);
    }
    /**
     * 签到积分
     */
    public function signScore(){
    	$m = new MUserscores();
    	$userId = (int)session('WST_USER.userId');
    	$rs = $m->signScore($userId);
    	return $rs;
    }
}
