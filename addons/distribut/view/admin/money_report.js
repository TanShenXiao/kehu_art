var mmg;
function initGrid(){
    var laydate = layui.laydate;
    laydate.render({
        elem: '#startDate'
    });
    laydate.render({
        elem: '#endDate'
    });
    var h = WST.pageHeight();
    var cols = [
            {title:'订单编号', name:'orderNo', width: 60,renderer:function(val,item,rowIndex){
                $("#totalMoney").html(item.totalMoney);
                return val;
            }},
            {title:'获佣用户', name:'userName' ,width:60},
            {title:'佣金描述', name:'remark' ,width:120},
            {title:'订单金额', name:'distributMoney' ,width:60},
            {title:'佣金金额', name:'distributMoney' ,width:60},
            {title:'记录时间', name:'createTime' ,width:60}
        ];
 
    mmg = $('.mmg').mmGrid({height: (h-87),indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: WST.AU('distribut://distribut/adminDistributMoneysByPage',WST.getParams('.ipt')), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });  
}
function loadGrid(){
	var params = WST.getParams('.ipt');
    params.page = 1;
	mmg.load(params);
}
function toolTip(){
    WST.toolTip();
}
function toExport(){
    var params = WST.getParams('.ipt');
    var box = WST.confirm({content:"您确定要导出该统计数据吗?",yes:function(){
        layer.close(box);
        location.href=WST.AU('distribut://distribut/toExportDistributMoneys',params);
    }});
}