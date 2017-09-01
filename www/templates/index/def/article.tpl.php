<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>
<title><?php echo $article["title"]?></title>
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
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:30px">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="25"><span class="article_title"><?php echo $article["title"]?></span></td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td class="title_td_2"></td>
        <td class="title_td_2"></td>
      </tr>
    </table></td>
    <td width="40">&nbsp;</td>
    <td width="285"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="70" height="25"><span class="title">最新公告</span></td>
        <td align="right"><a href="<?php echo url("index.article_list")?>">更多...</a></td>
      </tr>
      <tr>
        <td class="title_td_1"></td>
        <td class="title_td_2"></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td height="30" style="color:#a4a4a4">类型：<?php  foreach ($GLOBALS['article_type'] AS $key=>$val){ if($article['type'] == $key  ){echo $val;} }?> 时间：<?php echo $article["addtime"]?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td  style="line-height:22px"><?php echo $article["content"]?></td>
      </tr>
    </table></td>
    <td>&nbsp;</td>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:20px">
      <?php 
	//如只要公告"1",多个用“,”分开 效果"1,2,3",后面为要显示条数
	$article = dr ( 'index/article.get_type_article' ,"1,3",12);
	foreach((array)$article AS $a) {
	?>
      <tr>
        <td width="15" height="30" class="red_dotted"></td>
        <td><a href="<?php echo url("index.article?id=".$a["articleid"])?>"><!--[
          <?php  foreach ($GLOBALS['article_type'] AS $key=>$val){ if($a['type'] == $key  ){ echo $val; /*substr($val,0,6);*/} }?>
          ]--><?php echo $a["title"]?></a></td>
        <td><?php echo date("m-d",strtotime($a['addtime']));?></td>
      </tr>
      <?php }?>
    </table></td>
  </tr>
  <tr>
    <td height="30">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
 <?php TPL::display('footer');?>