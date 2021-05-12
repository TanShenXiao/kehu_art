<?php /*a:2:{s:67:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\brands\edit.html";i:1602924209;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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
.goodsCat{display:inline-block;width:150px}
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

<input type='hidden' id='brandId' value='<?php echo $object["brandId"]; ?>'/>
<div class="l-loading" style="display: block" id="wst-loading"></div>
<form id="brandForm" autocomplete="off">
<table class='wst-form wst-box-top'>
  <tr>
     <th width='150'>品牌名称<font color='red'>*</font>：</th>
     <td><input type="text" id='brandName' name='brandName' maxLength='20' style='width:300px;' class='ipt' /></td>
  </tr>
   <tr>
     <th width='150' align='right'>所属分类<font color='red'>*</font>：</th>
     <td>
     <?php if(is_array($gcatList) || $gcatList instanceof \think\Collection || $gcatList instanceof \think\Paginator): $i = 0; $__LIST__ = $gcatList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	     <label class='goodsCat'>
	     <input type='checkbox' id='catId' name='catId' class="ipt" value='<?php echo $vo["catId"]; ?>'
       <?php if($object['brandId'] !=0): if(in_array($vo["catId"],$object['catIds'])==1): ?>checked<?php endif; ?>
       <?php endif; ?>
       >&nbsp;<?php echo $vo["catName"]; ?>&nbsp;
	     </label>
	 <?php endforeach; endif; else: echo "" ;endif; ?>
     </td>
   </tr>
   <tr width='150'>
     <th align='right'>品牌图标<font color='red'>*</font>：</th>
     <td>
     	   <input id="brandImg" name="brandImg" class="text ipt" data-target='#msg_brandImg' autocomplete="off" type="text" readonly="readonly" value="<?php echo $object['brandImg']; ?>" data-rule="品牌图标: required;" style="float: left;width: 250px;"/>
    	   <div id="filePicker">上传</div>
     	    <span style='margin-left:5px;float:left;line-height: 30px;'>图片大小:400 x 200 (px)，格式为 gif, jpg, jpeg,bmp, png </span>
          <span class='msg-box' id='msg_brandImg'></span>
     	    <div id="preview" style="float: left;;margin-left: 5px;">
            <?php if($object['brandId']!=0): ?>
            <img src="__RESOURCE_PATH__/<?php echo $object['brandImg']; ?>" class="ipt" height='30'/>
            <?php endif; ?>
        </div>
     </td>
   </tr>
   <tr>
     <th width='150'>排序号<font color='red'>*</font>：</th>
     <td><input type="text" id='sortNo' name='sortNo' maxLength='20' style='width:300px;' class='ipt' onkeypress="return WST.isNumberKey(event);" onkeyup="javascript:WST.isChinese(this,1)" maxlength="10" value="0" data-tip="请输入排序号"/></td>
  </tr>
   <tr>
       <th width='150'>品牌介绍<font color='red'>*</font>：</th>
       <td>
       	<textarea id='brandDesc' name='brandDesc' class="form-control ipt" style='width:80%;height:400px'></textarea>
       </td>
    </tr>
     <tr>
       <td colspan='2' align='center'>
           <button type="submit" class="btn btn-primary btn-mright"><i class="fa fa-check"></i>保&nbsp;存</button>
           <button type="button" class="btn" onclick="javascript:location.href='<?php echo Url('admin/brands/index','p='.$p); ?>'"><i class="fa fa-angle-double-left"></i>返&nbsp;回</button>
       </td>
     </tr>
</table>
 </form>
 <script>
$(function(){
  //文件上传
	WST.upload({
  	  pick:'#filePicker',
  	  formData: {dir:'brands',mWidth:500,mHeight:250},
  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
  	  callback:function(f){
  		  var json = WST.toAdminJson(f);
  		  if(json.status==1){
        	$('#preview').html('<img src="'+WST.conf.RESOURCE_PATH+"/"+json.savePath+json.thumb+'" height="30" />');
        	$('#brandImg').val(json.savePath+json.thumb);
  		  }
	  }
    });
  //编辑器
    KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="brandDesc"]', {
			height:'350px',
			uploadJson : WST.conf.ROOT+'/admin/brands/editorUpload',
			allowFileManager : false,
			allowImageUpload : true,
			items:[
			        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
			        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
			        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
			        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
			        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
			        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
			        'anchor', 'link', 'unlink', '|', 'about'
			],
			afterBlur: function(){ this.sync(); }
		});
	});
});
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>" type="text/javascript" ></script>
<script src="/static/plugins//kindeditor/kindeditor.js?v=<?php echo $v; ?>" type="text/javascript" ></script>
<script src="__ADMIN__/brands/brands.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
$(function () {
	   <?php if($object['brandId'] !=0): ?>
		WST.setValues(<?php echo $object; ?>);
	   <?php endif; ?>
		$('#brandForm').validator({
		    fields: {
		    	brandName: {
		    		tip: "请输入品牌名称",
		    		rule: '品牌名称:required;length[~16];'
		    	},
		    	catId: {
		    		tip: "请选择分类",
		    		rule: 'checked(1~);length[~16];'
		    	},
		    	brandImg:{
	                tip:"请上传品牌图片",
	                rule:"品牌图片:required;",
		    	},
		    	brandDesc: {
		    		tip: "请输入品牌介绍",
		    		rule: '品牌介绍:required;'
		    	}
		    },
		    valid: function(form){
		    	var brandId = $('#brandId').val();
		    	toEdits(brandId,<?php echo $p; ?>);
		    }
		})
});
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>