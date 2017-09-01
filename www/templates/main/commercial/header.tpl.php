<!doctype html>
<html>
<head>
<link rel="stylesheet" href="<?php echo SRC_TPL_DIR?>/style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>商务后台</title>
</head>
<body>
<div id="navigation">
  <div class="container-fluid"> <a href="#" id="brand">商务后台</a>
    <ul class='main-nav'>
      <li <?php if(RUN_CONTROLLER=='main/main' OR !in_array(RUN_CONTROLLER,array('commercial/users','commercial/plan','commercial/account','commercial/ads','commercial/account'))) echo "class='active'"?>> <a href="<?php echo url("commercial/index.get_list")?>"> <i class="icon-home"></i> <span>我的首页</span> </a> </li>
      <li <?php if(RUN_CONTROLLER === 'commercial/users') {?>class="active"<?php }?>> <a href="<?php echo url("commercial/users.get_list")?>" data-toggle="dropdown" class='dropdown-toggle'> <i class="icon-edit"></i> <span>厂商管理</span> <span class="caret"></span> </a> </li>
      <li <?php if(RUN_CONTROLLER === 'commercial/plan') {?>class="active"<?php }?>> <a href="<?php echo url("commercial/plan.get_list")?>" data-toggle="dropdown" class='dropdown-toggle'> <i class="icon-edit"></i> <span>计划管理</span> <span class="caret"></span> </a> </li>
      <li <?php if(RUN_CONTROLLER === 'commercial/ads') {?>class="active"<?php }?>> <a href="<?php echo url("commercial/ads.get_list")?>" data-toggle="dropdown" class='dropdown-toggle'> <i class="icon-edit"></i> <span>广告管理</span> <span class="caret"></span> </a> </li>
      <li <?php if(RUN_CONTROLLER === 'commercial/account') {?>class="active"<?php }?>> <a href="<?php echo url("commercial/account.get_list")?>" data-toggle="dropdown" class='dropdown-toggle'> <i class="icon-edit"></i> <span>帐户设置</span> <span class="caret"></span> </a> </li>
    </ul>
    <div class="user">
      <ul class="icon-nav">
        <li class="dropdown" title="退出"> <a href="<?php echo url("main/main.logout?id=".$GLOBALS ['userinfo']['uid'])?>" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-exit"></i> </a> </li>
        <li class="dropdown"> <a href="<?php echo url("commercial/account.get_list")?>" class="dropdown-toggle" data-toggle="dropdown" style="padding-top:9px;"> <?php echo $GLOBALS ['userinfo'] ["username"]?> </a> </li>
      </ul>
    </div>
  </div>
</div>
<div class="container-fluid" id="content">
