<?php /*a:6:{s:96:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\goods\editNew.html";i:1621149234;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;s:97:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\goods\editNew0.html";i:1621394019;s:97:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\goods\editNew1.html";i:1621399653;s:97:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\goods\editNew2.html";i:1621154801;s:97:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\goods\editNew3.html";i:1621139726;}*/ ?>
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

<style>
#specsAttrBox .webuploader-container,#specsAttrBox .webuploader-container .webuploader-pick{width:80px;height:27px;line-height:27px;overflow:hidden;}
#specTby td{padding-bottom:5px;}
#spec-sale-tby td{padding:2px;}
.attr-table td{padding:2px;}
label{font-weight: normal;}
.wst-goods-edit-jincheng{
    display: flex;
    justify-content: center;
    padding-top: 20px;
    padding-bottom: 20px;
    width: 100%;
    background: white;
    position: fixed;
    z-index:1000;
}
.wst-goods-edit-jincheng li{
    display: flex;
    align-items: center;
}
.wst-goods-edit-jincheng li div:nth-child(1){
    padding: 1px 5px;
    border-radius: 50%;
    background: #888585;
    color: white;
}
.wst-goods-edit-jincheng .wancheng div:nth-child(1){
    background: #1890ff;
}
.wst-goods-edit-jincheng li div:nth-child(2){
    color:#888585 ;
    margin-left: 3px;
    margin-right: 3px;
}
.wst-goods-edit-jincheng .wancheng div:nth-child(2){
    color:#1890ff ;
}
.wst-goods-edit-jincheng li div:nth-child(3){
    width: 70px;
    background: #888585;
    height: 1px;
    margin-left:5px;
    margin-right: 8px;
}
.wst-goods-edit-jincheng .wancheng div:nth-child(3){
    background:#1890ff ;
}

</style>
<div id='tab' class="wst-tab-box">
	<!--<ul class="wst-tab-nav">
	   <li>商品基本信息</li>
	   <li>作者信息</li>
	   <li>商品描述</li>
	</ul>-->
    <ul class="wst-goods-edit-jincheng wst-tab-nav-two">
       <li class="wancheng">
           <div>1</div>
           <div>填写商品信息</div>
           <div>&nbsp;</div>
       </li>
        <li>
            <div>2</div>
            <div>自定义作者信息</div>
            <div>&nbsp;</div>
        </li>
        <li>
            <div>3</div>
            <div>自定议商品描述</div>
            <div>&nbsp;</div>
        </li>
        <li>
            <div>4</div>
            <div>完成</div>
        </li>
    </ul>
    <div id="vals">0</div>
    <div class="wst-tab-content" style='width:100%;margin-bottom: 60px;border: 1px solid #f1f1f1; border-top: 0px;margin-top: 0px;'>
      <form id='editform' autocomplete='off'>
        <div class="wst-tab-item" style="position: relative;">
        <style>
    /*.wst-batchupload .webuploader-pick{
        height: 40px;line-height: 40px;
    }*/
    #filePicker{height: auto;width: auto;}
</style>
<input type='hidden' id='goodsId' class='j-ipt' value='<?php echo $object["goodsId"]; ?>'/>
<table class='wst-form' style="margin-top: 40px">
    <tr>
        <th>
            <div style="font-size: 13px;font-weight: bold;margin: 10px 0 0 10px;">商品基础信息</div>
        </th>
        <td></td>
    </tr>
  <tr>
     <th><font color='red'>*</font>商品名称：</th>
     <td>
        <input type="text" class='j-ipt ipwd' id='goodsName' maxLength='150' data-rule='商品名称:required;' value="<?php echo $object["goodsName"]; ?>">
     </td>
  </tr>
    <tr>
        <th><font color='red'>*</font>商品编号：</th>
        <td><input type='text' class='j-ipt ipwd' id='goodsSn' value='<?php echo $object["goodsSn"]; ?>' maxLength='20' data-rule='商品编号:required;'/></td>
    </tr>
    <tr>
        <th><font color='red'>*</font>商品类型：</th>
        <td>
            <select id='goodsType' class='j-ipt ipwd' onchange="changeGoodsType(this.value)" <?php if($object["goodsId"]>0): ?>disabled<?php endif; ?>>
            <option value='0' <?php if(($object["goodsType"]==0)): ?>selected<?php endif; ?>>实物商品</option>
            <option value='1' <?php if(($object["goodsType"]==1)): ?>selected<?php endif; ?>>虚拟商品</option>
            <?php if((isset($object['isDB']) && ($object['isDB']==1))): ?>
            <option value='2' <?php if(($object["goodsType"]==2)): ?>selected<?php endif; ?>>保底交易商品</option>
            <?php endif; ?>
            </select>
        </td>
    </tr>
    <tr>
        <th><font color='red'>*</font>商城分类：</th>
        <td>
            <select id="cat_0" class='ipt j-goodsCats' level="0" onchange="WST.ITGoodsCats({id:'cat_0',val:this.value,isRequire:true,className:'j-goodsCats',afterFunc:'lastGoodsCatCallback'});getBrands('brandId',this.value)" style="width: 395px">
                <option value="">-请选择-</option>
                <?php $_result=WSTShopApplyGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </td>
    </tr>
    <tr>
        <th>本店分类：</th>
        <td>
            <select id="shopCatId1" class='j-ipt' onchange="getShopsCats('shopCatId2',this.value,'');" style="width: 395px">
                <option value="">-请选择-</option>
                <?php $_result=WSTShopCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $vo['catId']; ?>" <?php if($object['shopCatId1']==$vo['catId']): ?>selected<?php endif; ?>><?php echo $vo['catName']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <select id='shopCatId2' class='j-ipt' style="width: 395px">
                <option value=''>请选择</option>
            </select>
        </td>
    </tr>

    <tr>
        <th>
            <div style="font-size: 13px;font-weight: bold;margin: 10px 0 0 10px;">商品图文信息</div>
        </th>
        <td></td>
    </tr>

    <tr>
        <th>商品图片<font color='red'>*</font>：</th>
        <td>
            <input type="text" id='goodsImg' readonly="readonly" value='<?php if($object["goodsImg"]!=''): ?><?php echo $object["goodsImg"]; else: ?><?php echo WSTConf('CONF.goodsLogo'); ?><?php endif; ?>' class="j-ipt" style="float: left; width: 655px;" />
            <div id='goodsImgPicker' style="float: left;margin-top: 5px;">上传</div><span id='uploadMsg'></span>
            <div id='goodsImgBox' style='margin-bottom:5px; float: left; height: 30px; margin-left: 5px;'>
            <span class='weixin'>
                  <img class='img' style='height:16px;width:18px;' src='/static/images/upload-common-select.png'>
                  <img class='imged'  id='preview'  style='max-height:150px;max-width: 200px; border:1px solid #dadada; background:#fff' src="__RESOURCE_PATH__/<?php if($object['goodsImg']!=''): ?><?php echo $object['goodsImg']; else: ?><?php echo WSTConf('CONF.goodsLogo'); ?><?php endif; ?>">
                </span>
            </div>
            <div class="f-clear"></div>
            <span class='msg-box' id='msg_goodsImg'></span>
        </td>
    </tr>
    <tr>
        <th></th>
        <td><span>(图片大小为800x800，格式为jpg或者png)</span></td>
    </tr>
    <tr>
        <th>商品视频：</th>
        <td>
            <input type='text' id='goodsVideo' class='j-ipt'  value='<?php echo $object["goodsVideo"]; ?>' readonly="readonly" style="float: left; width: 655px;" />
            <div id='goodsVideoPicker' style="float: left;margin-top: 5px;">上传</div>
            <span id='uploadVideoMsg'></span>

            <div id='goodsVedioBox' style='margin-bottom:5px; float: left; height: 30px; margin-left: 5px;'>
          <span  class='weixin'>
            <img class='img' style='height:16px;width:18px;' src='/static/images/upload-common-select.png'>
            <video  class='imged' id='goodsVideoPlayer' src="__RESOURCE_PATH__/<?php echo $object["goodsVideo"]; ?>" controls="controls"  style='max-height:150px;max-width: 200px; border:1px solid #dadada; background:#fff'></video>
              <span class="vedio-del" <?php if($object["goodsVideo"]!=''): ?>style='display:inline-block'<?php endif; ?> onclick="javascript:clearVedio(this)"></span>
                </span>

            </div>
        </td>
    </tr>
    <tr>
        <th></th>
        <td><span>(视频格式为3gp,mp4,rmvb,mov,avi)</span></td>
    </tr>
    <tr>
        <th>商品细节图：</th>
        <td>
            <div id="batchUpload" class="wst-batchupload">
                <div class="queueList filled" style="border:1px solid #eee;">
                    <div id="dndArea" class="placeholder <?php if(!empty($object['gallery'])): ?>element-invisible<?php endif; ?>">
                        <div id="filePicker"></div>
                        <p>或将照片拖到这里，单次最多可选50张，每张最大不超过5M</p>
                    </div>
                    <ul class="filelist" >
                        <?php if(is_array($object['gallery']) || $object['gallery'] instanceof \think\Collection || $object['gallery'] instanceof \think\Paginator): $i = 0; $__LIST__ = $object['gallery'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <li  class="state-complete" style="border: 1px solid rgb(59, 114, 165);">
                            <p class="title"></p>
                            <p class="imgWrap">
                                <img src="__RESOURCE_PATH__/<?php echo $vo; ?>">
                            </p>
                            <input type="hidden" v="<?php echo $vo; ?>" iv="<?php echo $vo; ?>" class="j-gallery-img">
                            <span class="btn-del">删除</span>
                        </li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
                <div class="statusBar" <?php if(empty($object['gallery'])): ?>style="display: none;"<?php endif; ?>>
                <div class="progress" style="display: none;">
                    <span class="text">0%</span>
                    <span class="percentage" style="width: 0%;"></span>
                </div>
                <div class="info"></div>
                <div class="btns">
                    <div id="filePicker2" style="height: auto;"></div><div class="uploadBtn">开始上传</div>
                </div>
            </div>
            </div>
        </td>

    <tr>
        <th>
            <div style="font-size: 13px;font-weight: bold;margin: 10px 0 0 10px;">商品销售信息</div>
        </th>
        <td></td>
    </tr>
    <tr>
        <th>店铺价格<font color='red'>*</font>：</th>
        <td><input type='text' class='j-ipt ipwd' id='shopPrice' value='<?php echo $object["shopPrice"]; ?>' maxLength='10' data-rule='店铺价格:required;price' data-rule-price="[/^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/, '价格必须大于0']" onblur="javascript:WST.limitDecimal(this,2)" onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/></td>
    </tr>
    <tr>
        <th>市场价格<font color='red'>*</font>：</th>
        <td><input type='text' class='j-ipt ipwd' id='marketPrice' value='<?php echo $object["marketPrice"]; ?>' maxLength='10' data-rule='市场价格:required;price' data-rule-price="[/^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/, '价格必须大于0']" onblur="javascript:WST.limitDecimal(this,2)" onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/></td>
    </tr>
    <tr>
        <th>商品单位<font color='red'>*</font>：</th>
        <td><input type='text' class='j-ipt ipwd' id='goodsUnit' value='<?php echo $object["goodsUnit"]; ?>' maxLength='10' data-rule='商品单位:required;'/></td>
    </tr>
    <tr id='goodsStockTr' <?php if(($object["goodsType"]==1)): ?>style='display:none'<?php endif; ?>>
    <th>商品库存<font color='red'>*</font>：</th>
    <td><input type='text' class='j-ipt ipwd' id='goodsStock' value='<?php echo $object["goodsStock"]; ?>' maxLength='10' data-rule='商品库存:required;integer[+0]' onkeypress="return WST.isNumberKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/></td>
    <td><font color='red'>库存为0时仅做展示</font></td>
    </tr>
    <tr>
        <th>商品状态<font color='red'>*</font>：</th>
        <td class="layui-form">
            <label><input type='radio' name='isSale' id="isSale-1" class='j-ipt' value='1' <?php if($object['isSale']==1): ?>checked<?php endif; ?> title='上架'/></label>
            <label><input type='radio' name='isSale' id="isSale-0" class='j-ipt' value='0' <?php if($object['isSale']==0): ?>checked<?php endif; ?> title='下架'/></label>
        </td>
    </tr>
    <tr>
        <th>售卖选项<font color='red'>*</font>：</th>
        <td class="layui-form">
            <label><input type='radio' name='saleType' id='sType0' class='j-ipt' value='0' <?php if($object['saleType']==0): ?>checked<?php endif; ?> title='线上销售'/></label>
            <label><input type='radio' name='saleType' id='sType1' class='j-ipt' value='1' <?php if($object['saleType']==1): ?>checked<?php endif; ?> title='议价'/></label>
            <label><input type='radio' name='saleType' id='sType2' class='j-ipt' value='2' <?php if($object['saleType']==2): ?>checked<?php endif; ?> title='仅做展示'/></label>
        </td>
    </tr>
    <tr>
        <th>商品属性：</th>
        <td class='layui-form'>
            <input id="isRecom" name='isRecom' lay-skin="primary" class="j-ipt" <?php if($object['isRecom']==1): ?>checked<?php endif; ?> value="1" type="checkbox" title='推荐' />
            <input id="isBest" name="isBest" lay-skin="primary" class="j-ipt" <?php if($object['isBest']==1): ?>checked<?php endif; ?> value="1" type="checkbox" title='精品'/>
            <input id="isNew" name="isNew" lay-skin="primary" class="j-ipt" <?php if($object['isNew']==1): ?>checked<?php endif; ?> value="1" type="checkbox" title='新品'/>
            <input id="isHot" name="isHot" lay-skin="primary" class="j-ipt" <?php if($object['isHot']==1): ?>checked<?php endif; ?> value="1" type="checkbox" title='热销'/>
        </td>
    </tr>
    <tr id='goodsWeightTr' <?php if(($object["goodsType"]==1)): ?>style='display:none'<?php endif; ?>>
    <th>商品重量(kg)<font color='red'>*</font>：</th>
    <td><input type='text' class='j-ipt ipwd' id='goodsWeight' value='<?php echo $object["goodsWeight"]; ?>' maxLength='10' onblur="javascript:WST.limitDecimal(this,2)" onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/></td>
    </tr <?php if(($object["goodsType"]==1)): ?>style='display:none'<?php endif; ?>>
    <tr id='goodsVolumeTr' <?php if(($object["goodsType"]==1)): ?>style='display:none'<?php endif; ?>>
    <th>商品体积(m³)<font color='red'>*</font>：</th>
    <td><input type='text' class='j-ipt ipwd' id='goodsVolume' value='<?php echo $object["goodsVolume"]; ?>' maxLength='10' onblur="javascript:WST.limitDecimal(this,2)" onkeypress="return WST.isNumberdoteKey(event)" onkeyup="javascript:WST.isChinese(this,1)"/></td>
    </tr>

    <tr>
        <th>
            <div style="font-size: 13px;font-weight: bold;margin: 10px 0 0 10px;">商品属性信息</div>
        </th>
        <td></td>
    </tr>
    <tr>
        <th width='150'><font color='red'>*</font>作者：</th>
        <td width='300'>
            <input type='text' class='j-ipt' id='goodsAuthor' value='<?php echo $object["goodsAuthor"]; ?>' maxLength='100'/>
        </td>
    </tr>
    <tr>
        <th width='150'><font color='red'>*</font>尺寸(CM)：</th>
        <td width='300'>
            <input type='text' class='j-ipt' id='goodsTips' value='<?php echo $object["goodsTips"]; ?>' maxLength='100'/>
        </td>
    </tr>
    <tr>
        <th width='150'><font color='red'>*</font>创作时间：</th>
        <td width='300'>
            <input type='text' class='j-ipt' id='createTime' value='<?php echo $object["createTime"]; ?>' maxLength='100'/>
        </td>
    </tr>
    <tr>
        <th width='150'>材质：</th>
        <td width='300'>
            <input type='text' class='j-ipt' id='goodsTc' value='<?php echo $object["goodsCz"]; ?>' maxLength='100'/>
        </td>
    </tr>
    <tr>
        <th width='150'>题材：</th>
        <td width='300'>
            <input type='text' class='j-ipt' id='goodsCz' value='<?php echo $object["goodsTc"]; ?>' maxLength='100'/>
        </td>
    </tr>

  <tr>
     <td align='center' style='text-align:center;padding-top:10px;'></td>
  </tr>
</table>
        </div>
        <div class="wst-tab-item" style="position: relative;display:none">
        
<table class='wst-form' style="margin-top: 40px">
    <tr>
        <th>
            <div style="font-size: 13px;font-weight: bold;margin: 10px 0 0 10px;">自定义作者信息(选填)</div>
        </th>
        <td></td>
    </tr>
    <tr>
        <th>作者头像<font color='red'>*</font>：</th>
        <td>
            <input type="text" id='goodsImg' readonly="readonly" value='<?php if($object["goodsImg"]!=''): ?><?php echo $object["goodsImg"]; else: ?><?php echo WSTConf('CONF.goodsLogo'); ?><?php endif; ?>' class="j-ipt" style="float: left; width: 655px;" />
            <div id='goodsImgPicker' style="float: left;margin-top: 5px;">上传</div><span id='uploadMsg'></span>
            <div id='goodsImgBox' style='margin-bottom:5px; float: left; height: 30px; margin-left: 5px;'>
            <span class='weixin'>
                  <img class='img' style='height:16px;width:18px;' src='/static/images/upload-common-select.png'>
                  <img class='imged'  id='preview'  style='max-height:150px;max-width: 200px; border:1px solid #dadada; background:#fff' src="__RESOURCE_PATH__/<?php if($object['goodsImg']!=''): ?><?php echo $object['goodsImg']; else: ?><?php echo WSTConf('CONF.goodsLogo'); ?><?php endif; ?>">
                </span>
            </div>
            <div class="f-clear"></div>
            <span class='msg-box' id='msg_goodsImg'></span>
        </td>
    </tr>
    <tr>
        <th>作者简介<font color='red'>*</font>：</th>
        <td>
            <textarea rows="2" cols="60" id='goodsAuthorDesc' class='j-ipt' name='goodsAuthorDesc' data-rule='商品描述:required;'><?php echo $object['goodsAuthorDesc']; ?></textarea>
        </td>
    </tr>
    <tr>
        <th>作者年表：</th>
        <td>
            <textarea rows="2" cols="60" id='zznb' class='j-ipt' name='zznb' ><?php echo $object['zznb']; ?></textarea>
        </td>

    </tr>
    <tr>
        <th>所在院系：</th>
        <td>
            <input type="text" class='j-ipt ipwd' id='szyx' maxLength='150'  value="<?php echo $object["szyx"]; ?>">
        </td>
    </tr>
    <tr>
        <th>指导老师：</th>
        <td>
            <input type="text" class='j-ipt ipwd' id='zdls' maxLength='150'  value="<?php echo $object["zdls"]; ?>">
        </td>
    </tr>

    <tr>
        <td align='center' style='text-align:center;padding-top:10px;'></td>
    </tr>
</table>
        </div>
        <div class="wst-tab-item" style="position: relative;display:none">
        
<table class='wst-form' style="margin-top: 40px">
    <tr>
        <th>
            <div style="font-size: 13px;font-weight: bold;margin: 10px 0 0 10px;">自定义商品描述(选填)</div>
        </th>
        <td></td>
    </tr>
    <tr>
        <th>作者头像<font color='red'>*</font>：</th>
        <td>
            <input type="text" id='goodsImg' readonly="readonly" value='<?php if($object["goodsImg"]!=''): ?><?php echo $object["goodsImg"]; else: ?><?php echo WSTConf('CONF.goodsLogo'); ?><?php endif; ?>' class="j-ipt" style="float: left; width: 655px;" />
            <div id='goodsImgPicker' style="float: left;margin-top: 5px;">上传</div><span id='uploadMsg'></span>
            <div id='goodsImgBox' style='margin-bottom:5px; float: left; height: 30px; margin-left: 5px;'>
            <span class='weixin'>
                  <img class='img' style='height:16px;width:18px;' src='/static/images/upload-common-select.png'>
                  <img class='imged'  id='preview'  style='max-height:150px;max-width: 200px; border:1px solid #dadada; background:#fff' src="__RESOURCE_PATH__/<?php if($object['goodsImg']!=''): ?><?php echo $object['goodsImg']; else: ?><?php echo WSTConf('CONF.goodsLogo'); ?><?php endif; ?>">
                </span>
            </div>
            <div class="f-clear"></div>
            <span class='msg-box' id='msg_goodsImg'></span>
        </td>
    </tr>
    <tr>
        <th>商品描述<font color='red'>*</font>：</th>
        <td>
            <textarea rows="2" cols="60" id='goodsDesc' class='j-ipt' name='goodsDesc' ><?php echo $object['goodsDesc']; ?></textarea>
        </td>
    </tr>
</table>
        </div>
          <div class="wst-tab-item" style="position: relative;display:none">
              
<table class='wst-form' style="margin-top: 40px">
    <tr>
        <th style="font-weight: bold;width: 100%;text-align: center;font-size: 20px;padding-top: 130px;margin-top: 177px;padding-bottom:130px">
            填写完成，点击下方"保存"
        </th>
    </tr>
</table>
          </div>
     </form>
         <div id="nextFlbtn" class="flbtn">
             <button type='button' class="btn btn-primary btn-mright" onclick='javascript:prevNext("prev")' style="margin-left: 40%;"><i class="fa fa-angle-double-left"></i>上一步</button>
             <button type='button' class="btn" onclick="prevNext('next')" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>下一步</button>
        <!--<button type='button' class="btn btn-primary btn-mright" onclick='javascript:save(<?php echo $p; ?>)' style="margin-left: 40%;"><i class="fa fa-check"></i>保&nbsp;存</button>
        <button type='button' class="btn" onclick="toBack(<?php echo $p; ?>,'<?php echo $src; ?>')" style="margin-left: 10px;"><i class="fa fa-angle-double-left"></i>返&nbsp;回</button>-->
        </div>
        <div id="saveFlbtn" class="flbtn" style="display: none">
            <button type='button' class="btn btn-primary btn-mright" onclick='javascript:prevNext("prev")' style="margin-left: 40%;"><i class="fa fa-angle-double-left"></i>上一步</button>
            <button type='button' class="btn" onclick="save(<?php echo $p; ?>)" style="margin-left: 10px;"><i class="fa fa-angle-double-left"></i>保存</button>
        </div>
    </div>
</div>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script src="/static/plugins/kindeditor/kindeditor.js?v=<?php echo $v; ?>" type="text/javascript" ></script>
<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='/static/plugins/webuploader/batchupload.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__SHOP__/goods/goods.js?v=<?php echo $v; ?>'></script>
<script>
var initBatchUpload = false,editor1 = null,specNum = 0,src='<?php echo $src; ?>';
<?php unset($object['goodsDesc']); ?>
var OBJ = <?=json_encode($object)?>;
$(function(){initEdit()});
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>