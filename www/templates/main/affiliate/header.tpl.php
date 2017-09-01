<!doctype html>
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
<title>站长后台</title>

<style type="text/css">
    .btn-group{ font-size:0}
	.btn-group .btn{ margin-left:-1px}
	.btn-group .btn:not(:first-child):not(:last-child):not(.dropdown-toggle){ border-radius:0}
	.btn-group > .btn:first-child:not(:last-child):not(.dropdown-toggle){border-bottom-right-radius: 0;border-top-right-radius: 0}
	.btn-group > .btn:last-child:not(:first-child),.btn-group > .dropdown-toggle:not(:first-child) {border-bottom-left-radius: 0;border-top-left-radius: 0}
</style>

 <script type="text/javascript">
       $(document).ready(function() {
       	$('#zr').click(function() {
       		$('#zr').css({"background-color":"#519af2","color":"white"});
       		$('#jr').css({"background-color":"#e1e1e1","color":"black"});
       		$('#bz').css({"background-color":"#e1e1e1","color":"black"});
       		$('#by').css({"background-color":"#e1e1e1","color":"black"});
       		$('#sy').css({"background-color":"#e1e1e1","color":"black"});
       		$('#wjs').css({"background-color":"#e1e1e1","color":"black"});
            
            document.getElementById("zuori").style.display="block";
            document.getElementById("jinri").style.display="none";
            document.getElementById("benzhou").style.display="none";
            document.getElementById("benyue").style.display="none";
            document.getElementById("shangyue").style.display="none";
            document.getElementById("weijiesuan").style.display="none";
            /*$.get("/zuori.txt",function(jdata) {
                $('#ajax').html(jdata);
            });*/

            // alert("ghlakdjgkl");
       	});

       	$('#bz').click(function() {
       		$('#bz').css({"background-color":"#519af2","color":"white"});
       		$('#jr').css({"background-color":"#e1e1e1","color":"black"});
       		$('#zr').css({"background-color":"#e1e1e1","color":"black"});
       		$('#by').css({"background-color":"#e1e1e1","color":"black"});
       		$('#sy').css({"background-color":"#e1e1e1","color":"black"});
       		$('#wjs').css({"background-color":"#e1e1e1","color":"black"});

       		document.getElementById("benzhou").style.display="block";
            document.getElementById("jinri").style.display="none";
            document.getElementById("zuori").style.display="none";
            document.getElementById("benyue").style.display="none";
            document.getElementById("shangyue").style.display="none";
            document.getElementById("weijiesuan").style.display="none";
       	});

       	$('#jr').click(function() {
       		$('#jr').css({"background-color":"#519af2","color":"white"});
       		$('#zr').css({"background-color":"#e1e1e1","color":"black"});
       		$('#bz').css({"background-color":"#e1e1e1","color":"black"});
       		$('#by').css({"background-color":"#e1e1e1","color":"black"});
       		$('#sy').css({"background-color":"#e1e1e1","color":"black"});
       		$('#wjs').css({"background-color":"#e1e1e1","color":"black"});

       		document.getElementById("jinri").style.display="block";
            document.getElementById("zuori").style.display="none";
            document.getElementById("benzhou").style.display="none";
            document.getElementById("benyue").style.display="none";
            document.getElementById("shangyue").style.display="none";
            document.getElementById("weijiesuan").style.display="none";
       	});

       	$('#by').click(function() {
       		$('#by').css({"background-color":"#519af2","color":"white"});
       		$('#jr').css({"background-color":"#e1e1e1","color":"black"});
       		$('#bz').css({"background-color":"#e1e1e1","color":"black"});
       		$('#zr').css({"background-color":"#e1e1e1","color":"black"});
       		$('#sy').css({"background-color":"#e1e1e1","color":"black"});
       		$('#wjs').css({"background-color":"#e1e1e1","color":"black"});

       		document.getElementById("benyue").style.display="block";
            document.getElementById("jinri").style.display="none";
            document.getElementById("benzhou").style.display="none";
            document.getElementById("zuori").style.display="none";
            document.getElementById("shangyue").style.display="none";
            document.getElementById("weijiesuan").style.display="none";
       	});

       	$('#sy').click(function() {
       		$('#sy').css({"background-color":"#519af2","color":"white"});
       		$('#jr').css({"background-color":"#e1e1e1","color":"black"});
       		$('#bz').css({"background-color":"#e1e1e1","color":"black"});
       		$('#by').css({"background-color":"#e1e1e1","color":"black"});
       		$('#zr').css({"background-color":"#e1e1e1","color":"black"});
       		$('#wjs').css({"background-color":"#e1e1e1","color":"black"});

       		document.getElementById("shangyue").style.display="block";
            document.getElementById("jinri").style.display="none";
            document.getElementById("benzhou").style.display="none";
            document.getElementById("benyue").style.display="none";
            document.getElementById("zuori").style.display="none";
            document.getElementById("weijiesuan").style.display="none";
       	});

       	$('#wjs').click(function() {
       		$('#wjs').css({"background-color":"#519af2","color":"white"});
       		$('#jr').css({"background-color":"#e1e1e1","color":"black"});
       		$('#bz').css({"background-color":"#e1e1e1","color":"black"});
       		$('#by').css({"background-color":"#e1e1e1","color":"black"});
       		$('#sy').css({"background-color":"#e1e1e1","color":"black"});
       		$('#zr').css({"background-color":"#e1e1e1","color":"black"});

       		document.getElementById("weijiesuan").style.display="block";
            document.getElementById("jinri").style.display="none";
            document.getElementById("benzhou").style.display="none";
            document.getElementById("benyue").style.display="none";
            document.getElementById("shangyue").style.display="none";

            document.getElementById("zuori").style.display="none";
       	});
       });

  </script>
</head>

<body>
<header class="navbar-wrapper" >
<aside class="Hui-aside">
	<div class="menu_dropdown bk_2" style="margin-top:20px">

		<img src="<?php echo SRC_TPL_DIR?>/temp/image/juuhe.jpg" style=""><br><br>
		<dl id="menu-article">
			<dt style="font-size:17px;font-family:'宋体';font-weight:bold"><i class="Hui-iconfont">&#xe625;</i> <a data-href="<?php echo url("aff/index.home"); ?>"  data-title="后台首页" href="javascript:void(0)">后台首页</a></dt>
				
	</dl>
		<dl id="menu-picture">
			<dt style="font-size:17px;font-family:'宋体';font-weight:bold"><i class="Hui-iconfont">&#xe705;</i> 个人中心<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a data-href="<?php echo url("aff/account.get_list")?>" data-title="修改个人资料" href="javascript:void(0)">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp修改个人资料</a></li>
					<li><a data-href="<?php echo url("affiliate/site.get_list")?>" data-title="管理我的网站" href="javascript:void(0)">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp管理我的网站</a></li>
			</ul>
		</dd>
	</dl>
		<dl id="menu-product">
			<dt style="font-size:17px;font-family:'宋体';font-weight:bold" ><i class="Hui-iconfont">&#xe647;</i> <a data-href="<?php echo url("aff/zone.get_list")?>"  data-title="获取代码" href="javascript:void(0)">获取代码</a></dt>
			
	</dl>
		<dl id="menu-comments">
			<dt style="font-size:17px;font-family:'宋体';font-weight:bold"><i class="Hui-iconfont">&#xe622;</i> <a data-href="<?php echo url("aff/paylog.get_list")?>"  data-title="获取代码" href="javascript:void(0)">财务报表</dt>
		
	</dl>
		
</div>
</aside>
	<div class="navbar navbar-fixed-top" style="height:50px">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" style="font-size:22px;font-family:'宋体';font-style: italic">聚合传媒后台管理系统</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" href="<?php echo url("affiliate/index.get_list")?>">聚合</a> 
			<!-- <span class="logo navbar-slogan f-l mr-10 hidden-xs">v3.1</span>  -->
			<a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
			
		<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs" style="width:500px">
			<ul class="cl">
				<li><a href="<?php echo url("index.article")?>" target="_blank" class="dropdown-toggle" data-toggle="dropdown" > <i class="Hui-iconfont">&#xe616;</i>&nbsp系统公告&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</a></li>
				<li><i class="Hui-iconfont"></i>
				<?php if($qq!=""){ ?>
				<a href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo $qq;?>&amp;site=qq&amp;menu=yes">
				<?php } else { ?>
                 <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=6543115&amp;site=qq&amp;menu=yes">
				<?php } ?>
				<i class="Hui-iconfont">&#xe67b;</i>&nbsp联系我们&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</a></li>
				<li><a id="yonghu" class="dropdown-toggle" data-toggle="dropdown" > <i class="Hui-iconfont">&#xe60a;</i>&nbsp<?php echo $GLOBALS ['userinfo']['username'];?>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </a></li>
				<li><a href="<?php echo url("main/main.logout?id=".$GLOBALS ['userinfo']['uid'])?>" class="dropdown-toggle" data-toggle="dropdown"> <i class="Hui-iconfont">&#xe726;</i>&nbsp退出&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp </a></li>
			</ul>
		</nav>
	</div>
</div>
</header>

<aside class="Hui-aside">
      <div class="menu_dropdown bk_2" style="margin-top:20px">
           <dl id="menu-picture">
                  <dt style="font-size:17px;font-family:'宋体';font-weight:bold"><i class="Hui-iconfont">&#xe705;</i> 个人中心<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
                  <dd>
                        <ul>
                              <li><a id="geren"  data-href="<?php echo url("affiliate/account.get_list")?>" data-title="修改个人资料" href="javascript:void(0)">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp修改个人资料</a><>
                             
                  </ul>
            </dd>
      </dl>
      
</div>
</aside>

<script type="text/javascript">

    const BTN1 = document.getElementById('yonghu');
    const BTN2 = document.getElementById('geren');
    BTN1.addEventListener('click', () => {
        BTN2.click(); 
     
    });

</script>



