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
<link rel="stylesheet" type="text/css" href="<?php echo SRC_TPL_DIR?>/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SRC_TPL_DIR?>/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SRC_TPL_DIR?>/lib/Hui-iconfont/1.0.8/iconfont.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SRC_TPL_DIR?>/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="<?php echo SRC_TPL_DIR?>/static/h-ui.admin/css/style.css" />
<script type="text/javascript" src="<?php echo SRC_TPL_DIR?>/lib/jquery/1.9.1/jquery.min.js"></script> 
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
               width                    : 130pt;
               height                   : 30pt;
               line-height              : 30pt;
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
               font-size                : 13pt;
               font-weight              : 500;
               color                    : #000099;
               cursor                   : pointer;
               outline                  : none;
            }
            select#selectPointOfInterest option
            {
                padding             : 4px 10px 4px 10px;
                font-size           : 13pt;
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

</head>
<body>
	<div style="width:1600px;height:750px;margin-left:45px;margin-top:30px">
    	<h3> <?php  echo RUN_ACTION == 'create' ? '新建' : '编辑';?>广告位</h3><br>
      <?php if($_SESSION ['succ']) {?>
  <div class="alert alert-info" style="margin-top:10px"> 修改成功</div>
   <?php }?>
    	<hr width=100% size=3 color=#C0C0C0 style="filter:progid:DXImageTransform.Microsoft.Shadow(color#5151A2,direction:145,strength:15)"> 
    	<br>
      <form action="
	  <?php if(RUN_ACTION == 'edit') {
	  	   echo url("affiliate/zone.edit_post");
	  } else {
	  	echo url("affiliate/zone.create_post");
	  } ?>" method="POST" class="form-horizontal" id="form_b" >
    <input name="zoneid" id="zoneid"  type="hidden" value="<?php echo  $z['zoneid']?>" />
    	<span style="margin-left:60px;font-size:18px">计费模式:</span>&nbsp 
    	 
              
              <?php if(RUN_ACTION == 'edit' || $adsid){?>
              <input name="plantype" id="jifeimoshi"  type="radio" value="<?php echo $z['plantype']?>" checked/>
              <label for="radio-1"><?php echo strtoupper($z['plantype'])?></label>
              <?php } else {?>
              <?php foreach((array)$get_plantype_ok as $p) {?>
                <input name="plantype" id="jifeimoshi" type="radio" value="<?php echo $p['plantype']?>" <?php if($plantype==$p['plantype'] ) echo "checked"?>  />
                <label><?php echo strtoupper($p['plantype'])?></label>
              <?php } }?>


             
           <br><br>

              
     
    	<i style="margin-left:60px;font-size:18px">广告位名称:</i>&nbsp <input type="text"   name="zonename" class="input-text radius size-M"  value="<?php echo RUN_ACTION == 'create' ? "创建于".DATETIMES:$z['zonename']?>" style="margin-left:13px;width:300px"/>

    	<br><br>
    	<i style="margin-left:60px;font-size:18px" >广告类型:</i>&nbsp 	
          <label id="lblSelect" style="margin-left:28px">
          <?php if(RUN_ACTION == 'edit'){?>
                <input name="adtplid" id="adtplid" type="radio" value="<?php echo $z['adtplid']?>" checked>
                <?php $get_tpl = dr ( 'affiliate/adtpl.get_one_adtpl_adtype', $z['adtplid']);echo $get_tpl['tplname'].' - ' .$get_tpl['name']?>
                <?php } else {?>
                <select  name="adtplid" id="selectPointOfInterest" title="Select points of interest nearby" style="padding:5px; width:180px" >
                  <?php foreach((array)$get_adtpl_ok as $a) { ?>
                  <option value="<?php echo $a['tplid']?>" <?php if($a['tplid']==$adtplid) echo "selected"?>  > <?php echo $a['tplname'].' - ' .$a['name']?></option>
                  <?php } ?>
                </select>
                <?php } ?>
          </label>
    	<br><br>
    	<i style="margin-left:60px;font-size:18px" <?php if($adtpl['tpltype']=='script'  || $adtpl['tpltype']=='url_jump'){?>style="display:none" <?php } ?>>广告尺寸:</i>&nbsp 	
           <label id="lblSelect" style="margin-left:28px">
           <?php if(RUN_ACTION == 'edit'){?>
                <input name="specs" id="specs" type="radio" value="<?php echo $z['width'].'x' .$z['height']?>" checked>
                <?php  echo $z['width'].'x' .$z['height']?>
                <?php } else {?>
                <select name="specs" id="selectPointOfInterest" title="Select points of interest nearby">
                  <?php foreach((array)$adspecs as $sp) {?>
                  <option value="<?php echo $sp?>" <?php if($specs == $sp) echo "selected"?>> <?php echo $sp?></option>
                  <?php } ?>
                </select>
                <?php if($adtpl['customspecs']==2){?>
                <a href='javascript:;' style='margin-left:10px' id='ad_size_zd_a'>自定义尺寸</a> <a href='javascript:;' style='margin-left:10px; display:none' id='ad_size_zd_b'>选择尺寸</a>
                <?php } ?>
                <?php } ?>
           </label>
    	<br><br>
      <i class="ad_size_zd" style="display:none">
            <td valign="top">自定义尺寸</td>
            <td><table border="0" cellpadding="0" cellspacing="3" class="tbcodes">
                <tbody>
                  <tr>
                    <td  width="50">宽度：</td>
                    <td  ><input name="zd_size_w" type="text" id="zd_size_w" value="" size="8" maxlength="6" /></td>
                  </tr>
                  <tr>
                    <td>高度：</td>
                    <td><input name="zd_size_h" type="text" id="zd_size_h" value="" size="8" maxlength="6" /></td>
                  </tr>
                </tbody>
              </table></td>
          </i>	<br><br>
    	<i style="margin-left:60px;font-size:18px" <?php if(  $adtpl['tpltype']=='url_jump'){?>style="display:none" <?php } ?>>显示效果:</i>&nbsp 	
           <label id="lblSelect" style="margin-left:28px">
           <select name="styleid" id="selectPointOfInterest" title="Select points of interest nearby">
                <?php foreach((array)$adstyle as $as) {?>
                <option value="<?php echo $as['styleid']?>" <?php if($z['adstyleid']==$as['styleid'] || $styleid==$as['styleid']) echo "selected"?> > <?php echo $as['stylename']?></option>
                   <?php } ?>
              </select>
           </label>
    	<br><br>
      <i class="color_style_d" <?php if($adtpl['customcolor']==1){?>style="display:none" <?php } ?>>
            <td valign="top">配色</td>
            <td><table width="160" border="0" cellpadding="0" cellspacing="3" class="tbcodes">
                <tbody>
                  <tr>
                    <td  width="160">边框：
                      #
                      <input name="color[border]" type="text" id="color_border" value="<?php echo $codestyle['color']['border'] ? $codestyle['color']['border']:"FFFFFF"?>" size="8" maxlength="6"></td>
                    <td><span style="background-color:#<?php echo $codestyle['color']['border'] ? $codestyle['color']['border']:"FFFFFF"?>" data-target="color_border" class="j_clor color_border"></span></td>
                  </tr>
                  <tr>
                    <td>标题：
                      #
                      <input name="color[headline]" type="text" id="color_headline" value="<?php echo $codestyle['color']['headline'] ? $codestyle['color']['headline']:"0000FF"?>" size="8" maxlength="6"></td>
                    <td><span style="background-color:#<?php echo $codestyle['color']['headline'] ? $codestyle['color']['headline']:"0000FF"?>" data-target="color_headline" class="j_clor color_headline"></span></td>
                  </tr>
                  <tr>
                    <td>背景：
                      #
                      <input name="color[background]" type="text" id="color_background" value="<?php echo $codestyle['color']['background'] ? $codestyle['color']['background']:"FFFFFF"?>" size="8" maxlength="6"></td>
                    <td><span style="background-color:#<?php echo $codestyle['color']['background'] ? $codestyle['color']['background']:"FFFFFF"?>" data-target="color_background" class="j_clor color_background"></span></td>
                  </tr>
                  <tr>
                    <td>描述：
                      #
                      <input name="color[description]" type="text" id="color_description" value="<?php echo $codestyle['color']['description'] ? $codestyle['color']['description']:"444444"?>" size="8" maxlength="6"></td>
                    <td><span style="background-color:#<?php echo $codestyle['color']['description'] ? $codestyle['color']['description']:"444444"?>"   data-target="color_description"class="j_clor color_description"></span></td>
                  </tr>
                  <tr>
                    <td>链接：
                      #
                      <input name="color[dispurl]" type="text" id="color_dispurl" value="<?php echo $codestyle['color']['dispurl'] ? $codestyle['color']['dispurl']:"008000"?>" size="8" maxlength="6"></td>
                    <td><span style="background-color:#<?php echo $codestyle['color']['dispurl'] ? $codestyle['color']['dispurl']:"008000"?>" data-target="color_dispurl"  class="j_clor color_dispurl"></span></td>
                  </tr>
                </tbody>
              </table></td>
          </i>
    	<span style="margin-left:60px;font-size:18px">广告过滤:</span>&nbsp 
    	 
              
              <input name="viewtype" type="radio"  id="zhineng" value="1" <?php if($z['viewtype'] == 1 || !$z) echo "checked"?> />
              智能轮播
              <input name="viewtype" type="radio"  id="shoudong" value="2" <?php if($z['viewtype'] == 2 ) echo "checked"?> />
              手动选择 <span id="ckinfo"></span>
              <div  id="div" class="viewtype_html"  style="<?php if($z['viewtype'] == 1) { echo "display:none"; }?>" >
                <p>
                  <input type="checkbox" id="viewadsid_all" >
                  全选</p>
                我们为你匹配到以下的广告 <span id="viewtype_html_e_p_adnum"></span><br>
                <div class="a_d">
                  <?php   
				  foreach((array)$ads as $ad) { 
				 		 $ck = $au = '';
				        if(in_array($ad['adsid'], explode(",",$z["viewadsid"])) || $ad['adsid']==$adsid) $ck = ' checked' ; 
                   		$price = main_public::format_plan_print ( $ad ['planid'] );
						if (is_array ( $price )) {
							$price = $price ["min"] . '~' . $price ["max"];
						}
						if ($ad ['audit'] == 'y') {  
							$ap = dr ( 'affiliate/apply.get_apply_status', ( int ) $_SESSION ['affiliate'] ["uid"], $ad ['planid'] );
							if ($ap ['status'] == '0') {
								$ck = ' onclick="return false"  apply="n"';
								$au = '<font color="#ff0000">(申请审核中)</font>';
							} else if ($ap ['status'] == '1') {
								$ck = ' onclick="return false"  apply="n"';
								$au = '<font color="#ff0000">(申请被拒绝)</font>';
							} else if ($ap ['status'] == '2') {
								$audit = "a2";
							} else {
								$ck = ' onclick="return false" apply="n"';
								$au = '<a href="javascript:apply('.$ad['planid'].')" ><font color="#ff0000">(点击申请)</font></a>';
							}
						} 
						if($ad['headline']){
							$html .= ' <p><label> <input type="checkbox" name="viewadsid[]" value="'.$ad['adsid']. '" ' . $ck . ' > <a href= target="_blank">' .$ad['headline']. '#' .$ad['adsid']. ' </a>'.$au.'<font color="#ff0000"> / ' .$price.'元</font></label></p>';
						}else {
							$html .= ' <div class="img" id="ad_id_'.$ad['adsid'].'"><label> <input type="checkbox" name="viewadsid[]"  value="'.$ad['adsid']. '" ' . $ck . '>#' .$ad['planname'].'(Aid#'.$ad['adsid'].')'.$au.'<font color="#ff0000"> ' . ($price) . ''.($plantype == 'cps' ? "%" :"元").'</font>';
							if($ad['width']){
								$html .='<br><iframe  width='.$ad['width'].' height='.$ad['height'].' frameborder=0 src="'.url("affiliate/ad.view_ad?adsid=").$ad['adsid'].'" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no"></iframe>';
							}
							$html .='</label></div >';	
							}
					
                   } 
				   echo $html;
				   ?>
                </div>
              </div>
              
           <br>
           <div id="div" style="display:none;border:1px solid red;width:450px;height:200px;margin-left:170px">

           </div>
    	<br>
    	
    	<input style="margin-left:100px" class="btn btn-primary size-M radius" type="submit" value="提交保存">
      </form>
    </div>
    <script type="text/javascript">
           $(document).ready(function() {
               $("input[id='shoudong']").click(function() {
                
			        var val=document.getElementById('shoudong').checked;
			        
			         if(val==true){
			            document.getElementById('div').style.display='block';
			          }
			     			       
			       }); 
               $("input[id='zhineng']").click(function() {
			        var val=document.getElementById('zhineng').checked;
			      
			         if(val==true){
			            document.getElementById('div').style.display='none';
			          }
			     			       
			       }); 


           });

    </script> 
</body>
</html>