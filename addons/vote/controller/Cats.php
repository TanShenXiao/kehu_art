<?php
namespace addons\vote\controller;

use think\addons\Controller;
use addons\vote\model\Cats as M;
/**
 * 在线投票插件
 */
class Cats extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	
	/**
     * 显示在线投票项目页面
     */
    public function pageCatsByAdmin(){
        $this->checkAdminPrivileges();
        return $this->fetch("/admin/listcats");
    }
	
	/**
     * 查询在线投票项目
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
     * 编辑在线投票项目
     */
    public function editCatsByAdmin(){
        $this->checkAdminPrivileges();
        return $this->fetch("/admin/editcats");
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
		$totalVotes = $m->getTotalVotes();
		$this->assign('totalVotes',$totalVotes);
        return $this->fetch("/admin/editcats");
    }
	
    /*
    * 获取投票项目
    */
    public function getCatById(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->getCatById(Input("id/d",0));
    }
    /**
     * 新增投票项目
     */
    public function addCat(){
        $m = new M();
        return $m->addCat();
    }
    /**
    * 修改投票项目
    */
    public function editCat(){
        $m = new M();
        return $m->editCat();
    }
    /**
     * 删除投票项目
     */
    public function delCatByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->delCatByAdmin();
    }
}