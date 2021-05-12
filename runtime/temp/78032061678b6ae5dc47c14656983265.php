<?php /*a:2:{s:99:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\settlements\list.html";i:1602924181;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="/static/plugins/mmgrid/mmGrid.css?v=<?php echo $v; ?>" />
<style type="text/css">
	#mmg1 td:nth-child(4), #mmg1 td:nth-child(5), #mmg1 td:nth-child(6){
		color: red;
	}
</style>

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

<div id='tab' class="wst-tab-box">
	<ul class="wst-tab-nav ">
		<li id="wst-msg-li-0">结算信息<span style="display:none;"></span></li>
		<li id="wst-msg-li-1">未结算订单<span style="display:none;"></span></li>
		<li id="wst-msg-li-2">已结算订单<span style="display:none;"></span></li>
	</ul>
	<div class="wst-tab-content" style='width:100%;'>
	    <div class='wst-tab-item'>
	       <div class="wst-toolbar">
	       <input type="text" id="settlementNo_0" style='width:120px;' autocomplete="off" placeholder="请输入结算单号"/>
						<select id='isFinish_0' autocomplete="off">
								<option value='-1'>结算状态</option>
								<option value='0'>未结算</option>
								<option value='1'>已结算</option>
								</select>
						<a class='s-btn btn btn-primary' onclick="loadGrid()"><i class="fa fa-search"></i>查询</a>
		   </div>
			<div class='wst-grid'>
				<div id="mmg1" class="mmg1"></div>
				<div id="pg1" style="text-align: right;"></div>
			</div>
        </div>
        
        <div class='wst-tab-item'>
            <div class="wst-toolbar">
                <input type="text" id="orderNo_1" style='width:120px;' autocomplete="off" placeholder="请输入订单号"/>
				<a class='s-btn btn btn-primary' onclick="loadUnSettleGrid()"><i class="fa fa-search"></i>查询</a>
            </div>
			<div class='wst-grid'>
				<div id="mmg2" class="mmg2"></div>
				<div id="pg2" style="text-align: right;"></div>
			</div>
        </div>
        <div class='wst-tab-item'>
            <div class="wst-toolbar">
            	<input type="text" id="settlementNo_2" style='width:120px;' autocomplete="off" placeholder="请输入结算单号"/>
				<input type="text" id="orderNo_2" style='width:120px;' autocomplete="off" placeholder="请输入订单号"/>
				<select id='isFinish_2' autocomplete="off">
							    <option value='-1'>结算状态</option>
								<option value='0'>未结算</option>
								<option value='1'>已结算</option>
						  </select>
				<a class='s-btn btn btn-primary' onclick="loadSettleGrid()"><i class="fa fa-search"></i>查询</a>
            </div>
			<div class='wst-grid'>
				<div id="mmg3" class="mmg3"></div>
				<div id="pg3" style="text-align: right;"></div>
			</div>
        </div>
	</div>


<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script type='text/javascript' src='__SHOP__/settlements/settlements.js?v=<?php echo $v; ?>'></script>
	<script>
        $(function(){
            $('#tab').TabPanel({tab:0,callback:function(tab){
                    switch(tab){
                        case 0:getQueryPage(<?php echo $p; ?>);break;
                        case 1:getUnSettledOrderPage(<?php echo $p; ?>);break;
                        case 2:getSettleOrderPage(<?php echo $p; ?>);break;
                    }
                }});
        });
	</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>