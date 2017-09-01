<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header'); ?>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/jquery-validation/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/bigcolorpicker/js/jquery.bigcolorpicker.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/plan.js"></script>
<link rel="stylesheet" href="<?php echo WEB_URL?>js/jquery/lib/bigcolorpicker/css/jquery.bigcolorpicker.css" type="text/css" />
<div id="left">
  <div class="subnav">
    <div class="subnav-title"> <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>帮助</span></a> </div>
    <ul class="subnav-menu">
      <li> <a href="<?php echo url("index.article?id=84")?>" target="_blank">如何获取广告代码？</a> </li>
      <li> <a href="<?php echo url("index.article?id=85")?>" target="_blank">如何过渡广告？</a> </li>
      <li> <a href="<?php echo url("index.article?id=86")?>" title="一个广告位能显示多种广告样式吗？">广告位显示多种广告样式？</a> </li>
      <li> <a href="<?php echo url("index.article?id=87")?>">广告有哪些类型？</a> </li>
      <li> <a href="<?php echo url("index.article?id=88")?>">修改了广告位没有生效？</a> </li>
    </ul>
  </div>
</div>
<div id="main" style="padding-top:10px;">

  <div class="alert alert-info" style="margin-top:10px;display:<?php if($_SESSION ['succ']){ echo "";}else {echo "none"  ;}?>"> 修改成功</div>

	<div class="alert alert-error" style="margin-top:10px;display:<?php if($_SESSION ['err']){ echo "";}else {echo "none"  ;}?>"> 修改失败，需要审核后台才能修改</div>

  <div class="breadcrumbs mt30">
    <ul>
      <li> <a href="<?php echo url("advertiser/plan.get_list")?>">我的计划列表   »</a><?php if(RUN_ACTION == 'edit') {
	  	echo "编辑计划";
	  }else{
	  	echo "新建计划";
	  }?> </li>
    </ul>
    <div class="close-bread"> <a href="#"><i class="icon-remove"></i></a> </div>
  </div>
  <form action="
	  <?php if(RUN_ACTION == 'edit') {
	  	echo url("advertiser/plan.edit_post");
	  }else{
	  	echo url("advertiser/plan.create_post");
	  }?>" method="POST" enctype="multipart/form-data" name="form_b" class="form-horizontal" id="form_b"  >
    <input name="planid" id="planid"  type="hidden" value="<?php echo  $plan['planid']?>" />
    <input name="olb_price" id="olb_price"  type="hidden" value="<?php echo  $plan['priceadv']?>" />
    <div class="box">
      <div class="box-title">
        <h3><i class="icon-new"></i>基本信息</h3>
      </div>
      <div class="box-content" style="position:relative">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="zone_form controls">
          <tr>
            <td width="200">计划名称</td>
            <td><input type="text" name="planname" id="planname" class="input-27" value="<?php echo $plan['planname']?>" /></td>
          </tr>
          <tr>
            <td>计费模式</td>
            <td><select name="plantype" id="plantype"  <?php if(RUN_ACTION=='edit') echo "disabled='disabled'"?>  style="padding:5px; width:160px" >
                <?php foreach( explode(',', $GLOBALS['C_ZYIIS']['stats_type']) as $t) {?>
                <option value="<?php echo $t?>" <?php if($plan['plantype']==$t) echo "selected"?>>&nbsp;<?php echo strtoupper($t)?>&nbsp;</option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td valign="top">网站审核方式</td>
            <td><input name="audit" type="radio" value="n" <?php if ($plan['audit']=='n' || !$plan) echo "checked";?> />
              无需申请
              <input type="radio" name="audit" value="y" <?php if ($plan['audit']=='y') echo "checked";?> />
              人工审核 <span class="helpsp">需要站长申请才能投放</span></td>
          </tr>
          <tr >
            <td width="200" valign="top">投放设备</td>
            <td><input type="radio" value="all" name="acl[mobile][isacl]" <?php if($plan['checkplan']['mobile']['isacl'] == 'all' or !$plan) echo " checked"?>/>
              不限
              <input type="radio" value="acls" name="acl[mobile][isacl]" id="acl4isacl"   <?php if($plan['checkplan']['mobile']['isacl'] == 'acls') echo " checked"?>/>
              指定终端 <span id="mobile_data_error" class="frc_error"></span>
              <div style="<?php  if($plan['checkplan']['mobile']['isacl'] == 'all' or !$plan ) echo 'display:none'?>">
                <table style="width:450px" class="class_tb" >
                  <tr>
                    <?php 
								$mobile = array("pc"=>'桌面电脑',"ios"=>"苹果IOS","android"=>"Android","windows phone"=>"微软WP"); 	
								$i=1;
								foreach ($mobile as $k=>$m)
								{
									echo '<td ><label><input type="checkbox" name="acl[mobile][data][]" id="aclsitetype" value="'.$k.'"'?>
                    <?php 
									 
										if (in_array ($k, (array)$plan['checkplan']['mobile']['data'])) echo " checked";
									 	echo '>'.$m.'</label></td>';
									if ($i % 6 == 0){
										echo '</tr>';
									} 
									$i++;
								}
								?>
                  </tr>
                </table>
              </div></td>
          </tr>
          <tr>
            <td>结算周期 </td>
            <td><select name="clearing" style="padding:5px; width:160px">
                <option value="week" <?php if($plan['clearing']=='week' || !$plan) echo "selected"?>>周结项目</option>
                <option value="day" <?php if($plan['clearing']=='day') echo "selected"?>>日结项目</option>
                <option value="month" <?php if($plan['clearing']=='month') echo "selected"?>>月结项目</option>
              </select></td>
          </tr>
          <tr>
            <td>计划活动分类</td>
            <td><select name="classid" style="padding:5px; width:160px">
                <option  value="">选择分类</option>
                <?php  foreach ($plan_class AS $pc){?>
                <option value="<?php echo $pc["classid"]?>" <?php if($plan['classid'] == $pc["classid"] && $plan){echo 'selected';}?>><?php echo $pc["classname"]?></option>
                <?php }?>
              </select></td>
          </tr>
        </table>
        
         <table width="100%" border="0" cellpadding="0" cellspacing="0" class="zone_form controls keys">
         
          <tr>
            <td width="200">接口密钥</td>
            <td> <input name="pkey" type="text" class="input_text span30" id="info"  value="<?php echo $plan['pkey']?>">
              <span  class="helpsp">商家与联盟对接数据时的密钥.</span></td>
          </tr>
          
          <tr>
            <td width="200">数据返回</td>
            <td><input name="datatime" type="text" class="input-27"   value="<?php echo $plan['datatime']?  $plan['datatime'] :"隔天"?>">
              <span  class="helpsp">返回给站长的时间，比如 隔天 月底.</span></td>
          </tr>
          <tr>
            <td>cookie有效期</td>
            <td><input name="cookie" type="text" class="input-27"  value="<?php echo $plan['cookie']? $plan['cookie']: "30"?>">
              天 <span class="helpsp">在cookie有效期内联盟分成有效</span></td>
          </tr>
         
        </table>
        
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="zone_form controls table_cps">
          
          <tr>
            <td valign="top" width="200">自定义链接</td>
            <td><input name="linkon" type="radio" id="linkurl" value="y" <?php if ($plan['linkon']=='y' || !$plan) echo "checked";?> />
              开启
              <input type="radio" name="linkon" value="n" <?php if ($plan['linkon']=='n') echo "checked";?> />
              关闭 <span class="helpsp">站长可以随意自定义商品地址.</span></td>
          </tr>
          <tr>
            <td valign="top">自定义链接主域</td>
            <td><input name="linkurl" type="text" class="input-27"    value="<?php echo $plan['linkurl']?>">
              <span class="helpsp">如：www.taobao.com可自定义到这个域名下任何链接</span></td>
          </tr>
        </table>
      </div>
      <div class="box-title">
        <h3><i class="icon-new"></i>出价与预算</h3>
      </div>
      <div class="box-content" style="position:relative">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="zone_form controls">
          <tr id="gradeprice_d">
            <td width="200">站长分级佣金</td>
            <td><input type="radio" name="gradeprice" value="0"  <?php if ($plan['gradeprice']=='0' || !$plan) echo "checked";?>/>
              <span id="price_text1">固定分成</span>
              <input type="radio" name="gradeprice" id="gradeprice" value="1"   <?php if ($plan['gradeprice']=='1') echo "checked";?>/>
              <span id="price_text2">按类目分成</span>
              
              <input type="radio" name="gradeprice" id="gradeprice" value="2"   <?php if ($plan['gradeprice']=='2') echo "checked";?> />
              <span>接口自定义分成比例</span>
             
              </td>
          </tr>
          <tr id="classprice_d" <?php if ( $plan['plantype']!='cps' || $plan['gradeprice']=='0'  || $plan['gradeprice']=='2'  ) echo "style='display:none'";?>>
            <td width="200">按类提成</td>
            <td><div style="position: relative;">
                <table style="width:100%" border="0" cellpadding="0" cellspacing="0"  class="c_f_i">
                  <tr>
                    <td><table  style="width:100%" border="0" cellpadding="0" cellspacing="0"  class="c_f_f">
                        <tr>
                          <td>分成标识</td>
                          <td>标识说明</td>
                          <td>佣金（%）</td>
                          <td>备注</td>
                        </tr>
                        <?php 
						$cp_num  = 1;
						if($plan){
							$cp = (array)unserialize($plan['classprice']) ;
							$cp_num =  count($cp["classprice_mark"]);
						} 
						for($i=0;$i<$cp_num;$i++){
						?>
                        <tr>
                          <td><div>
                              <input name="classprice_mark[]" type="text" class="input-27 " style="width:90px" value="<?php echo $cp['classprice_mark'][$i]?>" />
                            </div></td>
                          <td><div>
                              <input name="classprice_mark_info[]" type="text" class="input-27 " style="width:120px" value="<?php echo $cp['classprice_mark_info'][$i]?>" />
                            </div></td>
                          <td><div>
                              <input name="classprice_adv[]" type="text" class="input_text span30 " style="width:50px" value="<?php echo $cp['classprice_adv'][$i]?>" />
                              %</div></td>
                          <td><div>
                              <input name="classprice_memo[]" type="text" class="input-27 " style="width:120px" value="<?php echo $cp['classprice_memo'][$i]?>" />
                              <?php if($i>=1){?>
                              <a href="javascript:;" class="delbtn"> 删</a>
                              <?php }?>
                            </div></td>
                        </tr>
                        <?php }?>
                      </table></td>
                    <td width="100"><a href="javascript:;" class="newbtn">增加一行</a></td>
                  </tr>
                </table>
              </div></td>
          </tr>
          <tr id="price_d" <?php if ( $plan['gradeprice']!='0' && $plan['plantype']=='cps' ) echo "style='display:none'";?>>
            <td width="200" id="price_text5">单价</td>
            <td><input name="priceadv" type="text" class="input-27" id="priceadv"  value="<?php echo $plan['priceadv']?>">
              <span id="price_text4"><?php echo $plan['plantype']=='cps' ? " %":" 元"?></td>
          </tr>
          
           <tr>
            <td>移动设备出价</td>
            <td> <input name="mobile_price" type="text" class="input-27" id="mobile_price"  value="<?php echo $plan['mobile_price']? abs($plan['mobile_price']) :'1'?>"> 
              倍 <span class="helpsp">为电脑设备的几倍</span></td>
          </tr>
          
          <tr>
            <td>每日限额</td>
            <td><input name="budget" type="text" class="input-27" id="budget"  value="<?php echo $plan['budget']?>">
              元 <span class="helpsp">达到每日预算限额时，广告就会在当天停止展示</span></td>
          </tr>
          <tr>
            <td valign="top">价格说明</td>
            <td><input name="priceinfo" type="text" class="input-27" id="priceinfo"  value="<?php echo $plan['priceinfo']?>">
              <span class="helpsp">广告计费简要说明</span></td>
          </tr>
        </table>
      </div>
      <div class="box-title">
        <h3><i class="icon-new"></i>结束日期</h3>
      </div>
      <div class="box-content" style="position:relative">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="zone_form controls">
          <tr>
            <td width="200">结束日期</td>
            <td><input type="radio" name="expire_date" value="0000-00-00"  <?php if ($plan['expire']=='0000-00-00' || !$plan) echo "checked";?>/>
              没有结束日期 <br>
              <input name="expire_date" type="radio" value="no" <?php if ($plan['expire']!='0000-00-00' && $plan) echo "checked";?>/>
              <select name="expire_year" id="expire_year" <?php if ($plan['expire']=='0000-00-00' || !$plan) echo "disabled='disabled'";?>>
                <?php 
										$expire = @explode("-",$plan['expire']);
										for ($i = date('Y') ;$i<date('Y')+21;$i++) {?>
                <option value="<?php echo $i?>" <?php if (($i == date('Y')+1&& !$plan) || $expire[0]==$i) echo 'selected'?>><?php echo $i?>年</option>
                <?php }?>
              </select>
              <select name="expire_month" id="expire_month" <?php if ($plan['expire']=='0000-00-00' || !$plan) echo "disabled='disabled'";?>>
                <?php for ($i = 1 ;$i<13;$i++) {?>
                <option value="<?php echo $i?>" <?php if (($i == date('n')&& !$plan)  || $expire[1]==$i) echo 'selected'?>><?php echo $i?>月</option>
                <?php }?>
              </select>
              <select name="expire_day" id="expire_day" <?php if ($plan['expire']=='0000-00-00' || !$plan) echo "disabled='disabled'";?>>
                <?php for ($i = 1 ;$i<32;$i++) {?>
                <option value="<?php echo $i?>" <?php if ( ($i == date('j',TIMES)&& !$plan)  || $expire[2]==$i) echo 'selected'?>><?php echo $i?>日</option>
                <?php }?>
              </select></td>
          </tr>
        </table>
      </div>
      <div class="box-title">
        <h3><i class="icon-new"></i>项目介绍</h3>
      </div>
      <div class="box-content" style="position:relative">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="zone_form controls">
          <tr>
            <td width="200">Logo</td>
            <td><input id="logo_imageurl" class="input-27" title="上传文件" type="file" name="logo_imageurl">
              <span> </span>
              <?php if($plan["logo"]){?>
              <img src="<?php echo $plan["logo"]?>"  style="120px; height:45px;"   border="0" align="absmiddle">
              <?php }?></td>
          </tr>
          <tr>
            <td width="200">项目介绍</td>
            <td><textarea name="planinfo" id="planinfo" class="input-27" style="width:320px;height:100px"><?php echo $plan['planinfo']?></textarea></td>
          </tr>
        </table>
      </div>
      <div class="box-title">
        <h3><i class="icon-new"></i>定向设置</h3>
      </div>
      <div class="box-content" style="position:relative">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="zone_form controls dx">
          <tr style="border-bottom: 1px #ccc dotted;">
            <td width="200" valign="top">投放地域</td>
            <td><input type="radio" value="all" name="acl[city][isacl]" <?php if($plan['checkplan']['city']['isacl'] == 'all' or !$plan) echo " checked"?>/>
              不限
              <input type="radio" value="acls" name="acl[city][isacl]"  id="acl0isacl"  <?php if($plan['checkplan']['city']['isacl'] == 'acls') echo " checked"?>/>
              选择地域 <span id="city_data_error" class="frc_error"></span>
              <div style=" <?php  if($plan['checkplan']['city']['isacl'] == 'all' or !$plan ) echo 'display:none'?>">
                <input id="radio"  type="radio"  value="==" name="acl[city][comparison]" <?php if($plan['checkplan']['city']['comparison'] == '==' || $plan['checkplan']['city']['comparison']=='' || !$plan) echo " checked"?>/>
                允许
                <input id="radio"  type="radio" value="!=" name="acl[city][comparison]" <?php if($plan['checkplan']['city']['comparison'] == '!=') echo " checked"?>/>
                拒绝 </div>
              <div style="  <?php  if($plan['checkplan']['city']['isacl'] == 'all' or !$plan ) echo 'display:none'?>" > 
                <SCRIPT LANGUAGE="JavaScript">				  
				  var _province = '<?php echo implode(',',(array)$plan['checkplan']['city']['province'])?>';
				  var _city = '<?php echo implode(',',(array)$plan['checkplan']['city']['data'])?>';
				  var _c = acity();document.write(_c);
				  </SCRIPT> 
              </div></td>
          </tr>
          <tr style="border-bottom: 1px #ccc dotted;">
            <td width="200" valign="top">网站类型</td>
            <td><input type="radio"  value="all" name="acl[siteclass][isacl]"  <?php if($plan['checkplan']['siteclass']['isacl'] == 'all' or !$plan) echo " checked"?>/>
              不限
              <input type="radio"  value="acls" name="acl[siteclass][isacl]" id="acl1isacl"  <?php if($plan['checkplan']['siteclass']['isacl'] == 'acls') echo " checked"?>/>
              指定类目 <span id="class_data_error" class="frc_error"></span>
              <div style="<?php  if($plan['checkplan']['siteclass']['isacl'] == 'all' or !$plan ) echo 'display:none'?>">
                <input  type="radio"   value="==" name="acl[siteclass][comparison]" <?php if($plan['checkplan']['siteclass']['comparison'] == '=='  || $plan['checkplan']['siteclass']['comparison']=='' || !$plan) echo " checked"?>/>
                允许
                <input  type="radio" value="!=" name="acl[siteclass][comparison]" <?php if($plan['checkplan']['siteclass']['comparison'] == '!=') echo " checked"?>/>
                拒绝 </div>
              <div style="<?php  if($plan['checkplan']['siteclass']['isacl'] == 'all' or !$plan ) echo 'display:none'?>">
              <table width="100%"  id="s1"   class="class_tb" >
                <tr>
                  <?php 
								$i=1;
								foreach ($site_class as $c)
								{
									echo '<td><label><input type="checkbox" name="acl[siteclass][data][]"  value="'.$c['classid'].'"'?>
                  <?php 
									if (in_array ($c['classid'], (array)$plan['checkplan']['siteclass']['data'])) echo " checked";
									 echo ' />'.$c['classname'].'</label></td>';
									if ($i % 6 == 0){
										echo '</tr>';
									} 
									$i++;
								}
								?>
                </tr>
              </table></td>
          </tr>
          <tr style="border-bottom: 1px #ccc dotted;">
            <td width="200" valign="top">周期日程</td>
            <td><input type="radio" value="all" name="acl[week][isacl]"  <?php if($plan['checkplan']['week']['isacl'] == 'all' or !$plan) echo " checked"?>/>
              不限
              <input type="radio" value="acls" name="acl[week][isacl]" id="acl2isacl"  <?php if($plan['checkplan']['week']['isacl'] == 'acls') echo " checked"?>/>
              设定周期日程 <span id="week_data_error" class="frc_error"></span>
              <div style="<?php  if($plan['checkplan']['week']['isacl'] == 'all' or !$plan ) echo 'display:none'?>">
                <table width="100%"  id="s1"   class="week_tb" >
                  <?php  
				$week  = array('1'=>"一",'2'=>"二",'3'=>"三",'4'=>"四",'5'=>"五",'6'=>"六",'7'=>"日");
				foreach($week AS $i=>$v) {  ?>
                  <tr>
                    <td width="80" valign="top" style="text-align: left;"><label>
                        <input type='checkbox'  name='acl[week][data][]' value='<?php echo $i?>' class="week" <?php if (in_array ($i, (array)$plan['checkplan']['week']['data'])  && $plan) echo " checked"?>>
                        星期<?php echo $v?></label></td>
                    <?php for($s=0;$s<24;$s++) {  ?>
                    <td align="center"  ><label>
                        <input type='checkbox'  name='acl[week][<?php echo $i?>][data][]' value='<?php echo $s?>'  class="week_in" <?php if (in_array ($s, (array)$plan['checkplan']['week'][$i]['data']) && $plan) echo " checked"?>>
                        <br>
                        <?php echo $s?></label></td>
                    <?php }?>
                  </tr>
                  <?php }?>
                </table>
              </div></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="form-actions" style="margin-top:60px; margin-bottom:60px">
      <button type="submit" class="btn btn-primary"> 提 交 </button>
    </div>
  </form>
</div>
<script language="JavaScript" type="text/javascript">

$(document).ready(function() {
    do_c($('#plantype').val());
    add_plan_vlt.init();


});

$('input:radio[name=expire_date]').on('click', function(option) {
    var v = $(this).val();
    if (v == 'no') {
        $("#expire_year,#expire_month,#expire_day").removeAttr("disabled");
    } else {
        $("#expire_year,#expire_month,#expire_day").attr("disabled", "disabled");
    }
});

$('input:radio[name="acl[city][isacl]"],input:radio[name="acl[siteclass][isacl]"],input:radio[name="acl[week][isacl]"],input:radio[name="acl[browser][isacl]"],input:radio[name="acl[mobile][isacl]"]').on('click', function(option) {
    var v = $(this).val();
    if (v == 'all') {
        $(this).next().next().next().next().hide();
        $(this).next().next().next().hide();
    } else {
        $(this).next().next().show();
        $(this).next().next().next().show();
    }
    add_plan_vlt.init();
});
$(".region >div").bind('mouseover', function() 
{
    if ($(this).next(".p_city").find('li').length > 2) {
        var v = $(this).find('input').val();
        $(this).addClass("hover");
        position = $(this).position();
        poptop = position.top;
        popleft = position.left + 66;
        $(this).next().show().css("top", poptop + "px").css("left", popleft + "px");
    }

}).bind('mouseleave', function() 
{
    $(this).next().hide();
    $(this).removeClass("hover");
});
$(".region >div").next().bind('mouseover', function() 
{
    $(this).prev().addClass("hover");
    $(this).show();
}).bind('mouseleave', function() 
{
    $(this).hide();
    $(this).prev().removeClass("hover");
});

$(".region >div>input").click(function() {
    
    var a = $(this).attr("checked");
    a = !a ? false : true;
    $(this).parent().next().find(':input').each(function() {
        
        $(this).attr("checked", a);
    });
});


$(".week_in").click(function() {
    var a = $(this).attr("checked");
    a = !a ? false : true;
    
    if ($(this).parent().parent().parent().find(".week_in:checked").length >= 1) {
        a = true;
    } else {
        a = false;
    }
    
    $(this).parent().parent().parent().find("input:first").attr("checked", a);
});


$(".city_in").click(function() {
    var a = $(this).attr("checked");
    a = !a ? false : true;
    var v = $(this).attr("id");
    id = v.split('_');
    
    if ($(".city_in:checked").length >= 1) {
        a = true;
    } else {
        a = false;
    }
    
    $("#province_" + id[1]).attr("checked", a);
});



$(".week").click(function() {
    var a = $(this).attr("checked");
    a = !a ? false : true;
    
    
    $(this).parent().parent().parent().find(':input:gt(0)').each(function() {
        $(this).attr("checked", a);
    });
});


$('input:radio[name="linkon"]').on('click', function(option) {
    var v = $(this).val();
    if (v == 'y') {
        $("#linkurl_s").show();
    } else {
        $("#linkurl_s").hide();
    }

});



$('#plantype').on('change', function(option) {
    var v = $(this).val();
    
    do_c(v)

});


$('input:radio[name=gradeprice]').on('click', function(option) {
    var v = $(this).val();
    if (v == 0) {
        $('#classprice_d').hide("");
        $('#price_d').show("");
    } else if (v == 1) {
        $('#classprice_d').show("");
        $('#price_d').hide("");
    } else {
        $('#classprice_d').hide("");
        $('#price_d').hide("");
    }
    
    add_plan_vlt.init();
});


function do_c(v) {
    
    $('.keys').hide("");
	$('.table_cps').hide("");
    if (v == "cps" || v == "cpa") {
        $('.keys').show("");
    }
    
    if (v == "cps") {
        $('#price_text1').html("固定分成");
        $('#price_text2').html("按类目分成");
		$('#price_text5').html("佣金分成");
        $('#price_text3,#price_text4').html(" %");
        $('.table_cps').show("");
        $('#gradeprice_d').show("");
    //$('#classprice_d').show("");

    //$('#price_d').hide("");
    
    } else {
        $('#price_text1').html("不分等级");
        $('#price_text2').html("分网站等级");
		$('#price_text5').html("单价");
        $('#price_text3,#price_text4').html(" 元");
        $('.table_cps').hide("");
        $('#s_price_f_cps').hide("");
        $('#gradeprice_d').hide("");
        $('#classprice_d').hide("");
    //$('#price_d').show("");
    }

}

add_plan_vlt = {
    init: function() {
        jQuery.validator.addMethod("SmallSize", function(value, element, params) {
            var pv = parseFloat($(params).val());
            if (!pv)
                pv = 0;
            return parseFloat(value) <= pv;
        }, "test值");
        jQuery.validator.addMethod("LargeSize", function(value, element, params) {
            var pv = parseFloat($(params).val());
            if (!pv)
                pv = 0;
            return parseFloat(value) >= pv;
        }, "test值");
        
        form_roles_validator = $("#form_b").validate({
            errorClass: "error",
            highlight: function(element) {
                $(element).closest('td').addClass("f_error");
            },
            unhighlight: function(element) {
                $(element).closest('td').removeClass("f_error");
            },
            rules: {
                uid: {required: true},
                planname: {
                    required: true
                }
                ,classid: {
                    required: true
                },
				'pkey':  {
                    required: true
                },
				'datatime':  {
                    required: true
                },
				'cookie':  {
                    required: true
                },
				linkurl:  {
                     required: "#linkurl:checked",
                },
                'acl[city][province][]': {
                    required: "#acl0isacl:checked",
                    minlength: 1
                },
                'acl[class][data][]': {
                    required: "#acl1isacl:checked",
                    minlength: 1
                },
                'acl[week][data][]': {
                    required: "#acl2isacl:checked",
                    minlength: 1
                },
                'acl[browser][data][]': {
                    required: "#acl3isacl:checked",
                    minlength: 1
                },
                'acl[mobile][data][]': {
                    required: "#acl4isacl:checked",
                    minlength: 1
                },
                'classprice_mark[]': {
                    required: "#gradeprice:checked",
                    minlength: 1
                },
                'classprice_mark_info[]': {
                    required: "#gradeprice:checked",
                    minlength: 1
                },
                'classprice_adv[]': {
                    required: "#gradeprice:checked",
                    minlength: 1,
                    number: true
                },
                priceadv: {
                    required: true,
                    number: true,
                    LargeSize: "#olb_price",
                    min: 0.0000001
                },
                budget: {
                    required: true,
                    digits: true
                }
            
            },
            messages: {
                uid: "选择一个广告商",
                planname: "请正确填写",
                classid: "选择一个活动分类",
				'pkey': "接口密钥不能为空",
				'datatime': "请正确填写",
				'cookie': "请正确填写",
				'linkurl': "请正确填写",
                'acl[city][province][]': "需要选择一个城市",
                'acl[class][data][]': "需要选择一个网站类型",
                'acl[week][data][]': "需要选择一个日程",
                'acl[browser][data][]': "需要选择一个浏览器",
                'acl[mobile][data][]': "需要选择一个终端",
                'classprice_mark[]': "",
                'classprice_mark_info[]': "",
                'classprice_aff[]': "",
                priceadv: {required: "请正确填写",number: "填写一个整数或是小数",LargeSize: "单价不能小于之前的",min: "单价值太小了吧"},
                budget: "请正确填写一个整数",
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                if (element.is(':radio') || element.is(':checkbox')) {
                    var eid = element.attr('name');
                    if (eid == "acl[city][province][]")
                        $('#city_data_error').html(error).addClass("frc_error");
                    if (eid == "acl[class][data][]")
                        $('#class_data_error').html(error).addClass("frc_error");
                    if (eid == "acl[week][data][]")
                        $('#week_data_error').html(error).addClass("frc_error");
                    if (eid == "acl[browser][data][]")
                        $('#browser_data_error').html(error).addClass("frc_error");
                    if (eid == "acl[mobile][data][]")
                        $('#mobile_data_error').html(error).addClass("frc_error");
                } else {
                    error.insertAfter(element);
                }
            }
        });
 
    }
};

</script> 