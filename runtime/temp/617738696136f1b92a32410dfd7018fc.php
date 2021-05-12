<?php /*a:2:{s:101:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\recharge\pay_step1.html";i:1602924179;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
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

<link href="__SHOP__/css/recharge.css?v=<?php echo $v; ?>" rel="stylesheet">

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

<div class='wst-shop-content'>
    <div class='pay-sbox'>
    	<div>
    		<div>
				<div class='wst-tips-box'>
				<div class='icon'></div>
					<div class='tips'>
						1.充值金额和赠送金额不能提现；<br/>
					</div>
				<div style="clear:both"></div>
	           	</div>
    		</div>
	   		<div class="wst-form">
			    <div class="pay-type" style="overflow: hidden; float: left;">充值金额：</div>
			    <div>
			       <div class="wst-list-box">
			          <?php if(is_array($chargeItems) || $chargeItems instanceof \think\Collection || $chargeItems instanceof \think\Paginator): $i = 0; $__LIST__ = $chargeItems;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
			          	<div class="wst-frame2 <?php echo $key; ?> " onclick="javascript:changeSelected(<?php echo $item['id']; ?>,'itmeId',this)">
				          	<?php if($item['giveMoney'] > 0): ?>
				          	<div class='charge-doub'>充值 <span class="charge-money"><?php echo $item['chargeMoney']; ?></span> 元</div>
				        	<div>送 <?php echo $item['giveMoney']; ?> 元</div>
				        	<?php else: ?>
				        	<div class='charge-alone'>充值 <span class="charge-money"><?php echo $item['chargeMoney']; ?></span> 元</div>
				          	<?php endif; ?>
				          	<i></i>
			     		</div>
			          <?php endforeach; endif; else: echo "" ;endif; ?>
			          	<div class="wst-frame2 " onclick="javascript:changeSelected(0,'itmeId',this)">
				        	<div class='charge-alone'>
				        		<span class="j-charge-other">其他金额</span>
				        		<span class="j-charge-money"><input class="charge-othermoney j-ipt" id="needPay" value="1" maxlength="10" data-rule="充值金额:required;" onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)" maxlength="8"></span>
				        	</div>
				          <!--<i></i>-->
			   			</div>
			          	<input type="hidden" value="" id='itmeId' class='j-ipt' />
			          <div class='f-clear'></div>
			       </div>
			  		
			    </div>
			    <div style="overflow: hidden;border-top: 1px dashed #eee;border-bottom: 1px dashed #eee;">
			   
			    <div class="pay-type">选择支付方式：</div>
			    <div class="pay-list" style="overflow: hidden;">
			    	<input type="hidden" id="payCode" name="payCode" />
			    	<?php if(is_array($payments) || $payments instanceof \think\Collection || $payments instanceof \think\Paginator): $i = 0; $__LIST__ = $payments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$payment): $mod = ($i % 2 );++$i;if($payment['isOnline'] == 1): ?>
	                    	<div class="wst-payCode-<?php echo $payment['payCode']; ?>" data="<?php echo $payment['payCode']; ?>"></div>
	                 	<?php endif; ?>
	                 <?php endforeach; endif; else: echo "" ;endif; ?>
			         <div class="wst-clear"></div>
			    </div>
			    
	    	    </div>
	    	    <div class="bnt-box">
			    	<button type='button' class='btn btn-success' onclick='javascript:getPayUrl();' class="wst-pay-bnt"><i class='fa fa-shield'></i>确认提交支付</button>
			    </div>
			</div>
	    	
		    
        </div>
    </div>
</div>
<div id="alipayform" style="display:none;"></div>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script type='text/javascript' src='__SHOP__/recharge/recharge.js?v=<?php echo $v; ?>'></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>