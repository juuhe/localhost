<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header'); ?>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/jquery-validation/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/jquery-validation/additional-methods.js"></script>

<div id="left">
  <?php TPL::display('left');?>
</div>
<div id="main" style="padding-top:10px;">
  <div class="breadcrumbs mt30">
    <ul>
      <li> <a href="<?php echo url("affiliate/gift.get_list")?>">积分乐园   »</a>
        
        兑换 </li>
    </ul>
    <div class="close-bread"> <a href="#"><i class="icon-remove"></i></a> </div>
  </div>
  <div class="box">
    <div class="box-title">
      <h3><i class="icon-new"></i>基本信息</h3>
    </div>
    <div class="box-content" style="position:relative">
      <form action="
	  <?php echo url("affiliate/gift.exchange_post");?>" method="POST" class="form-horizontal" id="form_b" >
         
        <input name="id" id="id"  type="hidden" value="<?php echo  $gift['id']?>" />
        <div class="control-group">
          <label for="textfield" class="control-label">奖品名称</label>
          <div class="controls"> <?php echo $gift['name']?> 
          </div>
          
        </div>
        <div class="control-group">
          <label for="textfield" class="control-label">扣除积分</label>
          <div class="controls">
           <?php echo $gift['integral']?> 分
          </div>
        </div>
        <div class="control-group">
          <label  class="control-label">奖品介绍</label>
          <div class="controls">
           <?php echo $gift['content']?> &nbsp;
        </div>
        <div class="control-group">
          <label  class="control-label">收件人</label>
          <div class="controls">
             <input name="contact" type="text" class="input-27" id="contact"   value="" />
          </div>
        </div>
        <div class="control-group">
          <label for="textfield" class="control-label">电话</label>
          <div class="controls">
          <input name="tel" type="text" class="input-27" id="tel"   value="" /></div>
        </div>
        <div class="control-group">
          <label for="textfield" class="control-label">收件地址</label>
          <div class="controls">
            <input name="address" type="text" class="input-27" id="address"   value="" /></div>
          </div>
        </div>
        <div class="form-actions" style="margin-top:60px; margin-bottom:60px">
          <button type="submit" class="btn btn-primary"> 提 交 </button>
        </div>
      </form>
    </div>
  </div>
</div>
<script language="JavaScript" type="text/javascript">

$(document).ready(function() {

$("#form_b").validate({
        errorClass: "error",
        highlight: function(element) {
            $(element).closest('div').addClass("f_error");
        },
        unhighlight: function(element) {
            $(element).closest('div').removeClass("f_error");
        },
	 
        rules: {
            contact: {
                required: true
				 
            },
            tel: {
                required: true
            },
			address: {
                required: true
            } 
        },
        messages: {
            contact: "不能为空" ,
            tel: "不能为空" ,
			address: "不能为空" 
        },
        errorElement: 'span' ,
    });

 

});
</script>
