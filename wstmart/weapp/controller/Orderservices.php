<?php
namespace wstmart\weapp\controller;
use wstmart\common\model\OrderServices as CM;
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
 * 订单控制器
 */
class Orderservices extends Base{
    
    protected $beforeActionList = ['checkAuth'];
    /**
     * 列表查询
     */
    public function pagequery(){
        $m = new CM();
        $userId = model('weapp/index')->getUserId();
        $rs = $m->pageQuery(0,$userId);
        return json_encode(WSTReturn('ok',1,$rs));
    }
    /**
    * 售后申请页面
    */
    public function apply(){
        $m = new CM();
        $userId = model('weapp/index')->getUserId();
        $goods = $m->getGoods($userId);
        // 退换货原因
        $reasons = WSTDatas('ORDER_SERVICES');
        $rs = ['goods'=>$goods, 'reasons'=>$reasons];
        return json_encode(WSTReturn('ok',1,$rs));
    }
    /**
     * 提交售后申请
     */
    public function commit(){
        $m = new CM();
        $userId = model('weapp/index')->getUserId();
        $rs = $m->commit($userId);
        return json_encode($rs);
    }

    /**
     * 详情页
     */
    public function detail(){
        $m = new CM();
        $userId = model('weapp/index')->getUserId();
        $detail = $m->getDetail(0, $userId);
        $log = $m->getLog($userId);
        foreach($log as $k=>$v){
            $log[$k]['avatar'] = WSTUserPhoto($v['avatar'], 1);
        }
        $rs = ['detail'=>$detail,'log'=>$log];
        return json_encode(WSTReturn('ok', 1, $rs));
    }
    /**
     * 用户发货页
     */
    public function sendPage(){
        $m = new CM();
        $userId = model('weapp/index')->getUserId();
        $detail = $m->getDetail(0, $userId);
        $express = model('Express')->listQuery();
        $rs = ['detail'=>$detail, 'express'=>$express];
        return json_encode(WSTReturn('ok', 1, $rs));
    }
    /**
     * 用户发货
     */
    public function userExpress(){
        $m = new CM();
        $userId = model('weapp/index')->getUserId();
        $rs = $m->userExpress($userId);
        return json_encode($rs);
    }
    /**
     * 用户确认收货
     */
    public function userReceive(){
        $m = new CM();
        $userId = model('weapp/index')->getUserId();
        $rs = $m->userReceive($userId);
        return json_encode($rs);
    }
    /**
     * 用户确认收货
     */
    public function getRejectReason(){
        $data = WSTDatas('ORDER_REJECT');
        return json_encode(WSTReturn('ok',1,$data));
    }
    /**
     * 获取当前可退款金额
     */
    public function getRefundableMoney(){
        $m = new CM();
        $userId = model('weapp/index')->getUserId();
        $rs = $m->getRefundableMoney($userId);
        return json_encode($rs);
    }

}