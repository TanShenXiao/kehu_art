<?php
namespace addons\vote\controller;

use think\addons\Controller;
use addons\vote\model\Lists as M;

/**
 * 线上报名插件
 */
class Lists extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	
	/**
     * 显示线上报名列表
     */
    public function pageListsByAdmin(){
        $this->checkAdminPrivileges();
        return $this->fetch("/admin/list");
    }
	
	/**
     * 查询线上报名清单
     */
    public function pageQueryByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQueryByAdmin());
    }
	
	/**
	 * 管理员查看线上报名明细
	 */
	public function pageListByItem(){
		$m = new M();
		$page = $m->pageListByItem();
        return WSTGrid($page);
	}
	
	public function listByItem(){
		$itemId = input('itemId/d');
		$this->assign('itemId',$itemId);
		return $this->fetch("/admin/listbyitem");
	}
	
	/**
     * 编辑线上报名项目
     */
    public function editCatsByAdmin(){
        $this->checkAdminPrivileges();
        $this->assign("areaList",model('common/areas')->listQuery(0));
        return $this->fetch("/admin/editcats");
    }
	
    /*
    * 获取报名项目
    */
    public function getCatById(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->getCatById(Input("id/d",0));
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
	/**
	 * 增加一次投票
	 */
	public function addList(){
		$m = new M();
		return $m->addList();
	}
	/**
     * 导出投票信息
     */
    public function toExport(){
    	$m = new M();
    	$rs = $m->toExport();
    	$this->assign('rs',$rs);
    }
}