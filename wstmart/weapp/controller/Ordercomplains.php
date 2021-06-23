<?php
namespace wstmart\weapp\controller;
use wstmart\weapp\model\OrderComplains as M;
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
 * 投诉控制器
 */
class orderComplains extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth'
    ];
	/**
     * 保存订单投诉信息
     */
    public function saveComplain(){
    	$m = new M(); 
        $rs = $m->saveComplain();
        return jsonReturn('',1,$rs);
    }

    /**
    * 获取用户投诉列表
    */    
    public function complainByPage(){
        $m = new M();
        $data = $m->queryUserComplainByPage();
        echo(jsonReturn('success',1,$data));die;
    }

    /**
     * 用户查投诉详情
     */
    public function getComplainDetail(){
    	$m = new M();
        $rs['list'] = $m->getComplainDetail(0);
        $annex = $rs['list']['complainAnnex'];
        if($annex){
        	foreach($annex as $k=>$v){
        		$annex1[] = WSTImg($v,3);
        	}
        	$rs['list']['complainAnnex'] = $annex1;
        }
        echo(jsonReturn('success',1,$rs));die;
    }

}
