<?php
namespace wstmart\weapp\behavior;
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
 * 初始化小程序消息模板
 */
class InitWeappMessges
{
    public function run($params){
        $tpl = WSTMsgTemplates($params['CODE']);
        if(!$tpl)return;
        $userType = (isset($params['userType']) && $params['userType']==3)?3:0;
        $userId = $params['userId'];
        if($userType==3){
            $user = Db::name('staffs')->where(['staffId'=>$userId,'staffStatus'=>1])->field('weOpenId')->find();
        }else{
            $user = Db::name('users')->where('userId',$userId)->field('weOpenId')->find();
        }
        if($user['weOpenId']=='')return;
        //数据封装
        $data = [];
        $data['touser'] = $user['weOpenId'];
        $data['template_id'] = $tpl['tplExternaId'];
        if(isset($params['URL']) && $params['URL'] !='')$data['page'] = $params['URL'];
        $data['form_id'] = $params['form_id'];
		$data['data'] = [];
        if(!empty($tpl['params'])){
            foreach($tpl['params'] as $key =>$v){
                foreach($params['params'] as $pkey =>$pv){
                   $v['fieldVal'] = str_replace('${'.$pkey.'}',$pv,$v['fieldVal']);
                }
                $tpl['params'][$key] = $v;
            }
        }
        foreach($tpl['params'] as $key =>$v){
            $data['data'][$v['fieldCode']] = array('value'=>urlencode($v['fieldVal']));
        }
        //屏蔽因发送微信有问题导致不能下单的情况
        try{
            $we = WSTWeapp();
            $rs = $we->sendWeTemplateMessage(urldecode(json_encode($data)));
        }catch (\Exception $e) {}
    }


    /**
     * 批量发送-需要自己判断微信openId并传入
     */
    public function batchRun($params){
        $tpl = WSTMsgTemplates($params['CODE']);
        if(!$tpl)return;
        $userType = (isset($params['userType']) && $params['userType']==3)?3:0;
        $userId = $params['userId'];
        if($userType==3){
            $user = Db::name('staffs')->where([['staffId','in',$userId],['staffStatus','=',1]])->field('wxOpenId')->select();
        }else{
            $user = Db::name('users')->where([['userId','in',$userId]])->field('wxOpenId')->select();
        }
        if(empty($user))return;
        for($i=0;$i<count($user);$i++){
            if($user[$i]['wxOpenId']=='')continue;
            //数据封装
            $data = [];
            $data['touser'] = $user[$i]['wxOpenId'];
            $data['template_id'] = $tpl['tplExternaId'];
            if(isset($params['URL']) && $params['URL'] !='')$data['url'] = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".WSTConf("CONF.wxAppId")."&redirect_uri=".rawurlencode( $params['URL'] )."&response_type=code&scope=snsapi_userinfo&state=".WSTConf("CONF.wxAppCode")."&connect_redirect=1#wechat_redirect";
            $data['data'] = [];
            if(!empty($tpl['params'])){
                foreach($tpl['params'] as $key =>$v){
                    foreach($params['params'] as $pkey =>$pv){
                       $v['fieldVal'] = str_replace('${'.$pkey.'}',$pv,$v['fieldVal']);
                    }
                    $tpl['params'][$key] = $v;
                }
            }
            foreach($tpl['params'] as $key =>$v){
                $data['data'][$v['fieldCode']] = array('value'=>urlencode($v['fieldVal']));
            }
            //屏蔽因发送微信有问题导致不能下单的情况
            try{
                $we = WSTWechat();
                $rs = $we->sendTemplateMessage(urldecode(json_encode($data)));
            }catch (\Exception $e) {}
        }
    }
}