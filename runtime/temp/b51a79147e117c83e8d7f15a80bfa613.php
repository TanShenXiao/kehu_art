<?php /*a:2:{s:68:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\finance\list.html";i:1588046487;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<style type="text/css">
  #mmg1 td:nth-child(4), #mmg2 td:nth-child(4), #mmg3 td:nth-child(4), #mmg7 td:nth-child(8), #mmg10 td:nth-child(7){color: red;}
  #mmg1 td:nth-child(5), #mmg2 td:nth-child(5), #mmg3 td:nth-child(5){color: #31c15a;}
  #mmg1 td:nth-child(6), #mmg2 td:nth-child(6), #mmg3 td:nth-child(6){color: #1890ff;}
  .head1,.head2{border-bottom: 1px solid #f2f2f2;padding: 5px;}
  .head2 .item{line-height: 30px;float: left;width: 100px;cursor: pointer;text-align: center;}
  .head2 .item.active{font-weight: bold;}
  .wst-total .inner{min-width: 160px;max-width: 180px;width:auto;margin-right:0;padding: 0;cursor: pointer;}
  .wst-total .inner .inner_right{float: none;text-align: center;width: auto;background: #f2f2f2;border-radius: 10px;margin:4px;padding: 4px 10px;}
  .wst-total .inner .inner_right span{max-width: 100%;}
</style>
<div>
  <div class="wst-toolbar head1">财务概况</div>
  <div class="wst-total wst-summary layui-col-md12" style="box-shadow:none;border-bottom:1px solid #f2f2f2;padding-left: 10px;">
    <div  id="t_totalUserMoney" class='inner'>
        <div class="inner_right"><span><?php echo $data['totalUserMoney']; ?></span><br/>
          用户余额(元)</div>
    </div>
    <div id="t_totalShopMoney" class='inner'>
      <div class="inner_right">
        <span><?php echo $data['totalShopMoney']; ?></span>&nbsp;<br/>
        商家余额(元)</div>
    </div>
    <?php if((WSTConf('CONF.isOpenSupplier')==1)): ?>
    <div id="t_totalSupplierMoney" class='inner'>
      <div class="inner_right">
        <span><?php echo $data['totalSupplierMoney']; ?></span><br/>
        供货商余额(元)</div>
    </div>
    <?php endif; ?>
    <div id="t_totalScore" class='inner'>
      <div class="inner_right">
        <span><?php echo $data['totalScore']; ?></span><br/>
        积分余额(个)</div>
    </div>
    <div style="clear:both"></div>
  </div>
  <div style="clear:both"></div>
</div>

<div style="border-top: 10px solid #f8f8f8;border-bottom: 10px solid #f8f8f8;">
  <div class="wst-toolbar head2" style="">
    <div class="item active" data="1">今日概况</div>
    <div class="item" data="2">七日概况</div>
    <div class="item" data="3">本月概况</div>
    <div style="clear:both"></div>
  </div>
  <div class="wst-total wst-summary layui-col-md12" style="box-shadow:none;border-bottom:1px solid #f2f2f2;padding-left: 10px;">

    <div id="v_rechangeMoney" class='inner'>
      <div class="inner_right">
        <span id="s_rechangeMoney">0</span><br/>
        充值金额(元)</div>
    </div>
    <div id="v_giveMoney" class='inner'>
      <div class="inner_right">
        <span id="s_giveMoney">0</span><br/>
        赠送金额(元)</div>
    </div>
    <div id="v_renewMoney" class='inner'>
      <div class="inner_right">
        <span id="s_renewMoney">0</span><br/>
        年费金额(元)</div>
    </div>
    <div id="v_cashDraw" class='inner'>
      <div class="inner_right">
        <span id="s_cashDraw">0</span><br/>
        已提现金额(元)</div>
    </div>
    <div id="v_refundMoney" class='inner'>
      <div class="inner_right">
        <span id="s_refundMoney">0</span><br/>
        已退款金额(元)</div>
    </div>
    <div id="v_giveScore" class='inner'>
        <div class="inner_right">
          <span id="s_giveScore">0</span><br/>
          赠送积分(个)</div>
    </div>
    <div id="v_exchangeScore" class='inner'>
        <div class="inner_right">
          <span id="s_exchangeScore">0</span>&nbsp;<br/>
          兑换已积分(个)</div>
    </div>
    <div id="v_commission" class='inner'>
        <div class="inner_right">
          <span id="s_commission">0</span>&nbsp;<br/>
          订单佣金(元)</div>
    </div>
    <div style="clear:both"></div>
  </div>
  <div style="clear:both"></div>
</div>
<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
    
   <ul class="layui-tab-title">
     <li id="users" class="layui-this" >用户资金</li>
     <li id="shops">商家资金</li>
     <?php if((WSTConf('CONF.isOpenSupplier')==1)): ?>
     <li id="suppliers">供货商资金</li>
     <?php endif; ?>
     <li id="rechangeMoney">充值记录</li>
     <li id="renewMoney">年费记录</li>
     <li id="cashDraw">提现记录</li>
     <li id="moneyList">资金流水</li>
     <li id="scoreList">积分流水</li>
     <li id="commissionList">订单佣金</li>
   </ul>
   <div class="layui-tab-content" style="padding: 0px 0;">
      <!--用户资金-->
      <div id="template_user" class="layui-tab-item layui-show">
         <div class="wst-toolbar">
         <input type='text' id='key1' placeholder='账号'/>
         <button class="btn btn-primary" onclick="javascript:loadUserGrid(0)"><i class="fa fa-search"></i>查询</button>
         </div>
         <div class='wst-grid'>
            <div id="mmg1" class="mmg1"></div>
            <div id="pg1" style="text-align: right;"></div>
         </div>
      </div>
      <!--商家资金-->
      <div id="template_shop" class="layui-tab-item ">
         <div class="wst-toolbar">
         <input type='text' id='key2' placeholder='账号/店铺名称'/>
         <button class="btn btn-primary" onclick="javascript:loadShopGrid(0)"><i class="fa fa-search"></i>查询</button>
         </div>
         <div class='wst-grid'>
            <div id="mmg2" class="mmg2"></div>
            <div id="pg2" style="text-align: right;"></div>
         </div>
      </div>
      <!--供货商资金-->
      <?php if((WSTConf('CONF.isOpenSupplier')==1)): ?>
      <div id="template_supplier" class="layui-tab-item ">
         <div class="wst-toolbar">
         <input type='text' id='key3' placeholder='账号/供货商名称'/>
         <button class="btn btn-primary" onclick="javascript:loadSupplierGrid(0)"><i class="fa fa-search"></i>查询</button>
         </div>
         <div class='wst-grid'>
            <div id="mmg3" class="mmg3"></div>
            <div id="pg3" style="text-align: right;"></div>
         </div>
      </div>
      <?php endif; ?>
      <!--充值记录-->
      <div id="template_flow" class="layui-tab-item ">
         <div class="wst-toolbar">
         <select id='type4'>
  		      <option value=''>会员类型</option>
  	        <option value='0'>会员</option>
  	        <option value='1'>商家</option>
            <?php if((WSTConf('CONF.isOpenSupplier')==1)): ?>
            <option value='3'>供货商</option>
            <?php endif; ?>
  	     </select>
         <input type='text' id='key4' placeholder='账号'/>
		  <input type="text" id="startDate4" name="startDate4" class="ipt laydate-icon" maxLength="20"  />
		 至
		  <input type="text" id="endDate4" name="endDate4" class="ipt laydate-icon" maxLength="20"  />
         <button class="btn btn-primary" onclick="javascript:loadRechangeGrid(0)"><i class="fa fa-search"></i>查询</button>
         <span class="f-right">充值总金额:<span style="color:red;font-weight:bold;" id="totalRechangeMoney">0</span>元&nbsp;&nbsp;&nbsp;</span>
         </div>
         <div class='wst-grid'>
            <div id="mmg4" class="mmg4"></div>
            <div id="pg4" style="text-align: right;"></div>
         </div>
      </div>
      <!--年费记录-->
      <div id="template_flow" class="layui-tab-item ">
        <div class="wst-toolbar">
          <select id='type5'>
            <option value=''>缴费对应</option>
            <option value='1'>商家</option>
            <?php if((WSTConf('CONF.isOpenSupplier')==1)): ?>
            <option value='3'>供货商</option>
            <?php endif; ?>
          </select>
          <input type='text' id='key5' placeholder='商家/供货商名称'/>
          <input type="text" id="startDate5" name="startDate5" class="ipt laydate-icon" maxLength="20"  />
          至
          <input type="text" id="endDate5" name="endDate5" class="ipt laydate-icon" maxLength="20"  />
          <button class="btn btn-primary" onclick="javascript:loadRenewGrid(0)"><i class="fa fa-search"></i>查询</button>
          <span class="f-right">年费总金额:<span style="color:red;font-weight:bold;" id="totalRenewMoney">0</span>元&nbsp;&nbsp;&nbsp;</span>
        </div>
        <div class='wst-grid'>
          <div id="mmg5" class="mmg5"></div>
          <div id="pg5" style="text-align: right;"></div>
        </div>
      </div>
      
      <!--提现记录-->
      <div id="template_flow" class="layui-tab-item ">
        <div class="wst-toolbar">
          <select id='type7'>
            <option value='-1'>会员类型</option>
            <option value='0'>会员</option>
            <option value='1'>商家</option>
            <?php if((WSTConf('CONF.isOpenSupplier')==1)): ?>
            <option value='3'>供货商</option>
            <?php endif; ?>
          </select>
          <input type='text' id='key7' placeholder='账号'/>
          <input type="text" id="startDate7" name="startDate7" class="ipt laydate-icon" maxLength="20"  />
          至
          <input type="text" id="endDate7" name="endDate7" class="ipt laydate-icon" maxLength="20"  />
          <button class="btn btn-primary" onclick="javascript:loadCashDrawGrid(0)"><i class="fa fa-search"></i>查询</button>
          <span class="f-right">提现总金额:<span style="color:red;font-weight:bold;" id="totalCashDrawMoney">0</span>元&nbsp;|&nbsp;总手续费:<span style="color:red;font-weight:bold;" id="totalCashDrawCommission">0</span>元</span>
        </div>
        <div class='wst-grid'>
          <div id="mmg7" class="mmg7"></div>
          <div id="pg7" style="text-align: right;"></div>
        </div>
      </div>
      <!--资金流水-->
      <div id="template_flow" class="layui-tab-item ">
        <div class="wst-toolbar">
          <select id='type8'>
            <option value='-1'>会员类型</option>
            <option value='0'>会员</option>
            <option value='1'>商家</option>
            <?php if((WSTConf('CONF.isOpenSupplier')==1)): ?>
            <option value='3'>供货商</option>
            <?php endif; ?>
          </select>
          <input type='text' id='key8' placeholder='账号'/>
          <input type="text" id="startDate8" name="startDate8" class="ipt laydate-icon" maxLength="20"  />
          至
          <input type="text" id="endDate8" name="endDate8" class="ipt laydate-icon" maxLength="20"  />
          <button class="btn btn-primary" onclick="javascript:loadMoneyGrid(0)"><i class="fa fa-search"></i>查询</button>
        </div>
        <div class='wst-grid'>
          <div id="mmg8" class="mmg8"></div>
          <div id="pg8" style="text-align: right;"></div>
        </div>
      </div>
      <!--积分流水-->
      <div id="template_flow" class="layui-tab-item ">
        <div class="wst-toolbar">
          <input type="text" id="startDate9" name="startDate9" class="ipt laydate-icon" maxLength="20"  />
          至
          <input type="text" id="endDate9" name="endDate9" class="ipt laydate-icon" maxLength="20"  />
          <button class="btn btn-primary" onclick="javascript:loadScoreGrid(0)"><i class="fa fa-search"></i>查询</button>
          <span class="f-right">兑换积分:<span style="color:green;font-weight:bold;" id="totalOutScore">0</span>个&nbsp;&nbsp;&nbsp;</span>
          <span class="f-right">新增积分:<span style="color:red;font-weight:bold;" id="totalInScore">0</span>个&nbsp;&nbsp;&nbsp;</span>
          
        </div>
        <div class='wst-grid'>
          <div id="mmg9" class="mmg9"></div>
          <div id="pg9" style="text-align: right;"></div>
        </div>
      </div>
      <!--订单佣金-->
      <div id="template_flow" class="layui-tab-item ">
        <div class="wst-toolbar">
          <select id='type10'>
            <option value='0'>结算对象</option>
            <option value='1'>商家</option>
            <?php if((WSTConf('CONF.isOpenSupplier')==1)): ?>
            <option value='3'>供货商</option>
            <?php endif; ?>
          </select>
          <input type="text" id="startDate10" name="startDate10" class="ipt laydate-icon" maxLength="20"  />
          至
          <input type="text" id="endDate10" name="endDate10" class="ipt laydate-icon" maxLength="20"  />
          <button class="btn btn-primary" onclick="javascript:loadCommissionGrid(0)"><i class="fa fa-search"></i>查询</button>
          <span class="f-right">订单总佣金:<span style="color:red;font-weight:bold;" id="totalCommission">0</span>元&nbsp;&nbsp;&nbsp;</span>
          
        </div>
        <div class='wst-grid'>
          <div id="mmg10" class="mmg10"></div>
          <div id="pg10" style="text-align: right;"></div>
        </div>
      </div>

   </div>
</div>
<script>
$(function(){
  initTab(<?php echo $p; ?>);
  userGridInit(<?php echo $p; ?>);
  phaseSummary(1,0);
  $(".head2 .item").click(function(){
    var type = $(this).attr("data");
    $(".head2 .item").removeClass("active");
    $(this).addClass("active");
    phaseSummary(type,1);
  });


  $('#t_totalUserMoney').poshytip({content:'用户余额：包含用户可用余额 + 用户申请提现被冻结余额',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });
  $('#t_totalShopMoney').poshytip({content:'商家余额：包含商家可用余额 + 商家申请提现被冻结余额',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });
  $('#t_totalSupplierMoney').poshytip({content:'供货商余额：包含供货商可用余额 + 供货商申请提现被冻结余额',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });
  $('#t_totalScore').poshytip({content:'积分余额：平台当前用户积分总数',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });


  $('#v_rechangeMoney').poshytip({content:'充值金额：平台收款帐户（如:微信商户号，支付宝商户号）入账总金额',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });
  $('#v_giveMoney').poshytip({content:'赠送金额：(用户，商家，供货商)通过平台的充值功能向帐户充值，平台赠送的总金额',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });
  $('#v_renewMoney').poshytip({content:'年费金额：(商家，供货商)支付年费总金额',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });
  $('#v_cashDraw').poshytip({content:'已提现金额：(用户，商家，供货商)申请提现总金额',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });
  $('#v_refundMoney').poshytip({content:'已退款金额：(用户，商家)申请退款总金额',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });

  $('#v_giveScore').poshytip({content:'赠送积分：平台新赠送给用户的积分总数',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });
  $('#v_exchangeScore').poshytip({content:'已兑换积分：平台兑换抵扣积分总数',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });
  $('#v_commission').poshytip({content:'订单佣金：平台收取(商家，供货商)订单佣金总金额',showTimeout:0,hideTimeout:1,
            offsetY: 25,allowTipHover: false,timeOnScreen:10000 });
})
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/finance/logmoneys.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>