var mmg;
function initGrid(p){
   var h = WST.pageHeight();
   var cols = [
        {title:'商品图片', name:'goodsName', width: 30,renderer:function(val,item,rowIndex){
        	var html = [];
            html.push('<div class="goods-img"><a href="'+WST.AU("auction://goods/detail","id="+item["auctionId"])+'" target="_blank">');
            html.push("<span class='weixin'><img class='img' style='height:50px;width:50px;' src='"+WST.conf.RESOURCE_PATH+"/"+item['goodsImg']+"'><img class='imged' style='height:200px;width:200px;max-width: 200px;max-height: 200px;' src='"+WST.conf.RESOURCE_PATH+"/"+item['goodsImg']+"'></span></a></div>");
            return html.join('');
        }},
       {title:'商品名称', name:'goodsName', width: 300},
        {title:'起拍价格', name:'auctionPrice', width: 60,renderer:function(val,item,rowIndex){
        	return '￥'+item['auctionPrice'];
        }},
        {title:'开始时间', name:'startTime', width: 120},
        {title:'结束时间', name:'endTime', width: 120},
        {title:'当前拍卖价', name:'currPrice', width: 60,renderer:function(val,item,rowIndex){
        	return '￥'+item['currPrice'];
        }},
        {title:'参与人数', name:'isNew', width: 30,renderer:function(val,item,rowIndex){
            return "<a style='color:blue' href='"+WST.AU("auction://shops/bidding","id="+item["auctionId"])+"'>"+item['auctionNum']+"</a>";
        }},
        {title:'状态', name:'attrSort', width: 70,renderer:function(val,item,rowIndex){
        	if(item['auctionStatus']==0){
		        return "<span class='statu-wait'><i class='fa fa-clock-o'></i>待审核</span>";
		    }else if(item['auctionStatus']==-1){
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
        {title:'操作', name:'' ,width:150,renderer:function(val,item,rowIndex){
        	var html = [];
            if(item['auctionStatus']==1){
	           html.push("<a class='btn btn-blue' href='"+WST.AU("auction://goods/detail","id="+item["auctionId"])+"' target='_blank'><i class='fa fa-search'></i>查看</a>");
	        }
	        if(item['editable']==1){
	           html.push(" <a class='btn btn-blue' href='javascript:toEdit("+item["auctionId"]+")'><i class='fa fa-pencil'></i>编辑</a>");
	        }
	        if(item['auctionNum']>0){
	            if(item['orderId']>0){
	               html.push(" <a class='btn btn-blue' href='javascript:del("+item["auctionId"]+")'><i class='fa fa-trash-o'></i>删除</a>");
	            }
	        }else{
	            html.push(" <a class='btn btn-blue' href='javascript:del("+item["auctionId"]+")'><i class='fa fa-trash-o'></i>删除</a>");
	        }
	        return html.join("");
        }}
    ];

    mmg = $('.mmg').mmGrid({height: h-100,indexCol: true, cols: cols,method:'POST',
        url: WST.AU('auction://shops/pageQuery'), fullWidthRows: true, autoLoad: false,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
    loadGrid(p);
}

function loadGrid(p){
	p=(p<=1)?1:p;
    var params = {};
    params = WST.getParams('.s-ipt');
    params.key = $.trim($('#key').val());
    params.page=p;
    console.log(params);
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
	$.post(WST.AU("auction://shops/searchGoods"),params,function(data,textStatus){
		layer.close(loading);
	    var json = WST.toJson(data);
	    if(json.status==1 && json.data){
	    	var html = [];
	    	var option1 = null;
	    	sgoods = json.data;
	    	for(var i=0;i<json.data.length;i++){
	    		if(i==0)option1 = json.data[i];
                html.push('<option value="'+json.data[i].goodsId+'">'+json.data[i].goodsName+'</option>');
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
function toEdit(id){
    location.href = WST.AU('auction://shops/edit','id='+id+'&p='+WST_CURR_PAGE);
}
function toView(id){
	location.href = WST.AU('auction://goods/detail','id='+id);
}

function save(p){
    $('#editform').isValid(function(v){
		if(v){
			var params = WST.getParams('.ipt');
			if(params.goodsId==''){
				WST.msg('请选择要参与拍卖的商品',{icon:2});
				return;
			}
			var loading = WST.load({msg:'正在提交数据，请稍后...'});
			$.post(WST.AU("auction://shops/toEdit"),params,function(data,textStatus){
				layer.close(loading);
			    var json = WST.toJson(data);
			    if(json.status==1){
		            WST.msg(json.msg,{icon:1},function(){
		            	location.href = WST.AU('auction://shops/auction','p='+p);
		            });
			    }else{
			    	WST.msg(json.msg,{icon:2});
			    }
			});
		}
	});
}
function del(id){
	var box = WST.confirm({content:"您确定删除该拍卖商品吗?",yes:function(){
		layer.close(box);
		var loading = WST.load({msg:'正在提交请求，请稍后...'});
		$.post(WST.AU("auction://shops/del"),{id:id},function(data,textStatus){
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
        {title:'竞拍人', name:'loginName', width: 300},
        {title:'竞拍价', name:'payPrice', width: 100},
        {title:'竞拍时间', name:'createTime', width: 50},
        {title:'订单号', name:'createTime', width: 50,renderer:function(val,item,rowIndex){
        	return "<a href='#none' style='color:blue' onclick='view("+item['orderId']+")'>"+item['orderNo']+"</a>";
        }},
        {title:'&nbsp;', name:'createTime', width: 50 ,renderer:function(val,item,rowIndex){
        	if(item['isTop']==1)return '<span class="lbel lbel-success">最高价</span>';
        }}
    ];

    mmg = $('.mmg').mmGrid({height: h-100,indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.AU('auction://shops/pageAuctionLogQueryByShops'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
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
	KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="auctionDesc"]', {
			height:'550px',
			width:'99.5%',
			uploadJson : WST.conf.ROOT+'/home/goods/editorUpload',
			allowFileManager : false,
			allowImageUpload : true,
			allowMediaUpload : false,
			items:[
				'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
				'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
				'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
				'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
				'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
				'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','multiimage','media','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
				'anchor', 'link', 'unlink', '|', 'about'
			],
			afterBlur: function(){ this.sync(); }
		});
	});
}