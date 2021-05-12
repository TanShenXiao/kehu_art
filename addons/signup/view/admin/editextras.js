var mmg,isInitUpload = false;
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'扩展字段名称', name:'extraName', width: 130, sortable:true},
            {title:'项目名称', name:'catName', width: 100, sortable:true},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,rowdata,rowIndex){
                var h = "";
	            if(WST.GRANT.SIGNUP_BMXG)h += "<a class='btn btn-red' href='javascript:getForEdit(" + rowdata['extraId'] + ",1)'><i class='fa fa-pencil'></i>修改</a> ";
	            if(WST.GRANT.SIGNUP_BMSC)h += "<a class='btn btn-red' href='javascript:del(" + rowdata['extraId'] + ",0)'><i class='fa fa-trash'></i>删除</a>"; 
	            return h;
	        }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-120,indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: WST.AU('signup://extras/pageQueryExtrasByAdmin'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function loadGrid(){
	var query = {};
	query['catName'] = $('#catQueryName').val();
	mmg.load(query);
}

function del(id,type){
	var box = WST.confirm({content:"将删除该报名项目字段及对应记录，您确定删除吗?",yes:function(){
	   var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
	   $.post(WST.AU('signup://extras/delExtraByAdmin'),{id:id},function(data,textStatus){
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
	var title =(id==0)?"新增":"编辑";
	var box = WST.open({title:title,type:1,content:$('#signupDlg'),area: ['550px', '320px'],btn: ['确定','取消'],yes:function(){
			$('#signupForm').submit();
	},cancel:function(){
		//重置表单
		$('#signupForm')[0].reset();
	},end:function(){
		//重置表单
		$('#signupForm')[0].reset();
    $('#signupDlg').hide();

	}});

	$('#signupForm').validator({
        fields: {
            extraName: {
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
			params.extraId = id;
			var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
			$.post(WST.AU('signup://extras/'+((id==0)?"addExtra":"editExtra")),params,function(data,textStatus){
				  layer.close(loading);
				  var json = WST.toAdminJson(data);
				  if(json.status=='1'){
						WST.msg("操作成功",{icon:1});
						$('#signupForm')[0].reset();
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
     $.post(WST.AU('signup://extras/getExtraById'),{id:id},function(data,textStatus){
           layer.close(loading);
           var json = WST.toAdminJson(data);
           if(json.extraId){
           		WST.setValues(json);
				$('#catIndex').val(json.catId);
           		toEdit(id);
           }else{
           		WST.msg(json.msg,{icon:2});
           }
    });
}

