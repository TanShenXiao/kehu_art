<?php /*a:2:{s:74:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\reports\stat_sales.html";i:1602924242;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<style>
#mainTable{width:95%;text-align:center;border:1px solid #eee;margin: 0px auto;margin-bottom:40px;font-size:13px;}
.wst-list .num{text-align:center;}
.wst-list tr th{background:#f5f5f5;border-bottom:1px solid #eee;font-weight: normal;}
.wst-list tr td,.wst-list tr th {height: 35px;line-height: 35px;text-align:center}
.wst-list tr td{border-bottom:1px dotted #eee;}
.wst-list tbody tr:hover{background:#f5f5f5;}
</style>
<div class="wst-toolbar">
<input type="text" id="startDate" name="startDate" class="laydate-icon ipt" maxLength="20" value='<?php echo $startDate; ?>' placeholder='开始日期'/>
至
<input type="text" id="endDate" name="endDate" class="laydate-icon ipt" maxLength="20" value='<?php echo $endDate; ?>' placeholder='结束日期'/>
<div class='f-left tbr-m'>
支付方式:
<select id='payType' class='ipt'>
	<option value='-1'>全部</option>
	<option value='0'>货到付款</option>
	<option value='1'>在线支付</option>
</select>
</div>
<button class="btn btn-primary" onclick='javascript:loadStat()'><i class='fa fa-search'></i>查询</button>  
<button class="btn btn-primary f-right" style='margin-top:0px;' onclick='javascript:toExport(0)'><i class="fa fa-sign-in"></i>导出</button>  
</div>
<div class="wst-tips-box">
    <div class="icon"></div>
    <div class="tips">该统计不含未支付的订单</div>
    <div style="clear:both"></div>
</div>
<div id="main" style='width:95%;height:400px;'></div>
<table id='mainTable' class='wst-list'>
        <thead>
            <tr>
              <th width='20'>&nbsp;&nbsp;</th>
              <th width='100'>日期</th>
              <th width='100'>电脑端(￥)</th>
              <th width='130'>触屏端(￥)</th>
              <th width='130'>微信端(￥)</th>
              <th width='130'>小程序(￥)</th>
              <th width='130'>安卓端(￥)</th>
              <th width='130'>苹果端(￥)</th>
              <th width='130'>总销售额(￥)</th>
            </tr>
        </thead>
        <tbody id='list-box'></tbody>
        <script id="stat-tblist" type="text/html">
        {{# for(var i = 0; i < d['days'].length; i++){ }}
            <tr>
              <td class='num'>{{(i+1)}}</td>
              <td>{{ d['days'][i]}}</td>
              <td>{{ WST.blank(d['p0'][i],'0')}}</td>
              <td>{{ WST.blank(d['p2'][i],'0')}}</td>
              <td>{{ WST.blank(d['p1'][i],'0')}}</td>
              <td>{{ WST.blank(d['p5'][i],'0')}}</td>
              <td>{{ WST.blank(d['p3'][i],'0')}}</td>
              <td>{{ WST.blank(d['p4'][i],'0')}}</td>
              <td>{{ WST.blank(d['total'][i],'0')}}</td>
            </tr>
        {{# } }}
        </script>
    </table>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/echarts/echarts.min.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/reports/stat_sales.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>