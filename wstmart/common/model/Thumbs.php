<?php
namespace wstmart\common\model;
use wstmart\common\model\Goods as CGoods;
use wstmart\home\validate\Shops as VShop;
use think\Db;
/**
 * 点赞类
 */
class Thumbs extends Base{
	/**
	 *保存点赞记录
	 **/
	 public function recordThumb(){
		$goodsId = (int)input('goodsId');
		$userId = (int)input('userId');
		$date = date('Y-m-d',time());
		//查询当日点赞次数
		$rs = $this->where("goodsId=$goodsId","userId=$userId")->count();
		if($rs<config('thumbNum')) {	// 点赞次数未达标
			$this->where("goodsId=$goodsId")->setInc('thumbsNum');	// 给商品点赞数加1
		}
	 }
}
