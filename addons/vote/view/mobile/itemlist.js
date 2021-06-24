//排序条件
function orderCondition(obj,condition){
    var classContent = $(obj).attr('class');
    var status = $(obj).attr('status');
    var theSiblings = $(obj).siblings('.sorts');
    theSiblings.removeClass('active').attr('status','down');
    $(obj).addClass('active');
    if(classContent.indexOf('active')==-1){
        $(obj).children('i').addClass('down2').removeClass('down');
        theSiblings.children('i').addClass('down').removeClass('down2');
    }
    if(status.indexOf('down')>-1){
        if(classContent.indexOf('active')==-1){
        	$(obj).children('i').addClass('down2').removeClass('up2');
            $('#desc').val('0');
        }else{
        	$(obj).children('i').addClass('up2').removeClass('down2');
            $(obj).attr('status','up');
            $('#desc').val('1');
        }
    }else{
    	$(obj).children('i').addClass('down2').removeClass('up2');
        $(obj).attr('status','down');
        $('#desc').val('0');
    }
    $('#condition').val(condition);//排序条件
    $('#currPage').val('0');//当前页归零
    $('#items-list').html('');
    goodsList();
}
//切换
function switchList(obj){
    if($('#items-list').hasClass('wst-go-switch')){
    	$(obj).removeClass('wst-se-icon2');
    	$('#items-list').removeClass('wst-go-switch');
    }else{
    	$(obj).addClass('wst-se-icon2');
    	$('#items-list').addClass('wst-go-switch');
    }
	$('.j-imgAdapt').removeAttr('style');
	$('.j-imgAdapt a').removeAttr('style');
	$('.j-imgAdapt a img').removeAttr('style');
    $('#currPage').val('0');
    $('#items-list').html('');
	goodsList();
}
//获取投票项列表
function itemsList(){
	$('#Load').show();
    loading = true;
    var param = {};
    param.catId = $('#catId').val();
    param.brandId = $('#brandId').val();
    param.condition = $('#condition').val();
    param.desc = $('#desc').val();
    param.keyword = $('#keyword').val();
	param.pagesize = 10;
	param.page = Number( $('#currPage').val() ) + 1;
    $.post(WST.AU('vote://items/pageQueryItems'), param,function(data){
        var json = WST.toJson(data);
        $('#currPage').val(json.current_page);
        $('#totalPage').val(json.last_page);
        var gettpl = document.getElementById('list').innerHTML;
        laytpl(gettpl).render(json.data, function(html){
            $('#items-list').append(html);
        });
        WST.imgAdapt('j-imgAdapt');
        loading = false;
        $('#Load').hide();
        echo.init();//图片懒加载
    });
}
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
	WST.initFooter('home');
	$('.wst-se-search').on('submit', '.input-form', function(event){
	    event.preventDefault();
	})
    itemsList();
    WST.imgAdapt('j-imgRec');
    $('.wst-gol-adsb').css('height',$('.j-imgRec').width()+20);
    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - screen.height)) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
                itemsList();
            }
        }
    });
});

function voteSubmit(itemId){
    location.href = WST.AU("vote://vote/msubmit","catId="+catId);
}
function vote(itemId,itemName,catId,catName,userName){
	var params = {itemId:itemId,itemName:itemName,catId:catId,catName:catName,userName:userName};
	var loading = WST.msg('正在提交数据，请稍候...', {icon: 16,time:60000});
	$.post(WST.AU('vote://lists/addlist'),params,function(data,textStatus){
		  var json = WST.toJson(data);
		  if(json.status=='1'){
				WST.msg("投票成功",{icon:1});
		  }else if(json.status=='-2'){
				WST.msg(json.msg,{icon:2});
				WST.inLogin();
		  }else{
				WST.msg(json.msg,{icon:2});
		  }
	});
}