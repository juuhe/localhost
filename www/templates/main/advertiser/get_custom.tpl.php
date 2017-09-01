<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>

<div id="left">
  <div class="subnav">
    <div class="subnav-title"> <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span>帮助</span></a> </div>
    <ul class="subnav-menu">
      <li > <a href="<?php echo url("index.article?id=88")?>" target="_blank">什么是自定义链接</a> </li>
      <li > <a href="<?php echo url("index.article?id=89")?>">什么是Url直链</a> </li>
    </ul>
  </div>
</div>
<div id="main" style="padding-top:10px">
<div class="box" >
<div class="box-title">
  <h3><i class="icon-table"></i>链接转换工具</h3>
</div>
<div class="box-content">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td ><table style="width:80%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="30"><div class="box box-info">仅对活动域名的地址转换</div></td>
          </tr>
          <tr>
            <td height="50"  ><select name="classid" onchange="location.href = this.options[selectedIndex].value" style="padding:6px">
                <option value="<?php echo url("affiliate/code.get_custom")?>">选择一个活动</option>
                <?php  foreach ($plan AS $pn){?>
                <option value="<?php echo url("affiliate/code.get_custom?planid=".$pn["planid"])?>" <?php if(get('planid') == $pn["planid"] ){echo 'selected';}?>><?php echo $pn["planname"]?></option>
                <?php }?>
              </select>
              活动域名：<a href="http://<?php echo $p['linkurl']?>" target="_blank"><font color="#FF0000"><?php echo $p['linkurl']?></font></a></td>
          </tr>
          <tr>
            <td width="100" height="30"  ><textarea name="tourl" id="tourl" cols="70" rows="3" placeholder=""  onfocus="if (this.value=='请输入一个活动域名中的URL,比如：http://<?php echo $p['linkurl']?>'){ this.value=''}"  onblur="if (this.value==''){ this.value='请输入一个活动域名中的URL,比如：http://<?php echo $p['linkurl']?>'}" style="color:#666; padding:10px" >请输入一个活动域名中的URL,比如：http://<?php echo $p['linkurl']?></textarea></td>
          </tr>
          <tr>
            <td height="60"  ><button type="submit" class="btn btn-primary" id="transurl"> 生成推广链接 </button></td>
          </tr>
          <tr>
            <td height="30"  ><textarea name="adurl" id="adurl" cols="70" rows="3" style="padding:10px" ></textarea></td>
          </tr>
          <tr>
            <td height="40"  ><input name="button"  type="button" class="btn btn-green big" id="getcode"  value="复制代码" />
              <span class="copy_ok" style=" ">复制成功!</span></td>
          </tr>
        </table></td>
    </tr>
  </table>
</div>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="<?php echo WEB_URL?>js/clipboard/clipboard.js"></script>
<script language="JavaScript" type="text/javascript">
 $('#transurl').on('click', function(option) { 
	var re=/^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)(\S+\.\S+)$/;
	var y_url = '<?php echo $p['linkurl']?>';
	var tourl = $("#tourl").val();

	if (tourl.match(re) == null){
		alert("请输入正确的网址");
		return false;
	}
	
	if(tourl.indexOf(y_url) == -1){
		alert("填写地址不在活动域名中，请正确的填写");
		return false;
	}
	var adurl = "http://c.ex.com/?s=";
	$("#adurl").val(adurl+escape(tourl));
 });
 
 $('#getcode').on('mouseenter', function(option) {  
				var clip = new ZeroClipboard.Client(); 
				ZeroClipboard.setMoviePath("<?php echo WEB_URL?>js/clipboard/clipboard.swf") ;
				clip.setHandCursor(true); 
				clip.setText($("#adurl").val());          
				clip.addEventListener('complete',  function(client, text) {
					$(".copy_ok").show();
				});
				clip.glue('getcode');  
 });
		
</script>
