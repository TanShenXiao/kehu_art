<?php
namespace wstmart\admin\model;
use wstmart\admin\validate\RecommendedArtists as validate;
use think\Db;

class RecommendedArtists extends Base{

	protected $name = 'recommended_artists';
	/**
	 * 分页
	 */
	public function pageQuery(){
        $where = [];
		$key = input('key');
		if($key!=''){
            $where[] = ['s.shopName|s.shopSn','like','%'.$key.'%'];
        }
		$model = Db::name('recommended_artists')->alias('ra')->where($where)
        ->join('shops s','s.shopId = ra.shop_id','inner')
		->field('ra.*,s.shopName,s.shopImg')
		->order(['ra.top'=> 'desc','ra.sort'=> 'desc','ra.created_time' => 'desc'])
		->paginate(input('post.limit/d'))->toArray();

		return $model;
	}	
	
	/**
	 * 获取指定对象
	 */
	public function getById($id){
		$result = $this->where(['id'=>$id])->alias('ra')
            ->join('shops s','s.shopId = ra.shop_id','inner')
            ->field('ra.*,s.shopSn as shop_id,s.shopImg')
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
        }

		return $result;
	}
	
	/**
	 * 新增
	 */
	public function add(){
		$data = input('post.');
		WSTUnset($data,'id');

        $validate = new validate();
        if(!$validate->scene('add')->check($data)){
            return WSTReturn($validate->getError());
        }
        $isApp = (int)input('post.isApp',0);
        //简介
        $resourceDomain = ($isApp==1)?WSTConf('CONF.resourceDomain'):WSTConf('CONF.resourcePath');
        $data['desc'] = htmlspecialchars_decode($data['desc']);
        $data['desc'] = WSTRichEditorFilter($data['desc']);
        $data['desc'] = str_replace($resourceDomain.'/upload/','${DOMAIN}/upload/',$data['desc']);
        //作品分析
        $data['analysis'] = htmlspecialchars_decode($data['analysis']);
        $data['analysis'] = WSTRichEditorFilter($data['analysis']);
        $data['analysis'] = str_replace($resourceDomain.'/upload/','${DOMAIN}/upload/',$data['analysis']);
        //故事
        $data['story'] = htmlspecialchars_decode($data['story']);
        $data['story'] = WSTRichEditorFilter($data['story']);
        $data['story'] = str_replace($resourceDomain.'/upload/','${DOMAIN}/upload/',$data['story']);

        $shop = Db::name('shops')->where(['shopSn' => $data['shop_id']])->field('shopId')->find();
        if(!$shop){
            return WSTReturn('未获取到店铺');
        }
        $data['shop_id'] = $shop['shopId'];
        $recommended_artists = Db::name('recommended_artists')->where(['shop_id' => $shop['shopId']])->field('id')->find();
        if($recommended_artists){
            return WSTReturn('推荐店铺已存在');
        }
        //某个人置顶 所有人将不置顶
        if($data['top'] == 1){
            Db::name('recommended_artists')->where("1=1")->update(['top' => 0]);
        }

        $result = $this->allowField(true)->save($data);
        if(false !== $result){

            return WSTReturn("新增成功", 1);
        }

        return WSTReturn('新增失败',-1);
	}
	
	/**
	 * 编辑
	 */
	public function edit(){
		$id = input('post.id/d');
		$data = input('post.');

        WSTUnset($data,'shop_id');
        $validate = new validate();
        if(!$validate->scene('edit')->check($data)){
            return WSTReturn($validate->getError());
        }
        $isApp = (int)input('post.isApp',0);
        //简介
        $resourceDomain = ($isApp==1)?WSTConf('CONF.resourceDomain'):WSTConf('CONF.resourcePath');
        $data['desc'] = htmlspecialchars_decode($data['desc']);
        $data['desc'] = WSTRichEditorFilter($data['desc']);
        $data['desc'] = str_replace($resourceDomain.'/upload/','${DOMAIN}/upload/',$data['desc']);
        //作品分析
        $data['analysis'] = htmlspecialchars_decode($data['analysis']);
        $data['analysis'] = WSTRichEditorFilter($data['analysis']);
        $data['analysis'] = str_replace($resourceDomain.'/upload/','${DOMAIN}/upload/',$data['analysis']);
        //故事
        $data['story'] = htmlspecialchars_decode($data['story']);
        $data['story'] = WSTRichEditorFilter($data['story']);
        $data['story'] = str_replace($resourceDomain.'/upload/','${DOMAIN}/upload/',$data['story']);

        //某个人置顶 所有人将不置顶
        if($data['top'] == 1){
            Db::name('recommended_artists')->where("1=1")->update(['top' => 0]);
        }
        $result = $this->allowField(['top','sort','desc','analysis','story'])->save($data,['id'=>$id]);
        if(false !== $result){

            return WSTReturn("修改成功", 1);
        }

        return WSTReturn('修改失败',-1);
	}
	
	/**
	 * 删除
	 */
	public function del(){
		$id = input('post.id/d');
        $result = $this->where(['id'=>$id])->delete();
        if(false !== $result){

            return WSTReturn("删除成功", 1);
        }

        return WSTReturn('删除失败',-1);
	}

    /**
     * 修改品牌排序
     */
    public function changeSort(){
        $id = (int)input('id');
        $sort = (int)input('sort');
        $result = $this->where(['id' => $id])->setField(['sort'=> $sort]);
        if(false !== $result){
            return WSTReturn("操作成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }
}