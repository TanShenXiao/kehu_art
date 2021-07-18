<?php
namespace wstmart\admin\controller;
use think\Db;
use wstmart\common\model\ManualOrders as M;

class ManualOrders extends Base{

    /**
     * 手工订单列表
     * @return mixed|string
     */
    public function index()
    {
        $kfp = input('kfp',0);
        $wst_taxperson = Db::name('taxperson')->where(['isauth' => 1 ])->select();
        $this->assign("userId",(int)input('userId'));
        $this->assign("p",(int)input("p"));
        $this->assign("wst_taxperson",$wst_taxperson);
        $this->assign("kfp",$kfp);

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

    /**
     * 设置商家费率
     */
    public function change_shop_tax_price()
    {
        $id = input('id');
        $shop_tax_price = (float)input('shop_tax_price',0);

        $data = Db::table('wst_manual_orders')->where(['id' => $id])->find();
        if(!$data){
            return WSTReturn("订单不存在", -1);
        }
        if($data['is_invoice'] == 0 ){
            return WSTReturn("该订单不需要开发票", -1);
        }
        if($data['status'] == 1){
            return WSTReturn("已设置税", -1);
        }
        Db::table('wst_manual_orders')->where(['id' => $id])->update(['tax_price' => $shop_tax_price,'status' => 1]);

        return WSTReturn("设置成功", 1);

    }
}
