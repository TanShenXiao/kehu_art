<?php /*a:1:{s:69:"D:\phpstudy_pro\WWW\www.art.com\wstmart\admin\view\logmoneys\box.html";i:1602924235;}*/ ?>
<form id="userForm" autocomplete="off" >
<table class='wst-form wst-box-top'>
  <tr>
    <th width='150'>账号<font color='red'>*</font>：</th>
      <td width='370'>
        <?php echo $object['loginName']; if($object['userName']!=''): ?>(<?php echo $object['userName']; ?>)<?php endif; ?>
      </td>
  </tr>
  <tr>
    <th width='150'>类型<font color='red'>*</font>：</th>
      <td width='370' class='layui-form'>
        <lable><input type='radio' name='moneyType' class='ipt' value='1' checked title='增加'/> </lable>
        <lable><input type='radio' name='moneyType' class='ipt' value='0' title='减少'/> </lable>
      </td>
  </tr>
  <tr>
    <th width='150'>变动金额<font color='red'>*</font>：</th>
      <td width='370'>
        <input type='text' id='money' class='ipt' maxLength='5'/>
      </td>
  </tr>
  <tr>
    <th width='150'>变动备注：</th>
      <td width='370'>
        <textarea style='width:400px;height:50px;' class='ipt' id='remark'></textarea>
      </td>
  </tr>
  <tr>
     <td colspan='2' align='center'>
       <button type="button" class="btn btn-primary btn-mright" onclick="javascript:editMoney(<?=(int)$object['userId']?>,<?=(int)$object['userType']?>)"><i class="fa fa-check"></i>提交</button> 
       <button type="button" class="btn" onclick="javascript:history.go(-1)"><i class="fa fa-angle-double-left"></i>返回</button>
     </td>
  </tr>
</table>
</form>