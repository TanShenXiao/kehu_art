<?php 
namespace wstmart\admin\validate;
use think\Validate;

class BrandActivities extends Validate{
	protected $rule = [
	    'title' => 'require|max:60',
		'cover_img'  => 'require',
		'target_url' => 'require',
    ];

    protected $message = [
        'title.require' => '活动名称',
        'title.max' => '品牌名称不能超过60个字符',
        'cover_img.require' => '活动封面图',
        'target_url.require' => '目标链接',
    ];

    protected $scene = [
        'add'   =>  ['title','cover_img','target_url'],
        'edit'  =>  ['title','cover_img','target_url']
    ]; 
}