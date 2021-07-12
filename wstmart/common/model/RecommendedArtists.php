<?php
namespace wstmart\common\model;

use think\Db;

class RecommendedArtists extends Base{
	protected $pk = 'id';

    /**
     * 获取指定对象
     */
    public function getById($id){
        $result = $this->where(['shop_id'=>$id])->alias('ra')
            ->join('shops s','s.shopId = ra.shop_id','inner')
            ->field('ra.*,s.shopId,s.shopName,s.shopSn,s.shopImg,s.shopBrief')
            ->find();

        if($result){
            //简介
            $result['desc'] = htmlspecialchars_decode($result['desc']);
            $result['desc'] = str_replace('${DOMAIN}',WSTConf('CONF.resourcePath'),$result['desc']);
            //作品分析
            $result['analysis'] = htmlspecialchars_decode($result['analysis']);
            $result['analysis'] = str_replace('${DOMAIN}',WSTConf('CONF.resourcePath'),$result['analysis']);
            //故事
            $result['story'] = htmlspecialchars_decode($result['story']);
            $result['story'] = str_replace('${DOMAIN}',WSTConf('CONF.resourcePath'),$result['story']);

            $config = Db::name('shop_configs')->where("shopId=".$result['shopId'])->find();
            //热搜关键词
        }

        return $result;
    }
}
