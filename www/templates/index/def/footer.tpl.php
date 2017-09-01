<?php if(!defined('IN_ZYADS')) exit(); ?>

<table width="100%" height="250" border="0" align="center" cellpadding="0" cellspacing="0" class="footer" style="margin-top:20px">
  <tr>
    <td valign="top"><table  border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:30px">
        <tr>
          <td height="50" align="center" class="links"><a href="<?php echo url("index.company")?>">关于我们</a></td>
          <td align="center" class="links"><a href="<?php echo url("index.help")?>">站长帮助</a></td>
          <td align="center" class="links"><a href="<?php echo url("index.help")?>">广告商帮助</a></td>
          <td class="links" ><a href="javascript:;" style="width:60px">网站主客服</a></td>
          <td  class="links"><a href="javascript:;" style="width:30px">商务</a></td>
        </tr>
        <tr>
          <td   class="links_1"><a href="<?php echo url("index.company")?>">关于我们</a></td>
          <td   class="links_1"><a href="<?php echo url("affiliate/index.get_list")?>">结算周期</a></td>
          <td  class="links_1"><a href="<?php echo url("advertiser/index.get_list")?>">发布广告</a></td>
          <td class="links_1"><a href="http://wpa.qq.com/msgrd?v=3&amp;uin=88888888&amp;site=qq&amp;menu=yes">客服QQ：888888</a></td>
          <td class="links_1"><a href="http://wpa.qq.com/msgrd?v=3&amp;uin=88888888&amp;site=qq&amp;menu=yes">商务QQ：888888</a></td>
        </tr>
        <tr>
          <td align="center" >&nbsp;</td>
          <td class="links_1"><a href="<?php echo url("affiliate/index.get_list")?>">获取广告</a></td>
          <td class="links_1"><a href="<?php echo url("advertiser/index.get_list")?>">在线充值</a></td>
          <td class="links">&nbsp;</td>
          <td class="links">&nbsp;</td>
        </tr>
        <tr>
          <td align="center" ><a href="#"></a></td>
          <td class="links_1"><a href="<?php echo url("affiliate/index.get_list")?>">广告类型</a></td>
          <td class="links_1"><a href="<?php echo url("advertiser/index.get_list")?>">数据统计</a></td>
          <td class="links">&nbsp;</td>
          <td class="links">&nbsp;</td>
        </tr>
      </table>
      <table width="900"  border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:30px">
        <tr >
          <td   align="center" height="1" style="border-bottom: 1px solid #bec9d0;"></td>
        </tr>
        <tr >
          <td   align="center" style="border-top: 1px solid #fff;"></td>
        </tr>
      </table>
      <table  border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:10px; margin-bottom:30px">
        <tr>
          <td height="40"   class="copyright ">Copyright ©<?php echo date("Y",TIMES)?> All Rights Reserved <a href="http://www.zyiis.com/" target="_blank"><img src="<?php echo SRC_TPL_DIR?>/images/z.gif" border="0" align="absmiddle" title="广告联盟" /></a></td>
        </tr>
        <tr>
          <td >信息产业部备案号：赣ICP备888号-1</td>
        </tr>
      </table></td>
  </tr>
</table>
<script language="JavaScript" type="text/javascript">
function $i(obj){
	return document.all ? document.all[obj] : document.getElementById(obj);
}
function doLogin(){
	var username = $i("username").value;
	var password = $i("password").value;
	if(username.length=='0'){
         alert('请输入你用户名');
         $i("username").focus()
         return false;
    }
    if(password.length=='0'){
         alert('请输入你的密码!');
         $i("password").focus()
         return false;
    }
	try{
		var checkcode = $i("checkcode").value;
		if(checkcode ==''){
			 alert('验证码不能为空');
			 $i("checkcode").focus()
			 return false;
		}
	}catch(e){}  
}
</script> 
