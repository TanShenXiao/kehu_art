<?php
namespace wstmart\weapp\controller;
use wstmart\weapp\model\GoodsCats as M;
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
 * 商品分类控制器
 */
class GoodsCats{
	/**
     * 列表
     */
    public function index(){
    	$m = new M();
    	$goodsCatList = $m->getGoodsCats();
    	if(!empty($goodsCatList)){
            return jsonReturn('success',1,$goodsCatList);
        }
        return jsonReturn('error',-1);
    }
    /**
     * 获取指定列表
     */
    public function lists(){
    	$keyword = (int)input('parentId');
    	$isFloor = input('isFloor',-1);
    	return jsonReturn('success',1,WSTGoodsCats($keyword,$isFloor));
    }
}
