<?php /*a:2:{s:86:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\admin\view\banks\list.html";i:1602924209;s:80:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<?php if(WSTGrant('YHGL_01')): ?>
<div class="wst-toolbar">
   <button class="btn btn-success f-right" onclick='javascript:toEdit(0)'><i class='fa fa-plus'></i>新增</button>
   <div style="clear:both"></div>
<?php endif; ?>
</div>
<div class='wst-grid'>
<div id="mmg" class="mmg"></div>
<div id="pg" style="text-align: right;"></div>
</div>
<div id='bankBox' style='display:none'>
    <form id='bankForm' autocomplete="off">
    <table class='wst-form wst-box-top'>
       <tr>
          <th width='100'>银行名称<font color='red'>*</font>：</th>
          <td><input type='text' id='bankName' name="bankName"  class='ipt' maxLength='50'/></td>
       </tr>
       <tr>
          <th width='100'>银行图标<font color='red'>*</font>：</th>
          <td>
            <input type='text' readonly="readonly" id='bankImg' name="bankImg" class='ipt' style="float: left;width: 355px;"/>
            <div id='bankImgPicker'>上传</div><span id='uploadMsg'></span>
            <div style="min-height:30px; float: left;margin-left: 5px;" id="preview"></div>
          </td>
       </tr>
    </table>
    </form>
 </div>
<script>
  $(function(){initGrid(<?php echo $p; ?>)});
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script src="__ADMIN__/banks/banks.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>