<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header'); ?>
 <title>奖品 <?php echo $GLOBALS['C_ZYIIS']['sitename']?></title>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="b_bc">
  <tr>
    <td> <table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="150" ><img src="<?php echo SRC_TPL_DIR?>/images/gift.jpg" border="0" align="absmiddle" /></td>
        </tr>
      </table></td>
  </tr>
  <tr></tr>
</table>
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:30px; margin-bottom:30px">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="70" height="25"><span class="title">奖品分类</span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="title_td_1"></td>
          <td class="title_td_2"></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="50" class="g_cl"><?php foreach((array)$GLOBALS['gift_type'] as $key=>$val) {?>
            <a href="<?php echo url("index.gift?type=".$key)?>"  <?php if($type==$key && is_numeric($type)) echo "class='jfc_1'"?>><?php echo $val?></a>
            <?php } ?>          </td>
        </tr>
        <tr>
          <td height="30" class="g_cl">  <a href="<?php echo url("index.gift?type=".$type."&gift=1")?>"  <?php if($gift==1) echo "class='jfc_1'"?>>0-500积分</a>
                          <a href="<?php echo url("index.gift?type=".$type."&gift=2")?>"  <?php if($gift==2) echo "class='jfc_1'"?>>500-2000积分</a>
                          <a href="<?php echo url("index.gift?type=".$type."&gift=3")?>"  <?php if($gift==3) echo "class='jfc_1'"?>>2000-5000积分</a>
                          <a href="<?php echo url("index.gift?type=".$type."&gift=4")?>"  <?php if($gift==4) echo "class='jfc_1'"?>>5000-10000积分</a>
                          <a href="<?php echo url("index.gift?type=".$type."&gift=5")?>"  <?php if($gift==5) echo "class='jfc_1'"?>>10000-50000积分</a>
                         <a href="<?php echo url("index.gift?type=".$type."&gift=6")?>"  <?php if($gift==6) echo "class='jfc_1'"?>>50000-100000积分</a>
          <a href="<?php echo url("index.gift?type=".$type."&gift=7")?>"  <?php if($gift==7) echo "class='jfc_1'"?>>100000积分以上</a></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr></tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="70" height="25"><span class="title">推荐奖品 </span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="title_td_1"></td>
          <td class="title_td_2"></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td valign="top"><ul class="jful">
                  <?php 
				   $gift_data = dr ( 'index/gift.get_top_gift',$type,$gift,20);
				   foreach((array)$gift_data  AS $i) {?>
                  <li>
                    <table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#E5E5E5" >
                      <tr>
                        <td valign="middle" bgcolor="#FFFFFF"><img src="<?php echo $i['imageurl']?>" align="absmiddle"  class="img"/></td>
                      </tr>
                      <tr>
                        <td bgcolor="#FFFFFF"><div class="jftit"><a href="javascript:;" class="exchange"><?php echo  $i['name'] ?></a> </div>
                            <div class="jfnum"><?php echo $i['integral']?>积分<a href="javascript:;" class="exchange"><img src="<?php echo SRC_TPL_DIR?>/images/gift_06.jpg" border="0" align="absmiddle"  style=" width:33px; height:14px"/></a></div></td>
                      </tr>
                    </table>
                  </li>
                  <?php } ?>
                </ul>                </td>
              </tr>
              <tr>
                <td width="730" valign="top">  <div class="row">
        <?php  echo $page->echoPage ();?>
      </div></td>
                </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td></td>
  </tr>
</table>


<?php TPL::display('footer');?>
