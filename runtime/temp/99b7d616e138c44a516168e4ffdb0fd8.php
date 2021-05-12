<?php /*a:2:{s:99:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\brandapplys\list.html";i:1589793158;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="/static/plugins/mmgrid/mmGrid.css?v=<?php echo $v; ?>" />

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

<input type="hidden" id="isNew" value="1"/>
<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
    <ul class="layui-tab-title">
        <li <?php if($type=='new'): ?>class="layui-this"<?php endif; ?>>我的品牌</li>
        <li <?php if($type=='join'): ?>class="layui-this"<?php endif; ?>>我加盟的品牌</li>
    </ul>
    <div class="layui-tab-content" style='width:99%;margin-bottom: 10px;'>
        <div class="layui-tab-item <?php if($type=='new'): ?>layui-show<?php endif; ?>">
            <div class="wst-toolbar">
                <input type='text' id='key' placeholder='品牌名称'/>
                <button class="btn btn-primary" onclick='javascript:loadGrid(0)'><i class='fa fa-search'></i>查询</button>
                <button class="btn btn-success f-right" onclick="javascript:toEdit(0)"><i class='fa fa-plus'></i>新增</button>

                <div style='clear:both'></div>
            </div>
            <div class='wst-grid'>
                <div id="mmg" class="mmg"></div>
                <div id="pg" style="text-align: right;"></div>
            </div>
        </div>
        <div class="layui-tab-item <?php if($type=='join'): ?>layui-show<?php endif; ?>" >
            <div class="wst-toolbar">
                <input type='text' id='key2' placeholder='品牌名称'/>
                <button class="btn btn-primary" onclick='javascript:loadGrid2(0)'><i class='fa fa-search'></i>查询</button>
                <button class="btn btn-success f-right" onclick="javascript:toEdit(0)"><i class='fa fa-plus'></i>新增</button>
                <div style='clear:both'></div>
            </div>
            <div class='wst-grid'>
                <div id="mmg2" class="mmg2"></div>
                <div id="pg2" style="text-align: right;"></div>
            </div>
        </div>
    </div>
</div>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__SHOP__/brandapplys/brandapplys.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
    $(function(){
        <?php if($type=='new'): ?>
            $('#isNew').val(1);
            initGrid(<?php echo $p; ?>);
        <?php else: ?>
            $('#isNew').val(0);
            initGrid2(<?php echo $p; ?>);
        <?php endif; ?>
        var element = layui.element;
        element.on('tab(msgTab)', function(data){
            if(data.index==1){
                $('#isNew').val(0);
                initGrid2(<?php echo $p; ?>);
            }else{
                $('#isNew').val(1);
                initGrid(<?php echo $p; ?>);
            }
        });
    })
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>