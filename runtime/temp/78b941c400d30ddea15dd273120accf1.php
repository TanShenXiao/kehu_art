<?php /*a:2:{s:92:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\shop\view.html";i:1620648649;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
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
<style>
    .webuploader-pick{background:#1890ff;}
</style>

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
label{margin-right:10px;}
th{height:25px;}
</style>
<div id='tab' class="layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this">店铺信息</li>
        <li>银行信息</li>
    </ul>
    <div class="layui-tab-content" style='width:99%;margin-bottom: 10px;'>
        <div class="layui-tab-item layui-show wst-box-top" style="position: relative;">
           <table id='vinfo_1' class='wst-form'> 
			  <tr>
			     <th width='150'>店铺编号：</th>
			     <td><span style='float:left;padding: 0px 8px;'><?php echo $object['shopSn']; ?></span>
                 <a class="btn btn-blue" onclick="javascript:toEdit(1)"><i class="fa fa-pencil"></i>编辑</a>
			     </td>
			  </tr>
			  <tr>
			     <th>店铺名称：</th>
			     <td><?php echo $object['shopName']; ?></td>
			  </tr>
			  <tr>
			     <th>店铺简介：</th>
			     <td>
			     	<?php echo $object['shopBrief']; ?>
			     </td>
			  </tr>
			  <tr>
			     <th>公司紧急联系人：</th>
			     <td><?php echo $object['shopkeeper']; ?></td>
			  </tr>
			  <tr>
			     <th>公司紧急联系人手机：</th>
			     <td><?php echo $object['telephone']; ?></td>
			  </tr>
			  <tr>
			     <th>公司名称：</th>
			     <td><?php echo $object['shopCompany']; ?></td>
			  </tr>
			  <tr>
			     <th>店铺联系电话：</th>
			     <td><?php echo $object['shopTel']; ?></td>
			  </tr>
			  <tr>
			     <th>经营类目：</th>
			     <td>
			      <?php if(is_array($object['catshopNames']) || $object['catshopNames'] instanceof \think\Collection || $object['catshopNames'] instanceof \think\Paginator): $i = 0; $__LIST__ = $object['catshopNames'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			      <div style='width:200px;float:left;line-height: 18px;'>
			      	<?php echo $vo; ?>
			      </div>
			      <?php endforeach; endif; else: echo "" ;endif; ?>
			     </td>
			  </tr>
			  <tr>
			     <th>认证类型：</th>
			     <td>
			       <?php $accredLen = count($object['accreds']); if(is_array($object['accreds']) || $object['accreds'] instanceof \think\Collection || $object['accreds'] instanceof \think\Paginator): $i = 0; $__LIST__ = $object['accreds'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			       <?php echo $vo["accredName"]; if($i < $accredLen): ?>、<?php endif; ?>
			       <?php endforeach; endif; else: echo "" ;endif; ?>
			     </td>
			  </tr>
			  <tr>
			     <th>店铺图标：</th>
			     <td>
			     <img id='v_shopImg' width='150' height='150' src='__RESOURCE_PATH__/<?php echo $object["shopImg"]; ?>'/>
			     </td>
			  </tr>
			  <tr>
			     <th>客服QQ：</th>
			     <td id='v_shopQQ'><?php echo $object['shopQQ']; ?></td>
			  </tr>
			  <tr>
			     <th>阿里旺旺：</th>
			     <td id='v_shopWangWang'><?php echo $object['shopWangWang']; ?></td>
			  </tr>
			  <tr>
			     <th>店铺地址：</th>
			     <td>
			       <?php echo $object['areaName']; ?>
			     </td>
			  </tr>
			  <tr>
			     <th>店铺详细地址：</th>
			     <td><?php echo $object['shopAddress']; ?></td>
			  </tr>
			  <tr>
			     <th>是否提供开发票：</th>
			     <td id='v_isInvoice'>
			        <?php if($object['isInvoice']==1): ?>提供发票<?php endif; if($object['isInvoice']==0): ?>不提供发票<?php endif; ?>  
			     </td>
			  </tr>
			  <tr id='tr_isInvoice' <?php if($object['isInvoice']==0): ?>style='display:none'<?php endif; ?>>
			     <th>发票说明：</th>
			     <td id='v_invoiceRemarks'><?php echo $object['invoiceRemarks']; ?></td>
			  </tr>
			  <tr>
			     <th>默认运费：</th>
			     <td >¥<span id='v_freight'><?php echo $object['freight']; ?></span></td>
			  </tr>
			  <tr>
			     <th>服务时间：</th>
			     <td><span id='v_serviceStartTime'><?php echo $object['serviceStartTime']; ?></span>至<span id='v_serviceEndTime'><?php echo $object['serviceEndTime']; ?></span>
			     </td>
			  </tr>
           </table>
           <form id='editFrom_1' autocomplete="off">
           <table id='einfo_1' class='wst-form hide'>
			  <tr>
			     <th width='150'>店铺图标<font color='red'>*</font>：</th>
			     <td>
			     	<input type='text' id='shopImg' class='ipt_1' value='<?php echo $object["shopImg"]; ?>' style="width: 500px; float: left;" />
			     	<div id='shopImgPicker' style='float: left; margin-top:5px;'>上传</div><span id='uploadMsg'></span>
			     	<div id='shopImgBox' style='margin-bottom:5px; float: left; height: 30px; margin-left: 5px;'>
			     		<span class='weixin'>
			     			<img class='img' style='height:16px;width:18px;' src='/static/images/upload-common-select.png'>
			     			<img class='imged'  id='preview'  style='max-height:150px;max-width: 200px; border:1px solid #dadada; background:#fff' src="__RESOURCE_PATH__/<?php if($object['shopImg']!=''): ?><?php echo $object['shopImg']; else: ?><?php echo WSTConf('CONF.goodsLogo'); ?><?php endif; ?>">
			     		</span>
			     	</div>
			     </td>
			  </tr>
			   <tr>
				   <th>店铺名称：</th>
				   <td>
					   <input class="ipt_1" id="shopName" value="<?php echo $object['shopName']; ?>" type="text">
				   </td>
			   </tr>
			   <tr>
				   <th>店铺联系电话：</th>
				   <td>
					   <input class="ipt_1" id="shopTel" value="<?php echo $object['shopTel']; ?>" type="text">
				   </td>
			   </tr>
			  <tr>
			     <th>店铺简介：</th>
			     <td>
			     	<textarea rows="4" class="ipt_1" id="shopBrief" style='width:60%'><?php echo $object['shopBrief']; ?></textarea>
			     </td>
			  </tr>
			  <tr>
			     <th>客服QQ：</th>
			     <td>
			     	<input class="ipt_1" id="shopQQ" value="<?php echo $object['shopQQ']; ?>" type="text">
			     </td>
			  </tr>
			  <tr>
			     <th> </th>
			     <td>
			     	<span style='color:gray;'>做为客服接收临时消息的QQ,需开通<a target="_blank" href="http://shang.qq.com/v3/index.html">QQ推广功能</a> -> '首页'-> '推广工具'-> '立即免费开通'</span>
			     </td>
			  </tr>
			  <tr>
			     <th>阿里旺旺：</th>
			     <td><input class="ipt_1" id="shopWangWang" value="<?php echo $object['shopWangWang']; ?>" type="text"></td>
			  </tr>
			  <tr>
			     <th>是否提供开发票<font color='red'>*</font>：</th>
			     <td class="layui-form">
			        <label>
			        	<input type='radio' value='1' class="ipt_1" name='isInvoice' onclick='javascript:WST.showHide(1,"#trInvoice")' <?php if($object['isInvoice']==1): ?>checked<?php endif; ?> title='提供'/>
			        </label>
			        <label>
			        	<input type='radio' value='0' class="ipt_1" name='isInvoice' onclick='javascript:WST.showHide(0,"#trInvoice")' <?php if($object['isInvoice']==0): ?>checked<?php endif; ?> title='不提供'/>
			        </label>
			     </td>
			  </tr>
			  <tr id='trInvoice' <?php if($object['isInvoice']==0): ?>style='display:none'<?php endif; ?>>
			     <th>发票说明<font color='red'>*</font>：</th>
			     <td><input class="ipt_1" id="invoiceRemarks" value="<?php echo $object['invoiceRemarks']; ?>" type="text" data-rule="发票说明:required(#isInvoice1:checked)"></td>
			  </tr>
			  <tr>
			     <th>默认运费<font color='red'>*</font>：</th>
			     <td>¥<input class="ipt_1" id="freight" value="<?php echo $object['freight']; ?>" size='5' maxlength="10" data-rule="默认运费:required;" type="text" style='margin-left:2px;width: 560px'></td>
			  </tr>
			  <tr>
			     <th>服务时间<font color='red'>*</font>：</th>
			     <td>
			     <select class='ipt_1' id='serviceStartTime' v="<?php echo $object['serviceStartTime']; ?>"></select>
		         至
		         <select class='ipt_1' id='serviceEndTime' v="<?php echo $object['serviceEndTime']; ?>"></select>
			     </td>
			  </tr>
			   <tr>
				   <th width='150'>店铺状态<font color='red'>*</font>：</th>
				   <td class='layui-form'>
					   <label>
						   <input type='radio' class='ipt_1' name='shopStatus' id='shopStatus-1' value='-1' <?php if($object['shopStatus']==-1): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(1,"#trStatusDesc")' title='停止'>
					   </label>
					   <label>
						   <input type='radio' class='ipt_1' name='shopStatus' value='1' <?php if($object['shopStatus']==1): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(0,"#trStatusDesc")' title='正常'>
					   </label>
				   </td>
			   </tr>
			   <tr id='trStatusDesc' <?php if($object['shopStatus']==1): ?>style='display:none'<?php endif; ?>>
			   <th>停止原因<font color='red'>*</font>：</th>
			   <td><textarea id='statusDesc' class='ipt_1' style='width:500px;height:100px;' maxLength='100' data-rule="停止原因:required(#shopStatus-1:checked);"><?php echo $object['statusDesc']; ?></textarea></td>
			   </tr>
			  <tr>
			  	<td colspan='2' style="padding-left: 155px;">
                    <button type="submit" class="btn btn-primary btn-mright" onclick="javascript:editInfo()"><i class="fa fa-check"></i>保&nbsp;存</button>
                    <button type="button" class="btn" onclick="javascript:toCancel(1)" style="margin-left: 10px;"><i class="fa fa-angle-double-left"></i>返&nbsp;回</button>
			  	</td>
			  </tr>
           </table>
           </form>
        </div>

        <div class="layui-tab-item" style="display:none;">
           <table class='wst-form wst-box-top'  id='vinfo_2'>
              <tr>
			     <th width='150'>开卡银行：</th>
			     <td><?php echo $object['bankName']; ?><a class="btn btn-blue" onclick="javascript:toEdit(2)"><i class="fa fa-pencil"></i>编辑</a></td>

			  </tr>
			  <tr>
			     <th width='150'>开卡地区：</th>
			     <td><?php echo $object['bankAreaName']; ?></td>
			  </tr>
              <tr>
			     <th>卡号：</th>
			     <td><?php echo $object['bankNo']; ?></td>
			  </tr>
			  <tr>
			     <th>持卡人：</th>
			     <td><?php echo $object['bankUserName']; ?></td>
			  </tr>
			  
           </table>
			<form id='editFrom_2' autocomplete="off">
				<table id='einfo_2' class='wst-form hide'>
					<tr>
						<th>开卡银行：</th>
						<td>
							<select id="bankId" class='a-ipt ipt_2'>
								<?php 
								$banks = WSTTable('banks',['dataFlag'=>1],'bankId,bankName',100);
								foreach($banks as $aky => $bank){
								 ?>
								<option value="<?php echo $bank['bankId']; ?>" <?php if($object['bankId']==$bank['bankId']): ?>selected<?php endif; ?>><?php echo $bank['bankName']; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th>开卡地区：</th>
						<td>
							<select id="bankAreaId" class="j-bankAreaIdPath" data-name="bankAreaIdPath" level="0" onchange="WST.ITAreas({id:'bankAreaId',val:this.value,isRequire:true,className:'j-bankAreaIdPath'});" data-value="<?php echo $object['areaIdPath']; ?>">
							<option value="">-请选择-</option>
								<?php 
								$areas = WSTTable('areas',['isShow'=>1,'dataFlag'=>1,'parentId'=>0],'areaId,areaName',100,'areaSort desc');
								foreach($areas as $aky => $area){
								 ?>
								<option value="<?php echo $area['areaId']; ?>"><?php echo $area['areaName']; ?></option>
								<?php } ?>
							</select>
						</td>
					</tr>
					<tr>
						<th>卡号:</th>
						<td>
							<input class="ipt_2" id="bankNo" value="<?php echo $object['bankNo']; ?>" type="text">
						</td>
					</tr>
					<tr>
						<th>持卡人：</th>
						<td><input class="ipt_2" id="bankUserName" value="<?php echo $object['bankUserName']; ?>" type="text">
						</td>
					</tr>
					<tr>
						<td colspan='2' style="padding-left: 155px;">
							<button type="submit" class="btn btn-primary btn-mright" onclick="javascript:editBankInfo()"><i class="fa fa-check"></i>保&nbsp;存</button>
							<button type="button" class="btn" onclick="javascript:toCancel(1)" style="margin-left: 10px;"><i class="fa fa-angle-double-left"></i>返&nbsp;回</button>
						</td>
					</tr>
				</table>
			</form>
        </div>
    </div>
</div>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='__SHOP__/shop/shops.js?v=<?php echo $v; ?>'></script>
<script>
$(function(){
	$(function(){
		var bankAreaIdPath = "<?php echo $object['bankAreaIdPath']; ?>";
		var bankAreaIdPath = bankAreaIdPath.split("_");
		$('#bankAreaId').val(bankAreaIdPath[0]);
		var aopts = {id:'bankAreaId',val:bankAreaIdPath[0],childIds:bankAreaIdPath,className:'j-bankAreaIdPath',isRequire:true}
		WST.ITSetAreas(aopts);
	})
//	$('#tab').TabPanel({tab:0,callback:function(no){}});
})
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>