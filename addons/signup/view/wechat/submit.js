function toEnd(catId){
	location.href = WST.AU("signup://signup/mend","catId="+catId);
}

function toSubmit(catId,needPay){
    WST.dialog('提交后不可修改，请确认','submit("'+needPay+'","'+catId+'")');
}

function toPay(catId){
	location.href = WST.AU("signup://signup/mpay","catId="+catId);
}

function submit(needPay,catId){
			var pList = getParams('.a-ipt');
			var pExtra = getParams('.e-ipt');
			var params = {'listData':pList,'extraData':pExtra};
			//params.catId = id;
			//var loading = WST.msg('正在提交数据，请稍候...', {icon: 16,time:60000});
			$.post(WST.AU('signup://lists/addlist'),params,function(data,textStatus){
				  //layer.close(loading);
				  var json = WST.toJson(data);
				  if(json.status=='1'){
						WST.msg("提交成功",{icon:1});
						$('#listId').val(json.data.listId);
						setTimeout(function(){
							if(needPay==0)
								location.href = WST.AU("signup://signup/mend","catId="+catId);
							else location.href = WST.AU("signup://signup/mpay","catId="+catId);
						},1000);
				  }else{
						WST.msg(json.msg,{icon:2});
				  }
			});
        	//layer.close(index);
};

getParams = function(obj){
	var params = {};
	var chk = {},s;
	$(obj).each(function(){
		if($(this)[0].type=='hidden' || $(this)[0].type=='number' || $(this)[0].type=='tel' || $(this)[0].type=='password' || $(this)[0].type=='select-one' || $(this)[0].type=='textarea' || $(this)[0].type=='text'){
			params[$(this).attr('id')] = $.trim($(this).val());
		}else if($(this)[0].type=='radio'){
			if($(this).attr('name')){
				params[$(this).attr('name')] = $('input[name='+$(this).attr('name')+']:checked').val();
			}
		}else if($(this)[0].type=='checkbox'){
			if($(this).attr('name') && !chk[$(this).attr('name')]){
				s = [];
				chk[$(this).attr('name')] = 1;
				$('input[name='+$(this).attr('name')+']:checked').each(function(){
					s.push($(this).val());
				});
				params[$(this).attr('name')] = s.join(',');
			}
		}
	});
	chk=null,s=null;
	return params;
}