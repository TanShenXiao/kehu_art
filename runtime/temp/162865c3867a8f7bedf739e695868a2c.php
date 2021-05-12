<?php /*a:2:{s:89:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\\index.html";i:1602924175;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<title>卖家中心 - <?php echo WSTConf('CONF.mallName'); ?></title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="/static/plugins/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/layui/css/layui.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css" type="text/css" />
<script src="__SHOP__/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>

<link rel="stylesheet" href="__SHOP__/css/skins/skin-blue.min.css"type="text/css"/>
<link rel="stylesheet" href="__SHOP__/css/index.css" type="text/css"/>

<link href="__SHOP__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<?php 
$msgGrant = [];
if(WSTShopGrant('shop/messages/shopMessage'))$msgGrant[] = 'message';
if(WSTShopGrant('shop/orders/waitdelivery'))$msgGrant[] = 'shoporder24';
if(WSTShopGrant('shop/orders/waituserPay'))$msgGrant[] = 'shoporder55';
if(WSTShopGrant('shop/orders/failure'))$msgGrant[] = 'shoporder45';
if(WSTShopGrant('shop/ordercomplains/shopComplain'))$msgGrant[] = 'shoporder25';
if(WSTShopGrant('shop/goods/stockWarnByPage'))$msgGrant[] = 'shoporder54';
 ?>
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>",'RESOURCE_PATH':'<?php echo WSTConf('CONF.resourcePath'); ?>',"SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>",'TIME_TASK':1,"MESSAGE_BOX":"<?php echo WSTShopMessageBox(); ?>",MSG_SHOP_GRANT:'<?php echo implode(',',$msgGrant); ?>'}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__SHOP__/img/ajax-loader.gif"/></div>

<style>body,.wrapper{overflow:hidden;}</style>
<input type='hidden' id='token' value='<?php echo WSTConf("CONF.pwdModulusKey"); ?>'/>
<div class="wrapper">
  <header class="main-header">
    <a href="#" class="logo">
      <span class="logo-mini">重庆艺术大市场</span>
      <span class="logo-lg" style="font-size:22px">重庆艺术大市场</span>
    </a>
    <nav class="navbar navbar-static-top">
      <div class="navbar-custom-menu" style='float:left'>
        <ul class='nav navbar-nav'>
          <li><a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a></li>
          <?php if(is_array($sysMenus) || $sysMenus instanceof \think\Collection || $sysMenus instanceof \think\Paginator): $i = 0; $__LIST__ = $sysMenus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?>
          <li><a href='#' class='top-menu' dataid='<?php echo $top["menuId"]; ?>'><span><?php echo $top['menuName']; ?></span></a></li>
          <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
           <li id='toMsg'>
               <a target='_blank' class="drop-down"style='padding-right: 0px;' href='javascript:void(0)' title='用户信息'><i class='fa fa-bell fa-lg'></i><span class='msg-num'></span></a>
            <div class='j-dorpdown-layer'>
                <?php if(WSTShopGrant('shop/messages/shopMessage')): ?>
                <div id='m-msg'><a href='javascript:void(0)' onclick='WST.redirect(120)'>用户消息<span></span></a></div>
                <?php endif; if(WSTShopGrant('shop/orders/waitdelivery')): ?>
                <div id='m-24'><a href='javascript:void(0)' onclick='WST.redirect(24)'>待发货订单</a><span></span></div>
                <?php endif; if(WSTShopGrant('shop/orders/waituserPay')): ?>
                <div id='m-55'><a href='javascript:void(0)' onclick='WST.redirect(55)'>待付款订单</a><span></span></div>
                <?php endif; if(WSTShopGrant('shop/orders/failure')): ?>
                <div id='m-45'><a href='javascript:void(0)' onclick='WST.redirect(45)'>待退款订单<span></span></a></div>
                <?php endif; if(WSTShopGrant('shop/ordercomplains/shopComplain')): ?>
                <div id='m-25'><a href='javascript:void(0)' onclick='WST.redirect(25)'>投诉订单<span></span></a></div>
                <?php endif; if(WSTShopGrant('shop/goods/stockWarnByPage')): ?>
                <div id='m-54'><a href='javascript:void(0)' onclick='WST.redirect(54)'>库存预警<span></span></a></div>
                <?php endif; ?>
            </div>
          </li>
          <li id='toMall'></li>
          <li id="toUser">
            <div class="image">
              <img src="<?php echo WSTUserPhoto(app('session')->get('WST_USER.userPhoto')); ?>" class="img-circle">
            </div>
            <?php if(app('session')->get('WST_USER.roleName')!=''): ?>
            <p style="float: right;">(<?php echo app('session')->get('WST_USER.roleName'); ?>)</p>
            <?php endif; ?>
            <p style="float: right;margin-left:5px;"><?php echo app('session')->get('WST_USER.loginName'); ?></p>
            <div class='j-dorpdown-layer'>
                <div class='button'>
                  <a target='_blank' href='<?php echo url('home/shops/index','shopId='.session('WST_USER.shopId')); ?>'><i class='fa fa-home'></i><span>我的店铺主页</span></a>
                  <a href='javascript:void(0);' class='j-edit-pass edit-pass'><i class='fa fa-key'></i><span>修改密码</span></a>
                  <a href='javascript:void(0);' class='j-logout logout'><i class='fa fa-power-off'></i><span>退出系统</span></a>
                </div>
            </div>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">
          <?php if(is_array($sysMenus) || $sysMenus instanceof \think\Collection || $sysMenus instanceof \think\Paginator): $key0 = 0; $__LIST__ = $sysMenus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$left0): $mod = ($key0 % 2 );++$key0;if(!empty($left0['list'])): if(is_array($left0['list']) || $left0['list'] instanceof \think\Collection || $left0['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $left0['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$left1): $mod = ($i % 2 );++$i;?>
          <li class="treeview j-menulevel0 j-sysmenu<?php echo $left0['menuId']; ?>"  <?php if($key0>1): ?>style='display:none'<?php endif; ?>>
             <a href="#">
               <i class="fa fa-<?php echo !empty($left1['menuIcon']) ? $left1['menuIcon'] : 'eercast'; ?>"></i> <span><?php echo $left1['menuName']; ?></span>
               <span class="pull-right-container">
                 <i class="fa fa-angle-left pull-right"></i>
               </span>
             </a>
             <?php if(!empty($left1['list'])): ?>
             <ul class="treeview-menu">
                <?php if(is_array($left1['list']) || $left1['list'] instanceof \think\Collection || $left1['list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $left1['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$left2): $mod = ($i % 2 );++$i;?>
                <li><a id="menuItem<?php echo $left2['menuId']; ?>" class='menuItem' href="<?php echo Url($left2['menuUrl']); ?>" dataid='<?php echo $left2['menuId']; ?>'><?php echo $left2['menuName']; ?>
                <?php endforeach; endif; else: echo "" ;endif; ?>
                </a>
             </ul>
             <?php endif; ?>
          </li>
          <?php endforeach; endif; else: echo "" ;endif; ?>
          <?php endif; ?>
          <?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
    </section>
  </aside>
  <div class="content-wrapper">
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href='#' onclick='javascript:location.reload()'><i class='fa fa-map-marker'></i>卖家中心</a></li>
      </ol>
      <button id='toFullSreen' class="fullscreen"><i class="fa fa-arrows-alt"></i></button>
    </section>
    <section class="content-iframe" style="margin:0px;padding:0;height:100%">
      <iframe id='iframe' class="iframe" width="100%" height="100%" src="<?php echo Url('shop/index/main'); ?>" frameborder="0"></iframe>
    </section>
  </div>
</div>
<div id='editPassBox' style='display:none;padding-top:5px;'>
  <form id='editPassFrom' autocomplete="off">
   <table class='wst-form'>
      <tr>
         <th style='width:110px'>原密码：</th>
         <td><input type='password' id='oldPass' name='oldPass' class='ipt' data-rule="原密码: required;" maxLength='16'/></td>
      </tr>
      <tr>
         <th style='width:110px'>新密码：</th>
         <td><input type='password' id='newPass' name='newPass' class='ipt' data-rule="新密码: required;length[6~]" maxLength='16'/></td>
      </tr>
      <tr>
         <th style='width:110px'>确认密码：</th>
         <td><input type='password' id='newPass2' name='newPass2' class='ipt' data-rule="确认密码: required;match(newPass);" maxLength='16'/></td>
      </tr>
   </table>
  </form>
</div>
<?php echo hook('shopDocumentListener'); ?>
<script>
var menus = <?php echo json_encode($sysMenus); ?>;
function showImg(opt){
  layer.photos(opt);
}
function showBox(opts){
  return WST.open(opts);
}
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script type="text/javascript" src="/static/js/rsa.js"></script>
<script src="__SHOP__/js/index.js"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>