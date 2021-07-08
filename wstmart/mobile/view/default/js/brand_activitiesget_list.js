// 获取商城快讯
function getNewList($catId = ''){
    $('#Load').show();
    loading = true;
    var param = {};
    param.pagesize = 10;
    param.page = Number( $('#currPage').val() ) + 1;
    param.catId = $('#catId').val();
    $.post(WST.U('mobile/brand_activities/getNewsList'), param, function(data){
        var json = WST.toJson(data);
        var html = '';
        if(json && json.data && json.data.length>0){
            var gettpl = document.getElementById('newsList').innerHTML;
            laytpl(gettpl).render(json.data, function(html){
                $('#newsListBox').append(html);
            });
            $('#currPage').val(data.current_page);
            $('#totalPage').val(data.last_page);
        }else{
            html += '<ul class="ui-row-flex wst-flexslp ui-flex-align-center" style="margin-top:150px;">';
            html += '<li class="ui-col ui-flex ui-flex-pack-center">';
            html += '<p>暂无品牌活动</p>';
            html += '</li>';
            html += '</ul>';
            $('#newsListBox').html(html);
        }
        WST.imgAdapt('j-imgAdapt');
        loading = false;
        $('#Load').hide();
        echo.init();//图片懒加载
    });
}
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
    WST.initFooter();
    getNewList();


    var dataHeight = $("#frame").css('height');
    $("#frame").css('top',0);
    var dataWidth = $("#frame").css('width');
    $("#frame").css('right','-100%');


    $(window).scroll(function(){
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - screen.height)) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
                getNewList();
            }
        }
    });
});
// 刷新列表页
function reFlashList(){
    $('#currPage').val('0');
    $('#newsListBox').html(' ');
    getNewList();
}

//弹框
function dataShow(){
    jQuery('#cover').attr("onclick","javascript:dataHide();").show();
    jQuery('#frame').animate({"right": 0}, 500);
}
function dataHide(){
    jQuery('#frame').animate({'right': '-100%'}, 500);
    jQuery('#cover').hide();
}


