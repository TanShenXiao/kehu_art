<?php
namespace wstmart\mobile\model;
use wstmart\common\model\TaxUsers as CTaxUsers;
use think\Db;
use wstmart\home\validate\ShopBase as VShopBase;
class TaxUsers extends CTaxUsers{
    public function getFieldsById($userId,$fields='*'){
        return $this->where(['userId'=>$userId])->field($fields)->find();
    }
    public function add($data){
        if($data['id']>0){
            $id= $data['id'];
            unset($data['id']);
            $res = $this->update($data,['id'=>$id]);
        }else{
            $data['create_time'] = time();
            $res = $this->insert($data);
        }

        if($res)   return WSTReturn('保存成功', 1);
        else{
            return WSTReturn('保存失败', 0);
        }
    }
}