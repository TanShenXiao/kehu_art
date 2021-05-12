<?php /*a:2:{s:90:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/users/orders/orders_pay_wallets.html";i:1618416063;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<title>支付订单 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/orders.css?v=<?php echo $v; ?>">

<script type='text/javascript' src='__MOBILE__/frozenui/js/zepto.min.js'></script>
<script type='text/javascript' src='__MOBILE__/frozenui/js/frozen.js'></script>
<script type='text/javascript' src='__MOBILE__/js/laytpl/laytpl.js?v=<?php echo $v; ?>'></script>
<script src="__MOBILE__/js/echo.min.js"></script>
<script type='text/javascript' src='__MOBILE__/js/common.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__MOBILE__/js/searchlist.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"","MOBILE":"__MOBILE__","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPTPWD":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>",HTTP:"<?php echo WSTProtocol(); ?>",'RESOURCE_PATH':'<?php echo WSTConf('CONF.resourcePath'); ?>'}
</script>
</head>
<body ontouchstart="">

    <header class="ui-header ui-header-positive wst-header">
        <a class="ui-icon-return" href='<?php echo Url('mobile/orders/index'); ?>'></a><h1>支付订单</h1>
    </header>


	<input type="hidden" value="<?php echo WSTConf('CONF.pwdModulusKey'); ?>" id="key" autocomplete="off">
   	
	<div class="ui-loading-block" id="Loadl">
	    <div class="ui-loading-cnt">
	        <i class="ui-loading-bright"></i>
	        <p id="j-Loadl">正在加载中...</p>
	    </div>
	</div>
	<section class="ui-container">
	<?php if((empty($message))): ?>
		<input type="hidden" name="" value="<?php echo $data['orderNo']; ?>" id="orderNo" autocomplete="off">
		<input type="hidden" name="" value="<?php echo $data['isBatch']; ?>" id="isBatch" autocomplete="off">
	   	<?php if(is_array($rs['list']) || $rs['list'] instanceof \think\Collection || $rs['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $rs['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$order): $mod = ($i % 2 );++$i;?>
	  	<div class="order-item">
	  		<div class="ui-row-flex item-head">
	   			<div class="ui-col ui-col-2 ui-nowrap-flex">订单号：<?php echo $order['orderNo']; ?><span style="float : right;">邮费：<?php echo $order['deliverMoney']; ?></span></div>
	 		</div>
	     	<?php if(is_array($rs['goods'][$order['orderId']]) || $rs['goods'][$order['orderId']] instanceof \think\Collection || $rs['goods'][$order['orderId']] instanceof \think\Paginator): $i = 0; $__LIST__ = $rs['goods'][$order['orderId']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	    	<div class="ui-row-flex">
	      		<div class="ui-col">
	            	<img src="__RESOURCE_PATH__/<?php echo $vo['goodsImg']; ?>" class="o-Img">
	       		</div>
	         	<div class="ui-col ui-col-3 o-gInfo">
	         		<p class="o-gName ui-nowrap-multi"><?php echo $vo['goodsName']; ?>
						<div class="ui-col order-tr" style="word-break:break-all;padding:5px 0;width: 100%;text-align: left;"><span>x <?php echo $vo['goodsNum']; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #de0202;">¥ <?php echo $vo['goodsPrice']; ?></span></div>
					 </p>
					<?php if(count($vo['goodsSpecNames']) > 0): ?>
	               	<p class="o-gSpec ui-nowrap-flex">规格：
	              		<?php if(is_array($vo['goodsSpecNames']) || $vo['goodsSpecNames'] instanceof \think\Collection || $vo['goodsSpecNames'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['goodsSpecNames'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$spec): $mod = ($i % 2 );++$i;?>
					   		<?php echo $spec; ?>&nbsp;
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</p>
	      			<?php endif; ?>
	       		</div>
	 		</div>
	       	<?php endforeach; endif; else: echo "" ;endif; ?>
	      	<div class="ui-btn-wrap" style="text-align: right;padding:10px 0">
	        	<span class="wst-orders_pricet">总金额：<span class="wst-orders_prices">¥ <?php echo sprintf("%.2f", $order['needPay']);?></span></span>
	      	</div>
	      	<div class="wst-clear"></div>
		</div>
	    <?php endforeach; endif; else: echo "" ;endif; ?>
	    <div class="wst-wa-info">
	    	<p class="info">钱包余额：<span>¥ <?php echo $userMoney; ?></span>，待支付订单总额：<span>¥ <?php echo $needPay; ?></span></p>
	    	<?php if(($userMoney>$rs['totalMoney'])): if(($payPwd==0)): ?>
	    		<p class="pay-info">您尚未设置支付密码，请设置支付密码</p>
	    		<div class="pay">设置密码：<input type="password" id="payPwd" maxlength="6" autocomplete="off"></div>
	    		<div class="pay">确认密码：<input type="password" id="payPwd2" maxlength="6" autocomplete="off"></div>
	    	<?php else: ?>
	    		<div class="pay">支付密码：<input type="password" id="payPwd" maxlength="6" autocomplete="off"></div>
	    	<?php endif; ?>
	    	<?php endif; ?>
	    </div>
	    <?php if(($payPwd==1 && $userMoney>$rs['totalMoney'])): ?><div class="wst-wa-forget ui-whitespace"><a href="<?php echo url('mobile/users/backPayPass'); ?>">忘记密码？</a></div><?php endif; ?>
		<div style="text-align: center;">
			<?php if(($userMoney>$rs['totalMoney'])): ?>
			<button type="button" class="wst-btn-dangerlo" onclick="javascript:walletPay(<?php echo $payPwd; ?>);" style="width: 40%;  display: inline-block;">确认支付</button>
			<?php else: ?>
			<button type="button" class="wst-btn-dangerlo" style="width: 40%;  display: inline-block;" disabled>余额不足</button>
			<?php endif; ?>
		</div>
	<?php else: ?>
		<ul class="ui-row-flex wst-flexslp">
			<li class="ui-col ui-flex ui-flex-pack-center">
			<p><?php echo $message; ?></p>
			</li>
		</ul>
	<?php endif; ?>
	</section>




<script type="text/javascript" src="/static/js/rsa.js"></script>
<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/users/orders/orders_list.js?v=<?php echo $v; ?>'></script>
<script>
$(document).ready(function(){
	backPrevPage(WST.U('mobile/orders/index'));
});
</script>

</body>
</html>