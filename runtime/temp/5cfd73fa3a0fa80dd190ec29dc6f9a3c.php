<?php /*a:9:{s:71:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\sysconfigs\edit.html";i:1602924249;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;s:74:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\sysconfigs\config0.html";i:1602924249;s:74:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\sysconfigs\config1.html";i:1602924249;s:74:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\sysconfigs\config3.html";i:1602924249;s:74:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\sysconfigs\config4.html";i:1602924249;s:74:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\sysconfigs\config5.html";i:1602924249;s:77:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\sysconfigs\config_app.html";i:1602924249;s:74:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\sysconfigs\config6.html";i:1602924249;}*/ ?>
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
<style>
.layui-form-label{width:140px;}
.layui-input-block{  margin-left: 170px;}
#wst-tab-5 input[type="text"]{width:50%}
td{height:40px; }
.login-type{display:inline-block;width:150px}
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

<form autocomplete='off'> 
<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
	   <ul class="layui-tab-title">
	      <li class="layui-this">基础设置</li>
	      <li>服务器设置</li>
		  <!--<li>订单设置</li>-->
	      <li>密匙设置</li>
	      <li>图片设置</li>
		  <li>SEO设置</li>
		  <?php if((WSTConf('CONF.hasApp'))): ?>
		  <li isApp="1">下载页设置</li>
		  <?php endif; ?>
		  <li>登录设置</li>
	   </ul>
	   <div class="layui-tab-content" style="padding: 10px 0;">
			<?php $grant = WSTGrant('SCPZ_02');  ?>
<div class="layui-tab-item layui-show layui-form">
     <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>商城名称：</th>
	     <td><input type="text" id='mallName' class='ipt' value="<?php echo $object['mallName']; ?>" maxLength='100' placeholder='对外的简称'/></td>
	  </tr>
	  <tr>
	     <th width='150'>商城特色介绍：</th>
	     <td><input type="text" id='mallSlogan' class='ipt' style='width:70%' value="<?php echo $object['mallSlogan']; ?>" maxLength='50' placeholder='商城特色短语介绍'/></td>
	  </tr>
	  <tr>
	     <th>商城开关：</th>
	     <td>
	     <input type="checkbox" <?php if($object['seoMallSwitch']==1): ?>checked<?php endif; ?> value='1' class="ipt" id="seoMallSwitch" name="seoMallSwitch" lay-skin="switch" lay-filter="seoMallSwitch" lay-text="开|关">
	     </td>
	  </tr>
	  <tr id="close" <?php if($object['seoMallSwitch']==1): ?>style="display:none;"<?php endif; ?>>
	     <th width='150'>商城关闭原因：</th>
	     <td><input type="text" id='seoMallSwitchDesc' class='ipt' style='width:70%'  value="<?php echo $object['seoMallSwitchDesc']; ?>" maxLength='50' placeholder='原因'/></td>
	  </tr>
	  <tr>
	     <th>商品是否需要审核：</th>
	     <td>
	     <input type="checkbox" <?php if($object['isGoodsVerify']==1): ?>checked<?php endif; ?> class="ipt" value='1' id="isGoodsVerify" name="isGoodsVerify" lay-skin="switch" lay-filter="isGoodsVerify" lay-text="是|否">
	     </td>
	  </tr>
	  <tr>
	     <th>底部设置：</th>
	     <td>
	       <textarea id='mallFooter' class='ipt' placeholder='显示在网站最底部的内容'><?php echo $object['mallFooter']; ?></textarea>
	     </td>
	  </tr>
	  <tr>
	     <th>访问统计：</th>
	     <td><textarea id='visitStatistics' class='ipt' placeholder='用于统计网站访问信息的代码'><?php echo $object['visitStatistics']; ?></textarea></td>
	  </tr>
	  <tr>
	     <th>客服QQ设置：</th>
	     <td><input type="text" id='serviceQQ' class='ipt' value="<?php echo $object['serviceQQ']; ?>" maxLength='200' placeholder='显示在网站的客服QQ号，多个QQ以，号分割'/></td>
	  </tr>
	  <tr>
	     <th>联系电话：</th>
	     <td><input type="text" id='serviceTel' class='ipt' value="<?php echo $object['serviceTel']; ?>" maxLength='200' placeholder="显示在网站的联系电话"/></td>
	  </tr>
	  <tr>
	     <th>联系邮箱：</th>
	     <td><input type="text" id='serviceEmail' class='ipt' value="<?php echo $object['serviceEmail']; ?>" maxLength='200' placeholder="显示在网站的联系邮箱"/></td>
	  </tr>
	  <tr>
	     <th>版权所有：</th>
	     <td><input type="text" id='copyRight' class='ipt' value="<?php echo $object['copyRight']; ?>" maxLength='200' placeholder="显示在网站的版权所有者"/></td>
	  </tr>
	  <tr>
	     <th>热搜关键词：</th>
	     <td><input type="text" id='hotWordsSearch' class='ipt' value="<?php echo $object['hotWordsSearch']; ?>" maxLength='100' placeholder='商城搜索栏下的引导搜索词' style='width:70%'/></td>
	  </tr>
	  <tr>
	     <th>热搜广告词（商品）：</th>
	     <td><input type="text" id='adsGoodsWordsSearch' class='ipt' value="<?php echo $object['adsGoodsWordsSearch']; ?>" maxLength='100' placeholder='商城搜索栏里的搜索词' style='width:70%'/></td>
	  </tr>
	  <tr>
	     <th>热搜广告词（店铺）：</th>
	     <td><input type="text" id='adsShopWordsSearch' class='ipt' value="<?php echo $object['adsShopWordsSearch']; ?>" maxLength='100' placeholder='商城搜索栏里的搜索词' style='width:70%'/></td>
	  </tr>
	  <tr>
	     <th>账号禁用关键字：</th>
	     <td><textarea id='registerLimitWords' class='ipt' placeholder='禁止用户注册时的账号内容'><?php echo $object['registerLimitWords']; ?></textarea></td>
	  </tr>
	  <tr>
	     <th>系统禁用关键字：</th>
	     <td><textarea id='limitWords' class='ipt' placeholder='禁止用户发布的商品、评价内容'><?php echo $object['limitWords']; ?></textarea></td>
	  </tr>

	 <tr>
		 <th>商品审核敏感词：</th>
		 <td><textarea id='sensitiveWords' class='ipt' placeholder='在商品不需要审核的情况下,出现此设置的词会让商品在待审核中'><?php echo $object['sensitiveWords']; ?></textarea></td>
	 </tr>

	  <tr>
	     <th width='150'>腾讯地图密匙</th>
	     <td><input type="text" id='mapKey' class='ipt' style='width:70%' value="<?php echo $object['mapKey']; ?>" maxLength='100' placeholder='腾讯地图密匙'/></td>
	  </tr>
	  <tr>
	     <th width='150'>开启调试模式</th>
	     <td><input type="checkbox" <?php if($object['isDebug']==1): ?>checked<?php endif; ?> class="ipt" value='1' id="isDebug" name="isDebug" lay-skin="switch" lay-filter="isDebug" lay-text="开|关"></td>
	  </tr>
	  <?php if(($grant)): ?>
	  <tr>
	     <td colspan='2' align='center'>
	     	<button type="button" onclick='javascript:edit()' style='margin-right:15px;' class='btn btn-primary btn-mright'><i class="fa fa-check"></i>保存</button>
            <button type="reset"  class='btn'><i class="fa fa-refresh"></i>重置</button>
	     </td>
	  </tr>
	  <?php endif; ?>
	 </table>
</div><div class="layui-tab-item layui-form">
	<?php echo hook('adminDocumentSysConfig',['object'=>$object]); ?>
    <fieldset class="layui-elem-field layui-field-title">
	  <legend>邮件服务器设置</legend>
     <table class='wst-form wst-box-top'>
      <tr>
	     <th width='150'>开启邮件发送：</th>
	     <td>
	     <input type="checkbox" <?php if($object['mailOpen']==1): ?>checked<?php endif; ?> class="ipt" id="mailOpen" value='1' name="mailOpen" value='1' lay-skin="switch" lay-filter="mailOpen" lay-text="开|关">
	     </td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>SMTP服务器：</th>
	     <td><input type="text" id='mailSmtp' class='ipt' maxLength='100' value='<?php echo $object["mailSmtp"]; ?>'/></td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>SMTP端口：</th>
	     <td><input type="text" id='mailPort' class='ipt' maxLength='10' value='<?php echo $object["mailPort"]; ?>'/></td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>是否开启SSL：</th>
	     <td>
	     <input type="checkbox" <?php if($object['mailOpenSSL']==1): ?>checked<?php endif; ?> class="ipt" id="mailOpenSSL" name="mailOpenSSL" value='1' lay-skin="switch" lay-filter="mailOpenSSL" lay-text="是|否">&nbsp;<span style='color:gray;'>例如SMTP端口为465时需开启；</span>
	     </td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>是否验证SMTP：</th>
	     <td>
	     <input type="checkbox" <?php if($object['mailAuth']==1): ?>checked<?php endif; ?> class="ipt" id="mailAuth" name="mailAuth" value='1' lay-skin="switch" lay-filter="mailAuth" lay-text="是|否">
	     </td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>SMTP发件人邮箱：</th>
	     <td><input type="text" id='mailAddress' class='ipt' value='<?php echo $object["mailAddress"]; ?>' maxLength='100'/></td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>SMTP登录账号：</th>
	     <td><input type="text" id='mailUserName' class='ipt' value='<?php echo $object["mailUserName"]; ?>' maxLength='100'/></td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>SMTP登录密码：</th>
	     <td><input type="text" id='mailPassword' class='ipt' value='<?php echo $object["mailPassword"]; ?>' maxLength='100'/></td>
	  </tr>
	  <tr class='mailOpenTr' <?php if($object['mailOpen']!=1): ?>style='display:none'<?php endif; ?>>
	     <th>发件人名称：</th>
	     <td><input type="text" id='mailSendTitle' class='ipt' value='<?php echo $object["mailSendTitle"]; ?>' maxLength='100'/></td>
	  </tr>
	</table>
    </fieldset>
    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
	  <legend>短信服务器设置</legend>
	  <table class='wst-form wst-box-top'>
	  <tr>
	  	<th colspan='2' style='text-align:left;padding-left:40px;padding-bottom: 15px;'><span style='color:gray;'>(请确保在“拓展管理-插件管理"中有安装相应的短信插件”)</span></th>
	  </tr>
	  <tr style='display:none'>
	     <th >开启手机验证：</th>
	     <td>
	     <input type="checkbox" checked class="ipt" id="smsOpen" value='1' name="smsOpen" value='1' lay-skin="switch" lay-filter="smsOpen" lay-text="开|关">
	     </td>
	  </tr>
	  <tr>
	     <th width='150'>每个号码每日发送数：</th>
	     <td><input type="text" id='smsLimit' class='ipt' value="<?php echo $object['smsLimit']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>开启短信发送验证码：</th>
	     <td>
	     <input type="checkbox" <?php if($object['smsVerfy']==1): ?>checked<?php endif; ?> class="ipt" id="smsVerfy" value='1' name="smsVerfy" value='1' lay-skin="switch" lay-filter="smsVerfy" lay-text="开|关">
	     </td>
	  </tr>
	  <?php if(($grant)): ?>
	  <tr>
	     <td colspan='2' align='center'>
	     	<button type="button" onclick='javascript:edit()'  class='btn btn-primary btn-mright'><i class="fa fa-check"></i>保存</button>
            <button type="reset"  class='btn'><i class="fa fa-refresh"></i>重置</button>
	     </td>
	  </tr>
	   <?php endif; ?>
	 </table>
	</fieldset>
</div><div class="layui-tab-item layui-form">
	<table class='wst-form wst-box-top'>
		<tr>
			<th width='150'>api对接地址：</th>
			<td>
				<input type="text" id='apiUrl' class='ipt' maxLength='30' value='<?php echo $object["apiUrl"]; ?>'/><span style='color:gray;margin-left:5px;'><font color='red'>注意：填入对接方的url链接!</font></span>
			</td>
		</tr>
		<tr>
			<th width='150'>api接口加密秘钥：</th>
			<td>
				<input type="text" id='apiSecretKey' class='ipt' maxLength='30' value='<?php echo $object["apiSecretKey"]; ?>'/><span style='color:gray;margin-left:5px;'><font color='red'>注意：如果修改了此密钥，对接方也必须修改，请谨慎操作!</font></span>
			</td>
		</tr>
	<tr>
	     <th width='150'>链接参数加密秘钥：</th>
	     <td>
	     	<input type="text" id='urlSecretKey' class='ipt' maxLength='30' value='<?php echo $object["urlSecretKey"]; ?>'/>
	    </td>
	</tr>
	<tr>
	     <th width='150'>密码加密传输：</th>
	     <td><input type="checkbox" <?php if($object['isCryptPwd']==1): ?>checked<?php endif; ?> value='1' class="ipt" id="isCryptPwd" name="isCryptPwd" lay-skin="switch" lay-filter="isCryptPwd" lay-text="开|关"><span style='color:gray;margin-left:5px;'>开启则用户登录、支付密码加密后再进行提交。<font color='red'>注意：该功能需开启openssl扩展支持!</font></span>
	     </td>
	</tr>
	<tr class='pwdCryptKeyTr' <?php if($object['isCryptPwd']==0): ?>style='display:none'<?php endif; ?>>
	    <th width='150'>商城密匙：</th>
	    <td>
	     	<textarea id='pwdPrivateKey' style='height:250px' class="ipt" placeholder='请输入用于登录、支付密码加密传输的密匙，请勿留空'><?php echo $object['pwdPrivateKey']; ?></textarea>
	    </td>
	</tr>
	<tr class='pwdCryptKeyTr' <?php if($object['isCryptPwd']==0): ?>style='display:none'<?php endif; ?>>
	    <th width='150'>Modulus：</th>
	    <td>
	     	<textarea id='pwdModulusKey' class="ipt" placeholder='请输入用于登录、支付密码加密传输的16进制公钥，请勿留空'><?php echo $object['pwdModulusKey']; ?></textarea>
	    </td>
	</tr>
	<?php if(($grant)): ?>
	<tr>
	    <td colspan='2' align="center">
	     	<button  type="button" class="btn btn-primary btn-mright" onclick='javascript:edit()'><i class="fa fa-check"></i>保存</button> 
        	<button class="btn" onclick='javascript:resetForm()'><i class="fa fa-refresh"></i>重置</button>
	    </td>
	 </tr>
	<?php endif; ?>
	</table>
</div><link rel="stylesheet" type="text/css" href="/static/plugins/colpick/css/colpick.css" />
<script src="/static/plugins/colpick/js/colpick.js"></script>
<div class="layui-tab-item layui-form">
    <table class='wst-form wst-box-top'>
	<tr>
	 <th width='160'>水印位置：</th>
	 <td>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="0" <?php if(($object['watermarkPosition']==0)): ?>checked<?php endif; ?> title='无'/></label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="1" <?php if(($object['watermarkPosition']==1)): ?>checked<?php endif; ?> title='左上'/></label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="3" <?php if(($object['watermarkPosition']==3)): ?>checked<?php endif; ?> title='右上'/></label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="5" <?php if(($object['watermarkPosition']==5)): ?>checked<?php endif; ?> title='居中'/></label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="7" <?php if(($object['watermarkPosition']==7)): ?>checked<?php endif; ?> title='左下'/></label>
	 	<label><input type="radio" id='watermarkPosition' name='watermarkPosition' class='ipt' value="9" <?php if(($object['watermarkPosition']==9)): ?>checked<?php endif; ?> title='右下'/></label>
	 	<span style="color:gray;">设置为"无"则视为关闭水印</span>
	 </td>
	</tr>
	  <tr>
	     <th>水印文字偏移量：</th>
	     <td>
	     	<input type="text" placeholder="-10,-10" id='watermarkOffset' class='ipt' value="<?php echo $object['watermarkOffset']; ?>"/>
	     	<span style="color:gray;"> x，y 定义了第一个字符的左上角。x<0,向左偏移；x>0,向右偏移。y<0,向上偏移；y>0,向下偏移。</span>
	     </td>
	  </tr>
	  <tr>
	     <th width='150'>水印文字：</th>
	     <td>
	     	<input type="text" id='watermarkWord' class='ipt' value="<?php echo $object['watermarkWord']; ?>" maxLength='50' />
	     	<span style="color:gray;">当文字和图片同时存在时以文字为主</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文字大小：</th>
	     <td>
	     	<input type="text" id='watermarkSize' class='ipt' value="<?php echo $object['watermarkSize']; ?>" maxLength='2'/>
	     	<span style="color:gray;">建议大小为20</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文字颜色：</th>
	     <td>
	     	<input type="text" id='watermarkColor' class='ipt' value="<?php echo $object['watermarkColor']; ?>" />
	     	<span style="color:gray;">仅支持16进制的颜色：如#00FF00</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文字字体路径：</th>
	     <td>
	     	<input type="text" id='watermarkTtf' class='ipt' value="<?php echo $object['watermarkTtf']; ?>" placeholder="extend/verify/verify/ttfs/test.ttf" />
	     	<span style="color:gray;">后缀为.ttf,若留空则使用默认字体(使用中文水印时，若字体不支持中文，则会出现方框)</span>
	     </td>
	  </tr>
	  <tr>
	     <th>水印文件：</th>
	     <td>
	     	<input type="text" readonly="readonly" id='watermarkFile' class='ipt' value="<?php echo $object['watermarkFile']; ?>" style="float: left;width: 355px;" />
	     	<div id='watermarkFilePicker'>上传</div><span id='watermarkFileMsg'></span>
	     	<span style="color:gray;line-height: 30px;float: left; padding-left: 5px;">水印图最终大小由上传的图片大小决定</span>
	     	<div style="min-height:30px; float: left;line-height: 30px;margin-left: 5px;" id="preview">
          	<?php if((isset($object['watermarkFile']))): ?>
          	 <img id='watermarkFilePrevw' src="__RESOURCE_PATH__/<?php echo $object['watermarkFile']; ?>" style="max-height:30px;" /> 
          	<?php endif; ?>
          	</div>
	     </td>
	  </tr>
	  
	  <tr>
	     <th>水印透明度：</th>
	     <td>
	     	<input type="text" id='watermarkOpacity' class='ipt' value="<?php echo $object['watermarkOpacity']; ?>" />
	     	<span style="color:gray;">水印的透明度,可选值为0-100。当设置为100时则为不透明</span>
	     </td>
	  </tr>
      <tr>
	     <th>商城Logo：</th>
	     <td>
	     	<input type="text" readonly="readonly" id='mallLogo' class='ipt' value='<?php echo $object["mallLogo"]; ?>' style="float: left;width: 355px;" />
	     <div id='mallLogoPicker'>上传</div><span id='mallLogoMsg'></span>
	     <span style="color:gray;line-height: 30px;float: left; padding-left: 5px;">(图片大小为300x150，格式为png或者jpg)</span>	
	     <img src='__RESOURCE_PATH__/<?php echo $object["mallLogo"]; ?>' height='30px' id='mallLogoPrevw' style="margin-left: 5px;" />
	     </td>
	  </tr>
	  <tr>
	     <th>默认店铺头像：</th>
	     <td>
	     <input type="text" readonly="readonly" id='shopLogo' class='ipt' value='<?php echo $object["shopLogo"]; ?>' style="float: left;width: 355px;"/>	
	     <div id='shopLogoPicker'>上传</div><span id='shopLogoMsg'></span>
	     <span style="color:gray;line-height: 30px;float: left; padding-left: 5px;">(图片大小为150x150，格式为png或者jpg)</span>
	     <img src='__RESOURCE_PATH__/<?php echo $object["shopLogo"]; ?>' height='30' id='shopLogoPrevw' style="margin-left: 5px;"/>
	     </td>
	  </tr>
	  <tr>
	     <th>移动端店铺默认顶部广告：</th>
	     <td>
	     <input type="text" readonly="readonly" id='shopAdtop' class='ipt' value='<?php echo $object["shopAdtop"]; ?>' style="float: left;width: 355px;"/>
	     <div id='shopAdtopPicker'>上传</div><span id='shopAdtopMsg'></span>
	     <span style="color:gray;line-height: 30px;float: left; padding-left: 5px;">(图片大小为750x260，格式为jpg或者png)</span>
	     <img src='__RESOURCE_PATH__/<?php echo $object["shopAdtop"]; ?>' height='30' id='shopAdtopPrevw' style="margin-left: 5px;"/>
	     </td>
	  </tr>
	  <tr>
	     <th>默认会员头像：</th>
	     <td>
	     	<input type="text" readonly="readonly" id='userLogo' class='ipt' value='<?php echo $object["userLogo"]; ?>' style="float: left;width: 355px;"/>
	     <div id='userLogoPicker'>上传</div><span id='userLogoMsg'></span>
	     <span style="color:gray;line-height: 30px;float: left; padding-left: 5px;">(图片大小为150x150，格式为png或者jpg)</span>
	     <img src='__RESOURCE_PATH__/<?php echo $object["userLogo"]; ?>' height='30' id='userLogoPrevw' style="margin-left: 5px;"/>
	     </td>
	  </tr>
	  <tr>
	     <th>默认商品图片：</th>
	     <td>
	     	<input type="text" readonly="readonly" id='goodsLogo' class='ipt' value='<?php echo $object["goodsLogo"]; ?>' style="float: left;width: 355px;"/>
	     <div id='goodsLogoPicker'>上传</div><span id='goodsLogoMsg'></span>
	     <span style="color:gray;line-height: 30px;float: left; padding-left: 5px;">(图片大小为360x360，格式为png或者jpg)</span>
	     <img src='__RESOURCE_PATH__/<?php echo $object["goodsLogo"]; ?>' height='30' id='goodsLogoPrevw' style="margin-left: 5px;"/>
	     </td>
	  </tr>
	  <?php if(($grant)): ?>
	    <tr>
	      <td colspan='2' align="center">
	     	<button type="button" class="btn btn-primary btn-mright" onclick='javascript:edit()'><i class="fa fa-check"></i>保存</button> 
        	<button class="btn" onclick='javascript:resetForm()'><i class="fa fa-refresh"></i>重置</button>
	     </td>
	    </tr>
	 <?php endif; ?>
	 </table>
</div><div class="layui-tab-item layui-form">
     <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>商城标题：</th>
	     <td><input type="text" id='seoMallTitle' class='ipt' value="<?php echo $object['seoMallTitle']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>商城关键字：</th>
	     <td><input type="text" id='seoMallKeywords' class='ipt' style='width:70%' value="<?php echo $object['seoMallKeywords']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	     <th>商城描述：</th>
	     <td><input type="text" id='seoMallDesc' class='ipt' style='width:70%' value="<?php echo $object['seoMallDesc']; ?>" maxLength='100'/></td>
	  </tr>
	  <tr>
	  	<?php if(($grant)): ?>
	     <td colspan='2' align='center'>
	     	<button type="button" class="btn btn-primary btn-mright" onclick='javascript:edit()'><i class="fa fa-check"></i>保存</button> 
        	<button type="reset" class="btn" onclick='javascript:resetForm()'><i class="fa fa-refresh"></i>重置</button>
	     </td>
	     <?php endif; ?>
	  </tr>
	 </table>
</div>
			
			<?php if((WSTConf('CONF.hasApp'))): ?>
				<div class="layui-tab-item layui-form">
    <table class='wst-form wst-box-top'>
        <tr>
            <th width='150'>app图标：</th>
            <td>
                <input type="text" readonly="readonly" id='appLogo' class='ipt' value='<?php echo !empty($object["appLogo"]) ? $object["appLogo"] : ""; ?>' style="float: left;width: 355px;" />
                <div id='appLogoPicker'>上传</div><span id='appLogoMsg'></span>
                <img src='__RESOURCE_PATH__/<?php echo !empty($object["appLogo"]) ? $object["appLogo"] : ""; ?>' height="30" id='appLogoPrevw' style="margin-left: 5px;" />
            </td>
        </tr>
        <tr>
            <th>app名称：</th>
            <td><input type="text" id='appName' class='ipt' value="<?php echo !empty($object['appName']) ? $object['appName'] : ''; ?>"
                    maxLength='100' /></td>
        </tr>
        <tr>
            <th>app描述：</th>
            <td><input type="text" id='appDesc' class='ipt' value="<?php echo !empty($object['appDesc']) ? $object['appDesc'] : ''; ?>"
                    maxLength='100' /></td>
        </tr>
        <tr>
            <th>android下载地址：</th>
            <td><input type="text" id='apkUrl' class='ipt' value="<?php echo !empty($object['apkUrl']) ? $object['apkUrl'] : ''; ?>" /></td>
        </tr>
        <tr>
            <th>ios端下载地址：</th>
            <td><input type="text" id='ipaUrl' class='ipt' value="<?php echo !empty($object['ipaUrl']) ? $object['ipaUrl'] : ''; ?>" /></td>
        </tr>
        <tr>
            <?php if(($grant)): ?>
            <td colspan='2' align='center'>
                <button type="button" class="btn btn-primary btn-mright" onclick='javascript:edit()'><i class="fa fa-check"></i>保存</button>
                <button type="reset" class="btn" onclick='javascript:resetForm()'><i class="fa fa-refresh"></i>重置</button>
            </td>
            <?php endif; ?>
        </tr>
    </table>
</div>
			<?php endif; ?>
		    <div class="layui-tab-item layui-form">
	<?php 
		$loginTypeArr = explode(',',$object['loginType']);
		$mobileLoginTypeArr = explode(',',$object['mobileLoginType']);
	 ?>
     <table class='wst-form wst-box-top'>
	  <tr>
	     <th width='150'>电脑端登录：</th>
	     <td>
			 <div class='login-type'>
				 <input type='checkbox' class='ipt' name='loginType' value='1' <?php if(empty($object['loginType']) || in_array(1,$loginTypeArr)): ?>checked<?php endif; ?> />账号登录
			 </div>
			 <div class='login-type'>
				 <input type='checkbox' class='ipt' name='loginType' value='2' <?php if(in_array(2,$loginTypeArr)): ?>checked<?php endif; ?> />手机登录
			 </div>
			 <div class='login-type' style='display: none'>
				 <input type='checkbox' class='ipt' name='loginType' value='3' <?php if(in_array(3,$loginTypeArr)): ?>checked<?php endif; ?> />扫码登录
			 </div>
		 </td>
	  </tr>
	 <tr>
		 <th width='150'>移动端登录：</th>
		 <td>
			 <div class='login-type'>
				 <input type='checkbox' class='ipt' name='mobileLoginType' value='1' <?php if(empty($object['mobileLoginType']) || in_array(1,$mobileLoginTypeArr)): ?>checked<?php endif; ?> />账号登录
			 </div>
			 <div class='login-type'>
				 <input type='checkbox' class='ipt' name='mobileLoginType' value='2' <?php if(in_array(2,$mobileLoginTypeArr)): ?>checked<?php endif; ?> />手机登录
			 </div>

		 </td>
	 </tr>
	 <?php if((WSTDatas('ADS_TYPE',2)!='')): ?>
		 <tr>
			 <th width='150'>微信端登录：
			 <td>
				 <div class='login-type'>
					 <input type='checkbox' class='ipt' name='wechatOneClickLogin' value='1' <?php if($object['wechatOneClickLogin']==1): ?>checked<?php endif; ?> />开启一键登录
				 </div>

			 </td>
		 </tr>
	 <?php endif; if((WSTDatas('ADS_TYPE',5)!='')): ?>
		 <tr>
			 <th width='150'>小程序登录：
			 <td>
				 <div class='login-type'>
					 <input type='checkbox' class='ipt' name='weappOneClickLogin' value='1' <?php if($object['weappOneClickLogin']==1): ?>checked<?php endif; ?> />开启一键登录
				 </div>

			 </td>
		 </tr>
	 <?php endif; if((WSTDatas('ADS_TYPE',2)!='') || (WSTDatas('ADS_TYPE',5)!='')): ?>
		 <tr>
			 <th width='150'></th>
			 <td >
				 <div >
					 开启一键登录，用户初次进入系统，系统会自动帮用户创建一个账号。如需多端统一一个账号则请勿勾选。
				 </div>
			 </td>
		 </tr>
      <?php endif; ?>
	  <tr>
	  	<?php if(($grant)): ?>
	     <td colspan='2' align='center'>
	     	<button type="button" class="btn btn-primary btn-mright" onclick='javascript:edit()'><i class="fa fa-check"></i>保存</button> 
        	<button type="reset" class="btn" onclick='javascript:resetForm()'><i class="fa fa-refresh"></i>重置</button>
	     </td>
	     <?php endif; ?>
	  </tr>
	 </table>
</div>
	   </div>
</div>
</form>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>' type="text/javascript"></script>
<script src="__ADMIN__/sysconfigs/sysconfigs.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>