<?php
namespace addons\distribut\controller;
use Env;
use think\addons\Controller;
use addons\distribut\model\Distribut as M;

class Distribut extends Controller{

    public function __construct(){
        parent::__construct();
        $this->assign("v",WSTConf('CONF.wstVersion')."_".WSTConf('CONF.wstPCStyleId'));
    }
   
    
    /*************************用户中心*****************************/
    /**
     * 加载店铺分销设置
     */
    public function userDistributUsers(){

        return $this->fetch("/home/users/user_list");
    }
    
    /**
     * 获取用户分成列表
     */
    public function queryMineUsers(){
        $m = new M();
        $rs = $m->queryMineUsers();
        $rs['status'] = 1;
        return $rs;
    }
    
    /**
     * 加载店铺分销设置
     */
    public function userDistributMoneys(){
         
        return $this->fetch("/home/users/money_list");
    }
    
    /**
     * 获取用户分成列表
     */
    public function queryUserMoneys(){
        $m = new M();
        $rs = $m->queryUserMoneys();
        $rs['status'] = 1;
        return $rs;
    }
    
    /*************************商家中心*****************************/
    
    /**
     * 加载店铺分销设置
     */
    public function shopDistributCfg(){
        $m = new M();
        $rs = $m->getDistributCfg();
        $this->assign("object",$rs);
        return $this->fetch("/shop/distribut_cfg");
    }
    
    /**
     * 保存店铺设置
     */
    public function saveCfg(){
        $m = new M();
        $rs = $m->saveCfg();
        return $rs;
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function shopDistributGoods(){
        $this->assign("p",(int)input("p"));
        return $this->fetch("/shop/goods_list");
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function queryDistributGoods(){
        $m = new M();
        $rs = $m->querydistributgoods();
        return WSTGrid($rs);
    }

    /**
     * 获取店铺分成列表
     */
    public function queryDistributMoneys(){
        $m = new M();
        $rs = $m->queryDistributMoneys();
        return WSTGrid($rs);
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function shopDistributMoneys(){
        $this->checkAuth();
        $this->assign("p",(int)input("p"));
        return $this->fetch("/shop/money_list");
    }
    
    /*******************************admin*********************************/
    
    /**
     * 获取分销店铺列表
     */
    public function adminDistributShops(){
        $this->checkAdminPrivileges();
        $this->assign("p",(int)input("p"));
        return $this->fetch("/admin/shop_list");
    }
    
    /**
     * 获取分销店铺列表
     */
    public function queryAdminDistributShops(){
        $this->checkAdminPrivileges();
        $m = new M();
        $rs = $m->queryAdminDistributShops();
        $rs['status'] = 1;
        return WSTGrid($rs);
    }
    
    /**
     * 获取分销商品列表
     */
    public function adminDistributGoods(){
        $this->checkAdminPrivileges();
        $this->assign("p",(int)input("p"));
        return $this->fetch("/admin/goods_list");
    }
    
    /**
     * 获取分销商品列表
     */
    public function queryAdminDistributGoods(){
        $this->checkAdminPrivileges();
        $m = new M();
        $rs = $m->queryAdminDistributGoods();
        $rs['status'] = 1;
        return WSTGrid($rs);
    }
    
    /**
     * 获取分销佣金列表
     */
    public function adminDistributMoneys(){
        $this->checkAdminPrivileges();
        $this->assign("p",(int)input("p"));
        return $this->fetch("/admin/money_list");
    }
    
    /**
     * 获取分销佣金列表
     */
    public function queryAdminDistributMoneys(){
        $this->checkAdminPrivileges();
        $m = new M();
        $rs = $m->queryAdminDistributMoneys();
        $rs['status'] = 1;
        return WSTGrid($rs);
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function adminDistributUsers(){
        $this->checkAdminPrivileges();
        $this->assign("p",(int)input("p"));
        return $this->fetch("/admin/user_list");
    }
    
    /**
     * 获取分销佣金列表
     */
    public function queryAdminDistributUsers(){
        $this->checkAdminPrivileges();
        $m = new M();
        $rs = $m->queryAdminDistributUsers();
        $rs['status'] = 1;
        return WSTGrid($rs);
    }
    
    /**
     * 获取推广用户子列表
     */
    public function adminDistributChildUsers(){
        $this->checkAdminPrivileges();
        $this->assign("userId",input("userId/d"));
        $this->assign("p",(int)input("p"));
        return $this->fetch("/admin/user_child_list");
    }
    
    /**
     * 获取推广用户子列表
     */
    public function queryAdminDistributChildUsers(){
        $this->checkAdminPrivileges();
        $m = new M();
        $rs = $m->queryAdminDistributChildUsers();
        $rs['status'] = 1;
        return WSTGrid($rs);
    }

    /**
     * 结算佣金统计报表
     */
    public function adminDistributMoneysReport(){
        $this->checkAdminPrivileges();
        $this->assign("startDate",date('Y-m-d',strtotime("-1month")));
        $this->assign("endDate",date('Y-m-d'));
        return $this->fetch("/admin/money_report");
    }
    
    /**
     * 结算佣金统计报表
     */
    public function adminDistributMoneysByPage(){
        $this->checkAdminPrivileges();
        $m = new M();
        $rs = $m->adminDistributMoneysByPage();
        return WSTGrid($rs);
    }

    /**
     * 导出结算佣金统计报表excel
     */
    public function toExportDistributMoneys(){
        $m = new M();
        $rs = $m->toExportDistributMoneys();
        $this->assign('rs',$rs);
    }
  
    /*******************************wechat*********************************/
    /**
     * 获取分销商品列表
     */
    public function wechatDistributGoods(){
        $this->assign("keyword", input('keyword'));
        return $this->fetch("/wechat/index/goods_list");
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function wechatDistributHome(){
        $this->checkAuth();
        $m = new M();
        $user = $m->getUserInfo();
        $cfg = $m->getAddonConfig();
        //分享信息
        $shareInfo= array(
            'title'=>$cfg["mallShareTitle"],
            'desc'=>WSTConf('CONF.mallName'),
            'link'=>url('wechat/index/index',array('shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true),
            'imgUrl'=>WSTConf('CONF.mallLogo')
        );
        $this->assign('shareInfo', $shareInfo);
        $this->assign('user', $user);
        return $this->fetch("/wechat/users/distribut_home");
    }
    
    
    /**
     * 获取用户列表
     */
    public function wechatDistributUsers(){
        $this->checkAuth();
        $m = new M();
        $user = $m->getUser();;
        $this->assign('user', $user);
        return $this->fetch("/wechat/users/user_list");
    }
    
    /**
     * 获取用户列表
     */
    public function queryWechatDistributUsers(){
        $this->checkAuth();
        $m = new M();
        $rs = $m->queryMineUsers();
        $rs['status'] = 1;
        return $rs;
    }
    
    /**
     * 获取佣金列表
     */
    public function wechatDistributMoneys(){
        $this->checkAuth();
        $m = new M();
        $user = $m->getUser();
        $this->assign('user', $user);
        return $this->fetch("/wechat/users/money_list");
    }
    
    /**
     * 获取佣金列表
     */
    public function queryWechatDistributMoneys(){
        $this->checkAuth();
        $m = new M();
        $rs = $m->queryUserMoneys();
        $rs['status'] = 1;
        return $rs;
    }
    
    /*******************************mobile*********************************/
    /**
     * 获取分销商品列表
     */
    public function mobileDistributGoods(){
        $this->assign("keyword", input('keyword'));
        return $this->fetch("/mobile/index/goods_list");
    }
    
    /**
     * 获取店铺分销商品列表
     */
    public function mobileDistributHome(){
        $this->checkAuth();
        $m = new M();
        $user = $m->getUserInfo();
        $cfg = $m->getAddonConfig();
        //分享信息
        $shareInfo= array(
            'title'=>$cfg["mallShareTitle"],
            'desc'=>WSTConf('CONF.mallName'),
            'link'=>url('mobile/index/index',array('shareUserId'=>base64_encode((int)session('WST_USER.userId'))),true,true),
            'imgUrl'=>WSTConf('CONF.mallLogo')
        );
        $this->assign('shareInfo', $shareInfo);
        $this->assign('user', $user);
        return $this->fetch("/mobile/users/distribut_home");
    }
    
    
    /**
     * 获取用户列表
     */
    public function mobileDistributUsers(){
        $this->checkAuth();
        $m = new M();
        $user = $m->getUser();;
        $this->assign('user', $user);
        return $this->fetch("/mobile/users/user_list");
    }
    
    /**
     * 获取用户列表
     */
    public function queryMobileDistributUsers(){
        $this->checkAuth();
        $m = new M();
        $rs = $m->queryMineUsers();
        $rs['status'] = 1;
        return $rs;
    }
    
    /**
     * 获取佣金列表
     */
    public function mobileDistributMoneys(){
        $this->checkAuth();
        $m = new M();
        $user = $m->getUser();
        $this->assign('user', $user);
        return $this->fetch("/mobile/users/money_list");
    }
    
    /**
     * 获取佣金列表
     */
    public function queryMobileDistributMoneys(){
        $this->checkAuth();
        $m = new M();
        $rs = $m->queryUserMoneys();
        $rs['status'] = 1;
        return $rs;
    }

    /**
     * 生成海报【微信】
     */
    public function wxCreatePoster(){
        $userId = (int)session("WST_USER.userId");
        $isNew = (int)input("isNew",0);
        $subDir =  'upload/shares/'.date("Y-m");
        WSTCreateDir(WSTRootPath().'/'.$subDir);
        $outImg = $subDir.'/share_img_wx_'.$userId.'.png';
        $shareImg = WSTRootPath().'/'.$outImg;
        if($isNew==0){
            if (file_exists($shareImg)) {
                return WSTReturn("",1,["shareImg"=>$outImg]);
            }
        }
        require Env::get('root_path') . 'extend/qrcode/phpqrcode.php';
        $m = new M();
        $qr_url = url('wechat/Index/index',array('shareUserId'=>base64_encode($userId)),true,true);//二维码内容   
        $errorCorrectionLevel = 'L';//容错级别   
        $matrixPointSize = 10;//生成图片大小   
        //生成二维码图片   
        $qrcode = new \QRcode();
        $today = date("Ymd");
        $qr_code = WSTRootPath().'/'.$subDir.'/qrcode_wx_'.$today.'_'.$userId.'.png';
        $qrcode->png($qr_url, $qr_code, $errorCorrectionLevel, $matrixPointSize, 0);   
        $rs = $m->createPoster($userId,$qr_code,$outImg);
        return $rs;
    }

    /**
     * 生成海报【手机】
     */
    public function moCreatePoster(){
        $userId = (int)session("WST_USER.userId");
        $isNew = (int)input("isNew",0);
        $subDir =  'upload/shares/'.date("Y-m");
        WSTCreateDir(WSTRootPath().'/'.$subDir);
        $outImg = $subDir.'/share_img_mo_'.$userId.'.png';
        $shareImg = WSTRootPath().'/'.$outImg;
        if($isNew==0){
            if (file_exists($shareImg)) {
                return WSTReturn("",1,["shareImg"=>$outImg]);
            }
        }
        require Env::get('root_path') . 'extend/qrcode/phpqrcode.php';
        $m = new M();
        $qr_url = url('mobile/Index/index',array('shareUserId'=>base64_encode($userId)),true,true);//二维码内容   
        $errorCorrectionLevel = 'L';//容错级别   
        $matrixPointSize = 10;//生成图片大小   
        //生成二维码图片   
        $qrcode = new \QRcode();
        $today = date("Ymd");
        $qr_code = WSTRootPath().'/'.$subDir.'/qrcode_mo_'.$today.'_'.$userId.'.png';
        $qrcode->png($qr_url, $qr_code, $errorCorrectionLevel, $matrixPointSize, 0);   
        $rs = $m->createPoster($userId,$qr_code,$outImg);
        return $rs;
    }

    /**
     * 生成海报【小程序】
     */
    public function weCreatePoster(){

        $userId = model('weapp/index')->getUserId();
        $isNew = (int)input("isNew",0);
        $subDir =  'upload/shares/'.date("Y-m");
        WSTCreateDir(WSTRootPath().'/'.$subDir);
        $outImg = $subDir.'/share_img_we_'.$userId.'.png';
        $shareImg = WSTRootPath().'/'.$outImg;
        if($isNew==0){
            if (file_exists($shareImg)) {
                return jsonReturn("",1,["shareImg"=>$outImg]);
            }
        }
        $weapp = new \weapp\WSTWeapp(WSTConf('CONF.weAppId'),WSTConf('CONF.weAppKey'));
        $tokenId = $weapp->getToken();
       
        $parm['scene'] = $userId;
        $parm['page'] = input('pages');//上线时解除注释
        $parm['width'] = 200;
        $url='https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$tokenId;
        $qrdata = $weapp->http($url,json_encode($parm));
        $today = date("Ymd");
        $qr_code = WSTRootPath().'/'.$subDir.'/qrcode_mo_'.$today.'_'.$userId.'.png';// 小程序码
        file_put_contents( $qr_code,$qrdata );
        
        $m = new M();
        $rs = $m->createPoster($userId,$qr_code,$outImg);

        return jsonReturn($rs['msg'],$rs['status'],$rs['data']);
    }

    /**
     * 获取分销配置
     */
    public function weDistributConfig(){
        $userId = model('weapp/index')->getUserId();
        $m = new M();
        $rs = $m->getConf("Distribut"); 
        $rs["shopIsDistribut"] = 0;
        $conf = $m->getDistributCfg((int)input("shopId"));
        if($conf["isDistribut"]==1){
           $rs["shopIsDistribut"] = 1;
        }
        $rs["shareUserId"] = $userId;
        return jsonReturn('success',1,$rs);
    }

    public function weShareInfo(){
        $userId = model('weapp/index')->getUserId();
        $m = new M();
        $cfg = $m->getAddonConfig();
        //分享信息
        $shareInfo= array(
            'title'=>$cfg["mallShareTitle"],
            'desc'=>WSTConf('CONF.mallName'),
            'shareUserId'=> $userId,
            'imgUrl'=>WSTConf('CONF.mallLogo')
        );
        return jsonReturn('success',1,$shareInfo);
    }

    /**
     * 获取分销用户信息
     */
    public function weGetUser(){
        $userId = model('weapp/index')->getUserId();
        $m = new M();
        $user = $m->getUser($userId);
        return jsonReturn('success',1,$user);
    }

    /**
     * 获取用户列表
     */
    public function weQueryDistributUsers(){
        $userId= model('weapp/index')->getUserId();
        $m = new M();
        $rs = $m->queryMineUsers($userId,0);
        $rs['status'] = 1;
        return jsonReturn('success',1,$rs);
    }
    
    /**
     * 获取佣金列表
     */
    public function weQueryDistributMoneys(){
        $userId= model('weapp/index')->getUserId();
        $m = new M();
        $rs = $m->queryUserMoneys($userId);
        $rs['status'] = 1;
        return jsonReturn('success',1,$rs);
    }
}