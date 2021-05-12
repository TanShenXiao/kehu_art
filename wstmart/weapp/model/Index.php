<?php
namespace wstmart\weapp\model;
use wstmart\common\model\Tags as T;
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
 * 默认类
 */
class Index extends Base{
	/**
	 * 楼层
	 */
	public function pageQuery(){
		$limit = (int)input('post.currPage');
		if($limit>9)return;
		$cacheData = cache('WE_CATS_ADS'.$limit);
		if($cacheData)return $cacheData;
		$rs = Db::name('goods_cats')->where(['dataFlag'=>1,'isShow'=>1,'parentId'=>0,'isFloor'=>1])->field('catId,catName')->order('catSort asc,catId asc')->limit($limit,1)->select();
		if($rs){
			$rs= $rs[0];
			$t = new T();
			$rs['ads'] = $t->listAds('weapp-ads-'.$limit,'1');
			$rs['goods'] = Db::name('goods')->alias('g')->join('__RECOMMENDS__ r','g.goodsId=r.dataId')->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId')
			->where(['r.goodsCatId'=>$rs['catId'],'g.isSale'=>1,'g.dataFlag'=>1,'g.goodsStatus'=>1,'r.dataSrc'=>0,'r.dataType'=>1])
			->field('g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.saleNum,gs.totalScore,gs.totalUsers')->order('r.dataSort asc')->select();
			if(empty($rs['goods'])){
				$rs['goods'] = Db::name('goods')->alias('g')->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId')
				->where([['g.goodsCatIdPath','like',$rs['catId'].'_%'],['g.isSale','=',1],['g.dataFlag','=',1],['g.goodsStatus','=',1],['g.isHot','=',1]])
				->field('g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.saleNum,gs.totalScore,gs.totalUsers')
				->order('saleNum desc,goodsId asc')->limit(6)->select();
			}
			if($rs['goods']){
				foreach ($rs['goods'] as $key =>$v){
					$rs['goods'][$key]['praiseRate'] = ($v['totalScore']>0)?(sprintf("%.2f",$v['totalScore']/($v['totalUsers']*15))*100).'%':'100%';
				}
			}
			$rs['currPage'] = $limit;
		}
		cache('WE_CATS_ADS'.$limit,$rs,86400);
		return $rs;
	}
	/**
	 * 获取系统消息
	 */
	function getSysMsg($msg='',$order=''){
		$data = [];
		$userId = $this->getUserId();
		if($msg!=''){
			$data['message']['num'] = Db::name('messages')->where(['receiveUserId'=>$userId,'msgStatus'=>0,'dataFlag'=>1])->count();
		}
		if($order!=''){
			$data['order']['waitPay'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>-2,'dataFlag'=>1])->count();
			$data['order']['waitSend'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>0,'dataFlag'=>1])->count();
			$data['order']['waitReceive'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>1,'dataFlag'=>1])->count();
			$data['order']['waitAppraise'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>2,'isAppraise'=>0,'dataFlag'=>1])->count();
		}
		return $data;
	}

    /*
     * 获取商城是否开启首页自定义页面功能
     */
    public function getCustomPagesSetting(){
        return Db::name('custom_pages')->where(['dataFlag'=>1,'isIndex'=>1,'pageType'=>3])->value('id');
    }

    /*
     * 判断自定义页面是否含有多店铺组件
     */
    public function hasShopComponent($pageId){
        $data = Db::name('custom_page_decoration')->field('name')->where(['dataFlag'=>'1','pageId'=>$pageId])->select();
        foreach($data as $k => $v){
            if($v['name'] == 'shop'){
                return true;
            }
        }
        return false;
    }

    /*
     * 获取商城首页自定义页面数据
     */
    public function getCustomPageDecorationData(){
        $lng = (float)input("longitude");
        $lat = (float)input("latitude");
        $pageId = input('pageId');
        $pageData = Db::name('custom_pages')->where(['dataFlag'=>'1','id'=>$pageId])->value('attr');
        $pageAttr = unserialize($pageData);
        $data = Db::name('custom_page_decoration')->field('name,attr,sort')->where(['dataFlag'=>'1','pageId'=>$pageId])->order('sort asc')->select();
        foreach($data as $k => $v){
            $data[$k]["attr"] = unserialize($data[$k]["attr"]);
        }
        $data[] = [
            'name'=>'page',
            'title'=>$pageAttr['title']
        ];
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
                    $swiperData['link'] = $v["attr"]["link"][$i];
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
                    $navData['link'] = $v["attr"]["item_link"][$i];
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
            }else if($v["name"] == "image"){
                $data[$k]['backgroundColor'] = $v["attr"]["background_color"];
                $data[$k]['paddingTop'] = $v["attr"]["padding_top"];
                $data[$k]['paddingBottom'] = $v["attr"]["padding_bottom"];
                $data[$k]['paddingLeft'] = $v["attr"]["padding_left"];
                $data[$k]['paddingRight'] = $v["attr"]["padding_right"];
                for($i=0;$i<count($v["attr"]["img"]);$i++){
                    $imageData['img'] = $v["attr"]["img"][$i];
                    $imageData['link'] = $v["attr"]["link"][$i];
                    $data[$k]["images"][] = $imageData;
                }
                unset($data[$k]['attr']);
            }else if($v["name"] == "shopwindow"){
                $data[$k]['backgroundColor'] = $v["attr"]["background_color"];
                $data[$k]['layout'] = $v["attr"]["layout"];
                for($i=0;$i<count($v["attr"]["img"]);$i++){
                    $imageData['img'] = $v["attr"]["img"][$i];
                    $imageData['link'] = $v["attr"]["link"][$i];
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
                $data[$k]['coupons'] = $this->getCouponsByIds($v['attr']['coupon_select_ids']);
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
                    $noticeData['link'] = $v["attr"]["link"][$i];
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
                $data[$k]['link'] = $v["attr"]["link"];
                $data[$k]['alignment'] = $v["attr"]["alignment"];
                unset($data[$k]['attr']);
            }else if($v["name"] == "image_text"){
                $data[$k]['style'] = $v["attr"]["style"];
                $data[$k]['title'] = $v["attr"]["title"];
                $data[$k]['desc'] = $v["attr"]["desc"];
                $data[$k]['link'] = $v["attr"]["link"];
                $data[$k]['img'] = $v["attr"]["img"];
                $data[$k]['verticalPadding'] = $v["attr"]["vertical_padding"];
                unset($data[$k]['attr']);
            }else if($v["name"] == "shop"){
                $data[$k]['title'] = $v["attr"]["title"];
                $data[$k]['shops'] = $this->getShops($v['attr']['search_radius'],$lng,$lat);
                $location = '未知';
                if(WSTConf('CONF.mapKey')!=''){
                    $res = WSTLatLngAddress($lat,$lng);
                    $location = $res['result']['address'];
                }
                $data[$k]['location'] = $location;
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
     * 获取后台自定义的底部导航栏菜单
     */
    public function getTabbarMenu($pageId){
        $rs = Db::name('custom_page_decoration')->field('name,attr,sort')->where(['pageId'=>$pageId,'name'=>'tabbar','dataFlag'=>'1'])->find();
        $rs['attr'] = unserialize($rs["attr"]);
        $rs['color'] = $rs["attr"]["text_color"];
        $rs['selectedColor'] = $rs["attr"]["text_checked_color"];
        $rs['backgroundColor'] = $rs["attr"]["background_color"];
        $rs['borderStyle'] = $rs["attr"]["border_color"];
        for($i=0;$i<count($rs["attr"]["icon"]);$i++){
            $tabbarData['icon'] = $rs["attr"]["icon"][$i];
            $tabbarData['selectIcon'] = $rs["attr"]["select_icon"][$i];
            $tabbarData['link'] = $rs["attr"]["link"][$i];
            $tabbarData['text'] = $rs["attr"]["text"][$i];
            $tabbarData['menuFlag'] = $rs["attr"]["menu_flag"][$i];
            $rs["tabbars"][] = $tabbarData;
        }
        unset($rs['attr']);
        return $rs;
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
    public function getCouponsByIds($couponIds=''){
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
     * 获取首页自定义多店铺组件的店铺
     */
    public function getShops($radius=0,$lng,$lat){
        $where = [];
        $where[] = ['dataFlag','=',1];
        $where[] = ['shopStatus','=',1];
        $where[] = ['applyStatus','=',2];
        $where2 = '';
        if($radius>0){
            $where2 = "round(6378.138*2*asin(sqrt(pow(sin( (".$lat."*pi()/180-s.latitude*pi()/180)/2),2)+cos(".$lat."*pi()/180)*cos(s.latitude*pi()/180)* pow(sin( (".$lng."*pi()/180-s.longitude*pi()/180)/2),2)))*1000)/1000 < ".$radius;
        }
        $rs = Db::name('shops')
            ->alias('s')
            ->join('__SHOP_SCORES__ ss','ss.shopId = s.shopId','left')
            ->fieldRaw('s.shopId,s.shopImg,s.shopName,s.shopCompany,ss.totalScore,ss.totalUsers,ss.goodsScore,ss.goodsUsers,ss.serviceScore,ss.serviceUsers,ss.timeScore,ss.timeUsers,s.areaIdPath,s.shopAddress')
            ->fieldRaw("round(6378.138*2*asin(sqrt(pow(sin( (".$lat."*pi()/180-s.latitude*pi()/180)/2),2)+cos(".$lat."*pi()/180)*cos(s.latitude*pi()/180)* pow(sin( (".$lng."*pi()/180-s.longitude*pi()/180)/2),2)))*1000)/1000 as distince")
            ->where($where)
            ->where($where2)
            ->select();
        $shopIds = [];
        $totalScores = [];
        $goodsCatMap = [];
        foreach ($rs as $key =>$v){
            $shopIds[] = $v['shopId'];
            $rs[$key]['totalScore'] = WSTScore($v["totalScore"]/3, $v["totalUsers"]==0?1: $v["totalUsers"]);
            $rs[$key]['goodsScore'] = WSTScore($v['goodsScore'],$v['goodsUsers']);
            $rs[$key]['serviceScore'] = WSTScore($v['serviceScore'],$v['serviceUsers']);
            $rs[$key]['timeScore'] = WSTScore($v['timeScore'],$v['timeUsers']);
        }
        $goodsCats = Db::name('cat_shops')->alias('cs')->join('__GOODS_CATS__ gc','cs.catId=gc.catId and gc.dataFlag=1','left')
            ->where([['shopId','in',$shopIds]])->field('cs.shopId,gc.catName')->select();
        foreach ($goodsCats as $v){
            $goodsCatMap[$v['shopId']][] = $v['catName'];
        }
        foreach ($rs as $key =>$v){
            $rs[$key]['catshops'] = (isset($goodsCatMap[$v['shopId']]))?implode(',',$goodsCatMap[$v['shopId']]):'';
        }
        return $rs;
    }

    /*
     * 获取首页自定义新闻组件的新闻
     */
    function getNews($count=2,$articleIds=''){
        $where = [];
        $where[] = ['dataFlag','=',1];
        if($articleIds) {
            $where[] = ['articleId', 'in', $articleIds];
            $count = count(explode(',',$articleIds));
        }
        $rs = Db::name('articles')->alias('a')
            ->field('*')
            ->where($where)
            ->order('catSort asc,createTime desc')
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
	/**
	 * 用户注册（接口注册）
	 */
	function registerByApi(){
		$params = input();
		$loginName = $params['loginName'];
		$password = $params['password'];
		if (!preg_match('/^[A-Za-z0-9_]+$/', $loginName)) {
			return jsonReturn("用户名必须是数字、字母或下划线!");
		}

		//检测账号是否存在
		$rs = WSTCheckLoginKey($loginName);
		//$loginName = WSTRandomLoginName($loginName);
		if($rs['status']==1){
			$data['loginName'] = $loginName;
			//$data['userName'] = $params['userName'];
			$data["loginSecret"] = rand(1000,9999);
			$data['loginPwd'] = md5($password.$data['loginSecret']);
			$data['createTime'] = date('Y-m-d H:i:s');
			$data['dataFlag'] = 1;
			$data['lastTime'] = date('Y-m-d H:i:s');
			$data['lastIP'] = request()->ip();
			//$data['userSex'] = (int)input('userSex',0);
			//$data['weOpenId'] = $openId;
			$um = Model('Users');
			$userId = $um->data($data)->save();
			if(false !== $userId){
				logOperation('api注册会员');

				$user = $um->get(['userId'=>$userId]);
				//注册成功后执行钩子
				hook('afterUserRegist',['user'=>$user]);

				return jsonReturn('注册成功',1,['userId'=>$um->userId]);
			}
		}else{
			return jsonReturn('账号已存在!',-1);
		}
	}
}
