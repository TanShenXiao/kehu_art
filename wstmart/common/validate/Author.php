<?php
namespace wstmart\common\validate;
use think\Validate;

class Author extends Validate{
    protected $rule = [
        'goods_id' => 'require|max:60',
        //'goodsAuthor' => 'require|max:100',
    ];

    protected $message = [
        'goods_id.require' => '商品id',
        //'goodsAuthor.require' => '作者名',
    ];
    protected $scene = [
        'add'=>['goods_id','goodsAuthor'],
        'edit'=>['goodsAuthor'],
    ];
}