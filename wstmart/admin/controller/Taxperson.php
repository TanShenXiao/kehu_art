<?php
namespace wstmart\admin\controller;
use think\Db;
use wstmart\common\model\Taxperson as M;

class Taxperson extends Base{

    /**
     * 开票人员列表 s
     * @return mixed|string
     */
    public function index()
    {

        $this->assign("userId",(int)input('userId'));
        $this->assign("p",(int)input("p"));

    	return $this->fetch("list");
    }

    /**
     * 获取分页
     */
    public function pageQuery(){
        $m = new M();
        $type = input('type',1);

        if($type == 1){
            return WSTGrid($m->pageQuery());
        }else{
            return WSTGrid($m->pageManualQuery());
        }
    }
    public function add(){
        $jbrxm = input('jbrxm',1);
        $jbrzjhm = input('jbrzjhm',1);
        $jbrmobile = input('jbrmobile',1);
        if(empty($jbrxm) || empty($jbrzjhm) || empty($jbrmobile) ){
            return WSTGrid(['msg'=>"经办人信息必须填写"],0);
        }
        Db::name('taxperson')->insertGetId([
            'jbrxm'=>$jbrxm,
            'jbrzjhm'=>$jbrzjhm,
            'jbrmobile'=>$jbrmobile,
            'isauth'=>1,
            'createtime'=>time(),
        ]);
        return WSTGrid(['msg'=>"认证成功"],1);
    }
    public function del(){
        $id = input('post.id/d');
//        Db::startTrans();
//        try{
        $result = Db::name('taxperson')->where('id',$id)->delete();
        if(false !== $result){
//                    Db::commit();
            return ['msg'=>"删除成功", 'status'=>1];
        }

//        }catch (\Exception $e) {
//            Db::rollback();
//        }
        return ['msg'=>'操作失败','status'=>0];
    }
}
