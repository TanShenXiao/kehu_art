<?php /*a:4:{s:83:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/users/orders/orders_list.html";i:1579267160;s:63:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/base.html";i:1579267089;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/footer.html";i:1618386322;s:65:"/mnt/d/nginx/html/artmark/wstmart/mobile/view/default/dialog.html";i:1579267090;}*/ ?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="format-detection" content="telephone=no">

<title>我的订单 - <?php echo WSTConf('CONF.mallName'); ?></title>
<link rel="stylesheet" href="__MOBILE__/frozenui/css/frozen.css">
<link rel="stylesheet"  href="__MOBILE__/css/common.css?v=<?php echo $v; ?>">

<link rel="stylesheet"  href="__MOBILE__/css/orders.css?v=<?php echo $v; ?>">

<script type='text/javascript' src='__MOBILE__/frozenui/js/zepto.min.js'></script>
<script type='text/javascript' src='__MOBILE__/frozenui/js/frozen.js'></script>
<script type='text/javascript' src='__MOBILE__/js/laytpl/laytpl.js?v=<?php echo $v; ?>'></script>
<script src="__MOBILE__/js/echo.min.js"></script>
<script type='text/javascript' src='__MOBILE__/js/common.js?v=<?php echo $v; ?>'></script>
<script type='text/javascript' src='__MOBILE__/js/searchlist.js?v=<?php echo $v; ?>'></script>
<script>
window.conf = {"ROOT":"","MOBILE":"__MOBILE__","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>","SMS_OPEN":"<?php echo WSTConf('CONF.smsOpen'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>","IS_LOGIN":"<?php if((int)session('WST_USER.userId')>0): ?>1<?php else: ?>0<?php endif; ?>","ROUTES":'<?php echo WSTRoute(); ?>',"IS_CRYPTPWD":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>",HTTP:"<?php echo WSTProtocol(); ?>",'RESOURCE_PATH':'<?php echo WSTConf('CONF.resourcePath'); ?>'}
</script>
</head>
<body ontouchstart="">

	<div id="info_list">
    <header style="background:#ffffff;" class="ui-header ui-header-positive wst-header wst-headero">
        <i class="ui-icon-return" onclick="location.href='<?php echo url('mobile/users/index'); ?>'"></i><h1>我的订单</h1>
    </header>


    <input type="hidden" name="" value="" id="currPage" autocomplete="off">
    <input type="hidden" name="" value="" id="totalPage" autocomplete="off">
    <input type="hidden" name="" value="<?php echo $type; ?>" id="type" autocomplete="off">

    <script id="shopList" type="text/html">
    {{# for(var i = 0; i < d.length; i++){ }}
            <div class="order-item">
                <div class="ui-row-flex ui-whitespace item-head">
                    <div class="ui-col ui-col-2" onclick="javascript:WST.intoShops({{d[i].shopId}});"><p class="ui-nowrap-flex"><i class="shopicon"></i>{{d[i].shopName}}</p></div>
                    <div class="ui-col order-tr o-status">
                        {{ d[i].status }}
                        {{# if($.inArray(d[i].orderStatus,[-1,-3])!=-1){ }}
						{{# if(d[i].payType==1 && d[i].isPay==1) { }} 
                            {{# if(d[i].isRefund==1) { }} 
                            (已退款)
                            {{# }else{ }} 
                            (未退款)
                            {{# } }}
						{{# } }} 
                        {{# } }}
                    </div>
                </div>

                {{# for(var g=0;g<d[i].list.length;g++){ }}
                <div class="ui-row-flex ui-whitespace border-b" onclick="getOrderDetail({{d[i].orderId}})">
                    <div class="ui-col">
                        <img src="__RESOURCE_PATH__/{{d[i].list[g].goodsImg}}" class="o-Img">
                    </div>
                    <div class="ui-col ui-col-3 o-gInfo">
                        <p class="o-gName ui-nowrap-multi ui-whitespace">{{d[i].list[g].goodsName}}</p>

                        {{# if(d[i].list[g].goodsSpecNames){ }}
                        <p class="o-gSpec ui-nowrap-flex ui-whitespace">规格：{{d[i].list[g].goodsSpecNames}}</p>
                        {{# } }}
                    </div>
                    <div class="ui-col order-tr" style="word-break:break-all;">
                        {{# if(d[i].list[g].goodsCode=='gift'){ }}
                        	【赠品】
                        {{# }else{ }}
                        <p>¥ {{d[i].list[g].goodsPrice}}</p><p>x {{d[i].list[g].goodsNum}}</p>
                        {{# } }}
                    </div>
                </div>
                {{#  } }}


                <div class="ui-btn-wrap" style="padding:5px 0px;">
                {{# if(d[i].orderCodeTitle!=""){ }}
                    <span class="order_from">{{d[i].orderCodeTitle}}</span>
                {{# } }}
                <div class="o-oListMoney">
                    订单总价：<span>¥ {{d[i].realTotalMoney}}</span>
                </div>
                {{# if(d[i].orderStatus==-2){ }}
                    <button class="ui-btn o-btn" onclick="choicePay('{{d[i].pkey}}');">
                        立即付款
                    </button>
                {{# } }}

                {{# if(d[i].orderStatus==0 && d[i].noticeDeliver==0 ){ }}
                    <button class="ui-btn o-btn o-cancel-btn" onclick="WST.dialog('您确定要提醒发货吗?','noticeDeliver({{d[i].orderId}})')">
                        提醒发货
                    </button>
                {{# } }}


                {{# if(d[i].orderStatus==-2 || d[i].orderStatus==0){ }}
                    <button class="ui-btn o-btn o-cancel-btn" onclick="showCancelBox('cancelOrder({{d[i].orderId}})')">
                        取消订单
                    </button>
                {{# } }}

                {{# if((d[i].orderStatus!=-1 || d[i].orderStatus==1) && d[i].orderStatus!=-2 && d[i].isComplain==0 ){ }}
                    <button class="ui-btn o-btn o-cancel-btn" onclick="complain({{d[i].orderId}})">
                        投诉
                    </button>
                {{# } }}

                {{# if(d[i].orderStatus==2 && d[i].isAppraise==0) { }}
                    <button class="ui-btn o-btn" onclick="toAppr({{d[i].orderId}})">
                        评价
                    </button>
                {{# } }}
                {{# if(d[i].isAppraise==1){ }}
                    <button class="ui-btn o-btn" onclick="toAppr({{d[i].orderId}})">
                        查看评价
                    </button>
                {{# } }}
                {{# if(d[i].orderStatus==2 && d[i].canAfterSale) { }}
                    <button class="ui-btn o-btn" style="color:#333;border:1px solid #333;" onclick="afterSale({{d[i].orderId}})">
                        申请售后
                    </button>
                {{# }  }}
                

                {{# if((d[i].allowRefund==1) && (d[i].orderStatus==-1 || d[i].orderStatus==-3)){ }}
                <button class="ui-btn o-btn" onclick="showRefundBox({{d[i].orderId}})">
                           申请退款
                </button>
                {{# } }}


                {{# if(d[i].orderStatus==1){  }}
                    <button class="ui-btn o-btn o-cancel-btn" onclick="showRejectBox('rejectOrder({{d[i].orderId}})')">
                        拒收
                    </button>
                    <button class="ui-btn o-btn" onclick="WST.dialog('你确定已收货吗?','receive({{d[i].orderId}})')">
                            确认收货
                    </button>
                {{# } }}
				{{ d[i]['hook']?d[i]['hook']:"" }}
                <div class="wst-clear"></div>
                </div>
            </div>
    {{#  } }}
    </script>

    <section class="ui-container" id="shopBox">
    	<div class="ui-tab">
	        <ul class="ui-tab-nav order-tab">
	            <li class="tab-item <?php if($type==''): ?>tab-curr<?php endif; ?>" type="" >全部</li>
	            <li class="tab-item <?php if($type=='waitPay'): ?>tab-curr<?php endif; ?>" type="waitPay" >待付款</li>
	            <li class="tab-item <?php if($type=='waitDeliver'): ?>tab-curr<?php endif; ?>" type="waitDeliver" >待发货</li>
	            <li class="tab-item <?php if($type=='waitReceive'): ?>tab-curr<?php endif; ?>" type="waitReceive" >待收货</li>
	            <li class="tab-item <?php if($type=='waitAppraise'): ?>tab-curr<?php endif; ?>" type="waitAppraise" >待评价</li>
	            <li class="tab-item <?php if($type=='finish'): ?>tab-curr<?php endif; ?>" type="finish" >已完成</li>
	            <li class="tab-item <?php if($type=='abnormal'): ?>tab-curr<?php endif; ?>" type="abnormal" >取消拒收</li>
	        </ul>
		</div>

        <div id="order-box">

        </div>

    </section>
    </div>
    
<?php echo hook('mobileDocumentOrderList'); ?>

<script type="text/html" id="detailBox">
           <div id="detailBox">
			<div class="detail-head" style="margin-top:0;">
			{{# if($.inArray(d.orderStatus,[-2,0,1,2])!=-1){ }}
			<div class="wst-or-process">
				<div class="ui-row-flex">
					{{# if(d.payType==1) { }}
    				<div class="ui-col ui-col process"><p class="line">
						<span {{# if($.inArray(d.orderStatus,[-2,0,1,2])!=-1){ }}class="active"{{# } }}></span>
						<span {{# if($.inArray(d.orderStatus,[0,1,2])!=-1){ }}class="active"{{# } }}></span>
						<p class="icon"><i class="ui-icon-success-block {{# if($.inArray(d.orderStatus,[-2,0,1,2])!=-1){ }}active{{# } }}"></i></p>
					<div class="wst-clear"></div></p><p>待付款</p></div>
					{{# } }}
    				<div class="ui-col ui-col process"><p class="line">
						<span {{# if($.inArray(d.orderStatus,[0,1,2])!=-1){ }}class="active"{{# } }}></span>
						<span {{# if($.inArray(d.orderStatus,[1,2])!=-1){ }}class="active"{{# } }}></span>
						<p class="icon"><i class="ui-icon-success-block {{# if($.inArray(d.orderStatus,[0,1,2])!=-1){ }}active{{# } }}"></i></p>
					<div class="wst-clear"></div></p><p>待发货</p></div>
    				<div class="ui-col ui-col process"><p class="line">
						<span {{# if($.inArray(d.orderStatus,[1,2])!=-1){ }}class="active"{{# } }}></span>
						<span {{# if(d.orderStatus==2){ }}class="active"{{# } }}></span>
						<p class="icon"><i class="ui-icon-success-block {{# if($.inArray(d.orderStatus,[1,2])!=-1){ }}active{{# } }}"></i></p>
					<div class="wst-clear"></div></p><p>已发货</p></div>
    				<div class="ui-col ui-col process"><p class="line">
						<span {{# if(d.orderStatus==2){ }}class="active"{{# } }}></span>
						<span {{# if(d.orderStatus==2){ }}class="active"{{# } }}></span>
						<p class="icon"><i class="ui-icon-success-block {{# if(d.orderStatus==2){ }}active{{# } }}"></i></p>
					<div class="wst-clear"></div></p><p>已收货</p></div>
				</div>
			</div>
			{{# } }}
			{{# if($.inArray(d.orderStatus,[-1,-3])!=-1 && d.payType==1 && d.isPay==1){ }}
			<div class="wst-or-process">
				<div class="ui-row-flex">
    				<div class="ui-col ui-col process"><p class="line">
						<span class="active"></span>
						<span {{# if(d.refundStatus==1 || d.refundStatus==2 || d.refundStatus==0){ }}class="active"{{# } }}></span>
						<p class="icon"><i class="ui-icon-success-block active"></i></p>
					<div class="wst-clear"></div></p><p>买家申请退款</p></div>
    				<div class="ui-col ui-col process"><p class="line">
						<span {{# if(d.refundStatus==1 || d.refundStatus==2 || d.refundStatus==0){ }}class="active"{{# } }}></span>
						<span {{# if(d.refundStatus==2){ }}class="active"{{# } }}></span>
						<p class="icon"><i class="ui-icon-success-block {{# if(d.refundStatus==1 || d.refundStatus==2 || d.refundStatus==0){ }}active{{# } }}"></i></p>
					<div class="wst-clear"></div></p><p>商家申请退款处理</p></div>
    				<div class="ui-col ui-col process"><p class="line">
						<span {{# if(d.refundStatus==2) { }} class="active"{{# } }}></span>
						<span {{# if(d.refundStatus==2) { }} class="active"{{# } }}></span>
						<p class="icon"><i class="ui-icon-success-block {{# if(d.refundStatus==2) { }} active{{# } }}"></i></p>
					<div class="wst-clear"></div></p><p>退款完成</p></div>
				</div>
			</div>
			{{# } }}
            <div class="ui-row-flex">
                <div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">订单状态：</span>
				<span class="o-status">{{d.status}}
                {{# if($.inArray(d.orderStatus,[-1,-3])!=-1){ }}
					{{# if(d.payType==1 && d.isPay==1) { }} 
                    {{# if(d.isRefund==1) { }} 
                    (已退款)
                    {{# }else{ }} 
                    (未退款)
                    {{# } }}
					{{# } }} 
                {{# } }}</span></div>
            </div>
            <div class="ui-row-flex">
                <div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">订单编号：</span>{{d.orderNo}}</div>
            </div>
			<div class="ui-row-flex">
                <div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">下单时间：</span>{{d.createTime}}</div>
            </div>
			</div>


			<div class="detail-head">
			{{# if(d.deliverType==0 && d.userName){ }}
            	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">收货人：</span>{{d.userName}} <span class="d-utel">{{d.userPhone}}</span></div>
            	</div>
            	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">收货地址：</span><span class="d-uaddr">{{d.userAddress}}<i></i></span></div>
            	</div>
            {{# }else{ }}
                <div class="ui-row-flex">
                    <div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">自提地址：</span><span class="d-uaddr">{{d.shopAddress}}<i></i></span></div>
                </div>
			{{# } }}
            	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">支付信息：</span>{{d.payInfo}}</div>
            	</div>
                {{# if(d.payTime){ }}
                <div class="ui-row-flex">
                    <div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">支付时间：</span>{{d.payTime}}</div>
                </div>
                {{# } }}
            	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">配送信息：</span>{{d.deliverInfo}}</div>
            	</div>
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">发票信息：</span>{{# if(d.isInvoice==1) { }}需要{{# } else{ }}不需要{{# } }}</div>
            	</div>
			{{# if(d.isInvoice==1) { }}
                <div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">发票状态：</span>{{# if(d.isMakeInvoice==1) { }}已开{{# } else{ }}未开{{# } }}</div>
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">发票抬头：</span>{{d.invoiceClient}}</div>
            	</div>
            {{#
                 var inv_json = JSON.parse(d.invoiceJson);
                 var inv_code = (inv_json!=null && inv_json.invoiceCode!=undefined)?inv_json.invoiceCode:'';
                 if(inv_json!=null && inv_json.type!=undefined && inv_json.type==0){
             }}
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">发票税号：</span>{{inv_code}}</div>
            	</div>
			{{# } }}
            {{# } }}
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">订单备注：</span>{{d.orderRemarks}}</div>
            	</div>
			</div>

			{{# if(d.isRefund==1){ }}
			<div class="detail-head">
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">退款金额：</span>¥ {{d.backMoney}}</div>
            	</div>
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">退款备注：</span>{{d.refundRemark}}</div>
            	</div>
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe">退款时间：</span>{{d.refundTime}}</div>
            	</div>
			</div>
			{{# } }}


			<div class="detail-head">
			<div class="ui-row-flex o-shops">
            	<div class="ui-col ui-col wst-or-term"><p class="shops" onclick="javascript:WST.intoShops({{d.shopId}});"><i></i>{{d.shopName}}<p></div>
           	</div>
            {{# for(var i=0;i<d.goods.length;i++){ }}
            <div class="ui-row-flex ui-whitespace border-b d-goodsitme" onclick="javascript:WST.intoGoods({{d.goods[i].goodsId}})">
                <div class="ui-col">
                    <img src="__RESOURCE_PATH__/{{d.goods[i].goodsImg}}" class="o-Img">
                </div>
                <div class="ui-col ui-col-3 o-gInfo">
                    <p class="o-gName ui-nowrap-multi ui-whitespace">{{d.goods[i].goodsName}}</p>
                    <p class="o-gSpec d-gSpec">
                    {{# if(d.goods[i].goodsSpecNames){ }}
                    {{d.goods[i].goodsSpecNames.replace(/@@_@@/g,'<br />')}}
                    {{# } }}
                    </p>
                </div>
                <div class="ui-col order-tr" style="word-break:break-all;padding:5px 0;">
                    {{# if(d.goods[i].goodsCode=='gift'){ }}
                    【赠品】
                    {{# }else{ }}
                    <p>¥ {{d.goods[i].goodsPrice}}</p><p>x {{d.goods[i].goodsNum}}</p>
                    {{# } }}
                </div>
            </div>
			{{# if(d.goods[i].goodsType==1 && d.orderStatus==2){ }}
				{{# for(var e=0;e<d.goods[i].extraJson.length;e++){ }}
				<div class="ui-row-flex ui-row-flex-ver d-uInfo">
               		<div class="ui-col">
						<p>卡券号：{{d.goods[i].extraJson[e].cardNo}}</p>
						<p>卡券密码：{{d.goods[i].extraJson[e].cardPwd}}</p>
					</div>
				</div>
				{{# } }}
			{{# } }}
                <div class="">
                        <button class="ui-btn o-btn" style="color:#333;border:1px solid #333;" onclick="addCart({{d.goods[i].goodsId}},{{d.goods[i].goodsSpecId}},{{d.goods[i].goodsType}})">
                            加购物车
                        </button>
                        <div class="wst-clear"></div>
                </div>
            {{# } }}
			</div>

			<div class="detail-head">
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe2">获得积分</span><span class="o-status2">{{d.orderScore}} 个</span></div>
            	</div>
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe2">商品总额</span><span class="o-status2">¥ {{d.goodsMoney}}</span></div>
            	</div>
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe2">运费</span><span class="o-status2">¥ {{d.deliverMoney}}</span></div>
            	</div>
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe2">积分抵扣金额</span><span class="o-status2">¥ -{{d.scoreMoney}}</span></div>
            	</div>
				{{# if(d.useScore>0){ }}
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term"><span class="wst-or-describe2">使用积分数</span><span class="o-status2">{{d.useScore}} 个</span></div>
            	</div>
				{{# } }}
				{{ d['hook']?d['hook']:"" }}
             	<div class="ui-row-flex">
                	<div class="ui-col ui-col wst-or-term2"><span class="wst-or-describe2">实付款</span><span class="o-status2"><span style="font-size:0.13rem;">¥ </span>{{d.realTotalMoney}}</span></div>
            	</div>
			</div>
         </div>
</script>
    
    <div class="wst-cover" id="cover"></div>
    
    <div class="wst-fr-box" id="frame">
        <div class="title" id="boxTitle"><span>订单详情</span><i class="ui-icon-close-page" onclick="javascript:dataHide();"></i><div class="wst-clear"></div></div>
        <div class="content" id="content">

        </div>
    </div>
    
    <div class="wst-fr-box" id="refundFrame">
        <div class="title"><span>申请退款</span><i class="ui-icon-close-page" onclick="javascript:reFundDataHide();"></i><div class="wst-clear"></div></div>
        <div class="content" id="refund-content">
			 <div class="detail-head" style="margin-top:0;">
	            <div class="wst-or-process">
	                <div class="ui-row-flex" style="padding:10px;border-bottom:RGB(242,242,242) 2px dashed;">
	                    <div class="ui-col ui-col process"><p class="line">
	                        <span class="active"></span>
	                        <span></span>
	                        <p class="icon"><i class="ui-icon-success-block active"></i></p>
	                    <div class="wst-clear"></div></p><p>卖家申请退款</p></div>
	                    <div class="ui-col ui-col process"><p class="line">
	                        <span></span>
	                        <span></span>
	                        <p class="icon"><i class="ui-icon-success-block"></i></p>
	                    <div class="wst-clear"></div></p><p>商家申请退款处理</p></div>
	                    <div class="ui-col ui-col process"><p class="line">
	                        <span></span>
	                        <span></span>
	                        <p class="icon"><i class="ui-icon-success-block"></i></p>
	                    <div class="wst-clear"></div></p><p>退款完成</p></div>
	                </div>
	            </div>
	            <div class="wst-or-refund">
	                <p class="prompt">请选择取消订单申请退款的原因，以便我们能更好的为您服务。</p>
	                <div class="term">
	                <span class="sign">*</span>退款原因：
	                <select id='refundReason' onchange='javascript:changeRefundType(this.value)'>
	                   <?php $_result=WSTDatas('REFUND_TYPE');if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	                   <option value='<?php echo $vo["dataVal"]; ?>'><?php echo $vo["dataName"]; ?></option>
	                   <?php endforeach; endif; else: echo "" ;endif; ?>
	                </select>
	                </div>
	                <div  class="term">
	                <span class="sign">*</span>退款金额： <input type='number' id='money' maxLength='10' onkeyup="javascript:WST.isChinese(this,1)" autocomplete="off">
	                </div>
					<p class="prompt">(金额不能超过<font color='red' id="realTotalMoney">0</font>，<span id="useScore">0</span>个积分抵扣<font color='red' id="scoreMoney">¥ 0</font>)</p>
	                <div  class="term">
	                <div id='refundTr' style="width:99%;display:none;" >
	                     <span class="sign">*</span>其他原因
	                     <textarea id='refundContent' style='width:100%;height:80px;padding: 5px;' maxLength='200'></textarea>
	                </div>
	                </div>
	                <p class="cancel-btn-box ui-flex ui-flex-pack-center">
	                <button id="wst-event8" type="button" class="ui-btn-s wst-dialog-b2">提交申请退款</button>
	                </p>
	             </div>
	         </div>
        </div>
    </div>


	        
        <div class="ui-loading-wrap wst-Load" id="Load">
		    <i class="ui-loading"></i>
		</div>
		
		<div class="ui-loading-block" id="Loadl">
		    <div class="ui-loading-cnt">
		        <i class="ui-loading-bright"></i>
		        <p id="j-Loadl">正在加载中...</p>
		    </div>
		</div>
		<?php 
			$pageId = WSTIsOpenIndexCustomPage(1);
			$menu = WSTIndexCustomPageMenu($pageId);
			$cartNum = WSTCartNum();
		 if($pageId > 0): ?>
		<input type="hidden" value="<?php echo $pageId; ?>" id="pageId" autocomplete="off">
		<footer class="ui-footer wst-footer-btns" style="position:fixed;bottom:0;height:43px; border-top: 1px solid <?php echo $menu['borderStyle']; ?>;" id="footer">
			<div class="wst-toTop" id="toTop">
				<i class="wst-toTopimg"></i>
			</div>
			<div class="ui-row-flex wst-custom-menus" style="background:<?php echo $menu['backgroundColor']; ?>">
				<?php if(is_array($menu['tabbars']) || $menu['tabbars'] instanceof \think\Collection || $menu['tabbars'] instanceof \think\Paginator): $i = 0; $__LIST__ = $menu['tabbars'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
					<div class='ui-col ui-col'>
						<a href='javascript:void(0);' menu-flag="<?php echo $vo['menuFlag']; ?>"  link="<?php echo $vo['link']; ?>"  onclick="javascript:WST.toCustomMenuPage(this)">
							<div class="wst-flex-column wst-center wst-custom-menus-item <?php if($vo['menuFlag'] == 'cart'): ?>carsNum<?php endif; ?>">
								<img class='custom-menu-icon ' src="__RESOURCE_PATH__/<?php echo $vo['icon']; ?>">
								<img class='custom-menu-select-icon wst-none' src="__RESOURCE_PATH__/<?php echo $vo['selectIcon']; ?>">
								<p style="color:<?php echo $menu['color']; ?>;" class='custom-menu-text'><?php echo $vo['text']; ?></p>
								<p style="color:<?php echo $menu['selectedColor']; ?>;" class='custom-menu-select-text wst-none'><?php echo $vo['text']; ?></p>
								<?php if($vo['menuFlag'] == 'cart' && $cartNum > 0): ?><i><?php  echo $cartNum; ?></i><?php endif; ?>
							</div>
						</a>
					</div>
				<?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
		</footer>
		<?php else: ?>
		<input type="hidden" value="0" id="pageId" autocomplete="off">
        <footer class="ui-footer wst-footer-btns" style="position:fixed;bottom:0;height:43px; border-top: 1px solid #e8e8e8;" id="footer">
	        <div class="wst-toTop" id="toTop">
			  <i class="wst-toTopimg"></i>
			</div>
            <div class="ui-row-flex wst-menus">
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/index/index'); ?>"><p id="home"></p></a></div>
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/goods/lists'); ?>"><p id="category"></p></a></div>
			    <?php echo hook('mobileDocumentBottomNav'); ?>
			    <div class="ui-col ui-col carsNum J_im_cart"><a href="<?php echo url('mobile/carts/index'); ?>"><p id="cart">
                </p></a><?php if(($cartNum>0)): ?><i><?php  echo $cartNum; ?></i><?php endif; ?></div>
                <div class="ui-col ui-col J_followbox"><a href="<?php echo url('mobile/favorites/goods'); ?>"><p id="follow"></p></a></div>
			    <div class="ui-col ui-col"><a href="<?php echo url('mobile/users/index'); ?>"><p id="user"></p></a></div>
			</div>
        </footer>
		<?php endif; ?>
        <?php echo hook('initCronHook'); ?>



<div class="ui-dialog" id="wst-di-prompt">
    <div class="ui-dialog-cnt">
        <div class="ui-dialog-bd">
            <p id="wst-dialog" class="wst-dialog-t">提示</p>
            <p class="wst-dialog-l"></p>
            <button id="wst-event1" type="button" class="ui-btn-s wst-dialog-b1" data-role="button">取消</button>&nbsp;&nbsp;
            <button id="wst-event2" type="button" class="ui-btn-s wst-dialog-b2">确定</button>
        </div>
    </div>      
</div>

<div class="ui-dialog" id="wst-di-share" onclick="WST.dialogHide('share');">
     <div class="wst-prompt"></div>
</div><!-- 对话框模板 -->
<div class="ui-dialog" id="cancelBox">
    <div class="ui-dialog-cnt">
        <div class="ui-dialog-bd">
            <div class="ui-dialog-bd-title">请选择您取消订单的原因:</div>
            <select id='reason'>
               <?php $_result=WSTDatas('ORDER_CANCEL');if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
               <option value='<?php echo $vo["dataVal"]; ?>'><?php echo $vo["dataName"]; ?></option>
               <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            
            <p class="cancel-btn-box">
            <button id="wst-event1" type="button" class="ui-btn-s wst-dialog-b1" data-role="button">取消</button>&nbsp;&nbsp;
            <button id="wst-event0" type="button" class="ui-btn-s wst-dialog-b2">确定</button>
            </p>
        </div>
    </div>        
</div>



<div class="ui-dialog" id="rejectBox">
    <div class="ui-dialog-cnt">
        <div class="ui-dialog-bd">
            <div class="ui-dialog-bd-title">请选择您拒收订单的原因:</div>
            <select id='reject' onchange='javascript:changeRejectType(this.value)'>
               <?php $_result=WSTDatas('ORDER_REJECT');if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
               <option value='<?php echo $vo["dataVal"]; ?>'><?php echo $vo["dataName"]; ?></option>
               <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <br />
            <div id='rejectTr' style='display:none'>
                 原因<font color='red'>*</font>：
                 <textarea id='rejectContent' style='width:99%;height:80px;' maxLength='200'></textarea>
            </div>

            <p class="cancel-btn-box">
            <button id="wst-event1" type="button" class="ui-btn-s wst-dialog-b1" data-role="button">取消</button>&nbsp;&nbsp;
            <button id="wst-event3" type="button" class="ui-btn-s wst-dialog-b2">确定</button>
            </p>
        </div>
    </div>        
</div>



<script type='text/javascript' src='__MOBILE__/js/jquery.min.js'></script>
<script type='text/javascript' src='__MOBILE__/users/orders/orders_list.js?v=<?php echo $v; ?>'></script>
<script>
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
  getOrderList();
    if(parseInt($('#pageId').val()) == 0){
        WST.initFooter('user');
    }else{
        WST.selectCustomMenuPage('order');
    }
  backPrevPage(WST.U('mobile/users/index'));
  // Tab切换卡
  $('.tab-item').click(function(){
      $(this).addClass('tab-curr').siblings().removeClass('tab-curr');
      var type = $(this).attr('type');
      $('#type').val(type);
      reFlashList();
  });
  // 弹出层
  $("#frame").css('top',0);
  $("#frame").css('right','-100%');

    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - $(window).height())) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
            	getOrderList();
            }
        }
    });
});
</script>

</body>
</html>