<?php /*a:4:{s:69:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/goods_list.html";i:1618387113;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/footer.html";i:1618386322;s:75:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/goods_search_box.html";i:1579267099;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<meta name="description" content="<?php if($catInfo && $catInfo['seoDes']): ?><?php echo $catInfo['seoDes']; else: ?><?php echo WSTConf('CONF.seoMallDesc'); ?><?php endif; ?>">
<meta name="Keywords" content="<?php if($catInfo && $catInfo['seoKeywords']): ?><?php echo $catInfo['seoKeywords']; else: ?><?php echo WSTConf('CONF.seoMallKeywords'); ?><?php endif; ?>">

<title>
    <?php if($catInfo['seoTitle']): ?>
        <?php echo $catInfo['seoTitle']; ?> - 
    <?php else: ?>
        <?php echo $catInfo['catName']; ?> - 商品列表 - 
    <?php endif; ?>
    <?php echo WSTConf('CONF.mallName'); ?>
</title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/goods_list.css?v=<?php echo $v; ?>">

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

    <header class="ui-header ui-header-positive wst-se-header2" style="border-bottom: 1px solid #f6f6f8;">
		<i class="ui-icon-return" onclick="history.back()"></i>
		<div class="wst-se-search">
		    <i class="ui-icon-search" onclick="javascript:WST.searchPage('goods',1);"></i>
		    <form action＝"" class="input-form" onclick="javascript:WST.searchPage('goods',1);">
			<input type="search" value="<?php echo $keyword; ?>" placeholder="按关键字搜索艺术品/艺廊/艺术家" onsearch="WST.search(0)" autocomplete="off" disabled="disabled">
			</form>
		</div>
		<?php if($catInfo['showWay'] == '1'): ?>
			<span class='wst-se-icon wst-se-icon2' onclick="javascript:switchList(this);"></span>
		<?php endif; if($catInfo['showWay'] == '0'): ?>
			<span class='wst-se-icon' onclick="javascript:switchList(this);"></span>
		<?php endif; ?>
	</header>


     <input type="hidden" name="" value="<?php echo $keyword; ?>" id="keyword" autocomplete="off">
     <input type="hidden" name="" value="<?php echo $catId; ?>" id="catId" autocomplete="off">
	 <input type="hidden" name="" value="<?php echo $brandId; ?>" id="brandId" autocomplete="off">
     <input type="hidden" name="" value="" id="condition" autocomplete="off">
	 <input type="hidden" name="" value="" id="desc" autocomplete="off">
	 <input type="hidden" name="" value="" id="currPage" autocomplete="off">
	 <input type="hidden" name="" value="<?php echo $searchType; ?>" id="searchType" autocomplete="off">
     <input type="hidden" name="" value="" id="totalPage" autocomplete="off">

     <section class="ui-container">
     	<div class="ui-row-flex wst-shl-head">
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,0);">
		   		 <p class="pd0">销量</p><i class="down"></i>
		    </div>
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,1);">
		   		 <p class="pd0">价格</p><i class="down"></i>
		    </div>
		    <div class="ui-col ui-col sorts active" status="down" onclick="javascript:orderCondition(this,5);">
		   		 <p class="pd0">人气</p><i class="up2"></i>
		   		 <span class="bar"></span>
		    </div>
		    <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,3);">
		   		 <p>上架时间</p><i class="down"></i>
			</div>
		</div>
		<ul class="ui-tab-content">
	        <li id="goods-list"  <?php if($catInfo['showWay'] == '1'): ?> class='wst-go-switch'<?php endif; ?>></li>
	    </ul>
     </section>
     
<script id="list" type="text/html">
{{# if(d && d.length>0){ }}
{{# for(var i=0; i<d.length; i++){ }}
<div class="wst-in-goods {{# if((i)%2==0){ }}left{{# }else{ }}right{{# } }}" onclick="javascript:WST.intoGoods({{ d[i].goodsId }});">
<div class="img j-imgAdapt"><a href="javascript:void(0);" onclick="javascript:WST.intoGoods({{ d[i].goodsId }});"><img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__RESOURCE_PATH__/{{ d[i].goodsImg }}" title="{{ d[i].goodsName }}"/></a></div>
<div class="name ui-nowrap-multi">{{ d[i].goodsName }}</div>

<div class="info"><span class="price">{{# if(d[i].saleType==1){ }}<span>议价</span>{{# }else if(d[i].saleType==2){ }}<span>仅展示</span>{{# }else{ }}¥ <span>{{ d[i].shopPrice }}</span>{{# } }}</span></div>
<div class="info2"><span class="price">{{# if(d[i].goodsAuthor!=null){ }}{{ d[i].goodsAuthor }} {{# } }}</span></div>
</div>
{{# } }}
{{# }else{ }}
<div class="wst-prompt-icon"><img src="__MOBILE__/img/nothing-goods.png"></div>
<div class="wst-prompt-info">
	<p>对不起，没有相关商品。</p>
</div>
{{# } }}
</script>
</script>


	        
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
<div class="wst-toTop" style="display: block;bottom: 0.68rem;">
	<a href="<?php echo url('mobile/goods/history'); ?>"><i class="wst-toHistoryimg"></i></a>
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
<script type='text/javascript' src='__MOBILE__/js/goods_list.js?v=<?php echo $v; ?>'></script>

</body>
</html>