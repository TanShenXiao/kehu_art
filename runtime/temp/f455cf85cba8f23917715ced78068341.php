<?php /*a:4:{s:70:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/users/index.html";i:1618413039;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/footer.html";i:1618386322;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/dialog.html";i:1579267090;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<title>我的 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/user.css?v=<?php echo $v; ?>">

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


    <section class="ui-container">
    	<div class="wst-users_info">
    		
	   		<i class="wst-info-icon" onclick="location.href='<?php echo url('mobile/users/userset'); ?>'" >设置</i>
	     	<div class="ui-row-flex" style="height:0.7rem">
	     		<div class="ui-col ui-col-4">
	     			<div class="wst-users_infol" id="previewImages">
	     			    <img src="<?php echo WSTUserPhoto($user['userPhoto']); ?>" class="wst-useri_portrait" id="imgurl">
	     			</div>
	     			<p class="wst-users_infor wst-users_infortop">
	     			<?php echo $user['userName']?$user['userName']:$user['loginName']; ?>
					 <a href="<?php echo url('mobile/messages/index'); ?>"><i class="wst-msg-icon">
						<?php if(($data['message']['num']>0)): ?>
					 <span class="number" id="msgNum"><?php echo $data['message']['num']; ?></span>
					 <?php endif; ?>
					</i></a>
	     			</p>
					<?php if(($user['userId'] > 0 && $user['ranks']['rankName']!='')): ?>
					<p class="wst-users_infor wst-users_inforbo"><?php echo $user['ranks']['rankName']; ?></p>
					<?php endif; ?>
	     		</div>
			   <div class="ui-col">
			    	<?php $signScore=explode(",",WSTConf('CONF.signScore')); ?>
			   </div>
			</div>
			<div class="top_bottom">
				<li>
					<a href="<?php echo url('mobile/logmoneys/usermoneys'); ?>">
						<p class="ui-badge-wrap ui-nowrap"><span>¥ </span><?php echo $user['userMoney']; ?></p>
						<span class="ui-flex ui-flex-align-end ui-flex-pack-center">我的余额</span>
					</a>
				</li>
				<li>
					<a href="<?php echo url('mobile/userscores/index'); ?>">
						<p class="ui-badge-wrap ui-nowrap" id="currentScore"><?php echo $user['userScore']; ?></p>
						<span class="ui-flex ui-flex-align-end ui-flex-pack-center">我的积分</span>
					</a>
				</li>
				<li>
					<a href="<?php echo url('mobile/favorites/goods'); ?>">
						<p class="ui-badge-wrap ui-nowrap" id="currentScore"><?php echo $user['subGoods']; ?></p>
						<span class="ui-flex ui-flex-align-end ui-flex-pack-center">关注商品</span>
					</a>
		    	</li>
				<li>
					<a href="<?php echo url('mobile/favorites/shops'); ?>">
						<p class="ui-badge-wrap ui-nowrap" id="currentScore"><?php echo $user['subShop']; ?></p>
						<span class="ui-flex ui-flex-align-end ui-flex-pack-center">关注店铺</span>
					</a>
				</li>
			</div>
		</div>
		<?php if($user['userId']>0): ?>
			<?php echo hook('mobileDocumentUserIndex'); ?>
		<?php endif; if(($user['userType']==1)): $shopMenus = WSTShopOrderMenus();if((count($shopMenus)>0)): ?>
			<div class="user-order">
				<ul class="ui-row order">
		    		<li class="ui-col ui-col-50"><i class="order-icon"></i>商家订单管理</li>
		    		<li class="ui-col ui-col-50 view-order" onclick="location.href='<?php echo url('mobile/orders/sellerorder'); ?>'">查看全部订单  ></li>
		    	</ul>
	    	</div>
	    	<?php endif; ?>
		<?php endif; ?>

		
		<div class="user-order">
			<ul class="ui-row order">
				<li class="ui-col ui-col-50">保底交易</li>
				<li class="ui-col ui-col-50 view-order" onclick="location.href='<?php echo url('mobile/goods/mygoods'); ?>'">查看全部  ></li>
			</ul>
		</div>

		
		<div class="user-order">
			<ul class="ui-row order">
	    		<li class="ui-col ui-col-50">我的订单</li>
	    		<li class="ui-col ui-col-50 view-order" onclick="location.href='<?php echo url('mobile/orders/index'); ?>'">查看全部  ></li>
	    	</ul>
    	</div>
		<div class="ui-row-flex ui-whitespace wst-users_icon">
			
		    <div class="ui-col ui-col">
		    <a href="<?php echo url('mobile/orders/index',['type'=>'waitPay']); ?>">
		    	<p class="ui-badge-wrap">
		    		<i class="wst-users_icon1"></i>
		    		<?php if(($data['order']['waitPay']>0)): ?>
		    		<span class="ui-badge-corner wait-payment ui-nowrap-flex ui-whitespace" id="waitPay"><?php echo $data['order']['waitPay']; ?></span>
		    		<?php endif; ?>
		    	</p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">待付款</span>
		    </a>
		    </div>
		    <div class="ui-col ui-col">
		    <a href="<?php echo url('mobile/orders/index',['type'=>'waitDeliver']); ?>">
		    	<p class="ui-badge-wrap">
		    		<i class="wst-users_icon2"></i>
		    		<?php if(($data['order']['waitSend']>0)): ?>
		    		<span class="ui-badge-corner wait-payment" id="waitSend"><?php echo $data['order']['waitSend']; ?></span>
		    		<?php endif; ?>
		    	</p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">待发货</span>
		   	</a>
		    </div>
		    <div class="ui-col ui-col">
		    <a href="<?php echo url('mobile/orders/index',['type'=>'waitReceive']); ?>">
		    	<p class="ui-badge-wrap">
		    		<i class="wst-users_icon3"></i>
		    		<?php if(($data['order']['waitReceive']>0)): ?>
		    		<span class="ui-badge-corner wait-payment" id="waitReceive"><?php echo $data['order']['waitReceive']; ?></span>
		    		<?php endif; ?>
		    	</p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">待收货</span>
		    </a>
		    </div>
		    <div class="ui-col ui-col">
		    <a href="<?php echo url('mobile/orders/index',['type'=>'waitAppraise']); ?>">
		    	<p class="ui-badge-wrap">
		    		<i class="wst-users_icon4"></i>
		    		<?php if(($data['order']['waitAppraise']>0)): ?>
		    		<span class="ui-badge-corner wait-payment" id="waitAppraise"><?php echo $data['order']['waitAppraise']; ?></span>
		    		<?php endif; ?>
		    	</p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">待评价</span>
		    </a>
		    </div>
		    <div class="ui-col ui-col">
		    <a href="<?php echo url('mobile/orders/index',['type'=>'abnormal']); ?>">
		    	<p  style="display:none;"><i class="wst-users_icon5"></i></p><p><i class="wst-users_icon5"></i></p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">取消/拒收</span>
		    </a>
		    </div>
		    
		</div>
		
		<!-- <div class="user-order">
			<ul class="ui-row order">
	    		<li class="ui-col ui-col-50"><i class="wallet-icon"></i>我的财产</li>
	    		<li class="ui-col ui-col-50 view-order" onclick="location.href='<?php echo url('mobile/logmoneys/usermoneys'); ?>'">资金管理  ></li>
	    	</ul>
    	</div> -->
		<!-- <div class="ui-row-flex wst-users_capital">
		    <div class="ui-col ui-col">
		    <a href="<?php echo url('mobile/logmoneys/usermoneys'); ?>">
		    	<p class="ui-badge-wrap ui-nowrap"><span>¥ </span><?php echo $user['userMoney']; ?></p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">余额</span>
		    </a>
		    </div>
		    <div class="ui-col ui-col">
		    <a href="<?php echo url('mobile/userscores/index'); ?>">
		    	<p class="ui-badge-wrap ui-nowrap" id="currentScore"><?php echo $user['userScore']; ?></p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">积分</span>
		   	</a>
		    </div>
		    <?php echo hook('mobileDocumentUserIndexTerm'); ?>
		</div> -->
		
		<div class="user-order">
			<ul class="ui-row order">
	    		<li class="ui-col ui-col-50">必备工具</li>
	    	</ul>
    	</div>
		<ul class="ui-row" style="background: #fff;padding-bottom: 0.15rem;">
		    <!-- <li class="ui-col ui-col-25 user-icon-box">
		    	<a href="<?php echo url('mobile/favorites/goods'); ?>">
		    	<p><i class="user-icon1"></i></p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">关注商品</span>
		    	</a>
		    </li> -->

		    <!-- <li class="ui-col ui-col-25 user-icon-box">
		    	<a href="<?php echo url('mobile/favorites/shops'); ?>">
		    	<p><i class="user-icon2"></i></p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">关注店铺</span>
		    	</a>
		    </li> -->

		    <li class="ui-col ui-col-25 user-icon-box">
		    	<a href="<?php echo url('mobile/goods/history'); ?>">
		    	<p><i class="user-icon3"></i></p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">浏览记录</span>
		    	</a>
		    </li>
		    <!-- <li class="ui-col ui-col-25 user-icon-box">
		    	<a href="<?php echo url('mobile/logmoneys/usermoneys'); ?>">
		    	<p><i class="user-icon5"></i></p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">资金管理</span>
		    	</a>
		    </li> -->

		    <!-- <li class="ui-col ui-col-25 user-icon-box">
		    	<a href="<?php echo url('mobile/userscores/index'); ?>">
		    	<p><i class="user-icon6"></i></p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">我的积分</span>
		    	</a>
		    </li> -->

		    <li class="ui-col ui-col-25 user-icon-box">
		    	<a href="<?php echo url('mobile/useraddress/index'); ?>">
		    	<p><i class="user-icon9"></i></p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">地址管理</span>
		    	</a>
		    </li>
		    <li class="ui-col ui-col-25 user-icon-box ui-center-hor">
		    	<a href="<?php echo url('mobile/ordercomplains/index'); ?>">
		    	<p><i class="user-icon11"></i></p>
		    	<span class="ui-flex ui-flex-align-end ui-flex-pack-center">订单投诉</span>
		    	</a>
		    </li>
			<li class="ui-col ui-col-25 user-icon-box ui-center-hor">
				<a href="<?php echo url('mobile/feedbacks/index'); ?>">
					<p><i class="user-icon12"></i></p>
					<span class="ui-flex ui-flex-align-end ui-flex-pack-center">功能反馈</span>
				</a>
			</li>
			<li class="ui-col ui-col-25 user-icon-box ui-center-hor">
				<a href="<?php echo url('mobile/orderservices/oslist'); ?>">
					<p><i class="user-icon14"></i></p>
					<span class="ui-flex ui-flex-align-end ui-flex-pack-center">售后列表</span>
				</a>
			</li>
			<?php if($user['userType']==0): ?>
			<li class="ui-col ui-col-25 user-icon-box ui-center-hor">
				<a href="<?php echo url('mobile/shopapplys/index'); ?>">
					<p><i class="user-icon13"></i></p>
					<span class="ui-flex ui-flex-align-end ui-flex-pack-center">商家入驻</span>
				</a>
			</li>
			<?php endif; ?>
		    <?php echo hook('mobileDocumentUserIndexTools'); ?>
		</ul>
		<div class="ui-btn-wrap logout">
		</div>

		<div class="contact">
			<div class="contact_top">
				<img src="/upload/goods/2021-04/dhtb.png" alt="">
				<span>咨询热线：<?php echo WSTConf('CONF.serviceTel'); ?></span>
			</div>
			<p>
				<?php echo WSTConf('CONF.mallFooter'); ?>
			</p>
		</div>
     </section>


	        
        <div class="ui-loading-wrap wst-Load" id="Load">
		    <i class="ui-loading"></i>
		</div>
		
		<div class="ui-loading-block" id="Loadl">
		    <div class="ui-loading-cnt">
		        <i class="ui-loading-bright"></i>
		        <p id="j-Loadl">正在加载中...</p>
		    </div>
		</div>
		<?php 
			$pageId = WSTIsOpenIndexCustomPage(1);
			$menu = WSTIndexCustomPageMenu($pageId);
			$cartNum = WSTCartNum();
		 if($pageId > 0): ?>
		<input type="hidden" value="<?php echo $pageId; ?>" id="pageId" autocomplete="off">
		<footer class="ui-footer wst-footer-btns" style="position:fixed;bottom:0;height:43px; border-top: 1px solid <?php echo $menu['borderStyle']; ?>;" id="footer">
			<div class="wst-toTop" id="toTop">
				<i class="wst-toTopimg"></i>
			</div>
			<div class="ui-row-flex wst-custom-menus" style="background:<?php echo $menu['backgroundColor']; ?>">
				<?php if(is_array($menu['tabbars']) || $menu['tabbars'] instanceof \think\Collection || $menu['tabbars'] instanceof \think\Paginator): $i = 0; $__LIST__ = $menu['tabbars'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
					<div class='ui-col ui-col'>
						<a href='javascript:void(0);' menu-flag="<?php echo $vo['menuFlag']; ?>"  link="<?php echo $vo['link']; ?>"  onclick="javascript:WST.toCustomMenuPage(this)">
							<div class="wst-flex-column wst-center wst-custom-menus-item <?php if($vo['menuFlag'] == 'cart'): ?>carsNum<?php endif; ?>">
								<img class='custom-menu-icon ' src="__RESOURCE_PATH__/<?php echo $vo['icon']; ?>">
								<img class='custom-menu-select-icon wst-none' src="__RESOURCE_PATH__/<?php echo $vo['selectIcon']; ?>">
								<p style="color:<?php echo $menu['color']; ?>;" class='custom-menu-text'><?php echo $vo['text']; ?></p>
								<p style="color:<?php echo $menu['selectedColor']; ?>;" class='custom-menu-select-text wst-none'><?php echo $vo['text']; ?></p>
								<?php if($vo['menuFlag'] == 'cart' && $cartNum > 0): ?><i><?php  echo $cartNum; ?></i><?php endif; ?>
							</div>
						</a>
					</div>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
		</footer>
		<?php else: ?>
		<input type="hidden" value="0" id="pageId" autocomplete="off">
        <footer class="ui-footer wst-footer-btns" style="position:fixed;bottom:0;height:43px; border-top: 1px solid #e8e8e8;" id="footer">
	        <div class="wst-toTop" id="toTop">
			  <i class="wst-toTopimg"></i>
			</div>
            <div class="ui-row-flex wst-menus">
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/index/index'); ?>"><p id="home"></p></a></div>
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/goods/lists'); ?>"><p id="category"></p></a></div>
			    <?php echo hook('mobileDocumentBottomNav'); ?>
			    <div class="ui-col ui-col carsNum J_im_cart"><a href="<?php echo url('mobile/carts/index'); ?>"><p id="cart">
                </p></a><?php if(($cartNum>0)): ?><i><?php  echo $cartNum; ?></i><?php endif; ?></div>
                <div class="ui-col ui-col J_followbox"><a href="<?php echo url('mobile/favorites/goods'); ?>"><p id="follow"></p></a></div>
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/users/index'); ?>"><p id="user"></p></a></div>
			</div>
        </footer>
		<?php endif; ?>
        <?php echo hook('initCronHook'); ?>


	
<div class="ui-dialog" id="wst-di-prompt">
    <div class="ui-dialog-cnt">
        <div class="ui-dialog-bd">
            <p id="wst-dialog" class="wst-dialog-t">提示</p>
            <p class="wst-dialog-l"></p>
            <button id="wst-event1" type="button" class="ui-btn-s wst-dialog-b1" data-role="button">取消</button>&nbsp;&nbsp;
            <button id="wst-event2" type="button" class="ui-btn-s wst-dialog-b2">确定</button>
        </div>
    </div>      
</div>

<div class="ui-dialog" id="wst-di-share" onclick="WST.dialogHide('share');">
     <div class="wst-prompt"></div>
</div><!-- 对话框模板 -->


<script type='text/javascript' src='__MOBILE__/users/user.js?v=<?php echo $v; ?>'></script>

</body>
</html>