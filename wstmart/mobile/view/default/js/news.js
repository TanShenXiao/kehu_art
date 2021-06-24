function getNewList($catId = ''){
  $('#Load').show();
    loading = true;
    var param = {};
    param.pagesize = 10;
    param.page = Number( $('#currPage').val() ) + 1;
    param.catId = $('#catId').val();
    $.post(WST.U('mobile/news/getNewsList'), param, function(data){
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
          html += '<p>暂无商城快讯</p>';
          html += '</li>';
          html += '</ul>';
          $('#newsListBox').html(html);
        }
        WST.imgAdapt('j-imgAdapt');
        loading = false;
        $('#Load').hide();
        echo.init();
    });
}
var currPage = totalPage = 0;
var loading = false;
function initPage(){
    WST.initFooter();
    getNewList();
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
}
// 刷新列表页
function reFlashList(){
  $('#currPage').val('0');
  $('#newsListBox').html(' ');
  getNewList();
}
function news(id){
    location.href=WST.U('mobile/news/getnews','id='+id);
}
function like(){
    var articleId = $('#articleId').val();
    $.post(WST.U('mobile/News/like'),{id:articleId},function(data){
        var json = WST.toJson(data);
        if(json.status==1){
           $(".icon-like1").removeClass('icon-like1').addClass('icon-like2');
           $num = parseInt($('#likeNum').html());
           $num = $num+1;
           $('#like').show();
           $('#likeNum').html($num);


        }
    })
}
