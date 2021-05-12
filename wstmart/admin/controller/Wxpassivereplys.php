<?php
namespace wstmart\admin\controller;
use wstmart\admin\model\WxPassiveReplys as M;
/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtao.net
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 微信被动回复管理控制器
 */
class Wxpassivereplys extends Base{
    // 文本消息列表
    public function text(){
        $this->assign("p",(int)input("p"));
    	return $this->fetch("text");
    }
    /**
     * 获取文本消息分页
     */
    public function textPageQuery(){
    	$m = new M();
    	return WSTGrid($m->pageQuery('text'));
    }
    /**
     * 跳去文本消息新增/编辑页面
     */
    public function textEdit(){
        $id = Input("get.id/d",0);
        $m = new M();
        $data = $m->getById($id);
        $this->assign('data',$data);
        $this->assign("p",(int)input("p"));
        return $this->fetch("text_edit");
    }



    // 图文消息列表
    public function news(){
        return $this->fetch("news");
    }
    /**
     * 获取图文消息分页
     */
    public function newsPageQuery(){
        $m = new M();
        return WSTReturn('',1,$m->pageQuery('news'));
    }
    /**
     * 跳去图文消息新增/编辑页面
     */
    public function newsEdit(){
        $id = Input("get.id/d",0);
        $m = new M();
        $data = $m->getById($id);
        $this->assign('data',$data);
        return $this->fetch("news_edit");
    }




    // 
    public function sendNews(){
        $wx = WXAdmin();
        $wx->_doText();
    }

    /**
     * 新增
     */
    public function add(){
        $m = new M();
        return $m->add();
    }
     /**
    * 修改
    */
    public function edit(){
        $m = new M();
        return $m->edit();
    }
    /**
     * 删除
     */
    public function del(){
        $m = new M();
        return $m->del();
    }

    /***
     *关注回复
     **/
    public function subscribeIndex(){
        $this->assign("p",(int)input("p"));
        return $this->fetch("subscribe");
    }
    /**
     * 获取主动回复内容
     */
    public function pagSubscribeQuery(){
        $m = new M();
        return WSTGrid($m->pagSubscribeQuery(1));
    }
    /**
     * 获取没有设置为主动回复的内容
     */
    public function pagNoSubscribeQuery(){
        $m = new M();
        return WSTGrid($m->pagSubscribeQuery(0));
    }
    /**
     * 删除关注回复记录
     */
    public function delSubscribe(){
        $m = new M();
        return $m->delSubscribe();
    }
    /**
     * 选择素材
     */
    public function selectSubscribe(){
        $m = new M();
        return $m->selectSubscribe();
    }
    /**
     * 新增关注回复
     */
    public function addSubscribe(){
        $m = new M();
        return $m->add();
    }
    /**
     * 编辑关注回复
     */
    public function editSubscribe(){
        $m = new M();
        return $m->edit();
    }
    /**
     * 编辑关注回复排序
     */
    public function editSubscribeSort(){
        $m = new M();
        return $m->edit();
    }
    /**
     * 获取关注回复用于修改
     */
    public function getById(){
        $id = Input("post.id/d",0);
        $m = new M();
        return WSTReturn('',1,$m->getById($id));
    }
}
