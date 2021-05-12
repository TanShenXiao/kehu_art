<?php /*a:2:{s:92:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\admin\view\shops\list_apply.html";i:1602924246;s:80:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<title>后台管理中心 - <?php echo WSTConf('CONF.mallName'); ?></title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="/static/plugins/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/layui/css/layui.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css" type="text/css" />
<script src="__ADMIN__/js/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="/static/plugins/mmgrid/mmGrid.css?v=<?php echo $v; ?>" />

<link href="__ADMIN__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<?php 
$msgGrant = [];
if(WSTGrant('TSDD_00'))$msgGrant[] = 'shopapply';
if(WSTGrant('DSHSP_00'))$msgGrant[] = 'goodsaudit';
if(WSTGrant('TSDD_00'))$msgGrant[] = 'ordercomplains';
if(WSTGrant('JBSP_00'))$msgGrant[] = 'informs';
 ?>
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"/index.php","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>",'RESOURCE_PATH':'<?php echo WSTConf('CONF.resourcePath'); ?>',MSG_GRANT:'<?php echo implode(',',$msgGrant); ?>'}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__ADMIN__/img/ajax-loader.gif"/></div>

<div class="layui-tab layui-tab-brief" lay-filter="msgTab">
    <ul class="layui-tab-title">
        <li class="layui-this">电脑端</li>
        <li>移动端</li>
    </ul>
    <div class="layui-tab-content" style="padding: 10px 0;">
      <div class="layui-tab-item layui-show">
          <div class="wst-toolbar" style='padding-top:0px;'>
          <select id="areaId1" class='j-ipt j-areas' level="0" onchange="WST.ITAreas({id:'areaId1',val:this.value,className:'j-areas'});">
            <option value="">-商家所在地-</option>
            <?php if(is_array($areaList) || $areaList instanceof \think\Collection || $areaList instanceof \think\Paginator): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <option value="<?php echo $vo['areaId']; ?>"><?php echo $vo['areaName']; ?></option>
            <?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
          <input type="text" id="shopName"  placeholder='店铺名称' id="shopName" class='j-ipt'/>
          <select id="isInvestment" class='j-ipt'>
             <option value="-1">-是否招商推广-</option>
             <option value="1">是</option>
             <option value="0">否</option>
          </select>
          <select id="isApply" class='j-ipt'>
             <option value="-1">-申请状态-</option>
             <option value="1">已提交</option>
             <option value="0">填写中</option>
          </select>
          <button class="btn btn-primary" onclick='javascript:loadApplyGrid(0)'><i class='fa fa-search'></i>查询</button>
          <div style='clear:both'></div>
          </div>
          <div class='wst-grid'>
           <div id="mmg" class="mmg"></div>
           <div id="pg" style="text-align: right;"></div>
          </div>
      </div>
      <div class="layui-tab-item">
          <div class="wst-toolbar" style='padding-top:0px;'>
          <input type="text" id="linkkey"  placeholder='联系人/联系电话' id="linkkey" class='ja-ipt'/>
          <select id="applyStatus" class='ja-ipt'>
             <option value="-1">-申请状态-</option>
             <option value="0">待处理</option>
             <option value="1">已处理</option>
             <option value="-1">无效</option>
          </select>
          <button class="btn btn-primary" onclick='javascript:loadApplyGrid2(0)'><i class='fa fa-search'></i>查询</button>
          <div style='clear:both'></div>
          </div>
          <div class='wst-grid'>
           <div id="mmg2" class="mmg"></div>
           <div id="pg2" style="text-align: right;"></div>
          </div>
      </div>
    </div>
</div>
<div id='applyBox' style='display:none'>
    <form id='applyForm' method="post" autocomplete="off">
    <table class='wst-form wst-box-top'>
      <tr>
          <th width='150'>申请账号：</th>
          <td id='loginName'></td>
       </tr>
       <tr>
          <th width='150'>联系人：</th>
          <td id='linkman'></td>
       </tr>
       <tr>
          <th width='150'>联系电话：</th>
          <td id='linkPhone'></td>
       </tr>
       <tr>
          <th width='150'>营业范围：</th>
          <td id='applyIntention'></td>
       </tr>
       <tr>
          <th width='150'>处理结果<font color='red'>*</font>：</th>
          <td class="layui-form" lay-filter='applyStatusBox'>
             <input type='radio' name='applyStatus' id='applyStatus1' lay-filter='applyStatus' value='1' class='eipt' title='申请成功' data-rule="处理结果:checked">
             <input type='radio' name='applyStatus' id='applyStatus0' lay-filter='applyStatus' value='-1' class='eipt' title='申请失败'>
          </td>
       </tr>
       <tr class='applyStatusTr1' style='display:none'>
        <th width='150'>&nbsp;</th>
          <td style='color:red'>*审核通过后请手工在商家管理中开通商家类型</td>
       </tr>
       <tr class='applyStatusTr1' style='display:none'>
        <th width='150'>店铺名称<font color='red'>*</font></th>
          <td><input type='text' id='shopName' style='width:60%;' class='eipt' data-rule="店铺名称:required(#applyStatus1:checked)" maxlenght='20' placeholder="申请通过的店铺名称"></textarea></td>
       </tr>
       <tr class='applyStatusTr0' style='display:none'>
          <th width='150'>失败原因<font color='red'>*</font>：</th>
          <td>
             <textarea id='handleReamrk' style='width:90%;height:100px;' class='eipt' data-rule="失败原因:required(#applyStatus0:checked)" placeholder="请填写失败原因，申请客户可看" data-target='#msg_handleReamrk'></textarea>
            <div id='msg_handleReamrk'></div>
          </td>
       </tr>
    </table>
    </form>
</div>
<div id='applyBox2' style='display:none'>
    <table class='wst-form wst-box-top'>
      <tr>
          <th width='150'>申请账号：</th>
          <td id='vloginName'></td>
       </tr>
       <tr>
          <th width='150'>联系人：</th>
          <td id='vlinkman'></td>
       </tr>
       <tr>
          <th width='150'>联系电话：</th>
          <td id='vlinkPhone'></td>
       </tr>
       <tr>
          <th width='150'>营业范围：</th>
          <td id='vapplyIntention'></td>
       </tr>
       <tr>
          <th width='150'>处理结果：</th>
          <td id='vapplyStatus'></td>
       </tr>
       <tr class='vapplyStatusTr1' style='display:none'>
          <th width='150'>店铺名称：</th>
          <td id='vshopName'></td>
       </tr>
       <tr class='vapplyStatusTr0' style='display:none'>
          <th width='150'>失败原因：</th>
          <td id='vhandleReamrk'></td>
       </tr>
    </table>
</div>
<script>
$(function(){
  initApplyGrid(<?php echo $p; ?>);
  var element = layui.element;
  element.on('tab(msgTab)', function(data){
     console.log(data.index);
     if(data.index==0)initApplyGrid(<?php echo $p; ?>);
     if(data.index==1)initApplyGrid2(<?php echo $p; ?>);
  });
})
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/shops/shops.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>