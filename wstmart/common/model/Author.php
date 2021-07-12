<?php
namespace wstmart\common\model;
use wstmart\common\validate\Author as validate;
use \think\Db;

class Author extends Base{

    /**
     * 根据商品id获取可供选择的属性
     */
    public function getAttribute($goodsId){
        if(empty($goodsId))return [];
        $attrs = Db::name('attributes')->alias('a')
            ->join('__GOODS_ATTRIBUTES__ ga','ga.attrId=a.attrId','inner')
            ->field('ga.attrId,GROUP_CONCAT(distinct ga.attrVal) attrVal,a.attrName')
            ->where(['a.isShow'=>1,'a.dataFlag'=>1])
            ->where([['ga.goodsId','in',$goodsId],['a.attrType','<>',0]])
            ->group('ga.attrId')
            ->order('a.attrSort asc')
            ->select();
        if(empty($attrs))return [];
        foreach ($attrs as $key =>$v){
            $attrs[$key]['attrVal'] = explode(',',$v['attrVal']);
        }
        return $attrs;
    }

    /**
     * 新增
     */
    public function add($data){

        $validate = new validate();
        if(!$validate->scene('add')->check($data)){
            return WSTReturn($validate->getError());
        }
        $result = $this->allowField(true)->save($data);
        if(false !== $result){
            return WSTReturn("新增成功", 1);
        }else{
            return WSTReturn($this->getError());
        }
    }
    /**
     * 编辑
     */
    public function edit($data){
        $validate = new validate();
        if(!$validate->scene('edit')->check($data)){
            return WSTReturn($validate->getError());
        }
        $result = $this->allowField(true)->save($data);
        if(false !== $result){
            return WSTReturn("编辑成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }
    /**
     * 删除
     */
    public function del($sId=0){
        $shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
        $attrId = input('post.attrId/d');
        $data["dataFlag"] = -1;
        $result = $this->save($data,['attrId'=>$attrId,'shopId'=>$shopId]);
        if(false !== $result){
            $where = [];
            $where['shopId'] = $shopId;
            $where['attrId'] = $attrId;
            Db::name('goods_attributes')->where($where)->delete();
            return WSTReturn("删除成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }

    /**
     *
     * 根据ID获取
     */
    public function getById($attrId,$sId=0){
        $shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
        $obj = null;
        if($attrId>0){
            $obj = $this->get(['attrId'=>$attrId,'dataFlag'=>1,'shopId'=>$shopId]);
        }else{
            $obj = self::getEModel("attributes");
        }
        return $obj;
    }

    /**
     * 获取格式化作者信息
     * @param $goods_id
     */
    public function get_format_data($goods_id,$shop = [])
    {
        $data = '';
        if($goods_id){
            $data = Db::name('author')->where(['goods_id' => $goods_id])->find();
        }
        $data = Db::name('author')->where(['goods_id' => $goods_id])->find();

        if(!$data){
            $data = [];
            $data['id'] = 0;
            $data['goods_id'] = isset($shop['shopId'])?$shop['shopId']:0;
            $data['goodsAuthor'] = isset($shop['shopName'])?$shop['shopName']:'';
            $data['goodsAuthorDesc'] = isset($shop['goodsDesc'])?$shop['goodsDesc']:'';
            $data['goodsAuthorImg'] = isset($shop['shopImg'])?$shop['shopImg']:'';;
            $data['szyx'] = '';
            $data['zznb'] = '';
            $data['zdls'] = '';
            $data['created_time'] = '';
            $data['updated_time'] = '';
        }
        //简介
        $data['goodsAuthorDesc'] = htmlspecialchars_decode($data['goodsAuthorDesc']);
        $data['goodsAuthorDesc'] = str_replace('${DOMAIN}',WSTConf('CONF.resourcePath'),$data['goodsAuthorDesc']);
        //年表
        $data['szyx'] = htmlspecialchars_decode($data['szyx']);
        $data['szyx'] = str_replace('${DOMAIN}',WSTConf('CONF.resourcePath'),$data['szyx']);

        return $data;
    }


}
