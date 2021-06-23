<?php
namespace wstmart\common\model;
use wstmart\common\model\GoodsCats as M;
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
 * 商品属性分类
 */
use \think\Db;
class Attributes extends Base{
	/**
	 * 获取可供筛选的商品属性
	 */
	public function listQueryByFilter($catId){
		$m = new M();
		$ids = $m->getParentIs($catId);
		if(!empty($ids)){
			$catIds = [];
			foreach ($ids as $key =>$v){
				$catIds[] = $v;
			}
			// 取出分类下有设置的属性。
			$attrs = Db::name('attributes')->where(['isShow'=>1,'dataFlag'=>1])
					  ->where([['goodsCatId','in',$catIds],['attrType','<>',0]])
			          ->field('attrId,attrName,attrVal')->order('attrSort asc')->select();
			foreach ($attrs as $key =>$v){
			    $attrs[$key]['attrVal'] = explode(',',$v['attrVal']);
			}
			return $attrs;
		}
		return [];
	}
	/**
	* 根据商品id获取可供选择的属性
	*/
	public function getAttribute($goodsId){
		if(empty($goodsId))return [];
		$attrs = Db::name('attributes')->alias('a')
					  ->join('__GOODS_ATTRIBUTES__ ga','ga.attrId=a.attrId','inner')
					  ->field('ga.attrId,GROUP_CONCAT(distinct ga.attrVal) attrVal,a.attrName')
					  ->where(['a.isShow'=>1,'a.dataFlag'=>1])
					  	->where([['ga.goodsId','in',$goodsId],['a.attrType','<>',0]])
					  ->group('ga.attrId')
					  ->order('a.attrSort asc')
					  ->select();
		if(empty($attrs))return [];
		foreach ($attrs as $key =>$v){
			    $attrs[$key]['attrVal'] = explode(',',$v['attrVal']);
		}
		return $attrs;
	}

    /**
     * 通过商品id获取商品相关属性
     * @param $goodsId
     */
	public function getGoodsAttributeByGoodsId( $goodsId ){
	    if( empty( $goodsId ) )
	        return array();
       $result = Db::name('goods_attributes')->where(['goodsId'=>$goodsId])->select();
       if( $result ){
           return $result;
       }else{
           return array();
       }
    }

    /**
     * 通过id批量返回数据
     * @param $ids
     */
    public function getAttributeByIds($ids){
        if( empty( $ids ) )
            return [];
        $where = [];
        $where[] = ['attrId','in',$ids];
        return  Db::name('attributes')->where($where)->select();
    }

    /**
     * 转换
     * @param $goods_attr
     * @param $attr
     * @param $data
     * @return array
     */
    public function attridToAttrval( $goods_attr , $attr , &$data ){
        $data['goodsCz'] = "";
        $data['goodsTc'] = "";
        $data['zznb'] = "";
        $data['szyx'] = "";
        $data['zdls'] = "";

        if( empty( $goods_attr ) or empty( $attr ) ){
            return [];
        }

        $goods_attr_val = [];
        foreach( $goods_attr as $k => $v ){
            $goods_attr_val[$v['attrId']] = $v['attrVal'];
        }



        foreach( $attr as $k => $v ){
            switch( $v['attrName'] ){
                case "材质":
                    $data['goodsCz'] = $goods_attr_val[$v['attrId']];
                    break;
                case "题材":
                    $data['goodsTc'] = $goods_attr_val[$v['attrId']];
                    break;
                case "作者年表":
                    $data['zznb'] = $goods_attr_val[$v['attrId']];
                    break;
                case "所有院系":
                    $data['szyx'] = $goods_attr_val[$v['attrId']];
                    break;
                case "指导老师":
                    $data['zdls'] = $goods_attr_val[$v['attrId']];
                    break;
                default:
                    break;
            }
        }
    }
}
