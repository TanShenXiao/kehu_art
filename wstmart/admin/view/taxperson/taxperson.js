var mmg;
$(function(){
    var laydate = layui.laydate;
    laydate.render({
        elem: '#startDate'
    });
    laydate.render({
        elem: '#endDate'
    });
})
function initGrid(page){
	var p = WST.arrayParams('.j-ipt');
	var h = WST.pageHeight();
    var cols = [
            {title:'经办人姓名', name:'jbrxm', width: 120,sortable:true},
            {title:'经办人证件号码', name:'jbrzjhm', width: 60,sortable:true},
            {title:'经办人手机号码', name:'jbrmobile' , width: 90,sortable:true},
            {title:'是否认证', name:'isauthstr', width: 120,sortable:true},
          /*  {title:'操作' , width: 60,name:'status', renderer:function(val,item,rowIndex){
            	var h = "";
            	if(item['freight_no']){
                    h += "<a class='btn btn-blue' href='javascript:toView(" + item['orderId'] + ")'><i class='fa fa-search'></i>查看物流信息</a> ";
                }
	            return h;
            }}*/
            ];
 
    mmg = $('.mmg').mmGrid({height: (h-90),indexCol: true,indexColWidth:50, cols: cols,method:'POST',nowrap:true,
        url: WST.U('admin/taxperson/pageQuery',p.join('&')), fullWidthRows: true, autoLoad: false,remoteSort: true,sortName:'createTime',sortStatus:'desc',
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
    loadGrid(page);
}

function toView(id){
	location.href=WST.U('admin/orders/view','id='+id+'&src=orders&p='+WST_CURR_PAGE);
}
function toBack(p,src){
    if(src=='orders'){
        location.href=WST.U('admin/taxperson/index','p='+p);
    }else{
        location.href=WST.U('admin/orderrefunds/refund','p='+p);
    }
}
function loadGrid(page){
	var p = WST.getParams('.j-ipt');
    page=(page<=1)?1:page;
    p.page = page;
	mmg.load(p);
}

function showDetail(id){
    parent.showBox({title:'订单详情',type:2,content:WST.U('admin/orders/view',{id:id,from:1}),area: ['1020px', '500px'],btn:['关闭']});
}
function loadMore(){
    var h = WST.pageHeight();
    if($('#moreItem').hasClass('hide')){
        $('#moreItem').removeClass('hide');
        mmg.resize({height:h-119});
    }else{
        $('#moreItem').addClass('hide');
        mmg.resize({height:h-89});
    }
}

function initChange(){
   var form = layui.form;
   form.on('radio(orderStatus)', function(data){
      if(data.value==0){
          $('.result_-1').hide();
      }else{
          $('.result_0').hide();
      }
      $('.result_'+data.value).show();
   });
}
