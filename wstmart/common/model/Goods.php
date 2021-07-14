<?php
namespace wstmart\common\model;
use think\Db;
use wstmart\common\validate\Goods as Validate;
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
class Goods extends Base{
	protected $pk = 'goodsId';
	/**
	 * 获取店铺商品列表
	 */
	public function shopGoods($shopId){
		$msort = (int)input("param.msort");
		$mdesc = (int)input("param.mdesc");
		$order = array('g.saleTime'=>'desc');
		$orderFile = array('0'=>'(gs.totalScore/gs.totalUsers),g.saleNum','1'=>'g.isHot','2'=>'g.saleNum','3'=>'g.shopPrice','4'=>'g.shopPrice','5'=>'g.thumbsNum','6'=>'g.saleTime');
		$orderSort = array('0'=>'asc','1'=>'desc');
		if($msort>0){
 			$order = $orderFile[$msort]." ".$orderSort[$mdesc];
		}else{
			$msort=5;	// 默认按点赞数排序
			$order = $orderFile[$msort]." ".$orderSort[1];
		}
		
		$goodsName = input("param.goodsName");//搜索店鋪名
		$words = $where = $where2 = $where3 = $where4 = [];
		if($goodsName!=""){
			$words = explode(" ",$goodsName);
		}
		if(!empty($words)){
			$sarr = array();
			foreach ($words as $key => $word) {
				if($word!=""){
					$sarr[] = "g.goodsName like '%$word%'";
				}
			}
			$where4 = implode(" or ", $sarr);
		}

		$sprice = input("param.sprice");//开始价格
		$eprice = input("param.eprice");//结束价格
		if($sprice!="")$where2 = "g.shopPrice >= ".(float)$sprice;
		if($eprice!="")$where3 = "g.shopPrice <= ".(float)$eprice;
		$ct1 = input("param.ct1/d");
		$ct2 = input("param.ct2/d");
		if($ct1>0)$where['shopCatId1'] = $ct1;
		if($ct2>0)$where['shopCatId2'] = $ct2;
		//销售类型
        $saleType = input('saleType');
        if($saleType!='' and $saleType != -1){
            $where['g.saleType'] =  $saleType;
        }
		$goods = Db::name('goods')->alias('g')
		->join('__GOODS_SCORES__ gs','gs.goodsId = g.goodsId','left')
		->where(['g.shopId'=>$shopId,'g.isSale'=>1,'g.goodsStatus'=>1,'g.dataFlag'=>1])
		->where($where)->where($where2)->where($where3)->where($where4)
		->field('g.goodsAuthor,g.goodsTips,g.goodsId,g.goodsName,g.goodsImg,g.shopPrice,g.marketPrice,g.saleNum,g.appraiseNum,g.goodsStock,g.isFreeShipping,gallery,g.saleType,g.thumbsNum')
		->orderRaw($order)
		->paginate((input('pagesize/d')>0)?input('pagesize/d'):16)->toArray();
		$goodsNum = Db::name('goods')->alias('g')
		->where(['g.shopId'=>$shopId,'g.isSale'=>1,'g.goodsStatus'=>1,'g.dataFlag'=>1])
		->where($where)->where($where2)->where($where3)->where($where4)
		->count();
		$goods['goodsNum'] = $goodsNum;
		return  $goods;
	}

	/**
	 * 新增商品
	 */
	public function add($sId=0){
		$shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
		$userId = (int)session('WST_USER.userId');
		$data = input('post.');
		//为api接口设置默认值
		$data['isSale'] = empty($data['isSale'])?0:$data['isSale'];
		$data['isFreeShipping'] = empty($data['isFreeShipping'])?0:$data['isFreeShipping'];
		$data['shopExpressId'] = empty($data['shopExpressId'])?9:$data['shopExpressId'];
		$data['goodsType'] = empty($data['goodsType'])?0:$data['goodsType'];
		$data['goodsDesc'] = empty($data['goodsDesc'])?' ':$data['goodsDesc'];
        $data['goodsAuthorDesc'] = empty($data['goodsAuthorDesc'])?' ':$data['goodsAuthorDesc'];
        $data['szyx'] = empty($data['goodsDesc'])?' ':$data['szyx'];
		$data['gallery'] = empty($data['gallery'])?'':$data['gallery'];
		if(input('shopId/d')>0){	// input shopId大于0表示是通过api接口传进来的数据，这时要进行decode
			$d = base64_decode($data['goodsDesc'],true);
			if($d)
				$data['goodsDesc'] = htmlspecialchars($d);
		}

		$isApp = (int)input('post.isApp',0);
		$specsIds = input('post.specsIds');
		WSTUnset($data,'goodsId,statusRemarks,goodsStatus,dataFlag');
        if(WSTConf("CONF.isGoodsVerify")==1){
            $data['goodsStatus'] = 0;
        }else{
            $data['goodsStatus'] = 1;
        }
		if(isset($data['goodsName'])){
			if(!WSTCheckFilterWords($data['goodsName'],WSTConf("CONF.limitWords"))){
				return WSTReturn("商品名称包含非法字符");
			}
            if(!WSTCheckFilterWords($data['goodsName'],WSTConf("CONF.sensitiveWords"))){
                $data['goodsStatus'] = 0;
            }
		}
		if(isset($data['goodsTips'])){
			if(!WSTCheckFilterWords($data['goodsTips'],WSTConf("CONF.limitWords"))){
				return WSTReturn("商品促销信息包含非法字符");
			}
            if(!WSTCheckFilterWords($data['goodsTips'],WSTConf("CONF.sensitiveWords"))){
                $data['goodsStatus'] = 0;
            }
		}
		if($data['isSale']==1 && $data['goodsImg']==''){
			return WSTReturn("上架商品必须有商品图片");
		}
		if(isset($data['goodsDesc'])){
            if(!WSTCheckFilterWords($data['goodsDesc'],WSTConf("CONF.limitWords"))){
                return WSTReturn("商品描述包含非法字符");
            }
            if(!WSTCheckFilterWords($data['goodsDesc'],WSTConf("CONF.sensitiveWords"))){
                $data['goodsStatus'] = 0;
            }
        }
        if(isset($data['goodsSeoKeywords'])){
            if(!WSTCheckFilterWords($data['goodsSeoKeywords'],WSTConf("CONF.sensitiveWords"))){
                $data['goodsStatus'] = 0;
            }
        }
        if(isset($data['goodsSeoDesc'])){
            if(!WSTCheckFilterWords($data['goodsSeoDesc'],WSTConf("CONF.sensitiveWords"))){
                $data['goodsStatus'] = 0;
            }
        }
        if((int)$data['goodsType']==0 &&  (int)$data['isFreeShipping']==0 && (int)$data['shopExpressId']==0){
        	return WSTReturn("请选择快递公司");
        }
		$data['shopId'] = $shopId;
		$data['ownerId'] = $userId;
		$data['saleTime'] = date('Y-m-d H:i:s');
		$data['createTime'] = date('Y-m-d H:i:s');
		$goodmodel = model('common/GoodsCats');
		$goodsCats = $goodmodel->getParentIs($data['goodsCatId']);
		//校验商品分类有效性
		$applyCatIds = $goodmodel->getShopApplyGoodsCats($shopId);
		$isApplyCatIds = array_intersect($applyCatIds,$goodsCats);
		if(empty($isApplyCatIds))return WSTReturn("请选择完整商城分类");		
		$data['goodsCatIdPath'] = implode('_',$goodsCats)."_";

		if($data['goodsType']==0){
			$data['isSpec'] = ($specsIds!='')?1:0;
		}else{
			$data['isSpec'] = 0;
		}
		Db::startTrans();
        try{
        	//对图片域名进行处理
			$resourceDomain = ($isApp==1)?WSTConf('CONF.resourceDomain'):WSTConf('CONF.resourcePath');
			$data['goodsDesc'] = htmlspecialchars_decode($data['goodsDesc']);
            $data['goodsDesc'] = WSTRichEditorFilter($data['goodsDesc']);
            $data['goodsDesc'] = str_replace($resourceDomain.'/upload/','${DOMAIN}/upload/',$data['goodsDesc']);
            //作者简介
            $data['goodsDesc'] = htmlspecialchars_decode($data['goodsAuthorDesc']);
            $data['goodsDesc'] = WSTRichEditorFilter($data['goodsAuthorDesc']);
            $data['goodsDesc'] = str_replace($resourceDomain.'/upload/','${DOMAIN}/upload/',$data['goodsAuthorDesc']);
            //作者年表
            $data['szyx'] = htmlspecialchars_decode($data['szyx']);
            $data['szyx'] = WSTRichEditorFilter($data['szyx']);
            $data['szyx'] = str_replace($resourceDomain.'/upload/','${DOMAIN}/upload/',$data['szyx']);

        	//保存插件数据钩子
        	hook('beforeEidtGoods',['data'=>&$data]);
        	$shop = model('shops')->get(['shopId'=>$shopId]);
        	if($shop['dataFlag'] ==-1 || $shop['shopStatus'] != 1)$data['isSale'] = 0;
			//userId为0时表示通过api接口增加，这时给ownerId赋值
			if($userId == 0)
				$data['ownerId'] = $shop['userId'];
        	$validate = new Validate;
        	if (!$validate->scene(true)->check($data)) {
        		return WSTReturn($validate->getError());
        	}else{
        		$result = $this->allowField(true)->save($data);
        	}
			if(false !== $result){
				$goodsId = $this->goodsId;
				//商品图片
				WSTUseResource(0, $goodsId, $data['goodsImg']);
				//商品相册
				WSTUseResource(0, $goodsId, $data['gallery']);
				//商品描述图片
				WSTEditorImageRocord(0, $goodsId, '',$data['goodsDesc']);
				// 视频
				if(isset($data['goodsVideo']) && $data['goodsVideo']!=''){
					WSTUseResource(0, $goodsId, $data['goodsVideo']);
				}
				//建立商品评分记录
				$gs = [];
				$gs['goodsId'] = $goodsId;
				$gs['shopId'] = $shopId;
				Db::name('goods_scores')->insert($gs);
				//如果是实物商品并且有销售规格则保存销售和规格值
    	        if($data['goodsType']==0 && $specsIds!=''){
	    	        $specsIds = explode(',',$specsIds);
			    	$specsArray = [];
			    	foreach ($specsIds as $v){
			    		$vs = explode('-',$v);
			    		foreach ($vs as $vv){
			    		   if(!in_array($vv,$specsArray))$specsArray[] = $vv;
			    		}
			    	}
		    		//保存规格名称
		    		$specMap = [];
		    		foreach ($specsArray as $v){
		    			$vv = explode('_',$v);
		    			$sitem = [];
		    			$sitem['shopId'] = $shopId;
		    			$sitem['catId'] = (int)$vv[0];
		    			$sitem['goodsId'] = $goodsId;
		    			$sitem['itemName'] = input('post.specName_'.$vv[0]."_".$vv[1]);
		    			$sitem['itemImg'] = input('post.specImg_'.$vv[0]."_".$vv[1],'');
		    			$sitem['dataFlag'] = 1;
		    			$sitem['createTime'] = date('Y-m-d H:i:s');
		    			$itemId = Db::name('spec_items')->insertGetId($sitem);
		    			if($sitem['itemImg']!='')WSTUseResource(0, $itemId, $sitem['itemImg']);
		    			$specMap[$v] = $itemId;
		    		}
		    		//保存销售规格
		    		$defaultPrice = 0;//最低价
		    		$totalStock = 0;//总库存
		    		$gspecArray = [];
		    		$isFindDefaultSpec = false;
		    		$defaultSpec = Input('post.defaultSpec');
		    		foreach ($specsIds as $v){
		    			$vs = explode('-',$v);
		    			$goodsSpecIds = [];
		    			foreach ($vs as $gvs){
		    				$goodsSpecIds[] = $specMap[$gvs];
		    			}
		    			$gspec = [];
		    			$gspec['specIds'] = implode(':',$goodsSpecIds);
		    			$gspec['shopId'] = $shopId;
		    			$gspec['goodsId'] = $goodsId;
		    			$gspec['productNo'] = Input('productNo_'.$v);
		    			$gspec['marketPrice'] = (float)Input('marketPrice_'.$v);
		    			$gspec['specPrice'] = (float)Input('specPrice_'.$v);
		    			$gspec['specStock'] = (int)Input('specStock_'.$v);
		    			$gspec['warnStock'] = (int)Input('warnStock_'.$v);
		    			$gspec['specWeight'] = (float)Input('specWeight_'.$v);
		    			$gspec['specVolume'] = (float)Input('specVolume_'.$v);
		    			//设置默认规格
		    			if($defaultSpec==$v){
		    				$isFindDefaultSpec = true;
		    				$defaultPrice = $gspec['specPrice'];
		    				$gspec['isDefault'] = 1;
		    			}else{
		    				$gspec['isDefault'] = 0;
		    			}
                        $gspecArray[] = $gspec;
                        //获取总库存
                        $totalStock = $totalStock + $gspec['specStock'];
		    		}
		    		if(!$isFindDefaultSpec)return WSTReturn("请选择推荐规格");
		    		if(count($gspecArray)>0){
		    		    Db::name('goods_specs')->insertAll($gspecArray);
		    		    //更新默认价格和总库存
    	                $this->where('goodsId',$goodsId)->update(['isSpec'=>1,'shopPrice'=>$defaultPrice,'goodsStock'=>$totalStock]);
		    		}
    	        }
    	        //保存商品属性
		    	$attrsArray = [];//通过id
		    	$attrsArrayName = [];//通过属性名称
		    	$attrRs = Db::name('attributes')->where([['goodsCatId','in',$goodsCats],['isShow','=',1],['dataFlag','=',1]])
		    		            ->field('attrId,attrName')->select();
		    	if( !empty( $attrRs ) ){
                    foreach ($attrRs as $key =>$v){
                        $attrs = [];
                        $attrs['attrVal'] = input('attr_'.$v['attrId']);
                        if($attrs['attrVal']=='')continue;
                        $attrs['shopId'] = $shopId;
                        $attrs['goodsId'] = $goodsId;
                        $attrs['attrId'] = $v['attrId'];
                        $attrs['createTime'] = date('Y-m-d H:i:s');
                        $attrsArray[] = $attrs;
                    }
                    if(count($attrsArray)>0){
                        Db::name('goods_attributes')->insertAll($attrsArray);
                    }

                  /*  //通过名称匹配
                    foreach ($attrRs as $key =>$v){
                        $attrs = [];
                        switch( $v['attrName'] ){
                            case "材质":
                                $attrs['attrVal'] = $data['goodsCz'];
                                break;
                            case "题材":
                                $attrs['attrVal'] = $data['goodsTc'];
                                break;
                            case "作者年表":
                                $attrs['attrVal'] = $data['zznb'];
                                break;
                            case "所有院系":
                                $attrs['attrVal'] = $data['szyx'];
                                break;
                            case "指导老师":
                                $attrs['attrVal'] = $data['zdls'];
                                break;
                            default:
                                $attrs['attrVal'] = "";
                                break;
                        }

                        if($attrs['attrVal']=='')continue;
                        $attrs['shopId'] = $shopId;
                        $attrs['goodsId'] = $goodsId;
                        $attrs['attrId'] = $v['attrId'];
                        $attrs['createTime'] = date('Y-m-d H:i:s');
                        $attrsArrayName[] = $attrs;
                    }
                    if(count($attrsArrayName)>0){
                        Db::name('goods_attributes')->insertAll($attrsArrayName);
                    }*/

                }
                //保存作者信息
                $author_data['goods_id'] = $goodsId;
                $author_data['goodsAuthor'] = input('goodsAuthor');
                $author_data['goodsAuthorDesc'] = input('goodsAuthorDesc');
                $author_data['goodsAuthorImg'] = input('goodsAuthorImg');
                $author_data['szyx'] = input('szyx');
                $author_data['zznb'] = input('zznb');
                $author_data['zdls'] = input('zdls');
                $author = Author::get(['goods_id' => $goodsId]);
                if($author){
                    $result = $author->edit($author_data);
                }else{
                    $author = new Author();
                    $result = $author->add($author_data);
                }
                if($result['status'] == -1){
                    return $result;
                }

		    	//保存关键字
        	    $searchKeys = WSTGroupGoodsSearchKey($goodsId);
        	    $this->where('goodsId',$goodsId)->update(['goodsSerachKeywords'=>implode(',',$searchKeys)]);
    	        hook('afterEditGoods',['goodsId'=>$goodsId]);

                model("common/logRecord")->add(array('staffId'=> session('WST_USER.userId'),'operateDesc'=>'添加商品','recordId'=>$goodsId,'type'=>1,'label'=>$data['goodsName']));//记录

    	        Db::commit();
				return WSTReturn("新增成功", 1,['id'=>$goodsId]);
			}else{
				return WSTReturn($this->getError(),-1);
			}
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('新增失败'.$e->getMessage().$this->getLastSql(),-1);
        }
	}
	
	/**
	 * 编辑商品资料
	 */
	public function edit($sId=0){
		$shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
        $isApp = (int)input('post.isApp',0);
	    $goodsId = input('post.goodsId/d');
	    $specsIds = input('post.specsIds');
		$data = input('post.');
		WSTUnset($data,'goodsId,dataFlag,statusRemarks,goodsStatus,createTime');
		$ogoods = $this->where(['goodsId'=>$goodsId,'shopId'=>$shopId,'dataFlag'=>1])->field('goodsImg,goodsStatus,goodsType')->find();
		if(empty($ogoods))return WSTReturn('商品不存在');
        if(WSTConf("CONF.isGoodsVerify")==1){
            $data['goodsStatus'] = 0;
        }else{
            $data['goodsStatus'] = 1;
        }
		$data['checkStatus'] = 1;
		if(isset($data['goodsName'])){
			if(!WSTCheckFilterWords($data['goodsName'],WSTConf("CONF.limitWords"))){
				return WSTReturn("商品名称包含非法字符");
			}
            if(!WSTCheckFilterWords($data['goodsName'],WSTConf("CONF.sensitiveWords"))){
                $data['goodsStatus'] = 0;
            }
		}
		if($ogoods['goodsImg']=='' && $data['isSale']==1 && $data['goodsImg']==''){
			return WSTReturn("上架商品必须有商品图片");
		}
		if(isset($data['goodsTips'])){
			if(!WSTCheckFilterWords($data['goodsTips'],WSTConf("CONF.limitWords"))){
				return WSTReturn("商品促销信息包含非法字符");
			}
            if(!WSTCheckFilterWords($data['goodsTips'],WSTConf("CONF.sensitiveWords"))){
                $data['goodsStatus'] = 0;
            }
		}
		if(isset($data['goodsDesc'])){
			if(!WSTCheckFilterWords($data['goodsDesc'],WSTConf("CONF.limitWords"))){
				return WSTReturn("商品描述包含非法字符");
			}
            if(!WSTCheckFilterWords($data['goodsDesc'],WSTConf("CONF.sensitiveWords"))){
                $data['goodsStatus'] = 0;
            }
		}
        if(isset($data['goodsSeoKeywords'])){
            if(!WSTCheckFilterWords($data['goodsSeoKeywords'],WSTConf("CONF.sensitiveWords"))){
                $data['goodsStatus'] = 0;
            }
        }
        if(isset($data['goodsSeoDesc'])){
            if(!WSTCheckFilterWords($data['goodsSeoDesc'],WSTConf("CONF.sensitiveWords"))){
                $data['goodsStatus'] = 0;
            }
        }
//        if((int)$ogoods['goodsType']==0 && (int)$data['isFreeShipping']==0 && (int)$data['shopExpressId']==0){
//        	return WSTReturn("请选择快递公司");
//        }

		//不允许修改商品类型
		$data['goodsType'] = $ogoods['goodsType'];
		$data['saleTime'] = date('Y-m-d H:i:s');
		$goodmodel = model('GoodsCats');
		$goodsCats = $goodmodel->getParentIs($data['goodsCatId']);
		//校验商品分类有效性
		$applyCatIds = $goodmodel->getShopApplyGoodsCats($shopId);
		$isApplyCatIds = array_intersect($applyCatIds,$goodsCats);
		if(empty($isApplyCatIds))return WSTReturn("请选择完整商城分类");	
		$data['goodsCatIdPath'] = implode('_',$goodsCats)."_";
		if($data['goodsType']==0){
		    $data['isSpec'] = ($specsIds!='')?1:0;
	    }else{
	    	$data['isSpec'] = 0;
	    }
		//检查保底交易商品状态
		if($data['isSale'] == 1){
			$bd = $this->checkBDGoods($goodsId,$data['shopPrice']);
			if($bd !== 0){
				return WSTReturn($bd);
			}
			$data['bdStatus'] = 1;
		}
		Db::startTrans();
        try{
            //对图片域名进行处理
			$resourceDomain = ($isApp==1)?WSTConf('CONF.resourceDomain'):WSTConf('CONF.resourcePath');
			$data['goodsDesc'] = htmlspecialchars_decode($data['goodsDesc']);
            $data['goodsDesc'] = WSTRichEditorFilter($data['goodsDesc']);
            $data['goodsDesc'] = str_replace($resourceDomain.'/upload/','${DOMAIN}/upload/',$data['goodsDesc']);
            
        	//保存插件数据钩子
        	hook('beforeEidtGoods',['data'=>&$data]);

        	//商品图片
			WSTUseResource(0, $goodsId, $data['goodsImg'],'goods','goodsImg');
			//商品相册
			WSTUseResource(0, $goodsId, $data['gallery'],'goods','gallery');
			// 商品描述图片
	        $desc = $this->where('goodsId',$goodsId)->value('goodsDesc');
			WSTEditorImageRocord(0, $goodsId, $desc, $data['goodsDesc']);
			// 视频
			if(isset($data['goodsVideo']) && $data['goodsVideo']!=''){
				WSTUseResource(0, $goodsId, $data['goodsVideo'], 'goods', 'goodsVideo');
			}

            $shop = model('shops')->get(['shopId'=>$shopId]);
        	if($shop['dataFlag'] ==-1 || $shop['shopStatus'] != 1)$data['isSale'] = 0;
        	//虚拟商品处理
        	if($data['goodsType']==1){
				$counts = Db::name('goods_virtuals')->where(['dataFlag'=>1,'isUse'=>0,'goodsId'=>$goodsId])->Count();
				$data['goodsStock'] = $counts;
			}
			$validate = new Validate;
			if (!$validate->scene(true)->check($data)) {
				return WSTReturn($validate->getError());
			}else{
				$result = $this->allowField(true)->save($data,['goodsId'=>$goodsId]);
			}
			if(false !== $result){
				/**
				 * 编辑的时候如果不想影响商品销售规格的销量，那么就要在保存的时候区别对待已经存在的规格和销售规格记录。
				 * $specNameMap的保存关系是：array('页面上生成的规格值ID'=>数据库里规则值的ID)
				 * $specIdMap的保存关系是:array('页面上生成的销售规格ID'=>数据库里销售规格ID)
				 */
				$specNameMapTmp = explode(',',input('post.specmap'));
				$specIdMapTmp = explode(',',input('post.specidsmap'));
				$specNameMap = [];//规格值对应关系
				$specIdMap = [];//规格和表对应关系
				foreach ($specNameMapTmp as $key =>$v){
					if($v=='')continue;
					$v = explode(':',$v);
					$specNameMap[$v[1]] = $v[0];   //array('页面上的规则值ID'=>数据库里规则值的ID)
				}
				foreach ($specIdMapTmp as $key =>$v){
					if($v=='')continue;
					$v = explode(':',$v);
					$specIdMap[$v[1]] = $v[0];     //array('页面上的销售规则ID'=>数据库里销售规格ID)
				}
				//如果是实物商品并且有销售规格则保存销售和规格值
    	        if($data['goodsType'] ==0 && $specsIds!=''){
    	        	//把之前之前的销售规格
	    	        $specsIds = explode(',',$specsIds);
			    	$specsArray = [];
			    	foreach ($specsIds as $v){
			    		$vs = explode('-',$v);
			    		foreach ($vs as $vv){
			    		   if(!in_array($vv,$specsArray))$specsArray[] = $vv;//过滤出不重复的规格值
			    		}
			    	}
			    	//先标记作废之前的规格值
			    	Db::name('spec_items')->where(['shopId'=>$shopId,'goodsId'=>$goodsId])->update(['dataFlag'=>-1]);
		    		//保存规格名称
		    		$specMap = [];
		    		foreach ($specsArray as $v){
		    			$vv = explode('_',$v);
		    			$specNumId = $vv[0]."_".$vv[1];
		    			$sitem = [];
		    			$sitem['itemName'] = input('post.specName_'.$specNumId);
		    			$sitem['itemImg'] = input('post.specImg_'.$specNumId,'');
		    			//如果已经存在的规格值则修改，否则新增
		    			if(isset($specNameMap[$specNumId]) && (int)$specNameMap[$specNumId]!=0){
		    				$sitem['dataFlag'] = 1;
		    				WSTUseResource(0, (int)$specNameMap[$specNumId], $sitem['itemImg'],'spec_items','itemImg');
		    				Db::name('spec_items')->where(['shopId'=>$shopId,'itemId'=>(int)$specNameMap[$specNumId]])->update($sitem);
		    				$specMap[$v] = (int)$specNameMap[$specNumId];
		    			}else{
		    				$sitem['goodsId'] = $goodsId;
		    				$sitem['shopId'] = $shopId;
		    			    $sitem['catId'] = (int)$vv[0];
		    				$sitem['dataFlag'] = 1;
		    			    $sitem['createTime'] = date('Y-m-d H:i:s');
		    			    $itemId = Db::name('spec_items')->insertGetId($sitem);
		    			    if($sitem['itemImg']!='')WSTUseResource(0, $itemId, $sitem['itemImg']);
		    			    $specMap[$v] = $itemId;
		    			}
		    		}
		    		//删除已经作废的规格值
		    		Db::name('spec_items')->where(['shopId'=>$shopId,'goodsId'=>$goodsId,'dataFlag'=>-1])->delete();
		    		//保存销售规格
		    		$defaultPrice = 0;//默认价格
		    		$totalStock = 0;//总库存
		    		$gspecArray = [];
		    		//把之前的销售规格值标记删除
		    		Db::name('goods_specs')->where(['goodsId'=>$goodsId,'shopId'=>$shopId])->update(['dataFlag'=>-1,'isDefault'=>0]);
		    		$isFindDefaultSpec = false;
		    		$defaultSpec = Input('post.defaultSpec');
		    		foreach ($specsIds as $v){
		    			$vs = explode('-',$v);
		    			$goodsSpecIds = [];
		    			foreach ($vs as $gvs){
		    				$goodsSpecIds[] = $specMap[$gvs];
		    			}
		    			$gspec = [];
		    			$gspec['specIds'] = implode(':',$goodsSpecIds);
		    			$gspec['productNo'] = Input('productNo_'.$v);
			    		$gspec['marketPrice'] = (float)Input('marketPrice_'.$v);
			    		$gspec['specPrice'] = (float)Input('specPrice_'.$v);
			    		$gspec['specStock'] = (int)Input('specStock_'.$v);
			    		$gspec['warnStock'] = (int)Input('warnStock_'.$v);
			    		$gspec['specWeight'] = (float)Input('specWeight_'.$v);
		    			$gspec['specVolume'] = (float)Input('specVolume_'.$v);
			    		//设置默认规格
			    		if($defaultSpec==$v){
			    			$gspec['isDefault'] = 1;
			    			$isFindDefaultSpec = true;
		    				$defaultPrice = $gspec['specPrice'];
			    		}else{
			    			$gspec['isDefault'] = 0;
			    		}
			    		//如果是已经存在的值就修改内容，否则新增
		    			if(isset($specIdMap[$v]) && $specIdMap[$v]!=''){
		    				$gspec['dataFlag'] = 1;
		    				Db::name('goods_specs')->where(['shopId'=>$shopId,'id'=>(int)$specIdMap[$v]])->update($gspec);
		    			}else{
			    			$gspec['shopId'] = $shopId;
			    			$gspec['goodsId'] = $goodsId;
			    			$gspecArray[] = $gspec;
		    			}
                        //获取总库存
                        $totalStock = $totalStock + $gspec['specStock'];
		    		}
		    		if(!$isFindDefaultSpec)return WSTReturn("请选择推荐规格");
		    		//删除作废的销售规格值
		    		Db::name('goods_specs')->where(['goodsId'=>$goodsId,'shopId'=>$shopId,'dataFlag'=>-1])->delete();
		    		if(count($gspecArray)>0){
		    		    Db::name('goods_specs')->insertAll($gspecArray);
		    		}
		    		//更新推荐规格和总库存
    	            $this->where('goodsId',$goodsId)->update(['isSpec'=>1,'shopPrice'=>$defaultPrice,'goodsStock'=>$totalStock]);
    	        }else if($specsIds==''){
    	        	Db::name('spec_items')->where(['goodsId'=>$goodsId,'shopId'=>$shopId])->delete();
    	        	Db::name('goods_specs')->where(['goodsId'=>$goodsId,'shopId'=>$shopId])->delete();
    	        }
    	        //保存商品属性
    	        //删除之前的商品属性
    	        Db::name('goods_attributes')->where(['goodsId'=>$goodsId,'shopId'=>$shopId])->delete();
    	        //新增商品属性
		    	$attrsArray = [];
		    	$attrRs = Db::name('attributes')->where([['goodsCatId','in',$goodsCats],['isShow','=',1],['dataFlag','=',1]])
		    		            ->field('attrId,attrName')->select();
		    	foreach ($attrRs as $key =>$v){
		    		$attrs = [];
		    		$attrs['attrVal'] = input('attr_'.$v['attrId']);
		    		if($attrs['attrVal']=='')continue;
		    		$attrs['shopId'] = $shopId;
		    		$attrs['goodsId'] = $goodsId;
		    		$attrs['attrId'] = $v['attrId'];
		    		$attrs['createTime'] = date('Y-m-d H:i:s');
		    		$attrsArray[] = $attrs;
		    	}
		    	if(count($attrsArray)>0)Db::name('goods_attributes')->insertAll($attrsArray);

                //通过名称匹配
                /*$attrsArrayName = [];
                foreach ($attrRs as $key =>$v){
                    $attrs = [];
                    switch( $v['attrName'] ){
                        case "材质":
                            $attrs['attrVal'] = $data['goodsCz'];
                            break;
                        case "题材":
                            $attrs['attrVal'] = $data['goodsTc'];
                            break;
                        case "作者年表":
                            $attrs['attrVal'] = $data['zznb'];
                            break;
                        case "所有院系":
                            $attrs['attrVal'] = $data['szyx'];
                            break;
                        case "指导老师":
                            $attrs['attrVal'] = $data['zdls'];
                            break;
                        default:
                            $attrs['attrVal'] = "";
                            break;
                    }

                    if($attrs['attrVal']=='')continue;
                    $attrs['shopId'] = $shopId;
                    $attrs['goodsId'] = $goodsId;
                    $attrs['attrId'] = $v['attrId'];
                    $attrs['createTime'] = date('Y-m-d H:i:s');
                    $attrsArrayName[] = $attrs;
                }
                if(count($attrsArrayName)>0){
                    Db::name('goods_attributes')->insertAll($attrsArrayName);
                }*/
                //保存作者信息
                $author_data['goods_id'] = $goodsId;
                $author_data['goodsAuthor'] = input('goodsAuthor');
                $author_data['goodsAuthorDesc'] = input('goodsAuthorDesc');
                $author_data['goodsAuthorImg'] = input('goodsAuthorImg');
                $author_data['szyx'] = input('szyx');
                $author_data['zznb'] = input('zznb');
                $author_data['zdls'] = input('zdls');
                $author = Author::get(['goods_id' => $goodsId]);
                if($author){
                    $result = $author->edit($author_data);
                }else{
                    $author = new Author();
                    $result = $author->add($author_data);
                }
                if($result['status'] == -1){
                    return $result;
                }
                //保存关键字
        	    $searchKeys = WSTGroupGoodsSearchKey($goodsId);
        	    $this->where('goodsId',$goodsId)->update(['goodsSerachKeywords'=>implode(',',$searchKeys)]);
		    	//删除购物车里的商品
		    	model('common/carts')->delCartByUpdate($goodsId);
		    	//商品编辑之后执行
		    	hook('afterEditGoods',['goodsId'=>$goodsId]);
			    hook('afterChangeGoodsStatus',['goodsId'=>$goodsId]);
				Db::commit();
				return WSTReturn("编辑成功", 1,['id'=>$goodsId]);
			}else{
				return WSTReturn($this->getError(),-1);
			}
	    }catch (\Exception $e) {
        	Db::rollback();
            return WSTReturn('编辑失败',-1);
        }
	}
	
	/**
	 * 获取商品资料方便编辑
	 */
	public function getById($goodsId,$sId=0){
		$shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
        $isApp = (int)input('post.isApp',0);
		$rs = $this->where(['shopId'=>$shopId,'goodsId'=>$goodsId])->find();
		if(!empty($rs)){
			if($rs['gallery']!='')$rs['gallery'] = explode(',',$rs['gallery']);
			$rs['goodsDesc'] = htmlspecialchars_decode($rs['goodsDesc']);
            $resourceDomain = ($isApp==1)?WSTConf('CONF.resourceDomain'):WSTConf('CONF.resourcePath');
            $rs['goodsDesc'] = str_replace('${DOMAIN}',$resourceDomain,$rs['goodsDesc']);
			//获取规格值
			$specs = Db::name('spec_cats')->alias('gc')->join('__SPEC_ITEMS__ sit','gc.catId=sit.catId','inner')
			                      ->where(['sit.goodsId'=>$goodsId,'gc.isShow'=>1,'sit.dataFlag'=>1])
			                      ->field('gc.isAllowImg,sit.catId,sit.itemId,sit.itemName,sit.itemImg')
			                      ->order('gc.isAllowImg desc,gc.catSort asc,gc.catId asc')->select();
			$spec0 = [];
			$spec1 = [];                    
			foreach ($specs as $key =>$v){
				if($v['isAllowImg']==1){
					$spec0[] = $v;
				}else{
					$spec1[] = $v;
				}
			}
			$rs['spec0'] = $spec0;
			$rs['spec1'] = $spec1;
			//获取销售规格
			$rs['saleSpec'] = Db::name('goods_specs')->where('goodsId',$goodsId)->field('id,isDefault,productNo,specIds,marketPrice,specPrice,specStock,warnStock,saleNum,specWeight,specVolume')->select();
			//获取属性值
			$rs['attrs'] = Db::name('goods_attributes')->alias('ga')->join('attributes a','ga.attrId=a.attrId','inner')
			                 ->where('goodsId',$goodsId)->field('ga.attrId,a.attrType,ga.attrVal')->select();
		}
        //获取作者信息
        $author = new Author();
        $author_data = $author->get_format_data($goodsId);
        $rs['author'] = $author_data;

		return $rs;
	}
	/**
	 * 检测商品主表的货号或者商品编号
	 */
	public function checkExistGoodsKey($key,$val,$id = 0,$sId = 0){
		$shopId = $sId>0?$sId:(int)session('WST_USER.shopId');
		if(!in_array($key,array('goodsSn','productNo')))return WSTReturn("非法的查询字段");
		$conditon[] = [$key,'=',$val];
		if($id>0)$conditon[] = ['goodsId','<>',$id];
		$conditon[] = ['shopId','in',$shopId];
		$conditon[] = ['dataFlag','=',1];
		$rs = $dbo = $this->where($conditon)->count();
		return ($rs==0)?false:true;
	}

	/**
	 *记录点赞数
	 **/
	 public function recordThumb(){
		$goodsId = (int)input('goodsId');
		$userId = (int)session('WST_USER.userId');
		$date = date('Y-m-d',time());
		//查询当日点赞次数
		$rs = Db::name('thumbs')->where("goodsId",$goodsId)->where("userId",$userId)->where("thumbDate",$date)->count();
		if($rs<config('thumbNum')) {	// 点赞次数未达到
			$this->where("goodsId=$goodsId")->setInc('thumbsNum');	// 给商品点赞数加1
			$goods = Db::name('goods')->field('goodsId,goodsName')->where("goodsId=$goodsId")->find();
			$user = Db::name('users')->field('userId,loginName')->where("userId=$userId")->find();
			if(empty($goods)||empty($user))
				return 'error';
			//保存点赞记录
			$data['userId'] = $userId;
			$data['loginName'] = $user['loginName'];
			$data['goodsId'] = $goodsId;
			$data['goodsName'] = $goods['goodsName'];
			$data['thumbDate'] = $date;
			if(false === Db::name('thumbs')->insert($data) )
				return 'error';
			else return 'success';
		} else {	// 已点赞
			return 'fail';
		}
	 }
	 
	 /**
	 *确认是否已点赞
	 **/
	 public function haveThumb(){
		if(0!=(int)input('id'))
			$goodsId = (int)input('id');
		else if(0!=(int)input('goodsId'))
			$goodsId = (int)input('goodsId');
		$userId = (int)session('WST_USER.userId');
		$date = date('Y-m-d',time());
		//查询当日是否已点过赞
		$rs = Db::name('thumbs')->where("goodsId",$goodsId)->where("userId",$userId)->where("thumbDate",$date)->find();
		if(empty($rs)) {	// 未点赞
			return false;
		} else {	// 已点赞
			return true;
		}
	 }

	//检查保底交易商品是否符合上架要求
	//price大于0时比较price的值，price等于0时比较数据库的值
	public function checkBDGoods_bak($ids,$price){
		//检查是否有保底交易商品
		$where[] = ['goodsId','in',$ids];
		$where[] = ['goodsType','=',2];
		//发生过转手的商品才需要检测
		$where[] = ['ownerId','<>',0];
		$where[] = ['oriGoodsId','<>',0];
		$gs = Db::name('goods')->where($where)->select();
		if(!empty($gs)){
			foreach($gs as $g){
				//检查保底交易商品是否满足时间及价格条件
				$cfg = Db::name('guarantee_config')->find();
				$goodsTime = strtotime($g['createTime']);
				$nowTime = strtotime(date("Y-m-d H:i:s"));
				$days = ceil(($nowTime-$goodsTime)/86400); //60s*60min*24h
				if($days<$cfg['backStart']){	// 未达到回购最低时间，则限制涨幅
					$nowPrice = $price;
					if($price == 0){
						$nowPrice = $g['shopPrice'];
					}
					$lastPrice = $g['lastPrice'];
					$maxRise = $cfg['maxRise'];var_dump($nowPrice);die;var_dump($lastPrice*(100+$maxRise)/100);die;
					if($nowPrice > $lastPrice*(100+$maxRise)/100){
						return '上架失败，保底交易商品在回购最低时间限制内价格涨幅不得超过'.$maxRise.'%!';
					}
				}
			}
		}
		return 0;
	}
	//检查保底交易商品是否符合上架要求
	//price大于0时比较price的值，price等于0时比较数据库的值
	public function checkBDGoods($goodsId,$price){
		//检查是否是保底交易商品
		$where[] = ['goodsId','=',$goodsId];
		//$where[] = ['goodsType','=',2];
		//发生过转手的商品才需要检测
		$where[] = ['oriGoodsId','<>',0];
		$gs = Db::name('goods')->where($where)->find();
		if(!empty($gs)){
			//检查保底交易商品是否满足时间及价格条件
			$cfg = Db::name('guarantee_config')->find();
			$goodsTime = strtotime($gs['createTime']);
			$nowTime = strtotime(date("Y-m-d H:i:s"));
			$days = ceil(($nowTime-$goodsTime)/86400); //60s*60min*24h
			if($days<$cfg['backStart']){	// 未达到回购最低时间，则限制涨幅
				$nowPrice = $price;
				if($price == 0){
					$nowPrice = $gs['shopPrice'];
				}
				$lastPrice = $gs['lastPrice'];
				$maxRise = $cfg['maxRise'];
				if($nowPrice > $lastPrice*(100+$maxRise)/100){
					return '上架失败，保底交易商品在回购最低时间限制内价格涨幅不得超过'.$maxRise.'%!';
				}
			}
		}
		return 0;
	}
	/**
	 * 获取商品资料用于打印
	 */
	public function getByIds($goodsIds){
		$rs = $this->where('goodsId','in',$goodsIds)->select();
		if(!empty($rs)){
			foreach($rs as $k=>$g){
				$rs[$k]['goodsCatName'] = model('common/goodsCats')->getCatName($g['goodsCatId']);
				$shop = Db::name('shops')->where('shopId',$g['shopId'])->find();
				$rs[$k]['shopName'] = $shop['shopName'];
			}
		}
		return $rs;
	}
	/**
	 * 获取商品规格属性
	 */
	public function getSpecAttrs(){
		$goodsType = (int)input('goodsType');
		$goodsCatId = Input('goodsCatId/d');
		$goodsCatIds = model('GoodsCats')->getParentIs($goodsCatId);
		$data = [];
		if($goodsType==0){
			$specs = Db::name('spec_cats')->where([['goodsCatId','in',$goodsCatIds],['isShow','=',1],['dataFlag','=',1]])->field('catId,catName,isAllowImg')->order('isAllowImg desc,catSort asc,catId asc')->select();
			$spec0 = null;
			$spec1 = [];
			foreach ($specs as $key => $v){
				if($v['isAllowImg']==1){
					$spec0 = $v;
				}else{
					$spec1[] = $v;
				}
			}
			$data['spec0'] = $spec0;
			$data['spec1'] = $spec1;
		}
		$data['attrs'] = Db::name('attributes')->where([['goodsCatId','in',$goodsCatIds],['isShow','=',1],['dataFlag','=',1]])->field('attrId,attrName,attrType,attrVal')->order('attrSort asc,attrId asc')->select();
		return WSTReturn("", 1,$data);
	}
}
