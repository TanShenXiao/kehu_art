<?php
namespace addons\vote;


use think\addons\Addons;
use addons\vote\model\Vote as DM;

/**
 * 在线报名
 * @author mnTech
 */
class Vote extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Vote',   // 插件标识
        'title' => '在线投票',  // 插件名称
        'description' => '在线投票',    // 插件简介
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
    	WSTClearHookCache();
    	cache('hooks',null);
        return true;
    }
    
    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable(){
    	WSTClearHookCache();
    	cache('hooks',null);
    	return true;
    }

    /**
     * 插件设置方法
     * @return bool
     */
    public function saveConfig(){
    	WSTClearHookCache();
        cache('vote',null);
    	cache('hooks',null);
    	return true;
    }
}