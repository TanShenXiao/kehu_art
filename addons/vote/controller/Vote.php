<?php
namespace addons\vote\controller;

use think\addons\Controller;
use addons\vote\model\Vote as M;
use addons\vote\model\Cats as MC;
use addons\vote\model\Items as MI;
use addons\vote\model\Lists as ML;
use think\Db;

/**
 * 线上报名插件
 */
class vote extends Controller{
	public function __construct(){
		parent::__construct();
		$this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
	}
	
	public function config(){
		$m = new M();
		$data = $m->config();
		$this->assign('config',$data['totalVotes']);
		return $this->fetch('/admin/config');
	}
	
	public function editConfig(){
		$data = input('post.totalVotes');
		if($data==null || $data=='' || $data<1){
			return WSTReturn('参数错误!',-1);
		}
		Db::name('vote_config')->where('totalVotes','<>',$data)->update(['totalVotes'=>$data]);
	    return WSTReturn('保存成功!',1);
	}
	
	public function lists(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			//$this->redirect("/home/users/index");
		}
		// 获取投票项目清单
		$m = new MC();
		$data = $m->getCats();
		$index = 0;
		$mi = new MI();
		foreach($data as $cat) {
			$data[$index]['items'] = $mi->getItemByCatId($cat['catId']);
			$index++;
		}
		$this->assign('cats',$data);
		$catId = input('get.catId/d');
		if($catId==0 && !empty($data))
			$catId = $data[0]['catId'];
		$this->assign('curCatId',$catId);
		
		$ml = new ML();
		// 获取会员投票信息
		if($userId==0) {
			$this->assign('isLogin',0);
			$data['isVoted'] = 0;
			$this->assign('userVote',$data);
		}else{
			$this->assign('isLogin',1);
			if($catId!=0){
				$data = $ml->getUserVote($userId,$catId);
			}else{
				$data['isVoted'] = 0;
			}
			$this->assign('userVote',$data);
		}
		// 投票排行
		$vp = $ml->getVoteRanking($catId);
		$this->assign('voteRanking',$vp);
		// 今日得票排行
		$mv = $ml->getVoteRankingToday($catId);
		$this->assign('voteRankingToday',$mv);
		// 最活跃的会员
		$mu = $ml->getUserRanking($catId);
		$this->assign('userRanking',$mu);
		// 投票飙升榜
		return $this->fetch('/home/list');
	}
	
	public function mlists(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			//$this->redirect("/home/users/index");
		}
		// 获取投票项目清单
		$m = new MC();
		$data = $m->getCats();
		$index = 0;
		$mi = new MI();
		foreach($data as $cat) {
			$data[$index]['items'] = $mi->getItemByCatId($cat['catId']);
			$index++;
		}
		$this->assign('cats',$data);
		$catId = input('get.catId/d');
		if($catId==0 && !empty($data))
			$catId = $data[0]['catId'];
		$this->assign('curCatId',$catId);
		
		// 获取会员投票信息
		if($userId==0) {
			$this->assign('isLogin',0);
			$data['isVoted'] = 0;
			$this->assign('userVote',$data);
		}else{
			$this->assign('isLogin',1);
			if($catId!=0){
				$m = new ML();
				$data = $m->getUserVote($userId,$catId);
			}else{
				$data['isVoted'] = 0;
			}
			$this->assign('userVote',$data);
		}
		return $this->fetch('/mobile/list');
	}
	
	public function mdesc(){
		$catId = input('catId/d');
		if($catId==0) {
			return;
		}
		// 获取报名项目清单
		$m = new MC();
		$data = $m->getCatById($catId);
		$this->assign('cat',$data);
		return $this->fetch('/mobile/catdesc');
	}
	
	public function mitemlist(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			//$this->redirect("/home/users/index");
		}
		$catId = input('catId/d');
		if($catId==0) {
			return;
		}
		$this->assign('catId',$catId);
		// 获取投票项
		$m = new MI();
		$data = $m->getItemByCatId($catId);
		$this->assign('items',$data);
		
		// 获取会员投票信息
		if($userId==0) {
			$this->assign('isLogin',0);
			$data['isVoted'] = 0;
			$this->assign('userVote',$data);
		}else{
			$this->assign('isLogin',1);
			if($catId!=0){
				$m = new ML();
				$data = $m->getUserVote($userId,$catId);
			}else{
				$data['isVoted'] = 0;
			}
			$this->assign('userVote',$data);
		}
		return $this->fetch('/mobile/itemlist');
	}
	
	public function wlists(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			//$this->redirect("/home/users/index");
		}
		// 获取投票项目清单
		$m = new MC();
		$data = $m->getCats();
		$index = 0;
		$mi = new MI();
		foreach($data as $cat) {
			$data[$index]['items'] = $mi->getItemByCatId($cat['catId']);
			$index++;
		}
		$this->assign('cats',$data);
		$catId = input('get.catId/d');
		if($catId==0 && !empty($data))
			$catId = $data[0]['catId'];
		$this->assign('curCatId',$catId);
		
		// 获取会员投票信息
		if($userId==0) {
			$this->assign('isLogin',0);
			$data['isVoted'] = 0;
			$this->assign('userVote',$data);
		}else{
			$this->assign('isLogin',1);
			if($catId!=0){
				$m = new ML();
				$data = $m->getUserVote($userId,$catId);
			}else{
				$data['isVoted'] = 0;
			}
			$this->assign('userVote',$data);
		}
		return $this->fetch('/wechat/list');
	}
	
	public function wdesc(){
		$catId = input('catId/d');
		if($catId==0) {
			return;
		}
		// 获取报名项目清单
		$m = new MC();
		$data = $m->getCatById($catId);
		$this->assign('cat',$data);
		if(WSTConf('CONF.wxenabled')==1){
	        $we = WSTWechat();
	        $datawx = $we->getJsSignature(request()->scheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	        $this->assign("datawx", $datawx);
        }
		return $this->fetch('/wechat/catdesc');
	}
	
	public function witemlist(){
		$userId = (int)session('WST_USER.userId');
		if($userId==0) {
			//$this->redirect("/home/users/index");
		}
		$catId = input('catId/d');
		if($catId==0) {
			return;
		}
		$this->assign('catId',$catId);
		// 获取投票项
		$m = new MI();
		$data = $m->getItemByCatId($catId);
		$this->assign('items',$data);
		
		// 获取会员投票信息
		if($userId==0) {
			$this->assign('isLogin',0);
			$data['isVoted'] = 0;
			$this->assign('userVote',$data);
		}else{
			$this->assign('isLogin',1);
			if($catId!=0){
				$m = new ML();
				$data = $m->getUserVote($userId,$catId);
			}else{
				$data['isVoted'] = 0;
			}
			$this->assign('userVote',$data);
		}
		return $this->fetch('/wechat/itemlist');
	}
}