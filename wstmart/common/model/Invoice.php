<?php
namespace wstmart\common\model;

class Invoice extends Base{
    protected $pk = 'id';
    /**
     *  平台获取报表
     */
    public function pageQuery(){
        $where = [];
        $where[] = ['o.dataFlag','=',1];

        $orderNo = input('orderNo');
        $shopName = input('shopName');
        $goodsName = input('goodsName');
        $freight_no = input('freight_no');
        $sort = input('sort');


        if($orderNo!=''){
            $where[] = ['o.orderNo','like','%'.$orderNo.'%'];
        }
        if($shopName!=''){
            $where[] = ['s.shopName|shopSn','like','%'.$shopName.'%'];
        }
        if($goodsName!=''){
            $where[] = ['og.goodsName','like','%'.$goodsName.'%'];
        }
        if($freight_no!=''){
            $where[] = ['i.freight_no','like','%'.$freight_no.'%'];
        }

        $order = 'i.created_time desc';
        if($sort){
            $sort =  str_replace('.',' ',$sort);
            $order = $sort;
        }
        $page = $this->alias('i')
            ->join('__ORDERS__ o','i.order_id=o.orderId','left')
            ->join('__ORDER_GOODS__ og','i.order_goods_id=og.id','left')
            ->join('__SHOPS__ s','o.shopId=s.shopId','left')
            ->where($where)
            ->field('og.goodsName,s.shopName,o.orderNo,og.goodsPrice,o.createTime,o.payTime,o.receiveTime,i.*')
            ->order($order)
            ->paginate(input('limit/d'))->toArray();
        if(count($page['data'])>0){
            foreach ($page['data'] as $key => $v){

            }
        }
        return $page;
    }

    /**
     * 商家获取报表
     */
    public function shopPageQuery(){

        $shopId = (int)session('WST_USER.shopId');

        $where = [];
        $where[] = ['o.dataFlag','=',1];
        $where = ['o.shopId'=>$shopId,'dataFlag'=>1];

        $orderNo = input('orderNo');
        $goodsName = input('goodsName');
        $freight_no = input('freight_no');
        $sort = input('sort');


        if($orderNo!=''){
            $where[] = ['o.orderNo','like','%'.$orderNo.'%'];
        }
        if($goodsName!=''){
            $where[] = ['og.goodsName','like','%'.$goodsName.'%'];
        }
        if($freight_no!=''){
            $where[] = ['i.freight_no','like','%'.$freight_no.'%'];
        }

        $order = 'i.created_time desc';
        if($sort){
            $sort =  str_replace('.',' ',$sort);
            $order = $sort;
        }
        $page = $this->alias('i')
            ->join('__ORDERS__ o','i.order_id=o.orderId','left')
            ->join('__ORDER_GOODS__ og','i.order_goods_id=og.id','left')
            ->join('__SHOPS__ s','o.shopId=s.shopId','left')
            ->where($where)
            ->field('og.goodsName,s.shopName,o.orderNo,og.goodsPrice,o.createTime,o.payTime,o.receiveTime,i.*')
            ->order($order)
            ->paginate(input('limit/d'))->toArray();
        if(count($page['data'])>0){
            foreach ($page['data'] as $key => $v){

            }
        }
        return $page;
    }
}
