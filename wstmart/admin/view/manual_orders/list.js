var mmg;
$(function(){
    var laydate = layui.laydate;
    laydate.render({
        elem: '#invoice_write_invoice',
        type:'datetime',
    });
    laydate.render({
        elem: '#invoice_reviewer_time',
        type:'datetime',
    });
})
function initGrid(page){
	var p = WST.arrayParams('.j-ipt');
	var h = WST.pageHeight();
    var cols = [
            {title:'商品名', name:'goods_name', width: 120,sortable:true},
            {title:'商品分类', name:'goods_type', width: 120,sortable:true},
            {title:'订单编号', name:'order_no', width: 90,sortable:true, renderer:function(val,item,rowIndex){return '￥'+val;}},
            {title:'销售价格', name:'price' , width: 90,sortable:true},
            {title:'税费', name:'price' , width: 90,sortable:true},
            {title:'是否开发表', name:'is_invoice' , width: 90,sortable:true,renderer:function(val,item,rowIndex){
                    var h = "";
                    if(val == 1){
                       h = '是';
                    }else{
                        h = '否';
                    }
                    return h;
            }},
            {title:'创建时间', name:'created_time' , width: 120,sortable:true,sort:true},
           /* {title:'操作' , width: 60,name:'status', renderer:function(val,item,rowIndex){
                    var h = "";
                    h += "<a class='btn btn-blue' href='javascript:toEdit("+ item['id']+")'><i class='fa fa-pencil'></i>修改</a> ";
                    return h;
            }}*/
            ];
    mmg = $('.mmg').mmGrid({height: (h-90),indexCol: true,indexColWidth:50, cols: cols,method:'POST',nowrap:true,
        url: WST.U('admin/manualOrders/pageQuery',p.join('&')), fullWidthRows: true, autoLoad: false,remoteSort: true,sortName:'createTime',sortStatus:'desc',
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
        location.href=WST.U('admin/invoice/index','p='+p);
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

//------------------属性类型---------------//
function toEdit(order_id){
    $("select[id^='bcat_0_']").remove();
    $('#orderForm').get(0).reset();
    if(!order_id){
        invoice_show(0)
    }
    $.post(WST.U('admin/attributes/get'),{order_id:order_id},function(data,textStatus){
        var json = WST.toAdminJson(data);
        WST.setValues(json);
        changeArrType(json.attrType);
        layui.form.render();
        if(json.goodsCatId>0){
            var goodsCatPath = json.goodsCatPath.split("_");
            $('#bcat_0').val(goodsCatPath[0]);
            var opts = {id:'bcat_0',val:goodsCatPath[0],childIds:goodsCatPath,className:'goodsCats'}
            WST.ITSetGoodsCats(opts);
        }
        var title =(order_id==0)?"新增":"编辑";
        var box = WST.open({title:title,type:1,content:$('#attrBox'),area: ['750px', '480px'],btn:['确定','取消'],
            end:function(){$('#attrBox').hide();},yes:function(){
                $('#orderForm').submit();
            }});
        $('#orderForm').validator({
            fields: {
                'goods_name': {rule:"required",msg:{required:'请输入商品名称'}},
            },
            valid: function(form){
                var params = WST.getParams('.ipt');
                console.log(params)
                var loading = WST.msg('正在提交数据，请稍后...', {icon: 16,time:60000});
                params.goods_type = WST.ITGetGoodsCatVal('goodsCats');
                $.post(WST.U('admin/manualOrders/'+((params.order_id==0)?"add":"edit")),params,function(data,textStatus){
                    layer.close(loading);
                    var json = WST.toAdminJson(data);
                    if(json.status=='1'){
                        WST.msg("操作成功",{icon:1});
                        layer.close(box);
                        $('#attrBox').hide();
                        loadGrid(WST_CURR_PAGE);
                        layer.close(box);
                    }else{
                        WST.msg(json.msg,{icon:2});
                    }
                });
            }
        });

    });
}

function changeArrType(v){
    if(v>0){
        $('#attrValTr').show();
    }else{
        $('#attrValTr').hide();
    }
}
//发票的显示与关闭
function invoice_show(invoice_show){
    if(invoice_show == 100){
        invoice_show = $("#is_invoice").val()
        console.log(invoice_show)
    }
    if(invoice_show == 1){
        $('.invoice').show();
    }else{
        $('.invoice').hide();
    }

}
