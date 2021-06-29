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
            $where[] = ['i.orderNo','like','%'.$orderNo.'%'];
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
            ->join('__ORDERS__ o','i.order_no=o.orderNo','inner')
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
     *  平台获取报表
     */
    public function pageManualQuery(){
        $where = [];

        $orderNo = input('orderNo');
        $goodsName = input('goodsName');
        $freight_no = input('freight_no');
        $sort = input('sort');


        if($orderNo!=''){
            $where[] = ['i.orderNo','like','%'.$orderNo.'%'];
        }
        if($goodsName!=''){
            $where[] = ['mo.goods_name','like','%'.$goodsName.'%'];
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
            ->join('__MANUAL_ORDERS__ mo','i.order_no=mo.order_no','inner')
            ->where($where)
            ->field('mo.goods_name as goodsName,"" as shopName,mo.order_no as orderNo,mo.price as goodsPrice,mo.created_time as createTime,"" as payTime,"" as receiveTime,i.*')
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
        $where = ['o.shopId'=>$shopId,'o.dataFlag'=>1];

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
            ->join('__ORDERS__ o','i.order_no=o.orderNo','left')
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
