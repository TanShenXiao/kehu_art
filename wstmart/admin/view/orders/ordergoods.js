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
            {title:'下单时间', name:'createTime', width: 100,sortable:true},
            {title:'订单编号', name:'orderNo', width: 50,sortable:true, renderer:function(val,item,rowIndex){
                var h = "";
	            h += "<img class='order-source2' src='"+WST.conf.ROOT+"/wstmart/admin/view/img/order_source_"+item['orderSrc']+".png'>";	
	            h += "<a style='cursor:pointer' onclick='javascript:showDetail("+ item['orderId'] +");'>"+item['orderNo']+"</a>";
	            return h;
            }},
            {title:'订单类型', name:'orderCode', width: 60,sortable:true},
            {title:'商品类型', name:'goodsType', width: 60,sortable:true},
            {title:'商品类别', name:'catName', width: 60,sortable:true},
            {title:'买家', name:'userName', width: 90,sortable:true},
            {title:'店铺', name:'shopName', width: 90,sortable:true},
            {title:'商品编码', name:'goodsSn' , width: 90,sortable:true},
            {title:'商品名称', name:'goodsName', width: 120,sortable:true},
            {title:'数量', name:'goodsNum' , width: 30,sortable:true},
            {title:'单价', name:'goodsPrice', width: 30,sortable:true, renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'总价', name:'totalPrice', width: 30,sortable:true, renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'支付方式', name:'payType' , width: 30,sortable:true},
            {title:'佣金比率', name:'commissionRate' , width: 30,sortable:true},
            {title:'佣金', name:'commission', width: 30,sortable:true, renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'订单状态', name:'orderStatus', width: 30,sortable:true, renderer:function(val,item,rowIndex){
            	 if(item['orderStatus']==-1 || item['orderStatus']==-3){
                     return "<span class='statu-no'><i class='fa fa-ban'></i> "+item.status+"</span>";
                 }else if(item['orderStatus']==2){
                     return "<span class='statu-yes'><i class='fa fa-check-circle'></i> "+item.status+"</span>";
            	 }else{
            	 	return "<span class='statu-wait'><i class='fa fa-clock-o'></i> "+item.status+"</span>";
            	 }
            }},
            {title:'操作' , width: 30,name:'status', renderer:function(val,item,rowIndex){
            	var h = "";
	            h += "<a class='btn btn-blue' href='javascript:toView(" + item['orderId'] + ")'><i class='fa fa-search'></i>详情</a> ";
	            return h;
            }}
            ];
 
    mmg = $('.mmg').mmGrid({height: (h-84),indexCol: true,indexColWidth:50, cols: cols,method:'POST',nowrap:true,
        url: WST.U('admin/orders/pageQueryOrderGoods',p.join('&')), fullWidthRows: true, autoLoad: true,remoteSort: true,sortName:'createTime',sortStatus:'desc',
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
	var box = WST.confirm({content:"您确定要导出订单吗?",yes:function(){
		layer.close(box);
		location.href=WST.U('admin/orders/toExportOrderGoods',params);
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