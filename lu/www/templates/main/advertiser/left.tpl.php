<div class="subnav">
    <div class="subnav-title"> <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>我的概况</span></a> </div>
    <ul class="subnav-menu">
      <li <?php if(RUN_CONTROLLER=="advertiser/index") echo "class='current'"?>> <a href="<?php echo url("advertiser/index.get_list")?>">我的概况</a> </li>
      <li <?php if(RUN_CONTROLLER=="advertiser/account") echo "class='current'"?>> <a href="<?php echo url("advertiser/account.get_list")?>">帐户设置</a> </li>
	  <li <?php if(RUN_CONTROLLER=="advertiser/apply") echo "class='current'"?>> <a href="<?php echo url("advertiser/apply.get_list")?>">审批申请</a> </li>
	  <li <?php if(RUN_CONTROLLER=="advertiser/paylog") echo "class='current'"?>> <a href="<?php echo url("advertiser/paylog.get_list")?>">充值记录</a> </li>
      <li <?php if(RUN_CONTROLLER=="advertiser/msg") echo "class='current'"?>> <a href="<?php echo url("advertiser/msg.get_list")?>">消息中心</a> </li> 
    </ul>
  </div>
  <div class="subnav">
    <div class="subnav-title"> <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>帮助</span></a> </div>
    <ul class="subnav-menu">
      <li> <a href="<?php echo url("index.article?id=81")?>" target="_blank">怎么发布广告？</a> </li>
      <li> <a href="<?php echo url("index.article?id=82")?>" target="_blank">什么是广告计划？</a> </li>
      <li> <a href="<?php echo url("index.article?id=83")?>" target="_blank">怎么在线充值？</a> </li>
     
    </ul>
  </div>