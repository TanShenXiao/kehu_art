jQuery.noConflict();
var attrsed = [];
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
    $('#goods-list').html('');
    goodsList(4);
}
//切换
function switchList(obj){
    if($('#goods-list').hasClass('wst-go-switch')){
        $(obj).removeClass('wst-se-icon2');
        $('#goods-list').removeClass('wst-go-switch');
    }else{
        $(obj).addClass('wst-se-icon2');
        $('#goods-list').addClass('wst-go-switch');
    }
    $('.j-imgAdapt').removeAttr('style');
    $('.j-imgAdapt a').removeAttr('style');
    $('.j-imgAdapt a img').removeAttr('style');
    $('#currPage').val('0');
    $('#goods-list').html('');
    goodsList(1);
}

/*打开筛选层*/
function screenTier(){
    $('.screen').addClass('screen1').removeClass('screen').next().css('color','#ec7070');
    $('#backgroundTier').show();
    jQuery("#screen").animate({height:WST.pageHeight(),right:"0"},500);
    $('.screen-top').css('height',WST.pageHeight()-44);
    $(".ui-container").css({height:WST.pageHeight()-88,overflow:"hidden"})
}
/*关闭筛选层*/
function closeScreenTier(){
    if($('#isFreeShipping').val() == '' && $('#minPrice').val() == '' && $('#maxPrice').val() == '' && $('#vs').val() == ''){
      $('.screen1').addClass('screen').removeClass('screen1').next().css('color','#6a6b6d');
    };
    jQuery("#screen").animate({right:"-91%"},500);
    setTimeout(function(){
        $('#backgroundTier').hide();
        $(".ui-container").css({height:"auto",overflow:"visible"})
    },570);
}
function screenGoodsList(){
    closeScreenTier();
    $('#currPage').val('0');//当前页归零
    $('#goods-list').html('');
    goodsList(7);
}
/*是否包邮*/
function isFreeShipping(obj){
    $('#isFreeShipping').val($(obj).attr('f'));
    $('#freeed').show();
    $('#freeValue').html($(obj).html());
    $('#free').hide();
    $('#currPage').val('0');//当前页归零
    $('#goods-list').html('');
    goodsList(2);
}
/*去选*/
function cancelFree(){
    $('#freeed').hide();
    $('#free').show();
    $('#isFreeShipping').val('');
    $('#currPage').val('0');//当前页归零
    $('#goods-list').html('');
    goodsList(2);
}
/*选择属性*/
function selectAttr(obj){
        $(obj).siblings('#a_'+$(obj).attr('d')).html($(obj).html()).show();
        $('#a_'+$(obj).attr('d')).siblings().hide();
        $(obj).parent().parent().removeClass('no');
        $(obj).parent().parent().siblings('.no').remove();
        var vs = $('#vs').val();
        vs = (vs!='')?vs.split(','):[];
        vs.push($(obj).attr('d'));
        $('#v_'+$(obj).attr('d')).val($(obj).attr('v'));
        /*除去重复*/
        var new_arr=[];
        for(var i=0;i<vs.length;i++) {
        　　var items=vs[i];
        if($.inArray(items,new_arr)==-1) {
        　　　　new_arr.push(items);
        　　}
        }
        var arr = { attrName: $(obj).attr('n'), attr: $(obj).html(), attrId: $(obj).attr('d')}
        attrsed.push(arr);
        $('#screenAttred').html('');
        var gettp2 = document.getElementById('screenListed').innerHTML;
            laytpl(gettp2).render(attrsed, function(html){
                $('#screenAttred').append(html);
            });
        $(obj).parent().parent().remove();
        $('#vs').val(new_arr.join(','));
        $('#currPage').val('0');//当前页归零
        $('#goods-list').html('');
        goodsList(3);

}
/*展开属性*/
function showAll(obj){
    if($(obj).attr('s') == 0){
        $(obj).attr('s',1);
        $(obj).addClass('arrowed').removeClass('arrow');
        $(obj).parent().next('.option-box').addClass('expand');
    }else{
        $(obj).attr('s',0);
        $(obj).addClass('arrow').removeClass('arrowed');
        $(obj).parent().next('.option-box').removeClass('expand');
    }
}
/*重置*/
function resetAll(){
    $('#isFreeShipping').val('');
    $('#vs').val('');
    $('#minPrice').val('');
    $('#maxPrice').val('');
    $('.vsed').val('');
    $('#freeed').hide();
    $('#free').show();
    $('#isFreeShipping').val('');
    attrsed = [];
    $('.attrs').each(function(){
        $(this).show().removeClass('selected').attr('onclick','javascript:selectAttr(this);').show().css('background-color','f0f2f5');
    });
    $('.screen-box').each(function(){
        $(this).attr('class','screen-box no');
    })
    $('#currPage').val('0');//当前页归零
    $('#goods-list').html('');
    $('#screenAttr').html('');
    $('#screenAttred').html('');
    goodsList(6);
}
/*取消属性选择*/
function cancelSeled(obj){
    var attrId = $(obj).attr('d');
    var vs = $('#vs').val();
    vs = (vs!='')?vs.split(','):[];
    var key = $.inArray(attrId,vs);
    vs.splice(key,1);
    $('#vs').val(vs.join(','));
    attrsed.splice($(obj).attr('k'),1);
    $('#screenAttr').children('.screen-box').remove();
    $('#screenAttred').html('');
    var gettp2 = document.getElementById('screenListed').innerHTML;
        laytpl(gettp2).render(attrsed, function(html){
            $('#screenAttred').append(html);
        });
    goodsList(3);
}

function goShopHome(sid){
    location.href=WST.U('mobile/shops/index','shopId='+sid,true);
}

function tabType(obj,tab_type){
    $('.tab_box li').removeClass('tab_box_active')
    $(obj).addClass('tab_box_active')


    if(tab_type != 3){
        $("#goods_screening").show();
    }else{
        $("#goods_screening").hide()
    }

    $('#tab_type').val(tab_type);
    goodsList(null,'newPage');
}


//获取商品列表
function goodsList(from,newPage){
    $('#Load').show();
    loading = true;
    var param = {};
    param.catId = $('#catId').val();
    param.brandId = $('#brandId').val();
    param.condition = $('#condition').val();
    param.desc = $('#desc').val();
    param.keyword = $('#keyword').val();
    param.searchType = $('#searchType').val();
    param.saleType=$("#saleType").val();
	param.page = Number( $('#currPage').val() ) + 1;
    param.fl = $('#fl').val();
    param.tab_type = $('#tab_type').val();
    param.pagesize = 10;
    param.page = Number( $('#currPage').val() ) + 1;
    if( newPage == 'newPage' ){
        $('#goods-list').html("");
        $("#shops-list").html("");
        param.page = 1;
    }
    $.post(WST.U('mobile/goods/pageQuery'), param,function(data){
        var json = WST.toJson(data);
        if(param.tab_type != 3){
            $('#currPage').val(json.current_page);
            $('#totalPage').val(json.last_page);
            var gettpl = document.getElementById('list').innerHTML;
            laytpl(gettpl).render(json.data, function(html){
                $('#goods-list').append(html);
            });
            WST.imgAdapt('j-imgAdapt');
            loading = false;
            $('#Load').hide();
            echo.init();//图片懒加载
        }else{
             json = json.author;
            $('#currPage').val(json.current_page);
            $('#totalPage').val(json.last_page);
            $('#minScore').val(json.minScore);
            var gettpl = document.getElementById('author_list').innerHTML;
            laytpl(gettpl).render(json.data, function(html){
                $('#shops-list').append(html);
            });
            //imgShop('j-imgAdapt');
            //imgShop('goods-item');
            loading = false;
            $('#Load').hide();
            echo.init();//图片懒加载
        }
    });
}
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
    WST.initFooter('category');
    $('.wst-se-search').on('submit', '.input-form', function(event){
        event.preventDefault();
    })
    goodsList(20);
    WST.imgAdapt('j-imgRec');
    $('.wst-gol-adsb').css('height',$('.j-imgRec').width()+20);
    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - screen.height)) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
                goodsList(8);
            }
        }
    });
});
$('#backgroundTier').on('touchstart',function(){
    closeScreenTier()
})