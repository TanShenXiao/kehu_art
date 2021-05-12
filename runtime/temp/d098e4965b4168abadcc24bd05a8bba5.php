<?php /*a:2:{s:67:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\trades\edit.html";i:1586933927;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<title>后台管理中心 - <?php echo WSTConf('CONF.mallName'); ?></title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="/static/plugins/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/layui/css/layui.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css" type="text/css" />
<script src="__ADMIN__/js/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="/static/plugins/mmgrid/mmGrid.css?v=<?php echo $v; ?>" />
<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<?php 
$msgGrant = [];
if(WSTGrant('TSDD_00'))$msgGrant[] = 'shopapply';
if(WSTGrant('DSHSP_00'))$msgGrant[] = 'goodsaudit';
if(WSTGrant('TSDD_00'))$msgGrant[] = 'ordercomplains';
if(WSTGrant('JBSP_00'))$msgGrant[] = 'informs';
 ?>
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"/index.php","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>",'RESOURCE_PATH':'<?php echo WSTConf('CONF.resourcePath'); ?>',MSG_GRANT:'<?php echo implode(',',$msgGrant); ?>'}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<form id='goodstradesForm' autocomplete="off">
<div id='tab' class="layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">行业信息</li>
        <li>seo设置</li>
    </ul>
    <div class="layui-tab-content" style='width:99%;margin-bottom: 10px;'>
        <div class="layui-tab-item layui-show wst-box-top" style="position: relative;">
            <div class='layui-form'>
                    <input type='hidden' id='parentId' name="parentId" class='ipt' value="<?php echo $object['parentId']; ?>"/>
                    <input type="hidden" id="tradeId" name="tradeId" class="ipt" value="<?php echo $object['tradeId']; ?>"/>
                    <table class='wst-form wst-box-top'>
                        <tr>
                            <th width='100'>行业名称<font color='red'>*</font>：</th>
                            <td><input type='text' id='tradeName' name="tradeName" class='ipt' maxLength='20' value="<?php echo $object['tradeName']; ?>" /></td>
                        </tr>
                        <tr>
                            <th width='100'>分类名缩写<font color='red'>*</font>：</th>
                            <td><input type='text' id='simpleName' name="simpleName" class='ipt' maxLength='20' value="<?php echo $object['simpleName']; ?>"/></td>
                        </tr>
                        <tr>
                            <th>移动端图标：</th>
                            <td>
                                <input type="text" readonly="readonly"  id='tradeImg' name="tradeImg" class="ipt" value="<?php echo $object['tradeImg']; ?>" style="float: left;width: 355px;" />
                                <div id='tradeFilePicker'>上传</div><span id='uploadMsg'></span>
                                <div style="min-height:30px; float: left; margin-left: 5px;" id="preview"><?php if($object['tradeImg']): ?><img src="__RESOURCE_PATH__/<?php echo $object['tradeImg']; ?>" height="30" /><?php endif; ?></div>
                            </td>
                        </tr>
                        <tr>
                            <th width='100'>是否显示<font color='red'>*</font>：</th>
                            <td height='24'>
                                <input type="checkbox" id="isShow" <?php if($object['isShow']==1): ?>checked<?php endif; ?> name="isShow" value="1" class="ipt" lay-skin="switch" lay-filter="isShow1" lay-text="显示|隐藏">
                            </td>
                        </tr>
                        <tr>
                            <th width='100'>楼层副标题<font color='red'> </font>：</th>
                            <td><input type='text' id='subTitle' name='subTitle' class='ipt' value="<?php echo $object['subTitle']; ?>"/></td>
                        </tr>
                        <tr>
                            <th width='100'>排序号<font color='red'>*</font>：</th>
                            <td><input type='text' id='tradeSort' name='tradeSort' class='ipt' style='width:60px;' onkeypress='return WST.isNumberKey(event);' onkeyup="javascript:WST.isChinese(this,1)" maxLength='10' value='<?php echo $object['tradeSort']; ?>'/></td>
                        </tr>
                        <?php if($object['parentId']==0): ?>
                        <tr>
                            <th width='100'>类目费用：</th>
                            <td><input type='text' id='tradeFee' name='tradeFee' class='ipt' style='width:120px;' onkeypress='return WST.isNumberKey(event);' onkeyup="javascript:WST.isChinese(this,1)" maxLength='7' value='<?php echo $object['tradeFee']; ?>'/></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td colspan='2' align='center'>
                                <button type="submit" class="btn btn-primary btn-mright" ><i class="fa fa-check"></i>保&nbsp;存</button>
                                <button type="button" class="btn" onclick="javascript:parent.closeEditBox()"><i class="fa fa-angle-double-left"></i>返&nbsp;回</button>
                            </td>
                        </tr>
                    </table>
                
            </div>
        </div>
        <div class="layui-tab-item" style="display:none;">
                <table class='wst-form wst-box-top'>
                    <tr>
                        <th width='150'>seo标题：</th>
                        <td>
                            <input type="text" id='seoTitle' name='seoTitle' class='ipt' value="<?php echo $object['seoTitle']; ?>" maxLength='100'/>
                            <span >(展示格式:seo标题 - 商城名称，为空则为:行业 - 店铺列表 - 商城名称)</span>
                        </td>
                    </tr>
                    <tr>
                        <th>seo关键字：</th>
                        <td>
                            <input type="text" id='seoKeywords' name='seoKeywords' class='ipt' value="<?php echo $object['seoKeywords']; ?>" maxLength='100'/>
                            <span >(为空则取商城seo关键字)</span>
                        </td>
                    </tr>
                    <tr>
                        <th>seo描述：</th>
                        <td>
                            <textarea id='seoDes' name='seoDes' class=" ipt" style='width:400px;'><?php echo $object['seoDes']; ?></textarea>
                            <span >(为空则取商城seo描述)</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='2' align='center'>
                            <button type="submit" class="btn btn-primary btn-mright" ><i class="fa fa-check"></i>保&nbsp;存</button>
                            <button type="button" class="btn" onclick="javascript:parent.closeEditBox()"><i class="fa fa-angle-double-left"></i>返&nbsp;回</button>
                        </td>
                    </tr>
                </table>
        </div>
        
</div>
</form>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/js/wstgridtree.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/trades/trades.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script>
$(function () {
    initUpload();
    $('#goodstradesForm').validator({
        fields: {
            tradeName: {
                tip: "请输入行业名称",
                rule: '行业名称:required;length[~20];'
            },
            simpleName: {
                tip: "请输入行业名缩写",
                rule: '行业名缩写:required;length[~20];'
            },
            tradeSort: {
                tip: "请输入排序号",
                rule: '排序号:required;length[~8];'
            }
        },
        valid: function(form){
            var tradeId = $('#tradeId').val();
            toEdits(tradeId);
        }
    });
});
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>