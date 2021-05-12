<?php /*a:3:{s:68:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/news_list.html";i:1579267123;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/footer.html";i:1618386322;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<title>商城快讯 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/articles.css?v=<?php echo $v; ?>">

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

	<div id="info_list" style="margin-top: 50px;">
    <header style="background:#ffffff;" class="ui-header ui-header-positive ui-border-b wst-header">
        <a href='<?php echo url("mobile/index/index"); ?>'><i class="ui-icon-return"></i></a><h1>商城快讯</h1>
    </header>


<input type="hidden" name="" value="" id="currPage" autocomplete="off">
<input type="hidden" name="" value="" id="totalPage" autocomplete="off">
<input type="hidden" name="" value="<?php echo $catId; ?>" id="catId" autocomplete="off">
    <section class="ui-container" id="shopBox">
      <div class="ui-tab">
          <ul class="ui-tab-nav order-tab">
            <?php if(is_array($catInfo) || $catInfo instanceof \think\Collection || $catInfo instanceof \think\Paginator): $i = 0; $__LIST__ = $catInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <li class="tab-item <?php if($catId==$vo['catId']): ?>tab-curr<?php endif; ?>" catId="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></li>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </ul>
    </div>
        <section  id="newsListBox" >
        </section>  
    <div style="height:50px;"></div>
    </section>
    <script id="newsList" type="text/html">
	{{# var imgSuffix = "<?php echo WSTConf('CONF.wstMobileImgSuffix'); ?>";}}
    {{# for(var i=0;i<d.length;i++){ }}
        {{# if(d[i].TypeStatus==1){ }}
             <div class="news-item wst-model" onclick="news({{d[i].articleId}})" >
              <div class="ui-row-flex">
                  <div class="ui-col">
                    <div class="img j-imgAdapt wst-bor-mix-img" >
                      <a href="javascript:void(0);" >
                          {{# if(d[i].coverImg) { }}
                         <img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__RESOURCE_PATH__/{{WST.replaceImg(d[i].coverImg,imgSuffix)}}">
                         {{# } else { }}
                         <img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" >
                         {{#   } }}
                      </a>
                    </div>
                  </div>
                  <div class="ui-col ui-col-3" >
                    <div class="ui-row-flex ui-row-flex-ver wst-info" >
                        <div class="ui-nowrap-multi" style="-webkit-line-clamp: 1;">{{d[i].articleTitle}}</div>
                        <div class="ui-nowrap-multi wst-mix-cont" style="-webkit-line-clamp: 3;">{{d[i].articleContent}}</div>
                    </div>
                  </div>
                </div>
                <div class="ui-row-flex ui-whitespace wst-model wst-mix-info ">
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-start">• 已有{{d[i].visitorNum}}人浏览</div>
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-end wst-right-align">• {{d[i].createTime}}</div>
                </div>
              </div>
        {{# } }}

        {{# if(d[i].TypeStatus==2){ }}
             <div class="news-item wst-model" onclick="news({{d[i].articleId}})">
              <div class="ui-row-flex">
               <div class="ui-col ui-col-3">
                 <div class="ui-row-flex ui-row-flex-ver wst-info" >
                     <div class="ui-nowrap-multi" style="-webkit-line-clamp: 1;">{{d[i].articleTitle}}{{d[i].TypeStatus}}</div>
                     <div class="ui-nowrap-multi wst-mix-cont" style="-webkit-line-clamp: 3;">{{d[i].articleContent}}</div>
                 </div>
               </div>
               <div class="ui-col">
                <div class="img j-imgAdapt wst-bor-mix-img">
                  <a href="javascript:void(0);" >
                     {{# if(d[i].coverImg) { }}
                     <img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__RESOURCE_PATH__/{{WST.replaceImg(d[i].coverImg,imgSuffix)}}">
                     {{# } else { }}
                     <img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" >
                     {{#   } }}
                  </a>
                </div>
               </div>
              </div>
              <div class="ui-row-flex ui-whitespace wst-model wst-mix-info ">
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-start">• 已有{{d[i].visitorNum}}人浏览</div>
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-end wst-right-align">• {{d[i].createTime}}</div>
              </div>
            </div>
        {{# } }}


        {{# if(d[i].TypeStatus==3){ }}
             <div class="ui-row-flex ui-whitespace ui-row-flex-ver wst-model"  style="height:auto;overflow:hidden;" onclick="news({{d[i].articleId}})">
              <div class="wst-max-info">
                    <div class="ui-nowrap-multi" style="-webkit-line-clamp: 1;" >{{d[i].articleTitle}}</div>
              </div>
              <div class="wst-max-info">
                    <div class="ui-nowrap-multi wst-mix-cont" style="-webkit-line-clamp: 1;padding-top: 0px;" >{{d[i].articleContent}}</div>
              </div>
              <div class="max-img">
                  <a href="javascript:void(0);">
                      {{# if(d[i].coverImg) { }}
                     <img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" data-echo="__RESOURCE_PATH__/{{WST.replaceImg(d[i].coverImg,imgSuffix)}}">
                     {{# } else { }}
                     <img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.goodsLogo'); ?>" >
                     {{#   } }}
                  </a>
             </div>
             <div class="max-remind wst-mix-info ui-row">
                <div class="ui-col ui-col-50 ui-flex ui-flex-ver ui-flex-pack-center ui-flex-align-start">• 已有{{d[i].visitorNum}}人浏览</div>
                <div class="ui-col ui-col-50 ui-flex ui-flex-ver ui-flex-pack-center ui-flex-align-end">• {{d[i].createTime}}</div>
             </div>
            </div>  
        {{# } }}
         {{# if(d[i].TypeStatus==4){ }}
             <div class="news-item wst-model" onclick="news({{d[i].articleId}})">
              <div class="ui-row-flex" style="height:100px;">
               <div class="ui-col">
                 <div class="ui-row-flex ui-row-flex-ver wst-info" >
                     <div class="ui-nowrap-multi" style="-webkit-line-clamp: 1;">{{d[i].articleTitle}}</div>
                     <div class="ui-nowrap-multi wst-mix-cont" style="-webkit-line-clamp: 3;">{{d[i].articleContent}}</div>
                 </div>
               </div>
               
              </div>
              <div class="ui-row-flex ui-whitespace wst-model wst-mix-info ">
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-start">• 已有{{d[i].visitorNum}}人浏览</div>
                  <div class="ui-col ui-col ui-flex-pack-center ui-flex-align-end wst-right-align">• {{d[i].createTime}}</div>
              </div>
            </div>
        {{# } }}
          
        {{# } }}
    </script>
    </div>


	        
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



<script>
$(function(){
  initPage();
  // Tab切换卡
  $('.tab-item').click(function(){
      $(this).addClass('tab-curr').siblings().removeClass('tab-curr');
      var catId = $(this).attr('catId');
      $('#catId').val(catId);
      reFlashList();
  });
})
</script>
<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/js/news.js?v=<?php echo $v; ?>'></script>

</body>
</html>