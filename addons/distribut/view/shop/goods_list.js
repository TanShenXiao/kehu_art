var mmg;
function initGrid(p){
   var h = WST.pageHeight();
   var cols = [
        {title:'商品图片', name:'goodsName', width: 60, renderer: function(val,item,rowIndex){
            return "<span class='weixin'><img class='img' style='height:50px;width:50px;' src='"+WST.conf.RESOURCE_PATH+"/"+item['goodsImg']+"'><img class='imged' style='height:200px;width:200px;' src='"+WST.conf.RESOURCE_PATH+"/"+item['goodsImg']+"'></span>";
        }},
        {title:'商品名称', name:'goodsName', width: 300},
        {title:'商品编号', name:'goodsSn', width: 100},
        {title:'价格(￥)', name:'shopPrice', width: 50},
        {title:'推荐', name:'isRecom', width: 30,renderer:function(val,item,rowIndex){
                if(item['isRecom']==1){
                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> 是</span>";
                }else{
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 否</span>";
                }
            }},
        {title:'精品', name:'isBest', width: 30,renderer:function(val,item,rowIndex){
                if(item['isBest']==1){
                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> 是</span>";
                }else{
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 否</span>";
                }
            }},
        {title:'新品', name:'isNew', width: 30,renderer:function(val,item,rowIndex){
                if(item['isNew']==1){
                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> 是</span>";
                }else{
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 否</span>";
                }
            }},
        {title:'热销', name:'isHot', width: 30,renderer:function(val,item,rowIndex){
                if(item['isHot']==1){
                    return "<span class='statu-yes'><i class='fa fa-check-circle'></i> 是</span>";
                }else{
                    return "<span class='statu-wait'><i class='fa fa-clock-o'></i> 否</span>";
                }
            }},
        {title:'销量', name:'saleNum', width: 50},
        {title:'库存', name:'goodsStock', width: 50},
        {title:'佣金', name:'' ,width:200,renderer:function(val,item,rowIndex){
            return (item['distributType']==1)?'￥'+item['commission']:"订单<span style='color:red;'>"+item['distributOrderRate']+"%</span>";
        }}
    ];

    mmg = $('.mmg').mmGrid({height: h-100,indexCol: true, cols: cols,method:'POST',
        url: WST.AU('distribut://distribut/queryDistributGoods'), fullWidthRows: true, autoLoad: false,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
    loadGrid(p);
}

function loadGrid(p){
    var params = {};
    params = WST.getParams('.s-query');
    params.key = $.trim($('#key').val());
    p=(p<=1)?1:p;
    params.page=p;
    mmg.load(params);
}

function getCat(val){
      if(val==''){
        $('#cat2').html("<option value='' >-请选择-</option>");
        return;
      }
      $.post(WST.U('shop/shopcats/listQuery'),{parentId:val},function(data,textStatus){
           var json = WST.toJson(data);
           var html = [],cat;
           html.push("<option value='' >-请选择-</option>");
           if(json.status==1 && json.list){
             json = json.list;
           for(var i=0;i<json.length;i++){
               cat = json[i];
               html.push("<option value='"+cat.catId+"'>"+cat.catName+"</option>");
            }
           }
           $('#cat2').html(html.join(''));
      });
}