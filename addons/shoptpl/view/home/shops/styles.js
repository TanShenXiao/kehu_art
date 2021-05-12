var laytpl = layui.laytpl;
$(function (){ 
	listQuery(0);
});
function listQuery(type){
	var loading = WST.msg('正在获取数据，请稍后...', {icon: 16,time:60000});
	$.post(WST.AU('Shoptpl://shoptpl/listQuery'),{type:type},function(data,textStatus){
		$('#tplType').val(type);
		layer.close(loading);
		var json = WST.toJson(data);
		if(json.status=='1'){
			var gettpl = document.getElementById('tblist').innerHTML;
	       	laytpl(gettpl).render(json.data, function(html){
	       		$('#shoptpls').html(html);
	       	});
	       	$('.btn').click(function(){
                changeTpl($(this),$(this).attr('dataid'));
            });
		}
	});
}
function changeTpl(obj,id){
	if(obj.hasClass('btn-disabled'))return;
	var box = WST.confirm({content:"您确定要使用这套模板吗?",yes:function(){
		var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
		var tplType = $('#tplType').val();
		$.post(WST.AU('Shoptpl://shoptpl/changeTpl'),{id:id,type:tplType},function(data,textStatus){
			layer.close(loading);
			var json = WST.toJson(data);
			if(json.status=='1'){
				WST.msg(json.msg,{icon:1});
				layer.close(box);
				$('.btn-disabled').attr('disabled',false).val('启用').addClass('btn-success').removeClass('btn-disabled');
				$('.style_'+id).removeClass('btn-success').addClass('btn-disabled').attr('disabled',true).val('应用中');
			}else{
				WST.msg(json.msg,{icon:2});
			}
		});
	}});
}