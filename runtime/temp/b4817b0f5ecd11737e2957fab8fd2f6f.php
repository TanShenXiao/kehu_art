<?php /*a:2:{s:69:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\articles\edit.html";i:1602924207;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<link href="/static/plugins/ztree/css/zTreeStyle/zTreeStyle.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />

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

<input type='hidden' id='articleId' value='<?php echo $object["articleId"]; ?>'/>
<form id='articleForm' autocomplete="off">
<table class='wst-form wst-box-top '>
  <tr>
     <th width='150'>文章标题<font color='red'>*</font>：</th>
     <td><input type="text" id='articleTitle' name='articleTitle' maxLength='50' class='ipt' style='width:60%;'/></td>
  </tr>
   <tr>
     <th width='150' align='right'>分类类型<font color='red'>*</font>：</th>
     <td>
       <input id="catSel" type="text" readonly onclick="showMenu();" value="<?php echo $object['catName']; ?>"/>
       <div id="ztreeMenuContent" class="ztreeMenuContent">
          <ul id="dropDownTree" class="ztree" style="margin-top:0; width:250px; height: 300px;"></ul>
       </div>
       <input id="catId" name='catId' class="text ipt" autocomplete="off" type="hidden" value=""/>
       <span id='catIdt'></span>
     </td>
   </tr>
   <tr>
      <th width='150'>是否显示<font color='red'>*</font>：</th>
      <td height='24' class="layui-form">
         <input type="checkbox" id="isShow" <?php if($object['isShow']==1): ?>checked<?php endif; ?> name="isShow" value="1" class="ipt" lay-skin="switch" lay-filter="isShow" lay-text="显示|隐藏">
      </td>
   </tr>
  <tr>
     <th width='150'>文章关键字<font color='red'>*</font>：</th>
     <td><input type="text" id='articleKey' name='articleKey' maxLength='120' class='ipt' style='width:80%;'/></td>
  </tr>
  <tr>
     <th width='150'>文章描述<font color='red'>*</font>：</th>
     <td><textarea id='articleDesc' name='articleDesc' maxLength='200' style='width:80%;height:80px;' class='ipt'></textarea></td>
  </tr>
   <tr >
      <th>移动端布局<font color='red'>*</font>：</th>
      <td  class="typeState" style="padding-top: 10px;">
          <li>
             <input type='radio' name='TypeStatus' class='ipt' value='1' <?php if($object["articleId"]==0): ?>checked<?php endif; ?> onclick="selectlLayout(1)"/>
             <label>
               <img src="__ADMIN__/img/news_1.png" style="width:150px;height:80px;">
             </label>
          </li>
          <li>
            <input type='radio' name='TypeStatus' class='ipt' value='2' onclick="selectlLayout(2)"/>
             <label>
                <img src="__ADMIN__/img/news_2.png" style="width:150px;height:80px;">
             </label>
          </li>
          <li>
             <input type='radio' name='TypeStatus' class='ipt' value='3' onclick="selectlLayout(3)"/>
             <label>
                <img src="__ADMIN__/img/news_3.png" style="width:150px;height:80px;">
             </label>
          </li>
          <li>
             <input type='radio' name='TypeStatus' class='ipt' value='4' onclick="selectlLayout(4)"/>
             <label>
                <img src="__ADMIN__/img/news_4.png" style="width:150px;height:80px;">
             </label>
          </li>
      </td>
   </tr>
  <tr id='upload'>
     <th>封面图片：</th>
     <td>
      <input type="text" readonly="readonly" id='coverImg' name="coverImg" class="ipt" style="float: left;width: 355px;"/>
     <div id='coverImgPicker'>上传</div><span id='coverImgMsg'></span><span id="remind" style="line-height: 30px;float: left;margin-left: 5px;">图片大小:230x195(px)，格式为 gif, jpg, jpeg, png</span>
     <div style="min-height:30px; float: left;margin-left: 5px;" id="preview"><?php if(($object['articleId']!=0 && $object['coverImg'])): ?><img src="__RESOURCE_PATH__/<?php echo $object['coverImg']; ?>" height="30" /><?php endif; ?></div>
     </td>
  </tr>
   <tr>
       <th width='150'>文章内容<font color='red'>*</font>：</th>
       <td>
       	<textarea id='articleContent' name='articleContent' class="form-control ipt" style='width:80%;'></textarea>
       </td>
    </tr> 
      <tr>
          <th>文章排序号：</th>
          <td>
            <input type="text" id="catSort" class="ipt" maxLength="20"  value='<?php echo $object['catSort']; ?>' />
          </td>
      </tr> 
     <tr>
       <td colspan='2' align='center'>
       	<button type="submit" class="btn btn-primary btn-mright" ><i class="fa fa-check"></i>保&nbsp;存</button> 
        <button type="button" class="btn" onclick="javascript:location.href='<?php echo Url('admin/articles/index','p='.$p); ?>'"><i class="fa fa-angle-double-left"></i>返&nbsp;回</button>
       </td>
     </tr>
</table>
 </form>
  <script>
$(function(){
	//文件上传
	WST.upload({
  	  pick:'#coverImgPicker',
  	  formData: {dir:'articles',isThumb:1},
  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
  	  callback:function(f){
  		  var json = WST.toAdminJson(f);
  		  if(json.status==1){
  			$('#coverImgMsg').empty().hide();
        	$('#preview').html('<img src="'+WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb+'" height="30" />');
        	$('#coverImg').val(json.savePath+json.thumb);
  		  }
	  },
	  progress:function(rate){
	      $('#coverImgMsg').show().html('已上传'+rate+"%");
	  }
    });
  //编辑器
    KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="articleContent"]', {
			height:'500px',
      uploadJson : WST.conf.ROOT+'/admin/articles/editorUpload',
			allowFileManager : false,
			allowImageUpload : true,
      allowMediaUpload : false,
			items:[
			        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
			        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
			        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
			        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
			        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
			        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','media','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
			        'anchor', 'link', 'unlink', '|', 'about'
			],
			afterBlur: function(){ this.sync(); }
		});
	});
  selectlLayout(<?php echo $object['TypeStatus']; ?>);
});
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/ztree/jquery.ztree.all-3.5.js?v=<?php echo $v; ?>"></script>
<script src="/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>" type="text/javascript" ></script>
<script src="/static/plugins//kindeditor/kindeditor.js?v=<?php echo $v; ?>" type="text/javascript" ></script>
<script src="__ADMIN__/articles/articles.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
$(function () {
	initCombo(0);
	<?php if($object['articleId'] !=0): ?>
	   WST.setValues(<?php echo $object; ?>);
	<?php endif; ?>
	$('#articleForm').validator({
	    fields: {
	    	articleTitle: {
	    		tip: "请输入文章名称",
	    		rule: '文章名称:required;length[~50];'
	    	},
	    	catId: {
		        tip: "请选择文章分类",
		    	rule: "文章分类:required;",
		    	target:"#catIdt"
		    },
	    	articleKey: {
	    		tip: "请输入文章关键字",
	    		rule: '关键字:required;length[~100];'
	    	},
        articleDesc: {
          tip: "请输入文章描述",
          rule: '文章描述:required;length[~200];'
        },
        layoutType: {
          tip: "请选择移动端布局样式",
          rule: '移动端布局样式:required;length[~100];'
        },
		    articleContent: {
	    		tip: "请输入文章内容",
	    		rule: '文章内容:required;'
	    	}
	    },
	    valid: function(form){
	    	var articleId = $('#articleId').val();
	    	toEdits(articleId,<?php echo $p; ?>);
	    }
	})
});
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>