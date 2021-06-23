<?php
namespace wstmart\weapp\model;
use think\Db;
use think\Model;
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
 * 品牌业务处理类
 */
class Brands extends Model{
	/**
	 * 获取品牌列表
	 */
	public function pageQuery($id = 0){
		$id = ($id>0)?$id:(int)input('id');
		$where['b.dataFlag']=1;
		$prefix = config('database.prefix');
		$where['gcb.catId']=$id;
		$rs = $this->alias('b')
		->join('__CAT_BRANDS__ gcb','gcb.brandId=b.brandId','left')
		->where($where)
		->field('b.brandId,brandName,brandImg,gcb.catId')
		->group('b.brandId,gcb.catId')
		->order('b.sortNo asc')
		->select();
		return $rs;
	}
}
