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
<script type="text/javascript" src="<?php echo SRC_TPL_DIR?>/lib/ios/click.js" ></script>
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
               width                    : 185pt;
               height                   : 40pt;
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
               font-size                : 18pt;
               font-weight              : 500;
               color                    : #000099;
               cursor                   : pointer;
               outline                  : none;
            }
            select#selectPointOfInterest option
            {
                padding             : 4px 10px 4px 10px;
                font-size           : 11pt;
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
        <h3>移动展示代码</h3><br>
        <hr width=100% size=3 color=#C0C0C0 style="filter:progid:DXImageTransform.Microsoft.Shadow(color#5151A2,direction:145,strength:15)"> 
        <br>
        |&nbsp&nbsp&nbsp<i style="font-size:22px;font-style:normal">站点</i><br><br>
             <label id="lblSelect">
                    <select id="selectPointOfInterest" title="Select points of interest nearby">
                        <option value='' name="siteurl">请选择</option>
                        <?php foreach((array)$site as $r) {
                               if($r['status'] == '3'){
                            ?>
                            <option value='<?php echo $r['siteid'];?>'><?php echo $r['siteurl'];?></option>
                                    <?php }
                                } ?>
                        
                    </select>   
           </label>
       
        <br><br>
        |&nbsp&nbsp&nbsp<i style="font-size:22px;font-style:normal">移动端</i><br>
        <div class="mt-20 skin-minimal">
              <div class="radio-box">
                <input type="radio" id="radio-1" name="demo-radio1">
                <label for="radio-1">横幅CPC</label>
              </div>
              <div class="radio-box">
                <input type="radio" id="radio-2" name="demo-radio1" checked>
                <label for="radio-2">底飘CPV </label>
              </div>
        </div> 
        <br><br>  

        |&nbsp&nbsp&nbsp<i style="font-size:22px;font-style:normal">广告代码</i><br>  

        <textarea class="textarea radius" style="width:450px"></textarea><br><br>

        <input style="margin-left:20px" class="btn btn-primary size-M radius" id="daima" type="button" value="获取代码">
        <input style="margin-left:20px" class="btn btn-primary size-M radius" type="button" id="ios" value="IOS效果预览">
        <input style="margin-left:20px" class="btn btn-primary size-M radius" type="button" id="android" value="安卓效果预览">

        <br><br>

        |&nbsp&nbsp&nbsp<i style="font-size:22px;font-style:normal">投放说明：</i><br>

        <span style="font-size:15px">1、投放代码由系统自动生成，请勿修改其代码，否则可能会影响到您的网站广告投放和相关统计。<br>
            2、这里提供直接投放广告地址，站长可以获取该地址自行写JS效果，但不得使用iframe嵌入投放。<br>
            3、同一页面弹窗、横幅只允许同时投放1个广告； <br>
            4、如果超出我们声明的个数范围，我们视为作弊行为处理，将不再结算佣金。平台将自动根据您投放的个数及质量指数进行系统评级，等级的高低决定您广告佣金收入。<br>
            5、请各位会员注意：拒绝与任何存在作弊行为的网站合作，对于存在人为恶意骗取广告佣金的网站，聚合移动传媒将给以佣金拒付，并对该网站实行永久锁定。会被认为是恶     意骗取广告佣金的行为有(不仅限于)：以骗取佣金收益的恶意刷增广告展示量；以人工刷、程序刷、肉鸡、木马、插件等方式获取的劣质流量骗取佣金；通过弹窗或嵌套的方式获得的劣质流量网站；将广告投放到未经审核的网站以骗取佣金。<br> 
            6、投放页面不得存在散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪等内容，一经发现，聚合移动传媒将给以佣金拒付，并对该网站实行永久锁定的处理。</span>
        
       

    </div>
    <script>
$(document).ready(function() {
    $("#daima").click(function() {
        var id=$("#selectPointOfInterest  option:selected").val();
       // alert(url);
        $.ajax(
			{
				dataType: 'html',
				url: '<?php echo url("affiliate/zone.id")?>',
				type: 'post',
				data: 'siteid=' +id,
				success: function(da) 
				{
                    var html ="<script src='<?php echo $GLOBALS['C_ZYIIS']['js_url'].WEB_URL?>s.php?id="+da+"'><\/script>";
                    //alert(html)
                     $(".textarea").val(html);
				}
			});
    });

   });
   </script>

</body>
</html>