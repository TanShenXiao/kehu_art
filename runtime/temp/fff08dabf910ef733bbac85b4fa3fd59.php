<?php /*a:2:{s:96:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\shopcats\list.html";i:1602924182;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
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

<script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>
<div class='wst-toolbar'>
    <a class="btn btn-success f-right" href='javascript:addCat(1);'><i class="fa fa-plus"></i>新增</a>
    <div style="clear:both"></div>
</div>
<div class="wst-grid">
    <form autocomplete="off">
        <table id="cat_list_tab" class='wst-list wst-form'>
            <thead>
            <tr class="wst-colour">
                <th class="wst-fre-th wnm">名称</th>
                <th class="wpxh">排序号</th>
                <th class="wxs">是否显示<span style="font-weight:normal;color:red;">(双击可修改)</span></th>
                <th class="wcz">操作</th>
            </tr>
            </thead>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tbody>
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
            <tr id='tr_<?php echo $i; ?>' isLoad='1'>
                <td class="wst-fre-td wnm">
                    <span class='wst-tree-open active' onclick='javascript:treeCatOpen(this,<?php echo $vo['catId']; ?>)'><img class="wst-lfloat" style="margin-top:-3px;" src="__SHOP__/img/seller_icon_zk.png"></span>
                    <input type='text' style='width:400px;' value='<?php echo $vo['catName']; ?>' dataId="<?php echo $vo['catId']; ?>" onchange='javascript:editCatName(this)'/>
                </td>
                <td class="wpxh"><input class='catsort' type='text' style='width:35px;' value="<?php echo $vo['catSort']; ?>" dataId="<?php echo $vo['catId']; ?>" onchange='javascript:editCatSort(this)' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)"/></td>
                <?php if($vo['isShow']==0): ?>
                <td class="wxs" style="cursor:pointer;" ondblclick="changeCatStatus(1,<?php echo $vo['catId']; ?>,0)"><span class='wst-state_no'><img class="wst-lfloat" style="margin-top:3px;" src="__SHOP__/img/seller_icon_error.png"></span></td>
                <?php else: ?>
                <td class="wxs" style="cursor:pointer;" ondblclick="changeCatStatus(0,<?php echo $vo['catId']; ?>,0)"><span class='wst-state_yes'><img class="wst-lfloat" style="margin-top:3px;" src="__SHOP__/img/seller_icon_right.png"></span></td>
                <?php endif; ?>
                <td class="wcz">
                    <a href="javascript:void(0);" onclick='javascript:addCat(this,<?php echo $vo["catId"]; ?>,<?php echo $i; ?>);' class='add btn btn-blue' title='新增'><i class="fa fa-plus"></i>新增</a>
                    <a href="javascript:void(0);" onclick="javascript:delCat(<?php echo $vo['catId']; ?>,0)" class='del btn btn-red' title='删除'><i class="fa fa-trash-o"></i>删除</a>&nbsp;
                </td>
            </tr>
            <?php if(isset($vo['childNum'])): if($vo['childNum'] > 0): if(is_array($vo['child']) || $vo['child'] instanceof \think\Collection || $vo['child'] instanceof \think\Paginator): $i2 = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i2 % 2 );++$i2;?>
            <tr id='tr_<?php echo $i; ?>_<?php echo $i2; ?>' class="tr_<?php echo $i; ?> tree_<?php echo $vo['catId']; ?>" isLoad='1'>
                <td class="wst-fre-td wnm">
                    <span class="wst-tree-second"></span>
                    <input type='text' style='width:400px;' value='<?php echo $vo2['catName']; ?>' dataId="<?php echo $vo2['catId']; ?>" onchange='javascript:editCatName(this)'/>
                </td>
                <td class="wpxh"><input class='catsort' type='text' style='width:35px;' value="<?php echo $vo2['catSort']; ?>" dataId="<?php echo $vo2['catId']; ?>" onchange='javascript:editCatSort(this)' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)"/></td>
                <?php if($vo2['isShow']==0): ?>
                <td class="wxs" style="cursor:pointer;" onclick="changeCatStatus(1,<?php echo $vo2['catId']; ?>,<?php echo $vo['catId']; ?>)"><span class='wst-state_no'><img class="wst-lfloat" style="margin-top:3px;" src="__SHOP__/img/seller_icon_error.png"></span></td>
                <?php else: ?>
                <td class="wxs" style="cursor:pointer;" onclick="changeCatStatus(0,<?php echo $vo2['catId']; ?>,<?php echo $vo['catId']; ?>)"><span class='wst-state_yes'><img class="wst-lfloat" style="margin-top:3px;" src="__SHOP__/img/seller_icon_right.png"></span></td>
                <?php endif; ?>
                <td class="wcz">
                    <a href="javascript:delCat(<?php echo $vo2['catId']; ?>,0)" class='del btn btn-red' title='删除'><i class="fa fa-trash-o"></i>删除</a>&nbsp;
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <?php endif; ?>
            <?php endif; ?>
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
            </tbody>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
    </form>
    <div class='wst-tbar-group' style='height: 76px;text-align: center;margin-top: 10px;padding: 0px 10px;'>
        <button class='wst-shop-but btn btn-primary btn-mright' style="display: none;" type="button" onclick='javascript:batchSaveCats()'><i class="fa fa-check"></i>保&nbsp;存</button>
        <button class='wst-shop-but btn' style="display: none;" type="button" onclick='javascript:location.reload()'><i class="fa fa-angle-double-left"></i>取&nbsp;消</button>
        <a class="btn btn-success f-right" href='javascript:addCat(1);'><i class="fa fa-plus"></i>新增</a>
    </div>
</div>



<script id="cat_p_tr" type="text/html">
    <tbody class='tbody_new'>
    <tr class="tr_new" isload="1">
        <td class="wst-fre-td wnm">
            <span class="wst-tree-open"><img class="wst-lfloat" style="margin-top:-3px;" src="__SHOP__/img/seller_icon_zk.png"></span>
            <input class="catname" type="text" style="width:400px;margin-left:6px;" dataid="">
        </td>
        <td class="wpxh">
            <input class="catsort" type="text" style="width:35px;" value="0" onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)">
        </td>
        <td class="wxs" style="cursor:pointer;">
            <input class="catshow" type="checkbox" checked=""/>
        </td>
        <td class="wcz">
            <a href="javascript:void(0);" onclick="addCat(this,0,0);" class="add btn btn-blue" title="新增"><i class="fa fa-plus"></i>新增</a>
            <a href="javascript:void(0);" class="del btn btn-red" title="删除" onclick="delCatObj(this,1)"><i class="fa fa-trash-o"></i>删除</a>&nbsp;
        </td>
    </tr>
    </tbody>
</script>

<script id="cat_c_tr" type="text/html">
    <tr class="{{d.className}}" isload="1" catid="{{d.p}}">
        <td class="wst-fre-td wnm">
            <span class="wst-tree-second"></span>
            <input class="catname" type="text" style='width:400px' dataid="">
        </td>
        <td class="wpxh">
            <input class="catsort" type="text" style="width:35px;" value="0" onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)">
        </td>
        <td class="wxs" style="cursor:pointer;">
            <input class="catshow" type="checkbox" checked=""/>
        </td>
        <td class="wcz">
            <a href="javascript:void(0);" class="del btn btn-red" title="删除" onclick="delCatObj(this,2)"><i class="fa fa-trash-o"></i>删除</a>&nbsp;
        </td>
    </tr>
</script>


<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script type='text/javascript' src='__SHOP__/shopcats/shopcats.js?v=<?php echo $v; ?>'></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>