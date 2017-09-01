<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header'); ?>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/jquery-validation/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/bigcolorpicker/js/jquery.bigcolorpicker.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/jquery-validation/additional-methods.js"></script>
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
  <div class="breadcrumbs mt30">
    <ul>
      <li> <a href="<?php echo url("advertiser/ad.get_list")?>">我的广告列表   »</a>新建广告 </li>
    </ul>
    <div class="close-bread"> <a href="#"><i class="icon-remove"></i></a> </div>
  </div>
   <form action="
		 <?php  if(RUN_ACTION == 'create') {
			echo url('advertiser/ad.create_post');
		  } else {
			 echo url('advertiser/ad.edit_post');
		  }?>"  method="post" enctype="multipart/form-data" name="form_b" class="form-horizontal" id="form_b"   >
          <input name="adsid" id="adsid" type="hidden" value="<?php echo $ads['adsid']?>" />
		   <input name="height" id="height" type="hidden" value="<?php echo $ads['height']?>" />
		    <input name="width" id="width" type="hidden" value="<?php echo $ads['width']?>" />
			
   
    <div class="box">
      <div class="box-title">
        <h3><i class="icon-new"></i>基本信息</h3>
      </div>
      <div class="box-content" style="position:relative">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="zone_form controls">
          <tr>
            <td width="200">属于计划</td>
            <td>  <select name="planid" id="planid"  <?php if(RUN_ACTION=='edit') echo "disabled='disabled'"?> style="padding:5px; width:160px" >
                <option value=""> 请选择一个计划 </option>
                <?php 
			   		foreach ( explode(',', $GLOBALS['C_ZYIIS']['stats_type']) AS $t){
						
			   ?>
                <optgroup  label="<?php echo strtoupper($t)?>">
                <?php foreach((array)$plan_all AS $p) {  
						if($p['plantype']!==$t)continue ;
				?>
                <option value="<?php echo $p['planid']?>" <?php if($p['planid']==$ads ['planid'] OR $p['planid']==request('planid')) echo "selected"?>>&nbsp;<?php echo $p['planname']?>&nbsp;</option>
                <?php } ?>
                </optgroup>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td>广告类型</td>
            <td>  <?php if(RUN_ACTION == 'create') {?>
              <select name="adtplid" id="adtplid" style="padding:5px;  ">
                <option value=""> 请选择一个 </option>
              </select>
              <?php } else {  ?>
              <select name="adtplid" id="adtplid" disabled style="padding:5px; ">
                <option value="<?php echo $tpl['tplid']?>" >&nbsp;<?php echo $tpl['tplname']?> --- <?php echo $tpl['name']?>&nbsp;</option>
              </select>
              <?php }?>
            </td>
          </tr>
         <?php if(RUN_ACTION == 'edit' && $ads['width']) {
			 	 
			 ?>
          <tr>
            <td>广告尺寸</td>
            <td>
              <select disabled style="padding:5px">
                <option value="" >&nbsp;<?php echo $ads['width'].'x'.$ads['height']?>&nbsp;</option>
              </select>
            </td>
          </tr>
          <?php }?>
		  
        </table>
		
		 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="zone_form controls " id="wg">
         
         
           <?php  
				for($i=0;$i<sizeof($htmlcontrol['htmlcontrol_title']) ;$i++ ){
						 $ah  = ' <tr class="add_div"  > 
							  <td width="200">'.$htmlcontrol['htmlcontrol_title'][$i].'</td> 
							 <td>'; 
						  $iname = $htmlcontrol['htmlcontrol_name'][$i];
							$ivalue = $ads[$iname];	 
					  switch ($htmlcontrol['htmlcontrol_type'][$i] ) {
						  case 'text': 
						  
							if(substr( $iname, 0, 3) == 'zd['){
								 preg_match_all("/(?:\[)(.*)(?:\])/i",$iname, $result);
								 $ivalue  =  $zd_htmlcontrol[$result[1][0] ];
							};
						    $ah .= ' <input class="input-27" type = "text" name="'.$iname.'" value="'.$ivalue.'">';
							break;
						 case 'file':   
						
						    $ah .= ' <input type="radio" name="files" value="up" ';
							if(!$ads || $ads['files'] == 'up') $ah .='checked';
							$ah .='>上传图片 <input type="radio" name="files" value="url"  ';
							if($ads['files'] == 'url') $ah .='checked';
							$ah .='>远程文件';
							 
							$ah .='<br><span id="_up"';
							if($ads['files'] == 'url') $ah .='style="display:none"';
							$ah .='><input type = "file" class="input-27" name="imageurl" > '.$htmlcontrol['htmlcontrol_span'][$i].'</span> <span id="_url" ';  
							if($ads['files'] == 'up') $ah .='style="display:none"';
							$ah .='><input type="text" name="urlfile"  id="urlfile" class="input-27" value='.$ads['imageurl'].' > 远程绝对地址支持JPG,GIF,PNG,SWF,HTML（支持HTML格式文件）</span>';
							break;
					  }
					  $ah .='</td></tr>';
					  echo $ah;
				}
             ?>
             
             
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
	
	add_ad_vlt.init();


<?php if(RUN_ACTION == 'create') {?>	
dev = 1;
get_adtype(); 
<?php }else {?>
dev = 0;
<?php } ?>
	
 
$("#planid").change(function () {  
	get_adtype();
});


$("#adtplid").change(function () {  
	get_adtpl();
	add_ad_vlt.init();
});


function get_adtype(){
	$(".add_div").remove();
	 $.ajax({  
        type: "POST",  
        dataType: "json",  
        url: "<?php echo url('advertiser/ad.get_adtype')?>",  
        data: { planid: $("#planid").val()},  
        success: function (data) {
			if(data){ 
				 var ck,oh;
				 $("#adtplid").empty();
				 $("<option value=''>请选择一个</option>").appendTo("#adtplid"); 
				 $.each(data, function (i, n) {   
					if(n.tpl){
						oh = "<optgroup label='" + n.name + "'>";
						$.each(n.tpl, function (oi,on) { 
							 oh += "<option value=" + on.tplid + " "+ck+">" + on.name + "</option>";
						})
						oh += "</optgroup>";
						$(oh).appendTo("#adtplid");  }
				 });   
			}  	
        }  
     });   
 	
}


function get_adtpl(){
	 var v =  $("#adtplid").val(),ah; 
	  $(".add_div").remove();
	 $.ajax({  
        type: "POST",  
        dataType: "json",  
        url: "<?php echo url('advertiser/ad.get_adtpl')?>",  
        data: { tplid: $("#adtplid").val()},  
        success: function (data) {
			if(data){  
				var hl = data.htmlcontrol;  
				
				if(data.specs[0]['specs'] &&　data.customspecs  ==1){
					 	ah += ' <tr class="add_div" id="dv_specs">'+
							  ' <td width="200">显示尺寸</td>'+
							  ' <td >'+
							  '<select name="specs" id="specs" <?php if(RUN_ACTION=='edit') echo "disabled"?> style="padding:5px">';
								 $.each(data.specs , function (k, v) {
									 ah +=' <option value="'+v.specs+'"';		 
									 ah += '>'+v.specs+' &nbsp;&nbsp;'+v.stylename+'</option>'; 
								 });
								 ah +='</select></td></tr>'; 
				 }
				 
				 $.each(hl.htmlcontrol_title, function (i, h) {   
				 
				 
					  ah += ' <tr class="add_div"  >'+
							' <td width="200">'+h+'</td>'+
							' <td >';
					  switch (hl.htmlcontrol_type[i] ) {
						  case 'text': 
						    ah += ' <input class="input-27" type = "text" name="'+hl.htmlcontrol_name[i]+'">';
							break;
						 case 'file': 
						    ah += ' <input type="radio" name="files" value="up"  checked>上传文件 <input type="radio" name="files" value="url"  >远程文件';
							//ah += '<i id="files_rand"  "><input type="radio" name="files" value="rand">图库随机</i>';
							ah +='<br><span id="_up"><input type = "file" class="input-27" name="imageurl" > '+hl.htmlcontrol_span[i]+'</span> <span id="_url" style="display:none"><input type="text" name="urlfile"  id="urlfile" class="input-27" > 远程绝对地址支持JPG,GIF,PNG,SWF,HTML（支持HTML格式文件 ）</span>';
							break;	
					  }
					  ah +='</td></tr>';
				 });    
				
				 $(ah).appendTo("#wg");  
				 
				 files(); 
								     
			}  	
        }  
     });   
 	  add_ad_vlt.init();	     
}
 
 
 
 function files(){
	
	  $('input:radio[name=files]').on('click', function(option) {
                       
                        var v = $(this).val(); 
                        if (v == 'up') {
                            $('#_up').show();
                            $('#_url').hide();
                        } else if (v == 'url') {
                            $('#_up').hide();
                            $('#_url').show();
                        } else {
                            $('#_up').hide();
                            $('#_url').hide();
						}
                  });
  } 
 files(); 
 
 
});


add_ad_vlt = {
		init: function() {
			form_roles_validator = $("#form_b").validate({
		    errorClass :"error", 
			highlight: function(element) {
					$(element).closest('td').addClass("f_error");
				},
			unhighlight: function(element) {
				$(element).closest('td').removeClass("f_error");
			},
			rules: {
				  planid: {
				   		required: true 
                   },
				   adtplid: {
				   		required: true 
                   } ,
				   url: {
				   		required: true,
						 url2: true
                   } ,
				    
				   imageurl:{
				   		images_file: true
				   },
				   urlfile:{
				   		required: true
				   },			 
				   
				   headline:{
				   		required: true
				   },
				   description:{
				   		required: true
				   },
				   dispurl:{
				   		required: true
				   }
				 },
			messages: {
				 planid:"请选择一个计划",
				 adtplid:"请选择一个广告类型",
				 url:{required:"广告地址不能为空",url2:"请填写一个正确url"},
				 htmlcode:"请正确填写",
				 imageurl:"请选择一个图片",
				 urlfile:"请正确填写",
				 htmlfile:"请正确填写",
				 headline:"请正确填写",
				 description:"请正确填写",
				 dispurl:"请正确填写"
			},
			errorElement: 'span'
        });
    }
};
</script>
