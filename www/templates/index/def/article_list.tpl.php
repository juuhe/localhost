<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>
<title>公告列表</title>
 
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="b_bc">
  <tr>
    <td><table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td height="150" ><img src="<?php echo SRC_TPL_DIR?>/images/article.jpg" border="0" align="absmiddle" /></td>
      </tr>
    </table></td>
  </tr>
  <tr></tr>
</table>
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:30px">
  <tr>
    <td width="285"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="70" height="25"><span class="title">公告列表</span></td>
        <td align="right">&nbsp;</td>
      </tr>
      <tr>
        <td class="title_td_1"></td>
        <td class="title_td_2"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:20px">
      <?php 
		foreach((array)$article AS $a) {
		?>
      <tr>
        <td width="15" height="30" class="red_dotted"></td>
        <td><a href="<?php echo url("index.article?id=".$a["articleid"])?>"><!--[
          <?php  foreach ($GLOBALS['article_type'] AS $key=>$val){ if($a['type'] == $key  ){ echo $val; /*substr($val,0,6);*/} }?>
          ]--><font color="<?php echo $a['color']?>" >
              <?php 
			echo $a['top']=='2'?'[置顶] ':'';
			echo $a["title"];
			?>
              </font></a></td>
        <td><?php echo date("m-d",strtotime($a['addtime']));?></td>
      </tr>
      <?php }?>
    </table></td>
  </tr>
  <tr>
    <td height="60"><div class="row">
        <?php  echo $page->echoPage ();?>
      </div> </td>
  </tr>
</table>
 <?php TPL::display('footer');?>