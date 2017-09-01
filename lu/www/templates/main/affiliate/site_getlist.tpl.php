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

<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>H-ui.admin v3.1</title>
<script type="text/javascript">
 function addweb() {
    window.location.href="/index.php?e=aff/site.create";
 }
</script>

</head>
<body>
	<div style="width:1600px;height:750px;margin-left:45px;margin-top:30px">
    	<h3>管理我的网站</h3><br>
    	<hr width=100% size=3 color=#C0C0C0 style="filter:progid:DXImageTransform.Microsoft.Shadow(color#5151A2,direction:145,strength:15)"> 
    	<br>
        <div style="width:1600px;height:50px;">
           <input style="margin-left:1400px;margin-top:15px" class="btn btn-primary size-M radius" type="button" onclick="addweb()" value="添加网站">
        </div>
        <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                        <tr class="text-c">
                            <th width="40">状态</th>
                            <th width="80">网站ID</th>
                            <th width="150">网站名称</th>
                            <th width="150">网站域名</th>
                            <th width="100">每日流量</th>
                            <th width="60">网站类型</th>
                            <th width="130">网站备案</th>
                            <th width="130">星级</th>                           
                            <th width="60">操作</th>
                        </tr>
                    </thead>
                    <?php foreach((array)$site as $s) { 
		  		$c =  dr ( 'main/class.get_one',$s["sitetype"] );
		  ?>
          <tr class="text-c" sid="<?php echo $s['siteid']?>">
          <td><a href="<?php echo url("affiliate/site.edit?siteid=".$s['siteid'])?>">修改</a> <a href="#" class="delsite" onclick='return confirm("确认删除吗");'>删除</a></td>
          <td><?php echo $s['siteid']?></td>
            <td><?php echo $s['sitename']?></td>
            <td><?php echo $s['siteurl']?></td>
            <td><?php echo $s['dayip']?></td>
            <td><?php echo $c['classname']?></td>
            <td><?php echo $s['beian']?></td>
            <td><img alt="" src="<?php echo SRC_TPL_DIR?>/images/s<?php echo $s['grade']?>.jpg" title="<?php echo $s['grade']?>级" /></td>
            <td><?php 
					 
			  		switch($s['status']){
						case 0:
							echo '<span class="notification error_bg">新增待审</span>';
							break;
						case 1:
							echo '<span class="notification error_bg">拒绝</span>';
							break;
						case 2;
							echo '<span class="notification error_bg">锁定</span>';
							break;
						case 3:
							echo '<span class="notification info_bg">正常</span>';
							break;
						case 4:
							echo '<span class="notification error_bg">修改待审</span>';
							
					} 
				?> </td>
           
          </tr>
          <?php }?>
                   <!--  <tbody>
                        <tr class="text-c">
                            <td><input name="" type="checkbox" value=""></td>
                            <td>001</td>
                            <td>分类名称</td>
                            <td><a href="javascript:;" onClick="picture_edit('图库编辑','picture-show.html','10001')"><img width="100" class="picture-thumb" src="pic/200x150.jpg"></a></td>
                            <td class="text-l"><a class="maincolor" href="javascript:;" onClick="picture_edit('图库编辑','picture-show.html','10001')">现代简约 白色 餐厅</a></td>
                            <td class="text-c">标签</td>
                            <td>2014-6-11 11:11:42</td>
                            <td class="td-status"><span class="label label-success radius">已发布</span></td>
                            <td class="td-manage"><a style="text-decoration:none" onClick="picture_stop(this,'10001')" href="javascript:;" title="下架"><i class="Hui-iconfont">&#xe6de;</i></a> <a style="text-decoration:none" class="ml-5" onClick="picture_edit('图库编辑','picture-add.html','10001')" href="javascript:;" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> <a style="text-decoration:none" class="ml-5" onClick="picture_del(this,'10001')" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
                        </tr>
                    </tbody> -->
                </table>
            </div>

<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/leanmodal/leanmodal.min.js"></script>
<link rel="stylesheet" href="<?php echo SRC_TPL_DIR?>/style/modal.css">
<script>
	$(document).ready(function() {
	$(".delsite").click(function() {
		_siteid = $(this).parent().parent().attr("sid");
		window.location.reload();
		$.ajax(
			{
				dataType: 'html',
				url: '<?php echo url("affiliate/site.del")?>',
				type: 'post',
				data: 'siteid=' + _siteid,
				success: function() 
				{
					 _parent.css("backgroundColor", "#faa").hide('normal');
				}
			});
	});
});
</script>
<script language="JavaScript" type="text/javascript">

$("#zid").on('keyup', function(option) {
	 var v = $(this).val();
	 $(".d_a").each(function() {
	 	 $(this).hide();
          if(v == $(this).attr("zid"))   $(this).show();
     });
	 if(!v) $(".d_a").show(); 
	 
});

$("#zname").on('keyup', function(option) {
	 var v = $(this).val();
	 $(".d_a").each(function() {
	 	  $(this).hide();
          if ($(this).attr("zname").indexOf(v) > -1 )  $(this).show();
     });
	 if(!v) $(".d_a").show(); 
	 
});


$("#zadsize").on('change', function(option) {
	 var v = $(this).val();
	 $(".d_a").each(function() {
	 	  $(this).hide();
          if ($(this).attr("zsize").indexOf(v) > -1 )  $(this).show();
     });
	 if(!v) $(".d_a").show(); 
	 
});


$("#zadtplid").on('change', function(option) {
	 var v = $(this).val();
	 $(".d_a").each(function() {
	 	  $(this).hide();
          if ($(this).attr("ztype").indexOf(v) > -1 )  $(this).show();
     });
	 if(!v) $(".d_a").show(); 
	 
});

 $('.actions').on('click', function(option) { 
       if($('.z_panel').is(":hidden")){
	   		$('.actions span span').html("一");
			$('.z_panel').show();
	   }else {
	   		$('.actions span span').html("十");
			$('.z_panel').hide();
	   }
 });
</script>

    </div>
</body>
</html>