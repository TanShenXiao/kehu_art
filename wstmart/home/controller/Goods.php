<?php
namespace wstmart\home\controller;
use think\console\Input;
use think\Db;
use wstmart\home\model\Goods as M;
use wstmart\common\model\Goods as CM;
use wstmart\common\model\Attributes as AT;
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
     * 获取商品规格属性
     */
    public function getSpecAttrs(){
    	$m = new M();
    	return $m->getSpecAttrs();
    }
	/**
	 * 获取商品规格属性
	 */
	public function getSpecAndAttrs(){
		$m = model('goods');
		return jsonReturn('获取成功', 1, $m->getSpecAttrs());
	}
    /**
     * 进行商品搜索
     */
    public function search(){
    	//获取商品记录
    	$m = new M();
    	$data = [];
    	$data['isStock'] = Input('isStock/d');
    	$data['isNew'] = Input('isNew/d');
        $data['isFreeShipping'] = input('isFreeShipping/d');
    	$data['orderBy'] = Input('orderBy/d');
    	$data['order'] = Input('order/d',1);
    	$data['keyword'] = input('keyword');
    	$data['minPrice'] = Input('minPrice/d');
    	$data['maxPrice'] = Input('maxPrice/d');
        $data['areaId'] = (int)Input('areaId');
        $data['areaId'] = (int)Input('areaId');
        $data['tab_type'] = (int)Input('tab_type',1);
        $aModel = model('home/areas');

        // 获取地区
        $data['area1'] = $data['area2'] = $data['area3'] = $aModel->listQuery(); // 省级

        // 如果有筛选地区 获取上级地区信息
        
        if($data['areaId']!==0){
            $areaIds = $aModel->getParentIs($data['areaId']);
            /*
              2 => int 440000
              1 => int 440100
              0 => int 440106
            */
            $selectArea = [];
            $areaName = '';
            foreach($areaIds as $k=>$v){
                $a = $aModel->getById($v);
                $areaName .=$a['areaName'];
                $selectArea[] = $a;
            }
            // 地区完整名称
            $selectArea['areaName'] = $areaName;
            // 当前选择的地区
            $data['areaInfo'] = $selectArea;
            $data['area2'] = $aModel->listQuery($areaIds[2]); // 广东的下级
 
            $data['area3'] = $aModel->listQuery($areaIds[1]); // 广州的下级
        }
    	$data['goodsPage'] = $m->pageQuery();
        //作者列表
        $s = model('shops');
        $pagesize = 42;
        $data['author'] = $s->pageQuery($pagesize);

    	return $this->fetch("goods_search",$data);
    }
    
    /**
     * 获取商品列表
     */
    public function lists(){
    	$catId = (int)Input('cat/d');
    	$catFl = (int)Input('fl/d',100);// 1优惠专区
        $goodsCatIds = [];
        if($catId>0){
            $goodsCatIds = model('GoodsCats')->getParentIs($catId);
        }
    	reset($goodsCatIds);
    	//填充参数
    	$data = [];
    	$data['catId'] = $catId;
    	$data['isStock'] = Input('isStock/d');
    	$data['isNew'] = Input('isNew/d');
        $data['isFreeShipping'] = input('isFreeShipping/d');
    	$data['orderBy'] = Input('orderBy/d');
    	$data['order'] = Input('order/d',1);
    	$data['minPrice'] = Input('minPrice');
    	$data['maxPrice'] = Input('maxPrice');
    	$data['attrs'] = [];

        $data['areaId'] = (int)Input('areaId');
        $aModel = model('home/areas');

        // 分类信息
        $catInfo = Db::name("goods_cats")->field("seoTitle,seoKeywords,seoDes,catListTheme")->where(['catId'=>$catId,'dataFlag'=>1])->find();
        $this->assign("catInfo",$catInfo);

        // 获取地区
        $data['area1'] = $data['area2'] = $data['area3'] = $aModel->listQuery(); // 省级

        // 如果有筛选地区 获取上级地区信息
        if($data['areaId']!==0){
            $areaIds = $aModel->getParentIs($data['areaId']);
            /*
              2 => int 440000
              1 => int 440100
              0 => int 440106
            */
            $selectArea = [];
            $areaName = '';
            foreach($areaIds as $k=>$v){
                $a = $aModel->getById($v);
                $areaName .=$a['areaName'];
                $selectArea[] = $a;
            }
            // 地区完整名称
            $selectArea['areaName'] = $areaName;
            // 当前选择的地区
            $data['areaInfo'] = $selectArea;

            $data['area2'] = $aModel->listQuery($areaIds[2]); // 广东的下级
 
            $data['area3'] = $aModel->listQuery($areaIds[1]); // 广州的下级
        }
        
    	$vs = input('vs');
    	$vs = ($vs!='')?explode(',',$vs):[];
    	foreach ($vs as $key => $v){
    		if($v=='' || $v==0)continue;
    		$v = (int)$v;
    		$data['attrs']['v_'.$v] = input('v_'.$v);
    	}
    	$data['vs'] = $vs;

    	$brandIds = Input('brand');

		
        $bgIds = [];// 品牌下的商品Id
        if(!empty($vs)){
            // 存在筛选条件,取出符合该条件的商品id,根据商品id获取可选品牌
            $goodsId = model('goods')->filterByAttributes();
            $data['brandFilter'] = model('Brands')->canChoseBrands($goodsId);
        }else{
           // 取出分类下包含商品的品牌
           $data['brandFilter'] = model('Brands')->goodsListQuery((int)current($goodsCatIds));
        }
        if(!empty($brandIds))$bgIds = model('Brands')->getGoodsIds($brandIds);


    	$data['price'] = Input('price');
    	//封装当前选中的值
    	$selector = [];
    	//处理品牌
        $brandIds = explode(',',$brandIds);
        $bIds = $brandNames = [];
        foreach($brandIds as $bId){
        	if($bId>0){
        		foreach ($data['brandFilter'] as $key =>$v){
        			if($v['brandId']==$bId){
                        array_push($bIds, $v['brandId']);
                        array_push($brandNames, $v['brandName']);
                    }
        		}
                $selector[] = ['id'=>join(',',$bIds),'type'=>'brand','label'=>"品牌","val"=>join('、',$brandNames)];
            }
        }
        // 当前是否有品牌筛选
        if(!empty($selector)){
            $_s[] = $selector[count($selector)-1];
            $selector = $_s;
            unset($data['brandFilter']);
        }
        $data['brandId'] = Input('brand');

    	//处理价格
    	if($data['minPrice']!='' && $data['maxPrice']!=''){
    		$selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>$data['minPrice']."-".$data['maxPrice']];
    	}
        if($data['minPrice']!='' && $data['maxPrice']==''){
        	$selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>$data['maxPrice']."以上"];
    	}
        if($data['minPrice']=='' && $data['maxPrice']!=''){
        	$selector[] = ['id'=>0,'type'=>'price','label'=>"价格","val"=>"0-".$data['maxPrice']];
    	}
    	//处理已选属性
        $at = new AT();
    	$goodsFilter = $at->listQueryByFilter($catId);
		$ngoodsFilter = [];
		// 完整的属性
		$fullAttrs = [];
        if(!empty($vs)){
            // 存在筛选条件,取出符合该条件的商品id,根据商品id获取可选属性进行拼凑
            $goodsId = model('goods')->filterByAttributes();
                // 如果同时有筛选品牌,则与品牌下的商品Id取交集
            if(!empty($bgIds))$goodsId = array_intersect($bgIds,$goodsId);


            $fullAttrs = $attrs = model('Attributes')->getAttribute($goodsId);
            // 去除已选择属性
            foreach ($attrs as $key =>$v){
                if(!in_array($v['attrId'],$vs))$ngoodsFilter[] = $v;
            }
        }else{
			if(!empty($bgIds))$goodsFilter = model('Attributes')->getAttribute($bgIds);// 存在品牌筛选
			$fullAttrs = $goodsFilter;
            // 当前无筛选条件,取出分类下所有属性
        	foreach ($goodsFilter as $key =>$v){
        		if(!in_array($v['attrId'],$vs))$ngoodsFilter[] = $v;
            }
        }
        if(count($vs)>0){
            $_vv = [];
			$_attrArr = [];
			$_arr = array_merge($goodsFilter, $fullAttrs);
            foreach ($_arr as $key =>$v){
                if(in_array($v['attrId'],$vs)){
                    foreach ($v['attrVal'] as $key2 =>$vv){
                        if(strstr(input('v_'.$v['attrId']),'、')!==false){
                            $attrvs = explode('、',input('v_'.$v['attrId']));
                            foreach($attrvs as $av){
                               if($av==$vv){
                                  array_push($_vv, $vv);
                                  $_attrArr[$v['attrId']]['attrName'] = $v['attrName'];
                                  $_attrArr[$v['attrId']]['val'] = $_vv;
                               }
                            }
                        }else{
                            if(input('v_'.$v['attrId'])==$vv){
                                $_attrArr[$v['attrId']]['attrName'] = $v['attrName'];
                                $_attrArr[$v['attrId']]['val'][] = $vv;
                            }
                        }
                    }
                    $_vv = [];
                }
            }
            foreach($_attrArr as $k1=>$v1){
                $selector[] = ['id'=>$k1,'type'=>'v_'.$k1,'label'=>$v1['attrName'],"val"=>implode('、',$v1['val'])];
            }
        }
    	$data['selector'] = $selector;
        $attrs = [];
        foreach ($ngoodsFilter as $k => $val) {
           $result = array_unique($ngoodsFilter[$k]['attrVal']);
           $ngoodsFilter[$k]['attrVal'] = $result;
        }
    	$data['goodsFilter'] = $ngoodsFilter;
    	//获取商品记录
    	$m = new M();
    	$data['priceGrade'] = $m->getPriceGrade($goodsCatIds);

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
			$data['goodsPage'] = $m->pageQuery($goodsCatIds, $fl_data);
		} else {
			$data['goodsPage'] = $m->pageQuery($goodsCatIds);
		}
		$data['fl'] = $catFl;

        $catPaths = model('goodsCats')->getParentNames($catId);

        $data['catNamePath'] = '全部商品分类';
        if(!empty($catPaths))$data['catNamePath'] = implode(' - ',$catPaths);

        $this->assign('tab_type',$tab_type);
    	return $this->fetch($catInfo['catListTheme']?$catInfo['catListTheme']:'goods_list',$data);
    }
    
    /**
     * 查看商品详情
     */
    public function detail(){
    	$m = new M();
    	$goods = $m->getBySale(input('goodsId/d',0));

    	if(!empty($goods)){
    	    $history = cookie("history_goods");
    	    $history = is_array($history)?$history:[];
            array_unshift($history, (string)$goods['goodsId']);
            $history = array_values(array_unique($history));
            
			if(!empty($history)){
				cookie("history_goods",$history,25920000);
			}
            // 分类信息
            $catInfo = Db::name("goods_cats")->field("detailTheme")->where(['catId'=>$goods['goodsCatId'],'dataFlag'=>1])->find();

            // 商品详情延迟加载
            $rule = '/<img src="\/(upload.*?)"/';
            preg_match_all($rule, $goods['goodsDesc'], $images);
            foreach($images[0] as $k=>$v){
                $goods['goodsDesc'] = str_replace($v, "<img class='goodsImg' data-original=\"".WSTConf('CONF.resourcePath').str_replace('/index.php','',request()->root())."/".WSTImg($images[1][$k],3)."\"", $goods['goodsDesc']);
            }
			$goods['haveThumb'] = $m->haveThumb();

	    	$this->assign('goods',$goods);
            $this->assign('shop',$goods['shop']);
	    	return $this->fetch($catInfo['detailTheme']);
    	}else{
    		return $this->fetch("error_lost");
    	}
    }

    
	/**
	 * 获取商品浏览记录
	 */
	public function historyByGoods(){
		$rs = model('Tags')->historyByGoods(8);
		return WSTReturn('',1,$rs);
	}
	/**
	 *  记录对比商品
	 */
	public function contrastGoods(){
		$id = (int)input('post.id');
		$contras = cookie("contras_goods");
		if($id>0){
			$m = new M();
			$goods = $m->getBySale($id);
			$catId = explode('_',$goods['goodsCatIdPath']);
			$catId = $catId[0];
			if(isset($contras['catId']) && $catId!=$contras['catId'])return WSTReturn('请选择同分类商品进行对比',-1);
			if(isset($contras['list']) && count($contras['list'])>3)return WSTReturn('商品对比栏已满',-1);
			if(!isset($contras['catId']))$contras['catId'] = $catId;
			$contras['list'][$id] = $id;
			cookie("contras_goods",$contras,25920000);
		}
		if(isset($contras['list'])){
			$m = new M();
			$list = [];
			foreach($contras['list'] as $k=>$v){
				$list[] = $m->getBySale($v);
			}
			return WSTReturn('',1,$list);
		}else{
			return WSTReturn('',1);
		}
	}
	/**
	 *  删除对比商品
	 */
	public function contrastDel(){
		$id = (int)input('post.id');
		$contras = cookie("contras_goods");
		if($id>0 && isset($contras['list'])){
			unset($contras['list'][$id]);
			cookie("contras_goods",$contras,25920000);
		}else{
			cookie("contras_goods", null);
		}
		return WSTReturn('删除成功',1);
	}
	/**
	 *  商品对比
	 */
	public function contrast(){
		$contras = cookie("contras_goods");
		$list = [];
		$list = $lists= $saleSpec = $shop = $score = $brand = $spec = [];
		if(isset($contras['list'])){
			$m = new M();
			foreach($contras['list'] as $key=>$value){
				$dara = $m->getBySale($value);
				if(isset($dara['saleSpec'])){
					foreach($dara['saleSpec'] as $ks=>$vs){
						if($vs['isDefault']==1){
							$dara['defaultSpec'] = $vs;
							$dara['defaultSpec']['ids'] = explode(':',$ks);
						}
					}
					$saleSpec[$value] = $dara['saleSpec'];
				}
				$list[] = $dara;
			}
			//第一个商品信息
			$goods = $list[0];
			//对比处理
			$shops['identical'] = $scores['identical'] = $brands['identical'] = 1;
			foreach($list as $k=>$v){
				$shop[$v['goodsId']] = $v['shop']['shopName'];
				if($goods['shop']['shopId']!=$v['shop']['shopId'])$shops['identical'] = 0;
				$score[$v['goodsId']] = $v['scores']['totalScores'];
				if($goods['scores']['totalScores']!=$v['scores']['totalScores'])$scores['identical'] = 0;
				$brand[$v['goodsId']] = $v['brandName'];
				if($goods['brandId']!=$v['brandId'])$brands['identical'] = 0;
				if(isset($v['spec'])){
					foreach($v['spec'] as $k2=>$v2){
						$spec[$k2]['identical'] = 0;
						$spec[$k2]['type'] = 'spec';
						$spec[$k2]['name'] = $v2['name'];
						$spec[$k2]['catId'] = $k2;
						foreach($v2['list'] as $ks22=>$vs22){
							$v['spec'][$k2]['list'][$ks22]['isDefault'] = (in_array($vs22['itemId'],$v['defaultSpec']['ids']))?1:0;
						}
						$spec[$k2]['info'][$v['goodsId']] = $v['spec'][$k2];
					}
				}
			}
			$shops['name'] = '店铺';
			$shops['type'] = 'shop';
			$shops['info'] =  $shop;
			$lists[] = $shops;
			$scores['name'] = '商品评分';
			$scores['type'] = 'score';
			$scores['info'] =  $score;
			$lists[] = $scores;
			$brands['name'] = '品牌';
			$brands['type'] = 'brand';
			$brands['info'] =  $brand;
			$lists[] = $brands;
			foreach($spec as $k3=>$v3){
				$lists[] = $v3;
			}
		}
		$data['list'] = $list;
		$data['lists'] = $lists;
		$data['saleSpec'] = $saleSpec;
		$this->assign('data',$data);
		return $this->fetch("goods_contrast");
	}
	
	/**
	 *记录点赞数
	 */
	 public function recordThumb(){
        $m = new M();
        return $m->recordThumb();
	 }
	 
	 /**
	 *确认是否已点赞
	 **/
	 public function haveThumb(){
        $m = new M();
        return $m->haveThumb();
	 }
	 
	 /**
    *   增值出售
    */
    public function priceySale(){
        $m = new M();
        return $m->priceySale();
    }
	/**
    *   保底回购
    */
    public function backSale(){
        $m = new M();
        return $m->backSale();
    }
	/**
    *   保底交易提货
    */
    public function bdDelivery(){
        $m = new M();
        return $m->bdDelivery();
    }
	/**
    *   保底交易发送提货码
    */
    public function bdResendSms(){
        $m = new M();
        return $m->bdResendSms();
    }
	/**
	 * 会员仓库中商品
	 */
    public function mygoods(){
		$bdStatus = input('bs/d');
		$this->assign('bdStatus',$bdStatus);
		return $this->fetch('users/goods/list_store');
	}
	/**
	 * 获取会员仓库中的商品
	 */
    public function myGoodsByPage(){
		$m = new M();
		$rs = $m->myGoodsByPage();
		$rs['status'] = 1;
		return $rs;
	}
	/**
     * 获取专题商品列表
     */
    public function topicLists(){
		$catId = Input('catId/d',0);;
        $where = [];
        $where[] = ['r.dataSrc','=',0];
        $where[] = ['g.isSale','=',1];
        $where[] = ['g.goodsStatus','=',1]; 
        $where[] = ['g.dataFlag','=',1]; 
        $goods=[];
	    $where[] = ['r.dataType','=',1];	//热销商品
		if($catId>0){
			$aId[] = $catId;
			$childIds = model('goodsCats')->getChildIds($aId);
			$where[] = ['r.goodsCatId','in',$childIds];
		}
		$goods = Db::name('goods')->alias('g')->join('__RECOMMENDS__ r','g.goodsId=r.dataId')
				   ->join('__SHOPS__ s','g.shopId=s.shopId')
				   ->where($where)->field('g.goodsTips,s.shopName,s.shopId,g.goodsId,goodsName,goodsImg,goodsSn,goodsStock,saleNum,shopPrice,marketPrice,isSpec,appraiseNum,visitNum,isNew,goodsUnit,thumbsNum,goodsAuthor,saleType')
				   ->order('g.thumbsNum desc,r.dataSort asc')->paginate(15)->toArray();
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

    	//填充参数
    	$data = [];
    	$data['catId'] = $catId;
    	$data['isStock'] = Input('isStock/d');
    	$data['isNew'] = Input('isNew/d');
        $data['isFreeShipping'] = input('isFreeShipping/d');
    	$data['orderBy'] = Input('orderBy/d');
    	$data['order'] = Input('order/d',1);
    	$data['sprice'] = Input('sprice');
    	$data['eprice'] = Input('eprice');
    	$data['attrs'] = [];

		$topicInfo = [];
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
			$catPath = model('goodsCats')->getParentIs($gctId);
			$i = 0;
			foreach($catPath as $cId){
				if($i==0){
					$goodsCats[$gctId]['catName'] = model('goodsCats')->getCatName($cId);	//添加父类
				}else if($i==1){	//判断子类
					$goodsCats[$gctId]['children'][$cId] = model('goodsCats')->getCatName($cId);	//添加子类
				}
				$i++;
			}
        }
    	//$data['goodsPage'] = $goods;
        $catPaths = model('goodsCats')->getParentNames($catId);

        $data['catNamePath'] = '全部商品分类';
        if(!empty($catPaths))$data['catNamePath'] = implode(' - ',$catPaths);
        // 商品分类下级
        //$where = ['parentId'=>0,'dataFlag'=>1];
        //$goodsCats = model('goodsCats')->field('catId,catName')->where($where)->select();
		$topicInfo['goodscats'] = $goodsCats;
		$topicInfo['shop'] = [];
		$topicInfo['shop']['shopId'] = 1;
		$topicInfo['list'] = $goods;
		$ct1 = 0;
		$ct2 = 0;
        if($catId>0){
			$catPath = model('goodsCats')->getParentIs($catId);
			$i = 0;
			foreach($catPath as $c){
				if($i==0)	$ct1 = $c;
				else if($i==1)	$ct2 == $c;
			}
		}
		$this->assign('data',$topicInfo);
        $this->assign('ct1',$ct1);
        $this->assign('ct2',$ct2);
		$this->assign('msort',1);
		$this->assign('mdesc',1);
    	return $this->fetch("goods_topic2",$data);
    }
}
