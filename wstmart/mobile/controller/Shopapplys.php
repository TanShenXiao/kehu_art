<?php
namespace wstmart\mobile\controller;
use wstmart\common\model\ShopApplys as M;
use wstmart\common\model\Users as UM;
use wstmart\mobile\model\TaxUsers as TU;
use wstmart\common\model\TaxInvoiceUsers as TAX;
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
 * 商家入驻控制器
 */
class Shopapplys extends Base{
    // 前置方法执行列表
    protected $beforeActionList = [
        'checkAuth',
    ];
    /**
     * 跳去商家入驻页面
     */
    public function index(){
        $m = new M();
        $um = new UM();
        $userId = (int)session('WST_USER.userId');
        // 获取是否已经填写商家入驻
        $isApply = $m->isApply();
        $rs = $um->getFieldsById($userId,'userPhone,cardNumber');
        $this->assign('isApply',$isApply);
        $this->assign('userPhone',$rs['userPhone']);
        $this->assign('cardNumber',$rs['cardNumber']);

        $apply = model('shops')->getShopApply();
        $this->assign('apply',$apply);
        if( $apply['applyStatus'] == 1 || $apply['applyStatus'] == 1  ){
            return $this->fetch('users/shopapplys/shop_applys_result');
        }else{
            return $this->fetch('users/shopapplys/shop_applys_one');
        }
    }

    /**
     * 保存商家入驻
     */
    public function add(){
        $m = new M();
        return $m->add();
    }
    public function tax_auth(){
        $userId = (int)session('WST_USER.userId');
        $Taxuser = new TU();
        $rs = $Taxuser->getFieldsById($userId);
        $m = new \wstmart\admin\model\Articles();
        $artice = $m->get(436);
        $this->assign('users',$rs);
        $this->assign('articeInfo',$artice);
        $this->assign('isApply',0);

        return $this->fetch('users/shopapplys/tax_auth');

    }
    //开票认证
    public function save_tax_auth(){
        $userId = (int)session('WST_USER.userId');
        $tax = new TAX();
        $Taxuser = new TU();
        $rs = $Taxuser->getFieldsById($userId);
        $data = [
            'xm'=>input('xm'),
            'mobile'=>input('mobile'),
            'sfzhm'=>input('sfzhm'),
            'email'=>input('email'),
            'gjdqdm'=>input('gjdqdm'),
            'ssjAddress'=>input('ssjAddress'),
            'qxjAddress'=>input('qxjAddress'),
            'address'=>input('address'),
            'id'=>input('id/d',0)
        ];
        $Taxuser->add($data);

        //$res = $tax->taxBusinessToken(['cardNumber'=>$rs['cardNumber']]);
        $res['code']=0;
        if($res['code']!=0){
            return WSTReturn('认证失败',0);
        }else{
//            $res['token'];
            $params=[
                'sfzhm'=>$rs['sfzhm'],
                'xm'=>$rs['xm'],
                'zjlx'=>$rs['zjlx'],
                'mobile'=>$rs['mobile'],
                'gjdqdm'=>$rs['gjdqdm'],
                'ssjAddress'=>$rs['ssjAddress'],
                'qxjAddress'=>$rs['qxjAddress'],
                'address'=>$rs['address'],
                'email'=>$rs['email']
            ];
            $jsonParams = json_encode($params);
            $key='123456';
            $url = '/dzswj_wx/user/verify.html';
//            $jsonParams = 'json';//$tax->encrypt($jsonParams,$key);
            $data = [
                'appid'=>'1',
                'token'=>'2',
                'params'=>$jsonParams,
            ];
//            return ['url'=>$url,'jsonData'=>$data];
            //$this->assign('url',$url);
            //$this->assign('jsonData',$data);
            return ['status'=>1,'msg'=>'认证成功','url'=>$url,'jsondata'=>$data];
        }
    }
    public function xieyi(){
        return $this->fetch('users/shopapplys/xieyi');
    }
    public function yhhcxy(){
        $data = [
            'nsrsbh'=>input('nsrsbh'),
            'nsrmc'=>input('nsrmc'),
            'yhxybh'=>input('yhxybh'),
            'xylx'=>input('xylx'),
            'yxqq'=>input('yxqq'),
            'yxqz'=>input('yxqz'),
            'xynr'=>input('xynr'),
        ];
        $tax = new TAX();
        $tax->returnTreaty($data);
    }

}
