<?php
namespace wstmart\mobile\controller;
use \wstmart\common\model\BrandActivities as M;

class BrandActivities extends Base{

    public function get_list(){
        //获取左侧列表
        $m = model('BrandActivities');
        // 资讯列表下的新闻
        $pageObj = $m->getList();
        $news = $pageObj->toArray();
        // 分页页码
        $page = $pageObj->render();
        $this->assign('page',$page);
        $this->assign('index',$news['data']);

        return $this->fetch('brand_activitiesget_list');
    }

    /**
     * 获取商城快讯列表
     */
    public function getNewsList(){
        $m = new M();
        $data = $m->getList();

        return $data;
    }

}