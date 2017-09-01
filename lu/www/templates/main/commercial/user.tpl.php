<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>

<div id="left">
  <?php TPL::display('left');?>
</div>
<div id="main" style="padding-top:10px">
  <div class="mt30" style=" height:50px; ">
    <button class="btn btn-primary <?php if($status=='0') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/users.get_list?status=0")?>'">待审人员</button>
    <button class="btn btn-primary <?php if($status=='2') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/users.get_list?status=2")?>'">已审人员</button>
    <button class="btn btn-primary <?php if($status=='4') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/users.get_list?status=4")?>'">已锁定人员</button>
    <form action="<?php echo url("commercial/users.get_list")?>" method="post" class="form-horizontal" style="padding-left:0px">
      <div  class="controls" style="margin-left:0px;">
        <input name="searchval" type="text" class="input-27" style="width:120px" value="<?php echo $searchval?>"/>
        <select name="searchtype" style="padding:5px; width:100px">
          <option value="username" <?php if ($searchtype == 'username') echo "selected";?>>厂商名称</option>
          <option value="uid" <?php if ($searchtype == 'uid') echo "selected";?>>厂商ID</option>
        </select>
        <select name="sortingm"   id="sortingm" style="padding:5px; width:100px">
          <option value="DESC" <?php if($sortingm=='DESC')echo "selected"?>>降序</option>
          <option value="ASC" <?php if($sortingm=='ASC')echo "selected"?>>升序</option>
        </select>
        <select name="sortingtype"  id="sortingtype" style="padding:5px; width:100px">
          <option value="money" <?php if($sortingtype=='money')echo "selected"?>>总余额</option>
          
        </select>
        <button class="btn btn-primary" style="margin-right:10px">搜索</button>
      </div>
    </form>
  </div>
  <div class="box" style="margin-top:30px">
    <div class="box-title">
      <h3><i class="icon-table"></i>会员管理</h3>
    </div>
    <div class="box-content">
      <table class="table">
        <thead>
          <tr>
            <th>厂商ID</th>
            <th>厂商名称</th>
            <th>余额</th>
            <th>计划（条）</th>
            <th>广告（条）</th>
            <th>联系人</th>
            <th>QQ</th>
            <th width="100">状态</th>
            <th width="120">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach((array)$get_user_list as $u) { 
		  		 
		  ?>
          <tr >
            <td><?php echo $u['uid']?></td>
            <td><a href="<?php echo url('commercial/users.glogin?uid='.$u['uid'])?>" title="登入到会员后台" target="_blank"><?php echo $u['username']?></a></td>
            <td><?php echo  $u["money"] >0 ? round($u["money"],2):0;?></td>
            <td><?php echo  $u["daymoney"] >0 ? round($u["daymoney"],2):0?></td>
            <td><?php echo  $u["weekmoney"] >0 ? round($u["weekmoney"],2):0?></td>
            <td><?php echo  $u["monthmoney"] >0 ? round($u["monthmoney"],2):0?></td>
            <td><?php echo  $u["xmoney"] >0 ? round($u["xmoney"],2):0?></td>
            <td><?php 
					 
			  	switch($u['status']){
						case 0:
							echo '<span class="notification error_bg">待审</span>';
							break;
						case 1:
							echo '<span class="notification error_bg">邮件激活</span>';
							break;
						case 2;
							echo '<span class="notification info_bg">活动</span>';
							break;
						case 3:
							echo '<span class="notification error_bg">拒绝</span>';
							break;
						case 4:
							echo '<span class="notification error_bg">锁定</span>';
							
					} 
				?>            </td>
            <td><span class="hidden-480"><a href="<?php echo url('commercial/users.unlock?uid='.$u['uid'])?>" onclick="return confirm('确认审核通过吗')">审核</a> <a href="<?php echo url('commercial/users.lock?uid='.$u['uid'])?>" onclick="return confirm('确认锁定吗')">锁定</a></span></td>
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
