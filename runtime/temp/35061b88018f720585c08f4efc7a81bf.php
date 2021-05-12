<?php /*a:4:{s:64:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/index.html";i:1618389325;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/footer.html";i:1618386322;s:75:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/goods_search_box.html";i:1579267099;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<title>首页 - <?php if($pageId > 0): ?><?php echo $customPageTitle; else: ?><?php echo WSTConf('CONF.mallName'); ?><?php endif; ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="/static/plugins/swiper/swiper.min.css"/>
<link rel="stylesheet"  href="__MOBILE__/css/index.css?v=<?php echo $v; ?>"/>

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

	<?php if($pageId == 0): ?>
    <div class="wst-in-search">
    	<div class="searchs" id="j-searchs">
		    <form action＝"" class="input-form">
		    <input type="text" placeholder="按关键字搜索艺术品/艺廊/艺术家" onsearch="WST.search(0)">
		    <i class="ui-icon-searcha" onclick="javascript:toSearch(0);"></i>
		</form>
			<div class="wst-clear"></div>
		</div>
	</div>
	<?php endif; if($pageId == 0): ?>
<section class="ui-container">
		<ul class="zp_big_category" style="background: #eeeeee;">
			<li style="width: 20%;"><a href="<?php echo url('mobile/shops/shopstreet'); ?>">艺术家</a></li>
			<li style="width: 20%;"><a href="<?php echo url('mobile/goods/lists'); ?>">艺术服务</a></li>
			<li style="width: 20%;"><a href="<?php echo url('mobile/goods/lists'); ?>">税收服务</a></li>
			<li style="width: 20%;"><a href="<?php echo url('mobile/shops/artCollect'); ?>">作品征集</a></li>
			<li style="width: 20%;"><a href="<?php echo url('mobile/goods/lists'); ?>">官网</a></li>
		</ul>
        <div class="swiper-container banner">
			<div class="swiper-wrapper ads">
				<?php $wstTagAds =  model("common/Tags")->listAds("mo-ads-index",99,86400); foreach($wstTagAds as $key=>$vo){?>
				<div class="swiper-slide"><a class='ms-slide' href="<?php echo $vo['adURL']; ?>"><img src="/<?php echo WSTImg($vo['adFile'],2); ?>"></a></div>
				<?php } ?>
		    </div>
		    <div class="swiper-pagination" style="background:none;"></div>
		</div>

		<ul class="zp_big_category">
			<?php $_result=WSTSideCategorys();if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
			<li><a href="<?php echo url('mobile/goods/lists','cat='.$vo['catId']); ?>"><?php echo WSTMSubstr($vo['catName'],0,2); ?></a></li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
		<div class="sector_pack">
			<h2 class="sector_h2">精选作品</h2>
			<div class="sector_desc">
				<p>Selected works</p>
				<!-- <a href="/category-365.html">查看更多>></a> -->
			</div>
			<div class="zp_litter">
				<ul>
					<?php
						$goodsInfo = WSTGoodsCatsList(['365','366','367','368','369','370'],[],'saleNum desc',4);
						foreach ($goodsInfo as $k => $goodInfo) {
					?>
					<li>
						<a href="<?php echo url('mobile/goods/detail','goodsId='.$goodInfo['goodsId']); ?>">
							<img src="/<?php echo $goodInfo['goodsImg']; ?>" alt="">
							<div class="zp_mod">
								<p class="zp_name"><?php echo $goodInfo['goodsName']; ?></p>
								<p class="zp_prize">价格 ￥<span><?php echo $goodInfo['shopPrice']; ?></span></p>
								<span class="zp_author">作者<?php echo $goodInfo['goodsAuthor']; ?></span>&nbsp;
								<span class="zp_size">尺寸<?php echo $goodInfo['goodsTips']; ?></span>
							</div>
						</a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div class="sector_pack">
			<h2 class="sector_h2">优惠专区</h2>
			<div class="sector_desc">
				<p>Preferential zone</p>
				<!-- <a href="/category-365.html">查看更多>></a> -->
			</div>
			<div class="sector_content_yh">
				<div class="yh_big">
					<img class="yh_big_img" src="/upload/goods/2019-04/yh.JPG" alt="">
					<div class="yh_big_tost">
						<h2 class="yh_big_title">优惠专区</h2>
						<p class="yh_big_desc">PREFERENTIAL ZONE</p>
						<button>立即进入</button>
					</div>
				</div>
				<div class="yh_litter">
					<ul>
						<?php
							$goodsInfo = WSTGoodsCatsList(['365','366','367','368','369','370'], [['isBest','=',1]], 'saleNum desc', '4');
							foreach ($goodsInfo as $k => $goodInfo) {
						?>
						<li>
							<img src="/<?php echo $goodInfo['goodsImg']; ?>" alt="">
							<div class="yh_content">
								<p class="author">作者<?php echo $goodInfo['goodsAuthor']; ?></p>
								<p class="name"><?php echo $goodInfo['goodsName']; ?></p>
								<p class="cur_prize">价格 ￥<span><?php echo $goodInfo['shopPrice']; ?></span></p>
								<p class="old_prize"><s>原价 ￥<?php echo $goodInfo['marketPrice']; ?></s></p>
								<a class="button"href="<?php echo url('home/goods/detail','goodsId='.$goodInfo['goodsId']); ?>">立即进入</a>
							</div>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="artist">
			<div class="sector_artist">
				<h2 class="sector_h2">艺术家</h2>
				<div class="sector_desc">
					<p>artist</p>
					<!-- <a href="/street.html">查看更多>></a> -->
				</div>
				<div class="artist_content">
					<?php
						$shopList = WSTShopList('1');
						foreach ($shopList as $k => $shopIfo) {
					?>
					<div class="artist_big_cont">
						<div class="artist_big">
							<a href="<?php echo url('mobile/shops/index',['shopId'=>$shopIfo['shopId']]); ?>">
								<img src="/<?php echo $shopIfo['shopImg']; ?>" alt="">
								<p><?php echo $shopIfo['shopName']; ?></p>
							</a>
						<?php } ?>
						</div>
						<ul class="artist_big_right">
							<?php
								$shopList = WSTShopList('1,4');
								foreach ($shopList as $k => $shopIfo) {
							?>
							<li>
								<a href="<?php echo url('mobile/shops/index',['shopId'=>$shopIfo['shopId']]); ?>">
									<img src="/<?php echo $shopIfo['shopImg']; ?>" alt="">
								</a>
							</li>
							<?php } ?>
						</ul>
					</div>
					<ul class="artist_big_bottom">
						<?php
							$shopList = WSTShopList('5,8');
							foreach ($shopList as $k => $shopIfo) {
						?>
						<li>
							<a href="<?php echo url('mobile/shops/index',['shopId'=>$shopIfo['shopId']]); ?>">
								<img src="/<?php echo $shopIfo['shopImg']; ?>" alt="">
							</a>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
		
<div class="brand_pack">
	<h2 class="sector_h2">品牌活动</h2>
	<div class="sector_desc">
		<p>Brand activities</p>
		<!-- <a href="">查看更多>></a> -->
	</div>
	<div class="brand_content">
		<ul>
			<li>
				<img src="/upload/goods/2021-04/5cc561b111a01.JPG" alt="">
				<p>艺术家张海峰国画专场</p>
			</li>
			<li>
				<img src="/upload/goods/2021-04/5cc561b111a01.JPG" alt="">
				<p>艺术家张海峰国画专场</p>
			</li>
			<li>
				<img src="/upload/goods/2021-04/5cc561b111a01.JPG" alt="">
				<p>艺术家张海峰国画专场</p>
			</li>
			<li>
				<img src="/upload/goods/2021-04/5cc561b111a01.JPG" alt="">
				<p>艺术家张海峰国画专场</p>
			</li>
		</ul>
	</div>
</div>
<div class="wst-clear"></div>

<div class="service_pack">
	<h2 class="sector_h2">艺术服务</h2>
	<div class="sector_desc">
		<p>Art services</p>
		<!-- <a href="">查看更多>></a> -->
	</div>
	<div class="services_content">
		<ul>
			<li>
				<img src="/upload/goods/2021-04/ysfw.jpg" alt="">
				<div class="mark">
					<p>艺术服务</p>
					<a href="/news/catId/402">查看详情</a>
				</div>
			</li>
			<li>
				<img src="/upload/goods/2021-04/ysfw2.jpg" alt="">
				<div class="mark">
					<p>税收服务</p>
					<a href="/news/catId/403">查看详情</a>
				</div>
			</li>
			<li>
				<img src="/upload/goods/2021-04/ysfw3.jpg" alt="">
				<div class="mark">
					<p>作品征集</p>
					<a href="/news/catId/404">查看详情</a>
				</div>
			</li>
		</ul>
	</div>
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
		<div class="bottom_clear"></div>
</section>
<script id="list" type="text/html">
	{{# if(d.ads && d.ads.length>0){ }}
		<div class="wst-in-adscats"><a href="{{ d.ads[0].adURL }}"><img src="__RESOURCE_PATH__/{{ d.ads[0].adFile }}"/></a></div>
	{{# } }}
	{{# if(d.goods.length>0){ }}
		<div class="wst-in-title colour0" onclick="javascript:getGoodsList({{ d.catId }});">
			<div class="name">
				<p style="float:left"><span>{{ d.catName }}</span></p>
				<p style="width:30%;float:right;margin-right:5%;text-align:right">
					<span style="color:#d30322;font-size:0.15rem">更多>></span>
				</p>
			</div>
		</div>
		<div class="wst-in-back">
		{{# for(var i=0; i<d.goods.length; i++){ }}
			<div class="wst-in-goods {{# if((i)%2==0){ }}left{{# }else{ }}right{{# } }}">
				<div class="img j-imgAdapt"><a href="javascript:void(0);" onclick="javascript:WST.intoGoods({{ d.goods[i].goodsId }});"><img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__RESOURCE_PATH__/{{ d.goods[i].goodsImg }}" title="{{ d.goods[i].goodsName }}"/></a></div>
				<div class="name ui-nowrap-multi" style="text-align:center" onclick="javascript:WST.intoGoods({{ d.goods[i].goodsId }});">{{ d.goods[i].goodsName }}</div>
				<div style="background-color:#eeeeee;height:0.44rem">
					<!--<div class="info2" style="text-align:center;width:100%;background-color:#eeeeee">{{# if(d.goods[i].goodsAuthor!=null){ }}{{ d.goods[i].goodsAuthor }} {{# } }}</div>-->
					<div class="info2">{{# if(d.goods[i].goodsAuthor!=null){ }}{{ d.goods[i].goodsAuthor }} {{# } }}</div>
					<div class="info3">{{ d.goods[i].thumbsNum }}</div>
				</div>
			</div>
		{{# } }}
		</div>
	{{# } }}
<div class="wst-clear"></div>
</script>
<?php else: ?>
<div class="decoration-container">
	<?php echo action('index/loadCustomPage',array('pageId'=>$pageId)); ?>
</div>
<?php endif; ?>


	        
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


    <div class="wst-co-search" id="wst-goods-search">
    <header class="ui-header ui-header-positive wst-se-header2" style="border-bottom: 1px solid #f6f6f8;">
		<i class="ui-icon-return" onclick="javascript:WST.searchPage('goods',0);"></i>
		<div class="wst-se-search">
		    <i class="ui-icon-search" onclick="javascript:toSearch();"></i>
		    <form action＝"" class="input-form">
			<input type="search" value="" placeholder="按关键字搜索艺术品/艺廊/艺术家" onsearch="javascript:toSearch()" autocomplete="off" id="wst-search" style="width:70%;">
			<div class="s-input-tab">
				<input type="hidden" id="s-type" value="goods"/>
				<div class="s-input-tab-txt">艺术品</div>
				<div class="s-input-tab-nav off" id="J_TabNav">
					<ul id="s-ul">
						<li class="goods"><span class="icon icons-baobei"></span>艺术品</li>
						<li class="shop"><span class="icon icons-shop"></span>艺廊</li>
						<li class="author"><span class="icon icons-tmall"></span>艺术家</li>
					</ul>
				</div>
			</div>
			</form>
		</div>
		<a class="btn" href="javascript:void(0);" onclick="javascript:toSearch();">搜索</a>
	</header>
	<div class="list">
		<p class="search"><i></i>热门搜索</p>
		<?php $hotWordsSearch = WSTConf("CONF.hotWordsSearch");
		if($hotWordsSearch!='')$hotWordsSearch = explode(',',$hotWordsSearch); ?>
		<div class="term">
			<?php if(is_array($hotWordsSearch) || $hotWordsSearch instanceof \think\Collection || $hotWordsSearch instanceof \think\Paginator): $i = 0; $__LIST__ = $hotWordsSearch;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hot): $mod = ($i % 2 );++$i;?>
			<a href="<?php echo url('mobile/goods/search',['keyword'=>$hot]); ?>"><?php echo $hot; ?></a>
			<?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
	</div>
	</div>
	<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
	<script>
	jQuery.noConflict();
	document.addEventListener('touchmove', function(event) {
	    //阻止背景页面滚动,
	    if(!jQuery("#wst-goods-search").is(":hidden")){
	        event.preventDefault();
	    }
	})
	</script>


<script type='text/javascript' src='/static/plugins/swiper/swiper.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/index.js?v=<?php echo $v; ?>'></script>

</body>
</html>