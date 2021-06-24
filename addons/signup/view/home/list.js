$(function(){
	$(".wst-frame2:first").click();
	$("#wst-check-orders").click(function(){
		$("#wst-orders-box").slideToggle(600);
	});
	$("div[class^=wst-payCode]").click(function(){
		var payCode = $(this).attr("data");
		$("div[class^=wst-payCode]").each(function(){
			$(this).removeClass().addClass("wst-payCode-"+$(this).attr("data"));
		});
		$(this).removeClass().addClass("wst-payCode-"+payCode+"-curr");
		$("#payCode").val(payCode);
	});
	if($("div[class^=wst-payCode]").length>0){
		$("div[class^=wst-payCode]")[0].click();
	};
});

function toSubmit(){
	$('#tabname').html('开始报名');
	$('#catdesctab').hide();
	$('#submittab').show();
	$('#paystep1').hide();
	$('#paystep2').hide();
	$('#aftersignuptab').hide();
}

function toEnd(){
	$('#tabname').html('报名成功');
	$('#catdesctab').hide();
	$('#submittab').hide();
	$('#paystep1').hide();
	$('#paystep2').hide();
	$('#aftersignuptab').show();
};

function toPay(){
	$('#tabname').html('缴纳费用');
	$('#catdesctab').hide();
	$('#submittab').hide();
	$('#paystep1').show();
	$('#paystep2').hide();
	$('#aftersignuptab').hide();
};

function toPayStep2(){
	$('#tabname').html('缴纳费用');
	$('#catdesctab').hide();
	$('#submittab').hide();
	$('#paystep1').hide();
	$('#paystep2').show();
	$('#aftersignuptab').hide();
};

function submit(needPay){
	layer.confirm('提交后不可修改，请确认', {
        	  btn: ['确定','取消'] //按钮
        }, function(index){
			var pList = WST.getParams('.a-ipt');
			var pExtra = WST.getParams('.e-ipt');
			var params = {'listData':pList,'extraData':pExtra};
			//params.catId = id;
			var loading = WST.msg('正在提交数据，请稍候...', {icon: 16,time:60000});
			$.post(WST.AU('signup://lists/addlist'),params,function(data,textStatus){
				  layer.close(loading);
				  var json = WST.toJson(data);
				  if(json.status=='1'){
						WST.msg("提交成功",{icon:1});
						$('#listId').val(json.data.listId);
						setTimeout(function(){
							if(needPay==0)
								toEnd();
							else{
								$('#pkey').val(json.data.pkey);
								toPay();
							}
						},1000);
				  }else{
						WST.msg(json.msg,{icon:2});
				  }
			});
        	layer.close(index);
     	});
};