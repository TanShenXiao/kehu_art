<?php
namespace wstmart\admin\validate;
use think\Validate;

class ManualOrders extends Validate{
	protected $rule = [
		'goods_name|商品名称' => 'require',
		'price|价格' => 'require|number|min:0',
		'tax_price|税费' => 'require|number|min:0',
		'is_invoice|是否开发票' => 'in:0,1',
		'goods_type|商品类别' => 'number',
	];
	protected $scene = [
		'add'=>[
		    'goods_name',
		    'price',
		    'tax_price',
		    'is_invoice',
		    'goods_type',
        ],
		'edit'=>['attrName'],
	];
}