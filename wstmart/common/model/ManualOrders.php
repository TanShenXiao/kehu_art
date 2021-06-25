<?php
namespace wstmart\common\model;

use think\Db;
use wstmart\admin\validate\ManualOrders as validate;

class ManualOrders extends Base{
    protected $pk = 'id';
    /**
     *  平台获取报表
     */
    public function pageQuery(){
        $where = [];
        $order_sn = input('order_sn');
        $goods_name = input('goods_name');
        $sort = input('sort');


        if($order_sn!=''){
            $where[] = ['o.order_sn','like','%'.$order_sn.'%'];
        }
        if($goods_name!=''){
            $where[] = ['o.goods_name','like','%'.$goods_name.'%'];
        }

        $order = 'o.created_time desc';
        if($sort){
            $sort =  str_replace('.',' ',$sort);
            $order = $sort;
        }
        $page = $this->alias('o')
            ->where($where)
            ->field('*')
            ->order($order)
            ->paginate(input('limit/d'))->toArray();
        if(count($page['data'])>0){
            foreach ($page['data'] as $key => $v){

            }
        }
        return $page;
    }

    /**
     * 新增
     */
    public function add(){
        $data = input('post.');
        WSTUnset($data, 'order_id');
        $date = date('Y-m-d H:i:s');
        $data['created_time'] =$date;
        $data['order_no'] = WSTOrderNo();

        $validate = new validate();
        if(!$validate->scene('add')->check($data)){
            return WSTReturn($validate->getError());
        }
        if($data['is_invoice'] == 1){
            $invoice_data['agent'] = input('invoice_agent');
            $invoice_data['write_invoice'] = input('invoice_write_invoice');
            $invoice_data['reviewer'] = input('invoice_reviewer');
            $invoice_data['reviewer_time'] = input('invoice_reviewer_time');
            $invoice_data['price'] = input('invoice_price');
            $invoice_data['freight_price'] = input('invoice_freight_price');
            $invoice_data['freight_no'] = input('invoice_freight_no');
            $invoice_data['order_no'] =  $data['order_no'];
            $invoice_data['order_goods_id'] =  0;
            $invoice_data['created_time'] =  0;
            $invoice_data['created_time'] =  $date;
            $invoice_data['updated_time'] =  $date;
            $invoice_validate = new \wstmart\admin\validate\Invoice();
            if(!$invoice_validate->scene('add')->check($invoice_data)){
                return WSTReturn($invoice_validate->getError());
            }
            Db::startTrans();
            $result = $this->allowField(true)->save($data);
            if(false === $result){
                Db::rollback();
                return WSTReturn($this->getError(),-1);
            }
            $invoice_model = new Invoice();
            $result = $invoice_model->allowField(true)->save($invoice_data);
            if(false === $result){
                Db::rollback();
                return WSTReturn($this->getError(),-1);
            }
            Db::commit();
            return WSTReturn("新增成功", 1);
        }else{
            $result = $this->allowField(true)->save($data);
            if(false === $result){

                return WSTReturn($this->getError(),-1);
            }else{
                return WSTReturn("新增成功", 1);
            }
        }
    }

    /**
     * 编辑
     */
    public function edit(){
        $attrId = input('post.attrId/d');
        $data = input('post.');
        $data["attrSort"] = (int)$data["attrSort"];
        WSTUnset($data, 'attrId,dataFlag,createTime');
        $data['attrVal'] = str_replace('，',',',$data['attrVal']);
        $goodsCats = model('GoodsCats')->getParentIs($data['goodsCatId']);
        krsort($goodsCats);
        if(!empty($goodsCats))$data['goodsCatPath'] = implode('_',$goodsCats)."_";
        $validate = new validate();
        if(!$validate->scene('edit')->check($data))return WSTReturn($validate->getError());
        $result = $this->allowField(true)->save($data,['attrId'=>$attrId]);
        if(false !== $result){
            Db::name('goods_attributes')->where('attrId', $attrId)->delete();
            return WSTReturn("编辑成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }
}
