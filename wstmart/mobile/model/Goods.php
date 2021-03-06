<?php
namespace wstmart\mobile\model;
use wstmart\common\model\Author;
use wstmart\common\model\Goods as CGoods;
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
 * 商品类
 */
class Goods extends CGoods{
	/**
	 * 获取列表
	 */
	public function pageQuery($goodsCatIds = [], $fl = ''){
		//查询条件
		$keyword = input('keyword');
		$brandId = input('brandId/d');
        $goodsType = input('goodsType');
        $saleType = input('saleType');
		$searchType = input('searchType/d');
        $tab_type    = (int)Input('tab_type',1);
		$where = $where2 = [];
		$where[] = ['goodsStatus', '=', 1];
		$where[] = ['g.dataFlag', '=', 1];
		$where[] = ['isSale', '=', 1];
		if($keyword!=''){
			if($searchType == 3){
				$where[] = ['goodsAuthor','like','%'.$keyword.'%'];
			}else{
                if($tab_type == 1){
                    $where[] = ['g.goodsName','like','%'.$keyword.'%'];
                }elseif ($tab_type == 2){
                    $where[] = ['g.goodsAuthor','like','%'.$keyword.'%'];
                }else{
                    $where[] = ['g.goodsName','like','%'.$keyword.'%'];
                }
			}
		}
		if($brandId>0)$where[] = ['g.brandId','=',$brandId];
		if($goodsType!='')$where[] = ['g.goodsType', '=', $goodsType];
        if($saleType!='' and $saleType != -1)$where[] = ['g.saleType', '=', $saleType];

		//排序条件
		$orderBy = input('condition/d',0);
		$orderBy = ($orderBy>=0 && $orderBy<=4)?$orderBy:0;
		$order = (input('desc/d',0)==1)?1:0;
		$pageBy = ['thumbsNum','shopPrice','visitNum','saleTime'];
		$pageOrder = ['desc','asc'];
		if(!empty($goodsCatIds))$where[] = ['goodsCatIdPath','like',implode('_',$goodsCatIds).'_%'];

        if ($fl) {
            $where[] = ['r.dataType','=',$fl['dataType']];	//热销商品
            $where[] = ['r.dataSrc','=',$fl['dataSrc']];	//热销商品
            $list = Db::name('goods')->alias('g')
                ->join('__RECOMMENDS__ r','g.goodsId=r.dataId')
                ->join("__SHOPS__ s","g.shopId = s.shopId")
                ->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId','left')
                ->where($where)
                ->field('g.goodsId,goodsName,saleNum,shopPrice,marketPrice,isSpec,goodsImg,appraiseNum,visitNum,s.shopId,shopName,isSelf,isFreeShipping,gallery,gs.totalScore,gs.totalUsers,g.saleType,g.thumbsNum,g.goodsType,g.goodsStock')
                ->order($pageBy[$orderBy]." ".$pageOrder[$order].",goodsStock desc")
                ->paginate(input('pagesize/d'))->toArray();
        } else {
            $list = Db::name('goods')->alias('g')
                ->join("__SHOPS__ s","g.shopId = s.shopId")
                ->join('__GOODS_SCORES__ gs','gs.goodsId=g.goodsId','left')
                ->where($where)
                ->field('g.goodsId,goodsName,saleNum,shopPrice,marketPrice,isSpec,goodsImg,appraiseNum,visitNum,s.shopId,shopName,isSelf,isFreeShipping,gallery,gs.totalScore,gs.totalUsers,g.saleType,g.thumbsNum,g.goodsType,g.goodsStock')
                ->order($pageBy[$orderBy]." ".$pageOrder[$order].",goodsStock desc")
                ->paginate(input('pagesize/d'))->toArray();
        }
		return $list;
	}
    
	/**
	 * 关键字
	 */
	public function getKeyWords($name){
		$words = WSTAnalysis($name);
		if(!empty($words)){
			$str = [];
			if(count($words)==1){
				$str[] = ['g.goodsSerachKeywords','LIKE','%'.$words[0].'%'];
			}else{
				foreach ($words as $v){
					$str[] = ['g.goodsSerachKeywords','LIKE','%'.$v.'%'];
				}
			}
			return $str;
		}
		return "";
	}
	/**
	 * 获取商品资料在前台展示
	 */
	public function getBySale($goodsId){
		$key = input('key');
		// 浏览量
		$this->where('goodsId',$goodsId)->setInc('visitNum',1);
		$rs = Db::name('goods')->where(['goodsId'=>$goodsId,'dataFlag'=>1])->find();
		
		if(!empty($rs)){
			$rs['read'] = false;
			$rs['goodsDesc'] = htmlspecialchars_decode($rs['goodsDesc']);
			$rs['goodsDesc'] = str_replace('${DOMAIN}',WSTConf('CONF.resourcePath'),$rs['goodsDesc']);
			$rs['catName'] = Db::name('goods_cats')->where(['catId'=>$rs['goodsCatId']])->find()['catName'];

			//判断是否可以公开查看
			$viKey = WSTShopEncrypt($rs['shopId']);
			if(($rs['isSale']==0 || $rs['goodsStatus']==0) && $viKey != $key)return [];
			if($key!='')$rs['read'] = true;
			//获取店铺信息
			$rs['shop'] = model('shops')->getShopInfo((int)$rs['shopId']);
			$rs['shop']['shopDesc'] = Db::name('shop_configs')->where(['shopId'=>(int)$rs['shopId']])->find()['shopDesc'];

			if(empty($rs['shop']))return [];
			$goodsCats = Db::name('cat_shops')->alias('cs')->join('__GOODS_CATS__ gc','cs.catId=gc.catId and gc.dataFlag=1','left')->join('__SHOPS__ s','s.shopId = cs.shopId','left')
			->where('cs.shopId',$rs['shopId'])->field('cs.shopId,s.shopTel,gc.catId,gc.catName')->select();
			$rs['shop']['catId'] = $goodsCats[0]['catId'];
			$rs['shop']['shopTel'] = $goodsCats[0]['shopTel'];
			$rs['shop']['count'] = Db::name('goods')->where(['shopId'=>$rs['shopId'],'isSale'=>1,'goodsStatus'=>1])->count();


			$cat = [];
			foreach ($goodsCats as $v){
				$cat[] = $v['catName'];
			}
			$rs['shop']['cat'] = implode('，',$cat);
			if(empty($rs['shop']))return [];
			$gallery = [];
			$gallery[] = $rs['goodsImg'];
			if($rs['gallery']!=''){
				$tmp = explode(',',$rs['gallery']);
				$gallery = array_merge($gallery,$tmp);
			}
			$rs['gallery'] = $gallery;
			//获取规格值
			$specs = Db::name('spec_cats')->alias('gc')->join('__SPEC_ITEMS__ sit','gc.catId=sit.catId','inner')
			->where(['sit.goodsId'=>$goodsId,'gc.isShow'=>1,'sit.dataFlag'=>1])
			->field('gc.isAllowImg,gc.catName,sit.catId,sit.itemId,sit.itemName,sit.itemImg')
			->order('gc.isAllowImg desc,gc.catSort asc,gc.catId asc')->select();
			$rs['spec']=[];
			foreach ($specs as $key =>$v){
				$rs['spec'][$v['catId']]['name'] = $v['catName'];
				$rs['spec'][$v['catId']]['list'][] = $v;
			}
			//获取销售规格
			$sales = Db::name('goods_specs')->where('goodsId',$goodsId)->field('id,isDefault,productNo,specIds,marketPrice,specPrice,specStock')->select();
			if(!empty($sales)){
				foreach ($sales as $key =>$v){
					$str = explode(':',$v['specIds']);
					sort($str);
					unset($v['specIds']);
					$rs['saleSpec'][implode(':',$str)] = $v;
				}
			}
			//获取商品属性
			$rs['attrs'] = Db::name('attributes')->alias('a')->join('goods_attributes ga','a.attrId=ga.attrId','inner')
			->where(['a.isShow'=>1,'dataFlag'=>1,'goodsId'=>$goodsId])->field('a.attrName,ga.attrVal')
			->order('attrSort asc')->select();
			//获取商品评分
			$rs['scores'] = Db::name('goods_scores')->where('goodsId',$goodsId)->field('totalScore,totalUsers')->find();
			$rs['scores']['totalScores'] = ($rs['scores']['totalScore']==0)?5:WSTScore($rs['scores']['totalScore'],$rs['scores']['totalUsers'],5,0,3);
			WSTUnset($rs, 'totalUsers');
			//关注
			$f = model('Favorites');
			$rs['favShop'] = $f->checkFavorite($rs['shopId'],1);
			$rs['favGood'] = $f->checkFavorite($goodsId,0);

            //获取作者信息
            $author = new Author();
            $author_data = $author->get_format_data($goodsId,$rs['shop']);
            $rs['author'] = $author_data;
		}
		return $rs;
	}


	public function historyQuery(){
		$ids = cookie("wx_history_goods");
		if(empty($ids))return [];
	    $where = [];
	    $where[] = ['isSale','=',1];
	    $where[] = ['goodsStatus','=',1]; 
	    $where[] = ['dataFlag','=',1]; 
	    $where[] = ['goodsId','in',$ids];
	    $orderBy = "field(`goodsId`,".implode(',',$ids).")";
        return Db::name('goods')
                   ->where($where)->field('goodsId,goodsName,goodsImg,saleNum,shopPrice')
                   ->orderRaw($orderBy)
                   ->paginate((int)input('pagesize'))->toArray();
	}
    /**
     * 获取符合筛选条件的商品ID
     */
    public function filterByAttributes(){
    	$vs = input('vs');
    	if($vs=='')return [];
    	$vs = explode(',',$vs);
    	$goodsIds = [];
    	$prefix = config('database.prefix');
		//循环遍历每个属性相关的商品ID
	    foreach ($vs as $v){
	    	$goodsIds2 = [];
	    	$attrVal = input('v_'.(int)$v);
	    	if($attrVal=='')continue;
		    	$sql = "select goodsId from ".$prefix."goods_attributes 
		    	where attrId=".(int)$v." and find_in_set('".$attrVal."',attrVal) ";
				$rs = Db::query($sql);
				if(!empty($rs)){
					foreach ($rs as $vg){
						$goodsIds2[] = $vg['goodsId'];
					}
				}
			//如果有一个属性是没有商品的话就不需要查了
			if(empty($goodsIds2))return [-1];
			//第一次比较就先过滤，第二次以后的就找集合
			$goodsIds2[] = -1;
			if(empty($goodsIds)){
				$goodsIds = $goodsIds2;
			}else{
				$goodsIds = array_intersect($goodsIds,$goodsIds2);
			}
		}
		return $goodsIds;
    }
	/**
	 * 会员仓库中的商品
	 */
    public function myGoodsByPage(){
    	$userId = (int)session('WST_USER.userId');
		$bdStatus = input('bdStatus/d');
    	$where[] = ['ownerId', '=', $userId];
		//$where['shopId'] = 0;
		$where[] = ['dataFlag', '=', 1];
		if($bdStatus == 4){	// 待审核
			$where[] = ['goodsStatus', '=', 0];
			$where[] = ['isSale', '=', 1];
			$where[] = ['goodsType','exp',Db::raw('=2 or oriGoodsId>0')];
		}else{
			$where[] = ['goodsStatus', '=', 1];
			$where[] = ['bdStatus', '=', $bdStatus];
			$where[] = ['oriGoodsId','>','0'];
		}
		$c1Id = (int)input('cat1');
		$c2Id = (int)input('cat2');
		$goodsName = input('goodsName');
		if($goodsName != ''){
			$where[] = ['goodsName','like',"%$goodsName%"];
		}
		if($c2Id!=0 && $c1Id!=0){
			$where[] = ['shopCatId2', '=', $c2Id];
		}else if($c1Id!=0){
			$where[] = ['shopCatId1', '=', $c1Id];
		}
		$rs = $this->alias('m')
		    ->where($where)
		    ->where('goodsStatus','<>',-1)
			->field('goodsId,goodsName,goodsImg,goodsType,goodsSn,isSale,isBest,isHot,isNew,isRecom,goodsStock,saleNum,shopPrice,isSpec,oriGoodsId')
			->order('saleTime', 'desc')
			->paginate(input('pagesize/d'))->toArray();
        foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['verfiycode'] =  WSTShopEncrypt(0);
			$rs['data'][$key]['bdStatus'] = $bdStatus;
		}
		return $rs;
	}
}
