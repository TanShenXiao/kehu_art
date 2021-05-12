<?php /*a:2:{s:98:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\users\user_edit.html";i:1602924184;s:87:"D:\soft\phpstudy\phpstudy_pro\WWW\three\www.art.com\wstmart\shop\view\default\base.html";i:1602924165;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<title>卖家中心 - <?php echo WSTConf('CONF.mallName'); ?></title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" href="/static/plugins/bootstrap/css/bootstrap.min.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/layui/css/layui.css" type="text/css" />
<link rel="stylesheet" href="/static/plugins/font-awesome/css/font-awesome.min.css" type="text/css" />
<script src="__SHOP__/js/jquery.min.js"></script>
    <script type="text/javascript" src="/static/plugins/layer/layer.js?v=<?php echo $v; ?>"></script>

<link href="/static/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">
<link rel="stylesheet" href="/static/plugins/jcrop/css/jquery.Jcrop.css?v=<?php echo $v; ?>" type="text/css" />
<link rel="stylesheet" type="text/css" href="/static/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />

<link href="__SHOP__/css/common.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />
<?php 
$msgGrant = [];
if(WSTShopGrant('shop/messages/shopMessage'))$msgGrant[] = 'message';
if(WSTShopGrant('shop/orders/waitdelivery'))$msgGrant[] = 'shoporder24';
if(WSTShopGrant('shop/orders/waituserPay'))$msgGrant[] = 'shoporder55';
if(WSTShopGrant('shop/orders/failure'))$msgGrant[] = 'shoporder45';
if(WSTShopGrant('shop/ordercomplains/shopComplain'))$msgGrant[] = 'shoporder25';
if(WSTShopGrant('shop/goods/stockWarnByPage'))$msgGrant[] = 'shoporder54';
 ?>
<script>
window.conf = {"DOMAIN":"<?php echo str_replace('index.php','',app('request')->root(true)); ?>","ROOT":"","APP":"","STATIC":"/static","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'',"IS_CRYPT":"<?php echo WSTConf('CONF.isCryptPwd'); ?>","ROUTES":'<?php echo WSTRoute(); ?>',"MAP_KEY":"<?php echo WSTConf('CONF.mapKey'); ?>","__HTTP__":"<?php echo WSTProtocol(); ?>",'RESOURCE_PATH':'<?php echo WSTConf('CONF.resourcePath'); ?>',"SMS_VERFY":"<?php echo WSTConf('CONF.smsVerfy'); ?>",'TIME_TASK':1,"MESSAGE_BOX":"<?php echo WSTShopMessageBox(); ?>",MSG_SHOP_GRANT:'<?php echo implode(',',$msgGrant); ?>'}
</script>
<script language="javascript" type="text/javascript" src="/static/js/common.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div id="j-loader"><img src="__SHOP__/img/ajax-loader.gif"/></div>

<script type='text/javascript' src='/static/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<form id="userEditForm" autocomplete="off" >
    <div id='tab' class="wst-tab-box">
        <ul class="wst-tab-nav uinfo-nav" style='border:none;'>
            <li id="wst-msg-li-0">个人资料<span style="display:none;" class="wst-order-tips-box"></span></li>
            <li id="wst-msg-li-1">头像照片<span style="display:none;"></span></li>
        </ul>
        <div class="wst-tab-content" style='width:99%;border:none;'>
            <div class='wst-tab-item'>
                <table class='wst-form uinfo-form' >
                    <tr>
                        <th width='150'>登录账号<font color='red'>  </font>：</th>
                        <td width="260" class="gray">
                            <div id='user_account'><?php echo session('WST_USER.loginName'); ?></div>
                        </td>
                        <td class="gray" style='padding-left:15px;'>个人头像：</td>
                    </tr>
                    <tr>
                        <th><font color='red'>*</font>昵称：</th>
                        <td class="uinfo">
                            <input type="text" class="ipt" id="userName" name="userName" value="<?php echo $data['userName']; ?>" />
                        </td>
                        <td rowspan="6" valign="top">
                            <div id='userPhotoPreview1' style="border:1px solid #f1f1f1;width:152px;height:152px;text-align: center; margin-top: 5px;">
                                <img  class="usersImg" data-original='<?php echo WSTUserPhoto($data['userPhoto']); ?>' height='150'/>
                            </div>
                        </td>
                    </tr>
                   
                    <tr>
                        <th>真实姓名<font color='red'>  </font>：</th>
                        <td class="uinfo">
                            <input type="text" class="ipt" id="trueName" name="trueName" value="<?php echo $data['trueName']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th>性别<font color='red'>  </font>：</th>
                        <td class="layui-form">
                            <label><input type='radio' name='userSex'  id="userSex-1" class='ipt wst-radio' value='1' <?php if($data['userSex']==1): ?>checked<?php endif; ?> title='男'/></label>
                            <label><input type='radio' name='userSex'  id="userSex-2" class='ipt wst-radio' value='2' <?php if($data['userSex']==2): ?>checked<?php endif; ?> title='女'/></label>
                            <label><input type='radio' name='userSex'  id="userSex-3" class='ipt wst-radio' value='0' <?php if($data['userSex']==0): ?>checked<?php endif; ?> title='保密'/></label>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>生日<font color='red'></font>：</th>
                        <td class="uinfo">
                            <input type="text" name="brithday" id="brithday" class="s-query ipt" value="<?php echo $data['brithday']; ?>" />
                        </td>
                        <td></td>
                    </tr>

                    <tr>
                        <th>QQ<font color='red'>  </font>：</th>
                        <td class="uinfo">
                            <input type="text" class="ipt" id="userQQ" name="userQQ" value="<?php echo $data['userQQ']; ?>" />
                        </td>
                        <td></td>
                    </tr>



                    <tr>
                        <td colspan='2' class='wst-bottombar'>
                            <input type="hidden" name="id" id="userId" class="ipt" value="<?php echo $data['userId']; ?>" />
                            <button type="submit"  class="wst-sec-but u-btn btn btn-primary btn-mright"><i class='fa fa-check'></i>保存</button>
                            <button type="reset"  class="wst-sec-but u-btn btn"><i class='fa fa-refresh'></i>重置</button>
                        </td>
                        <td></td>
                    </tr>
                </table>

            </div>


            <div class='wst-tab-item' style="display:none" >
                <table class='wst-form'  id="userPhoto">
                    <tr>
                        <th width='150'>头像预览<font color='red'> </font>：</th>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div id='userPhotoPreview'>
                                <?php if(($data['userPhoto'])): ?>
                                <img  class="usersImg" data-original='__RESOURCE_PATH__/<?php echo $data['userPhoto']; ?>' height='150' width="150"/>
                                <?php else: ?>
                                <img class="usersImg" data-original='' height='150' width="150" />
                                <?php endif; ?>
                                <br/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>图片大小:150 x 150 (px)，格式为 gif, jpg, jpeg, png</td></tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='hidden' id='userPic' value='<?php echo $data['userPhoto']; ?>' />
                            <div id="userPhotoPicker" style='margin-left:0px;margin-top:5px;height:30px;overflow:hidden'>上传用户头像</div>
                        </td>
                    </tr>
                </table>



                <div class="container"  id="userPhotoCut" style="display:none;">
                    <div class="row">
                        <div class="span12">
                            <div class="jc-demo-box">
                                <div id="img-src" style="text-align:center;max-width:500px;height:auto;">
                                    <p>裁剪区域</p>
                                </div>


                                <div id="userPhotoCutBox" style="position:relative;min-height:505px;">
                                    <div id="cutArea"></div><p></p>
                                </div>




                                <form action="<?=url('shop/Users/editUserPhoto')?>" method="post" id="userPhotoInfo">
                                    <input type="hidden" id="x" name="x" class="photo-size" />
                                    <input type="hidden" id="y" name="y" class="photo-size" />
                                    <input type="hidden" id="w" name="w" class="photo-size" />
                                    <input type="hidden" id="h" name="h" class="photo-size" />
                                    <input type="hidden" id="photoSrc" name="photoSrc" value="">
                                    <div id="c-btn">
                                        <button type="button" class="btn btn-primary btn-mright" onclick="checkCoords()"><i class='fa fa-check'></i>保存</button>
                                        <button type="button" class="btn btn-primary" onclick="returnPhotoPage()"><i class='fa fa-undo'></i>取消</button>
                                    </div>
                                </form>

                                <div class="wst-clear"></div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>



</form>


<script src="/static/plugins/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="/static/plugins/layui/layui.all.js"></script>
<script language="javascript" type="text/javascript" src="__SHOP__/js/common.js"></script>
<script type="text/javascript" src="/static/plugins/lazyload/jquery.lazyload.min.js?v=<?php echo $v; ?>"></script>

<script type="text/javascript" src="/static/plugins/jcrop/js/jquery.Jcrop.js?v=<?php echo $v; ?>"></script>
<script type='text/javascript' src='__SHOP__/users/user.js?v=<?php echo $v; ?>'></script>
<script type="text/javascript" src="/static/plugins/validator/jquery.validator.min.js?v=<?php echo $v; ?>"></script>
<script>
    var laydate = layui.laydate;
    laydate.render({
        elem: '#brithday'
    });
</script>

<?php echo hook('initCronHook'); ?>
</body>
</html>