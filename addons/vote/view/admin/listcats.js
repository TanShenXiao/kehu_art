var mmg,isInitUpload = false;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'项目主图', name:'catImage', width: 100, renderer: function(val,item,rowIndex){
                return "<span class='weixin'><img id='img' onmouseout='toolTip()' onmouseover='toolTip()' style='height:40px;width:40px;' src='"+WST.conf.ROOT+"/"+item['catImage']
            	+"'><span class='imged' style='left:45px;' ><img  style='height:150px;width:150px;' src='"+WST.conf.ROOT+"/"+item['catImage']+"'></span></span>";
            }},
            {title:'项目名称', name:'catName', width: 130, sortable:true},
            {title:'开始时间', name:'startDate', width: 100, sortable:true, align:'center',renderer: function(val,item,rowIndex){return item['startDate'];}},
            {title:'结束时间', name:'endDate', width: 100, sortable:true, align:'center',renderer: function(val,item,rowIndex){return item['endDate'];}},
			{title:'每人每日最大投票数', name:'totalVotes', sortable:true, width: 100, align:'center'},
            {title:'单项目每人每日最大投票数', name:'itemVotes', sortable:true, width: 100, align:'center'},
            {title:'状态', name:'status', width: 30, sortable:true, renderer: function(val,rowdata,rowIndex){
            	if(rowdata['status']==1){
                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> 进行中</span>";
	        	}else if(rowdata['status']==0){
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 未开始</span>";
	        	}else{
                    return "<span class='statu-no'><i class='fa fa-ban'></i> 已结束</span>";
	        	}
            }},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,rowdata,rowIndex){
                var h = "";
	            if(WST.GRANT.VOTE_TPXG)h += "<a class='btn btn-red' href='javascript:getForEdit(" + rowdata['catId'] + ",1)'><i class='fa fa-pencil'></i>修改</a> ";
	            if(WST.GRANT.VOTE_TPSC)h += "<a class='btn btn-red' href='javascript:del(" + rowdata['catId'] + ",0)'><i class='fa fa-trash'></i>删除</a>"; 
	            return h;
	        }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-120,indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: WST.AU('vote://cats/pageQueryCatsByAdmin'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadGrid(){
	var query = WST.getParams('.query');
	mmg.load(query);
}

function del(id,type){
	var box = WST.confirm({content:"将删除该报名项目及对应报名记录，您确定删除吗?",yes:function(){
	   var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
	   $.post(WST.AU('vote://cats/delCatByAdmin'),{id:id},function(data,textStatus){
			layer.close(loading);
			var json = WST.toAdminJson(data);
			if(json.status=='1'){
				WST.msg(json.msg,{icon:1});
				layer.close(box);
				loadGrid();
			}else{
				WST.msg(json.msg,{icon:2});
				loadGrid();
			}
		});
	}});
}

function toolTip(){
    WST.toolTip();
}

function toEdit(id){
	location.href=WST.AU('vote://cats/toEdit','id='+id);
	return;
	if(!isInitUpload){
		initUpload();
	}
	var title =(id==0)?"新增":"编辑";
	var box = WST.open({title:title,type:1,content:$('#voteDlg'),area: ['550px', '450px'],btn: ['确定','取消'],yes:function(){
			$('#voteForm').submit();
		},cancel:function(){
			//重置表单
			$('#preview').html('');
			$('#catImage').val('');
			$('#voteForm')[0].reset();
		},end:function(){
			//重置表单
			$('#preview').html('');
			$('#catImage').val('');
			$('#voteForm')[0].reset();
			$('#voteDlg').hide();
		}
	});
	
	var laydate = layui.laydate;
    form = layui.form; 
    laydate.render({
        elem: '#startDate'
    });
    laydate.render({
        elem: '#endDate'
    });

	$('#voteForm').validator({
        fields: {
            catName: {
            	rule:"required;",
            	msg:{required:"请输入项目名称"},
            	tip:"请输入项目名称",
            	ok:"",
            },
            startDate: {
            	rule:"required;",
            	msg:{required:"请输入开始时间"},
            	tip:"请输入开始时间",
            	ok:"",
            },
            endDate:  {
            	rule:"required;",
            	msg:{required:"请输入结束时间"},
            	tip:"请输入结束时间",
            	ok:"",
            },
			totalVotes: {
				rule:"required;digits;min:1",
				msg:{required:"请输入每日最大投票数",digits:"请输入整数",min:"投票数不能小于1"},
				tip:"请输入每日最大投票数",
				ok:"",
			},
            itemVotes:{
				rule:"required;digits;min:1",
				msg:{required:"请输入单项目每日最大投票数",digits:"请输入整数",min:"投票数不能小于1"},
				tip:"请输入单项目每日最大投票数",
				ok:"",
			},
        },
       valid: function(form){
			var params = WST.getParams('.ipt');
		    params.catId = id;
			var tv = parseInt($('#totalVotes').val());
			var iv = parseInt($('#itemVotes').val());
			if(tv<iv){
				var box1 = WST.confirm({content:"单项目每日投票数不能大于每日最大投票总数",yes:function(){
					layer.close(box1);
				}});
				return;
			}
			var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
			$.post(WST.AU('vote://cats/'+((id==0)?"addCat":"editCat")),params,function(data,textStatus){
				  layer.close(loading);
				  var json = WST.toAdminJson(data);
				  if(json.status=='1'){
						WST.msg("操作成功",{icon:1});
						$('#preview').html('');
						$('#catImage').val('');
						$('#voteForm')[0].reset();
						layer.close(box);
						loadGrid();
				  }else{
						WST.msg(json.msg,{icon:2});
				  }
			});
    	}
  });
}

function getForEdit(id){
	 var loading = WST.msg('正在获取数据，请稍侯...', {icon: 16,time:60000});
     $.post(WST.AU('vote://cats/getCatById'),{id:id},function(data,textStatus){
           layer.close(loading);
           var json = WST.toAdminJson(data);
           if(json.catId){
           		WST.setValues(json);
				$('#preview').html('<img src="'+WST.conf.ROOT+"/"+json.catImage+'" height="100" />');
				$('#catImage').val(json.catImage);
           		toEdit(json.catId);
           }else{
           		WST.msg(json.msg,{icon:2});
           }
    });
}

function initUpload(){
	isInitUpload = true;
	//文件上传
	WST.upload({
  	  pick:'#catFilePicker',
  	  formData: {dir:'image',mWidth:500,mHeight:250},
  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
  	  callback:function(f){
  		  var json = WST.toAdminJson(f);
  		  if(json.status==1){
        	$('#preview').html('<img src="'+WST.conf.ROOT+"/"+json.savePath+json.thumb+'" height="100" />');
        	$('#catImage').val(json.savePath+json.thumb);
  		  }
	  },
	  progress:function(rate){
	      $('#msg_catImg').show().html('已上传'+rate+"%");
	  }
	});
}
