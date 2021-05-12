<?php /*a:2:{s:74:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\users\account_list.html";i:1602924251;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<div id='alertTips' class='alert alert-success alert-tips fade in'>
  <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明</div>
  <ul class='body'>
      <li>本功能主要用于管理会员（商家）的账号信息，您可以通过各种条件查询会员账号。</li>
  </ul>
</div>
<div class="wst-toolbar">
	<form>
  <div id="query">
        <input type="text" name="loginName1" placeholder='账号/店铺名称'  id="loginName1" class="query" />
        <select name="userType1" id="userType1" class="query">
          <option value="">会员类型</option>
          <option value="0">普通会员</option>
          <option value="1">商家</option>
        </select>
        <select name="userStatus1" id="userStatus1" class="query">
          <option value="">账号状态</option>  
          <option value="1">启用</option>  
          <option value="0">停用</option>  
        </select>
        <button type="button"  class='btn btn-primary btn-mright' onclick="javascript:accountQuery()" ><i class="fa fa-search"></i>查询</button>
  </div>
  </form>
   <div style="clear:both"></div>
</div>
<form autocomplete="off" class='layui-form wst-grid' lay-filter='gridForm'>
<div id="mmg" class="mmg"></div>
</form>
<div id="pg" style="text-align: right;"></div>
<div id='accountBox' style='display:none'>
    <form id='accountForm' autocomplete="off" class='layui-form'>
    <table class='wst-form wst-box-top'>
       <tr>
          <th width='100'>账号<font color='red'>*</font>：</th>
          <td height='30'>
            <input type="hidden" name="userId" id="userId"> 
           	<div id="loginName"></div>
            </td>
       </tr>
       <tr>
          <th>密码<font color='red'>*</font>：</th>
          <td><input type='password' id='loginPwd' data-rule="length[6~20];" placeholder="为空则说明不修改密码" class='ipt' maxLength='20'/></td>
       </tr>
       <tr>
          <th>会员状态<font color='red'>*</font>：</th>
          <td height='30'>
          		<input type="checkbox" class='ipt'  value='1' name="userStatus" id="userStatus" lay-skin="switch" lay-text="启用|停用">
          </td>
          
       </tr>
    </table>
    </form>
  </div>
<script>
  $(function(){
    initGrid(<?php echo $p; ?>)

  });
</script>


<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/users/account.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>