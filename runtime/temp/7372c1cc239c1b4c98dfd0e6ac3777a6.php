<?php /*a:2:{s:66:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\users\edit.html";i:1602924251;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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
<form id="userForm" autocomplete="off" class='layui-form'>
<table class='wst-form wst-box-top'>
  <tr>
      <th width='150'>账号<font color='red'>*</font>：</th>
          <td>
            <?php if(($data['userId']>0)): ?>
              <?php echo $data['loginName']; else: ?>
              <input type="text" class="ipt" id="loginName" name="loginName"  />
            <?php endif; ?>
              
          </td>
       </tr>
       <?php if(((int)$data['userId']==0)): ?>
         <tr>
            <th>密码<font color='red'>*</font>：</th>
            <td><input type="password" id='loginPwd' class='ipt' maxLength='20' value='66666666' data-rule="登录密码: required;length[6~20]" data-target="#msg_loginPwd"/>
               <span id='msg_loginPwd'>(默认为66666666)</span>
             </td>
         </tr>
       <?php endif; ?>
       <tr>
          <th>昵称：</th>
          <td>
              <input type="text" class="ipt" id="userName" name="userName" value="<?php echo $data['userName']; ?>" />
          </td>
       </tr>
       <tr>
          <th>真实姓名：</th>
          <td>
              <input type="text" class="ipt" id="trueName" name="trueName" value="<?php echo $data['trueName']; ?>" />
          </td>
       </tr>
       <tr>
         <th>用户头像：</th>
         <td>
           <input type="text" readonly="readonly"  id="userPhoto" class="ipt" value="<?php echo $data['userPhoto']; ?>" style="float: left;width: 355px;"/>
            <div id='adFilePicker'>上传</div>
            <div id="preview" style="float: left;margin-left: 5px;">
                <img src="__RESOURCE_PATH__/<?php if($data['userPhoto']==''): ?><?php echo WSTConf('CONF.userLogo'); else: ?><?php echo $data['userPhoto']; ?><?php endif; ?>"  height="30" />
            </div>
            <span id='uploadMsg'></span>
         </td>
       </tr>
       <tr>
          <th>性别<font color='red'>*</font>：</th>
          <td>
            <label><input type="radio" class="ipt" id="userSex" name="userSex" <?=($data['userSex']==1)?'checked':'';?> value="1" title='男'/></label>
            <label><input type="radio" class="ipt" id="userSex" name="userSex" <?=($data['userSex']==2)?'checked':'';?> value="2" title='女'/></label>
            <label><input type="radio" class="ipt" id="userSex" name="userSex" <?=($data['userSex']==0)?'checked':'';?> value="0" title='保密'/></label>
          </td>
       </tr>
       <tr>
          <th>出生日期：</th>
          <td>
              <input type="text" class="ipt" id="brithday" name="brithday" value="<?php echo $data['brithday']; ?>" />
          </td>
       </tr>
       <tr>
          <th>手机号码：</th>
          <td>
              <input type="text" class="ipt" id="userPhone" name="userPhone" value="<?php echo $data['userPhone']; ?>" />
          </td>
       </tr>
       <tr>
          <th>电子邮箱：</th>
          <td>
              <input type="text" class="ipt" id="userEmail" name="userEmail" value="<?php echo $data['userEmail']; ?>" />
          </td>
       </tr>
       <tr>
          <th>QQ：</th>
          <td>
              <input type="text" class="ipt" id="userQQ" name="userQQ" value="<?php echo $data['userQQ']; ?>" />
          </td>
       </tr>
        <tr>
          <th>是否允许举报<font color='red'>*</font>：</th>
          <td>
            <label><input type="radio" class="ipt" id="isInform" name="isInform" <?=($data['isInform']==1)?'checked':'';?> value="1" title='允许举报商品'/></label>
            <label><input type="radio" class="ipt" id="isInform" name="isInform" <?=($data['isInform']==0)?'checked':'';?> value="0" title='禁止举报商品'/></label>
          </td>
       </tr>
       <?php if(((int)$data['userId']==0)): ?>
         <tr>
            <th>会员状态<font color='red'>*</font>：</th>
            <td>
                <input type="checkbox" style='width:80px;' class="ipt" checked name="userStatus" id='userStatus' lay-skin="switch" title="开关" value='1' lay-text="启用|停用">
            </td>
         </tr>
       <?php endif; ?>
  
  <tr>
     <td colspan='2' align='center'>
       <input type="hidden" name="id" id="userId" class="ipt" value="<?=(int)$data['userId']?>" />
       <button type="submit" class="btn btn-primary btn-mright" ><i class="fa fa-check"></i>提交</button> 
        <button type="button" class="btn" onclick="javascript:location.href='<?php echo Url('admin/users/index','p='.$p); ?>'"><i class="fa fa-angle-double-left"></i>返回</button>
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
<script src="__ADMIN__/users/users.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>