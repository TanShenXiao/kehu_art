var mmg1,mmg2,isInit1 = false,isInit2 = false;

function initGrid1(p){
    if(isInit1){
        loadGrid1(p);
        return;
    }
    isInit1 = true;
    var h = WST.pageHeight();
    var cols = [
            {title:'&nbsp;', name:'goodsImg', width: 50, renderer: function(val,item,rowIndex){
                var thumb = item['goodsImg'];
	        	thumb = thumb.replace('_thumb.','.');
                return "<span class='weixin'><img id='img' onmouseout='toolTip()' onmouseover='toolTip()' style='height:50px;width:50px;' src='"+WST.conf.RESOURCE_PATH+"/"+item['goodsImg']
            	+"'><span class='imged' ><img  style='height:180px;width:180px;' src='"+WST.conf.RESOURCE_PATH+"/"+thumb+"'></span></span>";
            }},
            {title:'商品名称', name:'goodsName', width: 100},
            {title:'所属店铺', name:'shopName', width: 100},
            {title:'起拍价', name:'auctionPrice', width: 30},
            {title:'保证金', name:'cautionMoney', width: 30},
            {title:'加价幅度', name:'fareInc', width: 30},
            {title:'参与人数', name:'auctionNum', width: 30,renderer: function(val,item,rowIndex){
                return "<a style='color:blue;text-decoration:underline' href=\"javascript:logs(" + item['auctionId'] + ")\">"+item['auctionNum']+"</a>";
            }},
            {title:'拍卖时间', name:'startTime', width: 100, align:'center',renderer: function(val,item,rowIndex){
            	return item['startTime']+"<br/>至<br/>"+item['endTime'];
            }},
            {title:'状态', name:'saleNum', width: 30,renderer: function(val,item,rowIndex){
            	if(item['status']==1){
                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> 进行中</span>";
	        	}else if(item['status']==0){
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 未开始</span>";
	        	}else{
                    return "<span class='statu-no'><i class='fa fa-ban'></i> 已结束</span>";
	        	}
            }},
            {title:'订单状态', name:'saleNum', width: 30,renderer: function(val,item,rowIndex){
            	if(item['auctionNum']>0){
                    if(item['orderId']>0){
                        return "<span class='statu-yes' style='margin-top:15px;'><i class='fa fa-check-circle'></i> 已下单</span>";
                    }else{
                        return "<span class='statu-wait' style='margin-top:15px;'><i class='fa fa-clock-o'></i> 未下单</span>";
                    }
                }
            }},
            {title:'操作', name:'' ,width:100, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
	            h += "<a class='btn btn-blue' target='_blank' href='"+WST.AU("auction://goods/detail","id="+item['auctionId']+"&key="+item['verfiycode'])+"'><i class='fa fa-search'></i>查看</a> ";
	            if(item['status']==1 && item['auctionNum']==0){
	                if(WST.GRANT.AUCTION_PMHD_04)h += "<a class='btn btn-red' href='javascript:illegal(" + item['auctionId'] + ",0)'><i class='fa fa-ban'></i>下架</a> ";
	            }
	            if(WST.GRANT.AUCTION_PMHD_03){
	            	if(item['auctionNum']>0){
	            		if(item['orderId']>0)h += "<a class='btn btn-red' href='javascript:del(" + item['auctionId'] + ",0)'><i class='fa fa-trash'></i>删除</a></div> "; 
	            	}else{
                        h += "<a class='btn btn-red' href='javascript:del(" + item['auctionId'] + ",0)'><i class='fa fa-trash'></i>删除</a> "; 
	            	}
	            }
	            return h;
	        }}
            ];
 
    mmg1 = $('.mmg1').mmGrid({height: h-125,indexCol: true, indexColWidth:50, cols: cols,method:'POST',
        url: WST.AU('auction://goods/pageQueryByAdmin'), fullWidthRows: true, autoLoad: false,
        plugins: [
            $('#pg1').mmPaginator({})
        ]
    });
    loadGrid1(p)
}
function loadGrid1(p){
	p=(p<=1)?1:p;
	var params = {};
	params.page=p;
	params.shopName = $('#shopName1').val();
	params.goodsName = $('#goodsName1').val();
	params.areaIdPath = WST.ITGetAllAreaVals('areaId1','j-areas').join('_');
	params.goodsCatIdPath = WST.ITGetAllGoodsCatVals('cat1_0','pgoodsCats').join('_');
	mmg1.load(params);
}
function initGrid2(p){
    if(isInit2){
        loadGrid2(p);
        return;
    }
    isInit2 = true;
    var h = WST.pageHeight();
    var cols = [
        {title:'&nbsp;', name:'bankName', width: 50, renderer: function(val,item,rowIndex){
                var thumb = item['goodsImg'];
                thumb = thumb.replace('_thumb.','.');
                return "<span class='weixin'><img id='img' onmouseout='toolTip()' onmouseover='toolTip()' style='height:50px;width:50px;' src='"+WST.conf.RESOURCE_PATH+"/"+item['goodsImg']
                    +"'><span class='imged' ><img  style='height:180px;width:180px;' src='"+WST.conf.RESOURCE_PATH+"/"+thumb+"'></span></span>";
            }},
        {title:'商品名称', name:'goodsName', width: 100},
        {title:'所属店铺', name:'shopName', width: 100},
        {title:'起拍价', name:'auctionPrice', width: 30},
        {title:'保证金', name:'cautionMoney', width: 30},
        {title:'加价幅度', name:'fareInc', width: 30},
        {title:'参与人数', name:'auctionNum', width: 30},
        {title:'拍卖时间', name:'startTime', width: 100,  align:'center',renderer: function(val,item,rowIndex){
                return item['startTime']+"<br/>至<br/>"+item['endTime'];
            }},
        {title:'状态', name:'saleNum', width: 30,renderer: function(val,item,rowIndex){
                if(item['auctionStatus']==1){
                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> 通过</span>";
                }else if(item['auctionStatus']==0){
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 待审核</span>";
                }else{
                    return "<span class='statu-no'><i class='fa fa-ban'></i> 不通过</span>";
                }
            }},
        {title:'操作', name:'' ,width:100, align:'center', renderer: function(val,item,rowIndex){
                var h = "";
                h += "<a class='btn btn-blue' target='_blank' href='"+WST.AU("auction://goods/detail","id="+item['auctionId']+"&key="+item['verfiycode'])+"'><i class='fa fa-search'></i> 查看</a> ";
                if(WST.GRANT.AUCTION_PMHD_04){
                    h += "<a class='btn btn-blue' href='javascript:allow(" + item['auctionId'] + ")'><i class='fa fa-check'></i> 通过</a> ";
                    h += "<a class='btn btn-red' href='javascript:illegal(" + item['auctionId'] + ",1)'><i class='fa fa-ban'></i> 不通过</a> ";
                }
                if(WST.GRANT.AUCTION_PMHD_03)h += "<a class='btn btn-red' href='javascript:del(" + item['auctionId'] + ",1)'><i class='fa fa-trash'></i>删除</a></div> ";
                return h;
            }}
    ];

    mmg2 = $('.mmg2').mmGrid({height: h-125,indexCol: true, indexColWidth:50, cols: cols,method:'POST',
        url: WST.AU('auction://goods/pageAuditQueryByAdmin'), fullWidthRows: true, autoLoad: false,
        plugins: [
            $('#pg2').mmPaginator({})
        ]
    });
    loadGrid2(p);
}
function loadGrid2(p){
    p=(p<=1)?1:p;
    var params = {};
    params.page=p;
	params.shopName = $('#shopName2').val();
	params.goodsName = $('#goodsName2').val();
	params.areaIdPath = WST.ITGetAllAreaVals('areaId2','j-areas').join('_');
	params.goodsCatIdPath = WST.ITGetAllGoodsCatVals('cat2_0','pgoodsCats').join('_');
	mmg2.load(params);
}

function del(id,type){
	var box = WST.confirm({content:"您确定要删除该拍卖商品吗?<br/>若有参与者则退回参与者保证金!",yes:function(){
	           var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
	           $.post(WST.AU('auction://goods/delByAdmin'),{id:id},function(data,textStatus){
	           			layer.close(loading);
	           			var json = WST.toAdminJson(data);
	           			if(json.status=='1'){
	           			    WST.msg(json.msg,{icon:1});
	           			    layer.close(box);
	           			    if(type==0){
	           		            loadGrid1(WST_CURR_PAGE);
	           			    }else{
	           			    	loadGrid2(WST_CURR_PAGE);
	           			    }
	           			}else{
	           			    WST.msg(json.msg,{icon:2});
	           			}
	           		});
	            }});
}
function illegal(id,type){
	var w = WST.open({type: 1,title:((type==1)?"下架原因":"不通过原因"),shade: [0.6, '#000'],border: [0],
	    content: '<textarea id="illegalRemarks" rows="7" style="width:100%" maxLength="200"></textarea>',
	    area: ['500px', '260px'],btn: ['确定', '关闭窗口'],
        yes: function(index, layero){
        	var illegalRemarks = $.trim($('#illegalRemarks').val());
        	if(illegalRemarks==''){
        		WST.msg('请输入原因 !', {icon: 5});
        		return;
        	}
        	var ll = WST.msg('数据处理中，请稍候...',{time:6000000});
		    $.post(WST.AU('auction://goods/illegal'),{id:id,illegalRemarks:illegalRemarks},function(data){
		    	layer.close(w);
		    	layer.close(ll);
		    	var json = WST.toAdminJson(data);
				if(json.status>0){
					WST.msg(json.msg, {icon: 1});
					if(type==0){
                        loadGrid1(WST_CURR_PAGE);
					}else{
						loadGrid2(WST_CURR_PAGE);
					}
				}else{
					WST.msg(json.msg, {icon: 2});
				}
		   });
        }
	});
}



function allow(id,type){
	var box = WST.confirm({content:"您确定审核通过该拍卖商品吗?",yes:function(){
        var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
        $.post(WST.AU('auction://goods/allow'),{id:id},function(data,textStatus){
        			layer.close(loading);
        			var json = WST.toAdminJson(data);
        			if(json.status=='1'){
        			    WST.msg(json.msg,{icon:1});
        			    layer.close(box);
        		        loadGrid1(WST_CURR_PAGE);
        		        loadGrid2(WST_CURR_PAGE);
        		    }else{
        			    WST.msg(json.msg,{icon:2});
        			}
        		});
         }});
}

function logs(id){
	parent.showBox({type:2,title:'竞拍记录',area: ['800px', '450px'],content:WST.AU('auction://goods/auctionLogByAdmin','id='+id+"&rd="+Math.random())});
}
function toolTip(){
    WST.toolTip();
}
