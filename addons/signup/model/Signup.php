<?php
namespace addons\signup\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 
 * 在线报名
 *
 */
class Signup extends Base{
	public function getConfigs(){
		$data = cache('signup');
		if(!$data){
			$rs = Db::name('addons')->where('name','Signup')->field('config')->find();
		    $data =  json_decode($rs['config'],true);
		    cache('signup',$data,31622400);
		}
		return $data;
	}
	
    /***
     * 安装插件
     */
    public function installMenu(){
    	Db::startTrans();
		try{
			$hooks = ['signup'];
			$this->bindHoods("signup", $hooks);
			//管理员后台
			$rs = Db::name('menus')->insert(["parentId"=>15,"menuName"=>"线上报名","menuSort"=>10,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"signup","menuIcon"=>"user-plus"]);
			if($rs!==false){
				$parentId = Db::name('menus')->getLastInsID();
				$rs = Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"报名项目管理","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"signup","menuIcon"=>"id-card-o"]);
				if($rs!==false){
					$datas = [];
					$pId = Db::name('menus')->getLastInsID();
					$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"SIGNUP_00","privilegeName"=>"查看线上报名","isMenuPrivilege"=>0,"privilegeUrl"=>"","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1];
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"SIGNUP_BMXZ","privilegeName"=>"增加报名项目","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/signup-cats-pageCatsByAdmin","otherPrivilegeUrl"=>"/addon/groupon-goods-pageQueryByAdmin,/addon/groupon-goods-pageAuditQueryByAdmin","dataFlag"=>1,"isEnable"=>1];
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"SIGNUP_BMXG","privilegeName"=>"修改报名项目","isMenuPrivilege"=>0,"privilegeUrl"=>"","otherPrivilegeUrl"=>"/addon/groupon-goods-allow,/addon/groupon-goods-illegal","dataFlag"=>1,"isEnable"=>1];
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"SIGNUP_BMSC","privilegeName"=>"删除报名项目","isMenuPrivilege"=>0,"privilegeUrl"=>"/addon/groupon-goods-delByAdmin","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1];
					Db::name('privileges')->insertAll($datas);
				}
				$rs = Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"报名字段管理","menuSort"=>2,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"signup","menuIcon"=>"tasks"]);
				if($rs!==false){
					$datas = [];
					$pId = Db::name('menus')->getLastInsID();
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"SIGNUP_ZDXZ","privilegeName"=>"增加报名字段","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/signup-extras-pageExtrasByAdmin","otherPrivilegeUrl"=>"/addon/groupon-goods-pageQueryByAdmin,/addon/groupon-goods-pageAuditQueryByAdmin","dataFlag"=>1,"isEnable"=>1];
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"SIGNUP_ZDXG","privilegeName"=>"修改报名字段","isMenuPrivilege"=>0,"privilegeUrl"=>"","otherPrivilegeUrl"=>"/addon/groupon-goods-allow,/addon/groupon-goods-illegal","dataFlag"=>1,"isEnable"=>1];
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"SIGNUP_ZDSC","privilegeName"=>"删除报名字段","isMenuPrivilege"=>0,"privilegeUrl"=>"/addon/groupon-goods-delByAdmin","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1];
					Db::name('privileges')->insertAll($datas);
				}
				$rs = Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"线上报名查看","menuSort"=>3,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"signup","menuIcon"=>"list-ol"]);
				if($rs!==false){
					$datas = [];
					$pId = Db::name('menus')->getLastInsID();
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"SIGNUP_BMCK","privilegeName"=>"查看线上报名","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/signup-lists-pageListsByAdmin","otherPrivilegeUrl"=>"/addon/groupon-goods-pageQueryByAdmin,/addon/groupon-goods-pageAuditQueryByAdmin","dataFlag"=>1,"isEnable"=>1];
					Db::name('privileges')->insertAll($datas);
				}
			}
			
			$now = date("Y-m-d H:i:s");
			//商家中心
			//Db::name('home_menus')->insert(["parentId"=>77,"menuName"=>"线上报名","menuUrl"=>"addon/groupon-shops-groupon","menuOtherUrl"=>"addon/groupon-shops-groupon,addon/groupon-shops-pageQuery,addon/groupon-shops-searchGoods,addon/groupon-shops-edit,addon/groupon-shops-toEdit,addon/groupon-shops-del","menuType"=>1,"isShow"=>1,"menuSort"=>1,"dataFlag"=>1,"createTime"=>$now,"menuMark"=>"signup"]);
			//$this->addMobileBtn();
			installSql("signup");
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();echo $e;die;
	  		return false;
	   	}
    }

    /**
	 * 删除菜单
	 */
	public function uninstallMenu(){
		Db::startTrans();
		try{
			$hooks = ['signup'];
			$this->unbindHoods("signup", $hooks);
			Db::name('menus')->where(["menuMark"=>"signup"])->delete();
			Db::name('home_menus')->where(["menuMark"=>"signup"])->delete();
			Db::name('privileges')->where("privilegeCode","like","SIGNUP_%")->delete();
            //删除微信参数数据
			//$tplMsgIds = Db::name('template_msgs')->where('tplCode','in',explode(',','GROUPON_GOODS_ALLOW,GROUPON_GOODS_REJECT,WX_GROUPON_GOODS_ALLOW,WX_GROUPON_GOODS_REJECT'))
			//  ->column('id');
			//if((int)WSTConf('CONF.wxenabled')==1)Db::name('wx_template_params')->where('parentId','in',$tplMsgIds)->delete();
			uninstallSql("signup");//传入插件名
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
	* 获取报名项目列表
	*/ 
	function getCats(){
		$userId = (int)session('WST_USER.userId');
		$data = array();
		// 判断报名活动表是否存在
		$exist = Db::query('show tables like "%signup_cats"');
		if($exist){
			// 获取有效的报名清单
			$cats = Db::name('signup_cats')->where(['dataFlag'=>1])->select();
			if(!empty($cats)){
				$data['menuId'] = 'signup';
				$data['cats'] = $cats;
			}
		}
	    return $data;
	}
}
