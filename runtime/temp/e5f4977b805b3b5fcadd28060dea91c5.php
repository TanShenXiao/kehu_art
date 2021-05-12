<?php /*a:2:{s:74:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\supplierflows\edit.html";i:1587916476;s:60:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\base.html";i:1602924209;}*/ ?>
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

<div id='alertTips' class='alert alert-success alert-tips fade in'>
    <div id='headTip' class='head'><i class='fa fa-lightbulb-o'></i>操作说明</div>
    <ul class='body'>
        <li>添加步骤内容所需填写的字段。</li>
        <li>无法删除则为必填字段。</li>
        <li>标识“*”的选项为必填项。</li>
        <li>表单字段名称请勿包含数值或者其他特殊字符、建议使用纯字母（如telephone，supplierCompany）。</li>
        <li>表单类型为其他(other)，选择类型为日期类型，若填了日期关联字段，则需将关联字段的是否显示设置为否，否则前台会重复显示。（同理选择类型为时间类型） </li>
        <li>日期关联字段请按照提示的格式进行设置，否则前台不会进行相应的显示。 </li>
        <li>新增的表单类型为单选按钮(radio)的字段，单选按钮名称格式：值||内容，以,分隔。若要有默认选中的效果，需设置其中的值为0，例如：1||营业中,0||休息中（默认选中“休息中”） </li>
    </ul>
</div>
<input type="hidden" id="fId" class="s-ipt" value="<?php echo $flowId; ?>"/>
<div class="wst-toolbar">
    <input type='text' class='fieldName'  placeholder='表单字段' value=""/>
    <select class="dataType">
        <option value="-1">数据类型</option>
        <option value="varchar">varchar</option>
        <option value="char">char</option>
        <option value="int">int</option>
        <option value="mediumint">mediumint</option>
        <option value="smallint">smallint</option>
        <option value="tinyint">tinyint</option>
        <option value="text">text</option>
        <option value="decimal">decimal</option>
    </select>
    <input type='text' class='fieldTitle' placeholder='表单标题'/>
    <select class="isRequire" >
        <option value="-1">是否必填</option>
        <option value="1" >是</option>
        <option value="0" >否</option>
    </select>
    <select class="fieldType">
        <option value="-1">表现形式</option>
        <option value="input">文本字段(input)</option>
        <option value="textarea">文本区域(textarea)</option>
        <option value="radio">单选按钮(radio)</option>
        <option value="checkbox">多选按钮(checkbox)</option>
        <option value="select">下拉菜单(select)</option>
        <option value="other">其他(other)</option>
    </select>
    <button class="btn btn-primary" onclick='javascript:loadGrid(0)'><i class='fa fa-search'></i>查询</button>
    <button type="button" class='f-right btn' onclick="javascript:history.go(-1)"><i class="fa fa-angle-double-left"></i>返回</button>
    <button class="btn btn-success f-right btn-mright" onclick="javascript:getForEdit(0)"><i class='fa fa-plus'></i>新增</button>
    <div style="clear:both"></div>
</div>
<div class='wst-grid'>
    <div id="mmg" class="mmg"></div>
    <div id="pg" style="text-align: right;"></div>
</div>
<div id='fieldBox' style='display:none;'>
    <form id='fieldForm' autocomplete="off">
        <table class='wst-form wst-box-top field-table'>
            <input type="hidden" name="id" id="id" class='ipt' value=""/>
            <input type="hidden" name="flowId" id="flowId" class='ipt' value=""/>
            <tr>
                <th width='150' align="right"><span>表单字段<font color='red'>*</font>：</span></th>
                <td >
                    <input type="text" id="fieldName" name="fieldName" style='width:70%;' class='ipt' value="" data-rule='表单字段:required;'  data-msg-required='请填写表单字段' data-target="#fieldNameMsg" placeholder='表单字段名称请勿包含数值或者其他特殊字符、建议使用纯字母（如telephone，supplierCompany）'/><span id="fieldNameMsg"></span>
                </td>
            </tr>
            <tr>
                <th ><span>数据类型&nbsp;：</span></th>
                <td>
                    <select id='dataType' name="dataType"  class="ipt" style='padding-left:10px;' onchange="changeDataType(this)">
                        <option value="varchar">varchar</option>
                        <option value="char">char</option>
                        <option value="int">int</option>
                        <option value="mediumint">mediumint</option>
                        <option value="smallint">smallint</option>
                        <option value="tinyint">tinyint</option>
                        <option value="text">text</option>
                        <option value="decimal">decimal</option>
                        <option value="date">date</option>
                        <option value="time">time</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th ><span>表单标题<font color='red'>*</font>：</span></th>
                <td>
                    <input type="text"  id="fieldTitle" name="fieldTitle" style='width:70%;' class='ipt' value="" data-rule='表单标题:required;'  data-msg-required='请填写表单标题' data-target="#fieldTitleMsg"/><span id="fieldTitleMsg"></span>
                </td>
            </tr>
            <tr class="dataLength">
                <th ><span>数据长度<font color='red'>*</font>：</span></th>
                <td>
                    <input type="text"  id="dataLength" name="dataLength"  style='width:30%;' class='ipt' value="" data-rule='数据长度:required;'  data-msg-required='请填写数据长度' data-target="#dataLengthMsg"/><span id="dataLengthMsg"></span>
                </td>
            </tr>
            <tr>
                <th ><span>显示排序&nbsp;：</span></th>
                <td>
                    <input type="text" id="fieldSort" name='fieldSort'  style='width:70%;' class='ipt' value=""/>
                </td>
            </tr>
            <tr>
                <th ><span>是否必填&nbsp;：</span></th>
                <td>
                    <select id="isRequire" name="isRequire" class="ipt" style='padding-left:10px;'>
                        <option value="1" >是</option>
                        <option value="0" >否</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th ><span>是否关联字段：</span></th>
                <td>
                    <select id="isRelevance" name="isRelevance" class="ipt" style='padding-left:10px;'>
                        <option value="0" >否</option>
                        <option value="1" >是</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th ><span>关联字段：</span></th>
                <td >
                    <div>
                        <input type="text" id="fieldRelevance" name="fieldRelevance"  style='width:70%;' class='ipt' value="" placeholder='请填写关联的表单字段'/>
                    </div>
                </td>
            </tr>
            <tr>
                <th > <span>表单注释&nbsp;：</span></th>
                <td>
                    <input type="text"  id="fieldComment" name='fieldComment'  style='width:70%;' class='ipt' value=""/>
                </td>
            </tr>
            <tr>
                <th ><span>表现形式&nbsp;：</span></th>
                <td>
                    <select id="fieldType" name="fieldType" class="ipt" onchange="changeFieldType(this)" style='padding-left:10px;'>
                        <option value="input">文本字段(input)</option>
                        <option value="textarea">文本区域(textarea)</option>
                        <option value="radio">单选按钮(radio)</option>
                        <option value="checkbox">多选按钮(checkbox)</option>
                        <option value="select">下拉菜单(select)</option>
                        <option value="other">其他(other)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th ><span class="fieldAttrTitle">表单长度<font color='red'>*</font>：</span></th>
                <td >
                    <div class="fieldAttr">
                        <input type="text" id="fieldAttr" name="fieldAttr"  style='width:70%;' class='ipt' value="" data-rule='表单属性:required;'  data-msg-required='请填写表单属性' data-target="#fieldAttrMsg"/><span id="fieldAttrMsg"></span>
                    </div>
                </td>
            </tr>
            <tr class="isShow" >
                <th ><span>是否显示&nbsp;：</span></th>
                <td>
                    <select id="isShow" name="isShow" class="ipt" style='padding-left:10px;'>
                        <option value="1" >是</option>
                        <option value="0" >否</option>
                    </select>
                </td>
            </tr>
            <tr class="dateRelevance" style="display: none;">
                <th><span >日期关联字段：</span></th>
                <td>
                    <input type="text" id="dateRelevance" name="dateRelevance"  style='width:70%;' class='ipt' value="" placeholder='格式：结束日期字段,是否长期字段,是否长期标题'/>
                </td>
            </tr>
            <tr class="timeRelevance" style="display: none;">
                <th><span >时间关联字段：</span></th>
                <td>
                    <input type="text" id="timeRelevance" name="timeRelevance"  style='width:70%;' class='ipt' value="" placeholder='格式：关联字段名'/>
                </td>
            </tr>
            <tr class="fileNum" style="display: none;">
                <th><span >上传文件数量：</span></th>
                <td>
                    <input type="text" id="fileNum" name="fileNum"  style='width:70%;' class='ipt' value="1" maxLength='3' placeholder='不填写代表上传文件数量为1' onkeyup="javascript:WST.isChinese(this,1)" onkeypress="return WST.isNumberKey(event)"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<script>
    $(function(){initGrid(<?php echo $p; ?>)});
</script>

<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__ADMIN__/js/common.js"></script>

<script src="/static/plugins/mmgrid/mmGrid.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
<script src="__ADMIN__/supplierflows/supplierbase.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<?php echo hook('initCronHook'); ?>
</body>
</html>