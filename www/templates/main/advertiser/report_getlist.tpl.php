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
      <li <?php if($timerange==$get_timerange['day']) echo "class='current'"?>> <a href="<?php echo url("advertiser/report.get_list?type=all&timerange=".$get_timerange['day'])?>">综合报告(当天)</a> </li>
      <li <?php if($timerange==$get_timerange['7day']) echo "class='current'"?>> <a href="<?php echo url("advertiser/report.get_list?type=all&timerange=".$get_timerange['7day'])?>">综合报告(一周)</a> </li>
      <li <?php  if($timerange==$get_timerange['30day']) echo "class='current'"?>> <a href="<?php echo url("advertiser/report.get_list?type=all&timerange=".$get_timerange['30day'])?>">综合报告(一月)</a> </li>
      <?php if(in_array('cps',$stats_type)){?>
       <li <?php //if($de_plantype==$t1) echo "class='current'"?>> <a href="<?php echo url("advertiser/orders.get_list?timerange=".$get_timerange['day'])?>">CPS明细报表</a> </li>
      <?php }?>
      <?php if(in_array('cpa',$stats_type)){?>
       <li <?php //if($de_plantype==$t1) echo "class='current'"?>> <a href="<?php echo url("advertiser/cpa_report.get_list?timerange=".$get_timerange['day'])?>"> CPA明细报表</a> </li>
       <?php }?>
    </ul>
  </div>
</div>
<div id="main" style="padding-top:1px">
  <?php if(!$report) {?>
  <!--<div class="alert alert-error" style="margin-top:10px"> 哇哦！没有当天数据 </div>-->
  <?php }?>
  <div class="box" >
    <div class="box-title" style="height:76px;">
      <div style="font-size:20px; "> 数据报告</div>
      <div class="button" style="float:left">
        <button class="btn btn-primary defaul_report">设置为默认报告</button>
        <span class="report_view_type"><strong>数据显示</strong>：
        <label>
          <input name="report_view_type" type="radio" value="all" <?php if($type=='all')echo " checked"?>/>
          汇总报告</label>
        <label>
          <input name="report_view_type" type="radio" value="plan" <?php if($type=='plan')echo " checked"?>  />
          项目报告</label>
        <label>
          </label>
        <label>
          <input name="report_view_type" type="radio" value="ads" <?php if($type=='ads')echo " checked"?>/>
          广告报告</label>
        </span> </div>
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
    <?php if($type!='all'){?>
    <div class="box-content mt30"  style="margin-left:20px">
     <?php if($type=='plan'){?>
      <select name="" onchange="location.href = this.options[selectedIndex].value">
        <option value="<?php echo url("advertiser/report.get_list?&type=".$type."&timerange=".$timerange)?>">全部数据</option>
        <?php 
			foreach((array)$view_type_data as $p) { 
			?>
        <option value="<?php echo url("advertiser/report.get_list?&type=".$type."&id=".$p[$to_id_type]."&timerange=".$timerange)?>" <?php if($id==$p[$to_id_type])echo " selected"?> ><?php echo $p[$to_name]?> #<?php echo $p[$to_id_type]?> </option>
        <?php } ?>
      </select>
      或 <?php } ?>搜索ID：
      <input name="searchval" id="searchval" type="text" class="input-27" style="width:120px" value="<?php echo $id?>"/>
      时间段：
      <input name="searchtime" id="searchtime" type="text" class="input-27" style="width:150px" value="<?php echo $timerange?>" onclick="__C('searchtime','2')"/>
      <button class="btn btn-primary search" style="margin-right:10px">搜索</button>
    </div>
    <?php }?>
    <div class="box-content" >
      <div  id="container" style="margin-top:40px; height:230px;"> </div>
      <div class="fold"> <a href="javascript:void(0);" id="fold_close"></a> </div>
    </div>
    <div class="box-content mt30">
      <?php if($type=='all'){?>
      <table class="table">
        <thead>
          <tr>
            <th>日期</th>
            <th>网页浏览量</th>
            <?php foreach( $stats_type as $t) {?>
            <th><?php echo strtoupper($t)?>有效</th>
            <?php }?>
            <th>支付</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach((array)$report as $r) {
		  		$num = explode(',',$r['num']);
				$sumadvpay = explode(',',$r['sumadvpay']);
				$plantype = explode(',',$r['plantype']);
				
				 if( $type=="site") {
				 	 $site =  dr ( 'advertiser/site.get_id_one' ,$r["siteid"]);
				 }
				 
				 if( $type=="zone") {
				 	 $zone =  dr ( 'advertiser/zone.get_one' ,$r["zoneid"]);
				 }
				 
		  ?>
          <tr>
            <td><?php echo $r['day']."	周".$get_timerange['week_array'][date("w",strtotime($r['day']))]; ?></td>
            <td><?php echo array_sum(explode(',',$r['views']))?></td>
            <?php foreach( $stats_type as $t) {?>
            <td><?php 
			 	$ns =0;	
			 	foreach( $plantype as $k =>$v) {
					if($v == $t){
					   $ns += $num[$k];
					} 
				}
				echo $ns;
			 ?></td>
            <?php }?>
            <td><?php 
				echo array_sum($sumadvpay);
			 ?>元</td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <?php }else {?>
      <table class="table">
        <thead>
          <tr>
            <th>日期 </th>
            <th><?php
            	switch ($type) {
					case 'plan':
						echo "计划项目";
						break;
					case 'ads':
						echo "广告ID";
						break;
				}
			?></th>
            <th>浏览量</th>
            <th>结算数</th>
            <th>支付</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach((array)$report as $r) { ?>
          <tr>
            <td><?php echo $r['day']."	周".$get_timerange['week_array'][date("w",strtotime($r['day']))]; ?></td>
            <td><?php
            	switch ($type) {
					case 'plan':
						 $plan =  dr ( 'advertiser/plan.get_one' ,$r["planid"]);
						 echo $plan['planname'] ;
						break;
					case 'ads':
						 
						 echo $r['adsid'] ;
						break;
				}
			?></td>
            <td><?php echo $r['views']?></td>
            <td><?php echo $r['num']?></td>
            <td><?php echo  abs($r['sumadvpay']) ?>元</td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <?php } ?>
    </div>
  </div>
</div>
<script type="text/javascript">

 
<?php if($_COOKIE['highcharts'] == 'hide'){
	echo "$('#container').hide();";
}else {
	echo "$('#container').show();highcharts()";	
	}?>		
	
function highcharts(){
        $('#container').highcharts({
		   chart:{
			 
		   	borderWidth:0,
			borderRadius:2
			
		   },
            title: {
                text: '<?php echo $timerange?  str_replace("_"," 至 ",$timerange):'所有时间';?>',
                x: -20 //center
            },
            
            xAxis: {
                categories: [<?php echo $xAxis?>],
				 tickInterval: <?php $cn = count(explode(',', $xAxis));echo $cn>7 ? (int)($cn*0.2) : 1;?>  ,
            },
            yAxis: {
                title: {
                    text: '流量统计 (IP)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }],
				min: 0
            },
            tooltip: {
                valueSuffix: ''
            } ,
            legend: {
                
                align: 'right',
                x: -10,
                verticalAlign: 'top',
                y: -10,
                floating: true,
                backgroundColor: '#FFFFFF',
				borderColor: '#FFFFFF'
            },
            series: [
			
		 
			 
			{
                name: '浏览量',
                data: [<?php echo $trend_view_data?>]
            },
			
			{
                name: '结算数',
                data: [<?php echo $trend_num_data?>]
            }
			
			]
        });
 } 
    
$(".menubutton").on('click', function(option) { 
     
	 var d = $(this).attr("data_href");
	 location.href = '<?php echo url("advertiser/report.get_list?&type=".$type."&id=".$id."&timerange=")?>' + d;
	 
});

$(".staendbutton").on('click', function(option) {  
	 var sta = $("#sta").val();
	 var end = $("#end").val();
	 if( (sta && !end) ||(!sta && end) ){
		 alert("开始时间和结束时间不能为空");
		 
	 }else{
	 	var d = sta+'_'+end;
	 	location.href = '<?php echo url("advertiser/report.get_list?&type=".$type."&id=".$id."&timerange=")?>' + d;
	 }
	
	 
});

$(".report_menu").on('click', function(option) {
	$(".report_menu_html").show();           
    var offset = $(this).offset();
    $('.report_menu_html').css('left',offset.left - 175+ 'px').css('top',offset.top+15+ 'px');
	 
});

$(".down > a").on('click', function(option) {
	$(".report_menu_html").hide(); 
	 
});

$("#fold_close").click(function(){
	var o = $('#container'); 
	if(o.css("display")=='none'){
		o.show(); 
	    $(this).css("backgroundImage","url(<?php echo SRC_TPL_DIR?>/images/fold_t.jpg)");
		var v ='show';
		highcharts();
	}else {
		o.hide();
	    $(this).css("backgroundImage","url(<?php echo SRC_TPL_DIR?>/images/fold_m.jpg)");
		var v ='hide';
	}
	$.get("<?php echo url("advertiser/report.highcharts?v=")?>"+v);
});


 
  $('input:radio[name="report_view_type"]').on('click', function(option) {
        var v = $(this).val();
        var url="<?php echo url("advertiser/report.get_list?timerange=".$timerange."&type=")?>"+v;
		location.href = url;
    });
   
   $('.search').on('click', function(option) {
        var v = $("#searchval").val();
		var s = $("#searchtime").val();
		 
        var url="<?php echo url("advertiser/report.get_list?type=".$type."&id=")?>"+v+'<?php echo url_f("&timerange=")?>'+s;
		location.href = url;
    });
	
	$('.defaul_report').on('click', function(option) {  
        var url="<?php echo url("advertiser/report.set_defaul_report?type=".$type)?>";
		$.get(url);
		alert("设置成功");
    });
	
 </script> 
