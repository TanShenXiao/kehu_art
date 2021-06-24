<?php
namespace addons\signup\controller;

use think\addons\Controller;
use addons\signup\model\Extras as M;
/**
 * 线上报名插件
 */
class Extras extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	/**
     * 显示线上报名扩展字段
     */
    public function pageExtrasByAdmin(){
        $this->checkAdminPrivileges();
		$m = new M();
		$catsList = $m->listCats();
		$this->assign("catsList",$catsList);
        return $this->fetch("/admin/editextras");
    }
	
	/**
     * 查询线上报名扩展字段
     */
    public function pageQueryExtrasByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQueryExtrasByAdmin());
    }
	
    /*
    * 获取报名字段
    */
    public function getExtraById(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->getExtraById(Input("id/d",0));
    }
    /**
     * 新增报名字段
     */
    public function addExtra(){
        $m = new M();
        return $m->addExtra();
    }
    /**
    * 修改报名字段
    */
    public function editExtra(){
        $m = new M();
        return $m->editExtra();
    }
	/**
     * 删除报名字段
     */
    public function delExtraByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->delExtraByAdmin();
    }
}