//jQuery.noConflict();

$(function(){

  $(".time-component").each(function (idx, item) {
    var id_selector = $(item).attr('id');
    initTime('#'+id_selector,$('#'+id_selector).attr('v'));
  });

  //时间选择
  WST.upload({
    pick:'#legalCertificateImg_b',
    formData: {dir:'shops'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toJson(f);
      if(json.status==1){
        $('#legalCertificateImg_img').css({"height": "65px","margin-left": "10px"}).attr('src',WST.conf.RESOURCE_PATH+"/"+json.savePath+json.thumb).show();
        $('#legalCertificateImg').val(json.savePath+json.name);
      }
    },
    progress:function(rate){

    }
  });
  WST.upload({
    pick:'#businessLicenceImg_b',
    formData: {dir:'shops'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toJson(f);
      if(json.status==1){
        $('#businessLicenceImg_img').css({"height": "65px","margin-left": "10px"}).attr('src',WST.conf.RESOURCE_PATH+"/"+json.savePath+json.thumb).show();
        $('#businessLicenceImg').val(json.savePath+json.name);
      }
    },
    progress:function(rate){

    }
  });
  WST.upload({
    pick:'#bankAccountPermitImg_b',
    formData: {dir:'shops'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toJson(f);
      if(json.status==1){
        $('#bankAccountPermitImg_img').css({"height": "65px","margin-left": "10px"}).attr('src',WST.conf.RESOURCE_PATH+"/"+json.savePath+json.thumb).show();
        $('#bankAccountPermitImg').val(json.savePath+json.name);
      }
    },
    progress:function(rate){

    }
  });
  WST.upload({
    pick:'#organizationCodeImg_b',
    formData: {dir:'shops'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toJson(f);
      if(json.status==1){
        $('#organizationCodeImg_img').css({"height": "65px","margin-left": "10px"}).attr('src',WST.conf.RESOURCE_PATH+"/"+json.savePath+json.thumb).show();
        $('#organizationCodeImg').val(json.savePath+json.name);
      }
    },
    progress:function(rate){

    }
  });
  WST.upload({
    pick:'#taxRegistrationCertificateImg_b',
    formData: {dir:'shops'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toJson(f);
      if(json.status==1){
        $('#taxRegistrationCertificateImg_img').css({"height": "65px","margin-left": "10px"}).attr('src',WST.conf.RESOURCE_PATH+"/"+json.savePath+json.thumb).show();
        $('#taxRegistrationCertificateImg').val(json.savePath+json.name);
      }
    },
    progress:function(rate){

    }
  });

  WST.upload({
    pick:'#taxpayerQualificationImg_b',
    formData: {dir:'shops'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toJson(f);
      if(json.status==1){
        $('#taxpayerQualificationImg_img').css({"height": "65px","margin-left": "10px"}).attr('src',WST.conf.RESOURCE_PATH+"/"+json.savePath+json.thumb).show();
        $('#taxpayerQualificationImg').val(json.savePath+json.name);
      }
    },
    progress:function(rate){

    }
  });
  WST.upload({
    pick:'#shopImg_b',
    formData: {dir:'shops'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toJson(f);
      if(json.status==1){
        $('#shopImg_img').css({"height": "65px","margin-left": "10px"}).attr('src',WST.conf.RESOURCE_PATH+"/"+json.savePath+json.thumb).show();
        $('#shopImg').val(json.savePath+json.name);
      }
    },
    progress:function(rate){

    }
  });

  WST.upload({
    pick:'#shopImgButton',
    formData: {dir:'shops'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toJson(f);
      if(json.status==1){
        $('#shopImgShow').css({"height": "65px","margin-left": "10px"}).attr('src',WST.conf.RESOURCE_PATH+"/"+json.savePath+json.thumb).show();
        $('#shopImg').val(json.savePath+json.name);
      }
    },
    progress:function(rate){

    }
  });
})
var init_times = {}
function tsx_date(obj_name){
  if(!init_times[obj_name]){
      //时间管理
      $("#"+obj_name).mobiscroll().date({
        theme: "android-ics",
        lang: "zh",
        display: 'bottom',
        dateFormat: 'yy-mm-dd', //返回结果格式化为年月格式
        headerText: function (valueText) { //自定义弹出框头部格式
          array = valueText.split('-');
          return array[0] + "年" + array[1] + "月" + array[2] + "日";
        },
        onSelect: function (valueText, inst) {
          $("#" + obj_name).val(valueText);
        }
      });
    init_times[obj_name] = 1
    $('#'+obj_name).trigger("click");
  }
}

function initTime(id,val){
  var html = [],t0,t1;
  var str = val.split(':');
  for(var i=0;i<24;i++){
    t0 = (val.indexOf(':00')>-1 && (parseInt(str[0],10)==i))?'selected':'';
    t1 = (val.indexOf(':30')>-1 && (parseInt(str[0],10)==i))?'selected':'';
    html.push('<option value="'+i+':00" '+t0+'>'+i+':00</option>');
    html.push('<option value="'+i+':30" '+t1+'>'+i+':30</option>');
  }
  $(id).append(html.join(''));
}



/**
 * 循环创建地区
 * @param id            当前分类ID
 * @param val           当前分类值
 * @param className     样式，方便将来获取值
 */
WST.ITAreas = function(opts){
  opts.className = opts.className?opts.className:"j-areas";
  var obj = $('#'+opts.id);
  obj.attr('lastarea',1);
  $.post(WST.U('mobile/areas/listQuery'),{parentId:opts.val},function(data,textStatus){
    var json = WST.toJson(data);
    if(json.data && json.data.length>0){
      json = json.data;
      var html = [],tmp;
      var tid = opts.id+"_"+opts.val;
      var level = parseInt(obj.attr('level'),10);
      $('.area_'+level).addClass('hide');
      var level = level+1;
      html.push('<div id="'+tid+'" class="list '+opts.className+' area_'+level+'" areaId="0" level="'+level+'">');
      for(var i=0;i<json.length;i++){
        tmp = json[i];
        html.push("<p onclick='javascript:inChoice(this,\""+tid+"\","+tmp.areaId+","+level+");'>"+tmp.areaName+"</p>");
      }
      html.push('</div>');
      $(html.join('')).insertAfter('#'+opts.id);
      var h = WST.pageHeight();
      var listh = h/2-106;
      $(".wst-fr-box2 .list").css('overflow-y','scroll').css('height',listh+'px');
      $(".wst-fr-box2 .option").append('<p class="ui-nowrap-flex term active_'+level+' active" onclick="javascript:inOption(this,'+level+')">请选择</p>');
    }else{
      opts.isLast = true;
      opts.lastVal = opts.val;
      $(opts.addresst).val(opts.lastVal);
      var ht = '';
      $('.wst-fr-box2 .term').each(function(){
        ht += $(this).html();
      });
      $(opts.addresst+111).html(ht);
      dataHide();
    }
  });
}
$(document).ready(function(){
  var h = WST.pageHeight();
  $("#frame").css('bottom','-'+h/2);
  var listh = h/2-106;
  $(".wst-fr-box2 .list").css('overflow-y','scroll').css('height',listh+'px');
});
//弹框
function dataShow(addresst){

  if(window.addresst != addresst){
    inOption($('#add_fire'),0)
  }
  window.addresst = addresst

  jQuery('#frame').show();
  jQuery('#cover').attr("onclick","javascript:dataHide();").show();
  jQuery('#frame').animate({"bottom": 0}, 500);
}
function dataHide(){
  var dataHeight = $("#frame").css('height');
  jQuery('#frame').animate({'bottom': '-'+dataHeight}, 500);
  jQuery('#cover').hide();
  setTimeout(function(){
    jQuery('#frame').hide();
  },500);
}

//地址选择
function inOption(obj,n){
  $(obj).addClass('active').siblings().removeClass('active');
  $('.area_'+n).removeClass('hide').siblings('.list').addClass('hide');
  var level = $('#level').val();
  var n = n+1;
  for(var i=n; i<=level; i++){
    $('.area_'+i).remove();
    $('.active_'+i).remove();
  }
}

function inChoice(obj,id,val,level){
  $('#level').val((level+1));
  $(obj).addClass('active').siblings().removeClass('active');
  $('#'+id).attr('areaId',val);
  $('.active_'+level).removeClass('active').html($(obj).html());
  WST.ITAreas({id:id,val:val,className:'j-areas','addresst':window.addresst});
}

function save(){

  var linkPhone = $.trim($('#linkPhone').val());
  if(linkPhone==''){
    WST.msg('请填写联系方式','info');
    return;
  }
  var linkman = $.trim($('#linkman').val());
  if(linkman==''){
    WST.msg('请填写联系人','info');
    return;
  }
  var applyIntention = $.trim($('#applyIntention').val());
  if(applyIntention == ''){
    WST.msg('请填写营业范围','info');
    return;
  }
  var param = {};
  param.linkman = linkman;
  param.linkPhone = linkPhone;
  param.applyIntention = applyIntention;
  $.post(WST.U('mobile/shopapplys/add'),param,function(data){
    var json = WST.toJson(data);
    if(data.status==1){
      location.reload();
    }else{
      WST.msg(json.msg,'info');
    }
  });
}

function saveStep(flowId,nextflowId,type){

  var params = WST.getParams('.a-ipt');
  params.flowId = flowId;
  params.shopType = type;
  $('#applyFrom').isValid(function(v) {
    if (v) {
      var load = WST.load({msg:'正在提交请求，请稍后...'});
      $.post(WST.U('mobile/shops/saveStep'),params,function(data,textStatus){
        var json = WST.toJson(data);
        if(json.status==1){

            window.location.href = WST.U('mobile/shopapplys/index','id='+nextflowId +'&type='+ params.shopType);

        }else{
          WST.msg(json.msg,{icon:5});
        }
      });
    }
  })
}

/**
 * 进入到第三步
 */
function toThree(){
  $("#flowTwo").hide();
  $("#flowThree").show();
  saveStep(2);
}

function selectType( type ){
  if( type == 1 ){
    $(".showType0").hide();
    $(".showType1").show();
  }else{
    $(".showType0").show();
    $(".showType1").hide();
  }
  $("#shopType").val( type );
}

function showError( info ){
  if( info ){
    WST.dialog("很抱歉，您的入驻申请因【"+info +"】审核不通过。。。",'WST.dialogHide("prompt")')
  }
}


