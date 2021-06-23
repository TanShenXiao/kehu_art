var mmg;
$(function(){
    var laydate = layui.laydate;
    laydate.render({
        elem: '#startDate'
    });
    laydate.render({
        elem: '#endDate'
    });
})
function initGrid(){
	var p = WST.arrayParams('.j-ipt');
	var h = WST.pageHeight();
    var cols = [
            {title:'交易时间', name:'createTime', width: 100,sortable:true},
            {title:'支付时间', name:'payTime', width: 100,sortable:true},
            {title:'订单编号', name:'orderNo', width: 50,sortable:true, renderer:function(val,item,rowIndex){
                var h = "";
	            h += "<img class='order-source2' src='"+WST.conf.ROOT+"/wstmart/admin/view/img/order_source_"+item['orderSrc']+".png'>";	
	            h += "<a style='cursor:pointer' onclick='javascript:showDetail("+ item['orderId'] +");'>"+item['orderNo']+"</a>";
	            return h;
            }},
            {title:'订单成交类型', name:'orderCodeTitle', width: 60,sortable:true},
            {title:'买家', name:'userName', width: 90,sortable:true},
            {title:'店铺', name:'shopName', width: 90,sortable:true},
            {title:'商品金额', name:'goodsMoney' , width: 60,sortable:true},
            {title:'运费', name:'deliverMoney', width: 30,sortable:true},
            {title:'订单总金额', name:'totalMoney' , width: 30,sortable:true},
            {title:'积分抵扣', name:'scoreMoney', width: 30,sortable:true},
            {title:'实付金额', name:'realTotalMoney' , width: 30,sortable:true},
            {title:'支付通道', name:'payFrom' , width: 30,sortable:true},
            {title:'订单状态', name:'orderStatus', width: 30,sortable:true, renderer:function(val,item,rowIndex){
            	 if(item['orderStatus']==-1 || item['orderStatus']==-3){
                     return "<span class='statu-no'><i class='fa fa-ban'></i> "+item.status+"</span>";
                 }else if(item['orderStatus']==2){
                     return "<span class='statu-yes'><i class='fa fa-check-circle'></i> "+item.status+"</span>";
            	 }else{
            	 	return "<span class='statu-wait'><i class='fa fa-clock-o'></i> "+item.status+"</span>";
            	 }
            }},
            {title:'是否退款', name:'isRefund' , width: 30,sortable:true, renderer:function(val,item,rowIndex){
            	 if(item['isRefund']==1){return "是";}else{return "否";}
            }},
            {title:'退款金额', name:'backMoney' , width: 30,sortable:true},
            {title:'退款时间', name:'refundTime' , width: 100,sortable:true},
            {title:'备注', name:'remark' , width: 50,sortable:true},
            {title:'商家结算号', name:'settlementNo' , width: 30,sortable:true},
            {title:'操作' , width: 30,name:'status', renderer:function(val,item,rowIndex){
            	var h = "";
	            h += "<a class='btn btn-blue' href='javascript:toView(" + item['orderId'] + ")'><i class='fa fa-search'></i>详情</a> ";
	            return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: (h-84),indexCol: true,indexColWidth:50, cols: cols,method:'POST',nowrap:true,
        url: WST.U('admin/orders/pageQueryOrderMoney',p.join('&')), fullWidthRows: true, autoLoad: true,remoteSort: true,sortName:'createTime',sortStatus:'desc',
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });  
}

function toView(id){
	location.href=WST.U('admin/orders/view','id='+id);
}
function loadGrid(){
	var p = WST.getParams('.j-ipt');
    p.page = 1;
	mmg.load(p);
}
function toExport(){
	var params = {};
	params = WST.getParams('.j-ipt');
	var box = WST.confirm({content:"您确定要导出订单收款信息吗?",yes:function(){
		layer.close(box);
		location.href=WST.U('admin/orders/toExportOrderMoney',params);
    }});
}
function showDetail(id){
    parent.showBox({title:'订单详情',type:2,content:WST.U('admin/orders/view',{id:id,from:1}),area: ['1020px', '500px'],btn:['关闭']});
}
function loadMore(){
    var h = WST.pageHeight();
    if($('#moreItem').hasClass('hide')){
        $('#moreItem').removeClass('hide');
        mmg.resize({height:h-115});
    }else{
        $('#moreItem').addClass('hide');
        mmg.resize({height:h-85});
    }
}