<?php
namespace addons\shoptpl\controller;

use think\addons\Controller;
use addons\shoptpl\model\Shoptpl as M;

class Shoptpl extends Controller{
	
	public function __construct(){
		parent::__construct();
	}
	
	//选择模板
	public function setting(){
		$shopId = (int)session('WST_USER.shopId');
    	return $this->fetch('home/shops/style');
	}
	
	/**
     * 获取模板列表
     */
    public function listQuery(){
        $m = new M();
        $rs = $m->listQuery();
        return WSTReturn('',1,$rs);
    }
	
	//更改模板
	public function changeTpl(){
		$m = new M();
		$m->changeTpl();
		return WSTReturn('操作成功',1);
	}
}