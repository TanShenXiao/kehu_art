var mmg;
function initGrid(p){
   var h = WST.pageHeight();
   var cols = [
        {title:'领取会员', name:'loginName', width: 120},
        {title:'领取时间', name:'createTime', width: 120},
        {title:'状态', name:'isUse', width: 30,renderer:function(val,item,rowIndex){
        	return (item['isUse']==0)?"-":"已使用";
        }},
        {title:'使用时间', name:'useTime', width: 120},
        {title:'关联订单', name:'orderNo', width: 120}
    ];

    mmg = $('.mmg').mmGrid({height: h-100,indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.AU('coupon://shops/pageQueryByCoupons'), fullWidthRows: true, autoLoad: false,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
    loadGrid(p);
}
function loadGrid(p){
	var params={};
	p=(p<=1)?1:p;
	params.page=p;
	params.isUse = $('#isUse').val();
	params.couponId = $('#couponId').val();
    mmg.load(params);
}

function toBack(p){
    location.href = WST.AU('coupon://shops/index','p='+p);
}


