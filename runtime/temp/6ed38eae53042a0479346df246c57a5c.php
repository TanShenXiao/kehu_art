<?php /*a:2:{s:67:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\staffs\edit.html";i:1602924248;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<form autocomplete='off'> 
<input type='hidden' id='staffId' class='ipt' value="<?php echo $object['staffId']; ?>"/>
<table class='wst-form wst-box-top'>
  <tr>
     <th width='150'>登录账号：</th>
     <td><?php echo $object['loginName']; ?></td>
  </tr>
  <tr>
    <th>职员头像：</th>
    <td>
       <input type="text" readonly="readonly"  id='staffPhoto' class='ipt' value='<?php echo $object["staffPhoto"]; ?>' style="float: left;width: 355px;"/>
       <div id='photoPicker'>上传<span id='uploadMsg'></span></div>
       <div style='float: left;margin-left: 5px;'>
           <img id='prevwPhoto' height='30' src='__RESOURCE_PATH__/<?php if($object["staffPhoto"] != ''): ?><?php echo $object["staffPhoto"]; else: ?>__ADMIN__/img/img_mrtx_gly.png<?php endif; ?>'/>
       </div>
    </td>
  </tr>
  <tr>
     <th>职员名称<font color='red'>*</font>：</th>
     <td><input type="text" id='staffName' class='ipt' value="<?php echo $object['staffName']; ?>" maxLength='20' data-rule="职员名称: required;"/></td>
  </tr>
  <tr>
     <th>职员编号：</th>
     <td><input type="text" id='staffNo' class='ipt' value="<?php echo $object['staffNo']; ?>" maxLength='20'/></td>
  </tr>
  <tr>
     <th>角色：</th>
     <td>
     <select id='staffRoleId' class='ipt'>
        <option value='0'>请选择</option>
        <?php if(is_array($roles) || $roles instanceof \think\Collection || $roles instanceof \think\Paginator): $i = 0; $__LIST__ = $roles;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
		<option value="<?php echo $vo['roleId']; ?>" <?php if($object['staffRoleId'] == $vo['roleId']): ?>selected<?php endif; ?>><?php echo $vo['roleName']; ?></option>
		<?php endforeach; endif; else: echo "" ;endif; ?>
     </select>
     </td>
  </tr>
  <tr>
     <th>手机号码：</th>
     <td><input type="text" id='staffPhone' class='ipt'  maxLength='100' data-rule="mobile" value="<?php echo $object['staffPhone']; ?>"/></td>
  </tr>
  <tr>
     <th>微信OPENID：</th>
     <td><input type="text" id='wxOpenId' class='ipt'  maxLength='100' value="<?php echo $object['wxOpenId']; ?>"/></td>
  </tr>
  <tr>
     <th>工作状态：</th>
     <td class="layui-form">
       <label>
           <input id="workStatus1" name="workStatus" value="1" class='ipt' <?php if($object['workStatus'] == 1): ?>checked<?php endif; ?> type="radio" title='在职'>
       </label>
       <label>
           <input id="workStatus0" name="workStatus" value="0" class='ipt' <?php if($object['workStatus'] == 0): ?>checked<?php endif; ?> type="radio" title='离职'>
       </label>
             
     </td>
  </tr>
  <tr>
     <th>账号状态：</th>
     <td colspan='2' class="layui-form">
       <label>
          <input type='radio' id='staffStatus1' class='ipt' name='staffStatus' <?php if($object['staffStatus'] == 1): ?>checked<?php endif; ?> value='1' title='开启'>
       </label>
       <label>
          <input type='radio' id='staffStatus0' class='ipt' name='staffStatus' <?php if($object['staffStatus'] == 0): ?>checked<?php endif; ?> value='0' title='停用'>
       </label>
     </td>
  </tr>
  <tr>
     <td colspan='3' align='center' class='wst-bottombar'>
     	 <button type="button" onclick='javascript:save(<?php echo $p; ?>)' class='btn btn-primary btn-mright'><i class="fa fa-check"></i>保存</button>
       <button type="button" onclick="javascript:location.href='<?php echo Url('admin/staffs/index','p='.$p); ?>'" class='btn'><i class="fa fa-angle-double-left"></i>返回</button>
     </td>
  </tr>
</table>
</form>
<script>
$(function(){
	WST.upload({
  	  pick:'#photoPicker',
  	  formData: {dir:'staffs'},
  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
  	  callback:function(f){
  		  var json = WST.toAdminJson(f);
  		  if(json.status==1){
  			$('#uploadMsg').empty().hide();
  			$('#prevwPhoto').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.name);
  			$('#staffPhoto').val(json.savePath+json.name);
  		  }
	  },
	  progress:function(rate){
	      $('#uploadMsg').show().html('已上传'+rate+"%");
	  }
    });
});
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>' type="text/javascript"></script>
<script src="__ADMIN__/staffs/staffs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>