var mmg;
function initGrid(p){
   var h = WST.pageHeight();
   var cols = [
        {title:'订单编号', name:'orderNo', width: 100},
        {title:'获佣用户', name:'goodsSn', width: 100,renderer:function(val,item,rowIndex){
            return item['userName']?item['userName']:item['loginName'];
        }},
        {title:'佣金描述', name:'remark', width: 400},
        {title:'商品金额', name:'money', width: 100},
        {title:'佣金', name:'distributMoney', width: 100},
        {title:'记录时间', name:'createTime', width: 100},
        {title:'状态', name:'moneyStatus', width: 100,renderer:function(val,item,rowIndex){
            return item['moneyStatus']?"<span class='statu-yes'>已结算</span>":"未结算";
        }}
    ];

    mmg = $('.mmg').mmGrid({height: h-100,indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.AU('distribut://distribut/queryDistributMoneys'), fullWidthRows: true, autoLoad: false,
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
      $.post(WST.U('home/shopcats/listQuery'),{parentId:val},function(data,textStatus){
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