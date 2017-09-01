<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>

<div id="left">
 <?php TPL::display('left');?>
</div>
<div id="main" style="padding-top:10px">
   
 
     
  
  <div class="box" >
    <div class="box-title">
      <h3><i class="icon-table"></i>系统消息</h3>
      
    </div>
     
    <div class="box-content">
      <table class="table">
        <thead>
          <tr>
            <th>标题</th>
            <th width="160">发送时间</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach((array)$msg as $m) { ?>
          <tr>
            <td class="in_read" msgid="<?php echo $m['messageid']?>"><div class="i"></div> <?php 
			 if($m['read'] == 'n'){  
				  echo  "<b>".$m['title']."</b>";
			 }else {
				 echo  $m['title'];
			 }
			 ?></td>
            <td><?php echo $m['addtime']?></td>
          </tr>
          
          
           <tr id="read_msg_id_<?php echo $m['messageid']?>" style="display:none" >
            <td colspan="2"> <div class="read_msg"><div> <?php echo nl2br($m["content"]);?></div></div></td>
           </tr>
          
          <?php }?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script> 
<script type="text/javascript">
$('.in_read').on('click', function(option) {   
	var msgid = $(this).attr("msgid");
	if($(this).attr("data_v") == 1){  
		var o = $('#read_msg_id_'+msgid).hide();
		$(this).attr("data_v",0);
		$(this).find('div').css("backgroundImage","url(<?php echo SRC_TPL_DIR?>/images/z_13x13.jpg)"); 
	
	}else { 
    	var o = $('#read_msg_id_'+msgid).show();
		$(this).attr("data_v",1);
		$(this).find('div').css("backgroundImage","url(<?php echo SRC_TPL_DIR?>/images/g_13x13.jpg)"); 
		$.get("<?php echo url("advertiser/msg.read?msgid=")?>"+msgid, function(result){
			 
		});
	}
});
</script>
