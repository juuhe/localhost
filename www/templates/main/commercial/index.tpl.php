<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/highcharts/js/highcharts.js"></script>

<div id="left">
  <?php TPL::display('left');?>
</div>
<div id="main" >
  <div>厂商发展链接：<?php echo $GLOBALS ['C_ZYIIS']['authorized_url'].WEB_URL.'track/c/?cid='.$GLOBALS ['userinfo']['uid'];?></div>
  <table   border="0" cellpadding="0" cellspacing="0" class="mt30">
    <tr>
      <td width="21%"><span class="money"><?php echo  dr ( 'commercial/users.get_day_register_num') ?></span>个</td>
      <td width="21%"><span class="money"><?php echo  dr ( 'commercial/users.get_status0_num') ?></span>个</td>
      <td width="21%"><span class="money"><?php echo  dr ( 'commercial/plan.get_status0_num') ?></span>个</td>
      <td width="21%"><span class="money"><?php echo  dr ( 'commercial/ads.get_status0_num') ?></span>个</td>
    </tr>
    <tr>
      <td>当天新增下属厂商</td>
      <td>待审厂商</td>
      <td>待审计划</td>
      <td>待审广告</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <div class="box">
    <div class="box-title">
      <h3><i class="icon-table"></i>待审厂商TOP50</h3>
       
    </div>
    <div class="box-content">
      <table class="table table-hover table-nomargin">
        <thead>
          <tr>
            <th>UID</th>
            <th>厂商名</th>
            <th width="160">注册IP</th>
            <th width="160" class="hidden-350">注册时间 </th>
            <th width="160" class="hidden-480">操作</th>
          </tr>
        </thead>
        <tbody>
		
		 <?php foreach( (array)$get_user_status0_list as $gu) {?>
		 
          <tr>
            <td><?php echo $gu['uid']?></td>
            <td><?php echo $gu['username']?></td>
            <td><?php echo $gu['regip']?></td>
            <td class="hidden-350"><?php echo $gu['regtime']?></td>
            <td class="hidden-480"><a href="<?php echo url('commercial/index.user_unlock?uid='.$gu['uid'])?>" onclick="return confirm('确认审核通过吗')">审核</a>   <a href="<?php echo url('commercial/index.user_lock?uid='.$gu['uid'])?>" onclick="return confirm('确认锁定吗')">锁定</a></td> 
          </tr>
           <?php }?>
        </tbody>
      </table>
    </div>
    <div class="box">
    <div class="box-title">
      <h3><i class="icon-table"></i>待审计划TOP50</h3>
       
    </div>
    <div class="box-content">
      <table class="table table-hover table-nomargin">
        <thead>
          <tr>
            <th width="120">ID</th>
            <th width="120">厂商名</th>
            <th>计划名称</th>
            <th width="120" class="hidden-480">类型</th>
            <th width="160" class="hidden-480">单价</th>
            <th width="120" class="hidden-480">结算</th>
            <th width="120" class="hidden-480">操作</th>
          </tr>
        </thead>
        <tbody>
		
		 <?php foreach( (array)$get_plan_status0_list as $gs) {  
		 		 
		  ?>
		 
          <tr>
            <td><?php echo $gs['planid']?></td>
            <td><?php echo $gs['username']?></td>
            <td><?php echo $gs['planname']?></td>
            <td class="hidden-480"><?php echo ucfirst($gs['plantype'])?></td>
            <td class="hidden-480"><?php 
							if($gs['gradeprice']) echo '<font color="#FF0000">分级</font>';
							else echo abs($gs['price']); if($p['plantype'] == 'cps') echo '%';
							echo '<br><font color="gray">'.abs($gs['priceadv']);
							if($gs['plantype'] == 'cps') echo '% ';
							echo '</font>';
						  ?></td>
            <td class="hidden-480"><?php 
							if ($gs['clearing'] == 'day') echo '日结';
							if ($gs['clearing'] == 'week') echo '周结';
							if ($gs['clearing'] == 'month') echo '月结';
							?></td>
            <td class="hidden-480"><a href="<?php echo url('commercial/index.plan_unlock?planid='.$gs['planid'])?>" onclick="return confirm('确认审核通过吗')">审核</a>   <a href="<?php echo url('commercial/index.plan_lock?planid='.$gs['planid'])?>" onclick="return confirm('确认锁定吗')">锁定</a></td> 
          </tr>
           <?php }?>
        </tbody>
      </table>
    </div>
    
    
  </div>
  
  
    <div class="box">
    <div class="box-title">
      <h3><i class="icon-table"></i>待审广告TOP50</h3>
       
    </div>
    <div class="box-content">
      <table class="table table-hover table-nomargin">
        <thead>
          <tr>
            <th width="120">ID</th>
            <th width="120">厂商名</th>
            <th>属于计划</th>
            <th width="120" class="hidden-480">类型</th>
            <th width="160" class="hidden-480">广告类型</th>
            <th width="120" class="hidden-480">操作</th>
          </tr>
        </thead>
        <tbody>
		
		 <?php foreach( (array)$get_ads_status0_list as $gs) {  
		 		 
		  ?>
		 
          <tr>
            <td><?php echo $gs['adsid']?></td>
            <td><?php echo $gs['username']?></td>
            <td><?php echo $gs['planname']?></td>
            <td class="hidden-480"><?php echo ucfirst($gs['plantype'])?></td>
            <td class="hidden-480"><?php echo $GLOBALS ['ADTYPE_SPECS'][$gs['adtplid']]['name']?></td>
            <td class="hidden-480"><a href="<?php echo url('commercial/index.ads_unlock?adsid='.$gs['adsid'])?>" onclick="return confirm('确认审核通过吗')">审核</a>   <a href="<?php echo url('commercial/index.ads_lock?adsid='.$gs['adsid'])?>" onclick="return confirm('确认锁定吗')">锁定</a></td> 
          </tr>
           <?php }?>
        </tbody>
      </table>
    </div>
    
    
  </div>
  
</div>