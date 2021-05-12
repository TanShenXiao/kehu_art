<?php /*a:2:{s:77:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\templatemsgs\edit_msg.html";i:1602924250;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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
<form autocomplete='off'>
<input type='hidden' id='id' class='ipt' value="<?php echo $object['id']; ?>"/>
<input type='hidden' id='tplCode' class='ipt' value="<?php echo $object['tplCode']; ?>"/>
<table class='wst-form wst-box-top layui-form'>
  <tr>
     <th width='120'>发送时机：</th>
     <td>
         <?php $_result=WSTDatas("TEMPLATE_SYS");if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($vo['dataVal']==$object['tplCode']): ?><?php echo $vo['dataName']; ?><?php endif; ?>
         <?php endforeach; endif; else: echo "" ;endif; ?>
     </td>
  </tr>
  <tr>
     <th>内容：</th>
     <td>
     <textarea id='tplContent' style='width:70%;height:100px;' class='ipt' maxLength='150'><?php echo $object['tplContent']; ?></textarea>
     </td>
  </tr>
   <tr>
	     <th>是否开启：</th>
	     <td>
	     <input type="checkbox" <?php if($object['status']==1): ?>checked<?php endif; ?> value='1' class="ipt" id="isShow" name="seoMallSwitch" lay-skin="switch" lay-filter="isShow" lay-text="开启|关闭">
	     </td>
	  </tr>
  <tr>
     <th valign="top" style="line-height: 45px;">说明：</th>
     <td id='tplDesc'><?php echo $object['tplDesc']; ?></td>
  </tr>
  <tr>
     <td colspan='2' align='center' class='wst-bottombar' style="padding-left: 125px;">
     	<button type="button" class="btn btn-primary btn-mright" onclick='javascript:save(0,<?php echo $p; ?>)'><i class="fa fa-check"></i>保存</button>
        <button type="button" class="btn" onclick="javascript:location.href='<?php echo Url('admin/templatemsgs/index','p='.$p); ?>'"><i class="fa fa-angle-double-left"></i>返回</button>
     </td>
  </tr>
</table>
</form>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/templatemsgs/templatemsgs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>