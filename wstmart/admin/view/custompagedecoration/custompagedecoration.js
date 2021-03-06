var base_url = '';
var mmg;
var mmg_coupon;
var mmg_new;
var global_good_select_ids=[];
var global_coupon_select_ids=[];
var global_new_select_ids=[];
function initSaleGrid(p,ids){
    if(ids!='')global_good_select_ids = ids.split(',');
    var h = WST.pageHeight();
    var cols = [
        {title:'', name:'goodsName', width: 50,sortable:true,renderer: function(val,item,rowIndex){
                var checked = '';
                if(global_good_select_ids && in_array(item['goodsId'], global_good_select_ids)){
                    checked = 'checked';
                }
                return "<span class='select-goods-id'><input type='checkbox' "+checked+" goods-id="+item['goodsId']+" onclick='selectGoodIds(this)'></span>";
            }},
        {title:'商品图片', name:'goodsImg', width: 100, renderer: function(val,item,rowIndex){
                var thumb = item['goodsImg'];
                thumb = thumb.replace('.','_thumb.');
                return "<span class='weixin'><img id='img' onmouseout='toolTip()' onmouseover='toolTip()' style='height:50px;width:50px;' src='"+WST.conf.RESOURCE_PATH+"/"+thumb
                    +"'><span class='imged' ><img  style='height:180px;width:180px;' src='"+WST.conf.RESOURCE_PATH+"/"+item['goodsImg']+"'></span></span>";
            }},
        {title:'商品名称', name:'goodsName', width: 300,sortable:true,renderer: function(val,item,rowIndex){
                return "<span  ><p class='wst-nowrap'>"+item['goodsName']+"</p></span>";
            }},
        {title:'价格', name:'shopPrice' ,width:100,sortable:true, renderer: function(val,item,rowIndex){
                return '￥'+item['shopPrice'];
            }},
        {title:'所属分类', name:'goodsCatName' ,width:300,renderer: function(val,item,rowIndex){
                return "<span  ><p class='wst-nowrap'>"+item['goodsCatName']+"</p></span>";
            }},
        {title:'库存', name:'goodsStock' ,width:50,sortable:true,align:'center'}
    ];

    mmg = $('.mmg').mmGrid({height: h,indexCol: true, indexColWidth:50, cols: cols,method:'POST',
        url: WST.U('admin/goods/saleByPage'), fullWidthRows: true, autoLoad: false,remoteSort: true,sortName:'goodsSn',sortStatus:'desc',
        plugins: [
            $('#pg').mmPaginator({})
        ]
    });
    loadGrid(p);
}
function loadGrid(p){
    p=(p<=1)?1:p;
    var params = WST.getParams('.j-ipt');
    params.page = p;
    mmg.load(params);
}
function toolTip(){
    WST.toolTip();
}
function initGridCoupon(p,ids){
    if(ids!='')global_coupon_select_ids = ids.split(',');
    var h = WST.pageHeight();
    var cols = [
        {title:'', name:'couponValue', width: 50,sortable:true,renderer: function(val,item,rowIndex){
                var checked = '';
                if(global_coupon_select_ids && in_array(item['couponId'], global_coupon_select_ids)){
                    checked = 'checked';
                }
                return "<span class='select-coupon-id'><input type='checkbox' "+checked+" coupon-id="+item['couponId']+" onclick='selectCouponIds(this)'></span>";
            }},
        {title:'面值', name:'couponValue', width: 50,renderer:function(val,item,rowIndex){
                return '￥'+item['couponValue'];
            }},
        {title:'类型', name:'startPrice', width: 60,renderer:function(val,item,rowIndex){
                return (item['useCondition']==1)?("满"+item['useMoney']+"减"+item['couponValue']+"券"):"现金券";
            }},
        {title:'适用对象', name:'floorPrice', width: 60,renderer:function(val,item,rowIndex){
                return (item['useObjects']==0)?"全店通用":"部分商品";
            }},
        {title:'开始时间', name:'startDate', width: 120},
        {title:'结束时间', name:'endDate', width: 120},
        {title:'发放量', name:'couponNum', width: 30},
    ];

    mmg_coupon = $('.mmg-coupon').mmGrid({height: h,indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.U('admin/custompagedecoration/couponPageQuery'), fullWidthRows: true, autoLoad: false,
        plugins: [
            $('#pg-coupon').mmPaginator({})
        ]
    });
    loadGridCoupon(p);
}
function loadGridCoupon(p){
    var params=WST.getParams('#useCondition');
    p=(p<=1)?1:p;
    params.page=p;
    mmg_coupon.load(params);
}
function initGridNew(p,ids){
    if(ids!='')global_new_select_ids = ids.split(',');
    var h = WST.pageHeight();
    var cols = [
        {title:'', name:'', width: 50,sortable:true,renderer: function(val,item,rowIndex){
                var checked = '';
                if(global_new_select_ids && in_array(item['articleId'], global_new_select_ids) ){
                    checked = 'checked';
                }
                return "<span class='select-new-id'><input type='checkbox' "+checked+" new-id="+item['articleId']+" onclick='selectNewIds(this)'></span>";
            }},
        {title:'文章ID', name:'articleId' ,width:20,sortable:true},
        {title:'标题', name:'articleTitle' ,width:200,sortable:true},
        {title:'分类', name:'catName' ,width:100,sortable:true},
        {title:'创建时间', name:'createTime' ,width:120,sortable:true},
    ];

    mmg_new = $('.mmg-new').mmGrid({height:h,indexCol: true, cols: cols,method:'POST',nowrap:true,
        url: WST.U('admin/custompagedecoration/newPageQuery'), fullWidthRows: true, autoLoad: false,
        plugins: [
            $('#pg-new').mmPaginator({})
        ]
    });
    loadGridNew(p);
}
function loadGridNew(p){
    var params = {};
    p=(p<=1)?1:p;
    params.page=p;
    mmg_new.load(params);
}
function in_array(search,array){
    for(var i in array){
        if(array[i]==search){
            return true;
        }
    }
    return false;
}
$(function(){
    base_url = WST.conf.RESOURCE_PATH;
    // 商品组件列表名称长度计算
    $('.attr-content-goods-group').each(function(idx,item){
        $(item).find('.goods-group-columns-name').each(function(index,obj){
            var columns_title = $(obj).val();
            if(columns_title != undefined){
                $(obj).parent().find('.goods-group-columns-name-length').html(columns_title.length);
            }
        });
    });
    // 导航组件文字内容长度计算
    $('.attr-content-nav').each(function(idx,item){
        $(item).find('.attr-content-nav-text').each(function(index,obj){
            var nav_text = $(obj).val();
            if(nav_text != undefined){
                $(obj).parent().find('.nav-text-length').html(nav_text.length);
            }
        });
    });
    // 单文本组件提示文字长度计算
    $('.attr-content-txt').each(function(idx,item){
        var txt_text = $(item).find(".attr-content-txt-text").val();
        if(txt_text != undefined){
            $(item).find('.txt-text-length').html(txt_text.length);
        }
    });
    // 图文列表组件标题长度计算
    $('.attr-content-image-text').each(function(idx,item){
        var image_text_num = $(item).attr('image_text_num');
        var image_text_item = $(item).find('.image-text-style').val();
        var image_text_title = $(item).find(".attr-content-image-text-title").val();
        if(image_text_title != undefined){
            $(item).find('.image-text-title-length').html(image_text_title.length);
        }
        $(".effect-image-text").each(function(index, obj) {
            if($(obj).hasClass("image_text"+image_text_num)) {
                if (image_text_item == 1 || image_text_item == 2) {
                    if (image_text_title.length > 12) {
                        image_text_title = image_text_title.substr(0, 12) + '...';
                    }
                } else {
                    if (image_text_title.length > 20) {
                        image_text_title = image_text_title.substr(0, 20);
                    }
                }
                $(obj).find('.image-text-style-title').html(image_text_title);
            }
        });
    });
    // 图文列表组件简介长度计算
    $('.attr-content-image-text').each(function(idx,item){
        var image_text_num = $(item).attr('image_text_num');
        var image_text_item = $(item).find('.image-text-style').val();
        var image_text_desc = $(item).find(".attr-content-image-text-desc").val();
        if(image_text_desc != undefined){
            $(item).find('.image-text-desc-length').html(image_text_desc.length);
        }
        $(".effect-image-text").each(function(index, obj) {
            if($(obj).hasClass("image_text"+image_text_num)) {
                if (image_text_desc.length > 50) {
                    image_text_desc = image_text_desc.substr(0, 50)+ '...';
                }
                $(obj).find('.image-text-style-desc').html(image_text_desc);
            }
        });
    });
    // 多店铺组件顶部文字长度计算
    var shop_title = $(".attr-content-shop-title").val();
    if(shop_title != undefined){
        $('.shop-title-length').html(shop_title.length);
    }
    // 新闻组件新闻主题名称长度计算
    $('.attr-content-new').each(function(idx,item){
        var new_title = $(item).find(".attr-content-new-title").val();
        if(new_title != undefined){
            $(item).find('.new-title-length').html(new_title.length);
        }
    });
    // 营销活动组件新闻主题名称长度计算
    $('.attr-content-marketing').each(function(idx,item){
        var marketing_title = $(item).find(".attr-content-marketing-title").val();
        if(marketing_title != undefined){
            $(item).find('.marketing-title-length').html(marketing_title.length);
        }
    });
    // 底部导航组件按钮文字长度计算
    $(".attr-content-tabbar .attr-content-tabbar-item-group").each(function(idx, item) {
        var tabbar_text = $(item).find('.attr-content-tabbar-text').val();
        var tabbar_text_length = tabbar_text.length;
        if(tabbar_text_length > 4){
            $(item).find('.tabbar-text-length').html(4);
        }else{
            $(item).find('.tabbar-text-length').html(tabbar_text_length);
        }
    });
    // 页面组件封面图片上传初始化
    WST.upload({
        pick:'#page-picker',
        formData: {dir:'custompagedecoration'},
        accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
        callback:function(f){
            var json = WST.toAdminJson(f);
            if(json.status==1){
                $('#uploadMsg').empty().hide();
                var img_url = json.savePath+json.thumb; //保存到数据库的路径
                $("#page-picker").parent().attr("img_url",img_url);
                $('#page-picker').find('.page-poster').attr('src',WST.conf.RESOURCE_PATH+'/'+img_url);
                $('#page-picker').find('.page-poster').show();
                $('#page-picker').find('.page-poster-default').hide();
            }else{
                WST.msg(json.msg,{icon:2});
            }
        },
        progress:function(rate){
            $('#uploadMsg').show().html('已上传'+rate+"%");
        }
    });

    // swiper组件图片上传初始化
    $(".banner-picker").each(function(idx,item){
        var swiper_id_selector = "#"+$(item).attr('id');
        var swiper_num = $(item).parent().parent().parent().attr("swiper_num");
        WST.upload({
            pick:swiper_id_selector,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    var img_src = WST.conf.RESOURCE_PATH+'/'+$(item).parent().attr("img_url");
                    $(swiper_id_selector).parent().attr('img_url',img_url);
                    $(swiper_id_selector).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+img_url);
                    $(".effect-media-swiper").each(function(idx, item) {
                        if(parseInt($(item).attr("swiper_num")) == swiper_num){
                            if($(item).find("img").attr("src") == img_src){
                                $(item).find("img").attr("src",WST.conf.RESOURCE_PATH+'/'+img_url);
                            }
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
    });

    // nav组件图片上传初始化
    $(".nav-picker").each(function(idx,item){
        var nav_id_selector = "#"+$(item).attr('id');
        var nav_num = $(item).parent().parent().parent().attr("nav_num");
        var nav_item = $(item).parent().parent().attr("nav_item");
        WST.upload({
            pick:nav_id_selector,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $(nav_id_selector).parent().attr('img_url',img_url);
                    $(nav_id_selector).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+img_url);
                    $(".effect-nav").each(function(idx,item){
                        if(parseInt($(item).attr("nav_num")) == nav_num){
                            $(item).find(".navs").each(function(key, value) {
                                if($(value).attr("nav_item") == nav_item){
                                    $(value).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+img_url);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
    });

    // 单图组组件图片上传初始化
    $(".image-picker").each(function(idx,item){
        var image_id_selector = "#"+$(item).attr('id');
        var image_num = $(item).parent().parent().parent().attr("image_num");
        var image_item = $(item).parent().attr("image_item");
        WST.upload({
            pick:image_id_selector,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $(image_id_selector).parent().attr('img_url',img_url);
                    $(image_id_selector).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+img_url);
                    $(".effect-image").each(function(idx,item){
                        if(parseInt($(item).attr("image_num")) == image_num){
                            $(item).find('img').each(function(key,value){
                                if(parseInt($(value).attr('image_item')) == image_item){
                                    $(value).attr('src',WST.conf.RESOURCE_PATH+'/'+img_url);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
    });

    // 图片橱窗组件图片上传初始化
    $(".shopwindow-picker").each(function(idx,item){
        var shopwindow_id_selector = "#"+$(item).attr('id');
        var shopwindow_num = $(item).parent().parent().parent().attr("shopwindow_num");
        var shopwindow_item = $(item).parent().attr("shopwindow_item");
        WST.upload({
            pick:shopwindow_id_selector,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $(shopwindow_id_selector).parent().attr('img_url',img_url);
                    $(shopwindow_id_selector).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+img_url);
                    $(".effect-shopwindow").each(function(idx,item){
                        if(parseInt($(item).attr("shopwindow_num")) == shopwindow_num){
                            $(item).find('img').each(function(key,value){
                                if(parseInt($(value).attr('shopwindow_item')) == shopwindow_item){
                                    $(value).attr('src',WST.conf.RESOURCE_PATH+'/'+img_url);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
    });

    // 公告组件图片上传初始化
    $(".notice-picker").each(function(idx,item) {
        var notice_id_selector = "#" + $(item).attr('id');
        var notice_num = $(item).parent().parent().attr("notice_num");
        WST.upload({
            pick:notice_id_selector,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#notice-picker-"+notice_num).parent().parent().find('.notice-img').val(img_url);
                    $("#notice-picker-"+notice_num).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-notice").each(function(idx,item){
                        if(parseInt($(item).attr("notice_num")) == notice_num){
                            $(item).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
    });

    // 视频组件图片上传初始化
    $(".video-picker").each(function(idx,item){
        var video_id_selector = "#"+$(item).attr('id');
        var video_num = $(item).parent().parent().parent().attr("video_num");
        WST.upload({
            pick:video_id_selector,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $(video_id_selector).parent().attr('img_url',img_url);
                    $(video_id_selector).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+img_url);
                    $(".effect-video").each(function(idx,item){
                        if(parseInt($(item).attr("video_num")) == video_num){
                            $(item).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+img_url);
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
    });

    // 图文列表组件图片上传初始化
    $(".image-text-picker").each(function(idx,item) {
        var image_text_id_selector = "#" + $(item).attr('id');
        var image_text_num = $(item).parent().parent().parent().parent().parent().attr("image_text_num");
        var image_text_class_name = 'image_text'+image_text_num;
        var image_text_img_num = parseInt(image_text_id_selector.substr(parseInt(image_text_id_selector.lastIndexOf("\-"))+1,image_text_id_selector.length));
        WST.upload({
            pick:image_text_id_selector,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $(image_text_id_selector).parent().parent().attr('img_url',img_url);
                    $(image_text_id_selector).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-image-text").each(function(idx,item){
                        if ($(item).hasClass(image_text_class_name)) {
                            $(item).find('.image-text-imgs').each(function(key,value){
                                $(value).find('img').each(function(key2,value2){
                                    if(image_text_img_num==1 && key2==0){
                                        $(value2).attr('src', WST.conf.RESOURCE_PATH + '/' + json.savePath + json.thumb);
                                    }
                                    if(image_text_img_num==2 && key2==1){
                                        $(value2).attr('src', WST.conf.RESOURCE_PATH + '/' + json.savePath + json.thumb);
                                    }
                                    if(image_text_img_num==3 && key2==2){
                                        $(value2).attr('src', WST.conf.RESOURCE_PATH + '/' + json.savePath + json.thumb);
                                    }
                                });
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
    });

    // 底部导航栏组件图片上传初始化
    $(".tabbar-picker").each(function(idx,item){
        var tabbar_id_selector = "#"+$(item).attr('id');
        var tabbar_item = $(item).parent().parent().attr("tabbar_item");
        WST.upload({
            pick:tabbar_id_selector,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $(tabbar_id_selector).parent().attr('img_url',img_url);
                    $(tabbar_id_selector).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-tabbar-content").find(".tabbars").each(function(idx, item) {
                        if($(item).attr("tabbar_item") == tabbar_item){
                            $(item).find("img").attr("src",WST.conf.RESOURCE_PATH+'/'+img_url);
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
    });
    $(".tabbar-select-picker").each(function(idx,item){
        var tabbar_select_id_selector = "#"+$(item).attr('id');
        WST.upload({
            pick:tabbar_select_id_selector,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $(tabbar_select_id_selector).parent().attr('img_url',img_url);
                    $(tabbar_select_id_selector).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
    });

    // 颜色拾取器初始化开始
    $('.goods-group-select-bg-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var goods_group_num = $(el).parent().parent().attr("goods_group_num");
                $(".effect-goods-group").each(function(idx, item) {
                    if($(item).hasClass("goods_group"+goods_group_num)){
                        $(item).css('background','#'+hex);
                        $(el).parent().parent().find(".goods-group-bg-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.swiper-indicator-select-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var swiper_num = $(el).parent().parent().attr("swiper_num");
                $(".effect-media-swiper").each(function(idx, item) {
                    if($(item).hasClass("swiper"+swiper_num)){
                        $(item).find("span").css('background','#'+hex);
                        $(el).parent().parent().find(".indicator-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.nav-select-bg-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var nav_num = $(el).parent().parent().attr("nav_num");
                $(".effect-nav").each(function(idx, item) {
                    if($(item).hasClass("nav"+nav_num)){
                        $(item).css('background','#'+hex);
                        $(el).parent().parent().find(".nav-bg-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.nav-select-text-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var nav_num = $(el).parent().parent().parent().attr("nav_num");
                var nav_item = $(el).parent().parent().attr("nav_item");
                $(".effect-nav").each(function(idx, item) {
                    if($(item).hasClass("nav"+nav_num)){
                        $(item).find(".navs").each(function(key, value) {
                            if($(value).attr("nav_item") == nav_item){
                                $(value).find(".navs-info p").css('color','#'+hex);
                                $(el).parent().find(".attr-content-nav-text-color").val('#'+hex);
                            }
                        });
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.notice-select-bg-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var notice_num = $(el).parent().parent().attr("notice_num");
                $(".effect-notice").each(function(idx, item) {
                    if($(item).hasClass("notice"+notice_num)){
                        $(item).css('background','#'+hex);
                        $(el).parent().parent().find(".notice-bg-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.notice-select-text-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var notice_num = $(el).parent().parent().attr("notice_num");
                $(".effect-notice").each(function(idx, item) {
                    if($(item).hasClass("notice"+notice_num)){
                        $(item).find(".notice-info p").css('color','#'+hex);
                        $(el).parent().parent().find(".notice-text-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.coupon-select-bg-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var coupon_num = $(el).parent().parent().attr("coupon_num");
                $(".effect-coupon").each(function(idx, item) {
                    if($(item).hasClass("coupon"+coupon_num)){
                        $(item).css('background','#'+hex);
                        $(el).parent().parent().find(".coupon-bg-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.image-select-bg-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var image_num = $(el).parent().parent().attr("image_num");
                $(".effect-image").each(function(idx, item) {
                    if($(item).hasClass("image"+image_num)){
                        $(item).css('background','#'+hex);
                        $(el).parent().parent().find(".image-bg-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.shopwindow-select-bg-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var shopwindow_num = $(el).parent().parent().attr("shopwindow_num");
                $(".effect-shopwindow").each(function(idx, item) {
                    if($(item).hasClass("shopwindow"+shopwindow_num)){
                        $(item).css('background','#'+hex);
                        $(el).parent().parent().find(".shopwindow-bg-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.blank-select-bg-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var blank_num = $(el).parent().parent().parent().attr("blank_num");
                $(".effect-blank").each(function(idx, item) {
                    if($(item).hasClass("blank"+blank_num)){
                        $(item).css('background','#'+hex);
                        $(el).parent().parent().parent().find(".blank-bg-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.line-select-bg-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var line_num = $(el).parent().parent().parent().attr("line_num");
                $(".effect-line").each(function(idx, item) {
                    if($(item).hasClass("line"+line_num)){
                        $(item).css('background','#'+hex);
                        $(el).parent().parent().parent().find(".line-bg-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.line-select-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var line_num = $(el).parent().parent().parent().attr("line_num");
                $(".effect-line").each(function(idx, item) {
                    if($(item).hasClass("line"+line_num)){
                        $(item).find(".line").css('border-color','#'+hex);
                        $(el).parent().parent().parent().find(".line-border-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.text-select-bg-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var text_num = $(el).parent().parent().attr("text_num");
                $(".effect-text").each(function(idx, item) {
                    if($(item).hasClass("text"+text_num)){
                        $(item).find(".text").css('background','#'+hex);
                        $(el).parent().parent().parent().find(".text-bg-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.txt-select-bg-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var txt_num = $(el).parent().parent().attr("txt_num");
                $(".effect-txt").each(function(idx, item) {
                    if($(item).hasClass("txt"+txt_num)){
                        $(item).find(".txt-info").css('background','#'+hex);
                        $(el).parent().parent().find(".txt-bg-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.txt-select-text-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var txt_num = $(el).parent().parent().attr("txt_num");
                $(".effect-txt").each(function(idx, item) {
                    if($(item).hasClass("txt"+txt_num)){
                        $(item).find(".txt-info p").css('color','#'+hex);
                        $(el).parent().parent().find(".txt-text-color").val('#'+hex);
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.tabbar-select-bg-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                $(".effect-tabbar-content").css('background','#'+hex);
                $(el).parent().parent().find(".tabbar-bg-color").val('#'+hex);
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.tabbar-select-border-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                $(".effect-tabbar-content").css('border-top','1px solid #'+hex);
                $(el).parent().parent().find(".tabbar-border-color").val('#'+hex);
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.tabbar-select-text-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                $(".effect-tabbar-content").css('color','#'+hex);
                $(el).parent().parent().find(".tabbar-text-color").val('#'+hex);
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    $('.tabbar-select-text-checked-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                $(el).parent().parent().find(".tabbar-text-checked-color").val('#'+hex);
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
    // 颜色拾取器初始化结束

    // 滑块初始化开始
    $('.swiper-range-slider').jRange({
        from: 0,
        to: 50,
        step: 1,
        format: '%s',
        width: 100,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.notice-range-slider').jRange({
        from: 0,
        to: 50,
        step: 1,
        format: '%s',
        width: 120,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.coupon-padding-range-slider').jRange({
        from: 0,
        to: 50,
        step: 1,
        format: '%s',
        width: 120,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.coupon-counts-range-slider').jRange({
        from: 0,
        to: 6,
        step: 1,
        format: '%s',
        width: 120,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.image-range-slider').jRange({
        from: 0,
        to: 50,
        step: 1,
        format: '%s',
        width: 100,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.video-range-slider').jRange({
        from: 0,
        to: 50,
        step: 1,
        format: '%s',
        width: 120,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.blank-range-slider').jRange({
        from: 0,
        to: 200,
        step: 1,
        format: '%s',
        width: 120,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.line-height-range-slider').jRange({
        from: 1,
        to: 20,
        step: 1,
        format: '%s',
        width: 120,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.line-margin-range-slider').jRange({
        from: 0,
        to: 50,
        step: 1,
        format: '%s',
        width: 120,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.text-range-slider').jRange({
        from: 0,
        to: 50,
        step: 1,
        format: '%s',
        width: 120,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.txt-range-slider').jRange({
        from: 0,
        to: 50,
        step: 1,
        format: '%s',
        width: 120,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.image-text-range-slider').jRange({
        from: 0,
        to: 50,
        step: 1,
        format: '%s',
        width: 120,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    $('.marketing-range-slider').jRange({
        from: 0,
        to: 50,
        step: 1,
        format: '%s',
        width: 120,
        showLabels: false,
        isRange : true,
        showScale: false
    });
    // 滑块初始化结束

    // 富文本初始化开始
    var kindeditor_flag = false;
    $(".effect-text").each(function(idx, item) {
        kindeditor_flag = true;
        var text_num = $(item).attr("text_num");
        KindEditor.ready(function(K) {
            editor1 = K.create('.text-desc'+text_num, {
                height:'400px',
                width:'280px',
                uploadJson : WST.conf.ROOT+'/admin/index/editorUpload',
                allowFileManager : false,
                allowImageUpload : true,
                allowMediaUpload : false,
                items:[
                    'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
                    'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','multiimage','media','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                    'anchor', 'link', 'unlink', '|', 'about'
                ],
                afterBlur: function(){ this.sync(); },
                afterChange : function() {
                    var content = $(document.getElementsByTagName('iframe')[idx].contentWindow.document.body).html();
                    $(".effect-text").each(function(idx, item) {
                        if($(item).hasClass("text"+text_num)){
                            $(item).find(".text").html(content);
                        }
                    });
                }
            });
        });
    });
    if(kindeditor_flag == false){
        KindEditor.ready(function(K) {
            editor1 = K.create('.text-desc', {
                height:'400px',
                width:'280px',
                uploadJson : WST.conf.ROOT+'/admin/index/editorUpload',
                allowFileManager : false,
                allowImageUpload : true,
                allowMediaUpload : false,
                items:[
                    'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
                    'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','multiimage','media','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                    'anchor', 'link', 'unlink', '|', 'about'
                ],
                afterBlur: function(){ this.sync(); }
            });
        });
    }
    // 富文本初始化结束

    // 初始化底部导航栏文字样式开始
    $(".effect-tabbar-content").find(".tabbars").each(function(idx, item) {
        var tabbar_text = $(item).find(".tabbars-info p").html();
        if(tabbar_text.length>4){
            tabbar_text = tabbar_text.substr(0,4)+'...';
        }
        $(item).find(".tabbars-info p").html(tabbar_text);
    });
    // 初始化底部导航栏文字样式结束

    // 页面设置组件
    $(".effect-title").click(function(event){
        componentRemoveClasses(1);
        hideComponentAttrContent(1);
        $(".effect-title").addClass("effect-active");
        $(".effect-title").addClass("select");
        $(".attr-title").html("页面设置");
        $(".attr-content-page").show();
    });

    // 轮播组件
    $(".component-media-swiper").click(function(event){
        componentRemoveClasses(1);
        var swiper_num = 0;
        var swiper_num_arr = [];
        $(".effect-media-swiper").each(function(idx, item) {
            swiper_num = parseInt($(item).attr('swiper_num'));
            swiper_num_arr.push(swiper_num);
        });
        if(swiper_num_arr.length>0){
            swiper_num = Math.max.apply(null,swiper_num_arr)+1;
        }else{
            swiper_num = 1;
        }
        var swiper_class_name = "swiper"+swiper_num;
        var swiper_attr_class_name = "swiper_attr"+swiper_num;
        $(".effect-content").append("<div class='el-relative'><div class='effect-media-swiper "+swiper_class_name+"' swiper_num="+swiper_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='swiper'/><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' alt=''/><div class='dots round'><span class='roundness'></span><span class='roundness'></span></div><div class='btn-del' onclick='delSwiperBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addSwiperClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("图片轮播");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-swiper "+swiper_attr_class_name+"' swiper_num="+swiper_num+"><div class='attr-content-swiper-item' ><label class='d-label'>指示点形状</label><input type='radio' value='1' name='swiper-indicator"+swiper_num+"' id='i-rectangle"+swiper_num+"' onclick='changeSwiperIndicator(this)'  /><label for='i-rectangle"+swiper_num+"' class='d-a-label'>长方形</label><input type='radio' value='2' name='swiper-indicator"+swiper_num+"' id='i-square"+swiper_num+"' onclick='changeSwiperIndicator(this)'  /><label for='i-square"+swiper_num+"' class='d-a-label'>正方形</label><input type='radio' value='3' name='swiper-indicator"+swiper_num+"' id='i-roundness"+swiper_num+"' checked onclick='changeSwiperIndicator(this)'  /><label for='i-roundness"+swiper_num+"' class='d-a-label'>圆形</label></div><div class='attr-content-swiper-item' ><label class='d-label'>轮播间隔</label><select name='swiper-inteval"+swiper_num+"' onchange='changeSwiperInterval(this)'><option value='3'>3s</option><option value='4'>4s</option><option value='5'>5s</option><option value='6'>6s</option></select></div><div class='attr-content-swiper-item'><label class='d-label'>上边距</label><div class='attr-content-swiper-item-content'><input type='hidden' class='swiper-range-slider'  value='0' onchange='changeSwiperPadding(this,1)'/><span class='swiper-padding-top-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-swiper-item'><label class='d-label'>下边距</label><div class='attr-content-swiper-item-content'><input type='hidden' class='swiper-range-slider'  value='0' onchange='changeSwiperPadding(this,2)'/><span class='swiper-padding-bottom-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-swiper-item'><label class='d-label'>左边距</label><div class='attr-content-swiper-item-content'><input type='hidden' class='swiper-range-slider'  value='0' onchange='changeSwiperPadding(this,3)'/><span class='swiper-padding-left-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-swiper-item'><label class='d-label'>右边距</label><div class='attr-content-swiper-item-content'><input type='hidden' class='swiper-range-slider'  value='0' onchange='changeSwiperPadding(this,4)'/><span class='swiper-padding-right-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-swiper-item' ><label class='d-label'>指示点颜色</label><div class='select-component-item-color swiper-indicator-select-color' ></div></div><div class='attr-content-swiper-item-img' ><div class='attr-content-swiper-img' img_url='upload/custompagedecoration/base/banner_01.jpg'><label class='d-label'>图片</label><div class='banner-picker' id='banner-picker-"+swiper_num+"-1'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg'/></div><div class='image-tips'>建议宽度750px，如尺寸不达标，图片将自适应显示</div></div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-swiper-link' /><div class='attr-btn-del' onclick='delSwiperItemBtn(this)'>X</div></div><div class='attr-content-swiper-item-add' onclick='addSwiperItem(this)'>添加一个</div><input type='hidden' class='indicator-type' value=3 /><input type='hidden' class='indicator-color' value='' /><input type='hidden' class='interval' value='3' /><input type='hidden' class='swiper-padding-top' value='0'><input type='hidden' class='swiper-padding-bottom' value='0'><input type='hidden' class='swiper-padding-left' value='0'><input type='hidden' class='swiper-padding-right' value='0'></div>");
        WST.upload({
            pick:'#banner-picker-'+swiper_num+'-1',
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#banner-picker-"+swiper_num+"-1").parent().attr('img_url',img_url);
                    $("#banner-picker-"+swiper_num+"-1").find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-media-swiper").each(function(idx,item){
                       if($(item).hasClass(swiper_class_name)){
                           $(item).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                       }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        $('.swiper-indicator-select-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var swiper_num = $(el).parent().parent().attr("swiper_num");
                    $(".effect-media-swiper").each(function(idx, item) {
                        if($(item).hasClass("swiper"+swiper_num)){
                            $(item).find("span").css('background','#'+hex);
                            $(el).parent().parent().find(".indicator-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $('.swiper-range-slider').jRange({
            from: 0,
            to: 50,
            step: 1,
            format: '%s',
            width: 100,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $(".attr-content-swiper").each(function(idx, item) {
            if($(item).hasClass("swiper_attr"+swiper_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 商品组组件
    $(".component-goods-group").click(function(event){
        var hidden_cats_id = $('.hidden-cats-id').val();
        componentRemoveClasses(1);
        var goods_group_num = 0;
        var goods_group_num_arr = [];
        $(".effect-goods-group").each(function(idx, item) {
            goods_group_num = parseInt($(item).attr('goods_group_num'));
            goods_group_num_arr.push(goods_group_num);
        });
        if(goods_group_num_arr.length>0){
            goods_group_num = Math.max.apply(null,goods_group_num_arr)+1;
        }else{
            goods_group_num = 1;
        }
        var goods_group_class_name = "goods_group"+goods_group_num;
        var goods_group_attr_class_name = "goods_group_attr"+goods_group_num;
        $(".effect-content").append("<div class='el-relative'><div class='interval'></div><div class='effect-goods-group "+goods_group_class_name+"' goods_group_num="+goods_group_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='goods_group'/><div class='wst-flex-row wst-jc wst-ac columns-title'><div columns_item='1'>列表名称1</div><div columns_item='2'>列表名称2</div><div columns_item='3'>列表名称3</div></div><div class='wst-flex-row'><div class='goods'><img src='"+base_url+"/upload/custompagedecoration/base/good_01.jpg' alt=''/><div class='goods-info'><div><p class='goods-name'>此处显示商品名称</p><p class='goods-price red'>￥99.00</p><div class='wst-flex-row wst-jsb'><p class='goods-praise-rate'>100%</p><p class='goods-sale-num'>成交数:99</p></div></div></div></div><div class='goods'><img src='"+base_url+"/upload/custompagedecoration/base/good_02.jpg' alt=''/><div class='goods-info'><div><p class='goods-name'>此处显示商品名称</p><p class='goods-price red'>￥99.00</p><div class='wst-flex-row wst-jsb'><p class='goods-praise-rate'>100%</p><p class='goods-sale-num'>成交数:99</p></div></div></div></div></div><div class='btn-del' onclick='delGoodsGroupBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addGoodsGroupClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("商品组");
        hideComponentAttrContent(1);
        var goodsCatsHtml = $('.goodsCatsHtml').html();
        $(".attr-content").append("<div class='attr-content-goods-group "+goods_group_attr_class_name+"' goods_group_num="+goods_group_num+"><div class='attr-content-goods-group-item' ><label class='d-label d-label-g'>显示内容</label><div class='wst-flex-row wst-fw display-goods-attr'><input type='checkbox' value='' checked onclick='toggleShowGoodsName(this)'  id='goods-name'/><label for='goods-name'>商品名称</label><input type='checkbox' value='' checked onclick='toggleShowGoodsPrice(this)' id='goods-price'/><label for='goods-price'>商品价格</label><input type='checkbox' value='' checked onclick='toggleShowPraiseRate(this)' id='praise-rate' /><label for='praise-rate'>好评率</label><input type='checkbox' value='' checked onclick='toggleShowSaleNum(this)' id='sale-num'/><label for='sale-num'>销量</label></div></div><div class='attr-content-goods-group-item' ><label class='d-label'>显示布局</label><input type='radio' value='1' name='goods-group-columns"+goods_group_num+"' id='one-columns"+goods_group_num+"' onclick='changeGoodsGroupColumns(this)' /><label for='one-columns"+goods_group_num+"'>列表式</label><input type='radio' value='2' name='goods-group-columns"+goods_group_num+"' checked id='two-columns"+goods_group_num+"' onclick='changeGoodsGroupColumns(this)' /><label for='two-columns"+goods_group_num+"'>橱窗式</label><input type='radio' value='3' name='goods-group-columns"+goods_group_num+"' id='three-columns"+goods_group_num+"' onclick='changeGoodsGroupColumns(this)' /><label for='three-columns"+goods_group_num+"'>海报式</label></div><div class='attr-content-goods-group-item' ><label class='d-label'>商品数量</label><input type='radio' value='4' name='goods_num"+goods_group_num+"' id='goods-num-four"+goods_group_num+"' onclick='changeGoodsNum(this)' /><label for='goods-num-four"+goods_group_num+"'>4个</label><input type='radio' value='6' name='goods_num"+goods_group_num+"' id='goods-num-six"+goods_group_num+"' checked onclick='changeGoodsNum(this)' /><label for='goods-num-six"+goods_group_num+"'>6个</label><input type='radio' value='8' name='goods_num"+goods_group_num+"' id='goods-num-eight"+goods_group_num+"' onclick='changeGoodsNum(this)'/><label for='goods-num-eight"+goods_group_num+"'>8个</label></div><div class='goods-num-tips'>商品数量在选取商品为条件选取才有效</div><div class='attr-content-goods-group-item' ><label class='d-label'>列表名称</label><input type='radio' value='1' name='columns_title_is_show"+goods_group_num+"' id='columns-title-show"+goods_group_num+"' onclick='changeColumnsTitleDisplay(this)' checked/><label for='columns-title-show"+goods_group_num+"'>显示</label><input type='radio' value='0' name='columns_title_is_show"+goods_group_num+"' id='columns-title-hide"+goods_group_num+"' onclick='changeColumnsTitleDisplay(this)' /><label for='columns-title-hide"+goods_group_num+"'>隐藏</label></div><div class='attr-content-goods-group-item' ><label class='d-label'>商品排序</label><select name='goods-group-order"+goods_group_num+"' onchange='changeGoodsGroupOrder(this)'><option value='1' selected>销量从高到低</option><option value='2' >销量从低到高</option><option value='3' >价格从高到低</option><option value='4' >价格从低到高</option><option value='5' >更新时间由近到远</option><option value='6' >更新时间由远到近</option><option value='7' >商品排序由大到小</option><option value='8' >商品排序由小到大</option></select></div><div class='attr-content-goods-group-item-column' ><div class='wst-flex-row wst-ac'><label class='d-label'>列表名称</label><input type='text' value='列表名称1' class='goods-group-columns-name' columns_item='1' onkeyup='setColumnsTitle(this)' style='width:150px !important;' /><span class='goods-group-columns-name-tip'><span class='goods-group-columns-name-length'>5</span>/6</span></div><label class='d-label'>选取商品</label><input type='radio' value='1' name='goods-select"+goods_group_num+"-1' id='goods-select-condition"+goods_group_num+"-1' onclick='changeGoodsSelect(this)' columns_item='1' checked /><label for='goods-select-condition"+goods_group_num+"-1'>条件选取</label><input type='radio' value='2' name='goods-select"+goods_group_num+"-1' id='goods-select-manual"+goods_group_num+"-1' onclick='changeGoodsSelect(this)' columns_item='1' /><label for='goods-select-manual"+goods_group_num+"-1'>手动添加</label><input type='hidden' class='goods-select-ids-value' value='' columns_item='1'><input type='hidden' class='goods-select-cats-id-value' value="+hidden_cats_id+" columns_item='1'><input type='hidden' class='goods-tag-value' value='' columns_item='1'/><input type='hidden' class='goods-min-price-value' value='' columns_item='1'/><input type='hidden' class='goods-max-price-value' value='' columns_item='1'/><div class='attr-btn-del' onclick='delGoodsColumnItemBtn(this)'>X</div></div><div class='attr-content-goods-group-item-column' ><div class='wst-flex-row wst-ac'><label class='d-label'>列表名称</label><input type='text' value='列表名称2' class='goods-group-columns-name' columns_item='2' onkeyup='setColumnsTitle(this)' style='width:150px !important;' /><span class='goods-group-columns-name-tip'><span class='goods-group-columns-name-length'>5</span>/6</span></div><label class='d-label'>选取商品</label><input type='radio' value='1' name='goods-select"+goods_group_num+"-2' id='goods-select-condition"+goods_group_num+"-2' onclick='changeGoodsSelect(this)' columns_item='2' checked /><label for='goods-select-condition"+goods_group_num+"-2'>条件选取</label><input type='radio' value='2' name='goods-select"+goods_group_num+"-2' id='goods-select-manual"+goods_group_num+"-2' onclick='changeGoodsSelect(this)' columns_item='2' /><label for='goods-select-manual"+goods_group_num+"-2'>手动添加</label><input type='hidden' class='goods-select-ids-value' value='' columns_item='2'><input type='hidden' class='goods-select-cats-id-value' value="+hidden_cats_id+" columns_item='2'><input type='hidden' class='goods-tag-value' value='' columns_item='2'/><input type='hidden' class='goods-min-price-value' value='' columns_item='2'/><input type='hidden' class='goods-max-price-value' value='' columns_item='2'/><div class='attr-btn-del' onclick='delGoodsColumnItemBtn(this)'>X</div></div><div class='attr-content-goods-group-item-column' ><div class='wst-flex-row wst-ac'><label class='d-label'>列表名称</label><input type='text' value='列表名称3' class='goods-group-columns-name' columns_item='3' onkeyup='setColumnsTitle(this)' style='width:150px !important;' /><span class='goods-group-columns-name-tip'><span class='goods-group-columns-name-length'>5</span>/6</span></div><label class='d-label'>选取商品</label><input type='radio' value='1' name='goods-select"+goods_group_num+"-3' id='goods-select-condition"+goods_group_num+"-3' onclick='changeGoodsSelect(this)' columns_item='3' checked /><label for='goods-select-condition"+goods_group_num+"-3'>条件选取</label><input type='radio' value='3' name='goods-select"+goods_group_num+"-3' id='goods-select-manual"+goods_group_num+"-3' onclick='changeGoodsSelect(this)' columns_item='3' /><label for='goods-select-manual"+goods_group_num+"-3'>手动添加</label><input type='hidden' class='goods-select-ids-value' value='' columns_item='3'><input type='hidden' class='goods-select-cats-id-value' value="+hidden_cats_id+" columns_item='3'><input type='hidden' class='goods-tag-value' value='' columns_item='3'/><input type='hidden' class='goods-min-price-value' value='' columns_item='3'/><input type='hidden' class='goods-max-price-value' value='' columns_item='3'/><div class='attr-btn-del' onclick='delGoodsColumnItemBtn(this)'>X</div></div><div class='attr-content-goods-group-item-column-add' onclick='addGoodsColumnItem(this)'>添加一个</div><input type='hidden' class='goods-group-bg-color' value='#ffffff' /><input type='hidden' class='show-goods-name' value=1 /><input type='hidden' class='show-goods-price' value=1 /><input type='hidden' class='show-praise-rate' value=1 /><input type='hidden' class='show-sale-num' value=1 /><input type='hidden' class='show-goods-columns' value=2 /><input type='hidden' class='goods-group-goods-nums' value=6 /><input type='hidden' class='show-columns-title' value='1'><input type='hidden' class='goods-group-order' value='1' /></div>");
        $('.goods-group-select-bg-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var goods_group_num = $(el).parent().parent().attr("goods_group_num");
                    $(".effect-goods-group").each(function(idx, item) {
                        if($(item).hasClass("goods_group"+goods_group_num)){
                            $(item).css('background','#'+hex);
                            $(el).parent().parent().find(".goods-group-bg-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $(".attr-content-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group_attr"+goods_group_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 导航组件
    $(".component-nav").click(function(event){
        componentRemoveClasses(1);
        var nav_num = 0;
        var nav_num_arr = [];
        $(".effect-nav").each(function(idx, item) {
            nav_num = parseInt($(item).attr('nav_num'));
            nav_num_arr.push(nav_num);
        });
        if(nav_num_arr.length>0){
            nav_num = Math.max.apply(null,nav_num_arr)+1;
        }else{
            nav_num = 1;
        }
        var nav_class_name = "nav"+nav_num;
        var nav_attr_class_name = "nav_attr"+nav_num;
        $(".effect-content").append("<div class='el-relative'><div class='interval'></div><div class='effect-nav "+nav_class_name+"' nav_num="+nav_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='nav'/><div class='navs' nav_item='1'><img class='nav-style-roundness' src='"+base_url+"/upload/custompagedecoration/base/nav_01.png' alt=''/><div class='navs-info'><p>按钮文字</p></div></div><div class='navs' nav_item='2'><img class='nav-style-roundness' src='"+base_url+"/upload/custompagedecoration/base/nav_02.png' alt=''/><div class='navs-info'><p>按钮文字</p></div></div><div class='navs' nav_item='3'><img class='nav-style-roundness' src='"+base_url+"/upload/custompagedecoration/base/nav_03.png' alt=''/><div class='navs-info'><p>按钮文字</p></div></div><div class='navs' nav_item='4'><img class='nav-style-roundness' src='"+base_url+"/upload/custompagedecoration/base/nav_04.png' alt=''/><div class='navs-info'><p>按钮文字</p></div></div><div class='btn-del' onclick='delNavBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addNavClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("导航组");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-nav "+nav_attr_class_name+"' nav_num="+nav_num+"><div class='attr-content-nav-item' ><label class='d-label'>背景颜色</label><div class='select-component-item-color nav-select-bg-color'></div></div><div class='attr-content-nav-item' ><label class='d-label'>每行数量</label><input type='radio' value='1' name='nav-count"+nav_num+"'  id='n-three' onclick='changeNavCount(this)' /><label for='n-three'>3个</label><input type='radio' value='2' name='nav-count"+nav_num+"' id='n-four' onclick='changeNavCount(this)' checked /><label for='n-four'>4个</label><input type='radio' value='3' name='nav-count"+nav_num+"' id='n-five' onclick='changeNavCount(this)' /><label for='n-five'>5个</label></div><div class='attr-content-nav-item'><label class='d-label'>样式选择</label><input type='radio' value='1' name='nav-style"+nav_num+"' id='n-square"+nav_num+"' onclick='changeNavStyle(this)' /><label for='n-square"+nav_num+"' class='d-a-label'>正方形</label><input type='radio' value='2' name='nav-style"+nav_num+"' id='n-roundness"+nav_num+"' onclick='changeNavStyle(this)' checked /><label for='n-roundness"+nav_num+"' class='d-a-label'>圆形</label></div><div class='attr-content-nav-item-group' nav_item='1'><div class='attr-content-nav-img' img_url='/upload/custompagedecoration/base/nav_01.png'><label class='d-label'>图片</label><div class='nav-picker' id='nav-picker-"+nav_num+"-1'><img src='"+base_url+"/upload/custompagedecoration/base/nav_01.png'/></div><div class='image-tips'>建议尺寸宽度40px，高度40px</div></div><div class='attr-content-nav-info'><label class='nav-label'>文字内容</label><input type='text' value='按钮文字' class='attr-content-nav-text' onkeyup='setNavItemTitle(this)' /><span class='nav-text-tip'><span class='nav-text-length'>4</span>/4</span></div><div class='attr-content-nav-info'><label class='nav-label'>文字颜色</label><div class='select-component-item-color nav-select-text-color' style='background:#666666;'></div><input type='hidden' class='attr-content-nav-text-color' value='#666666' /></div><div class='attr-content-nav-info'><label class='nav-label'>链接地址</label><input type='text' value='' class='attr-content-nav-link' /></div><div class='attr-btn-del' onclick='delNavItemBtn(this)'>X</div></div><div class='attr-content-nav-item-group' nav_item='2'><div class='attr-content-nav-img' img_url='/upload/custompagedecoration/base/nav_02.png'><label class='d-label'>图片</label><div class='nav-picker' id='nav-picker-"+nav_num+"-2' ><img src='"+base_url+"/upload/custompagedecoration/base/nav_02.png'/></div><div class='image-tips'>建议尺寸宽度40px，高度40px</div></div><div class='attr-content-nav-info'><label class='nav-label'>文字内容</label><input type='text' value='按钮文字' class='attr-content-nav-text' onkeyup='setNavItemTitle(this)' /><span class='nav-text-tip'><span class='nav-text-length'>4</span>/4</span></div><div class='attr-content-nav-info'><label class='nav-label'>文字颜色</label><div class='select-component-item-color nav-select-text-color' style='background:#666666;'></div><input type='hidden' class='attr-content-nav-text-color' value='#666666' /></div><div class='attr-content-nav-info'><label class='nav-label'>链接地址</label><input type='text' value='' class='attr-content-nav-link' /></div><div class='attr-btn-del' onclick='delNavItemBtn(this)'>X</div></div><div class='attr-content-nav-item-group' nav_item='3'><div class='attr-content-nav-img' img_url='/upload/custompagedecoration/base/nav_03.png'><label class='d-label'>图片</label><div class='nav-picker' id='nav-picker-"+nav_num+"-3' ><img src='"+base_url+"/upload/custompagedecoration/base/nav_03.png'/></div><div class='image-tips'>建议尺寸宽度40px，高度40px</div></div><div class='attr-content-nav-info'><label class='nav-label'>文字内容</label><input type='text' value='按钮文字' class='attr-content-nav-text' onkeyup='setNavItemTitle(this)' /><span class='nav-text-tip'><span class='nav-text-length'>4</span>/4</span></div><div class='attr-content-nav-info'><label class='nav-label'>文字颜色</label><div class='select-component-item-color nav-select-text-color' style='background:#666666;'></div><input type='hidden' class='attr-content-nav-text-color' value='#666666' /></div><div class='attr-content-nav-info'><label class='nav-label'>链接地址</label><input type='text' value='' class='attr-content-nav-link' /></div><div class='attr-btn-del' onclick='delNavItemBtn(this)'>X</div></div><div class='attr-content-nav-item-group' nav_item='4'><div class='attr-content-nav-img' img_url='/upload/custompagedecoration/base/nav_04.png'><label class='d-label'>图片</label><div class='nav-picker' id='nav-picker-"+nav_num+"-4'><img src='"+base_url+"/upload/custompagedecoration/base/nav_04.png'/></div><div class='image-tips'>建议尺寸宽度40px，高度40px</div></div><div class='attr-content-nav-info'><label class='nav-label'>文字内容</label><input type='text' value='按钮文字' class='attr-content-nav-text' onkeyup='setNavItemTitle(this)' /><span class='nav-text-tip'><span class='nav-text-length'>4</span>/4</span></div><div class='attr-content-nav-info'><label class='nav-label'>文字颜色</label><div class='select-component-item-color nav-select-text-color'  style='background:#666666;'></div><input type='hidden' class='attr-content-nav-text-color' value='#666666' /></div><div class='attr-content-nav-info'><label class='nav-label'>链接地址</label><input type='text' value='' class='attr-content-nav-link' /></div><div class='attr-btn-del' onclick='delNavItemBtn(this)'>X</div></div><div class='attr-content-nav-item-add' onclick='addNavItem(this)'>添加一个</div><input type='hidden' class='nav-bg-color' value='#ffffff' /><input type='hidden' class='nav-count' value=2 /><input type='hidden' class='nav-style' value=2 /></div>");
        // nav上传图片开始
        WST.upload({
            pick:'#nav-picker-'+nav_num+'-'+1,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#nav-picker-"+nav_num+"-"+1).parent().attr('img_url',img_url);
                    $("#nav-picker-"+nav_num+"-"+1).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-nav").each(function(idx,item){
                        if($(item).hasClass(nav_class_name)){
                            $(item).find(".navs").each(function(key, value) {
                                if($(value).attr("nav_item") == 1){
                                    $(value).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        WST.upload({
            pick:'#nav-picker-'+nav_num+'-'+2,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#nav-picker-"+nav_num+"-"+2).parent().attr('img_url',img_url);
                    $("#nav-picker-"+nav_num+"-"+2).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-nav").each(function(idx,item){
                        if($(item).hasClass(nav_class_name)){
                            $(item).find(".navs").each(function(key, value) {
                                if($(value).attr("nav_item") == 2){
                                    $(value).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        WST.upload({
            pick:'#nav-picker-'+nav_num+'-'+3,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#nav-picker-"+nav_num+"-"+3).parent().attr('img_url',img_url);
                    $("#nav-picker-"+nav_num+"-"+3).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-nav").each(function(idx,item){
                        if($(item).hasClass(nav_class_name)){
                            $(item).find(".navs").each(function(key, value) {
                                if($(value).attr("nav_item") == 3){
                                    $(value).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        WST.upload({
            pick:'#nav-picker-'+nav_num+'-'+4,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#nav-picker-"+nav_num+"-"+4).parent().attr('img_url',img_url);
                    $("#nav-picker-"+nav_num+"-"+4).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-nav").each(function(idx,item){
                        if($(item).hasClass(nav_class_name)){
                            $(item).find(".navs").each(function(key, value) {
                                if($(value).attr("nav_item") == 4){
                                    $(value).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        // nav上传图片结束
        $('.nav-select-bg-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var nav_num = $(el).parent().parent().attr("nav_num");
                    $(".effect-nav").each(function(idx, item) {
                        if($(item).hasClass("nav"+nav_num)){
                            $(item).css('background','#'+hex);
                            $(el).parent().parent().find(".nav-bg-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $('.nav-select-text-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var nav_num = $(el).parent().parent().parent().attr("nav_num");
                    var nav_item = $(el).parent().parent().attr("nav_item");
                    $(".effect-nav").each(function(idx, item) {
                        if($(item).hasClass("nav"+nav_num)){
                            $(item).find(".navs").each(function(key, value) {
                                if($(value).attr("nav_item") == nav_item){
                                    $(value).find(".navs-info p").css('color','#'+hex);
                                    $(el).parent().find(".attr-content-nav-text-color").val('#'+hex);
                                }
                            });
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $(".attr-content-nav").each(function(idx, item) {
            if($(item).hasClass("nav_attr"+nav_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 公告组件
    $(".component-notice").click(function(event){
        componentRemoveClasses(1);
        var notice_num = 0;
        $(".effect-notice").each(function(idx, item) {
            notice_num = parseInt($(item).attr('notice_num'));
        });
        notice_num += 1;
        if(notice_num > 1){
            WST.msg('公告组件只能添加一个!',{time:1000,anim: 6});
            return;
        }
        var notice_class_name = "notice"+notice_num;
        var notice_attr_class_name = "notice_attr"+notice_num;
        $(".effect-content").append("<div class='el-relative'><div class='interval'></div><div class='effect-notice "+notice_class_name+"' notice_num="+notice_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='notice'/><div class='notice-info'><img src='"+base_url+"/upload/custompagedecoration/base/notice.png' alt=''/><p>此处填写公告内容</p></div><div class='btn-del' onclick='delNoticeBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addNoticeClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("公告组");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-notice "+notice_attr_class_name+"' notice_num="+notice_num+"><div class='attr-content-notice-item'><label class='d-label'>上下边距</label><div class='attr-content-notice-item-content'><input type='hidden' class='notice-range-slider' value='0' onchange='changeNoticePadding(this)'/><span class='notice-vertical-padding-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-notice-item' ><label class='d-label'>背景颜色</label><div class='select-component-item-color notice-select-bg-color'></div></div><div class='attr-content-notice-item' ><label class='d-label'>文字颜色</label><div class='select-component-item-color notice-select-text-color' style='background:#000000;'></div></div><div class='attr-content-notice-item'><label class='d-label'>公告图标</label><div class='notice-picker' id='notice-picker-"+notice_num+"' onclick='addNoticeImg(this)'><img src='"+base_url+"/upload/custompagedecoration/base/notice.png'/></div></div><div class='attr-content-notice-item' ><label class='d-label'>滚动方向</label><input type='radio' value='1' name='notice-direction' id='notice-direction-vertical' onclick='changeNoticeDirection(this)' checked /><label for='notice-direction-vertical' class='d-a-label'>纵向</label><input type='radio' value='2' name='notice-direction' id='notice-direction-horizontal' onclick='changeNoticeDirection(this)'   /><label for='notice-direction-horizontal' class='d-a-label'>横向</label></div><div class='attr-content-notice-item-group' ><div><label class='d-label'>公告</label><input type='text' value='此处填写公告内容' class='attr-content-notice-text'  onkeyup='setNoticeText(this)'/></div><div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-notice-link' /></div><div class='attr-btn-del' onclick='delNoticeItemBtn(this)'>X</div></div><div class='attr-content-notice-item-group' ><div><label class='d-label'>公告</label><input type='text' value='此处填写公告内容' class='attr-content-notice-text' onkeyup='setNoticeText(this)' /></div><div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-notice-link' /></div><div class='attr-btn-del' onclick='delNoticeItemBtn(this)'>X</div></div><div class='attr-content-notice-item-group-add' onclick='addNoticeItem(this)'>添加一个</div><input type='hidden' class='notice-bg-color' value='#ffffff' /><input type='hidden' class='notice-text-color' value='#000000' /><input type='hidden' class='notice-img' value='/upload/custompagedecoration/base/notice.png' /><input type='hidden' class='notice-vertical-padding' value='0'><input type='hidden' class='notice-direction' value='1' /></div>");
        WST.upload({
            pick:'#notice-picker-'+notice_num,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#notice-picker-"+notice_num).parent().parent().find('.notice-img').val(img_url);
                    $("#notice-picker-"+notice_num).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-notice").each(function(idx,item){
                        if($(item).hasClass(notice_class_name)){
                            $(item).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        $('.notice-select-bg-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var notice_num = $(el).parent().parent().attr("notice_num");
                    $(".effect-notice").each(function(idx, item) {
                        if($(item).hasClass("notice"+notice_num)){
                            $(item).css('background','#'+hex);
                            $(el).parent().parent().find(".notice-bg-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $('.notice-select-text-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var notice_num = $(el).parent().parent().attr("notice_num");
                    $(".effect-notice").each(function(idx, item) {
                        if($(item).hasClass("notice"+notice_num)){
                            $(item).find(".notice-info p").css('color','#'+hex);
                            $(el).parent().parent().find(".notice-text-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $('.notice-range-slider').jRange({
            from: 0,
            to: 50,
            step: 1,
            format: '%s',
            width: 120,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $(".attr-content-notice").each(function(idx, item) {
            if($(item).hasClass("notice_attr"+notice_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 搜索框组件
    $(".component-search").click(function(){
        componentRemoveClasses(1);
        var search_num = 0;
        $(".effect-search").each(function(idx, item) {
            search_num = parseInt($(item).attr('search_num'));
        });
        search_num += 1;
        if(search_num > 1){
            WST.msg('搜索组件只能添加一个!',{time:1000,anim: 6});
            return;
        }
        var search_class_name = "search"+search_num;
        var search_attr_class_name = "search_attr"+search_num;
        $(".effect-content").append("<div class='el-relative'><div class='interval'></div><div class='effect-search "+search_class_name+"' search_num="+search_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='search'/><div class='search-info'><i class='fa fa-search'></i><p>请输入关键字进行搜索</p></div><div class='btn-del' onclick='delSearchBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addSearchClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("搜索框");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-search "+search_attr_class_name+"' search_num="+search_num+" ><div class='attr-content-search-item'><div class='attr-content-search-item-title'><label class='d-label'>提示文字</label></div><div class='attr-content-search-item-content'><input type='text' value='请输入关键字进行搜索' class='attr-content-search-text' onkeyup='setSearchText(this)' /></div></div><div class='attr-content-search-item'><div class='attr-content-search-item-title'><label class='d-label'>搜索框样式</label></div><div class='attr-content-search-item-content'><input type='radio' value='1' checked name='search-class"+search_num+"'  id='s-square' onclick='changeSearchClass(this)' /><label for='s-square'>方形</label><input type='radio' value='2' name='search-class"+search_num+"' id='s-circular' onclick='changeSearchClass(this)' /><label for='s-circular'>圆角</label><input type='radio' value='3' name='search-class"+search_num+"' id='s-arc' onclick='changeSearchClass(this)' /><label for='s-arc'>圆弧</label></div></div><div class='attr-content-search-item'><div class='attr-content-search-item-title'><label class='d-label'>文字对齐</label></div><div class='attr-content-search-item-content'><input type='radio' value='1' checked name='search-text-alignment"+search_num+"'  id='s-left' onclick='changeSearchTextAlignment(this)' /><label for='s-left'>居左</label><input type='radio' value='2' name='search-text-alignment"+search_num+"' id='s-center' onclick='changeSearchTextAlignment(this)' /><label for='s-center'>居中</label><input type='radio' value='3' name='search-text-alignment"+search_num+"' id='s-right' onclick='changeSearchTextAlignment(this)' /><label for='s-right'>居右</label></div></div><input type='hidden' class='attr-content-search-class' value='1' /><input type='hidden' class='attr-content-search-text-alignment' value='1' /></div>");
        $(".attr-content-search").each(function(idx, item) {
            if($(item).hasClass("search_attr"+search_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 优惠券组件
    $(".component-coupon").click(function(event){
        componentRemoveClasses(1);
        var coupon_num = 0;
        var coupon_num_arr = [];
        $(".effect-coupon").each(function(idx, item) {
            coupon_num = parseInt($(item).attr('coupon_num'));
            coupon_num_arr.push(coupon_num);
        });
        if(coupon_num_arr.length>0){
            coupon_num = Math.max.apply(null,coupon_num_arr)+1;
        }else{
            coupon_num = 1;
        }
        var coupon_class_name = "coupon"+coupon_num;
        var coupon_attr_class_name = "coupon_attr"+coupon_num;
        var couponHtml1 = $('.couponHtml1').html();
        var couponHtml2 = $('.couponHtml2').html();
        $(".effect-content").append("<div class='el-relative'><div class='interval'></div><div class='effect-coupon "+coupon_class_name+"' coupon_num="+coupon_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='coupon'/><div class='coupons'>"+couponHtml1+"</div><div class='btn-del' onclick='delCouponBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addCouponClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("优惠券组");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-coupon "+coupon_attr_class_name+"' coupon_num="+coupon_num+"><div class='attr-content-coupon-item'><label class='d-label'>优惠券样式</label><input type='radio' value='1' name='coupon-style"+coupon_num+"'  id='c-style-one"+coupon_num+"' onclick='changeCouponStyle(this)'  checked /><label for='c-style-one"+coupon_num+"'>样式1</label><input type='radio' value='2' name='coupon-style"+coupon_num+"' id='c-style-two"+coupon_num+"' onclick='changeCouponStyle(this)' /><label for='c-style-two"+coupon_num+"'>样式2</label></div><div class='attr-content-coupon-item'><label class='d-label'>上下边距</label><div class='attr-content-coupon-item-content'><input type='hidden' class='coupon-padding-range-slider'  value='0' onchange='changeCouponPadding(this)'/><span class='coupon-vertical-padding-value'>10</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-coupon-item' ><label class='d-label'>背景颜色</label><div class='select-component-item-color coupon-select-bg-color'></div></div><div class='attr-content-coupon-item'><label class='d-label'>添加优惠券</label><div class='add-coupon' onclick='addCoupons(this)'>+ 添加优惠券</div></div><div class='coupon-tips'>1.本组件最多支持显示<span class='coupon-nums'>3</span>张优惠券</div><div class='coupon-tips'>2.保存页面后，优惠券的数据才会更新</div><input type='hidden' class='coupon-vertical-padding' value='0'><input type='hidden' class='coupon-bg-color' value='#ffffff' /><input type='hidden' class='coupon-counts' value='2'><input type='hidden' class='coupon-style' value='1' /><input type='hidden' class='coupon-select-ids-value' value='' ></div>");
        $('.coupon-select-bg-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var coupon_num = $(el).parent().parent().attr("coupon_num");
                    $(".effect-coupon").each(function(idx, item) {
                        if($(item).hasClass("coupon"+coupon_num)){
                            $(item).css('background','#'+hex);
                            $(el).parent().parent().find(".coupon-bg-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $('.coupon-padding-range-slider').jRange({
            from: 0,
            to: 50,
            step: 1,
            format: '%s',
            width: 120,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $('.coupon-counts-range-slider').jRange({
            from: 0,
            to: 6,
            step: 1,
            format: '%s',
            width: 120,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $(".attr-content-coupon").each(function(idx, item) {
            if($(item).hasClass("coupon_attr"+coupon_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 单图组组件
    $(".component-image").click(function(event){
        componentRemoveClasses(1);
        var image_num = 0;
        var image_num_arr = [];
        $(".effect-image").each(function(idx, item) {
            image_num = parseInt($(item).attr('image_num'));
            image_num_arr.push(image_num);
        });
        if(image_num_arr.length>0){
            image_num = Math.max.apply(null,image_num_arr)+1;
        }else{
            image_num = 1;
        }
        var image_class_name = "image"+image_num;
        var image_attr_class_name = "image_attr"+image_num;
        $(".effect-content").append("<div class='el-relative'><div class='effect-image "+image_class_name+"' image_num="+image_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='image'/><div class='images'><div class='images-item'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' alt='' image_item='1'/></div><div class='images-item'><img src='"+base_url+"/upload/custompagedecoration/base/banner_02.jpg' alt='' image_item='2' /></div></div><div class='btn-del' onclick='delImageBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addImageClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("图片");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-image "+image_attr_class_name+"' image_num="+image_num+"><div class='attr-content-image-item'><label class='d-label'>上边距</label><div class='attr-content-image-item-content'><input type='hidden' class='image-range-slider'  value='0' onchange='changeImagePadding(this,1)'/><span class='image-padding-top-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-image-item'><label class='d-label'>下边距</label><div class='attr-content-image-item-content'><input type='hidden' class='image-range-slider'  value='0' onchange='changeImagePadding(this,2)'/><span class='image-padding-bottom-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-image-item'><label class='d-label'>左边距</label><div class='attr-content-image-item-content'><input type='hidden' class='image-range-slider'  value='0' onchange='changeImagePadding(this,3)'/><span class='image-padding-left-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-image-item'><label class='d-label'>右边距</label><div class='attr-content-image-item-content'><input type='hidden' class='image-range-slider'  value='0' onchange='changeImagePadding(this,4)'/><span class='image-padding-right-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-image-item' ><label class='d-label'>背景颜色</label><div class='select-component-item-color image-select-bg-color'></div></div><div class='attr-content-image-item-img' ><div class='attr-content-image-img' img_url='/upload/custompagedecoration/base/banner_01.jpg' image_item='1'><label class='d-label'>图片</label><div class='image-picker' id='image-picker-"+image_num+"-1' onclick='addImageItemImg(this)'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg'/></div><div class='image-tips'>手机端与微信端建议宽度750px，高度自适应。小程序端建议宽度750px，高度374px</div></div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-image-link' /><div class='attr-btn-del' onclick='delImageItemBtn(this)'>X</div></div><div class='attr-content-image-item-img' ><div class='attr-content-image-img' img_url='/upload/custompagedecoration/base/banner_02.jpg' image_item='2'><label class='d-label'>图片</label><div class='image-picker' id='image-picker-"+image_num+"-2' onclick='addImageItemImg(this)'><img src='"+base_url+"/upload/custompagedecoration/base/banner_02.jpg' /></div><div class='image-tips'>手机端与微信端建议宽度750px，高度自适应。小程序端建议宽度750px，高度374px</div></div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-image-link' /><div class='attr-btn-del' onclick='delImageItemBtn(this)'>X</div></div><div class='attr-content-image-item-add' onclick='addImageItem(this)'>添加一个</div><input type='hidden' class='image-padding-top' value='0'><input type='hidden' class='image-padding-bottom' value='0'><input type='hidden' class='image-padding-left' value='0'><input type='hidden' class='image-padding-right' value='0'><input type='hidden' class='image-bg-color' value='#ffffff' /></div>");
        WST.upload({
            pick:'#image-picker-'+image_num+'-1',
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#image-picker-"+image_num+"-1").parent().attr('img_url',img_url);
                    $("#image-picker-"+image_num+"-1").find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-image").each(function(idx,item){
                        if($(item).hasClass(image_class_name)){
                            $(item).find('img').each(function(idx,obj){
                                if(parseInt($(obj).attr('image_item')) == 1){
                                    $(obj).attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        WST.upload({
            pick:'#image-picker-'+image_num+'-2',
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#image-picker-"+image_num+"-2").parent().attr('img_url',img_url);
                    $("#image-picker-"+image_num+"-2").find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-image").each(function(idx,item){
                        if($(item).hasClass(image_class_name)){
                            $(item).find('img').each(function(idx,obj){
                                if(parseInt($(obj).attr('image_item')) == 2){
                                    $(obj).attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        $('.image-select-bg-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var image_num = $(el).parent().parent().attr("image_num");
                    $(".effect-image").each(function(idx, item) {
                        if($(item).hasClass("image"+image_num)){
                            $(item).css('background','#'+hex);
                            $(el).parent().parent().find(".image-bg-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $('.image-range-slider').jRange({
            from: 0,
            to: 50,
            step: 1,
            format: '%s',
            width: 100,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $(".attr-content-image").each(function(idx, item) {
            if($(item).hasClass("image_attr"+image_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 图片橱窗组件
    $(".component-shopwindow").click(function(event){
        componentRemoveClasses(1);
        var shopwindow_num = 0;
        var shopwindow_num_arr = [];
        $(".effect-shopwindow").each(function(idx, item) {
            shopwindow_num = parseInt($(item).attr('shopwindow_num'));
            shopwindow_num_arr.push(shopwindow_num);
        });
        if(shopwindow_num_arr.length>0){
            shopwindow_num = Math.max.apply(null,shopwindow_num_arr)+1;
        }else{
            shopwindow_num = 1;
        }
        var shopwindow_class_name = "shopwindow"+shopwindow_num;
        var shopwindow_attr_class_name = "shopwindow_attr"+shopwindow_num;
        $(".effect-content").append("<div class='el-relative'><div class='effect-shopwindow "+shopwindow_class_name+"' shopwindow_num="+shopwindow_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='shopwindow'/><div class='shopwindows'><div class='shopwindows-item' ><img src='"+base_url+"/upload/custompagedecoration/base/shopwindow_01.jpg' alt='' shopwindow_item='1'/></div><div class='shopwindows-item' ><img src='"+base_url+"/upload/custompagedecoration/base/shopwindow_02.jpg' alt='' shopwindow_item='2'/></div><div class='shopwindows-item' ><img src='"+base_url+"/upload/custompagedecoration/base/shopwindow_03.jpg' alt='' shopwindow_item='3'/></div><div class='shopwindows-item' ><img src='"+base_url+"/upload/custompagedecoration/base/shopwindow_04.jpg' alt='' shopwindow_item='4'/></div></div><div class='btn-del' onclick='delShopwindowBtn(this)'>删除</div><div class='shopwindows-layout none'><div class='s-layout'><div class='s-layout-left'><div class='s-layout-left-item' ></div></div><div class='s-layout-right'><div class='s-layout-top'><div class='s-layout-top-item' ></div></div><div class='s-layout-bottom'><div class='s-layout-bottom-item' ></div><div class='s-layout-bottom-item' ></div></div></div></div></div><div class='shopwindows-s-hide'></div></div><div class='border-absolute effect-active select' onclick='addShopwindowClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("图片橱窗");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-shopwindow "+shopwindow_attr_class_name+"' shopwindow_num="+shopwindow_num+"><div class='attr-content-shopwindow-item' ><label class='d-label'>背景颜色</label><div class='select-component-item-color shopwindow-select-bg-color'></div></div><div class='attr-content-shopwindow-item'><label class='d-label'>布局方式</label><div class='shopwindow-layout' style='width:150px;'><input type='radio' value='1' name='shopwindow-layout"+shopwindow_num+"' id='s-two"+shopwindow_num+"' onclick='changeShopwindowLayout(this)' checked /><label for='s-two"+shopwindow_num+"'>堆积两列</label><input type='radio' value='2' name='shopwindow-layout"+shopwindow_num+"' id='s-three"+shopwindow_num+"' onclick='changeShopwindowLayout(this)' /><label for='s-three"+shopwindow_num+"'>堆积三列</label><input type='radio' value='3' name='shopwindow-layout"+shopwindow_num+"' id='s-four"+shopwindow_num+"' onclick='changeShopwindowLayout(this)' /><label for='s-four"+shopwindow_num+"'>堆积四列</label><input type='radio' value='4' name='shopwindow-layout"+shopwindow_num+"' id='s-s"+shopwindow_num+"' onclick='changeShopwindowLayout(this)' /><label for='s-s"+shopwindow_num+"'>橱窗样式</label></div></div><div class='attr-content-shopwindow-item'><label class='d-label'>布局说明</label><div class='shopwindow-layout-info'>请确保所有图片的尺寸/比例相同</div></div><div class='attr-content-shopwindow-item-img' ><div class='attr-content-shopwindow-img' img_url='/upload/custompagedecoration/base/shopwindow_01.jpg' shopwindow_item='1'><label class='d-label'>图片</label><div class='shopwindow-picker' id='shopwindow-picker-"+shopwindow_num+"-1' onclick='addShopwindowItemImg(this)'><img src='"+base_url+"/upload/custompagedecoration/base/shopwindow_01.jpg'/></div></div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-shopwindow-link' /><div class='attr-btn-del' onclick='delShopwindowItemBtn(this)'>X</div></div><div class='attr-content-shopwindow-item-img' ><div class='attr-content-shopwindow-img' img_url='/upload/custompagedecoration/base/shopwindow_02.jpg' shopwindow_item='2'><label class='d-label'>图片</label><div class='shopwindow-picker' id='shopwindow-picker-"+shopwindow_num+"-2' onclick='addShopwindowItemImg(this)'><img src='"+base_url+"/upload/custompagedecoration/base/shopwindow_02.jpg'/></div></div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-shopwindow-link' /><div class='attr-btn-del' onclick='delShopwindowItemBtn(this)'>X</div></div><div class='attr-content-shopwindow-item-img' ><div class='attr-content-shopwindow-img' img_url='/upload/custompagedecoration/base/shopwindow_03.jpg' shopwindow_item='3'><label class='d-label'>图片</label><div class='shopwindow-picker' id='shopwindow-picker-"+shopwindow_num+"-3' onclick='addShopwindowItemImg(this)'><img src='"+base_url+"/upload/custompagedecoration/base/shopwindow_03.jpg'/></div></div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-shopwindow-link' /><div class='attr-btn-del' onclick='delShopwindowItemBtn(this)'>X</div></div><div class='attr-content-shopwindow-item-img' ><div class='attr-content-shopwindow-img' img_url='/upload/custompagedecoration/base/shopwindow_04.jpg' shopwindow_item='4'><label class='d-label'>图片</label><div class='shopwindow-picker' id='shopwindow-picker-"+shopwindow_num+"-4' onclick='addShopwindowItemImg(this)'><img src='"+base_url+"/upload/custompagedecoration/base/shopwindow_04.jpg'/></div></div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-shopwindow-link' /><div class='attr-btn-del' onclick='delShopwindowItemBtn(this)'>X</div></div><div class='attr-content-shopwindow-item-add' onclick='addShopwindowItem(this)'>添加一个</div><input type='hidden' class='shopwindow-bg-color' value='#ffffff' /><input type='hidden' class='shopwindow-layout-value' value='1' /></div>");
        WST.upload({
            pick:'#shopwindow-picker-'+shopwindow_num+'-1',
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#shopwindow-picker-"+shopwindow_num+"-1").parent().attr('img_url',img_url);
                    $("#shopwindow-picker-"+shopwindow_num+"-1").find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-shopwindow").each(function(idx,item){
                        if($(item).hasClass(shopwindow_class_name)){
                            $(item).find('img').each(function(idx,obj){
                                if(parseInt($(obj).attr('shopwindow_item')) == 1){
                                    $(obj).attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        WST.upload({
            pick:'#shopwindow-picker-'+shopwindow_num+'-2',
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#shopwindow-picker-"+shopwindow_num+"-2").parent().attr('img_url',img_url);
                    $("#shopwindow-picker-"+shopwindow_num+"-2").find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-shopwindow").each(function(idx,item){
                        if($(item).hasClass(shopwindow_class_name)){
                            $(item).find('img').each(function(idx,obj){
                                if(parseInt($(obj).attr('shopwindow_item')) == 2){
                                    $(obj).attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        WST.upload({
            pick:'#shopwindow-picker-'+shopwindow_num+'-3',
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#shopwindow-picker-"+shopwindow_num+"-3").parent().attr('img_url',img_url);
                    $("#shopwindow-picker-"+shopwindow_num+"-3").find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-shopwindow").each(function(idx,item){
                        if($(item).hasClass(shopwindow_class_name)){
                            $(item).find('img').each(function(idx,obj){
                                if(parseInt($(obj).attr('shopwindow_item')) == 3){
                                    $(obj).attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        WST.upload({
            pick:'#shopwindow-picker-'+shopwindow_num+'-4',
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#shopwindow-picker-"+shopwindow_num+"-4").parent().attr('img_url',img_url);
                    $("#shopwindow-picker-"+shopwindow_num+"-4").find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-shopwindow").each(function(idx,item){
                        if($(item).hasClass(shopwindow_class_name)){
                            $(item).find('img').each(function(idx,obj){
                                if(parseInt($(obj).attr('shopwindow_item')) == 4){
                                    $(obj).attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                                }
                            });
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });

        $('.shopwindow-select-bg-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var shopwindow_num = $(el).parent().parent().attr("shopwindow_num");
                    $(".effect-shopwindow").each(function(idx, item) {
                        if($(item).hasClass("shopwindow"+shopwindow_num)){
                            $(item).css('background','#'+hex);
                            $(el).parent().parent().find(".shopwindow-bg-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $(".attr-content-shopwindow").each(function(idx, item) {
            if($(item).hasClass("shopwindow_attr"+shopwindow_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 视频组件
    $(".component-video").click(function(event){
        componentRemoveClasses(1);
        var video_num = 0;
        var video_num_arr = [];
        $(".effect-video").each(function(idx, item) {
            video_num = parseInt($(item).attr('video_num'));
            video_num_arr.push(video_num);
        });
        if(video_num_arr.length>0){
            video_num = Math.max.apply(null,video_num_arr)+1;
        }else{
            video_num = 1;
        }
        var video_class_name = "video"+video_num;
        var video_attr_class_name = "video_attr"+video_num;
        $(".effect-content").append("<div class='el-relative'><div class='effect-video "+video_class_name+"' video_num="+video_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='video'/><img src='"+base_url+"/upload/custompagedecoration/base/video.png' alt=''/><div class='btn-del' onclick='delVideoBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addVideoClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("视频组");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-video "+video_attr_class_name+"' video_num="+video_num+" ><div class='attr-content-video-item'><label class='d-label'>上下边距</label><div class='attr-content-video-item-content'><input type='hidden' class='video-range-slider'  value='0' onchange='changeVideoMargin(this)'/><span class='video-vertical-padding-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-video-item'><div class='attr-content-video-img' img_url='/upload/custompagedecoration/base/video.png' ><label class='d-label'>视频封面</label><div class='video-picker' id='video-picker-"+video_num+"' onclick='addVideoItemImg(this)'><img src='"+base_url+"/upload/custompagedecoration/base/video.png'/></div></div></div><div class='attr-content-video-item'><label class='d-label'>视频地址</label><input type='text' value='' class='attr-content-video-link' /></div><input type='hidden' class='video-vertical-padding' value='0' /></div>");
        WST.upload({
            pick:'#video-picker-'+video_num,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $("#video-picker-"+video_num).parent().attr('img_url',img_url);
                    $("#video-picker-"+video_num).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-video").each(function(idx,item){
                        if($(item).hasClass(video_class_name)){
                            $(item).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        $('.video-range-slider').jRange({
            from: 0,
            to: 50,
            step: 1,
            format: '%s',
            width: 120,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $(".attr-content-video").each(function(idx, item) {
            if($(item).hasClass("video_attr"+video_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 辅助空白组件
    $(".component-blank").click(function(event){
        componentRemoveClasses(1);
        var blank_num = 0;
        var blank_num_arr = [];
        $(".effect-blank").each(function(idx, item) {
            blank_num = parseInt($(item).attr('blank_num'));
            blank_num_arr.push(blank_num);
        });
        if(blank_num_arr.length>0){
            blank_num = Math.max.apply(null,blank_num_arr)+1;
        }else{
            blank_num = 1;
        }
        var blank_class_name = "blank"+blank_num;
        var blank_attr_class_name = "blank_attr"+blank_num;
        $(".effect-content").append("<div class='el-relative'><div class='effect-blank "+blank_class_name+"' blank_num="+blank_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='blank'/><div class='btn-del' onclick='delBlankBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addBlankClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("辅助空白");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-blank "+blank_attr_class_name+"' blank_num="+blank_num+" ><div class='attr-content-blank-item'><label class='d-label'>组件高度</label><div class='attr-content-blank-item-content'><input type='hidden' class='blank-range-slider'  value='20' onchange='changeBlankHeight(this)'/><span class='blank-height-value'>20</span><span>px </span></div></div><div class='attr-content-blank-item' ><label class='d-label'>背景颜色</label><div class='attr-content-blank-item-content'><div class='select-component-item-color blank-select-bg-color'></div></div></div><input type='hidden' class='blank-bg-color' value='#ffffff' /><input type='hidden' class='blank-height' value='20'></div>");
        $('.blank-select-bg-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var blank_num = $(el).parent().parent().parent().attr("blank_num");
                    $(".effect-blank").each(function(idx, item) {
                        if($(item).hasClass("blank"+blank_num)){
                            $(item).css('background','#'+hex);
                            $(el).parent().parent().parent().find(".blank-bg-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $('.blank-range-slider').jRange({
            from: 0,
            to: 200,
            step: 1,
            format: '%s',
            width: 120,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $(".attr-content-blank").each(function(idx, item) {
            if($(item).hasClass("blank_attr"+blank_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 辅助线组件
    $(".component-line").click(function(event){
        componentRemoveClasses(1);
        var line_num = 0;
        var line_num_arr = [];
        $(".effect-line").each(function(idx, item) {
            line_num = parseInt($(item).attr('line_num'));
            line_num_arr.push(line_num);
        });
        if(line_num_arr.length>0){
            line_num = Math.max.apply(null,line_num_arr)+1;
        }else{
            line_num = 1;
        }
        var line_class_name = "line"+line_num;
        var line_attr_class_name = "line_attr"+line_num;
        $(".effect-content").append("<div class='el-relative'><div class='effect-line "+line_class_name+"' line_num="+line_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='line'/><div class='line'></div><div class='btn-del' onclick='delLineBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addLineClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("辅助线");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-line "+line_attr_class_name+"' line_num="+line_num+" ><div class='attr-content-line-item' ><label class='d-label'>背景颜色</label><div class='attr-content-line-item-content'><div class='select-component-item-color line-select-bg-color'></div></div></div><div class='attr-content-line-item'><label class='d-label'>线条样式</label><div class='attr-content-line-item-content'><input type='radio' value='1' name='line-class"+line_num+"' id='l-solid' onclick='changeLineClass(this)' checked /><label for='l-solid'>实线</label><input type='radio' value='2' name='line-class"+line_num+"' id='l-dashed' onclick='changeLineClass(this)' /><label for='l-dashed'>虚线</label><input type='radio' value='3' name='line-class"+line_num+"' id='l-dotted' onclick='changeLineClass(this)' /><label for='l-dotted'>点状</label></div></div><div class='attr-content-line-item' ><label class='d-label'>线条颜色</label><div class='attr-content-line-item-content'><div class='select-component-item-color line-select-color'></div></div></div><div class='attr-content-line-item'><label class='d-label'>线条高度</label><div class='attr-content-line-item-content'><input type='hidden' class='line-height-range-slider'  value='1' onchange='changeLineHeight(this)'/><span class='line-height-value'>1</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-line-item'><label class='d-label'>上下边距</label><div class='attr-content-line-item-content'><input type='hidden' class='line-margin-range-slider'  value='10' onchange='changeLineMargin(this)'/><span class='line-vertical-margin-value'>10</span><span>px (像素)</span></div></div><input type='hidden' class='line-bg-color' value='#ffffff' /><input type='hidden' class='line-class' value='1' /><input type='hidden' class='line-border-color' value='#000000' /><input type='hidden' class='line-height' value='1' /><input type='hidden' class='line-vertical-margin' value='10' /></div>");
        $('.line-select-bg-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var line_num = $(el).parent().parent().parent().attr("line_num");
                    $(".effect-line").each(function(idx, item) {
                        if($(item).hasClass("line"+line_num)){
                            $(item).css('background','#'+hex);
                            $(el).parent().parent().parent().find(".line-bg-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $('.line-select-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var line_num = $(el).parent().parent().parent().attr("line_num");
                    $(".effect-line").each(function(idx, item) {
                        if($(item).hasClass("line"+line_num)){
                            $(item).find(".line").css('border-color','#'+hex);
                            $(el).parent().parent().parent().find(".line-border-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $('.line-height-range-slider').jRange({
            from: 1,
            to: 20,
            step: 1,
            format: '%s',
            width: 120,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $('.line-margin-range-slider').jRange({
            from: 0,
            to: 50,
            step: 1,
            format: '%s',
            width: 120,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $(".attr-content-line").each(function(idx, item) {
            if($(item).hasClass("line_attr"+line_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 富文本组件
    $(".component-text").click(function(event){
        componentRemoveClasses(1);
        var text_num = 0;
        var text_num_arr = [];
        $(".effect-text").each(function(idx, item) {
            text_num = parseInt($(item).attr('text_num'));
            text_num_arr.push(text_num);
        });
        if(text_num_arr.length>0){
            text_num = Math.max.apply(null,text_num_arr)+1;
        }else{
            text_num = 1;
        }
        var text_class_name = "text"+text_num;
        var text_attr_class_name = "text_attr"+text_num;
        $(".effect-content").append("<div class='el-relative'><div class='effect-text "+text_class_name+"' text_num="+text_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='text'/><div class='text'>这里是富文本的内容</div><div class='btn-del' onclick='delTextBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addTextClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("富文本");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-text "+text_attr_class_name+"' text_num="+text_num+" ><div class='attr-content-text-item'><label class='d-label'>上下边距</label><div class='attr-content-text-item-content'><input type='hidden' class='text-range-slider'  value='0' onchange='changeTextPadding(this,1)'/><span class='text-vertical-padding-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-text-item'><label class='d-label'>左右边距</label><div class='attr-content-text-item-content'><input type='hidden' class='text-range-slider'  value='0' onchange='changeTextPadding(this,2)'/><span class='text-horizontal-padding-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-text-item' ><label class='d-label'>背景颜色</label><div class='select-component-item-color text-select-bg-color'></div></div><div class='attr-content-text-item'><textarea rows='2' cols='60' class='text-desc"+text_num+"'>这里是富文本的内容</textarea></div><input type='hidden' class='text-vertical-padding' value='0' /><input type='hidden' class='text-horizontal-padding' value='0' /><input type='hidden' class='text-bg-color' value='#ffffff' /></div>");
        $('.text-select-bg-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var text_num = $(el).parent().parent().attr("text_num");
                    $(".effect-text").each(function(idx, item) {
                        if($(item).hasClass("text"+text_num)){
                            $(item).find(".text").css('background','#'+hex);
                            $(el).parent().parent().parent().find(".text-bg-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        KindEditor.ready(function(K) {
            editor1 = K.create('.text-desc'+text_num, {
                height:'400px',
                width:'280px',
                uploadJson : WST.conf.ROOT+'/admin/index/editorUpload',
                allowFileManager : false,
                allowImageUpload : true,
                allowMediaUpload : false,
                items:[
                    'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
                    'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
                    'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                    'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
                    'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
                    'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|','image','multiimage','media','table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                    'anchor', 'link', 'unlink', '|', 'about'
                ],
                afterBlur: function(){ this.sync(); },
                afterChange : function() {
                    var content = "";
                    $(".attr-content-text").each(function(idx, item) {
                        if($(item).hasClass("text_attr"+text_num)){
                            content = $(document.getElementsByTagName('iframe')[idx].contentWindow.document.body).html();
                        }
                    });

                    $(".effect-text").each(function(idx, item) {
                        if($(item).hasClass("text"+text_num)){
                            $(item).find(".text").html(content);
                        }
                    });
                }
            });
        });
        $('.text-range-slider').jRange({
            from: 0,
            to: 50,
            step: 1,
            format: '%s',
            width: 120,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $(".attr-content-text").each(function(idx, item) {
            if($(item).hasClass("text_attr"+text_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 单文本组件
    $(".component-txt").click(function(event){
        componentRemoveClasses(1);
        var txt_num = 0;
        var txt_num_arr = [];
        $(".effect-txt").each(function(idx, item) {
            txt_num = parseInt($(item).attr('txt_num'));
            txt_num_arr.push(txt_num);
        });
        if(txt_num_arr.length>0){
            txt_num = Math.max.apply(null,txt_num_arr)+1;
        }else{
            txt_num = 1;
        }
        var txt_class_name = "txt"+txt_num;
        var txt_attr_class_name = "txt_attr"+txt_num;
        $(".effect-content").append("<div class='el-relative'><div class='interval'></div><div class='effect-txt "+txt_class_name+"' txt_num="+txt_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='txt'/><div class='txt-info'><p>添加一个文本内容</p></div><div class='btn-del' onclick='delTxtBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addTxtClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("单文本");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-txt "+txt_attr_class_name+"' txt_num="+txt_num+" ><div class='attr-content-txt-item'><label class='d-label'>提示文字</label><input type='text' value='添加一个文本内容' class='attr-content-txt-text' onkeyup='setTxtText(this)' /><span class='txt-text-tip'><span class='txt-text-length'>8</span>/15</span></div><div class='attr-content-txt-item'><label class='d-label'>链接地址</label><input type='text' value='' class='attr-content-txt-link' /></div><div class='attr-content-txt-item'><label class='d-label'>字体位置</label><input type='radio' value='1' checked name='txt-text-alignment"+txt_num+"'  id='t-left"+txt_num+"' onclick='changeTxtTextAlignment(this)' /><label for='t-left"+txt_num+"'>居左</label><input type='radio' value='2' name='txt-text-alignment"+txt_num+"' id='s-center"+txt_num+"' onclick='changeTxtTextAlignment(this)' /><label for='s-center"+txt_num+"'>居中</label><input type='radio' value='3' name='txt-text-alignment"+txt_num+"' id='s-right"+txt_num+"' onclick='changeTxtTextAlignment(this)' /><label for='s-right"+txt_num+"'>居右</label></div><div class='attr-content-txt-item' ><label class='d-label'>背景颜色</label><div class='select-component-item-color txt-select-bg-color'></div></div><div class='attr-content-txt-item' ><label class='d-label'>文字颜色</label><div class='select-component-item-color txt-select-text-color' style='background:#000000;'></div></div><div class='attr-content-txt-item'><label class='d-label'>上下边距</label><div class='attr-content-txt-item-content'><input type='hidden' class='txt-range-slider'  value='0' onchange='changeTxtPadding(this,1)'/><span class='txt-vertical-padding-value'>0</span><span class='pixel'>px (像素)</span></div></div><div class='attr-content-txt-item'><label class='d-label'>左右边距</label><div class='attr-content-txt-item-content'><input type='hidden' class='txt-range-slider'  value='0' onchange='changeTxtPadding(this,2)'/><span class='txt-horizontal-padding-value'>0</span><span class='pixel'>px (像素)</span></div></div><input type='hidden' class='txt-bg-color' value='#ffffff' /><input type='hidden' class='txt-text-color' value='#000000' /><input type='hidden' class='attr-content-txt-text-alignment' value='1' /><input type='hidden' class='txt-vertical-padding' value='0' /><input type='hidden' class='txt-horizontal-padding' value='0' /></div>");
        $('.txt-select-bg-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var txt_num = $(el).parent().parent().attr("txt_num");
                    $(".effect-txt").each(function(idx, item) {
                        if($(item).hasClass("txt"+txt_num)){
                            $(item).find(".txt-info").css('background','#'+hex);
                            $(el).parent().parent().find(".txt-bg-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $('.txt-select-text-color').colpick({
            layout:'hex',
            submit:1,
            colorScheme:'dark',
            onSubmit:function(hsb,hex,rgb,el,bySetColor){
                if(!bySetColor){
                    $(el).css('background','#'+hex);
                    var txt_num = $(el).parent().parent().attr("txt_num");
                    $(".effect-txt").each(function(idx, item) {
                        if($(item).hasClass("txt"+txt_num)){
                            $(item).find(".txt-info p").css('color','#'+hex);
                            $(el).parent().parent().find(".txt-text-color").val('#'+hex);
                        }
                    });
                }
                $(el).colpickHide();
            }
        }).keyup(function(){
            $(this).colpickSetColor(this.value);
            $(this).colpickHide();
        });
        $('.txt-range-slider').jRange({
            from: 0,
            to: 50,
            step: 1,
            format: '%s',
            width: 120,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $(".attr-content-txt").each(function(idx, item) {
            if($(item).hasClass("txt_attr"+txt_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 图文列表组件
    $(".component-image-text").click(function(event){
        componentRemoveClasses(1);
        var image_text_num = 0;
        var image_text_num_arr = [];
        $(".effect-image-text").each(function(idx, item) {
            image_text_num = parseInt($(item).attr('image_text_num'));
            image_text_num_arr.push(image_text_num);
        });
        if(image_text_num_arr.length>0){
            image_text_num = Math.max.apply(null,image_text_num_arr)+1;
        }else{
            image_text_num = 1;
        }
        var image_text_class_name = "image_text"+image_text_num;
        var image_text_attr_class_name = "image_text_attr"+image_text_num;
        $(".effect-content").append("<div class='el-relative'><div class='effect-image-text image_text"+image_text_num+"' image_text_num="+image_text_num+"><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='image_text'/><div class='image-text image-text-style-1' image_text_item='1'><div class='image-text-imgs image-text-style-1-left'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' alt=''/></div><div class='image-text-style-1-right'><p class='image-text-style-title'>WSTMart招商标准</p><p class='image-text-style-desc'>WSTMart开放平台将一如既往的最大程度地维护卖家的品牌利益，尊重品牌传统和内涵，欢迎优质品牌旗舰店入驻。</p></div></div><div class='image-text image-text-style-2 none2' image_text_item='2'><div class='image-text-style-2-left'><p class='image-text-style-title'>WSTMart招商标准</p><p class='image-text-style-desc'>WSTMart开放平台将一如既往的最大程度地维护卖家的品牌利益，尊重品牌传统和内涵，欢迎优质品牌旗舰店入驻。</p></div><div class='image-text-imgs image-text-style-2-right'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' alt=''/></div></div><div class='image-text image-text-style-3 none' image_text_item='3'><div class='image-text-style-3-top'><p class='image-text-style-title'>WSTMart招商标准</p><p class='image-text-style-desc'>WSTMart开放平台将一如既往的最大程度地维护卖家的品牌利益，尊重品牌传统和内涵，欢迎优质品牌旗舰店入驻。</p></div><div class='image-text-imgs image-text-style-3-bottom'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' alt=''/></div></div><div class='image-text image-text-style-4 none' image_text_item='4'><div class='image-text-style-4-top'><p class='image-text-style-title'>WSTMart招商标准</p><p class='image-text-style-desc'>WSTMart开放平台将一如既往的最大程度地维护卖家的品牌利益，尊重品牌传统和内涵，欢迎优质品牌旗舰店入驻。</p></div><div class='image-text-imgs wst-flex-row wst-jsa image-text-style-4-bottom'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' alt=''/><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' alt=''/></div></div><div class='image-text image-text-style-5 none' image_text_item='5'><div class='image-text-style-5-top'><p class='image-text-style-title'>WSTMart招商标准</p><p class='image-text-style-desc'>WSTMart开放平台将一如既往的最大程度地维护卖家的品牌利益，尊重品牌传统和内涵，欢迎优质品牌旗舰店入驻。</p></div><div class='image-text-imgs wst-flex-row wst-jsa image-text-style-5-bottom'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' alt=''/><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' alt=''/><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' alt=''/></div></div><div class='btn-del' onclick='delImageTextBtn(this)'>删除</div></div><div class='border-absolute effect-active select' onclick='addImageTextClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("图文列表");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-image-text image_text_attr"+image_text_num+"' image_text_num="+image_text_num+" ><div class='attr-content-image-text-item'><label class='d-label'>排列样式</label></div><div class='attr-content-image-text-item'><div class='image-text-style-item active' onclick='changeImageTextStyle(this)' image_text_item='1'>左图右文</div><div class='image-text-style-item' onclick='changeImageTextStyle(this)' image_text_item='2'>左文右图</div><div class='image-text-style-item' onclick='changeImageTextStyle(this)' image_text_item='3'>上下单图</div><div class='image-text-style-item' onclick='changeImageTextStyle(this)' image_text_item='4'>上下双图</div><div class='image-text-style-item' onclick='changeImageTextStyle(this)' image_text_item='5'>上下三图</div></div><div class='attr-content-image-text-item-img' ><div class='image-text-img'><div class='attr-content-image-text-img ' img_url='upload/custompagedecoration/base/banner_01.jpg'><div class='wst-flex-row'><label class='d-label'>图片</label><div class='image-text-picker' id='image-text-picker-"+image_text_num+"-1'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg'/></div></div><div class='image-tips'>建议宽度540px，高度380px，如尺寸不达标，图片将自适应显示</div></div></div><div class='image-text-img none'><div class='attr-content-image-text-img ' img_url='upload/custompagedecoration/base/banner_01.jpg'><div class='wst-flex-row'><label class='d-label'>图片</label><div class='image-text-picker' id='image-text-picker-"+image_text_num+"-2'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg'/></div></div><div class='image-tips'>建议宽度540px，高度380px，如尺寸不达标，图片将自适应显示</div></div></div><div class='image-text-img none'><div class='attr-content-image-text-img ' img_url='upload/custompagedecoration/base/banner_01.jpg'><div class='wst-flex-row'><label class='d-label'>图片</label><div class='image-text-picker' id='image-text-picker-"+image_text_num+"-3'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg'/></div></div><div class='image-tips'>建议宽度540px，高度380px，如尺寸不达标，图片将自适应显示</div></div></div><div><label class='d-label'>标题</label><input type='text' value='此处填写标题' class='attr-content-image-text-title' onkeyup='setImageTextTitle(this)'style='width:145px !important;' /><span class='image-text-title-tip'><span class='image-text-title-length'>6</span>/20</span></div><div><label class='d-label'>简介</label><input type='text' value='此处填写简介' class='attr-content-image-text-desc' onkeyup='setImageTextDesc(this)' style='width:145px !important;' /><span class='image-text-desc-tip'><span class='image-text-desc-length'>6</span>/200</span></div><div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-image-text-link' /></div></div><div class='attr-content-image-text-item'><label class='d-label'>上下边距</label><div class='attr-content-image-text-item-content'><input type='hidden' class='image-text-range-slider'  value='0' onchange='changeImageTextPadding(this)'/><span class='image-text-vertical-padding-value'>10</span><span class='pixel'>px (像素)</span></div></div><input type='hidden' class='image-text-style' value='1' /><input type='hidden' class='image-text-vertical-padding' value='0'></div>");
        WST.upload({
            pick: '#image-text-picker-' + image_text_num + '-1',
            formData: {dir: 'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png', mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback: function (f) {
                var json = WST.toAdminJson(f);
                if (json.status == 1) {
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath + json.thumb; //保存到数据库的路径
                    $('#image-text-picker-' + image_text_num+ '-1').parent().parent().attr('img_url', img_url);
                    $('#image-text-picker-' + image_text_num+ '-1').find('img').attr('src', WST.conf.RESOURCE_PATH + '/' + json.savePath + json.thumb);
                    $('.effect-image-text').each(function (idx, item) {
                        if ($(item).hasClass(image_text_class_name)) {
                            $(item).find('.image-text-imgs').each(function(key,value){
                                $(value).find('img').each(function(key2,value2){
                                    if(key2==0){
                                        $(value2).attr('src', WST.conf.RESOURCE_PATH + '/' + json.savePath + json.thumb);
                                    }
                                });
                            });
                        }
                    });
                } else {
                    WST.msg(json.msg, {icon: 2});
                }
            },
            progress: function (rate) {
                $('#uploadMsg').show().html('已上传' + rate + '%');
            }
        });
        WST.upload({
            pick: '#image-text-picker-' + image_text_num + '-2',
            formData: {dir: 'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png', mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback: function (f) {
                var json = WST.toAdminJson(f);
                if (json.status == 1) {
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath + json.thumb; //保存到数据库的路径
                    $('#image-text-picker-' + image_text_num+ '-2').parent().parent().attr('img_url', img_url);
                    $('#image-text-picker-' + image_text_num+ '-2').find('img').attr('src', WST.conf.RESOURCE_PATH + '/' + json.savePath + json.thumb);
                    $('.effect-image-text').each(function (idx, item) {
                        if ($(item).hasClass(image_text_class_name)) {
                            $(item).find('.image-text-imgs').each(function(key,value){
                                $(value).find('img').each(function(key2,value2){
                                    if(key2==1){
                                        $(value2).attr('src', WST.conf.RESOURCE_PATH + '/' + json.savePath + json.thumb);
                                    }
                                });
                            });
                        }
                    });
                } else {
                    WST.msg(json.msg, {icon: 2});
                }
            },
            progress: function (rate) {
                $('#uploadMsg').show().html('已上传' + rate + '%');
            }
        });
        WST.upload({
            pick: '#image-text-picker-' + image_text_num + '-3',
            formData: {dir: 'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png', mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback: function (f) {
                var json = WST.toAdminJson(f);
                if (json.status == 1) {
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath + json.thumb; //保存到数据库的路径
                    $('#image-text-picker-' + image_text_num+ '-3').parent().parent().attr('img_url', img_url);
                    $('#image-text-picker-' + image_text_num+ '-3').find('img').attr('src', WST.conf.RESOURCE_PATH + '/' + json.savePath + json.thumb);
                    $('.effect-image-text').each(function (idx, item) {
                        if ($(item).hasClass(image_text_class_name)) {
                            $(item).find('.image-text-imgs').each(function(key,value){
                                $(value).find('img').each(function(key2,value2){
                                    if(key2==2){
                                        $(value2).attr('src', WST.conf.RESOURCE_PATH + '/' + json.savePath + json.thumb);
                                    }
                                });
                            });
                        }
                    });
                } else {
                    WST.msg(json.msg, {icon: 2});
                }
            },
            progress: function (rate) {
                $('#uploadMsg').show().html('已上传' + rate + '%');
            }
        });
        $('.image-text-range-slider').jRange({
            from: 0,
            to: 50,
            step: 1,
            format: '%s',
            width: 120,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $(".attr-content-image-text").each(function(idx, item) {
            if($(item).hasClass("image_text_attr"+image_text_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 多店铺组件
    $(".component-shop").click(function(event){
        componentRemoveClasses(1);
        var shop_num = 0;
        $(".effect-shop").each(function(idx, item) {
            shop_num = parseInt($(item).attr('shop_num'));
        });
        shop_num += 1;
        if(shop_num > 1){
            WST.msg('多店铺组件只能添加一个!',{time:1000,anim: 6});
            return;
        }
        var shop_class_name = "shop"+shop_num;
        var shop_attr_class_name = "shop_attr"+shop_num;
        $(".effect-content").append("<div class='el-relative'><div class='effect-shop "+shop_class_name+"' shop_num="+shop_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='shop'/><div class='shop-title'>—— <span class='shop-title-text'>附近商家</span> ——</div><div class='current-location'><div class='location-icon'></div><div>当前：天河北路</div></div><div class='shop-item'><div class='shop-left'><img src='"+base_url+"/upload/custompagedecoration/base/shop.png' alt=''></div><div class='shop-right'><div><p class='shop-name'>WSTMart自营超市</p><p class='shop-desc'>主营：时蔬水果、网上菜场...</p><div class='shop-score'><p>店铺评分：</p><div class='star-icon'></div><div class='star-icon'></div><div class='star-icon'></div><div class='star-icon'></div><div class='star-icon'></div></div></div><div class='wst-flex-row wst-jsb'><div class='shop-location'><div class='location-icon'></div><p>燕岭路89号</p></div><p>2km</p></div></div></div><div class='shop-item'><div class='shop-left'><img src='"+base_url+"/upload/custompagedecoration/base/shop.png' alt=''></div><div class='shop-right'><div><p class='shop-name'>WSTMart自营超市</p><p class='shop-desc'>主营：时蔬水果、网上菜场...</p><div class='shop-score'><p>店铺评分：</p><div class='star-icon'></div><div class='star-icon'></div><div class='star-icon'></div><div class='star-icon'></div><div class='star-icon'></div></div></div><div class='wst-flex-row wst-jsb'><div class='shop-location'><div class='location-icon'></div><p>燕岭路89号</p></div><p>2km</p></div></div></div><div class='btn-del' onclick='delShopBtn(this)'>删除</div></div><div class='border-absolute' onclick='addShopClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("多店铺");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-shop "+shop_attr_class_name+"' shop_num="+shop_num+" ><div class='attr-content-shop-item'><label class='d-label'>搜索半径</label><input type='text' value='5' class='attr-content-shop-search-radius' onkeyup='javascript:WST.isChinese(this,1)' onkeypress='return WST.isNumberdoteKey(event)' /><span class='shop-radius-tip'>Km以内</span></div><div class='attr-content-shop-item'><label class='d-label'>顶部文字</label><input type='text' value='附近商家' class='attr-content-shop-title' onkeyup='setShopTitle(this)'/><span class='shop-title-tip'><span class='shop-title-length'>4</span>/10</span></div></div>");
        $(".attr-content-shop").each(function(idx, item) {
            if($(item).hasClass("shop_attr"+shop_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 新闻组件
    $(".component-new").click(function(event){
        componentRemoveClasses(1);
        var new_num = 0;
        var new_num_arr = [];
        $(".effect-new").each(function(idx, item) {
            new_num = parseInt($(item).attr('new_num'));
            new_num_arr.push(new_num);
        });
        if(new_num_arr.length>0){
            new_num = Math.max.apply(null,new_num_arr)+1;
        }else{
            new_num = 1;
        }
        var new_class_name = "new"+new_num;
        var new_attr_class_name = "new_attr"+new_num;
        $(".effect-content").append("<div class='el-relative'><div class='effect-new "+new_class_name+"' new_num="+new_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='new'/><div class='new-title'><div class='new-title-text'>新闻资讯</div><div>更多</div></div><div class='btn-del' onclick='delNewBtn(this)'>删除</div></div><div class='border-absolute' onclick='addNewClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("新闻");
        hideComponentAttrContent(1);
        $(".attr-content").append("<div class='attr-content-new "+new_attr_class_name+"' new_num="+new_num+"><div class='attr-content-new-item' ><label class='d-label'>新闻数量</label><input type='radio' value='2' name='new-count"+new_num+"'  id='n-count-2-"+new_num+"' checked onclick='changeNewCount(this)' /><label for='n-count-2-"+new_num+"' class='d-a-label'>2个</label><input type='radio' value='4' name='new-count"+new_num+"' id='n-count-4-"+new_num+"' onclick='changeNewCount(this)' /><label for='n-count-4-"+new_num+"' class='d-a-label'>4个</label><input type='radio' value='6' name='new-count"+new_num+"' id='n-count-6-"+new_num+"' onclick='changeNewCount(this)' /><label for='n-count-6-"+new_num+"' class='d-a-label'>6个</label><input type='radio' value='8' name='new-count"+new_num+"' id='n-count-8-"+new_num+"' onclick='changeNewCount(this)' /><label for='n-count-8-"+new_num+"' class='d-a-label'>8个</label></div><div class='attr-content-new-item'><label class='d-label'>新闻主题名称</label><input type='text' value='新闻资讯' class='attr-content-new-title' onkeyup='setNewTitle(this)'/><span class='new-title-tip'><span class='new-title-length'>4</span>/8</span></div><div class='attr-content-new-item'><label class='d-label'>新闻列表添加</label><input type='radio' value='1' name='news-select"+new_num+"' onclick='addNews(this)' /></div><input type='hidden' class='new-select-ids-value' value='' ><input type='hidden' class='new-count' value='2' /></div>");
        $(".attr-content-new").each(function(idx, item) {
            if($(item).hasClass("new_attr"+new_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 营销活动组件
    $(".component-marketing").click(function(event){
        componentRemoveClasses(1);
        var marketing_num = 0;
        var marketing_num_arr = [];
        $(".effect-marketing").each(function(idx, item) {
            marketing_num = parseInt($(item).attr('marketing_num'));
            marketing_num_arr.push(marketing_num);
        });
        if(marketing_num_arr.length>0){
            marketing_num = Math.max.apply(null,marketing_num_arr)+1;
        }else{
            marketing_num = 1;
        }
        var marketing_class_name = "marketing"+marketing_num;
        var marketing_attr_class_name = "marketing_attr"+marketing_num;
        $(".effect-content").append("<div class='el-relative'><div class='effect-marketing "+marketing_class_name+"' marketing_num="+marketing_num+" ><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort' value='0'/><input type='hidden' class='sort-name' value='marketing'/><div class='marketing-title'><div class='wst-flex-row wst-ac'><div class='marketing-title-text'>活动标题</div><div class='seckill-title none2'><div class='seckill-title-text'>12:00点场</div><div class='seckill-time'>01:15:16</div></div></div><div class='seckill-more'>更多</div></div><div class='wst-flex-row'><div class='goods' ><img src='"+base_url+"/upload/custompagedecoration/base/good_01.jpg' alt=''  /><div class='goods-info' ><div class='wst-flex-column wst-jsb' ><p class='goods-name'>此处显示商品名称</p><div class='pintuan-detail none'><div class='wst-flex-row wst-jsb'><p class='goods-price red '>￥99.00</p><p class='pintuan-num' >2人拼单</p></div></div><div class='seckill-detail none'><div class='wst-flex-row wst-jsb'><p class='goods-price red '>￥99.00</p><p class='goods-price goods-price-decoration' >￥150.00</p></div></div><div class='auction-detail none'><p class='goods-price auction-num'>1次出价</p><p class='goods-price'>当前价：<span class='red'>￥10.00</span></p></div><div class='bargain-detail none'><div class='wst-flex-row wst-jsb'><p class='market-price goods-price-decoration '>原价:￥20.00</p><p class='bargain-num'>2人参与</p></div><p class='goods-price red '>低价：￥5.00</p></div></div></div></div><div class='goods' ><img src='"+base_url+"/upload/custompagedecoration/base/good_02.jpg' alt=''  /><div class='goods-info' ><div class='wst-flex-column wst-jsb' ><p class='goods-name'>此处显示商品名称</p><div class='pintuan-detail none'><div class='wst-flex-row wst-jsb'><p class='goods-price red '>￥99.00</p><p class='pintuan-num' >2人拼单</p></div></div><div class='seckill-detail none'><div class='wst-flex-row wst-jsb'><p class='goods-price red '>￥99.00</p><p class='goods-price goods-price-decoration' >￥150.00</p></div></div><div class='auction-detail none'><p class='goods-price auction-num'>1次出价</p><p class='goods-price'>当前价：<span class='red'>￥10.00</span></p></div><div class='bargain-detail none'><div class='wst-flex-row wst-jsb'><p class='market-price goods-price-decoration '>原价:￥20.00</p><p class='bargain-num'>2人参与</p></div><p class='goods-price red '>低价：￥5.00</p></div></div></div></div></div><div class='btn-del' onclick='delMarketingBtn(this)'>删除</div></div><div class='border-absolute' onclick='addMarketingClassActive(this)' onmouseover='addClassActive(this)' onmouseout='removeClassActive(this)'></div></div>");
        $(".attr-title").html("营销活动");
        hideComponentAttrContent(1);
        var marketingTypeHtml = $('.marketingType').html();
        $(".attr-content").append("<div class='attr-content-marketing "+marketing_attr_class_name+"' marketing_num="+marketing_num+" ><div class='attr-content-marketing-item'><label class='d-label'>营销活动</label>"+marketingTypeHtml+"</div><div class='attr-content-marketing-item'><label class='d-label'>列表名称</label><input type='text' value='活动标题' class='attr-content-marketing-title' onkeyup='setMarketingTitle(this)'/><span class='marketing-title-tip'><span class='marketing-title-length'>0</span>/6</span></div><div class='attr-content-marketing-item'><label class='d-label'>上下边距</label><div class='attr-content-marketing-item-content'><input type='hidden' class='marketing-range-slider'  value='0' onchange='changeMarketingPadding(this)'/><span class='marketing-vertical-padding-value'>0</span><span class='pixel'>px (像素)</span></div></div><input type='hidden' class='marketing-type' value=''><input type='hidden' class='marketing-vertical-padding' value='0'></div>");
        $('.marketing-range-slider').jRange({
            from: 0,
            to: 50,
            step: 1,
            format: '%s',
            width: 120,
            showLabels: false,
            isRange : true,
            showScale: false
        });
        $(".attr-content-marketing").each(function(idx, item) {
            if($(item).hasClass("marketing_attr"+marketing_num)){
                $(item).show();
            }else{
                $(item).hide();
            }
        });
    });

    // 底部导航栏组件
    $(".effect-tabbar").click(function(event){
        componentRemoveClasses(1);
        hideComponentAttrContent(1);
        $(".effect-tabbar").addClass("effect-active");
        $(".effect-tabbar").addClass("select");
        $(".attr-title").html("底部导航栏");
        $(".attr-content-tabbar").show();
    });
});

// 公共方法开始
function addClassActive(obj){
    $(obj).parent().find(".border-absolute").addClass("effect-active");
}

function removeClassActive(obj){
    if($(obj).hasClass("select")){

    }else{
        $(obj).parent().find(".border-absolute").removeClass("effect-active");
    }
}

function delBtn(obj){
    var that = $(obj);
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        that.parent().remove();
        layer.close(index);
    });
}

function componentRemoveClasses(type){
    if(type==1){
        $(".effect-title").removeClass("effect-active");
        $(".effect-title").removeClass("select");
        $(".effect-tabbar").removeClass("effect-active");
        $(".effect-tabbar").removeClass("select");
    }
    $(".effect-media-swiper").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-goods-group").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-nav").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-notice").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-search").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-coupon").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-image").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-shopwindow").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-video").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-blank").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-line").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-text").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-txt").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-image-text").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-shop").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-new").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
    $(".effect-marketing").each(function(idx, item) {
        $(item).parent().find(".border-absolute").removeClass('effect-active');
        $(item).parent().find(".border-absolute").removeClass('select');
    });
}

function hideComponentAttrContent(type){
    if(type==1){
        $(".attr-content-page").hide();
        $(".attr-content-tabbar").hide();
    }
    $(".attr-content-swiper").hide();
    $(".attr-content-goods-group").hide();
    $(".attr-content-nav").hide();
    $(".attr-content-notice").hide();
    $(".attr-content-search").hide();
    $(".attr-content-coupon").hide();
    $(".attr-content-image").hide();
    $(".attr-content-shopwindow").hide();
    $(".attr-content-video").hide();
    $(".attr-content-blank").hide();
    $(".attr-content-line").hide();
    $(".attr-content-text").hide();
    $(".attr-content-txt").hide();
    $(".attr-content-image-text").hide();
    $(".attr-content-shop").hide();
    $(".attr-content-new").hide();
    $(".attr-content-marketing").hide();
}
// 公共方法结束

// 页面设置组件方法开始
function setPageTitle(obj){
    var page_title = $(obj).val();
    $(".effect-title-text").html(page_title);
}
// 页面设置组件方法结束

// 轮播组件方法开始
function delSwiperBtn(obj){
    var swiper_num = $(obj).parent().attr("swiper_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-media-swiper").each(function(idx, item) {
            if($(item).hasClass("swiper"+swiper_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-swiper").each(function(idx, item) {
            if($(item).hasClass("swiper_attr"+swiper_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function delSwiperItemBtn(obj){
    var that = $(obj);
    var swiper_item_counts = 0;
    var swiper_num = $(obj).parent().parent().attr("swiper_num");
    var swiper_class_name = "swiper"+swiper_num;
    var swiper_attr_class_name = "swiper_attr"+swiper_num;
    var img_url = $(obj).parent().find(".webuploader-pick img").attr("src") ? $(obj).parent().find(".webuploader-pick img").attr("src") : $(obj).parent().find('.banner-picker img').attr("src");
    var img_src = "";
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".attr-content-swiper").each(function(idx, item) {
            if($(item).attr("swiper_num") == swiper_num){
                $(item).find(".attr-content-swiper-item-img").each(function(idx, item) {
                    swiper_item_counts += 1;
                });
            }
        });
        if(swiper_item_counts == 1){
            WST.msg('至少保留一个!',{time:1000,anim: 6});
        }else{
            that.parent().remove();
            $(".attr-content-swiper").each(function(idx, item) {
                if($(item).hasClass(swiper_attr_class_name)){
                    $(item).find(".attr-content-swiper-item-img").each(function(index, obj) {
                        if(index == 0){
                            // 保存swiper组件第一张图片的路径，用来显示在中间的效果图
                            img_src = $(obj).find(".webuploader-pick img").attr("src");
                        }
                    });
                }
            });
            $(".effect-media-swiper").each(function(idx, item) {
                if($(item).hasClass(swiper_class_name)){
                    if($(item).find("img").attr("src") == img_url){
                        $(item).find("img").attr("src",img_src);
                    }
                }
            });
            layer.close(index);
        }
    });
}

function addSwiperClassActive(obj){
    var swiper_num = $(obj).parent().find(".effect-media-swiper").attr("swiper_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("图片轮播");
    hideComponentAttrContent(1);
    $(".attr-content-swiper").each(function(idx, item) {
        if($(item).hasClass("swiper_attr"+swiper_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function addSwiperItem(obj){
    var swiper_num = $(obj).parent().attr("swiper_num");
    var swiper_img_num = '';
    var swiper_class_name = "swiper"+swiper_num;
    var swiper_attr_class_name = "swiper_attr"+swiper_num;
    var img_src = "";
    $(".attr-content-swiper").each(function(idx, item) {
        if($(item).hasClass(swiper_attr_class_name)){
            $(item).find(".attr-content-swiper-item-img").each(function(index, obj) {
                if($(obj).find(".banner-picker")){
                    swiper_img_num = $(obj).find(".banner-picker").attr('id');
                }
            });
        }
    });
    // 截取最后一个id属性值里的数字，例如（banner-picker-2-1）
    swiper_img_num = parseInt(swiper_img_num.substr(parseInt(swiper_img_num.lastIndexOf("\-"))+1,swiper_img_num.length))+1;
    $(obj).before("<div class='attr-content-swiper-item-img'><div class='attr-content-swiper-img' img_url='upload/custompagedecoration/base/banner_01.jpg'><label class='d-label'>图片</label><div class='banner-picker' id='banner-picker-"+swiper_num+"-"+swiper_img_num+"'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg'/></div><div class='image-tips'>建议宽度750px，如尺寸不达标，图片将自适应显示</div></div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-swiper-link' /><div class='attr-btn-del' onclick='delSwiperItemBtn(this)'>X</div></div>");
    WST.upload({
        pick:'#banner-picker-'+swiper_num+'-'+swiper_img_num,
        formData: {dir:'custompagedecoration'},
        accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
        callback:function(f){
            var json = WST.toAdminJson(f);
            if(json.status==1){
                $('#uploadMsg').empty().hide();
                var img_url = json.savePath+json.thumb; //保存到数据库的路径
                $("#banner-picker-"+swiper_num+'-'+swiper_img_num).parent().attr('img_url',img_url);
                $("#banner-picker-"+swiper_num+'-'+swiper_img_num).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                $(".attr-content-swiper").each(function(idx, item) {
                    if($(item).hasClass(swiper_attr_class_name)){
                        $(item).find(".attr-content-swiper-item-img").each(function(index, obj) {
                            if(index == 0){
                                // 保存swiper组件第一张图片的路径，用来显示在中间的效果图
                                img_src = $(obj).find(".attr-content-swiper-img").attr("img_url");
                            }
                        });
                    }
                });
                $(".effect-media-swiper").each(function(idx,item){
                    if($(item).hasClass(swiper_class_name)){
                        // 当改变的是第一张轮播图，需要更新中间的效果图
                        if(swiper_img_num==1){
                            if(img_src.indexOf('banner')!=-1){
                                $(item).find('img').attr('src',img_src);
                            }else{
                                $(item).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+img_src);
                            }
                        }
                    }
                });
            }else{
                WST.msg(json.msg,{icon:2});
            }
        },
        progress:function(rate){
            $('#uploadMsg').show().html('已上传'+rate+"%");
        }
    });
}

function changeSwiperIndicator(obj){
    var swiper_num = $(obj).parent().parent().attr("swiper_num");
    var indicator_type = $(obj).val();
    $(obj).parent().parent().find(".indicator-type").val(indicator_type);
    $(".effect-media-swiper").each(function(idx, item) {
        if($(item).hasClass("swiper"+swiper_num)){
            if(indicator_type == 1){
                // 长方形
                $(item).find("span").removeClass("roundness");
                $(item).find("span").removeClass("square");
                $(item).find("span").addClass("rectangle");
            }else if(indicator_type == 2){
                // 正方形
                $(item).find("span").removeClass("roundness");
                $(item).find("span").removeClass("rectangle");
                $(item).find("span").addClass("square");
            }else{
                // 圆形
                $(item).find("span").removeClass("square");
                $(item).find("span").removeClass("rectangle");
                $(item).find("span").addClass("roundness");
            }
        }
    });
}

function changeSwiperInterval(obj){
    var swiper_num = $(obj).parent().parent().attr("swiper_num");
    var interval = $(obj).val();
    $(obj).parent().parent().find(".interval").val(interval);
}

function changeSwiperPadding(obj,type){
    var swiper_num = $(obj).parent().parent().parent().attr("swiper_num");
    var swiper_margin = $(obj).val().split(',')[1];
    switch (type) {
        case 1:
            $(obj).parent().parent().parent().find('.swiper-padding-top').val(swiper_margin);
            $(obj).parent().find(".swiper-padding-top-value").text(swiper_margin);
            break;
        case 2:
            $(obj).parent().parent().parent().find('.swiper-padding-bottom').val(swiper_margin);
            $(obj).parent().find(".swiper-padding-bottom-value").text(swiper_margin);
            break;
        case 3:
            $(obj).parent().parent().parent().find('.swiper-padding-left').val(swiper_margin);
            $(obj).parent().find(".swiper-padding-left-value").text(swiper_margin);
            break;
        case 4:
            $(obj).parent().parent().parent().find('.swiper-padding-right').val(swiper_margin);
            $(obj).parent().find(".swiper-padding-right-value").text(swiper_margin);
            break;
    }
    $(".effect-media-swiper").each(function(idx, item) {
        if($(item).hasClass("swiper"+swiper_num)){
            $(item).find("img").each(function(key, value) {
                switch (type) {
                    case 1:
                        $(value).css("padding-top",swiper_margin+"px");
                        break;
                    case 2:
                        $(value).css("padding-bottom",swiper_margin+"px");
                        break;
                    case 3:
                        $(value).css("padding-left",swiper_margin+"px");
                        break;
                    case 4:
                        $(value).css("padding-right",swiper_margin+"px");
                        break;
                }
            });
        }
    });
}
// 轮播组件方法结束

// 商品组组件方法开始
function delGoodsGroupBtn(obj){
    var goods_group_num = $(obj).parent().attr("goods_group_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group_attr"+goods_group_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function addGoodsGroupClassActive(obj){
    var goods_group_num = $(obj).parent().find(".effect-goods-group").attr("goods_group_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("商品组");
    hideComponentAttrContent(1);
    $(".attr-content-goods-group").each(function(idx, item) {
        if($(item).hasClass("goods_group_attr"+goods_group_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function toggleShowGoodsName(obj){
    var goods_group_num = $(obj).parent().parent().parent().attr("goods_group_num");
    if($(obj).prop("checked") == true){
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).find(".goods-name").show();
                $(obj).parent().parent().parent().find(".show-goods-name").val(1);
            }
        });
    }else{
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).find(".goods-name").hide();
                $(obj).parent().parent().parent().find(".show-goods-name").val(0);
            }
        });
    }
}

function toggleShowGoodsPrice(obj){
    var goods_group_num = $(obj).parent().parent().parent().attr("goods_group_num");
    if($(obj).prop("checked") == true){
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).find(".goods-price").show();
                $(obj).parent().parent().parent().find(".show-goods-price").val(1);
            }
        });
    }else{
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).find(".goods-price").hide();
                $(obj).parent().parent().parent().find(".show-goods-price").val(0);
            }
        });
    }
}

function toggleShowPraiseRate(obj){
    var goods_group_num = $(obj).parent().parent().parent().attr("goods_group_num");
    if($(obj).prop("checked") == true){
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).find(".goods-praise-rate").show();
                $(obj).parent().parent().parent().find(".show-praise-rate").val(1);
            }
        });
    }else{
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).find(".goods-praise-rate").hide();
                $(obj).parent().parent().parent().find(".show-praise-rate").val(0);
            }
        });
    }
}

function toggleShowSaleNum(obj){
    var goods_group_num = $(obj).parent().parent().parent().attr("goods_group_num");
    if($(obj).prop("checked") == true){
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).find(".goods-sale-num").show();
                $(obj).parent().parent().parent().find(".show-sale-num").val(1);
            }
        });
    }else{
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).find(".goods-sale-num").hide();
                $(obj).parent().parent().parent().find(".show-sale-num").val(0);
            }
        });
    }
}

function changeGoodsGroupColumns(obj){
    var goods_group_num = $(obj).parent().parent().attr("goods_group_num");
    var columns_value = $(obj).val();
    $(obj).parent().parent().find(".show-goods-columns").val(columns_value);
    var goods_columns = 0;
    if(columns_value == 2){
        // 橱窗式
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).find(".goods").each(function(key, value) {
                    goods_columns += 1;
                    $(value).find('.goods-praise-rate').parent().removeClass('goods-other-info');
                    $(value).find("img").css('width','');
                    $(value).removeClass('wst-flex-row');
                    $(value).find('.goods-info').css('width','');
                    $(value).find('.goods-name').parent().removeClass("wst-flex-column");
                    $(value).find('.goods-name').parent().removeClass("wst-jsb");
                    $(value).find('.goods-name').parent().css('height','');
                });
                if(goods_columns == 1){
                    $(item).find('.goods').parent().append("<div class='goods'><img src='"+base_url+"/upload/custompagedecoration/base/good_02.jpg' alt=''/><div class='goods-info'><div><p class='goods-name'>此处显示商品名称</p><p class='goods-price red'>￥99.00</p><div class='wst-flex-row wst-jsb'><p class='goods-praise-rate'>100%</p><p class='goods-sale-num'>成交数:99</p></div></div></div></div>");
                }
            }
        });
    }else if(columns_value == 3){
        // 海报式
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).find(".goods").each(function(key, value) {
                    goods_columns += 1;
                    $(value).find('.goods-praise-rate').parent().addClass('goods-other-info');
                    $(value).find("img").css('width','');
                    $(value).removeClass('wst-flex-row');
                    $(value).find('.goods-info').css('width','');
                    $(value).find('.goods-name').parent().removeClass("wst-flex-column");
                    $(value).find('.goods-name').parent().removeClass("wst-jsb");
                    $(value).find('.goods-name').parent().css('height','');
                    $(value).find('.goods-sale-num').css('margin-left','0');
                    if(goods_columns == 2 && key == 1){
                        $(value).remove();
                    }
                });
            }
        });
    }else{
        // 列表式
        $(".effect-goods-group").each(function(idx, item) {
            if($(item).hasClass("goods_group"+goods_group_num)){
                $(item).find(".goods").each(function(key, value) {
                    goods_columns += 1;
                    $(value).find('.goods-praise-rate').parent().removeClass('goods-other-info');
                    $(value).find("img").css('width','50%');
                    $(value).addClass('wst-flex-row');
                    $(value).find('.goods-info').css('width','50%');
                    $(value).find('.goods-name').parent().addClass("wst-flex-column");
                    $(value).find('.goods-name').parent().addClass("wst-jsb");
                    $(value).find('.goods-name').parent().css('height','100%');
                    if(goods_columns == 2 && key == 1){
                        $(value).remove();
                    }
                });
            }
        });
    }
}

function changeGoodsNum(obj){
    var goods_group_num = $(obj).parent().parent().attr("goods_group_num");
    var goods_num_value = $(obj).val();
    $(obj).parent().parent().find(".goods-group-goods-nums").val(goods_num_value);
}

function changeColumnsTitleDisplay(obj) {
    var show_columns_title = $(obj).val();
    var goods_group_num = $(obj).parent().parent().attr("goods_group_num");
    $(obj).parent().parent().find('.show-columns-title').val(show_columns_title);
    $(".effect-goods-group").each(function (idx, item) {
        if ($(item).hasClass("goods_group" + goods_group_num)) {
            if (show_columns_title == 1) {
                $(item).find('.columns-title').show();
            } else {
                $(item).find('.columns-title').hide();
            }
        }
    });
}

function setColumnsTitle(obj){
    var goods_group_num = $(obj).parent().parent().parent().attr("goods_group_num");
    var columns_title = $(obj).val();
    var columns_title_length = columns_title.length;
    var columns_item = $(obj).attr('columns_item');
    $(".effect-goods-group").each(function(idx, item) {
        if ($(item).hasClass("goods_group" + goods_group_num)) {
            $(item).find('.columns-title div').each(function(key, value) {
                if($(value).attr('columns_item') == columns_item){
                    if(columns_title.length>6){
                        columns_title = columns_title.substr(0,6);
                    }
                    $(value).text(columns_title);
                }
            });
        }
    });
    $(".attr-content-goods-group").each(function(idx, item) {
        if($(item).hasClass("goods_group_attr"+goods_group_num)){
            $(item).find('.attr-content-goods-group-item-column').each(function(index,obj){
               if($(obj).find('.goods-group-columns-name').attr('columns_item') == columns_item){
                   if(columns_title_length > 6){
                       $(obj).find('.goods-group-columns-name-length').html(6);
                   }else{
                       $(obj).find('.goods-group-columns-name-length').html(columns_title_length);
                   }
               }
            });
        }
    });
}

function addGoodsColumnItem(obj){
    var hidden_cats_id = $('.hidden-cats-id').val();
    var columns_item_counts = 0;
    var columns_item = 0;
    var goods_group_num = $(obj).parent().attr("goods_group_num");
    var goods_group_class_name = "goods_group"+goods_group_num;
    $(".attr-content-goods-group").each(function(idx, item) {
        if($(item).attr("goods_group_num") == goods_group_num){
            $(item).find(".attr-content-goods-group-item-column").each(function(idx, item) {
                columns_item_counts += 1;
                columns_item = $(item).find('.goods-group-columns-name').attr('columns_item');
            });
        }
    });
    if(columns_item_counts == 3){
        layer.msg("最多3个列表!",{time:1000,anim: 6});
    }else{
        columns_item += 1;
        $('.attr-content-goods-group-item-column-add').before("<div class='attr-content-goods-group-item-column' ><div class='wst-flex-row wst-ac'><label class='d-label'>列表名称</label><input type='text' value='列表名称1' class='goods-group-columns-name' columns_item="+columns_item+" onkeyup='setColumnsTitle(this)' style='width:150px !important;' /><span class='goods-group-columns-name-tip'><span class='goods-group-columns-name-length'>5</span>/6</span></div><label class='d-label'>选取商品</label><input type='radio' value='1' name='goods-select"+goods_group_num+"-"+columns_item+"' id='goods-select-condition"+goods_group_num+"-"+columns_item+"' onclick='changeGoodsSelect(this)' columns_item='"+columns_item+"' checked /><label for='goods-select-condition"+goods_group_num+"-"+columns_item+"'>条件选取</label><input type='radio' value='"+columns_item+"' name='goods-select"+goods_group_num+"-"+columns_item+"' id='goods-select-manual"+goods_group_num+"-"+columns_item+"' onclick='changeGoodsSelect(this)' columns_item='"+columns_item+"' /><label for='goods-select-manual"+goods_group_num+"-"+columns_item+"'>手动添加</label><input type='hidden' class='goods-select-ids-value' value='' columns_item='"+columns_item+"'><input type='hidden' class='goods-select-cats-id-value' value="+hidden_cats_id+" columns_item='"+columns_item+"'><input type='hidden' class='goods-tag-value' value='' columns_item='"+columns_item+"'/><input type='hidden' class='goods-min-price-value' value='' columns_item='"+columns_item+"'/><input type='hidden' class='goods-max-price-value' value='' columns_item='"+columns_item+"'/><div class='attr-btn-del' onclick='delGoodsColumnItemBtn(this)'>X</div></div>");
        $(".effect-goods-group").each(function(idx, item) {
            if ($(item).hasClass(goods_group_class_name)) {
                $(item).find('.columns-title').append("<div columns_item="+columns_item+">列表名称1</div>");
            }
        });
    }
}

function delGoodsColumnItemBtn(obj){
    var that = $(obj);
    var columns_item_counts = 0;
    var columns_item = $(obj).parent().find('.goods-group-columns-name').attr('columns_item');
    var goods_group_num = $(obj).parent().parent().attr("goods_group_num");
    var goods_group_class_name = "goods_group"+goods_group_num;

    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".attr-content-goods-group").each(function(idx, item) {
            if($(item).attr("goods_group_num") == goods_group_num){
                $(item).find(".attr-content-goods-group-item-column").each(function(idx, item) {
                    columns_item_counts += 1;
                });
                if(columns_item_counts == 1){
                    WST.msg('至少保留一个!',{time:1000,anim: 6});
                }else {
                    that.parent().remove();
                    $(".effect-goods-group").each(function(idx, item) {
                        if($(item).hasClass(goods_group_class_name)){
                            $(item).find(".columns-title div").each(function(key, value) {
                                if($(value).attr('columns_item') == columns_item){
                                    $(value).remove();
                                }
                            });
                        }
                    });
                    layer.close(index);
                }
            }
        });
    });
}

function changeGoodsSelect(obj){
    var goods_select = $(obj).val();
    var goods_group_num = $(obj).parent().parent().attr("goods_group_num");
    var columns_item = $(obj).attr('columns_item');
    if(goods_select == 1){
        // 条件选取
        var goods_select_cats_id_value = $(obj).parent().find('.goods-select-cats-id-value').val();
        var goods_tag_value = $(obj).parent().find('.goods-tag-value').val();
        var goods_min_price_value = $(obj).parent().find('.goods-min-price-value').val();
        var goods_max_price_value = $(obj).parent().find('.goods-max-price-value').val();
        if(goods_select_cats_id_value!=''){
            $('.goods-select-cat-name').find('input[type=radio]').each(function(idx, item) {
                if($(item).attr('cats-id')==goods_select_cats_id_value){
                    $(item).prop("checked",true);
                }
            });
        }else{
            $('.goods-select-cat-name').find('input[type=radio]').each(function(idx, item) {
                // 默认选中第一个分类
                if(idx==0){
                    $(item).attr("checked",true);
                }
            });
        }
        if(goods_tag_value!=''){
            $("input[name='goods-tag']").each(function(idx, item) {
                if($(item).val()==goods_tag_value){
                    $(item).prop("checked",true);
                }
            });
        }else{
            $("input[name='goods-tag']").each(function(idx, item) {
                // 默认选中不设置
                if(idx==0){
                    $(item).prop("checked",true);
                }
            });
        }
        if(goods_min_price_value!=''){
            $(".goods-min-price").val(goods_min_price_value);
        }else{
            $(".goods-min-price").val('');
        }
        if(goods_max_price_value!=''){
            $(".goods-max-price").val(goods_max_price_value);
        }else{
            $(".goods-max-price").val('');
        }

        var box = WST.open({title:'按条件选取商品',type:1,content:$('#goodBox2'),area: ['80%', '80%'],btn: ['确定','取消'],
            yes:function(){
                var select_cats_ids = [];
                var goods_tag = $("input[name='goods-tag']:checked").val();
                var min_price = '';
                var max_price = '';
                if($(".goods-min-price").val()!=''){
                    min_price = parseInt($(".goods-min-price").val());
                }
                if($(".goods-max-price").val()!=''){
                    max_price = parseInt($(".goods-max-price").val());
                }
                var goods_min_price = min_price;
                var goods_max_price = max_price;
                if(goods_min_price>0 && goods_max_price==''){
                    WST.msg('请填写最高价格',{time:1000,anim: 6});
                    return;
                }
                if(goods_max_price>0 && goods_min_price==''){
                    WST.msg('请填写最低价格',{time:1000,anim: 6});
                    return;
                }
                if(goods_min_price > goods_max_price){
                    WST.msg('价格设置错误',{time:1000,anim: 6});
                    return;
                }
                $('.goods-select-cat-name').find('input[type=radio]').each(function(idx, item) {
                    if($(item).prop('checked')){
                        select_cats_ids.push($(item).attr('cats-id'));
                    }
                });
                $(".attr-content-goods-group").each(function(idx, item) {
                    if($(item).attr('goods_group_num')==goods_group_num){
                        $(item).find('.goods-select-ids-value').each(function(key, value) {
                            if($(value).attr('columns_item') == columns_item){
                                $(value).val('');
                            }
                        });
                        $(item).find('.goods-select-cats-id-value').each(function(key, value) {
                            if($(value).attr('columns_item') == columns_item){
                                $(value).val(select_cats_ids);
                            }
                        });
                        $(item).find('.goods-tag-value').each(function(key, value) {
                            if($(value).attr('columns_item') == columns_item){
                                $(value).val(goods_tag);
                            }
                        });
                        $(item).find('.goods-min-price-value').each(function(key, value) {
                            if($(value).attr('columns_item') == columns_item){
                                $(value).val(goods_min_price);
                            }
                        });
                        $(item).find('.goods-max-price-value').each(function(key, value) {
                            if($(value).attr('columns_item') == columns_item){
                                $(value).val(goods_max_price);
                            }
                        });
                    }
                });
                $('#goodBox2').hide();
                layer.close(box);
            },cancel:function(){
                $('#goodBox2').hide();
            },end:function(){
                $('#goodBox2').hide();
            }});
    }else{
        // 手动添加
        $('#goodsName').val('');
        var goods_select_ids_value = $(obj).parent().find('.goods-select-ids-value').val();
        initSaleGrid(1,goods_select_ids_value);
        var box = WST.open({title:'选择商品',type:1,content:$('#goodBox'),area: ['80%', '80%'],btn: ['确定','取消'],
            yes:function(){
                $(".attr-content-goods-group").each(function(idx, item) {
                    if($(item).attr('goods_group_num')==goods_group_num){
                        $(item).find('.goods-select-ids-value').each(function(key, value) {
                            if($(value).attr('columns_item') == columns_item){
                                $(value).val(global_good_select_ids);
                            }
                        });
                        $(item).find('.goods-select-cats-id-value').each(function(key, value) {
                            if($(value).attr('columns_item') == columns_item){
                                $(value).val('');
                            }
                        });
                    }
                });
                layer.close(box);
            },cancel:function(){
                $('#goodBox').hide();
            },end:function(){
                $('#goodBox').hide();
            }});
    }
}

function changeGoodsGroupOrder(obj){
    var order = $(obj).val();
    $(obj).parent().parent().find(".goods-group-order").val(order);
}

function selectGoodIds(obj){
    var checked = $(obj).prop('checked');
    var good_id = $(obj).attr("goods-id");
    if(checked){
        global_good_select_ids.push(good_id);
    }else{
        var index = global_good_select_ids.indexOf(good_id);
        if (index > -1) { // 将之前插入数组的good_id删除
            global_good_select_ids.splice(index, 1);
        }
    }
}
// 商品组组件方法结束

// 导航组件方法开始
function delNavBtn(obj){
    var nav_num = $(obj).parent().attr("nav_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-nav").each(function(idx, item) {
            if($(item).hasClass("nav"+nav_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-nav").each(function(idx, item) {
            if($(item).hasClass("nav_attr"+nav_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function addNavClassActive(obj){
    var nav_num = $(obj).parent().find(".effect-nav").attr("nav_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("导航组");
    hideComponentAttrContent(1);
    $(".attr-content-nav").each(function(idx, item) {
        if($(item).hasClass("nav_attr"+nav_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function delNavItemBtn(obj){
    var nav_num = $(obj).parent().parent().attr("nav_num");
    var nav_item = $(obj).parent().attr("nav_item");
    var nav_item_counts = 0;
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".attr-content-nav-item-group").each(function(idx, item) {
            nav_item_counts += 1;
        });
        if(nav_item_counts == 3){
            WST.msg('至少保留三个!',{time:1000,anim: 6});
        }else{
            $(obj).parent().remove();
            $(".effect-nav").each(function(idx, item) {
                if($(item).hasClass("nav"+nav_num)){
                    $(item).find(".navs").each(function(key, value) {
                        if($(value).attr("nav_item") == nav_item){
                            $(value).remove();
                        }
                    });
                }
            });
            layer.close(index);
        }
    });
}

function addNavItem(obj){
    var nav_num = $(obj).parent().attr("nav_num");
    var nav_style =  $(obj).parent().find(".nav-style").val();
    var nav_img_num = '';
    var nav_class_name = "nav"+nav_num;
    var nav_attr_class_name = "nav_attr"+nav_num;
    $(".attr-content-nav").each(function(idx, item) {
        if($(item).hasClass(nav_attr_class_name)){
            $(item).find(".attr-content-nav-item-group").each(function(index, obj) {
                if($(obj).find(".nav-picker")){
                    nav_img_num = $(obj).find(".nav-picker").attr('id');
                }
            });
        }
    });
    // 截取最后一个id属性值里的数字，例如（nav-picker-2-1）
    nav_img_num = parseInt(nav_img_num.substr(parseInt(nav_img_num.lastIndexOf("\-"))+1,nav_img_num.length))+1;
    var nav_item = 1;
    var nav_count = $(obj).parent().find(".nav-count").val();
    $(".effect-nav").each(function(idx, item) {
        if($(item).hasClass("nav"+nav_num)){
            $(item).find(".navs").each(function(key, value) {
                nav_item += 1;
            });
            $(item).find(".btn-del").before("<div class='navs' nav_item="+nav_item+"><img src='"+base_url+"/upload/custompagedecoration/base/nav_01.png' alt=''/><div class='navs-info'><p>按钮文字</p></div></div>");
            if(nav_count == 1){
                // nav组件每行显示三个
                $(item).find(".navs").css("margin","20px 15px 10px");
            }else if(nav_count == 2){
                // nav组件每行显示四个
                $(item).find(".navs").css("margin","20px 2px 10px");
            }else{
                // nav组件每行显五个
                $(item).find(".navs").css("margin","20px -5px 10px");
            }
            // 改变样式
            $(item).find(".navs img").each(function(key, value) {
                if(nav_style==1){
                   $(value).removeClass('nav-style-roundness').addClass('nav-style-square');
                }else{
                   $(value).removeClass('nav-style-square').addClass('nav-style-roundness');
                }
            });
        }
    });
    $(obj).before("<div class='attr-content-nav-item-group' nav_item="+nav_item+"><div class='attr-content-nav-img' img_url='/upload/custompagedecoration/base/nav_01.png'><label class='d-label'>图片</label><div class='nav-picker' id='nav-picker-"+nav_num+"-"+nav_img_num+"' ><img src='"+base_url+"/upload/custompagedecoration/base/nav_01.png'/></div><div class='image-tips'>建议尺寸宽度40px，高度40px</div></div><div class='attr-content-nav-info'><label class='nav-label'>文字内容</label><input type='text' value='按钮文字' class='attr-content-nav-text' onkeyup='setNavItemTitle(this)'  /><span class='nav-text-tip'><span class='nav-text-length'>4</span>/4</span></div><div class='attr-content-nav-info'><label class='nav-label'>文字颜色</label><div class='select-component-item-color nav-select-text-color' style='background:#666666;'></div><input type='hidden' class='attr-content-nav-text-color' value='#666666' /></div><div class='attr-content-nav-info'><label class='nav-label'>链接地址</label><input type='text' value='' class='attr-content-nav-link' /></div><div class='attr-btn-del' onclick='delNavItemBtn(this)'>X</div></div>");
    WST.upload({
        pick:'#nav-picker-'+nav_num+'-'+nav_img_num,
        formData: {dir:'custompagedecoration'},
        accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
        callback:function(f){
            var json = WST.toAdminJson(f);
            if(json.status==1){
                $('#uploadMsg').empty().hide();
                var img_url = json.savePath+json.thumb; //保存到数据库的路径
                $("#nav-picker-"+nav_num+"-"+nav_img_num).parent().attr('img_url',img_url);
                $("#nav-picker-"+nav_num+"-"+nav_img_num).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                $(".effect-nav").each(function(idx,item){
                    if($(item).hasClass(nav_class_name)){
                        $(item).find(".navs").each(function(key, value) {
                            if($(value).attr("nav_item") == nav_item){
                                $(value).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                            }
                        });
                    }
                });
            }else{
                WST.msg(json.msg,{icon:2});
            }
        },
        progress:function(rate){
            $('#uploadMsg').show().html('已上传'+rate+"%");
        }
    });
    $('.nav-select-text-color').colpick({
        layout:'hex',
        submit:1,
        colorScheme:'dark',
        onSubmit:function(hsb,hex,rgb,el,bySetColor){
            if(!bySetColor){
                $(el).css('background','#'+hex);
                var nav_num = $(el).parent().parent().parent().attr("nav_num");
                var nav_item = $(el).parent().parent().attr("nav_item");
                $(".effect-nav").each(function(idx, item) {
                    if($(item).hasClass("nav"+nav_num)){
                        $(item).find(".navs").each(function(key, value) {
                            if($(value).attr("nav_item") == nav_item){
                                $(value).find(".navs-info p").css('color','#'+hex);
                                $(el).parent().find(".attr-content-nav-text-color").val('#'+hex);
                            }
                        });
                    }
                });
            }
            $(el).colpickHide();
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
        $(this).colpickHide();
    });
}

function changeNavCount(obj){
    var nav_num = $(obj).parent().parent().attr("nav_num");
    var nav_count = $(obj).val();
    $(obj).parent().parent().find(".nav-count").val(nav_count);
    $(".effect-nav").each(function(idx, item) {
        if($(item).hasClass("nav"+nav_num)){
            if(nav_count == 1){
                // nav组件每行显示三个
                $(item).find(".navs").css("margin","20px 15px 10px");
            }else if(nav_count == 2){
                // nav组件每行显示四个
                $(item).find(".navs").css("margin","20px 2px 10px");
            }else{
                // nav组件每行显五个
                $(item).find(".navs").css("margin","20px -5px 10px");
            }
        }
    });
}

function setNavItemTitle(obj){
    var nav_item = $(obj).parent().parent().attr("nav_item");
    var nav_text = $(obj).val();
    var nav_text_length = nav_text.length;
    var nav_num = $(obj).parent().parent().parent().attr("nav_num");
    $(".effect-nav").each(function(idx, item) {
        if($(item).hasClass("nav"+nav_num)){
            $(item).find(".navs").each(function(key, value) {
                if($(value).attr("nav_item") == nav_item){
                    if(nav_text.length>4){
                        nav_text = nav_text.substr(0,4);
                    }
                    $(value).find(".navs-info p").html(nav_text);
                }
            });
        }
    });
    $(".attr-content-nav .attr-content-nav-item-group").each(function(idx, item) {
        if($(item).attr('nav_item') == nav_item){
            if(nav_text_length > 4){
                $(item).find('.nav-text-length').html(4);
            }else{
                $(item).find('.nav-text-length').html(nav_text_length);
            }
        }
    });
}

function changeNavStyle(obj){
    var nav_num = $(obj).parent().parent().attr("nav_num");
    var nav_style = $(obj).val();
    $(obj).parent().parent().find(".nav-style").val(nav_style);
    $(".effect-nav").each(function(idx, item) {
        if($(item).hasClass("nav"+nav_num)){
            if(nav_style == 1){
                // 正方形nav-style-square
                $(item).find(".navs").each(function(key, value) {
                     $(value).find("img").removeClass('nav-style-roundness').addClass('nav-style-square');
                });
            }else{
                // 圆形
                $(item).find(".navs").each(function(key, value) {
                    $(value).find("img").removeClass('nav-style-square').addClass('nav-style-roundness');
                });
            }
        }
    });
}
// 导航组件方法结束

// 公告组件方法开始
function delNoticeBtn(obj){
    var notice_num = $(obj).parent().attr("notice_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-notice").each(function(idx, item) {
            if($(item).hasClass("notice"+notice_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-notice").each(function(idx, item) {
            if($(item).hasClass("notice_attr"+notice_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function addNoticeClassActive(obj){
    var notice_num = $(obj).parent().find(".effect-notice").attr("notice_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("公告组");
    hideComponentAttrContent(1);
    $(".attr-content-notice").each(function(idx, item) {
        if($(item).hasClass("notice_attr"+notice_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function addNoticeImg(){

}

function setNoticeText(obj){
    var notice_num = $(obj).parent().parent().parent().attr("notice_num");
    $(".attr-content-notice").each(function(idx, item) {
        if($(item).hasClass("notice_attr"+notice_num)){
            $(item).find('.attr-content-notice-item-group').each(function(key, value) {
                if(key==0){
                    // 只显示第一个公告的内容
                    var notice_text = $(value).find('.attr-content-notice-text').val();
                    $(".effect-notice").each(function(idx2, item2) {
                        if($(item2).hasClass("notice"+notice_num)){
                            if(notice_text.length>20){
                                notice_text = notice_text.substr(0,20)+'...';
                            }
                            $(item2).find(".notice-info p").html(notice_text);
                        }
                    });
                }
            });
        }
    });
}

function changeNoticePadding(obj){
    var notice_num = $(obj).parent().parent().parent().attr("notice_num");
    var notice_vertical_padding = $(obj).val().split(',')[1];
    $(obj).parent().parent().parent().find('.notice-vertical-padding').val(notice_vertical_padding);
    $(obj).parent().find(".notice-vertical-padding-value").text(notice_vertical_padding);
    $(".effect-notice").each(function(idx, item) {
        if($(item).hasClass("notice"+notice_num)){
            $(item).find(".notice-info").css("padding-top",notice_vertical_padding+"px");
            $(item).find(".notice-info").css("padding-bottom",notice_vertical_padding+"px");
        }
    });
}

function addNoticeItem(obj){
    var notice_item_counts = 0;
    var notice_item = 0;
    var notice_num = $(obj).parent().attr("notice_num");
    $(".attr-content-notice").each(function(idx, item) {
        if($(item).attr("notice_num") == notice_num){
            $(item).find(".attr-content-notice-item-group").each(function(idx, item) {
                notice_item_counts += 1;
                notice_item = $(item).find('.attr-content-notice-text').attr('notice_item');
            });
        }
    });
    if(notice_item_counts == 10){
        layer.msg("最多10个公告!",{time:1000,anim: 6});
    }else{
        notice_item += 1;
        $('.attr-content-notice-item-group-add').before("<div class='attr-content-notice-item-group' ><div><label class='d-label'>公告</label><input type='text' value='此处填写公告内容' class='attr-content-notice-text' onkeyup='setNoticeText(this)' /></div><div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-notice-link' /></div><div class='attr-btn-del' onclick='delNoticeItemBtn(this)'>X</div></div>");
    }
}

function delNoticeItemBtn(obj){
    var that = $(obj);
    var notice_item_counts = 0;
    var notice_num = $(obj).parent().parent().attr("notice_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".attr-content-notice").each(function(idx, item) {
            if($(item).attr("notice_num") == notice_num){
                $(item).find(".attr-content-notice-item-group").each(function(idx, item) {
                    notice_item_counts += 1;
                });
                if(notice_item_counts == 2){
                    WST.msg('至少保留两个公告!',{time:1000,anim: 6});
                }else {
                    that.parent().remove();
                    layer.close(index);
                }
            }
        });
    });
}

function changeNoticeDirection(obj){
    $(obj).parent().parent().find('.notice-direction').val($(obj).val());
}
// 公告组件方法结束

// 搜索框组件方法开始
function delSearchBtn(obj){
    var search_num = $(obj).parent().attr("search_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-search").each(function(idx, item) {
            if($(item).hasClass("search"+search_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-search").each(function(idx, item) {
            if($(item).hasClass("search_attr"+search_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function addSearchClassActive(obj){
    var search_num = $(obj).parent().find(".effect-search").attr("search_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("搜索框");
    hideComponentAttrContent(1);
    $(".attr-content-search").each(function(idx, item) {
        if($(item).hasClass("search_attr"+search_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function setSearchText(obj){
    var search_num = $(obj).parent().parent().parent().attr("search_num");
    var search_text = $(obj).val();
    $(".effect-search").each(function(idx, item) {
        if($(item).hasClass("search"+search_num)){
            if(search_text.length>19){
                search_text = search_text.substr(0,19)+'...';
            }
            $(item).find(".search-info p").html(search_text);
        }
    });
}

function changeSearchClass(obj){
    var search_num = $(obj).parent().parent().parent().attr("search_num");
    var search_class = $(obj).val();
    $(obj).parent().parent().parent().find(".attr-content-search-class").val(search_class);
    $(".effect-search").each(function(idx, item) {
        if($(item).hasClass("search"+search_num)){
            if(search_class == 1){
                // 方形
                $(item).find(".search-info").css("border-radius","0");
            }else if(search_class == 2){
                // 圆角
                $(item).find(".search-info").css("border-radius","5px");
            }else{
                // 圆弧
                $(item).find(".search-info").css("border-radius","100px");
            }
        }
    });
}

function changeSearchTextAlignment(obj){
    var search_num = $(obj).parent().parent().parent().attr("search_num");
    var search_text_alignment = $(obj).val();
    $(obj).parent().parent().parent().find(".attr-content-search-text-alignment").val(search_text_alignment);
    $(".effect-search").each(function(idx, item) {
        if($(item).hasClass("search"+search_num)){
            if(search_text_alignment == 1){
                // 居左
                $(item).find(".search-info").css("justify-content","flex-start");
            }else if(search_text_alignment == 2){
                // 居中
                $(item).find(".search-info").css("justify-content","center");
            }else{
                // 居右
                $(item).find(".search-info").css("justify-content","flex-end");
            }
        }
    });
}
// 搜索框组件方法结束

// 优惠券组件方法开始
function delCouponBtn(obj){
    var coupon_num = $(obj).parent().attr("coupon_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-coupon").each(function(idx, item) {
            if($(item).hasClass("coupon"+coupon_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-coupon").each(function(idx, item) {
            if($(item).hasClass("coupon_attr"+coupon_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function addCouponClassActive(obj){
    var coupon_num = $(obj).parent().find(".effect-coupon").attr("coupon_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("优惠券组");
    hideComponentAttrContent(1);
    $(".attr-content-coupon").each(function(idx, item) {
        if($(item).hasClass("coupon_attr"+coupon_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function changeCouponPadding(obj){
    var coupon_num = $(obj).parent().parent().parent().attr("coupon_num");
    var coupon_vertical_padding = $(obj).val().split(',')[1];
    $(obj).parent().parent().parent().find('.coupon-vertical-padding').val(coupon_vertical_padding);
    $(obj).parent().find(".coupon-vertical-padding-value").text(coupon_vertical_padding);
    $(".effect-coupon").each(function(idx, item) {
        if($(item).hasClass("coupon"+coupon_num)){
            $(item).find(".coupons").css("padding-top",coupon_vertical_padding+"px");
            $(item).find(".coupons").css("padding-bottom",coupon_vertical_padding+"px");
        }
    });
}

function changeCouponCounts(obj){
    var coupon_num = $(obj).parent().parent().parent().attr("coupon_num");
    var coupon_counts = $(obj).val().split(',')[1];
    if(coupon_counts == 0){
        coupon_counts = 1;
    }
    $(obj).parent().parent().parent().find('.coupon-counts').val(coupon_counts);
    $(obj).parent().find(".coupon-counts-value").text(coupon_counts);
}

function changeCouponStyle(obj){
    var coupon_num = $(obj).parent().parent().attr("coupon_num");
    var couponHtml1 = $('.couponHtml1').html();
    var couponHtml2 = $('.couponHtml2').html();
    var coupon_style = $(obj).val();
    $(obj).parent().parent().find('.coupon-style').val(coupon_style);

    $(".effect-coupon").each(function(idx, item) {
        if($(item).hasClass("coupon"+coupon_num)){
            if(coupon_style == 1){
                $(item).find('.coupons').html(couponHtml1);
            }else{
                $(item).find('.coupons').html(couponHtml2);
            }
        }
    });
    $(".attr-content-coupon").each(function(idx, item) {
        if($(item).hasClass("coupon_attr"+coupon_num)){
            if(coupon_style == 1){
                $(item).find('.coupon-nums').html('3');
            }else{
                $(item).find('.coupon-nums').html('2');
            }
            // 清空已选优惠券
            //$(item).find('.coupon-select-ids-value').val('');
        }
    });
}

function addCoupons(obj){
    var coupon_style = $(obj).parent().parent().find('.coupon-style').val();
    var coupon_num = $(obj).parent().parent().attr('coupon_num');
    var coupon_limit = 0;
    var coupon_select_ids_value = $(obj).parent().parent().find('.coupon-select-ids-value').val();
    if(coupon_style==1){
        coupon_limit=3;
    }else{
        coupon_limit=2;
    }
    initGridCoupon(1,coupon_select_ids_value);
    var box = WST.open({title:"优惠券列表&nbsp;<span style='color:#ff0000;'>(首页仅可发放起始时间小于当前时间的优惠券)</span>",type:1,content:$('#couponBox'),area: ['80%', '55%'],btn: ['确定','取消'],
        yes:function(){
            var coupon_nums = 0;
            $('.select-coupon-id').find('input[type=checkbox]').each(function(idx, item) {
                if($(item).prop('checked')){
                    coupon_nums += 1;
                }
            });
            if(coupon_nums > coupon_limit){
                WST.msg('已超过优惠券样式允许的最大数量',{time:1000,anim: 6});
                return;
            }
            if(coupon_style==1){
                if(coupon_nums < 3){
                    WST.msg('至少选择三张优惠券',{time:1000,anim: 6});
                    return;
                }
            }else{
                if(coupon_nums < 2){
                    WST.msg('至少选择两张优惠券',{time:1000,anim: 6});
                    return;
                }
            }
            $(".attr-content-coupon").each(function(idx, item) {
                if($(item).attr('coupon_num')==coupon_num){
                    $(item).find('.coupon-select-ids-value').val(global_coupon_select_ids);
                }
            });
            layer.close(box);
        },cancel:function(){
            $('#couponBox').hide();
        },end:function(){
            $('#couponBox').hide();
        }});
}

function selectCouponIds(obj){
    var checked = $(obj).prop('checked');
    var coupon_id = $(obj).attr("coupon-id");
    if(checked){
        global_coupon_select_ids.push(coupon_id);
    }else{
        var index = global_coupon_select_ids.indexOf(coupon_id);
        if (index > -1) { // 将之前插入数组的coupon_id删除
            global_coupon_select_ids.splice(index, 1);
        }
    }
}
// 优惠券组件方法结束

// 单图组组件方法开始
function delImageBtn(obj){
    var image_num = $(obj).parent().attr("image_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-image").each(function(idx, item) {
            if($(item).hasClass("image"+image_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-image").each(function(idx, item) {
            if($(item).hasClass("image_attr"+image_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function addImageClassActive(obj){
    var image_num = $(obj).parent().find(".effect-image").attr("image_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("图片");
    hideComponentAttrContent(1);
    $(".attr-content-image").each(function(idx, item) {
        if($(item).hasClass("image_attr"+image_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function delImageItemBtn(obj){
    var that = $(obj);
    var image_item_counts = 0;
    var image_num = $(obj).parent().parent().attr("image_num");
    var image_item = $(obj).parent().find(".attr-content-image-img").attr("image_item");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".attr-content-image-item-img").each(function(idx, item) {
            image_item_counts += 1;
        });
        if(image_item_counts == 1){
            WST.msg('至少保留一个!',{time:1000,anim: 6});
        }else{
            $(".effect-image").each(function(idx, item) {
                if($(item).hasClass("image"+image_num)){
                    $(item).find(".images img").each(function(key, value) {
                        if($(value).attr("image_item") == image_item){
                            $(value).parent().remove();
                        }
                    });
                }
            });
            that.parent().remove();
            layer.close(index);
        }
    });
}

function addImageItemImg(){

}

function addImageItem(obj){
    var image_num = $(obj).parent().attr("image_num");
    var image_img_num = '';
    var image_class_name = "image"+image_num;
    var image_attr_class_name = "image_attr"+image_num;
    var image_padding_top =  parseInt($(obj).parent().find(".image-padding-top").val());
    var image_padding_bottom =  parseInt($(obj).parent().find(".image-padding-bottom").val());
    var image_padding_left =  parseInt($(obj).parent().find(".image-padding-left").val());
    var image_padding_right =  parseInt($(obj).parent().find(".image-padding-right").val());
    var image_item = 1;
    var style = "";
    var img_src = "";
    $(".effect-image").each(function(idx, item) {
        if($(item).hasClass("image"+image_num)){
            $(item).find(".images img").each(function(key, value) {
                image_item = parseInt($(value).attr('image_item'));
            });
            image_item += 1;
            if(image_padding_top > 0){
                style += "padding-top:"+image_padding_top+"px;";
            }
            if(image_padding_bottom > 0){
                style += "padding-bottom:"+image_padding_bottom+"px;";
            }
            if(image_padding_left > 0){
                style += "padding-left:"+image_padding_left+"px;";
            }
            if(image_padding_right > 0){
                style += "padding-right:"+image_padding_right+"px;";
            }
            $(item).find(".images").append("<div class='images-item' style='"+style+"' ><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' alt='' image_item="+image_item+" /></div>");
        }
    });
    $(".attr-content-image").each(function(idx, item) {
        if($(item).hasClass(image_attr_class_name)){
            $(item).find(".attr-content-image-item-img").each(function(index, obj) {
                if($(obj).find(".image-picker")){
                    image_img_num = $(obj).find(".image-picker").attr('id');
                }
            });
        }
    });
    // 截取最后一个id属性值里的数字，例如（image-picker-2-1）
    image_img_num = parseInt(image_img_num.substr(parseInt(image_img_num.lastIndexOf("\-"))+1,image_img_num.length))+1;
    $(obj).before("<div class='attr-content-image-item-img' ><div class='attr-content-image-img' img_url='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg' image_item="+image_item+"><label class='d-label'>图片</label><div class='image-picker' id='image-picker-"+image_num+"-"+image_img_num+"' onclick='addImageItemImg(this)'><img src='"+base_url+"/upload/custompagedecoration/base/banner_01.jpg'/></div><div class='image-tips'>手机端与微信端建议宽度750px，高度自适应。小程序端建议宽度750px，高度374px</div></div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-image-link' /><div class='attr-btn-del' onclick='delImageItemBtn(this)'>X</div></div>");
    WST.upload({
        pick:'#image-picker-'+image_num+'-'+image_img_num,
        formData: {dir:'custompagedecoration'},
        accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
        callback:function(f){
            var json = WST.toAdminJson(f);
            if(json.status==1){
                $('#uploadMsg').empty().hide();
                var img_url = json.savePath+json.thumb; //保存到数据库的路径
                $("#image-picker-"+image_num+'-'+image_img_num).parent().attr('img_url',img_url);
                $("#image-picker-"+image_num+'-'+image_img_num).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                $(".attr-content-image").each(function(idx, item) {
                    if($(item).hasClass(image_attr_class_name)){
                        $(item).find(".attr-content-image-item-img").each(function(index, obj) {
                            if(parseInt($(obj).find('.attr-content-image-img').attr('image_item')) == image_item) {
                                // 保存image组件图片的路径，用来显示在中间的效果图
                                img_src = $(obj).find(".attr-content-image-img").attr("img_url");
                            }
                        });
                    }
                });
                $(".effect-image").each(function(idx,item){
                    if($(item).hasClass(image_class_name)){
                        $(item).find('img').each(function(idx,obj){
                            if(parseInt($(obj).attr('image_item')) == image_item){
                                $(obj).attr('src',WST.conf.RESOURCE_PATH+'/'+img_src);
                            }
                        });
                    }
                });
            }else{
                WST.msg(json.msg,{icon:2});
            }
        },
        progress:function(rate){
            $('#uploadMsg').show().html('已上传'+rate+"%");
        }
    });
}

function changeImagePadding(obj,type){
    var image_num = $(obj).parent().parent().parent().attr("image_num");
    var image_margin = $(obj).val().split(',')[1];
    switch (type) {
        case 1:
            $(obj).parent().parent().parent().find('.image-padding-top').val(image_margin);
            $(obj).parent().find(".image-padding-top-value").text(image_margin);
            break;
        case 2:
            $(obj).parent().parent().parent().find('.image-padding-bottom').val(image_margin);
            $(obj).parent().find(".image-padding-bottom-value").text(image_margin);
            break;
        case 3:
            $(obj).parent().parent().parent().find('.image-padding-left').val(image_margin);
            $(obj).parent().find(".image-padding-left-value").text(image_margin);
            break;
        case 4:
            $(obj).parent().parent().parent().find('.image-padding-right').val(image_margin);
            $(obj).parent().find(".image-padding-right-value").text(image_margin);
            break;
    }
    $(".effect-image").each(function(idx, item) {
        if($(item).hasClass("image"+image_num)){
            $(item).find(".images .images-item").each(function(key, value) {
                switch (type) {
                    case 1:
                        $(value).css("padding-top",image_margin+"px");
                        break;
                    case 2:
                        $(value).css("padding-bottom",image_margin+"px");
                        break;
                    case 3:
                        $(value).css("padding-left",image_margin+"px");
                        break;
                    case 4:
                        $(value).css("padding-right",image_margin+"px");
                        break;
                }
            });
        }
    });
}
// 单图组组件方法结束

// 图片橱窗组件方法开始
function delShopwindowBtn(obj){
    var shopwindow_num = $(obj).parent().attr("shopwindow_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-shopwindow").each(function(idx, item) {
            if($(item).hasClass("shopwindow"+shopwindow_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-shopwindow").each(function(idx, item) {
            if($(item).hasClass("shopwindow_attr"+shopwindow_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function addShopwindowClassActive(obj){
    var shopwindow_num = $(obj).parent().find(".effect-shopwindow").attr("shopwindow_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("图片橱窗");
    hideComponentAttrContent(1);
    $(".attr-content-shopwindow").each(function(idx, item) {
        if($(item).hasClass("shopwindow_attr"+shopwindow_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function delShopwindowItemBtn(obj){
    var that = $(obj);
    var shopwindow_item_counts = 0;
    var shopwindow_num = $(obj).parent().parent().attr("shopwindow_num");
    var shopwindow_item = $(obj).parent().find(".attr-content-shopwindow-img").attr("shopwindow_item");
    var shopwindow_layout = parseInt($(obj).parent().parent().find(".shopwindow-layout-value").val());
    var shopwindow_layout_count = 0;
    var shopwindow_layout_array = [];
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".attr-content-shopwindow-item-img").each(function(idx, item) {
            shopwindow_item_counts += 1;
        });
        if(shopwindow_item_counts == 1){
            WST.msg('至少保留一个!',{time:1000,anim: 6});
        }else{
            $(".effect-shopwindow").each(function(idx, item) {
                if($(item).hasClass("shopwindow"+shopwindow_num)){
                    $(item).find(".shopwindows img").each(function(key, value) {
                        if($(value).attr("shopwindow_item") == shopwindow_item){
                            $(value).parent().remove();
                        }
                    });
                    if(shopwindow_layout == 4){
                        $(item).find(".shopwindows-layout img").each(function(key, value) {
                            $(value).remove();
                        });
                        $(item).find(".shopwindows .shopwindows-item img").each(function(key, value) {
                            $(item).find(".shopwindows-s-hide").append("<div class='shopwindows-item'><img src='" + $(value).attr('src') + "' alt='' shopwindow_item=" + $(value).attr('shopwindow_item') + " /></div>");
                        });
                        $(item).find(".shopwindows-s-hide .shopwindows-item img").each(function(key, value) {
                            shopwindow_layout_array.push($(value));
                            shopwindow_layout_count += 1;
                            $(value).parent().remove();
                        });
                        if(shopwindow_layout_count == 2){
                            $(item).find(".s-layout-top").css("height","100%");
                        }else{
                            $(item).find(".s-layout-top").css("height","50%");
                        }
                        for(var i=0;i<shopwindow_layout_array.length;i++){
                            if(i==0){
                                $(item).find(".s-layout-left-item").append(shopwindow_layout_array[i]);
                            }else if(i == 1){
                                $(item).find(".s-layout-top-item").append(shopwindow_layout_array[i]);
                            }else if(i == 2){
                                $(item).find(".s-layout-bottom-item:nth-child(1)").append(shopwindow_layout_array[i]);
                            }else if(i == 3){
                                $(item).find(".s-layout-bottom-item:nth-child(2)").append(shopwindow_layout_array[i]);
                            }
                        }
                    }
                }
            });
            that.parent().remove();
            layer.close(index);
        }
    });
}

function addShopwindowItemImg(){

}

function addShopwindowItem(obj){
    var shopwindow_num = $(obj).parent().attr("shopwindow_num");
    var shopwindow_img_num = '';
    var shopwindow_class_name = "shopwindow"+shopwindow_num;
    var shopwindow_attr_class_name = "shopwindow_attr"+shopwindow_num;
    var shopwindow_layout = parseInt($(obj).parent().find(".shopwindow-layout-value").val());
    var shopwindow_item = 1;
    var shopwindow_layout_count = 0;
    var shopwindow_layout_array = [];
    var style = "";
    $(".attr-content-shopwindow").each(function(idx, item) {
        if($(item).hasClass(shopwindow_attr_class_name)){
            $(item).find(".attr-content-shopwindow-item-img").each(function(index, obj) {
                if($(obj).find(".shopwindow-picker")){
                    shopwindow_img_num = $(obj).find(".shopwindow-picker").attr('id');
                }
            });
        }
    });
    // 截取最后一个id属性值里的数字，例如（image-picker-2-1）
    shopwindow_img_num = parseInt(shopwindow_img_num.substr(parseInt(shopwindow_img_num.lastIndexOf("\-"))+1,shopwindow_img_num.length))+1;
    $(".effect-shopwindow").each(function(idx, item) {
        if($(item).hasClass("shopwindow"+shopwindow_num)){
            if(shopwindow_layout == 1){
                style += "width:50%;";
            }else if(shopwindow_layout == 2){
                style += "width:33%;";
            }else if(shopwindow_layout == 3){
                style += "width:25%;";
            }
            $(item).find(".shopwindows img").each(function(key, value) {
                shopwindow_item += 1;
            });
            $(item).find(".shopwindows").append("<div class='shopwindows-item' style='"+style+"' ><img src='"+base_url+"/upload/custompagedecoration/base/shopwindow_01.jpg' alt='' shopwindow_item="+shopwindow_item+" /></div>");
            if(shopwindow_layout == 4){
                $(item).find(".shopwindows-layout img").each(function(key, value) {
                    $(value).remove();
                });
                $(item).find(".shopwindows .shopwindows-item img").each(function(key, value) {
                    $(item).find(".shopwindows-s-hide").append("<div class='shopwindows-item'><img src='"+$(value).attr('src')+"' alt='' shopwindow_item="+$(value).attr('shopwindow_item')+" /></div>");
                });
                $(item).find(".shopwindows-s-hide .shopwindows-item img").each(function(key, value) {
                    shopwindow_layout_array.push($(value));
                    shopwindow_layout_count += 1;
                    $(value).parent().remove();
                });
                if(shopwindow_layout_count == 2){
                    $(item).find(".s-layout-top").css("height","100%");
                }else{
                    $(item).find(".s-layout-top").css("height","50%");
                }
                for(var i=0;i<shopwindow_layout_array.length;i++){
                    if(i==0){
                        $(item).find(".s-layout-left-item").append(shopwindow_layout_array[i]);
                    }else if(i == 1){
                        $(item).find(".s-layout-top-item").append(shopwindow_layout_array[i]);
                    }else if(i == 2){
                        $(item).find(".s-layout-bottom-item:nth-child(1)").append(shopwindow_layout_array[i]);
                    }else if(i == 3){
                        $(item).find(".s-layout-bottom-item:nth-child(2)").append(shopwindow_layout_array[i]);
                    }
                }
            }
        }
    });
    $(obj).before("<div class='attr-content-shopwindow-item-img' ><div class='attr-content-shopwindow-img' img_url='"+base_url+"/upload/custompagedecoration/base/shopwindow_01.jpg' shopwindow_item="+shopwindow_item+"><label class='d-label'>图片</label><div class='shopwindow-picker' id='shopwindow-picker-"+shopwindow_num+"-"+shopwindow_img_num+"' onclick='addShopwindowItemImg(this)'><img src='"+base_url+"/upload/custompagedecoration/base/shopwindow_01.jpg'/></div></div><label class='d-label'>链接</label><input type='text' value='' class='attr-content-shopwindow-link' /><div class='attr-btn-del' onclick='delShopwindowItemBtn(this)'>X</div></div>");
    WST.upload({
        pick:'#shopwindow-picker-'+shopwindow_num+'-'+shopwindow_img_num,
        formData: {dir:'custompagedecoration'},
        accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
        callback:function(f){
            var json = WST.toAdminJson(f);
            if(json.status==1){
                $('#uploadMsg').empty().hide();
                var img_url = json.savePath+json.thumb; //保存到数据库的路径
                $("#shopwindow-picker-"+shopwindow_num+'-'+shopwindow_img_num).parent().attr('img_url',img_url);
                $("#shopwindow-picker-"+shopwindow_num+'-'+shopwindow_img_num).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                $(".attr-content-shopwindow").each(function(idx, item) {
                    if($(item).hasClass(shopwindow_attr_class_name)){
                        $(item).find(".attr-content-shopwindow-item-img").each(function(index, obj) {
                            if(parseInt($(obj).find('.attr-content-shopwindow-img').attr('shopwindow_item')) == shopwindow_item) {
                                // 保存shopwindow组件图片的路径，用来显示在中间的效果图
                                img_src = $(obj).find(".attr-content-shopwindow-img").attr("img_url");
                            }
                        });
                    }
                });
                $(".effect-shopwindow").each(function(idx,item){
                    if($(item).hasClass(shopwindow_class_name)){
                        $(item).find('img').each(function(idx,obj){
                            if(parseInt($(obj).attr('shopwindow_item')) == shopwindow_item){
                                $(obj).attr('src',WST.conf.RESOURCE_PATH+'/'+img_src);
                            }
                        });
                    }
                });
            }else{
                WST.msg(json.msg,{icon:2});
            }
        },
        progress:function(rate){
            $('#uploadMsg').show().html('已上传'+rate+"%");
        }
    });
}

function changeShopwindowLayout(obj){
    var shopwindow_num = $(obj).parent().parent().parent().attr("shopwindow_num");
    var shopwindow_layout = $(obj).val();
    var shopwindow_layout_info1 = "请确保所有图片的尺寸/比例相同";
    var shopwindow_layout_info2 = "最多显示四张图片，超出隐藏。请确保右上角的图片长宽比保持2:1，其余图片的比例相同";
    var shopwindow_layout_count = 0;
    var shopwindow_layout_array = [];
    var flag = false;
    $(obj).parent().parent().parent().find(".shopwindow-layout-value").val(shopwindow_layout);
    $(".effect-shopwindow").each(function(idx, item) {
        if($(item).hasClass("shopwindow"+shopwindow_num)){
            if(shopwindow_layout == 1){
                // 堆积两列
                $(item).find(".shopwindows").show();
                $(item).find(".shopwindows-layout").hide();
                $(item).find(".shopwindows-item").css("width","50%");
                $(obj).parent().parent().parent().find(".shopwindow-layout-info").text(shopwindow_layout_info1);
            }else if(shopwindow_layout == 2){
                // 堆积三列
                $(item).find(".shopwindows").show();
                $(item).find(".shopwindows-layout").hide();
                $(item).find(".shopwindows-item").css("width","33%");
                $(obj).parent().parent().parent().find(".shopwindow-layout-info").text(shopwindow_layout_info1);
            }else if(shopwindow_layout == 3){
                // 堆积四列
                $(item).find(".shopwindows").show();
                $(item).find(".shopwindows-layout").hide();
                $(item).find(".shopwindows-item").css("width","25%");
                $(obj).parent().parent().parent().find(".shopwindow-layout-info").text(shopwindow_layout_info1);
            }else{
                // 橱窗样式
                $(item).find(".shopwindows-layout img").each(function(key, value) {
                    $(value).remove();
                });
                $(item).find(".shopwindows .shopwindows-item img").each(function(key, value) {
                    $(item).find(".shopwindows-s-hide").append("<div class='shopwindows-item'><img src='"+$(value).attr('src')+"' alt='' shopwindow_item="+$(value).attr('shopwindow_item')+" /></div>");
                });
                $(item).find(".shopwindows-s-hide .shopwindows-item img").each(function(key, value) {
                    shopwindow_layout_array.push($(value));
                    shopwindow_layout_count += 1;
                    $(value).parent().remove();
                });
                for(var i=0;i<shopwindow_layout_array.length;i++){
                    if(i==0){
                        $(item).find(".s-layout-left-item").append(shopwindow_layout_array[i]);
                    }else if(i == 1){
                        $(item).find(".s-layout-top-item").append(shopwindow_layout_array[i]);
                    }else if(i == 2){
                        $(item).find(".s-layout-bottom-item:nth-child(1)").append(shopwindow_layout_array[i]);
                    }else if(i == 3){
                        $(item).find(".s-layout-bottom-item:nth-child(2)").append(shopwindow_layout_array[i]);
                    }
                }
                $(item).find(".shopwindows").hide();
                $(item).find(".shopwindows-layout").show();
                $(obj).parent().parent().parent().find(".shopwindow-layout-info").text(shopwindow_layout_info2);
            }
        }
    });
}
// 图片橱窗组件方法结束

// 视频组件方法开始
function delVideoBtn(obj){
    var video_num = $(obj).parent().attr("video_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-video").each(function(idx, item) {
            if($(item).hasClass("video"+video_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-video").each(function(idx, item) {
            if($(item).hasClass("video_attr"+video_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function addVideoClassActive(obj){
    var video_num = $(obj).parent().find(".effect-video").attr("video_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("视频组");
    hideComponentAttrContent(1);
    $(".attr-content-video").each(function(idx, item) {
        if($(item).hasClass("video_attr"+video_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function addVideoItemImg(obj){

}

function changeVideoMargin(obj){
    var video_num = $(obj).parent().parent().parent().attr("video_num");
    var video_margin = $(obj).val().split(',')[1];
    $(obj).parent().parent().parent().find('.video-vertical-padding').val(video_margin);
    $(obj).parent().find(".video-vertical-padding-value").text(video_margin);
    $(".effect-video").each(function(idx, item) {
        if($(item).hasClass("video"+video_num)){
            $(item).css("margin-top",video_margin+"px");
            $(item).css("margin-bottom",video_margin+"px");
        }
    });
}
// 视频组件方法结束

// 辅助空白组件方法开始
function delBlankBtn(obj){
    var blank_num = $(obj).parent().attr("blank_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-blank").each(function(idx, item) {
            if($(item).hasClass("blank"+blank_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-blank").each(function(idx, item) {
            if($(item).hasClass("blank_attr"+blank_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function addBlankClassActive(obj){
    var blank_num = $(obj).parent().find(".effect-blank").attr("blank_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("辅助空白");
    hideComponentAttrContent(1);
    $(".attr-content-blank").each(function(idx, item) {
        if($(item).hasClass("blank_attr"+blank_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function changeBlankHeight(obj){
    var blank_num = $(obj).parent().parent().parent().attr("blank_num");
    var blank_height = $(obj).val().split(',')[1];
    if(blank_height == 0){
        $(obj).parent().parent().parent().find('.blank-height').val(1);
        $(obj).parent().find(".blank-height-value").text(1);
    }else{
        $(obj).parent().parent().parent().find('.blank-height').val(blank_height);
        $(obj).parent().find(".blank-height-value").text(blank_height);
    }
    $(".effect-blank").each(function(idx, item) {
        if($(item).hasClass("blank"+blank_num)){
            if(blank_height == 0){
                $(item).css("height","1px");
            }else{
                $(item).css("height",blank_height+"px");
            }
            if(blank_height <= 16){
                $(item).find(".btn-del").css("height",blank_height+"px");
            }else{
                $(item).find(".btn-del").css("height","16px");
            }
        }
    });
}
// 辅助空白组件方法结束

// 辅助线组件方法开始
function delLineBtn(obj){
    var line_num = $(obj).parent().attr("line_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-line").each(function(idx, item) {
            if($(item).hasClass("line"+line_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-line").each(function(idx, item) {
            if($(item).hasClass("line_attr"+line_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function addLineClassActive(obj){
    var line_num = $(obj).parent().find(".effect-line").attr("line_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("辅助线");
    hideComponentAttrContent(1);
    $(".attr-content-line").each(function(idx, item) {
        if($(item).hasClass("line_attr"+line_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function changeLineHeight(obj){
    var line_num = $(obj).parent().parent().parent().attr("line_num");
    var line_height = $(obj).val().split(',')[1];
    if(line_height == 0){
        $(obj).parent().parent().parent().find('.line-height').val(1);
        $(obj).parent().find(".line-height-value").text(1);
    }else{
        $(obj).parent().parent().parent().find('.line-height').val(line_height);
        $(obj).parent().find(".line-height-value").text(line_height);
    }
    $(".effect-line").each(function(idx, item) {
        if($(item).hasClass("line"+line_num)){
            if(line_height == 0){
                $(item).find(".line").css("border-width","1px");
            }else{
                $(item).find(".line").css("border-width",line_height+"px");
            }
        }
    });
}

function changeLineMargin(obj){
    var line_num = $(obj).parent().parent().parent().attr("line_num");
    var line_margin = $(obj).val().split(',')[1];
    $(obj).parent().parent().parent().find('.line-vertical-margin').val(line_margin);
    $(obj).parent().find(".line-vertical-margin-value").text(line_margin);
    $(".effect-line").each(function(idx, item) {
        if($(item).hasClass("line"+line_num)){
            $(item).find(".line").css("margin-top",line_margin+"px");
            $(item).find(".line").css("margin-bottom",line_margin+"px");
            var line_component_height = parseInt($(item).find(".line").css("margin-top")) + parseInt($(item).find(".line").css("margin-bottom"));
            if(line_component_height <= 16){
                $(item).find(".btn-del").css("height",line_margin+"px");
            }else{
                $(item).find(".btn-del").css("height","16px");
            }
        }
    });
}

function changeLineClass(obj){
    var line_num = $(obj).parent().parent().parent().attr("line_num");
    var line_class = $(obj).val();
    $(obj).parent().parent().parent().find(".line-class").val(line_class);
    $(".effect-line").each(function(idx, item) {
        if($(item).hasClass("line"+line_num)){
            if(line_class == 1){
                // 实线
                $(item).find(".line").css("border-top-style","solid");
            }else if(line_class == 2){
                // 虚线
                $(item).find(".line").css("border-top-style","dashed");
            }else{
                // 点状
                $(item).find(".line").css("border-top-style","dotted");
            }
        }
    });
}
// 辅助线组件方法结束

// 富文本组件方法开始
function delTextBtn(obj){
    var text_num = $(obj).parent().attr("text_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-text").each(function(idx, item) {
            if($(item).hasClass("text"+text_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-text").each(function(idx, item) {
            if($(item).hasClass("text_attr"+text_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function addTextClassActive(obj){
    var text_num = $(obj).parent().find(".effect-text").attr("text_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("富文本");
    hideComponentAttrContent(1);
    $(".attr-content-text").each(function(idx, item) {
        if($(item).hasClass("text_attr"+text_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function changeTextPadding(obj,type){
    var text_num = $(obj).parent().parent().parent().attr("text_num");
    var text_padding = $(obj).val().split(',')[1];
    if(type == 1){
        $(obj).parent().parent().parent().find('.text-vertical-padding').val(text_padding);
        $(obj).parent().find(".text-vertical-padding-value").text(text_padding);
    }else{
        $(obj).parent().parent().parent().find('.text-horizontal-padding').val(text_padding);
        $(obj).parent().find(".text-horizontal-padding-value").text(text_padding);
    }
    $(".effect-text").each(function(idx, item) {
        if($(item).hasClass("text"+text_num)){
            if(type == 1){
                $(item).find(".text").css("padding-top",text_padding+"px");
                $(item).find(".text").css("padding-bottom",text_padding+"px");
            }else{
                $(item).find(".text").css("padding-left",text_padding+"px");
                $(item).find(".text").css("padding-right",text_padding+"px");
            }
        }
    });
}
// 富文本组件方法结束

// 单文本组件方法开始
function addTxtClassActive(obj){
    var txt_num = $(obj).parent().find(".effect-txt").attr("txt_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("单文本");
    hideComponentAttrContent(1);
    $(".attr-content-txt").each(function(idx, item) {
        if($(item).hasClass("txt_attr"+txt_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function delTxtBtn(obj){
    var txt_num = $(obj).parent().attr("txt_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-txt").each(function(idx, item) {
            if($(item).hasClass("txt"+txt_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-txt").each(function(idx, item) {
            if($(item).hasClass("txt_attr"+txt_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}
function setTxtText(obj){
    var txt_num = $(obj).parent().parent().attr("txt_num");
    var txt_text = $(obj).val();
    var txt_text_length = txt_text.length;
    $(".effect-txt").each(function(idx, item) {
        if($(item).hasClass("txt"+txt_num)){
            if(txt_text.length>15){
                txt_text = txt_text.substr(0,15);
            }
            $(item).find(".txt-info p").html(txt_text);
        }
    });
    $(".attr-content-txt").each(function(idx, item) {
        if($(item).hasClass("txt_attr"+txt_num)){
            if(txt_text_length > 15){
                $(item).find('.txt-text-length').html(15);
            }else{
                $(item).find('.txt-text-length').html(txt_text_length);
            }
        }
    });
}

function changeTxtTextAlignment(obj){
    var txt_num = $(obj).parent().parent().attr("txt_num");
    var txt_text_alignment = $(obj).val();
    $(obj).parent().parent().parent().find(".attr-content-txt-text-alignment").val(txt_text_alignment);
    $(".effect-txt").each(function(idx, item) {
        if($(item).hasClass("txt"+txt_num)){
            if(txt_text_alignment == 1){
                // 居左
                $(item).find(".txt-info").css("justify-content","flex-start");
            }else if(txt_text_alignment == 2){
                // 居中
                $(item).find(".txt-info").css("justify-content","center");
            }else{
                // 居右
                $(item).find(".txt-info").css("justify-content","flex-end");
            }
        }
    });
}

function changeTxtPadding(obj,type){
    var txt_num = $(obj).parent().parent().parent().attr("txt_num");
    var txt_padding = $(obj).val().split(',')[1];
    if(type == 1){
        $(obj).parent().parent().parent().find('.txt-vertical-padding').val(txt_padding);
        $(obj).parent().find(".txt-vertical-padding-value").text(txt_padding);
    }else{
        $(obj).parent().parent().parent().find('.txt-horizontal-padding').val(txt_padding);
        $(obj).parent().find(".txt-horizontal-padding-value").text(txt_padding);
    }
    $(".effect-txt").each(function(idx, item) {
        if($(item).hasClass("txt"+txt_num)){
            if(type == 1){
                $(item).find(".txt-info").css("padding-top",txt_padding+"px");
                $(item).find(".txt-info").css("padding-bottom",txt_padding+"px");
            }else{
                $(item).find(".txt-info").css("padding-left",txt_padding+"px");
                $(item).find(".txt-info").css("padding-right",txt_padding+"px");
            }
        }
    });
}
// 单文本组件方法结束

// 图文列表组件方法开始
function addImageTextClassActive(obj){
    var image_text_num = $(obj).parent().find(".effect-image-text").attr("image_text_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("图文列表");
    hideComponentAttrContent(1);
    $(".attr-content-image-text").each(function(idx, item) {
        if($(item).hasClass("image_text_attr"+image_text_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function delImageTextBtn(obj){
    var image_text_num = $(obj).parent().attr("image_text_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-image-text").each(function(idx, item) {
            if($(item).hasClass("image_text"+image_text_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-image-text").each(function(idx, item) {
            if($(item).hasClass("image_text_attr"+image_text_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function changeImageTextStyle(obj){
    var image_text_num = $(obj).parent().parent().attr("image_text_num");
    var image_text_item = $(obj).attr("image_text_item");
    var image_text_img_count = 0;
    var image_text_class_name = "image_text"+image_text_num;
    var image_text_attr_class_name = "image_text_attr"+image_text_num;
    var image_text_title = $(obj).parent().parent().find('.attr-content-image-text-title').val();
    $(obj).parent().parent().find('.image-text-style').val(image_text_item);
    $(".attr-content-image-text").each(function(idx, item) {
        if($(item).hasClass(image_text_attr_class_name)){
            $(item).find('.image-text-style-item ').each(function(key, value) {
                $(value).removeClass('active');
            });
            $(item).find(".attr-content-image-text-img").each(function(index, obj) {
                if($(obj).find(".image-text-picker")){
                    image_text_img_count += 1;
                }
            });
        }
        $(obj).addClass('active');
    });
    $(".attr-content-image-text").each(function(idx, item) {
        if($(item).hasClass(image_text_attr_class_name)){
            switch(image_text_item){
                case '1':
                case '2':
                case '3':
                    $(item).find('.image-text-img').each(function(key,value){
                       if(key==0){
                           $(value).removeClass('none');
                       }else{
                           $(value).addClass('none');
                       }
                    });
                    break;
                case '4':
                    $(item).find('.image-text-img').each(function(key,value){
                        if(key==2){
                            $(value).addClass('none');
                        }else{
                            $(value).removeClass('none');
                        }
                    });
                    break;
                case '5':
                    $(item).find('.image-text-img').each(function(key,value){
                        $(value).removeClass('none');
                    });
                    break;
            }
        }
    });
    $(".effect-image-text").each(function(idx, item) {
        if($(item).hasClass("image_text"+image_text_num)){
            if(image_text_item == 1 || image_text_item == 2){
                if(image_text_title.length>12){
                    image_text_title = image_text_title.substr(0,12)+'...';
                }
            }else{
                if(image_text_title.length>20){
                    image_text_title = image_text_title.substr(0,20);
                }
            }
            $(item).find(".image-text-style-title").html(image_text_title);
            $(item).find('.image-text').each(function(key, value){
                if($(value).attr("image_text_item") == image_text_item){
                    if(key == 0 || key == 1){
                        $(value).removeClass('none2');
                    }else{
                        $(value).removeClass('none');
                    }
                }else{
                    if(key == 0 || key == 1){
                        $(value).addClass('none2');
                    }else{
                        $(value).addClass('none');
                    }
                }
            });
        }
    });
}

function setImageTextTitle(obj){
    var image_text_num = $(obj).parent().parent().parent().attr("image_text_num");
    var image_text_title = $(obj).val();
    var image_text_title_length = image_text_title.length;
    var image_text_style = $(obj).parent().parent().parent().find('.image-text-style').val();
    $(".effect-image-text").each(function(idx, item) {
        if($(item).hasClass("image_text"+image_text_num)){
            if(image_text_style==1||image_text_style==2){
                if(image_text_title.length>12){
                    image_text_title = image_text_title.substr(0,12)+'...';
                }
            }else{
                if(image_text_title.length>20){
                    image_text_title = image_text_title.substr(0,20);
                }
            }
            $(item).find(".image-text-style-title").html(image_text_title);
        }
    });
    $(".attr-content-image-text").each(function(idx, item) {
        if($(item).hasClass("image_text_attr"+image_text_num)){
            if(image_text_title_length > 20){
                $(item).find('.image-text-title-length').html(20);
            }else{
                $(item).find('.image-text-title-length').html(image_text_title_length);
            }
        }
    });
}

function setImageTextDesc(obj){
    var image_text_num = $(obj).parent().parent().parent().attr("image_text_num");
    var image_text_desc = $(obj).val();
    var image_text_desc_length = image_text_desc.length;
    $(".effect-image-text").each(function(idx, item) {
        if($(item).hasClass("image_text"+image_text_num)){
            if(image_text_desc.length>50){
                image_text_desc = image_text_desc.substr(0,50)+'...';
            }
            $(item).find(".image-text-style-desc").html(image_text_desc);
        }
    });
    $(".attr-content-image-text").each(function(idx, item) {
        if($(item).hasClass("image_text_attr"+image_text_num)){
            if(image_text_desc_length > 200){
                $(item).find('.image-text-desc-length').html(200);
            }else{
                $(item).find('.image-text-desc-length').html(image_text_desc_length);
            }
        }
    });
}

function changeImageTextPadding(obj){
    var image_text_num = $(obj).parent().parent().parent().attr("image_text_num");
    var image_text_vertical_padding = $(obj).val().split(',')[1];
    $(obj).parent().parent().parent().find('.image-text-vertical-padding').val(image_text_vertical_padding);
    $(obj).parent().find(".image-text-vertical-padding-value").text(image_text_vertical_padding);
    $(".effect-image-text").each(function(idx, item) {
        if($(item).hasClass("image_text"+image_text_num)){
            $(item).find(".image-text").css("padding-top",image_text_vertical_padding+"px");
            $(item).find(".image-text").css("padding-bottom",image_text_vertical_padding+"px");
        }
    });
}
// 图文列表组件方法结束

// 多店铺组件方法开始
function addShopClassActive(obj){
    var shop_num = $(obj).parent().find(".effect-shop").attr("shop_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("多店铺");
    hideComponentAttrContent(1);
    $(".attr-content-shop").each(function(idx, item) {
        if($(item).hasClass("shop_attr"+shop_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function delShopBtn(obj){
    var shop_num = $(obj).parent().attr("shop_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-shop").each(function(idx, item) {
            if($(item).hasClass("shop"+shop_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-shop").each(function(idx, item) {
            if($(item).hasClass("shop_attr"+shop_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function setShopTitle(obj){
    var shop_num = $(obj).parent().parent().attr("shop_num");
    var shop_title = $(obj).val();
    var shop_title_length = shop_title.length;
    $(".effect-shop").each(function(idx, item) {
        if($(item).hasClass("shop"+shop_num)){
            if(shop_title.length>10){
                shop_title = shop_title.substr(0,10);
            }
            $(item).find('.shop-title-length').html(shop_title.length);
            $(item).find(".shop-title-text").html(shop_title);
        }
    });
    $(".attr-content-shop").each(function(idx, item) {
        if($(item).hasClass("shop_attr"+shop_num)){
            if(shop_title_length > 10){
                $(item).find('.shop-title-length').html(10);
            }else{
                $(item).find('.shop-title-length').html(shop_title_length);
            }
        }
    });
}
// 多店铺组件方法结束

// 新闻组件方法开始
function addNewClassActive(obj){
    var new_num = $(obj).parent().find(".effect-new").attr("new_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("新闻");
    hideComponentAttrContent(1);
    $(".attr-content-new").each(function(idx, item) {
        if($(item).hasClass("new_attr"+new_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function delNewBtn(obj){
    var new_num = $(obj).parent().attr("new_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-new").each(function(idx, item) {
            if($(item).hasClass("new"+new_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-new").each(function(idx, item) {
            if($(item).hasClass("new_attr"+new_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function setNewTitle(obj){
    var new_num = $(obj).parent().parent().attr("new_num");
    var new_title = $(obj).val();
    var new_title_length = new_title.length;
    $(".effect-new").each(function(idx, item) {
        if($(item).hasClass("new"+new_num)){
            if(new_title.length>8){
                new_title = new_title.substr(0,8);
            }
            $(item).find('.new-title-length').html(new_title.length);
            $(item).find(".new-title-text").html(new_title);
        }
    });
    $(".attr-content-new").each(function(idx, item) {
        if($(item).hasClass("new_attr"+new_num)){
            if(new_title_length > 8){
                $(item).find('.new-title-length').html(8);
            }else{
                $(item).find('.new-title-length').html(new_title_length);
            }
        }
    });
}

function changeNewCount(obj){
    var count = $(obj).val();
    $(obj).parent().parent().find(".new-count").val(count);
}

function addNews(obj){
    var new_num = $(obj).parent().parent().attr('new_num');
    var new_select_ids_value = $(obj).parent().parent().find('.new-select-ids-value').val();
    initGridNew(1,new_select_ids_value);
    var box = WST.open({title:"选择新闻",type:1,content:$('#newBox'),area: ['80%', '55%'],btn: ['确定','取消'],
        yes:function(){
            $(".attr-content-new").each(function(idx, item) {
                if($(item).attr('new_num')==new_num){
                    $(item).find('.new-select-ids-value').val(global_new_select_ids);
                }
            });
            layer.close(box);
        },cancel:function(){
            $('#newBox').hide();
        },end:function(){
            $('#newBox').hide();
        }});
}

function selectNewIds(obj){
    var checked = $(obj).prop('checked');
    var new_id = $(obj).attr("new-id");
    if(checked){
        global_new_select_ids.push(new_id);
    }else{
        var index = global_new_select_ids.indexOf(new_id);
        if (index > -1) { // 将之前插入数组的new_id删除
            global_new_select_ids.splice(index, 1);
        }
    }
}
// 新闻组件方法结束

// 营销活动组件方法开始
function addMarketingClassActive(obj){
    var marketing_num = $(obj).parent().find(".effect-marketing").attr("marketing_num");
    componentRemoveClasses(1);
    $(obj).addClass("effect-active");
    $(obj).addClass("select");

    $(".attr-title").html("营销活动");
    hideComponentAttrContent(1);
    $(".attr-content-marketing").each(function(idx, item) {
        if($(item).hasClass("marketing_attr"+marketing_num)){
            $(item).show();
        }else{
            $(item).hide();
        }
    });
}

function delMarketingBtn(obj){
    var marketing_num = $(obj).parent().attr("marketing_num");
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-marketing").each(function(idx, item) {
            if($(item).hasClass("marketing"+marketing_num)){
                $(item).parent().remove();
            }
        });
        $(".attr-content-marketing").each(function(idx, item) {
            if($(item).hasClass("marketing_attr"+marketing_num)){
                $(item).remove();
            }
        });
        layer.close(index);
    });
}

function setMarketingTitle(obj){
    var marketing_num = $(obj).parent().parent().attr("marketing_num");
    var marketing_title = $(obj).val();
    var marketing_title_length = marketing_title.length;
    $(".effect-marketing").each(function(idx, item) {
        if($(item).hasClass("marketing"+marketing_num)){
            if(marketing_title.length>6){
                marketing_title = marketing_title.substr(0,6);
            }
            $(item).find(".marketing-title-text").html(marketing_title);
        }
    });
    $(".attr-content-marketing").each(function(idx, item) {
        if($(item).hasClass("marketing_attr"+marketing_num)){
            if(marketing_title_length > 6){
                $(item).find('.marketing-title-length').html(6);
            }else{
                $(item).find('.marketing-title-length').html(marketing_title_length);
            }
        }
    });
}

function changeMarketingType(obj){
    var marketing_type = $(obj).val();
    var marketing_num = $(obj).parent().parent().attr("marketing_num");
    $(obj).parent().parent().find('.marketing-type').val(marketing_type);
    $(".effect-marketing").each(function(idx, item) {
        if($(item).hasClass("marketing"+marketing_num)){
            switch(marketing_type){
                case 'Pintuan':
                    $(item).find('.goods').each(function(index,obj){
                        $(obj).find('.pintuan-detail').show();
                        $(obj).find('.seckill-detail').hide();
                        $(obj).find('.auction-detail').hide();
                        $(obj).find('.bargain-detail').hide();
                    });
                    $(item).find('.seckill-title').addClass('none2');
                    $(item).find('.seckill-more').hide();
                    break;
                case 'Seckill':
                    $(item).find('.goods').each(function(index,obj){
                        $(obj).find('.pintuan-detail').hide();
                        $(obj).find('.seckill-detail').show();
                        $(obj).find('.auction-detail').hide();
                        $(obj).find('.bargain-detail').hide();
                    });
                    $(item).find('.seckill-title').removeClass('none2');
                    $(item).find('.seckill-more').show();
                    break;
                case 'Auction':
                    $(item).find('.goods').each(function(index,obj){
                        $(obj).find('.pintuan-detail').hide();
                        $(obj).find('.seckill-detail').hide();
                        $(obj).find('.auction-detail').show();
                        $(obj).find('.bargain-detail').hide();
                    });
                    $(item).find('.seckill-title').addClass('none2');
                    $(item).find('.seckill-more').hide();
                    break;
                case 'Bargain':
                    $(item).find('.goods').each(function(index,obj){
                        $(obj).find('.pintuan-detail').hide();
                        $(obj).find('.seckill-detail').hide();
                        $(obj).find('.auction-detail').hide();
                        $(obj).find('.bargain-detail').show();
                    });
                    $(item).find('.seckill-title').addClass('none2');
                    $(item).find('.seckill-more').hide();
                    break;
                case '0':
                    $(item).find('.goods').each(function(index,obj){
                        $(obj).find('.pintuan-detail').hide();
                        $(obj).find('.seckill-detail').hide();
                        $(obj).find('.auction-detail').hide();
                        $(obj).find('.bargain-detail').hide();
                    });
                    $(item).find('.seckill-title').addClass('none2');
                    $(item).find('.seckill-more').hide();
                    break;
            }
        }
    });
    $(".attr-content-marketing").each(function(idx, item) {
        if($(item).hasClass("marketing_attr"+marketing_num)){
           $(item).find('.marketing-type').val(marketing_type);
        }
    });
}

function changeMarketingPadding(obj){
    var marketing_num = $(obj).parent().parent().parent().attr("marketing_num");
    var marketing_vertical_padding = $(obj).val().split(',')[1];
    $(obj).parent().parent().parent().find('.marketing-vertical-padding').val(marketing_vertical_padding);
    $(obj).parent().find(".marketing-vertical-padding-value").text(marketing_vertical_padding);
    $(".effect-marketing").each(function(idx, item) {
        if($(item).hasClass("marketing"+marketing_num)){
            $(item).css("padding-top",marketing_vertical_padding+"px");
            $(item).css("padding-bottom",marketing_vertical_padding+"px");
        }
    });
}
// 营销活动组件方法结束

// 底部导航栏组件方法开始
function delTabbarItemBtn(obj){
    var tabbar_item = $(obj).parent().attr("tabbar_item");
    var tabbar_item_counts = 0;
    layer.confirm('确定要删除吗?', {icon: 3, title:'信息'}, function(index){
        $(".effect-tabbar-content").find(".tabbars").each(function(idx, item) {
            tabbar_item_counts += 1;
        });
        if(tabbar_item_counts == 2){
            WST.msg('底部导航栏需至少包含2项!',{time:1000,anim: 6});
        }else{
            $(obj).parent().remove();
            $(".effect-tabbar-content").find(".tabbars").each(function(idx, item) {
                if($(item).attr("tabbar_item") == tabbar_item){
                    $(item).remove();
                }
            });
            layer.close(index);
        }
    });
}

function addTabbarItem(obj){
    var tabbar_item_counts = 0;
    var tabbar_item = 0;
    $(".effect-tabbar-content").find(".tabbars").each(function(idx, item) {
        tabbar_item_counts += 1;
        tabbar_item = parseInt($(item).attr("tabbar_item"));
    });
    if(tabbar_item_counts == 5){
        layer.msg("底部导航栏不能超过5项!",{time:1000,anim: 6});
    }else{
        tabbar_item = tabbar_item + 1;
        $(".effect-tabbar-content").append("<div class='tabbars' tabbar_item="+tabbar_item+"><img src='"+base_url+"/upload/custompagedecoration/base/footer-home.png' /><div class='tabbars-info'><p>主页</p></div></div>");
        $(obj).before("<div class='attr-content-tabbar-item-group' tabbar_item="+tabbar_item+"><div class='attr-content-tabbar-img' img_url='upload/custompagedecoration/base/footer-home.png'><label class='d-label'>未选中图片</label><div class='tabbar-picker' id='tabbar-picker-"+tabbar_item+"'><img src='"+base_url+"/upload/custompagedecoration/base/footer-home.png'/></div></div><div class='attr-content-tabbar-img' img_url='upload/custompagedecoration/base/footer-home2.png'><label class='d-label'>选中时图片</label><div class='tabbar-select-picker' id='tabbar-select-picker-"+tabbar_item+"'><img src='"+base_url+"/upload/custompagedecoration/base/footer-home2.png'/></div></div><div class='attr-content-tabbar-info'><label class='tabbar-label'>按钮文字</label><input type='text' value='主页' class='attr-content-tabbar-text' onkeyup='setTabbarItemTitle(this)'/></div><div class='attr-content-tabbar-info'><label class='tabbar-label'>链接地址</label><div class='tabbar-change-link' onclick='changeTabbarLink(this)'>设置链接</div> <input type='hidden' value='' class='attr-content-tabbar-link' /> <input type='hidden' value='' class='attr-content-tabbar-link-text' /><input type='hidden' value='' class='attr-content-tabbar-menu-flag' /></div><div class='attr-btn-del' onclick='delTabbarItemBtn(this)'>X</div></div>");
        WST.upload({
            pick:'#tabbar-picker-'+tabbar_item,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $('#tabbar-picker-'+tabbar_item).parent().attr('img_url',img_url);
                    $('#tabbar-picker-'+tabbar_item).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                    $(".effect-tabbar-content").find(".tabbars").each(function(idx, item) {
                        if($(item).attr("tabbar_item") == tabbar_item){
                            $(item).find("img").attr("src",WST.conf.RESOURCE_PATH+'/'+img_url);
                        }
                    });
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
        WST.upload({
            pick:'#tabbar-select-picker-'+tabbar_item,
            formData: {dir:'custompagedecoration'},
            accept: {extensions: 'gif,jpg,jpeg,png',mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
            callback:function(f){
                var json = WST.toAdminJson(f);
                if(json.status==1){
                    $('#uploadMsg').empty().hide();
                    var img_url = json.savePath+json.thumb; //保存到数据库的路径
                    $('#tabbar-select-picker-'+tabbar_item).parent().attr('img_url',img_url);
                    $('#tabbar-select-picker-'+tabbar_item).find('img').attr('src',WST.conf.RESOURCE_PATH+'/'+json.savePath+json.thumb);
                }else{
                    WST.msg(json.msg,{icon:2});
                }
            },
            progress:function(rate){
                $('#uploadMsg').show().html('已上传'+rate+"%");
            }
        });
    }
}

function setTabbarItemTitle(obj){
    var tabbar_item = $(obj).parent().parent().attr("tabbar_item");
    var tabbar_text = $(obj).val();
    var tabbar_text_length = tabbar_text.length;
    $(".effect-tabbar-content").find(".tabbars").each(function(idx, item) {
        if($(item).attr("tabbar_item") == tabbar_item){
            if(tabbar_text.length>4){
                tabbar_text = tabbar_text.substr(0,4);
            }
            $(item).find(".tabbars-info p").html(tabbar_text);
        }
    });
    $(".attr-content-tabbar .attr-content-tabbar-item-group").each(function(idx, item) {
        if($(item).attr('tabbar_item') == tabbar_item){
            if(tabbar_text_length > 4){
                $(item).find('.tabbar-text-length').html(4);
            }else{
                $(item).find('.tabbar-text-length').html(tabbar_text_length);
            }
        }
    });
}

function changeTabbarLink(obj){
    var menu_flag = $(obj).parent().find('.attr-content-tabbar-menu-flag').val();
    $('.link-attr-content input').each(function(idx,item){
        if($(item).attr('menu-flag') == menu_flag){
            $(item).prop('checked',true);
        }else{
            $(item).prop('checked',false);
        }
    });
    var box = WST.open({title:"链接目标",type:1,content:$('#linkBox'),area: ['80%', '55%'],btn: ['确定','取消'],
        yes:function(){
            var link = $("input[name='custom-link']:checked").val();
            var link_text = $("input[name='custom-link']:checked").attr('link-text');
            var menu_flag = $("input[name='custom-link']:checked").attr('menu-flag');
            $(obj).parent().find('.attr-content-tabbar-link').val(link);
            $(obj).parent().find('.attr-content-tabbar-link-text').val(link_text);
            $(obj).parent().find('.attr-content-tabbar-menu-flag').val(menu_flag);
            $(obj).html(link_text);
            layer.close(box);
        },cancel:function(){
            $('#linkBox').hide();
        },end:function(){
            $('#linkBox').hide();
        }});
}
// 底部导航栏组件方法结束

function submit(){
    var params = {};
    var sort = [];
    var sort_name = [];
    $(".sort").each(function(idx, item) {
        sort.push($(item).val());
    });
    $(".sort-name").each(function(idx, item) {
        sort_name.push($(item).val());
    });
    params.sort = sort;
    params.sort_name = sort_name;
    // 页面名称与id
    params.page_id = $('#pageId').val();
    params.page_type = $('#pageType').val();
    params.page_name = $(".attr-content-page-name").val();
    if(params.page_name==''){
        WST.msg('页面名称不能为空',{time:1000,anim: 6});
        return;
    }
    // 页面设置组件开始
    var page_component = [];
    var page_item_id = "";
    page_component.push($(".attr-content-page-title").val());
    page_component.push($(".attr-content-page-share-title").val());
    page_component.push($(".attr-content-page-img").attr('img_url'));
    params.page_component = page_component;
    params.page_item_id = $(".page-item-id").val();
    //console.log(page_component);
    // 页面设置组件结束

    // 轮播组件开始
    var swiper_component = [];
    var swiper_item_id = [];
    var swiper_img = [];
    var swiper_link = [];
    var swiper_indicator_type = [];
    var swiper_indicator_color = [];
    var swiper_interval = [];
    var img_temp = [];
    var link_temp = [];
    var swiper_padding_top = [];
    var swiper_padding_bottom = [];
    var swiper_padding_left = [];
    var swiper_padding_right = [];
    var swiper_nums = [];
    $(".effect-media-swiper").each(function(idx, item) {
        var swiperName = "swiper"+$(item).attr("swiper_num");
        swiper_component.push(swiperName);
        var swiper_num = $(item).attr("swiper_num");
        swiper_nums.push(swiper_num);
    });
    for(var i=0;i<swiper_nums.length;i++){
        $(".attr-content-swiper-item-img").each(function(index, obj) {
            if($(obj).parent().attr("swiper_num") == swiper_nums[i]){
                var swiperLink = $(obj).find(".attr-content-swiper-link").val();
                var swiperImg = $(obj).find(".attr-content-swiper-img").attr('img_url');
                link_temp.push(swiperLink);
                img_temp.push(swiperImg);
            }
        });
        swiper_link[i] = link_temp;
        swiper_img[i] = img_temp;
        link_temp = [];
        img_temp = [];
        $(".attr-content-swiper").each(function(idx, item) {
            if($(item).attr("swiper_num") == swiper_nums[i]){
                swiper_item_id.push($(item).find(".swiper-item-id").val());
                swiper_indicator_type.push($(item).find(".indicator-type").val());
                swiper_indicator_color.push($(item).find(".indicator-color").val()?$(item).find(".indicator-color").val():"#ffffff");
                swiper_interval.push($(item).find(".interval").val());
                swiper_padding_top.push($(item).find(".swiper-padding-top").val());
                swiper_padding_bottom.push($(item).find(".swiper-padding-bottom").val());
                swiper_padding_left.push($(item).find(".swiper-padding-left").val());
                swiper_padding_right.push($(item).find(".swiper-padding-right").val());
            }
        });
    }
    params.swiper_component = swiper_component;
    params.swiper_link = swiper_link;
    params.swiper_img = swiper_img;
    params.swiper_indicator_type = swiper_indicator_type;
    params.swiper_indicator_color = swiper_indicator_color;
    params.swiper_interval = swiper_interval;
    params.swiper_padding_top = swiper_padding_top;
    params.swiper_padding_bottom = swiper_padding_bottom;
    params.swiper_padding_left = swiper_padding_left;
    params.swiper_padding_right = swiper_padding_right;
    params.swiper_item_id = swiper_item_id;
    // 轮播组件结束

    // 商品组件开始
    var goods_group_component = [];
    var goods_group_item_id = [];
    var goods_group_background_color = [];
    var goods_group_goods_nums = [];
    var show_goods_name = [];
    var show_goods_price = [];
    var show_praise_rate = [];
    var show_sale_num = [];
    var goods_group_columns = [];
    var goods_group_goods_cats = [];
    var show_columns_title = [];
    var goods_group_columns_title = [];
    var title_temp = [];
    var goods_group_columns_goods_select = [];
    var goods_group_columns_goods_select_ids = [];
    var goods_group_columns_goods_select_cats_id = [];
    var select_temp = [];
    var select_ids_temp = [];
    var select_cats_id_temp = [];
    var goods_group_order = [];
    var goods_group_columns_goods_tag = [];
    var tag_temp = [];
    var goods_group_columns_goods_min_price = [];
    var min_price_temp = [];
    var goods_group_columns_goods_max_price = [];
    var max_price_temp = [];
    var goods_group_nums = [];
    $(".effect-goods-group").each(function(idx, item) {
        var goods_group_num = $(item).attr("goods_group_num");
        goods_group_nums.push(goods_group_num);
    });
    for(var i=0;i<goods_group_nums.length;i++){
        $(".attr-content-goods-group").each(function(idx, item) {
            if($(item).attr("goods_group_num") == goods_group_nums[i]){
                var goodsGroupName = "goods_group"+$(item).attr("goods_group_num");
                var bg_color = $(item).find(".goods-group-bg-color").val();
                var is_show_goods_name = $(item).find(".show-goods-name").val();
                var is_show_goods_price = $(item).find(".show-goods-price").val();
                var is_show_praise_rate = $(item).find(".show-praise-rate").val();
                var is_show_sale_num = $(item).find(".show-sale-num").val();
                var is_show_columns_title = $(item).find(".show-columns-title").val();
                var columns = $(item).find(".show-goods-columns").val();
                var goods_nums = $(item).find(".goods-group-goods-nums").val();
                goods_group_component.push(goodsGroupName);
                goods_group_background_color.push(bg_color);
                show_goods_name.push(is_show_goods_name);
                show_goods_price.push(is_show_goods_price);
                show_praise_rate.push(is_show_praise_rate);
                show_sale_num.push(is_show_sale_num);
                goods_group_columns.push(columns);
                goods_group_goods_nums.push(goods_nums);
                show_columns_title.push(is_show_columns_title);
                goods_group_order.push($(item).find(".goods-group-order").val());
                var goods_group_num = $(item).attr("goods_group_num");
                $(".attr-content-goods-group-item-column").each(function(index, obj) {
                    if($(obj).parent().attr("goods_group_num") == goods_group_num){
                        var columnsTitle = $(obj).find(".goods-group-columns-name").val();
                        if(columnsTitle.length>6){
                            columnsTitle = columnsTitle.substr(0,6);
                        }
                        $(obj).find("input[name^='goods-select']").each(function(key,value){
                            if($(value).prop('checked')){
                                select_temp.push($(value).val());
                            }
                        });
                        title_temp.push(columnsTitle);
                        var selectIds = $(obj).find('.goods-select-ids-value').val();
                        select_ids_temp.push(selectIds);
                        var selectCatsId = $(obj).find('.goods-select-cats-id-value').val();
                        select_cats_id_temp.push(selectCatsId);
                        var tag = $(obj).find('.goods-tag-value').val();
                        tag_temp.push(tag);
                        var minPrice = $(obj).find('.goods-min-price-value').val();
                        min_price_temp.push(minPrice);
                        var maxPrice = $(obj).find('.goods-max-price-value').val();
                        max_price_temp.push(maxPrice);
                    }
                });
                goods_group_columns_title[i] = title_temp;
                goods_group_columns_goods_select[i] = select_temp;
                goods_group_columns_goods_select_ids[i] = select_ids_temp;
                goods_group_columns_goods_select_cats_id[i] = select_cats_id_temp;
                goods_group_columns_goods_tag[i] = tag_temp;
                goods_group_columns_goods_min_price[i] = min_price_temp;
                goods_group_columns_goods_max_price[i] = max_price_temp;
                title_temp = [];
                select_temp = [];
                select_ids_temp = [];
                select_cats_id_temp = [];
                tag_temp = [];
                min_price_temp = [];
                max_price_temp = [];
            }
        });
        $(".attr-content-goods-group").each(function(idx, item) {
            if($(item).attr("goods_group_num") == goods_group_nums[i]){
                goods_group_item_id.push($(item).find(".goods-group-item-id").val());
            }
        });
    }
    params.goods_group_component = goods_group_component;
    params.goods_group_background_color = goods_group_background_color;
    params.show_goods_name = show_goods_name;
    params.show_goods_price = show_goods_price;
    params.show_praise_rate = show_praise_rate;
    params.show_sale_num = show_sale_num;
    params.goods_group_columns = goods_group_columns;
    params.goods_group_goods_nums = goods_group_goods_nums;
    params.show_columns_title = show_columns_title;
    params.goods_group_columns_title = goods_group_columns_title;
    params.goods_group_columns_goods_select = goods_group_columns_goods_select;
    params.goods_group_columns_goods_select_ids = goods_group_columns_goods_select_ids;
    params.goods_group_columns_goods_select_cats_id = goods_group_columns_goods_select_cats_id;
    params.goods_group_columns_goods_tag = goods_group_columns_goods_tag;
    params.goods_group_columns_goods_max_price = goods_group_columns_goods_max_price;
    params.goods_group_columns_goods_min_price = goods_group_columns_goods_min_price;
    params.goods_group_order = goods_group_order;
    params.goods_group_item_id = goods_group_item_id;
    //console.log("params:",params);return;
    // 商品组件结束

    // 导航组件开始
    var nav_component = [];
    var nav_item_id = [];
    var nav_background_color = [];
    var nav_count = [];
    var nav_style = [];
    var nav_item_text = [];
    var nav_item_img = [];
    var nav_item_link = [];
    var nav_item_color = [];
    var n_text_temp = [];
    var n_img_temp = [];
    var n_link_temp = [];
    var n_color_temp = [];
    var nav_nums = [];
    $(".effect-nav").each(function(idx, item) {
        var navName = "nav"+$(item).attr("nav_num");
        nav_component.push(navName);
        var nav_num = $(item).attr("nav_num");
        nav_nums.push(nav_num);
    });
    for(var i=0;i<nav_nums.length;i++){
        $(".attr-content-nav-item-group").each(function(index, obj) {
            if($(obj).parent().attr("nav_num") == nav_nums[i]){
                var navText = $(obj).find(".attr-content-nav-text").val();
                if(navText.length>4){
                    navText = navText.substr(0,4);
                }
                var navImg = $(obj).find(".attr-content-nav-img").attr('img_url');
                var navLink = $(obj).find(".attr-content-nav-link").val();
                var navTextColor = $(obj).find(".attr-content-nav-text-color").val();
                n_text_temp.push(navText);
                n_img_temp.push(navImg);
                n_link_temp.push(navLink);
                n_color_temp.push(navTextColor);
            }
        });
        nav_item_text[i] = n_text_temp;
        nav_item_img[i] = n_img_temp;
        nav_item_link[i] = n_link_temp;
        nav_item_color[i] = n_color_temp;
        n_text_temp = [];
        n_img_temp = [];
        n_link_temp = [];
        n_color_temp = [];
        $(".attr-content-nav").each(function(idx, item) {
            if($(item).attr("nav_num") == nav_nums[i]){
                nav_item_id.push($(item).find(".nav-item-id").val());
                nav_background_color.push($(item).find(".nav-bg-color").val());
                nav_count.push($(item).find(".nav-count").val());
                nav_style.push($(item).find(".nav-style").val());
            }
        });
    }

    params.nav_component = nav_component;
    params.nav_background_color = nav_background_color;
    params.nav_count = nav_count;
    params.nav_style = nav_style;
    params.nav_item_text = nav_item_text;
    params.nav_item_img = nav_item_img;
    params.nav_item_link = nav_item_link;
    params.nav_item_color = nav_item_color;
    params.nav_item_id = nav_item_id;
    // 导航组件结束

    // 公告组件开始
    var notice_component = [];
    var notice_item_id = [];
    var notice_background_color = [];
    var notice_text_color = [];
    var notice_img = [];
    var notice_text = [];
    var notice_link = [];
    var notice_text_temp = [];
    var notice_link_temp = [];
    var notice_vertical_padding = [];
    var notice_direction = [];
    $(".attr-content-notice").each(function(idx, item) {
        var noticeName = "notice"+$(item).attr("notice_num");
        var notice_num = $(item).attr("notice_num");
        $(".attr-content-notice-item-group").each(function(index, obj) {
            if($(obj).parent().attr("notice_num") == notice_num){
                var noticeText = $(obj).find(".attr-content-notice-text").val();
                var noticeLink = $(obj).find(".attr-content-notice-link").val();
                notice_text_temp.push(noticeText);
                notice_link_temp.push(noticeLink);
            }
        });
        notice_text[idx] = notice_text_temp;
        notice_link[idx] = notice_link_temp;
        notice_text_temp = [];
        notice_link_temp = [];
        notice_component.push(noticeName);
        notice_item_id.push($(item).find(".notice-item-id").val());
        notice_background_color.push($(item).find(".notice-bg-color").val());
        notice_text_color.push($(item).find(".notice-text-color").val());
        notice_img.push($(item).find(".notice-img").val());
        notice_vertical_padding.push($(item).find(".notice-vertical-padding").val());
        notice_direction.push($(item).find(".notice-direction").val());
    });
    params.notice_component = notice_component;
    params.notice_background_color = notice_background_color;
    params.notice_text_color = notice_text_color;
    params.notice_img = notice_img;
    params.notice_text = notice_text;
    params.notice_link = notice_link;
    params.notice_vertical_padding = notice_vertical_padding;
    params.notice_direction = notice_direction;
    params.notice_item_id = notice_item_id;
    // 公告组件结束

    // 搜索框组件开始
    var search_component = [];
    var search_item_id = [];
    var search_text = [];
    var search_class = [];
    var search_text_alignment = [];
    $(".attr-content-search").each(function(idx, item) {
        var searchName = "search"+$(item).attr("search_num");
        search_component.push(searchName);
        search_item_id.push($(item).find(".search-item-id").val());
        search_text.push($(item).find(".attr-content-search-text").val());
        search_class.push($(item).find(".attr-content-search-class").val());
        search_text_alignment.push($(item).find(".attr-content-search-text-alignment").val());
    });
    params.search_component = search_component;
    params.search_text = search_text;
    params.search_class = search_class;
    params.search_text_alignment = search_text_alignment;
    params.search_item_id = search_item_id;
    // 搜索框组件结束

    // 优惠券组件开始
    var coupon_component = [];
    var coupon_item_id = [];
    var coupon_vertical_padding = [];
    var coupon_background_color = [];
    var coupon_counts = [];
    var coupon_style = [];
    var coupon_select_ids = [];
    var coupon_flag = false;
    var coupons = [];
    $(".effect-coupon").each(function(idx, item) {
        var coupon_num = $(item).attr("coupon_num");
        coupons.push(coupon_num);
    });
    for(var i=0;i<coupons.length;i++){
        $(".attr-content-coupon").each(function(idx, item) {
            if($(item).attr("coupon_num") == coupons[i]){
                var couponName = "coupon"+$(item).attr("coupon_num");
                var coupon_select_ids_value = $(item).find(".coupon-select-ids-value").val();
                var coupon_style_value = $(item).find(".coupon-style").val();
                if(coupon_select_ids_value == ''){
                    WST.msg('您还没有添加优惠券',{time:1000,anim: 6});
                    coupon_flag = true;
                }
                var coupon_limit = 0;
                var coupon_arr = coupon_select_ids_value.split(',');
                var coupon_nums = coupon_arr.length;
                if(coupon_style_value==1){
                    coupon_limit=3;
                    if(coupon_nums < coupon_limit){
                        WST.msg('至少选择三张优惠券',{time:1000,anim: 6});
                        coupon_flag = true;
                    }
                    if(coupon_nums > coupon_limit){
                        WST.msg('已超过优惠券样式允许的最大数量',{time:1000,anim: 6});
                        coupon_flag = true;
                    }
                }else{
                    coupon_limit=2;
                    if(coupon_nums < coupon_limit){
                        WST.msg('至少选择两张优惠券',{time:1000,anim: 6});
                        coupon_flag = true;
                    }
                    if(coupon_nums > coupon_limit){
                        WST.msg('已超过优惠券样式允许的最大数量',{time:1000,anim: 6});
                        coupon_flag = true;
                    }
                }
                coupon_component.push(couponName);
                coupon_item_id.push($(item).find(".coupon-item-id").val());
                coupon_vertical_padding.push($(item).find(".coupon-vertical-padding").val());
                coupon_background_color.push($(item).find(".coupon-bg-color").val());
                coupon_counts.push($(item).find(".coupon-counts").val());
                coupon_style.push(coupon_style_value);
                coupon_select_ids.push(coupon_select_ids_value);
            }
        });
    }
    params.coupon_component = coupon_component;
    params.coupon_vertical_padding = coupon_vertical_padding;
    params.coupon_background_color = coupon_background_color;
    params.coupon_counts = coupon_counts;
    params.coupon_counts = coupon_counts;
    params.coupon_style = coupon_style;
    params.coupon_select_ids = coupon_select_ids;
    params.coupon_item_id = coupon_item_id;
    if(coupon_flag)return;
    //console.log("params:",params);return;
    // 优惠券组件结束

    // 单图组组件开始
    var image_component = [];
    var image_item_id = [];
    var image_img = [];
    var image_link = [];
    var image_img_temp = [];
    var image_link_temp = [];
    var image_padding_top = [];
    var image_padding_bottom = [];
    var image_padding_left = [];
    var image_padding_right = [];
    var image_background_color = [];
    var image_nums = [];
    $(".effect-image").each(function(idx, item) {
        var imageName = "image"+$(item).attr("image_num");
        image_component.push(imageName);
        var image_num = $(item).attr("image_num");
        image_nums.push(image_num);
    });
    for(var i=0;i<image_nums.length;i++){
        $(".attr-content-image-item-img").each(function(index, obj) {
            if($(obj).parent().attr("image_num") == image_nums[i]){
                var imageLink = $(obj).find(".attr-content-image-link").val();
                var imageImg = $(obj).find(".attr-content-image-img").attr('img_url');
                image_link_temp.push(imageLink);
                image_img_temp.push(imageImg);
            }
        });
        image_link[i] = image_link_temp;
        image_img[i] = image_img_temp;
        image_link_temp = [];
        image_img_temp = [];
        $(".attr-content-image").each(function(idx, item) {
            if($(item).attr("image_num") == image_nums[i]){
                image_item_id.push($(item).find(".image-item-id").val());
                image_padding_top.push($(item).find(".image-padding-top").val());
                image_padding_bottom.push($(item).find(".image-padding-bottom").val());
                image_padding_left.push($(item).find(".image-padding-left").val());
                image_padding_right.push($(item).find(".image-padding-right").val());
                image_background_color.push($(item).find(".image-bg-color").val());
            }
        });
    }

    params.image_component = image_component;
    params.image_img = image_img;
    params.image_link = image_link;
    params.image_padding_top = image_padding_top;
    params.image_padding_bottom = image_padding_bottom;
    params.image_padding_left = image_padding_left;
    params.image_padding_right = image_padding_right;
    params.image_background_color = image_background_color;
    params.image_item_id = image_item_id;
    // 单图组组件结束

    // 橱窗组件开始
    var shopwindow_component = [];
    var shopwindow_item_id = [];
    var shopwindow_img = [];
    var shopwindow_link = [];
    var shopwindow_img_temp = [];
    var shopwindow_link_temp = [];
    var shopwindow_layout = [];
    var shopwindow_background_color = [];
    var shopwindow_nums = [];
    $(".effect-shopwindow").each(function(idx, item) {
        var shopwindowName = "shopwindow"+$(item).attr("shopwindow_num");
        shopwindow_component.push(shopwindowName);
        var shopwindow_num = $(item).attr("shopwindow_num");
        shopwindow_nums.push(shopwindow_num);
    });
    for(var i=0;i<shopwindow_nums.length;i++){
        $(".attr-content-shopwindow-item-img").each(function(index, obj) {
            if($(obj).parent().attr("shopwindow_num") == shopwindow_nums[i]){
                var shopwindowLink = $(obj).find(".attr-content-shopwindow-link").val();
                var shopwindowImg = $(obj).find(".attr-content-shopwindow-img").attr('img_url');
                shopwindow_link_temp.push(shopwindowLink);
                shopwindow_img_temp.push(shopwindowImg);
            }
        });
        shopwindow_link[i] = shopwindow_link_temp;
        shopwindow_img[i] = shopwindow_img_temp;
        shopwindow_link_temp = [];
        shopwindow_img_temp = [];
        $(".attr-content-shopwindow").each(function(idx, item) {
            if($(item).attr("shopwindow_num") == shopwindow_nums[i]){
                shopwindow_item_id.push($(item).find(".shopwindow-item-id").val());
                shopwindow_background_color.push($(item).find(".shopwindow-bg-color").val());
                shopwindow_layout.push($(item).find(".shopwindow-layout-value").val());
            }
        });
    }

    params.shopwindow_component = shopwindow_component;
    params.shopwindow_img = shopwindow_img;
    params.shopwindow_link = shopwindow_link;
    params.shopwindow_background_color = shopwindow_background_color;
    params.shopwindow_layout = shopwindow_layout;
    params.shopwindow_item_id = shopwindow_item_id;
    //console.log("params:",params);return;
    // 橱窗组件结束

    // 视频组件开始
    var video_component = [];
    var video_item_id = [];
    var video_img = [];
    var video_link = [];
    var video_vertical_padding = [];
    var video_nums = [];
    $(".effect-video").each(function(idx, item) {
        var videoName = "video"+$(item).attr("video_num");
        var videoNum = $(item).attr("video_num");
        video_component.push(videoName);
        video_nums.push(videoNum);
    });
    for(var i=0;i<video_nums.length;i++) {
        $(".attr-content-video").each(function (idx, item) {
            if($(item).attr("video_num") == video_nums[i]){
                video_item_id.push($(item).find(".video-item-id").val());
                video_img.push($(item).find(".attr-content-video-img").attr("img_url"));
                video_link.push($(item).find(".attr-content-video-link").val());
                video_vertical_padding.push($(item).find(".video-vertical-padding").val());
            }
        });
    }
    params.video_component = video_component;
    params.video_link = video_link;
    params.video_img = video_img;
    params.video_vertical_padding = video_vertical_padding;
    params.video_item_id = video_item_id;
    // 视频组件结束

    // 辅助空白组件开始
    var blank_component = [];
    var blank_item_id = [];
    var blank_height = [];
    var blank_background_color = [];
    var blank_nums = [];
    $(".effect-blank").each(function(idx, item) {
        var blankName = "blank"+$(item).attr("blank_num");
        var blankNum = $(item).attr("blank_num");
        blank_component.push(blankName);
        blank_nums.push(blankNum);
    });
    for(var i=0;i<blank_nums.length;i++){
        $(".attr-content-blank").each(function(idx, item) {
            if($(item).attr("blank_num") == blank_nums[i]){
                blank_item_id.push($(item).find(".blank-item-id").val());
                blank_height.push($(item).find(".blank-height").val());
                blank_background_color.push($(item).find(".blank-bg-color").val());
            }
        });
    }
    params.blank_component = blank_component;
    params.blank_height = blank_height;
    params.blank_background_color = blank_background_color;
    params.blank_item_id = blank_item_id;
    // 辅助空白组件结束

    // 辅助线组件开始
    var line_component = [];
    var line_item_id = [];
    var line_background_color = [];
    var line_class = [];
    var line_border_color = [];
    var line_height = [];
    var line_vertical_margin = [];
    var line_nums = [];
    $(".effect-line").each(function(idx, item) {
        var lineName = "line"+$(item).attr("line_num");
        var lineNum = $(item).attr("line_num");
        line_component.push(lineName);
        line_nums.push(lineNum);
    });
    for(var i=0;i<line_nums.length;i++){
        $(".attr-content-line").each(function(idx, item) {
            if($(item).attr("line_num") == line_nums[i]){
                line_item_id.push($(item).find(".line-item-id").val());
                line_background_color.push($(item).find(".line-bg-color").val());
                line_class.push($(item).find(".line-class").val());
                line_border_color.push($(item).find(".line-border-color").val());
                line_height.push($(item).find(".line-height").val());
                line_vertical_margin.push($(item).find(".line-vertical-margin").val());
            }
        });
    }
    params.line_component = line_component;
    params.line_background_color = line_background_color;
    params.line_class = line_class;
    params.line_border_color = line_border_color;
    params.line_height = line_height;
    params.line_vertical_margin = line_vertical_margin;
    params.line_item_id = line_item_id;
    // 辅助线组件结束

    // 富文本组件开始
    var text_component = [];
    var text_item_id = [];
    var text_background_color = [];
    var text_vertical_padding = [];
    var text_horizontal_padding = [];
    var text_text = [];
    var text_nums = [];
    $(".effect-text").each(function(idx, item) {
        var textName = "text"+$(item).attr("text_num");
        var textNum = $(item).attr("text_num");
        text_component.push(textName);
        text_text.push($(item).find(".text").html());
        text_nums.push(textNum);
    });
    for(var i=0;i<text_nums.length;i++){
        $(".attr-content-text").each(function(idx, item) {
            if($(item).attr("text_num") == text_nums[i]){
                text_item_id.push($(item).find(".text-item-id").val());
                text_background_color.push($(item).find(".text-bg-color").val());
                text_vertical_padding.push($(item).find(".text-vertical-padding").val());
                text_horizontal_padding.push($(item).find(".text-horizontal-padding").val());
            }
        });
    }

    params.text_component = text_component;
    params.text_background_color = text_background_color;
    params.text_vertical_padding = text_vertical_padding;
    params.text_horizontal_padding = text_horizontal_padding;
    params.text_text = text_text;
    params.text_item_id = text_item_id;
    // 富文本组件结束

    // 单文本组件开始
    var txt_component = [];
    var txt_item_id = [];
    var txt_background_color = [];
    var txt_text_color = [];
    var txt_text_alignment = [];
    var txt_text = [];
    var txt_link = [];
    var txt_vertical_padding = [];
    var txt_horizontal_padding = [];
    var txt_nums = [];
    $(".effect-txt").each(function(idx, item) {
        var txtName = "txt"+$(item).attr("txt_num");
        var txtNum = $(item).attr("txt_num");
        txt_component.push(txtName);
        txt_nums.push(txtNum);
    });
    for(var i=0;i<txt_nums.length;i++){
        $(".attr-content-txt").each(function(idx, item) {
            if($(item).attr("txt_num") == txt_nums[i]){
                txt_item_id.push($(item).find(".txt-item-id").val());
                txt_background_color.push($(item).find(".txt-bg-color").val());
                txt_text_color.push($(item).find(".txt-text-color").val());
                var txt_text_content = $(item).find(".attr-content-txt-text").val();
                if(txt_text_content.length>15){
                    txt_text_content = txt_text_content.substr(0,15);
                }
                txt_text.push(txt_text_content);
                txt_link.push($(item).find(".attr-content-txt-link").val());
                txt_text_alignment.push($(item).find(".attr-content-txt-text-alignment").val());
                txt_vertical_padding.push($(item).find(".txt-vertical-padding").val());
                txt_horizontal_padding.push($(item).find(".txt-horizontal-padding").val());
            }
        });
    }
    params.txt_component = txt_component;
    params.txt_background_color = txt_background_color;
    params.txt_text_color = txt_text_color;
    params.txt_text_alignment = txt_text_alignment;
    params.txt_text = txt_text;
    params.txt_link = txt_link;
    params.txt_vertical_padding = txt_vertical_padding;
    params.txt_horizontal_padding = txt_horizontal_padding;
    params.txt_item_id = txt_item_id;
    // 单文本组件结束

    // 图文列表组件开始
    var image_text_component = [];
    var image_text_item_id = [];
    var image_text_vertical_padding = [];
    var image_text_style = [];
    var image_text_title = [];
    var image_text_desc = [];
    var image_text_link = [];
    var image_text_img = [];
    var image_text_img_temp = [];
    var image_text_nums = [];
    $(".effect-image-text").each(function(idx, item) {
        var imageTextName = "image_text"+$(item).attr("image_text_num");
        image_text_component.push(imageTextName);
        var image_text_num = $(item).attr("image_text_num");
        image_text_nums.push(image_text_num);
    });
    for(var i=0;i<image_text_nums.length;i++){
        $(".attr-content-image-text-item-img").each(function(index, obj) {
            if($(obj).parent().attr("image_text_num") == image_text_nums[i]){
                $(obj).find('.image-text-img').each(function(key,value){
                    if($(value).hasClass('none')){
                    }else{
                        var imageTextImg = $(value).find(".attr-content-image-text-img").attr('img_url');
                        image_text_img_temp.push(imageTextImg);
                    }
                });
            }
        });
        image_text_img[i] = image_text_img_temp;
        image_text_img_temp = [];
        $(".attr-content-image-text").each(function(idx, item) {
            if($(item).attr("image_text_num") == image_text_nums[i]){
                image_text_item_id.push($(item).find(".image-text-item-id").val());
                image_text_style.push($(item).find(".image-text-style").val());
                var image_text_title_text = $(item).find(".attr-content-image-text-title").val();
                if(image_text_title_text.length>20){
                    image_text_title_text = image_text_title_text.substr(0,20);
                }
                image_text_title.push(image_text_title_text);
                var image_text_desc_text = $(item).find(".attr-content-image-text-desc").val();
                if(image_text_desc_text.length>20){
                    image_text_desc_text = image_text_desc_text.substr(0,200);
                }
                image_text_desc.push(image_text_desc_text);
                image_text_link.push($(item).find(".attr-content-image-text-link").val());
                image_text_vertical_padding.push($(item).find(".image-text-vertical-padding").val());
            }
        });
    }

    params.image_text_component = image_text_component;
    params.image_text_img = image_text_img;
    params.image_text_style = image_text_style;
    params.image_text_title = image_text_title;
    params.image_text_desc = image_text_desc;
    params.image_text_link = image_text_link;
    params.image_text_vertical_padding = image_text_vertical_padding;
    params.image_text_item_id = image_text_item_id;
    // 图文列表组件结束

    // 多店铺组件开始
    var shop_component = [];
    var shop_item_id = [];
    var shop_title = [];
    var shop_search_radius = [];
    $(".effect-shop").each(function(idx, item) {
        var shopName = "shop"+$(item).attr("shop_num");
        shop_component.push(shopName);
    });
    $(".attr-content-shop").each(function(idx, item) {
        shop_item_id.push($(item).find(".shop-item-id").val());
        var shop_title_text = $(item).find(".attr-content-shop-title").val();
        if(shop_title_text.length>10){
            shop_title_text = shop_title_text.substr(0,10);
        }
        shop_title.push(shop_title_text);
        shop_search_radius.push($(item).find(".attr-content-shop-search-radius").val());
    });
    params.shop_component = shop_component;
    params.shop_title = shop_title;
    params.shop_search_radius = shop_search_radius;
    params.shop_item_id = shop_item_id;
    // 多店铺组件结束

    // 新闻组件开始
    var new_component = [];
    var new_item_id = [];
    var new_title = [];
    var new_count = [];
    var new_select_ids = [];
    var new_nums = [];
    $(".effect-new").each(function(idx, item) {
        var newName = "shop"+$(item).attr("new_num");
        var newNum = $(item).attr("new_num");
        new_component.push(newName);
        new_nums.push(newNum);
    });
    for(var i=0;i<new_nums.length;i++){
        $(".attr-content-new").each(function(idx, item) {
            if($(item).attr("new_num") == new_nums[i]){
                new_item_id.push($(item).find(".new-item-id").val());
                new_count.push($(item).find(".new-count").val());
                var new_title_text = $(item).find(".attr-content-new-title").val();
                if(new_title_text.length>8){
                    new_title_text = new_title_text.substr(0,8);
                }
                new_title.push(new_title_text);
                new_select_ids.push($(item).find(".new-select-ids-value").val());
            }
        });
    }
    params.new_component = new_component;
    params.new_title = new_title;
    params.new_count = new_count;
    params.new_select_ids = new_select_ids;
    params.new_item_id = new_item_id;
    // 新闻组件结束

    // 营销活动组件开始
    var marketing_component = [];
    var marketing_item_id = [];
    var marketing_type = [];
    var marketing_title = [];
    var marketing_vertical_padding = [];
    var marketing_flag = false;
    var marketing_nums = [];
    $(".effect-marketing").each(function(idx, item) {
        var marketingName = "marketing"+$(item).attr("marketing_num");
        var marketingNum = $(item).attr("marketing_num");
        marketing_component.push(marketingName);
        marketing_nums.push(marketingNum);
    });
    for(var i=0;i<marketing_nums.length;i++){
        $(".attr-content-marketing").each(function(idx, item) {
            if($(item).attr("marketing_num") == marketing_nums[i]){
                marketing_item_id.push($(item).find(".marketing-item-id").val());
                var marketing_type_value = $(item).find(".marketing-type").val();
                if(marketing_type_value == 0){
                    WST.msg('请选择营销活动',{time:1000,anim: 6});
                    marketing_flag = true;
                }else{
                    marketing_type.push(marketing_type_value);
                }
                var marketing_title_text = $(item).find(".attr-content-marketing-title").val();
                if(marketing_title_text.length>6){
                    marketing_title_text = marketing_title_text.substr(0,6);
                }
                marketing_title.push(marketing_title_text);
                marketing_vertical_padding.push($(item).find(".marketing-vertical-padding").val());
            }
        });
    }

    params.marketing_component = marketing_component;
    params.marketing_type = marketing_type;
    params.marketing_title = marketing_title;
    params.marketing_vertical_padding = marketing_vertical_padding;
    params.marketing_item_id = marketing_item_id;
    //console.log("params:",params);return;
    if(marketing_flag)return;
    // 营销活动组件结束

    // 底部导航栏组件开始
    var tabbar_component = [];
    var tabbar_icon = [];
    var tabbar_select_icon = [];
    var tabbar_link = [];
    var tabbar_link_text = [];
    var tabbar_menu_flag = [];
    var tabbar_text = [];
    var tabbar_item_id = "";
    var tabbar_flag = false;
    tabbar_component.push("tabbar");
    tabbar_component.push($(".tabbar-bg-color").val());
    tabbar_component.push($(".tabbar-border-color").val());
    tabbar_component.push($(".tabbar-text-color").val());
    tabbar_component.push($(".tabbar-text-checked-color").val());
    tabbar_item_id = $(".tabbar-item-id").val();
    $(".attr-content-tabbar-item-group").each(function(idx, item) {
        tabbar_icon.push($(item).find(".tabbar-picker").parent().attr("img_url"));
        tabbar_select_icon.push($(item).find(".tabbar-select-picker").parent().attr("img_url"));
        tabbar_link.push($(item).find(".attr-content-tabbar-link").val());
        tabbar_link_text.push($(item).find(".attr-content-tabbar-link-text").val());
        tabbar_menu_flag.push($(item).find(".attr-content-tabbar-menu-flag").val());
        var tabbar_text_content = $(item).find(".attr-content-tabbar-text").val();
        if(tabbar_text_content.length == 0){
            WST.msg('底部导航按钮文字不能为空',{time:1000,anim: 6});
            tabbar_flag = true;
        }
        if(tabbar_text_content.length>4){
            tabbar_text_content = tabbar_text_content.substr(0,4);
        }
        tabbar_text.push(tabbar_text_content);
    });
    if(tabbar_flag)return;
    params.tabbar_component = tabbar_component;
    params.tabbar_icon = tabbar_icon;
    params.tabbar_select_icon = tabbar_select_icon;
    params.tabbar_link = tabbar_link;
    params.tabbar_link_text = tabbar_link_text;
    params.tabbar_menu_flag = tabbar_menu_flag;
    params.tabbar_text = tabbar_text;
    params.tabbar_item_id = tabbar_item_id;
    //console.log("params:",params);return;
    // 底部导航栏组件结束

    //console.log(params);
    //return;
    //console.log(sort);
    //console.log(sort_name);
    $.post(WST.U('admin/custompagedecoration/edit'),params,function(res){
        var json = WST.toAdminJson(res);
        if(json.status==1){
            WST.msg("操作成功",{icon:1});
            setTimeout(function(){
                location.href=WST.U('admin/custompages/index','type='+params.page_type);
            },1000);
        }else{
            WST.msg(json.msg,{icon:2});
        }
    });
}

