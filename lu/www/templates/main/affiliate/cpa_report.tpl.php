<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header'); ?>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/highcharts/js/highcharts.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/calendar/calendar.js"></script>
<link rel="stylesheet" href="<?php echo WEB_URL?>js/calendar/calendar.css" media="all" type="text/css" />

<div id="left">
  <div class="subnav">
    <div class="subnav-title"> <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>效果报告</span></a> </div>
    <ul class="subnav-menu">
      <li> <a href="<?php echo url("affiliate/report.get_list?&timerange=".$get_timerange['day'])?>">综合报告(当天)</a> </li>
      <li> <a href="<?php echo url("affiliate/report.get_list?&timerange=".$get_timerange['7day'])?>">综合报告(一周)</a> </li>
      <li> <a href="<?php echo url("affiliate/report.get_list?&timerange=".$get_timerange['30day'])?>">综合报告(一月)</a> </li>
       <?php if(in_array('cps',explode(',', $GLOBALS['C_ZYIIS']['stats_type']))){?>
      <li> <a href="<?php echo url("affiliate/orders.get_list?timerange=".$get_timerange['day'])?>">CPS明细报表</a> </li>
       <?php }?>
      <li class='current'> <a href="<?php echo url("affiliate/cpa_report.get_list?timerange=".$get_timerange['day'])?>"> CPA明细报表</a> </li>
    </ul>
  </div>
</div>
<div id="main" style="padding-top:1px">
  
  <div class="box" >
    <div class="box-title" style="height:76px">
      <div style="font-size:20px; ">
        <?php  echo $timerange ?  str_replace("_"," 至 ",$timerange) : "所有时间 "?>
        报告</div>
      <div class="button" style="float:left">
        <button class="btn btn-primary stausbnt" status=1>有效CPA</button>
           <button class="btn btn-primary stausbnt" status=0>待确认CPA</button>
         <button class="btn btn-primary stausbnt" status=2>无效CPA</button>
         <button class="btn btn-primary stausbnt"  status=all>全部CPA</button>
      </div>
      <div class="report_menu">
        <div class="report_menu_date">
          <div class="report_menu_date_text">按时间段</div>
          <div class="report_menu_date_time"><?php echo $timerange?  str_replace("_"," 至 ",$timerange):'所有时间';?></div>
        </div>
        <div class="report_menu_right">
          <div class="report_menu_right_bg"></div>
        </div>
      </div>
      <div class="report_menu_html" style="display:none">
        <div class="up">
          <div class="up_left">
            <div><strong>日期范围</strong></div>
            <div style="margin-top:10px">
              <input type="text" name="sta" id="sta" onclick="__C('sta','1')">
              <span> – </span>
              <input type="text" name="end" id="end" onclick="__C('end','1')">
            </div>
          </div>
          <div class="up_right">
            <div><strong>快速报告的时间范围</strong></div>
            <div style="margin-top:10px">
              <table>
                <tbody>
                  <tr>
                    <td><a class="menubutton" href="javascript:void(0)" data_href="<?php echo $get_timerange['day']?>">今天：<?php echo date("n月d日", TIMES)?></a></td>
                    <td><a class="menubutton" href="javascript:void(0)" data_href="<?php echo $get_timerange['thismonth']?>">本月：<?php echo date("n", TIMES)?>月</a></td>
                  </tr>
                  <tr>
                    <td><a class="menubutton" href="javascript:void(0)" data_href="<?php echo $get_timerange['yesterday']?>">昨天：<?php echo date("n月d日", TIMES-86400)?></a></td>
                    <td><a class="menubutton" href="javascript:void(0)" data_href="<?php echo $get_timerange['lastmonth']?>">上月：<?php echo date('n', mktime(0, 0, 0, date('m',TIMES), 0, date('Y',TIMES)))?>月</a></td>
                  </tr>
                  <tr>
                    <td><a class="menubutton" href="javascript:void(0)" data_href="<?php echo $get_timerange['7day']?>">过去 7 天</a></td>
                    <td><a class="menubutton" href="javascript:void(0)" data_href="">所有时间</a></td>
                  </tr>
                  <tr>
                    <td><a class="menubutton" href="javascript:void(0)"  data_href="<?php echo $get_timerange['30day']?>">最近 30 天</a></td>
                    <td>&nbsp;</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="down">
          <button class="btn btn-primary staendbutton" style="margin-right:10px">应用</button>
          <a href="#">取消 </a></div>
      </div>
    </div>
    <div class="box-content mt30">
      <table class="table">
        <thead>
          <tr>
            <th>日期</th>
            <th>唯一认证</th>
            <th>计划项目</th>
            <th>行为状态</th>
            <th>联盟状态</th>
            <th>收入</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach((array)$cpa_report as $c) {
		  		 $p =  dr ( 'affiliate/plan.get_one' ,$c["planid"]);
				 
		  ?>
          <tr>
            <td><?php echo $c['day']?></td>
            <td><?php echo $c["unique_id"]?></td>
            <td><?php echo $p['planname']?></td>
            <td><?php echo $c["cpastatus"]?></td>
            <td><span class="status">
              <?php  
							if ($c['status'] == 0){ echo '<span class="notification error_bg">待确认</span>';} 
							if ($c['status'] == 1){ echo '<span class="notification info_bg">已确认</span>';} 
							if ($c['status'] == 2){ echo '<span class="notification error_bg">已作废</span>';} 
					?>
            </span></td>
            <td><?php echo $c["price"]?></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
     <div><?php  echo $page->echoPage ();?></div>
  </div>
</div>
<script type="text/javascript">

 
		
		
$(function () {
        
    
$(".menubutton").on('click', function(option) { 
     
	 var d = $(this).attr("data_href");
	 location.href = '<?php echo url("affiliate/cpa_report.get_list?&status=".$status."&timerange=")?>' + d;
	 
});

$(".staendbutton").on('click', function(option) {  
	 var sta = $("#sta").val();
	 var end = $("#end").val();
	 if( (sta && !end) ||(!sta && end) ){
		 alert("开始时间和结束时间不能为空");
		 
	 }else{
	 	var d = sta+'_'+end;
	 	location.href = '<?php echo url("affiliate/cpa_report.get_list?&status=".$status."&timerange=")?>' + d;
	 }
	
	 
});
$(".stausbnt").on('click', function(option) { 
	   var val = $(this).attr('status');
	   location.href = '<?php echo url("affiliate/cpa_report.get_list?&timerange=".$timerange."&status=")?>' + val;
});
$(".report_menu").on('click', function(option) {
	$(".report_menu_html").show();           
    var offset = $(this).offset();
    $('.report_menu_html').css('left',offset.left - 175+ 'px').css('top',offset.top+15+ 'px');
	 
});

$(".down > a").on('click', function(option) {
	$(".report_menu_html").hide(); 
	 
});
 
});
 
		</script> 
