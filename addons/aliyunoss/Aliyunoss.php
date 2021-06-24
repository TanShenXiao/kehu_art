<?php
namespace addons\aliyunoss;  // 注意命名空间规范
use think\addons\Addons;
use addons\aliyunoss\model\Aliyunoss as DM;

/**
 * WSTMart 阿里云-对象存储OSS
 * @author WSTMart
 */
class Aliyunoss extends Addons{
    // 该插件的基础信息
    public $info = [
        'name' => 'Aliyunoss',   // 插件标识
        'title' => '阿里云-对象存储服务',  // 插件名称
        'description' => '对象存储服务OSS，将本站文件存储在阿里云上',    // 插件简介
        'status' => 0,  // 状态
        'author' => 'WSTMart',
        'version' => '1.0.0'
    ];

	
    /**
     * 插件安装方法
     * @return bool
     */
    public function install(){
        $m = new DM();
        $flag = $m->install();
    	WSTClearAllCache();
        return $flag;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall(){
        $m = new DM();
        $flag = $m->uninstall();
    	WSTClearAllCache();
        return $flag;
    }
    
	/**
     * 插件启用方法
     * @return bool
     */
    public function enable(){
    	$m = new DM();
        $flag = $m->enableConfig(true);
        WSTClearAllCache();
        return true;
    }
    
    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable(){
    	$m = new DM();
        $flag = $m->enableConfig(false);
        WSTClearAllCache();
    	return true;
    }

    /**
     * 插件设置方法
     * @return bool
     */
    public function saveConfig(){
    	WSTClearHookCache();
        cache('aliyun_oss',null);
    	return true;
    }

    /**
     * 商城配置页面
     */
    public function adminDocumentSysConfig(){
        $dm = new DM();
        $data = $dm->getConfig();
        $this->assign("data",$data);
        return $this->fetch('view/admin/config');
    }
    
    /**
     * 上传图片
     */
    function afterUploadPic($params){
       $dm = new DM();
       $dm->upload($params);
       return true;
    }
    
    /**
     * 删除图片
     */
    function delPic($params){
       $dm = new DM();
       $dm->del($params);
       return true;
    }
}