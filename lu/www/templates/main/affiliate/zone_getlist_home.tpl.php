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


</head>
<body>
	<div style="width:1600px;height:750px;margin-left:45px;margin-top:30px">
    	<h3>广告位管理</h3><br><br>
        <div style="margin-left:1400px">
            <i class="Hui-iconfont" style="font-size:20px">&#xe600;</i>
            <input  id="add" class="btn btn-primary size-M radius" type="button" value="添加广告位">
        </div>
        <br>
    	<hr width=100% size=3 color=#C0C0C0 style="filter:progid:DXImageTransform.Microsoft.Shadow(color#5151A2,direction:145,strength:15)"> 
    	<br>
        
        <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                        <tr class="text-c">
                            <th width="180">广告位名称</th>
                            <th width="100">广告位ID</th>
                            <th width="80">尺寸</th>
                            <th width="80">广告</th>
                            <th width="100">计费方式</th>
                            <th width="100">上次修改日期</th>
                            <th width="100">状态</th>
                        </tr>
                    </thead>
                    <tbody>
          <?php foreach((array)$zone as $z) { 
		  			$tpl = dr ( 'affiliate/adtpl.get_one', $z['adtplid'] );
		  ?>
          <tr class="d_a gzid_<?php echo $z['zoneid']?>" zname="<?php echo $z['zonename']?>" zid="<?php echo $z['zoneid']?>" zsize="<?php echo $z['width'].'x'.$z['height']?>" ztype="<?php echo $z['adtplid']?>" zl="<?php echo $tpl['tpltype'] == 'url_jump' ? '1' : 0?>">
            <td><?php echo $z['zonename']?><br>
            </td>
            <td><?php echo $z['zoneid']?></td>
            <td><?php echo $z['width'].'x'.$z['height'];?></td>
            <td>
			<?php  
		    echo  $tpl['tplname'];
			?></td>
            <td><?php echo strtoupper($z['plantype'])?></td>
            <td><?php echo $z['uptime']?></td>
            <td><a id="edit" href="<?php echo url("affiliate/zone.edit?zoneid=".$z['zoneid'])?>">修改</a> <a href="#" class="delzone" >删除</a></td>
          </tr>
          <?php }?>
                </table>
            </div> 	

    </div>
    <script type="text/javascript" src="<?php echo SRC_TPL_DIR?>/lib/jquery/1.9.1/jquery.min.js"></script> 
    <script type="text/javascript">
        $(document).ready(function() {
          //  alert(2222)
            $(".delzone").click(function() {
            _zoneid = $(this).parent().parent().attr("zid");
            alert(_zoneid)
		window.location.reload();
		$.ajax(
			{
				dataType: 'html',
				url: '<?php echo url("affiliate/zone.del")?>',
				type: 'post',
				data: 'zoneid=' + _zoneid,
				success: function() 
				{
					 _parent.css("backgroundColor", "#faa").hide('normal');
				}
			});
	});

            $("#add").click(function() {
              window.location.href="/index.php?e=aff/zone.create&type=";
            });
       
 }); 
 
    </script>
</body>
</html>