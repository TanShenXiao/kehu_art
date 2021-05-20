<?php /*a:2:{s:88:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\\main.html";i:1602924176;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
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

<link href="__SHOP__/css/main.css?v=<?php echo $v; ?>" rel="stylesheet">

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

<!---->
<div class="wst-shop-info">
	<div class="wst-shop-na">
		<div class='wst-shop-name'><a target='_blank' href='<?php echo Url("home/shops/index","shopId=".$data["shop"]["shopId"]); ?>'><?php echo $data['shop']['shopName']; ?></a></div>
		<span class="wst-shop-img">
	        <a target='_blank' href="<?php echo url('home/shops/index',array('shopId'=>$data['shop']['shopId'])); ?>">
	            <img src="__RESOURCE_PATH__/<?php echo WSTImg($data['shop']['shopImg']); ?>" title="<?php echo WSTStripTags($data['shop']['shopName']); ?>" alt="<?php echo WSTStripTags($data['shop']['shopName']); ?>">
	        </a>
	    </span>
		<div class="wst-shop-na2">
		<span>认证等级：
		<?php if(is_array($data['shop']['accreds']) || $data['shop']['accreds'] instanceof \think\Collection || $data['shop']['accreds'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['shop']['accreds'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sv): $mod = ($i % 2 );++$i;?>
		<img src="__RESOURCE_PATH__/<?php echo $sv['accredImg']; ?>">
		<?php endforeach; endif; else: echo "" ;endif; ?>
	    </span>
		<span class="wst-shop-na3">用户名：<?php echo app('session')->get('WST_USER.loginName'); ?></span>
		<span class="wst-shop-na3">上次登录：<?php echo session('WST_USER.lastTime'); ?></span>
		<span class="wst-shop-na3">店铺地址：<?php echo WSTMSubstr($data['shop']['shopAddress'],0,10); ?></span>
		</div>
		
	</div>
	<div style="width: 30%;float: left;">
    <div class='wst-shop-name' style="margin-left: 20px;"><a>店铺评分</a></div>
	<div class="wst-shop-eva" style="margin-left: 8%">
		<p>商品评分</p>
		<div class="wst-shop-evai">
		<?php $__FOR_START_1969596461__=0;$__FOR_END_1969596461__=$data['shop']['scores']['goodsScore'];for($i=$__FOR_START_1969596461__;$i < $__FOR_END_1969596461__;$i+=1){ ?>
			<img src="/static/plugins/raty/img/star-on.png">
		<?php } $__FOR_START_2018362243__=1;$__FOR_END_2018362243__=6-$data['shop']['scores']['goodsScore'];for($i=$__FOR_START_2018362243__;$i < $__FOR_END_2018362243__;$i+=1){ ?>
			<img src="/static/plugins/raty/img/star-off.png">
		<?php } ?>
		</div>
	</div>
	<div class="wst-shop-eva">
		<p>时效评分</p>
		<div class="wst-shop-evai">
		<?php $__FOR_START_986199759__=0;$__FOR_END_986199759__=$data['shop']['scores']['timeScore'];for($i=$__FOR_START_986199759__;$i < $__FOR_END_986199759__;$i+=1){ ?>
			<img src="/static/plugins/raty/img/star-on.png">
		<?php } $__FOR_START_760404033__=1;$__FOR_END_760404033__=6-$data['shop']['scores']['timeScore'];for($i=$__FOR_START_760404033__;$i < $__FOR_END_760404033__;$i+=1){ ?>
			<img src="/static/plugins/raty/img/star-off.png">
		<?php } ?>
		</div>
	</div>
	<div class="wst-shop-eva">
		<p>服务评分</p>
		<div class="wst-shop-evai">
		<?php $__FOR_START_1830247877__=0;$__FOR_END_1830247877__=$data['shop']['scores']['serviceScore'];for($i=$__FOR_START_1830247877__;$i < $__FOR_END_1830247877__;$i+=1){ ?>
			<img src="/static/plugins/raty/img/star-on.png">
		<?php } $__FOR_START_855685331__=1;$__FOR_END_855685331__=6-$data['shop']['scores']['serviceScore'];for($i=$__FOR_START_855685331__;$i < $__FOR_END_855685331__;$i+=1){ ?>
			<img src="/static/plugins/raty/img/star-off.png">
		<?php } ?>
		</div>
	</div>
    </div>
	<div class="wst-shop-con">
		<div class='wst-shop-name' style="margin-left: 20px;"><a>平台联系方式</a></div>
		<p style="margin-left: 8%;"><span>电话：<?php echo $data['shop']['shopTel']; ?></span><span>QQ：<?php echo $data['shop']['shopQQ']; ?></span></p>
		<p style="margin-left: 8%;"><span>邮箱：<?php echo WSTConf('CONF.serviceEmail'); ?></span><span>服务时间：<?php echo date("H:i",strtotime($data['shop']['serviceStartTime'])); ?>-<?php echo date("H:i",strtotime($data['shop']['serviceEndTime'])); ?></span></p>
		<p></p>
	</div>
	<div class="f-clear"></div>
</div>


<div class="main">
	<div class="main_middle">
		<ul class="main_mid_box">
			<li class="mid_l">
				<div class="mid_l_item">
					<div class="main_title">
						<div class="wst-lfloat">
							
							<span class="c16_555">订单提示</span>
						</div>
						<div class="f-clear"></div>
					</div>
					<div class="mid_main">
						<ul class="order_info">
							<?php if(WSTShopGrant('shop/ordercomplains/shopcomplain')): ?>
							<li><a id="menuItem25" href="<?php echo Url('shop/ordercomplains/shopcomplain'); ?>" dataid="25">
								<div class="complain_num"><?php echo $data['stat']['complainNum']; ?></div>
								<div>待回应投诉</div>
							</a></li>
							<?php endif; if(WSTShopGrant('shop/orders/delivered')): ?>
							<li><a id="menuItem53" href="<?php echo Url('shop/orders/delivered'); ?>" dataid="53">
								<div class="complain_num"><?php echo $data['stat']['waitReceiveCnt']; ?></div>
								<div>待收货</div>
							</a></li>
							<?php endif; if(WSTShopGrant('shop/orders/waitdelivery')): ?>
							<li><a id="menuItem24" href="<?php echo Url('shop/orders/waitdelivery'); ?>" dataid="24">
								<div class="complain_num"><?php echo $data['stat']['waitDeliveryCnt']; ?></div>
								<div>待发货</div>
							</a></li>
							<?php endif; if(WSTShopGrant('shop/orders/waituserpay')): ?>
							<li><a id="menuItem55" href="<?php echo Url('shop/orders/waituserpay'); ?>" dataid="55">
								<div class="complain_num"><?php echo $data['stat']['orderNeedpayCnt']; ?></div>
								<div>待付款</div>
							</a></li>
							<?php endif; if(WSTShopGrant('shop/orders/failure')): ?>
							<li><a id="menuItem45" href="<?php echo Url('shop/orders/failure'); ?>" dataid="45">
								<div class="complain_num"><?php echo $data['stat']['cancel']; ?></div>
								<div>取消/拒收</div>
							</a></li>
							<?php endif; if(WSTShopGrant('shop/orders/failure')): ?>
							<li><a id="menuItem45" href="<?php echo Url('shop/orders/failure'); ?>" dataid="45">
								<div class="complain_num"><?php echo $data['stat']['orderRefundCnt']; ?></div>
								<div>待退款</div>
							</a></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
                
				<div class="mid_l_item" style="margin-top:10px;">
					<div class="main_title">
						<div class="wst-lfloat">
							<span class="c16_555">商品信息</span>
						</div>
					</div>
					<div class="f-clear"></div>
					<div class="mid_main">
						<ul class="order_info">
							<?php if(WSTShopGrant('shop/goods/store')): ?>
							<li><a id="menuItem34" href="<?php echo Url('shop/goods/store'); ?>" dataid="34">
								<div class="complain_num"><?php echo $data['stat']['unSaleCnt']; ?></div>
								<div>仓库中</div>
							</a></li>
							<?php endif; if(WSTShopGrant('shop/goods/stockwarnbypage')): ?>
							<li><a id="menuItem54" href="<?php echo Url('shop/goods/stockwarnbypage'); ?>" dataid="54">
								<div class="complain_num"><?php echo $data['stat']['stockWarnCnt']; ?></div>
							    <div >预警库存</div>
							</a></li>
							<?php endif; if(WSTShopGrant('shop/goods/sale')): ?>
							<li><a id="menuItem32" href="<?php echo Url('shop/goods/sale'); ?>" dataid="32">
								<div class="complain_num"><?php echo $data['stat']['onSaleCnt']; ?></div>
								<div>出售中</div>
							</a></li>
							<?php endif; if(WSTShopGrant('shop/goods/audit')): ?>
							<li><a id="menuItem33" href="<?php echo Url('shop/goods/audit'); ?>" dataid="33">
								<div class="complain_num"><?php echo $data['stat']['waitAuditCnt']; ?></div>
								<div>待审核</div>
							</a></li>
							<?php endif; if(WSTShopGrant('shop/goods/illegal')): ?>
							<li><a id="menuItem56" href="<?php echo Url('shop/goods/illegal'); ?>" dataid="56">
								<div class="complain_num"><?php echo $data['stat']['illegalCnt']; ?></div>
							    <div>违规商品</div>
							</a></li>
							<?php endif; if(WSTShopGrant('shop/goodsconsult/shopReplyConsult')): ?>
							<li><a id="menuItem102" href="<?php echo Url('shop/goodsconsult/shopReplyConsult'); ?>" dataid="102">
								<div class="complain_num"><?php echo $data['stat']['consult']; ?></div>
							    <div>待回复咨询</div>
							</a></li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</li>
			
			<li class="mid_r">
				
				<div class='mid_r_rbottom' style="margin-bottom: 10px;">
					<div class="main_title">
						<div class="wst-lfloat">
							
							<span class="c16_555">商家帮助</span>
						</div>
						<div class="f-clear"></div>
					</div>
					<div class="rbottom_main">
						<ul class="shop_tips">
							<?php $wstTagArticle =  model("common/Tags")->listArticle("300",8,0); foreach($wstTagArticle as $key=>$vo){?>
							<li class="wst-textover"><a href="<?php echo url('home/news/view',['id'=>$vo['articleId']]); ?>" target="_blank"><i></i><?php echo $key+1; ?>、<?php echo $vo['articleTitle']; ?></a><span><?php echo date('Y-m-d',strtotime($vo['createTime'])); ?></span></li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class='mid_r_rbottom'>
					<div class="main_title">
						<div class="wst-lfloat">
							
							<span class="c16_555">商家公告</span>
						</div>
						<div class="f-clear"></div>
					</div>
					<div class="rbottom_main">
						<ul class="shop_tips2">
							<?php $wstTagArticle =  model("common/Tags")->listArticle("51",5,0); foreach($wstTagArticle as $key=>$vo){?>
							<li class="wst-textover"><a href="<?php echo url('home/news/view',['id'=>$vo['articleId']]); ?>" target="_blank"><?php echo $key+1; ?>、<?php echo $vo['articleTitle']; ?></a><i>NEWS</i>&nbsp;&nbsp;&nbsp;&nbsp;<span><?php echo date('Y-m-d',strtotime($vo['createTime'])); ?></span></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</li>
			<?php if(WSTShopGrant('shop/reports/topSaleGoods')): ?>
			<li class="mid_c">
				<div class="index-right">
					<div class="index-right-item">
						<div class="main_title" style="padding-left:10px;">
							<div class="wst-lfloat">
								
								<span class="c16_555">最近30天销售排行</span>
							</div>
							<div class="f-clear"></div>
						</div>
						<ul class="right-list-tit">
							<li class="c12_555">序号</li>
							<li class="c12_555">商品</li>
							<li class="c12_555">数量</li>
						</ul>
						<?php if(is_array($data['stat']['goodsTop']) || $data['stat']['goodsTop'] instanceof \think\Collection || $data['stat']['goodsTop'] instanceof \think\Paginator): $gkey = 0;$__LIST__ = is_array($data['stat']['goodsTop']) ? array_slice($data['stat']['goodsTop'],0,10, true) : $data['stat']['goodsTop']->slice(0,10, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$glist): $mod = ($gkey % 2 );++$gkey;?>
						<ul class="right-list-tit right-list">
							<li class="c14_ff66">
								<div class="gTop<?php echo $gkey; ?> top-num"><?php echo $gkey; ?></div>
							</li>
							<li class="wst-textover"><a class="c14_ff90 atop<?php echo $gkey; ?>" target="_blank" href="<?php echo url('home/goods/detail',['goodsId'=>$glist['goodsId']]); ?>"><?php echo $glist['goodsName']; ?></a></li>
							<li class="c14_ff66 gTop<?php echo $gkey; ?>"><?php echo !empty($glist['goodsNum']) ? $glist['goodsNum'] : 0; ?></li>
						</ul>
						<?php endforeach; endif; else: echo "" ;endif; ?>

					</div>
				</div>
			</li>
			<?php endif; if(WSTShopGrant('shop/reports/statSales')): ?>
			<li class="mid_r">
				<div class="sale_info">
					<div class="main_title">
						<div class="wst-lfloat">
							
							<span class="c16_555">近30天销售情况</span>
						</div>
						<div class="f-clear"></div>
					</div>
					<div id="saleMain" style="width:100%;height:335px;"></div>
				</div>
			</li>
			<script>$(function(){saleCount()});</script>
			<?php endif; ?>
		</ul>
	</div>
<div class="f-clear"></div>
	
</div>



<input type="hidden"  id="startDate"  class="ipt" value='<?php echo date("Y-m-d",strtotime("-30 day")); ?>'/>
<input type="hidden" id="endDate" class="ipt" value='<?php echo date("Y-m-d"); ?>'/>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script src="/static/plugins/echarts/echarts.min.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
// 销售统计
function saleCount(){
	$.post(WST.U('shop/reports/getStatSales'),WST.getParams('.ipt'),function(data,textStatus){
	    var json = WST.toJson(data);
	    var myChart = echarts.init(document.getElementById('saleMain'));
	    myChart.clear();
	    $('#mainTable').hide();
	    if(json.status=='1' && json.data){
			var option = {
			    tooltip : {
			        trigger: 'axis'
			    },
			    calculable : true,
			    xAxis : [
			        {
			            type : 'category',
			            data : json.data.days
			        }
			    ],
			    yAxis : [
			        {
			            type : 'value'
			        }
			    ],
			    series : [
			        {
			            name:'销售额',
			            type:'line',
			            data:json.data.dayVals
			        }
			    ]
			};
			myChart.setOption(option);
	    }
	}); 
}
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>