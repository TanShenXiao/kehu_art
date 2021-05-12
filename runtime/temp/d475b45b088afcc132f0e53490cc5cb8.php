<?php /*a:2:{s:99:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\shopstyles\index.html";i:1602924183;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<title>卖家中心 - <?php echo WSTConf('CONF.mallName'); ?></title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="/static/plugins/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/layui/css/layui.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css" type="text/css" />
<script src="__SHOP__/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>

<link href="__SHOP__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<?php 
$msgGrant = [];
if(WSTShopGrant('shop/messages/shopMessage'))$msgGrant[] = 'message';
if(WSTShopGrant('shop/orders/waitdelivery'))$msgGrant[] = 'shoporder24';
if(WSTShopGrant('shop/orders/waituserPay'))$msgGrant[] = 'shoporder55';
if(WSTShopGrant('shop/orders/failure'))$msgGrant[] = 'shoporder45';
if(WSTShopGrant('shop/ordercomplains/shopComplain'))$msgGrant[] = 'shoporder25';
if(WSTShopGrant('shop/goods/stockWarnByPage'))$msgGrant[] = 'shoporder54';
 ?>
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>",'RESOURCE_PATH':'<?php echo WSTConf('CONF.resourcePath'); ?>',"SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>",'TIME_TASK':1,"MESSAGE_BOX":"<?php echo WSTShopMessageBox(); ?>",MSG_SHOP_GRANT:'<?php echo implode(',',$msgGrant); ?>'}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__SHOP__/img/ajax-loader.gif"/></div>

<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
   <ul class="layui-tab-title">
   <?php if(is_array($cats) || $cats instanceof \think\Collection || $cats instanceof \think\Paginator): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
      <li <?php if($key==0): ?>class="layui-this"<?php endif; ?>  onclick="listQuery('<?php echo $vo['styleSys']; ?>',1)"><?php echo $vo['styleSys']; ?></li>
   <?php endforeach; endif; else: echo "" ;endif; ?>
   </ul>
   <div class="layui-tab-content" style="padding: 10px 0;">
      <?php if(is_array($cats) || $cats instanceof \think\Collection || $cats instanceof \think\Paginator): $i = 0; $__LIST__ = $cats;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
         <div id="style_<?php echo $vo['styleSys']; ?>" class="layui-tab-item <?php if($key==0): ?>layui-show<?php endif; ?>">
         </div>
      <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
</div>
<script id="tblist" type="text/html">
    {{# var dcat=d['cat'];var dcats=d['cats']; var dsys=d['sys'];}}
    <div class='style-txt' style="margin-left:10px;">分类：
        <select name="shopStylesCat" id="shopStylesCat" onchange="listQuery('{{dsys}}',1,this)">
            <option class="ipt" value="-1">全部分类</option>
            {{# for(var j = 0; j < dcats.length; j++){ }}
            <option class="ipt" value="{{dcats[j]['dataVal']}}" {{# if(dcat == dcats[j]['dataVal']){ }}selected{{# } }}>{{dcats[j]['dataName']}}</option>
            {{#}}}
        </select>
    </div>
{{# var dt = d['theme'];var dl = d['list']['data'];for(var i = 0; i < dl.length; i++){ }}
<div class='style-box' style="height:270px;">
   <div class='style-img'>
     <a>
      <img data-original='/wstmart/{{d["sys"]}}/view/{{dl[i]["styleImgPath"]}}/{{dl[i]["screenshot"]}}' class='gImg' />
     </a>
   </div>
   <div class='style-txt' style="margin-bottom:10px;">标题：{{dl[i]['styleName']}}</div>
   <div class='style-op'>
   {{# if(dl[i]['stylePath']==dt){}}
   <button class='btn btn-disabled style_{{dl[i]['id']}}' dataid='{{dl[i]['id']}}' type='button' disabled><i class='fa fa-check-circle'></i>应用中</button>
   {{# }else{ }}
   <button class='btn btn-success style_{{dl[i]['id']}}' dataid='{{dl[i]['id']}}' type='button' style="margin-top: 0px;"><i class='fa fa-check-circle'></i>启用</button>
   {{# } }}
   </div>
</div>
{{#}}}
</script>
<div style="position:fixed;bottom:10px;width:100%;height:50px;display:none">
    <div id='pager' align="center" style='padding:5px 0 5px 0'>&nbsp;</div>
</div>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script src="__SHOP__/shopstyles/styles.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>