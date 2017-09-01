<?php if(!defined('IN_ZYADS')) exit(); 
TPL::display('header');
?>
<link rel="stylesheet" href="<?php echo SRC_TPL_DIR?>/style/modal.css" media="all" type="text/css" />
<script type="text/javascript" src="<?php echo WEB_URL?>js/jquery/js/jquery-1.7.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo WEB_URL?>js/jquery/lib/leanmodal/leanmodal.min.js"></script>
<div id="left">
 <?php TPL::display('left');?>
</div>
<div id="main" style="padding-top:10px">
   
  
   
     <div class="mt30"  >
    
   <button class="btn btn-primary stausbnt" status=2>已审核</button>
           <button class="btn btn-primary stausbnt" status=0>等审核</button>
         <button class="btn btn-primary stausbnt" status=1>已拒绝</button>
         <button class="btn btn-primary stausbnt"  status=all>全部</button>
   
  </div>
  
  <div class="box" >
    <div class="box-title">
      <h3><i class="icon-table"></i>申请列表</h3>
      
    </div>
     
    <div class="box-content">
      <table style=" margin-bottom:30px; margin-top:30px" border="0" cellpadding="0" cellspacing="0"  >
        <tr>
          <td width="160">批量操作：<select size="1" name="choose_type" id="choose_type" class="select">
              <option value="">请选择</option>
              <option value="unlock">激活</option>
              <option value="lock">锁定</option>
              <option value="del">删除</option>
            </select></td>
          
          <td width="60"><button type="button" class="btn btn-primary" id="choose_sb"> 提 交 </button></td>
          <td align="right"><form method="post"> 
     <table style="width:330px" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><select name="searchtype" class="select">
                <option value="username" <?php if ($searchtype == 'username') echo "selected";?>>会员名称</option>
				 <option value="uid" <?php if ($searchtype == 'uid') echo "selected";?>>会员ID</option>
			   <option value="planid" <?php if ($searchtype == 'planid') echo "selected";?>>计划ID</option>
              </select></td>
    <td><input type="text" class="input_text" name="search" value="<?php echo $search?>" style="margin-bottom:0px" /></td>
    <td><button type="submit" class="btn btn-primary"  > 搜 索 </button>  </td>
  </tr>
  </table>

 </form></td>
        </tr>
      </table>
      <table class="table">
        <thead>
          <tr>
            <th><input type="checkbox" name="select_id" id="select_id"></th>
            <th>申请计划</th>
            <th>申请会员</th>
            <th>申请网站</th>
            <th>状态</th>
            <th width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php  foreach((array)$apply as $s){ 
				 $site = $sitedata =array();	
			  ?>
          <tr >
            <td><input type="checkbox" name="id" id="apply_<?php echo $s["id"]?>" value="<?php echo $s["id"]?>"></td>
            <td><?php echo $s["planname"]?><br />
              <span class="help-block" style="margin: 0;"><?php echo $s['addtime']?><br />
            <?php echo $s["approvaltime"] == '0000-00-00 00:00:00'? "" : $s["approvaltime"]?></span></td>
            <td><?php echo $s["username"]?></td>
            <td><?php 
				  if($s['applysiteidtype']==1){ 
				  	 $site = 	dr ( 'advertiser/site.get_list_ok',$s['uid']);
					 foreach((array)$site AS $sn ){
					 	 $sitedata[] = $sn['siteid'];
					 }
					 $site = $sitedata;
				  }else {
				 	 $site = explode(',',$s['siteid']);
				  }
				 
				  foreach((array)$site AS $sn ){
				  	 $se = 	dr ( 'advertiser/site.get_one',$sn);
					 $c = dr ( 'advertiser/class.get_one',$se['sitetype'] );
					 echo "<a href=javascript:window.open('http://".$se['siteurl']."');>".$se['siteurl']."</a>  <span class='help-block' style='margin: 0;'>Alexa/PR：".$se["alexa"]."/". $se["pr"]." 类型：".$c['classname']." "."</span><br>";
				  }
			?></td>
            <td><span class="status">
              <?php 	 
			  		switch($s['status']){
						case 0:
							echo '<span class="notification error_bg">等待待审</span>';
							break;
						case 1:
							echo '<span class="notification error_bg">已被拒绝</span>';
							break;
						case 2;
							echo '<span class="notification info_bg">通过</span>';
							break;
					} 
				?>
            <?php echo $s["denyinfo"]?"<br>".$s["denyinfo"]:""?></span></td>
            <td applyid='<?php echo $s["id"]?>' class="uld_img"><a href="<?php echo url('admin/apply.edit?siteid='.$s['id'])?>"></a> <img src="<?php echo SRC_TPL_DIR?>/images/access_ok_gray.png" alt="" border="0" class="unlock" title="通过" /> <img src="<?php echo SRC_TPL_DIR?>/images/lock-icon.png" alt="" border="0" class="lock" title="拒绝"/> <img src="<?php echo SRC_TPL_DIR?>/images/trashcan_gray.png" alt="" border="0"  class="del" title="删除"/></td>
          </tr>
          <?php }?>
        </tbody>
      </table>
        <div><?php  echo $page->echoPage ();?></div>
    </div>
  </div>
</div>
 

<script language="JavaScript" type="text/javascript">
function uld(type,e) {
	var html = '';
   	var width = 400;
	if (type == 'del') {
		url = '<?php echo url('advertiser/apply.del')?>';
		title = '删除';
		text = '确定要删除吗？删除后无法恢复!';
	}
	if (type == 'lock') {
		url = '<?php echo url('advertiser/apply.lock')?>';
		html = '<span class="notification error_bg">拒绝投放</span>';
	    text = '<textarea  class="input_text span70 textarea_text" name="log_text" id="log_text">不能达到要求，申请不能通过！</textarea>';
		title = '拒绝投放';
	}
	if (type == 'unlock') {
		url = '<?php echo url('advertiser/apply.unlock')?>';
		html = '<span class="notification info_bg">通过申请</span>';
	    text = '<textarea  class="input_text span70 textarea_text" name="log_text" id="log_text"></textarea>';
		title = '通过申请';
	}
 		 
	box.confirm(text,width,title,function(bool){ 
		 var log_text = $("#log_text").text(); 
		 if(e) apply_id = $(e).parent().attr("applyid");
		 apply_id = apply_id.split(',');
		 if (bool) {
			 if (type == 'del') {
				$.each(apply_id, function(i,val){   
					$("#apply_"+val).parent().parent().css("backgroundColor", "#faa").hide('normal');
				});  
			 } 
			$.ajax(
			{
				dataType: 'html',
				url: url,
				type: 'post',
				data: 'id=' + apply_id + '&log_text=' + log_text,
				success: function() 
				{
					 $.each(apply_id, function(i,val){    
						 	$("#apply_"+val).parent().parent().find('.status').html(html);
					 });   
					$(".success").show();
				}
			});
		}
	});
}

$(".unlock,.lock,.del").click(function(){
	uld(this.className,this);
});
$("#select_id").click(function(){
 	 var a = $("#select_id").attr("checked");
	 if(a!='checked') a = false;
     $("input[name='id']").attr("checked",a);
});


$("#choose_sb").click(function(){
	var arr=[];
	var choose_type = $("#choose_type").val();
	if(!choose_type){
		box.alert('批量操作请选择一个操作对像',300);
		return ;
	}
 	var arrChk=$("input[name='id']:checked"); 
     
    for (var i=0;i<arrChk.length;i++)
    {
        var v = arrChk[i].value;
		arr.push(v);
		
    }
	apply_id = arr.join(",");
	uld(choose_type);
});

$(".stausbnt").on('click', function(option) { 
	   var val = $(this).attr('status');
	   location.href = '<?php echo url("advertiser/apply.get_list?&status=")?>' + val;
});

</script>