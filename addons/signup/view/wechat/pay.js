jQuery.noConflict();
function inEffect(obj,n){
	$("ul div").removeClass('j-selected');
	$(obj).addClass('j-selected');
}
function changeSelected(n,index,obj){
	$('#'+index).val(n);
	if(n==0){
		$(".j-charge-other").hide();
		$(".j-charge-money").show();
		var needPay =  $("#needPay").val();
		
	}else{
		$(".j-charge-other").show();
		$(".j-charge-money").hide();
		var needPay = $("#needPay_"+n).attr("sum");
	}
	rechargeMoney(needPay);
	inEffect(obj,2);
}
function rechargeMoney(n){
	$("#rechargeMoney").html(n);
}


function toPay(){
	var params = {};
		params.payObj = "signup";
		params.targetType = 0;
		params.needPay = $.trim($("#signupFee").val());
		params.payCode = $("input[name='payCode']:checked").val();
		params.itemId = $.trim($("#itemId").val());
		params.catId = $("#catId").val();
		params.orderNo = $("#orderNo").val();
	if(params.payCode==""){
		WST.msg('请先选择支付方式','info');
		return;
	}
	if(params.payCode=="weixinpays"){
		location.href = WST.U('wechat/weixinpays/toPay',params);
	}else if(params.payCode=="wallets"){
		location.href = WST.AU('signup://signup/mpayWallets',params);
 	}
	
	
}

$(function(){
	jQuery(".wst-frame2:first").click();
});