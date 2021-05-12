<?php /*a:2:{s:69:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\suppliers\add.html";i:1599792153;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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
.upload-picker div:nth-child(2){top:0!important;left:0!important;width:100%!important;height:100%!important;}
label{font-weight: normal;}
</style>
<form id='editFrom' autocomplete='off'>
<input type='hidden' id='supplierId' name='supplierId' class='a-ipt' value="0"/>
<fieldset class="layui-elem-field layui-field-title" style='margin-top:10px;'>
<legend>公司信息</legend>
                <table class='wst-form wst-box-top'>
                    <tr>
                       <th width='170'>新增类型<font color='red'>*</font>：</th>
                       <td class='layui-form'>
                         <label>
                               <input type='radio' class='a-ipt' name='isNew' id='isNew1' value='1' onclick='javascript:WST.showHide(1,".newUserTr1");WST.showHide(0,"#newUserTr0")' checked title='新账号'>
                         </label>
                         <label>
                               <input type='radio' class='a-ipt' name='isNew' value='0' onclick='javascript:WST.showHide(0,".newUserTr1");WST.showHide(1,"#newUserTr0")' title='已有账号'>
                         </label>
                       </td>
                    </tr>
                    <tr id='newUserTr0' style='display:none'>
                       <th width='170' valign="top" style='padding-top:20px;'>请输入用户账号<font color='red'>*</font>：</th>
                       <td>
                       <input type="text" id='keyName' name='keyName' class='ipt' placeholder='请输入用户账号/手机'/>
                       <input type='button' value='查询' class='btn btn-primary' onclick='javascript:getUserByKey()'>
                       <div id='keyNameBox' style='height:30px;line-height:30px'></div>
                       <input type='hidden' class='a-ipt' id='supplierUserId' value='0'/>
                       </td>
                    </tr>
                    <tr class='newUserTr1'>
                       <th width='170'>登录账号<font color='red'>*</font>：</th>
                       <td><input type="text" id='loginName' name='loginName' class='a-ipt' value=" " maxLength='20' data-rule="登录账号: required(#isNew1:checked);length[6~20];remote(post:<?php echo url('admin/users/checkLoginKey'); ?>)" onkeyup="javascript:WST.isChinese(this,1)"/></td>
                    </tr>
                    <tr class='newUserTr1'>
                       <th>登录密码<font color='red'>*</font>：</th>
                       <td><input type="password" id='loginPwd' class='a-ipt' maxLength='20' value='88888888' data-rule="登录密码: required(#isNew1:checked);length[6~20];" data-target="#msg_loginPwd"/>
                       <span class='msg-box' id='msg_loginPwd'>(默认为88888888)</span>
                       </td>
                    </tr>
                    <tr>
                        <th width='170'>供货商编号：</th>
                        <td><input type="text" id='supplierSn' name='supplierSn' class='a-ipt' maxLength='20' data-rule="供货商编号:ignoreBlank;length[1~20];remote(post:<?php echo url('admin/suppliers/checkSupplierSn'); ?>)" data-target="#msg_supplierSn"/><span class='msg-box' id='msg_supplierSn'>(为空则自动生成'S000000001'类型号码)</span></td>
                    </tr>
                    <?php if(is_array($companyFields) || $companyFields instanceof \think\Collection || $companyFields instanceof \think\Paginator): $i = 0; $__LIST__ = $companyFields;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;switch($vo['fieldType']): case "input": if($vo['isShow']==1): ?>
                                    <tr <?php if($vo['isRelevance']): ?>id="<?php echo $vo['fieldName']; ?>Tr"<?php endif; if($vo['isRelevance']): ?>style='display:none;'<?php endif; ?> >
                                        <th width='170' <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td height='23'>
                                            <?php if($vo['isRelevance']): ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required(#<?php echo $vo['fieldRelevance']; ?>1:checked)"<?php endif; ?> maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; else: ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; break; case "textarea": if($vo['isShow']==1): $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <textarea id="<?php echo $vo['fieldName']; ?>" class='a-ipt' rows="<?php echo $fieldAttr[0]; ?>" cols="<?php echo $fieldAttr[1]; ?>" <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?>></textarea>
                                        <?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                        </td>
                                </tr>
                                <?php endif; break; case "radio": if($vo['isShow']==1): $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr >
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <?php if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                        <label>
                                            <input type='radio' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?><?php echo $fieldAttrValue[0]; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>" onclick='javascript:WST.showHide(<?php echo $fieldAttrValue[0]; ?>,"#<?php echo $vo['fieldRelevance']; ?>Tr")'/><?php echo $fieldAttrValue[1]; ?>
                                        </label>
                                        <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; break; case "checkbox": if($vo['fieldAttr'] == 'custom'): ?>
                                    <tr >
                                        <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td>
                                            <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?>
                                            <label class='goodsCat'>
                                                <input type='checkbox' class='a-ipt' name="<?php echo $vo['fieldName']; ?>" value='<?php echo $voo["catId"]; ?>' <?php if($i == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>"/><?php echo $voo["catName"]; ?>
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
                                                    <input type='checkbox' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>"  <?php if($vo['isRequire'] == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; ?>/><?php echo $fieldAttrValue[1]; ?>
                                                </label>
                                                <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                            </td>
                                         </tr>
                                    <?php endif; ?>
                                <?php endif; break; case "select": if($vo['isShow']==1):  if($vo['fieldAttr']!='custom')$fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <select id="<?php echo $vo['fieldName']; ?>" class='a-ipt'>
                                            <?php if($vo['fieldAttr']!='custom'): if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                                <option value="<?php echo $fieldAttrValue[0]; ?>"><?php echo $fieldAttrValue[1]; ?></option>
                                                <?php endforeach; endif; else: echo "" ;endif; else: 
                                                    $banks = WSTTable('banks',['dataFlag'=>1,'isShow'=>1],'bankId,bankName',100);
                                                    foreach($banks as $aky => $bank){
                                                 ?>
                                                <option value="<?php echo $bank['bankId']; ?>"><?php echo $bank['bankName']; ?></option>
                                                <?php } ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; break; case "other": switch($vo['fieldAttr']): case "area": if($vo['isShow']==1): ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <select id="<?php echo $vo['fieldName']; ?>_0" class="j-<?php echo $vo['fieldName']; ?>" data-name="<?php echo $vo['fieldName']; ?>" level="0" onchange="WST.ITAreas({id:'<?php echo $vo['fieldName']; ?>_0',val:this.value,isRequire:true,className:'j-<?php echo $vo['fieldName']; ?>'});">
                                                    <option value="">-请选择-</option>
                                                    <?php 
                                                    $areas = WSTTable('areas',['isShow'=>1,'dataFlag'=>1,'parentId'=>0],'areaId,areaName',100,'areaSort desc');
                                                    foreach($areas as $aky => $area){
                                                     ?>
                                                    <option value="<?php echo $area['areaId']; ?>"><?php echo $area['areaName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php if($vo['isMap']): if((WSTConf('CONF.mapKey'))): ?> <button type='button' class='btn btn-primary' data-name="<?php echo $vo['fieldName']; ?>" onclick="javascript:mapCity(this)" style="top: 8px;height: 28px;line-height: 28px;font-size: 14px;font-weight: 400;"><i class='fa fa-map-marker'></i>地图定位</button><?php endif; ?>
                                                <?php endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php if($vo['isMap']): if((WSTConf('CONF.mapKey'))): ?>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <td>
                                                        <div id="container"  style='width:700px;height:400px'></div>
                                                        <input type='hidden' id='mapLevel' class='a-ipt'  value="<?php echo $apply['mapLevel']; ?>"/>
                                                        <input type='hidden' id='longitude' class='a-ipt'  value=""/>
                                                        <input type='hidden' id='latitude' class='a-ipt'  value=""/>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php endif; break; case "date": if($vo['isShow']==1): ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" readonly class='a-ipt laydate'  <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>" data-timely="2"/>
                                                <?php if($vo['dateRelevance']): $dateRelevance = explode(',',$vo['dateRelevance']); ?>
                                                
                                                - <input type='text' id="<?php echo $dateRelevance[0]; ?>" readonly class='a-ipt laydate'  data-timely="2"/>&nbsp;&nbsp;&nbsp;<label><input type='checkbox' name='<?php echo $dateRelevance[1]; ?>' id='<?php echo $dateRelevance[1]; ?>' class='a-ipt' onclick='WST.showHide(this.checked?0:1,"#<?php echo $dateRelevance[0]; ?>")' value='1'/><?php echo $dateRelevance[2]; ?></label>
                                                <?php endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><br><?php endif; ?>
                                                <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                            </td>
                                        </tr>
                                        <?php endif; break; case "time": if($vo['isShow']==1): ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <select class='a-ipt time-component' id="<?php echo $vo['fieldName']; ?>" v=""></select>
                                                <?php if($vo['timeRelevance']): ?>
                                                至
                                                <select class='a-ipt time-component' id="<?php echo $vo['timeRelevance']; ?>" v=""></select>
                                                <?php endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endif; break; case "file": if($vo['isShow']==1): ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <input type='hidden' id="<?php echo $vo['fieldName']; ?>" fileNum="<?php echo $vo['fileNum']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>"/>
                                                <div id="<?php echo $vo['fieldName']; ?>Picker" class="upload-picker">请上传<?php echo $vo['fieldTitle']; ?></div>
                                                <span id="<?php echo $vo['fieldName']; ?>Msg"></span>
                                                <div id="<?php echo $vo['fieldName']; ?>Box"></div>
                                                <div id="<?php echo $vo['fieldName']; ?>ImgBox">
                                                </div>
                                                <img id="<?php echo $vo['fieldName']; ?>Preview" src=""  width='150'>
                                                <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><br><?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endif; break; default: ?>
                                <?php endswitch; break; default: ?>
                        <?php endswitch; ?>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
</fieldset>
<fieldset class="layui-elem-field layui-field-title">
<legend>供货商信息</legend>
                <table class='wst-form wst-box-top'>
                    <?php if(is_array($supplierFields) || $supplierFields instanceof \think\Collection || $supplierFields instanceof \think\Paginator): $i = 0; $__LIST__ = $supplierFields;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;switch($vo['fieldType']): case "input": if($vo['isShow']==1): ?>
                                    <tr <?php if($vo['isRelevance']): ?>id="<?php echo $vo['fieldName']; ?>Tr"<?php endif; if($vo['isRelevance']): ?>style='display:none;'<?php endif; ?> >
                                        <th width='170' <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td height='23'>
                                            <?php if($vo['isRelevance']): ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required(#<?php echo $vo['fieldRelevance']; ?>1:checked)"<?php endif; ?> maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; else: ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; break; case "textarea": if($vo['isShow']==1): $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <textarea id="<?php echo $vo['fieldName']; ?>" class='a-ipt' rows="<?php echo $fieldAttr[0]; ?>" cols="<?php echo $fieldAttr[1]; ?>" <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?>></textarea>
                                        <?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; break; case "radio": if($vo['isShow']==1): $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr >
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <?php if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                        <label>
                                            <input type='radio' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?><?php echo $fieldAttrValue[0]; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>" onclick='javascript:WST.showHide(<?php echo $fieldAttrValue[0]; ?>,"#<?php echo $vo['fieldRelevance']; ?>Tr")'/><?php echo $fieldAttrValue[1]; ?>
                                        </label>
                                        <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; break; case "checkbox": if($vo['fieldAttr'] == 'custom'): ?>
                                    <tr >
                                        <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td>
                                            <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?>
                                            <label class='goodsCat'>
                                                <input type='checkbox' class='a-ipt' name="<?php echo $vo['fieldName']; ?>" value='<?php echo $voo["catId"]; ?>' <?php if($i == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>"/><?php echo $voo["catName"]; ?>
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
                                                    <input type='checkbox' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>"  <?php if($vo['isRequire'] == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; ?>/><?php echo $fieldAttrValue[1]; ?>
                                                </label>
                                                <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endif; break; case "select": if($vo['isShow']==1):  if($vo['fieldAttr']!='custom')$fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <select id="<?php echo $vo['fieldName']; ?>" class='a-ipt'>
                                            <?php if($vo['fieldAttr']!='custom'): if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                                <option value="<?php echo $fieldAttrValue[0]; ?>" ><?php echo $fieldAttrValue[1]; ?></option>
                                                <?php endforeach; endif; else: echo "" ;endif; else: if($vo['fieldName']=='tradeId'): ?>
                                                    <option value="">-请选择-</option>
                                                    <?php $_result=WSTTrades(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$td): $mod = ($i % 2 );++$i;?>
                                                    <option value="<?php echo $td['tradeId']; ?>"><?php echo $td['tradeName']; ?></option>
                                                    <?php endforeach; endif; else: echo "" ;endif; else: 
                                                        $bankList = WSTTable('banks',['dataFlag'=>1,'isShow'=>1],'bankId,bankName',100);
                                                     foreach($bankList as $bk): ?>
                                                    <option value="<?php echo $bk['bankId']; ?>"><?php echo $bk['bankName']; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; break; case "other": switch($vo['fieldAttr']): case "area": if($vo['isShow']==1): ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <select id="<?php echo $vo['fieldName']; ?>_0" class="j-<?php echo $vo['fieldName']; ?>" data-name="<?php echo $vo['fieldName']; ?>" level="0" onchange="WST.ITAreas({id:'<?php echo $vo['fieldName']; ?>_0',val:this.value,isRequire:true,className:'j-<?php echo $vo['fieldName']; ?>'});">
                                                    <option value="">-请选择-</option>
                                                    <?php 
                                                    $areas = WSTTable('areas',['isShow'=>1,'dataFlag'=>1,'parentId'=>0],'areaId,areaName',100,'areaSort desc');
                                                    foreach($areas as $aky => $area){
                                                     ?>
                                                    <option value="<?php echo $area['areaId']; ?>"><?php echo $area['areaName']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endif; break; case "date": if($vo['isShow']==1): ?>
                                            <tr>
                                                <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                                <td>
                                                    <input type='text' id="<?php echo $vo['fieldName']; ?>" readonly class='a-ipt laydate'  <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>" data-timely="2"/>
                                                    <?php if($vo['dateRelevance']): $dateRelevance = explode(',',$vo['dateRelevance']); ?>
                                                    
                                                    - <input type='text' id="<?php echo $dateRelevance[0]; ?>" readonly class='a-ipt laydate'  data-timely="2" style='display:none'/>&nbsp;&nbsp;&nbsp;<label><input type='checkbox' name='<?php echo $dateRelevance[1]; ?>' id='<?php echo $dateRelevance[1]; ?>' class='a-ipt' onclick='WST.showHide(this.checked?0:1,"#<?php echo $dateRelevance[0]; ?>")' value='1'/><?php echo $dateRelevance[2]; ?></label>
                                                    <?php endif; ?>
                                                    <?php endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><br><?php endif; ?>
                                                    <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                                </td>
                                            </tr>
                                        {/if}
                                    <?php break; case "time": if($vo['isShow']==1): ?>
                                            <tr>
                                                <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                                <td>
                                                    <select class='a-ipt time-component' id="<?php echo $vo['fieldName']; ?>" v=""></select>
                                                    <?php if($vo['timeRelevance']): ?>
                                                    至
                                                    <select class='a-ipt time-component' id="<?php echo $vo['timeRelevance']; ?>" v=""></select>
                                                    <?php endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; break; case "file": if($vo['isShow']==1): ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <input type='hidden' id="<?php echo $vo['fieldName']; ?>" fileNum="<?php echo $vo['fileNum']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>"/>
                                                <div id="<?php echo $vo['fieldName']; ?>Picker" class="upload-picker">请上传<?php echo $vo['fieldTitle']; ?></div>
                                                <span id="<?php echo $vo['fieldName']; ?>Msg"></span>
                                                <div id="<?php echo $vo['fieldName']; ?>Box"></div>
                                                <div id="<?php echo $vo['fieldName']; ?>ImgBox">
                                                </div>
                                                <img id="<?php echo $vo['fieldName']; ?>Preview" src=""  width='150'>
                                                <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><br><?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endif; break; default: ?>
                                <?php endswitch; break; default: ?>
                        <?php endswitch; ?>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
</fieldset>
<fieldset class="layui-elem-field layui-field-title">
<legend>附加信息</legend>
                <table class='wst-form wst-box-top'>
                    <?php if(is_array($otherFields) || $otherFields instanceof \think\Collection || $otherFields instanceof \think\Paginator): $i = 0; $__LIST__ = $otherFields;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;switch($vo['fieldType']): case "input": if($vo['isShow']==1): ?>
                                    <tr <?php if($vo['isRelevance']): ?>id="<?php echo $vo['fieldName']; ?>Tr"<?php endif; if($vo['isRelevance']): ?>style='display:none;'<?php endif; ?> >
                                        <th width='170'  <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td height='23'>
                                            <?php if($vo['isRelevance']): ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required(#<?php echo $vo['fieldRelevance']; ?>1:checked)"<?php endif; ?> maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; else: ?>
                                                <input type='text' id="<?php echo $vo['fieldName']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> maxlength="<?php echo $vo['fieldAttr']; ?>" /><?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; break; case "textarea": if($vo['isShow']==1): $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <textarea id="<?php echo $vo['fieldName']; ?>" class='a-ipt' rows="<?php echo $fieldAttr[0]; ?>" cols="<?php echo $fieldAttr[1]; ?>" <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?>></textarea>
                                        <?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; break; case "radio": if($vo['isShow']==1): $fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr >
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <?php if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                        <label>
                                            <input type='radio' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?><?php echo $fieldAttrValue[0]; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>" onclick='javascript:WST.showHide(<?php echo $fieldAttrValue[0]; ?>,"#<?php echo $vo['fieldRelevance']; ?>Tr")'/><?php echo $fieldAttrValue[1]; ?>
                                        </label>
                                        <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; break; case "checkbox": if($vo['fieldAttr'] == 'custom'): ?>
                                    <tr >
                                        <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td>
                                            <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?>
                                            <label class='goodsCat'>
                                                <input type='checkbox' class='a-ipt' name="<?php echo $vo['fieldName']; ?>" value='<?php echo $voo["catId"]; ?>' <?php if($i == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>"/><?php echo $voo["catName"]; ?>
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
                                                    <input type='checkbox' name="<?php echo $vo['fieldName']; ?>"  id="<?php echo $vo['fieldName']; ?>" class='a-ipt' value="<?php echo $fieldAttrValue[0]; ?>"  <?php if($vo['isRequire'] == 1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:checked" <?php endif; ?>/><?php echo $fieldAttrValue[1]; ?>
                                                </label>
                                                <?php endforeach; endif; else: echo "" ;endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endif; break; case "select": if($vo['isShow']==1):  if($vo['fieldAttr']!='custom')$fieldAttr = explode(',',$vo['fieldAttr']); ?>
                                <tr>
                                    <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                    <td>
                                        <select id="<?php echo $vo['fieldName']; ?>" class='a-ipt'>
                                            <?php if($vo['fieldAttr']!='custom'): if(is_array($fieldAttr) || $fieldAttr instanceof \think\Collection || $fieldAttr instanceof \think\Paginator): $i = 0; $__LIST__ = $fieldAttr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;$fieldAttrValue = explode('||',$voo); ?>
                                                    <option value="<?php echo $fieldAttrValue[0]; ?>"><?php echo $fieldAttrValue[1]; ?></option>
                                                <?php endforeach; endif; else: echo "" ;endif; else: if($vo['fieldName']=='tradeId'): ?>
                                                    <option value="">-请选择-</option>
                                                    <?php $_result=WSTTrades(0);if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$td): $mod = ($i % 2 );++$i;?>
                                                    <option value="<?php echo $td['tradeId']; ?>"><?php echo $td['tradeName']; ?></option>
                                                    <?php endforeach; endif; else: echo "" ;endif; else: 
                                                        $bankList = WSTTable('banks',['dataFlag'=>1,'isShow'=>1],'bankId,bankName',100);
                                                     foreach($bankList as $bk): ?>
                                                    <option value="<?php echo $bk['bankId']; ?>"><?php echo $bk['bankName']; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; break; case "other": switch($vo['fieldAttr']): case "area": if($vo['isShow']==1): ?>
                                    <tr>
                                        <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                        <td>
                                            <select id="<?php echo $vo['fieldName']; ?>_0" class="j-<?php echo $vo['fieldName']; ?>" data-name="<?php echo $vo['fieldName']; ?>" level="0" onchange="WST.ITAreas({id:'<?php echo $vo['fieldName']; ?>_0',val:this.value,isRequire:true,className:'j-<?php echo $vo['fieldName']; ?>'});">
                                                <option value="">-请选择-</option>
                                                <?php 
                                                $areas = WSTTable('areas',['isShow'=>1,'dataFlag'=>1,'parentId'=>0],'areaId,areaName',100,'areaSort desc');
                                                foreach($areas as $aky => $area){
                                                 ?>
                                                <option value="<?php echo $area['areaId']; ?>"><?php echo $area['areaName']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <?php if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endif; break; case "date": if($vo['isShow']==1): ?>
                                            <tr>
                                                <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                                <td>
                                                    <input type='text' id="<?php echo $vo['fieldName']; ?>" readonly class='a-ipt laydate'  <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>" data-timely="2"/>
                                                    <?php if($vo['dateRelevance']): $dateRelevance = explode(',',$vo['dateRelevance']); ?>
                                                        - <input type='text' id="<?php echo $dateRelevance[0]; ?>" readonly class='a-ipt laydate'  data-timely="2" style='display:none'/>&nbsp;&nbsp;&nbsp;<label><input type='checkbox' name='<?php echo $dateRelevance[1]; ?>' id='<?php echo $dateRelevance[1]; ?>' class='a-ipt' onclick='WST.showHide(this.checked?0:1,"#<?php echo $dateRelevance[0]; ?>")' value='1'/><?php echo $dateRelevance[2]; ?></label>
                                                        <?php endif; ?>
                                                    <?php endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><br><?php endif; ?>
                                                    <span class='msg-box' id="msg_<?php echo $vo['fieldName']; ?>"></span>
                                                </td>
                                            </tr>
                                        {/if}
                                    <?php break; case "time": if($vo['isShow']==1): ?>
                                            <tr>
                                                <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                                <td>
                                                    <select class='a-ipt time-component' id="<?php echo $vo['fieldName']; ?>" v=""></select>
                                                    <?php if($vo['timeRelevance']): ?>
                                                    至
                                                    <select class='a-ipt time-component' id="<?php echo $vo['timeRelevance']; ?>" v=""></select>
                                                    <?php endif; if($vo['fieldComment']): ?><br><span class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></span><?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; break; case "file": if($vo['isShow']==1): ?>
                                        <tr>
                                            <th <?php if($vo['fieldComment']): ?>valign="top" style='padding-top: 20px;'<?php endif; ?>><?php echo $vo['fieldTitle']; if($vo['isRequire']==1): ?><font color='red'>*</font><?php endif; ?>：</th>
                                            <td>
                                                <input type='hidden' id="<?php echo $vo['fieldName']; ?>" fileNum="<?php echo $vo['fileNum']; ?>" class='a-ipt' <?php if($vo['isRequire']==1): ?>data-rule="<?php echo $vo['fieldTitle']; ?>:required;"<?php endif; ?> data-target="#msg_<?php echo $vo['fieldName']; ?>" value=""/>
                                                <div id="<?php echo $vo['fieldName']; ?>Picker" class="upload-picker">请上传<?php echo $vo['fieldTitle']; ?></div>
                                                <span id="<?php echo $vo['fieldName']; ?>Msg"></span>
                                                <div id="<?php echo $vo['fieldName']; ?>Box"></div>
                                                <div id="<?php echo $vo['fieldName']; ?>ImgBox">
                                                </div>
                                                <img id="<?php echo $vo['fieldName']; ?>Preview" src=""  width='150'>
                                                <?php if($vo['fieldComment']): ?><div class="c-tip"><?php echo htmlspecialchars_decode($vo['fieldComment']); ?></div><br><?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endif; break; default: ?>
                                <?php endswitch; break; default: ?>
                        <?php endswitch; ?>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
</fieldset>
<fieldset class="layui-elem-field layui-field-title">
<table class='wst-form wst-box-top'>
    <tr>
        <th width='150'>到期日期<font color='red'>*</font>：</th>
        <td class='layui-form'>
            <input type='text' id="expireDate" class='a-ipt laydate' value=""  data-rule="到期日期:required;"/>
        </td>
    </tr>
  <tr>
       <td colspan='2' align='center'>
         <button type="button" class='btn btn-primary btn-mright' onclick='javascript:add(<?php echo $p; ?>,"index")'><i class="fa fa-check"></i>新增</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <button type="button"  class='btn' onclick="javascript:location.href='<?php echo Url('admin/suppliers/index','p='.$p); ?>'"><i class="fa fa-angle-double-left" ></i>返回</button>
       </td>
  </tr>
</table>
</fieldset>
</form>


<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script charset="utf-8" src="<?php echo WSTProtocol(); ?>map.qq.com/api/js?v=2.exp&key=<?php echo WSTConf('CONF.mapKey'); ?>"></script>
<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
<script src="__ADMIN__/suppliers/suppliers.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
    $(function() {
        $(".upload-picker").each(function (idx, item) {
            var id_selector = $(item).prev().attr('id');
            if(id_selector=='supplierImg'){
                WST.upload({
                    pick: "#" + id_selector + 'Picker',
                    formData: {dir: 'suppliers',isThumb:1},
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
                var fileNumLimit = $(item).prev().attr('fileNum');
                var uploader = WST.upload({
                    pick: "#" + id_selector + 'Picker',
                    formData: {dir: 'supplierextras'},
                    accept: {extensions: 'gif,jpg,jpeg,png', mimeTypes: 'image/jpg,image/jpeg,image/png,image/gif'},
                    fileNumLimit:fileNumLimit,
                    callback: function (f,file) {
                        var json = WST.toAdminJson(f);
                        if (json.status == 1) {
                            if(fileNumLimit>1){
                                $('#' + id_selector + 'ImgBox').empty();
                                var tdiv = $("<div style='height:75px;float:left;margin:0px 5px;position:relative'><a target='_blank' href='"+WST.conf.RESOURCE_PATH+"/"+json.savePath+json.name+"'>"+
                                    "<img class='"+id_selector+"step_pic"+"' height='75' src='"+WST.conf.RESOURCE_PATH+"/"+json.savePath+json.thumb+"' v='"+json.savePath+json.name+"'></a></div>");
                                var btn = $('<div style="position: absolute;top: -5px;right: 0px;cursor: pointer;background: rgba(0,0,0,0.5);width: 18px;height: 18px;text-align: center;border-radius: 50%;" ><img src="'+WST.conf.ROOT+'/wstmart/home/view/default/img/seller_icon_error.png"></div>');
                                tdiv.append(btn);
                                $('#' + id_selector + 'Box').append(tdiv);
                                $('#msg_' + id_selector).hide();
                                var imgPath = [];
                                $('.'+id_selector+'step_pic').each(function(){
                                    imgPath.push($(this).attr('v'));
                                });
                                $('#' + id_selector).val(imgPath.join(','));
                                btn.on('click','img',function(){
                                    uploader.removeFile(file);
                                    $(this).parent().parent().remove();
                                    uploader.refresh();
                                    if($('#'+id_selector+'Box').children().size()<=0){
                                        $('#msg_' + id_selector).show();
                                    }
                                    var imgPath = [];
                                    $('.'+id_selector+'_step_pic').each(function(){
                                        imgPath.push($(this).attr('v'));
                                    });
                                    $('#' + id_selector).val(imgPath.join(','));
                                });
                            }else{
                                $('#' + id_selector + 'Msg').empty().hide();
                                $('#' + id_selector + 'Preview').attr('src', WST.conf.RESOURCE_PATH + "/" + json.savePath + json.thumb).show();
                                $('#' + id_selector).val(json.savePath + json.name);
                                $('#msg_' + id_selector).hide();
                                uploader.reset();
                            }
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
    });
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>