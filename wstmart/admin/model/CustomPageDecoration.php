<?php
namespace wstmart\admin\model;
use think\Db;
/**
 * ============================================================================
 * WSTMart多用户商城
 * 版权所有 2016-2066 广州商淘信息科技有限公司，并保留所有权利。
 * 官网地址:http://www.wstmart.net
 * 交流社区:http://bbs.shangtao.net
 * 联系QQ:153289970
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！未经本公司授权您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 自定义页面详情业务处理
 */
class CustomPageDecoration extends Base{
	/**
	 * 获取自定义页面装修内容
	 */
	public function pageQuery($pageId=0){
        $data = $this->where(['dataFlag'=>1,'pageId'=>$pageId])->order("sort asc")->select();
        foreach($data as $k => $v){
            $data[$k]["attr"] = unserialize($data[$k]["attr"]);
        }
		return $data;
	}

	/*
	 * 获取自定义页面的页面信息
	 */
	public function pageDetail($pageId=0){
        return Db::name('custom_pages')->where(['id'=>$pageId])->find();
    }
    
    /*
     * 保存自定义页面装修内容
     */
    public function edit(){
        $pageId = input('page_id',0);
        $pageName = input('page_name');
        $pageType = input('page_type');
        $page_component = input("page_component");
        $page_data_attr = [];
        for($i=0;$i<count($page_component);$i++){
            if($i==0){
                $page_data_attr["title"] = $page_component[$i];
            }
            if($i==1){
                $page_data_attr["share_title"] = $page_component[$i];
            }
            if($i==2){
                $page_data_attr["poster"] = $page_component[$i];
            }
        }
        $pageData = [
            'pageName'=>$pageName,
            'createTime'=>date('Y-m-d H:i:s'),
            'dataFlag'=>1,
            'pageType'=>$pageType,
            "attr"=>serialize($page_data_attr),
        ];
        Db::startTrans();
        try{
            if($pageId){
                Db::name('custom_pages')->where("id","=",$pageId)->update($pageData);
            }else{
                $pageId = Db::name('custom_pages')->insertGetId($pageData);
            }
            $swiper_item_id = input("swiper_item_id");
            $goods_group_item_id = input("goods_group_item_id");
            $nav_item_id = input("nav_item_id");
            $notice_item_id = input("notice_item_id");
            $search_item_id = input("search_item_id");
            $coupon_item_id = input("coupon_item_id");
            $image_item_id = input("image_item_id");
            $shopwindow_item_id = input("shopwindow_item_id");
            $video_item_id = input("video_item_id");
            $blank_item_id = input("blank_item_id");
            $line_item_id = input("line_item_id");
            $text_item_id = input("text_item_id");
            $txt_item_id = input("txt_item_id");
            $image_text_item_id = input("image_text_item_id");
            $shop_item_id = input("shop_item_id");
            $new_item_id = input("new_item_id");
            $marketing_item_id = input("marketing_item_id");
            if(!$swiper_item_id){
                $this->deleteComponent("swiper");
            }
            if(!$goods_group_item_id){
                $this->deleteComponent("goods_group");
            }
            if(!$nav_item_id){
                $this->deleteComponent("nav");
            }
            if(!$notice_item_id){
                $this->deleteComponent("notice");
            }
            if(!$search_item_id){
                $this->deleteComponent("search");
            }
            if(!$coupon_item_id){
                $this->deleteComponent("coupon");
            }
            if(!$image_item_id){
                $this->deleteComponent("image");
            }
            if(!$shopwindow_item_id){
                $this->deleteComponent("shopwindow");
            }
            if(!$video_item_id){
                $this->deleteComponent("video");
            }
            if(!$blank_item_id){
                $this->deleteComponent("blank");
            }
            if(!$line_item_id){
                $this->deleteComponent("line");
            }
            if(!$text_item_id){
                $this->deleteComponent("text");
            }
            if(!$txt_item_id){
                $this->deleteComponent("txt");
            }
            if(!$image_text_item_id){
                $this->deleteComponent("image_text");
            }
            if(!$shop_item_id){
                $this->deleteComponent("shop");
            }
            if(!$new_item_id){
                $this->deleteComponent("new");
            }
            if(!$marketing_item_id){
                $this->deleteComponent("marketing");
            }

            // 图片轮播开始
            $swiper_data = [];
            $swiper_data_attr = [];
            $swiper_component = (array)input("swiper_component");
            $swiper_link = input("swiper_link");
            $swiper_img = input("swiper_img");
            $swiper_indicator_type = input("swiper_indicator_type");
            $swiper_indicator_color = input("swiper_indicator_color");
            $swiper_interval = input("swiper_interval");
            $swiper_padding_top = input("swiper_padding_top");
            $swiper_padding_bottom = input("swiper_padding_bottom");
            $swiper_padding_left = input("swiper_padding_left");
            $swiper_padding_right = input("swiper_padding_right");
            for($i=0;$i<count($swiper_component);$i++){
                $swiper_data_attr[$swiper_component[$i]]["indicator_type"] = $swiper_indicator_type[$i];
                $swiper_data_attr[$swiper_component[$i]]["indicator_color"] = $swiper_indicator_color[$i];
                $swiper_data_attr[$swiper_component[$i]]["interval"] = $swiper_interval[$i];
                $swiper_data_attr[$swiper_component[$i]]["padding_top"] = $swiper_padding_top[$i];
                $swiper_data_attr[$swiper_component[$i]]["padding_bottom"] = $swiper_padding_bottom[$i];
                $swiper_data_attr[$swiper_component[$i]]["padding_left"] = $swiper_padding_left[$i];
                $swiper_data_attr[$swiper_component[$i]]["padding_right"] = $swiper_padding_right[$i];
                $swiper_data_attr[$swiper_component[$i]]["link"] = $swiper_link[$i];
                $swiper_data_attr[$swiper_component[$i]]["img"] = $swiper_img[$i];
                $swiper_data = [
                    "pageId"=>$pageId,
                    "name"=>"swiper",
                    "attr"=>serialize($swiper_data_attr[$swiper_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($swiper_item_id)){
                    $this->where("id","=",$swiper_item_id[$i])->update($swiper_data);
                }else{
                    $id = $this->insertGetId($swiper_data);
                    $swiper_item_id[] = $id;
                }
            }
            if($swiper_item_id){
                $this->updateComponent($swiper_item_id,"swiper");
            }
            // 图片轮播结束

            // 商品组开始
            $goods_group_data = [];
            $goods_group_data_attr = [];
            $goods_group_component = (array)input("goods_group_component");
            $goods_group_background_color = input("goods_group_background_color");
            $show_goods_name = input("show_goods_name");
            $show_goods_price = input("show_goods_price");
            $show_praise_rate = input("show_praise_rate");
            $show_sale_num = input("show_sale_num");
            $goods_group_columns = input("goods_group_columns");
            $goods_group_goods_cats = input("goods_group_goods_cats");
            $goods_group_goods_nums = input("goods_group_goods_nums");
            $show_columns_title = input("show_columns_title");
            $goods_group_columns_title = input("goods_group_columns_title");
            $goods_group_columns_goods_select = input("goods_group_columns_goods_select"); // 选取商品类型
            $goods_group_columns_goods_select_ids = input("goods_group_columns_goods_select_ids"); // 手动添加的商品id
            $goods_group_columns_goods_select_cats_id = input("goods_group_columns_goods_select_cats_id"); // 按条件选取的分类id
            $goods_group_columns_goods_tag = input("goods_group_columns_goods_tag"); // 按条件选取的商品标签
            $goods_group_columns_goods_min_price = input("goods_group_columns_goods_min_price"); // 按条件设置的商品最低价
            $goods_group_columns_goods_max_price = input("goods_group_columns_goods_max_price"); // 按条件设置的商品最高价
            $goods_group_order = input("goods_group_order");
            for($i=0;$i<count($goods_group_component);$i++){
                $goods_group_data_attr[$goods_group_component[$i]]["background_color"] = $goods_group_background_color[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["show_goods_name"] = $show_goods_name[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["show_goods_price"] = $show_goods_price[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["show_praise_rate"] = $show_praise_rate[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["show_sale_num"] = $show_sale_num[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["show_columns_title"] = $show_columns_title[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["columns"] = $goods_group_columns[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["goods_cat"] = $goods_group_goods_cats[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["goods_nums"] = $goods_group_goods_nums[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["columns_title"] = $goods_group_columns_title[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["goods_select"] = $goods_group_columns_goods_select[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["goods_select_ids"] = $goods_group_columns_goods_select_ids[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["goods_select_cats_id"] = $goods_group_columns_goods_select_cats_id[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["goods_tag"] = $goods_group_columns_goods_tag[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["goods_min_price"] = $goods_group_columns_goods_min_price[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["goods_max_price"] = $goods_group_columns_goods_max_price[$i];
                $goods_group_data_attr[$goods_group_component[$i]]["goods_order"] = $goods_group_order[$i];
                $goods_group_data = [
                    "pageId"=>$pageId,
                    "name"=>"goods_group",
                    "attr"=>serialize($goods_group_data_attr[$goods_group_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($goods_group_item_id)){
                    $this->where("id","=",$goods_group_item_id[$i])->update($goods_group_data);
                }else{
                    $id = $this->insertGetId($goods_group_data);
                    $goods_group_item_id[] = $id;
                }
            }
            if($goods_group_item_id){
                $this->updateComponent($goods_group_item_id,"goods_group");
            }
            // 商品组结束

            // 导航组开始
            $nav_data = [];
            $nav_data_attr = [];
            $nav_component = (array)input("nav_component");
            $nav_background_color = input("nav_background_color");
            $nav_count = input("nav_count");
            $nav_style = input("nav_style");
            $nav_item_text = input("nav_item_text");
            $nav_item_img = input("nav_item_img");
            $nav_item_link = input("nav_item_link");
            $nav_item_color = input("nav_item_color");
            for($i=0;$i<count($nav_component);$i++){
                $nav_data_attr[$nav_component[$i]]["background_color"] = $nav_background_color[$i];
                $nav_data_attr[$nav_component[$i]]["count"] = $nav_count[$i];
                $nav_data_attr[$nav_component[$i]]["style"] = $nav_style[$i];
                $nav_data_attr[$nav_component[$i]]["item_text"] = $nav_item_text[$i];
                $nav_data_attr[$nav_component[$i]]["item_img"] = $nav_item_img[$i];
                $nav_data_attr[$nav_component[$i]]["item_link"] = $nav_item_link[$i];
                $nav_data_attr[$nav_component[$i]]["item_color"] = $nav_item_color[$i];
                $nav_data = [
                    "pageId"=>$pageId,
                    "name"=>"nav",
                    "attr"=>serialize($nav_data_attr[$nav_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($nav_item_id)){
                    $this->where("id","=",$nav_item_id[$i])->update($nav_data);
                }else{
                    $id = $this->insertGetId($nav_data);
                    $nav_item_id[] = $id;
                }
            }
            if($nav_item_id){
                $this->updateComponent($nav_item_id,"nav");
            }
            // 导航组结束

            // 公告组件开始
            $notice_data = [];
            $notice_data_attr = [];
            $notice_component = (array)input("notice_component");
            $notice_background_color = input("notice_background_color");
            $notice_text_color = input("notice_text_color");
            $notice_img = input("notice_img");
            $notice_text = input("notice_text");
            $notice_link = input("notice_link");
            $notice_vertical_padding = input("notice_vertical_padding");
            $notice_direction = input("notice_direction");
            for($i=0;$i<count($notice_component);$i++){
                $notice_data_attr[$notice_component[$i]]["background_color"] = $notice_background_color[$i];
                $notice_data_attr[$notice_component[$i]]["text_color"] = $notice_text_color[$i];
                $notice_data_attr[$notice_component[$i]]["img"] = $notice_img[$i];
                $notice_data_attr[$notice_component[$i]]["text"] = $notice_text[$i];
                $notice_data_attr[$notice_component[$i]]["link"] = $notice_link[$i];
                $notice_data_attr[$notice_component[$i]]["vertical_padding"] = $notice_vertical_padding[$i];
                $notice_data_attr[$notice_component[$i]]["direction"] = $notice_direction[$i];
                $notice_data = [
                    "pageId"=>$pageId,
                    "name"=>"notice",
                    "attr"=>serialize($notice_data_attr[$notice_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($notice_item_id)){
                    $this->where("id","=",$notice_item_id[$i])->update($notice_data);
                }else{
                    $id = $this->insertGetId($notice_data);
                    $notice_item_id[] = $id;
                }
            }
            if($notice_item_id){
                $this->updateComponent($notice_item_id,"notice");
            }
            // 公告组件结束

            // 搜索框组件开始
            $search_data = [];
            $search_data_attr = [];
            $search_component = (array)input("search_component");
            $search_text = input("search_text");
            $search_class = input("search_class");
            $search_text_alignment = input("search_text_alignment");
            for($i=0;$i<count($search_component);$i++){
                $search_data_attr[$search_component[$i]]["text"] = $search_text[$i];
                $search_data_attr[$search_component[$i]]["class"] = $search_class[$i];
                $search_data_attr[$search_component[$i]]["alignment"] = $search_text_alignment[$i];
                $search_data = [
                    "pageId"=>$pageId,
                    "name"=>"search",
                    "attr"=>serialize($search_data_attr[$search_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($search_item_id)){
                    $this->where("id","=",$search_item_id[$i])->update($search_data);
                }else{
                    $id = $this->insertGetId($search_data);
                    $search_item_id[] = $id;
                }
            }
            if($search_item_id){
                $this->updateComponent($search_item_id,"search");
            }

            // 搜索框组件结束

            // 优惠券组件开始
            $coupon_data = [];
            $coupon_data_attr = [];
            $coupon_component = (array)input("coupon_component");
            $coupon_vertical_padding = input("coupon_vertical_padding");
            $coupon_background_color = input("coupon_background_color");
            $coupon_counts = input("coupon_counts");
            $coupon_style = input("coupon_style"); // 选取商品类型
            $coupon_select_ids = input("coupon_select_ids"); // 手动添加的商品id
            for($i=0;$i<count($coupon_component);$i++){
                $coupon_data_attr[$coupon_component[$i]]["vertical_padding"] = $coupon_vertical_padding[$i];
                $coupon_data_attr[$coupon_component[$i]]["background_color"] = $coupon_background_color[$i];
                $coupon_data_attr[$coupon_component[$i]]["counts"] = $coupon_counts[$i];
                $coupon_data_attr[$coupon_component[$i]]["style"] = $coupon_style[$i];
                $coupon_data_attr[$coupon_component[$i]]["coupon_select_ids"] = $coupon_select_ids[$i];
                $coupon_data = [
                    "pageId"=>$pageId,
                    "name"=>"coupon",
                    "attr"=>serialize($coupon_data_attr[$coupon_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($coupon_item_id)){
                    $this->where("id","=",$coupon_item_id[$i])->update($coupon_data);
                }else{
                    $id = $this->insertGetId($coupon_data);
                    $coupon_item_id[] = $id;
                }
            }
            if($coupon_item_id){
                $this->updateComponent($coupon_item_id,"coupon");
            }
            // 优惠券组件结束

            // 单图组组件开始
            $image_data = [];
            $image_data_attr = [];
            $image_component = (array)input("image_component");
            $image_link = input("image_link");
            $image_img = input("image_img");
            $image_padding_top = input("image_padding_top");
            $image_padding_bottom = input("image_padding_bottom");
            $image_padding_left = input("image_padding_left");
            $image_padding_right = input("image_padding_right");
            $image_background_color = input("image_background_color");
            for($i=0;$i<count($image_component);$i++){
                $image_data_attr[$image_component[$i]]["link"] = $image_link[$i];
                $image_data_attr[$image_component[$i]]["img"] = $image_img[$i];
                $image_data_attr[$image_component[$i]]["padding_top"] = $image_padding_top[$i];
                $image_data_attr[$image_component[$i]]["padding_bottom"] = $image_padding_bottom[$i];
                $image_data_attr[$image_component[$i]]["padding_left"] = $image_padding_left[$i];
                $image_data_attr[$image_component[$i]]["padding_right"] = $image_padding_right[$i];
                $image_data_attr[$image_component[$i]]["background_color"] = $image_background_color[$i];
                $image_data = [
                    "pageId"=>$pageId,
                    "name"=>"image",
                    "attr"=>serialize($image_data_attr[$image_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($image_item_id)){
                    $this->where("id","=",$image_item_id[$i])->update($image_data);
                }else{
                    $id = $this->insertGetId($image_data);
                    $image_item_id[] = $id;
                }
            }
            if($image_item_id){
                $this->updateComponent($image_item_id,"image");
            }
            // 单图组组件结束

            // 橱窗组件开始
            $shopwindow_data = [];
            $shopwindow_data_attr = [];
            $shopwindow_component = (array)input("shopwindow_component");
            $shopwindow_link = input("shopwindow_link");
            $shopwindow_img = input("shopwindow_img");
            $shopwindow_background_color = input("shopwindow_background_color");
            $shopwindow_layout = input("shopwindow_layout");
            for($i=0;$i<count($shopwindow_component);$i++){
                $shopwindow_data_attr[$shopwindow_component[$i]]["link"] = $shopwindow_link[$i];
                $shopwindow_data_attr[$shopwindow_component[$i]]["img"] = $shopwindow_img[$i];
                $shopwindow_data_attr[$shopwindow_component[$i]]["background_color"] = $shopwindow_background_color[$i];
                $shopwindow_data_attr[$shopwindow_component[$i]]["layout"] = $shopwindow_layout[$i];
                $shopwindow_data = [
                    "pageId"=>$pageId,
                    "name"=>"shopwindow",
                    "attr"=>serialize($shopwindow_data_attr[$shopwindow_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($shopwindow_item_id)){
                    $this->where("id","=",$shopwindow_item_id[$i])->update($shopwindow_data);
                }else{
                    $id = $this->insertGetId($shopwindow_data);
                    $shopwindow_item_id[] = $id;
                }
            }
            if($shopwindow_item_id){
                $this->updateComponent($shopwindow_item_id,"shopwindow");
            }
            // 橱窗组件结束

            // 视频组件开始
            $video_data = [];
            $video_data_attr = [];
            $video_component = (array)input("video_component");
            $video_link = input("video_link");
            $video_img = input("video_img");
            $video_vertical_padding = input("video_vertical_padding");
            for($i=0;$i<count($video_component);$i++){
                $video_data_attr[$video_component[$i]]["link"] = $video_link[$i];
                $video_data_attr[$video_component[$i]]["img"] = $video_img[$i];
                $video_data_attr[$video_component[$i]]["vertical_padding"] = $video_vertical_padding[$i];
                $video_data = [
                    "pageId"=>$pageId,
                    "name"=>"video",
                    "attr"=>serialize($video_data_attr[$video_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($video_item_id)){
                    $this->where("id","=",$video_item_id[$i])->update($video_data);
                }else{
                    $id = $this->insertGetId($video_data);
                    $video_item_id[] = $id;
                }
            }
            if($video_item_id){
                $this->updateComponent($video_item_id,"video");
            }
            // 视频组件结束

            // 辅助空白组件开始
            $blank_data = [];
            $blank_data_attr = [];
            $blank_component = (array)input("blank_component");
            $blank_height = input("blank_height");
            $blank_background_color = input("blank_background_color");
            for($i=0;$i<count($blank_component);$i++){
                $blank_data_attr[$blank_component[$i]]["height"] = $blank_height[$i];
                $blank_data_attr[$blank_component[$i]]["background_color"] = $blank_background_color[$i];
                $blank_data = [
                    "pageId"=>$pageId,
                    "name"=>"blank",
                    "attr"=>serialize($blank_data_attr[$blank_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($blank_item_id)){
                    $this->where("id","=",$blank_item_id[$i])->update($blank_data);
                }else{
                    $id = $this->insertGetId($blank_data);
                    $blank_item_id[] = $id;
                }
            }
            if($blank_item_id){
                $this->updateComponent($blank_item_id,"blank");
            }
            // 辅助空白组件结束

            // 辅助线组件开始
            $line_data = [];
            $line_data_attr = [];
            $line_component = (array)input("line_component");
            $line_background_color = input("line_background_color");
            $line_class = input("line_class");
            $line_border_color = input("line_border_color");
            $line_height = input("line_height");
            $line_vertical_margin = input("line_vertical_margin");
            for($i=0;$i<count($line_component);$i++){
                $line_data_attr[$line_component[$i]]["background_color"] = $line_background_color[$i];
                $line_data_attr[$line_component[$i]]["class"] = $line_class[$i];
                $line_data_attr[$line_component[$i]]["border_color"] = $line_border_color[$i];
                $line_data_attr[$line_component[$i]]["height"] = $line_height[$i];
                $line_data_attr[$line_component[$i]]["vertical_margin"] = $line_vertical_margin[$i];
                $line_data = [
                    "pageId"=>$pageId,
                    "name"=>"line",
                    "attr"=>serialize($line_data_attr[$line_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($line_item_id)){
                    $this->where("id","=",$line_item_id[$i])->update($line_data);
                }else{
                    $id = $this->insertGetId($line_data);
                    $line_item_id[] = $id;
                }
            }
            if($line_item_id){
                $this->updateComponent($line_item_id,"line");
            }
            // 辅助线组件结束

            // 富文本组件开始
            $text_data = [];
            $text_data_attr = [];
            $text_component = (array)input("text_component");
            $text_vertical_padding = input("text_vertical_padding");
            $text_horizontal_padding = input("text_horizontal_padding");
            $text_background_color = input("text_background_color");
            $text_text = input("text_text");
            for($i=0;$i<count($text_component);$i++){
                $text_data_attr[$text_component[$i]]["vertical_padding"] = $text_vertical_padding[$i];
                $text_data_attr[$text_component[$i]]["horizontal_padding"] = $text_horizontal_padding[$i];
                $text_data_attr[$text_component[$i]]["background_color"] = $text_background_color[$i];
                $text_data_attr[$text_component[$i]]["text"] = $text_text[$i];
                $text_data = [
                    "pageId"=>$pageId,
                    "name"=>"text",
                    "attr"=>serialize($text_data_attr[$text_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($text_item_id)){
                    $this->where("id","=",$text_item_id[$i])->update($text_data);
                }else{
                    $id = $this->insertGetId($text_data);
                    $text_item_id[] = $id;
                }
            }
            if($text_item_id){
                $this->updateComponent($text_item_id,"text");
            }
            // 富文本组件结束

            // 单文本组件开始
            $txt_data = [];
            $txt_data_attr = [];
            $txt_component = (array)input("txt_component");
            $txt_background_color = input("txt_background_color");
            $txt_text_color = input("txt_text_color");
            $txt_text_alignment = input("txt_text_alignment");
            $txt_text = input("txt_text");
            $txt_link = input("txt_link");
            $txt_vertical_padding = input("txt_vertical_padding");
            $txt_horizontal_padding = input("txt_horizontal_padding");
            for($i=0;$i<count($txt_component);$i++){
                $txt_data_attr[$txt_component[$i]]["background_color"] = $txt_background_color[$i];
                $txt_data_attr[$txt_component[$i]]["text_color"] = $txt_text_color[$i];
                $txt_data_attr[$txt_component[$i]]["text"] = $txt_text[$i];
                $txt_data_attr[$txt_component[$i]]["link"] = $txt_link[$i];
                $txt_data_attr[$txt_component[$i]]["alignment"] = $txt_text_alignment[$i];
                $txt_data_attr[$txt_component[$i]]["vertical_padding"] = $txt_vertical_padding[$i];
                $txt_data_attr[$txt_component[$i]]["horizontal_padding"] = $txt_horizontal_padding[$i];
                $txt_data = [
                    "pageId"=>$pageId,
                    "name"=>"txt",
                    "attr"=>serialize($txt_data_attr[$txt_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($txt_item_id)){
                    $this->where("id","=",$txt_item_id[$i])->update($txt_data);
                }else{
                    $id = $this->insertGetId($txt_data);
                    $txt_item_id[] = $id;
                }
            }
            if($txt_item_id){
                $this->updateComponent($txt_item_id,"txt");
            }
            // 单文本组件结束

            // 图文列表组件开始
            $image_text_data = [];
            $image_text_data_attr = [];
            $image_text_component = (array)input("image_text_component");
            $image_text_style = input("image_text_style");
            $image_text_title = input("image_text_title");
            $image_text_desc = input("image_text_desc");
            $image_text_link = input("image_text_link");
            $image_text_img = input("image_text_img");
            $image_text_vertical_padding = input("image_text_vertical_padding");

            for($i=0;$i<count($image_text_component);$i++){
                $image_text_data_attr[$image_text_component[$i]]["style"] = $image_text_style[$i];
                $image_text_data_attr[$image_text_component[$i]]["title"] = $image_text_title[$i];
                $image_text_data_attr[$image_text_component[$i]]["desc"] = $image_text_desc[$i];
                $image_text_data_attr[$image_text_component[$i]]["link"] = $image_text_link[$i];
                $image_text_data_attr[$image_text_component[$i]]["img"] = $image_text_img[$i];
                $image_text_data_attr[$image_text_component[$i]]["vertical_padding"] = $image_text_vertical_padding[$i];
                $image_text_data = [
                    "pageId"=>$pageId,
                    "name"=>"image_text",
                    "attr"=>serialize($image_text_data_attr[$image_text_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($image_text_item_id)){
                    $this->where("id","=",$image_text_item_id[$i])->update($image_text_data);
                }else{
                    $id = $this->insertGetId($image_text_data);
                    $image_text_item_id[] = $id;
                }
            }
            if($image_text_item_id){
                $this->updateComponent($image_text_item_id,"image_text");
            }
            // 图文列表组件结束

            // 多店铺组件开始
            $shop_data = [];
            $shop_data_attr = [];
            $shop_component = (array)input("shop_component");
            $shop_title = input("shop_title");
            $shop_search_radius = input("shop_search_radius");
            for($i=0;$i<count($shop_component);$i++){
                $shop_data_attr[$shop_component[$i]]["title"] = $shop_title[$i];
                $shop_data_attr[$shop_component[$i]]["search_radius"] = $shop_search_radius[$i];
                $shop_data = [
                    "pageId"=>$pageId,
                    "name"=>"shop",
                    "attr"=>serialize($shop_data_attr[$shop_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($shop_item_id)){
                    $this->where("id","=",$shop_item_id[$i])->update($shop_data);
                }else{
                    $id = $this->insertGetId($shop_data);
                    $shop_item_id[] = $id;
                }
            }
            if($shop_item_id){
                $this->updateComponent($shop_item_id,"shop");
            }
            // 多店铺组件结束

            // 新闻组件开始
            $new_data = [];
            $new_data_attr = [];
            $new_component = (array)input("new_component");
            $new_title = input("new_title");
            $new_count = input("new_count");
            $new_select_ids = input("new_select_ids"); // 手动添加的新闻id
            for($i=0;$i<count($new_component);$i++){
                $new_data_attr[$new_component[$i]]["title"] = $new_title[$i];
                $new_data_attr[$new_component[$i]]["count"] = $new_count[$i];
                $new_data_attr[$new_component[$i]]["new_select_ids"] = $new_select_ids[$i];
                $new_data = [
                    "pageId"=>$pageId,
                    "name"=>"new",
                    "attr"=>serialize($new_data_attr[$new_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($new_item_id)){
                    $this->where("id","=",$new_item_id[$i])->update($new_data);
                }else{
                    $id = $this->insertGetId($new_data);
                    $new_item_id[] = $id;
                }
            }
            if($new_item_id){
                $this->updateComponent($new_item_id,"new");
            }
            // 新闻组件结束

            // 营销活动组件开始
            $marketing_data = [];
            $marketing_data_attr = [];
            $marketing_component = (array)input("marketing_component");
            $marketing_title = input("marketing_title");
            $marketing_type = input("marketing_type");
            $marketing_text_vertical_padding = input("marketing_vertical_padding");
            for($i=0;$i<count($marketing_component);$i++){
                $marketing_data_attr[$marketing_component[$i]]["title"] = $marketing_title[$i];
                $marketing_data_attr[$marketing_component[$i]]["type"] = $marketing_type[$i];
                $marketing_data_attr[$marketing_component[$i]]["vertical_padding"] = $marketing_text_vertical_padding[$i];
                $marketing_data = [
                    "pageId"=>$pageId,
                    "name"=>"marketing",
                    "attr"=>serialize($marketing_data_attr[$marketing_component[$i]]),
                    "createTime"=>date("Y-m-d H:i:s",time())
                ];
                if(($i+1)<=count($marketing_item_id)){
                    $this->where("id","=",$marketing_item_id[$i])->update($marketing_data);
                }else{
                    $id = $this->insertGetId($marketing_data);
                    $marketing_item_id[] = $id;
                }
            }
            if($marketing_item_id){
                $this->updateComponent($marketing_item_id,"marketing");
            }
            // 营销活动组件结束

            // 底部导航栏开始
            $tabbar_data = [];
            $tabbar_data_attr = [];
            $tabbar_component = (array)input("tabbar_component");
            $tabbar_item_id = input("tabbar_item_id");
            $tabbar_link = input("tabbar_link");
            $tabbar_link_text = input("tabbar_link_text");
            $tabbar_menu_flag = input("tabbar_menu_flag");
            $tabbar_text = input("tabbar_text");
            $tabbar_icon = input("tabbar_icon");
            $tabbar_select_icon = input("tabbar_select_icon");
            for($i=0;$i<count($tabbar_component);$i++){
                // 背景颜色
                if($i==1){
                    $tabbar_data_attr["background_color"] = $tabbar_component[$i];
                }
                // 上边框颜色
                if($i==2){
                    $tabbar_data_attr["border_color"] = $tabbar_component[$i];
                }
                // 文字颜色
                if($i==3){
                    $tabbar_data_attr["text_color"] = $tabbar_component[$i];
                }
                // 选中时文字颜色
                if($i==4){
                    $tabbar_data_attr["text_checked_color"] = $tabbar_component[$i];
                }
            }
            for($i=0;$i<count($tabbar_icon);$i++){
                $tabbar_data_attr["icon"][] = $tabbar_icon[$i];
                $tabbar_data_attr["select_icon"][] = $tabbar_select_icon[$i];
                $tabbar_data_attr["text"][] = $tabbar_text[$i];
                $tabbar_data_attr["link"][] = $tabbar_link[$i];
                $tabbar_data_attr["link_text"][] = $tabbar_link_text[$i];
                $tabbar_data_attr["menu_flag"][] = $tabbar_menu_flag[$i];
            }
            $tabbar_data = [
                "pageId"=>$pageId,
                "name"=>"tabbar",
                "attr"=>serialize($tabbar_data_attr),
                "createTime"=>date("Y-m-d H:i:s",time())
            ];
            if($tabbar_item_id){
                $this->where("id","=",$tabbar_item_id)->update($tabbar_data);
            }else{
                $this->insert($tabbar_data);
            }
            // 底部导航栏结束

            Db::commit();
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('操作失败',-1);
        }

        // 排序开始
        $sort = input("sort");
        $sort_name = input("sort_name");
        $isFirst = true;
        for($i=0;$i<count($sort);$i++){
            if($sort[$i]!=0){
                $isFirst = false;
                break;
            }
        }
        if($isFirst) {
            // 第一次按照页面布局的组件名字来排序
            if($sort_name){
                $res = $this->where(['dataFlag'=>1,'pageId'=>$pageId])->select();
                for($i=0;$i<count($sort_name);$i++){
                    foreach($res as $k => $v) {
                        if($sort_name[$i] == $v['name']) {
                            $this->where(["id"=>$v['id'],'pageId'=>$pageId])->update(["sort"=>$i]);
                            unset($res[$k]);
                            break;
                        }
                    }
                }
            }
        }else {
            // 第二次开始按照页面布局的组件id来排序
            // 如果包含新的组件，要先将新组件的id存放到$sort变量中
            $new_ids = [];
            if($sort_name){
                // 去掉重复的组件名
                $sort_name = array_unique($sort_name);
                // 重新生成数组键值
                $sort_name = array_values($sort_name);
                for($i=0;$i<count($sort_name);$i++){
                    $tempArr = $this->field('id')->where(['sort'=>'0','dataFlag'=>1,'pageId'=>$pageId,'name'=>$sort_name[$i]])->order('id asc')->select();
                    foreach($tempArr as $k => $v){
                        $new_ids[] = $v['id'];
                    }
                }
                for($i=0;$i<count($sort);$i++){
                    if ($sort[$i] == 0) {
                        for($j=0;$j<count($new_ids);$j++) {
                            $sort[$i] = $new_ids[$j];
                            unset($new_ids[$j]);
                            $new_ids = array_values($new_ids);
                            break;
                        }
                    }
                }
            }
            for($i=0;$i<count($sort);$i++){
                $this->where(["id"=>$sort[$i],'pageId'=>$pageId])->update(["sort"=>$i]);
            }
        }
        // 排序结束

        return $pageId;
    }

    /*
     * 根据组件名字来删除组件
     */
    public function deleteComponent($componentName){
        $pageId = input('page_id',0);
        if($pageId==0)return;
        $where = ["name"=>$componentName,'dataFlag'=>1,'pageId'=>$pageId];
        $itemIdsArr = $this->field("id")->where($where)->select();
        foreach($itemIdsArr as $k => $v){
            $this->where(["id"=>$v["id"],'pageId'=>$pageId])->update(["dataFlag"=>-1]);
        }
        return true;
    }

    /*
     * 删除没有提交的组件id,但该id存在于数据表中的
     */
    public function updateComponent($itemId,$componentName){
        $pageId = input('page_id',0);
        if($pageId==0)return;
        if(!is_array($itemId)){
            return false;
        }
        $itemIds = $this->field("id")->where(["name"=>$componentName,'dataFlag'=>1,'pageId'=>$pageId])->select();
        for($i=0;$i<count($itemId);$i++){
            foreach($itemIds as $k => $v){
                if($v["id"] == $itemId[$i]){
                    unset($itemIds[$k]);
                }
            }
        }
        foreach($itemIds as $k => $v){
            $this->where(["id"=>$v["id"],'pageId'=>$pageId])->update(["dataFlag"=>-1]);
        }
        return true;
    }

    /*
     * 根据优惠券id获取优惠券
     */
    public function getCouponsByIds($ids){
        $where[] = ['dataFlag','=',1];
        $where[] = ['endDate','>=',date('Y-m-d')];
        $where[] = ['couponId','in',$ids];
        $rs = Db::name('coupons')
            ->where($where)
            ->field('*')
            ->order('endDate desc')
            ->select();
        return $rs;
    }

    /**
     * 优惠券列表
     */
    public function couponPageQuery(){
        $useCondition = (int)input('useCondition/d',-1);
        $isTrue =(int)input('isTrue',-1);//判断试过有效,1为有效,0为过期,-1则无调用
        $time = date('Y-m-d');// 当天有效
        $condition = '';
        if($isTrue!=-1){
            if($isTrue==1){
                $condition = " UNIX_TIMESTAMP(endDate) > '{$time}'";
            }else{
                $condition = " UNIX_TIMESTAMP(endDate) < '{$time}'";
            }
        }
        $where = ['dataFlag'=>1];
        if(in_array($useCondition,[0,1]))$where['useCondition'] = $useCondition;
        $page =  Db::name('coupons')->where($where)
            ->where($condition)
            ->order('createTime desc')
            ->paginate(input('limit/d'))->toArray();
        $page['status'] = 1;
        return $page;
    }

    /**
     * 新闻列表
     */
    public function newPageQuery(){
        $where[] = ['a.dataFlag','=',1];
        $where[] = ['ac.catType','=',0];
        $page = Db::name('articles')->alias('a')
            ->join('__ARTICLE_CATS__ ac','a.catId= ac.catId','left')
            ->where($where)
            ->field('a.articleId,a.catId,a.articleTitle,a.isShow,a.articleContent,a.articleKey,a.createTime,a.catSort,ac.catName')
            ->paginate(input('post.limit/d'))->toArray();
        if(count($page['data'])>0){
            foreach ($page['data'] as $key => $v){
                $page['data'][$key]['articleContent'] = strip_tags(htmlspecialchars_decode($v['articleContent']));
            }
        }
        return $page;
    }

    /*
     * 获取商城首页自定义页面数据
     */
    public function getCustomPageDecorationData($pageId){
        $data = Db::name('custom_page_decoration')->field('id,name,attr,sort')->where(['dataFlag'=>'1','pageId'=>$pageId])->order('sort asc')->select();
        foreach($data as $k => $v){
            $data[$k]["attr"] = unserialize($data[$k]["attr"]);
        }
        foreach($data as $k => $v){
            if($v["name"] == "swiper"){
                $data[$k]['indicatorType'] = $v["attr"]["indicator_type"];
                $data[$k]['indicatorColor'] = $v["attr"]["indicator_color"];
                $data[$k]['interval'] = $v["attr"]["interval"];
                $data[$k]['paddingTop'] = $v["attr"]["padding_top"];
                $data[$k]['paddingBottom'] = $v["attr"]["padding_bottom"];
                $data[$k]['paddingLeft'] = $v["attr"]["padding_left"];
                $data[$k]['paddingRight'] = $v["attr"]["padding_right"];
                for($i=0;$i<count($v["attr"]["img"]);$i++){
                    $swiperData['img'] = $v["attr"]["img"][$i];
                    $swiperData['link'] = WSTCustomPageLink($v["attr"]["link"][$i]);
                    $data[$k]["swipers"][] = $swiperData;
                }
                unset($data[$k]['attr']);
            }elseif($v["name"] == "nav"){
                $data[$k]['backgroundColor'] = $v["attr"]["background_color"];
                // 样式类型
                $style = $v["attr"]["style"];
                // 每行数量
                $count = $v["attr"]["count"];
                for($i=0;$i<count($v["attr"]["item_img"]);$i++){
                    $navData['img'] = $v["attr"]["item_img"][$i];
                    $navData['link'] = WSTCustomPageLink($v["attr"]["item_link"][$i]);
                    $navData['text'] = $v["attr"]["item_text"][$i];
                    $navData['color'] = $v["attr"]["item_color"][$i];
                    $navData['count'] = $count;
                    $navData['style'] = $style;
                    $data[$k]["navs"][] = $navData;
                }
                unset($data[$k]['attr']);
            }elseif($v["name"] == "goods_group") {
                $data[$k]['backgroundColor'] = $v["attr"]["background_color"];
                $data[$k]['showGoodsName'] = $v["attr"]["show_goods_name"];
                $data[$k]['showGoodsPrice'] = $v["attr"]["show_goods_price"];
                $data[$k]['showPraiseRate'] = $v["attr"]["show_praise_rate"];
                $data[$k]['showSaleNum'] = $v["attr"]["show_sale_num"];
                $data[$k]['showColumnsTitle'] = $v["attr"]["show_columns_title"];
                $data[$k]['columns'] = $v["attr"]["columns"];
                $data[$k]['columnsTitle'] = $v["attr"]["columns_title"];
                $where = [
                    'columns_title'=>$v['attr']['columns_title'],
                    'goods_select'=>$v['attr']['goods_select'],
                    'goods_nums'=>$v['attr']['goods_nums'],
                    'goods_select_ids'=>$v['attr']['goods_select_ids'],
                    'goods_select_cats_id'=>$v['attr']['goods_select_cats_id'],
                    'goods_tag'=>$v['attr']['goods_tag'],
                    'goods_min_price'=>$v['attr']['goods_min_price'],
                    'goods_max_price'=>$v['attr']['goods_max_price'],
                    'goods_order'=>$v['attr']['goods_order'],
                ];
                $data[$k]['goods'] = $this->getGoods($where);
                unset($data[$k]['attr']);
            }else if($v['name'] == 'search'){
                $data[$k]['placeholder'] = $v['attr']['text'];
                $data[$k]['class'] = $v['attr']['class'];
                $data[$k]['alignment'] = $v['attr']['alignment'];
                unset($data[$k]['attr']);
            }else if($v["name"] == "image"){
                $data[$k]['backgroundColor'] = $v["attr"]["background_color"];
                $data[$k]['paddingTop'] = $v["attr"]["padding_top"];
                $data[$k]['paddingBottom'] = $v["attr"]["padding_bottom"];
                $data[$k]['paddingLeft'] = $v["attr"]["padding_left"];
                $data[$k]['paddingRight'] = $v["attr"]["padding_right"];
                for($i=0;$i<count($v["attr"]["img"]);$i++){
                    $imageData['img'] = $v["attr"]["img"][$i];
                    $imageData['link'] = WSTCustomPageLink($v["attr"]["link"][$i]);
                    $data[$k]["images"][] = $imageData;
                }
                unset($data[$k]['attr']);
            }else if($v["name"] == "shopwindow"){
                $data[$k]['backgroundColor'] = $v["attr"]["background_color"];
                $data[$k]['layout'] = $v["attr"]["layout"];
                for($i=0;$i<count($v["attr"]["img"]);$i++){
                    $imageData['img'] = $v["attr"]["img"][$i];
                    $imageData['link'] = WSTCustomPageLink($v["attr"]["link"][$i]);
                    $data[$k]["images"][] = $imageData;
                }
                unset($data[$k]['attr']);
            }else if($v["name"] == "video"){
                $data[$k]['verticalPadding'] = $v["attr"]["vertical_padding"];
                $data[$k]['img'] = $v["attr"]["img"];
                $data[$k]['link'] = $v["attr"]["link"];
                unset($data[$k]['attr']);
            }else if($v["name"] == "coupon"){
                $data[$k]['verticalPadding'] = $v["attr"]["vertical_padding"];
                $data[$k]['backgroundColor'] = $v["attr"]["background_color"];
                $data[$k]['style'] = $v["attr"]["style"];
                $data[$k]['coupons'] = $this->getCoupons($v['attr']['coupon_select_ids']);
                unset($data[$k]['attr']);
            }else if($v["name"] == "blank"){
                $data[$k]['height'] = $v["attr"]["height"];
                $data[$k]['backgroundColor'] = $v["attr"]["background_color"];
                unset($data[$k]['attr']);
            }else if($v["name"] == "text"){
                $data[$k]['verticalPadding'] = $v["attr"]["vertical_padding"];
                $data[$k]['horizontalPadding'] = $v["attr"]["horizontal_padding"];
                $data[$k]['backgroundColor'] = $v["attr"]["background_color"];
                //对图片域名进行处理
                $text = htmlspecialchars_decode($v["attr"]["text"]);
                $text = str_replace(WSTConf('CONF.resourcePath'),'',$text);
                $rule = '/<img src="\/(upload.*?)"/';
                preg_match_all($rule, $text, $images);
                foreach($images[0] as $k1=>$v1){
                    $text = str_replace('/'.$images[1][$k1], url('/','','',true).WSTImg($images[1][$k1],3), $text);
                }
                $data[$k]['text'] = $text;
                unset($data[$k]['attr']);
            }else if($v["name"] == "notice"){
                $data[$k]['backgroundColor'] = $v["attr"]["background_color"];
                $data[$k]['verticalPadding'] = $v["attr"]["vertical_padding"];
                $data[$k]['direction'] = $v["attr"]["direction"];
                $textColor = $v["attr"]["text_color"];
                $data[$k]['img'] = $v["attr"]["img"];
                for($i=0;$i<count($v["attr"]["text"]);$i++){
                    $noticeData['text'] = $v["attr"]["text"][$i];
                    $noticeData['link'] = WSTCustomPageLink($v["attr"]["link"][$i]);
                    $noticeData['textColor'] = $textColor;
                    $data[$k]["notices"][] = $noticeData;
                }
                unset($data[$k]['attr']);
            }else if($v["name"] == "txt"){
                $data[$k]['verticalPadding'] = $v["attr"]["vertical_padding"];
                $data[$k]['horizontalPadding'] = $v["attr"]["horizontal_padding"];
                $data[$k]['backgroundColor'] = $v["attr"]["background_color"];
                $data[$k]['textColor'] = $v["attr"]["text_color"];
                $data[$k]['text'] = $v["attr"]["text"];
                $data[$k]['link'] = WSTCustomPageLink($v["attr"]["link"]);
                $data[$k]['alignment'] = $v["attr"]["alignment"];
                unset($data[$k]['attr']);
            }else if($v["name"] == "image_text"){
                $data[$k]['style'] = $v["attr"]["style"];
                $data[$k]['title'] = $v["attr"]["title"];
                $data[$k]['desc'] = $v["attr"]["desc"];
                $data[$k]['link'] = WSTCustomPageLink($v["attr"]["link"]);
                $data[$k]['img'] = $v["attr"]["img"];
                $data[$k]['verticalPadding'] = $v["attr"]["vertical_padding"];
                unset($data[$k]['attr']);
            }else if($v["name"] == "shop"){
                $data[$k]['title'] = $v["attr"]["title"];
                $data[$k]['searchRadius'] = $v["attr"]["search_radius"];
                unset($data[$k]['attr']);
            }else if($v["name"] == "new"){
                $data[$k]['title'] = $v["attr"]["title"];
                $news = $this->getNews($v['attr']['count'],$v['attr']['new_select_ids']);
                $data[$k]['news'] = $news;
                if(count($news)>0){
                    $data[$k]['hasData'] = 1;
                }else{
                    $data[$k]['hasData'] = 0;
                }
                unset($data[$k]['attr']);
            }else if($v["name"] == "marketing"){
                $data[$k]['title'] = $v["attr"]["title"];
                $data[$k]['verticalPadding'] = $v["attr"]["vertical_padding"];
                $data[$k]['type'] = $v["attr"]["type"];
                $goods = $this->getMarketingGoods($v['attr']['type']);
                $data[$k]['goods'] = $goods;
                if($v["attr"]["type"] == 'Seckill'){
                    $currTime = date("H:i:s");
                    $where = [];
                    $where[] = ["dataFlag",'=',1];
                    $where[] = ['startTime','<',$currTime];
                    $where[] = ['endTime','>',$currTime];
                    $secRes = Db::name("seckill_time_intervals")
                        ->where($where)
                        ->field('title,startTime,endTime')
                        ->find();
                    $data[$k]['secTitle'] = $secRes['title'];
                    $data[$k]['secStartTime'] = date("Y-m-d").' '.$secRes['startTime'];
                    $data[$k]['secEndTime'] = date("Y-m-d").' '.$secRes['endTime'];
                }
                if(count($goods)>0){
                    $data[$k]['hasData'] = 1;
                }else{
                    $data[$k]['hasData'] = 0;
                }
                unset($data[$k]['attr']);
            }
        }
        return $data;
    }

    /*
     * 获取首页自定义商品组件的商品
     */
    public function getGoods($array){
        $goodsNums = $array['goods_nums'];
        $columnsTitle = $array['columns_title'];
        $goodsSelect = $array['goods_select'];
        $goodsSelectIds = $array['goods_select_ids'];
        $goodsSelectCatsId = $array['goods_select_cats_id'];
        $goodsOrder = $array['goods_order'];
        $goodsTag = $array['goods_tag'];
        $goodsMinPrice = $array['goods_min_price'];
        $goodsMaxPrice = $array['goods_max_price'];
        $order = '';
        switch ($goodsOrder){
            case 1:
                //销量从高到低
                $order = 'saleNum desc';
                break;
            case 2:
                //销量从低到高
                $order = 'saleNum asc';
                break;
            case 3:
                //价格从高到低
                $order = 'shopPrice desc';
                break;
            case 4:
                //价格从低到高
                $order = 'shopPrice asc';
                break;
            case 5:
                //更新时间由近到远
                $order = 'createTime desc';
                break;
            case 6:
                //更新时间由远到近
                $order = 'createTime asc';
                break;
            case 7:
                //商品排序由大到小
                $order = 'goodsId desc';
                break;
            case 8:
                //商品排序由小到大
                $order = 'goodsId asc';
                break;
        }
        $data = [];
        for($i=0;$i<count($columnsTitle);$i++){
            if($goodsSelect[$i]==1){
                // 条件选取
                $where = [];
                $where2 = '';
                $where3 = '';
                switch ($goodsTag[$i]) {
                    case '1':
                        // 推荐
                        $where[] = ['g.isRecom','=',1];
                        break;
                    case '2':
                        // 新品
                        $where[] = ['g.isNew','=',1];
                        break;
                    case '3':
                        // 热卖
                        $where[] = ['g.isHot','=',1];
                        break;
                }
                $minPrice = $goodsMinPrice[$i]; // 最低价格
                $maxPrice = $goodsMaxPrice[$i]; // 最高价格
                if($minPrice!="")$where2 = "g.shopPrice >= ".(float)$minPrice;
                if($maxPrice!="")$where3 = "g.shopPrice <= ".(float)$maxPrice;
                $data[$i] = Db::name('goods')->alias('g')->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId')
                    ->where([['g.goodsCatIdPath','like',$goodsSelectCatsId[$i].'_%'],['g.isSale','=',1],['g.dataFlag','=',1],['g.goodsStatus','=',1]])
                    ->where($where)
                    ->where($where2)
                    ->where($where3)
                    ->field('g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.saleNum,gs.totalScore,gs.totalUsers')
                    ->order($order)->limit($goodsNums)->select();
                if($data[$i]){
                    foreach ($data[$i] as $key =>$v){
                        $data[$i][$key]['praiseRate'] = ($v['totalScore']>0)?(sprintf("%.2f",$v['totalScore']/($v['totalUsers']*15))*100).'%':'100%';
                    }
                }
            }else{
                // 手动添加
                $data[$i] = Db::name('goods')->alias('g')->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId','left')
                    ->where([['g.goodsId','in',$goodsSelectIds[$i]],['g.isSale','=',1],['g.dataFlag','=',1],['g.goodsStatus','=',1]])
                    ->field('g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.saleNum,gs.totalScore,gs.totalUsers')
                    ->order($order)->select();
                if($data[$i]){
                    foreach ($data[$i] as $key =>$v){
                        $data[$i][$key]['praiseRate'] = ($v['totalScore']>0)?(sprintf("%.2f",$v['totalScore']/($v['totalUsers']*15))*100).'%':'100%';
                    }
                }
            }
        }
        return $data;
    }

    /*
     * 获取首页自定义优惠券组件的优惠券
     */
    public function getCoupons($couponIds=''){
        $where[] = ['dataFlag','=',1];
        $where[] = ['endDate','>=',date('Y-m-d')];
        $where[] = ['couponId','in',$couponIds];
        $rs = Db::name('coupons')
            ->where($where)
            ->field('*')
            ->order('endDate desc')
            ->select();
        return $rs;
    }

    /*
     * 获取首页自定义新闻组件的新闻
     */
    function getNews($count=2,$articleIds=''){
        $where = [];
        $where[] = ['a.dataFlag','=',1];
        $where[] = ['a.isShow','=',1];
        $where[] = ['ac.catType','=',0];
        if($articleIds) {
            $where[] = ['a.articleId', 'in', $articleIds];
            $count = count(explode(',',$articleIds));
        }
        $rs = Db::name('articles')->alias('a')
            ->join('__ARTICLE_CATS__ ac','a.catId=ac.catId','inner')
            ->field('*')
            ->where($where)
            ->order('a.catSort asc,a.createTime desc')
            ->limit($count)
            ->select();
        foreach($rs as $k=>$v){
            $rs[$k]['articleContent'] = strip_tags(html_entity_decode($v['articleContent']));
            $rs[$k]['createTime'] = date('Y-m-d',strtotime($rs[$k]['createTime']));
            if($rs[$k]['coverImg']){
                $rs[$k]['coverImg'] = str_replace("_thumb.", ".",  $rs[$k]['coverImg']);
            }else{
                $rs[$k]['coverImg'] = WSTConf('CONF.goodsLogo');
            }

        }
        return $rs;
    }

    /*
     * 获取首页自定义营销活动组件的商品
     */
    function getMarketingGoods($type){
        $rs = [];
        switch($type){
            case 'Pintuan':
                $rs = Db::name('pintuans')->alias('p')->join('__GOODS__ g','p.goodsId=g.goodsId','inner')
                    ->where('g.dataFlag=1 and g.isSale=1 and g.goodsStatus=1 and p.dataFlag=1 and p.tuanStatus=1')
                    ->field('g.goodsName,g.goodsImg,g.marketPrice,g.goodsCatId,g.isFreeShipping,p.*')
                    ->order('p.updateTime desc,tuanId desc')
                    ->select();
                break;
            case 'Seckill':
                $currTime = date("H:i:s");
                $where[] = ["dataFlag",'=',1];
                $where[] = ['startTime','<',$currTime];
                $where[] = ['endTime','>',$currTime];
                $timeId = Db::name("seckill_time_intervals")
                    ->where($where)
                    ->value('id');
                $today = date("Y-m-d");
                $where = [];
                $where[] = ["sg.timeId",'=',$timeId];
                $where[] = ["sg.dataFlag",'=',1];
                $where[] = ['sg.secPrice','>',0];
                $where[] = ['g.goodsStatus','=',1];
                $where[] = ['g.dataFlag','=',1];
                $where[] = ['g.isSale','=',1];
                $where[] = ['k.seckillStatus','=',1];
                $where[] = ['k.dataFlag','=',1];
                $where[] = ['k.startDate','<=',$today];
                $where[] = ['k.endDate','>=',$today];
                $rs = Db::name("seckill_goods sg")
                    ->join("goods g","sg.goodsId=g.goodsId","inner")
                    ->join("seckills k","k.id=sg.seckillId","inner")
                    ->join("shops s","g.shopId = s.shopId")
                    ->field("g.goodsId,g.goodsName,g.shopPrice,g.goodsImg,sg.id,sg.seckillId,sg.timeId,sg.secPrice,sg.secNum,sg.secLimit,sg.createTime,sg.hasBuyNum")
                    ->where($where)
                    ->order("sg.saleNum desc,sg.id")
                    ->select();
                break;
            case 'Auction':
                $rs = Db::name('auctions')->alias('gu')->join('__GOODS__ g','gu.goodsId=g.goodsId','inner')
                    ->where('gu.dataFlag=1 and gu.auctionStatus=1 and g.dataFlag=1')
                    ->field('gu.goodsId,gu.goodsImg,gu.goodsName,gu.currPrice,gu.startTime,gu.endTime,gu.auctionId,gu.auctionNum')
                    ->order('gu.isClose asc,gu.startTime asc,gu.updateTime desc')
                    ->select();
                break;
            case 'Bargain':
                $where[] = ['b.endTime','>=',date('Y-m-d H:i:s')];
                $rs = Db::name('bargains')->alias('b')->join('__GOODS__ g','b.goodsId=g.goodsId','inner')
                    ->where('g.dataFlag=1 and g.isSale=1 and g.goodsStatus=1 and b.dataFlag=1 and b.bargainStatus=1')
                    ->where($where)
                    ->field('g.goodsName,g.goodsImg,g.marketPrice,b.*')
                    ->order('b.updateTime desc,b.startTime asc')
                    ->select();
                break;
        }
        return $rs;
    }
}
