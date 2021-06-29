<?php
namespace wstmart\shop\controller;
use wstmart\common\model\Invoice as M;


class Invoice extends Base{

    /**
     * 发票列表
     * @return mixed|string
     */
    public function index()
    {
        $this->checkAuth();

        $this->assign("userId",(int)input('userId'));
        $this->assign("p",(int)input("p"));

        return $this->fetch("invoice/list");
    }

    /**
     * 获取分页
     */
    public function pageQuery(){
        $this->checkAuth();
        $m = new M();
        return WSTGrid($m->shopPageQuery());
    }

}
