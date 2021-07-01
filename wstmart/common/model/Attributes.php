<?php
namespace wstmart\common\model;
use wstmart\common\model\GoodsCats as M;
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
 * 商品属性分类
 */
use \think\Db;
use wstmart\common\validate\Attributes as validate;

class Attributes extends Base{

    protected $pk = 'attrId';
	/**
	 * 获取可供筛选的商品属性
	 */
	public function listQueryByFilter($catId){
		$m = new M();
		$ids = $m->getParentIs($catId);
		if(!empty($ids)){
			$catIds = [];
			foreach ($ids as $key =>$v){
				$catIds[] = $v;
			}
			// 取出分类下有设置的属性。
			$attrs = Db::name('attributes')->where(['isShow'=>1,'dataFlag'=>1])
					  ->where([['goodsCatId','in',$catIds],['attrType','<>',0]])
			          ->field('attrId,attrName,attrVal')->order('attrSort asc')->select();
			foreach ($attrs as $key =>$v){
			    $attrs[$key]['attrVal'] = explode(',',$v['attrVal']);
			}
			return $attrs;
		}
		return [];
	}
	/**
	* 根据商品id获取可供选择的属性
	*/
	public function getAttribute($goodsId){
		if(empty($goodsId))return [];
		$attrs = Db::name('attributes')->alias('a')
					  ->join('__GOODS_ATTRIBUTES__ ga','ga.attrId=a.attrId','inner')
					  ->field('ga.attrId,GROUP_CONCAT(distinct ga.attrVal) attrVal,a.attrName')
					  ->where(['a.isShow'=>1,'a.dataFlag'=>1])
					  	->where([['ga.goodsId','in',$goodsId],['a.attrType','<>',0]])
					  ->group('ga.attrId')
					  ->order('a.attrSort asc')
					  ->select();
		if(empty($attrs))return [];
		foreach ($attrs as $key =>$v){
			    $attrs[$key]['attrVal'] = explode(',',$v['attrVal']);
		}
		return $attrs;
	}

    /**
     * 通过商品id获取商品相关属性
     * @param $goodsId
     */
	public function getGoodsAttributeByGoodsId( $goodsId ){
	    if( empty( $goodsId ) )
	        return array();
       $result = Db::name('goods_attributes')->where(['goodsId'=>$goodsId])->select();
       if( $result ){
           return $result;
       }else{
           return array();
       }
    }

    /**
     * 通过id批量返回数据
     * @param $ids
     */
    public function getAttributeByIds($ids){
        if( empty( $ids ) )
            return [];
        $where = [];
        $where[] = ['attrId','in',$ids];
        return  Db::name('attributes')->where($where)->select();
    }

    /**
     * 转换
     * @param $goods_attr
     * @param $attr
     * @param $data
     * @return array
     */
    public function attridToAttrval( $goods_attr , $attr , &$data ){
        $data['goodsCz'] = "";
        $data['goodsTc'] = "";
        $data['zznb'] = "";
        $data['szyx'] = "";
        $data['zdls'] = "";

        if( empty( $goods_attr ) or empty( $attr ) ){
            return [];
        }

        $goods_attr_val = [];
        foreach( $goods_attr as $k => $v ){
            $goods_attr_val[$v['attrId']] = $v['attrVal'];
        }



        foreach( $attr as $k => $v ){
            switch( $v['attrName'] ){
                case "材质":
                    $data['goodsCz'] = $goods_attr_val[$v['attrId']];
                    break;
                case "题材":
                    $data['goodsTc'] = $goods_attr_val[$v['attrId']];
                    break;
                case "作者年表":
                    $data['zznb'] = $goods_attr_val[$v['attrId']];
                    break;
                case "所有院系":
                    $data['szyx'] = $goods_attr_val[$v['attrId']];
                    break;
                case "指导老师":
                    $data['zdls'] = $goods_attr_val[$v['attrId']];
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * 分页
     */
    public function pageQuery($sId=0){
        $shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
        $attrSrc = (int)input('attrSrc');
        $keyName = input('keyName');
        $goodsCatPath = input('goodsCatPath');
        $dbo = $this->field(true);
        $map[] = ['dataFlag','=',1];
        if($attrSrc==1){
            $map[] = ['shopId','=',0];
        }else if($attrSrc==2){
            $map[] = ['shopId','=',$shopId];
        }else{
            $map[] = ['shopId','in',[0,$shopId]];
        }
        if($keyName!="")$map[] = ['attrName',"like","%".$keyName."%"];
        if($goodsCatPath!='')$map[] = ['goodsCatPath',"like",$goodsCatPath."_%"];
        $page = $dbo->field(true)->where($map)->order('shopId desc,attrId desc')->paginate(input('limit/d'))->toArray();
        if(count($page['data'])>0){
            $keyCats = model('GoodsCats')->listKeyAll();
            foreach ($page['data'] as $key => $v){
                $goodsCatPath = $page['data'][$key]['goodsCatPath'];
                $page['data'][$key]['goodsCatNames'] = self::getGoodsCatNames($goodsCatPath,$keyCats);
                $page['data'][$key]['children'] = [];
                $page['data'][$key]['isextend'] = false;
            }
        }
        return $page;
    }

    public function getGoodsCatNames($goodsCatPath, $keyCats){
        $catIds = explode("_",$goodsCatPath);
        $catNames = array();
        for($i=0,$k=count($catIds);$i<$k;$i++){
            if($catIds[$i]=='')continue;
            if(isset($keyCats[$catIds[$i]]))$catNames[] = $keyCats[$catIds[$i]];
        }
        return implode("→",$catNames);
    }

    /**
     * 新增
     */
    public function add($sId=0){
        $shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
        $data = input('post.');
        WSTUnset($data, 'attrId,dataFlag');
        $data['createTime'] = date('Y-m-d H:i:s');
        $data['attrVal'] = str_replace('，',',',$data['attrVal']);
        $data["dataFlag"] = 1;
        $data["shopId"] = $shopId;
        $data["attrSort"] = (int)$data["attrSort"];
        $goodsCats = model('GoodsCats')->getParentIs($data['goodsCatId']);
        krsort($goodsCats);
        if(!empty($goodsCats))$data['goodsCatPath'] = implode('_',$goodsCats)."_";
        $validate = new validate();
        if(!$validate->scene('add')->check($data))return WSTReturn($validate->getError());
        $result = $this->allowField(true)->save($data);
        if(false !== $result){
            return WSTReturn("新增成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }
    /**
     * 编辑
     */
    public function edit($sId=0){
        $shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
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
        $result = $this->allowField(true)->save($data,['attrId'=>$attrId,'shopId'=>$shopId]);
        if(false !== $result){
            $where = [];
            $where['shopId'] = $shopId;
            $where['attrId'] = $attrId;
            Db::name('goods_attributes')->where($where)->delete();
            return WSTReturn("编辑成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }
    /**
     * 删除
     */
    public function del($sId=0){
        $shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
        $attrId = input('post.attrId/d');
        $data["dataFlag"] = -1;
        $result = $this->save($data,['attrId'=>$attrId,'shopId'=>$shopId]);
        if(false !== $result){
            $where = [];
            $where['shopId'] = $shopId;
            $where['attrId'] = $attrId;
            Db::name('goods_attributes')->where($where)->delete();
            return WSTReturn("删除成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }

    /**
     *
     * 根据ID获取
     */
    public function getById($attrId,$sId=0){
        $shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
        $obj = null;
        if($attrId>0){
            $obj = $this->get(['attrId'=>$attrId,'dataFlag'=>1,'shopId'=>$shopId]);
        }else{
            $obj = self::getEModel("attributes");
        }
        return $obj;
    }

    /**
     * 显示隐藏
     */
    public function setToggle($sId=0){
        $shopId = ($sId==0)?(int)session('WST_USER.shopId'):$sId;
        $attrId = input('post.attrId/d');
        $isShow = input('post.isShow/d');

        $result = $this->where(['attrId'=>$attrId,'shopId'=>$shopId,"dataFlag"=>1])->setField("isShow", $isShow);
        if(false !== $result){
            return WSTReturn("设置成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }

    /**
     * 列表
     */
    public function listQuery(){
        $catId = input("post.catId/d");
        $rs = $this->field("attrId id, attrId, catId, attrName name,  '' goodsCatNames")->where(["dataFlag"=>1,"catId"=>$catId])->sort('attrSort asc,attrId asc')->select();
        return $rs;
    }
}
