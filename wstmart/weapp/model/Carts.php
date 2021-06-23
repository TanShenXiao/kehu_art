<?php
namespace wstmart\weapp\model;
use wstmart\common\model\Carts as CCarts;
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
 * 购物车业务处理类
 */

class Carts extends CCarts{
	protected $pk = 'cartId';
	
	/**
	* 批量修改购物车选中状态
	*/
	public function batchSetIsCheck(){
		$id = Input('post.id');
		// 转数组
		$ids = explode(',',$id);
		$isCheck = (int)Input('post.isCheck');
		$userId = model('weapp/index')->getUserId();
		$data['isCheck'] = $isCheck;
		$where = [];
		$where[] = ['userId','=',$userId];
		$where[] = ['cartId','in',$id];
		$this->where($where)->update($data);
		return jsonReturn("操作成功", 1);
	}
	/**
	 * 获取购物车数量
	 */
	function cartNum(){
		$userId = model('weapp/index')->getUserId();
		$cartNum = $this->where(['userId'=>$userId])->field('cartId')->select();
		$count = count($cartNum);
		return $count;
	}
}
