<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>

<div id="left">
  
  <div class="subnav">
    <div class="subnav-title"> <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>计费模式</span></a> </div>
    <ul class="subnav-menu">
       <li <?php if(!$plantype) echo "class='current'"?>> <a href="<?php echo url("advertiser/plan.get_list")?>"  >所有计划</a> </li>
	    <?php foreach((array)$all_plantype as $pt) { ?>
      <li <?php if($plantype==$pt['plantype']) echo "class='current'"?>> <a href="<?php echo url("advertiser/plan.get_list?plantype=".$pt['plantype'])?>"  ><?php echo strtoupper($pt['plantype'])?>类</a> </li>
	   <?php }?>
    </ul>
  </div>
</div>
<div id="main" style="padding-top:10px">

 <div class="mt30" style=" height:10px; ">
    <div class="new_bg" style="float:left"><a href="<?php echo url("advertiser/plan.create?type=".$type)?>">新建计划</a></div>
   
  </div>
  
  <div class="box" >
    <div class="box-title">
      <h3><i class="icon-table"></i>计划列表</h3>
       <div class="actions" style="color: #08c;"> 
          <select size="1" name="choose_type" id="choose_type" style="margin-left:20px"  onchange="location.href = this.options[selectedIndex].value">
            <option value="<?php echo url('advertiser/plan.get_list&plantype='.$plantype)?>" >所有分类</option>
            <?php foreach ( ( array ) $plan_class as  $c ) {?>
            <option value="<?php echo url('advertiser/plan.get_list?plantype='.$plantype.'&classid='.$c['classid'])?>"  <?php if ($classid == $c['classid']) echo "selected";?>><?php echo $c['classname']?></option>
            <?php }?>
          </select>
         </div>
    </div>
     
    <div class="box-content">
      <table class="table plan_logo">
        <thead>
          <tr>
            <th>活动名称</th>
            <th width="80">类型</th>
            <th width="90">佣金</th>
            <th width="90">限额</th>
            <th width="90">分类</th>
            <th width="100">状态</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach((array)$plantype_list as $p) { 
		  			 $c =  dr ( 'main/class.get_one',(int)$p["classid"] );
		  ?>
          <tr class="d_a" >
            <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="logo">
                <tr>
                  <td width="120" rowspan="2"><img src="<?php echo $p["logo"] ? $p["logo"] : SRC_TPL_DIR.'/images/no.gif'?>"   border="0" /></td>
                  <td><a href="<?php echo url("advertiser/plan.edit?planid=".$p['planid'])?>"><?php echo $p['planname']?></a></td>
                </tr>
                <tr>
                  <td>结算：
                    <?php 
							if ($p['clearing'] == 'day') echo '日结';
							if ($p['clearing'] == 'week') echo '周结';
							if ($p['clearing'] == 'month') echo '月结';
							?></td>
                </tr>
              </table></td>
            <td><?php echo strtoupper($p['plantype'])?></td>
            <td><?php 
					if ($p['plantype'] != 'cps') {
						echo abs($p['priceadv']).' 元';
					} else {
						if($p['gradeprice'] ==1 ) {
							echo '按类目分成';
						}else{
							echo abs($p['priceadv']).' %';
						}
					}
			?></td>
            <td><?php echo $p['budget']?></td>
            <td class="status"><?php echo $c['classname']?></td>
            <td class="status"><?php 
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
						case 6:
							echo '<span class="notification error_bg">暂停中(修改)</span>';
							
					} 
				?></td>
            <td> 
              <a href="<?php echo url("advertiser/plan.edit?planid=".$p['planid'])?>">编辑</a>
             <a href="<?php echo url("advertiser/ad.get_list?planid=".$p['planid'])?>">查看广告</a> 
              <a href="<?php echo url("advertiser/ad.create?planid=".$p['planid'])?>">新建广告</a></td>
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
                  <th colspan="2" ><input type="checkbox" id="siteid_all" >
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
  
  <div><?php  echo $page->echoPage ();?></div>
  
</div>
 