<?php
namespace wstmart\mobile\model;
use wstmart\common\model\TaxUsers as CTaxUsers;
use think\Db;
use wstmart\home\validate\ShopBase as VShopBase;
class TaxUsers extends CTaxUsers{
    public function getFieldsById($userId,$fields='*'){
        return $this->where(['userId'=>$userId])->field($fields)->find();
    }
}