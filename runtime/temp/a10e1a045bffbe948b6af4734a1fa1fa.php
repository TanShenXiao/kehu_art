<?php /*a:4:{s:70:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/shop_street.html";i:1618380183;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/footer.html";i:1618386322;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/dialog.html";i:1579267090;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<title><?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="/static/plugins/swiper/swiper.min.css"/>
<link rel="stylesheet"  href="__MOBILE__/css/shops_list.css?v=<?php echo $v; ?>">

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
		<div class="wst-se-search" onclick="javascript:WST.searchPage('shops',1);">
		    <i class="ui-icon-search" onclick="javascript:WST.searchPage('shops',1);"></i>
		    <form action＝"" class="input-form">
			<input type="search" value="<?php echo $keyword; ?>" placeholder="按关键字搜索店铺" onsearch="WST.search(1)" autocomplete="off" disabled="disabled">
			</form>
		</div>
	</header>


	 <input type="hidden" name="" value="<?php echo $keyword; ?>" id="keyword" autocomplete="off">
	 <input type="hidden" name="" value="" id="condition" autocomplete="off">
	 <input type="hidden" name="" value="" id="desc" autocomplete="off">
	 <input type="hidden" name="" value="" id="catId" autocomplete="off">
	 <input type="hidden" name="" value="" id="currPage" autocomplete="off">
     <input type="hidden" name="" value="" id="totalPage" autocomplete="off">
	 <input type="hidden" name="" value="" id="accredId" autocomplete="off">
     <input type="hidden" name="" value="" id="totalScore" autocomplete="off">
	 <input type="hidden" name="" value="<?php echo $type; ?>" id="type" autocomplete="off">
    
     <div id="backgroundTier" onclick="javascript:closeScreenTier();" style="display:none;"></div>
     <div id="screen">
     <div class="screen-top">
		<ul class="ui-tab-content">
	        <li id="screenAttr"></li>
	    </ul>
		<ul class="ui-tab-content">
	        <li id="graded"></li>
	    </ul>
     </div>
     	<div id="indexbnts" class="index-bnts">	
     		<div   onclick="javascript:resetAll();" class="left J_ping">重置</div>	
     		<div onclick="javascript:closeScreenTier();" report-eventparam="B" report-eventid="MFilter_Confirm" class="right J_ping">确定</div>
     	</div>
     </div>
     <section class="ui-container">
     	<div class="wst-shl-ads" style='padding-bottom:0.05rem'>
     	   <div class="title">名铺抢购</div>
		   <div class="wst-shl-adsb">
			<div class="swiper-container">
	          <div class="swiper-wrapper">
	          	<?php $wstTagAds =  model("common/Tags")->listAds("mo-ads-street",4,86400); foreach($wstTagAds as $key=>$vo){?>
	                <div class="swiper-slide" style="width:33.333333%;">
	                    <a href="<?php echo $vo['adURL']; ?>" class="adsImg"><img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__RESOURCE_PATH__/<?php echo WSTImg($vo['adFile'],3); ?>"></a>
	                </div>
	            <?php } ?>
	          </div>
	        </div>
	        </div>
     	</div>
		<div class="tj_arts">
			<h2>推荐艺术家</h2>
			<ul>
				<?php
					foreach ($tjlist as $k => $tjinfo) {
				?>
				<li>
					<div class="tj_arts_img"><a href="javascript:void(0);" onclick="goShopHome(<?php echo $tjinfo['shopId']; ?>)">
						<img src="/<?php echo $tjinfo['shopImg']; ?>"></a>
					</div>
					<div class="tj_arts_cont" onclick="goToShop(<?php echo $tjinfo['shopId']; ?>)">
						<div class="title ui-nowrap"><?php echo $tjinfo['shopName']; ?></div>
						<p class="ui-nowrap">类别：<?php echo $tjinfo['catName']; ?></p>
						<p><span>商品数：</span><?php echo $tjinfo['count']; ?></p>
					</div>
				</li>
				<?php } ?>
			</ul>
		</div>
		<div class="wst-ysj-sy" style="padding:8px;margin:20px 0;">
			<p>艺术家索引：</p>
			<ul>
				<li><a href="#">全部</a></li>
				<li><a href="#">A</a></li>
				<li><a href="#">B</a></li>
				<li><a href="#">C</a></li>
				<li><a href="#">D</a></li>
				<li><a href="#">E</a></li>
				<li><a href="#">F</a></li>
				<li><a href="#">G</a></li>
				<li><a href="#">H</a></li>
				<li><a href="#">I</a></li>
				<li><a href="#">J</a></li>
				<li><a href="#">K</a></li>
				<li><a href="#">L</a></li>
				<li><a href="#">M</a></li>
				<li><a href="#">N</a></li>
				<li><a href="#">O</a></li>
				<li><a href="#">P</a></li>
				<li><a href="#">Q</a></li>
				<li><a href="#">R</a></li>
				<li><a href="#">S</a></li>
				<li><a href="#">T</a></li>
				<li><a href="#">U</a></li>
				<li><a href="#">V</a></li>
				<li><a href="#">W</a></li>
				<li><a href="#">X</a></li>
				<li><a href="#">Y</a></li>
				<li><a href="#">Z</a></li>
			</ul>
		</div>
		<ul class="ui-tab-content">
			<li id="shops-list"></li>
		</ul>
	</section>
	<div class="contact">
		<div class="contact_top">
			<img src="/upload/goods/2021-04/dhtb.png" alt="">
			<span>咨询热线：<?php echo WSTConf('CONF.serviceTel'); ?></span>
		</div>
		<p>
			<?php echo WSTConf('CONF.mallFooter'); ?>
		</p>
	</div>
<script id="list" type="text/html">
	<div class="art_list_cont">
{{# if(d && d.length>0){ }}
{{# for(var i=0; i<d.length; i++){ }}
	<div class="art_list">
		<a href="javascript:void(0);" onclick="goShopHome({{ d[i].shopId }})"><img src="__RESOURCE_PATH__/{{ d[i].shopImg }}" alt=""></a>
		<div class="art_name">{{d[i].shopName}}</div>
	</div>
{{# } }}
{{# }else{ }}
</div>
<div class="wst-prompt-icon"><img src="__MOBILE__/img/nothing-follow-shps.png"></div>
<div class="wst-prompt-info">
	<p>对不起，没有相关店铺。</p>
</div>
{{# } }}
</script>
<script id="accredList" type="text/html">

     	<div class="accred-box screen-box no">
        <input type="hidden"  class="vsed" value=""/>
     		<p class="title">店铺服务{{# if(d.length>3){ }}<i class="arrow-base arrow" onclick="javascript:showAll(this)"  s=0></i>{{# } }}</p>
	         <div class="option-box">
	                <span id="cancelAccred" onclick="javascript:cancelAccred(this);" class="attrs after-color selected" d="" style="background-color: rgb(255, 255, 255);display:none;"></span>
				{{# if(d && d.length>0){ }}
				{{# for (var i=0; i<d.length;i++){ }}
			     			<span onclick="javascript:selectAccred(this);" class="attrs after-color  accred-lines" d="{{d[i].accredId}}">{{d[i].accredName}}</span>
				{{# } }}
				{{# } }}
		     </div>
     	</div>

</script>
<script id="scoreList" type="text/html">
         
     	<div class="score-box  screen-box no">
        <input type="hidden"  class="vsed" value=""/>
     		<p class="title">好评率</p>
	         <div class="option-box">
	                <span id="cancelScore" onclick="javascript:cancelScore(this);" class="attrs after-color selected" d="" style="background-color: rgb(255, 255, 255);display:none;"></span>
				{{# for(var i in d){ }}

			     	<span onclick="javascript:selectScore(this);" class="attrs after-color wrap-lines" d="{{i}}" style="padding: 0.05rem 0.01rem;">{{d[i]}}</span>
				{{# } }}
		     </div>
     	</div>

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
    <div class="wst-co-search" id="wst-shops-search" style="background-color: #f6f6f8;">
    <header class="ui-header ui-header-positive wst-se-header2" style="border-bottom: 1px solid #f6f6f8;">
		<i class="ui-icon-return" onclick="javascript:WST.searchPage('shops',0);"></i>
		<div class="wst-se-search">
		    <i class="ui-icon-search" onclick="javascript:WST.search(1);"></i>
		    <form action＝"" class="input-form">
			<input type="search" value="" placeholder="按关键字搜索店铺" onsearch="WST.search(1)" autocomplete="off" id="wst-search">
			</form>
		</div>
		<a class="btn" href="javascript:void(0);" onclick="javascript:WST.search(1);">搜索</a>
	</header>
	<div class="classify">
		<ul class="ui-list ui-list-text ui-list-link ui-list-active shops">
		    <li onclick="javascript:searchCondition(0);">
		        <h4 class="ui-nowrap">全部店铺</h4>
		    </li>
		</ul>
		<ul class="ui-list ui-list-text ui-list-active shops2">
            <?php if(is_array($goodscats) || $goodscats instanceof \think\Collection || $goodscats instanceof \think\Paginator): $i = 0; $__LIST__ = $goodscats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?>
		    <li onclick="javascript:searchCondition(<?php echo $g['catId']; ?>);">
		        <h4 class="ui-nowrap"><?php echo $g['catName']; ?></h4>
		        <div class="ui-txt-info">查看全部</div>
		    </li>
		    <?php endforeach; endif; else: echo "" ;endif; ?>
		</ul>
	</div>
	</div>
	<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>


<script type='text/javascript' src='/static/plugins/swiper/swiper.min.js'></script>
<?php if(WSTConf('CONF.mapKey')!=''): ?>
<script type="text/javascript" src="<?php echo WSTProtocol(); ?>3gimg.qq.com/lightmap/components/geolocation/geolocation.min.js"></script>
<?php endif; ?>
<script type='text/javascript' src='__MOBILE__/js/shops_list.js?v=<?php echo $v; ?>'></script>

</body>
</html>