<?php /*a:2:{s:68:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/shop_home.html";i:1618384676;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<meta name="description" content="<?php echo $data['shop']['shopDesc']; ?>">
<meta name="keywords" content="<?php echo $data['shop']['shopKeywords']; ?>">

<title><?php echo $data['shop']['shopName']; ?> - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="/static/plugins/swiper/swiper.min.css"/>
<link rel="stylesheet"  href="__MOBILE__/css/shop_home.css?v=<?php echo $v; ?>">

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


<input type="hidden" name="" value="" id="condition" autocomplete="off">
<input type="hidden" name="" value="" id="desc" autocomplete="off">
<input type="hidden" name="" value="<?php echo $data['shop']['shopId']; ?>" id="shopId" autocomplete="off">
<input type="hidden" name="" value="<?php echo $goodsName; ?>" id="keyword" autocomplete="off">
<input type="hidden" name="" value="<?php echo $ct1; ?>" id="ct1" autocomplete="off">
<input type="hidden" name="" value="<?php echo $ct2; ?>" id="ct2" autocomplete="off">
<input type='hidden' name="" value="0" id="currPage" autocomplete="off">
<input type='hidden' name="" value="0" id="totalPage" autocomplete="off">
<input type="hidden" name="" value="<?php echo $data['shop']['shopName']; ?>" id="shopName" autocomplete="off">
     <section class="ui-container">
     	<div class="wst-sh-banner">
			<header class="ui-header ui-header-positive wst-se-header2 wst-se-header3">
				<i class="ui-icon-return" onclick="history.back()"></i>
				<div class="wst-se-search wst-se-search2" onclick="javascript:WST.searchPage('shops',1);">
				    <i class="ui-icon-search" onclick="javascript:WST.searchPage('shops',1);"></i>
				    <form action＝"" class="input-form">
					<input type="search" value="<?php echo $goodsName; ?>" placeholder="按关键字搜索本店商品" onsearch="WST.search(2)" autocomplete="off" disabled="disabled">
					</form>
				</div>
		       	<span class="wst-se-icon wst-se-icon0" onclick="javascript:dataShow();"></span>
		       	 <?php $cartNum = WSTCartNum(); ?>
		       	<a href="<?php echo url('mobile/carts/index'); ?>"><span class="wst-se-icon wst-se-icon2"><?php if(($cartNum>0)): ?><i><?php echo $cartNum; ?></i><?php endif; ?></span></a>
			</header>
			
     	</div>
		 <?php $shopId = $data['shop']['shopId']; ?>
		<div class="wst-intro">
			<div class="wst-intro-content">
				<div class="wst-intro-content-top">
					<div class="wst-intro-img"><a href="<?php echo url('home/shops/index',array('shopId'=>$data['shop']['shopId'])); ?>"><img src="__RESOURCE_PATH__/<?php echo $data['shop']['shopImg']; ?>" title="<?php echo WSTStripTags($data['shop']['shopName']); ?>"></a></div>
					<div class="wst-intro-img-r">
						<div class="wst-intro-shopname">
							<?php echo $data['shop']['shopName']; ?>
							<div class="wst-intro-shopname-qq">
								<img src="/upload/goods/2021-04/ysjzytb.png" alt="">
								有事您Q我 ！
							</div>
						</div>
						<p class="wst-intro-lb">类别：绘画艺术</p>
						<p class="wst-intro-lb">商品：<?php echo $data['goodsNum']; ?></p>
						<div class="wst-intro-sub"><img src="/upload/goods/2021-04/ysjzytb2.png" alt="">关注店铺</div>
					</div>
				</div>
				<div class="wst-intro-f">
					<div class="wst-intro-ff">描述 
						<img src="/upload/goods/2019-04/rwu.png" alt="">
						<img src="/upload/goods/2019-04/rwu.png" alt="">
						<img src="/upload/goods/2019-04/rwu.png" alt="">
						<img src="/upload/goods/2019-04/hwu.png" alt="">
						<img src="/upload/goods/2019-04/hwu.png" alt="">
					</div>
					<div class="wst-intro-ff">服务 
						<img src="/upload/goods/2019-04/rwu.png" alt="">
						<img src="/upload/goods/2019-04/rwu.png" alt="">
						<img src="/upload/goods/2019-04/rwu.png" alt="">
						<img src="/upload/goods/2019-04/rwu.png" alt="">
						<img src="/upload/goods/2019-04/rwu.png" alt="">
					</div>
					<div class="wst-intro-ff">物流 
						<img src="/upload/goods/2019-04/rwu.png" alt="">
						<img src="/upload/goods/2019-04/rwu.png" alt="">
						<img src="/upload/goods/2019-04/rwu.png" alt="">
						<img src="/upload/goods/2019-04/rwu.png" alt="">
						<img src="/upload/goods/2019-04/hwu.png" alt="">
					</div>
				</div>
			</div>
			
			<div class="wst-intro-content-bottom">
				<div class="wst-intro-content-title">
					<span class="wst-intro-content-bottom-text wst-intro-content-bottom-text-title">简介</span><span class="wst-intro-content-bottom-lim">/</span>
					<span class="wst-intro-content-bottom-text">作品故事</span><span class="wst-intro-content-bottom-lim">/</span>
					<span class="wst-intro-content-bottom-text">价值分析</span>
				</div>
				<div class="wst-intro-content-info">
					1959年生于四川乐山，四川美术许愿教授，中国画系主任，硕士生导师，中国美术家协会会员，中国壁画学会理事，重庆市美术家协会理事，重庆市美术家协会理事，重庆市美术家协会综合材料回话艺委会副主任。作品参加“第二届全国少数民族美术作品展”优秀奖（文化部），“第六届中国体育美术作品展览”，“2012打通国际壁画双年展”，“第三届全国壁画大展”，第二届、第三届“学院·经...
				</div>
			</div>
		</div>
		<div class="wst-selector">
			<div class='wst-lfloat'>
				<div class='item dorpdown'>
			  <div class="wst-pos">
				  <div>商品分类：</div>
				  <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
				  <div class="cat1 cat"><a href='<?php echo Url("home/goods/lists","cat=".$vo2["catId"]); ?>'><?php echo $vo2['catName']; ?></a></div>
				  <?php endforeach; endif; else: echo "" ;endif; ?>
			 </div>
			 </div>
			</div>
			 <div class='wst-clear'></div>
		 </div>
         <?php if(!empty($data['shop']['shopAds'])): ?>
         <div class="shop-ads swiper-container banner">
            <div class="swiper-wrapper" >
                <?php if(is_array($data['shop']['shopAds']) || $data['shop']['shopAds'] instanceof \think\Collection || $data['shop']['shopAds'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['shop']['shopAds'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ads): $mod = ($i % 2 );++$i;?>
                <div class="swiper-slide"><a href="<?php echo $ads['adUrl']; ?>"><img src="__RESOURCE_PATH__/<?php echo $ads['adImg']; ?>"></a></div>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
			 <div class="swiper-pagination" style="background:none;"></div>
         </div>
         <?php endif; ?>
         <ul class="wst-sh-term" id="j-top" style="display: none;">
         	<li id="j-top1" class="active" onclick="javascript:switchTerm(1);">首页</li>
         	<li id="j-top0" onclick="javascript:switchTerm(0);">全部商品</li>
         	<div class="wst-clear"></div>
         </ul>
        <div class="wst-sh-index" id="j-index1">
        <script id="shopBest" type="text/html">
         {{# for(var i=0; i<d.length; i++){ }}
             <div class="wst-in-goods {{# if((i)%2==0){ }}left{{# }else{ }}right{{# } }}" onclick="WST.intoGoods({{d[i].goodsId}})">
             <div class="img j-imgBest" onclick="WST.intoGoods({{d[i].goodsId}})">
             <a href="javascript:void(0)" onclick="WST.intoGoods({{d[i].goodsId}})">
             <img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__RESOURCE_PATH__/{{d[i].goodsImg }}" title="{{d[i].goodsName}}"/>
             </a>
             </div>
             <div class="name ui-nowrap-multi">{{ d[i].goodsName}}</div>
			 <div class="info"><span class="price"><span>{{# if(d[i].saleType==1){ }}议价{{# }else if(d[i].saleType==2){ }}仅展示{{# }else{ }}¥ {{ d[i].shopPrice }}{{# } }}</span></span></div>
			 <div class="info3">{{ d[i].thumbsNum }}</div>
             </div>
          {{# } }}
        </script>
        </div>
        
        <div class="wst-sh-list" id="j-index0" style="">
			<h2>作品/展示</h2>
        <div class="ui-row-flex wst-shl-head">
            <div class="ui-col ui-col sorts active" status="down" onclick="javascript:orderCondition(this,2);">
                 <p class="pd0">销量</p><i class="down2"></i>
            </div>
            <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,3);">
                 <p class="pd0">价格</p><i class="down"></i>
            </div>
            <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,1);">
                 <p class="pd0">人气</p><i class="down"></i>
            </div>
            <div class="ui-col ui-col sorts" status="down" onclick="javascript:orderCondition(this,6);">
                 <p>上架时间</p><i class="down"></i>
            </div>
        </div>
        <script id="shopList" type="text/html">
         {{# for(var i=0; i<d.length; i++){ }}
             <div class="wst-in-goods {{# if((i)%2==0){ }}left{{# }else{ }}right{{# } }}" onclick="WST.intoGoods({{d[i].goodsId}})">
             <div class="img j-imgAdapt" onclick="WST.intoGoods({{d[i].goodsId}})">
             <a href="javascript:void(0)" onclick="WST.intoGoods({{d[i].goodsId}})">
             <img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__RESOURCE_PATH__/{{d[i].goodsImg }}" title="{{d[i].goodsName}}"/>
             </a>
             </div>
			 <div class="goods_foot">
             	<div class="name ui-nowrap-multi">{{ d[i].goodsName}}</div>
				<div class="info"><span class="price"><span>{{# if(d[i].saleType==1){ }}议价{{# }else if(d[i].saleType==2){ }}仅展示{{# }else{ }}¥ {{ d[i].shopPrice }}{{# } }}</span></span></div>
				<div class="name ui-nowrap-multi name-author">{{ d[i].goodsAuthor}}&nbsp;{{ d[i].goodsTips}}</div>
			</div>
             </div>
          {{# } }}
        </script>

        <div id="shops-list" class="wst-sh-goods"></div>
		</div>

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


<div class="wst-toTop" id="toTop">
	<i class="wst-toTopimg"></i>
</div>

<div class="wst-cover" id="cover"></div>

<div class="wst-fr-box" id="container">
    <div class="title"><?php echo $data['shop']['shopName']; ?> - 店铺地址<i class="ui-icon-close-page" onclick="javascript:mapHide();"></i><div class="wst-clear"></div></div>
    <div id="map"></div>
</div>

<div class="wst-fr-box" id="frame">
    <div class="title">商品分类<i class="ui-icon-close-page" onclick="javascript:dataHide();"></i><div class="wst-clear"></div></div>
    <div class="content" id="content">


       <div class="ui-scrollerl" id="ui-scrollerl">
            <ul>
                <?php $wstTagShopscats =  model("common/Tags")->listShopCats(0,99,$data['shop']['shopId'],0); foreach($wstTagShopscats as $k=>$go){?>
                <li id="goodscate" class="wst-goodscate <?php if(($k==0)): ?>wst-goodscate_selected<?php endif; ?>" onclick="javascript:showRight(this,<?php echo $k; ?>);"><?php echo $go['catName']; ?></li>
                <?php } ?>
            </ul>
        </div>
        <?php $wstTagShopscats =  model("common/Tags")->listShopCats(0,99,$data['shop']['shopId'],0); foreach($wstTagShopscats as $k=>$go){?>
        <div class="wst-scrollerr goodscate1" <?php if(($k!=0)): ?>style="display:none;"<?php endif; ?>>
            <ul>
                <li class="wst-goodsca">
                    <a href="javascript:void(0);" onclick="javascript:goGoodsList(<?php echo $data['shop']['shopId']; ?>,<?php echo $go['catId']; ?>);"><span>&nbsp;<?php echo $go['catName']; ?></span></a>
                    <a href="javascript:void(0);" onclick="javascript:goGoodsList(<?php echo $data['shop']['shopId']; ?>,<?php echo $go['catId']; ?>);"><i class="ui-icon-arrow"></i></a>
                </li>
                <li>
                    <div class="wst-goodscat">
                        <?php $wstTagShopscats =  model("common/Tags")->listShopCats($go['catId'],99,$data['shop']['shopId'],0); foreach($wstTagShopscats as $key=>$go1){?>
                        <span><a href="javascript:void(0);" onclick="javascript:goGoodsList(<?php echo $data['shop']['shopId']; ?>,<?php echo $go['catId']; ?>,<?php echo $go1['catId']; ?>);"><?php echo $go1['catName']; ?></a></span>
                        <?php } ?>
                    </div>
                </li>
            </ul>
            <div class="wst-clear"></div>
        </div>
        <?php } ?>
    </div>
</div>


    <div class="wst-co-search" id="wst-shops-search" style="background-color: #f6f6f8;">
    <header class="ui-header ui-header-positive wst-se-header2" style="border-bottom: 1px solid #f6f6f8;">
		<i class="ui-icon-return" onclick="javascript:WST.searchPage('shops',0);"></i>
		<div class="wst-se-search">
		    <i class="ui-icon-search" onclick="javascript:WST.search(2);"></i>
		    <form action＝"" class="input-form">
			<input type="search" value="" placeholder="按关键字搜索本店商品" onsearch="WST.search(2)" autocomplete="off" id="wst-search">
			</form>
		</div>
		<a class="btn" href="javascript:void(0);" onclick="javascript:WST.search(2);">搜索</a>
	</header>
	<div class="list">
        <p class="search"><i></i>本店搜索</p>
        <div class="term">
            <?php if(is_array($data['shop']['shopHotWords']) || $data['shop']['shopHotWords'] instanceof \think\Collection || $data['shop']['shopHotWords'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['shop']['shopHotWords'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hot): $mod = ($i % 2 );++$i;?>
            <a href="<?php echo url('mobile/shops/goods',['goodsName'=>$hot,'shopId'=>$data['shop']['shopId']]); ?>"><?php echo $hot; ?></a>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
	</div>


<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='/static/plugins/swiper/swiper.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/shop_home.js'></script>
<script type="text/javascript" src="<?php echo WSTProtocol(); ?>map.qq.com/api/js?v=2.exp&key=<?php echo WSTConf('CONF.mapKey'); ?>"></script>

</body>
</html>