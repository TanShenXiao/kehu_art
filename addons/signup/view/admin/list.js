var mmg,isInitUpload = false;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'项目名称', name:'catName', width: 130, sortable:true},
            {title:'报名开始时间', name:'startDate', width: 100, sortable:true},
            {title:'报名截止时间', name:'endDate', width: 100, sortable:true},
            {title:'会员账号', name:'loginName', width: 100, sortable:true},
            {title:'报名时间', name:'createTime', width: 100, sortable:true},
            {title:'是否支付', name:'isPaid', width: 60, sortable:true, renderer: function(val,rowdata,rowIndex){
				if(rowdata['isPaid']==0){
                    return "<span class='statu-wait'>否</span>";
	        	}else{
                    return "<span class='statu-no'>是</span>";
	        	}
			}},
            {title:'支付时间', name:'payTime', width: 100, sortable:true},
            {title:'支付通道', name:'payCode', width: 60, sortable:true,renderer: function (rowdata, rowindex, value){
				if(rowindex['payCode']=='cod')	return '货到付款';
				else if(rowindex['payCode']=='alipays')	return '支付宝';
				else if(rowindex['payCode']=='weixinpays')	return '微信支付';
				else if(rowindex['payCode']=='wallets')	return '余额支付';
			}},
            {title:'支付通道号', name:'tradeNo', width: 130, sortable:true},
            {title:'报名费', name:'signupFee', width: 60, sortable:true},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,rowdata,rowIndex){
                var h = "";
	            if(WST.GRANT.SIGNUP_BMCK)h += "<a class='btn btn-red' href='javascript:about(" + rowdata['listId'] + ",1)'><i class='fa fa-search'></i>查看</a> ";
	            if(WST.GRANT.SIGNUP_BMSCXXX)h += "<a class='btn btn-red' href='javascript:del(" + rowdata['listId'] + ",0)'><i class='fa fa-trash'></i>删除</a>"; 
	            return h;
	        }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-120,indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: WST.AU('signup://lists/pageQueryByAdmin'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadGrid(){
	var params = {};
	params.catName = $('#catName').val();
	params.startTime = $('#startTime').val();
	params.endTime = $('#endTime').val();
	mmg.load(params);
}

function del(id,type){
	var box = WST.confirm({content:"您确定要删除该报名信息吗?",yes:function(){
	           var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
	           $.post(WST.AU('signup://lists/delByAdmin'),{id:id},function(data,textStatus){
	           			layer.close(loading);
	           			var json = WST.toAdminJson(data);
	           			if(json.status=='1'){
	           			    WST.msg(json.msg,{icon:1});
	           			    layer.close(box);
	           			    if(type==0){
	           		            loadGrid1();
	           			    }else{
	           			    	loadGrid2();
	           			    }
	           			}else{
	           			    WST.msg(json.msg,{icon:2});
	           			}
	           		});
	            }});
}
function about(id,type){
	location.href=WST.AU('signup://lists/userSignupInfo','id='+id);
	return;
	var w = WST.open({type: 1,title:"报名详情",shade: [0.6, '#000'],border: [0],
	    content: '<textarea id="illegalRemarks" rows="7" style="width:100%" maxLength="200"></textarea>',
	    area: ['500px', '600px'],btn: ['确定', '关闭窗口'],
        yes: function(index, layero){
        }
	});
}

function toExport1(){
	var params = {};
	params = WST.getParams('.query');
	var box = WST.confirm({content:"您确定要导出报名信息总览吗?",yes:function(){
		layer.close(box);
		location.href=WST.AU('signup://lists/toExportOverview',params);
    }});
}
function toExport2(){
	var params = {};
	params = WST.getParams('.query');
	var box = WST.confirm({content:"您确定要导出报名信息详情吗?",yes:function(){
		layer.close(box);
		location.href=WST.AU('signup://lists/toExport',params);
    }});
}

function toolTip(){
    WST.toolTip();
}

