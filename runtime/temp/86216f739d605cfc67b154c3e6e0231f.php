<?php /*a:4:{s:64:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/carts.html";i:1618414861;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/footer.html";i:1618386322;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/dialog.html";i:1579267090;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<title>购物车 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<style>
	body{background: #fff;}
</style>
<link rel="stylesheet"  href="__MOBILE__/css/carts.css?v=<?php echo $v; ?>">

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
       	   <a href="<?php echo url('mobile/index/index'); ?>"><i class="ui-icon-return"></i></a><h1>购物车</h1>
       	   <?php if((count($carts['carts'])>0)): ?>
       	   <span id="edit" class="edit" onclick="javascript:edit(0);">编辑</span><span id="complete" class="edit" onclick="javascript:edit(1);" style="display: none;">完成</span>
       	   <div class="wst-ca-more" onclick="javascript:inMore();">···</div>
       	   <?php endif; ?>
       </header>
         <div class="wst-go-more" id="arrow" style="display: none;"><i class="arrow"></i>
	    	<ul class="ui-row ui-list-active more">
			    <li class="ui-col"><div class="column line"><a href="<?php echo url('mobile/index/index'); ?>"><i class="home"></i><p>首页</p></a></div></li>
			    <li class="ui-col"><div class="column line"><a href="<?php echo url('mobile/goodscats/index'); ?>"><i class="category"></i><p>分类</p></a></div></li>
			    <li class="ui-col"><div class="column line"><a href="<?php echo url('mobile/favorites/goods'); ?>"><i class="follow"></i><p>关注</p></a></div></li>
			    <li class="ui-col"><div class="column"><a href="<?php echo url('mobile/users/index'); ?>"><i class="user"></i><p>我的</p></a></div></li>
			</ul>
	    </div>
	    <div class="wst-ca-layer" id="layer" onclick="javascript:inMore();"></div>


     <section class="ui-container" style='margin-bottom: 0.55rem'>
     <?php if((count($carts['carts'])>0)): ?>
     <input type="hidden" name="" value="0" id="buyNum_0" autocomplete="off">
     <input type="hidden" name="" value="<?php echo count($carts['carts'])+1; ?>" id="totalshop" autocomplete="off">
     <?php if(is_array($carts['carts']) || $carts['carts'] instanceof \think\Collection || $carts['carts'] instanceof \think\Paginator): $k = 0; $__LIST__ = $carts['carts'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ca): $mod = ($k % 2 );++$k;?>
	     <div class="wst-ca-s">
			<div class="wst-ca-layout shop">
				<div class="wst-ca-10 shopl"><i class="ui-icon-chooses ui-icon-unchecked-s j-shop<?php echo $k; ?>" shopId="<?php echo $ca['shopId']; ?>" childrenId="clist<?php echo $k; ?>" cartId="0" mval="0"></i>&nbsp;</div>
				<div class="wst-ca-90 shopr"><p class="ui-nowrap" onclick="javascript:WST.intoShops(<?php echo $ca['shopId']; ?>);"><img src="/<?php echo $ca['shopImg']; ?>" alt=""> <?php echo $ca['shopName']; ?></p></div>
			</div>
			<?php if(is_array($ca['list']) || $ca['list'] instanceof \think\Collection || $ca['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $ca['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($i % 2 );++$i;?>
			<?php echo hook('mobileDocumentCartGoodsPromotion',['goods'=>$li]); ?>
			<div class="wst-ca-layout goods<?php if(($li['goodsStock']==0)): ?> nogoods<?php endif; ?> j-g<?php echo $li["cartId"]; ?>">
				<div class="wst-ca-10 goodsl">
					<i id="gchk_<?php echo $li["cartId"]; ?>" class="ui-icon-chooseg <?php if(($li['isCheck'])): ?>ui-icon-success-block wst-active<?php else: ?>ui-icon-unchecked-s<?php endif; ?> clist<?php echo $k; ?>" cartId="<?php echo $li['cartId']; ?>" mval="<?php echo $li['shopPrice']; ?>"></i>&nbsp;</div>
				<div class="wst-ca-90">
					<div class="wst-ca-24 goodsr">
					<div class="img j-imgAdapt">
						<a href="javascript:void(0);" onclick="javascript:WST.intoGoods(<?php echo $li['goodsId']; ?>);">
						<img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__RESOURCE_PATH__/<?php echo WSTImg($li['goodsImg'],3); ?>" title="<?php echo WSTStripTags($li['goodsName']); ?>">
						</a>
					</div>
					</div>
					<div class="wst-ca-76">
						<div class="info">
						<a href="javascript:void(0);" onclick="javascript:WST.intoGoods(<?php echo $li['goodsId']; ?>);"><p class="name"><?php echo $li['goodsName']; ?></p></a>
						<?php if(($li['specNames'])): ?>
						<p class="spec">规格：
						<?php if(is_array($li['specNames']) || $li['specNames'] instanceof \think\Collection || $li['specNames'] instanceof \think\Paginator): $i = 0; $__LIST__ = $li['specNames'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sp): $mod = ($i % 2 );++$i;?>
							<?php echo $sp['catName']; ?>:<?php echo $sp['itemName']; ?>
						<?php endforeach; endif; else: echo "" ;endif; ?>
						</p>
						<?php endif; ?>
<div class="wst-buy_l">
	<p class="price" id='gprice_<?php echo $li['cartId']; ?>'>¥ <?php echo $li['shopPrice']; ?></p><input class="wst-buy_l1" type="button" value="-" onclick='javascript:WST.changeIptNum(-1,"#buyNum",<?php echo $li["cartId"]; ?>,"statCartMoney")'><input id="buyNum_<?php echo $li['cartId']; ?>" class="wst-buy_l2" data-min='1' data-max='<?php echo $li["goodsStock"]; ?>' type="number" value="<?php echo $li['cartNum']; ?>" autocomplete="off" onkeyup='WST.changeIptNum(0,"#buyNum",<?php echo $li["cartId"]; ?>,"statCartMoney")'><input class="wst-buy_l3" type="button" value="+" onclick='javascript:WST.changeIptNum(1,"#buyNum",<?php echo $li["cartId"]; ?>,"statCartMoney")'>
</div>
						</div>
					</div>
				</div>
				<span id="noprompt<?php echo $li['cartId']; ?>" class="noprompt" style="display: none;"></span>
			</div>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="wst-ca-layout bottom">
				<p class="wst-ca-50">共 <?php echo count($ca['list']); ?> 件商品</p><p id="tprice_<?php echo $k; ?>" class="wst-ca-50 price tprice<?php echo $li["cartId"]; ?>"><span>¥ </span><?php echo sprintf("%.2f", $ca['goodsMoney']); ?></p>
			</div>
		</div>
		<?php endforeach; endif; else: echo "" ;endif; else: ?>
		<div class="wst-prompt-icon" style="width: 1rem;height: 1rem;"><img src="__MOBILE__/img/nothing-cart.png" style="width: 1rem;height: 1rem;"></div>
		<div class="wst-prompt-info">
			<p style="letter-spacing: 4px;">购物车空空如也~</p>
			<p style="color: rgb(157, 157, 157);font-size: 14px;">去挑几件喜欢的作品吧</p>
			<button style="border:1px solid rgb(157,157,157);border-radius:22px; color:#999 ;" class="ui-btn-s" onclick="javascript:WST.intoIndex();">随便看看</button>
		</div>
		<?php endif; ?>
     </section>


<?php if((count($carts['carts'])>0)): ?>
        <footer class="ui-footer wst-footer-btns" style="height:42px; border-top: 1px solid #e8e8e8;" id="footer">
			<div class="wst-ca-se">
			<div class="wst-ca-layout">
				<div class="wst-ca-10 totall"><i class="ui-icon-choose ui-icon-unchecked-s" cartId="0" mval="0"></i>&nbsp;</div>
				<div class="wst-ca-90 totalr">
					<span>全选</span>
					<button id="settlement" class="button" type="button" onclick="javascript:toSettlement();">结算</button>
					<button id="delete" class="button" type="button" onclick="javascript:deletes();" style="display: none;">删除</button>
					<span id="total" class="total">合计：<span id="totalMoney" class="price"><span>¥ </span><?php echo sprintf("%.2f", $carts['goodsTotalMoney']); ?></span></span>
				</div>
			</div>
			</div>
        </footer>
<?php else: ?>
        
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
<?php endif; ?>



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


<script type='text/javascript' src='__MOBILE__/js/carts.js?v=<?php echo $v; ?>'></script>

</body>
</html>