function loadStat(){
    var loading = WST.load({msg:'正在查询数据，请稍后...'});
    $.post(WST.AU('coupon://shops/stat'),WST.getParams('.j-ipt'),function(data,textStatus){
        layer.close(loading);
        var json = WST.toJson(data);
        var myChart = echarts.init(document.getElementById('main'));
        myChart.clear();
        $('#mainTable').addClass('hide');
        if(json.status=='1' && json.data){
            var option = {
                tooltip : {
                    trigger: 'axis'
                },
                toolbox: {
                    show : true,
                    y: 'top',
                    feature : {
                        mark : {show: true},
                        dataView : {show: false, readOnly: false},
                        magicType : {show: true, type: ['line', 'bar', 'tiled']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
                legend: {
                    data:['领取量','使用量']
                },
                xAxis : [
                    {
                        type : 'category',
                        splitLine : {show : false},
                        data : json.data.days
                    }
                ],
                yAxis : [
                    {
                        type : 'value',
                        position: 'right'
                    }
                ],
                series : [
                    {
                        name:'领取量',
                        type:'line',
                        data:json.data['coupon']
                    },
                    {
                        name:'使用量',
                        type:'line',
                        data:json.data['use']
                    }
                ]
            };  
            myChart.setOption(option);
        }else{
            WST.msg('没有查询到记录',{icon:5});
        }
    }); 
}

function toBack(p){
    location.href = WST.AU('coupon://shops/index','p='+p);
}


