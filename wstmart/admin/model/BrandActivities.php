<?php
namespace wstmart\admin\model;
use wstmart\admin\validate\BrandActivities as validate;
use think\Db;

class BrandActivities extends Base{
	protected $pk = 'brand_activities';
	/**
	 * 分页
	 */
	public function pageQuery(){
        $where = [];
		$key = input('key');
		if($key!='')$where[] = ['title','like','%'.$key.'%'];
		$model = Db::name('brand_activities')->where($where)
		->field('*')
		->order(['sort'=> 'desc','created_time' => 'desc'])
		->paginate(input('post.limit/d'))->toArray();

		return $model;
	}	
	
	/**
	 * 获取指定对象
	 */
	public function getById($id){
		$result = $this->where(['id'=>$id])->find();

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
        $result = $this->allowField(true)->save($data);
        if(false !== $result){
            //启用上传图片
            WSTUseResource(1, $this->getLastInsID(), $data['cover_img'],'brand_activities');
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

        WSTUseResource(1, $id, $data['cover_img'], 'brand_activities', 'cover_img');
        $validate = new validate();
        if(!$validate->scene('edit')->check($data)){
            return WSTReturn($validate->getError());
        }
        $result = $this->allowField(['title','cover_img','target_url','status','sort'])->save($data,['id'=>$id]);
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
        WSTUnuseResource('brand_activities','cover_img',$id);
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