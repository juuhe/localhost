<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>

<div id="left">
  <?php TPL::display('left');?>
</div>
<div id="main" style="padding-top:10px">
  <div class="mt30" style=" height:50px; ">
    <button class="btn btn-primary <?php if($status=='0') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("service/site.get_list?status=0")?>'">待审网站</button>
    <button class="btn btn-primary <?php if($status=='3') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("service/site.get_list?status=3")?>'">已审网站</button>
    <button class="btn btn-primary <?php if($status=='2') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("service/site.get_list?status=2")?>'">已锁定网站</button>
    <form action="<?php echo url("service/site.get_list")?>" method="post" class="form-horizontal" style="padding-left:0px">
      <div  class="controls" style="margin-left:0px;">
        <input name="searchval" type="text" class="input-27" style="width:120px" value="<?php echo $searchval?>"/>
        <select name="searchtype" style="padding:5px; width:100px">
		 <option value="sitename">网站名称</option>
          <option value="siteid">网站ID</option>
		  
          <option value="username">会员名称</option>
          <option value="uid">会员ID</option>
        </select>
       
        <button class="btn btn-primary" style="margin-right:10px">搜索</button>
      </div>
    </form>
  </div>
  <div class="box" style="margin-top:30px">
    <div class="box-title">
      <h3><i class="icon-table"></i>网站管理</h3>
    </div>
    <div class="box-content">
      <table class="table">
        <thead>
          <tr>
            <th>网站ID</th>
            <th>会员名称</th>
            <th>网站名称</th>
            <th>分类</th>
            <th>网站备案信息</th>
            <th>日访问量</th>
            <th>网站描述</th>
            <th width="100">状态</th>
            <th width="120">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach((array)$get_site_list as $s) { 
		  		$c =  dr ( 'main/class.get_one',$s["sitetype"] );
		  ?>
          <tr >
            <td><?php echo $s['siteid']?></td>
            <td><a href="<?php echo url('service/users.glogin?uid='.$s['uid'])?>" title="登入到会员后台" target="_blank"><?php echo $s['username']?></a></td>
            <td><?php echo $s['sitename']?></td>
            <td><?php echo $c['classname']?></td>
            <td><?php echo $s['beian']?></td>
            <td><?php echo $s['dayip']?></td>
            <td><?php echo htmlspecialchars($s['siteinfo'])?></td>
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
				?>
            </td>
            <td><span class="hidden-480"><a href="<?php echo url('service/site.unlock?siteid='.$s['siteid'])?>" onclick="return confirm('确认审核通过吗')">审核</a> <a href="<?php echo url('service/site.lock?siteid='.$s['siteid'])?>" onclick="return confirm('确认锁定吗')">锁定</a></span></td>
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
