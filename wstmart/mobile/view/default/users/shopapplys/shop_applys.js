jQuery.noConflict();

$(function(){

  WST.upload({
    pick:'#businessLicenceImgbutton',
    formData: {dir:'shops'},
    accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
    callback:function(f){
      var json = WST.toJson(f);
      if(json.status==1){
        $('#businessLicenceImgShow').css({"height": "65px","margin-left": "10px"}).attr('src',WST.conf.RESOURCE_PATH+"/"+json.savePath+json.thumb).show();
        $('#businessLicenceImg').val(json.savePath+json.name);
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

function saveStep(flowId){

  if( flowId == 3 ){
    var params = WST.getParams('.a-ipt3');
  }else{
    var params = WST.getParams('.a-ipt');
  }

  params.flowId = flowId;

  var load = WST.load({msg:'正在提交请求，请稍后...'});
  $.post(WST.U('mobile/shops/saveStep'),params,function(data,textStatus){
    var json = WST.toJson(data);
    if(json.status==1){
      if( flowId == 3 ){
        window.location.reload();
      }
    }else{
      layer.close(load);
      WST.msg(json.msg,{icon:5});
    }
  });
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