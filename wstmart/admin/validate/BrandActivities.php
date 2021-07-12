<?php 
namespace wstmart\admin\validate;
use think\Validate;

class RecommendedArtists extends Validate{
	protected $rule = [
	    'shop_id|店铺id' => 'require',
		'top|是否置顶'  => 'require',
		'sort|排序' => 'require',
    ];

    protected $scene = [
        'add'   =>  ['shop_id','top','sort'],
        'edit'  =>  ['shop_id','top','sort'],
    ]; 
}