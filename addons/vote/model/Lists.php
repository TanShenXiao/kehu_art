<?php
namespace addons\vote\model;
use think\addons\BaseModel as Base;
use think\Db;
use Env;

/**
 * 
 * 在线投票
 *
 */
class Lists extends Base{
	/**
	 * 管理员查看线上报名项目
	 */
	public function pageQueryByAdmin(){
		$catName = input('catName');
		$itemName = input('itemName');
		$where[] = ['l.dataFlag','<>',-1];
		$where[] = ['l.catName','like','%'.$catName.'%'];
		$where[] = ['l.itemName','like','%'.$itemName.'%'];
        $page =  Db::name('vote_lists')->alias('l')
					->join('vote_items i','l.itemId=i.itemId')
					->field('listId,l.catId,l.catName,l.itemId,l.itemName,userId,userName,l.createTime,count(l.itemId) voteNum,i.itemImage')
					->order('voteNum desc')->where($where)->group('l.itemId')
                    ->paginate(input('limit/d'))->toArray();
        return $page;
	}
	
	/**
	 * 管理员查看在线投票明细
	 */
	public function pageListByItem(){
		$userName = input('userName');
		$itemId = input('itemId');
		$where[] = ['dataFlag','<>',-1];
		$where[] = ['userName','like','%'.$userName.'%'];
		$where[] = ['itemId','=',$itemId];
        $page =  Db::name('vote_lists')->order('createTime')->where($where)
                    ->paginate(input('limit/d'))->toArray();
        return $page;
	}
	
	/**
	 * 管理员编辑在线投票项目
	 */
	public function editCat(){
		$data = input('post.');
		if(empty($data['catName']) || empty($data['startDate']) || empty($data['endDate'])){
			return WSTReturn('请将信息补充完整',-1);
		}
		WSTUnset($data,'dataFlag,catSort,status');
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
		return Db::name('vote_cats')->where($where)->find();
	}
	
	/**
	 * 管理员查看线上报名字段
	 */
	public function listCats(){
		$where[] = ['dataFlag','<>',-1];
		$where[] = ["startDate",'exp',Db::raw("<=date(now())")];
		$where[] = ["endDate",'exp',Db::raw(">=date(now())")];
		$rs = Db::name('vote_cats')->where($where)->select();
        return $rs;
	}
	/**
	 * 新增一次投票
	 */
	public function addList(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0)
			return WSTReturn('您尚未登录系统，请先登录系统',-2);
		$data = input('post.');
		if(empty($data['itemId']) || empty($data['catId'])){
			return WSTReturn('未检测到itemId或catId',-1);
		}
		$data['dataFlag'] = 1;
		$data['createTime'] = date('Y-m-d H:i:s');
		$data['userId'] = $userId;
		//获取投票次数设置
		$where['catId'] = $data['catId'];
		$rs = Db::name('vote_cats')->where($where)->find();
		$uCount = $this->getUserVoteCatCount($userId,$data['catId']);
		if($uCount>=$rs['totalVotes'])
			return WSTReturn("今日您在该项目的投票次数已达上限",-1);
		$ucCount = $this->getUserVoteItemCount($userId,$data['itemId']);
		if($ucCount>=$rs['itemVotes'])
			return WSTReturn("今日您在该项的投票次数已达上限",-1);
		Db::startTrans();
		try{
			$result = Db::name('vote_lists')->insert($data);
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
	 * 是否已投票
	 */
	public function isVoted($userId,$itemId){
		$where[] = ['userId','=',$userId];
		$where[] = ['itemId','=',$itemId];
		//$where[] = ['createTime','=','today'];
		$rs = Db::name('vote_lists')->where($where)->whereTime('createTime','today')->find();
		if(empty($rs))
			return 0;
		else return 1;
	}

	/**
	 * 获取会员投票信息
	 */
	public function getUserVote($userId,$catId){
		$where[] = ['dataFlag','=',1];
		$where[] = ['catId','=',$catId];
		$where[] = ['userId','=',$userId];
		$rs = Db::name('vote_lists')->where($where)->select();
		$rs['isVoted'] = $this->isVoted($userId,$catId);
		return $rs;
	}
	
	/**
	 * 获取会员总投票次数
	 */
	public function getUserVoteCount($userId){
		$where[] = ['userId','=',$userId];
		$where[] = ['to_days(createTime)','exp','= to_days(now())'];
		$rs = Db::name('vote_lists')->where($where)->count();
		return $rs;
	}
	
	/**
	 * 获取会员某项总投票次数
	 */
	public function getUserVoteItemCount($userId,$itemId){
		$where[] = ['itemId','=',$itemId];
		$where[] = ['userId','=',$userId];
		//$where[] = ['createTime','=','today'];
		$rs = Db::name('vote_lists')->where($where)->whereTime('createTime','today')->count();
		return $rs;
	}
	
	/**
	 * 获取会员某项目投票次数
	 */
	public function getUserVoteCatCount($userId,$catId){
		$where[] = ['catId','=',$catId];
		$where[] = ['userId','=',$userId];
		//$where[] = ['createTime','=','today'];
		$rs = Db::name('vote_lists')->where($where)->whereTime('createTime','today')->count();
		return $rs;
	}
	
	/**
	 * 获取投票总次数
	 */
	public function getTotalVotes(){
		$rs = Db::name('vote_config')->find();
		return $rs['totalVotes'];
	}
	
	/**
	 * 导出投票信息
	 */
	public function toExport(){
		$name='投票清单';
		$where[] = ['l.dataFlag','=',1];
		//$orderStatus = (int)input('orderStatus',0);
		$catName = input('catQueryName');
		if(!empty($catName))
			$where[] = ['c.catName','like','%'.$catName.'%'];
		$itemName = input('itemQueryName');
		if(!empty($itemName))
			$where[] = ['c.itemName','like','%'.$itemName.'%'];
		//查询投票明细
		$lists = Db::name('vote_lists')->alias('l')->join('vote_cats c','l.catId=c.catId')->join('vote_items i','l.itemId=i.itemId')->where($where)->select();
		//投票项目清单
		$catIds = array_unique(array_column($lists,'catId'));
		require Env::get('root_path') . 'extend/phpexcel/PHPExcel/IOFactory.php';
		$objPHPExcel = new \PHPExcel();
		// 设置excel文档的属性
		$objPHPExcel->getProperties()->setCreator("重庆艺术大市场")//创建人
		->setLastModifiedBy("重庆艺术大市场")//最后修改人
		->setTitle($name)//标题
		->setSubject($name)//题目
		->setDescription($name)//描述
		->setKeywords("报名")//关键字
		->setCategory("Signup file");//种类
	
		// 列名，应该不会超过这么多
		$cols = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE');

		$cSize = count($catIds);
		$i = 0;
		foreach($catIds as $catId){
			// 开始操作excel表
			$objPHPExcel->setActiveSheetIndex($i);
			$i++;
			if($i<$cSize)
				$objPHPExcel->createSheet();
			$rs = Db::name('vote_cats')->field('catName')->where('catId','=',$catId)->find();
			// 设置工作薄名称
			$objPHPExcel->getActiveSheet()->setTitle($rs['catName']);
			// 设置默认字体和大小
			$objPHPExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', ''));
			$objPHPExcel->getDefaultStyle()->getFont()->setSize(11);
			$styleArray = array(
					'font' => array(
							'bold' => true,
							'color'=>array(
									'argb' => 'ffffffff',
							)
					),
					'borders' => array (
							'outline' => array (
									'style' => \PHPExcel_Style_Border::BORDER_THIN,  //设置border样式
									'color' => array ('argb' => 'FF000000'),     //设置border颜色
							)
					)
			);
			//设置宽
			//foreach($cols as $col){
			//	$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
			//}
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFill()->getStartColor()->setARGB('333399');
			
			// 设置sheet标题
			$objPHPExcel->getActiveSheet()->setCellValue('A1', '会员帐号');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', '投票时间');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', '投票项');
		
			// 获取投票清单
			$lists = Db::name('vote_lists')->alias('l')->join('vote_cats c','l.catId=c.catId')
					->join('vote_items i','l.itemId=i.itemId')->join('users u','l.userId=u.userId')->where('l.catId',$catId)->select();
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray($styleArray);
			$rowIndex = 2;
			foreach($lists as $l){
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowIndex, $l['loginName']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowIndex, $l['createTime']);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$rowIndex, $l['itemName']);
				$rowIndex++;
			}
		}
	
		//输出EXCEL格式
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		// 从浏览器直接输出$filename
		header('Content-Type:application/csv;charset=UTF-8');
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-excel;");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition: attachment;filename="'.$name.'.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
	}
	
	// 投票排行
	public function getVoteRanking($catId){
		$rs = Db::name('vote_lists')->alias('l')->join('vote_items i','l.itemId=i.itemId')
				->field('l.listId,l.catId,l.itemId,l.userId,l.userName,l.createTime,i.itemName,i.itemUrl,i.itemImage,count(l.itemId) itemCount')
				->where('l.catId',$catId)->group('l.itemId')->order('itemCount desc')->limit(5)->select();
		return $rs;
	}
	// 今日得票排行
	public function getVoteRankingToday($catId){
		$rs = Db::name('vote_lists')->alias('l')->join('vote_items i','l.itemId=i.itemId')
				->field('l.listId,l.catId,l.itemId,l.userId,l.userName,l.createTime,i.itemName,i.itemUrl,i.itemImage,count(l.itemId) itemCount')
				->where('l.catId',$catId)->where('to_days(createTime)=to_days(now())')->group('l.itemId')->order('itemCount desc')->limit(5)->select();
		return $rs;
	}
	// 最活跃的会员
	public function getUserRanking($catId){
		$rs = Db::name('vote_lists')->alias('l')->join('users u','l.userId=u.userId')
				->field('l.listId,l.catId,l.itemId,l.userId,l.userName,l.createTime,concat(left(u.loginName,2),"****",right(u.loginName,2)) loginName,u.userName,u.userPhoto,count(l.userId) itemCount')
				->where('l.catId',$catId)->group('l.userId')->order('itemCount desc')->limit(5)->select();
		return $rs;
	}
		// 投票飙升榜
}
