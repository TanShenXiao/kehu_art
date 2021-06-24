<?php
namespace addons\signup;

use think\addons\Addons;
use addons\signup\model\Signup as DM;

/**
 * 在线报名
 * @author mnTech
 */
class Signup extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Signup',   // 插件标识
        'title' => '线上报名',  // 插件名称
        'description' => '线上报名',    // 插件简介
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
    	cache('hooks',null);
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
    	cache('hooks',null);
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
        cache('hooks',null);
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
        cache('hooks',null);
        return $flag;
    }

    /**
     * 插件设置方法
     * @return bool
     */
    public function saveConfig(){
    	WSTClearHookCache();
        cache('signup',null);
    	cache('hooks',null);
    	return true;
    }
}