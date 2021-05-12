var mmg,isInitUpload = false;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'投票项主图', name:'itemImage', width: 100, renderer: function(val,item,rowIndex){
                return "<span class='weixin'><img id='img' onmouseout='toolTip()' onmouseover='toolTip()' style='height:40px;width:40px;' src='"+WST.conf.ROOT+"/"+item['itemImage']
            	+"'><span class='imged' style='left:45px;' ><img  style='height:150px;width:150px;' src='"+WST.conf.ROOT+"/"+item['itemImage']+"'></span></span>";
            }},
            {title:'投票项名称', name:'itemName', width: 130, sortable:true},
            {title:'所属项目', name:'catName', width: 100, sortable:true},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,rowdata,rowIndex){
                var h = "";
	            if(WST.GRANT.VOTE_TPXG)h += "<a class='btn btn-red' href='javascript:getForEdit(" + rowdata['itemId'] + ",1)'><i class='fa fa-pencil'></i>修改</a> ";
	            if(WST.GRANT.VOTE_TPSC)h += "<a class='btn btn-red' href='javascript:del(" + rowdata['itemId'] + ",0)'><i class='fa fa-trash'></i>删除</a>"; 
	            return h;
	        }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-120,indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: WST.AU('vote://items/pageQueryItemsByAdmin'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadGrid(){
	var query = {};
	query['catName'] = $('#catQueryName').val();
	query['itemName'] = $('#itemQueryName').val();
	mmg.load(query);
}

function del(id,type){
	var box = WST.confirm({content:"将删除该报名项目字段及对应记录，您确定删除吗?",yes:function(){
	   var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
	   $.post(WST.AU('vote://items/delItemByAdmin'),{id:id},function(data,textStatus){
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

function toEdit(id){
	if(!isInitUpload){
		initUpload();
	}
	var title =(id==0)?"新增":"编辑";
	var box = WST.open({title:title,type:1,content:$('#voteDlg'),area: ['550px', '450px'],btn: ['确定','取消'],yes:function(){
			$('#voteForm').submit();
	},cancel:function(){
		//重置表单
		$('#preview').html('');
		$('#itemImage').val('');
		$('#voteForm')[0].reset();
	},end:function(){
		//重置表单
		$('#preview').html('');
		$('#itemImage').val('');
		$('#voteForm')[0].reset();
    $('#voteDlg').hide();

	}});

	$('#voteForm').validator({
        fields: {
            itemName: {
            	rule:"required;",
            	msg:{required:"请输入字段名称"},
            	tip:"请输入字段名称",
            	ok:"",
            },
        },
       valid: function(form){
			var params = WST.getParams('.ipt');
		    params.catId = $('#catIndex').val();
			params.catName = $('#catIndex option:selected').text();
			params.itemId = id;
			var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
			$.post(WST.AU('vote://items/'+((id==0)?"addItem":"editItem")),params,function(data,textStatus){
				  layer.close(loading);
				  var json = WST.toAdminJson(data);
				  if(json.status=='1'){
						WST.msg("操作成功",{icon:1});
						$('#preview').html('');
						$('#itemImage').val('');
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
     $.post(WST.AU('vote://items/getItemById'),{id:id},function(data,textStatus){
           layer.close(loading);
           var json = WST.toAdminJson(data);
           if(json.itemId){
           		WST.setValues(json);
				$('#catIndex').val(json.catId);
				$('#preview').html('<img src="'+WST.conf.ROOT+"/"+json.itemImage+'" height="100" />');
				$('#itemImage').val(json.itemImage);
           		toEdit(json.itemId);
           }else{
           		WST.msg(json.msg,{icon:2});
           }
    });
}

function initUpload(){
	isInitUpload = true;
	//文件上传
	WST.upload({
  	  pick:'#itemFilePicker',
  	  formData: {dir:'image',mWidth:500,mHeight:250},
  	  accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
  	  callback:function(f){
  		  var json = WST.toAdminJson(f);
  		  if(json.status==1){
        	$('#preview').html('<img src="'+WST.conf.ROOT+"/"+json.savePath+json.thumb+'" height="100" />');
        	$('#itemImage').val(json.savePath+json.thumb);
  		  }
	  },
	  progress:function(rate){
	      $('#msg_itemImg').show().html('已上传'+rate+"%");
	  }
	});
}

