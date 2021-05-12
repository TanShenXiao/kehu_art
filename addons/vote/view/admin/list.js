var mmg,isInitUpload = false;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
			{title:'投票项主图', name:'itemImage', width: 100, renderer: function(val,item,rowIndex){
                return "<span class='weixin'><img id='img' onmouseout='toolTip()' onmouseover='toolTip()' style='height:40px;width:40px;' src='"+WST.conf.ROOT+"/"+item['itemImage']
            	+"'><span class='imged' style='left:45px;' ><img  style='height:150px;width:150px;' src='"+WST.conf.ROOT+"/"+item['itemImage']+"'></span></span>";
            }},
            {title:'所属项目', name:'catName', width: 130, sortable:true},
            {title:'投票项', name:'itemName', width: 50, sortable:true},
            {title:'投票数量', name:'voteNum', width: 50, sortable:true},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,rowdata,rowIndex){
                var h = "";
	            if(WST.GRANT.VOTE_TPCK)h += "<a class='btn btn-red' href='javascript:listByItem(" + rowdata['itemId'] + ",1)'><i class='fa fa-search'></i>查看</a> ";
	            return h;
	        }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-120,indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: WST.AU('vote://lists/pageQueryByAdmin'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadGrid(){
	var params = {};
	params.catName = $('#catQueryName').val();
	params.itemName = $('#itemQueryName').val();
	mmg.load(params);
}

function listByItem(itemId){
	location.href=WST.AU('vote://lists/listByItem','itemId='+itemId);
}

function del(id,type){
	var box = WST.confirm({content:"您确定要删除该团购商品吗?",yes:function(){
	           var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
	           $.post(WST.AU('vote://goods/delByAdmin'),{id:id},function(data,textStatus){
	           			layer.close(loading);
	           			var json = WST.toAdminJson(data);
	           			if(json.status=='1'){
	           			    WST.msg(json.msg,{icon:1});
	           			    layer.close(box);
	           			    if(type==0){
	           		            loadGrid1();
	           			    }else{
	           			    	loadGrid2();
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
		    $.post(WST.AU('vote://goods/illegal'),{id:id,illegalRemarks:illegalRemarks},function(data){
		    	layer.close(w);
		    	layer.close(ll);
		    	var json = WST.toAdminJson(data);
				if(json.status>0){
					WST.msg(json.msg, {icon: 1});
					if(type==1){
                        loadGrid1();
					}else{
                        loadGrid2();
					}
				}else{
					WST.msg(json.msg, {icon: 2});
				}
		   });
        }
	});
}

function allow(id,type){
	var box = WST.confirm({content:"您确定审核通过该团购商品吗?",yes:function(){
        var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
        $.post(WST.AU('vote://goods/allow'),{id:id},function(data,textStatus){
        			layer.close(loading);
        			var json = WST.toAdminJson(data);
        			if(json.status=='1'){
        			    WST.msg(json.msg,{icon:1});
        			    layer.close(box);
        		        loadGrid1();
        		        loadGrid2();
        		    }else{
        			    WST.msg(json.msg,{icon:2});
        			}
        		});
         }});
}
function toolTip(){
    WST.toolTip();
}
function toExport(){
	var params = {};
	params = WST.getParams('.query');
	var box = WST.confirm({content:"您确定要导出投票信息吗?",yes:function(){
		layer.close(box);
		location.href=WST.AU('vote://lists/toExport',params);
    }});
}

