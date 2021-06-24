<?php
namespace addons\signup\model;
use think\addons\BaseModel as Base;
use think\Db;
use addons\signup\model\Extras as ME;
use wstmart\common\model\LogSms;
use Env;
/**
 * 
 * 在线报名
 *
 */
class Lists extends Base{
	//查询筛选
	public function queryFilter(){
		$catName = input('catName');
		$startTime = input('startTime');
		$endTime = input('endTime');
		if($startTime!='' && $endTime!=''){
			$where[] = ['l.payTime','between time',[$startTime,$endTime]];
		}else if($startTime!=''){
			$where[] = ['l.payTime','>= time',$startTime];
		}else if($endTime!=''){
			$where[] = ['l.payTime','<= time',$endTime];
		}
		$where[] = ['l.dataFlag','<>',-1];
		if(!empty($catName!=''))
			$where[] = ['c.catName','like','%'.$catName.'%'];
		return $where;
	}

	/**
	 * 管理员查看线上报名项目
	 */
	public function pageQueryByAdmin(){
		$where = $this->queryFilter();
        $page =  Db::name('signup_lists')->alias('l')->join('users u','l.userId=u.userId')->join('signup_cats c','l.catId=c.catId')->order('l.createTime')->where($where)
					->field('c.catName,c.startDate,c.endDate,u.loginName,l.isPaid,l.createTime,l.payCode,l.signupSn,l.signupFee,l.payTime,l.tradeNo,l.listId')->paginate(input('limit/d'))->toArray();
        return $page;
	}
	
	/**
	 * 管理员编辑线上报名项目
	 */
	public function editCat(){
		$data = input('post.');
		if(empty($data['catName']) || empty($data['startDate']) || empty($data['endDate'])){
			return WSTReturn('请将信息补充完整',-1);
		}
		WSTUnset($data,'dataFlag,catSort,status');
		Db::startTrans();
		try{
			$result = Db::name('signup_cats')->where('catId',$data['catId'])->update($data);
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
			$result = Db::name('signup_cats')->insert($data);
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
        $rs = Db::name('signup_cats')->where('catId',$id)->update(['dataFlag'=>-1]);
        return WSTReturn('删除成功',1);
	}
	
	/**
	 *  获取报名项目
	 */
	public function getCatById($id){
		$where = [];
		$where['catId'] = (int)$id;
		$where['dataFlag'] = 1;
		return Db::name('signup_cats')->where($where)->find();
	}
	
	/**
	 * 管理员查看线上报名字段
	 */
	public function listCats(){
		$where[] = ['dataFlag','<>',-1];
		$where[] = ["startDate",'exp',Db::raw("<=date(now())")];
		$where[] = ["endDate",'exp',Db::raw(">=date(now())")];
		$rs = Db::name('signup_cats')->where($where)->select();
        return $rs;
	}
	
	/**
	 * 新增报名
	 */
	public function addList(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0)
			return WSTReturn('您尚未登录系统，请先登录系统');
		$data = input('post.');
		//if(empty($data['listData']))
		//	return WSTReturn('参数错误',-1);
		if(empty($data['extraData']))
			$extraData = array();
		else $extraData = $data['extraData'];
		$listData = $data['listData'];
		//if(empty($listData['name']) || empty($listData['instructor']) || empty($listData['institute']) || empty($listData['address']) || empty($listData['telephone'])){
		//	return WSTReturn('请将信息补充完整',-1);
		//}
		//判断是否有必填字段没填
		$me = new ME();
		$extras = $me->getExtrasByCatId($listData['catId']);
		$isOK = true;
		$names = array();
		foreach($extras as $e){
			if($e['isRequired'] && empty($extraData[$e['extraId']])){
					$isOK = false;
			}
			// 补充字段名及项目名
			$name = array();
			$name['extraName'] = $e['extraName'];
			$name['catName'] = $e['catName'];
			$names[$e['extraId']] = $name;
		}
		if($isOK == false)
				return WSTReturn('请将信息补充完整',-1);
		$listData['dataFlag'] = 1;
		$listData['createTime'] = date('Y-m-d H:i:s');
		$listData['userId'] = $userId;
		$listData['signupSn'] = WSTOrderNo();
		if($this->isSigned($userId,$listData['catId']))
			return WSTReturn("您已报过名，请勿重复提交",-1);
		Db::startTrans();
		try{
			$result = Db::name('signup_lists')->insert($listData);
			$listId = Db::name('signup_lists')->getLastInsId();
			$exData = array();
			if(!empty($extraData)) {
				foreach($extraData as $k=>$v) {
					$ex['listId'] = $listId;
					$ex['catId'] = $listData['catId'];
					$ex['catName'] = $names[$k]['catName'];
					$ex['extraId'] = $k;
					$ex['extraName'] = $names[$k]['extraName'];
					$ex['extraVal'] = $v;
					$ex['isShow'] = 1;
					$ex['dataFlag'] = 1;
					$exData[] = $ex;
				}
			}
			if(!empty($exData))
				$result1 = Db::name('signup_lists_extras')->insertAll($exData);
			else $result1 = true;
        	if(false !== $result && false !== $result1){
        		WSTClearAllCache();
        		Db::commit();

				// 报名成功后发送短信
				if($listData['signupFee']==0){
					$rs = Db::name('users')->field('loginName,userPhone,userId')->where('userId',$userId)->find();
					$userPhone = $rs['userPhone'];
					if(strlen($userPhone)==11 && substr($userPhone,0,1)=="1"){	//检测手机号码
						$tpl = WSTMsgTemplates('PHONE_USER_SIGNUP');
						if( $tpl['tplContent']!='' && $tpl['status']=='1'){
							$params = ['tpl'=>$tpl,'params'=>['SIGNUP_TITLE'=>$listData["catName"],'LOGIN_NAME'=>$rs['loginName']]];
							$m = new LogSms();
							$rv = $m->sendSMS(0,$userPhone,$params,'singupAddList',"",$userId,0);
						}
					}
				}
				$pkey = WSTBase64urlEncode($listData['signupSn']."@signup");
        	    return WSTReturn("新增成功", 1, ['listId'=>$listId,'pkey'=>$pkey]);
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
	 * 是否已报名
	 */
	public function isSigned($userId,$catId){
		$rs = Db::name('signup_lists')->where('userId',$userId)->where('catId',$catId)->find();
		if(empty($rs))
			return 0;
		else return 1;
	}

	/**
	 * 获取会员报名信息
	 */
	public function getUserSignup($userId,$catId){
		$where[] = ['dataFlag','=',1];
		$where[] = ['catId','=',$catId];
		$where[] = ['userId','=',$userId];
		$rs = Db::name('signup_lists')->where($where)->find();
		$signupSn = '';
		if(empty($rs)){
			$rs['isPaid'] = 0;
		}else{
			$signupSn = $rs['signupSn'];
		}
		$rs['isSigned'] = $this->isSigned($userId,$catId);
		$pkey = WSTBase64urlEncode($signupSn."@signup");
		$rs['pkey'] = $pkey;
		return $rs;
	}
	
		
	/**
	 * 导出报名信息
	 */
	public function toExport(){
		$name='报名信息详情';
		$where = $this->queryFilter();
		//查看哪些项目报过名
		$lists = Db::name('signup_lists')->alias('l')->join('signup_cats c','l.catId=c.catId')->where($where)->select();
		//报名项目清单
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
			$rs = Db::name('signup_cats')->field('catName')->where('catId','=',$catId)->find();
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
		
			// 获取报名扩展字段
			$extras = Db::name('signup_extras')->where('catId','=',$catId)->select();
			$colIndex = 2;
			$columns = array();
			foreach($extras as $e){
				// 设置sheet标题
				$objPHPExcel->getActiveSheet()->setCellValue('A1', '会员帐号');
				$objPHPExcel->getActiveSheet()->setCellValue('B1', '报名时间');
				$objPHPExcel->getActiveSheet()->setCellValue($cols[$colIndex].'1', $e['extraName']);
				$columns[$e['extraId']] = $colIndex;
				$colIndex++;
			}
			$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray($styleArray);
			$lists = Db::name('signup_lists')->alias('l')->join('users u','l.userId=u.userId')->field('u.loginName,l.isPaid,l.createTime,l.payCode,l.signupSn,l.signupFee,l.payTime,l.tradeNo,l.listId')->where('l.catId',$catId)->select();
			$rowIndex = 2;
			foreach($lists as $l){
				$lextras = Db::name('signup_lists_extras')->where('listId',$l['listId'])->select();
				foreach ($lextras as $le){
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowIndex, $l['loginName']);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowIndex, $l['createTime']);
					$extraId = $le['extraId'];
					if(array_key_exists($extraId,$columns)){
						$objPHPExcel->getActiveSheet()->setCellValue($cols[$columns[$le['extraId']]].$rowIndex, $le['extraVal']);
					}
				}
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

	/**
	 * 导出报名信息总览
	 */
	public function toExportOverview(){
		$name='报名信息总览';
		$where = $this->queryFilter();
        $lists =  Db::name('signup_lists')->alias('l')->join('users u','l.userId=u.userId')->join('signup_cats c','l.catId=c.catId')->order('l.createTime')->where($where)
					->field('c.catName,c.startDate,c.endDate,u.loginName,l.isPaid,l.createTime,l.payCode,l.signupSn,l.signupFee,l.payTime,l.tradeNo')->select();

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

		// 开始操作excel表
		$objPHPExcel->setActiveSheetIndex(0);
		// 设置工作薄名称
		$objPHPExcel->getActiveSheet()->setTitle($name);
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
		$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFill()->getStartColor()->setARGB('333399');

		// 设置sheet标题
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '项目名称');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', '报名开始时间');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', '报名截止时间');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', '会员帐号');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '报名时间');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '是否支付');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', '支付时间');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', '支付通道');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', '支付通道号');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', '报名费');
		$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray($styleArray);
		$rowIndex = 2;
		foreach($lists as $l){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$rowIndex, $l['catName']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$rowIndex, $l['startDate']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$rowIndex, $l['endDate']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$rowIndex, $l['loginName']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$rowIndex, $l['createTime']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$rowIndex, ($l['isPaid']==0)?'否':'是');
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$rowIndex, $l['payTime']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$rowIndex, WSTLangPayCode($l['payCode']));
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$rowIndex, ' '.$l['tradeNo']);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$rowIndex, $l['signupFee']);
			$rowIndex++;
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
}
