<?php /*a:2:{s:70:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\userranks\edit.html";i:1602924251;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<div class="l-loading" style="display: block" id="wst-loading"></div>
<form id="userRankForm" autocomplete="off">
<table class='wst-form wst-box-top'>
      <tr>
      <th width='170'>会员等级名称<font color='red'>*</font>：</th>
          <td>
            <input type="text" class="ipt" id="rankName" name="rankName" value="<?php echo $data['rankName']; ?>" />
          </td>
       </tr>
       <tr>
          <th width='100'>会员等级图标：</th>
          <td>
            <input type="text" readonly="readonly" name="userrankImg" id="userrankImg" value="<?php echo $data['userrankImg']; ?>" class="ipt" style="float: left;width: 355px;"/>
            <div id='userranksPicker'>上传</div><span id='uploadMsg'></span>
            <div style="min-height:30px; float: left; margin-left: 5px;" id="preview">
              <?php if((isset($data['userrankImg']))): ?>
                <img src="__RESOURCE_PATH__/<?php echo $data['userrankImg']; ?>" height="30" />
              <?php endif; ?>
            </div>
          </td>
       </tr>
       <tr>
          <th>积分下限(大于或等于)<font color='red'>*</font>：</th>
          <td>
              <input type="text" class="ipt" id="startScore" name="startScore" value="<?php echo $data['startScore']; ?>" />
          </td>
       </tr>
       <tr>
          <th>积分上限(小于)<font color='red'>*</font>：</th>
          <td>
              <input type="text" class="ipt" id="endScore" name="endScore" value="<?php echo $data['endScore']; ?>" />
          </td>
       </tr>
       <tr>
          <td colspan='2' align='center' class='wst-bottombar' style="padding-left: 175px;">
             <input type="hidden" name="id" id="rankId" class="ipt" value="<?php echo $data['rankId']+0; ?>" />
             <button type="submit"  class='btn btn-primary btn-mright'><i class="fa fa-check"></i>提交</button>
             <button type="button"  class='btn' onclick="javascript:location.href='<?php echo Url('admin/userranks/index','p='.$p); ?>'"><i class="fa fa-angle-double-left"></i>返回</button>
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
<script src="__ADMIN__/userranks/userranks.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>