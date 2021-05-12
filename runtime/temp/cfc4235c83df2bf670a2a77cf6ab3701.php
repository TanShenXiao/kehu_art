<?php /*a:2:{s:69:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\orders\change.html";i:1602924239;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<div style='margin:10px;'>
  <div class='order-box'>
    <div class='box-head'>日志信息</div>
    <?php if(in_array($object['orderStatus'],[-2,0,1,2])): ?>
      <div class='log-box'>
      <div class="state">
      <?php if($object['payType']==1): ?>
        <div class="icon">
        	<span class="icons <?php if(($object['orderStatus']==-2)OR($object['orderStatus']==0)OR($object['orderStatus']==1)OR($object['orderStatus']==2)): ?>icon12 <?php else: ?>icon11 <?php endif; if(($object['orderStatus']==-2)): ?>icon13 <?php endif; ?>"></span>
        </div>
        <div class="arrow <?php if(($object['orderStatus']==0) OR ($object['orderStatus']==1) OR ($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">··················></div>
        	<div class="icon"><span class="icons <?php if(($object['orderStatus']==0)OR($object['orderStatus']==1)OR($object['orderStatus']==2)): ?>icon22 <?php else: ?>icon21<?php endif; if(($object['orderStatus']==0)): ?>icon23 <?php endif; ?>"></span></div>
        	<div class="arrow <?php if(($object['orderStatus']==1) OR ($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">··················></div>
      <?php else: ?>
        <div class="icon">
        	<span class="icons <?php if(($object['orderStatus']==-2)OR($object['orderStatus']==0)OR($object['orderStatus']==1)OR($object['orderStatus']==2)): ?>icon12 <?php else: ?>icon11 <?php endif; if(($object['orderStatus']==0)): ?>icon13 <?php endif; ?>"></span>
        </div>
        <div class="arrow <?php if(($object['orderStatus']==1) OR ($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">··················></div>
      <?php endif; ?>
      <div class="icon">
      	<span class="icons <?php if(($object['orderStatus']==1)OR($object['orderStatus']==2)OR($object['orderStatus']==1)): ?>icon32 <?php else: ?>icon31 <?php endif; if(($object['orderStatus']==1)): ?>icon33 <?php endif; ?>"></span>
      </div>
      <div class="arrow <?php if(($object['orderStatus']==2)): ?>arrow2<?php endif; ?>">··················></div>
      <div class="icon"><span class="icons  <?php if(($object['orderStatus']==2)AND($object['isAppraise']==1)): ?>icon42 <?php else: ?>icon41 <?php endif; if(($object['orderStatus']==2)AND($object['isAppraise']==0)): ?>icon43 <?php endif; ?>"></span></div>
      <div class="arrow <?php if(($object['isAppraise']==1)): ?>arrow2<?php endif; ?>">··················></div>
      <div class="icon"><span class="icons <?php if(($object['isAppraise']==1)): ?>icon53 <?php else: ?>icon51 <?php endif; ?>"></span></div>
      </div>
         <div class="state2">
         <div class="path">
         <?php if(is_array($object['log']) || $object['log'] instanceof \think\Collection || $object['log'] instanceof \think\Paginator): $i = 0; $__LIST__ = $object['log'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lo): $mod = ($i % 2 );++$i;?>
         	<span><?php echo $lo['logContent']; ?><br/><?php echo $lo['logTime']; ?></span>
         <?php endforeach; endif; else: echo "" ;endif; ?>
         </div>
         <p>下单</p><?php if($object['payType']==1): ?><p>等待支付</p><?php endif; ?><p>商家发货</p><p>确认收货</p><p>订单结束<br/>双方互评</p>
         </div>
         <div class="wst-clear"></div>
         </div>
    <?php else: ?>
        <div class="odcont">
          <table class='log'>
            <?php if(is_array($object["log"]) || $object["log"] instanceof \think\Collection || $object["log"] instanceof \think\Paginator): $i = 0; $__LIST__ = $object["log"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
             <tr>
               <td><?php echo $vo['logTime']; ?></td>
               <td><?php echo $vo['logContent']; ?></td>
             </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </table>
        </div>                 
    <?php endif; ?>
   </div>
   <div style='width:100%;padding-bottom:30px;height:auto;'>
     <div style='width:300px;float:left;'>
       <div style='width:100%;min-height:300px;height:auto;' class='order-box'>
           <div style='background:#eee;width:100%' class='box-head'>订单信息</div>
           <ul style='width:100%'>
              <li style='height:30px;line-height: 30px;padding-left:10px;'><span>订单编号：</span><?php echo $object['orderNo']; ?></li>
              <li style='height:30px;line-height: 30px;padding-left:10px;'><span>商品金额：</span><?php echo $object['goodsMoney']; ?></li>
              <li style='height:30px;line-height: 30px;padding-left:10px;'><span>运&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;费：</span><?php echo $object['deliverMoney']; ?></li>
              <li style='height:30px;line-height: 30px;padding-left:10px;'><span>订单金额：</span><?php echo $object['totalMoney']; ?></li>
              <li style='height:30px;line-height: 30px;padding-left:10px;'><span>实付总金额：¥<span><?php echo $object['realTotalMoney']; ?></li>
              <li style='height:30px;line-height: 30px;padding-left:10px;'><span>下单时间：</span></li>
              <li style='height:30px;line-height: 30px;padding-left:10px;'><span>支付方式：</span><?php echo WSTLangPayType($object['payType']); ?></li>
              <li style='height:30px;line-height: 30px;padding-left:10px;'><span>配送方式：</span><?php echo WSTLangDeliverType($object['deliverType']); ?></li>
              <li style='height:30px;line-height: 30px;padding-left:10px;'><span>商&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;家：</span></li>
           </ul>
       </div>
       <div style='width:100%;min-height:300px;height:auto;' class='order-box'>
           <div style='background:#eee;width:100%' class='box-head'>订单商品</div>
           <div style='width:100%;padding:5px;'>
           <?php if(is_array($object["goods"]) || $object["goods"] instanceof \think\Collection || $object["goods"] instanceof \think\Paginator): $i = 0; $__LIST__ = $object["goods"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
           <a href='<?php echo Url("home/goods/detail","goodsId=".$vo2["goodsId"]); ?>' target='_blank'>
              <img src='__RESOURCE_PATH__/<?php echo $vo2["goodsImg"]; ?>' width='80' height='80' title='<?php echo WSTStripTags($vo2["goodsName"]); ?>'/>
           </a>
           <?php endforeach; endif; else: echo "" ;endif; ?>
           </div>
       </div>
     </div>
     <div style='width:calc(100% - 310px);min-height:622px;height:auto;float:left;margin-left:10px;' class='order-box force-order-r'>
        <div style='background:#eee;width:100%' class='box-head'>订单状态</div>
        <table class='wst-form'>
           <tr>
              <th width='150'>变更状态：</td>
              <td class='layui-form'>
                
                <?php if($object['orderStatus']==-2): ?>
                  <label><input type='radio' name='orderStatus' lay-filter="orderStatus" value='0' title='订单已支付【系统只是标记状态，不会有实际的扣款行为】'></label>
                <?php endif; if(in_array($object['orderStatus'],[-2,0])): ?>
                  <label><input type='radio' name='orderStatus' lay-filter="orderStatus" value='-1' title='取消订单【取消订单，所支付的金额和积分原路返回】'></label>
                <?php endif; if(in_array($object['orderStatus'],[1,-3,2])): ?>
                  <!-- label><input type='radio' name='orderStatus' lay-filter="orderStatus" value='-3' title='拒收订单'></label -->
                  <label><input type='radio' name='orderStatus' lay-filter="orderStatus" value='2' title='确认收货'></label>
                <?php endif; ?>
              </td>
           </tr>

           
           <tr class='result_0' style='display: none'>
              <th width='120'>支付方式<font color='red'>*</font>：</th>
              <td>
                  <select id="payFrom_0">
                    <option value="-1">请选择支付方式</option>
                    <?php if(is_array($payMents) || $payMents instanceof \think\Collection || $payMents instanceof \think\Paginator): $i = 0; $__LIST__ = $payMents;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pays): $mod = ($i % 2 );++$i;if($pays['payCode']!='wallets'): ?>
                    <option value="<?php echo $pays['payCode']; ?>"><?php echo $pays['payName']; ?></option>
                    <?php endif; ?>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                  </select>
              </td>
            </tr>
            <tr class='result_0' id='otherPay_0' style='display: none'>
                <th>外部流水号<font color='red'>*</font>：</th>
                <td><input type="text" id="trade_no_0" autocomplete="false" style='width:60%'></td>
            </tr>

            
            <tr class='result_-1' style='display: none'>
                <th width='120'>退回金额：</th>
                <td>
                    <?php if(($object['payType']==1 && $object['isPay']==1)): ?>
                    <input type="text" id="realTotalMoney_0" autocomplete="false" style='width:150px;margin-right:10px;'><font color='red'>(实付金额￥<?php echo $object['realTotalMoney']; ?>)</font>
                    <?php else: ?>
                    ￥0
                    <?php endif; ?>
                </td>
            </tr>
            <tr class='result_-1' style='display: none'>
                <th width='120'>退回积分：</th>
                <td><?php if(($object['useScore']>0)): ?><?php echo $object['useScore']; else: ?>0<?php endif; ?></td>
            </tr>
           <tr>
              <td colspan="2" align="center" style='text-align:center;'>
                  <button type="button" onclick='javascript:changeOrder(<?php echo $object['orderId']; ?>,<?php echo $p; ?>)' class='btn btn-primary btn-mright'><i class="fa fa-check"></i>确定更改</button>
                  <button type="button" onclick="javascript:location.href='<?php echo Url('admin/orders/index','p='.$p); ?>'" class='btn'><i class="fa fa-angle-double-left"></i>返回</button>
              </td>
           </tr>
        </table>
     </div>
     <div style='clear:both;'></div>
  </div>
<div>
<script>$(function(){initChange();})</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="__ADMIN__/orders/orders.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>