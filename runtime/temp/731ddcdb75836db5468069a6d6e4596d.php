<?php /*a:6:{s:88:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\index.html";i:1618033428;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\base.html";i:1618237658;s:86:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\top.html";i:1617513009;s:89:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\header.html";i:1617691381;s:93:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\right_cart.html";i:1617521580;s:89:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\footer.html";i:1617939615;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo WSTConf('CONF.mallName'); ?> - <?php echo WSTConf('CONF.mallSlogan'); ?></title>

<meta name="description" content="<?php echo WSTConf('CONF.seoMallDesc'); ?>">
<meta name="keywords" content="<?php echo WSTConf('CONF.seoMallKeywords'); ?>">

<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css" type="text/css" />

<link href="__STYLE__/css/index.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/shophome.css?v=<?php echo $v; ?>" rel="stylesheet">

<script type="text/javascript" src="/static/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>	
<script type='text/javascript' src='/static/js/common.js?v=<?php echo $v; ?>'></script>

<script type='text/javascript' src='__STYLE__/js/common.js?v=<?php echo $v; ?>'></script>


<?php if(((int)session('WST_USER.userId')<=0)): ?>
<link href="/static/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/login.css?v=<?php echo $v; ?>" rel="stylesheet">
<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/js/rsa.js"></script>
<script type='text/javascript' src='__STYLE__/js/login.js?v=<?php echo $v; ?>'></script>
<?php endif; ?>
<script>
window.conf = {"ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","TIME_TASK":"1","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","HTTP":"<?php echo WSTProtocol(); ?>","MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>",'RESOURCE_PATH':'<?php echo WSTConf('CONF.resourcePath'); ?>'}
$(function() {
	WST.initVisitor();
});
</script>
</head>

<body>

	<div class="wst-header">
    <div class="wst-nav">
		<ul class="headlf">
		<?php if(session('WST_USER.userId') >0): ?>
		   <li class="drop-info">
			  <div class="drop-infos">
			  <a href="<?php echo Url('home/users/index'); ?>">欢迎您，<?php echo session('WST_USER.userName')?session('WST_USER.userName'):session('WST_USER.loginName'); ?></a>
			  </div>
			  <div class="wst-tag dorpdown-user">
			  	<div class="wst-tagt">
			  	   <div class="userImg" >
				  	<img class='usersImg' data-original="<?php echo WSTUserPhoto(session('WST_USER.userPhoto')); ?>"/>
				   </div>	
				  <div class="wst-tagt-n">
				    <div style="height: 30px;overflow: hidden;">
					  	<span class="wst-tagt-na"><?php echo session('WST_USER.userName')?session('WST_USER.userName'):session('WST_USER.loginName'); ?></span>
					  	<?php if((int)session('WST_USER.rankId') > 0): $rankName = session('WST_USER.rankName');?>
					  		<img src="__RESOURCE_PATH__/<?php echo session('WST_USER.userrankImg'); ?>" title="<?php echo WSTStripTags($rankName); ?>"/>
					  	<?php endif; ?>
				  	</div>
				  	<div class='wst-tags'>
			  	     <span class="w-lfloat"><a onclick='WST.position(15,0)' href='<?php echo Url("home/users/edit"); ?>'>用户资料</a></span>
			  	     <span class="w-lfloat" style="margin-left:10px;"><a onclick='WST.position(16,0)' href='<?php echo Url("home/users/security"); ?>'>安全设置</a></span>
			  	    </div>
				  </div>
			  	  <div class="wst-tagb" >
			  		<a onclick='WST.position(5,0)' href='<?php echo Url("home/orders/waitReceive"); ?>'>待收货订单</a>
			  		<a onclick='WST.position(60,0)' href='<?php echo Url("home/logmoneys/usermoneys"); ?>'>我的余额</a>
			  		<a onclick='WST.position(49,0)' href='<?php echo Url("home/messages/index"); ?>'>我的消息</a>
			  		<a onclick='WST.position(13,0)' href='<?php echo Url("home/userscores/index"); ?>'>我的积分</a>
			  		<a onclick='WST.position(41,0)' href='<?php echo Url("home/favorites/goods"); ?>'>我的关注</a>
			  		<a style='display:none'>咨询回复</a>
			  	  </div>
			  	<div class="wst-clear"></div>
			  	</div>
			  </div>
			</li>
			<li class="spacer">|</li>
			<li class="drop-info">
			<a href='<?php echo Url("home/messages/index"); ?>' target='_blank' onclick='WST.position(49,0)'>消息（<span id='wst-user-messages'>0</span>）</a>
			</li>
			<li class="spacer">|</li>
			<li class="drop-info">
			  <div><a href="javascript:WST.logout();">退出</a></div>
			</li>
			<?php else: ?>
			<li class="drop-info">
			  <div><a href="<?php echo Url('home/users/login'); ?>" onclick="WST.currentUrl();">HI~请登录</a></div>
			</li>
			<li class="spacer">|</li>
			<li class="drop-info">
			  <div><a href="<?php echo Url('home/users/regist'); ?>" onclick="WST.currentUrl();">免费注册</a></div>
			</li>
			<?php endif; ?>
		</ul>
		<ul class="headrf" style='float:right;'>
			<?php if((WSTConf('CONF.wxenabled')==1)): ?>
			<!-- <li class="spacer">|</li> -->
			<li class="j-dorpdown">
				<div class="drop-down drop-down2 pdr5"><i class="di-left"></i><a href="#" target="_blank">关注我们</a></div>
				<div class='j-dorpdown-layer des-list' style="width:120px;">
					<div style="height:114px;"><?php if((WSTConf('CONF.wxAppLogo'))): ?><img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.wxAppLogo'); ?>" style="height:114px;"><?php endif; ?></div>
					<div>关注我们</div>
				</div>
			</li>
			<?php endif; ?>
			<!-- <li class="spacer">|</li> -->
			<li class="j-dorpdown">
				<div class="drop-down drop-down5 pdr5"><a href="#" target="_blank">我的收藏</a></div>
				<div class='j-dorpdown-layer foucs-list'>
				   <div><a href="<?php echo Url('home/favorites/goods'); ?>" onclick='WST.position(41,0)'>商品收藏</a></div>
				   <div><a href="<?php echo Url('home/favorites/shops'); ?>" onclick='WST.position(46,0)'>店铺收藏</a></div>
				</div>
			</li>
			<li class="j-dorpdown j-dorpdown-cart">
				<div class="wst-cart-box">
					<a href="<?php echo url('home/carts/index'); ?>" target="_blank" onclick="WST.currentUrl('<?php echo url("home/carts/index"); ?>');"><span class="word j-word">我的购物车(<span class="num" id="goodsTotalNum">0</span>)</span></a>
					<div class="wst-cart-boxs hide">
						<div id="list-carts"></div>
						<div id="list-carts2"></div>
						<div id="list-carts3"></div>
						<div class="wst-clear"></div>
					</div>
				</div>
			</li>
		    <li class="j-dorpdown" style="width: 100px;">
				<div class="drop-down drop-down6 pdr5">
					<a href="<?php echo Url('home/users/index'); ?>" target="_blank">我的订单<i class="di-right"><s>◇</s></i></a>
				</div>
				<div class='j-dorpdown-layer order-list'>
				   <div><a href='<?php echo Url("home/orders/waitPay"); ?>' onclick='WST.position(3,0)'>待付款订单</a></div>
				   <div><a href='<?php echo Url("home/orders/waitReceive"); ?>' onclick='WST.position(5,0)'>待发货订单</a></div>
				   <div><a href='<?php echo Url("home/orders/waitAppraise"); ?>' onclick='WST.position(6,0)'>待评价订单</a></div>
				</div>
			</li>
			<!-- <li class="spacer">|</li> -->
			<?php if(session('WST_USER.userId') > 0 && session('WST_USER.userType') == 1): ?>
			<li class="drop-down drop-down4 pdr5"><a target="_blank" href="<?php echo Url('shop/index/index'); ?>" rel="nofollow">商家中心</a></li>
            <?php else: ?>
			<li class="j-dorpdown" style="width:94px">
				<div class="drop-down drop-down4 pdl5"><a href="#">商家管理</a><i class="di-right-pdl"><s>◇</s></i></div>
				<div class='j-dorpdown-layer foucs-list'>
				   <div><a href="<?php echo url('shop/index/login'); ?>" target="_blank">商家登录</a></div>
				   <div><a href="<?php echo url('home/shops/join','type=0'); ?>" rel="nofollow" onclick="WST.currentUrl('<?php echo url("home/shops/join","type=0"); ?>');">商家入驻</a></div>
				   <div><a href="<?php echo url('home/shops/join','type=1'); ?>" rel="nofollow" onclick="WST.currentUrl('<?php echo url("home/shops/join","type=1"); ?>');">个人入驻</a></div>
				</div>
				</li>
            <?php endif; ?>
		</ul>
		<div class="wst-clear"></div>
  </div>
</div>
<script>
$(function(){
	//二维码
	//参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
	var a = qrcode(8, 'H');
	var url = window.location.host+window.conf.APP;
	a.addData(url);
	a.make();
	$('#qrcodea').html(a.createImgTag());
});
function goShop(id){
  location.href=WST.U('home/shops/index','shopId='+id);
}
</script>
<script type='text/javascript' src='__STYLE__/js/qrcode.js'></script>


	<div style="height:120px;margin:20px auto 0;width:100%;">
<div class='wst-search-container' style="width:1000px">
   <div class='wst-logo'>
    <?php $mallName = WSTConf('CONF.mallName'); ?>
   <a href='<?php echo app('request')->root(true); ?>' title="<?php echo WSTStripTags($mallName); ?>" >
      <img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.mallLogo'); ?>" style="max-width:240px;max-height:120px" title="<?php echo WSTConf('CONF.mallName'); ?>" alt="<?php echo WSTConf('CONF.mallName'); ?>">
   </a>
   </div>
   <div style="z-index:100;overflow:hidden;float:left;margin-top: 6px;">
		<div style="float: left;">
		
			<div id="wst-nav-items">
				<ul>
					<?php $_result=WSTNavigations(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
					<li class="fore1">
						<a href="<?php echo $vo['navUrl']; ?>" <?php if($vo['isOpen']==1): ?>target="_blank"<?php endif; ?>><?php echo $vo['navTitle']; ?></a>
					</li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
		</div>
	</div>
	<div class="wst-search-box">
		<div class='wst-search'>
			  <input type="hidden" id="search-type" value="0"/>
			<input type="text" id='search-ipt' class='search-ipt' value=''/>
		</div>
		<div id='search-btn' class="wst-search-btn" onclick='javascript:WST.search(this.value)'></div>
	 </div>
   <!-- <div class="wst-cart-box">
   <a href="<?php echo url('home/carts/index'); ?>" target="_blank" onclick="WST.currentUrl('<?php echo url("home/carts/index"); ?>');"><span class="word j-word">我的购物车<span class="num" id="goodsTotalNum" style='display:none'>0</span></span></a>
   	<div class="wst-cart-boxs hide">
   		<div id="list-carts"></div>
   		<div id="list-carts2"></div>
   		<div id="list-carts3"></div>
	   	<div class="wst-clear"></div>
   	</div>
   </div> -->

<script id="list-cart" type="text/html">
{{# for(var i = 0; i < d.list.length; i++){ }}
	<div class="goods" id="j-goods{{ d.list[i].cartId }}">
	   	<div class="imgs"><a href="{{ WST.U('home/goods/detail','goodsId='+d.list[i].goodsId) }}"><img class="goodsImgc" data-original="__RESOURCE_PATH__/{{ d.list[i].goodsImg }}" title="{{ d.list[i].goodsName }}"></a></div>
	   	<div class="number"><p><a  href="{{ WST.U('home/goods/detail','goodsId='+d.list[i].goodsId) }}">{{WST.cutStr(d.list[i].goodsName,26)}}</a></p><p>数量：{{ d.list[i].cartNum }}</p></div>
	   	<div class="price"><p>{{# if(d.list[i].saleType==1){ }}议价{{# }else if(d.list[i].saleType==2){ }}仅展示{{# }else{ }}￥{{ d.list[i].shopPrice }}{{# } }}</p><span><a href="javascript:WST.delCheckCart({{ d.list[i].cartId }})">删除</a></span></div>
	</div>
{{# } }}
</script>
</div>
<div class="wst-clear"></div>



<div class="wst-clear"></div>


<div class="wst-ads" style="position:relative;" >
	<div class="wst-slide" id="wst-slide">
		<ul class="wst-slide-items">
			<?php $wstTagAds =  model("common/Tags")->listAds("ads-index",99,86400); foreach($wstTagAds as $key=>$vo){?>
				<a href="<?php echo $vo['adURL']; ?>" <?php if(($vo['isOpen'])): ?>target='_blank'<?php endif; if(($vo['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $vo['adId']; ?>)"<?php endif; ?>><li style="background: url(__RESOURCE_PATH__/<?php echo $vo['adFile']; ?>) no-repeat  scroll center top;background-size:cover;" ></li></a>
			<?php } ?>
		</ul>
		<div class="wst-slide-numbox">
			<div style="position:absolute;right:0;top:-420px;">
			</div>
			<div style="width: 1000px;position: relative;margin:0 auto;">
				<div class="wst-slide-controls">
					<?php $wstTagAds =  model("common/Tags")->listAds("ads-index",99,86400); foreach($wstTagAds as $k=>$vo){if($k+1 == 1): ?>
							<span class="curr"></span>
						<?php else: ?>
							<span class=""></span>
						<?php endif; } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="width:1000px;margin:20px auto 0 auto;">
	
	<div class="sector_pack">
		<h2 class="sector_h2">精选作品</h2>
		<div class="sector_desc">
			<p>SELECTED WORKS</p>
			<a href="/category-365.html">查看更多>></a>
		</div>
		<div class="sector_content">
			<div class="zp_big">
				<img class="zp_big_img" src="/upload/goods/2019-04/5cc561bf41a801.JPG" alt="">
				<div class="zp_big_tost">
					<h2 class="zp_big_title">精选作品</h2>
					<p class="zp_big_desc">SELECTED WORKS</p>
					<ul class="zp_big_category">
						<?php $_result=WSTSideCategorys();if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $k = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
						<li><a href="<?php echo url('home/goods/lists','cat='.$vo['catId']); ?>"><?php echo WSTMSubstr($vo['catName'],0,2); ?></a></li>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
			<div class="zp_litter">
				<ul>
					<?php
						$goodsInfo = WSTGoodsCatsList();
						foreach ($goodsInfo as $k => $goodInfo) {
					?>
					<li>
						<a href="<?php echo url('home/goods/detail','goodsId='.$goodInfo['goodsId']); ?>">
							<img src="/<?php echo $goodInfo['goodsImg']; ?>" alt="">
							<div class="zp_mod">
								<p class="zp_author">作者 <?php echo $goodInfo['goodsAuthor']; ?></p>
								<p class="zp_size">尺寸<?php echo $goodInfo['goodsTips']; ?></p>
								<p class="zp_name"><?php echo $goodInfo['goodsName']; ?></p>
								<p class="zp prize">价格 ￥<span><?php echo $goodInfo['shopPrice']; ?></span></p>
							</div>
						</a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="sector_pack">
		<h2 class="sector_h2">优惠专区</h2>
		<div class="sector_desc">
			<p>PREFERENTIAL ZONE</p>
			<a href="/category-365.html">查看更多>></a>
		</div>
		<div class="sector_content_yh">
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
			<div class="yh_big">
				<img class="yh_big_img" src="/upload/goods/2019-04/5cc561bf41a815.JPG" alt="">
				<div class="yh_big_tost">
					<h2 class="yh_big_title">优惠专区</h2>
					<p class="yh_big_desc">PREFERENTIAL ZONE</p>
					<button>立即进入</button>
				</div>
			</div>
		</div>
	</div>
	
</div>
<div class="wst-clear"></div>
<div class="artist">
	<div class="sector_artist">
		<h2 class="sector_h2">艺术家</h2>
		<div class="sector_desc">
			<p>PREFERENTIAL ZONE</p>
			<a href="/street.html">查看更多>></a>
		</div>
		<div class="artist_content">
			<?php
				$shopList = WSTShopList('1');
				foreach ($shopList as $k => $shopIfo) {
			?>
			<div class="artist_big">
				<a href="<?php echo url('home/shops/index',['shopId'=>$shopIfo['shopId']]); ?>">
					<img src="/<?php echo $shopIfo['shopImg']; ?>" alt="">
					<p><?php echo $shopIfo['shopName']; ?></p>
				</a>
			</div>
			<?php } ?>
			<ul>
				<?php
					$shopList = WSTShopList('1,12');
					foreach ($shopList as $k => $shopIfo) {
				?>
				<li>
					<a href="<?php echo url('home/shops/index',['shopId'=>$shopIfo['shopId']]); ?>">
						<img src="/<?php echo $shopIfo['shopImg']; ?>" alt="">
						<p><?php echo $shopIfo['shopName']; ?></p>
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
		<p>BRAND ACTIVITIES</p>
		<a href="">查看更多>></a>
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
		<p>ART SERVICES</p>
		<a href="">查看更多>></a>
	</div>
	<div class="services_content">
		<ul>
			<li>
				<img src="/upload/goods/2021-04/5cc562b141a811.JPG" alt="">
				<div class="mark">
					<p>艺术服务</p>
					<a href="/news/catId/402">点我查看详情</a>
				</div>
			</li>
			<li>
				<img src="/upload/goods/2021-04/5cc562b141a812.JPG" alt="">
				<div class="mark">
					<p>税收服务</p>
					<a href="/news/catId/403">点我查看详情</a>
				</div>
			</li>
			<li>
				<img src="/upload/goods/2021-04/5cc562b141a813.JPG" alt="">
				<div class="mark">
					<p>作品征集</p>
					<a href="/news/catId/404">点我查看详情</a>
				</div>
			</li>
		</ul>
	</div>
</div>
<div class="wst-clear"></div>

<link href="__STYLE__/css/right_cart.css?v=<?php echo $v; ?>" rel="stylesheet">
<div class="j-global-toolbar">
	<div class="toolbar-wrap j-wrap" >
		<div class="toolbar">
			<div class="toolbar-panels j-panel">
				<div style="visibility: hidden;" class="j-content toolbar-panel tbar-panel-cart toolbar-animate-out">
					<h3 class="tbar-panel-header j-panel-header">
						<a href="" class="title"><i></i><em class="title">购物车</em></a>
						<span class="close-panel j-close" title='关闭'></span>
					</h3>
					<div class="tbar-panel-main" >
						<div class="tbar-panel-content j-panel-content">
						    <?php if(session('WST_USER.userId') == 0): ?>
							<div id="j-cart-tips" class="tbar-tipbox hide">
								<div class="tip-inner">
									<span class="tip-text">还没有登录，登录后商品将被保存</span>
									<a href="#none" onclick='WST.loginWindow()' class="tip-btn j-login">登录</a>
								</div>
							</div>
							<?php endif; ?>
							<div id="j-cart-render">
								<div id='cart-panel' class="tbar-cart-list"></div>
								  <script id="list-rightcart" type="text/html">
								  {{#
                                    var shop,goods,specs;
                                    for(var key in d){
                                        shop = d[key];
					                    for(var i=0;i<shop.list.length;i++){
                                           goods = shop.list[i];
						                   goods.goodsImg = WST.conf.RESOURCE_PATH+"/"+goods.goodsImg.replace('.','_thumb.');
						                   specs = '';
						                   if(goods.specNames && goods.specNames.length>0){
							                  for(var j=0;j<goods.specNames.length;j++){
								                 specs += goods.specNames[j].itemName+ " ";
							                  }
						                   }
                                   }}
								   <div class="tbar-cart-item" id="shop-cart-{{shop.shopId}}">
							          <div class="jtc-item-promo">
							            <div class="promo-text">{{shop.shopName}}</div>
							          </div>
								      <div class="jtc-item-goods j-goods-item-{{goods.cartId}}" dataval="{{goods.cartId}}">
								          <div class='wst-lfloat'>
			                                 <input type='checkbox' id='rcart_{{goods.cartId}}' class='rchk' onclick='javascript:checkRightChks({{goods.cartId}},this);' {{# if(goods.isCheck==1){}}checked{{# } }}/></div>
									      <span class="p-img"><a target="_blank" href="{{WST.U('home/goods/detail','goodsId='+goods.goodsId)}}" target="_blank"><img src="{{goods.goodsImg}}" title="{{goods.goodsName}}" height="50" width="50"></a></span>
									      <div class="p-name">
									          <a target="_blank" title="{{(goods.goodsName+((specs!='')?"【"+specs+"】":""))}}" href="{{WST.U('home/goods/detail','goodsId='+goods.goodsId)}}">{{WST.cutStr(goods.goodsName,22)}}<br/>{{specs}}</a>
									      </div>
									      <div class="p-price">
									          <strong>{{# if(goods.saleType==1){ }}议价{{# }else if(goods.saleType==2){ }}仅展示{{# }else{ }}¥<span id='gprice_{{goods.cartId}}'>{{goods.shopPrice}}</span>{{# } }}</strong> 
									          <div class="wst-rfloat">
									             <a href="#none" class="buy-btn" id="buy-reduce_{{goods.cartId}}" onclick="javascript:WST.changeIptNum(-1,'#buyNum','#buy-reduce,#buy-add','{{goods.cartId}}','statRightCartMoney')">-</a>
									             <input type="text" id="buyNum_{{goods.cartId}}" class="right-cart-buy-num" value="{{goods.cartNum}}" data-max="{{goods.goodsStock}}" data-min="1" onkeyup="WST.changeIptNum(0,'#buyNum','#buy-reduce,#buy-add',{{goods.cartId}},'statRightCartMoney')" autocomplete="off"  onkeypress="return WST.isNumberKey(event);" maxlength="6"/>
									             <a href="#none" class="buy-btn" id="buy-add_{{goods.cartId}}" onclick="javascript:WST.changeIptNum(1,'#buyNum','#buy-reduce,#buy-add','{{goods.cartId}}','statRightCartMoney')">+</a>
									          </div>
									     </div>
									      <span onclick="javascript:delRightCart(this,{{goods.cartId}});" dataid="{{shop.shopId}}|{{goods.cartId}}" class="goods-remove" title="删除"></span>
									 </div>
								 </div>    
								 {{# } } }} 
                                 </script>   	
							</div>
						</div>
					</div>
					<div class="tbar-panel-footer j-panel-footer">
						<div class="tbar-checkout">
							<div class="jtc-number">已选<strong id="j-goods-count">0</strong>件商品 </div>
							<div class="jtc-sum"> 共计：¥<strong id="j-goods-total-money">0</strong> </div>
							<a class="jtc-btn j-btn" href="#none" onclick='javascript:jumpSettlement()'>去结算</a>
						</div>
					</div>
				</div>

				<div style="visibility: hidden;" data-name="follow" class="j-content toolbar-panel tbar-panel-follow">
					<h3 class="tbar-panel-header j-panel-header">
						<a href="#" target="_blank" class="title"> <i></i> <em class="title">我的关注</em> </a>
						<span class="close-panel j-close" title='关闭'></span>
					</h3>
					<div class="tbar-panel-main">
						<div class="tbar-panel-content j-panel-content">
							<div class="tbar-tipbox2">
								<div class="tip-inner"> <i class="i-loading"></i> </div>
							</div>
						</div>
					</div>
					<div class="tbar-panel-footer j-panel-footer"></div>
				</div>
				<div style="visibility: hidden;" class="j-content toolbar-panel tbar-panel-history toolbar-animate-in">
					<h3 class="tbar-panel-header j-panel-header">
						<a href="#none" class="title"> <i></i> <em class="title">我的足迹</em> </a>
						<span class="close-panel j-close" title='关闭'></span>
					</h3>
					<div class="tbar-panel-main">
						<div class="tbar-panel-content j-panel-content">
							<div class="jt-history-wrap">
								<ul id='history-goods-panel'></ul>
								<script id="list-history-goods" type="text/html">
								{{# 
                                 for(var i = 0; i < d.length; i++){ 
                                  d[i].goodsImg = WST.conf.RESOURCE_PATH+"/"+d[i].goodsImg.replace('.','_thumb.');
                                 }}
								   <li class="jth-item">
										<a target='_blank' href="{{WST.U('home/goods/detail','goodsId='+d[i].goodsId)}}" class="img-wrap"><img src="{{d[i].goodsImg}}" height="100" width="100"> </a>
										<a class="add-cart-button" href="javascript:WST.addCart({{d[i].goodsId}});">加入购物车</a>
										<a href="#none" class="price">￥{{d[i].shopPrice}}</a>
									</li>
								{{# } }}
                                </script>
							</div>
						</div>
					</div>
					<div class="tbar-panel-footer j-panel-footer"></div>
				</div>
			</div>
			
			<div class="toolbar-header"></div>
			
			<div class="toolbar-tabs j-tab">
				
				<div class="toolbar-tab tbar-tab-cart">
					<i class="tab-ico"></i>
					<em class="tab-text">购物车</em>
					<span class="tab-sub j-cart-count hide"></span>
				</div>
				<div class="toolbar-tab tbar-tab-follow" style='display:none'>
					<i class="tab-ico"></i>
					<em class="tab-text">我的关注</em>
					<span class="tab-sub j-count hide">0</span> 
				</div>
				<div class=" toolbar-tab tbar-tab-history ">
					<i class="tab-ico"></i>
					<em class="tab-text">我的足迹</em>
					<span class="tab-sub j-count hide"></span>
				</div>
				<div class="toolbar-tab tbar-tab-message">
				  <a target='_blank' href='<?php echo Url("home/messages/index"); ?>' onclick='WST.position(49,0)'>
					<i class="tab-ico"></i>
					<em class="tab-text">我的消息</em>
					<span class="tab-sub j-message-count hide"></span> 
				  </a>
				</div>
				<div class=" toolbar-tab tbar-tab-feedback"> <a href="<?php echo url('home/feedbacks/index'); ?>" target="_blank"> <i class="tab-ico"></i> <em class="footer-tab-text ">反馈</em> </a> </div>
			</div>
			
			<div class="toolbar-footer">
				<div class="toolbar-tab tbar-tab-top"> <a href="#"> <i class="tab-ico  "></i> <em class="footer-tab-text">顶部</em> </a> </div>
			</div>
			<div class="toolbar-mini"></div>
		</div>
		<div id="j-toolbar-load-hook"></div>		
	</div>
</div>
<script type='text/javascript' src='__STYLE__/js/right_cart.js?v=<?php echo $v; ?>'></script>


<?php $wstTagAds =  model("common/Tags")->listAds("index-top-ads",99,86400); foreach($wstTagAds as $key=>$tads){if(($tads['adFile']!='')): ?>
<div class="index-top-ads">
  <a href="<?php echo $tads['adURL']; ?>" <?php if(($tads['isOpen'])): ?>target='_blank'<?php endif; if(($tads['adURL']!='')): ?>onclick="WST.recordClick(<?php echo $tads['adId']; ?>)"<?php endif; ?> onfocus="this.blur();">
    <img src="/<?php echo $tads['adFile']; ?>"></a>
</div>
<?php endif; } ?>


	<div class="wst-footer-help">
	<div class="wst-footer">
		<div class="wst-footer-hp-ck1">
		   <div style="width:900px;margin:auto">
			<?php $_result=WSTHelps(5,6);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?>
			<div class="wst-footer-wz-ca">
				<div class="wst-footer-wz-pt">
					<span class="wst-footer-wz-pn"><?php echo $vo1["catName"]; ?></span>
					<ul style='margin-left:10px;'>
						<?php if(is_array($vo1['articlecats']) || $vo1['articlecats'] instanceof \think\Collection || $vo1['articlecats'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo1['articlecats'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
						<li color:#fff;font-size:12px;'>
						<a href="<?php echo Url('Home/Helpcenter/view',array('id'=>$vo2['articleId'])); ?>"><?php echo WSTMSubstr($vo2['articleTitle'],0,8); ?></a>
						</li>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="wst-clear"></div>
		</div>
		</div>
	</div>
</div>
<?php echo hook('homeDocumentListener'); ?>
<?php echo hook('initCronHook'); ?>


<script>
$(function(){
	//二维码
	//参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
	var a = qrcode(8, 'M');
	var url = window.location.host+window.conf.APP;
	a.addData(url);
	a.make();
	$('#qrcodea_l').html(a.createImgTag());
});
function goShop(id){
  location.href=WST.U('home/shops/home','shopId='+id);
}
$(".zp_litter li").hover(function(){
	$(this).find(".zp_mod").css("display", "block");
},function(){
	$(this).find(".zp_mod").css("display", "none");
});
</script>
<script type='text/javascript' src='__STYLE__/js/qrcode.js?v=<?php echo $v; ?>'></script>
<script async="async" type='text/javascript' src='__STYLE__/js/index.js?v=<?php echo $v; ?>'></script>

</body>
</html>