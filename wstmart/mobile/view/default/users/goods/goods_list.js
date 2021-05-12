jQuery.noConflict();
// 获取保底交易商品列表
function getBdGoodsList(){
  $('#Load').show();
    loading = true;
    var param = {};
    param.bdStatus = $('#bdStatus').val();
    param.pagesize = 10;
    param.page = Number( $('#currPage').val() ) + 1;
    param.deliverType = -1;
    param.payType = -1;
    $.post(WST.U('mobile/goods/myGoodsByPage'), param, function(data){
        var json = WST.toJson(data);
        if(json.status>0){
          var html = '';
          if(json && json.data && json.data.length>0){
            var gettpl = document.getElementById('shopList').innerHTML;
            laytpl(gettpl).render(json.data, function(html){
              $('#order-box').append(html);
            });

            $('#currPage').val(json.current_page);
            $('#totalPage').val(json.last_page);
          }else{
  	        html += '<div class="wst-prompt-icon"><img src="'+ window.conf.MOBILE +'/img/nothing-order.png"></div>';
	        html += '<div class="wst-prompt-info">';
	        html += '<p>暂无相关商品</p>';
	        html += '</div>';
            $('#order-box').html(html);
          }
          WST.imgAdapt('j-imgAdapt');
          loading = false;
          $('#Load').hide();
          echo.init();//图片懒加载
        }else{
          WST.msg(json.msg,'info');
        }
    });
}
var currPage = totalPage = 0;
var loading = false;
$(document).ready(function(){
  
  $('#bdStatus').val(jQuery("#shopBox li:first").attr("type"));
  jQuery("#shopBox li:first").addClass("tab-curr");
  getBdGoodsList();

  WST.initFooter('user');
  // Tab切换卡
  $('.tab-item').click(function(){
      $(this).addClass('tab-curr').siblings().removeClass('tab-curr');
      var type = $(this).attr('type');
      $('#bdStatus').val(type);
      reFlashList();
  });
  // 弹出层
   $("#frame").css('top',0);

    $(window).scroll(function(){  
        if (loading) return;
        if ((5 + $(window).scrollTop()) >= ($(document).height() - screen.height)) {
            currPage = Number( $('#currPage').val() );
            totalPage = Number( $('#totalPage').val() );
            if( totalPage > 0 && currPage < totalPage ){
            	getBdGoodsList();
            }
        }
    });
});

// 刷新列表页
function reFlashList(){
  $('#currPage').val('0');
  $('#order-box').html(' ');
  getBdGoodsList();
}


// 拒收
function showEditMoneyBox(event){
    $("#wst-event3").attr("onclick","javascript:"+event);
    $("#editMoneyBox").dialog("show");
}

function editOrderMoney(oid){
  var newOrderMoney = $('#newOrderMoney').val();
  $.post(WST.U('mobile/orders/editOrderMoney'),{id:oid,orderMoney:newOrderMoney},function(data){
              hideDialog('#editMoneyBox');
              var json = WST.toJson(data);
              if(json.status>0){
                $('#newOrderMoney').val(' ');
                WST.msg(json.msg,'success');
                reFlashList();
              }else{
                WST.msg(json.msg,'info');
              }
            });
}

//退款
function showRefundBox(id){
    $.post(WST.U('mobile/orders/toShopRefund'),{id:id},function(data){
           var json = WST.toJson(data);
           $('#refundOid').html(json.orderNo);
           $('#realTotalMoney').html('¥ '+json.realTotalMoney);
           $('#refundMoney').html('¥ '+json.backMoney);
           $('#useScore').html(json.useScore);
           $('#scoreMoney').html('¥ '+json.scoreMoney);
           // 弹出层滚动条
           var clientH = WST.pageHeight();// 屏幕高度
           var boxheadH = $('#refund-boxTitle').height();// 弹出层标题高度
           var contentH = $('#refund-content').height(); // 弹出层内容高度
           $('#refund-content').css('height',clientH-boxheadH+'px');
           $("#wst-event8").attr("onclick","javascript:refund("+id+")");
           reFundDataShow();
    })
}
//弹框
function reFundDataHide(){
    $('#shopBox').show();
    var dataHeight = $("#refundFrame").css('height');
    var dataWidth = $("#refundFrame").css('width');
    jQuery('#refundFrame').animate({'right': '-'+dataWidth}, 500);
    jQuery('#cover').hide();
}
function reFundDataShow(){
    jQuery('#cover').attr("onclick","javascript:reFundDataHide();").show();
    jQuery('#refundFrame').animate({"right": 0}, 500);
    setTimeout(function(){$('#shopBox').hide();},600)
}
// 退款
function refund(id){

  var params = {};
      params.refundStatus = $('#refundStatus')[0].checked?1:-1;
      params.content = $.trim($('#shopRejectReason').val());
      params.id = id;
      if(params.refundStatus==-1 && params.content==''){
        WST.msg('请输入原因','info');
        return;
      }
      $.post(WST.U('mobile/orderrefunds/shoprefund'),params,function(data){
        var json = WST.toJson(data);
        if(json.status==1){
            WST.msg('操作成功','success');
            history.go(0);
          }else{
            WST.msg(json.msg,'info');
          }
     });

}

//  隐藏对话框
function hideDialog(id){
  $(id).dialog("hide");
}

// 确认收货
function receive(oid){
  hideDialog('#wst-di-prompt');
  $.post(WST.U('mobile/orders/receive'),{id:oid},function(data){
      var json = WST.toJson(data);
      if(json.status==1){
        reFlashList();// 刷新列表
      }else{
        WST.msg(json.msg,'info');
      }
  });
}



/*********************** 订单详情 ****************************/
//弹框
function dataShow(){
    jQuery('#cover').attr("onclick","javascript:dataHide();").show();
    jQuery('#frame').animate({"right": 0}, 500);
    setTimeout(function(){$('#shopBox').hide();},600)
    
}
function dataHide(){
    $('#shopBox').show();
    var dataHeight = $("#frame").css('height');
    var dataWidth = $("#frame").css('width');
    jQuery('#frame').animate({'right': '-'+dataWidth}, 500);
    jQuery('#cover').hide();
}



function getOrderDetail(oid){
  $.post(WST.U('mobile/orders/getDetail'),{id:oid},function(data){
      var json = WST.toJson(data);
      if(json.status!=-1){
        var gettpl1 = document.getElementById('detailBox').innerHTML;
          laytpl(gettpl1).render(json, function(html){
            $('#content').html(html);
            // 弹出层滚动条
            var clientH = WST.pageHeight();// 屏幕高度
            var boxheadH = $('#boxTitle').height();// 弹出层标题高度
            var contentH = $('#content').height(); // 弹出层内容高度
            if((clientH-boxheadH) < contentH){
              $('#content').css('height',clientH-boxheadH+'px');
            }
            dataShow();
          });
      }else{
        WST.msg(json.msg,'info');
      }
  });
}
// 跳转到评价页
function toAppr(oid){
  location.href=WST.U('mobile/orders/orderappraise',{'oId':oid});
}
// 投诉
function complain(oid){
  location.href=WST.U('mobile/ordercomplains/complain',{'oId':oid});
}

//修改价格
function editPrice(orderNo){
  alert('修改价格');
}

// 出售增值
function toSale(){
	WST.dialog('该操作需要开设店铺或登录店铺，请在电脑上进行此操作',"WST.dialogHide('prompt')");
}

// 保底回购
function toBackSale(id){
	WST.dialog('确定保底回购吗？','backSale('+id+')');
}
function backSale(id){
	var params = {};
	params.ids = {goodsId:id};
	$.post(WST.U('mobile/goods/backSale'), params, function(data,textStatus){
		var json = WST.toJson(data);
		if(json.status=='1'){
			WST.dialog('操作成功,商品已进入保底回购状态,请仔细阅读回购事项并留下联系方式.',"WST.dialogHide('prompt')");
			reFlashList();
		}else if(json.status=='-1'){
			WST.dialog(json.msg,"WST.dialogHide('prompt')");
		}else{
			WST.dialog('操作失败!',"WST.dialogHide('prompt')");
		}
	});
}

// 提货
function toDeliver(id){
	WST.dialog('确定提货吗？','delivery('+id+')');
}
function delivery(id){
	var params = {};
	params.ids = {goodsId:id};
	$.post(WST.U('mobile/goods/bdDelivery'), params, function(data,textStatus){
		var json = WST.toJson(data);
		if(json.status=='1'){
			WST.dialog('操作成功,商品已登记提货,请凭借验证码和注册手机号到线下服务中心提货.',"WST.dialogHide('prompt')");
			reFlashList();
		}else if(json.status=='-1'){
			WST.dialog(json.msg,"WST.dialogHide('prompt')");
		}else{
			WST.dialog('操作失败!',"WST.dialogHide('prompt')");
		}
	});
}

function orderDelivery(oid){
	if(deliverType==0){
		hideDialog('#deliveryBox');
	}else{
		 WST.dialogHide('prompt');
	}
  $.post(WST.U('mobile/orders/deliver'),{id:oid,expressId:$('#expressId').val(),expressNo:$('#expressNo').val()},function(data){
        var json = WST.toJson(data);
        if(json.status>0){
          $('#order-box').html(' ');
          reFlashList();
        }else{
          WST.msg(json.msg,'info');
        }
      });
}

/*************** 修改价格需要用到的方法 ******************/

 //只能輸入數字和小數點
 WST.isNumberdoteKey = function(evt){
  var e = evt || window.event; 
  var srcElement = e.srcElement || e.target;
  
  var charCode = (evt.which) ? evt.which : event.keyCode;     
  if (charCode > 31 && ((charCode < 48 || charCode > 57) && charCode!=46)){
    return false;
  }else{
    if(charCode==46){
      var s = srcElement.value;     
      if(s.length==0 || s.indexOf(".")!=-1){
        return false;
      }     
    }   
    return true;
  }
 }
 WST.limitDecimal = function(obj,len){
  var s = obj.value;
  if(s.indexOf(".")>-1){
    if((s.length - s.indexOf(".")-1)>len){
      obj.value = s.substring(0,s.indexOf(".")+len+1);
    }
  }
  s = null;
}