<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>

<div id="left">
  <div class="subnav">
    <div class="subnav-title"> <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>广告创意</span></a> </div>
    <ul class="subnav-menu">
      <?php foreach((array)$adtpl as $ap) {
	  		$get_tpl = dr ( 'affiliate/adtpl.get_one_adtpl_adtype', $ap['adtplid']);
	  ?>
      <li <?php if($type==$ap['adtplid']) echo "class='current'"?>> <a href="<?php echo url("affiliate/plan.get_ad?planid=".$p['planid']."&type=".$ap['adtplid'])?>"><?php echo $get_tpl['tplname']?> <font color="#FF0000">(<?php echo $ap['num'];?>)</font></a> </li>
      <?php } ?>
      <?php if($p['linkon'] == 'y') {?>
      <li> <a href="<?php echo url("affiliate/code.get_custom?planid=".$p['planid'])?>">自定义链接
        <input name="" type="button"  value="自定义"/>
        </a> </li>
      <?php  }?>
    </ul>
  </div>
  <div class="subnav">
    <div class="subnav-title"> <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>活动简介</span></a> </div>
    <ul class="subnav-menu" style="margin-left: 20px; margin-top:30px">
      
      <li>名称：<a href="<?php echo url("affiliate/plan.info?planid=".$p['planid'])?>" style="display: inline; padding-left: 0px;line-height:0px"><?php echo $p["planname"]?></a></li>
      <li>类型：<?php echo strtoupper($p['plantype'])?></li>
      <li>佣金：
        <?php 
			$af = $p["plantype"] == 'cps' ? "%" :"元"; 
			$price = main_public::format_plan_print($p['planid']);
			if(is_array($price)){
				echo $price["min"].$af.'-'.$price["max"].$af;
			}else {
				echo $price.$af;
			}
			
			?>
      </li>
    </ul>
  </div>
</div>
<div id="main" style="padding-top:10px">
  <?php if(count($specs)>1 && $specs[0]['width'] > 0)  {?>
  <div class="box" >
    <div class="box-title">
      <h3><i class="icon-table"></i>广告尺寸</h3>
    </div>
    <div class="box-content">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table plan_logo nbb1">
        <tbody>
          <tr>
            <td><div class="adsspecs" style="margin-top:10px; padding-top:3px">
                <ul>
                  <li style="float:left; padding-right:20px">
                    <label>
                      <input name="specs" type="radio" value="" onclick="location.href = '<?php echo url("affiliate/plan.get_ad?planid=".$p['planid']."&type=".$type."&width=".$s['width']."&height=".$s['height'])?>'" <?php if($width =='' && $height =='') echo "checked"?> />
                      全部</label>
                  </li>
                  <?php foreach((array)$specs as $s) {  ?>
                  <li style="float:left; padding-right:20px">
                    <label>
                      <input type="radio" name="specs" onclick="location.href = '<?php echo url("affiliate/plan.get_ad?planid=".$p['planid']."&type=".$type."&width=".$s['width']."&height=".$s['height'])?>'" <?php if($width == $s['width'] && $height == $s['height'] && is_numeric ( get('width') )) echo "checked"?> />
                      <?php echo $s['width'].'x'.$s['height']?></label>
                  </li>
                  <?php } ?>
                </ul>
              </div></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php } ?>
    <div class="row-fluid">
      <div class="span12">
        <div class="box box-color box-bordered">
          <div class="box-title">
            <h3> <i class="icon-table"></i> 广告 </h3>
          </div>
          <div class="box-content nopadding">
            <?php foreach((array)$ad as $a) {   ?>
            <table width="100%" class="table table-hover table-nomargin">
              <tbody>
                <tr>
                  <td>#<?php echo $a['adsid']?></td>
                  <td><div style="width:700px; overflow:hidden">
                      <?php 
				  if($a['plantype'] == 'cpm'){
				 	 echo "无预览";
				  }else {
				  	 echo api ( 'ad.view', $a['adsid'],$a );
				  }
		?>
                    </div></td>
                  <td width="80"><?php if($a['width']>0)echo $a['width'].'x'.$a['height']?></td>
                  <td width="100">
                   <?php 
				   	if($p['audit'] == 'y'){
							$ap =  dr ( 'affiliate/apply.get_apply_status',( int )$_SESSION ['affiliate'] ["uid"],$p['planid']);
							if ($ap['status']=='0'){
									echo '<span class="notification error_bg">审核中</span>';
								}else if ($ap['status']=='1'){
									echo '<span class="notification error_bg">已被拒绝</span>';
								}
								else if ($ap['status']=='2'){
									echo  '<a href="'.url("affiliate/zone.create?adsid=".$a['adsid']."&plantype=".$a['plantype']).'">获取代码</a>';
								}else {
									echo '<span class="notification error_bg">未申请</span>';
									$notap = 1;
								}
						}else {
							echo  '<a href="'.url("affiliate/zone.create?adsid=".$a['adsid']."&plantype=".$a['plantype']).'">获取代码</a>';
							}
				   ?>
                   
                 
                  
                  </td>
                </tr>
              </tbody>
            </table>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
