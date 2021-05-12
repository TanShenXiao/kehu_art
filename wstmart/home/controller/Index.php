<?php
namespace wstmart\home\controller;
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
 * 默认控制器
 */
class Index extends Base{
	protected $beforeActionList = [
          'checkAuth' =>  ['only'=>'getsysmessages']
    ];
    public function download(){
        return view('/download');
    }
    public function index(){
    	$categorys = model('GoodsCats')->getFloors();
    	$this->assign('floors',$categorys);
      $this->assign('hideCategory',1);
      //获取用户积分
      $rs = model('Users')->getFieldsById((int)session('WST_USER.userId'),'userScore');
      $this->assign('object',$rs);
	  // 个人店铺数据
		$shopStreet = model('shops')->indexShopQuery(1,8);
		$this->assign('shopStreet1',$shopStreet);
		
		// 艺廊数据
		$shopStreet = model('shops')->indexShopQuery(0,8);
		$this->assign('shopStreet0',$shopStreet);
		return $this->fetch('index');
    }
    /**
     * 检测伪静态
     */
    public function checkroute(){
        return WSTReturn('ok',1);
    }
    /**
     * 保存目录ID
     */
    public function getMenuSession(){
    	$menuId = input("post.menuId");
    	session('WST_MENUID3',$menuId);
    } 
    /**
     * 获取用户信息
     */
    public function getSysMessages(){
    	$rs = model('Systems')->getSysMessages();
    	return $rs;
    }
    /**
     * 定位菜单以及跳转页面
     */
    public function position(){
    	$menuId = (int)input("post.menuId");
    	$menuType = ((int)input("post.menuType")==1)?1:0;
        $menus = model('HomeMenus')->getParentId($menuId);
        session('WST_MENID'.$menus['menuType'],$menus['parentId']);
    	session('WST_MENUID3'.$menuType,$menuId);
    }

    /**
     * 转换url
     */
    public function transfor(){
        $data = input('param.');
        $url = $data['url'];
        unset($data['url']);
        echo Url($url,$data);
    }
    /**
     * 保存url
     */
    public function currenturl(){
    	session('WST_HO_CURRENTURL',input('url'));
    	return WSTReturn("", 1);
    }
	/**
	 * 获取推荐商品
	 */
	public function listByGoods($num){
        $where = [];
        $where[] = ['r.dataSrc','=',0];
        $where[] = ['g.isSale','=',1];
        $where[] = ['g.goodsStatus','=',1]; 
        $where[] = ['g.dataFlag','=',1]; 
        $goods=[];
		$where[] = ['r.dataType','=',1];
		$goods = Db::name('goods')->alias('g')->join('__RECOMMENDS__ r','g.goodsId=r.dataId')
				   ->join('__SHOPS__ s','g.shopId=s.shopId')
				   ->where($where)->field('g.goodsTips,s.shopName,s.shopId,g.goodsId,goodsName,goodsImg,goodsSn,goodsStock,saleNum,shopPrice,marketPrice,isSpec,appraiseNum,visitNum,isNew,goodsUnit,thumbsNum,goodsAuthor,saleType')
				   ->order('g.thumbsNum desc,r.dataSort asc')->limit($num)->select();

        $ids = [];
        foreach($goods as $key =>$v){
        	if($v['isSpec']==1)$ids[] = $v['goodsId'];
			if($v['saleType']==1)
				$goods[$key]['shopPrice'] = '议价';
			else if($v['saleType']==2)
				$goods[$key]['shopPrice'] = '仅展示';
        }
        if(!empty($ids)){
        	$specs = [];
        	$rs = Db::name('goods_specs gs ')->where([['goodsId','in',$ids],['dataFlag','=',1]])->order('id asc')->select();
        	foreach ($rs as $key => $v){
        		$specs[$v['goodsId']] = $v;
        	}
        	foreach($goods as $key =>$v){
        		if(isset($specs[$v['goodsId']]))
        		$goods[$key]['specs'] = $specs[$v['goodsId']];
        	}
        }
        return $goods;
	}
}
