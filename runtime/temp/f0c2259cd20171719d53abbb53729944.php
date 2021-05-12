<?php /*a:2:{s:84:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\articles\edit_user_agreement.html";i:1583911195;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

        <input type='hidden' id='articleId' class='ipt' value='<?php echo $object["articleId"]; ?>'/>
		<form id='articleForm' autocomplete="off">
		<table class='wst-form wst-box-top '>
		   <tr>
		       <td align='center'>用户注册协议</th>
		   </tr>
		       <td align='center'>
		       	<?php if(WSTGrant('YHRZXY_00')): ?> 
		       	<textarea id='articleContent' name='articleContent' class="form-control ipt" style='width:80%;'><?php echo $object['articleContent']; ?></textarea>
		       	<?php else: ?>
		       	<div style='width:80%;text-align:left;'>
                <?php echo htmlspecialchars_decode($object['articleContent']); ?>
                <div>
		       	<?php endif; ?>
		       </td>
		    </tr>
		    <?php if(WSTGrant('YHRZXY_02')): ?>
		    <tr>
		       <td align='center'>
		       	<button type="button" class="btn btn-primary btn-mright" onclick="javascript:saveAgreement()"><i class="fa fa-check"></i>保&nbsp;存</button> 
		        <button type="button" class="btn" onclick="javascript:clearHtml()"><i class="fa fa-refresh"></i>清&nbsp;除</button>
		       </td>
		    </tr>
		    <?php endif; ?>
		</table>
<?php if(WSTGrant('YHRZXY_02')): ?> 
<script>
var editor1 = null;
$(function(){
  //编辑器
    KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="articleContent"]', {
			height:'600px',
            uploadJson : WST.conf.ROOT+'/admin/articles/editorUpload',
			allowFileManager : false,
			allowImageUpload : true,
            themeType : "default",
		    items:[     'source', 'undo', 'redo',  'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
		                'plainpaste', 'wordpaste', 'justifyleft', 'justifycenter', 'justifyright',
		                'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
		                'superscript', 'clearhtml', 'quickformat', 'selectall',  'fullscreen',
		                'formatblock', 'fontname', 'fontsize',  'forecolor', 'hilitecolor', 'bold',
		                'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', 'image','multiimage','media','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
		                'anchor', 'link', 'unlink'
		    ],
			afterBlur: function(){ this.sync(); }
		});
	});
});
function clearHtml(){
    editor1.html("");
}
</script>
<?php endif; ?>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="/static/plugins//kindeditor/NKeditor-all.js?v=<?php echo $v; ?>" type="text/javascript" ></script>
<script src="__ADMIN__/articles/agreement.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>