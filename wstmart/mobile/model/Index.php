<?php
namespace wstmart\mobile\model;
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
		$cacheData = cache('MO_CATS_ADS'.$limit);
		///if($cacheData)return $cacheData;
		$rs = Db::name('goods_cats')->where(['dataFlag'=>1,'isShow'=>1,'parentId'=>0,'isFloor'=>1])->field('catId,catName')->order('catSort asc')->limit($limit,1)->select();
		if($rs){
			$rs= $rs[0];
			$t = new T();
			$rs['ads'] = $t->listAds('mo-ads-'.$limit,'1');
			$rs['goods'] = Db::name('goods')->alias('g')->join('__RECOMMENDS__ r','g.goodsId=r.dataId')->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId','left')
			->where([['r.goodsCatId','=',$rs['catId']],['g.isSale','=',1],['g.dataFlag','=',1],['g.goodsStatus','=',1],['r.dataSrc','=',0],['r.dataType','=',0]])
			->field('g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.marketPrice,g.saleNum,gs.totalScore,gs.totalUsers,g.goodsAuthor,g.thumbsNum')->order('g.thumbsNum desc,r.dataSort asc')->limit(6)->select();
			if($rs['goods']){
				foreach ($rs['goods'] as $key =>$v){
					$rs['goods'][$key]['praiseRate'] = ($v['totalScore']>0)?(sprintf("%.2f",$v['totalScore']/($v['totalUsers']*15))*100).'%':'100%';
				}
			}
			$rs['currPage'] = $limit;
		}
		cache('MO_CATS_ADS'.$limit,$rs,86400);
		return $rs;
	}

	/**
	* 获取系统消息
	*/
	function getSysMsg($msg='',$order='',$follow='',$history=''){
		$data = [];
		$userId = (int)session('WST_USER.userId');
		if($msg!=''){
			$data['message']['num'] = Db::name('messages')->where(['receiveUserId'=>$userId,'msgStatus'=>0,'dataFlag'=>1])->count();
		}
		if($order!=''){
			$data['order']['waitPay'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>-2,'dataFlag'=>1])->count();
			$data['order']['waitSend'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>0,'dataFlag'=>1])->count();
			$data['order']['waitReceive'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>1,'dataFlag'=>1])->count();
			$data['order']['waitAppraise'] = Db::name('orders')->where(['userId'=>$userId,'orderStatus'=>2,'isAppraise'=>0,'dataFlag'=>1])->count();
		}
		if($follow!=''){
			$data['follow']['goods'] = Db::name('favorites')->where(['userId'=>$userId,'favoriteType'=>0])->count();
			$data['follow']['shops'] = Db::name('favorites')->where(['userId'=>$userId,'favoriteType'=>1])->count();
		}
		if($history!=''){
			$history = (array)cookie("wx_history_goods");
			$data['history']['num'] = count($history);
		}
		return $data;
	}

    /*
     * 获取商城是否开启首页自定义页面功能
     */
    public function getCustomPagesSetting(){
        return  Db::name('custom_pages')->where(['dataFlag'=>1,'isIndex'=>1,'pageType'=>1])->value('id');
    }

    /*
     * 获取商城首页自定义页面的页面标题
     */
    public function getCustomPageTitle($pageId){
        $pageAttr = Db::name('custom_pages')->where(['dataFlag'=>'1','id'=>$pageId])->value('attr');
        $pageAttr = unserialize($pageAttr);
        return $pageAttr['title'];
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
     * 获取首页自定义多店铺组件的店铺
     */
    public function customPageShopsList(){
        $radius = (int)input('radius');
        $lng = (float)input("longitude");
        $lat = (float)input("latitude");
        $where = [];
        $where[] = ['dataFlag','=',1];
        $where[] = ['shopStatus','=',1];
        $where[] = ['applyStatus','=',2];
        $where2 = '';
        if($radius>0){
            $where2 = "round(6378.138*2*asin(sqrt(pow(sin( (".$lat."*pi()/180-s.latitude*pi()/180)/2),2)+cos(".$lat."*pi()/180)*cos(s.latitude*pi()/180)* pow(sin( (".$lng."*pi()/180-s.longitude*pi()/180)/2),2)))*1000)/1000 < ".$radius;
        }
        $rs['shops'] = Db::name('shops')
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
        foreach ($rs['shops'] as $key =>$v){
            $shopIds[] = $v['shopId'];
            $rs['shops'][$key]['totalScore'] = WSTScore($v["totalScore"]/3, $v["totalUsers"]==0?1: $v["totalUsers"]);
            $rs['shops'][$key]['goodsScore'] = WSTScore($v['goodsScore'],$v['goodsUsers']);
            $rs['shops'][$key]['serviceScore'] = WSTScore($v['serviceScore'],$v['serviceUsers']);
            $rs['shops'][$key]['timeScore'] = WSTScore($v['timeScore'],$v['timeUsers']);
        }
        $goodsCats = Db::name('cat_shops')->alias('cs')->join('__GOODS_CATS__ gc','cs.catId=gc.catId and gc.dataFlag=1','left')
            ->where([['shopId','in',$shopIds]])->field('cs.shopId,gc.catName')->select();
        foreach ($goodsCats as $v){
            $goodsCatMap[$v['shopId']][] = $v['catName'];
        }
        foreach ($rs['shops'] as $key =>$v){
            $rs['shops'][$key]['catshops'] = (isset($goodsCatMap[$v['shopId']]))?implode(',',$goodsCatMap[$v['shopId']]):'';
        }
        $location = '';
        if(WSTConf('CONF.mapKey')!=''){
            $res = WSTLatLngAddress($lat,$lng);
            $location = $res['result']['address'];
        }
        $rs['location'] = $location;
        return $rs;
    }
}
