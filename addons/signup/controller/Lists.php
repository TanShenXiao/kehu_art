<?php
namespace addons\signup\controller;

use think\addons\Controller;
use addons\signup\model\Lists as M;
use addons\signup\model\Extras as ME;
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
     * 显示线上报名详情
     */
    public function userSignupInfo(){
        $this->checkAdminPrivileges();
		$input = input('get.');
		// 获取会员报名信息
		$userExtrasVal = array();
		if(isset($input['id'])) {
			$listId = $input['id'];
			$me = new ME();
			$userExtrasVal = $me->getUserExtrasVal($listId);
		}
		$this->assign('userExtrasVal',$userExtrasVal);
        return $this->fetch("/admin/usersignupinfo");
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
	 * 新增报名
	 */
	public function addList(){
		$m = new M();
		return $m->addList();
	}
	
	/**
     * 导出报名信息
     */
    public function toExport(){
    	$m = new M();
    	$rs = $m->toExport();
    	$this->assign('rs',$rs);
    }
	/**
     * 导出报名信息总览
     */
    public function toExportOverview(){
    	$m = new M();
    	$rs = $m->toExportOverview();
    	$this->assign('rs',$rs);
    }
}