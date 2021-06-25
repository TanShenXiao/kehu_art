<?php
namespace wstmart\admin\controller;
use wstmart\common\model\Invoice as M;

class Invoice extends Base{

    /**
     * 发票列表 s
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
}
