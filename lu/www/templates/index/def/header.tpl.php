<!doctype html>
<html>
<head>
<link rel="stylesheet" href="<?php echo SRC_TPL_DIR?>/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="广告联盟" />
<meta name="description" content="" />
<meta name="generator" content="zyiis.com v9" />
<meta name="author" content="The YingZhong network Science and Technology CO.Ltd All rights reserved" />
<meta name="copyright" content="2005-2018 YingZhong Inc." />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="960" height="70" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="200"><a href="<?php echo WEB_URL?>"><img src="<?php echo SRC_TPL_DIR?>/images/logo.gif" width="193" height="46" border="0"></a></td>
          <td align="right"><table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="13" height="30" class="red_dotted">&nbsp;</td>
                <td width="30" align="left"><a href="<?php echo url("index.register")?>">注册</a></td>
                <td width="13" class="red_dotted">&nbsp;</td>
                <td width="30" align="left"><a href="<?php echo url("index.login")?>">登入</a></td>
                <td width="13" align="left" class="red_dotted">&nbsp;</td>
                <td width="30"><a href="<?php echo url("index.help")?>">帮助</a></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td bgcolor="#333333"><table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td><table width="100%" height="55" border="0" align="center" cellpadding="0" cellspacing="0"  class="top_nav">
              <tr>
                <?php if(RUN_ACTION=='register') echo '<td class="current" ><a href="#">正在注册</a></td>'?>
                <?php if(RUN_ACTION=='msg') echo '<td class="current" ><a href="#">系统提示</a></td>'?>
                <?php if(RUN_ACTION=='article') echo '<td class="current"  ><a href="#">信息内容</a></td>'?>
                <td  <?php if(RUN_ACTION=='default_action') echo ' class="current"'?>><a href="<?php echo WEB_URL?>" target="_parent">首页</a></td>
                <td    <?php if(RUN_ACTION=='advertiser') echo ' class="current"'?>><a href="<?php echo url("index.advertiser")?>">广告商</a></td>
                <td    <?php if(RUN_ACTION=='affiliate') echo ' class="current"'?>><a href="<?php echo url("index.affiliate")?>">网站主</a></td>
                <?php if ($GLOBALS ['C_ZYIIS']['integral_status']=='1'){?>
                <td   <?php if(RUN_ACTION=='gift') echo ' class="current"'?>><a href="<?php echo url("index.gift")?>">积分乐园</a></td>
                 <?php }?>
                <td  <?php if(RUN_ACTION=='contact') echo ' class="current"'?>><a href="<?php echo url("index.contact")?>">联系联盟</a></td>
              </tr>
            </table></td>
          <td width="200">&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
