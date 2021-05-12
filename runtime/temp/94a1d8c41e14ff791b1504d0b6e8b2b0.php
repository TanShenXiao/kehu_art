<?php /*a:3:{s:71:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/goods_detail.html";i:1618325076;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/dialog.html";i:1579267090;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<meta name="description" content="<?php echo $info['goodsSeoDesc']; ?>">
<meta name="Keywords" content="<?php echo $info['goodsSeoKeywords']; ?>">

<title><?php echo $info['goodsName']; ?> - 商品详情 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="/static/plugins/swiper/swiper.min.css"/>
<link rel="stylesheet"  href="__MOBILE__/css/goods_detail.css?v=<?php echo $v; ?>">
<link rel="stylesheet"  href="__MOBILE__/js/share/nativeShare.css?v=<?php echo $v; ?>">
<link rel="stylesheet" href="/static/plugins/photoswipe/photoswipe.css">
<link rel="stylesheet" href="/static/plugins/photoswipe/default-skin/default-skin.css">

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

	<?php $cartNum = WSTCartNum(); ?>
	



<div id="gallery" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
        <div class="pswp__container">
        <div class="pswp__item"></div>
        <div class="pswp__item"></div>
        <div class="pswp__item"></div>
        </div>
        <div class="pswp__ui pswp__ui--hidden">
        <div class="pswp__top-bar">
            <div class="pswp__counter"></div>
            <button class="pswp__button pswp__button--close" title="关闭"></button>
            <button class="pswp__button pswp__button--fs" title="全屏"></button>
            <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
            <div class="pswp__preloader">
                <div class="pswp__preloader__icn">
                    <div class="pswp__preloader__cut">
                    <div class="pswp__preloader__donut"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
            <div class="pswp__share-tooltip">
            </div>
        </div>
        <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
        <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
        <div class="pswp__caption">
            <div class="pswp__caption__center">
            </div>
        </div>
        </div>
    </div>
</div>

	 
	 <div class="wst-go-more" id="arrow" style="display: none;"><i class="arrow"></i>
	 	<ul class="ui-row ui-list-active more">
		    <li class="ui-col"><div class="column line"><a href="<?php echo url('mobile/index/index'); ?>"><i class="home"></i><p>首页</p></a></div></li>
		    <li class="ui-col"><div class="column line"><a href="<?php echo url('mobile/goodscats/index'); ?>"><i class="category"></i><p>分类</p></a></div></li>
		    <li class="ui-col"><div class="column line"><a href="<?php echo url('mobile/carts/index'); ?>"><i class="cart"></i><p>购物车</p></a></div></li>
		    <li class="ui-col"><div class="column line"><a href="<?php echo url('mobile/favorites/goods'); ?>"><i class="follow"></i><p>关注</p></a></div></li>
		    <li class="ui-col"><div class="column"><a href="<?php echo url('mobile/users/index'); ?>"><i class="user"></i><p>我的</p></a></div></li>
	 	</ul>
	 </div>
	 <div class="wst-ca-layer" id="layer" onclick="javascript:inMore();"></div>
     <section class="ui-container" id="goods1" style="border-top: 0px solid transparent;">
		<div class="swiper-container">
          <div class="swiper-wrapper">
          		<?php if($info['goodsVideo']!=""): ?>
          		<div class="swiper-slide" style="width:100%;">
			        <div class="wst-video-box">
			          <video muted src="__RESOURCE_PATH__/<?php echo $info["goodsVideo"]; ?>" id='previewVideo' controls="controls" autoplay="autoplay" style="width:3.75rem;height:3.75rem;"></video>
			        </div>
			    </div>
				<?php endif; if(is_array($info['gallery']) || $info['gallery'] instanceof \think\Collection || $info['gallery'] instanceof \think\Paginator): $i = 0; $__LIST__ = $info['gallery'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ga): $mod = ($i % 2 );++$i;$_i = $i-1; ?>
                <div onclick="gViewImg(<?php echo $_i; ?>)" class="swiper-slide" style="width:100%;">
                	<div class="wst-go-img"><a><img src="__RESOURCE_PATH__/<?php echo WSTImg($ga,2); ?>"></a></div>
                </div>
                <?php endforeach; endif; else: echo "" ;endif; ?>
          </div>
   		  <?php if((count($info['gallery'])>1)): ?><div class="swiper-pagination"></div><?php endif; ?>
   		  <div class="wst-go-return" onclick="history.back()"><i class="ui-icon-prev"></i></div>
   		  <div class="wst-go-mores" onclick="javascript:inMore()"><i>···</i></div>
        </div>
	    <div class="wst-go-name"><?php echo $info['goodsName']; ?></div>
		<div class="ui-row-flex wst-go-price">
		    <div class="ui-col ui-col-2">
		    	<div class="price"><?php if(($info['saleType']==1)): ?>议价<?php elseif(($info['saleType']==2)): ?>仅展示<?php else: ?><i>价格 ¥ </i><?php echo $info['shopPrice']; ?><?php endif; ?></div>
				<div style="float:right;font-size:0.15rem">
					 <div <?php if(0==$info['haveThumb']): ?>class='dt dzs'<?php else: ?>class='dt dzs-red'<?php endif; ?> id='dzs'><?php echo $info['thumbsNum']; ?></div>
						<div class='dd'>
						<input type="hidden" name="" value="<?php echo $info['thumbsNum']; ?>" id="thumbsNum" autocomplete="off">
						 <div class="item" style="margin-left:44px">
							<a href="javascript:void(0)" onclick="WST.recordThumb(<?php echo $info['goodsId']; ?>,<?php echo session('WST_USER.userId'); ?>+0,<?php echo $info['thumbsNum']; ?>,<?php echo $info['shop']['userId']; ?>)">
							<?php if(0==$info['haveThumb']): ?>
								<div class='dz' id='dz'>点个赞</div>
							<?php else: ?>
								<div class='dz-red' id='dz'>已点赞</div>
							<?php endif; ?>
							</a>
						 </div>
                     </div>
                </div>
		    	<?php echo hook('mobileDocumentGoodsDetailTips',["goods"=>$info]); ?>
		    	<div class="ui-row-flex info">
					<div class="ui-col ui-col" style="text-align: left;">浏览数: <?php echo $info['visitNum']; ?></div>
				    <div class="ui-col ui-col" style="text-align: left;">销量: <?php echo $info['saleNum']; ?></div>
				    <div class="ui-col ui-col" style="text-align: left;">快递: <?php if($info['isFreeShipping']==1): ?>免运费<?php else: ?><?php echo sprintf("￥%.2f", $info['shop']['freight']); ?><?php endif; ?></div>
					<div class="ui-col ui-col" style="text-align: left;"></div>
				</div>
		    </div>
		</div>
		<ul class="ui-list ui-list-one ui-list-link wst-go-shop">
		    <div class="info">
		    	<div class="img"><a><img src="__RESOURCE_PATH__/<?php echo WSTImg($info['shop']['shopImg'],3); ?>" title="<?php echo WSTStripTags($info['shop']['shopName']); ?>"></a></div>
				<div class="ui-row-flex button">
					<div class="ui-col ui-col"><a href="<?php echo url('mobile/shops/home',['shopId'=>$info['shopId']]); ?>" class="shop">进入店铺</a></div>
				</div>
		    	<div class="name"><p class="ui-nowrap-flex name1"><?php echo $info['shop']['shopName']; ?></p><p class="ui-nowrap-flex name2">共<?php echo $info['shop']['count']; ?>件作品</p><p class="ui-nowrap-flex name2"><span>主营: <?php echo $info['shop']['cat']; ?></span></p></div>
		    	<div class="wst-clear"></div>
		    </div>
		</ul>
		
		<div class="wst_params">
			<h2 class="_title">参数</h2>
			<ul>
				<li>作品名称：<?php echo $info['goodsName']; ?></li>
				<li>作品类型：<?php echo $info['catName']; ?></li>
				<li>艺术家：<?php echo $info['goodsAuthor']; ?></li>
				<li>创作时间：<?php echo date('Y-m-d',!is_numeric($info['createTime'])? strtotime($info['createTime']) : $info['createTime']); ?></li>
				<li>尺寸：<?php echo $info['goodsTips']; ?></li>
			</ul>
		</div>
		<div class="author_intro">
			<h2 class="_title">作者介绍</h2>
			<div>
				<?php echo $info['shop']['shopDesc']; ?>
			</div>
		</div>
		
		<section class="_container" id="goods2">
			<h2 class="_title">作品介绍</h2>
			<div class="wst-go-details"><?php echo $info['goodsDesc']; ?></div>
		</section>
		<div class="wst-shl-ads">
	     	<div class="_title">猜你喜欢</div>
	     	<?php $wstTagGoods =  model("common/Tags")->listGoods("best",$info['shop']['catId'],4,0); foreach($wstTagGoods as $key=>$vo){?>
	     	<div class="wst-go-goods" onclick="javascript:WST.intoGoods(<?php echo $vo['goodsId']; ?>);">
	     		<div class="img j-imgAdapt">
	     			<a href="javascript:void(0);" onclick="javascript:WST.intoGoods(<?php echo $vo['goodsId']; ?>);"><img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__RESOURCE_PATH__/<?php echo WSTImg($vo['goodsImg'],3); ?>" title="<?php echo WSTStripTags($vo['goodsName']); ?>"></a>
	     		</div>
	     		<p class="name ui-nowrap-multi _name"><?php echo $vo['goodsName']; ?></p>
	     		<div class="info"><?php echo $vo['goodsAuthor']; ?> &nbsp;&nbsp;&nbsp;价格 ¥ <span class="ui-nowrap-flex price lprice"><?php if(($vo['saleType']==1)): ?>议价<?php elseif(($vo['saleType']==2)): ?>仅展示<?php else: ?><?php echo $vo['shopPrice']; ?><?php endif; ?></span></div>
	     	</div>
	     	<?php } ?>
		    <div class="wst-clear"></div> 
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



    <div class="ui-loading-wrap wst-Load" id="Load">
	    <i class="ui-loading"></i>
	</div>
	<input type="hidden" name="" value="<?php echo $info['goodsId']; ?>" id="goodsId" autocomplete="off">
	<input type="hidden" name="" value="<?php echo $info['goodsType']; ?>" id="goodsType" autocomplete="off">
    <footer class="ui-footer wst-footer-btns" style="height:42px;" id="footer">
        <div class="wst-toTop" id="toTop">
	  	<i class="wst-toTopimg"></i>
		</div>
		<div class="ui-row-flex">
		<div class="ui-col ui-col-3 wst-go-icon">
			<div class="ui-row-flex">
			    <div class="ui-col ui-col" style="border-right: 1px solid rgba(0,0,0,.05);">
					<div class="icon">
					<?php if(($info['shop']['shopQQ'])!=''): ?>
							<a class="J_service" href="<?php echo WSTProtocol(); ?>wpa.qq.com/msgrd?v=3&uin=<?php echo $info['shop']['shopQQ']; ?>&site=qq&menu=yes">
								<span class="img qq"></span><span class="word">客服</span>
							</a>
					<?php else: ?>
							<a class="J_service" href="tel:<?php echo $info['shop']['shopTel']; ?>">
								<span class="img tel"></span><span class="word">客服</span>
							</a>
					<?php endif; ?>
					<?php echo hook('mobileDocumentContact',['type'=>'goodsDetail','shopId'=>$info['shop']['shopId'],'goodsId'=>$info['goodsId']]); ?>
					</div>
			    </div>
			    <div class="ui-col ui-col" style="border-right: 1px solid rgba(0,0,0,.05);">
			    	<div class="icon"><a href="<?php echo url('mobile/shops/index',['shopId'=>$info['shop']['shopId']]); ?>"><span class="img shop"></span><span class="word">店铺</span></a></div>
			    </div>
			    <div class="ui-col ui-col">
			    <?php if(($info['favGood']==0)): ?>
		    	<button class="but" type="button"><span class="img imgfollow nofollow" onclick="javascript:WST.favorites(<?php echo $info['goodsId']; ?>,0);"></span><span style="bottom: 5px;" class="word">关注</span></button>
				<?php else: ?>
		    	<button class="but" type="button"><span class="img imgfollow follow" onclick="javascript:WST.cancelFavorite(<?php echo $info['favGood']; ?>,0);"></span><span style="bottom: 5px;" class="word">关注</span></button>
				<?php endif; ?>
			    </div>
			</div>
		</div>
	    <div class="ui-col ui-col-4 wst-goods_buy">
 			<?php if(($info['goodsStock']<=0)): ?>
				<button class="wst-goods_buym" <?php if(($info['goodsId']==0)): ?>disabled<?php endif; ?>>仅展示</button>
 			<?php elseif(($info['goodsType']==1)): ?>
				<button class="wst-goods_buym" type="button" onclick="javascript:cartShow(1);" <?php if(($info['goodsId']==0)): ?>disabled<?php endif; ?>>立即购买</button>
			<?php elseif(($info['goodsType']==2)): ?>
				<button class="wst-goods_buym" type="button" onclick="javascript:cartShow(1);" <?php if(($info['goodsId']==0)): ?>disabled<?php endif; ?>>保底交易</button>
			<?php else: if(($info['saleType']==1)): ?>
					<button class="wst-goods_buyr" <?php if(($info['goodsId']==0)): ?>disabled<?php endif; ?>>议价</button>
				<?php elseif(($info['saleType']==2)): ?>
					<button class="wst-goods_buyr" <?php if(($info['goodsId']==0)): ?>disabled<?php endif; ?>>仅展示</button>
				<?php else: ?>
					<button class="wst-goods_buyl" type="button" onclick="javascript:cartShow(0);" <?php if(($info['goodsId']==0)): ?>disabled<?php endif; ?>>加入购物车</button>
					<button class="wst-goods_buyr" type="button" onclick="javascript:cartShow(1);" <?php if(($info['goodsId']==0)): ?>disabled<?php endif; ?>>立即购买</button>
				<?php endif; ?>
			<?php endif; ?>
	    </div>
	    </div>
    </footer>



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

<div class="wst-cover" id="cover"></div>

<?php echo hook('mobileDocumentGoodsDetail',['goods'=>$info,'getParams'=>input()]); ?>


<div class="wst-cart-box" id="frame-cart">
	<div class="title">
     	<div class="picture"><div class="img"><a href="javascript:void(0);"><img src="__RESOURCE_PATH__/<?php echo WSTImg($info['goodsImg'],3); ?>" title="<?php echo WSTStripTags($info['goodsName']); ?>" id="specImage"></a></div></div>
		<i class="ui-icon-close-page" onclick="javascript:cartHide();"></i>
		<p class="ui-nowrap-multi"><?php echo $info['goodsName']; ?></p>
		<p class="ui-nowrap-flex price"><span id="j-shop-price">¥<?php echo $info['shopPrice']; ?></span><span id="j-market-price" class="price2">¥<?php echo $info['marketPrice']; ?></span></p>
		<div class="wst-clear"></div>
		<?php echo hook('mobileDocumentGoodsBoxDetailTips',["goods"=>$info]); ?>
	</div>
	<div class="standard" id="standard">
	<?php if(!empty($info['spec'])): if(is_array($info['spec']) || $info['spec'] instanceof \think\Collection || $info['spec'] instanceof \think\Paginator): $i = 0; $__LIST__ = $info['spec'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sp): $mod = ($i % 2 );++$i;?>
	<div class="spec">
		<p><?php echo $sp['name']; ?></p>
		<?php if(is_array($sp['list']) || $sp['list'] instanceof \think\Collection || $sp['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $sp['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sp2): $mod = ($i % 2 );++$i;if($sp2['itemImg']!=''): ?>
			<span style="line-height: 44px;" class="j-option" data-val="<?php echo $sp2['itemId']; ?>" data-image="__RESOURCE_PATH__/<?php echo WSTImg($sp2['itemImg'],3); ?>"><img class="img" data-image="__RESOURCE_PATH__/<?php echo WSTImg($sp2['itemImg'],3); ?>" src="__RESOURCE_PATH__/<?php echo WSTImg($sp2['itemImg'],3); ?>" title="<?php echo WSTStripTags($sp2['itemName']); ?>"><?php echo $sp2['itemName']; ?></span>
		<?php else: ?>
			<span class="j-option" data-val="<?php echo $sp2['itemId']; ?>"><?php echo $sp2['itemName']; ?></span>
		<?php endif; ?>
		<?php endforeach; endif; else: echo "" ;endif; ?>
		<div class="wst-clear"></div>
	</div>
	<?php endforeach; endif; else: echo "" ;endif; ?>
	<?php endif; ?>
	<div class="number">
		<p>数量</p>
		<div class="stock">库存：<span id="goods-stock">0</span><?php echo $info['goodsUnit']; ?></div>
	  	<div class="wst-buy_l">
           <input class="wst-buy_l1" type="button" value="-" onclick='javascript:WST.changeIptNum(-1,"#buyNum")'><input id="buyNum" class="wst-buy_l2" data-min='1' data-max='' type="number" value="1" autocomplete="off" onkeyup='WST.changeIptNum(0,"#buyNum")'><input class="wst-buy_l3" type="button" value="+" onclick='javascript:WST.changeIptNum(1,"#buyNum")'>
      	</div>
		<div class="wst-clear"></div>
	</div>
	</div>
	<div class="determine"><button class="button" onclick="javascript:addCart();">确定</button></div>
</div>


<script>
<?php 
	$gallery = implode(',',$info['gallery']);
 ?>

var goodsInfo = {
	id:<?php echo $info['goodsId']; ?>,	
	isSpec:<?php echo $info['isSpec']; ?>,
	goodsStock:<?php echo $info['goodsStock']; ?>,
	marketPrice:<?php echo $info['marketPrice']; ?>,
	goodsPrice:<?php echo $info['shopPrice']; if(isset($info['saleSpec'])): ?>
	,sku:<?php echo json_encode($info['saleSpec']); ?>
	<?php endif; ?>
	,gallery:"<?php echo $gallery; ?>",
}

$(function(){
	if(goodsInfo.gallery!=''){
		goodsInfo.gallery = goodsInfo.gallery.split(',').map(function(imgUrl,i){
			imgUrl = WST.conf.RESOURCE_PATH+"/"+imgUrl;
			var _obj = { src:imgUrl, w:0, h:0 };
			return _obj;
		})
	}
})

//弹框
function shareShow(){
	jQuery('#cover').attr("onclick","javascript:shareHide();").show();
	jQuery('#frame-share').animate({"bottom": 0}, 500);
}
function shareHide(){
	var cartHeight = parseInt($("#frame-cart").css('height'))+52+'px';
	jQuery('#frame-share').animate({'bottom': '-'+cartHeight}, 500);
	jQuery('#cover').hide();
}
function goConsult(){
	location.href=WST.U('mobile/goodsconsult/index',{goodsId:goodsInfo.id})
}
/************ 兼容safari *****************/
function isTouchDevice(){
    try{
        document.createEvent("TouchEvent");
        return true;
    }catch(e){
        return false;
    }
}
function touchScroll(id){
    if(isTouchDevice()){
        var el=document.getElementById(id);
        var scrollStartPos=0;

        document.getElementById(id).addEventListener("touchstart", function(event) {
            scrollStartPos=this.scrollTop+event.touches[0].pageY;
            // event.preventDefault();
        },false);

        document.getElementById(id).addEventListener("touchmove", function(event) {
            this.scrollTop=scrollStartPos-event.touches[0].pageY;
            // event.preventDefault();
        },false);
    }
}
touchScroll("standard");
</script>
<script type='text/javascript' src='/static/plugins/swiper/swiper.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/goods_detail.js?v=<?php echo $v; ?>'></script>
<script src="/static/plugins/photoswipe/photoswipe.js"></script>
<script src="/static/plugins/photoswipe/photoswipe-ui-default.min.js"></script>

</body>
</html>