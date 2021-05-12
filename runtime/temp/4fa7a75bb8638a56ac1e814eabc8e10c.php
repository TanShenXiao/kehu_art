<?php /*a:2:{s:98:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\goods\list_sale.html";i:1602924168;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
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
<style type="text/css">
.mmGrid{
  width: 100%;
  margin: 0px auto;
}
</style>

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

<div class="wst-toolbar">
    <select name="cat1" id="cat1" onchange="getCat(this.value)" class="s-query">
        <option value="">-请选择商品分类-</option>
      <?php $_result=WSTShopCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <option value="<?php echo $vo['catId']; ?>" ><?php echo $vo['catName']; ?></option>
      <?php endforeach; endif; else: echo "" ;endif; ?>
    </select>
    <select name="cat2" id="cat2" class="s-query"><option value="">-请选择商品分类-</option></select>
  <select id='goodsType' class="s-query">
     <option value=''>全部商品类型</option>
     <option value='0'>实物商品</option>
     <option value='1'>虚拟商品</option>
  </select>
    <input type="text" name="goodsName" id="goodsName" class="s-query" placeholder='商品名称/商品编号'/>
    <a class="s-btn btn btn-primary" onclick="loadGrid()"><i class="fa fa-search"></i>查询</a>
</div>
<div class="wst-toolbar"> 
   <div class="s-menu">
      <a href='javascript:;' onclick="changeSale(0,'sale')" class="s-sale"><span>下架</span></a>
      <a href='javascript:void(0);' onclick="changeGoodsStatus('isRecom','sale')" class="s-rec"><span>推荐</span></a>
      <a href='javascript:void(0);' onclick="changeGoodsStatus('isNew','sale')" class="s-new"><span>新品</span></a>
      <a href='javascript:void(0);' onclick="changeGoodsStatus('isBest','sale')" class="s-best"><span>精品</span></a>
      <a href='javascript:void(0);' onclick="changeGoodsStatus('isHot','sale')" class="s-hot"><span>热销</span></a>
      <a href='javascript:void(0);' onclick="benchDel('sale')" class="s-del btn btn-danger wst-rfloat" style="float: right;"><span><i class="fa fa-trash-o"></i>删除</span></a>
      <a href='javascript:void(0);' onclick="toAdd('sale')" class="s-add btn btn-success wst-rfloat" style="float: right;"><span><i class="fa fa-plus"></i>新增</span></a>
   </div>
</div>
<div class='wst-grid'>
    <div id="mmg" class="mmg"></div>
    <div id="pg" style="text-align: right;margin: 10px 0px 20px;"></div>
</div>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script type='text/javascript' src='__SHOP__/goods/goods.js?v=<?php echo $v; ?>'></script>
<script>    var mmg;
$(function(){saleByPage(<?php echo $p; ?>)})
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>