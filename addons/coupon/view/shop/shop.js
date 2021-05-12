var mmg;
function initGrid(p){
   var h = WST.pageHeight();
   var cols = [
        {title:'面值', name:'couponValue', width: 100,renderer:function(val,item,rowIndex){
        	return '￥'+item['couponValue'];
        }},
        {title:'类型', name:'startPrice', width: 60,renderer:function(val,item,rowIndex){
        	return (item['useCondition']==1)?("满"+item['useMoney']+"减"+item['couponValue']+"券"):"现金券";
        }},
        {title:'适用对象', name:'floorPrice', width: 60,renderer:function(val,item,rowIndex){
        	return (item['useObjects']==0)?"全店通用":"部分商品";
        }},
        {title:'开始时间', name:'startDate', width: 120},
        {title:'结束时间', name:'endDate', width: 120},
        {title:'发放量', name:'couponNum', width: 30},
        {title:'领取数量', name:'receiveNum', width: 30},
        {title:'操作', name:'' ,width:190,renderer:function(val,item,rowIndex){
            var html = [];
            if(item['receiveNum']>0)html.push("<a class='btn btn-blue' onclick='javascript:toStat("+item["couponId"]+")'><i class='fa fa-line-chart'></i>统计</a> ");
            html.push("<a class='btn btn-blue' onclick='javascript:toList("+item["couponId"]+")'><i class='fa fa-search'></i>查看</a> ");
            html.push("<a class='btn btn-blue' onclick='javascript:toEdit("+item["couponId"]+")'><i class='fa fa-pencil'></i>编辑</a>");
            html.push(" <a class='btn btn-red' onclick='javascript:del("+item["couponId"]+")'><i class='fa fa-trash-o'></i>删除</a>");
            return html.join('');
        }}
    ];

    mmg = $('.mmg').mmGrid({height: h-100,indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.AU('coupon://shops/pageQuery'), fullWidthRows: true, autoLoad: false,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
    loadGrid(p);
}
function loadGrid(p){
	var params=WST.getParams('#useCondition');
	p=(p<=1)?1:p;
	params.page=p;
    mmg.load(params);
}
function checkUseCondition(v){
    if(v==1){
    	$('#useMoney').attr('disabled',false);
    }else{
    	$('#useMoney').val(0);
    	$('#useMoney').attr('disabled',true);
    }
}
function toStat(id){
	location.href = WST.AU('coupon://shops/toStat','id='+id+'&p='+WST_CURR_PAGE);
}
function toList(id){
	location.href = WST.AU('coupon://shops/coupons','id='+id+'&p='+WST_CURR_PAGE);
}
function toEdit(id){
    location.href = WST.AU('coupon://shops/edit','id='+id+'&p='+WST_CURR_PAGE);
}
function toView(id){
	location.href = WST.AU('coupon://goods/detail','id='+id);
}

function save(p){
    $('#couponform').isValid(function(v){
		if(v){
			var params = WST.getParams('.ipt');
			var loading = WST.load({msg:'正在提交数据，请稍后...'});
			$.post(WST.AU("coupon://shops/toEdit"),params,function(data,textStatus){
				layer.close(loading);
			    var json = WST.toJson(data);
			    if(json.status==1){
		            WST.msg(json.msg,{icon:1},function(){
		            	location.href = WST.AU('coupon://shops/index',"p="+p);
		            });
			    }else{
			    	WST.msg(json.msg,{icon:2});
			    }
			});
		}
	});
}
function del(id){
	var box = WST.confirm({content:"您确定删除该优惠券吗?",yes:function(){
		layer.close(box);
		var loading = WST.load({msg:'正在提交请求，请稍后...'});
		$.post(WST.AU("coupon://shops/del"),{id:id},function(data,textStatus){
			layer.close(loading);
		    var json = WST.toJson(data);
			if(json.status==1){
			    WST.msg(json.msg,{icon:1},function(){
			        loadGrid(WST_CURR_PAGE);
			    });
		    }else{
				WST.msg(json.msg,{icon:2});
			}
		});
	}});
}

function searchGoods(){
	var params = WST.getParams('.s-ipt');
    var loading = WST.load({msg:'正在查询数据，请稍后...'});
	$.post(WST.AU("coupon://shops/searchGoods"),params,function(data,textStatus){
		layer.close(loading);
	    var json = WST.toJson(data);
	    $('#goodsSearchBox').empty();
	    if(json.status==1 && json.data){
	    	var gettpl = document.getElementById('tblist').innerHTML;
	       	laytpl(gettpl).render(json.data, function(html){
	       		$('#goodsSearchBox').html(html);
	       	});
	    }
	});
}

function moveRight(){
	if($('.lchk').size()<=0)return;
	var ids = $('#useObjectIds').val();
	if(ids.length>0){
		ids = ids.split(',');
	}else{
		ids = [];
	}
	$('.lchk').each(function(){
		if($(this)[0].checked){
	        $(this).attr('class','rchk');
	        $('#goodsResultBox').append($(this).parent().parent());
	        ids.push($(this).val());
	    }
	})
	$('#useObjectIds').val(ids.join(','));
}

function moveLeft(){
	if($('.rchk').size()<=0)return;
	var ids = $('#useObjectIds').val().split(',');
	$('.rchk').each(function(){
		if($(this)[0].checked){
	        $(this).attr('class','lchk');
	        $('#goodsSearchBox').append($(this).parent().parent());
	        for(var i=0;i<ids.length;i++){
	        	if(ids[i]==$(this).val())ids.splice(i, 1);
	        }
	    }
	})
    $('#useObjectIds').val(ids.join(','));
}