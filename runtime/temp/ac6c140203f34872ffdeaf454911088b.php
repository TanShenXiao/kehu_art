<?php /*a:2:{s:74:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\logmoneys\list_log.html";i:1602924236;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<style type="text/css">
  #mmg1 td:nth-child(4){color: red;}
  #mmg1 td:nth-child(5){color: #31c15a;}
  #mmg1 td:nth-child(6){color: #1890ff;}
  #mmg3 td:nth-child(4){color: red;}
</style>
<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
  <ul class="layui-tab-title">
    <li class="layui-this"><?php echo $object['userName']; ?>(<?php echo $object['loginName']; ?>)资金流水</li>
  </ul>
  <div class="layui-tab-content" style='padding:0px;'>
    <div class="layui-tab-item layui-show">
   <div class="wst-toolbar">
  <input type="text" id="startDate" name="startDate" class="ipt laydate-icon" maxLength="20"  />
 至
  <input type="text" id="endDate" name="endDate" class="ipt laydate-icon" maxLength="20"  />
  <button type="button" class="btn btn-primary btn-mright" onclick='javascript:loadMoneyGrid(<?php echo $object['userType']; ?>,<?php echo $object['userId']; ?>)'><i class="fa fa-search"></i>查询</button> 
  <button type="button" class="btn f-right" onclick="javascript:location.href='<?php echo Url('admin/'.$src.'/index','p='.$p); ?>'"><i class="fa fa-angle-double-left"></i>返回</button>
  <?php if(WSTGrant('ZJGL_02')): ?>
  <button class="btn btn-success btn-mright f-right" onclick="javascript:toAdd(<?php echo $object['userId']; ?>,<?php echo $object['userType']; ?>)"><i class='fa fa-plus'></i>新增</button> 
  <?php endif; ?>
  <div style='clear: both'></div>
  </div>
  <div class='wst-grid'>
   <div id="mmg" class="mmg"></div>
   <div id="pg" style="text-align: right;"></div>
  </div>
 </div>
</div>
<script>
  $(function(){moneyGridInit(<?php echo $object['userType']; ?>,<?php echo $object['userId']; ?>);})
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/logmoneys/logmoneys.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>