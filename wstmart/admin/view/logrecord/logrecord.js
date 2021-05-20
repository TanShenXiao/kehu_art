var mmg;
$(function(){
    var laydate = layui.laydate;
    laydate.render({
        elem: '#startDate'
    });
    laydate.render({
        elem: '#endDate'
    });
    var h = WST.pageHeight();
    /*
    id: 11
label: "测试12"
operateDesc: "添加商品"
operateTime: "2021-05-16 18:59:29"
recordId: 149
staffId: 4
staffName: "rrr"
type: 1
     */
    var cols = [
            {title:'用户名', name:'staffName', width: 50},
            {title:'操作时间', name:'operateTime' ,width:100},
            {title:'操作记录', name:'operateDesc' ,width:100},
            {title:'操作目标', name:'label' ,width:100}
            ];
 
    mmg = $('.mmg').mmGrid({height: h-162,indexCol: true,indexColWidth:50,cols: cols,method:'POST',
        url: WST.U('admin/logrecord/pageQuery'), fullWidthRows: true, autoLoad: true,
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });  
     $('#headTip').WSTTips({width:90,height:35,callback:function(v){
         var diff = v?162:135;
         mmg.resize({height:h-diff})
    }});   
})
function loadGrid(){
	mmg.load({page:1,loginSrc:$('#loginSrc').val(),startDate:$('#startDate').val(),endDate:$('#endDate').val(),staffName:$('#loginName').val(),label:$("#label").val(),loginIp:$('#loginIp').val()});
}