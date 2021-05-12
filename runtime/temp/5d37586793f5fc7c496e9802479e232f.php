<?php /*a:2:{s:101:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\orderservices\list.html";i:1602924179;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
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

<div class='wst-toolbar'>
    <input type='text' class="s-ipt" id='orderNo' placeholder='订单号' />
    <a class="btn btn-primary" onclick="queryByPage()"><i class="fa fa-search"></i>查询</a>
</div>
<style>
.gImg{width: 50px; height: 50px; margin: 5px;}
.os-tc{text-align: center;}
.j-order-row td{text-align: center;}
</style>
<div class='wst-grid'>
    <table class='wst-order-list'>
        <thead>
            <tr class='head'>
                <th width="155" class="th-padding os-tc">订单编号</th>
                <th class="os-tc">售后商品</th>
                <th width="210" class="os-tc">售后类型</th>
                <th width="210" class="os-tc">状态</th>
                <th width="210" class="os-tc">操作</th>
            </tr>
        </thead>
        <tbody id='loadingBdy'>
            <tr id='loading' class='empty-row' style='display:none'>
                <td colspan='4'><img src="__SHOP__/img/loading.gif">正在加载数据...</td>
            </tr>
        </tbody>
        <script id="tblist" type="text/html">
       {{# for(var i = 0; i < d.length; i++){ }}
       <tbody class="j-order-row">
            <tr>
                <td>{{ d[i].orderNo }}</td>
                <td>
                    {{# 
                        var imgCode = d[i].gImgs.map(function(item){
                            return "<img data-original='__RESOURCE_PATH__/"+item+"' class='gImg'>"
                        });
                        imgCode = imgCode.join('')
                    }}
                    {{ imgCode }}
                </td>
                <td>
                    {{# 
                        var type = "";
                        switch(d[i].goodsServiceType){
                            case 0:
                                type = "退货退款";
                            break;
                            case 1:
                                type = "仅退款";
                            break;
                            case 2:
                                type = "仅换货";
                            break;
                        }
                    }}
                    {{ type }}
                </td>
                <td>
                    {{ d[i].statusText }}
                </td>
                <td>
                    {{# 
                        var props = {id:d[i].id};
                        var dealUrl = WST.U('shop/orderservices/deal',props);
                    }}
                    <a href="{{ dealUrl }}">【详情】</a>
                </td>
            </tr>
       </tbody>
       {{# }  }}
       </script>
        <tr class='empty-row' style="border: 0px;">
            <td colspan='4' id='pager' align="center" style='padding:5px 0px 5px 0px'>&nbsp;</td>
        </tr>
    </table>
</div>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script type="text/javascript" src="/static/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__SHOP__/orderservices/orderservices.js?v=<?php echo $v; ?>'></script>
<script>
    $(function () {
        queryByPage(<?php echo $p; ?>);
    })
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>