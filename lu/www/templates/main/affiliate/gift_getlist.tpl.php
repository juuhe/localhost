<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>

<div id="left">
  <?php TPL::display('left');?>
</div>



  
<div id="main" style="padding-top:10px"> 
 <?php if( get('not_integral') == 'yes') {?>
  <div class="alert alert-error" style="margin-top:10px"> 哇哦！积分不够,无法兑换 </div>
  <?php }?>
  
   <?php if( get('exchange_ok') == 'yes') {?>
  <div class="alert alert-info" style="margin-top:10px">  兑换成功 </div>
  <?php }?>
  
<div class="alert alert-info" style="margin-top:10px">
							<h4>如何获得积分</h4>
							<p>1.投放广告获的收入，结算时转换成对等的税分</p>
							<p>2.投放广告一天获得<?php echo $GLOBALS['C_ZYIIS']['integral_day']?>分</p>
							<p>3.不定期增送税分和联盟保持长期稳定的合作，合作时间越长，获得积分越多</p>
                              <p>4.获的收入并且达到了我们结算标准已确认支付，即获得积分 如：这周期收入100元=<?php echo 100*$GLOBALS['C_ZYIIS']['integral_topay']?>积分 1元的收入=<?php echo  $GLOBALS['C_ZYIIS']['integral_topay']?>积分 </p>
              <p>5.一天24小时不间断投放广告PV达到<?php echo $GLOBALS['C_ZYIIS']['integral_daypv']?>以上时获的<?php echo $GLOBALS['C_ZYIIS']['integral_day']?>分</p>
  </div>
  <div class="box" >
  
 
    <div class="box-title">
      <h3><i class="icon-table"></i>积分乐园 <span style="font-size:12px; color:#F00">你的积分：<?php echo $GLOBALS ['userinfo']['integral'];?>分</span></h3>
      <div class="actions" style="color: #08c;"> <span style=" cursor:pointer">
        <select name="classid" onchange="location.href = this.options[selectedIndex].value">
          <option value="<?php echo url("affiliate/gift.get_list")?>">按活动分类</option>
          <?php foreach((array)$GLOBALS['gift_type'] as $key=>$val) {?>
          <option value="<?php echo url("affiliate/gift.get_list?type=".$key)?>" <?php if($type==$key && is_numeric($type)){echo 'selected';}?>><?php echo $val?></option>
          <?php }?>
        </select>
        <select name="classid" onchange="location.href = this.options[selectedIndex].value">
          <option value="<?php echo url("affiliate/gift.get_list")?>">按积分高低</option>
          <option value="<?php echo url("affiliate/gift.get_list?type=".$type."&gift=1")?>" <?php if($gift==1){echo 'selected';}?>>0-500积分</option>
          <option value="<?php echo url("affiliate/gift.get_list?type=".$type."&gift=2")?>" <?php if($gift==2){echo 'selected';}?>>500-2000积分</option>
          <option value="<?php echo url("affiliate/gift.get_list?type=".$type."&gift=3")?>" <?php if($gift==3){echo 'selected';}?>>2000-5000积分</option>
          <option value="<?php echo url("affiliate/gift.get_list?type=".$type."&gift=4")?>" <?php if($gift==4){echo 'selected';}?>>5000-10000积分</option>
          <option value="<?php echo url("affiliate/gift.get_list?type=".$type."&gift=5")?>" <?php if($gift==5){echo 'selected';}?>>10000-50000积分</option>
          <option value="<?php echo url("affiliate/gift.get_list?type=".$type."&gift=6")?>" <?php if($gift==6){echo 'selected';}?>>50000-100000积分</option>
          <option value="<?php echo url("affiliate/gift.get_list?type=".$type."&gift=7")?>" <?php if($gift==7){echo 'selected';}?>>100000积分以上</option>
        </select>
        </span> </div>
    </div>
    <div class="box-content">
      <table class="table" >
        <tr>
          <td style="border-bottom: 0px dotted #ddd;"  ><ul class="jful">
              <?php 
				   
				   foreach((array)$gift_data  AS $i) {?>
              <li>
                <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#E5E5E5" >
                  <tr>
                    <td valign="middle" bgcolor="#FFFFFF"><img src="<?php echo $i['imageurl']?>" align="absmiddle"  class="img"/></td>
                  </tr>
                  <tr>
                    <td bgcolor="#FFFFFF" style="border-bottom: 1px dotted #ddd;"><div class="jftit"><a href="<?php echo url("affiliate/gift.exchange?id=".$i['id'])?>" class="exchange"><?php echo  $i['name'] ?></a> </div>
                      <div class="jfnum"><?php echo $i['integral']?>积分<a href="<?php echo url("affiliate/gift.exchange?id=".$i['id'])?>" class="exchange"><img src="<?php echo SRC_TPL_DIR?>/images/gift_06.jpg" border="0" align="absmiddle"  style=" width:33px; height:14px"/></a></div></td>
                  </tr>
                </table>
              </li>
              <?php } ?>
            </ul></td>
        </tr>
      </table>
    </div>
  </div>
  <div>
    <?php  echo $page->echoPage ();?>
  </div>
</div>
