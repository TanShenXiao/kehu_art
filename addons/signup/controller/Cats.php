<?php
namespace addons\signup\controller;

use think\addons\Controller;
use addons\signup\model\Cats as M;
/**
 * 线上报名插件
 */
class Cats extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	
	/**
     * 显示线上报名项目页面
     */
    public function pageCatsByAdmin(){
        $this->checkAdminPrivileges();
        return $this->fetch("/admin/listcats");
    }
	
	/**
     * 查询线上报名项目
     */
    public function pageQueryCatsByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQueryCatsByAdmin());
    }
	
	public function pageQueryCats(){
        $m = new M();
        return $m->pageQueryCats();
    }
	
	/**
     * 编辑线上报名项目
     */
    public function editCatsByAdmin(){
        $this->checkAdminPrivileges();
        return $this->fetch("/admin/listcats");
    }
	/**
     * 跳去新增/编辑页面
     */
    public function toEdit(){
    	$id = Input("get.id/d",0);
    	$m = new M();
		$catInfo['catId'] = $id;
    	if($id>0){
    		$catInfo = $m->getCatById($id);
    	}
        $this->assign('catInfo',$catInfo);
        return $this->fetch("/admin/editcats");
    }

    /*
    * 获取报名项目
    */
    public function getCatById(){
        $m = new M();
        return $m->getCatById(Input("id/d",0));
    }
	/*
    * 获取报名项目
    */
    public function getCats(){
        $m = new M();
        return $m->getCats();
    }
    /**
     * 新增报名项目
     */
    public function addCat(){
        $m = new M();
        return $m->addCat();
    }
    /**
    * 修改报名项目
    */
    public function editCat(){
        $m = new M();
        return $m->editCat();
    }
    /**
     * 删除报名项目
     */
    public function delCatByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->delCatByAdmin();
    }
}