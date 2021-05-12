<?php /*a:4:{s:102:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\users\messages\list.html";i:1602924314;s:93:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\users\base.html";i:1602924312;s:86:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\top.html";i:1617513009;s:89:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\home\view\default\footer.html";i:1617939615;}*/ ?>
<!doctype html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>首页-买家中心</title>
<link rel="stylesheet" href="/static/plugins/layui/css/layui.css"/>
<link href="__STYLE__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__STYLE__/css/user.css?v=<?php echo $v; ?>" rel="stylesheet">


<script type="text/javascript" src="/static/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>	  
<script type='text/javascript' src='/static/js/common.js?v=<?php echo $v; ?>'></script>

<script type='text/javascript' src='__STYLE__/js/common.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"","APP":"","STATIC":"/static", "SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","PHONE_VERFY":"<?php echo WSTConf('CONF.phoneVerfy'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","TIME_TASK":"1","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","HTTP":"<?php echo WSTProtocol(); ?>",'RESOURCE_PATH':'<?php echo WSTConf('CONF.resourcePath'); ?>'}
$(function() {
	WST.initUserCenter();
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



<div class='wst-lite-bac'>
<div class='wst-lite-container'>
   <div class='wst-logo'><a href='<?php echo app('request')->root(true); ?>'><img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.mallLogo'); ?>" style="max-height:120px;max-width:240px"></a></div>
   <div class="wst-lite-cart">
   	<a href="<?php echo url('home/carts/index'); ?>" target="_blank" onclick="WST.currentUrl('<?php echo url("home/carts/index"); ?>');"><span class="word j-word">我的购物车<span class="num" id="goodsTotalNum" style='display:none'>0</span></span></a>
   	<div class="wst-lite-carts hide">
   		<div id="list-carts"></div>
   		<div id="list-carts2"></div>
   		<div id="list-carts3"></div>
	   	<div class="wst-clear"></div>
   	</div>
   </div>
<script id="list-cart" type="text/html">
{{# for(var i = 0; i < d.list.length; i++){ }}
	<div class="goods" id="j-goods{{ d.list[i].cartId }}">
	   	<div class="imgs"><a href="{{ WST.U('home/goods/detail','goodsId='+d.list[i].goodsId) }}"><img class="goodsImgc" data-original="__RESOURCE_PATH__/{{ d.list[i].goodsImg }}" title="{{ d.list[i].goodsName }}"></a></div>
	   	<div class="number"><p><a  href="{{ WST.U('home/goods/detail','goodsId='+d.list[i].goodsId) }}">{{WST.cutStr(d.list[i].goodsName,26)}}</a></p><p>数量：{{ d.list[i].cartNum }}</p></div>
	   	<div class="price"><p>￥{{ d.list[i].shopPrice }}</p><span><a href="javascript:WST.delCheckCart({{ d.list[i].cartId }})">删除</a></span></div>
	</div>
{{# } }}
</script>
   <div class="wst-search-box">
	  <div class="j-search-type" style="margin-top:26px;color:#bdbdbd">
	  <ul>
		  <li class="j-search-goods" style="float:left;margin-right:10px;margin-left:5px">
		  <span data="0" id="j-search-artwork" <?php if(isset($keytype)&&($keytype==0)): ?> class="j-search-select" <?php else: ?>class="j-search-unselect"<?php endif; ?>>艺术品</span>
		  <span data="1" id="j-search-artist" <?php if(isset($keytype)&&($keytype==1)): ?> class="j-search-select" <?php else: ?>class="j-search-unselect"<?php endif; ?>>艺术家·艺廊</span>
		  <span data="2" id="j-search-author" <?php if(isset($keytype)&&($keytype==2)): ?> class="j-search-select" <?php else: ?>class="j-search-unselect"<?php endif; ?>>艺术家</span>
		</li>
	  </ul>
	  </div>
      <div class='wst-search'>
      	  <input type="hidden" id="search-type" value="<?php echo isset($keytype)?$keytype:'0'; ?>"/>
	      <input type="text" id='search-ipt' class='search-ipt' placeholder='<?php echo WSTConf("CONF.adsGoodsWordsSearch"); ?>' value='<?php echo isset($keyword)?$keyword:""; ?>'/>
	      <input type='hidden' id='adsGoodsWordsSearch' value='<?php echo WSTConf("CONF.adsGoodsWordsSearch"); ?>'>
	      <input type='hidden' id='adsShopWordsSearch' value='<?php echo WSTConf("CONF.adsShopWordsSearch"); ?>'>
	      <input type='hidden' id='adsAuthorWordsSearch' value='<?php echo WSTConf("CONF.adsAuthorWordsSearch"); ?>'>
      </div>
	  <div id='search-btn' class="wst-search-btn" onclick='javascript:WST.search(this.value)'>搜索</div>
   </div>
   <div class="wst-clear"></div>
</div>
<div class="wst-clear"></div>
</div>

<div class="wst-wrap">
          <div class='wst-header' style='border-bottom: 1px solid #ffffff;'>
			<div class="wst-shop-nav">
				<div class="wst-nav-box">
					<?php $homeMenus = WSTHomeMenus(0); if(is_array($homeMenus['menus']) || $homeMenus['menus'] instanceof \think\Collection || $homeMenus['menus'] instanceof \think\Paginator): $i = 0; $__LIST__ = $homeMenus['menus'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
						<a href="/<?php echo $vo['menuUrl']; ?>?homeMenuId=<?php echo $vo['menuId']; ?>"><li class="liselect wst-lfloat <?php if(($vo['menuId'] == $homeMenus['menuId1'])): ?>wst-nav-boxa<?php endif; ?>"><?php echo $vo['menuName']; ?></li></a>
					<?php endforeach; endif; else: echo "" ;endif; ?>
					<div class="wst-clear"></div>
				</div>
			</div>
			<div class="wst-clear"></div>
		</div>
          <div class='wst-nav'></div>
          <div class='wst-main'>
            <div class='wst-menu'>
              <?php if(isset($homeMenus['menus'][$homeMenus['menuId1']]['list'])): if(is_array($homeMenus['menus'][$homeMenus['menuId1']]['list']) || $homeMenus['menus'][$homeMenus['menuId1']]['list'] instanceof \think\Collection || $homeMenus['menus'][$homeMenus['menuId1']]['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $homeMenus['menus'][$homeMenus['menuId1']]['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menus): $mod = ($i % 2 );++$i;?>
              	<span class='wst-menu-title'><?php echo $menus['menuName']; ?><img src="__STYLE__/img/user_icon_sider_zhankai.png"></span>
              	<ul>
                <?php if(isset($menus['list'])): if(is_array($menus['list']) || $menus['list'] instanceof \think\Collection || $menus['list'] instanceof \think\Paginator): $k = 0; $__LIST__ = $menus['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($k % 2 );++$k;?>
                  	<li class="<?php if(($homeMenus['menuId3']==$menu['menuId'])): ?>wst-menua<?php endif; ?> wst-menuas" onclick="getMenus('<?php echo $menu['menuId']; ?>','<?php echo $menu['menuUrl']; ?>')">
                  	<?php echo $menu['menuName']; ?>
                  	<span id="mId_<?php echo $menu['menuId']; ?>"></span>
                  	</li>
                	<?php endforeach; endif; else: echo "" ;endif; ?>
                <?php endif; ?>
              	</ul>
              	<?php endforeach; endif; else: echo "" ;endif; ?>
              <?php endif; ?>
              
            </div>
            <div class='wst-content'>
            
<div class="wst-user-head"><span>用户信息</span></div>
<div class="wst-clear"></div>
<div class="u-menu">
   <a href="javascript:void(0)" onclick="batchRead()">标记为已读</a> |
   <a href='javascript:void(0);' onclick="batchDel()" >删除</a>
</div>

  <div class="wst-body"> 
       <div class='wst-page-content'>        
        <table class='wst-list'>
           <thead>
             <tr>
               <th width='25'>
                <div class="checkbox-box-s">
                   <input class="wst-checkbox-s" onclick="javascript:WST.checkChks(this,'.chk')" type='checkbox' id="all"/>
                   <label for="all"></label>
                 </div>
              </th>
               <th width='45'>状态</th>
               <th>消息</th>
               <th width='130'>时间</th>
               <th width='100' style="border-right: 1px solid #f1f1f1;">操作</th>
             </tr>
           </thead>
           <script id="msg" type="text/html">
            {{# for(var i = 0, len = d.length; i < len; i++){ }}
             <tr>
               <td>
                  <div class="checkbox-box-s">
                    <input type='checkbox'  class='chk wst-checkbox-s' id="chk-{{i}}" value='{{ d[i].id }}' /><label class='mt-1' for="chk-{{i}}"></label>
                  </div>
               </td>
               <td>

               {{# if(d[i].msgStatus ==1) { }}
                <div class='readMsg'></div>
               {{# }else{ }}
                <div class='newMsg'></div>
               {{# } }}
               </td>
               <td><div class="wst-hide msg-content">{{ d[i].msgContent }}</div></td>
               <td>{{ d[i].createTime }}</td>
               <td style="border-right: 1px solid #f1f1f1;">
               <a class="s-handle" href="javascript:showMsg({{ d[i].id }})">[查看]</a>
               <a class="s-handle" href="javascript:delMsg(this,{{ d[i].id }})">[删除]</a>
               &nbsp;
               </td>
             </tr>
            {{# } }}
            </script>
           <tbody id="msg_box">

           	



             <tfoot>
             <tr>
                <td colspan='12' align='center'>

                <div id="wst-page" class='wst-page' style="padding-bottom:10px;">
                </div>

                </td>
             </tr>
             </tfoot>
           </tbody>
        </table>
        </div>
    </div>

            </div>
          </div>
          <div style='clear:both;'></div>
          <br/>
</div>

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


<script type='text/javascript' src='__STYLE__/users/messages/message.js?v=<?php echo $v; ?>'></script>
<script>
$(function(){
  queryByList(<?php echo $p; ?>);
});
</script>

<script>
function getMenus(menuId,menuUrl){
    $.post(WST.U('home/index/getMenuSession'), {menuId:menuId}, function(data){
    	location.href=WST.U(menuUrl);
    });
}
</script>
</body>
</html>