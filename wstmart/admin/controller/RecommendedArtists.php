<?php
namespace wstmart\admin\controller;
use wstmart\admin\model\RecommendedArtists as M;

class RecommendedArtists extends Base{

    public function index(){

    	$this->assign("p",(int)input("p"));
    	return $this->fetch("recommendedartists/list");
    }
    
    /**
     * 获取分页
     */
    public function pageQuery(){
    	$m = new M();
    	$rs = $m->pageQuery();
    	return WSTGrid($rs);
    }
    
    /**
     * 获取艺术家
     */
    public function get(){
    	$m = new M();
    	$rs = $m->get((int)Input("post.id"));
    	return $rs;
    }
    
    /**
     * 跳去新增/编辑页面
     */
    public function toEdit(){
    	$id = Input("get.id/d",0);
    	$m = new M();
    	if($id>0){
    		$object = $m->getById($id);
    	}else{
    		$object = $m->getEModel('recommended_artists');
    	}
    	$this->assign("p",(int)input("p"));
        $this->assign('object',$object);
        $this->assign('gcatList',model('GoodsCats')->listQuery(0));
        return $this->fetch("recommendedartists/edit");
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
     * 编辑
     */
    public function edit(){
    	$m = new M();
    	$rs = $m->edit();
    	return $rs;
    }
    
    /**
     * 删除
     */
    public function del(){
    	$m = new M();
    	$rs = $m->del();
    	return $rs;
    }

    /**
    * 修改品牌排序
    */
    public function changeSort(){
        $m = new M();
        return $m->changeSort();
    }
   
}
