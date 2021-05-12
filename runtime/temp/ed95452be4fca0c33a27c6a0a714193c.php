<?php /*a:2:{s:103:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\orders\list_finished.html";i:1602924177;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
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

  <div class='wst-toolbar'>
     <input type='text' class="s-ipt" id='orderNo' placeholder='订单号'/> 
     <select name="payType" id="payType" class="s-ipt">
                <option value="-1">支付方式</option>
                <option value="0">货到付款</option>
                <option value="1">在线支付</option>
               </select>

     <select name="deliverType" id="deliverType" class="s-ipt">
                <option value="-1">配送方式</option>
                <option value="0">送货上门</option>
                <option value="1">自提</option>
               </select>

     <a class="btn btn-primary" onclick="finisedByPage()"><i class="fa fa-search"></i>查询</a>
     <a class="btn btn-primary" style="float:right;" onclick="javascript:toExport(2,2,'')"><i class="fa fa-sign-out"></i>导出</a>
  </div>
  <div class='wst-grid'>
    <table class='wst-order-list'>
       <thead>
	      <tr class='head'>
	         <th class="th-padding">订单详情</th>
	         <th width="115">支付方式/配送信息</th>
	         <th style="text-align: center;">金额</th>
	         <th width="110" style="text-align: center;">操作</th>
	      </tr>
	   </thead>
	   <tbody id='loadingBdy'>
	       <tr id='loading' class='empty-row' style='display:none'>
	            <td colspan='4'><img src="__SHOP__/img/loading.gif">正在加载数据...</td>
	       </tr>
     </tbody>
       <script id="tblist" type="text/html">
       {{# for(var i = 0; i < d.length; i++){ }}
       <tbody class="j-order-row {{#if(d[i].isAppraise==0){}}j-warn{{#} }}">
         <tr>
           <td class='order-head'>
                  <div class='time'>{{d[i].createTime}}</div>
                  <div class='orderno'>订单号：{{d[i].orderNo}}
                    {{# if(d[i].orderSrc==0){ }}<i class="order-pc"></i>
                    {{# }else if(d[i].orderSrc==1){ }}<i class="order-wx"></i>
                    {{# }else if(d[i].orderSrc==2){ }}<i class="order-mo"></i>
                    {{# }else if(d[i].orderSrc==3){ }}<i class="order-app"></i>
                    {{# }else if(d[i].orderSrc==4){ }}<i class="order-ios"></i>
                    {{# }else if(d[i].orderSrc==5){ }}<i class="order-weapp"></i>
                    {{# } }}
                    {{# if(d[i].orderCodeTitle!=""){ }}
                      <span class="order_from">{{d[i].orderCodeTitle}}</span>
                    {{# } }}
                  </div>
           </td>
           <td></td>
           <td></td>
           <td><div style="text-align:center;">{{d[i].status}}</div></td>
         </tr>
         {{# 
          var tmp = null,rows = d[i]['list'].length;
          for(var j = 0; j < d[i]['list'].length; j++){
             tmp = d[i]['list'][j]; 
         }}
         <tr class='goods-box'>
            <td>
               <div class='goods-img'>
                <a href="{{WST.U('home/goods/detail','goodsId='+tmp.goodsId)}}" target='_blank'>
                    <span class='weixin'><img class='img gImg' title='{{tmp.goodsName}}' style='height:40px;width:40px;' data-original='__RESOURCE_PATH__/{{tmp.goodsImg}}'><img class='imged' style='height:200px;width:200px;max-width: 200px;max-height: 200px;border: 1px solid #f1f1f1;background: #fff;' title='{{tmp.goodsName}}' src="__RESOURCE_PATH__/{{tmp.goodsImg}}"></span>
                </a>
               </div>
               <div class='goods-name'>
                 <div>{{tmp.goodsName}}</div>
                 <div>{{tmp.goodsSpecNames}}</div>
               </div>
               <div class='goods-extra'>{{tmp.goodsPrice}} x {{tmp.goodsNum}}</div>
            </td>
            {{# if(j==0){ }}
            <td rowspan="{{rows}}">
                <div style="text-align: center;">{{d[i].payTypeName}}</div>
                <div style="text-align: center;">{{d[i].deliverTypeName}}</div>
            </td>
            <td rowspan="{{rows}}">
                <div>商品金额：<span style="color: red">¥{{d[i].goodsMoney}}</span></div>
                <div class='line'>运费：<span style="color: red">¥{{d[i].deliverMoney}}</span></div>
                <div>实付金额：<span style="color: red">¥{{d[i].realTotalMoney}}</span></div>
            </td>
            <td rowspan="{{rows}}">
				{{#if(d[i].isAppraise==1){}}
				<div style="text-align:center;">已评价</div>
				{{#}else{}}
				<div style="text-align:center;">未评价</div>
				{{#}}}
         <div style="text-align:center;"><a target='blank' href='{{WST.U("shop/orders/orderPrint","id="+d[i].orderId)}}'>【打印订单】</a></div>
         <div style="text-align:center;"><a href='#none' onclick="view({{d[i].orderId}},'finished')">【订单详情】</a></div>
            </td>
            {{#}}}
         </tr>
         {{# } }}
       </tbody>
       {{# if(WST.blank(d[i].orderRemarks)!=''){  }}
       <tr>
          <td colspan="4">
            
               <div class="order_remaker">
               【用户留言】{{d[i].orderRemarks}}
               </div>
             
          </td>
         </tr>
         {{# }  }}
         <tr class='empty-row' style="border: 0px;">
            <td colspan='4'>&nbsp;</td>
         </tr>
       {{# } }}
       </script>
       <tr class='empty-row' style="border: 0px;">
            <td colspan='4' id='pager' align="center" style='padding:5px 0px 5px 0px'>&nbsp;</td>
       </tr>
    </table>
  </div>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script type="text/javascript" src="/static/js/jquery.min.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__SHOP__/orders/orders.js?v=<?php echo $v; ?>'></script>
<script>
$(function(){
	finisedByPage(<?php echo $p; ?>);
})
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>