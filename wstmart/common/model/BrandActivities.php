<?php
namespace wstmart\common\model;
use think\Db;


class BrandActivities extends Base{
	protected $pk = 'brand_activities';

    /**
     * 分页
     */
    public function getList(){
        $where = [];
        $key = input('key');
        $where[] = ['status','=',1];
        if($key!='')$where[] = ['title','like','%'.$key.'%'];
        $model = Db::name('brand_activities')->where($where)
            ->field('*')
            ->order(['sort'=> 'desc','created_time' => 'desc'])
            ->paginate(input('post.limit/d'));

        return $model;
    }
}
