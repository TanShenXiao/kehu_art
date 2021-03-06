<?php
namespace wstmart\weapp\controller;
use wstmart\common\model\Invoices as M;
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
 * 发票信息控制器
 */
class Invoices extends Base{
	// 前置方法执行列表
	protected $beforeActionList = [
			'checkAuth'
	];
     /**
     * 获取发票列表
     */
    public function pageQuery(){
        $m = new M();
        $userId = model('weapp/Users')->getUserId();
        $rs = $m->pageQuery(5,$userId);
        return jsonReturn('success',1,$rs);
    }
    /**
     * 新增发票
     */
    public function add(){
        $m = new M();
        $userId = model('weapp/Users')->getUserId();
        $rs = $m->add($userId);
        return jsonReturn('',1,$rs);
    }
    /**
     * 新增发票
     */
    public function edit(){
        $m = new M();
        $userId = model('weapp/Users')->getUserId();
        $rs = $m->edit($userId);
        return jsonReturn('',1,$rs);
    }
}
