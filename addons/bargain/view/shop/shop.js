var mmg;
function initGrid(p){
   var h = WST.pageHeight();
   var cols = [

       {title:'商品图片', name:'goodsName', width: 30,renderer:function(val,item,rowIndex){
               var html = [];
               html.push('<div class="goods-img">');
               html.push("<span class='weixin'><img class='img' style='height:50px;width:50px;' src='"+WST.conf.RESOURCE_PATH+"/"+item['goodsImg']+"'><img class='imged' style='height:200px;width:200px;max-width: 200px;max-height: 200px;' src='"+WST.conf.RESOURCE_PATH+"/"+item['goodsImg']+"'></span></div>");
               return html.join('');
           }},
       {title:'商品名称', name:'goodsName', width: 300},
        {title:'商品原价', name:'startPrice', width: 60,renderer:function(val,item,rowIndex){
        	return '￥'+item['startPrice'];
        }},
        {title:'商品底价', name:'floorPrice', width: 60,renderer:function(val,item,rowIndex){
        	return '￥'+item['floorPrice'];
        }},
        {title:'开始时间', name:'startTime', width: 120},
        {title:'结束时间', name:'endTime', width: 120},
        {title:'参与人数', name:'isNew', width: 30,renderer:function(val,item,rowIndex){
            return "<a style='color:blue' href='"+WST.AU("bargain://shops/joins","bargainId="+item["bargainId"])+'&p='+WST_CURR_PAGE+"'>"+item['joinNum']+"</a>";
        }},
        {title:'订单数', name:'isHot', width: 30,renderer:function(val,item,rowIndex){
            return "<a style='color:blue' href='"+WST.AU("bargain://shops/orders","bargainId="+item["bargainId"])+'&p='+WST_CURR_PAGE+"'>"+item['orderNum']+"</a>";
        }},
        {title:'状态', name:'attrSort', width: 70,renderer:function(val,item,rowIndex){
        	if(item['bargainStatus']==0){
               return "<span class='statu-wait'><i class='fa fa-clock-o'></i>待审核</span>";
            }else if(item['bargainStatus']==-1){
              return "<span class='statu-no' title='"+item['illegalRemarks']+"'><i class='fa fa-ban'></i>审核不通过</span>";
            }else{
              if(item['status']==0){
                 return "<span class='lbel lbel-info'>未开始</span>";
              }else if(item['status']==1){
                 return "<span class='lbel lbel-success'>进行中</span>";
              }else{
                 return "<span class='lbel lbel-gray'>已结束</span>";
              }
           }
        }},
        {title:'操作', name:'' ,width:110,renderer:function(val,item,rowIndex){
            var html = [];
            if(item['status']!=-1){
               html.push("<a  class='btn btn-blue' onclick='javascript:toEdit("+item["bargainId"]+")'><i class='fa fa-pencil'></i>编辑</a>");
            }
            html.push(" <a class='btn btn-red' onclick='javascript:del("+item["bargainId"]+")'><i class='fa fa-trash-o'></i>删除</a>");
            return html.join('');
        }}
    ];

    mmg = $('.mmg').mmGrid({height: h-100,indexCol: true, cols: cols,method:'POST',
        url: WST.AU('bargain://shops/pageQuery'), fullWidthRows: true, autoLoad: false,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
    loadGrid(p);
}

function loadGrid(p){
    var params = {};
    params = WST.getParams('.s-ipt');
    params.key = $.trim($('#key').val());
    p=(p<=1)?1:p;
    params.page=p;
    mmg.load(params);
}
var sgoods = [];
function searchGoods(){
	var params = {};
	params.shopCatId1 = $('#shopCatId1').val();
	params.shopCatId2 = $('#shopCatId2').val();
    params.goodsName = $('#sgoodsName').val();
    if(params.shopCatId1=='' && params.goodsName==''){
		 WST.msg('请至少选择商品分类',{icon:2});
		 return;
	}
	$('#goodsId').empty();
    var loading = WST.load({msg:'正在查询数据，请稍后...'});
	$.post(WST.AU("bargain://shops/searchGoods"),params,function(data,textStatus){
		layer.close(loading);
	    var json = WST.toJson(data);
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
        $('#startPrice').val(option1.shopPrice);
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
  $('#startPrice').val(option1.shopPrice);
}
function toEdit(id){
    location.href = WST.AU('bargain://shops/edit','id='+id+'&p='+WST_CURR_PAGE);
}
function toView(id){
	location.href = WST.AU('bargain://goods/detail','id='+id);
}

function save(p){
    $('#editform').isValid(function(v){
		if(v){
			var params = WST.getParams('.ipt');
			if(params.goodsId==''){
				WST.msg('请选择要参与砍价的商品',{icon:2});
				return;
			}
			var loading = WST.load({msg:'正在提交数据，请稍后...'});
			$.post(WST.AU("bargain://shops/toEdit"),params,function(data,textStatus){
				layer.close(loading);
			    var json = WST.toJson(data);
			    if(json.status==1){
		            WST.msg(json.msg,{icon:1},function(){
		            	location.href = WST.AU('bargain://shops/index','p='+p);
		            });
			    }else{
			    	WST.msg(json.msg,{icon:2});
			    }
			});
		}
	});
}
function del(id){
	var box = WST.confirm({content:"您确定删除该活动商品吗?",yes:function(){
		layer.close(box);
		var loading = WST.load({msg:'正在提交请求，请稍后...'});
		$.post(WST.AU("bargain://shops/del"),{id:id},function(data,textStatus){
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
function initJoinGrid(){
   var h = WST.pageHeight();
   var cols = [
        {title:'参与者', name:'userName', width: 300,renderer:function(val,item,rowIndex){
        	return "<div class='bargin-user-photo'><img height='50' src='__RESOURCE_PATH__/"+item['userPhoto']+"'/></div><div class='bargin-user-name'>"+item['userName']+'</div>';
        }},
        {title:'原价', name:'startPrice', width: 100,renderer:function(val,item,rowIndex){
        	return '￥'+item['startPrice'];
        }},
        {title:'当前价', name:'currPrice', width: 50,renderer:function(val,item,rowIndex){
        	return '￥'+item['currPrice'];
        }},
        {title:'亲友团人数', name:'createTime', width: 50,renderer:function(val,item,rowIndex){
        	return "<a style='color:blue' href='"+WST.AU("bargain://shops/showHelps","bargainJoinId="+item['id']+"&bargainId="+item['bargainId'])+"'>"+item['helpNum']+"</a>";
        }},
        {title:'参与时间', name:'createTime', width: 50},
        {title:'订单号', name:'createTime', width: 50,renderer:function(val,item,rowIndex){
        	if(d[i]['orderId']!=0){
                return "<a href='#none' onclick='view("+item['orderId']+")'>"+item['orderNo']+"</a>";
            }
        }}
    ];

    mmg = $('.mmg').mmGrid({height: h-100,indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.AU('bargain://shops/pageByJoins'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
}
function initHelpGrid(){
   var h = WST.pageHeight();
   var cols = [
        {title:'亲友名称', name:'userName', width: 300,renderer:function(val,item,rowIndex){
        	return "<div class='bargin-user-photo'><img height='50' src='__RESOURCE_PATH__/"+item['userPhoto']+"'/></div><div class='bargin-user-name'>"+item['userName']+'</div>';
        }},
        {title:'帮砍金额', name:'minusMoney', width: 100},
        {title:'砍价时间', name:'createTime', width: 50}
    ];

    mmg = $('.mmg').mmGrid({height: h-100,indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.AU('bargain://shops/pageByHelps'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
}

function initOrderGrid(p){
	$('#loading').show();
	var params = {};
	params = WST.getParams('.s-ipt');
	params.key = $.trim($('#key').val());
	params.page = p;
	$.post(WST.AU('bargain://shops/pageByOrders'),params,function(data,textStatus){
		$('#loading').hide();
	    var json = WST.toJson(data);
	    $('.j-order-row').remove();
	    if(json.status==1){
	    	json = json.data;
	       	var gettpl = document.getElementById('bargainlist').innerHTML;
	       	laytpl(gettpl).render(json.data, function(html){
	       		$(html).insertAfter('#loadingBdy');
	       		$('.gImg').lazyload({ effect: "fadeIn",failurelimit : 10,skip_invisible : false,threshold: 200,placeholder:WST.conf.RESOURCE_PATH+'/'+WST.conf.GOODS_LOGO});
	       	});
	       	if(json.last_page>1){
	       		laypage({
		        	 cont: 'pager', 
		        	 pages:json.last_page, 
		        	 curr: json.current_page,
		        	 skin: '#e23e3d',
		        	 groups: 3,
		        	 jump: function(e, first){
		        		 if(!first){
		        			 listByPage(e.curr);
		        		 }
		        	 } 
		        });
	       	}else{

	       		$('#pager').empty();
	       	}
       	} 
	});
}
function view(id){
    location.href=WST.U('home/orders/view','id='+id);
}
var editor1;
function initForm(){
	var laydate = layui.laydate;
    laydate.render({
        elem: '#startTime',
        type: 'datetime'
    });
    laydate.render({
        elem: '#endTime',
        type: 'datetime'
    });
}
