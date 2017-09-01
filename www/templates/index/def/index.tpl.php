<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header'); ?>
<title><?php echo $GLOBALS['C_ZYIIS']['sitename']?></title>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:1px">
  <tr>
    <td bgcolor="#F7F6EF"><table width="960" height="340" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="660"><div id="slider">
              <ul style="display:none;">
                <li><img src="<?php echo SRC_TPL_DIR?>/images/slider_1.jpg" align="absmiddle" border="0" /></li>
                <li><img src="<?php echo SRC_TPL_DIR?>/images/slider_2.jpg" align="absmiddle" border="0" /></li>
                <li><img src="<?php echo SRC_TPL_DIR?>/images/slider_3.jpg" align="absmiddle" border="0" /></li>
              </ul>
              <a href="<?php echo url("index.register")?>" class="slider-reg"></a> </div></td>
          <td width="285" valign="top"><form id="form1" name="form1" method="post" action="<?php echo url("index.postlogin")?>" onSubmit="return doLogin()">
              <table width="100%" border="0" cellpadding="0" cellspacing="0"  class="login">
                <tr>
                  <td height="40" style="color:#785A3A;font-size:16px; font-weight:bold">登 入 <span class="error_span"></span></td>
                </tr>
                <tr>
                  <td height="50"><input name="username" type="text"  class="user" id="username" /></td>
                </tr>
                <tr>
                  <td height="50"><input name="password" type="password" class="pwd" id="password" /></td>
                </tr>
                <?php if ($GLOBALS ['C_ZYIIS']['login_check_code']=='1'){?>
                <tr>
                  <td height="50"><input name="checkcode" type="text" id="checkcode" style="width:120px; padding-left:8px" maxlength="4" />
                    <img src="<?php echo url("index.codeimage")?>" align="absmiddle"  title= "看不清?请点击刷新验证码"  onclick="this.src='<?php echo url("index.codeimage?rand=")?>'+Math.random();"  style= "cursor:pointer;"/></td>
                </tr>
                <?php }?>
                <tr>
                  <td height="50"><input name="image3" type="image" src="<?php echo SRC_TPL_DIR?>/images/login_sb.jpg" align="absmiddle" border="0" style="width:104px; height:35px; border:0px" />
                    <span style="margin-left:20px"><a href="<?php echo url("index.find_passwd")?>">忘记密码？</a></span></td>
                </tr>
                <tr>
                  <td height="60"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="120" align="center">没有帐号？<a href="<?php echo url("index.register")?>" style=" color:#3399e0">立即注册</a></td>
                        <td align="right">
                        <?php if ($GLOBALS ['C_ZYIIS']['oauth_qq_app_id']){?>
                        <a href="<?php echo url("oauth/qq.login")?>" title="腾讯QQ登录 "><img src="<?php echo SRC_TPL_DIR?>/images/qq_login1.png"  border="0" alt="腾讯QQ登录" /></a>
                         <?php }?>
                        </td>
                        <td align="right">&nbsp;</td>
                      </tr>
                    </table></td>
                </tr>
              </table>
            </form></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="960" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-top:20px">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="70" height="25"><span class="title">计费形式</span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="title_td_1"></td>
          <td class="title_td_2"></td>
        </tr>
      </table></td>
    <td width="40">&nbsp;</td>
    <td width="285"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="70" height="25"><span class="title">联盟公告</span></td>
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
          <td height="50"> 专注于网络广告的研究与发展，为客户提供各种形式的网络广告投放服务。</td>
        </tr>
        <tr>
          <td><img src="<?php echo SRC_TPL_DIR?>/images/ad_type.jpg" /></td>
        </tr>
      </table></td>
    <td>&nbsp;</td>
    <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:10px">
        <?php 
		//如只要公告"1",多个用“,”分开 效果"1,2,3",后面为要显示条数
		$article = dr ( 'index/article.get_type_article' ,"1,2",6);
		foreach((array)$article AS $a) {
		?>
        <tr>
          <td width="15" height="30" class="red_dotted"></td>
          <td><div style="width:200px; overflow:hidden;height: 20px;"> <a href="<?php echo url("index.article?id=".$a["articleid"])?>"><!--[
            <?php  foreach ($GLOBALS['article_type'] AS $key=>$val){ if($a['type'] == $key  ){echo $val; /*substr($val,0,6);*/} }?>
            ]--> 
              
              <font color="<?php echo $a['color']?>" >
              <?php 
			echo $a['top']=='2'?'[置顶] ':'';
			echo $a["title"];
			?>
              </font> </a> </div></td>
          <td><?php echo date("m-d",strtotime($a['addtime']));?></td>
        </tr>
        <?php }?>
      </table></td>
  </tr>
  <tr>
    <td height="10">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="200" height="25"><span class="title">广告类型（部分）</span></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td class="title_td_1"></td>
          <td class="title_td_2"></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td colspan="3"><table width="100%" border="0" cellpadding="0" style="margin-top:22px">
        <tr>
          <td align="center" valign="top"  class="box"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td height="30"><table width="200" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="40" class="box_tit">移动插屏广告(移动广告专用)</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td height="30"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="220" height="40" style="line-height:22px; padding:10px">插屏广告是一种新兴的广告形式，一般在网页中间或全屏形式插入广告，插屏广告采用了自动广告适配和缓存优化技术，用户可自己定义“全屏展示”“半屏展示”，点击率较高。</td>
                      <td><img src="<?php echo SRC_TPL_DIR?>/images/cp.jpg" /></td>
                    </tr>
                  </table></td>
              </tr>
              <tr></tr>
            </table></td>
          <td align="center"  class="box"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td height="30"><table width="200" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="40" class="box_tit">移动通栏广告(移动广告专用)</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td height="30"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="220" height="40" style="line-height:22px; padding:10px">即横幅广告条，是在一个手机网站的顶部或是底部区域内固定展示广告，点击广告之后会有 跳转到手机网页、下载手机应用。</td>
                      <td><img src="<?php echo SRC_TPL_DIR?>/images/banner.jpg" /></td>
                    </tr>
                  </table></td>
              </tr>
              <tr></tr>
            </table></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" style="margin-top:22px">
        <tr>
          <td align="center" valign="top"  class="box"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td height="30"><table width="200" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="40" class="box_tit">弹窗广告(通用)</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td height="30"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="220" height="40" style="line-height:22px; padding:10px">弹窗广告是指打开网站后自动弹出的广告， 弹窗广告是互联网上最古老也最常用的网络推广形式之一，广泛的应用于网站 、企业的产品快速推广和宣传。</td>
                      <td><img src="<?php echo SRC_TPL_DIR?>/images/cpm.jpg" /></td>
                    </tr>
                  </table></td>
              </tr>
              <tr></tr>
            </table></td>
          <td align="center"  class="box"><table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td height="30"><table width="200" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td height="40" class="box_tit">标签云、图文混排(通用)</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td height="30"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="220" height="40" style="line-height:22px; padding:10px">标签云（Tag Cloud）是用以表示一个网站中的内容标签、关键字，按照字体的大小和颜色随机排列。图文混排是一种按文字和图片混排的广告形式，达到更好的展示效果。</td>
                      <td><img src="<?php echo SRC_TPL_DIR?>/images/cp1.jpg" /></td>
                    </tr>
                  </table></td>
              </tr>
              <tr></tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<script src="<?php echo WEB_URL?>js/imgnum.js" type="text/javascript"></script>
<?php TPL::display('footer');?>
