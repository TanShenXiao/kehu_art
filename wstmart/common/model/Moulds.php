<?php
namespace wstmart\common\model;
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
 * 规格分类业务处理
 */
class Moulds extends Base{
	
	/**
	 * 新增
	 */
	public function add(){
		$shopId = (int)session('WST_USER.shopId');
		$mouldName = input("mouldName");
		$mouldId = (int)input("mouldId");
		if($mouldId==0 && $mouldName=="")return WSTReturn('请输入模板名称',-1);
		
		$optName = ($mouldId>0)?"修改":"新增";
		$goodsCatId = (int)input("goodsCatId");
		$rs = $this->checkMouldName();
		if($rs['status']==1){
			Db::startTrans();
	        try{
	        	$specsIds = input('post.specsIds');
				$goodmodel = model('GoodsCats');
				$goodsCats = $goodmodel->getParentIs($goodsCatId);
				if($mouldId==0){
					$data = [];
					$data["shopId"] = $shopId;
					$data["mouldName"] = $mouldName;
					$data["goodsCatId"] = $goodsCatId;
					$data["dataFlag"] = 1;
					$data["createTime"] = date("Y-m-d H:i:s");

					$mouldId = Db::name("moulds")->insertGetId($data);
				}
				
				
				//如果是实物商品并且有销售规格则保存销售和规格值
		        if($specsIds!=''){
		        	//把之前之前的销售规格
	    	        $specsIds = explode(',',$specsIds);
			    	$specsArray = [];
			    	foreach ($specsIds as $v){
			    		$vs = explode(':',$v);
			    		$specsArray[] = $vs;
			    	}
			    	Db::name('mould_goods_spec_items')->where(['shopId'=>$shopId,'mouldId'=>$mouldId])->update(['dataFlag'=>-1]);
		    		//保存规格名称
		    		$specMap = [];
		    		foreach ($specsArray as $v){
		    			$specNumId = $v[0]."_".$v[1];
		    			$itemName = input('post.specName_'.$specNumId);
		    			if($itemName=='')continue;
		    			$sitem = [];
		    			$sitem['mouldId'] = $mouldId;
		    			$sitem['goodsCatId'] = $goodsCatId;
		    			$sitem['catId'] = $v[0];
		    			$sitem['itemName'] = input('post.specName_'.$specNumId);
		    			$sitem['itemImg'] = input('post.specImg_'.$specNumId,'');
	    				$sitem['shopId'] = $shopId;
	    				$sitem['dataFlag'] = 1;
	    			    $sitem['createTime'] = date('Y-m-d H:i:s');
	    			    $itemId = Db::name('mould_goods_spec_items')->insertGetId($sitem);
	    			    if($sitem['itemImg']!='')WSTUseResource(0, $itemId, $sitem['itemImg']);
		    			
		    		}
		    		
		        }
		        //保存商品属性
		        //删除之前的商品属性
		        Db::name('mould_goods_attributes')->where(['mouldId'=>$mouldId,'shopId'=>$shopId])->update(['dataFlag'=>-1]);
		        //新增商品属性
		    	$attrsArray = [];
		    	$attrRs = Db::name('attributes')->where([['goodsCatId','in',$goodsCats],['isShow','=',1],['dataFlag','=',1],['shopId','in',[0,$shopId]]])
		    		            ->field('attrId')->select();
		    	foreach ($attrRs as $key =>$v){
		    		$attrs = [];
		    		$attrs['attrVal'] = input('attr_'.$v['attrId']);
		    		if($attrs['attrVal']=='')continue;
		    		$attrs['shopId'] = $shopId;
		    		$attrs['mouldId'] = $mouldId;
		    		$attrs['goodsCatId'] = $goodsCatId;
		    		$attrs['dataFlag'] = 1;
		    		$attrs['attrId'] = $v['attrId'];
		    		$attrs['createTime'] = date('Y-m-d H:i:s');
		    		$attrsArray[] = $attrs;
		    	}
		    	if(count($attrsArray)>0)Db::name('mould_goods_attributes')->insertAll($attrsArray);
			    Db::commit();
				return WSTReturn($optName."成功", 1,['mouldId'=>$mouldId]);
			
			}catch (\Exception $e) {
	            Db::rollback();
	            return WSTReturn($optName.'失败',-1);
	        }
		}else{
			return $rs;
		}
	}

	/**
	 * 删除
	 */
    public function del(){
    	$shopId = (int)session('WST_USER.shopId');
	    $mouldId = (int)input('mouldId');
	    Db::startTrans();
        try{
        	$data["dataFlag"] = -1;
		    $where = [];
			$where[] = ["shopId","=",$shopId];
			$where[] = ["id","=",$mouldId];
			Db::name("moulds")->where($where)->update($data);
			$where = [];
			$where[] = ["shopId","=",$shopId];
			$where[] = ["mouldId","=",$mouldId];
			Db::name("mould_goods_attributes")->where($where)->update($data);
			Db::name("mould_goods_spec_items")->where($where)->update($data);
        	Db::commit();
			return WSTReturn("删除成功", 1);
		
		}catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('删除失败',-1);
        }
	    
	}
	
	/**
	 * 
	 * 根据ID获取
	 */
	public function getById(){
		$shopId = (int)session('WST_USER.shopId');
		$mouldId = (int)input('mouldId');
		$mould = Db::name("moulds")->where(["shopId"=>$shopId,"id"=>$mouldId])->find();
		$goodsCatId = $mould['goodsCatId'];
		$goodsCatIds = model('GoodsCats')->getParentIs($goodsCatId);
		$data = [];
		
		$specs = Db::name('spec_cats')->where([['shopId','in',[0,$shopId]],['goodsCatId','in',$goodsCatIds],['isShow','=',1],['dataFlag','=',1]])->field('catId,catName,isAllowImg')->order('isAllowImg desc,catSort asc,catId asc')->select();
		$spec0 = null;
		$spec1 = [];
		foreach ($specs as $key => $v){
			if($v['isAllowImg']==1){
				if(!$spec0){
					$spec0 = $v;
				}else{
					$spec1[] = $v;
				}
			}else{
				$spec1[] = $v;
			}
		}
		$data['spec0'] = $spec0;
		$data['spec1'] = $spec1;
		
		$data['attrs'] = Db::name('attributes')->where([['shopId','in',[0,$shopId]],['goodsCatId','in',$goodsCatIds],['isShow','=',1],['dataFlag','=',1]])->field('attrId,attrName,attrType,attrVal')->order('attrSort asc,attrId asc')->select();

		$specAttrObj = [];
		//获取规格值
		$specs = Db::name('spec_cats gc')
				->join('mould_goods_spec_items sit','gc.catId=sit.catId','inner')
				->where(['sit.mouldId'=>$mouldId,'gc.isShow'=>1,'sit.dataFlag'=>1])
				->field('gc.isAllowImg,sit.catId,sit.itemId,sit.itemName,sit.itemImg')
				->order('gc.isAllowImg desc,gc.catSort asc,gc.catId asc')
				->select();
		$spec0 = [];
		$spec1 = [];
		foreach ($specs as $key =>$v){
			if($v['isAllowImg']==1){
				$spec0[] = $v;
			}else{
				$spec1[] = $v;
			}
		}
		$specAttrObj['spec0'] = $spec0;
		$specAttrObj['spec1'] = $spec1;
		//获取销售规格
		$specAttrObj['saleSpec'] = [];
		//获取属性值
		$specAttrObj['attrs'] = Db::name('mould_goods_attributes ga')
								->join('attributes a','ga.attrId=a.attrId','inner')
								->where('mouldId',$mouldId)
								->field('ga.attrId,a.attrType,ga.attrVal')
								->select();
		$data['specAttrObj'] = $specAttrObj;

	    return WSTReturn("", 1,$data);
	}
	


	
	/**
	 * 分页
	 */
	public function getMouldList(){
		$shopId = (int)session('WST_USER.shopId');
		$goodsCatId = (int)input('goodsCatId');
		$where = [];
		$where[] = ["shopId","=",$shopId];
		$where[] = ["goodsCatId","=",$goodsCatId];
		$where[] = ["dataFlag","=",1];
		$rs = Db::name("moulds")->where($where)->order("createTime desc")->select();
		return $rs;
	}

	public function editMouldName(){
		$shopId = (int)session('WST_USER.shopId');
		$rs = $this->checkMouldName();
		if($rs['status']==1){
			$mouldId = (int)input("mouldId");
			$mouldName = input("mouldName");
			$data = [];
			$data["mouldName"] = $mouldName;
			$where = [];
			$where[] = ["shopId","=",$shopId];
			$where[] = ["id","=",$mouldId];
			Db::name("moulds")->where($where)->update($data);
			return WSTReturn("修改成功",1);
		}else{
			return $rs;
		}
	}

	public function checkMouldName(){
		$mouldId = (int)input("mouldId");
		$shopId = (int)session('WST_USER.shopId');
		$mouldName = input("mouldName");
		$goodsCatId = (int)input("goodsCatId");
		$where = [];
		$where[] = ["shopId","=",$shopId];
		$where[] = ["goodsCatId","=",$goodsCatId];
		$where[] = ["mouldName","=",$mouldName];
		if($mouldId>0)$where[] = ["id","<>",$mouldId];
		$rs = Db::name("moulds")->where($where)->find();
		if(empty($rs)){
			return WSTReturn("",1);
		}else{
			return WSTReturn("模板已存在",-1);
		}
	}

}
