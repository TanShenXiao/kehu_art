<?php /*a:2:{s:80:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\admin\view\main.html";i:1602924237;s:80:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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
body{ background: #f5f5f5; margin-left: 10px;}
.small-box{margin-right: 5%;border-radius: 2px;position: relative;display: block; margin-bottom: 20px;box-shadow: 0 1px 1px rgba(0,0,0,0.1);}
.small-box>.inner {padding: 10px;color: #fff !important;}
.small-box h3, .small-box p {z-index: 5;}
.small-box h3 {font-size: 38px; font-weight: bold;margin: 0 0 10px 0;white-space: nowrap;padding: 0;}
.small-box h3, .small-box p {z-index: 5;}
.small-box p {font-size: 15px;}
.small-box .icon {-webkit-transition: all .3s linear;-o-transition: all .3s linear;transition: all .3s linear;position: absolute;top: 10px;right: 20px;z-index: 0;font-size: 60px;color: rgba(0,0,0,0.15);}
.small-box:hover .icon {font-size: 70px;}
.wst-strip .progress{height:25px;width:70%;float:left}
.alert-dismissable .close, .alert-dismissible .close{right: 1px;}
</style>
<div id='wstmart-version-tips' class="alert alert-warning alert-dismissible wst-repair layui-col-xs12" role="alert" style='width: 99%;background: #fff3cd;display:none;margin-bottom:0px;color:#856404; border-bottom: 1px solid #ddd;border-radius: 2px;margin-top: 10px;'>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong style='color:#856404'>更新提示：　</strong>您有新的版本(<span id='wstmart_version'>0.0.0</span>)可以下载啦~，请联系客服<a href="tencent://message/?uin=153289970&Site=QQ交谈&Menu=yes">
					  <img border="0" style='vertical-align:middle' src="<?php echo WSTProtocol(); ?>wpa.qq.com/pa?p=1:153289970:7" alt="QQ交谈" width="60" height="20" />
				  </a>获取
</div>
<div id='wstmart-accredit-tips' class="alert alert-warning alert-dismissible wst-repair layui-col-xs12" role="alert" style="width: 99%;background: #fff3cd;color: #856404; display:none;margin-bottom:0px; border-bottom: 1px solid #ddd;border-radius: 2px;margin-top: 10px;">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong style='color:#856404'>授权提示：　</strong>系统检测到您未获取授权，点此<a target='_blank' href='http://www.wstmart.net/index.php?c=License&a=index' style="color: #333;margin-left: 5px;text-decoration: underline;">获取系统授权码</a>
</div>
	<div class="wst-total wst-summary layui-col-md12">
		<?php if(WSTGrant('DDLB_00')): ?>
		<div class='inner'>
			 	<div class="inner_left"><img src="__ADMIN__/img/main/001.png"></div>
			 	<div class="inner_right"><span><?php echo $object['sale']['today']; ?></span>&nbsp;昨日：<?php echo $object['sale']['yesterday']; ?><br/>
			 		今日销量(元)</div>
		</div>
        <div class='inner'>
        	    <div class="inner_left"><img src="__ADMIN__/img/main/002.png"></div>
			 	<div class="inner_right">
			 		<span><?php echo $object['order']['today']; ?></span>&nbsp;昨日：<?php echo $object['order']['yesterday']; ?><br/>
			 		今日订单(笔)</div>
	    </div>
	    <?php endif; if(WSTGrant('DPGL_00')): ?>
	    <div class='inner'>
	    	    <div class="inner_left"><img src="__ADMIN__/img/main/003.png"></div>
			 	<div class="inner_right">
			 		<span><?php echo $object['shop']['today']; ?></span>&nbsp;昨日：<?php echo $object['shop']['yesterday']; ?><br/>
			 		新增店铺(个)</div>
		</div>
		<?php endif; if(WSTGrant('HYGL_00')): ?>
		<div class='inner'>
			    <div class="inner_left"><img src="__ADMIN__/img/main/004.png"></div>
			 	<div class="inner_right">
			 		<span><?php echo $object['user']['today']; ?></span>&nbsp;昨日：<?php echo $object['user']['yesterday']; ?><br/>
			 		新增会员(个)</div>
		</div>
		<?php endif; if(WSTGrant('TKDD_00')): ?>
		<div class='inner'>
			    <div class="inner_left"><img src="__ADMIN__/img/main/005.png"></div>
			 	<div class="inner_right">
			 		<span><?php echo $object['refund']['today']; ?></span>&nbsp;昨日：<?php echo $object['refund']['yesterday']; ?><br/>
			 		退款订单(个)</div>
		</div>
		<?php endif; ?>
	</div>
<div class='layui-col-md12'>
    <div class='layui-col-md7'>
		<div class="wst-total wst-summary zytx">
			<div class='wst-summary-head'>
				<i class="yuan"></i>
				<span class="content">重要提醒</span>
			</div>
			<ul class="wst-total-ul">
				<?php if(WSTGrant('DPSQ_00')): ?>
				<li>
					<div class='icon'>
				 		<span><a href="<?php echo Url('admin/shops/apply'); ?>" id="menuItem45" dataid="45"><?php echo $object['tips']['shopAudit']; ?></a></span>
				 	</div>
				 	<div class="txt"><a href="<?php echo Url('admin/shops/apply'); ?>" id="menuItem45" dataid="45">
				 		<p>店铺审核</p>
				 	</a></div>
				</li>
				<?php endif; if(WSTGrant('DSHSP_00')): ?>
				<li>
					<div class='icon'>
				 		<span><a id="menuItem54" class="menuItem" href="<?php echo Url('admin/goods/auditindex'); ?>" dataid="54"><?php echo $object['tips']['goodsAudit']; ?></a></span>
				 	</div>
				 	<div class="txt">
				 		<a id="menuItem54" class="menuItem" href="<?php echo Url('admin/goods/auditindex'); ?>" dataid="54">
				 			<p>商品审核</p>
				 		</a>
				 	</div>
				</li>
				<?php endif; if(WSTGrant('TXSQ_00')): ?>
				<li>
					<div class='icon'>
				 		<span><a id="menuItem63" class="menuItem" href="<?php echo Url('admin/cashdraws/index'); ?>" dataid="63"><?php echo $object['tips']['cashDraw']; ?></a></span>
				 	</div>
				 	<div class="txt">
				 		<a id="menuItem63" class="menuItem" href="<?php echo Url('admin/cashdraws/index'); ?>" dataid="63">
				 			<p>提现审核</p>
				 		</a>
				 		
				 	</div>
			    </li>
			    <?php endif; if(WSTGrant('TKDD_00')): ?>
			    <li>
					<div class='icon'>
				 		<span><a id="menuItem63" class="menuItem" href="<?php echo Url('admin/orderrefunds/refund'); ?>" ><?php echo $object['tips']['refund']; ?></a></span>
				 	</div>
				 	<div class="txt">
				 		<a id="menuItem52" class="menuItem" href="<?php echo Url('admin/orderrefunds/refund'); ?>">
				 			<p>退款审核</p>
				 		</a>
				 		
				 	</div>
				</li>
				<?php endif; if(WSTGrant('TSDD_00')): ?>
			    <li>
					<div class='icon'>
				 		<span><a id="menuItem63" class="menuItem" href="<?php echo Url('admin/ordercomplains/index'); ?>" dataid="63"><?php echo $object['tips']['complains']; ?></a></span>
				 	</div>
				 	<div class="txt">
				 		<a id="menuItem51" class="menuItem" href="<?php echo Url('admin/ordercomplains/index'); ?>" dataid="51">
				 			<p>订单投诉</p>
				 		</a>
				 		
				 	</div>
				</li>
				<?php endif; if(WSTGrant('JBSP_00')): ?>
			    <li>
					<div class='icon'>
				 		<span><a id="menuItem63" class="menuItem" href="<?php echo Url('admin/informs/index'); ?>" dataid="63"><?php echo $object['tips']['informs']; ?></a></span>
				 	</div>
				 	<div class="txt">
				 		<a id="menuItem188" class="menuItem" href="<?php echo Url('admin/informs/index'); ?>" dataid="188">
				 			<p>商品举报</p>
				 		</a>
				 	</div>
			    </li>
			    <?php endif; ?>
			</ul>
		</div>
		<div class="wst-total wst-summary zytx" >
			<div class='wst-summary-head'>
				<span class="content"><i class="yuan"></i>活动审核</span>
			</div>
			<ul class="wst-total-ul">
				<?php echo hook('adminDocumentHookSummary'); ?>
			</ul>
		</div>
    </div>
    <?php if(WSTGrant('REPORTS_01')): ?>
	<div class="wst-total wst-summary paihang">
		<div class='wst-summary-head'>
			<span class="content"><i class="yuan"></i>最近30天商品销售排行</span>
		</div>
		<script id="goodsjs" type="text/html">
        {{# for(var i = 0; i < d.length; i++){ if(i>5){break;}  }}
        <tr>
         <td style="text-align: center;" class="th0{{i+1}}">{{i+1}}</td>
         <td><p class="thname"><a target='_blank' href='{{WST.U('home/goods/detail','goodsId='+d[i]['goodsId'])}}'>{{d[i]['goodsName']}}</a></p></td>
         <td class="th0{{i+1}}">{{d[i]['goodsNum']}}</td>
        </tr>
        {{# } }}
        </script>
		<table>
          <tr style="background: #1890ff1a;"><td width='15%' style="text-align: center;">排行</td><td width='70%'>商品</td><td width='15%'>销量</td></tr>
          <tbody id='goodsList'></tbody>
		</table>
	</div>
	<script>$(function(){loadGoodsStat();})</script>
	<?php endif; ?>
</div>
<?php if(WSTGrant('REPORTS_04')): ?>
<div class="wst-total wst-summary zytx" style="width: 59.3%;float: left;height: 400px;">
  <div class='wst-summary-head layui-col-md12'>
   <span class="content"><i class="yuan"></i>最近1个月订单数</span>
   <div id="main1" style='width:100%;height:350px;'></div>
  </div>
</div>
<script>$(function(){loadStat1();})</script>
<?php endif; if(WSTGrant('REPORTS_05')): ?>
<div class="wst-total wst-summary zytx" style="width: 39.5%;float: left; height: 400px;margin-left: 10px;">
 <div class='wst-summary-head layui-col-md12'>
   <span class="content"><i class="yuan"></i>最近1个月新增会员</span>
   <div id="main" style='width:100%;height:350px;'></div>
 </div>
</div>
<script>$(function(){loadStat();})</script>
<?php endif; ?>
<div class="wst-total wst-summary zytx" style="width: 59.3%;float: left; min-height: 180px;">
	<div class='wst-summary-head layui-col-md12'>
				<span class="content"><i class="yuan"></i>商城管理</span>
	</div>
	<div class="sclx">
		 <p>商城首页：<a href="<?php echo WSTDomain(); ?>" target="_blank"><?php echo WSTDomain(); ?></a></p>
		 <p>平台后台：<a href=" <?php echo WSTDomain(); ?>/admin" target="_blank"><?php echo WSTDomain(); ?>/admin</a></p>
		 <p>商家后台：<a href="<?php echo WSTDomain(); ?>/shop" target="_blank"><?php echo WSTDomain(); ?>/shop</a></p>
		 <p>WAP首页：<a href="<?php echo WSTDomain(); ?>/mobile" target="_blank"><?php echo WSTDomain(); ?>/mobile</a></p>
	</div>
</div>
<div class="wst-total wst-summary zytx" style="width: 39.5%;float: left; min-height: 180px;margin-left: 10px; ">
	<div class='wst-summary-head layui-col-md12'>
				<span class="content"><i class="yuan"></i>客户服务</span>
	</div>
	<div class="sclx kfser">
		 <p>客服电话：<a href="tel:020-85289921">020-85289921</a></p>
		 <p>客服QQ：<a href="tencent://message/?uin=153289970&amp;Site=QQ交谈&amp;Menu=yes">153289970</a></p>
		 <p>商淘官网：<a href="https://shangtao.net" target="_blank">https://www.shangtao.net</a></p>
		 <p>知识社区：<a href="http://bbs.shangtao.net" target="_blank">http://bbs.shangtao.net</a></p>
		 <p>交流QQ群：<a >590755485</a></p>
	</div>
</div>

<div class="wst-total wst-summary zytx" style='padding-bottom:40px'>
			<div class='wst-summary-head layui-col-md12'>
				<span class="content"><i class="yuan"></i>系统信息</span>
			</div>
			<div class="wst-system-chunk layui-col-md6 wst-bor-top">
				<div class="wst-system-strip wst-line-top">
					<div class="title"><span>软件版本号：</span></div>
					<div class="text">【授权版】<?php echo WSTConf("CONF.wstVersion"); ?></div>
				</div>
				<div class="wst-system-strip">
					<div class="title"><span>问题反馈：</span></div>
					<div class="text"><a href="http://bbs.shangtao.net" target='_blank'>点击反馈</a></div>
				</div>
				<div class="wst-system-strip">
					<div class="title"><span>服务器操作系统：</span></div>
					<div class="text"><?php echo PHP_OS; ?></div>
				</div>
				<div class="wst-system-strip">
					<div class="title"><span>PHP版本：</span></div>
					<div class="text"><?php echo PHP_VERSION; ?></div>
				</div>
			</div>
			<div class="wst-system-chunk layui-col-md6 wst-bor-top" style="border-right:0px solid transparent;">
				<div class="wst-system-strip wst-line-top">
					<div class="title"><span>授权类型：</span></div>
					<div class="text"><div id='licenseStatus'></div></div>
				</div>
				<div class="wst-system-strip">
					<div class="title"><span>授权码：</span></div>
					<div class="text"><?php echo WSTConf("CONF.mallLicense"); ?></div>
				</div>
				<div class="wst-system-strip">
					<div class="title"><span>WEB服务器：</span></div>
					<div class="text"><?php echo app('request')->server('SERVER_SOFTWARE'); ?></div>
				</div>
				<div class="wst-system-strip">
					<div class="title"><span>MYSQL版本：</span></div>
					<div class="text"><?php echo $object['MySQL_Version']; ?></div>
				</div>
			</div>
</div>
<input type="hidden" id="startDate" name="startDate" class="laydate-icon ipt" maxLength="20" value="<?php echo $object['time']['startDate']; ?>" placeholder='开始日期'/>
<input type="hidden" id="endDate" name="endDate" class="laydate-icon ipt" maxLength="20" value="<?php echo $object['time']['endDate']; ?>" placeholder='结束日期'/>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/echarts/echarts.min.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/js/main.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
function enterLicense(){
	location.href='<?php echo Url("admin/index/enterLicense"); ?>';
}
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>