<?php /*a:6:{s:95:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\goods_search.html";i:1602924267;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\base.html";i:1618237658;s:86:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\top.html";i:1617513009;s:89:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\header.html";i:1617691381;s:93:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\right_cart.html";i:1617521580;s:89:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\footer.html";i:1617939615;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>商品搜索 -<?php echo WSTConf('CONF.mallName'); ?></title>

<meta name="description" content="<?php echo WSTConf('CONF.seoMallDesc'); ?>，商品关键字搜索">
<meta name="Keywords" content="<?php echo $keyword; ?>">

<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css" type="text/css" />

<link href="__STYLE__/css/goodslist.css?v=<?php echo $v; ?>" rel="stylesheet">

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



<input type="hidden" id="keyword" class="sipt" value='<?php echo $keyword; ?>'/>
<input type="hidden" id="orderBy" class="sipt" value='<?php echo $orderBy; ?>'/>
<input type="hidden" id="order" class="sipt" value='<?php echo $order; ?>'/>
<input type="hidden" id="areaId" class="sipt" value='<?php echo $areaId; ?>'/>
<div class='wst-filters'>
   <div class='item' style="border-left:2px solid #df2003;padding-left: 8px;">
      <a class='link' href=''>全部结果</a>
      <i class="arrow">></i>
   </div>
   <div class='wst-lfloat keyword'><?php echo $keyword; ?></div>
   <div class='wst-clear'></div>
</div>

<div class="wst-container">
   <?php if(!empty($goodsPage["data"])): ?>
	<div class='goods-side'>
		<div class="guess-like">
		    <div class="title">猜你喜欢</div>
			<?php $wstTagGoods =  model("common/Tags")->listGoods("best",-1,3,0); foreach($wstTagGoods as $key=>$vo){?>
			<div class="item">
				<div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>"><img class='goodsImg' data-original="__RESOURCE_PATH__/<?php echo WSTImg($vo['goodsImg']); ?>" alt="<?php echo WSTStripTags($vo['goodsName']); ?>" title="<?php echo WSTStripTags($vo['goodsName']); ?>"/></a></div>
				<div class="p-name"><a class="wst-hide wst-redlink"><?php echo $vo['goodsName']; ?></a></div>
				<div class="p-price"><?php if(($vo['saleType']==1)): ?>议价<?php elseif(($vo['saleType']==2)): ?>仅展示<?php else: ?>￥<?php echo $vo['shopPrice']; ?><?php endif; ?></div>
			</div>
			<?php } ?>
		</div>
	</div>
	<div class='goods-main'>
	   <div class='goods-filters'>
	   	  <div class='line'>
	   	  <div class='wst-lfloat chk'>发货地</div>
	        <div class='city wst-address'>
		    <div class='item dorpdown'>
		     <div class='drop-down'>
		        <a class='link' href=''>
		        	<?php if(empty($areaInfo['areaName'])): ?>
		        	请选择
		        	<?php else: ?>
		        		<?php echo $areaInfo['areaName']; ?>
		        	<?php endif; ?>
		        </a>
		        <i class="drop-down-arrow"></i>
		     </div>
		     <div class="dorp-down-layer">
		     	<div class="tab-header">
		     	 <ul class="tab">
		     	 	<li class="tab-item1" id="fl_1_1" onclick="gpanelOver(this);" c="1" >
		     	 		<?php if(isset($areaInfo)): ?>
		     	 		<a href='javascript:void(0)'><?php echo $areaInfo[0]['areaName']; ?></a>
		     	 		<?php else: ?>
		     	 		<a href='javascript:void(0)'>请选择</a>
		     	 		<?php endif; ?>
		     	 	</li>

		     	 	<?php if(isset($areaInfo)): ?>
		     	 	<li class="tab-item1" id="fl_1_2" onclick="gpanelOver(this);" c="1" >
						<a href="javascript:void(0)"><?php echo $areaInfo[1]['areaName']; ?></a>
					</li>
					<li class="tab-item1 j-tab-selected1" id="fl_1_3" onclick="gpanelOver(this);" c="1" >
						<a href="javascript:void(0)"><?php echo $areaInfo[2]['areaName']; ?></a>
					</li>
					<?php else: ?>
					<li class="tab-item1" id="fl_1_2" onclick="gpanelOver(this);" c="1" pid="" >
						<a href="javascript:void(0)">请选择</a>
					</li>
					<li class="tab-item1 j-tab-selected1" id="fl_1_3" onclick="gpanelOver(this);" c="1" pid="" >
						<a href="javascript:void(0)">请选择</a>
					</li>
					<?php endif; ?>


					
		     	 </ul>
		     	</div>
		     	 <ul class="area-box" id="fl_1_1_pl" style="display:none;">
		     	 	<?php if(is_array($area1) || $area1 instanceof \think\Collection || $area1 instanceof \think\Paginator): $i = 0; $__LIST__ = $area1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$area1): $mod = ($i % 2 );++$i;?>
					<li onclick="choiceArea(this,<?php echo $area1['areaId']; ?>)" search='1'><a href="javascript:void(0)"><?php echo $area1['areaName']; ?></a></li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
				<ul class="area-box" id="fl_1_2_pl" style="display:none;">
					<?php if(is_array($area2) || $area2 instanceof \think\Collection || $area2 instanceof \think\Paginator): $i = 0; $__LIST__ = $area2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$area2): $mod = ($i % 2 );++$i;?>
					<li onclick="choiceArea(this,<?php echo $area2['areaId']; ?>)" search='1'><a href="javascript:void(0)"><?php echo $area2['areaName']; ?></a></li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>

				<ul class="area-box" id="fl_1_3_pl">
					<?php if(is_array($area3) || $area3 instanceof \think\Collection || $area3 instanceof \think\Paginator): $i = 0; $__LIST__ = $area3;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$area3): $mod = ($i % 2 );++$i;?>
					<li onclick="choiceArea(this,<?php echo $area3['areaId']; ?>)" search='1'><a href="javascript:void(0)"><?php echo $area3['areaName']; ?></a></li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>

			</div>


			</div>
			</div>
	        <div class='chk'>
			 <div class="checkbox-box-s">
		     <input name='isStock' value='1' class="sipt wst-checkbox-s" onclick="searchFilter(this,4)" type='checkbox' id="stock" <?php if($isStock==1): ?>checked<?php endif; ?>/>
		     <label for="stock"></label>
		     </div>
	                  仅显示有货</div>
	        <div class='chk'>
	         <div class="checkbox-box-s">
		     <input name='isNew' value='1' class="sipt wst-checkbox-s" onclick="searchFilter(this,4)" type='checkbox' id="new" <?php if($isNew==1): ?>checked<?php endif; ?>/>
		     <label for="new"></label>
		     </div>
	                  新品</div>
	      </div>
	      <div class='line line2'>
	        <a class="<?php if($orderBy == 0): ?>curr <?php endif; ?>" href='javascript:void(0)' onclick='javascript:searchOrder(0)'>销量<span class="<?php if($orderBy != 0): ?>store<?php endif; if($orderBy == 0 and $order == 1): ?>store2<?php endif; if($orderBy == 0 and $order == 0): ?>store3<?php endif; ?>"></span></a>
	        <a class="<?php echo $orderBy==1 ? 'curr' : ''; ?>" href='javascript:void(0)' onclick='javascript:searchOrder(1)'>价格<span class="<?php if($orderBy != 1): ?>store<?php endif; if($orderBy == 1 and $order == 1): ?>store2<?php endif; if($orderBy == 1 and $order == 0): ?>store3<?php endif; ?>"></span></a>
	        <a class="<?php echo $orderBy==2 ? 'curr' : ''; ?>" href='javascript:void(0)' onclick='javascript:searchOrder(2)'>评论数<span class="<?php if($orderBy != 2): ?>store<?php endif; if($orderBy == 2 and $order == 1): ?>store2<?php endif; if($orderBy == 2 and $order == 0): ?>store3<?php endif; ?>"></span></a>
	        <a class="<?php echo $orderBy==3 ? 'curr' : ''; ?>" href='javascript:void(0)' onclick='javascript:searchOrder(3)'>人气<span class="<?php if($orderBy != 3): ?>store<?php endif; if($orderBy == 3 and $order == 1): ?>store2<?php endif; if($orderBy == 3 and $order == 0): ?>store3<?php endif; ?>"></span></a>
	        <a class="<?php echo $orderBy==4 ? 'curr' : ''; ?>" href='javascript:void(0)' onclick='javascript:searchOrder(4)'>上架时间<span class="<?php if($orderBy != 4): ?>store<?php endif; if($orderBy == 4 and $order == 1): ?>store2<?php endif; if($orderBy == 4 and $order == 0): ?>store3<?php endif; ?>"></span></a>
        	<div class="wst-price-ipts">
			<span class="wst-price-ipt1">￥</span><span class="wst-price-ipt2">￥</span>
			<input type="text" class="sipt wst-price-ipt" id="minPrice" value="<?php echo $minPrice; ?>" style="margin-left:8px;" onkeypress='return WST.isNumberdoteKey(event);' onkeyup="javascript:WST.isChinese(this,1)">
			- <input type="text" class="sipt wst-price-ipt" id="maxPrice" value="<?php echo $maxPrice; ?>" onkeypress='return WST.isNumberKey(event);' onkeyup="javascript:WST.isChinese(this,1)">
			</div>
			<button class="wst-price-but" type="submit" style="width:60px;height: 25px;" onclick='javascript:searchOrder()'>确定</button>
	      </div>
	   </div>
	   <div class="goods-list">
	      <?php if(is_array($goodsPage["data"]) || $goodsPage["data"] instanceof \think\Collection || $goodsPage["data"] instanceof \think\Paginator): $i = 0; $__LIST__ = $goodsPage["data"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	      <div class="goods">
	      	<div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>"><img title="<?php echo $vo['goodsName']; ?>" alt="<?php echo $vo['goodsName']; ?>" class='goodsImg2' data-original="/<?php echo WSTImg($vo['goodsImg']); ?>"/></a></div>
	      	<div class="p-name"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>" class="wst-redlink" title="<?php echo $vo['goodsName']; ?>"><?php echo $vo['goodsName']; ?></a></div>
	      	<div>
	      		<div class="p-price"><?php if(($vo['saleType']==1)): ?>议价<?php elseif(($vo['saleType']==2)): ?>仅展示<?php else: ?>￥<?php echo $vo['shopPrice']; ?><?php endif; ?></div>
	      		<div class="p-hsale">
	      			<div class="sale-num">成交数：<span class="wst-fred"><?php echo $vo['saleNum']; ?></span></div>
		      		<a class="p-add-cart" style="display:none;" href="javascript:WST.addCart(<?php echo $vo['goodsId']; ?>);">加入购物车</a>
	      		</div>
	      		<div class='wst-clear'></div>
	      	</div>
	      	<div>
	      		<div class="p-appraise">已有<span class="wst-fred"><?php echo $vo['appraiseNum']; ?></span>人评价</div>
	      		<div class='wst-clear'></div>
	      	</div>
	      	<div class="p-shop"><a href="<?php echo Url('home/shops/home','shopId='.$vo['shopId']); ?>" target='_blank' class="wst-redlink"><?php echo $vo['shopName']; ?></a></div>
	      	<div class="tags">
	      	  <?php if(is_array($vo['tags']) || $vo['tags'] instanceof \think\Collection || $vo['tags'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['tags'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tv): $mod = ($i % 2 );++$i;?>
	      	  <?php echo $tv; ?>
	      	  <?php endforeach; endif; else: echo "" ;endif; ?>
	      	</div>
	      </div>
	      
	      <?php endforeach; endif; else: echo "" ;endif; ?>
	     <div class='wst-clear'></div>
	   	</div>
	   	<div style="width:980px;">
	  		<div id="wst-pager"></div>
		</div>
		
	</div>
	<div class='wst-clear'></div>
	<?php else: ?>
	<div class="wst-no-goods">很抱歉，没有找到“<span><?php echo $keyword; ?></span>”为关键字的商品搜索结果。</div>
	
	<div class="wst-recommend">
		<div class="title">为您推荐<span style="float: right;"></span></div>
		<div class="tgoods-list">
	      <?php $wstTagGoods =  model("common/Tags")->listGoods("best",-1,5,0); foreach($wstTagGoods as $key=>$rvo){?>
	      <div class="goods">
	      	<div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$rvo['goodsId']); ?>"><img title="<?php echo WSTStripTags($rvo['goodsName']); ?>" alt="<?php echo WSTStripTags($rvo['goodsName']); ?>" class='goodsImg' data-original="__RESOURCE_PATH__/<?php echo $rvo['goodsImg']; ?>"/></a></div>
	      	<div class="p-name"><a target='_blank' class="wst-redlink"><?php echo $rvo['goodsName']; ?></a></div>
	      	<div>
	      		<div class="p-price"><?php if(($rvo['saleType']==1)): ?>议价<?php elseif(($rvo['saleType']==2)): ?>仅展示<?php else: ?>￥<?php echo $rvo['shopPrice']; ?><?php endif; ?></div>
	      		<div class="p-hsale">
	      			<div class="sale-num">成交数：<span class="wst-fred"><?php echo $rvo['saleNum']; ?></span></div>
		      		<a class="p-add-cart" style="display:none;" href="javascript:WST.addCart(<?php echo $rvo['goodsId']; ?>);">加入购物车</a>
	      		</div>
	      		<div class='wst-clear'></div>
	      	</div>
	      	<div>
	      		<div class="p-appraise">已有<span class="wst-fred"><?php echo $rvo['appraiseNum']; ?></span>人评价</div>
	      		<div class='wst-clear'></div>
	      	</div>
	      	<div class="p-shop"><a href="" class="wst-redlink"><?php echo $rvo['shopName']; ?></a></div>
	      </div>
	      <?php } ?>
	     <div class='wst-clear'></div>
	   	</div>
	</div>
	<?php endif; if(cookie("history_goods")!=''): ?>
	<div class="wst-gview">
		<div class="title">您最近浏览的商品</div>
		<div class="view-goods">
	       <?php $wstTagGoods =  model("common/Tags")->listGoods("history",-1,6,0); foreach($wstTagGoods as $key=>$vo){?>
			<div class="item">
				<div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>"><img class='goodsImg' data-original="__RESOURCE_PATH__/<?php echo WSTImg($vo['goodsImg']); ?>" alt="<?php echo WSTStripTags($vo['goodsName']); ?>" title="<?php echo WSTStripTags($vo['goodsName']); ?>"/></a></div>
				<div class="p-name"><a class="wst-hide wst-redlink" href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>"><?php echo $vo['goodsName']; ?></a></div>
				<div class="p-price"><?php if(($vo['saleType']==1)): ?>议价<?php elseif(($vo['saleType']==2)): ?>仅展示<?php else: ?>￥<?php echo $vo['shopPrice']; ?><?php endif; ?></div>
			</div>
	       <?php } ?>
	     	<div class='wst-clear'></div>
	   	</div>
	</div>
	<?php endif; ?>
</div>
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


<script type='text/javascript' src='__STYLE__/js/goodslist.js?v=<?php echo $v; ?>'></script>
<?php if(!empty($goodsPage["data"])): ?>
<script type='text/javascript'>
$(function(){
	<?php if(!isset($areaInfo)): ?>
	$('#fl_1_1').click();
	<?php endif; ?>
	contrastGoods(1,0,2);
})
laypage({
    cont: 'wst-pager',
    pages: <?php echo $goodsPage["last_page"]; ?>, //总页数
    skip: true, //是否开启跳页
    skin: '#e23e3d',
    groups: 3, //连续显示分页数
    curr: function(){ //通过url获取当前页，也可以同上（pages）方式获取
        var page = location.search.match(/page=(\d+)/);
        return page ? page[1] : 1;
    }(), 
    jump: function(e, first){ //触发分页后的回调
        if(!first){ //一定要加此判断，否则初始时会无限刷新
        	var nuewurl = WST.splitURL("page");
        	var ulist = nuewurl.split("?");
        	if(ulist.length>1){
        		location.href = nuewurl+'&page='+e.curr;
        	}else{
        		location.href = '?page='+e.curr;
        	}
            
        }
    }
});
</script>
<?php endif; ?>

</body>
</html>