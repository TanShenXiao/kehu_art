<?php
namespace wstmart\home\controller;
//use wstmart\home\model\Invoice as M;

class Invoice extends Base{
	protected $beforeActionList = ['checkShopAuth'];
	/**
	 * 开具发票首页
	 */
	public function invoice(){
		return $this->fetch('shops/invoice/invoice');
	}
}
