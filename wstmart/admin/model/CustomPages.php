<?php
namespace wstmart\admin\model;
//use wstmart\admin\validate\Banks as validate;
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
 * 自定义页面业务处理
 */
class CustomPages extends Base{
    protected $pk = 'id';
    /**
     * 分页
     */
    public function pageQuery(){
        $rs = $this->where(['dataFlag'=>1,'pageType'=>(int)input('type')])->field('*')->order('id asc')->select();
        foreach($rs as $k => $v){
            $pageAttrData = unserialize($v['attr']);
            $rs[$k]['pagePoster'] = $pageAttrData['poster'];
        }
        return ['list'=>$rs];
    }
    /*
     * 设置是否为首页
     */
    public function editIsIndex(){
        $id = (int)input('id',0);
        $val = (int)input('val',0);
        $type = (int)input('type');
        $result = $this->where('id','eq',$id)->where(['pageType'=>$type])->setField('isIndex', $val);
        $result2 = $this->where('id','neq',$id)->where(['pageType'=>$type])->setField('isIndex', 0);
        if(false !== $result && false !== $result2){
            return WSTReturn("设置成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }

    /**
     * 删除
     */
    public function del(){
        $id = input('id',0);
        $data = [];
        $data['dataFlag'] = -1;
        $result = $this->update($data,['id'=>$id]);
        $result2 = Db::name("custom_page_decoration")->where(['pageId'=>$id])->update(['dataFlag'=>-1]);
        if(false !== $result && false !== $result2){
            return WSTReturn("删除成功", 1);
        }else{
            return WSTReturn($this->getError(),-1);
        }
    }

    /**
     * 复制自定义页面静态文件
     */
    public function copyCustomPage(){
        $pageId = input('id',0);
        $pageType = input('type');
        if($pageId<1)WSTReturn("非法请求",-1);
        $pageData = Db::name('custom_pages')->where(['id'=>$pageId])->find();
        if(empty($pageData))WSTReturn("页面不存在",-1);
        $pageDecorationData =  Db::name('custom_page_decoration')->field('*')->where(['pageId'=>$pageId])->order('sort asc')->select();
        $copyPageData = [
            'pageName'=>$pageData['pageName'],
            'isIndex'=>0,
            'createTime'=>date('Y-m-d H:i:s'),
            'dataFlag'=>1,
            'pageType'=>$pageData['pageType'],
            "attr"=>$pageData['attr'],
        ];
        Db::startTrans();
        try{
            $copyPageId = Db::name('custom_pages')->insertGetId($copyPageData);
            foreach($pageDecorationData as $k => $v){
                $copyPageDecorationData = [
                    "pageId"=>$copyPageId,
                    "name"=>$v['name'],
                    "attr"=>$v['attr'],
                    "createTime"=>date("Y-m-d H:i:s",time()),
                    "dataFlag"=>1,
                    "sort"=>$v['sort']
                ];
                Db::name('custom_page_decoration')->insert($copyPageDecorationData);
            }
            if(in_array($pageType,[1,2])){
                $sourceFileName = 'custom_page_index_'.$pageId.'.html';
                $destFileName = 'custom_page_index_'.$copyPageId.'.html';
                switch ($pageType){
                    case 1:
                        $filePath = WSTRootPath() . "/wstmart/mobile/view/default/tpl/";
                        break;
                    case 2:
                        $filePath = WSTRootPath() . "/wstmart/wechat/view/default/tpl/";
                        break;
                }
                if(!is_dir($filePath)){
                    if (!@mkdir($filePath, 0755)){
                        return WSTReturn("页面复制失败",-1);
                    }
                }
                $sourceFilePath = $filePath.$sourceFileName;
                $destFilePath = $filePath.$destFileName;
                $rs = copy($sourceFilePath,$destFilePath);
                if(false !== $rs){
                    Db::commit();
                    return WSTReturn("操作成功",1);
                }
            }
            Db::commit();
            return WSTReturn("操作成功",1);
        }catch (\Exception $e) {
            Db::rollback();
            return WSTReturn('操作失败',-1);
        }
    }
}
