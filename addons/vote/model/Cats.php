<?php
namespace addons\vote\model;
use think\addons\BaseModel as Base;
use think\Db;
/**
 * 
 * 在线报名
 *
 */
class Cats extends Base{
	/**
	 * 管理员查看线上投票项目
	 */
	public function pageQueryCatsByAdmin(){
		$startDate = input('startDate');
		$endDate = input('endDate');
		$catName = input('catName');
		$where[] = ['dataFlag','<>',-1];
		$where[] = ['catName','like','%'.$catName.'%'];
		if($startDate!='' && $endDate!=''){
			$where[] = ['startDate','between time',[$startDate,$endDate]];
		}else if($startDate!=''){
			$where[] = ['startDate','>= time',$startDate];
		}else if($endDate!=''){
			$where[] = ['startDate','<= time',$endDate];
		}
		$where[] = ['dataFlag','<>',-1];
		$where[] = ['catName','like','%'.$catName.'%'];
        $page =  Db::name('vote_cats')->order('startDate desc')->where($where)
                    ->paginate(input('limit/d'))->toArray();
        if(count($page['data'])>0){
        	$today = date('Y-m-d');
        	foreach($page['data'] as $key =>$v){
        		//$page['data'][$key]['catName'] = $v['catName'];
        		//$page['data'][$key]['catDesc'] = $v['catDesc'];
        		if(strtotime($v['startDate'])<=strtotime($today) && strtotime($v['endDate'])>=strtotime($today)){
        			$page['data'][$key]['status'] = 1; 
        		}else if(strtotime($v['startDate'])>strtotime($today)){
                    $page['data'][$key]['status'] = 0; 
        		}else{
        			$page['data'][$key]['status'] = -1; 
        		}
        	}
        }
        return $page;
	}
	
	public function pageQueryCats(){
		$catName = input('catName');
		$where[] = ['dataFlag','<>',-1];
		$where[] = ['catName','like','%'.$catName.'%'];
		$status = input('status/d');
		
		if($status==-1){	// 已过期
			$where[] = ["endDate",'exp',Db::raw("<date(now())")];
		}else if($status==0){	// 未开始
			$where[] = ["startDate",'exp',Db::raw(">date(now())")];
		}else if($status==1){	// 进行中
			$where[] = ["startDate",'exp',Db::raw("<=date(now())")];
			$where[] = ["endDate",'exp',Db::raw(">=date(now())")];
		}
        $page =  Db::name('vote_cats')->order('catSort,startDate')->where($where)
                    ->paginate(input('pagesize/d'))->toArray();
        if(count($page['data'])>0){
        	$today = date('Y-m-d');
        	foreach($page['data'] as $key =>$v){
        		//$page['data'][$key]['catName'] = $v['catName'];
        		//$page['data'][$key]['catDesc'] = $v['catDesc'];
        		if(strtotime($v['startDate'])<=strtotime($today) && strtotime($v['endDate'])>=strtotime($today)){
        			$page['data'][$key]['status'] = 1; 
        		}else if(strtotime($v['startDate'])>strtotime($today)){
                    $page['data'][$key]['status'] = 0; 
        		}else{
        			$page['data'][$key]['status'] = -1; 
        		}
        	}
        }
        return $page;
	}
	
	/**
	 * 管理员编辑线上投票项目
	 */
	public function editCat(){
		$data = input('post.');
		if(empty($data['catName']) || empty($data['startDate']) || empty($data['endDate'])){
			return WSTReturn('请将信息补充完整',-1);
		}
		WSTUnset($data,'dataFlag,status');
		Db::startTrans();
		try{
			$result = Db::name('vote_cats')->where('catId',$data['catId'])->update($data);
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
	
	public function addCat(){
		$data = input('post.');
		//$data['adSort'] = (int)$data['adSort'];
		//WSTUnset($data,'adId');
		if(empty($data['catName']) || empty($data['startDate']) || empty($data['endDate'])){
			return WSTReturn('请将信息补充完整',-1);
		}
		$data['dataFlag'] = 1;
		Db::startTrans();
		try{
			$result = Db::name('vote_cats')->insert($data);
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
	 * 删除
	 */
	public function delCatByAdmin(){
		$id = (int)input('id');
        $rs = Db::name('vote_cats')->where('catId',$id)->update(['dataFlag'=>-1]);
        return WSTReturn('删除成功',1);
	}
	
	/**
	 *  获取报名项目
	 */
	public function getCatById($id){
		$where = [];
		$where['catId'] = (int)$id;
		$where['dataFlag'] = 1;
		$rs = Db::name('vote_cats')->where($where)->find();
        $today = date('Y-m-d');
		if(strtotime($rs['startDate'])<=strtotime($today) && strtotime($rs['endDate'])>=strtotime($today)){
			$rs['status'] = 1; 
		}else if(strtotime($rs['startDate'])>strtotime($today)){
			$rs['status'] = 0; 
		}else{
			$rs['status'] = -1; 
		}
		if(!empty($rs)){
			$rs['catDesc'] = htmlspecialchars_decode($rs['catDesc']);
		}
		return $rs;
	}
	
	/**
	 * 管理员查看在线投票字段
	 */
	public function listCats(){
		$where[] = ['dataFlag','<>',-1];
		$where[] = ["startDate",'exp',Db::raw("<=date(now())")];
		$where[] = ["endDate",'exp',Db::raw(">=date(now())")];
		$rs = Db::name('vote_cats')->where($where)->select();
        return $rs;
	}
	
	/**
	 * 获取所有投票项目
	 */
	public function getCats(){
		$where[] = ['dataFlag','<>',-1];
		//$where[] = ["date(startDate)",'exp',"<=date(now())"];
		//$where[] = ["date(endDate)",'exp',">=date(now())"];
        $rs =  Db::name('vote_cats')->order('catSort,startDate')->where($where)->select();
        if(count($rs)>0){
        	$today = date('Y-m-d');
        	foreach($rs as $key =>$v){
				$rs[$key]['catDesc'] = htmlspecialchars_decode($v['catDesc']);
        		if(strtotime($v['startDate'])<=strtotime($today) && strtotime($v['endDate'])>=strtotime($today)){
        			$rs[$key]['status'] = 1; 
        		}else if(strtotime($v['startDate'])>strtotime($today)){
                    $rs[$key]['status'] = 0; 
        		}else{
        			$rs[$key]['status'] = -1; 
        		}
        	}
        }
        return $rs;
	}
	
	/**
	 * 获取投票总次数
	 */
	public function getTotalVotes(){
		$rs = Db::name('vote_config')->find();
		return $rs['totalVotes'];
	}
}
