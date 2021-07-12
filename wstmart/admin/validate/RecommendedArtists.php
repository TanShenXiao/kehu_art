<?php
namespace wstmart\admin\validate;
use think\Validate;

class RecommendedArtists extends Validate{

    protected $rule = [
        'shop_id|店铺id' => 'require',
        'top|是否置顶'  => 'require',
        'sort|排序' => 'require',
        'desc|简介' => 'require',
        'analysis|分析' => 'require',
        'story|故事' => 'require',
    ];

    protected $scene = [
        'add'   =>  ['shop_id','top','sort','desc','analysis','story'],
        'edit'  =>  ['top','sort','desc','analysis','story'],
    ];
}