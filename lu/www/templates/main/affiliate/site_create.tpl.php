
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/jquery-validation/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/jquery-validation/additional-methods.js"></script>

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
               line-height              : 40pt;
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
    	<h3> <?php  echo RUN_ACTION == 'create' ? '新建' : '编辑';?>网站</h3><br>
    	<hr width=100% size=3 color=#C0C0C0 style="filter:progid:DXImageTransform.Microsoft.Shadow(color#5151A2,direction:145,strength:15)"> 
    	<br>
        <form action="
	  <?php if(RUN_ACTION == 'edit') {
	  	echo url("affiliate/site.edit_post");
	  }else{
	  	echo url("affiliate/site.create_post");
	  }?>" method="POST" class="form-horizontal" id="form_b" >
              <input name="isok" id="isok"  type="hidden" value="" />
        <input name="siteid" id="siteid"  type="hidden" value="<?php echo  $site['siteid']?>" />
    	<i style="margin-left:60px;font-size:18px">网站名称:</i>&nbsp <input type="text"  name="sitename" class="input-text radius size-M" style="width:300px" value="<?php echo $site['sitename']?>"/><br><br>
    	<i style="margin-left:60px;font-size:18px">网站URL:</i>&nbsp 	<input type="text"  name="siteurl" class="input-text radius size-M" style="width:300px" value="<?php echo $site['siteurl']?>" 
        <?php if(RUN_ACTION == 'edit') {?>disabled="disabled" <?php }?>>
         <span style="font-size:15px">例如:http://qq.com(忽略www)</span>
    	<br><br>
    	<i style="margin-left:60px;font-size:18px">网站类型:</i>&nbsp 	
          <label id="lblSelect">
            <select id="selectPointOfInterest" title="Select points of interest nearby" name="sitetype">
            <option value="">请选择</option>
            <?php foreach((array)$sitetype as $st) {?>
            <option value="<?php echo $st['classid']?>" <?php if($site['sitetype']==$st['classid']) echo "selected"?> ><?php echo $st['classname']?></option>
            <?php }?>
            </select>
           </label>
    	<br><br>
    	<i style="margin-left:98px;font-size:18px">日IP:</i>&nbsp 	<input type="text"  name="dayip" class="input-text radius size-M" style="width:300px" value="<?php echo $site['dayip']?>"  name="dayip"/>
    	<br><br>
    	<i style="margin-left:70px;font-size:18px">备案号:</i>&nbsp &nbsp	<input type="text" name="beian"  class="input-text radius size-M" style="width:300px"  value="<?php echo $site['beian']?>"/><br><br>
        <i style="margin-left:98px;font-size:18px">网站描述:</i>&nbsp 	<textarea name="siteinfo" class="input-27" id="siteinfo" style="height:50px"><?php echo $site['siteinfo']?></textarea>
    	<br><br>
    	<i style="margin-left:98px;font-size:18px">平台:</i>&nbsp 	
          <label id="lblSelect">
            <select id="selectPointOfInterest" title="Select points of interest nearby">
                <option value=''>移动端</option>
                
            </select>
           </label>
    	<br><br>
    	<br>
    	
    	<input id="addSite" style="margin-left:100px" class="btn btn-primary size-M radius" type="submit" value="提交保存">
        </form>
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
		  <?php if(RUN_ACTION == 'create' && in_array($GLOBALS ['C_ZYIIS'] ['site_status'],array(4,5))) {?>
		  ignore: "",
		  <?php }?>
        rules: {
            siteurl: {
                required: true,
				remote:{  
					　　 type:"POST",    
					　　 url:'<?php echo url("affiliate/site.check_site_repeat")?>',  
					　　 data:{
						  siteurl:function(){
								return $("#siteurl").val();
							}
				　　 	   } 
				},
			   url2:true
            },
            sitename: {
                required: true
            },
			beian: {
                required: true
            },
			sitetype: {
                required: true
            },
			dayip: {
                required: true
            },
			isok: {
                required: true
            }
        },
        messages: {
            siteurl: {
				required:"网站url为能空",
				url2:"请填写一个正确url",
				remote:"存在的域名"
			},
            sitename: "网站名称不能为空",
			beian: "输入一个备案号",
			sitetype: "选择一个网站类型",
			dayip: "日访问量不能为空",
        	isok: "无法验证当前网站域名"
        },
        
        errorElement: 'span' ,
        errorPlacement: function(error, element) {
            var name = element.attr('name');  
            if (name == 'isok') {
                $('#ckinfo').append(error);
            } else {
                error.insertAfter(element);
            }
        }

    });


$("#cksite").click(function(){
		 if($("#form_b").validate().element($("#siteurl"))){
		 		$(".checksite").show();
		 }
});


$("#down").click(function(){
		if($("#form_b").validate().element($("#siteurl"))){
			this.href +="&url="+$("#siteurl").val();  
		}
});

$("#for_1").click(function(){
		this.className = 'active cktab';
		$('#for_2').removeClass().addClass("cktab");
		$('#text1').show();
		$('#text2').hide();
});
$("#for_2").click(function(){
		var siteurl = $("#siteurl").val();
		this.className = 'active cktab';
		$('#for_1').removeClass().addClass("cktab");
		$('#text2').show();
		$('#text1').hide();
		$.post("<?php echo url("affiliate/site.download_file")?>", { type:'html',url: siteurl},
		function (data, textStatus){
			if(data){
				$('#ck2val').val(data);
				$("#ck2val").attr("disabled",true);  
			}
		}, "text");
		
});

$("#doCheckSite").click(function(){
	 if($("#form_b").validate().element($("#siteurl"))){
			 var siteurl = $("#siteurl").val();
	 		 var cktype = $("input:[name=cktype]:radio:checked").val();
	 		$.post("<?php echo url("affiliate/site.check_site")?>", { type:cktype,url:siteurl},
			function (data, textStatus){
				if(data){
					if(data=='ok'){
						$("#ckinfo").html("验证完成");
						$('#isok').val("ok");
					}
					if(data=='repeat'){
						$("#ckinfo").html("无法完成验证，重复的域名");
					}
					
					if(data=='no'){
						$("#ckinfo").html("无法验证当前域名,请按上面的方法操作");
					}
					
				}
			}, "text")
	 }
});


});
</script>
    
</body>
</html>