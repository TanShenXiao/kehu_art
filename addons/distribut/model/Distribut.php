<?php
namespace addons\distribut\model;
use think\addons\BaseModel as Base;
use think\Db;
use Env;
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
 * 分销业务处理
 */
class Distribut extends Base{
	
	/**
	 * 添加菜单、权限
	 */
	public function installMenu(){
		Db::startTrans();
		try{
			
			$hooks = array("beforeSubmitOrder","mobileControllerCartsSettlement","wechatControllerCartsSettlement","homeControllerCartsSettlement","wechatDocumentGoodsDetail",
							"wechatDocumentGoodsDetailTips","wechatDocumentUserIndex","wechatControllerGoodsIndex","mobileDocumentGoodsDetail","mobileDocumentGoodsDetailTips",
							"mobileDocumentUserIndex","mobileControllerIndexIndex","wechatControllerIndexIndex","homeDocumentGoodsDetail","homeDocumentShopHomeHeader","loadHomePage",
							"beforeEidtGoods","afterUserReceive","afterSubmitOrder","afterUserRegist","mobileControllerGoodsIndex",
							"shopDocumentShopEditGoods","initConfigHook","beforeEditOrderMoney"
					);
			$this->bindHoods("Distribut", $hooks);
			
			
			//管理员后台
			$rs = Db::name('menus')->insert(["parentId"=>15,"menuName"=>"分销管理","menuSort"=>4,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"distribut"]);
			
			if($rs!==false){
				$parentId = Db::name('menus')->getLastInsID();
				Db::name('privileges')->insert(["menuId"=>$parentId,"privilegeCode"=>"DISTRIBUT_FXGL_00","privilegeName"=>"查看分销管理","isMenuPrivilege"=>1,"privilegeUrl"=>"","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1]);
				
				Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"分销商家列表","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"distribut"]);
				$menuId = Db::name('menus')->getLastInsID();
				Db::name('privileges')->insert(["menuId"=>$menuId,"privilegeCode"=>"DISTRIBUT_FXSJ_00","privilegeName"=>"查看分销商家","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/distribut-distribut-admindistributshops","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1]);
				
				
				Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"分销商品列表","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"distribut"]);
				$menuId = Db::name('menus')->getLastInsID();
				Db::name('privileges')->insert(["menuId"=>$menuId,"privilegeCode"=>"DISTRIBUT_FXSP_00","privilegeName"=>"查看分销商品","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/distribut-distribut-admindistributgoods","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1]);
				
				Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"佣金分成列表","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"distribut"]);
				$menuId = Db::name('menus')->getLastInsID();
				Db::name('privileges')->insert(["menuId"=>$menuId,"privilegeCode"=>"DISTRIBUT_YJFC_00","privilegeName"=>"查看佣金分成","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/distribut-distribut-admindistributmoneys","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1]);
				
				Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"推广用户列表","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"distribut"]);
				$menuId = Db::name('menus')->getLastInsID();
				Db::name('privileges')->insert(["menuId"=>$menuId,"privilegeCode"=>"DISTRIBUT_TGYH_00","privilegeName"=>"查看推广用户","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/distribut-distribut-admindistributusers","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1]);

				Db::name('menus')->insert(["parentId"=>71,"menuName"=>"结算佣金统计","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"distribut"]);
				$menuId = Db::name('menus')->getLastInsID();
				Db::name('privileges')->insert(["menuId"=>$menuId,"privilegeCode"=>"DISTRIBUT_JSYJTJ_00","privilegeName"=>"查看结算佣金统计","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/distribut-distribut-admindistributmoneysreport","otherPrivilegeUrl"=>"/addon/distribut-distribut-admindistributmoneysbypage,/addon/distribut-distribut-toexportdistributmoneys","dataFlag"=>1,"isEnable"=>1]);
				
			}
			
			$now = date("Y-m-d H:i:s");
			//用户中心
			$rs = Db::name('home_menus')->insert(["parentId"=>100,"menuName"=>"分销管理","menuUrl"=>"#","menuOtherUrl"=>"","menuType"=>0,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
			if($rs!==false){
				$parentId = Db::name('home_menus')->getLastInsID();
				Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"我的推广用户","menuUrl"=>"addon/distribut-distribut-userdistributusers","menuOtherUrl"=>"addon/distribut-distribut-querymineusers","menuType"=>0,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
				Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"分成记录","menuUrl"=>"addon/distribut-distribut-userdistributmoneys","menuOtherUrl"=>"addon/distribut-distribut-queryusermoneys","menuType"=>0,"isShow"=>1,"menuSort"=>2,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
			}
			
			//商家中心
			$rs = Db::name('home_menus')->insert(["parentId"=>76,"menuName"=>"分销管理","menuUrl"=>"#","menuOtherUrl"=>"","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
			if($rs!==false){
				$parentId = Db::name('home_menus')->getLastInsID();
				Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"分销商品","menuUrl"=>"/addon/distribut-distribut-shopdistributgoods","menuOtherUrl"=>"/addon/distribut-distribut-querydistributgoods","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
				Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"分成记录","menuUrl"=>"/addon/distribut-distribut-shopdistributmoneys","menuOtherUrl"=>"/addon/distribut-distribut-querydistributmoneys","menuType"=>1,"isShow"=>1,"menuSort"=>2,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
				Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"分销设置","menuUrl"=>"/addon/distribut-distribut-shopdistributcfg","menuOtherUrl"=>"","menuType"=>1,"isShow"=>1,"menuSort"=>3,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"distribut"]);
			}
			
			installSql("distribut");//传入插件名
			$this->addMobileBtn();
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
		
	}
	
	/**
	 * 删除菜单
	 */
	public function uninstallMenu(){
		Db::startTrans();
		try{
			
			$hooks = array("beforeSubmitOrder","mobileControllerCartsSettlement","wechatControllerCartsSettlement","homeControllerCartsSettlement","wechatDocumentGoodsDetail",
							"wechatDocumentGoodsDetailTips","wechatDocumentUserIndex","wechatControllerIndexIndex","wechatControllerGoodsIndex","mobileDocumentGoodsDetail","mobileDocumentGoodsDetailTips",
							"mobileDocumentUserIndex","mobileControllerIndexIndex","homeDocumentGoodsDetail","homeDocumentShopHomeHeader","loadHomePage",
							"beforeEidtGoods","afterUserReceive","afterSubmitOrder","afterUserRegist","mobileControllerGoodsIndex",
							"shopDocumentShopEditGoods","initConfigHook","beforeEditOrderMoney"
					);
			$this->unbindHoods("Distribut", $hooks);
			
			Db::name('menus')->where("menuMark",'=',"distribut")->delete();
			Db::name('home_menus')->where("menuMark",'=',"distribut")->delete();
			Db::name('privileges')->where("privilegeCode","like","DISTRIBUT_%")->delete();

			uninstallSql("distribut");//传入插件名
			$this->delMobileBtn();
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}
	
	public function addMobileBtn(){
		
		$data = array();
		$data["btnName"] = "分销商品";
		$data["btnSrc"] = 0;
		$data["btnUrl"] = "addon/distribut-distribut-mobiledistributgoods.html";
		$data["btnImg"] = "addons/distribut/view/images/distribut.png";
		$data["addonsName"] = "Distribut";
		$data["btnSort"] = 5;
		Db::name('mobile_btns')->insert($data);
		
		$data = array();
		$data["btnName"] = "分销商品";
		$data["btnSrc"] = 1;
		$data["btnUrl"] = "addon/distribut-distribut-wechatdistributgoods.html";
		$data["btnImg"] = "addons/distribut/view/images/distribut.png";
		$data["addonsName"] = "Distribut";
		$data["btnSort"] = 5;
		Db::name('mobile_btns')->insert($data);
		
		// app端
		if(WSTDatas('ADS_TYPE',4)){
			$data = array();
			$data["btnName"] = "分销商品";
			$data["btnSrc"] = 3;
			$data["btnUrl"] = "wst://Distribute";
			$data["btnImg"] = "addons/distribut/view/app/img/distribut.png";
			$data["addonsName"] = "Distribut";
			$data["btnSort"] = 5;
			Db::name('mobile_btns')->insert($data);
		}
		// 小程序端
		if(WSTDatas('ADS_TYPE',5)){
			$data = array();
			$data["btnName"] = "分销商品";
			$data["btnSrc"] = 2;
			$data["btnUrl"] = "/addons/package/pages/distribut/goods/list";
			$data["btnImg"] = "addons/distribut/view/images/distribut.png";
			$data["addonsName"] = "Distribut";
			$data["btnSort"] = 5;
			Db::name('mobile_btns')->insert($data);
		}

	}
	
	public function delMobileBtn(){
	
		Db::name('mobile_btns')->where(["addonsName"=>"Distribut"])->delete();
	
	}
	
	/**
	 * 菜单显示隐藏
	 */
	public function toggleShow($isShow = 1){
		Db::startTrans();
		try{
			Db::name('menus')->where("menuMark",'=',"distribut")->update(["isShow"=>$isShow]);
			Db::name('home_menus')->where("menuMark",'=',"distribut")->update(["isShow"=>$isShow]);
			Db::name('navs')->where(["navUrl"=>"addon/distribut-goods-glist"])->update(["isShow"=>$isShow]);
			if($isShow==1){
				$this->addMobileBtn();
			}else{
				$this->delMobileBtn();
			}
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}
	
	/**
	 * 获取店铺分销配置
	 */
	public function getDistributCfg($sId=0){
		$shopId =($sId==0)?(int)session("WST_USER.shopId"):$sId;
		$conf = Db::name('shop_configs')->where("shopId", $shopId)->field("isDistribut,distributType,distributOrderRate")->find();
		return $conf;
	}
	
	/**
	 * 修改配置
	 */
	public function saveCfg($sId=0){
		$shopId =($sId==0)?(int)session("WST_USER.shopId"):$sId;
		$data = array();
		$data["isDistribut"] = input("isDistribut/d",0);
		$data["distributType"] = input("distributType/d",0);
		$orderNum = (int)input("orderNum/d",0);
		if($data["distributType"]==1){
			$data["distributOrderRate"] = 0;
		}else{
			if($orderNum<=0){
				return WSTReturn("订单佣金比例必须大于0%",-1);
			}else if($orderNum>=100){
				return WSTReturn("订单佣金比例必须小于100%",-1);
			}
			$data["distributOrderRate"] = $orderNum;
		}
		$data["distributOrderRate"] = $data["distributType"]==1?0:input("orderNum/d",0);
		$config = Db::name('shop_configs')->where("shopId", $shopId)->field("distributType")->find();
		$rs = Db::name('shop_configs')->where("shopId", $shopId)->update($data);
		if($data["isDistribut"]==0){
			Db::name('goods')->where(["shopId"=>$shopId,"dataFlag"=>1])->update(["isDistribut"=>0,"commission"=>0]);
		}else{
			if($data["distributType"]==2){////按订单分佣
				Db::name('goods')->where(["shopId"=>$shopId,"dataFlag"=>1])->update(["isDistribut"=>1,"commission"=>0]);
			}else{
				if($config["distributType"]==2){
					Db::name('goods')->where(["shopId"=>$shopId,"dataFlag"=>1])->update(["isDistribut"=>0,"commission"=>0]);
				}
			}
		}
		return WSTReturn("设置成功",1);
		
	}
	
	/**
	 * 获取商品分销设置
	 */
	public function getGoodsDistribut($goodsId){
		
		return Db::name('goods')->where("goodsId",$goodsId)->field("isDistribut,commission")->find();
	}
	
	/**
	 * 店铺分成记录
	 */
	public function queryDistributMoneys($sId=0){
		$shopId =($sId==0)?(int)session('WST_USER.shopId'):$sId;
		$moneyStatus = input("moneyStatus",-1);
		$where = array();
		$where[] = ["dm.shopId",'=',$shopId];
		$orderNo = input("orderNo");
		$userName = input("userName");
		if($orderNo!=""){
			$where[] = ["o.orderNo","like",$orderNo."%"];
		}
		if($userName!=""){
			$where[] = ['u.userName|u.loginName', 'like', '%'.$userName.'%'];
		}
		if($moneyStatus>=0){
			$where[] = ["dm.moneyStatus","=",$moneyStatus];
		}
		$rs = Db::name('distribut_moneys dm')
				->join("__USERS__ u","u.userId=dm.userId")
				->join("__ORDERS__ o","o.orderId=dm.orderId")
				->where($where)
				->field("u.userId,u.userName,u.loginName,dm.moneyId,dm.money,dm.distributMoney,dm.remark,dm.createTime,dm.distributType,dm.moneyStatus,o.orderId,o.orderNo")
				->order('dm.moneyId', 'desc')
				->paginate(input('pagesize/d'))->toArray();
		return $rs;
	}
	
	/**
	 *  分销商品列表
	 */
	public function queryDistributGoods($sId=0){
		$shopId =($sId==0)?(int)session('WST_USER.shopId'):$sId;
		$shopConf = Db::name('shop_configs')->where("shopId",$shopId)->field("distributType,distributOrderRate")->find();
		$where =[];
		$where[] = ['shopId','=',$shopId];
		$where[] = ['goodsStatus','=',1];
		$where[] = ['dataFlag','=',1];
		$where[] = ['isSale','=',1];
		$where[] = ['isDistribut','=',1];
		$c1Id = (int)input('cat1');
		$c2Id = (int)input('cat2');
		$goodsName = input('goodsName');
		if($goodsName !='')$where[] = ['goodsName', 'like', '%'.$goodsName.'%'];
		if($c2Id!=0 && $c1Id!=0){
			$where[] = ['shopCatId2','=',$c2Id];
		}else if($c1Id!=0){
			$where[] = ['shopCatId1','=',$c1Id];
		}
		$rs = Db::name('goods')
			->where($where)
			->field('goodsId,goodsName,goodsImg,goodsSn,isSale,isBest,isHot,isNew,isRecom,goodsStock,saleNum,shopPrice,isSpec,commission')
			->order('saleTime', 'desc')
			->paginate(input('pagesize/d'))->toArray();
		foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['verfiycode'] = WSTShopEncrypt($shopId);
			$rs['data'][$key]['distributType'] = $shopConf["distributType"];
			$rs['data'][$key]['distributType'] = $shopConf["distributType"];
			$rs['data'][$key]['distributOrderRate'] = $shopConf["distributOrderRate"];
		}
		return $rs;
	}
	
	/**
	 * 用户注册设置
	 */
	public function userRegist($userId){
		$isWeapp = (int)input("isWeapp");
		$shareUserId = 0;
		if($isWeapp==1){
			$shareUserId = (int)input("shareUserId");
		}else{
			$shareUserId = (int)session("WST_shareUserId");
		}
		
		if($shareUserId>0){
			$addon = Db::name('addons')->where("name","Distribut")->field("config")->find();
			if($addon){
				$config = json_decode($addon["config"],true);
				$distributDeal = $config["distributDeal"];
				$sharer = Db::name('users')->where("userId",$shareUserId)->field("isBuyer")->find();
				if(($sharer["isBuyer"]!=1 && $distributDeal==2)){
					return true;
				}
			}
			$puser = Db::name('distribut_users')->where("userId",$shareUserId)->field("parentId")->find();
			$data = array();
			$data["grandpaId"] = isset($puser["parentId"])?$puser["parentId"]:0;
			$data["parentId"] = $shareUserId;
			$data["userId"] = $userId;
			$data["createTime"] = date("Y-m-d H:i:s");
			$data['ip'] = request()->ip();
			Db::name('distribut_users')->insert($data);
			session('WST_shareUserId', null);
		}
		return true;
	}
	
	/**
	 * 获取配置
	 */
	public function getAddonConfig(){
		$addon = Db::name('addons')->where("name","Distribut")->field("config")->find();
		$config = json_decode($addon["config"],true);
		return $config;
	}
	
	/**
	 * 设置订单分销信息
	 */
	public function setOrderDistribut($orderId){
		
		$order = Db::name('orders')->where("orderId",$orderId)->field("shopId,scoreMoney,realTotalMoney,deliverMoney,userId")->find();
		$shopId = $order["shopId"];
		$userId = $order["userId"];
		
		$duser = Db::name('distribut_users')->where("userId",$userId)->field("parentId,userId")->find();
		$parentId = isset($duser["parentId"])?(int)$duser["parentId"]:0;
		$conf = self::getDistributCfg($shopId);
		
		$data = array();
		$data["distributOrderRate"] = $conf['distributOrderRate'];
		if($conf["isDistribut"]==1 && $parentId>0){
			$orderRealMoney = $order["scoreMoney"]+$order["realTotalMoney"]-$order["deliverMoney"];
			$data["distributType"] = $conf['distributType'];
			if($conf['distributType']==1 || $conf['distributType']==2){//1:按商品提成佣金 1:按商品比例提成佣金
				$totalCommission = 0;//总佣金
				$where = array();
				$where["orderId"] = $orderId;
				$goodslist = Db::name('order_goods og')->join("__GOODS__ g","g.goodsId=og.goodsId")
							->where($where)
							->field("og.id,g.commission,og.goodsNum,og.goodsPrice,og.couponVal,og.rewardVal")
							->select();

				foreach ($goodslist as $key=> $goods){
					if($conf['distributType']==1){//按商品提成佣金
						$commission = ($goods["commission"]<$goods["goodsPrice"])?$goods["commission"]:0;
					}else{
						$commission = round(($goods["goodsPrice"]-$goods["couponVal"]-$goods["rewardVal"])*$conf["distributOrderRate"]/100,2);
					}
					Db::name('order_goods')->where("id",$goods["id"])->update(["commission"=>$commission]);
					$totalCommission = $totalCommission + round($goods["goodsNum"]*$commission,2);
				}
				if($orderRealMoney<$totalCommission){
					$data["totalCommission"] = $orderRealMoney;
				}else{
					$data["totalCommission"] = $totalCommission;
				}
			}
			
		}else{
			$data["distributType"] = 0;
			$data["totalCommission"] = 0;
		}
		Db::name('orders')->where("orderId",$orderId)->update($data);
		
		return true;
		
	}
	
	/**
	 * 用户确认收货
	 */
	public function userReceive($orderId){
		$order = Db::name('orders')->where("orderId",$orderId)->field("userId,shopId,distributType")->find();
		$userId = $order["userId"];
		$shopId = $order["shopId"];
		$duser = Db::name('distribut_users')->where("userId",$userId)->field("grandpaId,parentId,userId")->find();
		
		Db::name('users')->where("userId",$userId)->update(["isBuyer"=>1]);
		
		if(!empty($duser)){
			if ($order["distributType"]<=0) {
				return ;
			}
			$grandpaId = (int)$duser["grandpaId"];
			$parentId = (int)$duser["parentId"];
			$cfg = self::getAddonConfig();
			$thirdRate = $cfg["thirdRate"] ;
			$secondRate = $cfg["secondRate"];
			$buyerRate = $cfg["buyerRate"];
			if(($thirdRate+$secondRate+$buyerRate)!=100){
				return ;
			}
			$goodslist = Db::name('order_goods')->where("orderId",$orderId)->field("id,goodsId,goodsName,goodsNum,goodsPrice,commission")->select();
			
			foreach ($goodslist as $key=> $goods){
				if($goods['commission']>0){
					//第三级
					$thirdMoney = 0;
					if($grandpaId>0){
						$thirdMoney = round(($goods['goodsNum']*$goods['commission']*$thirdRate/100),2);
						if($thirdMoney>0){
							$obj = array();
							$obj["shopId"] = $shopId;
							$obj["orderId"] = $orderId;
							$obj["userId"] = $grandpaId;
							$obj["buyerId"] = $userId;
							$obj["remark"] = "商品【".$goods["goodsName"]."】";
							$obj["distributType"] = 1;
							$obj["dataId"] = $goods["id"];
							$obj["money"] = $goods["goodsPrice"]*$goods["goodsNum"];
							$obj["goodsNum"] = $goods["goodsNum"];
							$obj["distributMoney"] = $thirdMoney;
							$obj["createTime"] = date("Y-m-d H:i:s");
							$obj["moneyType"] = 3;
							Db::name('distribut_moneys')->insert($obj);
							
							Db::name('users')->where("userId",$grandpaId)->update([
	                            'lockMoney'=>Db::raw('lockMoney+'.$thirdMoney),
	                            'distributMoney'=>Db::raw('distributMoney+'.$thirdMoney)
	                        ]);
						}
					}else{
						$thirdRate = 0;
					}
					
					//第二级
					if($grandpaId==0){
						$secondRate = 100 - $thirdRate - $buyerRate;
					}
					$secondMoney = round(($goods['goodsNum']*$goods['commission']*$secondRate/100),2);
					if($secondMoney>0){
						$obj = array();
						$obj["shopId"] = $shopId;
						$obj["orderId"] = $orderId;
						$obj["userId"] = $parentId;
						$obj["buyerId"] = $userId;
						$obj["remark"] = "商品【".$goods["goodsName"]."】";
						$obj["distributType"] = 1;
						$obj["dataId"] = $goods["id"];
						$obj["money"] = $goods["goodsPrice"]*$goods["goodsNum"];
						$obj["goodsNum"] = $goods["goodsNum"];
						$obj["distributMoney"] = $secondMoney;
						$obj["createTime"] = date("Y-m-d H:i:s");
						$obj["moneyType"] = 2;
						Db::name('distribut_moneys')->insert($obj);
						
						Db::name('users')->where("userId",$parentId)->update([
                            'lockMoney'=>Db::raw('lockMoney+'.$secondMoney),
                            'distributMoney'=>Db::raw('distributMoney+'.$secondMoney)
                        ]);
					}
					
					//购买者
					$buyerMoney = round(($goods['goodsNum']*$goods['commission']*$buyerRate/100),2);
					if($buyerMoney>0){
						$obj = array();
						$obj["shopId"] = $shopId;
						$obj["orderId"] = $orderId;
						$obj["userId"] = $userId;
						$obj["buyerId"] = $userId;
						$obj["remark"] = "商品【".$goods["goodsName"]."】";
						$obj["distributType"] = 1;
						$obj["dataId"] = $goods["id"];
						$obj["money"] = $goods["goodsPrice"]*$goods["goodsNum"];
						$obj["goodsNum"] = $goods["goodsNum"];
						$obj["distributMoney"] = $buyerMoney;
						$obj["createTime"] = date("Y-m-d H:i:s");
						$obj["moneyType"] = 1;
						Db::name('distribut_moneys')->insert($obj);
					
						Db::name('users')->where("userId",$userId)->update([
                            'lockMoney'=>Db::raw('lockMoney+'.$buyerMoney),
                            'distributMoney'=>Db::raw('distributMoney+'.$buyerMoney)
                        ]);
						
					}
				}
			}
			
		}
		//修改结算佣金
		Db::name('orders')->where("orderId",$orderId)->update([
	                            'commissionFee'=>Db::raw('commissionFee+totalCommission')
	                        ]);
		return true;
		
	}
	
	
	/**
	 * 用户分成记录
	 */
	public function queryUserMoneys($uId=0){
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		$where = array();
		$moneyStatus = input("moneyStatus",-1);
		$where[] = ["dm.userId",'=',$userId];
		$orderNo = input("orderNo");
		$userName = input("userName");
		$type = input("type/d",0);
		if($orderNo!=""){
			$where[] = ["o.orderNo","like",$orderNo."%"];
		}
		if($userName!=""){
			$where[] = ["u.userName|u.loginName","like",$userName."%"];
		}
		if($type==1){
			$where[] = ["dm.moneyType",'=',1];
		}else if($type==2){
			$where[] = ["dm.moneyType","gt",1];
		}
		if($moneyStatus>=0){
			$where[] = ["dm.moneyStatus","=",$moneyStatus];
		}
		$rs = Db::name('distribut_moneys dm')
				->join("__USERS__ u","u.userId=dm.userId")
				->join("__ORDERS__ o","o.orderId=dm.orderId")
				->where($where)
				->field("u.userId,u.userName,u.loginName,dm.moneyId,dm.money,dm.distributMoney,dm.remark,dm.createTime,dm.distributType,dm.moneyStatus,o.orderId,o.orderNo")
				->order('dm.moneyId', 'desc')
				->paginate(input('pagesize/d'))->toArray();
		return $rs;
	}
	
	/**
	 * 我的推广用户
	 */
	public function queryMineUsers($uId=0){
		$userId = ($uId==0)?(int)session('WST_USER.userId'):$uId;
		$where = array();
		$where[] = ["dm.parentId",'=',$userId];
		$userName = input("userName");
		if($userName!=""){
			$where[] = ["u.userName|u.loginName","like",$userName."%"];
		}
		$rs = Db::name('distribut_users dm')
				->join("__USERS__ u","u.userId=dm.userId")
				->where($where)
				->field("u.userId,u.userName,u.loginName,u.userSex,u.createTime,u.userPhoto")
				->order('u.userId', 'desc')
				->paginate(input('pagesize/d'))->toArray();
		$userIds = array();
		foreach ($rs['data'] as $key => $v){
			$userIds[] = $v["userId"];
		}
		$where = array();
		$where[] = ["parentId","in",implode(",",$userIds)];
		$pusers = Db::name('distribut_users')->where($where)->field("parentId,count(parentId) userCnt")->group("parentId")->select();
		
		$ulist = array();
		foreach ($pusers as $key => $v){
			$ulist[$v["parentId"]] = $v["userCnt"];
		}
		foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['userCnt'] = isset($ulist[$v["userId"]])?$ulist[$v["userId"]]:0;
			$rs['data'][$key]['userPhoto2'] = $v['userPhoto'];
			$rs['data'][$key]['userPhoto'] = WSTUserPhoto($v['userPhoto']);

		}
		return $rs;
	}
	
	
	public function queryAdminDistributUsers(){
		
		$where = array();
		$where[] = ['dataFlag','=',1];
		$userId = input('userId/d');
		
		$lName = input('loginName');
		$phone = input('loginPhone');
		$email = input('loginEmail');
		if(!empty($lName))
			$where[] = ['loginName','like',"%$lName%"];
		if(!empty($phone))
			$where[] = ['userPhone','like',"%$phone%"];
		if(!empty($email))
			$where[] = ['userEmail','like',"%$email%"];
		
		$rs = Db::name('distribut_users dm')
			->join("__USERS__ u","u.userId=dm.parentId")
			->field("dm.parentId,u.userId,u.userName,u.loginName,u.userSex,u.createTime,u.userPhone,u.userEmail,u.userScore,u.userStatus,u.distributMoney,count(u.userId) userCnt")
			->where($where)
			->group("parentId")
			->order('u.userId', 'desc')
			->paginate(input('limit/d'))->toArray();

		$userIds = array();
		foreach ($rs['data'] as $key => $v){
			$userIds[] = $v["userId"];
		}
		
		return $rs;
	}
	
	public function queryAdminDistributChildUsers(){
	
		$where = array();
		$where[] = ['dataFlag','=',1];
		$userId = input('userId/d');
		$where[] = ["dm.parentId",'=',$userId];
	
		$lName = input('loginName');
		$phone = input('loginPhone');
		$email = input('loginEmail');
		if(!empty($lName))
			$where[] = ['loginName','like',"%$lName%"];
		if(!empty($phone))
			$where[] = ['userPhone','like',"%$phone%"];
		if(!empty($email))
			$where[] = ['userEmail','like',"%$email%"];
	
		$rs = Db::name('distribut_users dm')
		->join("__USERS__ u","u.userId=dm.userId")
		->field("dm.parentId,u.userId,u.userName,u.loginName,u.userSex,u.createTime,u.userPhone,u.userEmail,u.userScore,u.userStatus,u.distributMoney")
		->where($where)
		->order('u.userId', 'desc')
		->paginate(input('limit/d'))->toArray();
	
		$userIds = array();
		foreach ($rs['data'] as $key => $v){
			$userIds[] = $v["userId"];
		}
	
		return $rs;
	}
	
	public function queryAdminDistributShops(){
		
		$shopSn = input('get.shopSn');
		$shopName = input('get.shopName');
		$shopkeeper = input('get.shopkeeper');
		$where = array();
		if(!empty($shopSn))
			$where[] = ['shopSn','like',"%$shopSn%"];
		if(!empty($phone))
			$where[] = ['shopName','like',"%$shopName%"];
		if(!empty($email))
			$where[] = ['shopkeeper','like',"%$shopkeeper%"];
		
		$rs = Db::name('shops s')
				->join('__SHOP_CONFIGS__ sc','s.shopId=sc.shopId and sc.isDistribut=1')
				->join('__AREAS__ a2','s.areaId=a2.areaId','left')
				->where(['s.dataFlag'=>1,'s.dataFlag'=>1,'s.shopStatus'=>1])->where($where)
				->field('s.shopId,shopSn,shopName,a2.areaName,shopkeeper,telephone,shopAddress,shopCompany,shopAtive,shopStatus,distributType')
				->order('s.shopId desc')
				->paginate(input('limit/d'))->toArray();
		
		$cfg = self::getAddonConfig();
		$thirdRate = $cfg["thirdRate"] ;
		$secondRate = $cfg["secondRate"];
		$buyerRate = $cfg["buyerRate"];
		
		foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['thirdRate'] = $thirdRate."%";
			$rs['data'][$key]['secondRate'] = $secondRate."%";
			$rs['data'][$key]['buyerRate'] = $buyerRate."%";
		}
		return $rs;
	}
	
	public function queryAdminDistributGoods(){
		
		$where = array();
		$where[] = ['g.goodsStatus','=',1];
		$where[] = ['g.dataFlag','=',1];
		$where[] = ['g.isSale','=',1];
		$where[] = ['g.isDistribut','=',1];
		$goodsName = input('goodsName');
		$shopName = input('shopName');
		
		if($goodsName != '')$where[] = ['goodsName|goodsSn','like',"%$goodsName%"];
		if($shopName != '')$where[] = ['shopName|shopSn','like',"%$shopName%"];
		$keyCats = self::listKeyAll();
		
		$rs = Db::name('goods g')
				->join('__SHOPS__ s','g.shopId=s.shopId')
				->join('__SHOP_CONFIGS__ sc','sc.shopId=s.shopId')
				->where($where)
				->field('goodsId,goodsName,goodsSn,saleNum,shopPrice,g.shopId,goodsImg,s.shopName,goodsCatIdPath,commission,sc.distributType')
				->order('saleTime', 'desc')
				->paginate(input('limit/d'))->toArray();
		
		foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['verfiycode'] = WSTShopEncrypt($v['shopId']);
			$rs['data'][$key]['goodsCatName'] = self::getGoodsCatNames($v['goodsCatIdPath'],$keyCats);
		}
		return $rs;
		
	}
	
	public function listKeyAll(){
		$rs = Db::name('goods_cats')->field("catId,catName")->where(['dataFlag'=>1])->order('catSort asc,catName asc')->select();
		$data = array();
		foreach ($rs as $key => $cat) {
			$data[$cat["catId"]] = $cat["catName"];
		}
		return $data;
	}
	
	public function getGoodsCatNames($goodsCatPath, $keyCats){
		$catIds = explode("_",$goodsCatPath);
		$catNames = array();
		for($i=0,$k=count($catIds);$i<$k;$i++){
			if($catIds[$i]=='')continue;
			if(isset($keyCats[$catIds[$i]]))$catNames[] = $keyCats[$catIds[$i]];
		}
		return implode("→",$catNames);
	}
	
	
	/**
	 * 用户分成记录
	 */
	public function queryAdminDistributMoneys(){
		$where = array();
		$orderNo = input("orderNo");
		$userName = input("userName");
		if($orderNo!=""){
			$where[] = ["o.orderNo","like",$orderNo."%"];
		}
		if($userName!=""){
			$where[] = ["u.userName|u.loginName","like",$userName."%"];
		}
		$rs = Db::name('distribut_moneys dm')
				->join("__USERS__ u","u.userId=dm.userId")
				->join("__ORDERS__ o","o.orderId=dm.orderId")
				->where($where)
				->field("u.userId,u.userName,u.loginName,dm.moneyId,dm.money,dm.remark,dm.distributMoney,dm.createTime,dm.distributType,o.orderId,o.orderNo")
				->order('dm.moneyId', 'desc')
				->paginate(input('limit/d'))->toArray();
		foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['userName'] = $rs['data'][$key]['userName']!=""?$rs['data'][$key]['userName']:$rs['data'][$key]['loginName'];
		}
		return $rs;
	}
	
	/**
	 * 会员中心
	 */
	public function getUserInfo($uId=0){
		$userId = $uId==0?session('WST_USER.userId'):$uId;
		$user =  Db::name('users')->where(['userId'=>$userId])->find();
		$user['ranks'] = Db::name('user_ranks')->where(['dataFlag'=>1])->where('startScore','<=',$user['userTotalScore'])->where('endScore','>=',$user['userTotalScore'])->find();
		if($user['userName']=='')$user['userName']=$user['loginName'];
		$cnt = Db::name('distribut_users')->where(['parentId'=>$userId])->count();
		$user["userCnt"] = $cnt;
		return $user;
		
	}
	
	/**
	 * 会员中心
	 */
	public function getUser($uId=0){
		$userId = $uId==0?session('WST_USER.userId'):$uId;
		$user =  Db::name('users')->where(['userId'=>$userId])->field("distributMoney")->find();
		$cnt = Db::name('distribut_users')->where(['parentId'=>$userId])->count();
		$user["userCnt"] = $cnt;
		return $user;
	}

	/**
	 * 根据指定的商品分类获取其路径
	 */
	function WSTGoodsCat(){
		$rs = Db::table('__GOODS_CATS__')->where(['isShow'=>1,'dataFlag'=>1,'parentId'=>0])->field("parentId,catName,catId")->select();
		return $rs;
	}
	
	public function checkPayments($carts){
		foreach ($carts as $key1 => $shop){
			if($shop["list"]){
				for($i=0,$k=count($shop["list"]);$i<$k;$i++){
					$goods = $shop["list"][$i];
					$goodsId = $goods["goodsId"];
					$row = Db::name('goods')->where(['goodsId'=>$goodsId])->field("isDistribut")->find();
					if($row["isDistribut"]){
						return true;
					}
				}
			}
		}
		return false;
	}
	/**
	 * 检查订单价格
	 */
	public function checkEditOrderMoney($obj){
		$orderId = (int)$obj['orderId'];
		$orderMoney = (float)$obj['orderMoney'];
		$order = Db::name("orders")->where(["orderId"=>$orderId])->field("totalCommission,commissionFee")->find();
		if($order["totalCommission"]>$orderMoney){
			exit( json_encode(WSTReturn('订单价格不能低于￥'.WSTBCMoney($order["totalCommission"],$order["commissionFee"]),"-1")));
		}
	}


	/*
    * 生成分享海报
    */
    public function createPoster($userId,$qr_code,$outImg){
        $cfg = self::getAddonConfig();
        $user = Db::name("users")->where(["userId"=>$userId])->find();
        //生成二维码图片   
        $share_bg = WSTRootPath().'/'.$cfg["posterBg"];
        $share_bg = imagecreatefromstring(file_get_contents($share_bg));
        $new_qrcode = imagecreatefromstring(file_get_contents($qr_code));

        $share_width = imagesx($share_bg);//二维码图片宽度   
        $share_height = imagesy($share_bg);//二维码图片高度   
        $new_width = imagesx($new_qrcode);//logo图片宽度   
        $new_height = imagesy($new_qrcode);//logo图片高度   
        $new_qr_width = $share_width / 2;   
        $new_qr_height = $new_qr_width; 
        $from_width = ($share_width - $new_qr_width) / 2;
       
        //重新组合图片并调整大小   
        imagecopyresampled($share_bg, $new_qrcode, $from_width, 564, 0, 0, $new_qr_width,   
        $new_qr_height, $new_width, $new_height); 
        imagedestroy($new_qrcode);

        $new_qrcode = WSTUserPhoto($user["userPhoto"]);
        if(substr($new_qrcode,0,4)!='http' && $new_qrcode){
        	$new_qrcode = WSTRootPath().'/'.($user["userPhoto"]?$user["userPhoto"]:WSTConf('CONF.userLogo'));
        }
        $new_qrcode = imagecreatefromstring(file_get_contents($new_qrcode));

        $new_width = imagesx($new_qrcode);//logo图片宽度   
        $new_height = imagesy($new_qrcode);//logo图片高度   
        $new_qr_width = 150;   
        $new_qr_height = $new_qr_width; 
        $from_width = 80;  
        //重新组合图片并调整大小   
        imagecopyresampled($share_bg, $new_qrcode, $from_width, 80, 0, 0, $new_qr_width,   
        $new_qr_height, $new_width, $new_height); 

        // 字体文件
        $textcolor = imagecolorallocate($share_bg,50,50,50);
        $textcolor2 = imagecolorallocate($share_bg,100,100,100);
        $font = WSTRootPath().'/extend/verify/verify/ttfs/SourceHanSerifCN-Medium.otf';
        
       
        //$text = mb_convert_encoding('长按识别微信二维码', "html-entities", "utf-8"); //转成html编码
        imagettftext($share_bg, 22, 0, 245, 1040, $textcolor, $font, '长按识别微信二维码');

        $text = $this->autowrap(30, 0, $font, $user["userName"],400); 
        imagettftext($share_bg, 28, 0, 250, 130, $textcolor, $font, $text);

        //输出图片
        $shareImg = WSTRootPath().'/'.$outImg;
        imagepng($share_bg, $shareImg);
        imagedestroy($new_qrcode);
    	imagedestroy($share_bg);
    	unlink($qr_code);
        return WSTReturn("",1,["shareImg"=>$outImg]);
    }

    /**
    * 文字自动换行
    * @param  [type] $fontsize    [字体大小]
    * @param  [type] $angle       [角度]
    * @param  [type] $fontface    [字体名称]
    * @param  [type] $string      [字符串]
    * @param  [type] $width       [预设宽度]
    */
    public function autowrap($fontsize, $angle, $fontface, $string, $width) {
        $content = "";
        // 将字符串拆分成一个个单字 保存到数组 letter 中
        preg_match_all("/./u", $string, $arr);
        $letter = $arr[0];
        foreach ($letter as $l) {
            $teststr = $content." ".$l;
            $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
            // 判断拼接后的字符串是否超过预设的宽度
            if (($testbox[2] > $width) && ($content !== "")) {
                $content .= PHP_EOL;
            }
            $content .= $l;
        }
        return $content;
    }


    /**
	 * 结算佣金统计报表
	 */
	public function adminDistributMoneysByPage(){
		$where = array();
		$start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
        $end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
		$rs = Db::name('distribut_moneys')->alias("dm")
				->join("__USERS__ u","u.userId=dm.userId")
				->join("__ORDERS__ o","o.orderId=dm.orderId")
				->whereTime('dm.createTime','between',[$start,$end])
				->field("u.userId,u.userName,u.loginName,dm.moneyId,dm.money,dm.remark,dm.distributMoney,dm.createTime,dm.distributType,o.orderId,o.orderNo")
				->order('dm.moneyId', 'desc')
				->paginate(input('limit/d'))->toArray();
		$totalMoney = Db::name('distribut_moneys')->alias("dm")
				->join("__USERS__ u","u.userId=dm.userId")
				->join("__ORDERS__ o","o.orderId=dm.orderId")
				->whereTime('dm.createTime','between',[$start,$end])
				->sum("dm.distributMoney");
		foreach ($rs['data'] as $key => $v){
			$rs['data'][$key]['userName'] = $rs['data'][$key]['userName']!=""?$rs['data'][$key]['userName']:$rs['data'][$key]['loginName'];
			$rs['data'][$key]['totalMoney'] = $totalMoney;
		}
		return $rs;
	}


	/**
     * 导出结算佣金统计报表excel
     */
    public function toExportDistributMoneys(){
        $name='report';
        $start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
        $end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
        $where = [];
        $where[] = ["us.dataSrc","=",1];
        $where[] = ["us.scoreType","=",0];
        $rs =  $start = date('Y-m-d 00:00:00',strtotime(input('startDate')));
        $end = date('Y-m-d 23:59:59',strtotime(input('endDate')));
		$rs = Db::name('distribut_moneys')->alias("dm")
				->join("__USERS__ u","u.userId=dm.userId")
				->join("__ORDERS__ o","o.orderId=dm.orderId")
				->whereTime('dm.createTime','between',[$start,$end])
				->field("u.userId,u.userName,u.loginName,dm.moneyId,dm.money,dm.remark,dm.distributMoney,dm.createTime,dm.distributType,o.orderId,o.orderNo")
                ->select();
        $totalMoney = Db::name('distribut_moneys')->alias("dm")
				->join("__USERS__ u","u.userId=dm.userId")
				->join("__ORDERS__ o","o.orderId=dm.orderId")
				->whereTime('dm.createTime','between',[$start,$end])
				->sum("dm.distributMoney");
        foreach($rs as $k=>$v){
            $rs[$k]['userName'] = ($v['userName']!="")?$v['userName']:$v['loginName'];
        } 
        
        require Env::get('root_path') . 'extend/phpexcel/PHPExcel/IOFactory.php';
        $objPHPExcel = new \PHPExcel();
        // 设置excel文档的属性
        $objPHPExcel->getProperties()->setCreator("WSTMart")//创建人
        ->setLastModifiedBy("WSTMart")//最后修改人
        ->setTitle($name)//标题
        ->setSubject($name)//题目
        ->setDescription($name)//描述
        ->setKeywords("结算佣金统计报表");//种类
        // 开始操作excel表
        $objPHPExcel->setActiveSheetIndex(0);
        // 设置工作薄名称
        $objPHPExcel->getActiveSheet()->setTitle(iconv('gbk', 'utf-8', 'Sheet'));
        // 设置默认字体和大小
        $objPHPExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', ''));
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(11);
        $styleArray = array(
                'font' => array(
                        'bold' => true,
                        'color'=>array(
                                'argb' => 'ffffffff',
                        )
                )
        );
        //设置宽
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(90);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $objRow = $objPHPExcel->getActiveSheet()->getStyle('A2:E2');
        $objRow->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objRow->getFill()->getStartColor()->setRGB('666699');
        $objRow->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objRow->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);   
        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25);



        $objPHPExcel->getActiveSheet()
			        ->setCellValue('E1', '佣金总金额:'.$totalMoney."元")
			        ->setCellValue('A2', '订单编号')
			        ->setCellValue('B2', '获佣用户')
			        ->setCellValue('C2', '佣金描述')
			        ->setCellValue('D2', '佣金金额')
			        ->setCellValue('E2', '记录时间');
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($styleArray);
        $i = 2;
        $totalRow = 0;
        for ($row = 0; $row < count($rs); $row++){
            $i = $row+3;
            $objPHPExcel->getActiveSheet()
            ->setCellValue('A'.$i, $rs[$row]['orderNo'])
            ->setCellValue('B'.$i, $rs[$row]['userName'])
            ->setCellValue('C'.$i, $rs[$row]['remark'])
            ->setCellValue('D'.$i, '￥'.$rs[$row]['distributMoney'])
            ->setCellValue('E'.$i, $rs[$row]['createTime']);
            $totalRow++;
        }
        $totalRow = ($totalRow==0)?1:$totalRow+2;
        $objPHPExcel->getActiveSheet()->getStyle('A2:E'.$totalRow)->applyFromArray(array(
                'borders' => array (
                        'allborders' => array (
                                'style' => \PHPExcel_Style_Border::BORDER_THIN,  //设置border样式
                                'color' => array ('argb' => 'FF000000'),     //设置border颜色
                        )
                )
        ));
        $this->PHPExcelWriter($objPHPExcel,$name);
    }


	/**
	 * 退款退货，佣金变动
	 * @param  [type] $obj [orderId]
	 * @return [type]      [serviceId]
	 */
    public function changeDistributMoney($obj){
    	$orderId = (int)$obj['orderId'];
		$serviceId = (int)$obj['serviceId'];
		$where = [];
		$where[] = ["os.id","=",$serviceId];
		$where[] = ["os.orderId","=",$orderId];
		$where[] = ["sg.dataFlag","=",1];
		$list = Db::name("order_services os")->join("service_goods sg","sg.serviceId=os.id","inner")
				->where($where)
				->field("sg.goodsId,sg.goodsSpecId,sg.goodsNum,sg.orderId")
				->select();

		foreach ($list as $key => $vo) {
			$goodsId = $vo["goodsId"];
			$goodsSpecId = $vo["goodsSpecId"];
			$goodsNum = $vo["goodsNum"];
			$gorders = Db::name("order_goods og")
					->join("distribut_moneys dm","dm.dataId=og.id","inner")
					->where(["og.orderId"=>$orderId,"og.goodsId"=>$goodsId,"og.goodsSpecId"=>$goodsSpecId,"dm.moneyStatus"=>0])
					->field("dm.moneyId,dm.shopId,dm.userId,dm.money,dm.distributMoney,dm.goodsNum")
					->select();
					
			foreach ($gorders as $key2 => $vo2) {
				$moneyId = $vo2["moneyId"];
				$shopId = $vo2["shopId"];
				$userId = $vo2["userId"];
				$o_money = $vo2["money"];
				$o_distributMoney = $vo2["distributMoney"];
				$o_goodsNum = $vo2["goodsNum"];
				$avg_money = WSTBCMoney($o_money/$o_goodsNum,0);
				$avg_distributMoney = WSTBCMoney($o_distributMoney/$o_goodsNum,0);

				//修改佣金
				$data = [];
				$tGoodsNum = $o_goodsNum -$goodsNum; 
				$data["goodsNum"] = $tGoodsNum;
				$data["money"] = WSTBCMoney($avg_money*$tGoodsNum,0);
				$data["distributMoney"] = WSTBCMoney($avg_distributMoney*$tGoodsNum,0);
				Db::name('distribut_moneys')->where("moneyId",$moneyId)->update($data);

				//修改用户冻结金额
				$backMoney = WSTBCMoney($avg_distributMoney*$goodsNum,0);
				
				Db::name('users')->where("userId",$userId)->update([
	                            'lockMoney'=>Db::raw('lockMoney-'.$backMoney),
	                            'distributMoney'=>Db::raw('distributMoney-'.$backMoney)
	                        ]);
				//修改商家未结算金额
				Db::name("shops")->where(['shopId'=>$shopId])->update([
			                    'noSettledOrderFee'=>Db::raw('noSettledOrderFee+'.$backMoney)
			                ]);
				//修改订单总佣金
				Db::name("orders")->where(['orderId'=>$orderId])->update([
	                            'totalCommission'=>Db::raw('totalCommission-'.$backMoney),
	                            'commissionFee'=>Db::raw('commissionFee-'.$backMoney)
	                        ]);
			}
			
		}
		
    }

    /**
     * 结算佣金
     * @return [type] [description]
     */
    public function dmoneySettlement($obj){
    	$orderId = (int)$obj['orderId'];
    	$where = [];
    	$where[] = ["o.dmoneyIsSettlement","=",0];
    	$where[] = ["o.orderId","=",$orderId];
    	$olist = Db::name("orders o")->join("distribut_moneys dm","o.orderId=dm.orderId","inner")
		    	->where($where)
		    	->field("dm.moneyId,dm.userId,dm.remark,dm.distributMoney,dm.dataId")
		    	->select();
		foreach ($olist as $key => $vo) {
			
			$moneyId = $vo["moneyId"];
			$distributMoney = $vo["distributMoney"];
			if($distributMoney>0){
				$userId = $vo['userId'];
				//解冻用户佣金
				Db::name("users")->where(['userId'=>$userId])->update([
		                            'lockMoney'=>Db::raw('lockMoney-'.$distributMoney),
		                            'userMoney'=>Db::raw('userMoney+'.$distributMoney)
		                        ]);
				$data = array();
				$data["targetType"] = 0;
				$data["targetId"] = $userId;
				$data["remark"] = "获得".$vo["remark"]."佣金 ¥".$distributMoney;
				$data["dataSrc"] = 10000;
				$data["dataId"] = $vo["dataId"];
				$data["money"] = $distributMoney;
				$data["tradeNo"] = 0;
				$data["payType"] = 0;
				$data["moneyType"] = 1;
				$data["createTime"] = date('Y-m-d H:i:s');
				Db::name('log_moneys')->insert($data);
			}
			Db::name("distribut_moneys")->where(["moneyId"=>$moneyId])->update(['moneyStatus'=>1]);
		}
		//关闭结算
    	Db::name("orders o")->where(['orderId'=>$orderId])->update(['dmoneyIsSettlement'=>1]);
		return WSTReturn("操作成功", 1);
		
    }

}
