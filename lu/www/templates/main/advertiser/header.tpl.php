<!doctype html>
<html>
<head>
<link rel="stylesheet" href="<?php echo SRC_TPL_DIR?>/style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>广告商后台</title>
</head>

<body>
<div id="navigation">
  <div class="container-fluid"> <a href="#" id="brand">广告商后台</a>
    <ul class='main-nav'>
      <li <?php if(RUN_CONTROLLER=='main/main' OR !in_array(RUN_CONTROLLER,array('advertiser/zone','advertiser/stats','advertiser/report','advertiser/plan','advertiser/code','advertiser/ad','advertiser/orders','advertiser/cpa_report'))) echo "class='active'"?>> <a href="<?php echo url("advertiser/index.get_list")?>"> <i class="icon-home"></i> <span>我的首页</span> </a> </li>
      <li <?php if(RUN_CONTROLLER === 'advertiser/plan') {?>class="active"<?php }?>> <a href="<?php echo url("advertiser/plan.get_list")?>" data-toggle="dropdown" class='dropdown-toggle'> <i class="icon-edit"></i> <span>计划管理</span>  </a> </li>
      <li <?php if(RUN_CONTROLLER === 'advertiser/ad') {?>class="active"<?php }?>> <a href="<?php echo url("advertiser/ad.get_list")?>" data-toggle="dropdown" class='dropdown-toggle'> <i class="icon-edit"></i> <span>广告管理</span>  </a> </li>
      <li <?php if(RUN_CONTROLLER === 'advertiser/report') {?>class="active"<?php }?>> <a href="<?php echo url("advertiser/report.get_list?type=".$_COOKIE['report_de_type']."&timerange=".DAYS."_".DAYS)?>" data-toggle="dropdown" class='dropdown-toggle'> <i class="icon-table"></i> <span>效果报告</span>   </a> </li>
      <?php if(RUN_CONTROLLER === 'advertiser/code') {?>
      <li class="active"> <a href="<?php echo url("advertiser/code.get_custom")?>" data-toggle="dropdown" class='dropdown-toggle'> <i class="icon-edit"></i> <span>链接转换工具</span>  </a> </li>
      <?php }?>
      
       <?php if(RUN_CONTROLLER === 'advertiser/cpa_report') {?>
      <li class="active"> <a href="<?php echo url("advertiser/cpa_report.get_list")?>" data-toggle="dropdown" class='dropdown-toggle'> <i class="icon-edit"></i> <span>CPA明细报表</span>  </a> </li>
      <?php }?>
      <?php if(RUN_CONTROLLER === 'advertiser/orders') {?>
      <li class="active"> <a href="<?php echo url("advertiser/cpa_report.get_list")?>" data-toggle="dropdown" class='dropdown-toggle'> <i class="icon-edit"></i> <span>CPS明细报表</span>  </a> </li>
      <?php }?>
      
    </ul>
     <div class="user">
      <ul class="icon-nav">
          <?php if($GLOBALS ['read_num']){?>
        <li class="dropdown" title="消息"> <a href="<?php echo url("advertiser/msg.get_list")?>" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-msg"></i> <span class="label label-lightred"><?php echo $GLOBALS ['read_num']?></span> </a> </li>
         <?php }?>
         

  <?php if($GLOBALS ['service_qq']){?>
         <li class="dropdown" title="客服"> <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php  echo $GLOBALS ['service_qq'];?>&site=qq&menu=yes" target="_blank"  class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-kf"></i> </a> </li>
         <?php }?>

         <li class="dropdown" title="退出"> <a href="<?php echo url("main/main.logout?id=".$GLOBALS ['userinfo']['uid'])?>" class="dropdown-toggle" data-toggle="dropdown"> <i class="icon-exit"></i> </a> </li>
         
           <li class="dropdown"> <a href="<?php echo url("advertiser/account.get_list")?>" class="dropdown-toggle" data-toggle="dropdown" style="padding-top:9px;"> <?php echo $GLOBALS ['userinfo'] ["username"]?> </a> </li>
         
        </li>
      </ul>
    </div>
  </div>
</div>
<div class="container-fluid" id="content">
