<?php /*a:2:{s:103:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\shopconfigs\shop_cfg.html";i:1602924182;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />
<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/batchupload.css?v=<?php echo $v; ?>" />
<link href="/static/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">

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

<script>
$(function(){
  $('.state-complete').css('border-color','#ddd');
})
</script>
<style>
.filelist .btn-del,.webuploader-pick,.wst-batchupload .placeholder .webuploader-pick,.wst-batchupload .statusBar .btns .uploadBtn{background: #1890ff;}
.wst-batchupload .statusBar .btns .uploadBtn:hover {background: #e45525 none repeat scroll 0 0;}
.shopbanner{position: relative;}
.del-banner{position: absolute;top:0px;right:0px;background: #e45050 none repeat scroll 0 0  z-index: 55;color: #ffffff;cursor: pointer;height: 18px;line-height: 18px;padding: 0 5px;background: rgba(0,0,0,0.6);width: 18px;border-radius: 50%;color: #fff !important;}
.wst-batchupload .filelist li{background:#ffffff;height: 213px;}
#filePicker,#filePicker .webuploader-pick,#filePicker2,#filePicker2 .webuploader-pick{height:30px;line-height: 30px;}
/*#filePicker2{width:120px;}*/
#shopCfg span{color: red}
</style>
<div class="wst-body">
<div class="f-clear"></div>
   <div class="wst-shop-content">

    


        <table class="wst-form">
        <form name="shopCfg"  id="shopCfg" autocomplete="off">
        <tbody>
           <tr>
             <th width='120' align='right'>店铺SEO关键字<font color='red'>*</font>：</th>
             <td><input type='text' id='shopKeywords' name='shopKeywords' class="ipt"  value='<?php echo $object['shopKeywords']; ?>' data-rule='关键字:required;' maxLength='25' /></td>
           </tr>
           <tr>
           <th width='120'>店铺SEO描述：</th>
           <td colspan='3'>
               <textarea rows="2" style='width:568px;' class="ipt" id='shopDesc' name='shopDesc' ><?php echo $object['shopDesc']; ?></textarea>
           </td>
           </tr>
           <tr>
           <th width='120'>店铺热搜关键词：</th>
           <td><input type='text' id='shopHotWords' name='shopHotWords' class="ipt"  value='<?php echo $object['shopHotWords']; ?>' placeholder="店铺主页搜索栏下的引导搜索词" maxLength='100'/></td>
         </tr>
          
          
          <tr style="height:80px">
             <th width='120' align='right' valign='top'>店铺街背景：</th>
             <td>
                <input type="text" readonly="readonly" id="shopStreetImg" value="<?php echo $object['shopStreetImg']; ?>" class="ipt" style="width: 483px;float: left;" />
                <div id="shopStreetImgPicker"style='margin-left:0px;margin-top:5px;height:30px;float: left; overflow:hidden'>上传</div>
                <div style="float: left; height: 30px;margin-left: 5px;">
                  <span class='weixin'>
                  
                  <img class='img' style='height:16px;width:18px;' src='/static/images/upload-common-select.png'>
                  <img class='imged' id="shopStreetImgPreview"  style='max-height:150px;max-width: 200px; border:1px solid #dadada; background:#fff' src="__RESOURCE_PATH__/<?php echo $object['shopStreetImg']; ?>">
                  </span>
                </div>
                <div class="f-clear"></div>
              <div>图片大小:<span>228 x 138</span> (px)(格式为 <span>gif</span>, <span>jpg</span>, <span>jpeg</span>, <span>png</span>)</div>
             </td>
           </tr>
           <tr style="height:80px">
             <th width='120' align='right' valign='top'>顶部广告：</th>
             <td>
                <input type="text" readonly="readonly" id="shopBanner" value="<?php echo $object['shopBanner']; ?>" class="ipt" style="width: 483px;float: left;" />
                <div id="shopBannerPicker" style='margin-left:0px;margin-top:5px;height:30px;float: left; overflow:hidden'>上传</div>
                <div style="float: left; height: 30px;margin-left: 5px;">
                <span class='weixin'>
                  <img class='img' style='height:16px;width:18px;' src='/static/images/upload-common-select.png'>
                  <img class='imged' id="shopBannerPreview"  style='max-height:150px;max-width: 200px; border:1px solid #dadada; background:#fff' src="__RESOURCE_PATH__/<?php echo $object['shopBanner']; ?>">
                </span>
              </div>
                <div class="f-clear"></div>
              <div>图片大小:<span>1920 x 110</span> (px)(格式为 <span>gif</span>, <span>jpg</span>, <span>jpeg</span>, <span>png</span>)</div>
              
             </td>
           </tr>
           
            <tr style="height:80px">
             <th width='120' align='right' valign='top'>移动端顶部广告：</th>
             <td>
                <input type="text" readonly="readonly" id="shopMoveBanner" value="<?php echo $object['shopMoveBanner']; ?>" class="ipt" style="width: 483px;float: left;" />
                <div id="shopMoveBannerPicker" style='margin-left:0px;margin-top:5px;height:30px;float: left; overflow:hidden'>上传</div>
                <div style="float: left; height: 30px;margin-left: 5px;">
                  <span class='weixin'>
                  <img class='img' style='height:16px;width:18px;' src='/static/images/upload-common-select.png'>
                  <img class='imged' id="shopMoveBannerPreview"  style='max-height:150px;max-width: 200px; border:1px solid #dadada; background:#fff' src="__RESOURCE_PATH__/<?php echo $object['shopMoveBanner']; ?>">
                </span>
              </div>
                <div class="f-clear"></div>
              <div>图片大小:<span>414 x 190</span> (px)(格式为 <span>gif</span>, <span>jpg</span>, <span>jpeg</span>, <span>png</span>)</div>
              
             </td>
           </tr>
           <tr>
             <th width='120' align='right'>滚动广告<font color='red'>*</font>：</th>
             <td>
                  <div id="batchUpload" class="wst-batchupload" style="border:1px solid #f1f1f1;width: 568px;">
                    <div style="border-bottom:1px solid #f1f1f1;padding-left:10px;height:30px;line-height:30px">
                      图片大小:<span>1200 x 400</span> (px)(格式为 <span>gif</span>, <span>jpg</span>, <span>jpeg</span>, <span>png</span>) 
                    </div>
                    <div class="queueList filled">
                        <div id="dndArea" class="placeholder <?php if(!empty($object['shopAds'])): ?>element-invisible<?php endif; ?>">
				            <div id="filePicker"></div>
				            <p>或将照片拖到这里，单次最多可选5张，每张最大不超过5M</p>
				        </div>
                        <ul class="filelist" >
                            <?php if(is_array($object['shopAds']) || $object['shopAds'] instanceof \think\Collection || $object['shopAds'] instanceof \think\Paginator): $i = 0; $__LIST__ = $object['shopAds'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <li  class="state-complete" style="border: 1px solid #ddd;">
                               <p class="title"></p>
                               <p class="imgWrap">
                                  <img src="__RESOURCE_PATH__/<?php echo $vo; ?>">
                               </p>
                               <input type="hidden" v="<?php echo $vo; ?>" iv="<?php echo $vo; ?>" class="j-gallery-img">
                               <span class="btn-del">删除</span>
                               <input class="cfg-img-url" type="text" value="<?php echo $object['shopAdsUrl'][$key]; ?>" style="width:170px;" placeholder="广告路径">
                            </li>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                      </ul>
                    </div>
                    <div class="statusBar" >
                        <div class="progress" style="display: none;">
                            <span class="text">0%</span>
                            <span class="percentage" style="width: 0%;"></span>
                        </div>
                        <div class="info"></div>
                        <div class="btns">
                            <div id="filePicker2"></div><div class="uploadBtn">开始上传</div>
                        </div>
                    </div>
                </div>

              <div style='clear:both;'></div>
             </td>
           </tr>
          </tbody>
          </form>
          <tfoot>
           <tr>
             <td colspan='2' style='padding:20px 0px 20px 155px;'>
                 <button type="submit" class="btn btn-primary btn-mright" onclick="javascript:save()"><i class="fa fa-check"></i>保&nbsp;存</button>
<button type="button" class="btn" onclick="javascript:location.reload();" style="margin-left: 10px;"><i class="fa fa-refresh"></i>重&nbsp;置</button>
             </td>
           </tr>
           </tfoot>
        </table>
   </div>
</div>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script type='text/javascript' src='__SHOP__/shopconfigs/shop_cfg.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='/static/plugins/webuploader/batchupload.js?v=<?php echo $v; ?>'></script>
<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
<script>
$(function(){
})
function delbanner(obj){
    var c = WST.confirm({content:'您确定要删除顶部广告图片吗?',yes:function(){
             $('#shopBannerPreview').attr('src','');
             $('#shopBanner').val('');
             $(obj).hide();
             layer.close(c);
          }})
  }
  function delmovebanner(obj){
    var c = WST.confirm({content:'您确定要删除移动端顶部广告图片吗?',yes:function(){
             $('#shopMoveBannerPreview').attr('src','');
             $('#shopMoveBanner').val('');
             $(obj).hide();
             layer.close(c);
          }})
  }
function delShopStreetBg(obj){
  var c = WST.confirm({content:'您确定要删除店铺街背景图片吗?',yes:function(){
             $('#shopStreetImgPreview').attr('src','');
             $('#shopStreetImg').val('');
             $(obj).hide();
             layer.close(c);
          }})
}
</script>


<?php echo hook('initCronHook'); ?>
</body>
</html>