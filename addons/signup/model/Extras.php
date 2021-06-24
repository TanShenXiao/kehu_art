<?php
namespace addons\signup\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 
 * 在线报名
 *
 */
class Extras extends Base{
	/**
	 * 管理员查看线上报名字段
	 */
	public function pageQueryExtrasByAdmin(){
		$catName = input('catName');
		$where[] = ['e.dataFlag','<>',-1];
		$where[] = ['c.catName','like','%'.$catName.'%'];
		//$where[] = ["date(startDate)",'exp',"<=date(now())"];
		$where[] = ["c.endDate",'exp',Db::raw(">=date(now())")];
        $page =  Db::name('signup_extras')->alias('e')->join('signup_cats c','e.catId=c.catId')->where($where)->order('c.catName')
                    ->paginate(input('limit/d'))->toArray();
        return $page;
	}
	
	/**
	 * 管理员编辑线上报名字段
	 */
	public function editExtra(){
		$data = input('post.');
		if(empty($data['catName']) || empty($data['extraName']) || empty($data['extraId'])){
			return WSTReturn('请将信息补充完整',-1);
		}
		WSTUnset($data,'dataFlag,isShow');
		Db::startTrans();
		try{
			$result = Db::name('signup_extras')->where('extraId',$data['extraId'])->update($data);
        	if(false !== $result){
        		WSTClearAllCache();
        		Db::commit();
        	    return WSTReturn("编辑成功", 1);
        	}else{
        		return WSTReturn($this->getError(),-1); 
        	}
		}catch (\Exception $e) {
            Db::rollback();
			return WSTReturn('编辑失败:'.$e->getMessage(),-1); 
        }
		return WSTReturn('编辑失败',-1); 
	}
	
	public function addExtra(){
		$data = input('post.');
		if(empty($data['catName']) || empty($data['extraName'])){
			return WSTReturn('请将信息补充完整',-1);
		}
		$data['dataFlag'] = 1;
		Db::startTrans();
		try{
			$result = Db::name('signup_extras')->insert($data);
        	if(false !== $result){
        		WSTClearAllCache();
        		Db::commit();
        	    return WSTReturn("新增成功", 1);
        	}else{
        		return WSTReturn($this->getError(),-1); 
        	}
		}catch (\Exception $e) {
            Db::rollback();
			return WSTReturn('新增失败:'.$e->getMessage(),-1); 
        }
		return WSTReturn('新增失败',-1); 
	}

    /**
	 * 删除报名字段
	 */
	public function delExtraByAdmin(){
		$id = (int)input('id');
        $rs = Db::name('signup_extras')->where('extraId',$id)->update(['dataFlag'=>-1]);
        return WSTReturn('删除成功',1);
	}
	
	/**
	 *  获取报名字段
	 */
	public function getExtraById($id){
		$where = [];
		$where['extraId'] = (int)$id;
		$where['dataFlag'] = 1;
		return Db::name('signup_extras')->where($where)->find();
	}
	
	/**
	 *  获取报名字段
	 */
	public function getExtrasByCatId($id){
		$where = [];
		$where['catId'] = (int)$id;
		$where['dataFlag'] = 1;
		return Db::name('signup_extras')->where($where)->select();
	}
	
	/**
	 * 管理员查看线上报名项目
	 */
	public function listCats(){
		$where[] = ['dataFlag','<>',-1];
		//$where[] = ["date(startDate)",'exp',"<=date(now())"];
		$where[] = ["endDate",'>= time',"today"];
		$rs = Db::name('signup_cats')->where($where)->select();
        return $rs;
	}
	
	/**
	 * 获取会员报名扩展信息
	 */
	public function getUserExtrasVal($listId){
		$where[] = ['dataFlag','=',1];
		$where[] = ['listId','=',$listId];
		$rs = Db::name('signup_lists_extras')->where($where)->select();
		$data = array();
		foreach($rs as $r) {
			$data[$r['extraId']]['extraName'] = $r['extraName'];
			$data[$r['extraId']]['extraVal'] = $r['extraVal'];
		}
		return $data;
	}
}
