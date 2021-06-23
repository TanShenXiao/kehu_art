var mmg;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
        {title:'店铺编号', name:'shopSn', width: 30},
        {title:'店铺账号', name:'loginName',width: 100},
        {title:'店铺名称', name:'shopName',width: 100},
        {title:'所属行业', name:'tradeName',width: 100},
        {title:'店铺地址', name:'shopAddress',width:200 },
        {title:'到期日期', name:'expireDate' ,width: 100,sortable: true},
            ];
    mmg = $('.mmg').mmGrid({height: (h-87),indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: WST.U('admin/reports/shopExpireByPage',WST.getParams('.ipt')), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
}
function c(){
    var params = WST.getParams('.j-ipt');
    params.areaIdPath = WST.ITGetAllAreaVals('areaId1','j-areas').join('_');
    params.page = 1;
	mmg.load(params);
}
function toolTip(){
    WST.toolTip();
}
function toExport(){
    var params = WST.getParams('.j-ipt');
    params.areaIdPath = WST.ITGetAllAreaVals('areaId1','j-areas').join('_');
    var box = WST.confirm({content:"您确定要导出该统计数据吗?",yes:function(){
        layer.close(box);
        location.href=WST.U('admin/reports/toExportShopExpire',params);
    }});
}
