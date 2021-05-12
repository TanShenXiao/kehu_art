<?php /*a:2:{s:97:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\security\index.html";i:1602924180;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<title>卖家中心 - <?php echo WSTConf('CONF.mallName'); ?></title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="/static/plugins/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/layui/css/layui.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css" type="text/css" />
<script src="__SHOP__/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>

<link href="__SHOP__/css/security.css?v=<?php echo $v; ?>" rel="stylesheet">

<link href="__SHOP__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<?php 
$msgGrant = [];
if(WSTShopGrant('shop/messages/shopMessage'))$msgGrant[] = 'message';
if(WSTShopGrant('shop/orders/waitdelivery'))$msgGrant[] = 'shoporder24';
if(WSTShopGrant('shop/orders/waituserPay'))$msgGrant[] = 'shoporder55';
if(WSTShopGrant('shop/orders/failure'))$msgGrant[] = 'shoporder45';
if(WSTShopGrant('shop/ordercomplains/shopComplain'))$msgGrant[] = 'shoporder25';
if(WSTShopGrant('shop/goods/stockWarnByPage'))$msgGrant[] = 'shoporder54';
 ?>
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>",'RESOURCE_PATH':'<?php echo WSTConf('CONF.resourcePath'); ?>',"SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>",'TIME_TASK':1,"MESSAGE_BOX":"<?php echo WSTShopMessageBox(); ?>",MSG_SHOP_GRANT:'<?php echo implode(',',$msgGrant); ?>'}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__SHOP__/img/ajax-loader.gif"/></div>

	<div class="wst-sec-info">
	<img class="wst-lfloat usersImg" data-original='<?php echo WSTUserPhoto($data['userPhoto']); ?>' width='100' height='100' title="<?php echo WSTStripTags($data['loginName']); ?>"/>
	<div class="wst-sec-infor">
	<span class="wst-sec-na wst-lfloat"><?php echo $data['loginName']; ?></span>
	<?php if(($data['ranks'])): ?>
	<span class="wst-sec-grade"><img class="wst-lfloat" src="__RESOURCE_PATH__/<?php echo $data['ranks']['userrankImg']; ?>"/><span class="wst-lfloat"><?php echo $data['ranks']['rankName']; ?></span></span>
	<?php endif; ?>
	<div style='clear:both;'></div>
	<div style="margin-top:6px;">
	<span class="wst-sec-infoi" id="level"></span>
	<div class="wst-sec-infoi" style="margin-top:5px;">
		<span class="wst-sec-strip wst-lfloat"><?php if(($data['loginPwd'])): ?><span class="wst-sec-strip2 wst-lfloat"></span><?php endif; if(($data['userEmail'])): ?><span class="wst-sec-strip2 wst-lfloat"></span><?php endif; if(($data['userPhone'])): ?><span class="wst-sec-strip2 wst-lfloat"></span><?php endif; ?></span>
	</div>
	<div class="wst-sec-infoi" style="margin-top:5px;">
		<span>上次登录时间：<?php echo date("Y年m月d日 H:i:s",strtotime($data['lastTime'])); ?></span><br/>
		<span>上次登录IP：<?php echo $data['lastIP']; ?></span>
	</div>
	</div>
	</div>
	<div style='clear:both;'></div>
	</div>
	<div class="wst-sec-s">
		<div class="wst-sec-lists"><?php if(($data['loginPwd'])): ?><span class="wst-sec-green"><img src="__SHOP__/img/user_icon_yyz.png"/>已设置</span><?php else: ?><span class="wst-sec-ash"><img src="__SHOP__/img/icon_wyz.png"/>未设置</span><?php endif; ?>
		<span class="wst-sec-w">&nbsp;登录密码</span>&nbsp;&nbsp;登录密码用于会员的登录以及进行登录的一系列操作，<?php if(($data['loginPwd'])): ?>建议您定期更改密码<?php else: ?>您还没有设置密码<?php endif; ?>
			<a class="wst-user-buta wst-rfloat btn btn-blue" style="margin-top: 16px;" onclick="location.href=WST.U('shop/users/editPass')"><i class="fa fa-pencil"></i><?php if(($data['loginPwd'])): ?>修改密码<?php else: ?>立即设置<?php endif; ?></a></div>
		<div class="wst-sec-lists"><?php if(($data['payPwd'])): ?><span class="wst-sec-green"><img src="__SHOP__/img/user_icon_yyz.png"/>已设置</span><?php else: ?><span class="wst-sec-ash"><img src="__SHOP__/img/icon_wyz.png"/>未设置</span><?php endif; ?>
		<span class="wst-sec-w">&nbsp;支付密码</span>&nbsp;&nbsp;支付密码用于会员的提现账号设置及提现申请操作，<?php if(($data['payPwd'])): ?>建议您定期更改支付密码<?php else: ?>您还没有设置支付密码<?php endif; ?>
		<a class="wst-user-buta wst-rfloat btn btn-blue" style="margin-top: 16px;" onclick="location.href=WST.U('shop/users/editPayPass')"><i class="fa fa-pencil"></i><?php if(($data['payPwd'])): ?>修改密码<?php else: ?>立即设置<?php endif; ?></a></div>
		<div class="wst-sec-lists"><?php if(($data['userEmail'])): ?><span class="wst-sec-green"><img src="__SHOP__/img/user_icon_yyz.png"/>已验证</span><?php else: ?><span class="wst-sec-ash"><img src="__SHOP__/img/icon_wyz.png"/>未验证</span><?php endif; ?>
		<span class="wst-sec-w">&nbsp;邮箱验证</span>&nbsp;&nbsp;邮箱验证方便您及时接收优惠促销信息，以及积分、优惠卷和账户余额变动的提醒。<?php if(($data['userEmail'])): ?>您的绑定邮箱：<?php echo $data['userEmail']; else: ?>您还没有验证邮箱<?php endif; ?>
		<a class="wst-user-buta wst-rfloat btn btn-blue" style="margin-top: 16px;" onclick="location.href=WST.U('shop/users/editEmail')"><i class="fa fa-pencil"></i><?php if(($data['userEmail'])): ?>修改邮箱<?php else: ?>立即验证<?php endif; ?></a></div>
		<div class="wst-sec-lists"><?php if(($data['userPhone'])): ?><span class="wst-sec-green"><img src="__SHOP__/img/user_icon_yyz.png"/>已验证</span><?php else: ?><span class="wst-sec-ash"><img src="__SHOP__/img/icon_wyz.png"/>未验证</span><?php endif; ?>
		<span class="wst-sec-w">&nbsp;手机验证</span>&nbsp;&nbsp;手机验证方便您及时接收优惠促销信息，以及积分、优惠卷和账户余额变动的提醒。<?php if(($data['userPhone'])): ?>您的绑定手机：<?php echo $data['userPhone']; else: ?>您还没有验证手机<?php endif; ?>
		<a class="wst-user-buta wst-rfloat btn btn-blue" style="margin-top: 16px;" onclick="location.href=WST.U('shop/users/editPhone')"><i class="fa fa-pencil"></i><?php if(($data['userPhone'])): ?>修改手机<?php else: ?>立即验证<?php endif; ?></a></div>
		<div style='clear:both;'></div>
	</div>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script>
$(function(){
	var securityCount = $('.wst-sec-strip2').length;
	if(securityCount==1){
	   $('#level').html('安全级别(较低级)&nbsp; &nbsp; &nbsp;<span class="wst-sec-infoin">建议提升安全</span>');
	}else if(securityCount==2){
	   $('#level').html('安全级别(中级)&nbsp; &nbsp; &nbsp;<span class="wst-sec-infoin">建议提升安全</span>');
	}else if(securityCount==3){
	   $('#level').html('安全级别(高级)&nbsp; &nbsp; &nbsp;<span class="wst-sec-infoin">建议定期更改密码</span>');
	}else{
	   $('#level').html('安全级别(低级)&nbsp; &nbsp; &nbsp;<span class="wst-sec-infoin">账号有风险</span>');
	}
});
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>