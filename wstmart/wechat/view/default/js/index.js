//列表
function indexList(){
	$('#Load').show();
	loading = true;
	var param = {};
	param.currPage = Number( $('#currPage').val() ) + 1;
    $.post(WST.U('wechat/index/pageQuery'), param,function(data){
        var json = WST.toJson(data);
        if(json && json.catId){
	        $('#currPage').val(json.currPage);
            var gettpl = document.getElementById('list').innerHTML;
            laytpl(gettpl).render(json, function(html){
                $('#goods-list').append(html);
            });
            echo.init();
	        WST.imgAdapt('j-imgAdapt');
        }
	    loading = false;
	    $('#Load').hide();
    });
}
//商品列表页
function getGoodsList(goodsCatId){
	location.href = WST.U('wechat/goods/lists','cat='+goodsCatId);
}
//关注
function forFollow(){
	$("#wst-di-weixincode").dialog("show");
}
function closeFollow(){
    $.post(WST.U('wechat/index/closeFollow'), {},function(data){
        var json = WST.toJson(data);
        if(json.status==1){
        	$('.wst-in-public').hide();
        	$('.wst-in-header').removeClass('wst-in-change');
        	$('.wst-in-search').removeClass('wst-in-change');
        }
    });
}
var currPage = 0;
var loading = false;
$(document).ready(function(){
    var pageId = $('#pageId').val();
    WST.initFooter('home');
    if(pageId == 0){
        //搜索
        $(window).scroll(function(){
            if( $(window).scrollTop() > 42 ){
                $('#j-header').addClass('active');
                $('#j-searchs').addClass('active');
            }else{
                $('#j-header').removeClass('active');
                $('#j-searchs').removeClass('active');
            }
        });
        $('.wst-se-search').on('submit', '.input-form', function(event){
            event.preventDefault();
        })
        //广告
        new Swiper('.banner', {
            autoplay: true,
            autoHeight: true, //高度随内容变化
            width: window.innerWidth,
            on: {
                resize: function(){
                    this.params.width = window.innerWidth;
                    this.update();
                },
            } ,
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
            },
        });

        //文章
        if($('.wst-in-news a').hasClass("words")){
            new Swiper('.swiper-container1', {
                autoplay: true,
                autoHeight: true, //高度随内容变化
                width: window.innerWidth,
                on: {
                    resize: function(){
                        this.params.width = window.innerWidth;
                        this.update();
                    },
                }
            });
        }

        var w = WST.pageWidth();
        //咨询上广告
        if($('.wst-in-activity a').hasClass("advert4")){
        }else{
            $('.wst-in-activity .advert4').hide();
        }
        //中间大广告
        if($('.wst-in-adst a').hasClass("advert2")){
        }else{
            $('.wst-in-adst').hide();
        }

        //中间小广告
        if($('.wst-in-adsb a').hasClass("advert3")){
            new Swiper('.swiper-container2', {
                slidesPerView: 3,
                freeMode : true,
                spaceBetween: 0,
                autoplay : 2000,
                speed:1200,
                loop : true,
                autoHeight: true, //高度随内容变化
                on: {
                    resize: function(){
                        this.params.width = window.innerWidth;
                        this.update();
                    },
                    slideChange(){
                        echo.init();
                    }
                }
            });
        }else{
            $('.wst-in-adsb').hide();
        }

        //刷新
        indexList();
        $(window).scroll(function(){
            if (loading) return;
            if (5 + ($(window).scrollTop()) >= ($(document).height() - screen.height)) {
                currPage = Number( $('#currPage').val() );
                if(currPage < 10 ){
                    indexList();
                }
            }
        });
    }else{
        WST.selectCustomMenuPage('index');
        new Swiper('.banner', {
            autoplay: true,
            autoHeight: true, //高度随内容变化
            width: window.innerWidth,
            on: {
                resize: function(){
                    this.params.width = window.innerWidth;
                    this.update();
                },
            } ,
            pagination: {
                el: '.swiper-pagination',
                type: 'bullets',
            },
        });
    }
});

function shopsList(latitude,longitude){
    params = {};
    params.radius = $('.search-radius').val();
    params.latitude = latitude;
    params.longitude = longitude;
    $.post(WST.U('wechat/index/customPageShopsList'), params,function(data) {
        var json = WST.toJson(data);
        if(json){
            if(json.location){
                $(".location-text").html(json.location);
            }else{
                $(".location-text").html("未知");
            }
            var gettpl = document.getElementById('shop-list').innerHTML;
            laytpl(gettpl).render(json, function(html){
                $('#shops-list').append(html);
            });
        }
    });
}