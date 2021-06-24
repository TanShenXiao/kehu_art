<?php
namespace addons\shoptpl;  // 注意命名空间规范

use think\addons\Addons;
use addons\shoptpl\model\Shoptpl as DM;
use think\Db;

/**
 * 店铺自定义模板插件
 */
class Shoptpl extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Shoptpl',   // 插件标识
        'title' => '店铺自定义模板',  // 插件名称
        'description' => '店铺自定义模板插件',    // 插件简介
        'status' => 0,  // 状态
        'author' => '码牛科技',
        'version' => '1.0.0'
    ];

	
    /**
     * 插件安装方法
     * @return bool
     */
    public function install(){
    	$m = new DM();
    	$flag = $m->installMenu();
    	WSTClearHookCache();
    	return $flag;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall(){
        $m = new DM();
        $flag = $m->uninstallMenu();
        WSTClearHookCache();
        return $flag;
    }
    
    /**
     * 插件启用方法
     * @return bool
     */
    public function enable(){
        $m = new DM();
        $flag = $m->toggleShow(1);
        WSTClearHookCache();
        return $flag;
    }
    
    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable(){
        $m = new DM();
        $flag = $m->toggleShow(0);
        WSTClearHookCache();
        return $flag;
    }

    /**
     * 插件设置方法
     * @return bool
     */
    public function saveConfig(){
        WSTClearHookCache();
        return true;
    }
    /**
     * 跳去店铺首页前执行
     */
    public function homeBeforeGoShopHomeTpl($params){
        $m = new DM();
        $shopId = (int)$params["shopId"];
		$type = (int)$params["type"];
        $shopId = ($shopId>0)?$shopId:1;
        $conf = $m->getShopConf($shopId);
		if($type==0){	//电脑端
			$tplId = $conf["userTemplate"];
		}else{	//移动端
			$tplId = $conf["mobileTemplate"];
		}
        if($tplId>0){
			$rs = Db::name('shop_templates')->where('tplId',$tplId)->find();
			if($rs['tplPath']==0)	//默认模板
				return;
        	echo $params["obj"]->hookFetch('shops/tpl/'.$rs['tplPath'].'/shop_home');
        	exit();
        }
    }
    /**
     * 跳去自营店铺首页前执行
     */
    public function homeBeforeGoSelfShopTpl($params){
    	$m = new DM();
    	$shopId = (int)$params["shopId"];
        $shopId = ($shopId>0)?$shopId:1;
    	$conf = $m->getShopConf($shopId);
		$tplId = $conf["userTemplate"];
    	if($tplId>0){
			$rs = Db::name('shop_templates')->where('tplId',$tplId)->find();
    		echo $this->fetch('view/home/shops/tpl/'.$rs['tplPath'].'/shop_home');
    		exit();
    	}
    }
}