<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header'); ?>
<link rel="stylesheet" href="<?php echo SRC_TPL_DIR?>/style/modal.css" media="all" type="text/css" />
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/leanmodal/leanmodal.min.js"></script>
<div id="left">
  <?php TPL::display('left');?>
</div>
<div id="main" >
  <table   border="0" cellpadding="0" cellspacing="0" class="mt30">
    <tr>
      <td width="21%"><span class="money"><?php echo sprintf("%.2f",$get_day_sunpay["sumadvpay"]) ?></span>元</td>
      <td width="21%"><span class="money"><?php echo sprintf("%.2f",$get_yesterday_sunpay["sumadvpay"]) ?></span>元</td>
      <td width="21%"><span class="money"><?php echo sprintf("%.2f",$get_month_sunpay["sumadvpay"]) ?></span>元</td>
      <td><span class="money"><?php echo sprintf("%.2f",$GLOBALS['userinfo']['money']) ?></span>元</td>
    </tr>
    <tr>
      <td>当日支付</td>
      <td>昨日支付</td>
      <td>当月支付</td>
      <td>账户余额</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><button type="submit" class="btn btn-primary" id="do_onlinepay">马上充值»</button>
        查看最近的充值记录</td>
    </tr>
  </table>
  <div class="box">
    <div class="box-title">
      <h3><i class="icon-table"></i>当天报告</h3>
       
    </div>
    <div class="box-content">
      <table class="table table-hover table-nomargin">
        <thead>
          <tr>
            <th>类型</th>
            <th width="120">浏览量</th>
            <th width="120" class="hidden-350">结算</th>
            <th width="160" class="hidden-480">支出</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach( (array)$stats  as $t) { ?>
          <tr>
            <td><?php echo strtoupper($t['plantype'])?>报表数据</td>
            <td><?php echo $t['views']?></td>
            <td class="hidden-350"><?php echo $t['num']?></td>
            <td class="hidden-480"><?php echo abs($t['sumadvpay'])?>元</td>
          </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
    <div class="box-title">
      <h3><i class="icon-table"></i>快捷报告</h3>
    </div>
    <div class="box-content ai_report">
      <ul>
        <li><a href="<?php echo url("advertiser/report.get_list?&timerange=".$get_timerange['thismonth'])?>">本月报告</a></li>
        <li><a href="<?php echo url("advertiser/report.get_list?&timerange=".$get_timerange['lastmonth'])?>">上个月报告</a></li>
        <li><a href="<?php echo url("advertiser/report.get_list?&timerange=".$get_timerange['7day'])?>">最近一周报告</a></li>
        <li><a href="<?php echo url("advertiser/report.get_list?&timerange=".$get_timerange['day'])?>">今天报告</a></li>
      </ul>
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
