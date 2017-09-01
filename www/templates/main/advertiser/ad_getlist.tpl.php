<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>

<div id="left">
  
  <div class="subnav">
    <div class="subnav-title"> <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>所有推广计划</span></a> </div>
    <ul class="subnav-menu">
		  <li <?php if(!$planid) echo "class='current'"?>> <a href="<?php echo url("advertiser/ad.get_list")?>"  >所有计划</a> </li>
	    <?php foreach((array)$plan_all as $p) { ?>
      <li <?php if($planid==$p['planid']) echo "class='current'"?>> <a href="<?php echo url("advertiser/ad.get_list?planid=".$p['planid'])?>"  ><?php echo $p['planname']?></a> </li>
	   <?php }?>
    </ul>
  </div>
</div>
<div id="main" style="padding-top:10px">

 <div class="mt30" style=" height:10px; ">
    <div class="new_bg" style="float:left"><a href="<?php echo url("advertiser/ad.create?planid=".$planid)?>">新建广告</a></div>
   
  </div>
  
  <div class="box" >
    <div class="box-title">
      <h3><i class="icon-table"></i>广告列表</h3>
        <div class="actions" style="color: #08c;"> 
          <select size="1" name="choose_type" id="choose_type" style="margin-left:20px"  onchange="location.href = this.options[selectedIndex].value">
            <option value="<?php echo url('advertiser/ad.get_list&planid='.$planid)?>" >所有类型</option>
            <?php foreach ( ( array ) $GLOBALS ['ADTYPE_SPECS'] as $key => $at ) {?>
            <option value="<?php echo url('advertiser/ad.get_list?planid='.$planid.'&adtplid='.$key)?>"  <?php if ($adtplid == $key) echo "selected";?>><?php echo $at['name']?></option>
            <?php }?>
          </select>
         </div>
    </div>
     
    <div class="box-content">
      <table class="table plan_logo">
        <thead>
          <tr>
            <th width="50">ID</th>
            <th width="260">浏览</th>
            <th>&nbsp;</th>
            <th>计划名称</th>
            <th>计费形式</th>
            <th>广告类型</th>
            <th>状态</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach((array)$ads as $a) { 
		  		 $tpl = dr ( 'admin/adtpl.get_one', $a['adtplid']);
		  ?>
          <tr class="d_a" >
            <td align="left"  style="padding:20px; padding-left:10px"><?php echo $a['adsid']?></td>
            <td align="left" height="30"> 
                  <?php                  
				  
				  if($a['plantype']=='cpm'){
					 echo "<a href='javascript:void(0)' onclick='tourl(\"".$a['url']."\")'>".$a['url']."</a>";
				  }else {
					$v = api ( 'ad.view', $a['adsid'],$a );
					if(!$v) {echo "无预览";}else { echo $v;}
				  }
				  
				 
				  ?>
                   </td>
            <td><?php echo  $a['width'].'x'.$a['height'];?></td>
            <td><?php echo $a['planname']?></td>
            <td><?php echo strtoupper($a['plantype'])?></td>
            <td><?php  echo $tpl['tplname']?></td>
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
            <td> 
              <a href="<?php echo url("advertiser/ad.edit?adsid=".$a['adsid'])?>">编辑</a>
             </td>
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
 