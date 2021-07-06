<?php
namespace wstmart\wechat\controller;
use think\Db;
use wstmart\common\model\GoodsCats;
use wstmart\common\model\GoodsConsult as CG;
use wstmart\common\model\Attributes as AT;
use wstmart\home\model\Goods as HM;
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
 * 商品控制器
 */
class Goods extends Base{
	/**
	 * 商品主页
	 */
	public function detail(){
        $root = WSTDomain();
		$m = model('goods');
		$goods = $m->getBySale(input('goodsId/d'));
        // 找不到商品记录
        if(empty($goods))return $this->fetch('error_lost');
		hook('wechatControllerGoodsIndex',['getParams'=>input()]);        
        // 分类信息
        $catInfo = Db::name("goods_cats")->field("wechatDetailTheme")->where(['catId'=>$goods['goodsCatId'],'dataFlag'=>1])->find();
		if(!empty($goods)){
        $rule = '/<img src="\/(upload.*?)"/';
        preg_match_all($rule, $goods['goodsDesc'], $images);

        foreach($images[0] as $k=>$v){
            $goods['goodsDesc'] = str_replace('/'.$images[1][$k], $root.'/'.WSTConf("CONF.goodsLogo") . "\"  data-echo=\"".$root."/".WSTImg($images[1][$k],3), $goods['goodsDesc']);
        }
            $history = cookie("wx_history_goods");
            $history = is_array($history)?$history:[];
            array_unshift($history, (string)$goods['goodsId']);
            $history = array_values(array_unique($history));
            if(!empty($history)){
                cookie("wx_history_goods",$history,25920000);
            }
        }
        if(WSTConf('CONF.wxenabled')==1){
	        $we = WSTWechat();
	        $datawx = $we->getJsSignature(request()->scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	        $this->assign("datawx", $datawx);
        }
		$goods['haveThumb'] = $m->haveThumb();
        $goods['consult'] = model('GoodsConsult')->firstQuery($goods['goodsId']);
        $goods['appraises'] = model('GoodsAppraises')->getGoodsEachApprNum($goods['goodsId']);

		$this->assign("info", $goods);
		return $this->fetch($catInfo['wechatDetailTheme']);
	}
    /**
     * 搜索商品列表
     */
    public function search(){
        $this->assign("keyword", input('keyword'));
        $this->assign("minPrice", input('minPrice/d'));
        $this->assign("maxPrice", input('maxPrice/d'));
        $this->assign("brandId", input('brandId/d'));
		$this->assign("searchType", input('stype/d'));
        return $this->fetch('goods_search');
    }
	/**
     * 商品列表
     */
    public function lists(){
        $catId = input('cat/d');
    	$this->assign("keyword", input('keyword'));
    	$this->assign("catId", $catId);
        $this->assign("minPrice", input('minPrice/d'));
        $this->assign("maxPrice", input('maxPrice/d'));
    	$this->assign("brandId", input('brandId/d'));
		$this->assign("searchType", input('stype/d'));
        $this->assign("fl", input('fl/d'));
        // 分类信息
        $catInfo = Db::name("goods_cats")->field("catName,seoTitle,seoKeywords,seoDes,wechatCatListTheme,showWay")->where(['catId'=>$catId,'dataFlag'=>1])->find();
        $this->assign("catInfo",$catInfo);
    	return $this->fetch($catInfo['wechatCatListTheme']?$catInfo['wechatCatListTheme']:'goods_list');
    }
    /**
     * 获取列表
     */
    public function pageQuery(){
    	$m = model('goods');
        $gc = new GoodsCats();
        $catId = (int)input('catId');
        $catFl = (int)Input('fl/d',100);// 1优惠专区
        $data = []; 
        if($catId>0){
            $goodsCatIds = $gc->getParentIs($catId);
        }else{
            $goodsCatIds = [];
        }

        //处理已选属性
        $vs = input('vs');
        $vs = ($vs!='')?explode(',',$vs):[];
        $data['arvs'] = $vs;
        $data['vs'][] = implode(',',$vs);
         
        $at = new AT();
        $goodsFilter = $at->listQueryByFilter((int)input('catId/d'));
        $ngoodsFilter = [];
        if(!empty($vs)){
            // 存在筛选条件,取出符合该条件的商品id,根据商品id获取可选属性进行拼凑
            $goodsId = model('goods')->filterByAttributes();

            $attrs = model('Attributes')->getAttribute($goodsId);
            // 去除已选择属性
            foreach ($attrs as $key =>$v){
                if(!in_array($v['attrId'],$vs)){$ngoodsFilter[] = $v;}
            }
        }else{
            // 当前无筛选条件,取出分类下所有属性
            foreach ($goodsFilter as $key =>$v){
                if(!in_array($v['attrId'],$vs))$ngoodsFilter[] = $v;
            }
        }
        $fl_data = '';
        if ($catFl) {
            switch ($catFl) {
                case '1': // 优惠专区
                    $fl_data = ['dataType' => 0, 'dataSrc' => 0];
                    break;
                case '2': // 精选作品
                    $fl_data = ['dataType' => 2, 'dataSrc' => 0];
                    break;

                default:
                    $fl_data = '';
                    break;
            }
        }
        $data['goodsPage'] = $m->pageQuery($goodsCatIds,$fl_data);
        foreach ($ngoodsFilter as $k => $val) {
           $result = array_values(array_unique($ngoodsFilter[$k]['attrVal']));

           $ngoodsFilter[$k]['attrVal'] = $result;
        }
        $data['goodsFilter'] = $ngoodsFilter;

        foreach ($data['goodsPage']['data'] as $key =>$v){
            $data['goodsPage']['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
            $data['goodsPage']['data'][$key]['praiseRate'] = ($v['totalScore']>0)?(sprintf("%.2f",$v['totalScore']/($v['totalUsers']*15))*100).'%':'100%';
        }
        // `券`标签
        hook('afterQueryGoods',['page'=>&$data['goodsPage']]);
        return $data;
    }

    /**
    * 浏览历史页面
    */
    public function history(){
       return $this->fetch('users/history/list');
    }
    /**
    * 获取浏览历史
    */
    public function historyQuery(){
        $rs = model('goods')->historyQuery();
        if(!empty($rs)){
	        foreach($rs['data'] as $k=>$v){
	            $rs['data'][$k]['goodsImg'] = WSTImg($v['goodsImg'],3,'goodsLogo');
	        }
        }
        return $rs;
    }
	/**
	 *记录点赞数
	 */
	 public function recordThumb(){
        $m = model('goods');
        return $m->recordThumb();
	 }
	 
	 /**
	 *确认是否已点赞
	 **/
	 public function haveThumb(){
        $m = model('goods');
        return $m->haveThumb();
	 }
	 /**
	 * 会员的保底交易商品
	 */
    public function mygoods(){
		$bdStatus = input('bs/d');
		$this->assign('bdStatus',$bdStatus);
		return $this->fetch('users/goods/goods_list');
	}
	/**
	 * 获取会员仓库中的商品
	 */
    public function myGoodsByPage(){
		$m = model('goods');
		$rs = $m->myGoodsByPage();
		$rs['status'] = 1;
		return $rs;
	}
	
	/**
    *   保底回购
    */
    public function backSale(){
        $m = new HM();
        return $m->backSale();
    }
	/**
    *   保底交易提货
    */
    public function bdDelivery(){
        $m = new HM();
        return $m->bdDelivery();
    }
	/**
     * 获取专题商品列表
     */
    public function topicGoods(){
		$catId = Input('catId/d',0);
		//获取商品分类信息
		$where = [];
		$where[] = ['dataSrc','=',0];
		$where[] = ['dataType','=',1];	//热销商品
		$goodsCats = [];
		$gs = Db::name('recommends')
			->where($where)->field('distinct goodsCatId')
			->select();
		foreach($gs as $v){
			$gctId = $v['goodsCatId'];
			$catPath = model('common/goodsCats')->getParentIs($gctId);
			$i = 0;
			foreach($catPath as $cId){
				if($i==0){
					$goodsCats[$gctId]['catName'] = model('common/goodsCats')->getCatName($cId);	//添加父类
				}else if($i==1){	//判断子类
					$goodsCats[$gctId]['children'][$cId] = model('common/goodsCats')->getCatName($cId);	//添加子类
				}
				$i++;
			}
		}
		$ct1 = 0;
		$ct2 = 0;
		if($catId>0){
			$catPath = model('common/goodsCats')->getParentIs($catId);
			$i = 0;
			foreach($catPath as $c){
				if($i==0)	$ct1 = $c;
				else if($i==1)	$ct2 == $c;
			}
		}
		// 分类信息
		$catInfo = Db::name("goods_cats")->field("catName,seoTitle,seoKeywords,seoDes,mobileCatListTheme,showWay")->where(['catId'=>$catId,'dataFlag'=>1])->find();
		$this->assign("catInfo",$catInfo);
		$this->assign("catId", $catId);
		$this->assign("goodsCats",$goodsCats);
		$this->assign('msort',(int)input("param.msort",0));//筛选条件
		$this->assign('mdesc',(int)input("param.mdesc",1));//升降序
		$this->assign('sprice',input("param.sprice"));//价格范围
		$this->assign('eprice',input("param.eprice"));
		$this->assign('ct1',$ct1);//一级分类
		$this->assign('ct2',$ct2);//二级分类
		$this->assign("keyword", input('keyword'));
		return $this->fetch("goods_topic");
    }

	public function pageQueryTopicGoods(){
		$catId = Input('catId/d',0);
		$where = [];
		$where[] = ['r.dataSrc','=',0];
		$where[] = ['g.isSale','=',1];
		$where[] = ['g.goodsStatus','=',1];
		$where[] = ['g.dataFlag','=',1];
		$goods=[];
		$where[] = ['r.dataType','=',1];	//热销商品
		if($catId>0){
			$aId[] = $catId;
			$childIds = model('common/goodsCats')->getChildIds($aId);
			$where[] = ['r.goodsCatId','in',$childIds];
		}
		$goods = Db::name('goods')->alias('g')->join('__RECOMMENDS__ r','g.goodsId=r.dataId')
			->join('__SHOPS__ s','g.shopId=s.shopId')
			->where($where)->field('g.goodsTips,s.shopName,s.shopId,g.goodsId,goodsName,goodsImg,goodsSn,goodsStock,saleNum,shopPrice,marketPrice,isSpec,appraiseNum,visitNum,isNew,goodsUnit,thumbsNum,goodsAuthor,saleType')
			->order('g.thumbsNum desc,r.dataSort asc')->paginate(16)->toArray();
		$ids = [];
		foreach($goods['data'] as $key =>$v){
			if($v['isSpec']==1)$ids[] = $v['goodsId'];
			if($v['saleType']==1)
				$goods['data'][$key]['shopPrice'] = '议价';
			else if($v['saleType']==2)
				$goods['data'][$key]['shopPrice'] = '仅展示';
		}
		if(!empty($ids)){
			$specs = [];
			$rs = Db::name('goods_specs gs ')->where([['goodsId','in',$ids],['dataFlag','=',1]])->order('id asc')->select();
			foreach ($rs as $key => $v){
				$specs[$v['goodsId']] = $v;
			}
			foreach($goods as $key =>$v){
				if(isset($v['goodsId']))
					if(isset($specs[$v['goodsId']]))
						$goods[$key]['specs'] = $specs[$v['goodsId']];
			}
		}
		return $goods;
	}
}
