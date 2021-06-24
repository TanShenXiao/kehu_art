jQuery.noConflict();
var loading = false;
$(function(){
  $('.wst-se-search').on('submit', '.input-form', function(event){
      event.preventDefault();
  })
    // 加载商品列表
    shopsList();
    // 广告
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
    // 商家推荐
    new Swiper('.swiper-container1', {
        slidesPerView: 4,
        freeMode : true,
        spaceBetween: 0,
        autoplay : true,
        speed:1200,
        loop : false,
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
    // 推荐
    WST.imgAdapt('j-imgRec');
    // 热卖
    WST.imgAdapt('j-imgRec1');
    $('.wst-gol-adsb').css('height',$('.j-imgRec').width()+20);
    // 商品分类
    var h = WST.pageHeight();
    var dataHeight = $("#frame").css('height');
    if(parseInt(dataHeight)>h-42){
        $('#content').css('overflow-y','scroll').css('height',h-42);
    }
    $(window).scroll(function(){
        if (loading) return;
        if (($(window).scrollTop()) >= ($(document).height() - screen.height-10)) {
            shopsList();
        }
    });
});

//弹框
function dataShow(){
    jQuery('#cover').attr("onclick","javascript:dataHide();").show();
    jQuery('#frame').animate({"right": 0}, 500);
}
function dataHide(){
    jQuery('#frame').animate({'right': '-100%'}, 500);
    jQuery('#cover').hide();
}

function showRight(obj, index){
    $(obj).addClass('wst-goodscate_selected').siblings('#goodscate').removeClass('wst-goodscate_selected');
    $('.goodscate1').eq(index).show().siblings('.goodscate1').hide();
}
function searchGoods(){
    location.href=WST.U('wechat/shops/index','goodsName='+$('#searchKey').val(),true);
}
/*分类*/
function goGoodsList(shopId,ct1,ct2){
    var param = 'shopId='+shopId+'&ct1='+ct1;
    if(ct2)
        param += '&ct2='+ct2;
    param.shopId = 1;
    location.href=WST.U('wechat/shops/goods',param,true);
}

function shopAds(){
     //广告
    var slider = new fz.Scroll('.ui-slider', {
        role: 'slider',
        indicator: true,
        autoplay: true,
        interval: 3000
    });
    var w = WST.pageWidth();
    var h = w*2/5;
        var o = $('.ui-slider').css("padding-top",h);
        var scroll = new fz.Scroll('.ui-slider', {
            scrollY: true
        });
}

//获取商品列表
function shopsList(){
    $('#Load').show();
     loading = true;
     var param = {};
     param.currPage = Number( $('#currPage').val() ) + 1;
     $.post(WST.U('wechat/shops/getFloorData'), param, function(data){
         var json = WST.toJson(data);
         if(json && json.catId){
            var gettpl = document.getElementById('gList').innerHTML;
                laytpl(gettpl).render(json, function(html){
                  $('#goods-list').append(html);
            }); 
            $('#currPage').val(json.currPage);
            WST.imgAdapt('j-imgAdapt');
         }
         loading = false;
         $('#Load').hide();
     });
}

function toShopInfo(sid){
    location.href=WST.U('wechat/shops/view',{'shopId':sid},true)
}
function init(longitude,latitude) {
  var shopName = $('#shopName').val();
  var myLatlng = new qq.maps.LatLng(latitude,longitude);
  var myOptions = {
    zoom: 15,               
    center: myLatlng,      
    mapTypeId: qq.maps.MapTypeId.ROADMAP  
  }
  var map = new qq.maps.Map(document.getElementById("map"), myOptions);
  var marker = new qq.maps.Marker({
        position: myLatlng,
        map: map
    }); 
  var label = new qq.maps.Label({
        position: myLatlng,
        map: map,
        content:shopName
    });
  var cssC = {
        background:'#3A9BFF',
        padding:"2px",
        color: "#fff",
        fontSize: "18px",
    };
  label.setStyle(cssC);
  mapShow();
}
//地图弹框
function mapShow(){
    jQuery('#cover').attr("onclick","javascript:dataHide();").show();
    jQuery('#container').animate({"right": 0}, 500);
}
function mapHide(){
    var dataHeight = $("#container").css('height');
    var dataWidth = $("#container").css('width');
    jQuery('#container').animate({'right': '-'+dataWidth}, 500);
    jQuery('#cover').hide();
}