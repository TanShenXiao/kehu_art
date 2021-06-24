<?php
namespace addons\shoptpl\model;
use think\addons\BaseModel as Base;
use think\Db;

class Shoptpl extends Base{
	/***
     * 安装插件
     */
    public function installMenu(){
    	Db::startTrans();
		try{
			$hooks = ['homeBeforeGoShopHomeTpl','homeBeforeGoSelfShopTpl'];
			$this->bindHoods("Shoptpl", $hooks);
			$now = date("Y-m-d H:i:s");
			//商家中心
			Db::name('home_menus')->insert(["parentId"=>38,"menuName"=>"店铺模板","menuUrl"=>"addon/shoptpl-shoptpl-setting","menuOtherUrl"=>"","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"shoptpl"]);
			installSql("shoptpl");
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
			$hooks = ['homeBeforeGoShopHomeTpl','homeBeforeGoSelfShopTpl'];
			$this->unbindHoods("Shoptpl", $hooks);
			Db::name('menus')->where("menuMark",'=',"shoptpl")->delete();
			Db::name('home_menus')->where("menuMark",'=',"shoptpl")->delete();
			uninstallSql("shoptpl");//传入插件名
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}

	
	/**
	 * 菜单显示隐藏
	 */
	public function toggleShow($isShow = 1){
		Db::startTrans();
		try{
			Db::name('menus')->where("menuMark",'=',"shoptpl")->update(["isShow"=>$isShow]);
			Db::name('home_menus')->where("menuMark",'=',"shoptpl")->update(["isShow"=>$isShow]);
			Db::commit();
			return true;
		}catch (\Exception $e) {
			Db::rollback();
			return false;
		}
	}
	
	/**
	 * 获取模板列表
	 */
	public function listQuery(){
		$shopId = (int)session('WST_USER.shopId');
		$type = input('type/d');
		$tpls = Db::name('shop_templates')->where('tplType',$type)->select();
		$scfg = Db::name('shop_configs')->where('shopId',$shopId)->find();
		if($type == 0){	// 电脑端
			$tplId = $scfg['userTemplate'];
		}else{	// 移动端
			$tplId = $scfg['mobileTemplate'];
		}
		if($tplId==0){	//默认模板
			$tpls[0]['isUse'] = 1;
		}
		for($i=0;$i<sizeof($tpls);$i++){
			if($tpls[$i]['tplId']==$tplId){
				$tpls[$i]['isUse'] = 1;
				break;
			}
		}
		return $tpls;
	}
	
	//更改模板
	public function changeTpl(){
		$shopId = (int)session('WST_USER.shopId');
		$tplId = input('id/d');
		$type = input('type/d');
		if($type==null || $type==0){	//电脑端
			Db::name('shop_configs')->where('shopId',$shopId)->update(['userTemplate'=>$tplId]);
		}else{	//移动端
			Db::name('shop_configs')->where('shopId',$shopId)->update(['mobileTemplate'=>$tplId]);
		}
	}
	
	/**
	 * 获取店铺设置
	 * @return unknown
	 */
	public function getShopConf($shopId=0){
		$shopId = ($shopId>0)?$shopId:(int)session('WST_USER.shopId');
		$rs = Db::name('shop_configs')->where(["shopId"=>$shopId])->find();
		return $rs;
	}
}
