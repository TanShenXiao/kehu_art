<?php /*a:2:{s:86:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\admin\view\shops\edit.html";i:1620199782;s:80:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />
<link href="/static/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">

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

<style>
.goodsCat{display:inline-block;width:150px}
.accreds{display:inline-block;width:150px}
.upload-picker div:nth-child(2){top:0!important;left:0!important;width:50%!important;height:24px!important;}
label{font-weight: normal;}
</style>
<form id='editFrom' autocomplete='off'>
<input type='hidden' id='shopId' name='shopId' class='a-ipt' value="<?php echo $apply['shopId']; ?>"/>
    <div id='tab' class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title">
            <li class="layui-this">公司信息</li>
            <li>店铺信息</li>
            <li>附加信息</li>
        </ul>
        <div class="layui-tab-content" style='width:99%;margin-bottom: 10px;'>
            <div class="layui-tab-item layui-show wst-box-top" style="position: relative;">
                <table class='wst-form wst-box-top'>
                    <tr>
                        <th width='170'>申请会员：</th>
                        <td height='23'><?php echo $apply['loginName']; ?></td>
                    </tr>
                    <tr>
                        <th width='170'>申请时间：</th>
                        <td height='23'><?php echo $apply['applyTime']; ?></td>
                    </tr>
                    <tr>
                        <th width='170'>店铺编号：</th>
                        <td><input type="text" id='shopSn' name='shopSn' class='a-ipt' value="<?php echo $apply['shopSn']; ?>" maxLength='20' data-rule="店铺编号:required;length[1~20];remote(post:<?php echo url('admin/shops/checkShopSn','shopId='.$apply['shopId']); ?>)" data-target="#msg_shopSn"/><span class='msg-box' id='msg_shopSn'></span></td>
                    </tr>
                    <?php if(is_array($companyFields) || $companyFields instanceof \think\Collection || $companyFields instanceof \think\Paginator): $i = 0; $__LIST__ = $companyFields;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;switch($vo['fieldType']): case "input": if((($vo['isShow']==1 && $apply['shopType']==0) || ($vo['isShowPersonal']==1 && $apply['shopType']==1))): ?>
                                    <tr <?php if($vo['isRelevance']): ?>id="<?php echo $vo['fieldName']; ?>Tr"<?php endif; if($vo['isRelevance'] && $apply[$vo['fieldRelevance']] == 0): ?>style='display:none;'<?php endif; ?> >
                                        <th width='170' <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td height='23'>
                                            <?php if($vo['isRelevance']): ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required(#<?php echo $vo['fieldRelevance']; ?>1:checked)"<?php endif; ?>  value="<?php echo $apply[$vo['fieldName']]; ?>" maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; else: ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> value="<?php echo $apply[$vo['fieldName']]; ?>" maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; break; case "textarea": if((($vo['isShow']==1 && $apply['shopType']==0) || ($vo['isShowPersonal']==1 && $apply['shopType']==1))): $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <textarea id="<?php echo $vo['fieldName']; ?>" class='a-ipt' rows="<?php echo $fieldAttr[0]; ?>" cols="<?php echo $fieldAttr[1]; ?>" <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?>><?php echo $apply[$vo['fieldName']]; ?></textarea>
                                        <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                        </td>
                                </tr>
								<?php endif; break; case "radio": $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr >
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <?php if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                        <label>
                                            <input type='radio' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?><?php echo $fieldAttrValue[0]; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>" onclick='javascript:WST.showHide(<?php echo $fieldAttrValue[0]; ?>,"#<?php echo $vo['fieldRelevance']; ?>Tr")' <?php if($apply[$vo['fieldName']]==$fieldAttrValue[0]): ?>checked<?php endif; ?>/><?php echo $fieldAttrValue[1]; ?>
                                        </label>
                                        <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                    </td>
                                </tr>
                            <?php break; case "checkbox": if($vo['fieldAttr'] == 'custom'): ?>
                                    <tr >
                                        <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td>
                                            <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?>
                                            <label class='goodsCat'>
                                                <input type='checkbox' class='a-ipt' name="<?php echo $vo['fieldName']; ?>" value='<?php echo $voo["catId"]; ?>' <?php if($i == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; if(array_key_exists($voo['catId'],$apply['catshops'])): ?>checked<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>"/><?php echo $voo["catName"]; ?>
                                            </label>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                            <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                        </td>
                                    </tr>
                                <?php else: if((($vo['isShow']==1 && $apply['shopType']==0) || ($vo['isShowPersonal']==1 && $apply['shopType']==1))): $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                        <tr >
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <?php if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                                <label>
                                                    <input type='checkbox' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>"  <?php if($vo['isRequire'] == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; if($apply[$vo['fieldName']]==$fieldAttrValue[0]): ?>checked<?php endif; ?>/><?php echo $fieldAttrValue[1]; ?>
                                                </label>
                                                <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                            </td>
                                         </tr>
                                    <?php endif; ?>
                                <?php endif; break; case "select": if((($vo['isShow']==1 && $apply['shopType']==0) || ($vo['isShowPersonal']==1 && $apply['shopType']==1))):  if($vo['fieldAttr']!='custom')$fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <select id="<?php echo $vo['fieldName']; ?>" class='a-ipt'>
                                            <?php if($vo['fieldAttr']!='custom'): if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                                <option value="<?php echo $fieldAttrValue[0]; ?>" <?php if($apply[$vo['fieldName']]==$fieldAttrValue[0]): ?>selected<?php endif; ?> ><?php echo $fieldAttrValue[1]; ?></option>
                                                <?php endforeach; endif; else: echo "" ;endif; else: 
                                                    $banks = WSTTable('banks',['dataFlag'=>1],'bankId,bankName',100);
                                                    foreach($banks as $aky => $bank){
                                                 ?>
                                                <option value="<?php echo $bank['bankId']; ?>" <?php if($apply[$vo['fieldName']]==$bank['bankId']): ?>selected<?php endif; ?>><?php echo $bank['bankName']; ?></option>
                                                <?php } ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                    </td>
                                </tr>
								<?php endif; break; case "other": switch($vo['fieldAttr']): case "area": if((($vo['isShow']==1 && $apply['shopType']==0) || ($vo['isShowPersonal']==1 && $apply['shopType']==1))): ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <select id="<?php echo $vo['fieldName']; ?>_0" class="j-<?php echo $vo['fieldName']; ?>" data-name="<?php echo $vo['fieldName']; ?>" level="0" onchange="WST.ITAreas({id:'<?php echo $vo['fieldName']; ?>_0',val:this.value,isRequire:true,className:'j-<?php echo $vo['fieldName']; ?>'});" data-value="<?php echo $apply[$vo['fieldName']]; ?>">
                                                    <option value="">-请选择-</option>
                                                    <?php 
                                                    $areas = WSTTable('areas',['isShow'=>1,'dataFlag'=>1,'parentId'=>0],'areaId,areaName',100,'areaSort desc');
                                                    foreach($areas as $aky => $area){
                                                     ?>
                                                    <option value="<?php echo $area['areaId']; ?>"><?php echo $area['areaName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php if($vo['isMap']): if((WSTConf('CONF.mapKey'))): ?> <button type='button' class='btn btn-primary' data-name="<?php echo $vo['fieldName']; ?>" onclick="javascript:mapCity(this)" style="top: 8px;height: 28px;line-height: 28px;font-size: 14px;font-weight: 400;"><i class='fa fa-map-marker'></i>地图定位</button><?php endif; ?>
                                                <?php endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php if($vo['isMap']): if((WSTConf('CONF.mapKey'))): ?>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <td>
                                                        <div id="container"  style='width:700px;height:400px'></div>
                                                        <input type='hidden' id='mapLevel' class='a-ipt'  value="<?php echo $apply['mapLevel']; ?>"/>
                                                        <input type='hidden' id='longitude' class='a-ipt'  value="<?php echo $apply['longitude']; ?>"/>
                                                        <input type='hidden' id='latitude' class='a-ipt'  value="<?php echo $apply['latitude']; ?>"/>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endif; ?>
										<?php endif; break; case "date": if((($vo['isShow']==1 && $apply['shopType']==0) || ($vo['isShowPersonal']==1 && $apply['shopType']==1))): ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" readonly class='a-ipt laydate'  <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>" data-timely="2" value="<?php echo $apply[$vo['fieldName']]; ?>"/>
                                                <?php if($vo['dateRelevance']): $dateRelevance = explode(',',$vo['dateRelevance']); if(array_key_exists($dateRelevance[0],$apply) && array_key_exists($dateRelevance[1],$apply)): ?>
                                                - <input type='text' id="<?php echo $dateRelevance[0]; ?>" readonly class='a-ipt laydate'  data-timely="2" value="<?php echo $apply[$dateRelevance[0]]; ?>" <?php if($apply[$dateRelevance[1]]==1): ?>style='display:none'<?php endif; ?> />&nbsp;&nbsp;&nbsp;<label><input type='checkbox' name='<?php echo $dateRelevance[1]; ?>' id='<?php echo $dateRelevance[1]; ?>' class='a-ipt' onclick='WST.showHide(this.checked?0:1,"#<?php echo $dateRelevance[0]; ?>")' <?php if($apply[$dateRelevance[1]]==1): ?>checked<?php endif; ?>  value='1'/><?php echo $dateRelevance[2]; ?></label>
                                                <?php endif; ?>
                                                <?php endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><br><?php endif; ?>
                                                <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                            </td>
                                        </tr>
                                        <?php endif; break; case "time": if((($vo['isShow']==1 && $apply['shopType']==0) || ($vo['isShowPersonal']==1 && $apply['shopType']==1))): ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <select class='a-ipt time-component' id="<?php echo $vo['fieldName']; ?>" v="<?php echo $apply[$vo['fieldName']]; ?>"></select>
                                                <?php if($vo['timeRelevance']): ?>
                                                至
                                                <select class='a-ipt time-component' id="<?php echo $vo['timeRelevance']; ?>" v="<?php echo $apply[$vo['timeRelevance']]; ?>"></select>
                                                <?php endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endif; break; case "file": if((($vo['isShow']==1 && $apply['shopType']==0) || ($vo['isShowPersonal']==1 && $apply['shopType']==1))): ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <input type='hidden' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>" value="<?php echo $apply[$vo['fieldName']]; ?>"/>
                                                <div id="<?php echo $vo['fieldName']; ?>Picker" class="upload-picker">请上传<?php echo $vo['fieldTitle']; ?></div>
                                                <span id="<?php echo $vo['fieldName']; ?>Msg"></span>
                                                <img id="<?php echo $vo['fieldName']; ?>Preview" src="__RESOURCE_PATH__/<?php echo $apply[$vo['fieldName']]; ?>" <?php if($apply[$vo['fieldName']] ==''): ?>style='display:none'<?php endif; ?> width='150'>
                                                <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><br><?php endif; ?>
                                                <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                            </td>
                                        </tr>
										<?php endif; break; default: ?>
                                <?php endswitch; break; default: ?>
                        <?php endswitch; ?>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
            </div>

            <div class="layui-tab-item" style="display:none;">
                <table class='wst-form wst-box-top'>
                    <tr>
                        <th width='170'>认证类型：</th>
                        <td height='23'>
                            <?php 
                                $accreds = WSTTable('accreds',['dataFlag'=>1],'accredId,accredName',100);
                                foreach($accreds as $aky => $accred){
                             ?>
                            <label class='accreds'>
                                <input type='checkbox' class='a-ipt' name='accredIds' value='<?php echo $accred["accredId"]; ?>' <?php if(in_array($accred["accredId"],$apply['accreds'])): ?>checked<?php endif; ?>/><?php echo $accred["accredName"]; ?>
                            </label>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php if(is_array($shopFields) || $shopFields instanceof \think\Collection || $shopFields instanceof \think\Paginator): $i = 0; $__LIST__ = $shopFields;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;switch($vo['fieldType']): case "input": if($vo['isShow']==1): ?>
                                    <tr <?php if($vo['isRelevance']): ?>id="<?php echo $vo['fieldName']; ?>Tr"<?php endif; if($vo['isRelevance'] && $apply[$vo['fieldRelevance']] == 0): ?>style='display:none;'<?php endif; ?> >
                                        <th width='170' <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td height='23'>
                                            <?php if($vo['isRelevance']): ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required(#<?php echo $vo['fieldRelevance']; ?>1:checked)"<?php endif; ?>  value="<?php echo $apply[$vo['fieldName']]; ?>" maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; else: ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> value="<?php echo $apply[$vo['fieldName']]; ?>" maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; break; case "textarea": $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <textarea id="<?php echo $vo['fieldName']; ?>" class='a-ipt' rows="<?php echo $fieldAttr[0]; ?>" cols="<?php echo $fieldAttr[1]; ?>" <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?>><?php echo $apply[$vo['fieldName']]; ?></textarea>
                                        <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                    </td>
                                </tr>
                            <?php break; case "radio": $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr >
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <?php if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                        <label>
                                            <input type='radio' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?><?php echo $fieldAttrValue[0]; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>" onclick='javascript:WST.showHide(<?php echo $fieldAttrValue[0]; ?>,"#<?php echo $vo['fieldRelevance']; ?>Tr")' <?php if($apply[$vo['fieldName']]==$fieldAttrValue[0]): ?>checked<?php endif; ?>/><?php echo $fieldAttrValue[1]; ?>
                                        </label>
                                        <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                    </td>
                                </tr>
                            <?php break; case "checkbox": if($vo['fieldAttr'] == 'custom'): ?>
                                    <tr >
                                        <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td>
                                            <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?>
                                            <label class='goodsCat'>
                                                <input type='checkbox' class='a-ipt' name="<?php echo $vo['fieldName']; ?>" value='<?php echo $voo["catId"]; ?>' <?php if($i == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; if(array_key_exists($voo['catId'],$apply['catshops'])): ?>checked<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>"/><?php echo $voo["catName"]; ?>
                                            </label>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                            <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                        </td>
                                    </tr>
                                <?php else: if($vo['isShow']==1): $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                        <tr >
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <?php if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                                <label>
                                                    <input type='checkbox' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>"  <?php if($vo['isRequire'] == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; if($apply[$vo['fieldName']]==$fieldAttrValue[0]): ?>checked<?php endif; ?>/><?php echo $fieldAttrValue[1]; ?>
                                                </label>
                                                <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endif; break; case "select":  if($vo['fieldAttr']!='custom')$fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <select id="<?php echo $vo['fieldName']; ?>" class='a-ipt'>
                                            <?php if($vo['fieldAttr']!='custom'): if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                                <option value="<?php echo $fieldAttrValue[0]; ?>" <?php if($apply[$vo['fieldName']]==$fieldAttrValue[0]): ?>selected<?php endif; ?> ><?php echo $fieldAttrValue[1]; ?></option>
                                                <?php endforeach; endif; else: echo "" ;endif; else: foreach($bankList as $v): ?>
                                                <option value="<?php echo $v['bankId']; ?>" <?php if($apply[$vo['fieldName']]==$v['bankId']): ?>selected<?php endif; ?>><?php echo $v['bankName']; ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                    </td>
                                </tr>
                            <?php break; case "other": switch($vo['fieldAttr']): case "area": ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <select id="<?php echo $vo['fieldName']; ?>_0" class="j-<?php echo $vo['fieldName']; ?>" data-name="<?php echo $vo['fieldName']; ?>" level="0" onchange="WST.ITAreas({id:'<?php echo $vo['fieldName']; ?>_0',val:this.value,isRequire:true,className:'j-<?php echo $vo['fieldName']; ?>'});" data-value="<?php echo $apply[$vo['fieldName']]; ?>">
                                                    <option value="">-请选择-</option>
                                                    <?php 
                                                    $areas = WSTTable('areas',['isShow'=>1,'dataFlag'=>1,'parentId'=>0],'areaId,areaName',100,'areaSort desc');
                                                    foreach($areas as $aky => $area){
                                                     ?>
                                                    <option value="<?php echo $area['areaId']; ?>"><?php echo $area['areaName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php break; case "date": if($vo['isShow']==1): ?>
                                            <tr>
                                                <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                                <td>
                                                    <input type='text' id="<?php echo $vo['fieldName']; ?>" readonly class='a-ipt laydate'  <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>" data-timely="2" value="<?php echo $apply[$vo['fieldName']]; ?>"/>
                                                    <?php if($vo['dateRelevance']): $dateRelevance = explode(',',$vo['dateRelevance']); if(array_key_exists($dateRelevance[0],$apply) && array_key_exists($dateRelevance[1],$apply)): ?>
                                                    - <input type='text' id="<?php echo $dateRelevance[0]; ?>" readonly class='a-ipt laydate'  data-timely="2" value="<?php echo $apply[$dateRelevance[0]]; ?>" <?php if($apply[$dateRelevance[1]]==1): ?>style='display:none'<?php endif; ?> />&nbsp;&nbsp;&nbsp;<label><input type='checkbox' name='<?php echo $dateRelevance[1]; ?>' id='<?php echo $dateRelevance[1]; ?>' class='a-ipt' onclick='WST.showHide(this.checked?0:1,"#<?php echo $dateRelevance[0]; ?>")' <?php if($apply[$dateRelevance[1]]==1): ?>checked<?php endif; ?>  value='1'/><?php echo $dateRelevance[2]; ?></label>
                                                    <?php endif; ?>
                                                    <?php endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><br><?php endif; ?>
                                                    <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                                </td>
                                            </tr>
                                        <?php endif; break; case "time": if($vo['isShow']==1): ?>
                                            <tr>
                                                <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                                <td>
                                                    <select class='a-ipt time-component' id="<?php echo $vo['fieldName']; ?>" v="<?php echo $apply[$vo['fieldName']]; ?>"></select>
                                                    <?php if($vo['timeRelevance']): ?>
                                                    至
                                                    <select class='a-ipt time-component' id="<?php echo $vo['timeRelevance']; ?>" v="<?php echo $apply[$vo['timeRelevance']]; ?>"></select>
                                                    <?php endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; break; case "file": ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <input type='hidden' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>" value="<?php echo $apply[$vo['fieldName']]; ?>"/>
                                                <div id="<?php echo $vo['fieldName']; ?>Picker" class="upload-picker">请上传<?php echo $vo['fieldTitle']; ?></div>
                                                <span id="<?php echo $vo['fieldName']; ?>Msg"></span>
                                                <img id="<?php echo $vo['fieldName']; ?>Preview" src="__RESOURCE_PATH__/<?php echo $apply[$vo['fieldName']]; ?>" <?php if($apply[$vo['fieldName']] ==''): ?>style='display:none'<?php endif; ?> width='150'>
                                                <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><br><?php endif; ?>
                                                <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                            </td>
                                        </tr>
                                    <?php break; default: ?>
                                <?php endswitch; break; default: ?>
                        <?php endswitch; ?>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
            </div>
            <div class="layui-tab-item" style="display:none;">
                <table class='wst-form wst-box-top'>
                    <?php if(is_array($otherFields) || $otherFields instanceof \think\Collection || $otherFields instanceof \think\Paginator): $i = 0; $__LIST__ = $otherFields;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;switch($vo['fieldType']): case "input": if($vo['isShow']==1): ?>
                                    <tr <?php if($vo['isRelevance']): ?>id="<?php echo $vo['fieldName']; ?>Tr"<?php endif; if($vo['isRelevance'] && $apply[$vo['fieldRelevance']] == 0): ?>style='display:none;'<?php endif; ?> >
                                        <th width='170'  <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td height='23'>
                                            <?php if($vo['isRelevance']): ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required(#<?php echo $vo['fieldRelevance']; ?>1:checked)"<?php endif; ?>  value="<?php echo $apply[$vo['fieldName']]; ?>" maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; else: ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> value="<?php echo $apply[$vo['fieldName']]; ?>" maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; break; case "textarea": $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <textarea id="<?php echo $vo['fieldName']; ?>" class='a-ipt' rows="<?php echo $fieldAttr[0]; ?>" cols="<?php echo $fieldAttr[1]; ?>" <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?>><?php echo $apply[$vo['fieldName']]; ?></textarea>
                                        <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                    </td>
                                </tr>
                            <?php break; case "radio": $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr >
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <?php if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                        <label>
                                            <input type='radio' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?><?php echo $fieldAttrValue[0]; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>" onclick='javascript:WST.showHide(<?php echo $fieldAttrValue[0]; ?>,"#<?php echo $vo['fieldRelevance']; ?>Tr")' <?php if($apply[$vo['fieldName']]==$fieldAttrValue[0]): ?>checked<?php endif; ?>/><?php echo $fieldAttrValue[1]; ?>
                                        </label>
                                        <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                    </td>
                                </tr>
                            <?php break; case "checkbox": if($vo['fieldAttr'] == 'custom'): ?>
                                    <tr >
                                        <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td>
                                            <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?>
                                            <label class='goodsCat'>
                                                <input type='checkbox' class='a-ipt' name="<?php echo $vo['fieldName']; ?>" value='<?php echo $voo["catId"]; ?>' <?php if($i == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; if(array_key_exists($voo['catId'],$apply['catshops'])): ?>checked<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>"/><?php echo $voo["catName"]; ?>
                                            </label>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                            <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                        </td>
                                    </tr>
                                <?php else: if($vo['isShow']==1): $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                        <tr >
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <?php if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                                <label>
                                                    <input type='checkbox' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>"  <?php if($vo['isRequire'] == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; if($apply[$vo['fieldName']]==$fieldAttrValue[0]): ?>checked<?php endif; ?>/><?php echo $fieldAttrValue[1]; ?>
                                                </label>
                                                <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endif; break; case "select":  if($vo['fieldAttr']!='custom')$fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <select id="<?php echo $vo['fieldName']; ?>" class='a-ipt'>
                                            <?php if($vo['fieldAttr']!='custom'): if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                            <option value="<?php echo $fieldAttrValue[0]; ?>" <?php if($apply[$vo['fieldName']]==$fieldAttrValue[0]): ?>selected<?php endif; ?> ><?php echo $fieldAttrValue[1]; ?></option>
                                            <?php endforeach; endif; else: echo "" ;endif; else: foreach($bankList as $v): ?>
                                            <option value="<?php echo $v['bankId']; ?>" <?php if($apply[$vo['fieldName']]==$v['bankId']): ?>selected<?php endif; ?>><?php echo $v['bankName']; ?></option>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                    </td>
                                </tr>
                            <?php break; case "other": switch($vo['fieldAttr']): case "area": ?>
                                    <tr>
                                        <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td>
                                            <select id="<?php echo $vo['fieldName']; ?>_0" class="j-<?php echo $vo['fieldName']; ?>" data-name="<?php echo $vo['fieldName']; ?>" level="0" onchange="WST.ITAreas({id:'<?php echo $vo['fieldName']; ?>_0',val:this.value,isRequire:true,className:'j-<?php echo $vo['fieldName']; ?>'});" data-value="<?php echo $apply[$vo['fieldName']]; ?>">
                                                <option value="">-请选择-</option>
                                                <?php 
                                                $areas = WSTTable('areas',['isShow'=>1,'dataFlag'=>1,'parentId'=>0],'areaId,areaName',100,'areaSort desc');
                                                foreach($areas as $aky => $area){
                                                 ?>
                                                <option value="<?php echo $area['areaId']; ?>"><?php echo $area['areaName']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php break; case "date": if($vo['isShow']==1): ?>
                                            <tr>
                                                <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                                <td>
                                                    <input type='text' id="<?php echo $vo['fieldName']; ?>" readonly class='a-ipt laydate'  <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>" data-timely="2" value="<?php echo $apply[$vo['fieldName']]; ?>"/>
                                                    <?php if($vo['dateRelevance']): $dateRelevance = explode(',',$vo['dateRelevance']); if(array_key_exists($dateRelevance[0],$apply) && array_key_exists($dateRelevance[1],$apply)): ?>
                                                        - <input type='text' id="<?php echo $dateRelevance[0]; ?>" readonly class='a-ipt laydate'  data-timely="2" value="<?php echo $apply[$dateRelevance[0]]; ?>" <?php if($apply[$dateRelevance[1]]==1): ?>style='display:none'<?php endif; ?> />&nbsp;&nbsp;&nbsp;<label><input type='checkbox' name='<?php echo $dateRelevance[1]; ?>' id='<?php echo $dateRelevance[1]; ?>' class='a-ipt' onclick='WST.showHide(this.checked?0:1,"#<?php echo $dateRelevance[0]; ?>")' <?php if($apply[$dateRelevance[1]]==1): ?>checked<?php endif; ?>  value='1'/><?php echo $dateRelevance[2]; ?></label>
                                                        <?php endif; ?>
                                                    <?php endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><br><?php endif; ?>
                                                    <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                                </td>
                                            </tr>
                                        <?php endif; break; case "time": if($vo['isShow']==1): ?>
                                            <tr>
                                                <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                                <td>
                                                    <select class='a-ipt time-component' id="<?php echo $vo['fieldName']; ?>" v="<?php echo $apply[$vo['fieldName']]; ?>"></select>
                                                    <?php if($vo['timeRelevance']): ?>
                                                    至
                                                    <select class='a-ipt time-component' id="<?php echo $vo['timeRelevance']; ?>" v="<?php echo $apply[$vo['timeRelevance']]; ?>"></select>
                                                    <?php endif; if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; break; case "file": ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <input type='hidden' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>" value="<?php echo $apply[$vo['fieldName']]; ?>"/>
                                                <div id="<?php echo $vo['fieldName']; ?>Picker" class="upload-picker">请上传<?php echo $vo['fieldTitle']; ?></div>
                                                <span id="<?php echo $vo['fieldName']; ?>Msg"></span>
                                                <img id="<?php echo $vo['fieldName']; ?>Preview" src="__RESOURCE_PATH__/<?php echo $apply[$vo['fieldName']]; ?>" <?php if($apply[$vo['fieldName']] ==''): ?>style='display:none'<?php endif; ?> width='150'>
                                                <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><br><?php endif; ?>
                                                <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                            </td>
                                        </tr>
                                    <?php break; default: ?>
                                <?php endswitch; break; default: ?>
                        <?php endswitch; ?>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
            </div>
        </div>
    </div>
<fieldset class="layui-elem-field layui-field-title">
<legend>店铺状态</legend>
<table class='wst-form wst-box-top'>
    <tr>
       <th width='150'>店铺状态<font color='red'>*</font>：</th>
       <td class='layui-form'>
          <label>
             <input type='radio' class='a-ipt' name='shopStatus' id='shopStatus-1' value='-1' <?php if($apply['shopStatus']==-1): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(1,"#trStatusDesc")' title='停止'>
          </label>
          <label>
             <input type='radio' class='a-ipt' name='shopStatus' value='1' <?php if($apply['shopStatus']==1): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(0,"#trStatusDesc")' title='正常'>
          </label>
       </td>
    </tr>
    <tr id='trStatusDesc' <?php if($apply['shopStatus']==1): ?>style='display:none'<?php endif; ?>>
       <th>停止原因<font color='red'>*</font>：</th>
       <td><textarea id='statusDesc' class='a-ipt' style='width:500px;height:100px;' maxLength='100' data-rule="停止原因:required(#shopStatus-1:checked);"><?php echo $apply['statusDesc']; ?></textarea></td>
    </tr>
    <tr>
       <td colspan='2' align='center'>
        <button type="button"  class='btn btn-primary btn-mright' onclick="javascript:save(<?php echo $p; ?>,'<?php echo $src; ?>')"><i class="fa fa-check"></i>保存</button>
        <button type="button"  class='btn' onclick="javascript:location.href='<?php echo Url('admin/shops/'.$src,'p='.$p); ?>'"><i class="fa fa-angle-double-left"></i>返回</button>
       </td>
    </tr>
</table>
</fieldset>
</fieldset>
</form>


<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script charset="utf-8" src="<?php echo WSTProtocol(); ?>map.qq.com/api/js?v=2.exp&key=<?php echo WSTConf('CONF.mapKey'); ?>"></script>
<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
<script src="__ADMIN__/shops/shops.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
    $(function() {
        $(".upload-picker").each(function (idx, item) {
            var id_selector = $(item).prev().attr('id');
            if(id_selector=='shopImg'){
                WST.upload({
                    pick: "#" + id_selector + 'Picker',
                    formData: {dir: 'shops'},
                    accept: {extensions: 'gif,jpg,jpeg,png', mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
                    callback: function (f) {
                        var json = WST.toAdminJson(f);
                        if (json.status == 1) {
                            $('#' + id_selector + 'Msg').empty().hide();
                            $('#' + id_selector + 'Preview').attr('src', WST.conf.RESOURCE_PATH + "/" + json.savePath + json.thumb).show();
                            $('#' + id_selector).val(json.savePath + json.name);
                            $('#msg_' + id_selector).hide();
                        }
                    },
                    progress: function (rate) {
                        $('#' + id_selector).show().html('已上传' + rate + "%");
                    }
                });
            }else{
                WST.upload({
                    pick: "#" + id_selector + 'Picker',
                    formData: {dir: 'shopextras'},
                    accept: {extensions: 'gif,jpg,jpeg,png', mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
                    callback: function (f) {
                        var json = WST.toAdminJson(f);
                        if (json.status == 1) {
                            $('#' + id_selector + 'Msg').empty().hide();
                            $('#' + id_selector + 'Preview').attr('src', WST.conf.RESOURCE_PATH + "/" + json.savePath + json.thumb).show();
                            $('#' + id_selector).val(json.savePath + json.name);
                            $('#msg_' + id_selector).hide();
                        }
                    },
                    progress: function (rate) {
                        $('#' + id_selector).show().html('已上传' + rate + "%");
                    }
                });
            }
        });

        if(window.conf.MAP_KEY){
            var longitude = $('#longitude').val();
            var latitude = $('#latitude').val();
            var mapLevel = $('#mapLevel').val();
            initQQMap(longitude,latitude,mapLevel);
        }

        $(".time-component").each(function (idx, item) {
            var id_selector = $(item).attr('id');
            initTime('#'+id_selector,$('#'+id_selector).attr('v'));
        });

        var laydate = layui.laydate;
        $(".laydate").each(function(idx,item) {
            var id_selector = $(item).attr('id');
            laydate.render({elem: '#'+id_selector});
        });


        $("select[class^='j-']").each(function(idx,item){
            var id_selector = $(item).attr('id');
            var class_selector = $(item).attr('class');
            var datavalue = $(item).attr('data-value');
            if(datavalue){
                var areaPath = $(item).attr('data-value').split("_");
                $('#'+id_selector).val(areaPath[0]);
                var aopts = {id:id_selector,val:areaPath[0],childIds:areaPath,className:class_selector,isRequire:true}
                WST.ITSetAreas(aopts);
            }
        });
    });
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>