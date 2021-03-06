﻿<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header'); ?>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/highcharts/js/highcharts.js"></script>

<div id="left">
  <?php TPL::display('left');?>
</div>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
	<div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
		<div class="Hui-tabNav-wp">
			<ul id="min_title_list" class="acrossTab cl">
				<li class="active">
					<span title="我的桌面" data-href="welcome.html">首页</span>
					<em></em></li>
		</ul>
	</div>
		<div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
</div>
	<div id="iframe_box" class="Hui-article">
		<div class="show_iframe">
			<div style="width:1600px;height:750px;margin-left:45px;margin-top:30px">
			    	<h3>账号概况</h3><br>
			    	<hr width=100% size=3 color=#C0C0C0 style="filter:progid:DXImageTransform.Microsoft.Shadow(color#5151A2,direction:145,strength:15)"> 
			    	<br>
			        <div style="width:1600px;">
			        	 <div style="width:470px;height:55px;">
                              <div class="btn-group">
								  <span id="jr" class="btn btn-default radius" style="font-size:white;width:73px;height:40px;font-size:18px;background-color:#519af2">今日</span>
								  <span id="zr" class="btn btn-default radius" style="width:73px;height:40px;font-size:18px">昨日</span>
								  <span id="bz" class="btn btn-default radius" style="width:73px;height:40px;font-size:18px">本周</span>
								  <span id="by" class="btn btn-default radius" style="width:73px;height:40px;font-size:18px">本月</span>
								  <span id="sy" class="btn btn-default radius" style="width:73px;height:40px;font-size:18px">上月</span>
								  <span id="wjs" class="btn btn-default radius" style="width:87px;height:40px;font-size:18px">未结算</span>
								</div>

			        	 </div>
                          
                          <!-- 今日信息 -->
			        	 <div id="jinri" style="width:1600px;height:630px;">
			        	 	<br>
			        	 	<h3>今日收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_day_sunpay["sumpay"]);?>元</i></h3>
			        	 	<br>
                               <table class="table table-border table-bordered table-bg table-hover table-sort">
				                    <thead>
				                        <tr class="text-c">
				                            <th width="140">日期</th>
				                            <th width="140">横幅(元)</th>
				                            <th width="140">底飘(元)</th>
				                            <th width="140">合计收入(元)</th>
				                            
				                        </tr>
				                    </thead>
				                    <tbody>
                                        <tr class="text-c">
				                            <td><?php echo DAYS;?></td>
				                            <td><?php echo sprintf("%.2f", $get_day_sunpay["sumpay"]); ?></td>
				                            <td>0.00</td>
				                            <td><?php echo sprintf("%.2f", $get_day_sunpay["sumpay"]); ?></td>
                        				</tr>
				                    </tbody>	
				                    
                                </table>
                                 <br>
                                <span style="margin-left:1350px;font-size:15px">合计收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_day_sunpay["sumpay"]); ?>元</i></span>

                                <br><br><br>
                               
			        	 </div>


                         <!-- 昨日信息 -->
                         <div id="zuori" style="width:1600px;height:630px;display:none">
			        	 	<br>
			        	 	<h3>昨日收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_yesterday_sunpay["sumpay"]);?>元</i></h3>
			        	 	<br>
                               <table class="table table-border table-bordered table-bg table-hover table-sort">
				                    <thead>
				                        <tr class="text-c">
				                            <th width="140">日期</th>
				                            <th width="140">横幅(元)</th>
				                            <th width="140">底飘(元)</th>
				                            <th width="140">合计收入(元)</th>
				                            
				                        </tr>
				                    </thead>
				                    <tbody>
                                        <tr class="text-c">
				                            <td><?php echo date('Y-n-d', TIMES-86400);?></td>
				                            <td><?php echo sprintf("%.2f", $get_yesterday_sunpay["sumpay"]);?></td>
				                            <td>0.00</td>
				                            
				                            <td><?php echo sprintf("%.2f", $get_yesterday_sunpay["sumpay"]);?></td>
                        				</tr>
				                    </tbody>	
				                    
                                </table>
                                 <br>
                                <span style="margin-left:1350px;font-size:15px">合计收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_yesterday_sunpay["sumpay"]);?>元</i></span>

                                <br><br><br>
                                
                          
			        	 </div>	

			        	 <!-- 本周信息 -->	
			        	 <div id="benzhou" style="width:1600px;height:630px;display:none">
			        	 	<br>
			        	 	<h3>本周收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_week_sunpay["sumpay"]);?>元</i></h3>
			        	 	<br>
                               <table class="table table-border table-bordered table-bg table-hover table-sort">
				                    <thead>
				                        <tr class="text-c">
				                            <th width="140">日期</th>
				                            <th width="140">横幅(元)</th>
				                            <th width="140">底飘(元)</th>
				                            <th width="140">合计收入</th>
				                            
				                        </tr>
				                    </thead>
				                    <tbody>
									<?php foreach((array)$get_week_stats as $r) {
														  ?>
                                        <tr class="text-c">
				                            <td><?php echo $r['day'];?>
											</td>
												<td><?php echo sprintf("%.2f",$r['sumpay']);?></td>
				                            <td>0.00</td>
												<td><?php echo sprintf("%.2f",$r['sumpay']);?></td>
                        				</tr>
									<?php } ?>
				                    </tbody>	
				                    
                                </table>
                                 <br>
                                <span style="margin-left:1350px;font-size:15px">合计收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_week_sunpay["sumpay"]);?>元</i></span>

                                <br><br><br>
                               
			        	 </div>	

			        	 <!-- 本月信息 -->
			        	 <div id="benyue" style="width:1600px;height:630px;display:none">
			        	 	<br>
			        	 	<h3>本月收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_month_sunpay["sumpay"]);?>元</i></h3>
			        	 	<br>
                               <table class="table table-border table-bordered table-bg table-hover table-sort">
				                    <thead>
				                        <tr class="text-c">
				                            <th width="140">日期</th>
				                            <th width="140">横幅(元)</th>
				                            <th width="140">底飘(元)</th>
				                            <th width="140">合计收入</th>
				                            
				                        </tr>
				                    </thead>
				                    <tbody>
									<?php foreach((array)$get_month_stats as $r) {
														  ?>
                                        <tr class="text-c">
				                            <td><?php echo $r['day'];?>
											</td>
												<td><?php echo sprintf("%.2f",$r['sumpay']);?></td>
				                            <td>0.00</td>
												<td><?php echo sprintf("%.2f",$r['sumpay']);?></td>
                        				</tr>
									<?php } ?>
				                    </tbody>	
				                    	

				                    </tbody>	
				                    
                                </table>
                                 <br>
                                <span style="margin-left:1350px;font-size:15px">合计收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_month_sunpay["sumpay"]);?>元</i></span>

                                <br><br><br>
                              
                          
			        	 </div>	

			        	 <!-- 上月信息 -->
			        	 <div id="shangyue" style="width:1600px;height:630px;display:none">
			        	 	<br>
			        	 	<h3>上月收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_last_month_sunpay["sumpay"]);?>元</i></h3>
			        	 	<br>
                               <table class="table table-border table-bordered table-bg table-hover table-sort">
				                    <thead>
				                        <tr class="text-c">
				                            <th width="140">日期</th>
				                            <th width="140">横幅(元)</th>
				                            <th width="140">底飘(元)</th>
				                            <th width="140">合计收入</th>
				                            
				                        </tr>
				                    </thead>
				                    <tbody>
									<?php foreach((array)$get_last_month_stats as $r) {
										?>
					  <tr class="text-c">
						  <td><?php echo $r['day'];?>
						  </td>
							  <td><?php echo sprintf("%.2f",$r['sumpay']);?></td>
						  <td>0.00</td>
							  <td><?php echo sprintf("%.2f",$r['sumpay']);?></td>
					  </tr>
				  <?php } ?>
				                    </tbody>	
				                    
                                </table>
                                 <br>
                                <span style="margin-left:1350px;font-size:15px">合计收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_last_month_sunpay["sumpay"]);?>元</i></span>

                                <br><br><br>

                          
			        	 </div>	

			        	 <!-- 未结算信息 -->
			        	  <div id="weijiesuan" style="width:1600px;height:630px;display:none">
			        	 	<br>
			        	 	<h3>未结算收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_unpaid_sunpay["sumpay"]);?>元</i></h3>
			        	 	<br>
                               <table class="table table-border table-bordered table-bg table-hover table-sort">
				                    <thead>
				                        <tr class="text-c">
				                            <th width="140">日期</th>
				                            <th width="140">横幅(元)</th>
				                            <th width="140">底飘(元)</th>
				                            <th width="140">合计收入</th>
				                            
				                        </tr>
				                    </thead>
				                    <tbody>
									<?php foreach((array)$get_nopaid_stats as $r) {
										?>
					  <tr class="text-c">
						  <td><?php echo $r['day'];?>
						  </td>
							  <td><?php echo sprintf("%.2f",$r['sumpay']);?></td>
						  <td>0.00</td>
							  <td><?php echo sprintf("%.2f",$r['sumpay']);?></td>
					  </tr>
				  <?php } ?>
				                    </tbody>	
				                    
                                </table>
                                 <br>
                                <span style="margin-left:1350px;font-size:15px">合计收入：<i style="color:red">￥<?php echo sprintf("%.2f", $get_unpaid_sunpay["sumpay"]);?>元</i></span>

                                <br><br><br>

			        	 </div>		           
			        </div>
			       

			
			</div>
		</div>
  </div>
 <!-- 处理事件的点击 -->
 
</section>

<div class="contextMenu" id="Huiadminmenu">
	<ul>
		<li id="closethis">关闭当前 </li>
		<li id="closeall">关闭全部 </li>
</ul>
</div>
<!--_footer 作为公共模版分离出去-->

<script type="text/javascript" src="<?php echo SRC_TPL_DIR?>/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="<?php echo SRC_TPL_DIR?>/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="<?php echo SRC_TPL_DIR?>/static/h-ui.admin/js/H-ui.admin.js"></script> <!--/_footer 作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="lib/jquery.contextmenu/jquery.contextmenu.r2.js"></script>

<script type="text/javascript">


$(function(){
	/*$("#min_title_list li").contextMenu('Huiadminmenu', {
		bindings: {
			'closethis': function(t) {
				console.log(t);
				if(t.find("i")){
					t.find("i").trigger("click");
				}		
			},
			'closeall': function(t) {
				alert('Trigger was '+t.id+'\nAction was Email');
			},
		}
	});*/
});
/*个人信息*/
/*function myselfinfo(){
	layer.open({
		type: 1,
		area: ['300px','200px'],
		fix: false, //不固定
		maxmin: true,
		shade:0.4,
		title: '查看信息',
		content: '<div>管理员信息</div>'
	});
}
*/
/*资讯-添加*/
/*function article_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}*/
/*图片-添加*/
/*function picture_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}*/
/*产品-添加*/
/*function product_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}*/
/*用户-添加*/
/*function member_add(title,url,w,h){
	layer_show(title,url,w,h);
}*/


</script> 

<!--此乃百度统计代码，请自行删除-->
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?080836300300be57b7f34f4b3e97d911";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>