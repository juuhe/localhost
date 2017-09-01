<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>
<link rel="stylesheet" href="<?php echo SRC_TPL_DIR?>/style/modal.css" media="all" type="text/css" />
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/leanmodal/leanmodal.min.js"></script>
<div id="left">
 <?php TPL::display('left');?>
</div>
<div id="main" style="padding-top:10px">
   
  
  <div class="alert alert-info" style="margin-top:10px">
							<h4>可用余额 (<?php echo sprintf("%.2f",$GLOBALS['userinfo']['money']) ?>元)</h4>
							<p><button type="submit" class="btn btn-primary" id="do_onlinepay">马上充值»</button></p>
						 
  </div>
     
  
  <div class="box" >
    <div class="box-title">
      <h3><i class="icon-table"></i>付款记录</h3>
      
    </div>
     
    <div class="box-content">
      <table class="table">
        <thead>
          <tr>
            <th>日期</th>
            <th>订单</th>
            <th>金额</th>
            <th>网关</th>
            <th width="100">状态</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach((array)$paylog as $p) {
		  		
		  ?>
          <tr sid="<?php echo $p['addtime']?>">
            <td><?php echo $p['addtime']?></td>
            <td><?php echo $p["orderid"]?></td>
            <td>￥<?php echo $p["imoney"]?></td>
            <td><?php   foreach ($GLOBALS['c_onlinepay'] as $b=>$v){ 
								if($p['paytype'] == $v[0]) echo $b;
							}
						  ?></td>
            <td><span class="status">
              <?php
						  if ($p["status"] == '0') echo '<font color=red>充值没有完成</font>'; 	  
						  else if ($p["status"] == '1') echo '<font color=blue>充值失败</font>';
						  else if ($p["status"] == '2') echo " <font color=#ff6600>充值完成</font>";
						  else if ($p["status"] == '3') echo " <font color=blue>增加</font>";
						  else if ($p["status"] == '4') echo " <font color=red>扣除</font>";
						  ?>
            </span></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
        <div><?php  echo $page->echoPage ();?></div>
    </div>
  </div>
</div>
 <div id="add_pay_form" style="display:none">
  <form action="<?php echo url('advertiser/onlinepay.create_order')?>" name="pays" id="pays" method="post"  onsubmit="return doPay()" target="_blank">
    <div class="txt-fld" style="height:60px">
      <label for="" class="tit">网关</label>
	     <?php 
		 
		 foreach ($GLOBALS['c_onlinepay'] as $b=>$v){ 
		 	if(!$v[1]) continue;
		 	if (!$GLOBALS ['C_ZYIIS'][$v[0].'_id'])continue;
		 ?>
		<div style="float: left; margin-right:10px"> 
      <input type="radio" name="paytype" value="<?php echo $v[0]?>"  <?php if ($GLOBALS ['C_ZYIIS']['default_pay']==$v[0]) echo "checked";?> >
	  <img src="<?php echo SRC_TPL_DIR?>/images/<?php echo $v[0]?>.gif" width="120" height="45" /></div>
       
	  
	    <?php }?>
     </div>
    <div class="txt-fld"   id="to_username">
      <label for=""  class="tit">充值金额</label>
      <input id="imoney" name="imoney" type="text"> 充值金额不能小于 <font style="color:#FF0000"><?php echo $GLOBALS['C_ZYIIS']['min_pay']?></font> 元
    </div>
    
    <div class="btn-fld">
      <button type="submit">提交 »</button>
    </div>
  </form>
</div>

<script language="JavaScript" type="text/javascript">
$("#do_onlinepay").click(function(){
	box.form("#add_pay_form","在线充值",'ot');
});	

function doPay(){
	var m = $('#imoney').val();
	if(!m){
       alert("请填写充值金额");
	   return false;
     }
	 if(m<<?php echo $GLOBALS['C_ZYIIS']['min_pay']?>){
        	alert("充值金额不能小于<?php echo $GLOBALS['C_ZYIIS']['min_pay']?>元");
			return false;
     }
}

</script>