<?php
namespace addons\vote\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 
 * 在线投票
 *
 */
class Vote extends Base{
	public function getConfigs(){
		$data = cache('vote');
		if(!$data){
			$rs = Db::name('addons')->where('name','Vote')->field('config')->find();
		    $data =  json_decode($rs['config'],true);
		    cache('vote',$data,31622400);
		}
		return $data;
	}
	
	public function config(){
		$data = Db::name('vote_config')->find();
		return $data;
	}

    /***
     * 安装插件
     */
    public function installMenu(){
    	Db::startTrans();
		try{
			$hooks = ['vote'];
			$this->bindHoods("vote", $hooks);
			//管理员后台
			$rs = Db::name('menus')->insert(["parentId"=>15,"menuName"=>"在线投票","menuSort"=>10,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"vote","menuIcon"=>"user-plus"]);
			if($rs!==false){
				$parentId = Db::name('menus')->getLastInsID();
				$rs = Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"投票项目管理","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"vote","menuIcon"=>"id-card-o"]);
				if($rs!==false){
					$datas = [];
					$pId = Db::name('menus')->getLastInsID();
					$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"VOTE_00","privilegeName"=>"查看在线投票","isMenuPrivilege"=>0,"privilegeUrl"=>"","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1];
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"VOTE_TPXZ","privilegeName"=>"增加投票项目","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/vote-cats-pageCatsByAdmin","otherPrivilegeUrl"=>"/addon/groupon-goods-pageQueryByAdmin,/addon/groupon-goods-pageAuditQueryByAdmin","dataFlag"=>1,"isEnable"=>1];
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"VOTE_TPXG","privilegeName"=>"修改投票项目","isMenuPrivilege"=>0,"privilegeUrl"=>"","otherPrivilegeUrl"=>"/addon/groupon-goods-allow,/addon/groupon-goods-illegal","dataFlag"=>1,"isEnable"=>1];
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"VOTE_TPSC","privilegeName"=>"删除投票项目","isMenuPrivilege"=>0,"privilegeUrl"=>"/addon/groupon-goods-delByAdmin","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1];
					Db::name('privileges')->insertAll($datas);
				}
				$rs = Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"投票项管理","menuSort"=>2,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"vote","menuIcon"=>"tasks"]);
				if($rs!==false){
					$datas = [];
					$pId = Db::name('menus')->getLastInsID();
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"VOTE_ZDXZ","privilegeName"=>"增加投票项","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/vote-items-pageItemsByAdmin","otherPrivilegeUrl"=>"/addon/groupon-goods-pageQueryByAdmin,/addon/groupon-goods-pageAuditQueryByAdmin","dataFlag"=>1,"isEnable"=>1];
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"VOTE_ZDXG","privilegeName"=>"修改投票项","isMenuPrivilege"=>0,"privilegeUrl"=>"","otherPrivilegeUrl"=>"/addon/groupon-goods-allow,/addon/groupon-goods-illegal","dataFlag"=>1,"isEnable"=>1];
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"VOTE_ZDSC","privilegeName"=>"删除投票项","isMenuPrivilege"=>0,"privilegeUrl"=>"/addon/groupon-goods-delByAdmin","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1];
					Db::name('privileges')->insertAll($datas);
				}
				$rs = Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"在线投票查看","menuSort"=>3,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"vote","menuIcon"=>"list-ol"]);
				if($rs!==false){
					$datas = [];
					$pId = Db::name('menus')->getLastInsID();
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"VOTE_TPCK","privilegeName"=>"查看在线投票明细","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/vote-lists-pageListsByAdmin","otherPrivilegeUrl"=>"/addon/groupon-goods-pageQueryByAdmin,/addon/groupon-goods-pageAuditQueryByAdmin","dataFlag"=>1,"isEnable"=>1];
					Db::name('privileges')->insertAll($datas);
				}
			}
			
			$now = date("Y-m-d H:i:s");
			//商家中心
			//Db::name('home_menus')->insert(["parentId"=>77,"menuName"=>"线上报名","menuUrl"=>"addon/groupon-shops-groupon","menuOtherUrl"=>"addon/groupon-shops-groupon,addon/groupon-shops-pageQuery,addon/groupon-shops-searchGoods,addon/groupon-shops-edit,addon/groupon-shops-toEdit,addon/groupon-shops-del","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"vote"]);
			//$this->addMobileBtn();
			installSql("vote");
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
			$hooks = ['vote'];
			$this->unbindHoods("vote", $hooks);
			Db::name('menus')->where(["menuMark"=>"vote"])->delete();
			Db::name('home_menus')->where(["menuMark"=>"vote"])->delete();
			Db::name('privileges')->where("privilegeCode","like","VOTE_%")->delete();
            //删除微信参数数据
			//$tplMsgIds = Db::name('template_msgs')->where('tplCode','in',explode(',','GROUPON_GOODS_ALLOW,GROUPON_GOODS_REJECT,WX_GROUPON_GOODS_ALLOW,WX_GROUPON_GOODS_REJECT'))
			//  ->column('id');
			//if((int)WSTConf('CONF.wxenabled')==1)Db::name('wx_template_params')->where('parentId','in',$tplMsgIds)->delete();
			uninstallSql("vote");//传入插件名
			//$this->delMobileBtn();
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
			Db::name('menus')->where(["menuMark"=>"groupon"])->update(["isShow"=>$isShow]);
			Db::name('home_menus')->where(["menuMark"=>"groupon"])->update(["isShow"=>$isShow]);
			Db::name('navs')->where(["navUrl"=>"index.php/addon/groupon-goods-lists.html"])->update(["isShow"=>$isShow]);
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
	* 获取投票项目列表
	*/ 
	function getCats(){
		$userId = (int)session('WST_USER.userId');
		$data = array();
		// 判断投票活动表是否存在
		$exist = Db::query('show tables like "%vote_cats"');
		if($exist){
			// 获取有效的投票项目清单
			$cats = Db::name('vote_cats')->where(['dataFlag'=>1])->select();
			if(!empty($cats)){
				$data['menuId'] = 'vote';
				$data['cats'] = $cats;
			}
		}
	    return $data;
	}
}
