<?php /*a:2:{s:64:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/login.html";i:1579267118;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<title>登录 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/user.css?v=<?php echo $v; ?>">
<link rel="stylesheet"  href="/static/plugins/layer/layer.js?v=<?php echo $v; ?>">
<style>body{background:#fff}</style>

<script type='text/javascript' src='__MOBILE__/frozenui/js/zepto.min.js'></script>
<script type='text/javascript' src='__MOBILE__/frozenui/js/frozen.js'></script>
<script type='text/javascript' src='__MOBILE__/js/laytpl/laytpl.js?v=<?php echo $v; ?>'></script>
<script src="__MOBILE__/js/echo.min.js"></script>
<script type='text/javascript' src='__MOBILE__/js/common.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__MOBILE__/js/searchlist.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"","MOBILE":"__MOBILE__","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPTPWD":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>",HTTP:"<?php echo WSTProtocol(); ?>",'RESOURCE_PATH':'<?php echo WSTConf('CONF.resourcePath'); ?>'}
</script>
</head>
<body ontouchstart="">

		<?php $mobileLoginTypeArr = explode(',',WSTConf('CONF.mobileLoginType')); ?>
       <header class="ui-header ui-header-positive wst-header">
	    <i class="ui-icon-return" onclick="history.back()"></i>
	    <ul class="ui-tab-nav">
			<?php if(in_array(2,$mobileLoginTypeArr)): ?><li class="switch active" onclick="javascript:pageSwitch(this,2);">手机登录</li><?php endif; if(in_array(1,$mobileLoginTypeArr)): ?><li class="switch" onclick="javascript:pageSwitch(this,1);">账号登录</li><?php endif; ?>
    	</ul>
    </header>


      
      <input type="hidden" value="<?php echo WSTConf('CONF.pwdModulusKey'); ?>" id="key" autocomplete="off">
		<?php if(in_array(2,$mobileLoginTypeArr)): ?>
      <section class="ui-container" id="login2">
      	 <div class="wst-lo-frame">
			<div class="frame"><input id="regName" type="number" placeholder="手机号" maxlength="11"></div>
			<?php if((WSTConf('CONF.smsVerfy')==1)): ?>
			<div class="verify phone">
				<input id="smsVerfy" type="text" placeholder="输入验证码" maxlength="10">
				<img id='verifyImg3' src="<?php echo url('mobile/users/getVerify'); ?>" onclick='javascript:WST.getVerify("#verifyImg3")'>
			</div>
			<?php endif; ?>
			<div class="verify phone">
				<input id="phoneCode" type="text" placeholder="输入短信验证码" maxlength="8">
				<button id="obtain" class="ui-btn ui-btn-primary" onclick="javascript:obtainCode(2)">获取验证码</button>
			</div>
    	</div>
    	<div class="wst-lo-button">
			<button id="loginButton2" class="button" onclick="javascript:login2();">登录</button>
		</div>
		<?php echo hook('mobileDocumentLogin'); ?>
      </section>
	  <?php endif; if(in_array(1,$mobileLoginTypeArr)): ?>
      <section class="ui-container" id="login1" <?php if(in_array(2,$mobileLoginTypeArr)): ?>style="display: none;"<?php endif; ?>>
      	 <div class="wst-lo-frame">
			<div class="frame"><input id="loginName" type="text" placeholder="邮箱/用户名"></div>
			<div class="frame"><input id="loginPwd" type="password" placeholder="密码"></div>
			<div class="verify">
				<input id="loginVerfy" type="text" placeholder="输入验证码" maxlength="10">
				<img id='verifyImg1' src="<?php echo url('mobile/users/getVerify'); ?>" onclick='javascript:WST.getVerify("#verifyImg1")'>
			</div>
    	</div>
    	<div class="wst-lo-button">
			<button id="loginButton" class="button" onclick="javascript:login();">登录</button>
		</div>
		<?php echo hook('mobileDocumentLogin'); ?>
      </section>
      <?php endif; ?>
      <ul class="ui-row wst-lo-term">
		    <li class="ui-col ui-col-50"><a href="<?php echo url('mobile/users/toRegister'); ?>" class="term">注册新账号</a></li>
		    <li class="ui-col ui-col-50" style="text-align:right;"><a href="<?php echo url('mobile/users/forgetpass'); ?>" class="term">忘记密码</a></li>
		</ul>
      


		
		<div class="ui-loading-block" id="Loadl">
		    <div class="ui-loading-cnt">
		        <i class="ui-loading-bright"></i>
		        <p id="j-Loadl">正在加载中...</p>
		    </div>
		</div>



<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type="text/javascript" src="/static/js/rsa.js"></script>
<script type='text/javascript' src='__MOBILE__/js/login.js?v=<?php echo $v; ?>'></script>
<script type="text/javascript">
	function pageSwitch(obj,type){
	$(obj).addClass('active').siblings('.ui-tab-nav li.switch').removeClass('active');
	$('#login'+type).show().siblings('section.ui-container').hide();
}
</script>

</body>
</html>