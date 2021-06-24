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
 * 小程序首页装修类
 */
class WeappIndexDecoration extends Base{
	/**
	 * 获取小程序首页装修内容
	 */
	public function pageQuery(){
        $data = $this->where(['dataFlag'=>1])->order("sort asc")->select();
        foreach($data as $k => $v){
            $data[$k]["attr"] = unserialize($data[$k]["attr"]);
        }
		return $data;
	}
    
    /*
     * 保存小程序首页装修内容
     */
    public function add(){
        $shopId = (int)session('WST_USER.shopId');
        $swiper_item_id = input("swiper_item_id/a");
        $goods_group_item_id = input("goods_group_item_id/a");
        $nav_item_id = input("nav_item_id/a");
        $notice_item_id = input("notice_item_id/a");
        $search_item_id = input("search_item_id/a");
        $coupon_item_id = input("coupon_item_id/a");
        $image_item_id = input("image_item_id/a");
        $shopwindow_item_id = input("shopwindow_item_id/a");
        $video_item_id = input("video_item_id/a");
        $blank_item_id = input("blank_item_id/a");
        $line_item_id = input("line_item_id/a");
        $text_item_id = input("text_item_id/a");
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

        // 页面设置开始
        $page_data = [];
        $page_data_attr = [];
        $page_component = input("page_component/a");
        $page_item_id = input("page_item_id");
        for($i=0;$i<count($page_component);$i++){
            if($i==0){
                $page_data_attr["title"] = $page_component[$i];
            }
            if($i==1){
                $page_data_attr["share_title"] = $page_component[$i];
            }
        }
        $page_data = [
            "name"=>"page",
            "attr"=>serialize($page_data_attr),
            "createTime"=>date("Y-m-d H:i:s",time())
        ];
        if($page_item_id){
            $this->where("id","=",$page_item_id)->update($page_data);
        }else{
            $this->insert($page_data);
        }
        // 页面设置结束

        // 图片轮播开始
        $swiper_data = [];
        $swiper_data_attr = [];
        $swiper_component = input("swiper_component/a");
        $swiper_link = input("swiper_link/a");
        $swiper_img = input("swiper_img/a");
        $swiper_indicator_type = input("swiper_indicator_type/a");
        $swiper_indicator_color = input("swiper_indicator_color/a");
        for($i=0;$i<count($swiper_component);$i++){
            $swiper_data_attr[$swiper_component[$i]]["indicator_type"] = $swiper_indicator_type[$i];
            $swiper_data_attr[$swiper_component[$i]]["indicator_color"] = $swiper_indicator_color[$i];
            $swiper_data_attr[$swiper_component[$i]]["link"] = $swiper_link[$i];
            $swiper_data_attr[$swiper_component[$i]]["img"] = $swiper_img[$i];
            $swiper_data = [
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
        $goods_group_component = input("goods_group_component/a");
        $goods_group_background_color = input("goods_group_background_color/a");
        $show_goods_name = input("show_goods_name/a");
        $show_goods_price = input("show_goods_price/a");
        $goods_group_columns = input("goods_group_columns/a");
        $goods_group_goods_cats = input("goods_group_goods_cats/a");
        for($i=0;$i<count($goods_group_component);$i++){
            $goods_group_data_attr[$goods_group_component[$i]]["background_color"] = $goods_group_background_color[$i];
            $goods_group_data_attr[$goods_group_component[$i]]["show_goods_name"] = $show_goods_name[$i];
            $goods_group_data_attr[$goods_group_component[$i]]["show_goods_price"] = $show_goods_price[$i];
            $goods_group_data_attr[$goods_group_component[$i]]["columns"] = $goods_group_columns[$i];
            $goods_group_data_attr[$goods_group_component[$i]]["goods_cat"] = $goods_group_goods_cats[$i];
            $goods_group_data = [
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
        $nav_component = input("nav_component/a");
        $nav_background_color = input("nav_background_color/a");
        $nav_count = input("nav_count/a");
        $nav_item_text = input("nav_item_text/a");
        $nav_item_img = input("nav_item_img/a");
        $nav_item_link = input("nav_item_link/a");
        $nav_item_color = input("nav_item_color/a");
        for($i=0;$i<count($nav_component);$i++){
            $nav_data_attr[$nav_component[$i]]["background_color"] = $nav_background_color[$i];
            $nav_data_attr[$nav_component[$i]]["count"] = $nav_count[$i];
            $nav_data_attr[$nav_component[$i]]["item_text"] = $nav_item_text[$i];
            $nav_data_attr[$nav_component[$i]]["item_img"] = $nav_item_img[$i];
            $nav_data_attr[$nav_component[$i]]["item_link"] = $nav_item_link[$i];
            $nav_data_attr[$nav_component[$i]]["item_color"] = $nav_item_color[$i];
            $nav_data = [
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
        $notice_component = input("notice_component/a");
        $notice_background_color = input("notice_background_color/a");
        $notice_text_color = input("notice_text_color/a");
        $notice_img = input("notice_img/a");
        $notice_text = input("notice_text/a");
        $notice_updown_padding = input("notice_updown_padding/a");
        for($i=0;$i<count($notice_component);$i++){
            $notice_data_attr[$notice_component[$i]]["background_color"] = $notice_background_color[$i];
            $notice_data_attr[$notice_component[$i]]["text_color"] = $notice_text_color[$i];
            $notice_data_attr[$notice_component[$i]]["img"] = $notice_img[$i];
            $notice_data_attr[$notice_component[$i]]["text"] = $notice_text[$i];
            $notice_data_attr[$notice_component[$i]]["updown_padding"] = $notice_updown_padding[$i];
            $notice_data = [
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
        $search_component = input("search_component/a");
        $search_text = input("search_text/a");
        $search_class = input("search_class/a");
        $search_text_alignment = input("search_text_alignment/a");
        for($i=0;$i<count($search_component);$i++){
            $search_data_attr[$search_component[$i]]["text"] = $search_text[$i];
            $search_data_attr[$search_component[$i]]["class"] = $search_class[$i];
            $search_data_attr[$search_component[$i]]["alignment"] = $search_text_alignment[$i];
            $search_data = [
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
        $coupon_component = input("coupon_component/a");
        $coupon_updown_padding = input("coupon_updown_padding/a");
        $coupon_background_color = input("coupon_background_color/a");
        $coupon_counts = input("coupon_counts/a");
        for($i=0;$i<count($coupon_component);$i++){
            $coupon_data_attr[$coupon_component[$i]]["updown_padding"] = $coupon_updown_padding[$i];
            $coupon_data_attr[$coupon_component[$i]]["background_color"] = $coupon_background_color[$i];
            $coupon_data_attr[$coupon_component[$i]]["counts"] = $coupon_counts[$i];
            $coupon_data = [
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
        $image_component = input("image_component/a");
        $image_link = input("image_link/a");
        $image_img = input("image_img/a");
        $image_updown_padding = input("image_updown_padding/a");
        $image_leftright_padding = input("image_leftright_padding/a");
        $image_background_color = input("image_background_color/a");
        for($i=0;$i<count($image_component);$i++){
            $image_data_attr[$image_component[$i]]["link"] = $image_link[$i];
            $image_data_attr[$image_component[$i]]["img"] = $image_img[$i];
            $image_data_attr[$image_component[$i]]["updown_padding"] = $image_updown_padding[$i];
            $image_data_attr[$image_component[$i]]["leftright_padding"] = $image_leftright_padding[$i];
            $image_data_attr[$image_component[$i]]["background_color"] = $image_background_color[$i];
            $image_data = [
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
        $shopwindow_component = input("shopwindow_component/a");
        $shopwindow_link = input("shopwindow_link/a");
        $shopwindow_img = input("shopwindow_img/a");
        $shopwindow_background_color = input("shopwindow_background_color/a");
        $shopwindow_layout = input("shopwindow_layout/a");
        for($i=0;$i<count($shopwindow_component);$i++){
            $shopwindow_data_attr[$shopwindow_component[$i]]["link"] = $shopwindow_link[$i];
            $shopwindow_data_attr[$shopwindow_component[$i]]["img"] = $shopwindow_img[$i];
            $shopwindow_data_attr[$shopwindow_component[$i]]["background_color"] = $shopwindow_background_color[$i];
            $shopwindow_data_attr[$shopwindow_component[$i]]["layout"] = $shopwindow_layout[$i];
            $shopwindow_data = [
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
        $video_component = input("video_component/a");
        $video_link = input("video_link/a");
        $video_img = input("video_img/a");
        $video_updown_padding = input("video_updown_padding/a");
        for($i=0;$i<count($video_component);$i++){
            $video_data_attr[$video_component[$i]]["link"] = $video_link[$i];
            $video_data_attr[$video_component[$i]]["img"] = $video_img[$i];
            $video_data_attr[$video_component[$i]]["updown_padding"] = $video_updown_padding[$i];
            $video_data = [
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
        $blank_component = input("blank_component/a");
        $blank_height = input("blank_height/a");
        $blank_background_color = input("blank_background_color/a");
        for($i=0;$i<count($blank_component);$i++){
            $blank_data_attr[$blank_component[$i]]["height"] = $blank_height[$i];
            $blank_data_attr[$blank_component[$i]]["background_color"] = $blank_background_color[$i];
            $blank_data = [
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
        $line_component = input("line_component/a");
        $line_background_color = input("line_background_color/a");
        $line_class = input("line_class/a");
        $line_border_color = input("line_border_color/a");
        $line_height = input("line_height/a");
        $line_updown_margin = input("line_updown_margin/a");
        for($i=0;$i<count($line_component);$i++){
            $line_data_attr[$line_component[$i]]["background_color"] = $line_background_color[$i];
            $line_data_attr[$line_component[$i]]["class"] = $line_class[$i];
            $line_data_attr[$line_component[$i]]["border_color"] = $line_border_color[$i];
            $line_data_attr[$line_component[$i]]["height"] = $line_height[$i];
            $line_data_attr[$line_component[$i]]["updown_margin"] = $line_updown_margin[$i];
            $line_data = [
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
        $text_component = input("text_component/a");
        $text_updown_padding = input("text_updown_padding/a");
        $text_leftright_padding = input("text_leftright_padding/a");
        $text_background_color = input("text_background_color/a");
        $text_text = input("text_text/a");
        for($i=0;$i<count($text_component);$i++){
            $text_data_attr[$text_component[$i]]["updown_padding"] = $text_updown_padding[$i];
            $text_data_attr[$text_component[$i]]["leftright_padding"] = $text_leftright_padding[$i];
            $text_data_attr[$text_component[$i]]["background_color"] = $text_background_color[$i];
            $text_data_attr[$text_component[$i]]["text"] = $text_text[$i];
            $text_data = [
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

        // 底部导航栏开始
        $tabbar_data = [];
        $tabbar_data_attr = [];
        $tabbar_component = input("tabbar_component/a");
        $tabbar_item_id = input("tabbar_item_id");
        $tabbar_link = input("tabbar_link/a");
        $tabbar_text = input("tabbar_text/a");
        $tabbar_icon = input("tabbar_icon/a");
        $tabbar_select_icon = input("tabbar_select_icon/a");
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
        }
        $tabbar_data = [
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

        // 排序开始
        $sort = input("sort/a");
        $sort_name = input("sort_name/a");

        // 第二次开始按照页面布局的组件id来排序
        if($sort){
            $component_ids = $this->field("id")->where(['dataFlag'=>1])->select();
            $component_ids_arr = [];
            $component_new_ids_arr = [];
            foreach($component_ids as $k => $v){
                $component_ids_arr[] = $v["id"];
                if(!in_array($v["id"],$sort)){
                    $component_new_ids_arr[] = $v["id"];
                }
            }
            // 如果前台传过来的组件id的数量与数据表的组件id数量不一致,要先将新组件的id加入到$sort变量中
            if(count($sort) != count($component_ids_arr)){
                $sort = array_merge($sort,$component_new_ids_arr);
                for($i=0;$i<count($sort);$i++){
                    $this->where("id","=",$sort[$i])->update(["sort"=>$i]);
                }
            }else{
                for($i=0;$i<count($sort);$i++){
                    $this->where("id","=",$sort[$i])->update(["sort"=>$i]);
                }
            }
        }else{
            // 第一次按照页面布局的组件名字来排序
            if($sort_name){
                $res = $this->select();
                for($i=0;$i<count($sort_name);$i++){
                    foreach($res as $k => $v) {
                        if($sort_name[$i] == $v['name']) {
                            $this->where("id","=",$v['id'])->update(["sort"=>$i]);
                            unset($res[$k]);
                            break;
                        }
                    }
                }
            }
        }
        // 排序结束
    }

    /*
     * 根据组件名字来删除组件
     */
    public function deleteComponent($component_name){
        $where = ["name"=>$component_name,'dataFlag'=>1];
        $item_ids_arr = $this->field("id")->where($where)->select();
        foreach($item_ids_arr as $k => $v){
            $this->where("id","=",$v["id"])->update(["dataFlag"=>-1]);
        }
        return true;
    }

    /*
     * 删除没有提交的组件id,但该id存在于数据表中的
     */
    public function updateComponent($item_id,$component_name){
        if(!is_array($item_id)){
            return false;
        }
        $item_ids = $this->field("id")->where(["name"=>$component_name,'dataFlag'=>1])->select();
        for($i=0;$i<count($item_id);$i++){
            foreach($item_ids as $k => $v){
                if($v["id"] == $item_id[$i]){
                    unset($item_ids[$k]);
                }
            }
        }
        foreach($item_ids as $k => $v){
            $this->where("id","=",$v["id"])->update(["dataFlag"=>-1]);
        }
        return true;
    }

    /*
     * 开启或关闭商城小程序首页装修功能
     */
    public function weappIndexDecorationSettingSave(){
        $data = [
            'fieldValue'=>(int)input("weappIndexDecoration")
        ];
        Db::name('sys_configs')->where(['fieldCode'=>'weAppIndexDecoration'])->update($data);
        return WSTReturn('保存成功',1);
    }

    /*
     * 获取商城是否开启小程序首页装修
     */
    public function getWeappIndexDecorationSetting(){
        return Db::name('sys_configs')->where(['fieldCode'=>'weAppIndexDecoration'])->value('fieldValue');
    }
}
