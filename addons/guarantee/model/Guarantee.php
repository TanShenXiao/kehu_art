<?php
namespace addons\guarantee\model;
use think\addons\BaseModel as Base;
use wstmart\home\model\Goods as Goods;
use think\Db;
/**
 * 
 * 保底交易
 *
 */
class Guarantee extends Base{
	public function getConfigs(){
		$data = cache('guarantee');
		if(!$data){
			$rs = Db::name('addons')->where('name','Guarantee')->field('config')->find();
		    $data =  json_decode($rs['config'],true);
		    cache('guarantee',$data,31622400);
		}
		return $data;
	}
	
    /***
     * 安装插件
     */
    public function installMenu(){
    	Db::startTrans();
		try{
			$hooks = ['guarantee'];
			$this->bindHoods("guarantee", $hooks);
			//管理员后台
			$rs = Db::name('menus')->insert(["parentId"=>15,"menuName"=>"保底交易","menuSort"=>10,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"guarantee","menuIcon"=>"user-plus"]);
			if($rs!==false){
				$parentId = Db::name('menus')->getLastInsID();
				$rs = Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"保底交易设置","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"guarantee","menuIcon"=>"id-card-o"]);
				if($rs!==false){
					$datas = [];
					$pId = Db::name('menus')->getLastInsID();
					$datas[] = ["menuId"=>$parentId,"privilegeCode"=>"BDJY_00","privilegeName"=>"查看保底交易","isMenuPrivilege"=>0,"privilegeUrl"=>"","otherPrivilegeUrl"=>"","dataFlag"=>1,"isEnable"=>1];
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"BDJY_CFG","privilegeName"=>"设置保底交易","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/guarantee-guarantee-config","otherPrivilegeUrl"=>"/addon/groupon-goods-pageQueryByAdmin,/addon/groupon-goods-pageAuditQueryByAdmin","dataFlag"=>1,"isEnable"=>1];
					Db::name('privileges')->insertAll($datas);
				}
				$rs = Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"保底交易回购","menuSort"=>2,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"guarantee","menuIcon"=>"cubes"]);
				if($rs!==false){
					$datas = [];
					$pId = Db::name('menus')->getLastInsID();
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"BDJY_HG","privilegeName"=>"保底交易回购","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/guarantee-guarantee-backsale","otherPrivilegeUrl"=>"/addon/groupon-goods-allow,/addon/groupon-goods-illegal","dataFlag"=>1,"isEnable"=>1];
					Db::name('privileges')->insertAll($datas);
				}
				$rs = Db::name('menus')->insert(["parentId"=>$parentId,"menuName"=>"保底交易提货","menuSort"=>3,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"guarantee","menuIcon"=>"cubes"]);
				if($rs!==false){
					$datas = [];
					$pId = Db::name('menus')->getLastInsID();
					$datas[] = ["menuId"=>$pId,"privilegeCode"=>"BDJY_TH","privilegeName"=>"保底交易提货","isMenuPrivilege"=>1,"privilegeUrl"=>"/addon/guarantee-guarantee-delivery","otherPrivilegeUrl"=>"/addon/groupon-goods-allow,/addon/groupon-goods-illegal","dataFlag"=>1,"isEnable"=>1];
					Db::name('privileges')->insertAll($datas);
				}
			}
			//会员前台
			$now = date("Y-m-d H:i:s");
			$rs = Db::name('home_menus')->insert(["parentId"=>1,"menuName"=>"保底交易","menuSort"=>0,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"guarantee","menuType"=>0,"createTime"=>$now,"menuUrl"=>"#"]);
			if($rs!==false){
				$parentId = Db::name('home_menus')->getLastInsID();
				$rs = Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"保底中的商品","menuSort"=>0,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"guarantee","menuType"=>0,"createTime"=>$now,"menuUrl"=>"home/goods/mygoods/bs/0","menuOtherUrl"=>"home/goods/mygoods/bs/0"]);
				$rs = Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"增值中的商品","menuSort"=>1,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"guarantee","menuType"=>0,"createTime"=>$now,"menuUrl"=>"home/goods/mygoods/bs/1","menuOtherUrl"=>"home/goods/mygoods/bs/1"]);
				$rs = Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"已保底的商品","menuSort"=>2,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"guarantee","menuType"=>0,"createTime"=>$now,"menuUrl"=>"home/goods/mygoods/bs/2","menuOtherUrl"=>"home/goods/mygoods/bs/2"]);
				$rs = Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"已增值的商品","menuSort"=>3,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"guarantee","menuType"=>0,"createTime"=>$now,"menuUrl"=>"home/goods/mygoods/bs/3","menuOtherUrl"=>"home/goods/mygoods/bs/3"]);
				$rs = Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"审核中的商品","menuSort"=>4,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"guarantee","menuType"=>0,"createTime"=>$now,"menuUrl"=>"home/goods/mygoods/bs/4","menuOtherUrl"=>"home/goods/mygoods/bs/4"]);
				$rs = Db::name('home_menus')->insert(["parentId"=>$parentId,"menuName"=>"已提货的商品","menuSort"=>5,"dataFlag"=>1,"isShow"=>1,"menuMark"=>"guarantee","menuType"=>0,"createTime"=>$now,"menuUrl"=>"home/goods/mygoods/bs/5","menuOtherUrl"=>"home/goods/mygoods/bs/5"]);
			}
			
			installSql("guarantee");
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();echo $e;die;
	  		return false;
	   	}
    }

    /**
	 * 删除菜单
	 */
	public function uninstallMenu(){
		Db::startTrans();
		try{
			$hooks = ['guarantee'];
			$this->unbindHoods("guarantee", $hooks);
			Db::name('menus')->where(["menuMark"=>"guarantee"])->delete();
			Db::name('home_menus')->where(["menuMark"=>"guarantee"])->delete();
			Db::name('privileges')->where("privilegeCode","like","BDJY_%")->delete();
			uninstallSql("guarantee");//传入插件名
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}

	/**
	 * 菜单显示隐藏
	 */
	public function toggleShow($isShow = 1){
		Db::startTrans();
		try{
			Db::name('menus')->where(["menuMark"=>"groupon"])->update(["isShow"=>$isShow]);
			Db::name('home_menus')->where(["menuMark"=>"groupon"])->update(["isShow"=>$isShow]);
			Db::name('navs')->where(["navUrl"=>"index.php/addon/groupon-goods-lists.html"])->update(["isShow"=>$isShow]);
			if($isShow==1){
				$this->addMobileBtn();
			}else{
				$this->delMobileBtn();
			}
			Db::commit();
			return true;
		}catch (\Exception $e) {
	 		Db::rollback();
	  		return false;
	   	}
	}

	/**
	* 编辑保底交易配置参数
	*/ 
	function editConfig(){
		$userId = (int)session('WST_USER.userId');
		$data = input('post.');
		foreach($data as $d){
			if($d==null || $d=''){
				return WSTReturn('参数不完整!',-1);
			}
		}
		Db::name('guarantee_config')->where('id',1)->update($data);
	    return WSTReturn('保存成功!',1);
	}
	
	/**
	 * 获取保底交易商品记录
	 */
	public function pageQuery($goodsCatIds = []){
		//查询条件
		$isStock = input('isStock/d');
		$isNew = input('isNew/d');
		$isFreeShipping = input('isFreeShipping/d');
		$keyword = input('keyword');
		$where = $where2 = [];
		$searchType = Input('keytype/d');
		$where = [];
		$where[] = ['goodsStatus', '=', 1];
		$where[] = ['g.dataFlag', '=', 1];
		$where[] = ['isSale', '=', 1];
		//if($keyword!='')$where2 = $this->getKeyWords($keyword);
		if($searchType==2){
			if($keyword!='')$where[] = ['goodsAuthor','like','%'.$keyword.'%'];
		}else{
			if($keyword!='')$where[] = ['goodsName','like','%'.$keyword.'%'];
		}
		$where[] = ['goodsType','=',2];
		//属性筛选
		$gm = new Goods();
		$goodsIds = $gm->filterByAttributes();
		if(!empty($goodsIds))$where[] = ['goodsId','in',$goodsIds];
		// 品牌筛选
		$brandIds = input('param.brand');
		if(!empty($brandIds)){
			$brandIds = explode(',',$brandIds);
			$where[] = ['brandId','in',$brandIds];
		}

		// 发货地
		$areaId = (int)input('areaId');
		if($areaId>0)$where['areaId'] = $areaId;
		//排序条件
		$orderBy = input('orderBy/d',3);	//默认按点赞数排序
		$orderBy = ($orderBy>=0 && $orderBy<=4)?$orderBy:0;
		$order = (input('order/d',1)==1)?1:0;	// 默认按降序排序
		$pageBy = ['saleNum','shopPrice','appraiseNum','thumbsNum','saleTime'];
		$pageOrder = ['asc','desc'];
		if($isStock == 0){	//选中已完成交易商品
			$where[] = ['goodsStock', '<=', 0];
		}
		if($isNew==0){	//选中可交易商品
			$where[] = ['goodsStock','>',0];
		}
		if($isFreeShipping==1)$where['isFreeShipping'] = 1;
		if(!empty($goodsCatIds))$where[] = ['goodsCatIdPath','like',implode('_',$goodsCatIds).'_%'];
	    $sprice = input("param.sprice");//开始价格
	    $eprice = input("param.eprice");//结束价格
		if($sprice!='' && $eprice!=''){
	    	$where[] = ['g.shopPrice','between',[(int)$sprice,(int)$eprice]];
	    }elseif($sprice!=''){
	    	$where[] = ['g.shopPrice','>=',(int)$sprice];
		}elseif($eprice!=''){
			$where[] = ['g.shopPrice','<=',(int)$eprice];
		}
		$list = Db::name("goods")->alias('g')->join("__SHOPS__ s","g.shopId = s.shopId")
			->where($where)
			->field('goodsId,goodsName,goodsSn,goodsStock,isNew,saleNum,shopPrice,marketPrice,isSpec,goodsImg,appraiseNum,visitNum,s.shopId,shopName,isSelf,isFreeShipping,gallery,goodsAuthor,saleType,thumbsNum,goodsType')
			->order($pageBy[$orderBy]." ".$pageOrder[$order].",goodsStock desc")
			->paginate(input('pagesize/d',15))->toArray();
		//加载标签
		if(!empty($list['data'])){
			foreach ($list['data'] as $key => $v) {
				$list['data'][$key]['tags'] = [];
				if($v['isSelf']==1)$list['data'][$key]['tags'][] = "<span class='tag'>自营</span>";
	      	    if($v['isFreeShipping']==1)$list['data'][$key]['tags'][] = "<span class='tag'>包邮</span>";
	      	    if($v['goodsType']==2)$list['data'][$key]['tags'][] = "<span class='tag'>保底交易</span>";
			}
		}
		//关注
		if(!empty($list['data'])){
			foreach ($list['data'] as $key =>$v){
				$list['data'][$key]['favGood'] = model('home/Favorites')->checkFavorite($v['goodsId'],0);
			}
		}
		hook('afterQueryGoods',['page'=>&$list]);
		return $list;
	}
	
	function pageQueryDelivery(){
		$goodsName = input('goodsName');
		$where[] = ['g.dataFlag','<>',-1];
		$where[] = ['g.goodsName','like','%'.$goodsName.'%'];
		$where[] = ['g.bdStatus','=',5];
		$where[] = ['g.bdDeliveryStatus','=',0];
        $page =  Db::name('goods')->alias('g')->join('users bu','g.ownerId=bu.userId')->join('goods og','g.oriGoodsId=og.goodsId')->join('users su','og.ownerId=su.userId')->where($where)
					->field('g.goodsId goodsId,bu.loginName buyerLoginName,su.loginName sellerLoginName,g.goodsName goodsName,g.goodsSn goodsSn')
                    ->paginate(input('limit/d'))->toArray();
        return $page;
	}
	
	function pageQueryBackSale(){
		$goodsName = input('goodsName');
		$where[] = ['g.dataFlag','<>',-1];
		$where[] = ['g.goodsName','like','%'.$goodsName.'%'];
		$where[] = ['g.bdStatus','=',2];
		$where[] = ['g.bdBackStatus','<',3];
        $page =  Db::name('goods')->alias('g')->join('users bu','g.ownerId=bu.userId')->join('goods og','g.oriGoodsId=og.goodsId')->join('users su','og.ownerId=su.userId')->where($where)
					->field('g.goodsId goodsId,bu.loginName buyerLoginName,su.loginName sellerLoginName,g.goodsName goodsName,g.goodsSn goodsSn,g.bdBackStatus bdBackStatus')
                    ->paginate(input('limit/d'))->toArray();
        return $page;
	}
	
	function verifyCode(){
		$where['bdSmsCode'] = input('verifyCode');
		$where['goodsId'] = input('goodsId/d');
		$rs = Db::name('goods')->field('goodsId')->where($where)->find();
		if(empty($rs)){
			return WSTReturn('验证码错误',-1);
		}else{
			//修改提货状态
			Db::name('goods')->where($where)->setField('bdDeliveryStatus',1);
			return WSTReturn('ok',1);
		}
	}
	
	function confirmBackSale(){
		$where['goodsId'] = input('goodsId/d');
		$rs = Db::name('goods')->field('goodsId')->where($where)->find();
		if(empty($rs)){
			return WSTReturn('错误，没有该商品',-1);
		}else{
			//修改提货状态
			Db::name('goods')->where($where)->setInc('bdBackStatus',1);
			return WSTReturn('ok',1);
		}
	}
}
