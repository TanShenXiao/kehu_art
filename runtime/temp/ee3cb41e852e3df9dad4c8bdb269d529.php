<?php /*a:2:{s:71:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\mobilebtns\list.html";i:1602924238;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<div id='alertTips' class='alert alert-success alert-tips fade in'>
  <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明</div>
  <ul class='body'>
    <li>该功能主要用于设置移动/微信端顶部按钮图标及其链接地址。若“所属插件”栏有值的记录请谨慎操作，以免造成插件新增或者删除失败。</li>
  </ul>
</div>
<form autocomplete="off">
<div class="wst-toolbar">
    <select id="btnSrc1"  class="query">
      <option value="-1">请选择按钮位置</option>
      <option value="0">手机版</option>
      <option value="1">微信版</option>
      <option value="2">小程序</option>
      <option value="3">APP</option>
    </select>
  <input type="text" name="btnName" placeholder="按钮名称" id="btnName1" class="query">
  <button type="button"  class='btn btn-primary btn-mright' onclick="javascript:loadGrid()"><i class="fa fa-search"></i>查询</button>
  <?php if(WSTGrant('ANGL_01')): ?>
   <button type='button' class="btn btn-success f-right  btn-fixtop" onclick="javascript:toEdit(0)"><i class='fa fa-plus'></i>新增</button>
   <?php endif; ?>
   <div style="clear:both"></div>
</div>
</form>
<div class='wst-grid'>
<div id="mmg" class="mmg"></div>
<div id="pg" style="text-align: right;"></div>
</div>

<div id='mbtnBox' style='display:none'>
    <form id='mbtnForm' method="post" autocomplete="off">
    <table class='wst-form wst-box-top'>
       <tr>
          <th width='150'>按钮名称<font color='red'>*</font>：</th>
          <td><input type='text' id='btnName' name="btnName"  class='ipt' maxLength='20'/></td>
       </tr>
       <tr>
          <th width='150'>按钮Url<font color='red'>*</font>：</th>
          <td><input type='text' id='btnUrl' name="btnUrl"  class='ipt' /></td>
       </tr>
       <tr>
          <th>图标：</th>
          <td>
            <input type="text" readonly="readonly" id='btnImg' name="btnImg" class="ipt" style="float: left;width: 355px;"/>
            <div id='adFilePicker'>上传</div><span id='uploadMsg'></span>
            <div style="max-height:30px;float: left; margin-left: 5px;" id="preview"></div>
          </td>
       </tr>
       <tr id="mbBtnType">
          <th>按钮类别：</th>
          <td>
            <select id="btnSrc"  class="ipt">
              <option value="0">手机版</option>
              <option value="1">微信版</option>
              <option value="2">小程序</option>
              <option value="3">APP</option>
            </select>
          </td>
       </tr>
       <tr>
          <th>所属插件：</th>
          <td>
            <input type="text" id="addonsName" class="ipt" />
          </td>
       </tr>
       <tr>
          <th>排序号：</th>
          <td>
            <input type="text" id="btnSort" class="ipt" />
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
<script src="__ADMIN__/mobilebtns/mobilebtns.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>