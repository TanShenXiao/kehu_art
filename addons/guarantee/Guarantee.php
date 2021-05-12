<?php
namespace addons\guarantee;

use think\addons\Addons;
use addons\guarantee\model\Guarantee as DM;

/**
 * 保底交易
 * @author mnTech
 */
class Guarantee extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Guarantee',   // 插件标识
        'title' => '保底交易',  // 插件名称
        'description' => '保底交易',    // 插件简介
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
        cache('guarantee',null);
    	cache('hooks',null);
    	return true;
    }
}