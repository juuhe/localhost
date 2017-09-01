<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>

<div id="left">
  <?php TPL::display('left');?>
</div>
<div id="main" style="padding-top:10px">
  <div class="mt30" style=" height:50px; ">
    <button class="btn btn-primary <?php if($status=='0') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/ads.get_list?status=0")?>'">待审广告</button>
    <button class="btn btn-primary <?php if($status=='3') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/ads.get_list?status=3")?>'">已审广告</button>
    <button class="btn btn-primary <?php if($status=='1') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/ads.get_list?status=1")?>'">已锁广告</button>
      <button class="btn btn-primary <?php if($status=='2') echo 'btn-red';?>" style="margin-right:10px" onclick="javascript:window.location.href='<?php echo url("commercial/ads.get_list?status=2")?>'">修改待审</button>
  
    <form action="<?php echo url("commercial/plan.get_list")?>" method="post" class="form-horizontal" style="padding-left:0px">
      <div  class="controls" style="margin-left:0px;">
        <input name="searchval" type="text" class="input-27" style="width:120px" value="<?php echo $searchval?>"/>
        <select name="searchtype" style="padding:5px; width:100px">
         <option value="adsid" <?php if ($searchtype == 'adsid') echo "selected";?>>广告ID</option>
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
      <h3><i class="icon-table"></i>广告管理</h3>
    </div>
    <div class="box-content">
      <div class="box" >
        <div class="box-content">
          <table class="table plan_logo">
            <thead>
              <tr>
                <th width="60">ID</th>
                <th width="280">浏览</th>
                <th>计划名称</th>
                <th>计费形式</th>
                <th>广告类型</th>
                <th>状态</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach((array)$get_ads_list as $a) { 
		  		 
		  ?>
              <tr class="d_a" >
                <td align="left" style="padding:20px; padding-left:10px"><?php echo $a['adsid']?></td>
                <td align="left"><?php                  
				  
				  if($a['plantype']=='cpm'){
					 echo "<a href='javascript:void(0)' onclick='tourl(\"".$a['url']."\")'>".$a['url']."</a>";
				  }else {
					  echo api ( 'ad.view', $a['adsid'],$a );
				  }
				  
				 
				  ?></td>
                <td><?php echo $a['planname']?></td>
                <td><?php echo strtoupper($a['plantype'])?></td>
                <td><?php echo $GLOBALS ['ADTYPE_SPECS'][$a['adtplid']]['name']?></td>
                <td class="status"><?php 
					if($a['planstatus'] != '1') $p['status'] =6;
			  		switch($a['status']){
						case 0:
							echo '<span class="notification error_bg">新增待审</span>';
							break;
						case 1:
							echo '<span class="notification error_bg">已被锁定</span>';
							break;
						case 2;
							echo '<span class="notification error_bg">修改待审</span>';
							break;
						case 3:
							echo '<span class="notification info_bg">活动</span>';
							break;
						case 4:
							echo '<span class="notification error_bg">已被锁定</span>';
						case 5:
							echo '<span class="notification error_bg">广告商未激活</span>';
						case 6:
							echo '<span class="notification error_bg">计划未激活</span>';
							
					} 
				?></td>
                <td><span class="hidden-480"><a href="<?php echo url('commercial/ads.unlock?adsid='.$a['adsid'])?>" onclick="return confirm('确认审核通过吗')">审核</a> <a href="<?php echo url('commercial/ads.lock?adsid='.$a['adsid'])?>" onclick="return confirm('确认锁定吗')">锁定</a> <a href="<?php echo url('commercial/ads.edit?adsid='.$a['adsid'])?>"  )">编辑</a></span></td>
              </tr>
              <?php }?>
            </tbody>
          </table>
        </div>
      </div>
      <div id="apply_html" style="display:none">
        <table   border="0" cellpadding="0" cellspacing="0" style="width:450px">
          <tr>
            <td height="40" colspan="3">选择需要申请广告的网站</td>
          </tr>
          <tr>
            <td width="100"><input name="applysiteidtype" type="radio" value="1" checked="checked" />
              <input name="applyplanid" type="hidden" value="" />
              全部网站</td>
            <td height="40"><input type="radio" name="applysiteidtype" value="2" />
              选择网站</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><div style="width:258px; overflow: auto;border: 1px solid #b8b8b8;float:left;display:none" class="applysiteid_d">
              <table class="table" style="margin-bottom:0px;">
                <thead>
                  <tr>
                    <th style="width:12%;"></th>
                    <th >名称</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach((array)$site as $s) { ?>
                  <tr>
                    <td ><input name="siteid[]" type="checkbox" class="apply_siteid" value="<?php echo $s['siteid']?>" url="<?php echo $s['siteurl']?>" /></td>
                    <td ><?php echo $s['siteurl']?></td>
                  </tr>
                  <?php }?>
                  <tr>
                    <th colspan="2" ><input type="checkbox" id="siteid_all" />
                      全选</th>
                  </tr>
                </tbody>
              </table>
            </div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td height="40"></td>
            <td><button type="button" class="btn btn-primary post_apply"> 提交申请 </button></td>
          </tr>
        </table>
      </div>
      
      <div class="row">
        <?php  echo $page->echoPage ();?>
      </div>
    </div>
  </div>
</div>
