<?php if(!defined('IN_ZYADS')) exit(); ?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="广告联盟" />
<meta name="description" content="" />
<meta name="generator" content="zyiis.com v9" />
<meta name="author" content="The YingZhong network Science and Technology CO.Ltd All rights reserved" />
<meta name="copyright" content="2005-2018 YingZhong Inc." />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<script src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js" type="text/javascript"></script>
</head>
<link rel="stylesheet" href="<?php echo SRC_TPL_DIR?>/css.css">
<title>找回密码  <?php echo $GLOBALS['C_ZYIIS']['sitename']?></title>

<body  >
<div class="head">
  <div class="head_box">
    <div class="head_list">
      <h1 class="logo"> <a href="<?php echo WEB_URL?>" > <img src="<?php echo SRC_TPL_DIR?>/images/logo.gif" border="0"  > </a> </h1>
      <h2 class="logo-title"> 找回密码 </h2>
    </div>
  </div>
</div>
<div class="main">
  <div class="left_list">
    <div class="intro_box">通过以下方式找回密码，输入用户名！以下方式无法<a href="<?php echo url("index.contact")?>">点击这里</a>联系我们客服</div>
    <div class="title_box">
      <div class="mail_ok"><img src="<?php echo SRC_TPL_DIR?>/images/success.png" border="0">已成功发送邮件验证码，请查收</div>
      <div class="step item3">
        <ul>
          <li class="on" id="verifiy_username"><span>找回用户名</span></li>
          <li class="" id="verifiy_email"><span>重设密码</span></li>
        </ul>
      </div>
    </div>
    <ul class="form_list" id="ul_username" style="">
      <li style="position:relative;">
        <label><em class="red">*</em> 用户名：</label>
        <input type="text" class="input_text" name="txt_username" id="txt_username" placeholder="请输入用户名" maxlength="20">
        <div class="tip" id="txt_username_tip">不存在的用户名</div>
      </li>
      <li style="position:relative;">
        <label><em class="red">*</em> 验证码：</label>
        <input type="text" class="input_text" name="img_code" id="img_code" placeholder="请输入验证码" maxlength="6">
        <img src="<?php echo url("index.codeimage")?>" align="absmiddle"  title= "看不清?请点击刷新验证码"  onclick="this.src='<?php echo url("index.codeimage?rand=")?>'+Math.random();"  style= "cursor:pointer;"  />
        <div class="tip" id="img_code_tip" >验证码不正确</div>
      </li>
      <li>
        <label></label>
        <input type="button" value="下一步" class="btn_css" id="btn_username">
      </li>
      <li>
        <label></label>
        <a href="<?php echo url("index.register")?>" class="blue">如需注册，请点击这里！</a> </li>
    </ul>
    <ul class="form_list" id="ui_emai" style="display: none;">
      
      
        <li style="position:relative;">
        <label><em class="red">*</em> 验证的邮箱：</label>
         <div id="to_email" class="to_email"></div>
        
      </li>
      
      <li style="position:relative;">
        <label><em class="red">*</em> 邮件验证码：</label>
        <input type="text" class="input_text" name="verifiy_email_code" id="verifiy_email_code" placeholder="邮件中的验证码" maxlength="9">
        <div class="tip" id="verifiy_email_code_tip">请填写邮件验证码</div>
      </li>
      
      
       <li style="position:relative;">
        <label><em class="red">*</em> 设置新密码：</label>
        <input type="password" class="input_text" name="new_passwd" id="new_passwd">
         <div class="tip" id="new_passwd_tip">请设置新密码</div>
      </li>
      
       <li style="position:relative;">
        <label><em class="red">*</em> 再次输入新密码：</label>
        <input type="password" class="input_text" name="re_new_passwd" id="re_new_passwd">
         <div class="tip" id="re_new_passwd_tip">再次输入新密码</div>
      </li>
      
      
      
      
      <li>
        <label></label>
        <input type="button" value="下一步" class="btn_css" id="btn_email">
      </li>
      <li>
        <label></label>
        <a href="<?php echo url("index.register")?>" class="blue">如需注册，请点击这里！</a> </li>
    </ul>
  </div>
  <div class="right_login">
    <ul class="right_list">
      <li>已有账号，请直接登录</li>
      <li class="btn_box">
        <input type="button" value="登 录" class="btn_css" onclick="window.location.href = '<?php echo url("index.login")?>'">
      </li>
      <li>您还可以用其他方式直接登录：</li>
      <?php if ($GLOBALS ['C_ZYIIS']['oauth_qq_app_id']){?>
        <li class="other"> <a href="<?php echo url("oauth/qq.login")?>"><img src="<?php echo SRC_TPL_DIR?>/images/qqonline.gif"  border="0" alt="腾讯QQ登录"> QQ登录</a> </li>
        <?php }?> 
    </ul>
  </div>
  <div class="clear"></div>
</div>
<div class="footer">Copyright ©<?php echo date("Y",TIMES)?> All Rights Reserved</div>
<script>
$("#btn_username").click(function () {
	 var username = $.trim($("#txt_username").val());
     if (username == "") {
        $("#txt_username_tip").html('用户名不能为空').show();	
        return;
     }
	 $("#txt_username_tip").hide();	
	 var img_code = $.trim($("#img_code").val());
     if (img_code == "") {
        $("#img_code_tip").html('验证码不能为空').show();	
        return;
     }
	 $("#img_code_tip").hide();	
 	 $.ajax({
                    type: "post",
                    async: false,
                    url: "<?php echo url("index.find_passwd?step=1")?>",
                    data: { "username": username,"code": img_code, "rand": Math.random().toString() },
                    success: function (data) {
                        if(data =='user_error'){
							$("#txt_username_tip").html('不存在的用户名').show();	
						}else if(data =='code_error'){
							$("#img_code_tip").html('验证码不正确').show();	
						} else if(data =='email_error'){
							$("#txt_username_tip").html('当前用户无法找回请联系我们客服').show();	
						} else {
							  $("#ul_username").hide();	
							  $("#ui_emai").show();	
							  $(".intro_box").hide();
							  $("#verifiy_username").removeClass("on");
                			  $("#verifiy_email").addClass("on");
							  $("#to_email").html(data);
							  $(".mail_ok").show();	
							}
                    }
                });

});

$("#btn_email").click(function () {
	 var verifiy_email_code = $.trim($("#verifiy_email_code").val());
     if (verifiy_email_code == "") {
        $("#verifiy_email_code_tip").html('邮件中的验证码不能为空').show();	
        return;
     }
	 $("#verifiy_email_code_tip").hide();	
	 var new_passwd = $.trim($("#new_passwd").val());
     if (new_passwd == "") {
        $("#new_passwd_tip").html('请填写新密码').show();	
       return;
     }
	 $("#new_passwd_tip").hide();	
	 var re_new_passwd = $.trim($("#re_new_passwd").val());
     if (re_new_passwd == "") {
        $("#re_new_passwd_tip").html('请再一次输入上面的密码').show();	
       return;
     }
	 
	 if (re_new_passwd != new_passwd) {
        $("#re_new_passwd_tip").html('两次输入的密码不一致').show();	
       return;
     }
	 $("#re_new_passwd_tip").hide();
 	 $.ajax({
                    type: "post",
                    async: false,
                    url: "<?php echo url("index.find_passwd?step=2")?>",
                    data: { "code": verifiy_email_code,"new_passwd": new_passwd, "rand": Math.random().toString() },
                    success: function (data) {
                       if(data =='setp1' || data =='setpu1' || data =='nerror'){
							  $("#ul_username").show();	
							  $("#ui_emai").hide();	
							  $(".intro_box").show();
							  $("#verifiy_username").addClass("on");
                			  $("#verifiy_email").removeClass("on");
							 
						}else if(data =='codeerror'){
							 $("#verifiy_email_code_tip").html('邮件验证码不正确').show();	
						} else {
							 window.location.href = '<?php echo url("index.login")?>';
						}
                    }
                });

});
</script> 
