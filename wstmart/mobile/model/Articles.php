<?php
namespace wstmart\mobile\model;
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
 *  文章类
 */
class Articles extends Base{
	
	/**
	* 获取咨询中中心所有文章
	*/
	public function getArticles(){

		$catId = input('catId');
		$rs = $this->alias('a')
			  ->field('a.*')
			  ->join('__ARTICLE_CATS__ ac','a.catId=ac.catId','inner')
			  ->where(['a.catId'=>$catId,
			  	       'a.isShow'=>1,
			  	       'a.dataFlag'=>1,
			  		   'ac.dataFlag'=>1,
			  		   'ac.isShow'=>1,
			  		   'ac.catType'=>0,
			  		   ])
			  ->order('a.createTime desc')
			  ->paginate((int)input('pagesize'));
		return $rs;
	}


	/**
	*  根据id获取资讯文章
	*/
	public function getNewsById(){
		$id = (int)input('id');
		WSTArticleVisitorNum($id);
		$article = $this->alias('a')
					->field('a.*')
					->join('__ARTICLE_CATS__ ac','a.catId=ac.catId','inner')
					->where('ac.catType=0 and a.dataFlag=1 and a.isShow=1 and a.articleId='.$id)
					->find();
        $articleId = cookie("mobile_like_articleId");
		$articleId = is_array($articleId)?$articleId:[];
		$rc = !empty($articleId)?in_array($id,$articleId):'';
		if($rc){
         $article['likeState'] = 1;
		}else{
         $article['likeState'] = 0;
		}

		$article['articleContent'] = str_replace('${DOMAIN}',WSTConf('CONF.resourcePath'),$article['articleContent']);
        return $article;
	}
	/**
	 * 点赞
	 */
	public function like(){
	    $id = (int)input('id');
		$articleId = cookie("mobile_like_articleId");
		$articleId = is_array($articleId)?$articleId:[];
		$rc = !empty($articleId)?in_array($id,$articleId):'';
		if($rc)return WSTReturn("已点赞成功", -1);
		$rs = $this->where(['isShow'=>1,'dataFlag'=>1,'articleId'=>$id])->setInc('likeNum',1);

		if(false !== $rs){
			array_push($articleId,$id);
			cookie("mobile_like_articleId",$articleId,25920000);

			return WSTReturn("点赞成功", 1);
		}else{
			return WSTReturn($this->getError(),-1);
		}
	}
	/**
	* 获取资讯中心的子集分类
	*/
	public function getChildInfos(){
		$infos = cache('NEW_INFOS');
		$i = 0;
		if(!$infos){
			$data = Db::name('article_cats')->cache(true)->select();
			foreach($data as $k=>$v){
				if($v['parentId']== 8){
					$infos[$i]['catId'] = $v['catId'];
					$infos[$i]['catName'] = $v['catName'];
					$i++;
				}
			}
            cache('NEW_INFOS',$infos);
		}
		return $infos;
	}
}
