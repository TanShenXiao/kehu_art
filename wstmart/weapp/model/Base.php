<?php
namespace wstmart\weapp\model;
use think\Model;
use think\Db;
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
 * 基础模型器
 */
class Base extends Model{
	/**
	 * 获取登录用户的id
	 */
	public function getUserId(){
		$tokenId = input('tokenId');
		if($tokenId=='')return 0;
		$where = [];
		$where['w.tokenId'] = $tokenId;
		$rs = Db::name('weapp_session w')->join('users u','w.userId=u.userId','inner')
					->where($where)
					->field('u.dataFlag,u.userStatus,u.userId')
					->find();
		if($rs['dataFlag']==1 && $rs['userStatus']==1){
			return (int)$rs['userId'];
		}else{
			$userId = (int)$rs['userId'];
			Db::name('weapp_session')->where(['userId'=>$userId])->delete();
			return 0;
		}
	}
	/**
	 * 获取用户对应的shopId
	 */
	public function getShopId($userId){
		return (int)Db::name('shop_users')->where(['userId'=>$userId])->value('shopId');
	}

	/**
	 * 保存前台获取到的formId
	 */
	public function saveFormId($userId){
        $weappFormId = input('weappFormId');
        if((int)$userId>0 && $weappFormId!=''){
        	if(stripos($weappFormId,',')!==false){
                $str = explode(',',$weappFormId);
                $datas = [];
                for($i=0;$i<count($str);$i++){
                    $data = [];
		        	$data['userId'] = $userId;
		        	$data['formId'] = $str[$i];
		        	$data['expireTime'] = date('Y-m-d H:i:s', strtotime('+7 days'));
                }
                $datas[] = $data;
                Db::name('weapp_forms')->insertAll($datas);
        	}else{
                $data = [];
	        	$data['userId'] = $userId;
	        	$data['formId'] = $weappFormId;
	        	$data['expireTime'] = date('Y-m-d H:i:s', strtotime('+7 days'));
	        	Db::name('weapp_forms')->insert($data);
        	}
        }
	}
}