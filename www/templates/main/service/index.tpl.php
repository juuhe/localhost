<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/highcharts/js/highcharts.js"></script>

<div id="left">
  <?php TPL::display('left');?>
</div>
<div id="main" >
  <div>会员发展链接：<?php echo $GLOBALS ['C_ZYIIS']['authorized_url'].WEB_URL.'track/c/?sid='.$GLOBALS ['userinfo']['uid'];?></div>
  <table   border="0" cellpadding="0" cellspacing="0" class="mt30">
    <tr>
      <td width="21%"><span class="money"><?php echo  dr ( 'service/users.get_day_register_num') ?></span>个</td>
      <td width="21%"><span class="money"><?php echo  dr ( 'service/users.get_status0_num') ?></span>个</td>
      <td width="21%"><span class="money"><?php echo  dr ( 'service/site.get_status0_num') ?></span>个</td>
    </tr>
    <tr>
      <td>当天新增下属会员</td>
      <td>待审会员</td>
      <td>待审网站</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <div class="box">
    <div class="box-title">
      <h3><i class="icon-table"></i>待审会员TOP50</h3>
       
    </div>
    <div class="box-content">
      <table class="table table-hover table-nomargin">
        <thead>
          <tr>
            <th>会员名</th>
            <th width="160">注册IP</th>
            <th width="160" class="hidden-350">注册时间 </th>
            <th width="160" class="hidden-480">操作</th>
          </tr>
        </thead>
        <tbody>
		
		 <?php foreach( (array)$get_user_status0_list as $gu) {?>
		 
          <tr>
            <td><?php echo $gu['username']?></td>
            <td><?php echo $gu['regip']?></td>
            <td class="hidden-350"><?php echo $gu['regtime']?></td>
            <td class="hidden-480"><a href="<?php echo url('service/index.user_unlock?uid='.$gu['uid'])?>" onclick="return confirm('确认审核通过吗')">审核</a>   <a href="<?php echo url('service/index.user_lock?uid='.$gu['uid'])?>" onclick="return confirm('确认锁定吗')">锁定</a></td> 
          </tr>
           <?php }?>
        </tbody>
      </table>
    </div>
    <div class="box">
    <div class="box-title">
      <h3><i class="icon-table"></i>待审网站TOP50</h3>
       
    </div>
    <div class="box-content">
      <table class="table table-hover table-nomargin">
        <thead>
          <tr>
            <th width="120">会员名</th>
            <th>网站名称</th>
            <th width="120" class="hidden-480">分类</th>
            <th width="160" class="hidden-480">网站备案信息</th>
            <th width="120" class="hidden-480">操作</th>
          </tr>
        </thead>
        <tbody>
		
		 <?php foreach( (array)$get_site_status0_list as $gs) {  
		 		$c =  dr ( 'main/class.get_one',$gs["sitetype"] );
		  ?>
		 
          <tr>
            <td><?php echo $gs['username']?></td>
            <td><?php echo $gs['sitename']?><br><a href="http://<?php echo $gs['siteurl']?>" target="_blank">http://<?php echo $gs['siteurl']?></a></td>
            <td class="hidden-480"><?php echo $c['classname']?></td>
            <td class="hidden-480"><?php echo $gs['beian']?></td>
            <td class="hidden-480"><a href="<?php echo url('service/index.site_unlock?siteid='.$gs['siteid'])?>" onclick="return confirm('确认审核通过吗')">审核</a>   <a href="<?php echo url('service/index.site_lock?siteid='.$gs['siteid'])?>" onclick="return confirm('确认锁定吗')">锁定</a></td> 
          </tr>
           <?php }?>
        </tbody>
      </table>
    </div>
  </div>
</div>