<?php /*a:6:{s:95:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\goods_detail.html";i:1617806777;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\base.html";i:1618237658;s:86:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\top.html";i:1617513009;s:89:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\header.html";i:1617691381;s:93:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\right_cart.html";i:1617521580;s:89:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\footer.html";i:1617939615;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $goods['goodsName']; ?> - <?php echo WSTConf('CONF.mallName'); ?></title>

<meta name="description" content="<?php echo $goods['goodsSeoDesc']; ?>">
<meta name="Keywords" content="<?php echo $goods['goodsSeoKeywords']; ?>">

<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css" type="text/css" />

<link href="__STYLE__/css/goods.css?v=<?php echo $v; ?>" rel="stylesheet">

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



<div style="width:100%;"></div>
<style type="text/css">
    #preview ul li:nth-child(n+2) {display: none;}
</style>

<div class="wst-w-top">
<div class='wst-w' style='margin-bottom:0px'>
<div class='wst-filters'>
   <div class='item' style="padding-left: 8px">
      <a class='link'>当前位置：</a>
      <a class='link' href="<?php echo url('home/index/index'); ?>">首页</a>
      <i class="arrow"></i>
   </div>
   <?php $_result=WSTPathGoodsCat($goods['goodsCatId']);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
   <div class='wst-lfloat'>
    <div class='item dorpdown'>
     <div class='drop-down'>
        <a class='link' href='<?php echo Url("home/goods/lists",["cat"=>$vo["catId"]]); ?>'><?php echo $vo['catName']; ?></a>
     </div>
     <div class="dorp-down-layer">
         <?php $_result=WSTGoodsCats($vo['parentId']);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
         <div class="<?php echo $vo['parentId']>0 ? 'cat2' : 'cat1'; ?>"><a href='<?php echo Url("home/goods/lists","cat=".$vo2["catId"]); ?>'><?php echo $vo2['catName']; ?></a></div>
         <?php endforeach; endif; else: echo "" ;endif; ?>
     </div>
  </div>
  <i class="arrow"></i>
   </div>
   <?php endforeach; endif; else: echo "" ;endif; ?>
   <div class='wst-clear'></div>
</div>
</div>
<div class='wst-w'>
   <div class='wst-container' style=''>
      <div class='goods-img-box'>
          <?php if($goods['goodsVideo']!=""): ?>
            <div class="wst-video-box">
              <video muted src="__RESOURCE_PATH__/<?php echo $goods["goodsVideo"]; ?>" id='previewVideo' controls="controls" autoplay="autoplay" width='350' height='350'></video>
            </div>
          <?php endif; ?>
          <div class="goods-img spec-preview" id="preview">
            <!--<img title="<?php echo WSTStripTags($goods['goodsName']); ?>" alt="<?php echo WSTStripTags($goods['goodsName']); ?>" src="__RESOURCE_PATH__/<?php echo WSTImg($goods['goodsImg']); ?>"  class="cloudzoom" data-cloudzoom="zoomImage:'__RESOURCE_PATH__/<?php echo $goods['goodsImg']; ?>'" height="350" width="350">-->
              <ul >
              <?php if(is_array($goods['gallery']) || $goods['gallery'] instanceof \think\Collection || $goods['gallery'] instanceof \think\Paginator): $gi = 0; $__LIST__ = $goods['gallery'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($gi % 2 );++$gi;?>
              <li>
                  <img title="<?php echo WSTStripTags($goods['goodsName']); ?>"
                       alt="<?php echo WSTStripTags($goods['goodsName']); ?>"
                       class='cloudzoom'
                       src="__RESOURCE_PATH__/<?php echo WSTImg($vo); ?>"
                       layer-src="__RESOURCE_PATH__/<?php echo WSTImg($vo, 0); ?>"
                       width="350" height="350">
              </li>
              <?php endforeach; endif; else: echo "" ;endif; ?>
              </ul>
          </div>
          <div class="goods-pics">
            <a class="prev">&lt;</a>
            <a class="next">&gt;</a>
            <div class="items">
               <ul>
                <?php if($goods['goodsVideo']!=""): ?>
                 <li class="gallery-li hover">
                  <div class="wst-video-btn"><i class="fa fa-play-circle"></i></div>
                   <img class="gvideo gallery-img" src="__RESOURCE_PATH__/<?php echo $goods['goodsImg']; ?>"  width="60" height="60">
                 </li>
                <?php endif; ?>
                <div id="gallery-img-0">
                  <?php if(is_array($goods['gallery']) || $goods['gallery'] instanceof \think\Collection || $goods['gallery'] instanceof \think\Paginator): $gi = 0; $__LIST__ = $goods['gallery'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($gi % 2 );++$gi;?>
                      <li class="gallery-li <?php echo $goods['goodsVideo']=='' && $key==1 ? 'hover' :  ''; ?>">
                        <img title="<?php echo WSTStripTags($goods['goodsName']); ?>"
                              alt="<?php echo WSTStripTags($goods['goodsName']); ?>"
                              class='cloudzoom-gallery gallery-img'
                              src="__RESOURCE_PATH__/<?php echo WSTImg($vo); ?>"
                              layer-src="__RESOURCE_PATH__/<?php echo WSTImg($vo, 0); ?>"
                              data-cloudzoom="useZoom: '.cloudzoom', image:'__RESOURCE_PATH__/<?php echo WSTImg($vo); ?>', zoomImage:'__RESOURCE_PATH__/<?php echo $vo; ?>' " width="60" height="60">
                      </li>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>

         </ul>
      </div>
      <div class="wst-clear"></div>
         </div>
         
      </div>
      <div class='intro'>
          <div class='intro-name'>
          <h2><?php echo $goods['goodsName']; ?></h2> 
          </div>    
          <div class='summary'>
            <div class="infol">
             <div class='item'>
               <div class='dt dt_yj'>价格￥</div>
               <div class='dd price'><span id='j-shop-price'><?php if($goods['saleType']==0): ?><?php echo $goods['shopPrice']; ?><?php endif; ?></span></div>
               <s class='dt d_oj'>原价</s>
               <s class='dt dt_oj_p'><?php if($goods['saleType']==0): ?>￥<?php echo $goods['marketPrice']; ?><?php endif; ?></s>
               
             </div>
             <?php echo hook('homeDocumentGoodsPriceDetail',['goods'=>$goods,'getParams'=>input()]); ?>
             <div class='goods-intro-bg'>
               <div class='item'>
                 <ul class="ginfo_b">
                   <li>
                     <div class='dt'>销量</div>
                     <span class='appraise-num'><?php echo $goods['saleNum']; ?></span>
                   </li>
				   <li>
                     <div class='dt'>浏览数</div>
                     <span class='appraise-num'><?php echo $goods['visitNum']; ?></span>
                   </li>
                   <li>
                     <div class='dt'>累计评价</div>
                     <span class='appraise-num'><?php echo $goods['appraiseNum']; ?></span>
                   </li>
                   <li>
                     <div <?php if(0==$goods['haveThumb']): ?>class='dt dzs'<?php else: ?>class='dt dzs-red'<?php endif; ?> id='dzs'><?php echo $goods['thumbsNum']; ?></div>
                     <div class='dd'>
                        <input type="hidden" name="" value="<?php echo $goods['thumbsNum']; ?>" id="thumbsNum" autocomplete="off">
						 <div class="item" style="margin-left:44px">
							<a href="javascript:void(0)" onclick="WST.recordThumb(<?php echo $goods['goodsId']; ?>,<?php echo session('WST_USER.userId'); ?>+0,<?php echo $goods['thumbsNum']; ?>,<?php echo $goods['shop']['userId']; ?>)">
							<?php if(0==$goods['haveThumb']): ?>
								<div class='dz' id='dz'>点个赞</div>
							<?php else: ?>
								<div class='dz-red' id='dz'>已点赞</div>
							<?php endif; ?>
							</a>
						 </div>
                     </div>
                   </li>
                   <div class="wst-clear"></div>
                 </ul>
               </div>
             </div>
             </div>             
             <div class='wst-clear'></div>
          </div>
          
          <div class="sale_box">
            
             <div class='item' id='j-promotion' style='display:none'>
               <div class='dt'>促销</div>
               <div class='dd'>
                 <?php echo hook('homeDocumentGoodsPromotionDetail',['goods'=>$goods]); ?>
               </div>
             </div>
             <?php echo hook('homeDocumentGoodsPropDetail',['goods'=>$goods,'getParams'=>input()]); ?>
          </div>

          <div class='spec'>
             <?php if(!empty($goods['spec'])): if(is_array($goods['spec']) || $goods['spec'] instanceof \think\Collection || $goods['spec'] instanceof \think\Paginator): $i = 0; $__LIST__ = $goods['spec'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
             <div class='item'>
               <div class='dt'><?php echo $vo['name']; ?></div>
               <div class='dd'>
               <?php if(is_array($vo['list']) || $vo['list'] instanceof \think\Collection || $vo['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;if($vo2['itemImg']!=''): ?>
                  <div class='j-option img' data-val="<?php echo $vo2['itemId']; ?>" style='max-width:120px;min-width:80px;height:28px;padding:0px;position: relative;'><img class="cloudzoom-gallery" width="28" height="28" src="__RESOURCE_PATH__/<?php echo WSTImg($vo2['itemImg']); ?>" data-cloudzoom="useZoom: '.cloudzoom', image:'__RESOURCE_PATH__/<?php echo WSTImg($vo2['itemImg']); ?>', zoomImage:'__RESOURCE_PATH__/<?php echo $vo2['itemImg']; ?>' "  title="<?php echo WSTStripTags($vo2['itemName']); ?>" alt="<?php echo WSTStripTags($vo2['itemName']); ?>"/><span><?php echo $vo2['itemName']; ?></span><i></i></div>
                  <?php else: ?>
                  <div class='j-option' data-val="<?php echo $vo2['itemId']; ?>"><?php echo $vo2['itemName']; ?><i></i></div>
                  <?php endif; ?>
               <?php endforeach; endif; else: echo "" ;endif; ?>
               </div>
               <div class='wst-clear'></div>
             </div>
             <?php endforeach; endif; else: echo "" ;endif; ?>
             <?php endif; ?>
          </div>
          <div class='buy'>
             <div class='item'>
                <div class='dt'>数量</div>
                <div class='dd'>
                  <a href='#none' class='buy-btn' id='buy-reduce' style='' onclick='javascript:WST.changeIptNum(-1,"#buyNum","#buy-reduce,#buy-add")'>-</a>
                  <input type='text' id='buyNum' class='buy-num' value='1' data-min='1' autocomplete="off" onkeyup='WST.changeIptNum(0,"#buyNum","#buy-reduce,#buy-add")' onkeypress="return WST.isNumberKey(event);" maxlength="6"/>
                  <a href='#none' class='buy-btn' id='buy-add' onclick='javascript:WST.changeIptNum(1,"#buyNum","#buy-reduce,#buy-add")'>+</a>
                     （库存：<span id='goods-stock'>0</span>&nbsp;<?php echo $goods['goodsUnit']; ?>）
                </div>
             </div>
             <div class='item' style='padding-left:75px;margin-top:20px;'>
               <?php if($goods['read']): if($goods['goodsType']==0): if(($goods['saleType']==0)&&($goods['goodsStock']>0)): ?>
					<a id='addBtn' href='javascript:void(0);' class='addBtn un-buy' >加入购物车</a>
					<?php endif; ?>
                 <?php endif; if(($goods['saleType']==2)||($goods['goodsStock']<=0)): ?>
				 <a id='buyBtn' class='buyBtn un-buy'>仅做展示</a>
				 <?php elseif($goods['saleType']==1): ?>
				 <a id='buyBtn' class='buyBtn un-buy'>议价</a>
				 <?php elseif($goods['saleType']==0): if($goods['goodsType']==2): ?>
                 <a id='buyBtn' href='javascript:void(0);' class='buyBtn un-buy'>保底交易</a>
					<?php else: ?>
                 <a id='buyBtn' href='javascript:void(0);' class='buyBtn un-buy' style='background:#eee'>立即购买</a>
					<?php endif; ?>
				 <?php endif; else: if($goods['goodsType']==0): if(($goods['saleType']==0)&&($goods['goodsStock']>0)): ?>
					<a id='addBtn' href='javascript:addCart(0,"#buyNum")' class='addBtn' >加入购物车</a>
					<?php endif; ?>
                 <?php endif; if(($goods['saleType']==2)||($goods['goodsStock']<=0)): ?>
				 <a id='buyBtn' class='buyBtn'>仅做展示</a>
				 <?php elseif($goods['saleType']==1): ?>
				 <a id='buyBtn' class='buyBtn un-buy'>议价</a>
				 <?php elseif($goods['saleType']==0): if($goods['goodsType']==2): ?>
                 <a id='buyBtn' href='javascript:addCart(1,"#buyNum")' class='buyBtn'>保底交易</a>
					<?php else: ?>
                 <a id='buyBtn' href='javascript:addCart(1,"#buyNum")' class='buyBtn'>立即购买</a>
					<?php endif; ?>
				 <?php endif; ?>
               <?php endif; ?>
              <div class='wst-clear'></div>
            </div>
       
            <div class="wst-relative" style="margin-top: 20px;margin-left: 10px;">
              <?php echo hook('homeDocumentGoodsDetail',['goods'=>$goods,'getParams'=>input()]); ?>
              </div>
            </div>
            <div class="goods-term-box">
              <div class="wst-favorite">
                 <?php if(($goods['favGood']>0)): ?>
                   <a href='javascript:void(0);' onclick='WST.cancelFavorite(this,0,<?php echo $goods["goodsId"]; ?>,<?php echo $goods['favGood']; ?>)' class='favorite j-fav'>已关注</a>
                 <?php else: ?>
                   <a href='javascript:void(0);' onclick='WST.addFavorite(this,0,<?php echo $goods["goodsId"]; ?>,<?php echo $goods["goodsId"]; ?>)' class='favorite j-fav2 j-fav3'>关注商品</a>
                 <?php endif; ?>
                 </div>
             <a  href='javascript:informs(<?php echo $goods["goodsId"]; ?>)' class="j-inform">举报</a>
             <div class="wst-clear"></div>
           </div>
      </div>
      
      <div class='wst-clear'></div>
   </div>
</div>
</div>
<div class='wst-w'>
   <div class='wst-container'>
       <div class='wst-side'>
          <h2>店铺信息</h2>
          <div class='shop-intro'>
                <div class="shop_imgbox">
                  <img class="shopsImg" data-original="__RESOURCE_PATH__/<?php echo $shop['shopImg']; ?>" title="<?php echo WSTStripTags($shop['shopName']); ?>" src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" style="vertical-align: middle;width:100px;height:100px;border-radius: 100px;">
                </div>
                <div class='title shop-name'><?php echo $shop['shopName']; ?></div>
                <div class='title shop-coun'>共11件作品</div>
                <a href="#">艺术家完整资料 > </a>
           </div>
      </div>
    </div>
    <div class='goods-desc'>
        <div id='tab' class="wst-tab-box">
        <div class="goods-intro">
          <h2>商品信息</h2>
          <div class="goods-intro-detail">
            <ul>
              <li>作品名称：<?php echo $goods['goodsName']; ?></li>
              <li>艺 术 家：<?php echo $goods['goodsAuthor']; ?></li>
              <li>尺寸：<?php echo $goods['goodsTips']; ?></li>
              <li>创作时间：<?php echo $goods['createTime']; ?></li>
            </ul>
          </div>
          <h2>作者介绍</h2>
          <div class="author-intro">
            曹钟伟 男 汉族 职务：综合艺术教研室主任。研究领域：综合绘画。1971年10月出生于重庆。1994年毕业于四川美院师范系，童年留校任教至今；四川美术许愿美术教育系综合艺术教研室主任。
            <br/>
            <br/>
            个展
            <br/>
            2012 北京现实空间“白日梦的真实”
          </div>
          <h2>作品介绍</h2>
          <div class="goods-desc">
            <?php echo $goods['goodsDesc']; ?>
          </div>
        </div>
    </div>
    <div class='wst-clear'></div>
    <div class="goods-side">
      <div class="guess-like">
        <div class="guess-like">
        <div class="title">猜你喜欢</div>
        <?php $wstTagGoods =  model("common/Tags")->listGoods("best",$goods['goodsCatId'],3,0); foreach($wstTagGoods as $key=>$vo){?>
        <div class="item">
          <div class="img"><a target='_blank' href="<?php echo Url('home/goods/detail','goodsId='.$vo['goodsId']); ?>"><img title="<?php echo $vo['goodsName']; ?>" alt="<?php echo $vo['goodsName']; ?>" data-original="/<?php echo WSTImg($vo['goodsImg']); ?>" class="goodsImg" /></a></div>
          <div class="p-name"><a class="wst-hide wst-redlink"><?php echo $vo['goodsName']; ?></a></div>
          <div class="p-price"><?php if(($vo['saleType']==1)): ?>议价<?php elseif(($vo['saleType']==2)): ?>仅展示<?php else: ?>￥<?php echo $vo['shopPrice']; ?><?php endif; ?></div>
          <div class="p-name"><a class="wst-hide wst-redlink"></a><?php echo $vo['goodsAuthor']; ?></a></div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  </div>
  <div class='wst-clear'></div>
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


<script>
var goodsInfo = {
  id:<?php echo $goods['goodsId']; ?>, 
  isSpec:<?php echo $goods['isSpec']; ?>,
  goodsStock:<?php echo $goods['goodsStock']; ?>,
  marketPrice:"<?php echo $goods['marketPrice']; ?>",
  goodsPrice:"<?php echo $goods['shopPrice']; ?>"
  <?php if(isset($goods['saleSpec'])): ?>
  ,sku:<?php echo json_encode($goods['saleSpec']); ?>
  <?php endif; ?>
}



</script>
<script type='text/javascript' src='__STYLE__/js/cloudzoom.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__STYLE__/js/goods.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__STYLE__/js/qrcode.js?v=<?php echo $v; ?>'></script>
<script>
$(function(){
  layer.photos({
        photos: '#gallery-img-0',
    });

  layer.photos({
        photos: '#preview',
    });


  var qr = qrcode(8, 'H');
  var url = '<?php echo url("wechat/goods/detail","","html",true); ?>?goodsId=<?php echo $goods["goodsId"]; ?>';
  qr.addData(url);
  qr.make();
  $('.qrcode').html(qr.createImgTag());

  
});
function goShop(id){
  location.href=WST.U('home/shops/index','shopId='+id);
}
</script>

</body>
</html>