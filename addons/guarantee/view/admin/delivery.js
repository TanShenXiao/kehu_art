function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'商品编号', name:'goodsSn', width: 130},
            {title:'商品名称', name:'goodsName', width: 130},
            {title:'销售方账号', name:'sellerLoginName', width: 100},
            {title:'提货方账号', name:'buyerLoginName', width: 100},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,rowdata,rowIndex){
                var h = "";
	            h += "<a class='btn btn-red' href='javascript:toEdit(" + rowdata['goodsId'] + ")'><i class='fa fa-check'></i>确认提货</a> ";
	            return h;
	        }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-120,indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: WST.AU('guarantee://guarantee/pageQueryDelivery'), fullWidthRows: true, autoLoad: true,
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
	var box = WST.open({title:'请输入验证码',type:1,content:$('#verifyDlg'),area: ['450px', '220px'],btn: ['确定','取消'],yes:function(){
			$('#verifyForm').submit();
	},cancel:function(){
		//重置表单
		$('#verifyForm')[0].reset();
	},end:function(){
		//重置表单
		$('#verifyForm')[0].reset();
		$('#verifyDlg').hide();
	}});

	$('#verifyForm').validator({
        fields: {
            verifyCode: {
            	rule:"required;",
            	msg:{required:"请输入验证码"},
            	tip:"请输入验证码",
            	ok:"",
            },
        },
       valid: function(form){
			var params = WST.getParams('.ipt');
		    params.goodsId = id;
			var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
			$.post(WST.AU('guarantee://guarantee/verifyCode'),params,function(data,textStatus){
				  layer.close(loading);
				  var json = WST.toAdminJson(data);
				  if(json.status=='1'){
						WST.msg("验证成功，提货完成",{icon:1});
						$('#verifyForm')[0].reset();
						layer.close(box);
						loadGrid();
				  }else{
						WST.msg(json.msg,{icon:2});
				  }
			});
    	}
  });
}