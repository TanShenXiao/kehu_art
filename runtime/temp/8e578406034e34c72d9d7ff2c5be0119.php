<?php /*a:2:{s:71:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\sysconfigs\sign.html";i:1602924250;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<style>.scoreTbl td{padding-right:45px;}</style>
<form autocomplete='off'>
	<table class='wst-form wst-box-top'>
	   <tr>
	     <th width='150'>开启积分签到：</th>
	     <td class='layui-form'>
	     <input type="checkbox" <?php if($object['signScoreSwitch']==1): ?>checked<?php endif; ?> class="ipt" id="signScoreSwitch" name="signScoreSwitch" value='1' lay-skin="switch" lay-filter="signScoreSwitch" lay-text="开|关">
	     </td>
	  </tr>
	  <tr class="signScoreBox" <?php if($object['signScoreSwitch']==0): ?>style="display:none;"<?php endif; ?>>
	     <th valign="top" style='padding-top:15px'>累计签到获得的积分：</th>
     	 <td id="signScores">
	     	<table class='scoreTbl'><tbody>
	     	<tr>
	     	<th>第1天：</th><td><input type="text" id='signScore0' class='ipt' value="<?php echo $object['signScore0']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第2天：</th><td><input type="text" id='signScore1' class='ipt' value="<?php echo $object['signScore1']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第3天：</th><td><input type="text" id='signScore2' class='ipt' value="<?php echo $object['signScore2']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第4天：</th><td><input type="text" id='signScore3' class='ipt' value="<?php echo $object['signScore3']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第5天：</th><td><input type="text" id='signScore4' class='ipt' value="<?php echo $object['signScore4']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr>
	     	<th>第6天：</th><td><input type="text" id='signScore5' class='ipt' value="<?php echo $object['signScore5']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第7天：</th><td><input type="text" id='signScore6' class='ipt' value="<?php echo $object['signScore6']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第8天：</th><td><input type="text" id='signScore7' class='ipt' value="<?php echo $object['signScore7']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第9天：</th><td><input type="text" id='signScore8' class='ipt' value="<?php echo $object['signScore8']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第10天：</th><td><input type="text" id='signScore9' class='ipt' value="<?php echo $object['signScore9']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr>
	     	<th>第11天：</th><td><input type="text" id='signScore10' class='ipt' value="<?php echo $object['signScore10']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第12天：</th><td><input type="text" id='signScore11' class='ipt' value="<?php echo $object['signScore11']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第13天：</th><td><input type="text" id='signScore12' class='ipt' value="<?php echo $object['signScore12']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第14天：</th><td><input type="text" id='signScore13' class='ipt' value="<?php echo $object['signScore13']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第15天：</th><td><input type="text" id='signScore14' class='ipt' value="<?php echo $object['signScore14']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr>
	     	<th>第16天：</th><td><input type="text" id='signScore15' class='ipt' value="<?php echo $object['signScore15']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第17天：</th><td><input type="text" id='signScore16' class='ipt' value="<?php echo $object['signScore16']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第18天：</th><td><input type="text" id='signScore17' class='ipt' value="<?php echo $object['signScore17']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第19天：</th><td><input type="text" id='signScore18' class='ipt' value="<?php echo $object['signScore18']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第20天：</th><td><input type="text" id='signScore19' class='ipt' value="<?php echo $object['signScore19']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr>
	     	<th>第21天：</th><td><input type="text" id='signScore20' class='ipt' value="<?php echo $object['signScore20']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第22天：</th><td><input type="text" id='signScore21' class='ipt' value="<?php echo $object['signScore21']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第23天：</th><td><input type="text" id='signScore22' class='ipt' value="<?php echo $object['signScore22']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第24天：</th><td><input type="text" id='signScore23' class='ipt' value="<?php echo $object['signScore23']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第25天：</th><td><input type="text" id='signScore24' class='ipt' value="<?php echo $object['signScore24']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr>
	     	<th>第26天：</th><td><input type="text" id='signScore25' class='ipt' value="<?php echo $object['signScore25']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第27天：</th><td><input type="text" id='signScore26' class='ipt' value="<?php echo $object['signScore26']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第28天：</th><td><input type="text" id='signScore27' class='ipt' value="<?php echo $object['signScore27']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第29天：</th><td><input type="text" id='signScore28' class='ipt' value="<?php echo $object['signScore28']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	<th>第30天：</th><td><input type="text" id='signScore29' class='ipt' value="<?php echo $object['signScore29']; ?>" maxLength='5' style='width:40px;' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberdoteKey(event)"/></td>
	     	</tr>
	     	<tr><span style='color:gray;'>(单位（个），必须第1天大于0才能签到，填写为空则为0;填写第1天为0则保存所有为0;填写中间位或最后位为零则取前一天积分，类推取得不为0的积分)</tr>
	     	</tbody></table>
	     </td>
	  </tr>
	  <?php if(WSTGrant('QDSZ_02')): ?>
	  <tr>
	     <td colspan='2' align='center'>
	       <button type="button" onclick='javascript:edit()' class='btn btn-primary btn-mright'><i class="fa fa-check"></i>保存</button>
            <button type="reset"  class='btn'><i class="fa fa-refresh"></i>重置</button>
	     </td>
	  </tr>
	  <?php endif; ?>
	 </table>
</form>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/sysconfigs/sign.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>