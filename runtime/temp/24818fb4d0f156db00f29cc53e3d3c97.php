<?php /*a:1:{s:89:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\\login.html";i:1602924175;}*/ ?>
<!DOCTYpE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-Ua-Compatible" content="IE=edge">
<meta name="Keywords" content=""/>
<meta name="Description" content=""/> 
<link rel="stylesheet" href="/static/plugins/layui/css/layui.css" type="text/css" />
<link href="/static/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link href="__SHOP__/css/login.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<title>卖家中心登录 - <?php echo WSTConf('CONF.mallName'); ?></title>
</head>
<body id="loginFrame">
<div class="wst-lo-top">
  <div class='login_logo'>
      <img src="__RESOURCE_PATH__/<?php echo WSTConf('CONF.mallLogo'); ?>">
  </div>
</div>
<?php  $adsRs = WSTAds('ads-login-shop',1,31536000);?>
<div class="wst-lo-center" <?php if(isset($adsRs['adFile'])): ?>style='background: url(__RESOURCE_PATH__/<?php echo $adsRs["adFile"]; ?>) no-repeat top center;background-size:center;min-width: 1300px;'<?php endif; ?>>
  <div class="wst-lo">
    <div class="login-wrapper">
      <div class="boxbg2"></div>
      <div class="box">
          <form method="post" autocomplete="off">
          <div class="content-wrap">
            <div class='login-title'>卖家中心登录</div>
            <div class="login-box">
              <div>
                 <div class="login-icon1"></div>
                 <input id='loginName' type="text" class="layui-input ipt" placeholder="邮箱/用户名/手机号">
              </div>
              <div>
                 <div class="login-icon2"></div>
                 <input id='loginPwd' type="password" class="layui-input ipt" placeholder="登录密码">
              </div>
              <div class="frame">
                <div class="login-icon3"></div>
                <input type='text' id='verifyCode' class='layui-input  ipt text2' placeholder="验证码">
                <img id='verifyImg' src="<?php echo url('admin/index/getVerify'); ?>" onclick='javascript:getVerify(this)'>
              </div>
            </div>
            <div>
            <button id="loginbtn" type="button" onclick='javascript:login()' class="layui-btn layui-btn-big layui-btn-normal" style="width: 100%;">登&nbsp;&nbsp;&nbsp;&nbsp;录</button>
            </div>
          </div>
          </form>
        </div>
    </div>
</div>
</div>
<div class="login-footer">
      <div class="wst-footer-flink">
      <div class="wst-footer" >
    
        <div class="wst-footer-cld-box">
          <div style="text-align:center;">
            <span class="wst-footer-fl" style="margin-right: 30px;">友情链接</span>
            <?php $wstTagFriendlink =  model("common/Tags")->listFriendlink(99,86400); foreach($wstTagFriendlink as $key=>$vo){?>
            <a class="flink-hover" href="<?php echo $vo['friendlinkUrl']; ?>"  target="_blank"><?php echo $vo["friendlinkName"]; ?></a>
            <?php } ?>
            <div class="wst-clear"></div>
          </div>
        </div>
    
      </div>
    </div>
     <?php 
          if(WSTConf('CONF.mallFooter')!=''){
              echo htmlspecialchars_decode(WSTConf('CONF.mallFooter'));
            }
      
        if(WSTConf('CONF.visitStatistics')!=''){
          echo htmlspecialchars_decode(WSTConf('CONF.visitStatistics'))."<br/>";
          }
      if(WSTConf('CONF.mallLicense') == ''): ?>
     <div>
        Copyright©2019 Powered By <a target="_blank" href="http://www.wstmart.net">WSTMart</a>
     </div>
     <?php else: ?>
        <div id="wst-mallLicense" data='1' style="display:none;">Copyright©2016 Powered By <a target="_blank" href="http://www.wstmart.net">WSTMart</a></div>
     <?php endif; ?>
</div>
<input type='hidden' id='token' value='<?php echo WSTConf("CONF.pwdModulusKey"); ?>'/>
<script type='text/javascript' src='/static/js/jquery.min.js'></script>
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>"}
</script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script type='text/javascript' src='/static/js/common.js'></script>
<script type="text/javascript" src="/static/js/rsa.js"></script>
<script type='text/javascript' src='__SHOP__/js/common.js'></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>
<script src="__SHOP__/js/login.js?v=<?php echo $v; ?>" type="text/javascript"></script>
</body>
</html>