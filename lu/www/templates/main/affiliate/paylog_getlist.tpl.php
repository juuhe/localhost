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
    window.location.href="addweb.html";
 }
</script>

</head>
<body>
	<div style="width:1600px;height:750px;margin-left:45px;margin-top:30px">
    	<h3>结算记录</h3><br>
    	<hr width=100% size=3 color=#C0C0C0 style="filter:progid:DXImageTransform.Microsoft.Shadow(color#5151A2,direction:145,strength:15)"> 
    	<br>
        <div style="width:1600px;height:50px;">
          总收益: <span style="color:red"><?php echo sprintf("%.2f", $paylog_sunpay["sunpay"]);?>元</span>
        </div>
        <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                        <tr class="text-c">
                        <th width="140">结算<?php if($clearing=='week') { echo '周';} elseif($clearing=='month'){ echo '月'; } else{ echo '日';} ?>期</th>
                            <th width="100">支付时间</th>
                            <th width="80">应付金额(元)</th>
                            <th width="80">实付金额(元)</th>
                            <th width="100">备注</th>
                            <th width="100">结算状态</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach((array)$paylog as $p) {
		  		
		  ?>
          <tr sid="<?php echo $p['addtime']?>">
            <td><?php echo $p['addtime']."	周".$get_timerange['week_array'][date("w",strtotime($p['addtime']))];?></td>

            <?php
			  if ($p['status'] == '0')
				{
                    ?> <td width="100">未支付</td> <?php
                } else {
                    ?><td width="100"><?php echo $p['paytime'];?></td>
               <?php  } ?>
            <td>￥<?php echo abs($p['money'])?></td>
            <?php
			  if ($p['status'] == '0')
				{
                    ?> <td width="100">0.00</td> <?php
                } else{
                    ?><td width="100"><?php echo abs($p['money'])?></td>
               <?php  } ?>
            <td><?php echo $p['payinfo']?> </td>
            <td><?php
			  if ($p['status'] == '0')
				{
					$statusY = '<font color=red>未支付</font>';
				} 
				if ($p['status']=='1')
				{
					$statusY = '<font color=blue>已支付</font>';
				} 
				echo $statusY;
			  ?></td>
            
          </tr>
          <?php }?>
                    </tbody> 
                </table>
            </div>

    	

    </div>
</body>
</html>