<?php /*a:2:{s:66:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\roles\edit.html";i:1602924244;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<link href="/static/plugins/ztree/css/zTreeStyle/zTreeStyle.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />

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

<form autocomplete='off'>
<input type='hidden' id='roleId' class='ipt' value="<?php echo $object['roleId']; ?>"/>
<table class='wst-form wst-box-top'>
  <tr>
     <th width='120'>角色名称<font color='red'>*</font></th>
     <td><input type="text" id='roleName' class='ipt' value="<?php echo $object['roleName']; ?>" maxLength='20' data-rule="角色名称: required;"/></td>
  </tr>
  <tr>
     <th>角色备注</th>
     <td><input type="text" id='roleDesc' class='ipt' value="<?php echo $object['roleDesc']; ?>" style='width:70%' maxLength='100'/></td>
  </tr>
  <tr>
     <th valign='top'>权限</th>
     <td>
       <ul id="menuTree" class="ztree"></ul>
     </td>
  </tr>
  <tr>
     <td colspan='2' align='center' class='wst-bottombar'>
     	 <button type="button" onclick='javascript:save(<?php echo $p; ?>)' class='btn btn-primary btn-mright'><i class="fa fa-check"></i>保存</button>
       <button type="button" onclick="javascript:location.href='<?php echo Url('admin/roles/index','p='.$p); ?>'" class='btn'><i class="fa fa-angle-double-left"></i>返回</button>
     </td>
  </tr>
</table>
</form>
<script>
var zTree,rolePrivileges = '<?php echo $object['privileges']; ?>'.split(',');
$(function(){
	var roleId = $('#roleId').val();
	var setting = {
		    check: {
				enable: true
			},
		    async: {
		        enable: true,
		        url:WST.U('admin/privileges/listQueryByRole'),
		        autoParam:["id", "name=n", "level=lv"],
		        otherParam:["roleId",roleId]
		    },
		    callback:{
		    	onNodeCreated:getNodes
		    }
	};
	$.fn.zTree.init($("#menuTree"), setting);
	zTree = $.fn.zTree.getZTreeObj("menuTree");
})
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/ztree/jquery.ztree.all-3.5.js?v=<?php echo $v; ?>"></script>
<script src="__ADMIN__/roles/roles.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>