<?php /*a:2:{s:73:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\sysconfigs\notify.html";i:1602924249;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<style>
td.head-ititle{padding-left:30px;}
.staffName {min-width:120px;padding-right:10px;display:inline-block;}
</style>
<form class="layui-form" action="" lay-filter="example">
     <table class='wst-form wst-box-top layui-form'>
      <tr>
      	<td colspan='2'>
      		   <div id='alertTips' class='alert alert-success alert-tips fade in' style='margin:0px 0px'>
				  <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明</div>
				  <ul class='body'>
				    <li>因微信通和短信通知会在事件发生的时候触发，请勿同时发送给太多人，以免造成提交延时影响用户体验。</li>
				  </ul>
				</div>
      	</td>
      </tr>
      <tr>
	     <td colspan='2' class='head-ititle'>用户下单：</td>
	  </tr>
	  <tr>
	     <th width='150'>提醒方式：</th>
	     <td>
	     	<span class='staffName'>
	     	<input type='checkbox' id='wxSubmitOrderTip' class='ipt' <?php if($object["wxSubmitOrderTip"]==1): ?>checked<?php endif; ?>/>微信提醒
	        </span>
	        <span class='staffName'>
	     	<input type='checkbox' id='smsSubmitOrderTip' class='ipt' <?php if($object["smsSubmitOrderTip"]==1): ?>checked<?php endif; ?>/>短信提醒
	        </span> 
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<span class='staffName'>
	     		<input type='checkbox' class='ipt submitOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["submitOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?>
	     	</span>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>支付订单：</td>
	  </tr>
	  <tr>
	     <th>提醒方式：</th>
	     <td>
	     	<span class='staffName'>
	     		<input type='checkbox' id='wxPayOrderTip' class='ipt' <?php if($object["wxPayOrderTip"]==1): ?>checked<?php endif; ?>/>微信提醒
	     	</span>
	     	<span class='staffName'>
	     		<input type='checkbox' id='smsPayOrderTip' class='ipt' <?php if($object["smsPayOrderTip"]==1): ?>checked<?php endif; ?>/>短信提醒
	     	</span>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<span class='staffName'>
	     		<input type='checkbox' class='ipt payOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["payOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?>
	     	</span>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>取消订单：</td>
	  </tr>
	  <tr>
	     <th>提醒方式：</th>
	     <td>
	     	<span class='staffName'>
	     		<input type='checkbox' id='wxCancelOrderTip' class='ipt' <?php if($object["wxCancelOrderTip"]==1): ?>checked<?php endif; ?>/>微信提醒
	     	</span>
	     	<span class='staffName'>
	     		<input type='checkbox' id='smsCancelOrderTip' class='ipt' <?php if($object["smsCancelOrderTip"]==1): ?>checked<?php endif; ?>/>短信提醒
	     	</span>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<span class='staffName'>
	     		<input type='checkbox' class='ipt cancelOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["cancelOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?>
	     	</span>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>拒收订单：</td>
	  </tr>
	  <tr>
	     <th>提醒方式：</th>
	     <td>
	     	<span class='staffName'>
	     		<input type='checkbox' id='wxRejectOrderTip' class='ipt' <?php if($object["wxRejectOrderTip"]==1): ?>checked<?php endif; ?>/>微信提醒
	     	</span>
	     	<span class='staffName'>
	     		<input type='checkbox' id='smsRejectOrderTip' class='ipt' <?php if($object["smsRejectOrderTip"]==1): ?>checked<?php endif; ?>/>短信提醒
	     	</span>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<span class='staffName'>
	     		<input type='checkbox' class='ipt rejectOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["rejectOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?>
	     	</span>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>申请退款：</td>
	  </tr>
	  <tr>
	     <th>提醒方式：</th>
	     <td>
	     	<span class='staffName'>
	     		<input type='checkbox' id='wxRefundOrderTip' class='ipt' <?php if($object["wxRefundOrderTip"]==1): ?>checked<?php endif; ?>/>微信提醒
	     	</span>
	     	<span class='staffName'>
	     		<input type='checkbox' id='smsRefundOrderTip' class='ipt' <?php if($object["smsRefundOrderTip"]==1): ?>checked<?php endif; ?>/>短信提醒
	     	</span>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<span class='staffName'>
	     		<input type='checkbox' class='ipt refundOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["refundOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?>
	     	</span>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>订单投诉：</td>
	  </tr>
	  <tr>
	     <th>提示方式：</th>
	     <td>
	     	<span class='staffName'>
	     		<input type='checkbox' id='wxComplaintOrderTip' class='ipt' <?php if($object["wxComplaintOrderTip"]==1): ?>checked<?php endif; ?>/>微信提醒
	     	</span>
	     	<span class='staffName'>
	     		<input type='checkbox' id='smsComplaintOrderTip' class='ipt' <?php if($object["smsComplaintOrderTip"]==1): ?>checked<?php endif; ?>/>短信提醒
	     	</span>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<span class='staffName'>
	     		<input type='checkbox' class='ipt complaintOrderTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["complaintOrderTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?>
	     	</span>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	     <td colspan='2' class='head-ititle'>申请提现：</td>
	  </tr>
	  <tr>
	     <th>提醒方式：</th>
	     <td>
	     	<span class='staffName'>
	     		<input type='checkbox' id='wxCashDrawsTip' class='ipt' <?php if($object["wxCashDrawsTip"]==1): ?>checked<?php endif; ?>/>微信提醒
	     	</span>
	     	<span class='staffName'>
	     		<input type='checkbox' id='smsCashDrawsTip' class='ipt' <?php if($object["smsCashDrawsTip"]==1): ?>checked<?php endif; ?>/>短信提醒
	     	</span>
	     </td>
	  </tr>
	  <tr>
	     <th valign="top">提醒人：</th>
	     <td>
	     	<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     	<span class='staffName'><input type='checkbox' class='ipt cashDrawsTipUsers' value='<?php echo $vo['staffId']; ?>' <?php if(in_array($vo['staffId'],$object["cashDrawsTipUsers"])): ?>checked<?php endif; ?>/><?php echo $vo['staffName']; ?></span>
	     	<?php endforeach; endif; else: echo "" ;endif; ?>
	     </td>
	  </tr>
	  <tr>
	  	<?php if(WSTGrant('TZSZ_02')): ?>
	     <td colspan='2' align='center'>
	     	<button type="button" class="btn btn-primary btn-mright" onclick='javascript:edit()'><i class="fa fa-check"></i>保存</button> 
        	<button type="reset" class="btn" ><i class="fa fa-refresh"></i>重置</button>
	     </td>
	     <?php endif; ?>
	  </tr>
	 </table>
</form>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/sysconfigs/notify.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>