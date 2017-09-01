<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>

<div id="left">
  <?php TPL::display('left');?>
</div>
<div id="main" style="padding-top:10px">
  <div class="mt30" style=" height:50px; ">
    <button class="btn btn-primary <?php if($status=='0') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/plan.get_list?status=0")?>'">待审计划</button>
    <button class="btn btn-primary <?php if($status=='1') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/plan.get_list?status=1")?>'">已审计划</button>
    <button class="btn btn-primary <?php if($status=='2') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/plan.get_list?status=2")?>'">已锁定计划</button>
      <button class="btn btn-primary <?php if($status=='3') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/plan.get_list?status=3")?>'">限额停止</button>
        <button class="btn btn-primary <?php if($status=='4') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/plan.get_list?status=4")?>'">过期或是余额不足停止</button>
    <form action="<?php echo url("commercial/plan.get_list")?>" method="post" class="form-horizontal" style="padding-left:0px">
      <div  class="controls" style="margin-left:0px;">
        <input name="searchval" type="text" class="input-27" style="width:120px" value="<?php echo $searchval?>"/>
        <select name="searchtype" style="padding:5px; width:100px">
		 <option value="planname" <?php if ($searchtype == 'planname') echo "selected";?>>计划名称</option>
          <option value="planid"  <?php if ($searchtype == 'planid') echo "selected";?>>计划ID</option>
		  
          <option value="username"  <?php if ($searchtype == 'username') echo "selected";?>>广告商名称</option>
          <option value="uid"  <?php if ($searchtype == 'uid') echo "selected";?>>广告商ID</option>
        </select>
       
        <button class="btn btn-primary" style="margin-right:10px">搜索</button>
      </div>
    </form>
  </div>
  <div class="box" style="margin-top:30px">
    <div class="box-title">
      <h3><i class="icon-table"></i>计划管理</h3>
    </div>
    <div class="box-content">
      <table class="table">
        <thead>
          <tr>
            <th>厂商</th>
            <th>计划ID</th>
            <th>计划名称</th>
            <th>类型</th>
            <th>单价</th>
            <th>限额	</th>
            <th>结算</th>
            <th width="100">状态</th>
            <th width="120">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach((array)$get_plna_list as $p) { 
		  		 
		  ?>
          <tr >
            <td><?php echo $p['username']?></td>
            <td><?php echo $p['planid']?></td>
            <td><?php echo $p["planname"]?></td>
            <td><?php echo ucfirst($p['plantype'])?></td>
            <td><?php 
							if($p['gradeprice']) echo '<font color="#FF0000">分级</font>';
							else echo abs($p['price']); if($p['plantype'] == 'cps') echo '%';
							echo '<br><font color="gray">'.abs($p['priceadv']);
							if($p['plantype'] == 'cps') echo '% ';
							echo '</font>';
						  ?></td>
            <td>￥<?php echo abs($p['budget'])?></td>
            <td><?php 
							if ($p['clearing'] == 'day') echo '日结';
							if ($p['clearing'] == 'week') echo '周结';
							if ($p['clearing'] == 'month') echo '月结';
							?></td>
            <td><span class="status">
              <?php 
					if($p['userstatus'] != '2') $p['status'] =5;
			  		switch($p['status']){
						case 0:
							echo '<span class="notification error_bg">待审</span>';
							break;
						case 1:
							echo '<span class="notification info_bg">活动</span>';
							break;
						case 2;
							echo '<span class="notification error_bg">锁定</span>';
							break;
						case 3:
							echo '<span class="notification error_bg">暂停中(限额)</span>';
							break;
						case 4:
							echo '<span class="notification error_bg">停止(过期或是余额不足)</span>';
						case 5:
							echo '<span class="notification error_bg">广告商未激活</span>';
							
					} 
				?>
            </span></td>
            <td><span class="hidden-480"><a href="<?php echo url('commercial/plan.unlock?planid='.$p['planid'])?>" onclick="return confirm('确认审核通过吗')">审核</a> <a href="<?php echo url('commercial/plan.lock?planid='.$p['planid'])?>" onclick="return confirm('确认锁定吗')">锁定</a> <a href="<?php echo url('commercial/plan.edit?planid='.$p['planid'])?>" target="_blank" >编辑</a></span></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
      <div class="row">
        <?php  echo $page->echoPage ();?>
      </div>
    </div>
  </div>
</div>
