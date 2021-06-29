<?php
namespace wstmart\admin\controller;
use wstmart\common\model\ManualOrders as M;

class ManualOrders extends Base{

    /**
     * 手工订单列表
     * @return mixed|string
     */
    public function index()
    {

        $this->assign("userId",(int)input('userId'));
        $this->assign("p",(int)input("p"));

    	return $this->fetch("list");
    }

    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        return WSTGrid($m->pageQuery());
    }

    /**
     * 新增
     */
    public function add(){
        $m = new M();
        $rs = $m->add();
        return $rs;
    }
    /**
     * 修改
     */
    public function edit(){
        $m = new M();
        $rs = $m->edit();
        return $rs;
    }
}
