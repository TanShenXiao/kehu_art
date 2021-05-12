<?php
return array(
	'warn'=>array(
		'title'=>'<span style="color:red;font-size:20px;">【特别提醒】使用之前请先阅读阿里云-云通信短信发送规则</span>',
		'type'=>'hidden',
		'value'=>''
	),
	'smsKey'=>array(
		'title'=>'Access Key ID<span style="color:#FF6666;">【购买短信服务请点击<a target="_blank" href="https://dayu.aliyun.com/settled">阿里云-云通信短信</a>购买】',
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'smsPass'=>array(
		'title'=>'Access Key Secret',
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'signature'=>array(
		'title'=>'短信签名',
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_USER_REGISTER_VERFIY'=>array(
		'title'=>'用户注册验证码模板ID【模板参考：您的注册验证码为:$&#123;VERFIYCODE}，请及时输入。】',
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_BIND'=>array(
		'title'=>'绑定手机提醒模板ID【模板参考：您的正在操作绑定手机，校验码为:$&#123;VERFIYCODE}，请及时输入。】',
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_EDIT'=>array(
		'title'=>"更改手机提醒模板ID【模板参考：您正在操作修改手机，您的校验码为:$&#123;VERFIYCODE}，请及时输入。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_FOTGET'=>array(
		'title'=>"忘记密码模板ID【模板参考：您正在操作忘记密码功能，您的校验码为:$&#123;VERFIYCODE}，请及时输入。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_FOTGET_PAY'=>array(
		'title'=>"忘记支付密码模板ID【模板参考：您正在重置支付密码，验证码为:$&#123;VERFIYCODE}，请及时输入。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_USER_SHOP_OPEN_SUCCESS'=>array(
		'title'=>"开店成功提醒模板ID【模板参考：您申请成为$&#123;MALLNAME}商家的请求已通过。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_SHOP_OPEN_FAIL'=>array(
		'title'=>"开店失败提醒模板ID【模板参考：您申请成为$&#123;MALLNAME}商家的请求未通过，请登录系统查看详情。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_USER_CONSUME_VERFIY'=>array(
		'title'=>"线下收银余额或积分支付验证码模板ID【模板参考：您正在下钱使用余额或积分支付，验证码为:$&#123;VERFIYCODE}，请及时输入。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_COMMON_VERFIY'=>array(
		'title'=>"通用验证码模板ID【模板参考：您的验证码为：$&#123;VERFIYCODE}，请勿向任何人提供此验证码。如非本人操作，请忽略本短信。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_USER_BDDELIVERY_VERFIY'=>array(
		'title'=>'保底交易提货验证码模板ID【模板参考：您购买的保底交易商品$&#123;GOODSINFO}已下单成功，提货验证码为:$&#123;CODE}，请凭借验证码和注册手机号到提货点$&#123;SHOPADRR}提货。】',
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_USER_DELIVERY_VERFIY'=>array(
		'title'=>'自提订单提货验证码模板ID【模板参考：您购买的$&#123;GOODSINFO}已下单成功，提货验证码为:$&#123;CODE}，请凭借验证码和注册手机号到商家提货点$&#123;SHOPADRR}提货。】',
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_USER_SIGNUP'=>array(
		'title'=>'报名成功提醒短信模板ID【模板参考：尊敬的会员$&#123;LOGINNAME}，您报名的$&#123;SIGNUPTITLE}已提交成功，感谢您的参与。】',
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'line'=>array(
		'title'=>'&nbsp;',
		'type'=>'hidden',
		'value'=>''
	),
	'warn_admin'=>array(
		'title'=>'<span style="color:blue;font-size:18px;padding-top:10px">管理员短信提醒</span>',
		'type'=>'hidden',
		'value'=>''
	),
	'PHONE_ADMIN_SUBMIT_ORDER'=>array(
		'title'=>"管理员-用户下单提醒模板ID【模板参考：有新的订单[$&#123;ORDERNO}]，请留意。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_ADMIN_PAY_ORDER'=>array(
		'title'=>"管理员-支付订单提醒模板ID【模板参考：用户已支付订单[$&#123;ORDERNO}]，请留意。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_ADMIN_CANCEL_ORDER'=>array(
		'title'=>"管理员-取消订单提醒模板ID【模板参考：订单[$&#123;ORDERNO}]已被用户取消。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_ADMIN_REJECT_ORDER'=>array(
		'title'=>"管理员-拒收订单提醒模板ID【模板参考：订单[$&#123;ORDERNO}]已被用户拒收。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_ADMIN_REFUND_ORDER'=>array(
		'title'=>"管理员-申请退款提醒模板ID【模板参考：用户申请订单[$&#123;ORDERNO}]退款，请及时处理。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_ADMIN_COMPLAINT_ORDER'=>array(
		'title'=>"管理员-订单投诉提醒模板ID【模板参考：用户投诉订单[$&#123;ORDERNO}]，请及时处理。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	),
	'PHONE_ADMIN_CASH_DRAWS'=>array(
		'title'=>"管理员-申请提现提醒模板ID【模板参考：有新的用户申请提现请求，请及时处理。】",
		'type'=>'text',
		'value'=>'',
		'tips'=>''
	)
);
