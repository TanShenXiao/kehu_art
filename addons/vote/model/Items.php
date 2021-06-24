<?php
namespace addons\vote\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 
 * 在线投票
 *
 */
class Items extends Base{
	/**
	 * 管理员查看在线投票项目
	 */
	public function pageQueryItemsByAdmin(){
		$catName = input('catName');
		$itemName = input('itemName');
		$where[] = ['dataFlag','<>',-1];
		$where[] = ['catName','like','%'.$catName.'%'];
		$where[] = ['itemName','like','%'.$itemName.'%'];
        $page =  Db::name('vote_items')->where($where)->order('catName')
                    ->paginate(input('limit/d'))->toArray();
        return $page;
	}
	
	// 前端查看在线投票项
	public function pageQueryItems(){
		$catId = input('catId/d');
		$where[] = ['dataFlag','<>',-1];
		$where[] = ['catId','=',$catId];
        $page =  Db::name('vote_items')->where($where)->order('catName')
                    ->paginate(input('limit/d'))->toArray();
        return $page;
	}
	
	/**
	 * 管理员编辑在线投票项目
	 */
	public function editItem(){
		$data = input('post.');
		if(empty($data['catName']) || empty($data['itemName']) || empty($data['itemId'])){
			return WSTReturn('请将信息补充完整',-1);
		}
		WSTUnset($data,'dataFlag,isShow');
		Db::startTrans();
		try{
			$result = Db::name('vote_items')->where('itemId',$data['itemId'])->update($data);
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
	
	public function addItem(){
		$data = input('post.');
		if(empty($data['catName']) || empty($data['itemName'])){
			return WSTReturn('请将信息补充完整',-1);
		}
		$data['dataFlag'] = 1;
		Db::startTrans();
		try{
			$result = Db::name('vote_items')->insert($data);
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
	 * 删除投票项目
	 */
	public function delItemByAdmin(){
		$id = (int)input('id');
        $rs = Db::name('vote_items')->where('itemId',$id)->update(['dataFlag'=>-1]);
        return WSTReturn('删除成功',1);
	}
	
	/**
	 *  获取投票项目
	 */
	public function getItemById($id){
		$where = [];
		$where['itemId'] = (int)$id;
		$where['dataFlag'] = 1;
		return Db::name('vote_items')->where($where)->find();
	}
	
	/**
	 *  获取投票项目
	 */
	public function getItemByCatId($id){
		$where = [];
		$where['catId'] = (int)$id;
		$where['dataFlag'] = 1;
		return Db::name('vote_items')->where($where)->select();
	}
	
	/**
	 * 管理员查看在线投票项目
	 */
	public function listCats(){
		$where[] = ['dataFlag','<>',-1];
		//$where[] = ["date(startDate)",'exp',"<=date(now())"];
		$where[] = ["endDate",'>= time',"today"];
		$rs = Db::name('vote_cats')->where($where)->select();
        return $rs;
	}
}
