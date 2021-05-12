jQuery.noConflict();
// 获取图片宽高
function gGetImgWH(img_url, index){
	// 创建对象
	var img = new Image();
	// 改变图片的src
	img.src = img_url;
	// 判断是否有缓存
	if (img.complete) {
	  // 打印
	  goodsInfo.gallery[index].w = img.width;
	  goodsInfo.gallery[index].h = img.height;
	} else {
	  // 加载完成执行
	  img.onload = function () {
		//  打印
		goodsInfo.gallery[index].w = img.width;
		goodsInfo.gallery[index].h = img.height;
	  }
	}
  }
  
  // 查看大图
  function gViewImg(index){
	var pswpElement = document.querySelectorAll('.pswp')[0];
	// build items array
	if(!goodsInfo.gallery || goodsInfo.gallery.length==0)return;
	// define options (if needed)
	var options = {
	  index: index
	};
	// Initializes and opens PhotoSwipe
	var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, goodsInfo.gallery, options);
	gallery.init();
  }
//切换
function pageSwitch(obj,type){
	$(obj).addClass('active').siblings('.wst-de-introduce span').removeClass('active');
	$('#goods'+type).show().siblings('.wst-de-contents').hide();
	if(type==4){
    	$('#currPage').val('');
    	$('#record-list').html('');
		friendsList();
		bargainInfo();
	}
}
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
	$("embed").removeAttr('height').css('width','100%');
	time();
	//商品图片
	new Swiper('.swiper-container', {
		autoplay: false,
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
    $('.ui-slider-indicators').hide();
    
    //页码
    $('#record .contents').scroll(function(){
        if (loading) return;
        if ((300 + $('#record .contents').scrollTop()) >= $('#record .contents').height()) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
            	
            }
        }
    });
});
//亲友团列表
function friendsList(){
    loading = true;
    $('#Load').show();
    var param = {};
    param.id = goodsInfo.bargainId;
    param.bargainUserId = goodsInfo.bargainUserId;
	param.pagesize = 10;
	param.page = Number( $('#currPage').val() ) + 1;
	$('.wst-go-top').hide();
    $.post(WST.AU('bargain://bargains/helpsList'), param,function(data){
        var json = WST.toJson(data);
        $('#currPage').val(json.current_page);
        $('#totalPage').val(json.last_page);
        var gettpl = document.getElementById('list').innerHTML;
        laytpl(gettpl).render(json, function(html){
            $('#record-list').append(html);
        })
        $('#Load').hide();
        loading = false;
        echo.init();//图片懒加载
    });
}
//砍价人信息
function bargainInfo(){
    var param = {};
    param.id = goodsInfo.bargainId;
    param.bargainUserId = goodsInfo.bargainUserId;
    $.post(WST.AU('bargain://bargains/bargainInfo'), param,function(data){
    	var json = WST.toJson(data);
    	if(json){
    		$('.uhelpNum').html(json.helpNum);
    		$('.ucurrPrice').html('¥'+json.currPrice);
    	}
    });
}

//砍价成功提示
var prompt;
function promptBargain(message){
	jQuery('#cover').attr("onclick","javascript:cartHide();").show();
	prompt = new NotificationFx({
		message : '<div class="wst-pr-gold"><span class="money"><p>'+message+'</p></span><div>',
		layout : 'growl',
		effect : 'jelly',
		type : 'notice', // notice, warning, error or success
		onClose : function() {
			jQuery('#cover').hide();
		}
	});
	prompt.show();
}
function cartHide(){
	jQuery('#cover').hide();
	prompt.dismiss();
}
//第一刀
function forFirst(){
	if(WST.conf.IS_LOGIN==0){
		WST.inLogin();
		return;
	}
	$.post(WST.AU('bargain://bargains/firstKnife'),{id:goodsInfo.bargainId},function(data,textStatus){
	     var json = WST.toJson(data);
	     if(json.status==1){
	    	 promptBargain('恭喜！成功砍价'+json.data+'元！');
    		 setTimeout(function(){
    			 location.reload();
    		 },3000);
	     }else{
	    	 WST.msg(json.msg,'info');
	     }
	});
}
//补刀
function forAdd(){
    var param = {};
    param.id = goodsInfo.bargainId;
    param.bargainUserId = goodsInfo.bargainUserId;
	param.signType = goodsInfo.signType;
	param.openId = goodsInfo.openId;
	param.userName = goodsInfo.userName;
	param.userPhoto = goodsInfo.userPhoto;
	$.post(WST.AU('bargain://bargains/addKnife'),param,function(data,textStatus){
	     var json = WST.toJson(data);
	     if(json.status==1){
	    	 promptBargain('成功帮TA砍价'+json.data+'元！');
    		 setTimeout(function(){
    		    $('#currPage').val('');
    		    $('#record-list').html('');
    			friendsList();
    			bargainInfo();
    		 },2000);
	     }else{
	    	 WST.msg(json.msg,'info');
	     }
	});
}
//下单
function addOrder(){
	if(WST.conf.IS_LOGIN==0){
		WST.inLogin();
		return;
	}
	$.post(WST.AU('bargain://carts/addCart'),{id:goodsInfo.bargainId,buyNum:1,rnd:Math.random()},function(data,textStatus){
	     var json = WST.toJson(data);
	     if(json.status==1){
    		 location.href=WST.AU('bargain://carts/wxSettlement');
	     }else{
	    	 WST.msg(json.msg,'info');
	     }
	});
}
//参与
function toPartake(id){
	location.href=WST.AU('bargain://goods/wxdetail','id='+id);
}
//关注
function forFollow(){
	$("#wst-di-weixincode").dialog("show");
}

function time(){
	var g = $('#bargain-time');
	var nowTime = new Date(Date.parse(g.attr('sc').replace(/-/g, "/")));
    var startTime = new Date(Date.parse(g.attr('sv').replace(/-/g, "/")));
    var endTime = new Date(Date.parse(g.attr('ev').replace(/-/g, "/")));
    if(startTime.getTime()> nowTime.getTime()){
        var opts = {
        	nowTime:nowTime,
			endTime: startTime,
			callback: function(data){
			    if(data.last>0){
			    	var html = [];
				    if(data.day>0)html.push("<span>"+data.day+"</span>天");
				    html.push("<span>"+data.hour+"</span>小时<span>"+data.mini+"</span>分<span>"+data.sec+"</span>秒");
				    $('#bargaintime').html("砍价活动还有"+html.join('')+"开始");
			    }else{
			    	var opts2 = {
	                    nowTime: data.nowTime,
						endTime: endTime,
						callback: function(data2){
						    if(data2.last>=0){
						    	var html = [];
							    if(data2.day>0)html.push("<span>"+data2.day+"</span>天");
							    html.push(data2.hour+"小时"+data2.mini+"分"+data2.sec+"秒");
							    $('#bargaintime').html("砍价活动剩余"+html.join(''));
							    $('.wst-goods_buym').removeClass('active').removeAttr('disabled');
						    }else{
						    	$('#bargaintime').html('砍价活动已结束');
						    }
						    	
						}
					}
			    	WST.countDown(opts2);
			    }		
			}
		};
		WST.countDown(opts);
    }else if(startTime.getTime()<= nowTime.getTime() && endTime.getTime() >=nowTime.getTime()){
        var opts = {
        	nowTime:nowTime,
			endTime: endTime,
			callback: function(data){
			    if(data.last>0){
			    	var html = [];
				    if(data.day>0)html.push("<span>"+data.day+"</span>天");
				    html.push("<span>"+data.hour+"</span>小时<span>"+data.mini+"</span>分<span>"+data.sec+"</span>秒");
				    $('#bargaintime').html("砍价活动剩余"+html.join(''));
				    $('.wst-goods_buym').removeClass('active').removeAttr('disabled');
			    }else{
			    	$('.wst-goods_buym').addClass('active').attr('disabled', 'disabled');
			    	$('#bargaintime').html('砍价活动已结束');
			    }			    	
			}
		};
		WST.countDown(opts);
    }else{
        $('.wst-goods_buym').addClass('active').attr('disabled', 'disabled');
        $('#bargaintime').html('砍价活动已结束');
    }
}