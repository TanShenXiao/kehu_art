function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'商品编号', name:'goodsSn', width: 130},
            {title:'商品名称', name:'goodsName', width: 130},
            {title:'销售方账号', name:'sellerLoginName', width: 100},
            {title:'保底方账号', name:'buyerLoginName', width: 100},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,rowdata,rowIndex){
                var h = "";
				var op = "";
				if(rowdata['bdBackStatus']==0)	op = '联系买家';
				if(rowdata['bdBackStatus']==1)	op = '线下付款';
				if(rowdata['bdBackStatus']==2)	op = '完成回购';
	            h = "<a class='btn btn-red' href='javascript:toEdit(" + rowdata['goodsId'] + ")'><i class='fa fa-check'></i>" + op + "</a> ";
	            return h;
	        }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-120,indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: WST.AU('guarantee://guarantee/pageQueryBackSale'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadGrid(){
	var params = {};
	params.goodsName = $('#goodsName').val();
	mmg.load(params);
}

function toEdit(id){
	var params = {};
	params.goodsId = id;
	var box = WST.confirm({title:'确认',content:"请在线下执行该操作后再确认，确认已执行吗?",yes:function(){
		var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
		$.post(WST.AU('guarantee://guarantee/confirmBackSale'),params,function(data,textStatus){
			  layer.close(loading);
			  var json = WST.toAdminJson(data);
			  if(json.status=='1'){
					WST.msg("操作成功",{icon:1});
					layer.close(box);
					loadGrid();
			  }else{
					WST.msg(json.msg,{icon:2});
			  }
		});
	}});
}