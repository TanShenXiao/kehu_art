<?php
namespace addons\vote\controller;

use think\addons\Controller;
use addons\vote\model\Items as M;
/**
 * 在线投票插件
 */
class Items extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	/**
     * 显示在线投票项目
     */
    public function pageItemsByAdmin(){
        $this->checkAdminPrivileges();
		$m = new M();
		$catsList = $m->listCats();
		$this->assign("catsList",$catsList);
        return $this->fetch("/admin/edititems");
    }
	
	/**
     * 查询在线投票项目
     */
    public function pageQueryItemsByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQueryItemsByAdmin());
    }
	
	public function pageQueryItems(){
        $m = new M();
        return $m->pageQueryItems();
    }
	
    /*
    * 获取投票项目
    */
    public function getItemById(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->getItemById(Input("id/d",0));
    }
	
	/*
    * 获取投票项目
    */
    public function getItemByCatId($catId){
        $m = new M();
        return $m->getItemByCatId($catId);
    }

    /**
     * 新增投票项目
     */
    public function addItem(){
        $m = new M();
        return $m->addItem();
    }
    /**
    * 修改投票项目
    */
    public function editItem(){
        $m = new M();
        return $m->editItem();
    }
	/**
     * 删除投票项目
     */
    public function delItemByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->delItemByAdmin();
    }
}