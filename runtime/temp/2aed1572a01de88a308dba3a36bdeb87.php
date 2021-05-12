<?php /*a:2:{s:89:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\import.html";i:1602924174;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />

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

<div class="wst-body">
    <table class="wst-form">
        <tr>
            <td colspan='2' >
                <div class='wst-tips-box' style='margin-top:10px;'>
                    <div class='icon'></div>
                    <div class='tips' >
                        1.请确保商品价格必须大于0，否则将自动默认为0.01。<br/>
                        2.请确保商品库存不能为负数，否则将自动默认为0。<br/>
                        3.请勿重复上传, 否则将造成重复商品数据。<br/>
                        4.请保证导入的数据在Excel的第一个工作表(Sheet)。<br/>
                        5.若Excel上某一行第一列为空则代表商品数据导入完毕。<br/>
                        6.若没有数据模板，请点击<a href='/static/template/goods.xls' style='color:blue;' target='_blank'>下载Excel模板</a>。<br/>
                        7.推荐使用谷歌浏览器或者火狐浏览器Firefox以获得更佳体验。<br/>
                    </div>
                    <div style="clear:both"></div>
                </div>
            </td>
        </tr>
        <tr>
            <th align='right' width='90'>商品数据：</th>
            <td>
                <div id="filePicker" style='margin-left:0px;'>导入商品数据</div>
            </td>
        </tr>
        <tr style="display: none;">
            <th align='top' style="color: red;font-weight: bold;vertical-align: text-top;" width='90'>错误信息：</th>
            <td id="errMsgBox"></td>
        </tr>
    </table>
</div>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script>
    var uploading = null;
    $(function(){
        var uploader = WST.upload({
            server:"<?php echo url('shop/imports/importGoods'); ?>",pick:'#filePicker',
            formData: {dir:'temp'},
            callback:function(f,file){
                layer.close(uploading);
                uploader.removeFile(file);
                var json = WST.toJson(f);
                $('#errMsgBox').parent().hide();
                if(json.status==1){
                    uploader.refresh();
                    WST.msg('导入数据成功!已导入数据'+json.importNum+"条", {icon: 1});
                    if(json.specErrMsg && json.specErrMsg.length>0){
                        var _msg = json.specErrMsg.map(function(x){return "<div style='color: red;font-weight: bold;'>"+x.msg+"</div>"});
                        $('#errMsgBox').html(_msg.join('')).parent().show();
                    }
                }else{
                    WST.msg('导入数据失败,出错原因：'+json.msg, {icon: 5});
                }
            },
            progress:function(rate){
                uploading = WST.msg('正在导入数据，请稍后...');
            }
        });
    });
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>