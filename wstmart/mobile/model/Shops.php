<?php
namespace wstmart\mobile\model;
use wstmart\common\model\Shops as CShops;
use think\Db;
use wstmart\home\validate\ShopBase as VShopBase;

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
 * 门店类
 */
class Shops extends CShops{
    /**
     * 推荐列表
     */
    public function tjList() {
        $rs = $this->alias('s')
        ->join('__GOODS__ gs','gs.shopId = s.shopId','left')
        ->join('__GOODS_CATS__ cs','gs.goodsCatId = cs.catId','left')
        ->where([['s.isHot','=','1'],['gs.isSale','=','1']])->field('s.shopImg,s.shopId,s.shopName,cs.catName')->select();
        
        $return = [];
        foreach ($rs as $k => $v) {
            $return[$v['shopId']]['shopId'] = $v['shopId'];
            $return[$v['shopId']]['shopName'] = $v['shopName'];
            $return[$v['shopId']]['shopImg'] = $v['shopImg'];
            if (!isset($return[$v['shopId']['catName']])) {
                $return[$v['shopId']]['catName'][] = $v['catName'];
            } else if (!in_array($v['catName'], $return[$v['shopId']])) {
                $return[$v['shopId']]['catName'][] = $v['catName'];
            }
        }
        foreach ($return as $m => $n) {
            $return[$m]['catName'] = array_unique($n['catName']);
        }

        foreach ($return as $t_k => $t_v) {
            $return[$t_k]['catName'] = implode('/', $t_v['catName']);
            $return[$t_k]['count'] = Db::name('goods')->where(['isSale'=>1,'shopId' => $t_k])->count();
        }
        return array_values($return);
    }
    /**
     * 店铺街列表
     */
    public function pageQuery($pagesize){
        $lng = input("longitude");
        $lat = input("latitude");
        if($lng=='' && $lat==''){
            $lng = 0;
            $lat = 0;
        }
        $catId = (int)input("id");
        $keyword = input("keyword");
        $condition = input("condition");
        $distance = input("distance");
        $desc = input("desc");
        $totalScore = input('totalScore');
        $shopNameOne = input("shopNameOne");
        $datas = array('sort'=>array('1'=>'ss.totalScore/ss.totalUsers'),'desc'=>array('desc','asc'));
        $datas1 = array('sort'=>array('1'=>'distince'),'desc'=>array('desc','asc'));
        $rs = $this->alias('s');
        $tol = $this->alias('s');
        $where = [];
		$type = input("type");
		//if($type!=1)
		//	$type = 0;		// 暂时只允许0和1
		if($type==="0" || $type==="1")
			$where[] = ['s.shopType','=',$type];
        $where[] = ['s.dataFlag','=',1];
        $where[] = ['s.shopStatus','=',1];
        $where[] = ['s.applyStatus','=',2];
        if($keyword!='')$where[] = ['s.shopName','like','%'.$keyword.'%'];
        if( !empty( $shopNameOne ) )
        {
            $where[] = ['s.shopNameOne','=',$shopNameOne];
        }
        if($catId>0){
            $rs->join('__CAT_SHOPS__ cs','cs.shopId = s.shopId','left');
            $tol->join('__CAT_SHOPS__ cs','cs.shopId = s.shopId','left');
            $where[] = ['cs.catId','=',$catId];
        }
        $order = "s.shopSort asc";
        if($condition == 1){
            $order = "{$datas['sort'][$condition]} {$datas['desc'][$desc]}";
        }
        if($condition == 2){
            $order = "{$datas1['sort'][1]} {$datas['desc'][$desc]}";
        }
        $shopIds = $this->filterByCondition();
        if(!empty($shopIds)){
            $where[] = ['s.shopId','in',$shopIds];
        }
        $where1 = [];
        if($totalScore != ''){
            $totalScore = explode('_',$totalScore);
            $maxScore = (float)(($totalScore[1])*5/100);
            $minScore = (float)(($totalScore[0])*5/100);
            $where1[] = '(ss.totalScore/3)/ss.totalUsers >='.$minScore;
            $where1[] = '(ss.totalScore/3)/(case when ss.totalUsers = 0 then 1 else ss.totalUsers end) <= '.$maxScore;
        }
            $rs->fieldRaw('(ss.totalScore/3)/(case when ss.totalUsers = 0 then 1 else ss.totalUsers end) as aa');
        $page = $rs->join('__SHOP_SCORES__ ss','ss.shopId = s.shopId','left')
        ->where($where)->where(implode(' AND ',$where1))->orderRaw($order)
        ->fieldRaw('s.shopId,s.shopImg,s.shopName,s.shopCompany,ss.totalScore,ss.totalUsers,ss.goodsScore,ss.goodsUsers,ss.serviceScore,ss.serviceUsers,ss.timeScore,ss.timeUsers,s.areaIdPath')
        ->paginate($pagesize)->toArray();
        $totalShop = $tol->join('__SHOP_SCORES__ ss','ss.shopId = s.shopId','left')
                        ->where($where)->where(implode(' AND ',$where1))
                        ->field('ss.totalScore,ss.totalUsers')
                        ->select();
        foreach ($page['data'] as $key =>$v){
            //商品列表
            $goods = model('Tags')->listShopGoods('recom',$v['shopId'],0,4);
            $page['data'][$key]['goods'] = $goods;
        }
       // $page['a'] = $a;
        if(empty($page['data']))return $page;
        $shopIds = [];
        $areaIds = [];
        $totalScores = [];
        foreach ($totalShop as $key => $v) {
            if($v["totalUsers"] != 0){
               $totalScores[] = round(($v["totalScore"]/3)/$v["totalUsers"],8);
            }else{
               $totalScores[] = 5;
            }
        }
        foreach ($page['data'] as $key =>$v){
            $shopIds[] = $v['shopId'];
            $tmp = explode('_',$v['areaIdPath']);
            $areaIds[] = $tmp[1];
            $page['data'][$key]['areaId'] = $tmp[1];
            $page['data'][$key]['totalScore'] = WSTScore($v["totalScore"]/3, $v["totalUsers"]==0?1: $v["totalUsers"]);
            $page['data'][$key]['goodsScore'] = WSTScore($v['goodsScore'],$v['goodsUsers']);
            $page['data'][$key]['serviceScore'] = WSTScore($v['serviceScore'],$v['serviceUsers']);
            $page['data'][$key]['timeScore'] = WSTScore($v['timeScore'],$v['timeUsers']);
        }
        $maxScore = max($totalScores)<5?((max($totalScores)/5)*100):100;
        $minScore = min($totalScores)>0?(((min($totalScores)-0.01)/5)*100):0;
        $scores = [];
        //区间跨度
        $span = ($maxScore - $minScore) / 3;
        for($i=1;$i<=3;$i++){
            if(($minScore+$i*$span)>100) {
                $tmpMinVal = round(($minScore+($i-1)*$span),0);
                $scores[$tmpMinVal."_100"] = $tmpMinVal."%-100%";
                break;
            }
            $tmpMinVal = round(($minScore+($i-1)*$span),0);
            $tmpMaxVal = round(($minScore+$i*$span),0);
            $scores[$tmpMinVal."_".$tmpMaxVal] = $tmpMinVal."%-".$tmpMaxVal."%";
        }
        if($totalScore == '' && count($scores)>1){
            $page['screen']['scores'] = $scores;
        }
        $page['minScore'] = $minScore.'-'.$maxScore;
        $rccredMap = [];
        $goodsCatMap = [];
        $areaMap = [];
        //认证、地址、分类
        if(!empty($shopIds)){
            $rccreds = Db::name('shop_accreds')->alias('sac')->join('__ACCREDS__ a','a.accredId=sac.accredId and a.dataFlag=1','left')
                         ->where([['shopId','in',$shopIds]])->field('sac.shopId,accredName,accredImg,a.accredId')->select();
            $accredIds = [];
            $accreds = [];
            foreach ($rccreds as $v){
                $rccredMap[$v['shopId']][] = $v;
                if(!in_array($v['accredId'],$accredIds)){
                    $accredIds[] = $v['accredId'];
                    $accreds[] = $v;
                }
            }
            $page['screen']['accreds'] = $accreds;
            $goodsCats = Db::name('cat_shops')->alias('cs')->join('__GOODS_CATS__ gc','cs.catId=gc.catId and gc.dataFlag=1','left')
                           ->where([['shopId','in',$shopIds]])->field('cs.shopId,gc.catName')->select();
            foreach ($goodsCats as $v){
                $goodsCatMap[$v['shopId']][] = $v['catName'];
            }
            $areas = Db::name('areas')->alias('a')->join('__AREAS__ a1','a1.areaId=a.parentId','left')
                       ->where([['a.areaId','in',$areaIds]])->field('a.areaId,a.areaName areaName2,a1.areaName areaName1')->select();
            foreach ($areas as $v){
                $areaMap[$v['areaId']] = $v;
            }         
        }
        foreach ($page['data'] as $key =>$v){
            $page['data'][$key]['lng'] = $lng;
            $page['data'][$key]['lat'] = $lat;
            $page['data'][$key]['accreds'] = (isset($rccredMap[$v['shopId']]))?$rccredMap[$v['shopId']]:[];
            $page['data'][$key]['catshops'] = (isset($goodsCatMap[$v['shopId']]))?implode(',',$goodsCatMap[$v['shopId']]):'';
            $page['data'][$key]['areas']['areaName1'] = (isset($areaMap[$v['areaId']]['areaName1']))?$areaMap[$v['areaId']]['areaName1']:'';
            $page['data'][$key]['areas']['areaName2'] = (isset($areaMap[$v['areaId']]['areaName2']))?$areaMap[$v['areaId']]['areaName2']:'';
        }
        return $page;
    }
    /**
    * 自营店铺楼层
    */
    public function getFloorData(){
        $limit = (int)input('post.currPage');
        $cacheData = cache('WX_SHOP_FLOOR'.$limit);
        if($cacheData)return $cacheData;
        $rs = Db::name('shop_cats')->where(['dataFlag'=>1,'isShow'=>1,'parentId'=>0,'shopId'=>1])->field('catId,catName')->order('catSort asc,catId asc')->limit($limit,1)->select();
        if($rs){
            $rs= $rs[0];
            $goods = Db::name('goods')->where(['shopCatId1'=>$rs['catId'],'dataFlag'=>1,'isSale'=>1,'goodsStatus'=>1])->field('goodsId,goodsName,goodsImg,shopPrice,marketPrice,saleNum')->order('isHot desc')->limit(4)->select();
            $rs['goods'] = $goods;
            $rs['currPage'] = $limit;
        }
        cache('WX_SHOP_FLOOR'.$limit,$rs,86400);
        return $rs;
    }
    /**
    * 根据订单id 获取店铺名称
    */
    public function getShopName($oId){
        return $this->alias('s')
                    ->join('__ORDERS__ o',"s.shopId=o.shopId",'inner')
                    ->where("o.orderId=$oId")
                    ->value('s.shopName');

    }
    /*过滤条件获取符合店铺Id*/
    public function filterByCondition(){
        $accredId = input('accredId');
        $res = [];
        $shopIds = Db::name('shop_accreds')->where(['accredId'=>$accredId])->field('shopId')->select();
        foreach ($shopIds as $key => $value) {
            $res[] = $value['shopId'];
        }
        return $res;
    }

    /**
     * 保存入驻资料
     */
    public function saveStep($data = []){
        $userId = (int)session('WST_USER.userId');
        $flowId = (int)input('flowId');
        //判断是否存在入驻申请
        $shops = $this->alias('s')->join('__SHOP_USERS__ sur','s.shopId=sur.shopId','left')->field('s.*')->where(['sur.userId'=>$userId])->find();
        if(!empty($shops))return WSTReturn('请勿重复申请入驻');
        $shops = $this->where('userId',$userId)->find();
        $shopId = 0;
        if(empty($shops)){
            // type为1表示个人入驻
            if(isset($data['shopType']) && $data['shopType']==1)
                $shopType = 1;
            else $shopType = 0;
            $shop = ['userId'=>$userId,'applyStatus'=>0,'shopType'=>$shopType];
            $this->save($shop);
            $exData['shopId'] = $this->shopId;
            Db::name('shop_extras')->insert($exData);
            $shopId = $this->shopId;
        }else{
            $shopId = $shops['shopId'];
        }
        //$joinType = session('join_type');
        // type为1表示个人入驻
        if(isset($data['shopType']) && $data['shopType']==1)
            $joinType = 1;
        else $joinType = 0;
        if($shops['applyStatus']==1)return WSTReturn('您的入驻申请正在审核，请勿重复提交');
        if($shops['applyStatus']==2)return WSTReturn('请勿重复申请入驻');

        // 保存流程id
        $applyStep = ['applyStep'=>$flowId];
        if($flowId == 1){	//处于第一步时还可调整店铺类型
            $applyStep['shopType'] = $joinType;
        }
        $this->save($applyStep,['shopId'=>$shopId]);
        //获取完整流程信息
        $shopFlows = $this->getShopFlowDatas($flowId);

        //新增入驻申请
        // 先遍历前台传来的data,根据shop_base表判断是属于shops表还是shop_extras表，分别用两个数组保存
        $shopsData = [];
        $shopExtrasData = [];
        // 保存上传图片的路径，用来启用上传图片
        $uploadShopsImgPath = [];
        $uploadShopExtrasImgPath = [];
        $unsetField = [];
        $goodsCats = [];
        foreach($data as $k => $v){
            $field = Db::name('shop_bases')->where(['fieldName'=>$k,'dataFlag'=>1])->field('fieldName,fieldType,fieldAttr,isShopsTable,dateRelevance,isShow')->find();
            if($field['isShopsTable']==1){
                // 属于shops表
                $shopsData[$k] = $v;
                //获取地区
                if($field['fieldType'] == 'other' && $field['fieldAttr'] == 'area'){
                    $areaIds = model('Areas')->getParentIs($shopsData[$k]);
                    if(!empty($areaIds))$shopsData[$k] = implode('_',$areaIds)."_";
                }
                if($field['fieldType'] == 'other' && $field['fieldAttr'] == 'file'){
                    $uploadShopsImgPath[] = $data[$k];
                }
            }else{
                // 属于shop_extras表
                $shopExtrasData[$k] = $v;
                //获取地区
                if($field['fieldType'] == 'other' && $field['fieldAttr'] == 'area'){
                    $areaIds = model('Areas')->getParentIs($shopExtrasData[$k]);
                    if(!empty($areaIds))$shopExtrasData[$k] = implode('_',$areaIds)."_";
                }
                if($field['fieldType'] == 'other' && $field['fieldAttr'] == 'file'){
                    $uploadShopExtrasImgPath[] = $data[$k];
                }
                if($field['fieldType'] == 'other' && $field['fieldAttr'] == 'date' && $field['dateRelevance']){
                    $dateRelevance = explode(',',$field['dateRelevance']);
                    // 如果选择了长期，就删除字段的结束日期
                    if($data[$dateRelevance[1]]==1){
                        $unsetField[] = $dateRelevance[0];
                    }
                }
                //经营范围
                if(!empty($data['goodsCatIds']))$goodsCats = explode(',',$data['goodsCatIds']);
            }
        }

        // 删除无需入库的字段
        foreach($shopExtrasData as $k => $v){
            if(in_array($k,$unsetField)){
                unset($shopExtrasData[$k]);
            }
        }

        $validate = new VShopBase();
        $validate->setRuleAndMessage($shopsData);
        $validate->setRuleAndMessage($shopExtrasData);
        Db::startTrans();
        try{
            $shopsData['shopId'] = $shopId;
            //$shopsData['applyStatus'] = 1;
            $shopExtrasData['shopId'] = $shopId;
            if(!$validate->scene('add')->check($data))return WSTReturn($validate->getError());
            //首字母
            if( isset( $shopsData['shopName']  ) ){
                $shopsData['shopNameOne'] = WSTGetFirstCharter($shopsData['shopName'] );
            }

            //判断是不是最后一个表单环节了
            $flows = $shopFlows['flows'];
            if($flows[count($flows)-1]['flowId']==$shopFlows['nextStep']['flowId']){
                $shopsData['applyStatus'] = 1;
                $shopsData['applyTime'] = date('Y-m-d H:i:s');
            }
            $this->allowField(true)->save($shopsData,['shopId'=>$shopId]);
            foreach($uploadShopsImgPath as $v){
                //启用上传图片
                WSTUseResource(0, $this->shopId, $v ,'shops');
            }
            $seModel = model('ShopExtras');
            $seModel->allowField(true)->save($shopExtrasData,['shopId'=>$shopId]);
            $extraId = $seModel->where(['shopId'=>$shopId])->value('id');// 获取主键
            if($joinType!=1) {	// 非个人入驻
                foreach($uploadShopExtrasImgPath as $v){
                    //启用上传图片
                    WSTUseResource(0, $extraId, $v ,'shopextras');
                }
            }
            if($goodsCats){
                foreach ($goodsCats as $v){
                    if((int)$v>0)Db::name('cat_shops')->insert(['shopId'=>$shopId,'catId'=>$v]);
                }
            }
            Db::commit();
            session('tmpApplyStep',$shopFlows['nextStep']['flowId']);
            return WSTReturn('保存成功', 1, ['nextflowId'=>$shopFlows['nextStep']['flowId']]);
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('保存失败'.$e->getMessage(),-1);
        }
    }

    /**
     * 获取商家入驻资料
     */
    public function getShopApply(){
        $userId = (int)session('WST_USER.userId');
        $rs = $this->alias('s')->join('__SHOP_EXTRAS__ ss','s.shopId=ss.shopId','inner')
            ->where('s.userId',$userId)
            ->find();
        if(!empty($rs)){
            $rs = $rs->toArray();
            $goodscats = Db::name('cat_shops')->where('shopId',$rs['shopId'])->select();
            $rs['catshops'] = [];
            foreach ($goodscats as $v){
                $rs['catshops'][$v['catId']] = true;
            }
            $rs['taxRegistrationCertificateImgVO'] = ($rs['taxRegistrationCertificateImg']!='')?explode(',',$rs['taxRegistrationCertificateImg']):[];
        }else{
            $rs = [];
            $data1 = $this->getEModel('shops');
            $data2 = $this->getEModel('shop_extras');
            $rs = array_merge($data1,$data2);
            $rs['taxRegistrationCertificateImgVO'] = [];
        }
        return $rs;
    }

    /**
     * 获取商家入驻流程
     */
    public function getShopFlowDatas($flowId = 0){
        $data = ['flows'=>[],'prevStep'=>[],'nextStep'=>[]];
        $data['flows'] = Db::name('shop_flows')->where(['isShow'=>1,'dataFlag'=>1])->order('sort asc')->select();
        $flowNum = count($data['flows']);
        $flowId = ($flowId==0)?$data['flows'][0]['flowId']:$flowId;
        foreach ($data['flows'] as $key => $v) {
            if($key>0){
                $data['prevStep'] =  $data['flows'][$key-1];
            }
            if($v['flowId'] == $flowId){
                $data['currStep'] = $v;
                if(($flowNum-1)>$key){
                    $data['nextStep'] = $data['flows'][$key+1];
                }
                break;
            }
        }
        return $data;
    }

    /**
     * 获取店铺指定字段
     */
    public function getFieldsById($shopId,$fields){
        return $this->where(['shopId'=>$shopId,'dataFlag'=>1])->field($fields)->find();
    }


    /*
     * 获取单个入驻流程里的字段信息
     */
    public function getFlowFieldsById($id){
        return Db::name('shop_bases')->where(['flowId'=>$id,'dataFlag'=>1])->order('fieldSort asc,id asc')->select();
    }
}
