<?php /*a:2:{s:69:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/settlement.html";i:1618415181;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<title>确认订单 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/settlement.css?v=<?php echo $v; ?>">

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

    <header style="background:#ffffff;" class="ui-header ui-header-positive wst-header">
    	<a href="<?php echo url('mobile/carts/index'); ?>"><i class="ui-icon-return"></i></a><h1>确认订单</h1>
    </header>


     <?php $shopFreight = 0; ?>
     <input type="hidden" name="" value="1" id="sign" autocomplete="off">
     <section class="ui-container" style="border-bottom: 86px solid transparent;">
		<ul class="ui-list ui-list-text ui-list-link wst-se-address">
			<input type="hidden" name="" value="<?php if(isset($userAddress['addressId'])): ?><?php echo $userAddress['addressId']; ?><?php endif; ?>" id="addressId" autocomplete="off">
		    <input type="hidden" name="" value="<?php if(isset($userAddress['addressId'])): ?><?php echo $userAddress['areaId2']; ?><?php endif; ?>" id="areaId" autocomplete="off">
		    
		    <?php if(empty($userAddress)): ?>
		    <li onclick="javascript:addAddress(1);"><h4><p class="infono">您还没添加收货地址，请添加。</p></h4>
		    <?php else: ?>
		    <li onclick="javascript:addAddress(1,<?php echo $userAddress['addressId']; ?>);"><h5>
		    	<p class="infot"><?php echo $userAddress['userName']; ?>    <?php echo $userAddress['userPhone']; ?></p>
		    	<p class="infob"><i class="ui-icon-pin"></i><?php echo $userAddress['areaName']; ?><?php echo $userAddress['userAddress']; ?></p>
		    </h5></li>
		    <?php endif; ?>
		    
		</ul>
		<?php if(is_array($carts['carts']) || $carts['carts'] instanceof \think\Collection || $carts['carts'] instanceof \think\Paginator): $i = 0; $__LIST__ = $carts['carts'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ca): $mod = ($i % 2 );++$i;
		if($ca['isFreeShipping']){
			$freight = 0;
		}else{
	        if(!empty($userAddress)){
	            $freight = WSTOrderFreight($ca['shopId'],$userAddress['areaId2'],$ca);
	            $shopFreight = $shopFreight + $freight;
	        }else{
	            $freight = 0;
	            $shopFreight = $shopFreight + $freight;
	        }
        }
        $shopFreight = $shopFreight + $freight;
         ?>
		<div class="wst-se-sh">
			<p class="ui-nowrap-flex shopn" shopId="<?php echo $ca['shopId']; ?>"><img src="/<?php echo $ca['shopImg']; ?>" alt=""> <?php echo $ca['shopName']; ?></p>
			<?php if(is_array($ca['list']) || $ca['list'] instanceof \think\Collection || $ca['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $ca['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($i % 2 );++$i;?>
			<?php echo hook('mobileDocumentSettlementGoodsPromotion',['goods'=>$li]); ?>
			<ul class="ui-row goods j-g<?php echo $li['cartId']; ?>">
			    <li class="ui-col ui-col-25">
			    	<div class="img j-imgAdapt">
				    	<a href="javascript:void(0);" onclick="javascript:WST.intoGoods(<?php echo $li['goodsId']; ?>);">
				    	<img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__RESOURCE_PATH__/<?php echo WSTImg($li['goodsImg'],3); ?>" title="<?php echo WSTStripTags($li['goodsName']); ?>">
				    	</a>
			    	</div>
			    </li>
			    <li class="ui-col ui-col-75">
			    	<ul class="ui-row info">
			    		<li class="ui-col ui-col-75">
			    			<div class="name"><p class="names"><?php echo $li['goodsName']; ?></p>
			    			<?php if(($li['specNames'])): ?>
			    			<p class="spec">规格：
			    			<?php if(is_array($li['specNames']) || $li['specNames'] instanceof \think\Collection || $li['specNames'] instanceof \think\Paginator): $i = 0; $__LIST__ = $li['specNames'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sp): $mod = ($i % 2 );++$i;?>
								<?php echo $sp['catName']; ?>:<?php echo $sp['itemName']; ?>
							<?php endforeach; endif; else: echo "" ;endif; ?>
			    			</p>
			    			<?php endif; ?></div>
			    		</li>
					    <li class="ui-col ui-col-25"><p class="price" id="price_<?php echo $li['cartId']; ?>" mval="<?php echo $li['shopPrice']; ?>">¥<?php echo $li['shopPrice']; ?></p><p class="number" id="number_<?php echo $li['cartId']; ?>" mval="<?php echo $li['cartNum']; ?>">×<?php echo $li['cartNum']; ?></p></li>
					</ul>
			    </li>
			</ul>
			<?php endforeach; endif; else: echo "" ;endif; ?>

			<?php echo hook('mobileDocumentCartShopPromotion',$ca); ?>

			<div class="cost">
				<div>运费：<span id="shopF_<?php echo $ca['shopId']; ?>">¥<?php echo sprintf("%.2f", $freight); ?></span></div>
				<div id="reward_<?php echo $ca['shopId']; ?>" style="display:none;">立减：<span id="shopF_<?php echo $ca['shopId']; ?>">-&ensp;¥<?php echo sprintf("%.2f", $ca['promotionMoney']); ?></span></div>
				<div>店铺合计(含运费)：<span id="shopC_<?php echo $ca['shopId']; ?>">¥<?php echo sprintf("%.2f", $freight+$ca['goodsMoney']-$ca['promotionMoney']); ?></span></div>
			</div>
			<div class="remarks">
			<textarea id="remark_<?php echo $ca['shopId']; ?>" autocomplete="off" placeholder="填写订单备注："></textarea>
			</div>
		</div>
		<?php endforeach; endif; else: echo "" ;endif; ?>
		<ul class="ui-list ui-list-text ui-list-link ui-list-active wst-se-mode">
		    <li class="mode" onclick="javascript:dataShow('payments');">
		        <h4 class="ui-nowrap">支付方式</h4>
		        <div class="ui-txt-info" id="paymentst"><?php if(!empty($payments['0']) || !empty($payments['1'])): if(!empty($payments['1'])): ?><?php echo $payments['1']['0']['payName']; else: ?><?php echo $payments['0']['0']['payName']; ?><?php endif; else: ?>无<?php endif; ?></div>
		    </li>
		    <li class="mode" onclick="javascript:dataShow('gives');">
		        <h4 class="ui-nowrap">配送方式</h4>
		        <div class="ui-txt-info" id="givest">快递运输</div>
		    </li>
		    <li class="<?php if((WSTConf('CONF.isOpenScorePay')==1)): ?>mode<?php endif; ?>" onclick="javascript:getInvoiceList();">
		        <h4 class="ui-nowrap">发票信息</h4>
		        <div class="ui-txt-info" id="invoicest">不开发票</div>
		    </li>
		    <?php if((WSTConf('CONF.isOpenScorePay')==1)): ?>
		   	<li onclick="javascript:dataShow('score');">
		        <h4 class="ui-nowrap">积分支付</h4>
		        <div class="ui-txt-info" id="scoret">否</div>
		    </li>
		    <?php endif; ?>
		</ul>
     </section>


		<?php $shopFreight = 0;$shopIds = ''; if(is_array($carts['carts']) || $carts['carts'] instanceof \think\Collection || $carts['carts'] instanceof \think\Paginator): $i = 0; $__LIST__ = $carts['carts'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$car): $mod = ($i % 2 );++$i;
		if($car['isFreeShipping']){
          	$freight = 0;
		}else{
	        if(!empty($userAddress)){
	            $freight = WSTOrderFreight($car['shopId'],$userAddress['areaId2'],$car);
	        }else{
	            $freight = 0;
	        }
        }
        $shopFreight = $shopFreight + $freight;
         ?>
        <?php endforeach; endif; else: echo "" ;endif; ?>
		
		<div class="ui-loading-block" id="Loadl">
		    <div class="ui-loading-cnt">
		        <i class="ui-loading-bright"></i>
		        <p id="j-Loadl">正在加载中...</p>
		    </div>
		</div>
        <footer class="ui-footer wst-footer-btns" style="height:85px; border-top: 1px solid #e8e8e8;" id="footer">
        	<input type="hidden" name="" value="<?php if(empty($userAddress)): ?><?php echo sprintf("%.2f", $carts["goodsTotalMoney"]); else: ?><?php echo sprintf("%.2f", $carts["goodsTotalMoney"]+$shopFreight); ?><?php endif; ?>" id="totalPrice" autocomplete="off">
			<div class="wst-se-total">应付总金额(含运费)：<span id="totalMoney">
			¥<?php if(empty($userAddress)): ?>
             <?php echo sprintf("%.2f", $carts["goodsTotalMoney"]-$carts['promotionMoney']); else: ?>
             <?php echo sprintf("%.2f", $carts["goodsTotalMoney"]+$shopFreight-$carts['promotionMoney']); ?>
             <?php endif; ?></span></div>
			<div class="wst-se-confirm"><button class="button" onclick="javascript:submitOrder();">确定</button></div>
        </footer>



<div class="wst-cover" id="cover"></div>

<?php if(!empty($payments['0']) || !empty($payments['1'])): ?>
<input type="hidden" name="" value="<?php if(!empty($payments['1'])): ?>1<?php else: ?>0<?php endif; ?>" id="paymentsh" autocomplete="off">
<input type="hidden" name="" value="<?php if(!empty($payments['1'])): ?><?php echo $payments['1']['0']['payCode']; else: ?><?php echo $payments['0']['0']['payCode']; ?><?php endif; ?>" id="paymentsw" autocomplete="off">
<div class="wst-fr-box frame" id="payments">
	<div class="title"><span>支付方式</span><i class="ui-icon-close-page" onclick="javascript:dataHide('payments');"></i><div class="wst-clear"></div></div>
	<div class="content" id="content">
    <?php if(!empty($payments)): $paymentkey = 0; if(is_array($payments) || $payments instanceof \think\Collection || $payments instanceof \think\Paginator): $i = 0; $__LIST__ = $payments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$paymentvo): $mod = ($i % 2 );++$i;if(is_array($paymentvo) || $paymentvo instanceof \think\Collection || $paymentvo instanceof \think\Paginator): $i = 0; $__LIST__ = $paymentvo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$paymentitem): $mod = ($i % 2 );++$i;?>
          <ul class="ui-list" onclick="javascript:onSwitch(this);" style="border-bottom: 1px solid #f2f1f1;">
             <li><div class="wst-list-infose1" style="padding-left: 25px;"><i class="<?php echo $paymentitem['payCode']; ?>"></i><span><?php echo $paymentitem['payName']; ?></span></div></li>
             <i class="ui-icon-push payments_<?php echo $paymentitem['payCode']; ?> ui-icon-checked-s" payCode="<?php echo $paymentitem['payCode']; ?>" mode="<?php echo $paymentitem['isOnline']; ?>" word="<?php echo $paymentitem['payName']; ?>"></i>
          </ul>
          <?php $paymentkey++; ?>
          <?php endforeach; endif; else: echo "" ;endif; ?>
       <?php endforeach; endif; else: echo "" ;endif; ?>
    <?php endif; ?>
	</div>
	<button class="button" onclick="javascript:inDetermine('payments');">确定</button>
</div>
<?php endif; ?>

<input type="hidden" name="" value="0" id="givesh" autocomplete="off">
<input type="hidden" name="" value=<?php echo $bindPhone; ?> id="bindPhone" />
<div class="wst-fr-box frame" id="gives">
	<div class="title"><span>配送方式</span><i class="ui-icon-close-page" onclick="javascript:dataHide('gives');"></i><div class="wst-clear"></div></div>
	<div class="content" id="content">
         <ul class="ui-list" onclick="javascript:onSwitch(this);">
             <li><div class="wst-list-infose1"><span>快递运输</span></div></li>
             <i class="ui-icon-push gives0 ui-icon-checked-s wst-active" mode="0" word="快递运输"></i>
         </ul>
		 <div class="wst-se-line"><p></p></div>
         <ul class="ui-list" onclick="javascript:onSwitch(this);">
             <li><div class="wst-list-infose1"><span>自提</span></div></li>
             <i class="ui-icon-push gives1 ui-icon-unchecked-s" mode="1" word="自提"></i>
			 <span id="msgBindPhone" style="color:red;margin-left:10px;display:none">自提订单需要绑定手机，点击确定后将前往绑定</span>
         </ul>
	</div>
	<button class="button" onclick="javascript:inDetermine('gives');">确定</button>
</div>




<script type="text/html" id="invoiceBox">
	{{# for(var i = 0; i < d.length; i++){ }}
		<li invId="{{d[i].id}}" invCode="{{d[i].invoiceCode}}">{{d[i].invoiceHead}}</li>
	{{# } }}
</script>


<div class="invoice_box" id="frame">
    <div class="title" id="boxTitle"><span>发票信息</span><i class="ui-icon-close-page" onclick="javascript:invoiceHide();"></i><div class="wst-clear"></div></div>
    <div class="content" id="invoice_content">
		<div class="inv_item">
			<input type="hidden" id="isInvoice" value="0" />
			<div class="inv_tit inv_line">发票内容</div>
			<ul class="ui-list inv_ul none_float" onclick="javascript:isInvoice(this,0);">
	             <li><div class="pdtb10"><span>不开发票</span></div></li>
	             <i class="ui-icon-push invoices0 ui-icon-checked-s wst-active inv_chk" mode="0" word="不开发票"></i>
	        </ul>
	        <div style="float:left;color:red;margin-left:10px;">如需开具发票，请直接联系商家</div>
	        <div class="wst-clear"></div>
		</div>
		<button class="button" onclick="javascript:saveInvoice();">确定</button>
    </div>
</div>



<input type="hidden" name="" value="0" id="scoreh" autocomplete="off">
<div class="wst-fr-box frame" id="score">
	<div class="title"><span>积分支付</span><i class="ui-icon-close-page" onclick="javascript:dataHide('score');"></i><div class="wst-clear"></div></div>
	<div class="content" id="content">
         <ul class="ui-list" onclick="javascript:onSwitch(this);">
             <li><div class="wst-list-infose1"><span>是</span></div></li>
             <i class="ui-icon-push score1 ui-icon-checked-s wst-active" mode="1" word="是"></i>
         </ul>
		 <div class="wst-se-line"><p></p></div>
         <ul class="ui-list" onclick="javascript:onSwitch(this);">
             <li><div class="wst-list-infose1"><span>否</span></div></li>
             <i class="ui-icon-push score0 ui-icon-unchecked-s" mode="0" word="否"></i>
         </ul>
         <div class="wst-fr-score">（可用<span id="userOrderScore"><?php echo $userOrderScore; ?></span>个积分，可抵<span>¥<span id="userOrderMoney"><?php echo $userOrderMoney; ?></span></span>）</div>
	</div>
	<button class="button" onclick="javascript:inDetermine('score');">确定</button>
</div>


<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/settlement.js?v=<?php echo $v; ?>'></script>

</body>
</html>