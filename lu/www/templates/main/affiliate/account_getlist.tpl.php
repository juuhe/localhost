<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="Bookmark" href="/favicon.ico" >
<link rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5shiv.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<![endif]-->
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo SRC_TPL_DIR?>/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="<?php echo WEB_URL?>js/jqueryb/jquery-validation/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jqueryb/jquery-validation/additional-methods.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SRC_TPL_DIR?>/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SRC_TPL_DIR?>/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SRC_TPL_DIR?>b/Hui-iconfont/1.0.8/iconfont.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SRC_TPL_DIR?>/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="<?php echo SRC_TPL_DIR?>/static/h-ui.admin/css/style.css" />

<style type="text/css">
 
            /* SELECT W/IMAGE */
            select#selectTravelCity
            {
               width                    : 14em;
               height                   : 3.2em;
               padding                  : 0.2em 0.4em 0.2em 0.4em;
               vertical-align           : middle;
               border                   : 1px solid #e9e9e9;
               -moz-border-radius       : 0.2em;
               -webkit-border-radius    : 0.2em;
               border-radius            : 0.2em;
               box-shadow               : inset 0 0 3px #a0a0a0;
               -webkit-appearance       : none;
               -moz-appearance          : none;
               appearance               : none;
               /* sample image from the webinfocentral.com */
               background               : url(http://webinfocentral.com/Images/favicon.ico) 95% / 10% no-repeat #fdfdfd;
               font-family              : Arial,  Calibri, Tahoma, Verdana;
               font-size                : 1.1em;
               color                    : #000099;
               cursor                   : pointer;
            }
            select#selectTravelCity  option
            {
                font-size               : 1em;
                padding                 : 0.2em 0.4em 0.2em 0.4em;
            }
            select#selectTravelCity  option[selected]{ font-weight:bold}
            select#selectTravelCity  option:nth-child(even) { background-color:#f5f5f5; }
            select#selectTravelCity:hover
            {
                color                   : #101010;
                border                  : 1px solid #cdcdcd;
            }    
            select#selectTravelCity:focus {box-shadow: 0 0 2px 1px #404040;}
 
            /*SELECT W/DOWN-ARROW*/
            select#selectPointOfInterest
            {
               width                    : 185pt;
               height                   : 40pt;
               line-height              : 40pt;
               padding-right            : 20pt;
               text-indent              : 4pt;
               text-align               : left;
               vertical-align           : middle;
               box-shadow               : inset 0 0 3px #606060;
               border                   : 1px solid #acacac;
               -moz-border-radius       : 6px;
               -webkit-border-radius    : 6px;
               border-radius            : 6px;
               -webkit-appearance       : none;
               -moz-appearance          : none;
               appearance               : none;
               font-family              : Arial,  Calibri, Tahoma, Verdana;
               font-size                : 18pt;
               font-weight              : 500;
               color                    : #000099;
               cursor                   : pointer;
               outline                  : none;
            }
            select#selectPointOfInterest option
            {
                padding             : 4px 10px 4px 10px;
                font-size           : 11pt;
                font-weight         : normal;
            }
            select#selectPointOfInterest option[selected]{ font-weight:bold}
            select#selectPointOfInterest option:nth-child(even) { background-color:#f5f5f5; }
            select#selectPointOfInterest:hover {font-weight: 700;}
            select#selectPointOfInterest:focus {box-shadow: inset 0 0 5px #000099; font-weight: 600;}
 
            /*LABEL FOR SELECT*/
            label#lblSelect{ position: relative; display: inline-block;}
            /*DOWNWARD ARROW (25bc)*/
            label#lblSelect::after
            {
                content                 : "\25bc";
                position                : absolute;
                top                     : 0;
                right                   : 0;
                bottom                  : 0;
                width                   : 20pt;
                line-height             : 40pt;
                vertical-align          : middle;
                text-align              : center;
                background              : #000099;
                color                   : #fefefe;
               -moz-border-radius       : 0 6px 6px 0;
               -webkit-border-radius    : 0 6px 6px 0;
                border-radius           : 0 6px 6px 0;
                pointer-events          : none;
            }
        </style>
<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>H-ui.admin v3.1</title>

<script type="text/javascript">
  function add_mem() {
    $("#modal-demo").modal("show");
   }
</script>
</head>
<body>
<!-- 弹窗eof-->
<div id="modal-demo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">   
            <div class="modal-dialog">
              <div class="modal-content radius">
                <div class="modal-header">
                  <h3 class="modal-title">修改密码</h3>
                  <a class="close" data-dismiss="modal" aria-hidden="true" href="javascript:void();">×</a>
                </div>
                <div class="modal-body">
                  <div id="s_password">
                       <label>原始密码</label>
                   <input   type="password" name="oldpassword" id="oldpassword" class="input-text radius size-M" value="" style="width:350px;margin-left:27px">
                    <br><br>
                 <label>新密码</label>
                   <input  type="password" name="password" id="password" class="input-text radius size-M" value="" style="width:350px;margin-left:40px">
                    <br> <br> 
                 <label>确认新密码</label>
                   <input  type="password" name="password_confirm" id="password_confirm" class="input-text radius size-M" value="" style="width:350px;margin-left:12px">
                    <br> <br>
                    <button type="button" class="btn btn-primary" style="margin-left:102px"> 提 交 <tton>
                  </div>
                </div>
                <div class="modal-footer">
                  
                
                </div>
              </div>
            </div>
          </div>
<!-- 弹窗eof-->
	<div style="width:1600px;height:750px;margin-left:45px;margin-top:30px">
    	<h3>修改个人资料</h3><br>
    	<hr width=100% size=3 color=#C0C0C0 style="filter:progid:DXImageTransform.Microsoft.Shadow(color#5151A2,direction:145,strength:15)"> 
    	<br>
        <form action="<?php echo url("affiliate/account.edit_post")?>" method="POST" class="form-horizontal editAccount" id="form_b" >
    	|&nbsp&nbsp&nbsp<i style="font-size:22px;font-style:normal">基本资料</i><br><br>
        <input name="uid" id="uid"  type="hidden" value="<?php echo  $_SESSION['uid']?>" />
        <i style="margin-left:60px;font-size:18px">用户名:</i>&nbsp <i style="font-size:18px;color:red"><?php echo $u['username']?></i><br><br>
    	<i style="margin-left:60px;font-size:18px">密码:</i>&nbsp <i style="font-size:18px;">********<a href="javascript:;" id="s_editpass" onclick="add_mem()">修改密码</a>
        <!-- <div id="s_password" style="display:none; margin-top: 30px; margin-left:  100px; width:250px; padding-bottom:10px">
              <p><span>原始密码:</span>
                <input type="password" name="oldpassword" id="oldpassword">
              </p>
              <p><span>新密码:</span>
                <input type="password" name="password" id="password">
              </p>
              <p><span>确认新密码:</span>
                <input type="password" name="password_confirm" id="password_confirm">
              </p>
              <button type="button" class="btn btn-primary" style="margin-left:102px; margin-top:10px"> 提 交 </button>
            </div> -->
        </i><br><br>
        <i style="margin-left:60px;font-size:18px">联系手机:</i>&nbsp 	<input type="text" name="mobile" value="<?php echo $u['mobile']?>" class="input-text radius size-M" style="width:300px"/><br><br>
    	<i style="margin-left:60px;font-size:18px">QQ号码:</i>&nbsp &nbsp	<input type="text"  name="qq" value="<?php echo $u['qq']?>"  class="input-text radius size-M" style="width:300px"/><br><br>
    	<i style="margin-left:60px;font-size:18px">电子邮件:</i>&nbsp 	<input type="text" name="email" value="<?php echo $u['email']?>" class="input-text radius size-M" style="width:300px"/><br><br>
    	<i style="margin-left:60px;font-size:18px">固定电话:</i>&nbsp &nbsp	<input type="text" name="tel" value="<?php echo $u['tel']?>"  class="input-text radius size-M" style="width:300px"/><br><br>
        <i style="margin-left:60px;font-size:18px">身份证号码:</i>&nbsp &nbsp	<input type="text" name="idcard"  value="<?php echo $u['idcard']?>" class="input-text radius size-M" style="width:300px"/><br><br>
    	<br>
    	|&nbsp&nbsp&nbsp<i style="font-size:22px;font-style:normal">财务信息</i><br><br>
    	<i style="margin-left:60px;font-size:18px">收款银行:</i>&nbsp 
           <label id="lblSelect">
            <select id="selectPointOfInterest" title="Select points of interest nearby" name="bankname">
                <option value=''>请选择</option>
            <?php foreach ($GLOBALS['c_bank'] as $b=>$v){ if(!$v[1]) continue;?>
            <?php if($u['bankname'] == $b){ ?>
                <option value='<?php echo $b;?>'selected=""><?php echo $b;?></option>
            <?php  
            } else { ?>
                <option value='<?php echo $b;?>'><?php echo $b;?></option>
                <?php
            }
            }
            ?>
            </select>
           </label>
    	<br><br>
    	<i style="margin-left:60px;font-size:18px">开户地分行:</i>&nbsp &nbsp &nbsp  <input type="text" name="bankbranch"  class="input-text radius size-M" style="width:300px" value="<?php echo $u['bankbranch']?>"/><br><br>
    	<i style="margin-left:60px;font-size:18px">银行账号:</i>&nbsp 	<input type="text" value="<?php echo $u['bankaccount']?>" name="bankaccount" class="input-text radius size-M" style="width:300px"/>&nbsp
         <span style="font-size:15px">(仅限银行账号，不支持支付宝)</span>
    	<br><br>
    	<i style="margin-left:60px;font-size:18px">收款人:</i>&nbsp &nbsp	<input type="text"  value="<?php echo $u['accountname']?>" name="accountname"  class="input-text radius size-M" style="width:300px"/><br><br>
    	<br>
        <button type="submit" class="btn btn-primary"> 保存信息 </button>
        </form>
    </div>
    <script language="JavaScript" type="text/javascript">

$(document).ready(function() {
$('#s_editpass').on('click', function(option) {
     $('#s_password').show();
});
$('#s_password button').on('click', function(option) {
     var oldpassword =   $('#oldpassword').val();
	  var password =   $('#password').val();
	  var password_confirm =   $('#password_confirm').val();
	  if(oldpassword=='' || password=='' || password_confirm==''){
	  		alert("三项必填,请重新输入");
			return false;
	  }
	  if(password!=password_confirm){
	  		alert("两次输入的密码不一样,请重新输入");
			return false;
	  }
	   $.post("<?php echo url("affiliate/account.edit_password")?>", { oldpassword:oldpassword,password:password,password_confirm:password_confirm},
			function (data, textStatus){
				if(data){
					if(data=='err_pw'){
						alert("原始密码不能认证，无法修改")
					}
					if(data=='err_re'){
						alert("两次输入的密码不一样,请重新输入");
					}
					if(data=='ok'){
					   window.location.reload();
					}
				}
	 }, "text")

}); 
});
</script>
</body>
</html>