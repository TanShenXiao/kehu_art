<?php
namespace addons\auction\controller;

use think\addons\Controller;
use addons\auction\model\Auctions as M;
use Request;
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
 * 拍卖商品插件
 */
class Goods extends Controller{
	public function __construct(){
		parent::__construct();
        $m = new M();
        $data = $m->getConf('Auction');
        $this->assign("seoAuctionKeywords",$data['seoAuctionKeywords']);
        $this->assign("seoAuctionDesc",$data['seoAuctionDesc']);
        $this->assign("endPayDate",((int)$data['endPayDate']==0)?3:(int)$data['endPayDate']);
        $this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}

	/**
	 * 拍卖列表
	 */
	public function lists(){
        $catId = (int)input('catId');
        $orderBy = (int)input('orderBy');
        $order = (int)input('order');
        $data = [];
        $data['auctionCatId'] = $catId;
        $m = new M();
        $data['goodsPage'] = $m->pageQuery();
        $cats = WSTGoodsCats(0);
        $catName = '全部商品分类';
        foreach($cats as $k => $v){
           if($catId==$v['catId'])$catName = $v['catName'];
        }
        $data['catName'] = $catName;
        $data['catList'] = $cats;
		return $this->fetch("/home/index/list",$data);
	}

    /**
     * 商品详情
     */
    public function detail(){
        $m = new M();
        $goodsId = input('id/d',0);
        $goods = $m->getBySale($goodsId);
        if(!empty($goods)){
            $list = $m->getHotActions(5);
            $this->assign('hot_auctions',$list);
            $this->assign('goods',$goods);
            $this->assign('shop',$goods['shop']);
            
            //分享信息
            $conf = $m->getConf('Auction');
            $shareInfo['link'] = addon_url('auction://goods/detail',array('id'=>$goodsId,'shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true);
            $shareInfo['title'] = $goods['goodsName'];
            $shareInfo['desc'] = (isset($conf["goodsShareTitle"]) && $conf["goodsShareTitle"]!="")?$conf["goodsShareTitle"]:WSTConf("CONF.mallSlogan");
            $shareInfo['imgUrl'] = WSTDomain()."/".$goods['goodsImg'];
            $this->assign('shareInfo', $shareInfo);
            
            return $this->fetch("/home/index/detail");
        }else{
            $this->redirect('home/error/goods');
        }
    }


    /**
     * 查看拍卖商品列表
     */
    public function pageByAdmin(){
        $this->checkAdminPrivileges();
        $this->assign("p",(int)input("p"));
        $this->assign("areaList",model('common/areas')->listQuery(0));
        return $this->fetch("/admin/list");
    }

    /**
     * 查询拍卖商品
     */
    public function pageQueryByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQueryByAdmin(1));
    }
    /**
     * 查询待审核拍卖商品
     */
    public function pageAuditQueryByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return WSTGrid($m->pageQueryByAdmin(0));
    }

    /**
    * 设置违规商品
    */
    public function illegal(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->illegal();
    }
    /**
     * 通过商品审核
     */
    public function allow(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->allow();
    }

    /**
     * 删除
     */
    public function delByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        return $m->delByAdmin();
    }

    /**
     * 查看竞拍记录
     */
    public function auctionLogByAdmin(){
        $this->checkAdminPrivileges();
        $this->assign("id",(int)input('id'));
        $this->assign("p",(int)input("p"));
        return $this->fetch("/admin/list_logs");
    }
    /**
     * 查看竞拍记录
     */
    public function pageAuctionLogQueryByAdmin(){
        $this->checkAdminPrivileges();
        $m = new M();
        $rs = $m->pageAuctionLogQuery((int)input('id'),true);
        return WSTGrid($rs['data']);
    }
    
    /**
     * 微信拍卖列表页
     */
    public function wxlists(){
    	$gModel = model('wechat/GoodsCats');
    	$data['goodscats'] = $gModel->getGoodsCats();
    	$this->assign("keyword", input('goodsName'));
    	$this->assign("goodsCatId", input('goodsCatId/d'));
    	$this->assign("data", $data);
    	return $this->fetch("/wechat/index/list");
    }
    /**
     * 微信拍卖列表
     */
    public function wxGrouplists(){
    	$m = new M();
    	$rs = $m->pageQuery();
    	if(!empty($rs['data'])){
    		foreach ($rs['data'] as $key =>$v){
    			$rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
    		}
    	}
    	return $rs;
    }
    /**
     * 微信商品详情
     */
    public function wxdetail(){
        $root = WSTDomain();
    	$m = new M();
    	$goodsId = input('id/d',0);
    	$goods = $m->getBySale($goodsId);
    	if(!empty($goods)){
    
    		$history = cookie("history_goods");
    		$history = is_array($history)?$history:[];
    		array_unshift($history, (string)$goods['goodsId']);
    		$history = array_values(array_unique($history));
    
    		if(!empty($history)){
    			cookie("history_goods",$history,25920000);
    		}
    		$goods['imgcount'] =  count($goods['gallery']);
    		$goods['imgwidth'] = 'width:'.$goods['imgcount'].'00%';
    		$list = $m->getHotActions(6);
    		$this->assign('hot_auctions',$list);
    		$this->assign('info',$goods);
    	    if(WSTConf('CONF.wxenabled')==1){
		        $we = WSTWechat();
		        $datawx = $we->getJsSignature(request()->scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		        $this->assign("datawx", $datawx);
       		}
    		
    		//分享信息
    		$conf = $m->getConf('Auction');
    		$shareInfo['link'] = addon_url('auction://goods/wxdetail',array('id'=>$goodsId,'shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true);
    		$shareInfo['title'] = $goods['goodsName'];
    		$shareInfo['desc'] = (isset($conf["goodsShareTitle"]) && $conf["goodsShareTitle"]!="")?$conf["goodsShareTitle"]:WSTConf("CONF.mallSlogan");
    		$shareInfo['imgUrl'] = WSTDomain()."/".$goods['goodsImg'];
    		$this->assign('shareInfo', $shareInfo);
    		
    		return $this->fetch("/wechat/index/detail");
    	}else{
    		session('wxdetail','对不起你要找的商品不见了~~o(>_<)o~~');
    		$this->redirect('wechat/error/message',['code'=>'wxdetail']);
    	}
    }
    
    /**
     * 手机拍卖列表页
     */
    public function molists(){
    	$gModel = model('mobile/GoodsCats');
    	$data['goodscats'] = $gModel->getGoodsCats();
    	$this->assign("keyword", input('goodsName'));
    	$this->assign("goodsCatId", input('goodsCatId/d'));
    	$this->assign("data", $data);
    	return $this->fetch("/mobile/index/list");
    }
    /**
     * 手机拍卖列表
     */
    public function moGrouplists(){
    	$m = new M();
    	$rs = $m->pageQuery();
    	if(!empty($rs['data'])){
    		foreach ($rs['data'] as $key =>$v){
    			$rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
    		}
    	}
    	return $rs;
    }
    /**
     * 手机商品详情
     */
    public function modetail(){
        $root = WSTDomain();
    	$m = new M();
    	$goods = $m->getBySale(input('id/d',0));
    	if(!empty($goods)){
    		$history = cookie("history_goods");
    		$history = is_array($history)?$history:[];
    		array_unshift($history, (string)$goods['goodsId']);
    		$history = array_values(array_unique($history));
    
    		if(!empty($history)){
    			cookie("history_goods",$history,25920000);
    		}
    		$goods['imgcount'] =  count($goods['gallery']);
    		$goods['imgwidth'] = 'width:'.$goods['imgcount'].'00%';
    		$list = $m->getHotActions(6);
    		$this->assign('hot_auctions',$list);
    		$this->assign('info',$goods);
    		
    		
    		return $this->fetch("/mobile/index/detail");
    	}else{
    		session('modetail','对不起你要找的商品不见了~~o(>_<)o~~');
    		$this->redirect('mobile/error/message',['code'=>'modetail']);
    	}
    }

    /****************************************  小程序  ****************************************/
    /**
     * 拍卖列表页
     */
    public function weGoodsCats(){
        $gModel = model('weapp/GoodsCats');
        $data['goodscats'] = $gModel->getGoodsCats();
        return jsonReturn('success',1,$data);
    }
    /**
     * 列表
     */
    public function welists(){
        $m = new M();
        $rs = $m->pageQuery();
        if(!empty($rs['data'])){
            foreach ($rs['data'] as $key =>$v){
                $rs['data'][$key]['goodsImg'] = WSTImg($v['goodsImg'],2);
            }
        }
        return jsonReturn('success',1,$rs);
    }
     /**
     * 手机商品详情
     */
    public function weDetail(){
        $m = new M();
		$userId = model('weapp/index')->getUserId();
    	$rs = $m->getBySale((int)input('id'),$userId);
		// 未找到该拍卖商品
		if(empty($rs))return jsonReturn('拍卖详情不存在',1);
		if(isset($rs['goodsName'])){
			$rs['goodsName'] = htmlspecialchars_decode($rs['goodsName']);
		}
		// 热门拍卖
		$rs['hot'] = $m->getHotActions(6);
		foreach($rs['hot'] as $k=>$v){
			$rs['hot'][$k]['goodsName'] = htmlspecialchars_decode($v['goodsName']);
        }
        if($rs['auctionDesc']!=''){
            $rs['auctionDesc'] = htmlspecialchars_decode($rs['auctionDesc']);
            $rule = '/<img src="\/(upload.*?)"/';
            $rule = '/<img src="\/(upload.*?)"/';
        	preg_match_all($rule, $rs['auctionDesc'], $images);
        	foreach($images[0] as $k=>$v){
        		$rs['auctionDesc'] = str_replace('/'.$images[1][$k], url('/','','',true).WSTImg($images[1][$k],3), $rs['auctionDesc']);
        	}
        }
        
    	return json_encode(WSTReturn('ok',1,$rs));
    }
}