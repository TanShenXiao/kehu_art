<?php /*a:2:{s:73:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\templatemsgs\list.html";i:1602924250;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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
<style>
body{overflow:hidden;}
</style>

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

<div class="layui-tab layui-tab-brief " lay-filter="msgTab">
	<ul class="layui-tab-title">
	  <li <?php if($src==0): ?>class="layui-this"<?php endif; ?> onclick="initGridMsg(0)">消息模板</li>
	  <li <?php if($src==1): ?>class="layui-this"<?php endif; ?> onclick="initGridEmail(0)">邮件模板</li>
	  <li <?php if($src==2): ?>class="layui-this"<?php endif; ?> onclick="initGridSMS(0)">短信模板</li>
	</ul>
	<div class="layui-tab-content " style="padding: 0px 0;">
	 	<div id="template_msg" class="layui-tab-item <?php if($src==0): ?>layui-show<?php endif; ?>">
           <div class='wst-grid' style='margin-top:5px'>
			<div id="mmg1" class="mmg1 layui-form"></div>
			<div id="pg1" style="text-align: right;"></div>
		  </div>
        </div>
	 	<div id="template_email" class="layui-tab-item <?php if($src==1): ?>layui-show<?php endif; ?>">
           <div class='wst-grid' style='margin-top:5px'>
			<div id="mmg2" class="mmg2 layui-form"></div>
			<div id="pg2" style="text-align: right;"></div>
		  </div>
        </div>
	 	<div id="template_sms" class="layui-tab-item <?php if($src==2): ?>layui-show<?php endif; ?>">
	 	   <div id='alertTips' class='alert alert-success alert-tips fade in'>
		   <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明</div>
		   <ul class='body'>
		    <li>本功能主要用于管理短信发送的模板格式。</li>
		    <li>若短信服务商未要求预先定义模板，则发送时以本系统模板为主（例如中国网建）。</li>
		    <li>若短信服务商要求必须预先定义模板的(例如阿里云-云通信)，则本系统中的模板仅作为建模参考，发送格式以短信服务商上的模板为主。</li>
		   </ul>
		  </div>
		  <div class='wst-grid'>
			<div id="mmg3" class="mmg3 layui-form"></div>
			<div id="pg3" style="text-align: right;"></div>
		  </div>
        </div>
    </div>
</div>
<script>
	$(function(){
		h = WST.pageHeight();
		<?php if($src==1): ?>
			initGridEmail(<?php echo $p; ?>);
		<?php elseif($src==2): ?>
			initGridSMS(<?php echo $p; ?>);
		<?php else: ?>
			initGridMsg(<?php echo $p; ?>);
		<?php endif; ?>
            var isInit1 = false;
            var isInit2 = false;
            var isInit3 = false;
            var element = layui.element;
            element.on('tab(msgTab)', function(data){
                if(data.index==0){
                    if(!isInit1){
                        isInit1 = true;
                        initGridMsg(<?php echo $p; ?>);
                    }
                }else if(data.index==1){
                    if(!isInit2){
                        isInit2 = true;
                        initGridEmail(<?php echo $p; ?>);
                    }
                }else if(data.index==2){
                    if(!isInit3){
                        isInit3 = true;
                        initGridSMS(<?php echo $p; ?>);

                    }
                }
            });


	});
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/templatemsgs/templatemsgs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>