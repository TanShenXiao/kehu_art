var mmg,isInitUpload = false;
function init(){
    var laydate = layui.laydate;
    laydate.render({
        elem: '#startDate'
    });
    laydate.render({
        elem: '#endDate'
    });
	initGrid();
	initKindEditor();
}
function initGrid(){
    var h = WST.pageHeight();
    var cols = [
            {title:'项目名称', name:'catName', width: 130, sortable:true},
            {title:'开始时间', name:'startDate', width: 100, sortable:true, align:'center',renderer: function(val,item,rowIndex){return item['startDate'];}},
            {title:'结束时间', name:'startDate', width: 100, sortable:true, align:'center',renderer: function(val,item,rowIndex){return item['endDate'];}},
            {title:'人数限制', name:'signupLimit', width: 100, sortable:true, align:'center',renderer: function(val,item,rowIndex){return item['signupLimit'];}},
            {title:'状态', name:'status', width: 30, sortable:true, renderer: function(val,rowdata,rowIndex){
            	if(rowdata['status']==1){
                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> 进行中</span>";
	        	}else if(rowdata['status']==0){
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 未开始</span>";
	        	}else{
                    return "<span class='statu-no'><i class='fa fa-ban'></i> 已结束</span>";
	        	}
            }},
            {title:'是否收费', name:'needPay', width: 30, sortable:true, renderer: function(val,rowdata,rowIndex){
				if(rowdata['needPay']==0){
                    return "<span class='statu-wait'>否</span>";
	        	}else{
                    return "<span class='statu-no'>是</span>";
	        	}
			}},
            {title:'收费金额', name:'signupFee', sortable:true, width: 30},
            {title:'操作', name:'' ,width:120, align:'center', renderer: function(val,rowdata,rowIndex){
                var h = "";
	            if(WST.GRANT.SIGNUP_BMXG)h += "<a class='btn btn-red' href='javascript:toEdit(" + rowdata['catId'] + ",1)'><i class='fa fa-pencil'></i>修改</a> ";
	            if(WST.GRANT.SIGNUP_BMSC)h += "<a class='btn btn-red' href='javascript:del(" + rowdata['catId'] + ",0)'><i class='fa fa-trash'></i>删除</a>"; 
	            return h;
	        }}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-120,indexCol: true,indexColWidth:50,  cols: cols,method:'POST',
        url: WST.AU('signup://cats/pageQueryCatsByAdmin'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    }); 
}
function initKindEditor(){
	KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="catDesc"]', {
		  height:'350px',
		  width:'800px',
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
function loadGrid(){
	var query = WST.getParams('.query');
	mmg.load(query);
}

function del(id,type){
	var box = WST.confirm({content:"将删除该报名项目及对应报名记录，您确定删除吗?",yes:function(){
	   var loading = WST.msg('正在提交请求，请稍后...', {icon: 16,time:60000});
	   $.post(WST.AU('signup://cats/delCatByAdmin'),{id:id},function(data,textStatus){
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
	location.href=WST.AU('signup://cats/toEdit','id='+id);
	return;
	var title =(id==0)?"新增":"编辑";
	var box = WST.open({title:title,type:1,content:$('#signupDlg'),area: ['450px', '400px'],btn: ['确定','取消'],yes:function(){
			$('#signupForm').submit();
	},cancel:function(){
		//重置表单
		$('#signupForm')[0].reset();
	},end:function(){
		//重置表单
		$('#signupForm')[0].reset();
    $('#signupDlg').hide();

	}});
	
	var laydate = layui.laydate;
    form = layui.form; 
    laydate.render({
        elem: '#startDate'
    });
    laydate.render({
        elem: '#endDate'
    });

	$('#signupForm').validator({
        fields: {
            catsName: {
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
            
        },
       valid: function(form){
			var params = WST.getParams('.ipt');
		    params.catId = id;
			var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
			$.post(WST.AU('signup://cats/'+((id==0)?"addCat":"editCat")),params,function(data,textStatus){
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
     $.post(WST.AU('signup://cats/getCatById'),{id:id},function(data,textStatus){
           layer.close(loading);
           var json = WST.toAdminJson(data);
           if(json.catId){
           		WST.setValues(json);
           		toEdit(json.catId);
           }else{
           		WST.msg(json.msg,{icon:2});
           }
    });
}

