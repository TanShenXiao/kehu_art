<?php
namespace wstmart\admin\controller;
use wstmart\admin\model\Goods as M;
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
 * 商品控制器
 */
class Goods extends Base{
   /**
	* 查看上架商品列表
	*/
	public function index(){
	    $this->assign("p",(int)input("p"));
    	$this->assign("areaList",model('areas')->listQuery(0));
		return $this->fetch('list_sale');
	}
   /**
    * 批量删除商品
    */
    public function batchDel(){
        $m = new M();
        return $m->batchDel();
    }

    /**
    * 设置违规商品
    */
    public function illegal(){
        $m = new M();
        return $m->illegal();
    }
    /**
    * 批量设置违规商品
    */
    public function batchIllegal(){
        $m = new M();
        return $m->batchIllegal();
    }
    /**
     * 通过商品审核
     */
    public function allow(){
        $m = new M();
        return $m->allow();
    } 
    /**
     * 批量通过商品审核
     */
    public function batchAllow(){
        $m = new M();
        return $m->batchAllow();
    }
	/**
	 * 获取上架商品列表
	 */
	public function saleByPage(){
		$m = new M();
		$rs = $m->saleByPage();
		$rs['status'] = 1;
		return WSTGrid($rs);
	}
	
    /**
	 * 审核中的商品
	 */
    public function auditIndex(){
        $this->assign("p",(int)input("p"));
    	$this->assign("areaList",model('areas')->listQuery(0));
		return $this->fetch('goods/list_audit');
	}
	/**
	 * 获取审核中的商品
	 */
    public function auditByPage(){
		$m = new M();
		$rs = $m->auditByPage();

		$rs['status'] = 1;
		return WSTGrid($rs);
	}
   /**
	 * 审核中的商品
	 */
    public function illegalIndex(){
        $this->assign("p",(int)input("p"));
    	$this->assign("areaList",model('areas')->listQuery(0));
		return $this->fetch('list_illegal');
	}
    /**
	 * 获取违规商品列表
	 */
	public function illegalByPage(){
		$m = new M();
		$rs = $m->illegalByPage();
		$rs['status'] = 1;
		return WSTGrid($rs);
	}
    
    /**
     * 删除商品
     */
    public function del(){
    	$m = new M();
    	return $m->del();
    }

	/**
     * 打印商品标签
     */
    public function goodsPrint(){
		$ids = input('ids/s','');
		$ids = explode('@',$ids);
		$goods = model('common/goods')->getByIds($ids);
		$rs = Db::name('sys_configs')->where('fieldCode','goodsBkImg')->find();
		$bkImg = '';
		if(!empty($rs)){
			$bkImg = $rs['fieldValue'];
		}
		foreach($goods as $k=>$v){
			$attr = Db::name('goods_attributes')->alias('ga')->join('attributes a','ga.attrId=a.attrId')->field('ga.attrVal,a.attrName')->where('goodsId',$v['goodsId'])->select();
			$goods[$k]['material'] = '';
			$goods[$k]['size'] = '';
			foreach($attr as $vv){
				if($vv['attrName']=='材质')
					$goods[$k]['material'] = $vv['attrVal'];
				else if($vv['attrName']=='尺寸')
					$goods[$k]['size'] = $vv['attrVal'];
			}
		}
		$this->assign('bkImg',$bkImg);
		$this->assign('goods',$goods);
		return $this->fetch('print_goods');
    }

	/**
     * 设置商品标签背景
     */
    public function setBkImg(){
		$pathName = input('imgPathName/s','');
		$data = ['fieldName'=>'商品标签背景图片','fieldCode'=>'goodsBkImg'];
		$data['fieldValue'] = $pathName;
		$where = ['fieldCode'=>'goodsBkImg'];
		$bk = Db::name('sys_configs')->where($where)->find();
		if(empty($bk)){
			Db::name('sys_configs')->insert($data);
		}else{
			Db::name('sys_configs')->where($where)->update($data);
		}
		return WSTReturn('设置成功',1);
    }
}
