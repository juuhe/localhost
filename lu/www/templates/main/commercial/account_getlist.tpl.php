<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header'); ?>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/jquery-validation/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/jquery-validation/additional-methods.js"></script>

<div id="left">
  <?php TPL::display('left');?>
</div>
<div id="main" style="padding-top:10px;">
 <div class="alert alert-info" style="margin-top:10px;display:<?php if($_SESSION ['succ']){ echo "";}else {echo "none"  ;}?>"> 修改成功</div>
  <div class="box">
    <form action="<?php echo url("advertiser/account.edit_post")?>" method="POST" class="form-horizontal editAccount" id="form_b" >
      <div class="box-content">
        <div class="box-title" style="margin-bottom: 40px;">
          <h3><i class="icon-new"></i>基本信息 <span style="font-size:14px; padding-left:30px; color:#08c; cursor:pointer" id="s_edit">修改</span></h3>
        </div>
	   <input name="uid" id="uid"  type="hidden" value="<?php echo  $_SESSION['uid']?>" />
        <div class="control-group">
          <label for="textfield" class="control-label">用户名</label>
          <div class="controls"> <?php echo $u['username']?> </div>
        </div>
        <div class="control-group">
          <label for="textfield" class="control-label">密码</label>
          <div class="controls"> ******** <a href="javascript:;" id="s_editpass">修改密码</a> 
		  <div id="s_password">
		  		<p><span>原始密码</span><input type="password" name="oldpassword" id="oldpassword">
		  		</p>
				<p><span>新密码</span><input type="password" name="password" id="password">
				</p>
				<p><span>确认新密码</span><input type="password" name="password_confirm" id="password_confirm">
				</p>
				
				  <button type="button" class="btn btn-primary up_password" style="margin-left:102px; margin-top:10px"> 提 交 </button> 
				 
		  </div>
		  </div>
        </div>
        <div class="control-group">
          <label  class="control-label">手机号码</label>
          <div class="controls">
            <input type="text" name="mobile" id="mobile" class="input-27" value="<?php echo $u['mobile']?>">
            <span class="help-block"> </span> </div>
        </div>
        <div class="control-group">
          <label  class="control-label">QQ号码</label>
          <div class="controls">
            <input type="text" name="qq" id="qq" class="input-27" value="<?php echo $u['qq']?>">
          </div>
        </div>
        <div class="control-group">
          <label  class="control-label">电子邮件</label>
          <div class="controls">
            <input type="text" name="email" id="email" class="input-27" value="<?php echo $u['email']?>">
          </div>
        </div>
        <div class="control-group">
          <label for="textfield" class="control-label">固定电话</label>
          <div class="controls">
            <input type="text" name="tel" id="tel" class="input-27" value="<?php echo $u['tel']?>">
          </div>
        </div>
		
		  <div class="control-group">
          <label for="textfield" class="control-label">身份证号码</label>
          <div class="controls">
            <input type="text" name="idcard" id="idcard" class="input-27" value="<?php echo $u['idcard']?>">
          </div>
        </div>
       
		
        <div class="form-actions" style="margin-top:60px; margin-bottom:60px">
          <button type="submit" class="btn btn-primary"> 提 交 </button>
        </div>
      </div>
    </form>
  </div>
</div>
<script language="JavaScript" type="text/javascript">

$(document).ready(function() {
$(".input-27").css({"border-color":"#fff","border-width": "0px"}).attr("readonly",true);  
$('#s_edit').on('click', function(option) {
       $(".input-27").css({"border-color":"#ccc","border-width": "1px"}).attr("readonly",false);  
}); 

$('#s_editpass').on('click', function(option) {
     $('#s_password').show();

});
$('.up_password').on('click', function(option) {
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
	  $.post("<?php echo url("commercia/account.edit_password")?>", { oldpassword:oldpassword,password:password,password_confirm:password_confirm},
			function (data, textStatus){
				if(data){
					if(data=='err_pw'){
						alert("原始密码不能认证，无法修改")
					}
					if(data=='err_re'){
						alert("两次输入的密码不一样,请重新输入");
					}
					if(data=='ok'){
						  $('#s_password').hide();
						 $('.alert-info').show();
					}
				}
	 }, "text")

}); 
});
</script>
