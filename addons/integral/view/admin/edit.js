
function getShopsCats(objId,pVal,objVal){
	$('#'+objId).empty();
	$.post(WST.AU('integral://shops/getShopCats'),{parentId:pVal},function(data,textStatus){
	     var json = WST.toAdminJson(data);
	     var html = [],cat;
	     html.push("<option value='' >-请选择-</option>");
	     if(json.status==1 && json.list){
	    	 json = json.list;
			 for(var i=0;i<json.length;i++){
			     cat = json[i];
			     html.push("<option value='"+cat.catId+"' "+((objVal==cat.catId)?"selected":"")+">"+cat.catName+"</option>");
			 }
	     }
	     $('#'+objId).html(html.join(''));
	});
}
var sgoods = [];
function searchGoods(){
	var params = {};
    params.goodsName = $('#sgoodsName').val();
    params.goodsCatIdPath = WST.ITGetAllGoodsCatVals('cat_0','j-goodsCats').join('_');
    if(params.goodsCatIdPath=='' && params.goodsName==''){
		 WST.msg('请至少选择商品分类',{icon:2});
		 return;
	}
	$('#goodsId').empty();
	$.post(WST.AU("integral://shops/searchGoods"),params,function(data,textStatus){
	    var json = WST.toAdminJson(data);
	    if(json.status==1 && json.data){
	    	var html = [];
	    	var option1 = null;
            sgoods = json.data;
	    	for(var i=0;i<json.data.length;i++){
	    		if(i==0)option1 = json.data[i];
                html.push('<option value="'+json.data[i].goodsId+'" gt="'+json.data[i].goodsType+'" mp="'+json.data[i].marketPrice+'" sp="'+json.data[i].marketPrice+'">'+json.data[i].goodsName+'</option>');
	    	}
	    	$('#goodsId').html(html.join(''));
	    	$('#goodsSeoDesc').val(option1.goodsSeoDesc);
	    	$('#goodsName').val(option1.goodsName);
	    	$('#goodsSeoKeywords').val(option1.goodsSeoKeywords);
	    	$('#marketPrice').html("￥"+option1.marketPrice);
	    }
	});
}
function changeGoods(obj){
    var option1 = null
	for(var i=0;i<sgoods.length;i++){
		if(obj.value==sgoods[i].goodsId)option1 = sgoods[i];
	}
    $('#goodsSeoDesc').val(option1.goodsSeoDesc);
	$('#goodsName').val(option1.goodsName);
	$('#goodsSeoKeywords').val(option1.goodsSeoKeywords);
	$('#marketPrice').html("￥"+option1.marketPrice);
}


function editInit(p){
	var laydate = layui.laydate;
	laydate.render({
	    elem: '#startTime',
	    type: 'datetime'
	});
	laydate.render({
	    elem: '#endTime',
	    type: 'datetime'
	});
	 /* 表单验证 */
    $('#integralform').validator({
          valid: function(form){
            var params = WST.getParams('.ipt');
			if(params.goodsId==''){
				WST.msg('请选择要参与积分商城的商品',{icon:2});
				return;
			}
			$.post(WST.AU("integral://goods/edit"),params,function(data,textStatus){
			    var json = WST.toAdminJson(data);
			    if(json.status==1){
		            WST.msg(json.msg,{icon:1},function(){
		            	location.href = WST.AU('integral://goods/pageByAdmin','p='+p);
		            });
			    }else{
			    	WST.msg(json.msg,{icon:2});
			    }
			});

      }

	});
}
