function edit(){
	var params = WST.getParams('.ipt');
	var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
	$.post(WST.AU('vote://vote/editConfig'),params,function(data,textStatus){
		  layer.close(loading);
		  var json = WST.toAdminJson(data);
		  if(json.status=='1'){
				WST.msg("操作成功",{icon:1});
		  }else{
				WST.msg(json.msg,{icon:2});
		  }
	});
}