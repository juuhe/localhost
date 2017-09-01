<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>
<title>帮助中心</title>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="b_bc">
  <tr>
    <td><table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="150" ><img src="<?php echo SRC_TPL_DIR?>/images/help.jpg" border="0" align="absmiddle" /></td>
        </tr>
      </table></td>
  </tr>
  <tr></tr>
</table>
 <table width="900" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:30px">
   <tr>
     <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
         <tr>
           <td width="70" height="25"><span class="title">网站主</span></td>
           <td>&nbsp;</td>
         </tr>
         <tr>
           <td class="title_td_1"></td>
           <td class="title_td_2"></td>
         </tr>
     </table></td>
     <td width="40">&nbsp;</td>
     <td width="450"><table width="100%" border="0" cellpadding="0" cellspacing="0">
         <tr>
           <td width="70" height="25"><span class="title">广告商</span></td>
           <td align="right">&nbsp;</td>
         </tr>
         <tr>
           <td class="title_td_1"></td>
           <td class="title_td_2"></td>
         </tr>
     </table></td>
   </tr>
   <tr>
     <td><table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px">
       <?php 
		 
		$article = dr ( 'index/article.get_type_article' ,"3",6);
		foreach((array)$article AS $a) {
		?>
       <tr>
         <td width="15" height="30" class="red_dotted"></td>
         <td><a href="<?php echo url("index.article?id=".$a["articleid"])?>">
           <!--[
            <?php  foreach ($GLOBALS['article_type'] AS $key=>$val){ if($a['type'] == $key  ){echo $val; /*substr($val,0,6);*/} }?>
            ]-->
           <?php echo $a["title"]?></a></td>
        </tr>
       <?php }?>
     </table></td>
     <td>&nbsp;</td>
     <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px">
       <?php 
		 
		$article = dr ( 'index/article.get_type_article' ,"4",6);
		foreach((array)$article AS $a) {
		?>
       <tr>
         <td width="15" height="30" class="red_dotted"></td>
         <td><a href="<?php echo url("index.article?id=".$a["articleid"])?>">
           <!--[
            <?php  foreach ($GLOBALS['article_type'] AS $key=>$val){ if($a['type'] == $key  ){echo $val; /*substr($val,0,6);*/} }?>
            ]-->
           <?php echo $a["title"]?></a></td>
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
