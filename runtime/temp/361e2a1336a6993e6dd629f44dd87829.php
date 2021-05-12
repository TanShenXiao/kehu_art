<?php /*a:2:{s:66:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\users\list.html";i:1602924251;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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
<style type="text/css">
  #mmg td:nth-child(6){color: red;}
  #mmg td:nth-child(7){color: #31c15a;}
  #mmg td:nth-child(8){color: #1890ff;}
</style>

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

<div id='alertTips' class='alert alert-success alert-tips fade in'>
  <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明</div>
  <ul class='body'>
      <li>本功能主要用于管理会员（商家的会员身份）资料，您可以通过各种条件查询会员资料。</li>
      <li>本功能主要以买家身份涉及的资料为主，例如买家的积分，资金和消费记录。商家资金在财务模块的资金管理中查看。</li>
  </ul>
</div>
<div class="wst-toolbar">
  <form autocomplete="off" >
   <div id="query" style="float:left;">
        <select name="userType" id="userType" class="query">
          <option value="">会员类型</option>
          <option value="0">普通会员</option>
          <option value="1">商家</option>
        </select>
        <input type="text" name="loginName1"  placeholder='账号/店铺名称' id="loginName1" class="query" />
        <input type="text" name="loginPhone" placeholder='手机号码' id="loginPhone" class="query" />
        <input type="text" name="loginEmail" placeholder='电子邮箱' id="loginEmail" class="query" />
        <button type="button" class="btn btn-primary" onclick="javascript:userQuery()" ><i class="fa fa-search"></i>查询</button> 
  </div>
    <button type="button" class="btn btn-primary f-right" style='margin-top:0px;margin-left:5px;' onclick='javascript:toExport(0)'><i class="fa fa-sign-in"></i>导出</button>
   <?php if(WSTGrant('HYGL_01')): ?>
   <button type="button" class="btn btn-success f-right" style='margin-top:0px;' onclick="javascript:toEdit()"><i class="fa fa-plus"></i>新增</button> 
   <?php endif; ?>
   <div style="clear:both"></div>
</form>
</div>
<div class='wst-grid'>
<div id="mmg" class="mmg"></div>
<div id="pg" style="text-align: right;"></div>
</div>
<script>
  $(function(){initGrid(<?php echo $p; ?>)});
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/users/users.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>