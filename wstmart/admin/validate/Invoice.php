<?php
namespace wstmart\admin\validate;
use think\Validate;

class Invoice extends Validate{
	protected $rule = [
		'agent|代办人' => 'require',
		'write_invoice|代开发票时间' => 'require',
		'reviewer|审核人' => 'require',
		'reviewer_time|审核时间' => 'require',
		'price|代缴费' => 'require|number|min:0',
		'freight_price|运费' => 'require|number|min:0',
		'freight_no|运单号' => 'require',
	];
	protected $scene = [
		'add'=>[
		    'agent',
		    'write_invoice',
		    'reviewer',
		    'reviewer_time',
		    'price',
		    'freight_price',
		    'freight_no',
        ],
		'edit'=>['attrName'],
	];
}