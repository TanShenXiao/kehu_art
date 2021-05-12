<?php /*a:2:{s:72:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\chargeitems\edit.html";i:1602924210;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<form id="adPositionsForm">
<table class='wst-form wst-box-top'>
  <tr>
     
       <tr>
          <th width='150'>充值金额<font color='red'>*</font>：</th>
          <td>
            <input type="text" id="chargeMoney" name="chargeMoney" value='<?php echo $data['chargeMoney']; ?>' class="ipt" onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/>
          </td>
       </tr>
       <tr>
          <th>赠送金额<font color='red'>*</font>：</th>
          <td>
            <input type="text" id="giveMoney" name="giveMoney" value='<?php echo $data['giveMoney']; ?>' class="ipt" onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/>
          </td>
       </tr>
       <tr>
          <th>排序号<font color='red'> </font>：</th>
          <td>
            <input type='text' id='itemSort' name="itemSort" value='<?php echo $data['itemSort']; ?>' class='ipt' maxLength='10' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/>
          </td>
       </tr>
  
  <tr>
     <td colspan='2' align='center' class='wst-bottombar'>
       <input type="hidden" name="id" id="id" class="ipt" value="<?php echo $data['id']+0; ?>" />
       <button type="submit" class="btn btn-primary btn-mright" ><i class="fa fa-check"></i>提交</button> 
        <button type="button" class="btn" onclick="javascript:location.href='<?php echo Url('admin/chargeitems/index','p='.$p); ?>'"><i class="fa fa-angle-double-left"></i>返回</button>
     </td>
  </tr>
</table>
</form>
<script>
$(function(){editInit(<?php echo $p; ?>)});
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script src="__ADMIN__/chargeitems/chargeitems.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>