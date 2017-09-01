<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>

<div id="left">
  <div class="subnav">
    <div class="subnav-title"> <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>广告创意</span></a> </div>
    <ul class="subnav-menu">
	
      <?php
	   if(($ap['status'] == 2 && $p['audit'] == 'y') || $p['audit'] != 'y' ){
	  	 foreach((array)$adtpl as $apl) {
	  		$get_tpl = dr ( 'affiliate/adtpl.get_one_adtpl_adtype', $apl['adtplid']);
	  ?>
      <li <?php if($type==$apl['adtplid']) echo "class='current'"?>> <a href="<?php echo url("affiliate/plan.get_ad?planid=".$p['planid']."&type=".$apl['adtplid'])?>"><?php echo $get_tpl['tplname']?> <font color="#FF0000">(<?php echo $apl['num'];?>)</font></a> </li>
      <?php   }?>
	  
	   <?php if($p['linkon'] == 'y') {?>
      <li > <a href="<?php echo url("affiliate/code.get_custom?planid=".$p['planid'])?>">自定义链接 <input name="" type="button"  value="自定义"/></a> </li>
      <?php } }?>

    </ul>
  </div>
</div>
<div id="main" style="padding-top:10px">
<div class="box" >
<div class="box-title">
  <h3><i class="icon-table"></i>活动</h3>
</div>
<div class="box-content">
  <table class="table plan_logo nbb1">
    <thead>
      <tr>
        <th>活动名称</th>
        <th width="100">类型</th>
        <th width="100">佣金</th>
        <th width="120">活动分类</th>
        <th>状态</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="logo">
            <tr>
              <td width="150" rowspan="2"><img src="<?php echo $p["logo"] ? $p["logo"] : SRC_TPL_DIR.'/images/no.gif'?>" border="0" /></td>
              <td><?php echo $p['planname']?></td>
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
			$af = $p["plantype"] == 'cps' ? "%" :"元"; 
			$price = main_public::format_plan_print($p['planid']);
			if(is_array($price)){
				echo $price["min"].$af.'-'.$price["max"].$af;
			}else {
				echo $price.$af;
			}
			
			?></td>
        <td><?php 
			$c =  dr ( 'main/class.get_one',$p["classid"] );
			echo $c['classname']?></td>
        <td><?php 
							if($p['status'] == 3) echo '<span class="notification error_bg">饱和</span>';
							else if($p['status'] == 5) echo '<span class="notification error_bg">暂停申请</span>';
							else if ($p['audit'] == 'y') {
								 
								if ($ap['status']=='0'){
									echo '<span class="notification error_bg">审核中</span>';
								}else if ($ap['status']=='1'){
									echo '<span class="notification error_bg">已被拒绝</span>';
								}
								else if ($ap['status']=='2'){
									echo '<span class="notification info_bg">正常</span>';
								}else {
									echo '<span class="notification error_bg">未申请</span>';
								}
							 }
							else  echo '<span class="notification info_bg">活动</span>';
							?></td>
      </tr>
    </tbody>
  </table>
</div>
<div class="row-fluid">
  <div class="span12">
    <div class="box box-color box-bordered">
      <div class="box-title">
        <h3> <i class="icon-table"></i> 基本信息 </h3>
      </div>
      <div class="box-content nopadding">
        <table class="table table-hover table-nomargin">
          <tbody>
            <tr>
              <td>活动名称</td>
              <td><?php echo $p['planname']?></td>
            </tr>
            <tr>
              <td width="110">广告分类</td>
              <td><?php echo $c['classname']?></td>
            </tr>
            <tr>
              <td>活动周期</td>
              <td><?php 
							if ($p['expire']=='0000-00-00') echo '不限制';
							else echo substr($p['addtime'],0,10).' 至 '.$p['expire'];
							?></td>
            </tr>
            <tr>
              <td>支持设备</td>
              <td><?php 
							if (!$pc_mob) {
								echo '所有设备';
							}
							else {
								if(in_array('pc',$pc_mob)){
									  echo "桌面电脑";
								}
								if(in_array('mob',$pc_mob)){
									  echo count($pc_mob)>2? "、":"";
									  echo "移动设备";
								}
							}
							?></td>
            </tr>
            <?php if (in_array($p['plantype'],array("cps","cpa"))) {?>
            <tr>
              <td>自定义链接</td>
              <td><?php if ($p['linkon']=='n') {echo "不支持";} else { echo '支持';}?></td>
            </tr>
            <tr>
              <td>数据返回</td>
              <td><?php echo $p['datatime']?></td>
            </tr>
            <tr>
              <td>cookie有效期</td>
              <td><?php echo $p['cookie']?></td>
            </tr>
            <?php }?>
            <tr>
              <td>定向功能</td>
              <td><?php if ($checkplan) {echo "已启用";} else { echo '未启用';}?></td>
            </tr>
            <tr>
              <td>详细说明</td>
              <td><?php echo nl2br($p['planinfo'])?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <?php if($p['plantype'] == 'cps'){
		   if ($p ['gradeprice'] == '1') {
		   		$sp = ( array ) unserialize ( $p ['classprice'] );
				
		   }else {
		   		$sp['classprice_mark'] = abs($p['price']);
		   	}	
			
			 
	?>
    <div class="row-fluid">
      <div class="span12">
        <div class="box box-color box-bordered">
          <div class="box-title">
            <h3> <i class="icon-table"></i> 当前佣金 </h3>
          </div>
          <div class="box-content nopadding">
            <table class="table plan_logo">
              <thead>
                <tr>
                  <th>序号</th>
                  <th>佣金说明</th>
                  <th width="100">佣金</th>
                  <th>备注</th>
                </tr>
              </thead>
              <tbody>
                <?php for($i=0;$i<count($sp['classprice_mark']);$i++) {?>
                <tr>
                  <td height="40"><?php echo $i+1?></td>
                  <td><?php echo $p ['gradeprice'] == '1' ? $sp['classprice_mark_info'][$i]:"默认"?></td>
                  <td><?php echo $p ['gradeprice'] == '1' ? $sp['classprice_aff'][$i]:abs($p['price'])?>%</td>
                  <td><?php echo $p ['gradeprice'] == '1' ? $sp['classprice_memo'][$i]:$p['priceinfo']?></td>
                </tr>
                <?php }?>
              </tbody>
            </table>
          </div>
        </div>
        <?php }?>
      </div>
    </div>
  </div>
</div>
