<?php /*a:2:{s:67:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\addons\list.html";i:1602924205;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
      <ul class="layui-tab-title">
            <li class="layui-this">已安装</li>
            <li>未安装</li>
      </ul>
      <div class="layui-tab-content addons" style="padding: 10px 0;">
            <div class="layui-tab-item layui-show">
                <div class="qy">已启用<span style="color:red;font-weight:bold;">&nbsp;&nbsp;(安装插件后，进入“设置”，填写配置信息，然后点击保存！)</span></div>
                <ul><div id='addonsBox1'></div></ul>
                <div class="qy">未启用</div>
                <ul><div id='addonsBox2'></div></ul>
            </div>
            <div class="layui-tab-item">
                <ul><div id='addonsBox0'></div></ul>
                <div style='text-align:center' id='pager'></div>
            </div>
      </div>
</div>
<script id="addonsTpl" type="text/html">
      {{# for(var i = 0; i < d['data'].length; i++){ }}
      <li {{# if(d['addonStatus']==2){}}class="no_use"{{#}}}>
            <div class="cimg"><img src="/addons/{{d['data'][i].name.toLowerCase()}}/logo.png"></div>
            <div class="ctxt">
                  <h3>{{d['data'][i].title}}</h3>
                  <p title='{{d['data'][i].description}}'>{{d['data'][i].description}}</p>
            </div>
            <div class="cbtn">
                  {{# if(WST.GRANT.CJGL_01 && d['data'][i]['status']>0 && d['data'][i]['isConfig']==1){}}
                  <a href="{{WST.U('admin/Addons/toEdit','id='+d['data'][i]['addonId'])}}">设置</a>
                  {{#}}}
                  {{# if(WST.GRANT.CJGL_02 && d['data'][i]['status']==0){}}
                  <a href="javascript:install({{d['data'][i]['addonId']}})">安装</a>
                  {{#}}}
                  {{# if(WST.GRANT.CJGL_04 &&  d['data'][i]['status']==2){}}
                  <a href="javascript:enable({{d['data'][i]['addonId']}})">启用</a>
                  {{#}}}
                  {{# if(WST.GRANT.CJGL_05 && d['data'][i]['status']==1){}}
                  <a href="javascript:disable({{d['data'][i]['addonId']}})" class="xiez">禁用</a>
                  {{#}}}
                  {{# if(WST.GRANT.CJGL_03 && d['data'][i]['status']>0){}}
                  <a href="javascript:uninstall({{d['data'][i]['addonId']}})" class="xiez">卸载</a>
                  {{#}}}
            </div>
      </li>
      {{# } }}
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/addons/addons.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>