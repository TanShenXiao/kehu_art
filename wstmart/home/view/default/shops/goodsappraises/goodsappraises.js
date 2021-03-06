/**获取本店分类**/
function getShopsCats(objId,pVal,objVal){
	$('#'+objId).empty();
	$.post(WST.U('home/shopcats/listQuery'),{parentId:pVal},function(data,textStatus){
	     var json = WST.toJson(data);
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
function getCat(val){
  if(val==0){
     $('#cat2').html("<option value='' >-请选择-</option>");
     return;
  }
  $.post(WST.U('home/shopcats/listQuery'),{parentId:val},function(data,textStatus){
       var json = WST.toJson(data);
       var html = [],cat;
       html.push("<option value='' >-请选择-</option>");
       if(json.status==1 && json.list){
         json = json.list;
       for(var i=0;i<json.length;i++){
           cat = json[i];
           html.push("<option value='"+cat.catId+"'>"+cat.catName+"</option>");
        }
       }
       $('#cat2').html(html.join(''));
  });
}
function showImg(id){
  layer.photos({
      photos: '#img-file-'+id
    });
}
function queryByPage(p){
	$('#list').html('<tr><td colspan="5"><img src="'+WST.conf.ROOT+'/wstmart/home/view/default/img/loading.gif">正在加载数据...</td></tr>');
	var params = {};
	params = WST.getParams('.s-query');
	params.key = $.trim($('#key').val());
	params.page = p;
	$.post(WST.U('home/goodsappraises/queryByPage'),params,function(data,textStatus){
	    var json = WST.toJson(data);
	    $('#list').empty();
	    if(json.status==1){
	    	json = json.data;
	       	var gettpl = document.getElementById('tblist').innerHTML;
	       	laytpl(gettpl).render(json.data, function(html){
	       		$('#list').html(html);
	        	for(var g=0;g<=json.data.length;g++){
	       			showImg(g);
	       		}
	       		$('.gImg').lazyload({ effect: "fadeIn",failurelimit : 10,skip_invisible : false,threshold: 200,placeholder:window.conf.RESOURCE_PATH+'/'+window.conf.GOODS_LOGO});
	       	});
	       	laypage({
		        cont: 'pager', 
		        pages:json.last_page, 
		        curr: json.current_page,
		        skin: '#e23e3d',
		        groups: 3,
		        jump: function(e, first){
		        	if(!first){
		        		queryByPage(e.curr);
		        	}
		        } 
		    });
       	}  
	});
}

function reply(t,id){
 var params = {};
 if($('#reply-'+id).val()==''){
    WST.msg('回复内容不能为空',{icon:2});
    return false;
 }
 params.reply = $('#reply-'+id).val();
 params.id=id;
 $.post(WST.U('home/goodsappraises/shopReply'),params,function(data){
    var json = WST.toJson(data);
    if(json.status==1){
      var today = new Date();
          today = today.toLocaleDateString();
      var html = '<p class="reply-content">'+params.reply+'【'+today+'】</p>'
      $(t).parent().html(html);
    }
 });
}
